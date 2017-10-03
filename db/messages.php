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
    // Notify teacher about a meeting that fail to be created
    'error' => array (
        'capability'  => 'mod/bigbluebuttonbn:emailnotifyerror'
    )
);