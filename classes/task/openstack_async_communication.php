<?php

namespace mod_bigbluebuttonbn\task;


//require_once(dirname(dirname(dirname(__FILE__))).'/locallib.php');

use mod_bigbluebuttonbn\openstack\meeting_setup;

class openstack_async_communication extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('task_openstack_async_communication', 'mod_bigbluebuttonbn');
    }

    public function execute() {
        $closest_starting_meetings =  $this->bigbluebuttonbn_get_upcomming_meetings(60);
        if($closest_starting_meetings) {
            foreach ($closest_starting_meetings as $meeting) {
                $this->setup_meeting($meeting);
            }
        }
    }

    private function setup_meeting($meeting) {
        // TODO_BBB: Llamar a las clases hechas
        global $DB;
        echo "im here!";
        $meeting->openstack_stack_name = "otracosa";
        $DB->update_record('bigbluebuttonbn', $meeting);
        $meeting_setup = new meeting_setup($meeting);
        echo "línea 31 task!";
        $meeting_setup->create_meeting_host();
    }

    private function  bigbluebuttonbn_get_upcomming_meetings($minutes){
        global $DB;
        $creation_time = time()+($minutes*60);
        return $meetings = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn} WHERE openingtime < ? AND openstack_stack_name IS NULL', array($creation_time));
    }
}
