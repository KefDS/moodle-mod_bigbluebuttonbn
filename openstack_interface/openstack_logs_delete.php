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
$PAGE->set_title("$SITE->shortname: " . get_string('os_logs_delete_logs','bigbluebuttonbn'));
$PAGE->navbar->add(get_string('bigbluebuttonbn', 'bigbluebuttonbn'), $CFG->wwwroot.'/'.$CFG->admin.'/settings.php?section=modsettingbigbluebuttonbn');
$PAGE->navbar->add( get_string('openstack_integration','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php');
$PAGE->navbar->add( get_string('os_logs_delete_logs','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_logs_delete.php');

$records_number = bigbluebuttonbn_count_records('bigbluebuttonbn_os_logs', $SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);

if($_REQUEST['delete'] == 1 ){
    bigbluebuttonbn_delete_os_logs_by_date('bigbluebuttonbn_os_logs', $SESSION->date_interval['select_all'], $SESSION->date_interval['begin'], $SESSION->date_interval['end']);
    $sesskey = required_param('sesskey', PARAM_RAW);
    $params = array('sesskey' => $sesskey); //Used by Moodle to prevent XSS attacks
    $redirect = new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php', $params);
    redirect($redirect, get_string("os_logs_delete_success","bigbluebuttonbn", $records_number), null, \core\output\notification::NOTIFY_SUCCESS);
}

echo $OUTPUT->header();
echo $OUTPUT->heading( get_string('os_logs_delete_logs','bigbluebuttonbn'));

echo $OUTPUT->confirm(get_string('os_logs_delete_confirmation_message', 'bigbluebuttonbn', $records_number ), 'openstack_logs_delete.php?delete=1', 'openstack_integration_settings.php');
echo $OUTPUT->footer();
