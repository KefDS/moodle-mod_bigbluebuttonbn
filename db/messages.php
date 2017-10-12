<?php
/**
 * Register BigBlueButton mod to send messages
 *
 * @package mod_bigbluebuttonbn
 * @author Fabian Rodriguez (fabian.rodriguezobando [at] ucr.ac.cr)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

defined('MOODLE_INTERNAL') || die();

$messageproviders = array (

    // Notify about conection errors
    'openstack_conection_error' => array (
    ),

    // Notify about creation related errors
    'openstack_task_error' => array(
    ),

);