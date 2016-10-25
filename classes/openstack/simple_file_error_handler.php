<?php

/**
 * BBB servers error handling.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

namespace mod_bigbluebuttonbn\openstack;

class simple_file_error_handler {
    function handle_error(Exception $exception) {
        file_put_contents("../openstack_errors.txt", $exception->getMessage());
    }
}