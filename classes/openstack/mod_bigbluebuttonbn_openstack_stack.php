<?php
/**
 * OpenStack orchestration operations.
 *
 * @package mod_bigbluebuttonbn
 * @author Kevin Delgado (kevin.delgadosandi [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */
use OpenCloud\Orchestration\Service;

class mod_bigbluebuttonbn_openstack_stack {
    private $orchestration_service;

    function __construct(Service $orchestration_service) {
        $this->orchestration_service = $orchestration_service;
    }

    function create_stack($heat_param) {
        try {
            return $this->orchestration_service->stack($heat_param);
        }
        catch (Exception $e) {
            // TODO_BBB: what to do with $e
        }
    }

    function delete_stack($stack_name) {
        try {
            $stack = $this->orchestration_service->getStack($stack_name);
            $stack->delete();
        }
        catch (Exception $e) {
            // TODO_BBB: what to do with $e
        }
    }
}