<?php
/**
 * Library calls for Moodle and BigBlueButton.
 *
 * @package   mod_bigbluebuttonbn
 * @author    Fred Dixon  (ffdixon [at] blindsidenetworks [dt] com)
 * @author    Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 * @copyright 2010-2015 Blindside Networks Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

defined('MOODLE_INTERNAL') || die;

global $BIGBLUEBUTTONBN_CFG, $CFG;

require_once($CFG->dirroot.'/calendar/lib.php');
require_once($CFG->dirroot.'/message/lib.php');
require_once($CFG->dirroot.'/mod/lti/OAuth.php');
require_once($CFG->libdir.'/accesslib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/datalib.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once($CFG->libdir.'/enrollib.php');
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/formslib.php');

require_once(dirname(__FILE__).'/JWT.php');

if( file_exists(dirname(__FILE__).'/config.php') ) {
    require_once(dirname(__FILE__).'/config.php');
    if( isset($BIGBLUEBUTTONBN_CFG) ) {
        $CFG = (object) array_merge((array)$CFG, (array)$BIGBLUEBUTTONBN_CFG);
    }
} else {
    $BIGBLUEBUTTONBN_CFG = new stdClass();
}

/*
 * DURATIONCOMPENSATION: Feature removed by configuration
 */
$BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_scheduled_duration_enabled = 0;
/*
 * Remove this block when restored
 */

const BIGBLUEBUTTONBN_DEFAULT_SERVER_URL = "http://test-install.blindsidenetworks.com/bigbluebutton/";
const BIGBLUEBUTTONBN_DEFAULT_SHARED_SECRET = "8cd8ef52e8e101574e400365b55e11a6";

const BIGBLUEBUTTONBN_LOG_EVENT_CREATE = "Create";
const BIGBLUEBUTTONBN_LOG_EVENT_JOIN = "Join";
const BIGBLUEBUTTONBN_LOG_EVENT_LOGOUT = "Logout";
const BIGBLUEBUTTONBN_LOG_EVENT_IMPORT = "Import";
const BIGBLUEBUTTONBN_LOG_EVENT_DELETE = "Delete";

function bigbluebuttonbn_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return true;
        case FEATURE_GROUPS:                  return true;
        case FEATURE_GROUPINGS:               return true;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        // case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;

        default: return null;
    }
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $bigbluebuttonbn An object from the form in mod_form.php
 * @return int The id of the newly inserted bigbluebuttonbn record
 */
