<?php
/**
 * Big Blue Button meeting setup.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

require_once dirname(__FILE__) . '/bbb_host_management.php';

class meeting_setup {
    private $meeting;
    private $bbb_servers_management;

    function __construct($meeting, $orchestration_service) {
        $this->meeting = $meeting;
        $this->bbb_servers_management = new bbb_host_management($orchestration_service);
    }

    function create_meeting_host() {
        global $DB;
        try {
            $stack_params_url = bigbluebuttonbn_get_cfg_json_stack_parameters_url();
            $stack_params = json_decode(file_get_contents($stack_params_url), true);
            $templateURL = bigbluebuttonbn_get_cfg_yaml_template_url();
            $bbb_host_name = $this->bbb_servers_management->create_bbb_host($this->meeting->id, $stack_params, $templateURL);
            $this->meeting->stack_name = $bbb_host_name;
            $this->meeting->bbb_server_status = 'Create In Progress';
            $DB->update_record('bigbluebuttonbn_openstack', $this->meeting);
        }
        catch (\Exception $exception) {
            $exception_message = "The bbb host cannot be created. Stack parameters: " .
                var_export($stack_params, true) . ".\n" .
                $exception->getMessage();
            throw new \Exception($exception_message);
        }
    }

    function get_meeting_host_info() {
        global $DB;
        try {
            $bbb_host_information = $this->bbb_servers_management->get_stack_outputs($this->meeting->id);
            if($bbb_host_information) {
                $this->meeting->bbb_server_url = $bbb_host_information['bbb_url'];
                $this->meeting->bbb_server_shared_secret = $bbb_host_information['bbb_shared_key'];
                $this->meeting->bbb_server_status = 'Ready';
                $DB->update_record('bigbluebuttonbn_openstack', $this->meeting);
            }
        }
        catch (\Exception $exception) {
            throw $exception;
        }
    }

    function delete_meeting_host() {
        global $DB;
        try {
            $this->bbb_servers_management->delete_bbb_host($this->meeting->id);
            $this->meeting->bbb_server_status = 'Deletion started';
            $DB->update_record('bigbluebuttonbn_openstack', $this->meeting);
        }
        catch (\Exception $exception) {
            $exception_message = "The bbb host cannot be destroy. Try delete it manually. Meeting id: " . $this->meeting->meetingid . "\n" .
                $exception->getMessage();
            throw new \Exception($exception_message);
        }
    }
}
