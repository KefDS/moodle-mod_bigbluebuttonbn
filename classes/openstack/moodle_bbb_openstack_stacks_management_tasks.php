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
require_once dirname(__FILE__) . '/interfaces/error_communicator.php';
require_once dirname(__FILE__) . '/helpers.php';

use OpenCloud\OpenStack;

class moodle_bbb_openstack_stacks_management_tasks {
    // CONSTANTS
    const UPCOMING_MEETINGS_MINUTES = 30;

    private $admin_exception_handler;
    private $user_error_communicator;
    private $orchestration_service;

    function __construct(exception_handler $admin_exception_handler, error_communicator $user_error_communicator) {
        $this->admin_exception_handler = $admin_exception_handler;
        $this->user_error_communicator = $user_error_communicator;
    }

    public function do_tasks() {
        // Error with network, openstack server or openstack configuration in moodle
        try {
            $this->orchestration_service = $this->get_openstack_orchestration_service();
        }
        catch (\Exception $exception) {
            $openstack_services_error = "Error: Check your network, openstack service or configuration in moodle. The upcoming meetings will be canceled.\n";
            $this->admin_exception_handler->handle_exception(new \Exception($openstack_services_error . $exception->getMessage()));
            $this->communicate_tasks_error_to_users();
            //Log event
            $event_record =(['log_level'=>'ALERT', 'component'=>'OPENSTACK_CONNECTION', 'event'=>'CANT_ACCESS_OPENSTACK', 'event_details'=>$openstack_services_error]);
            helpers::bigbluebuttonbn_add_openstack_event($event_record);
            return;
        }

        //$this->get_bbb_host_info_for_upcoming_meetings();
        //$this->delete_bbb_host_for_finished_meetings();
        $this->create_bbb_host_for_upcoming_meetings();

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
    private function communicate_tasks_error_to_users() {
        global $DB;
        $upcomming_meetings = $this->get_upcoming_meetings();
        // TODO_BBB: Situación más compleja (podría dar chance a otra ronda de cron job antes de ponerla como fallida)
        $waiting_host_meetings = $this->get_in_progress_meetings();
        $involved_meetings = array_merge($upcomming_meetings, $waiting_host_meetings);

        foreach ($involved_meetings as $meeting) {
            $this->user_error_communicator->communicate_error($meeting);

            $meeting->bbb_server_status = "Failed";
            $DB->update_record('bigbluebuttonbn', $meeting);
        }
    }

    private function create_bbb_host_for_upcoming_meetings() {
        $upcoming_meetings = $this->get_upcoming_meetings();
        foreach ($upcoming_meetings as $meeting) {
            $this->create_bbb_host_for_upcoming_meeting($meeting);
        }
    }
    private function create_bbb_host_for_upcoming_meeting($meeting) {
        try {
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->create_meeting_host();
            //Log event
            $event_record =(['log_level'=>'INFO', 'component'=>'OPENSTACK', 'event'=>'CREATION_STARTED', 'event_details'=>'The BBB server creation has started']);
            helpers::bigbluebuttonbn_add_openstack_event($event_record);
        }
        catch (\Exception $exception) {
            $this->admin_exception_handler->handle_exception($exception);
            $this->user_error_communicator->communicate_error($meeting);
            //Log event
            $event_record =(['log_level'=>'ERROR', 'component'=>'OPENSTACK', 'event'=>'CREATION_FAILED', 'event_details'=>$exception->getMessage()]);
            helpers::bigbluebuttonbn_add_openstack_event($event_record);
        }
    }

    private function get_bbb_host_info_for_upcoming_meetings() {
        $upcoming_meetings = $this->get_in_progress_meetings();

        foreach ($upcoming_meetings as $meeting) {
            $this->get_bbb_host_info_for_upcoming_meeting($meeting);
        }
    }
    private function get_bbb_host_info_for_upcoming_meeting($meeting) {
        try {
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->get_meeting_host_info();
        }
        catch(\Exception $exception) {
            $this->admin_exception_handler->handle_exception($exception);
            $this->user_error_communicator->communicate_error($meeting);
        }
    }

    private function delete_bbb_host_for_finished_meetings() {
        $finished_meetings = $this->get_finished_meetings();
        foreach ($finished_meetings as $meeting) {
            $this->delete_bbb_host_for_finished_meeting($meeting);
        }
    }
    private function delete_bbb_host_for_finished_meeting($meeting) {
        try {
            $meeting_setup = new meeting_setup($meeting, $this->orchestration_service);
            $meeting_setup->delete_meeting_host();
        }
        catch (\Exception $exception) {
            $this->admin_exception_handler->handle_exception($exception);
            //Agregar a la bitácora
        }
    }

    private function get_upcoming_meetings() {
        return helpers::get_upcomming_meetings_by_minutes(self::UPCOMING_MEETINGS_MINUTES);
    }
    private function get_in_progress_meetings() {
        return helpers::get_meetings_by_state("In Progress");
    }
    private function get_finished_meetings() {
        return helpers::get_finished_meetings();
    }
}