function bigbluebuttonbn_add_instance($data, $mform) {
    global $DB, $CFG;

    $draftitemid = isset($data->presentation)? $data->presentation: null;
    $context = bigbluebuttonbn_get_context_module($data->coursemodule);

    bigbluebuttonbn_process_pre_save($data);

    unset($data->presentation);
    $bigbluebuttonbn_id = $DB->insert_record('bigbluebuttonbn', $data);
    $data->id = $bigbluebuttonbn_id;

    bigbluebuttonbn_update_media_file($bigbluebuttonbn_id, $context, $draftitemid);

    bigbluebuttonbn_process_post_save($data);

    return $bigbluebuttonbn_id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $bigbluebuttonbn An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function bigbluebuttonbn_update_instance($data, $mform) {
    global $DB, $CFG;

    $data->id = $data->instance;
    $draftitemid = isset($data->presentation)? $data->presentation: null;
    $context = bigbluebuttonbn_get_context_module($data->coursemodule);

    bigbluebuttonbn_process_pre_save($data);

    unset($data->presentation);
    $DB->update_record("bigbluebuttonbn", $data);

    bigbluebuttonbn_update_media_file($data->id, $context, $draftitemid);

    bigbluebuttonbn_process_post_save($data);

    return true;
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function bigbluebuttonbn_delete_instance($id) {
    global $CFG, $DB, $USER;

    if (! $bigbluebuttonbn = $DB->get_record('bigbluebuttonbn', array('id' => $id))) {
        return false;
    }

    $result = true;

    //
    // End the session associated with this instance (if it's running)
    //
    //$meetingID = $bigbluebuttonbn->meetingid.'-'.$bigbluebuttonbn->course.'-'.$bigbluebuttonbn->id;
    //
    //$modPW = $bigbluebuttonbn->moderatorpass;
    //$url = bigbluebuttonbn_get_cfg_server_url();
    //$shared_secret = bigbluebuttonbn_get_cfg_shared_secret();
    //
    //if( bigbluebuttonbn_isMeetingRunning($meetingID, $url, $shared_secret) )
    //    $getArray = bigbluebuttonbn_doEndMeeting( $meetingID, $modPW, $url, $shared_secret );

    if (! $DB->delete_records('bigbluebuttonbn', array('id' => $bigbluebuttonbn->id))) {
        $result = false;
    }

    if (! $DB->delete_records('event', array('modulename'=>'bigbluebuttonbn', 'instance'=>$bigbluebuttonbn->id))) {
        $result = false;
    }

    $log = new stdClass();

    $log->meetingid = $bigbluebuttonbn->meetingid;
    $log->courseid = $bigbluebuttonbn->course;
    $log->bigbluebuttonbnid = $bigbluebuttonbn->id;
    $log->userid = $USER->id;
    $log->timecreated = time();
    $log->log = BIGBLUEBUTTONBN_LOG_EVENT_DELETE;

    $logs = $DB->get_records('bigbluebuttonbn_logs', array('bigbluebuttonbnid' => $bigbluebuttonbn->id, 'log' => BIGBLUEBUTTONBN_LOG_EVENT_CREATE));
    error_log(json_encode($logs));
    $has_recordings = 'false';
    if (! empty($logs) ) {
        error_log("IS not empty");
        foreach ( $logs as $l ) {
            error_log(json_encode($l));
            $meta = json_decode($l->meta);
            if ( $meta->record ) {
                $has_recordings = 'true';
            }
        }
    }
    $log->meta = "{\"has_recordings\":{$has_recordings}}";

    if (! $returnid = $DB->insert_record('bigbluebuttonbn_logs', $log)) {
        $result = false;
    }

    /*---- OpenStack integration ----*/

    if(bigbluebuttonbn_openstack_managed_conference($bigbluebuttonbn) &&  ($bigbluebuttonbn->openingtime > bigbluebuttonbn_get_min_openingtime())){

        $event_record = (object)[
            'event_time' => time(),
            'meetingid'=>$bigbluebuttonbn->meetingid,
            'stack_name'=>bigbluebuttonbn_get_os_stack_name($bigbluebuttonbn->meetingid),
            'log_level'=>'INFO',
            'component'=>'BBB_PLUGIN',
            'event'=>'USER_DELETED_CONFERENCE',
            'event_details' => date('m/d/Y h:i:s a', time()).' User deleted conference before it started.',
            'conference_name'=>$bigbluebuttonbn->name,
            'user_name'=>$USER->username,
            'course_name'=>$bigbluebuttonbn->name
        ];

        //Log the event
        bigbluebuttonbn_add_openstack_event($event_record);
        //Delete the associated record
        bigbluebuttonbn_delete_os_conference($bigbluebuttonbn->meetingid);
        if(bigbluebuttonbn_get_cfg_reservation_module_enabled()){
            //Delete form reservation record
            bigbluebuttonbn_delete_reservation($bigbluebuttonbn->meetingid);
        }

    }
    //Conference is deleted before its openingtime

    /*---- end of OpenStack integration ----*/
    return $result;
}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 */
function bigbluebuttonbn_user_outline($course, $user, $mod, $bigbluebuttonbn) {
    return true;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 */
function bigbluebuttonbn_user_complete($course, $user, $mod, $bigbluebuttonbn) {
    return true;
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in bigbluebuttonbn activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function bigbluebuttonbn_print_recent_activity($course, $isteacher, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Returns all activity in bigbluebuttonbn since a given time
 *
 * @param array $activities sequentially indexed array of objects
 * @param int $index
 * @param int $timestart
 * @param int $courseid
 * @param int $cmid
 * @param int $userid defaults to 0
 * @param int $groupid defaults to 0
 * @return void adds items into $activities and increases $index
 */
function bigbluebuttonbn_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see recordingsbn_get_recent_mod_activity()}

 * @return void
 */
function bigbluebuttonbn_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 **/
function bigbluebuttonbn_cron () {
    return true;
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of bigbluebuttonbn. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $bigbluebuttonbnid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function bigbluebuttonbn_get_participants($bigbluebuttonbnid) {
    return false;
}

/**
 * Returns all other caps used in module
 * @return array
 */
function bigbluebuttonbn_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * This function returns if a scale is being used by one bigbluebuttonbn
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $bigbluebuttonbnid ID of an instance of this module
 * @return mixed
 */
function bigbluebuttonbn_scale_used($bigbluebuttonbnid, $scaleid) {
    $return = false;

    return $return;
}

/**
 * Checks if scale is being used by any instance of bigbluebuttonbn.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any bigbluebuttonbn
 */
function bigbluebuttonbn_scale_used_anywhere($scaleid) {
    $return = false;

    return $return;
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function bigbluebuttonbn_reset_userdata($data) {
    return array();
}

/**
 * List of view style log actions
 * @return array
 */
function bigbluebuttonbn_get_view_actions() {
    return array('view', 'view all');
}

/**
 * List of update style log actions
 * @return array
 */
function bigbluebuttonbn_get_post_actions() {
    return array('update', 'add', 'create', 'join', 'end', 'left', 'publish', 'unpublish', 'delete');
}


/**
 * @global object
 * @global object
 * @param array $courses
 * @param array $htmlarray Passed by reference
 */
function bigbluebuttonbn_print_overview($courses, &$htmlarray) {
    global $USER, $CFG;

    if (empty($courses) || !is_array($courses) || count($courses) == 0) {
        return array();
    }

    if (!$bigbluebuttonbns = get_all_instances_in_courses('bigbluebuttonbn', $courses)) {
        return;
    }

    foreach ($bigbluebuttonbns as $bigbluebuttonbn) {
        $now = time();
        if ( $bigbluebuttonbn->openingtime and (!$bigbluebuttonbn->closingtime or $bigbluebuttonbn->closingtime > $now)) { // A bigbluebuttonbn is scheduled.
            $str = '<div class="bigbluebuttonbn overview"><div class="name">'.
                get_string('modulename', 'bigbluebuttonbn').': <a '.($bigbluebuttonbn->visible ? '' : ' class="dimmed"').
                ' href="'.$CFG->wwwroot.'/mod/bigbluebuttonbn/view.php?id='.$bigbluebuttonbn->coursemodule.'">'.
                $bigbluebuttonbn->name.'</a></div>';
            if ( $bigbluebuttonbn->openingtime > $now ) {
                $str .= '<div class="info">'.get_string('starts_at', 'bigbluebuttonbn').': '.userdate($bigbluebuttonbn->openingtime).'</div>';
            } else {
                $str .= '<div class="info">'.get_string('started_at', 'bigbluebuttonbn').': '.userdate($bigbluebuttonbn->openingtime).'</div>';
            }
            $str .= '<div class="info">'.get_string('ends_at', 'bigbluebuttonbn').': '.userdate($bigbluebuttonbn->closingtime).'</div></div>';

            if (empty($htmlarray[$bigbluebuttonbn->course]['bigbluebuttonbn'])) {
                $htmlarray[$bigbluebuttonbn->course]['bigbluebuttonbn'] = $str;
            } else {
                $htmlarray[$bigbluebuttonbn->course]['bigbluebuttonbn'] .= $str;
            }
        }
    }
}


/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $coursemodule
 * @return object|null
 */
function bigbluebuttonbn_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;

    if ( !$bigbluebuttonbn = $DB->get_record('bigbluebuttonbn', array('id'=>$coursemodule->instance), 'id, name, intro, introformat')) {
        return NULL;
    }

    $info = new cached_cm_info();
    $info->name = $bigbluebuttonbn->name;

    if ($coursemodule->showdescription) {
        // Convert intro to html. Do not filter cached version, filters run at display time.
        $info->content = format_module_intro('bigbluebuttonbn', $bigbluebuttonbn, $coursemodule->id, false);
    }

    return $info;
}

/**
 * Runs any processes that must run before
 * a bigbluebuttonbn insert/update
 *
 * @global object
 * @param object $bigbluebuttonbn BigBlueButtonBN form data
 * @return void
 **/
function bigbluebuttonbn_process_pre_save(&$bigbluebuttonbn) {
    global $DB, $CFG;

    if ( !isset($bigbluebuttonbn->timecreated) || !$bigbluebuttonbn->timecreated ) {
        $bigbluebuttonbn->timecreated = time();
        //Assign password only if it is a new activity
        if( isset($bigbluebuttonbn->add) && !empty($bigbluebuttonbn->add) ) {
            $bigbluebuttonbn->moderatorpass = bigbluebuttonbn_random_password(12);
            $bigbluebuttonbn->viewerpass = bigbluebuttonbn_random_password(12);
        }

    } else {
        $bigbluebuttonbn->timemodified = time();
    }

    if (! isset($bigbluebuttonbn->wait))
        $bigbluebuttonbn->wait = 0;
    if (! isset($bigbluebuttonbn->record))
        $bigbluebuttonbn->record = 0;
    if (! isset($bigbluebuttonbn->tagging))
        $bigbluebuttonbn->tagging = 0;

    $bigbluebuttonbn->participants = htmlspecialchars_decode($bigbluebuttonbn->participants);
}

/**
 * Runs any processes that must be run
 * after a bigbluebuttonbn insert/update
 *
 * @global object
 * @param object $bigbluebuttonbn BigBlueButtonBN form data
 * @return void
 **/
function bigbluebuttonbn_process_post_save(&$bigbluebuttonbn) {
    global $DB, $CFG, $USER;

    // Now that an id was assigned, generate and set the meetingid property based on
    // [Moodle Instance + Activity ID + BBB Secret] (but only for new activities)
    if( isset($bigbluebuttonbn->add) && !empty($bigbluebuttonbn->add) ) {
        $bigbluebuttonbn_meetingid = sha1($CFG->wwwroot.$bigbluebuttonbn->id.bigbluebuttonbn_get_cfg_shared_secret());
        $DB->set_field('bigbluebuttonbn', 'meetingid', $bigbluebuttonbn_meetingid, array('id' => $bigbluebuttonbn->id));

        /*---- OpenStack integration ----*/

        if (bigbluebuttonbn_get_cfg_openstack_integration() ){
            //Construct record object
            $bbb_os_record = (object)[
                'meetingid'=>$bigbluebuttonbn_meetingid,
                'courseid'=>$bigbluebuttonbn->course,
                'meeting_duration'=>$bigbluebuttonbn->bbb_meeting_duration,
                'openingtime'=> $bigbluebuttonbn->openingtime,
                'bbb_server_status'=> 'Wating for creation',
                'deletiontime'=> get_meeting_deletion_time_minutes($bigbluebuttonbn->openingtime, $bigbluebuttonbn->bbb_meeting_duration),
                'conference_name'=>$bigbluebuttonbn->name,
                'user_name'=>$USER->username,
                'course_name'=>$bigbluebuttonbn->name,
            ];
            bigbluebuttonbn_create_or_update_os_conference($bbb_os_record);

            $event_record = (object)[
                'event_time' => time(),
                'meetingid'=>$bigbluebuttonbn_meetingid,
                'stack_name'=> bigbluebuttonbn_get_os_stack_name($bigbluebuttonbn_meetingid),
                'log_level'=>'INFO',
                'component'=>'BBB_PLUGIN',
                'event' => 'ADD_BBB_CONFERENCE',
                'event_details' => date('m/d/Y h:i:s a', time()).' User added a new BBB conference managed by OpenStack',
                'conference_name'=>$bigbluebuttonbn->name,
                'user_name'=>$USER->username,
                'course_name'=>bigbluebuttonbn_get_meeting_course_name($bigbluebuttonbn->course),
                ];
            //Log OpenStack event
            bigbluebuttonbn_add_openstack_event($event_record);
            //Reservations
            if(bigbluebuttonbn_get_cfg_reservation_module_enabled()){
                //Add metting id to reservation
                $reservation_data = (object)[
                    'meetingid'=>$bigbluebuttonbn_meetingid,
                    'id'=>$bigbluebuttonbn->reservation_id
                ];
                bigbluebuttonbn_add_meetingid_to_reservation($reservation_data);
            }
        }

        /*---- end of OpenStack integration*/

        $action = get_string('mod_form_field_notification_msg_created', 'bigbluebuttonbn');
    } else {
        $action = get_string('mod_form_field_notification_msg_modified', 'bigbluebuttonbn');

        /*---- OpenStack integration ----*/

        if (bigbluebuttonbn_openstack_managed_conference($bigbluebuttonbn) or bigbluebuttonbn_get_cfg_openstack_integration()){ //Edition of conference already managed by OpenStack

            //Get conference meetingid
            $bbb_openstack_meetingid = bigbluebuttonbn_get_openstack_meetingid_by_id($bigbluebuttonbn->id);
            //Construct record object
            $bbb_os_record = (object)[
                'courseid'=>$bigbluebuttonbn->course,
                'meeting_duration'=>$bigbluebuttonbn->bbb_meeting_duration,
                'openingtime'=> $bigbluebuttonbn->openingtime,
                'meetingid'=> $bbb_openstack_meetingid,
                'bbb_server_status'=> 'Wating for creation',
                'deletiontime'=> get_meeting_deletion_time_minutes($bigbluebuttonbn->openingtime, $bigbluebuttonbn->bbb_meeting_duration),
                'conference_name'=>$bigbluebuttonbn->name,
                'user_name'=>$USER->username,
                'course_name'=>bigbluebuttonbn_get_meeting_course_name($bigbluebuttonbn->course),
            ];

            $os_log_update_message = 'User updated a BBB conference managed by OpenStack';
            $os_log_event = 'UPDATE_BBB_CONFERENCE';

            if (!bigbluebuttonbn_openstack_managed_conference($bigbluebuttonbn)){
                $os_log_update_message = 'User added a new BBB conference managed by OpenStack';
                $os_log_event = 'ADD_BBB_CONFERENCE';
            }

            $event_record = (object)[
                'event_time' => time(),
                'stack_name'=> bigbluebuttonbn_get_os_stack_name($bbb_openstack_meetingid),
                'meetingid'=>$bbb_openstack_meetingid,
                'log_level'=>'INFO',
                'component'=>'BBB_PLUGIN',
                'event'=>$os_log_event,
                'event_details' => date('m/d/Y h:i:s a', time()).$os_log_update_message,
                'conference_name'=>$bigbluebuttonbn->name,
                'user_name'=>$USER->username,
                'course_name'=>bigbluebuttonbn_get_meeting_course_name($bigbluebuttonbn->course),
            ];

            //Add conference to OpenStack
            bigbluebuttonbn_create_or_update_os_conference($bbb_os_record);
            //Log OpenStack event
            bigbluebuttonbn_add_openstack_event($event_record);
        }

        /*---- end of OpenStack integration ----*/

    }
    $at = get_string('mod_form_field_notification_msg_at', 'bigbluebuttonbn');

    // Add evento to the calendar when if openingtime is set
    if ( isset($bigbluebuttonbn->openingtime) && $bigbluebuttonbn->openingtime ){
        $event = new stdClass();
        $event->name        = $bigbluebuttonbn->name;
        $event->courseid    = $bigbluebuttonbn->course;
        $event->groupid     = 0;
        $event->userid      = 0;
        $event->modulename  = 'bigbluebuttonbn';
        $event->instance    = $bigbluebuttonbn->id;
        $event->timestart   = $bigbluebuttonbn->openingtime;

        if ( $bigbluebuttonbn->closingtime ){
            $event->durationtime = $bigbluebuttonbn->closingtime - $bigbluebuttonbn->openingtime;
        } else {
            $event->durationtime = 0;
        }

        if ( $event->id = $DB->get_field('event', 'id', array('modulename'=>'bigbluebuttonbn', 'instance'=>$bigbluebuttonbn->id)) ) {
            $calendarevent = calendar_event::load($event->id);
            $calendarevent->update($event);
        } else {
            calendar_event::create($event);
        }

    } else {
        $DB->delete_records('event', array('modulename'=>'bigbluebuttonbn', 'instance'=>$bigbluebuttonbn->id));
    }

    if( isset($bigbluebuttonbn->notification) && $bigbluebuttonbn->notification ) {
        // Prepare message
        $msg = new stdClass();

        /// Build the message_body
        $msg->action = $action;
        $msg->activity_type = "";
        $msg->activity_title = $bigbluebuttonbn->name;
        $message_text = '<p>'.$msg->activity_type.' &quot;'.$msg->activity_title.'&quot; '.get_string('email_body_notification_meeting_has_been', 'bigbluebuttonbn').' '.$msg->action.'.</p>';

        /// Add the meeting details to the message_body
        $msg->action = ucfirst($action);
        $msg->activity_description = "";
        if( !empty($bigbluebuttonbn->intro) )
            $msg->activity_description = trim($bigbluebuttonbn->intro);
        $msg->activity_openingtime = "";
        if ($bigbluebuttonbn->openingtime) {
            $msg->activity_openingtime = calendar_day_representation($bigbluebuttonbn->openingtime).' '.$at.' '.calendar_time_representation($bigbluebuttonbn->openingtime);
        }
        $msg->activity_closingtime = "";
        if ($bigbluebuttonbn->closingtime ) {
            $msg->activity_closingtime = calendar_day_representation($bigbluebuttonbn->closingtime).' '.$at.' '.calendar_time_representation($bigbluebuttonbn->closingtime);
        }
        $msg->activity_owner = fullname($USER);

        $message_text .= '<p><b>'.$msg->activity_title.'</b> '.get_string('email_body_notification_meeting_details', 'bigbluebuttonbn').':';
        $message_text .= '<table border="0" style="margin: 5px 0 0 20px"><tbody>';
        $message_text .= '<tr><td style="font-weight:bold;color:#555;">'.get_string('email_body_notification_meeting_title', 'bigbluebuttonbn').': </td><td>';
        $message_text .= $msg->activity_title.'</td></tr>';
        $message_text .= '<tr><td style="font-weight:bold;color:#555;">'.get_string('email_body_notification_meeting_description', 'bigbluebuttonbn').': </td><td>';
        $message_text .= $msg->activity_description.'</td></tr>';
        $message_text .= '<tr><td style="font-weight:bold;color:#555;">'.get_string('email_body_notification_meeting_start_date', 'bigbluebuttonbn').': </td><td>';
        $message_text .= $msg->activity_openingtime.'</td></tr>';
        $message_text .= '<tr><td style="font-weight:bold;color:#555;">'.get_string('email_body_notification_meeting_end_date', 'bigbluebuttonbn').': </td><td>';
        $message_text .= $msg->activity_closingtime.'</td></tr>';
        $message_text .= '<tr><td style="font-weight:bold;color:#555;">'.$msg->action.' '.get_string('email_body_notification_meeting_by', 'bigbluebuttonbn').': </td><td>';
        $message_text .= $msg->activity_owner.'</td></tr></tbody></table></p>';

        // Send notification to all users enrolled
        bigbluebuttonbn_send_notification($USER, $bigbluebuttonbn, $message_text);
    }
}

/**
 * Update the bigbluebuttonbn activity to include any file
 * that was uploaded, or if there is none, set the
 * presentation field to blank.
 *
 * @param int $bigbluebuttonbn_id the bigbluebuttonbn id
 * @param stdClass $context the context
 * @param int $draftitemid the draft item
 */
function bigbluebuttonbn_update_media_file($bigbluebuttonbn_id, $context, $draftitemid) {
    global $DB;

    // Set the filestorage object.
    $fs = get_file_storage();
    // Save the file if it exists that is currently in the draft area.
    file_save_draft_area_files($draftitemid, $context->id, 'mod_bigbluebuttonbn', 'presentation', 0);
    // Get the file if it exists.
    $files = $fs->get_area_files($context->id, 'mod_bigbluebuttonbn', 'presentation', 0, 'itemid, filepath, filename', false);
    // Check that there is a file to process.
    if (count($files) == 1) {
        // Get the first (and only) file.
        $file = reset($files);
        // Set the presentation column in the bigbluebuttonbn table.
        $DB->set_field('bigbluebuttonbn', 'presentation', '/' . $file->get_filename(), array('id' => $bigbluebuttonbn_id));
    } else {
        // Set the presentation column in the bigbluebuttonbn table.
        $DB->set_field('bigbluebuttonbn', 'presentation', '', array('id' => $bigbluebuttonbn_id));
    }
}

/**
 * Serves the bigbluebuttonbn attachments. Implements needed access control ;-)
 *
 * @package mod_bigbluebuttonbn
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - justsend the file
 */
function bigbluebuttonbn_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $CFG, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    $fileareas = bigbluebuttonbn_get_file_areas();
    if (!array_key_exists($filearea, $fileareas)) {
        return false;
    }

    if (!$bigbluebuttonbn = $DB->get_record('bigbluebuttonbn', array('id'=>$cm->instance))) {
        return false;
    }

    if( sizeof($args) > 1 ) {
        $cache = cache::make_from_params(cache_store::MODE_APPLICATION, 'mod_bigbluebuttonbn', 'presentation_cache');
        $presentation_nonce_key = sha1($bigbluebuttonbn->id);
        $presentation_nonce = $cache->get($presentation_nonce_key);
        $presentation_nonce_value = $presentation_nonce['value'];
        $presentation_nonce_counter = $presentation_nonce['counter'];

        if( $args["0"] != $presentation_nonce_value ) {
            return false;
        }

        //The nonce value is actually used twice because BigBlueButton reads the file two times
        $presentation_nonce_counter += 1;
        if( $presentation_nonce_counter < 2 ) {
            $cache->set($presentation_nonce_key, array( "value" => $presentation_nonce_value, "counter" => $presentation_nonce_counter ));
        } else {
            $cache->delete($presentation_nonce_key);
        }

        $filename = $args["1"];

    } else {
        require_course_login($course, true, $cm);

        if (!has_capability('mod/bigbluebuttonbn:join', $context)) {
            return false;
        }

        $filename = implode('/', $args);
    }

    if ($filearea === 'presentation') {
        $fullpath = "/$context->id/mod_bigbluebuttonbn/$filearea/0/".$filename;
    } else {
        return false;
    }

    $fs = get_file_storage();
    if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
        return false;
    }

    // finally send the file
    send_stored_file($file, 0, 0, $forcedownload, $options); // download MUST be forced - security!
}

/**
 * Returns an array of file areas
 *
 * @package  mod_bigbluebuttonbn
 * @category files
 * @return array a list of available file areas
 */
function bigbluebuttonbn_get_file_areas() {
    $areas = array();
    $areas['presentation'] = get_string('mod_form_block_presentation', 'bigbluebuttonbn');

    return $areas;
}

/**
 * Returns an array with all the roles contained in the database
 *
 * @package  mod_bigbluebuttonbn
 * @return array a list of available roles
 */
function bigbluebuttonbn_get_db_moodle_roles($rolename='all') {
    global $DB;

    if( $rolename != 'all')
        $roles = $DB->get_record('role', array('shortname' => $rolename));
    else
        $roles = $DB->get_records('role', array());

    return $roles;
}

/**
 * Returns an array with all the users enrolled in a given course
 *
 * @package  mod_bigbluebuttonbn
 * @return array a list of enrolled users in the course
 */
function bigbluebuttonbn_get_users($context) {
    global $DB;

    $roles = bigbluebuttonbn_get_db_moodle_roles();
    $sqluserids = array();
    foreach($roles as $role){
        $users = get_role_users($role->id, $context);
        foreach($users as $user) {
            array_push($sqluserids, $user->id);
        }
    }

    $users_array = array();
    if( !empty($sqluserids) ) {
        $users_array = $DB->get_records_select("user", "id IN (" . implode(', ', $sqluserids) . ") AND deleted = 0");
    }

    return $users_array;
}

function bigbluebuttonbn_send_notification($sender, $bigbluebuttonbn, $message="") {
    global $CFG, $DB;

    $context = bigbluebuttonbn_get_context_course($bigbluebuttonbn->course);
    $course = $DB->get_record('course', array('id' => $bigbluebuttonbn->course), '*', MUST_EXIST);

    //Complete message
    $msg = new stdClass();
    $msg->user_name = fullname($sender);
    $msg->user_email = $sender->email;
    $msg->course_name = "$course->fullname";
    $message .= '<p><hr/><br/>'.get_string('email_footer_sent_by', 'bigbluebuttonbn').' '.$msg->user_name.'('.$msg->user_email.') ';
    $message .= get_string('email_footer_sent_from', 'bigbluebuttonbn').' '.$msg->course_name.'.</p>';

    $users = bigbluebuttonbn_get_users($context);
    foreach( $users as $user ) {
        if( $user->id != $sender->id ){
            $messageid = message_post_message($sender, $user, $message, FORMAT_HTML);
            if (!empty($messageid)) {
                error_log("Msg to ".$msg->user_name." was sent.");
            } else {
                error_log("Msg to ".$msg->user_name." was NOT sent.");
            }
        }
    }
}

function bigbluebuttonbn_get_context_module($id) {
    global $CFG;

    $version_major = bigbluebuttonbn_get_moodle_version_major();
    if ( $version_major < '2013111800' ) {
        //This is valid before v2.6
        $context = get_context_instance(CONTEXT_MODULE, $id);
    } else {
        //This is valid after v2.6
        $context = context_module::instance($id);
    }

    return $context;
}

function bigbluebuttonbn_get_context_course($id) {
    global $CFG;

    $version_major = bigbluebuttonbn_get_moodle_version_major();
    if ( $version_major < '2013111800' ) {
        //This is valid before v2.6
        $context = get_context_instance(CONTEXT_COURSE, $id);
    } else {
        //This is valid after v2.6
        $context = context_course::instance($id);
    }

    return $context;
}

function bigbluebuttonbn_get_cfg_server_url($meeting_id = null) {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    /*---- OpenStack integration ----*/
    if(bigbluebuttonbn_get_cfg_openstack_integration() && $meeting_id){
        return bigbluebuttonbn_get_meeting_server_url($meeting_id);
    /*---- end of OpenStack integration ---*/
    }else{
        return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url)? trim(trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url),'/').'/': (isset($CFG->bigbluebuttonbn_server_url)? trim(trim($CFG->bigbluebuttonbn_server_url),'/').'/': 'http://test-install.blindsidenetworks.com/bigbluebutton/'));
    }
}

