<?php
/**
 * Big Blue Button servers management through OpenStack.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
require_once('../../vendor/autoload.php');
use OpenCloud\OpenStack;

class mod_bigbluebuttonbn_openstack_bbb {
    private $openstack_connection;
    private $region;
    private $error_handler;

    function __construct(OpenStack $openstack_connection, $region) {
        $this->openstack_connection = $openstack_connection;
        $this->region = $region;
        $this->error_handler = new mod_bigbluebuttonbn_openstack_error_communication();
    }

    function new_bbb_host($meeting_id, $stack_parameters) {
        // Set stack name
        $stack_parameters['name'] = $this->get_default_name($meeting_id);
        try {
            $service = $this->get_orchestration_service();
            $stack_output = $service->stack($stack_parameters);

            // TODO_BBB: Return stack name, IP and ShareKey
            return [];
        }
        catch (Exception $e) {
           $this->error_handler->communicate_error($e);
        }
    }

    function  delete_bbb_host($meeting_id) {
        $stack_name = $this->get_default_name($meeting_id);
        try {
            $service = $this->get_orchestration_service();
            $stack = $service->getStack($stack_name);
            $stack->delete();
        }
        catch (Exception $e) {
            $this->error_handler->communicate_error($e);
        }
    }

    private function get_default_name($meeting_id) {
        return "moodle_bbb_host_meeting_" . $meeting_id;
    }

    private function get_orchestration_service() {
        return $this->openstack_connection->orchestrationService('heat', $this->region);
    }
}