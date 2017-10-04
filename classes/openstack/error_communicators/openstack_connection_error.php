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


class openstack_connection_error implements error_communicator
{

    function build_message($input){
        $message = new \stdClass();
        $message->log_id = $input['log_id'];
        $message->error_message = $input['error_message'];
        return get_string('openstack_error_conection_message', 'bigbluebuttonbn', $message);
    }


    function communicate_error($message){
        global $CFG;

        $subject = get_string('openstack_error_conection_subject', 'bigbluebuttonbn');
        // Pre 2.9 does not have \core\message\message()
        if ($CFG->branch >= 29) {
            $eventdata = new \core\message\message();
        } else {
            $eventdata = new \stdClass();
        }

        $eventdata->component         = 'mod_bigbluebuttonbn';
        $eventdata->name              = 'openstack_conection_error'; // This is the message name from messages.php.
        $eventdata->userfrom          = \core_user::get_noreply_user();
        $eventdata->userto            = 22; //Cambiar este usuario por el o los correctos.
        $eventdata->subject           = $subject;
        $eventdata->fullmessage       = $message;
        $eventdata->fullmessageformat = FORMAT_HTML;
        $eventdata->fullmessagehtml   = $message;
        $eventdata->smallmessage      = '';
        $eventdata->notification      = 1; // This is only set to 0 for personal messages between users.

        message_send($eventdata);
    }
}