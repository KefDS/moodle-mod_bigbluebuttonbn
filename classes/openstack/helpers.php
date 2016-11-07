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
    public static function get_output_value($key, $stack) {
        $outputs = self::get_protected_value($stack, 'outputs');
        if($outputs) {
            foreach ($outputs as $output) {
                if($output->output_key == $key) return $outputs->output_value;
            }
        }
        return null;
    }

    public static function get_protected_value($value, $object) {
        $array = (array) $object;
        $prefix = chr(0) . '*' . chr(0);
        return $array[$prefix . $value];
    }


    // DataBase queries

    public static function get_upcomming_meetings_by_minutes($minutes) {
        global $DB;
        $creation_time = time() + ($minutes*60);
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE openingtime < ? AND openstack_stack_name IS NULL', [$creation_time]);
    }

    public static function get_meetings_by_state($state) {
        global $DB;
        return $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE bbb_server_status  = ?', [$state]);
    }

    public static function get_finished_meetings(){
        global $DB;
        return $DB->get_records_sql( 'SELECT * FROM  {bigbluebuttonbn} WHERE ( openingtime + ( bbb_meeting_duration*60 ) ) > ?', [time()]);
    }
}