function bigbluebuttonbn_get_cfg_shared_secret($meeting_id = null) {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    /*---- OpenStack integration ----*/
    if(bigbluebuttonbn_get_cfg_openstack_integration() && $meeting_id){
        return bigbluebuttonbn_get_meeting_shared_secret($meeting_id);
    /*---- end of OpenStack integration ---*/
    }else{
        return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret): (isset($CFG->bigbluebuttonbn_shared_secret)? trim($CFG->bigbluebuttonbn_shared_secret): '8cd8ef52e8e101574e400365b55e11a6'));
    }
}

/*---- OpenStack integration ----*/

function bigbluebuttonbn_get_cfg_recording_server_url($meeting_id = null) {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    /*---- OpenStack integration ----*/
    if(bigbluebuttonbn_get_cfg_openstack_integration() && $meeting_id && !bigbluebuttonbn_get_cfg_backup_recording()){
        return bigbluebuttonbn_get_meeting_server_url($meeting_id);
    /*---- end of OpenStack integration ---*/
    }else{
        return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url)? trim(trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url),'/').'/': (isset($CFG->bigbluebuttonbn_server_url)? trim(trim($CFG->bigbluebuttonbn_server_url),'/').'/': 'http://test-install.blindsidenetworks.com/bigbluebutton/'));
    }
}

