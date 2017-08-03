<?php
/**
 * Created by PhpStorm.
 * User: carlosmataguzman
 * Date: 6/26/17
 * Time: 6:52 AM
 */

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('../locallib.php');

// Check the user is logged in.
require_login();

// Print moodle admin options in interface
admin_externalpage_setup('managemodules');

if (empty($SESSION->date_interval)) {
    redirect(new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php'));
}

// Set page view settings
$PAGE->set_title("$SITE->shortname: " . get_string('os_delete_records','bigbluebuttonbn'));
$PAGE->navbar->add(get_string('bigbluebuttonbn', 'bigbluebuttonbn'), $CFG->wwwroot.'/'.$CFG->admin.'/settings.php?section=modsettingbigbluebuttonbn');
$PAGE->navbar->add( get_string('openstack_integration','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php');
$PAGE->navbar->add( get_string('os_delete_records','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_records_delete.php');


if($_REQUEST['delete'] == 1 ){

    global $DB;
    $sesskey = required_param('sesskey', PARAM_RAW);
    $params = array('sesskey' => $sesskey); //Used by Moodle to prevent XSS attacks
    $redirect = new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php', $params);

    switch ($SESSION->module){
        case 'os_logs':
            bigbluebuttonbn_delete_os_logs_by_date($SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);
            redirect($redirect, get_string("os_delete_records_success","bigbluebuttonbn", $SESSION->records_number), null, \core\output\notification::NOTIFY_SUCCESS);
            break;
        case 'reservations':
            bigbluebuttonbn_delete_reservations_records_by_date($SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);
            redirect($redirect, get_string("os_delete_records_success","bigbluebuttonbn", $SESSION->records_number), null, \core\output\notification::NOTIFY_SUCCESS);
            break;
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading( get_string('os_delete_records','bigbluebuttonbn'));

switch ($SESSION->module){
    case 'os_logs':
        $SESSION->records_number = bigbluebuttonbn_count_os_logs_records($SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);
        echo $OUTPUT->confirm(get_string('os_delete_os_logs_confirmation', 'bigbluebuttonbn', $SESSION->records_number ), 'openstack_records_delete.php?delete=1', 'openstack_integration_settings.php');
        break;
    case 'reservations':
        $SESSION->records_number = bigbluebuttonbn_count_reservations_records($SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);
        echo $OUTPUT->confirm(get_string('os_delete_reservations_records_confirmation', 'bigbluebuttonbn', $SESSION->records_number ), 'openstack_records_delete.php?delete=1', 'openstack_integration_settings.php');
        break;
}
echo $OUTPUT->footer();
