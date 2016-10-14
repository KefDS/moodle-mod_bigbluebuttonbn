<?php

/**
 * BBB servers error handling.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
class mod_bigbluebuttonbn_openstack_error_communication {
    function communicate_error(Exception $exception) {
        file_put_contents("../openstack_errors", $exception->getMessage());
    }
}