function bigbluebuttonbn_get_cfg_recording_shared_secret($meeting_id = null) {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    /*---- OpenStack integration ----*/
    if(bigbluebuttonbn_get_cfg_openstack_integration() && $meeting_id && !bigbluebuttonbn_get_cfg_backup_recording()){
        return bigbluebuttonbn_get_meeting_shared_secret($meeting_id);
    /*---- end of OpenStack integration ---*/
    }else{
        return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret): (isset($CFG->bigbluebuttonbn_shared_secret)? trim($CFG->bigbluebuttonbn_shared_secret): '8cd8ef52e8e101574e400365b55e11a6'));
    }
}

function bigbluebuttonbn_get_meeting_course_name($course_id){
    global  $DB;
    return $DB->get_field('course','fullname',array('id'=>$course_id));
}
// Check if conference is duplicated
function bigbluebuttonbn_meeting_is_duplicated($meetingid){
    global  $DB;
    $meetingid = "'".$meetingid."'";
    $select = 'meetingid = '.$meetingid;
    return ( $DB->count_records_select('bigbluebuttonbn', $select) > 1 );
}

function bigbluebuttonbn_change_duplication($bbb_meeting){
    global $CFG, $DB;
    //Create new mettingid
    $bigbluebuttonbn_meetingid = sha1($CFG->wwwroot.$bbb_meeting->id.bigbluebuttonbn_get_cfg_shared_secret());
    //Update meetingid in original record
    $DB->set_field('bigbluebuttonbn', 'meetingid', $bigbluebuttonbn_meetingid, array('id' => $bbb_meeting->id));
    $moderatorpass = bigbluebuttonbn_random_password(12);
    $DB->set_field('bigbluebuttonbn', 'moderatorpass', $moderatorpass, array('id' =>$bbb_meeting->id));
    $viewerpass = bigbluebuttonbn_random_password(12);
    $DB->set_field('bigbluebuttonbn', 'viewerpass', $viewerpass, array('id' => $bbb_meeting->id));
}

