<?php
/**
 * Big Blue Button servers management through OpenStack.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
namespace mod_bigbluebuttonbn\openstack;

require_once('../../vendor/autoload.php');
use OpenCloud\OpenStack;

class bbb_servers_management {
    private $openstack_connection;
    private $region;

    function __construct(OpenStack $openstack_connection, $region) {
        $this->openstack_connection = $openstack_connection;
        $this->region = $region;
    }

    function create_bbb_host($meeting_id, $stack_parameters) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $stack_parameters['name'] = $stack_name;

        $service = $this->get_orchestration_service();
        $service->createStack($stack_parameters);
        return $stack_name;
    }

    function get_stack_outputs($meeting_id) {
        $stack = $this->get_orchestration_service()->getStack($this->get_bbb_host_name($meeting_id));
        if($this->get_bbb_server_status($stack) == "CREATE_COMPLETE") {
            return [
                'url' => $this->get_bbb_server_ip($stack),
                'shared_key' => $this->get_bbb_server_shared_key($stack)
            ];
        }
        return null;
    }

    function  delete_bbb_host($meeting_id) {
        $stack_name = $this->get_bbb_host_name($meeting_id);
        $service = $this->get_orchestration_service();
        $stack = $service->getStack($stack_name);
        $stack->delete();
    }


    // Auxiliary functions

    private function get_bbb_host_name($meeting_id) {
        return "moodle_bbb_host_meeting_" . $meeting_id;
    }

    private function get_orchestration_service() {
        return $this->openstack_connection->orchestrationService('heat', $this->region);
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
