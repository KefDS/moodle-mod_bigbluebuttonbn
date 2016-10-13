<?php
namespace mod_bigbluebuttonbn\task;
require_once(dirname(dirname(dirname(__FILE__))).'/lib.php');

class async_comunication extends \core\task\scheduled_task {
  public function get_name() {
    return get_string('OpenStack asynchronous Communication', 'mod_bigbluebuttonbn');
  }

  public function execute() {
    $closest_starting_meetings =  bigbluebuttonbn_get_upcomming_meetings(60);
    if($closest_starting_meetings) {
      foreach ($closest_starting_meetings as $meeting) {
        start_meeting($meeting);
      }
    }
  }

  private function start_meeting($meeting) {
    // TODO_BBB: Llamar a las clases hechas
    global $DB;
    $meeting->openstack_stack_name = "TheCronJobWasHere";
    $DB->update_record('bigbluebuttonbn', $meeting);
  }
}