//Get minimun time to schedule a meeting.
function bigbluebuttonbn_get_min_openingtime(){
    $time_dd_hh_mm = bigbluebuttonbn_get_cfg_min_openingtime();
    $time = explode('-', $time_dd_hh_mm);
    $time = preg_replace('/\D+/', "", $time);
    return ( $time[0] * 24 * 3600 + $time[1] * 3600 + $time[2] * 60 + time() );
}

//Get maximun anticipation time to schedule a meeting.
function bigbluebuttonbn_get_max_openingtime(){
    $time_dd_hh_mm = bigbluebuttonbn_get_cfg_max_openingtime();
    $time = explode('-', $time_dd_hh_mm);
    $time = preg_replace('/\D+/', "", $time);
    return ( $time[0] * 24 * 3600 + $time[1] * 3600 + $time[2] * 60 + time() );
}

function bigbluebuttonbn_create_or_update_os_conference($data){
    global $DB;
    $data->id = $DB->get_field('bigbluebuttonbn_openstack','id',array('meetingid'=>$data->meetingid));
    if ($data->id){//Update record
        $data->meetingid = $DB->get_field('bigbluebuttonbn_openstack','meetingid',array('id'=>$data->id));
        return $DB->update_record('bigbluebuttonbn_openstack', $data);
    }else{//Insert new record
        return $DB->insert_record('bigbluebuttonbn_openstack', $data);
    }
}

