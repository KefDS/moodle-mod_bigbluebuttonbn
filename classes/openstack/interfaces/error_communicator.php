<?php
namespace mod_bigbluebuttonbn\openstack;


interface error_communicator {
    public function communicate_error($meeting);
}