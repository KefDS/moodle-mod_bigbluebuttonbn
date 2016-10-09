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

    function __construct(OpenStack $openstack_connection, $region) {
        $this->openstack_connection = $openstack_connection;
        $this->region = $region;
    }

    function new_bbb_host($template_url) {
        try {
            $stack_output = $this->get_stack_service()->create_stack($this->default_heat_options($template_url));
            // TODO_BBB Save output in database
        }
        catch (Exception $e) {
            // TODO_BBB: what to do with $e
        }
    }

    function  delete_bbb_host($meeting_id) {
        // TODO_BBB: get stack name from $meeting_id
        $stack_name = null;
        try {
            $this->get_stack_service()->delete_stack($stack_name);
        }
        catch (Exception $e) {
            // TODO_BBB: what to do with $e
        }
    }

    private function default_heat_options($template_url) {
        // TODO_BBB: increment counter index name
        // TODO_BBB: get key name from admin form
        return ['name' => '',
            'templateUrl' => $template_url,
            'parameters' => ['key_name' => '']
        ];
    }

    private function get_stack_service() {
        try {
            $service = $this->openstack_connection->orchestrationService('heat', $this->region);
            return new mod_bigbluebuttonbn_openstack_stack($service);
        }
        catch (Exception $e) {
            // TODO_BBB: what to do with $e
        }
    }
}