function bigbluebuttonbn_delete_os_conference($meetingid){
    global $DB;
    return $DB->delete_records('bigbluebuttonbn_openstack',array('meetingid'=>$meetingid));
}


function bigbluebuttonbn_get_os_stack_name($meetingid){
    global $DB;
    $stack_name = $DB->get_field('bigbluebuttonbn_openstack','stack_name',array('meetingid'=>$meetingid));
    return $stack_name ? $stack_name : 'UNSET' ;
}

function bigbluebuttonbn_get_openstack_meetingid_by_id($bbb_id){
    global $DB;
    return $DB->get_field('bigbluebuttonbn', 'meetingid', array('id'=>$bbb_id));
}

function bigbluebuttonbn_openstack_managed_conference($bigbluebuttonbn)
{
    global $DB;
    if (!$bigbluebuttonbn->meetingid) {
        $bigbluebuttonbn->meetingid = bigbluebuttonbn_get_openstack_meetingid_by_id($bigbluebuttonbn->id);
    }
    return $DB->record_exists('bigbluebuttonbn_openstack', array('meetingid' => $bigbluebuttonbn->meetingid));
}

//----Reservations
//Add or edit new reservation
function bigbluebuttonbn_create_or_update_bbb_servers_reservation($data){
    global $DB;
    if ($data->meetingid){//Update record
        $data->id = $DB->get_field('bigbluebuttonbn_reservations','id',array('meetingid'=>$data->meetingid));
        return $DB->update_record('bigbluebuttonbn_reservations', $data);
    }else{//Insert new record
        return $DB->insert_record('bigbluebuttonbn_reservations', $data);
    }
}

