<?php
/**
 * Created by PhpStorm.
 * User: carlosmataguzman
 * Date: 6/12/17
 * Time: 1:05 PM
 */

defined('MOODLE_INTERNAL') || die;

global $BIGBLUEBUTTONBN_CFG;

require_once($CFG->libdir.'/formslib.php');
require_once(dirname(__FILE__).'/locallib.php');

class os_logs_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        // Actions for submit button
        $actions = array(0=>get_string('os_logs_choose','bigbluebuttonbn').'...');
        $actions[1] = get_string('os_logs_download', 'bigbluebuttonbn');
        $actions[2] = get_string('os_logs_delete', 'bigbluebuttonbn');

        //Create submit button
        $objs = array();
        $objs[] =& $mform->createElement('select', 'action', null, $actions);
        $objs[] =& $mform->createElement('submit', 'doaction', get_string('os_logs_go', 'bigbluebuttonbn'));

        //Add eleements to form
        $mform->addElement('header', 'logs_header', get_string('os_logs_management', 'bigbluebuttonbn'));
        $mform->addElement('static', 'os_logs_explanation', '', get_string('os_logs_explanation', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'logs_begin_date', get_string('os_logs_begin_date', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'logs_end_date', get_string('os_logs_end_date', 'bigbluebuttonbn'));
        $mform->addElement('checkbox', 'select_all', get_string('select_all', 'bigbluebuttonbn'));
        $mform->addElement('group', 'actionsgrp', get_string('os_logs_selected_logs', 'bigbluebuttonbn'), $objs, ' ', false);


    }
    //Custom validation should be added here
    /*function validation($data, $files) {
        return array();
    }*/
}