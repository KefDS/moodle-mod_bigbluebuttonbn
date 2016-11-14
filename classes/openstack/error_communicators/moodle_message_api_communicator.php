<?php
/**
 * Error messages from OpenStack
 *
 * @package mod_bigbluebuttonbn
 * @author Fabian Rodriguez (fabian.rodriguezobando [at] ucr.ac.cr)
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
namespace mod_bigbluebuttonbn\openstack;

require_once dirname(dirname(__FILE__)) . '/interfaces/error_communicator.php';

class moodle_message_api_communicator implements error_communicator
{
    function communicate_error($meeting)
    {
        global $DB;
        $context = context_course::instance($meeting->course);
        $course_name = $DB->get_field_select('course', 'fullname', "id = '$meeting->course'");
        $teacher_role_id = $DB->get_field_select('role', 'id', "shortname = 'editingteacher'");
        $user_from = $DB->get_field_select('user', 'id', "username = 'soporte.metics'");
        $user_to = $DB->get_field_select('role_assignments', 'userid', "contextid = '$context->id' AND roleid = '$teacher_role_id'");
        $message_id = message_send($this->create_message($meeting, intval($user_from), $user_to, $course_name));

    }

    /**
     * Creates a message to be send to a teacher.
     *
     * @author Fabian Rodriguez (fabian.rodriguezobando [at] ucr.ac.cr)
     * @param $meeting : the meeting that fail creating
     * @param $user_from : user from which the message is going to be send
     * @param $user_to : teacher to send the message
     * @param $course_name : name of the moodle course
     * @return An object "message" with the information about the error of the meeting
     */
    private function create_message($meeting, $user_from, $user_to, $course_name)
    {
        $message = new stdClass();
        $message->component = 'moodle';
        $message->userfrom = $user_from;
        $message->userto = $user_to;
        $message->subject = 'Error al reservar la sala de video conferencia';
        $message->fullmessage = "No se pudo reservar la sala de conferencia, contacte al administrador y brindele la sigueiente información:\n - Curso: $course_name \n - Nombre conferencia: $meeting->name \n - ID Conferencia: $meeting->meetingid \n - Error: $meeting->bbb_server_status";
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = "<p>No se pudo reservar la sala de conferencia, contacte al administrador y brindele la siguiente información:</p><ul><li><b>Curso: </b>$course_name</li><li><b>Nombre conferencia: </b>$meeting->name</li><li><b>ID Conferencia: </b>$meeting->meetingid</li><li><b>Error: </b>$meeting->bbb_server_status</li></ul>";
        $message->smallmessage = 'No se pudo reservar la sala';
        return $message;
    }
}