//Insert meeting id in reservation table
function bigbluebuttonbn_add_meetingid_to_reservation($data){
    global $DB;
    return $DB->update_record('bigbluebuttonbn_reservations',$data);
}

//Delete reservation
function bigbluebuttonbn_delete_reservation($meetingid){
    global $DB;
    return $DB->delete_records('bigbluebuttonbn_reservations',array('meetingid'=>$meetingid));
}


//----OS Logs
function bigbluebuttonbn_add_openstack_event($event_record){
    global $DB;
    return $DB->insert_record('bigbluebuttonbn_os_logs', $event_record);
}

//----Admin Settings

function bigbluebuttonbn_get_cfg_openstack_integration() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_integration)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_integration): (isset($CFG->bigbluebuttonbn_openstack_integration)? trim($CFG->bigbluebuttonbn_openstack_integration): 0));
}

function bigbluebuttonbn_get_cfg_use_backup_server() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_use_backup_server)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_use_backup_server): (isset($CFG->bigbluebuttonbn_openstack_use_backup_server)? trim($CFG->bigbluebuttonbn_openstack_use_backup_server): 0));
}

function bigbluebuttonbn_get_cfg_backup_recording() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_backup_recording)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_backup_recording): (isset($CFG->bigbluebuttonbn_openstack_backup_recording)? trim($CFG->bigbluebuttonbn_openstack_backup_recording): 0));
}

function bigbluebuttonbn_get_cfg_heat_url() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_url)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_url): (isset($CFG->bigbluebuttonbn_heat_url)? trim($CFG->bigbluebuttonbn_heat_url): null));
}

function bigbluebuttonbn_get_cfg_heat_region() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_region)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_region): (isset($CFG->bigbluebuttonbn_heat_region)? trim($CFG->bigbluebuttonbn_heat_region): null));
}

function bigbluebuttonbn_get_cfg_yaml_template_url(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_yaml_stack_template_url)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_yaml_stack_template_url): (isset($CFG->bigbluebuttonbn_yaml_stack_template_url)? trim($CFG->bigbluebuttonbn_yaml_stack_template_url): null));
}

function bigbluebuttonbn_get_cfg_json_stack_parameters_url() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_stack_parameters_url)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_stack_parameters_url): (isset($CFG->bigbluebuttonbn_json_stack_parameters_url)? trim($CFG->bigbluebuttonbn_json_stack_parameters_url): null));
}

function bigbluebuttonbn_get_cfg_json_meeting_durations() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_meeting_durations)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_meeting_durations): (isset($CFG->bigbluebuttonbn_json_meeting_durations)? trim($CFG->bigbluebuttonbn_json_meeting_durations): '[30,60,120]'));
}

