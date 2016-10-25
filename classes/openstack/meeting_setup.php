<?php
/**
 * Big Blue Button meeting setup.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;
use OpenCloud\OpenStack;

class meeting_setup {
    private $meeting;
    private $bbb_servers_management;
    private $error_handler;

    function __construct($meeting, $error_handler = null) {
        $this->meeting = $meeting;

        $openstack_client = new OpenStack(bigbluebuttonbn_get_cfg_heat_url(), [
            'username' => bigbluebuttonbn_get_cfg_openstack_username(),
            'password' => bigbluebuttonbn_get_cfg_openstack_password(),
            'tenantId' => bigbluebuttonbn_get_cfg_openstack_tenant_id()
        ]);
        $openstack_client->authenticate();
        $this->bbb_servers_management = new bbb_servers_management($openstack_client, bigbluebuttonbn_get_cfg_heat_region());
        $this->error_handler = $error_handler ? new simple_file_error_handler() : $error_handler;
    }

    function create_meeting_host() {
        global $DB;
        try {
            $bbb_host_name = $this->bbb_servers_management->create_bbb_host($this->meeting->meetingid, json_decode(bigbluebuttonbn_get_cfg_json_stack_parameters()));

            $this->meeting->openstack_stack_name = $bbb_host_name;
            $this->meeting->host_state = 'In Progress';
            $DB->update_record('bigbluebuttonbn', $this->meeting);
        }
        catch (Exception $e) {
            $this->error_handler->handle_error($e);
            // TODO_BBB: Poner estado de la BD en failed?
        }
    }

    function get_meeting_host_info() {
        global $DB;
        try {
            $bbb_host_data = $this->bbb_servers_management->get_stack_outputs($this->meeting->meetingid);
            if($bbb_host_data) {
                $this->meeting->bbb_server_url = $bbb_host_data['url'];
                $this->meeting->shared_secret = $bbb_host_data['shared_key'];
                $this->meeting->host_state = 'Ready';
                $DB->update_record('bigbluebuttonbn', $this->meeting);
            }
        }
        catch (Exception $e) {
            $this->error_handler->handle_error($e);
            // TODO_BBB: Poner estado de la BD en failed?
        }
    }

    function delete_meeting_host() {
        try {
            $this->bbb_servers_management->delete_bbb_host($this->meeting->meetingid);
        }
        catch (Exception $e) {
            $this->error_handler->handle_error($e);
            // TODO_BBB: ?
        }
    }
}