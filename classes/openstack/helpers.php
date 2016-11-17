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
        $creation_time = time() + ($minutes*60);
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE openingtime < ? AND bbb_server_status IS NULL', [$creation_time]);
    }
    public static function get_meetings_by_state($state) {
        global $DB;
        return $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE bbb_server_status  = ?', [$state]);
    }
    public static function get_finished_meetings(){
        global $DB;
        return $DB->get_records_sql('SELECT * FROM  {bigbluebuttonbn} WHERE ( openingtime + ( bbb_meeting_duration*60 ) ) < ? AND bbb_server_status <> ?', [time(), "Deleted"]);
    }
}
