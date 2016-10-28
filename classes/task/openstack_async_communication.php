<?php

namespace mod_bigbluebuttonbn\task;


//require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');

class openstack_async_communication extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('task_openstack_async_communication', 'mod_bigbluebuttonbn');
    }

    public function execute() {
        $closest_starting_meetings =  $this->bigbluebuttonbn_get_upcomming_meetings(60);
        if($closest_starting_meetings) {
            foreach ($closest_starting_meetings as $meeting) {
                $this->start_meeting($meeting);
            }
        }
    }

    private function start_meeting($meeting) {
        // TODO_BBB: Llamar a las clases hechas
        global $DB;
        $meeting->openstack_stack_name = "TheCronJobWasHere";
        $DB->update_record('bigbluebuttonbn', $meeting);
    }

    private function  bigbluebuttonbn_get_upcomming_meetings($minutes){
        global $DB;
        $creation_time = time()+($minutes*60);
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE openingtime < ? AND openstack_stack_name IS NULL', array($creation_time));
    }
}
