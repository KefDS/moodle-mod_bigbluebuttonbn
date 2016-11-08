<?php
namespace mod_bigbluebuttonbn\task;
require_once dirname(dirname(__DIR__)) . '/lib.php';
require_once dirname(__DIR__) . "/openstack/moodle_bbb_openstack_stacks_management_tasks.php";
require_once dirname(__DIR__) . "/openstack/exception_handlers/archive_log_exception_handler.php";
require_once dirname(__DIR__) . "/openstack/error_communicators/moodle_message_api_communicator.php";
use mod_bigbluebuttonbn\openstack;
class openstack_async_communication extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('task_openstack_async_communication', 'mod_bigbluebuttonbn');
    }
    public function execute() {
        $async_tasks = new openstack\moodle_bbb_openstack_stacks_management_tasks(
            new openstack\archive_log_exception_handler(),
            new openstack\moodle_message_api_communicator());
        $async_tasks->do_tasks();
    }
}
