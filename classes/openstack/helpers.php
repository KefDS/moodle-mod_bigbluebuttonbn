<?php
/**
 * Auxiliary functions.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

class helpers {

    //ToDo: Hacer un recolector de basura para conferncias que estén en null y ya deban de haber pasado

    //ToDo:Incluir deletion time como parámetro para obtener próximas videoconferencias
    public static function get_upcomming_meetings_by_minutes($minutes) {
        global $DB;
        $interval = time() + ($minutes*60);
        echo $interval;
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn_openstack} WHERE openingtime < ? AND bbb_server_status = ?', [$interval, 'Wating for creation']);
    }
    public static function get_meetings_by_state($state) {
        global $DB;
        return $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn_openstack} WHERE bbb_server_status  = ?', [$state]);
    }

    public static function get_finished_meetings(){
        global $DB;
        return $DB->get_records_sql('SELECT * FROM  {bigbluebuttonbn_openstack} WHERE ( openingtime + ( meeting_duration*60 ) ) < ? AND (bbb_server_status <> ?) AND (bbb_server_status <> ?)' , [time(), "Deletion started", "Deletion started failed"]);
    }

    public static function get_bbb_openstack_field_by_meetingid($meetingid,$field){
        global $DB;
        $meetingid_record = $DB->get_record('bigbluebuttonbn_openstack', array('meetingid'=>$meetingid));
        return $meetingid_record->$field;
    }

    public static function bigbluebuttonbn_add_openstack_event($event_record)
    {
        global $DB;
        $event_record->event_time = time();
        $event_record->event_details = date('m/d/Y h:i:s a', time()).' '.$event_record->event_details;
        return $DB->insert_record('bigbluebuttonbn_os_logs', $event_record);
    }

    public static function increase_meeting_creation_retries($meeting){
        global $DB;
        $meeting_record = $DB->get_record('bigbluebuttonbn_openstack', array('id'=>$meeting->id));
        $meeting_record->creation_retries += 1;
        return $DB->update_record('bigbluebuttonbn_openstack', $meeting_record);
    }

    public static function increase_meeting_deletion_retries($meeting){
        global $DB;
        $meeting_record = $DB->get_record('bigbluebuttonbn_openstack', array('id'=>$meeting->id));
        $meeting_record->deletion_retries += 1;
        return $DB->update_record('bigbluebuttonbn_openstack', $meeting_record);
    }
}
