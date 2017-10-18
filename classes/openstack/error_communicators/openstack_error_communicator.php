<?php
/**
 * Error messages from OpenStack
 *
 * @package mod_bigbluebuttonbn
 * @author  Carlos Mata Guzman (carlos.mataguzman [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
namespace mod_bigbluebuttonbn\openstack;
defined('MOODLE_INTERNAL') || die();
require_once dirname(dirname(__FILE__)) . '/interfaces/error_communicator.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/locallib.php';


class openstack_error_communicator implements error_communicator
{
    public function build_message($input, $type){
        $msg_data = new \stdClass();
        $message = "";

        switch ($type){
            case 'connection_error':
                $msg_data->log_id = $input['log_id'];
                $msg_data->error_message = $input['error_message'];
                $msg_data->meetings_urls = $input['meetings_urls'];
                $msg_data->number_upcoming_conferences = $input['number_upcoming_conferences'];
                $message = get_string('openstack_error_conection_message', 'bigbluebuttonbn', $msg_data);
                break;

            case 'creation_request_error':
            case 'first_creation_request_error':
                $msg_data->log_id = $input['log_id'];
                $msg_data->error_message = $input['error_message'];
                $msg_data->meetingid = $input['meetingid'];
                $msg_data->openingtime = $input['openingtime'];
                $msg_data->meeting_url = $input['meeting_url'];
                $msg_data->courseid = $input['courseid'];
                $message = get_string('openstack_error_creation_request_message', 'bigbluebuttonbn', $msg_data);
                break;

            case 'creation_error':
                $msg_data->log_id = $input['log_id'];
                $msg_data->error_message = $input['error_message'];
                $msg_data->meetingid = $input['meetingid'];
                $msg_data->openingtime = $input['openingtime'];
                $msg_data->meeting_url = $input['meeting_url'];
                $msg_data->courseid = $input['courseid'];
                $msg_data->stack_name = $input['stack_name'];
                $message = get_string('openstack_error_creation_message', 'bigbluebuttonbn', $msg_data);
                break;

            case 'deletion_request_error':
            case 'first_deletion_request_error':
                $msg_data->log_id = $input['log_id'];
                $msg_data->error_message = $input['error_message'];
                $msg_data->meetingid = $input['meetingid'];
                $msg_data->stack_name = $input['stack_name'];
                $msg_data->meeting_url = $input['meeting_url'];
                $msg_data->courseid = $input['courseid'];
                $message = get_string('openstack_error_deletion_request_message', 'bigbluebuttonbn', $msg_data);
                break;
        }
        return $message;
    }


    public function communicate_error($message, $type){
        global $CFG;

        // Pre 2.9 does not have \core\message\message()
        if ($CFG->branch >= 29) {
            $data = new \core\message\message();
        } else {
            $data = new \stdClass();
        }

        switch ($type){
            case 'connection_error':
                $data = $this->communicate_connection_error($data, $message);
                break;
            case 'first_creation_request_error':
                $data = $this->communicate_creation_request_error($data, $message, true);
                break;
            case 'creation_request_error':
                $data = $this->communicate_creation_request_error($data, $message, false);
                break;
            case 'creation_error':
                $data = $this->communicate_creation_error($data, $message);
                break;
            case 'first_deletion_request_error':
                $data = $this->communicate_deletion_request_error($data,$message,true);
                break;
            case 'deletion_request_error':
                $data = $this->communicate_deletion_request_error($data,$message,false);
        }
        message_send($data);
    }

    private function communicate_connection_error($data, $message){

//        $userto = bigbluebuttonbn_get_openstack_notification_error_email();

        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_conection_error'; // This is the message name from messages.php
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22;
        $data->subject           = get_string('openstack_error_conection_subject', 'bigbluebuttonbn');
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;
    }

    private function communicate_creation_request_error($data, $message, $first_attempt){

//        $userto = bigbluebuttonbn_get_openstack_notification_error_email();

        // Choose subject message
        if($first_attempt){
            $subject = get_string('openstack_error_first_creation_request_subject', 'bigbluebuttonbn');
        }else{
            $subject = get_string('openstack_error_creation_request_subject', 'bigbluebuttonbn');
        }

        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_task_error'; // This is the message name from messages.php
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22;
        $data->subject           = $subject;
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;

    }

    private function communicate_creation_error($data, $message){

//        $userto = bigbluebuttonbn_get_openstack_notification_error_email();

        $subject = get_string('openstack_error_creation_subject', 'bigbluebuttonbn');

        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_task_error'; // This is the message name from messages.php
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22;
        $data->subject           = $subject;
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;

    }

    private function communicate_deletion_request_error($data, $message, $first_attempt){

//        $userto = bigbluebuttonbn_get_openstack_notification_error_email();

        // Choose subject message
        if($first_attempt){
            $subject = get_string('openstack_error_first_deletion_request_subject', 'bigbluebuttonbn');
        }else{
            $subject = get_string('openstack_error_deletion_request_subject', 'bigbluebuttonbn');
        }

        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_task_error'; // This is the message name from messages.php.
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22; //Cambiar este usuario por el o los correctos.
        $data->subject           = $subject;
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;
    }

}