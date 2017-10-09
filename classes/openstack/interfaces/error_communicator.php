<?php
namespace mod_bigbluebuttonbn\openstack;

interface error_communicator {
    public function build_message($data);
    public function communicate_error($message, $type);
}
