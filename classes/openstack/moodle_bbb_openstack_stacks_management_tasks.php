<?php
/**
 * Moodle tasks about Big Blue Button OpenStack meetings.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

require_once dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';
require_once dirname(__FILE__) . '/interfaces/exception_handler.php';
# Message API
require_once dirname(__FILE__) . '/interfaces/error_communicator.php';
require_once dirname(__FILE__) . '/helpers.php';

use OpenCloud\OpenStack;

class moodle_bbb_openstack_stacks_management_tasks {
    // CONSTANTS
    const UPCOMING_MEETINGS_MINUTES = 30;
    const AFFECTED_METTINGS_INTERVAL_MINUTES = 60;

    private $admin_exception_handler;
    # Message API
    private $user_error_communicator;
    # OpenStack connection
    private $orchestration_service;
    # Resiliency enabled
    private $resiliency_enabled;
    private $max_meeting_creation_retries;
    private $max_meeting_deletion_retries;


    function __construct(exception_handler $admin_exception_handler, error_communicator $user_error_communicator) {
        $this->admin_exception_handler = $admin_exception_handler;
        # Message API
        $this->user_error_communicator = $user_error_communicator;
        $this->resiliency_enabled = bigbluebuttonbn_get_cfg_resiliency_module_enabled();
        $this->max_meeting_creation_retries = bigbluebuttonbn_get_cfg_creation_retries_number();
        $this->max_meeting_deletion_retries = bigbluebuttonbn_get_cfg_deletion_retries_number();
    }

    public function do_tasks() {

        // Declare meetings failed due to timeout
        try {
            $this->orchestration_service = $this->get_openstack_orchestration_service();
        }
        catch (\Exception $exception) {
            $this->admin_exception_handler->handle_exception(new \Exception($exception->getMessage()));
            //Log event
            $event_record =(object)(['log_level'=>'ALERT', 'component'=>'OPENSTACK_CONNECTION', 'event'=>'CANT_ACCESS_OPENSTACK', 'event_details'=>$exception->getMessage()]);
            $log_id = helpers::bigbluebuttonbn_add_openstack_event($event_record);
            //Communicate error
            $this->communicate_error($log_id,$exception->getMessage(), 'connection_error');
            return;
        }

        $this->get_bbb_host_info_for_upcoming_meetings();
        $this->create_bbb_host_for_upcoming_meetings();
        $this->delete_bbb_host_for_finished_meetings();
    }

    private function get_openstack_orchestration_service() {
        $openstack_client = new OpenStack(bigbluebuttonbn_get_cfg_heat_url(), [
            'username' => bigbluebuttonbn_get_cfg_openstack_username(),
            'password' => bigbluebuttonbn_get_cfg_openstack_password(),
            'tenantId' => bigbluebuttonbn_get_cfg_openstack_tenant_id()
        ]);
        $openstack_client->authenticate();
        return $openstack_client->orchestrationService('heat', bigbluebuttonbn_get_cfg_heat_region());
    }


    private function communicate_error($log_id, $error_message, $type, $meeting=null){
        $msg_data = [];
        $msg_data['log_id'] = $log_id;
        $msg_data['error_message'] = $error_message;

        switch ($type){
            case 'connection_error':
                $msg_data['meetings_urls'] = $this->get_affected_meeting_url_string();
                $msg_data['number_upcoming_conferences']= count($this->get_involved_meetings(time()*24*60));
                break;
            case 'creation_request_error':
            case 'first_creation_request_error':
                $msg_data['meetingid']= $meeting->meetingid;
                $msg_data['openingtime'] = date("Y-m-d h:i", $meeting->openingtime);
                $msg_data['meeting_url']= $this->construct_meeting_url($meeting);
                $msg_data['courseid']= $meeting->courseid;
                break;
            case 'creation_error':
                $msg_data['meetingid']= $meeting->meetingid;
                $msg_data['openingtime'] = date("Y-m-d h:i", $meeting->openingtime);
                $msg_data['meeting_url']= $this->construct_meeting_url($meeting);
                $msg_data['courseid']= $meeting->courseid;
                $msg_data['stack_name'] = $meeting->stack_name;
                break;
            case 'deletion_request_error':
            case 'first_deletion_request_error':
                $msg_data['meetingid']= $meeting->meetingid;
                $msg_data['stack_name'] = $meeting->stack_name;
                $msg_data['meeting_url']= $this->construct_meeting_url($meeting);
                $msg_data['courseid']= $meeting->courseid;
                break;
        }
        $message = $this->user_error_communicator->build_message($msg_data, $type);
        $this->user_error_communicator->communicate_error($message, $type);
        return;
    }

    //Creation
    private function create_bbb_host_for_upcoming_meetings() {
        $upcoming_meetings = $this->get_upcoming_meetings(self::UPCOMING_MEETINGS_MINUTES);
        foreach ($upcoming_meetings as $meeting) {
            $this->create_bbb_host_for_upcoming_meeting($meeting);
        }
    }
    private function create_bbb_host_for_upcoming_meeting($meeting) {
        $this-> increase_meeting_creation_attempts($meeting);
        $meeting->creation_attempts = $this->get_bbb_openstack_field_by_meetingid($meeting->meetingid, 'creation_attempts');

        try {
            //throw new \Exception('Always throw this error');
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->create_meeting_host();
            //Log event
            $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'INFO', 'component'=>'OPENSTACK', 'event'=>'CREATION_STARTED', 'event_details'=>'The BBB server creation has started', 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
            helpers::bigbluebuttonbn_add_openstack_event($event_record);
        }
        catch (\Exception $exception) {
            $type = 'creation_request_error';
            $send_message = true;
            if(!$this->resiliency_enabled or $meeting->creation_attempts > $this->max_meeting_creation_retries){
                $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'ERROR', 'component'=>'OPENSTACK', 'event'=>'CREATION_REQUEST_FAILED', 'event_details'=>$exception->getMessage(), 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
                //Declared failed server
                $this->declared_failed_server($meeting, 'Creation request failed');
                //Handle exception
                $this->admin_exception_handler->handle_exception($exception);
            }else{
                $send_message = false;
                $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'WARNING', 'component'=>'OPENSTACK', 'event'=>'CREATION_REQUEST_FAILED', 'event_details'=>$exception->getMessage(), 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
                $meeting->creation_attempts == 1 ? $type = 'first_creation_request_error' : $type = 'creation_request_error';
            }
            //Log error
            $log_id = helpers::bigbluebuttonbn_add_openstack_event($event_record);
            //Communicate error
            if($send_message or $type == 'first_creation_request_error'){
                $this->communicate_error($log_id,$exception->getMessage(), $type, $meeting);
            }
        }

    }

    //Info status
    private function get_bbb_host_info_for_upcoming_meetings() {
        $upcoming_meetings = $this->get_in_progress_meetings();
        foreach ($upcoming_meetings as $meeting) {
            $this->get_bbb_host_info_for_upcoming_meeting($meeting);
        }
    }
    private function get_bbb_host_info_for_upcoming_meeting($meeting) {
        try {
            //throw new \Exception('Always throw this error');
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->get_meeting_host_info();
            if($meeting->openingtime > time()){
                throw new \Exception('Meeting was cancelled because the openingtime had already passed.');
            }
            if($meeting->bbb_server_status == 'Ready'){
                //Log event
                $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'INFO', 'component'=>'OPENSTACK', 'event'=>'BBB_SERVER_READY', 'event_details'=>'The BBB server is ready to host the meeting.', 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
                helpers::bigbluebuttonbn_add_openstack_event($event_record);
            }
        }
        catch(\Exception $exception) {
            $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'ERROR', 'component'=>'OPENSTACK', 'event'=>'CREATION_FAILED', 'event_details'=>$exception->getMessage(), 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
            $log_id= helpers::bigbluebuttonbn_add_openstack_event($event_record);
            //Declare failed server
            $this->declared_failed_server($meeting, 'Creation failed');
            //Communicate error
            $this->communicate_error($log_id,$exception->getMessage(), 'creation_error', $meeting);
            //Handle exception
            $this->admin_exception_handler->handle_exception($exception);
        }
    }

    //Deletion
    private function delete_bbb_host_for_finished_meetings() {
        $finished_meetings = $this->get_finished_meetings();
        foreach ($finished_meetings as $meeting) {
            $this->delete_bbb_host_for_finished_meeting($meeting);
        }
    }
    private function delete_bbb_host_for_finished_meeting($meeting) {
        $this -> increase_meeting_deletion_attempts($meeting);
        $meeting->deletion_attempts = $this->get_bbb_openstack_field_by_meetingid($meeting->meetingid, 'deletion_attempts');

        try {
            //throw new \Exception('Always throw this error');
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->delete_meeting_host();
            //Log event
            $event_record =(object)(['meetingid'=>$meeting->meetingid, 'stack_name'=>$meeting->stack_name, 'log_level'=>'INFO', 'component'=>'OPENSTACK', 'event'=>'DELETION_STARTED', 'event_details'=>'The deletion of the BBB server was accepted']);
            helpers::bigbluebuttonbn_add_openstack_event($event_record);
        }
        catch (\Exception $exception) {
            $type = 'deletion_request_error';
            $send_message = true;
            if (!$this->resiliency_enabled or $meeting->deletion_attempts > $this->max_meeting_deletion_retries) {
                $event_record = (object)(['meetingid' => $meeting->meetingid, 'stack_name' => $meeting->stack_name, 'log_level' => 'ERROR', 'component' => 'OPENSTACK', 'event' => 'DELETION_START_FAILED', 'event_details' => $exception->getMessage(), 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
                //Declare failed server
                $this->declared_failed_server($meeting, 'Deletion started failed');
                //Handle exception
                $this->admin_exception_handler->handle_exception($exception);
            } else {
                $send_message = false;
                $event_record = (object)(['meetingid' => $meeting->meetingid, 'stack_name' => $meeting->stack_name, 'log_level' => 'WARNING', 'component' => 'OPENSTACK', 'event' => 'DELETION_START_FAILED', 'event_details' => $exception->getMessage(), 'conference_name'=>$meeting->conference_name, 'user_name'=>$meeting->user_name, 'course_name'=>$meeting->course_name]);
                $meeting->deletion_attempts == 1 ? $type = 'first_deletion_request_error' : $type = 'deletion_request_error';
            }
            //Log error
            $log_id = helpers::bigbluebuttonbn_add_openstack_event($event_record);
            //Communicate error
            if($send_message or $type == 'first_deletion_request_error'){
                $this->communicate_error($log_id,$exception->getMessage(), $type, $meeting);
            }
        }
    }


    // Helpers calls
    private function get_upcoming_meetings($minutes) {
        return helpers::get_upcomming_meetings_by_minutes($minutes);
    }

    private function get_in_progress_meetings() {
        return helpers::get_meetings_by_state("Create In Progress");
    }

    private function get_finished_meetings(){
        return helpers::get_finished_meetings();
    }

    private function increase_meeting_creation_attempts($meeting){
        return helpers::increase_meeting_creation_attempts($meeting);
    }

    private function increase_meeting_deletion_attempts($meeting){
        return helpers::increase_meeting_deletion_attempts($meeting);
    }

    private function get_bbb_openstack_field_by_meetingid($meetingid, $field){
        return helpers::get_bbb_openstack_field_by_meetingid($meetingid, $field);
    }

    private function get_affected_meeting_url_string(){
        $involved_meetings_url = '<ul>';
        $involved_meetings = $this->get_involved_meetings(self::AFFECTED_METTINGS_INTERVAL_MINUTES);
        foreach ($involved_meetings as $meeting) {
            $meeting_url = $this->construct_meeting_url($meeting);
            $involved_meetings_url .= '<li><a href="'.$meeting_url.'">'.'Meeting ID: '.$meeting->meetingid.'</a></li>';
        }
        $involved_meetings_url .= '</ul>';
        return $involved_meetings_url;
    }

    private function get_involved_meetings($minutes){
        return array_merge($this->get_upcoming_meetings($minutes), $this->get_in_progress_meetings(), $this->get_finished_meetings());
    }

    private function construct_meeting_url($meeting){
        return helpers::construct_meeting_url($meeting);
    }

    private function declared_failed_server($meeting, $status){
        return helpers::set_server_status($meeting, $status);
    }
}