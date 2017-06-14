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



class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        if ( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_integration)){
            $mform->addElement('static', 'bigbluebutton_manage_os_integration', get_string('config_openstack_integration', 'bigbluebuttonbn'));


        }



        $mform->addElement('text', 'email', "sssss"); // Add elements to your form
        $mform->setType('email', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('email', 'Please enter email');        //Default value

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}


class os_logs_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!


        $actions = array(0=>get_string('os_logs_choose','bigbluebuttonbn').'...');

        $actions[1] = get_string('os_logs_download', 'bigbluebuttonbn');
        $actions[2] = get_string('os_logs_delete', 'bigbluebuttonbn');


        $objs = array();
        $objs[] =& $mform->createElement('select', 'action', null, $actions);
        $objs[] =& $mform->createElement('submit', 'doaction', get_string('os_logs_go', 'bigbluebuttonbn'));

        $mform->addElement('header', 'logs_header', get_string('os_logs_management', 'bigbluebuttonbn'));
        $mform->addElement('static', 'os_logs_explanation', '', get_string('os_logs_explanation', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'logs_begin_date', get_string('os_logs_begin_date', 'bigbluebuttonbn'));
        $mform->addElement('date_time_selector', 'logs_end_date', get_string('os_logs_end_date', 'bigbluebuttonbn'));
        $mform->addElement('checkbox', 'select_all', get_string('select_all', 'bigbluebuttonbn'));
        $mform->addElement('group', 'actionsgrp', get_string('os_logs_selected_logs', 'bigbluebuttonbn'), $objs, ' ', false);


    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}