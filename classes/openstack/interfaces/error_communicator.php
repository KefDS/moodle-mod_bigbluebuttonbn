<?php
namespace mod_bigbluebuttonbn\openstack;

interface error_communicator {
    public function build_message($data, $type);
    public function communicate_error($message, $type);
}
