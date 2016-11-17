<?php
namespace mod_bigbluebuttonbn\openstack;

require_once dirname(dirname(__FILE__)) . '/interfaces/exception_handler.php';

class archive_log_exception_handler implements exception_handler  {
    public function handle_exception(\Exception $exception) {
        echo($exception->getMessage());
        $log_archive = dirname(dirname(dirname(dirname(__FILE__)))) . '/log/openstack_errors';
        file_put_contents($log_archive, "[" . date('Y-m-d H:i:s') . '] ' . $exception->getMessage() . "\n\n", FILE_APPEND | LOCK_EX);
    }
}
