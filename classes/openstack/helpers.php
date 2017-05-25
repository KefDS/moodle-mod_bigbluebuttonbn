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
    public static function get_upcomming_meetings_by_minutes($minutes) {
        global $DB;
        $interval = time() + ($minutes*60);
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn_openstack} WHERE openingtime < ? AND bbb_server_status IS NULL', [$interval]);
    }
    public static function get_meetings_by_state($state) {
        global $DB;
        return $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE bbb_server_status  = ?', [$state]);
    }
    public static function get_finished_meetings(){
        global $DB;
        return $DB->get_records_sql('SELECT * FROM  {bigbluebuttonbn} WHERE ( openingtime + ( bbb_meeting_duration*60 ) ) < ? AND bbb_server_status <> ?', [time(), "Deleted"]);
    }

    public static function get_bbb_meeting($meetingid){
        global $DB;
        return $DB->get_record('bigbluebuttonbn', array('meetingid'=>$meetingid));
    }
}
