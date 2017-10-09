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


class openstack_error_communicator implements error_communicator
{

    public function build_message($input){
        $message = new \stdClass();
        $message->log_id = $input['log_id'];
        $message->error_message = $input['error_message'];
        return get_string('openstack_error_conection_message', 'bigbluebuttonbn', $message);
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
                $data = $this->communicate_conecction_error($data, $message);
                break;
            case 'creation_error':
                $data = $this->communicate_creation_error($data, $message);
                break;
        }
        message_send($data);
    }

    private function communicate_conecction_error($data, $message){

        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_conection_error'; // This is the message name from messages.php.
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22; //Cambiar este usuario por el o los correctos.
        $data->subject           = get_string('openstack_error_conection_subject', 'bigbluebuttonbn');
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;
    }

    private function communicate_creation_error($data, $message){
        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_creation_error'; // This is the message name from messages.php.
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22; //Cambiar este usuario por el o los correctos.
        $data->subject           = get_string('openstack_error_conection_subject', 'bigbluebuttonbn');
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;

    }

    private function communicate_deletion_error($data, $message){
        $data->component         = 'mod_bigbluebuttonbn';
        $data->name              = 'openstack_conection_error'; // This is the message name from messages.php.
        $data->userfrom          = \core_user::get_noreply_user();
        $data->userto            = 22; //Cambiar este usuario por el o los correctos.
        $data->subject           = get_string('openstack_error_conection_subject', 'bigbluebuttonbn');;
        $data->fullmessage       = $message;
        $data->fullmessageformat = FORMAT_HTML;
        $data->fullmessagehtml   = $message;
        $data->smallmessage      = '';
        $data->notification      = 1; // This is only set to 0 for personal messages between users.

        return $data;
    }

}