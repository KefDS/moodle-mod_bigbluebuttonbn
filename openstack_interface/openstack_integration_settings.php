<?php
/**
 * Created by PhpStorm.
 * User: carlosmataguzman
 * Date: 6/8/17
 * Time: 4:22 PM
 */

global $BIGBLUEBUTTONBN_CFG;

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/dataformatlib.php');
require_once(dirname(__FILE__) . '/openstack_integration_forms.php');


//Aditional param to download logs
$dataformat = optional_param('dataformat', '', PARAM_ALPHA);


// Check the user is logged in.
require_login();

// Print moodle admin options in interface
admin_externalpage_setup('managemodules');


$PAGE->set_title("$SITE->shortname: " .get_string('openstack_integration','bigbluebuttonbn'));
$PAGE->navbar->add(get_string('bigbluebuttonbn', 'bigbluebuttonbn'), $CFG->wwwroot.'/'.$CFG->admin.'/settings.php?section=modsettingbigbluebuttonbn');
$PAGE->navbar->add( get_string('openstack_integration','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php');


//Create OpenStack logs form
$os_logs_form = new os_logs_form();

if ($data = $os_logs_form->get_data()) {

    if (!isset($SESSION->date_interval)) {
        $SESSION->date_interval = array();
    }

    $SESSION->date_interval['begin']=$data->logs_begin_date;
    $SESSION->date_interval['end']=$data->logs_end_date;
    $SESSION->date_interval['select_all']=$data->select_all;

    // Check if an action should be performed and do so
    switch ($data->action) {// Case numbers are related with selection in dropdown
        case 1:
            $sesskey = required_param('sesskey', PARAM_RAW);
            $params = array('sesskey' => $sesskey); //Used by Moodle to prevent XSS attacks
            $redirect = new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_logs_download.php', $params);
            redirect($redirect);
        case 2:
            $sesskey = required_param('sesskey', PARAM_RAW);
            $params = array('sesskey' => $sesskey); //Used by Moodle to prevent XSS attacks
            $redirect = new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_logs_delete.php', $params);
            redirect($redirect);
    }
}


echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('openstack_integration','bigbluebuttonbn'));

//$formcontinue = new single_button(new moodle_url("$CFG->wwwroot/course/mod.php", $optionsyes), get_string('yes'));
//$formcancel = new single_button($return, get_string('no'), 'get');
//echo $OUTPUT->confirm($strdeletechecktypename, $formcontinue, $formcancel);
//echo $OUTPUT->confirm('Are you sure?', '/index.php?delete=1', '/index.php');


$os_logs_form->display();

echo $OUTPUT->footer();


//Go to another page
/*$link = new moodle_url ('http://www.google.com');
echo $OUTPUT->action_link($link, 'Ir a Google', new popup_action ('click', $link));*/