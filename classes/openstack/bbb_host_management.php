<?php
/**
 * Big Blue Button servers management through OpenStack.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
namespace mod_bigbluebuttonbn\openstack;

class bbb_host_management {
    const DEFAULT_TIMEOUT_MINUTES = 14;

    private $orchestration_service;

    function __construct($orchestration_service) {
        $this->orchestration_service = $orchestration_service;
    }

    function create_bbb_host($meeting_id, $stack_parameters) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $stack_parameters['name'] = $stack_name;
        $stack_parameters['timeoutMins'] = self::DEFAULT_TIMEOUT_MINUTES;

        $this->orchestration_service->createStack($stack_parameters);
        return $stack_name;
    }

    function get_stack_outputs($meeting_id) {
        // TODO_BBB: Hash implementation
        $stack = $this->orchestration_service->getStack($this->get_bbb_host_name($meeting_id));
        if($this->get_bbb_server_status($stack) == "CREATE_COMPLETE") {
            return [
                'url' => $this->get_bbb_server_ip($stack),
                'shared_key' => $this->get_bbb_server_shared_key($stack)
            ];
        }
        // Server not ready
        elseif($this->get_bbb_server_status($stack) == "CREATE_IN_PROGRESS") {
            return null;
        }
        // Other state is an error (for now)
        throw new \Exception("Error in BBB server creation. Stack status: " . $this->get_bbb_server_status($stack));
    }

    function  delete_bbb_host($meeting_id) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $this->orchestration_service->getStack($stack_name)->delete();
    }


    // Auxiliary functions

    private function get_bbb_host_name($meeting_id) {
        return "moodle_bbb_host_of_meeting_" . $meeting_id;
    }

    private function get_bbb_server_status($stack) {
        return helpers::get_protected_value('status', $stack);
    }

    private function get_bbb_server_ip($stack) {
        return helpers::get_output_value('bbb_url', $stack);
    }

    private function get_bbb_server_shared_key($stack) {
        return helpers::get_output_value('bbb_shared_key', $stack);
    }
}