function bigbluebuttonbn_get_cfg_conference_extra_time() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_conference_extra_time)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_conference_extra_time): (isset($CFG->bigbluebuttonbn_conference_extra_time)? trim($CFG->bigbluebuttonbn_conference_extra_time): 0));
}

function bigbluebuttonbn_get_cfg_openstack_destruction_time() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_destruction_time)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_destruction_time): (isset($CFG->bigbluebuttonbn_openstack_destruction_time)? trim($CFG->bigbluebuttonbn_openstack_destruction_time): 15));
}

function bigbluebuttonbn_get_cfg_min_openingtime() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_min_openingtime)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_min_openingtime): (isset($CFG->bigbluebuttonbn_min_openingtime)? trim($CFG->bigbluebuttonbn_min_openingtime): '0d-0h-1m'));
}

function bigbluebuttonbn_get_cfg_max_openingtime(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_max_openingtime)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_max_openingtime): (isset($CFG->bigbluebuttonbn_max_openingtime)? trim($CFG->bigbluebuttonbn_max_openingtime): '30d-0h-0m'));
}

function bigbluebuttonbn_get_cfg_openstack_username() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_username)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_username): (isset($CFG->bigbluebuttonbn_openstack_username)? trim($CFG->bigbluebuttonbn_openstack_username): null));
}

function bigbluebuttonbn_get_cfg_openstack_password() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_password)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_password): (isset($CFG->bigbluebuttonbn_openstack_password)? trim($CFG->bigbluebuttonbn_openstack_password): null));
}

function bigbluebuttonbn_get_cfg_openstack_tenant_id() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_tenant_id)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_tenant_id): (isset($CFG->bigbluebuttonbn_openstack_tenant_id)? trim($CFG->bigbluebuttonbn_openstack_tenant_id): null));
}

function bigbluebuttonbn_get_cfg_max_simultaneous_instances() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_max_simultaneous_instances)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_max_simultaneous_instances): (isset($CFG->bigbluebuttonbn_max_simultaneous_instances)? trim($CFG->bigbluebuttonbn_max_simultaneous_instances): null));
}

//----Reservations module
function bigbluebuttonbn_get_cfg_reservation_module_enabled(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_reservation_module_enabled)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_reservation_module_enabled): (isset($CFG->bigbluebuttonbn_reservation_module_enabled)? trim($CFG->bigbluebuttonbn_reservation_module_enabled): 0));
}

function bigbluebuttonbn_get_cfg_reservation_users_list_logic(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_reservation_user_list_logic)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_reservation_user_list_logic): (isset($CFG->bigbluebuttonbn_reservation_user_list_logic)? trim($CFG->bigbluebuttonbn_reservation_user_list_logic): 1));
}

function bigbluebuttonbn_get_cfg_authorized_reservation_users_list() {
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_authorized_reservation_users_list)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_authorized_reservation_users_list): (isset($CFG->bigbluebuttonbn_authorized_reservation_users_list)? trim($CFG->bigbluebuttonbn_authorized_reservation_users_list): null));
}

//----Notifications module

function bigbluebuttonbn_get_cfg_bigbluebuttonbn_connection_error_users_list_enabled(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return(isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_connection_error_users_list_enabled)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_connection_error_users_list_enabled): (isset($CFG->bigbluebuttonbn_connection_error_users_list_enabled)? trim($CFG->bigbluebuttonbn_connection_error_users_list_enabled): 0));
}
function bigbluebuttonbn_get_cfg_openstack_connection_error_email_users_list(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return(isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_connection_error_email_users_list)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_connection_error_email_users_list): (isset($CFG->bigbluebuttonbn_openstack_connection_error_email_users_list)? trim($CFG->bigbluebuttonbn_openstack_connection_error_email_users_list): null));
}
function bigbluebuttonbn_get_cfg_bigbluebuttonbn_task_error_users_list_enabled(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return(isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_task_error_users_list_enabled)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_task_error_users_list_enabled): (isset($CFG->bigbluebuttonbn_task_error_users_list_enabled)? trim($CFG->bigbluebuttonbn_task_error_users_list_enabled): 0));
}
function bigbluebuttonbn_get_cfg_openstack_task_error_email_users_list(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return(isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_task_error_email_users_list)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_task_error_email_users_list): (isset($CFG->bigbluebuttonbn_openstack_task_error_email_users_list)? trim($CFG->bigbluebuttonbn_openstack_task_error_email_users_list): null));
}

//----Resiliency module
function bigbluebuttonbn_get_cfg_resiliency_module_enabled(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_resiliency_module_enabled)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_resiliency_module_enabled): (isset($CFG->bigbluebuttonbn_resiliency_module_enabled)? trim($CFG->bigbluebuttonbn_resiliency_module_enabled): 0));
}

function bigbluebuttonbn_get_cfg_creation_retries_number(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_creation_retries_number)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_creation_retries_number): (isset($CFG->bigbluebuttonbn_creation_retries_number)? trim($CFG->bigbluebuttonbn_creation_retries_number): 0));
}

function bigbluebuttonbn_get_cfg_deletion_retries_number(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_deletion_retries_number)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_deletion_retries_number): (isset($CFG->bigbluebuttonbn_deletion_retries_number)? trim($CFG->bigbluebuttonbn_deletion_retries_number): 0));
}

function bigbluebuttonbn_get_cfg_error_log_file_enabled(){
    global $BIGBLUEBUTTONBN_CFG, $CFG;
    return (isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_error_log_file_enabled)? trim($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_error_log_file_enabled): (isset($CFG->bigbluebuttonbn_error_log_file_enabled)? trim($CFG->bigbluebuttonbn_error_log_file_enabled): 0));
}

/*---- end of Openstack integration ---- */
