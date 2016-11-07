<?php
namespace mod_bigbluebuttonbn\openstack;

interface exception_handler {
    public function handle_exception(\Exception $exception);
}