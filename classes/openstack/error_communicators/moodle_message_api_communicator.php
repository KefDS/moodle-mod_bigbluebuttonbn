<?php
namespace mod_bigbluebuttonbn\openstack;

require_once dirname(dirname(__FILE__)) . '/interfaces/error_communicator.php';

class moodle_message_api_communicator implements error_communicator {
    function communicate_error($meeting) {
        // TODO_BBB: Implementar mensaje de error para el usuario
    }
}
