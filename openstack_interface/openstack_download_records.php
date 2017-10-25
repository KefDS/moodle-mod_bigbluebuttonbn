<?php
/**
 * Created by PhpStorm.
 * User: carlosmataguzman
 * Date: 6/8/17
 * Time: 4:22 PM
 */
require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/dataformatlib.php');


// Aditional param to download logs
$dataformat = optional_param('dataformat', '', PARAM_ALPHA);


// Check the user is logged in.
require_login();

// Print moodle admin options in interface
admin_externalpage_setup('managemodules');

// Redirect if cant get date_interval values
if (empty($SESSION->date_interval)) {
    redirect(new moodle_url('/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php'));
}

// Set page view settings
$PAGE->set_title("$SITE->shortname: " . get_string('os_download_records','bigbluebuttonbn'));
$PAGE->navbar->add(get_string('bigbluebuttonbn', 'bigbluebuttonbn'), $CFG->wwwroot.'/'.$CFG->admin.'/settings.php?section=modsettingbigbluebuttonbn');
$PAGE->navbar->add( get_string('openstack_integration','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_integration_settings.php');
$PAGE->navbar->add( get_string('os_download_records','bigbluebuttonbn'), $CFG->wwwroot.'/mod/bigbluebuttonbn/openstack_interface/openstack_download_records.php');

// Defines the file final output format
if ($dataformat){
    global $DB;

    switch ($SESSION->module){
        case 'os_logs':
            $fields = array(
                'id'=>"ID",
                'event_time'=>"EVENT_TIME",
                'meetingid'=>"MEETINGID",
                'stack_name'=>"STACK_NAME",
                'log_level'=>"LOG_LEVEL",
                'component'=>"COMPONENT",
                'event'=>"EVENT",
                'event_details'=>"EVENT_DETAILS",
                'conference_name'=>"CONFERENCE_NAME",
                'user_name'=>"USERNAME",
                'course_name'=>"COURSE_NAME"
            );

            //Get records to be download
            if($SESSION->date_interval['select_all']){
                $logs_records = $DB->get_records("bigbluebuttonbn_os_logs");
            }else{
                $logs_records = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn_os_logs} WHERE event_time > ? AND event_time < ?', array( $SESSION->date_interval['begin'] , $SESSION->date_interval['end'] ));
            }
            download_as_dataformat('openstack_logs_'.date('Y-m-d'), $dataformat, $fields, $logs_records);
            exit;

        case 'reservations':
            $fields = array(
                'id'=>"ID",
                'start_time'=>"START_TIME",
                'finish_name'=>"FINISH_TIME",
                'begin_date'=>"BEGIN_DATE",
                'end_date'=>"END_DATE",
                'user_info'=>"USER_INFO",
                'course_info'=>"COURSE_INFO",
                'meetingid'=>"MEETINGID"
            );

            //Get records to be download
            if($SESSION->date_interval['select_all']){
                $reservation_records = $DB->get_records("bigbluebuttonbn_reservations");
            }else{
                $reservation_records = $DB->get_records_sql('SELECT * FROM {bigbluebuttonbn_reservations} WHERE start_time > ? AND start_time < ?', array( $SESSION->date_interval['begin'] , $SESSION->date_interval['end'] ));
            }
            download_as_dataformat('bbbb_conference_reservations_'.date('Y-m-d'), $dataformat, $fields, $reservation_records);
            exit;

    }

}

// Construct page view

echo $OUTPUT->header();
echo $OUTPUT->heading( get_string('os_download_records','bigbluebuttonbn'));
echo $OUTPUT->download_dataformat_selector(get_string('os_records_download','bigbluebuttonbn'), 'openstack_download_records.php');
echo $OUTPUT->footer();
