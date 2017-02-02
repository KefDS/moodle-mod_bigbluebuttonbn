<?php
/**
 * Big Blue Button servers management through OpenStack.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

require_once dirname(__FILE__) . "/bbb_stack.php";

class bbb_host_management {
    const DEFAULT_TIMEOUT_MINUTES = 14;

    private $orchestration_service;

    function __construct($orchestration_service) {
        $this->orchestration_service = $orchestration_service;
    }

    function create_bbb_host($meeting_id, $stack_parameters, $templateURL) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $stack_parameters['name'] = $stack_name;
        $stack_parameters ['templateUrl']= $templateURL;
        $stack_parameters['timeoutMins'] = self::DEFAULT_TIMEOUT_MINUTES;

        $this->orchestration_service->createStack($stack_parameters);
        return $stack_name;
    }

    function get_stack_outputs($meeting_id) {
        $stack = $this->orchestration_service->getStack($this->get_bbb_host_name($meeting_id));
        $bbb_stack = new bbb_stack($stack);
        return $bbb_stack->get_bbb_host_data();
    }

    function  delete_bbb_host($meeting_id) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $this->orchestration_service->getStack($stack_name)->delete();
    }


    private function get_bbb_host_name($meeting_id) {
        return "bbb_meeting_" . $meeting_id;
    }
}
