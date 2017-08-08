<?php
/**
 * Created by PhpStorm.
 * User: carlosmataguzman
 * Date: 6/12/17
 * Time: 1:05 PM
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once(dirname(__FILE__) . '/../locallib.php');



//Download logs records form
class os_logs_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        // Actions for submit button
        $actions = array(0=>get_string('os_records_choose','bigbluebuttonbn').'...');
        $actions[1] = get_string('os_records_download', 'bigbluebuttonbn');
        $actions[2] = get_string('os_records_delete', 'bigbluebuttonbn');

        //Create submit button
        $objs = array();
        $objs[] =& $mform->createElement('select', 'os_logs_action', null, $actions);
        $objs[] =& $mform->createElement('submit', 'os_logs_doaction', get_string('os_records_go', 'bigbluebuttonbn'));

        //Add eleements to form
        $mform->addElement('header', 'logs_header', get_string('os_logs_management', 'bigbluebuttonbn'));
        $mform->addElement('static', 'os_logs_explanation', '', get_string('os_logs_explanation', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'os_logs_begin_date', get_string('os_records_begin_date', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'os_logs_end_date', get_string('os_records_end_date', 'bigbluebuttonbn'));
        $mform->addElement('checkbox', 'os_logs_select_all', get_string('os_records_select_all', 'bigbluebuttonbn'));
        $mform->addElement('group', 'os_logs_actionsgroup', get_string('os_records_selected', 'bigbluebuttonbn'), $objs, ' ', false);



    }
    //Custom validation should be added here
    /*function validation($data, $files) {
        return array();
    }*/
}

//Download reservation records log
class reservations_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        // Actions for submit button
        $actions = array(0=>get_string('os_records_choose','bigbluebuttonbn').'...');
        $actions[1] = get_string('os_records_download', 'bigbluebuttonbn');
        $actions[2] = get_string('os_records_delete', 'bigbluebuttonbn');

        //Create submit button
        $objs = array();
        $objs[] =& $mform->createElement('select', 'reservation_records_action', null, $actions);
        $objs[] =& $mform->createElement('submit', 'reservation_records_doaction', get_string('os_records_go', 'bigbluebuttonbn'));

        //Add eleements to form
        $mform->addElement('header', 'reservations_records_header', get_string('reservations_records_management', 'bigbluebuttonbn'));
        $mform->addElement('static', 'reservations_records_explanation', '', get_string('reservations_records_explanation', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'reservations_records_begin_date', get_string('os_records_begin_date', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'reservations_records_end_date', get_string('os_records_end_date', 'bigbluebuttonbn'));
        $mform->addElement('checkbox', 'reservation_records_select_all', get_string('os_records_select_all', 'bigbluebuttonbn'));
        $mform->addElement('group', 'reservation_records_actionsgroup', get_string('os_records_selected', 'bigbluebuttonbn'), $objs, ' ', false);

    }
    //Custom validation should be added here
    /*function validation($data, $files) {
        return array();
    }*/
}