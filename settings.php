<?php
/**
 * Settings for BigBlueButtonBN
 *
 * @package   mod_bigbluebuttonbn
 * @author    Fred Dixon  (ffdixon [at] blindsidenetworks [dt] com)
 * @author    Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 * @copyright 2010-2015 Blindside Networks Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

defined('MOODLE_INTERNAL') || die;

global $BIGBLUEBUTTONBN_CFG;

require_once(dirname(__FILE__).'/locallib.php');


/*---- OpenStack integration ----*/
//Regex values used for validation
$bbb_server_regex= '/^\S{0,60}\/bigbluebutton\/$/'; //Validates BBB server instance
$heat_url_regex='/^\S{0,60}5000\/v2.0$/'; //Validates API version
$small_length_string_regex= '/^\S{0,15}$/';
$medium_length_string_regex= '/^\S{0,40}$/';
$json_object_regex= '/^.{0,60}$/';
$json_array_regex='/\[\d+(,\d+)*\]$/';
/*---- end of OpenStack integration ----*/

if ($ADMIN->fulltree) {

    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_integration) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_config_general',
                get_string('config_general', 'bigbluebuttonbn'),
                get_string('config_general_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_server_url) ) {
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_server_url',
                    get_string( 'config_server_url', 'bigbluebuttonbn' ),
                    get_string( 'config_server_url_description', 'bigbluebuttonbn' ),
                    bigbluebuttonbn_get_cfg_server_url_default(), $bbb_server_regex));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_shared_secret) ) {
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_shared_secret',
                    get_string( 'config_shared_secret', 'bigbluebuttonbn' ),
                    get_string( 'config_shared_secret_description', 'bigbluebuttonbn' ),
                    bigbluebuttonbn_get_cfg_shared_secret_default(), $medium_length_string_regex));
        }

        //---- OpenStack integration ----
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_integration) ){
            //Enable OpenStack integration
            $settings->add( new admin_setting_configcheckbox( 'bigbluebuttonbn_openstack_integration',
                get_string('config_openstack_integration', 'bigbluebuttonbn'),
                get_string('config_openstack_integration_description','bigbluebuttonbn'),
                0));
        }
        //---- end of OpenStack integration ----
    }

    //---- OpenStack integration ----
    ////Configurations for stacks and OpenStack API
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_url) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_region) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_stack_parameters) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebutton_json_meeting_durations)) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_config_cloud',
            get_string('config_cloud', 'bigbluebuttonbn'),
            get_string('config_cloud_description', 'bigbluebuttonbn'),
            null));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_url) ){
            //URL of server with Heat endpoint
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_heat_url',
                get_string( 'config_heat_url', 'bigbluebuttonbn' ),
                get_string( 'config_heat_url_description', 'bigbluebuttonbn' ),
                null, $heat_url_regex));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_heat_region) ){
            //Region of Heat service
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_heat_region',
                get_string( 'config_heat_region', 'bigbluebuttonbn' ),
                get_string( 'config_heat_region_description', 'bigbluebuttonbn' ),
                null, $small_length_string_regex));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_meeting_durations)){
            //Meeting durations
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_json_meeting_durations',
                get_string('config_json_meeting_durations', 'bigbluebuttonbn'),
                get_string('config_json_meeting_durations_description','bigbluebuttonbn'),
                null, $json_array_regex));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_json_stack_parameters)){
            //Parameters for stack creation in JSON representation
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_json_stack_parameters',
                get_string( 'config_json_stack_parameters', 'bigbluebuttonbn' ),
                get_string( 'config_json_stack_parameters_description', 'bigbluebuttonbn' ),
                null,$json_object_regex));
        }
    }

    ////Configurations for OpenStack authentication
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_username)||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_password)||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_tenant_id)){
        $settings->add( new admin_setting_heading( 'bigbluebuttonbn_openstack_credentials',
            get_string( 'config_openstack_credentials', 'bigbluebuttonbn' ),
            get_string( 'config_openstack_credentials_description', 'bigbluebuttonbn' ),
            null));

        if(!isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_username)){
            //OpenStack username
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_openstack_username',
                get_string( 'config_openstack_username', 'bigbluebuttonbn' ),
                get_string( 'config_openstack_username_description', 'bigbluebuttonbn' ),
                null, $small_length_string_regex));
        }
        if(!isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_password)){
            $settings->add( new admin_setting_configpasswordunmask('bigbluebuttonbn_openstack_password',
                get_string( 'config_openstack_password', 'bigbluebuttonbn' ),
                get_string( 'config_openstack_password_description', 'bigbluebuttonbn' ),
                null, $small_length_string_regex));
        }
        if(!isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_openstack_tenant_id)){
            $settings->add( new admin_setting_configtext( 'bigbluebuttonbn_openstack_tenant_id',
                get_string( 'config_openstack_tenant_id', 'bigbluebuttonbn' ),
                get_string( 'config_openstack_tenant_id_description', 'bigbluebuttonbn' ),
                null, $medium_length_string_regex));
        }
    }

    //---- end of OpenStack integration ----

    //// Configuration for 'recording' feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_default) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_editable) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_icons_enabled) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_recording',
                get_string('config_feature_recording', 'bigbluebuttonbn'),
                get_string('config_feature_recording_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_default) ) {
            // default value for 'recording' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recording_default',
                    get_string('config_feature_recording_default', 'bigbluebuttonbn'),
                    get_string('config_feature_recording_default_description', 'bigbluebuttonbn'),
                    1));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_editable) ) {
            // UI for 'recording' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recording_editable',
                    get_string('config_feature_recording_editable', 'bigbluebuttonbn'),
                    get_string('config_feature_recording_editable_description', 'bigbluebuttonbn'),
                    1));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recording_icons_enabled) ) {
            // Front panel for 'recording' managment feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recording_icons_enabled',
                    get_string('config_feature_recording_icons_enabled', 'bigbluebuttonbn'),
                    get_string('config_feature_recording_icons_enabled_description', 'bigbluebuttonbn'),
                    1));
        }
    }

    //// Configuration for 'recording tagging' feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recordingtagging_default) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recordingtagging_editable) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_recordingtagging',
                get_string('config_feature_recordingtagging', 'bigbluebuttonbn'),
                get_string('config_feature_recordingtagging_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recordingtagging_default) ) {
            // default value for 'recording tagging' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recordingtagging_default',
                    get_string('config_feature_recordingtagging_default', 'bigbluebuttonbn'),
                    get_string('config_feature_recordingtagging_default_description', 'bigbluebuttonbn'),
                    0));
        }
        // UI for 'recording tagging' feature
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recordingtagging_editable) ) {
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recordingtagging_editable',
                    get_string('config_feature_recordingtagging_editable', 'bigbluebuttonbn'),
                    get_string('config_feature_recordingtagging_editable_description', 'bigbluebuttonbn'),
                    1));
        }
    }

    //// Configuration for 'import recordings' feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_importrecordings_enabled) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_importrecordings_from_deleted_activities_enabled) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_importrecordings',
                get_string('config_feature_importrecordings', 'bigbluebuttonbn'),
                get_string('config_feature_importrecordings_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_importrecordings_enabled) ) {
            // default value for 'import recordings' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_importrecordings_enabled',
                    get_string('config_feature_importrecordings_enabled', 'bigbluebuttonbn'),
                    get_string('config_feature_importrecordings_enabled_description', 'bigbluebuttonbn'),
                    0));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_importrecordings_from_deleted_activities_enabled) ) {
            // consider deleted activities for 'import recordings' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_importrecordings_from_deleted_activities_enabled',
                    get_string('config_feature_importrecordings_from_deleted_activities_enabled', 'bigbluebuttonbn'),
                    get_string('config_feature_importrecordings_from_deleted_activities_enabled_description', 'bigbluebuttonbn'),
                    0));
        }
    }

    //// Configuration for wait for moderator feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_default) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_editable) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_ping_interval) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_cache_ttl) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_feature_waitformoderator',
                get_string('config_feature_waitformoderator', 'bigbluebuttonbn'),
                get_string('config_feature_waitformoderator_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_default) ) {
            //default value for 'wait for moderator' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_waitformoderator_default',
                    get_string('config_feature_waitformoderator_default', 'bigbluebuttonbn'),
                    get_string('config_feature_waitformoderator_default_description', 'bigbluebuttonbn'),
                    0));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_editable) ) {
            // UI for 'wait for moderator' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_waitformoderator_editable',
                    get_string('config_feature_waitformoderator_editable', 'bigbluebuttonbn'),
                    get_string('config_feature_waitformoderator_editable_description', 'bigbluebuttonbn'),
                    1));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_ping_interval) ) {
            //ping interval value for 'wait for moderator' feature
            $settings->add(new admin_setting_configtext('bigbluebuttonbn_waitformoderator_ping_interval',
                    get_string('config_feature_waitformoderator_ping_interval', 'bigbluebuttonbn'),
                    get_string('config_feature_waitformoderator_ping_interval_description', 'bigbluebuttonbn'),
                    10, PARAM_INT));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_waitformoderator_cache_ttl) ) {
            //cache TTL value for 'wait for moderator' feature
            $settings->add(new admin_setting_configtext('bigbluebuttonbn_waitformoderator_cache_ttl',
                    get_string('config_feature_waitformoderator_cache_ttl', 'bigbluebuttonbn'),
                    get_string('config_feature_waitformoderator_cache_ttl_description', 'bigbluebuttonbn'),
                    60, PARAM_INT));
        }
    }

    //// Configuration for "static voice bridge" feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_voicebridge_editable) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_feature_voicebridge',
                get_string('config_feature_voicebridge', 'bigbluebuttonbn'),
                get_string('config_feature_voicebridge_description', 'bigbluebuttonbn')));

        // UI for establishing static voicebridge
        $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_voicebridge_editable',
                get_string('config_feature_voicebridge_editable', 'bigbluebuttonbn'),
                get_string('config_feature_voicebridge_editable_description', 'bigbluebuttonbn'),
                0));
    }

    //// Configuration for "preupload presentation" feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_preuploadpresentation_enabled) ) {
        // This feature only works if curl is installed
        if (extension_loaded('curl')) {
            $settings->add( new admin_setting_heading('bigbluebuttonbn_feature_preuploadpresentation',
                get_string('config_feature_preuploadpresentation', 'bigbluebuttonbn'),
                get_string('config_feature_preuploadpresentation_description', 'bigbluebuttonbn')
                ));

            // UI for 'preupload presentation' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_preuploadpresentation_enabled',
                get_string('config_feature_preuploadpresentation_enabled', 'bigbluebuttonbn'),
                get_string('config_feature_preuploadpresentation_enabled_description', 'bigbluebuttonbn'),
                0));
        } else {
            $settings->add( new admin_setting_heading('bigbluebuttonbn_feature_preuploadpresentation',
                get_string('config_feature_preuploadpresentation', 'bigbluebuttonbn'),
                get_string('config_feature_preuploadpresentation_description', 'bigbluebuttonbn').'<br><br>'.
                '<div class="form-defaultinfo">'.get_string('config_warning_curl_not_installed', 'bigbluebuttonbn').'</div><br>'
                ));
        }
    }

    //// Configuration for "user limit" feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_userlimit_default) ||
        !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_userlimit_editable) ) {
        $settings->add( new admin_setting_heading('config_userlimit',
                get_string('config_feature_userlimit', 'bigbluebuttonbn'),
                get_string('config_feature_userlimit_description', 'bigbluebuttonbn')));

        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_userlimit_default) ) {
            //default value for 'user limit' feature
            $settings->add(new admin_setting_configtext('bigbluebuttonbn_userlimit_default',
                    get_string('config_feature_userlimit_default', 'bigbluebuttonbn'),
                    get_string('config_feature_userlimit_default_description', 'bigbluebuttonbn'),
                    0, PARAM_INT));
        }
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_userlimit_editable) ) {
            // UI for 'user limit' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_userlimit_editable',
                    get_string('config_feature_userlimit_editable', 'bigbluebuttonbn'),
                    get_string('config_feature_userlimit_editable_description', 'bigbluebuttonbn'),
                    0));
        }
    }

    //// Configuration for "scheduled duration" feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_scheduled_duration_enabled) ) {
      $settings->add( new admin_setting_heading('config_scheduled',
              get_string('config_scheduled', 'bigbluebuttonbn'),
              get_string('config_scheduled_description', 'bigbluebuttonbn')));

      // calculated duration for 'scheduled session' feature
      $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_scheduled_duration_enabled',
              get_string('config_scheduled_duration_enabled', 'bigbluebuttonbn'),
              get_string('config_scheduled_duration_enabled_description', 'bigbluebuttonbn'),
              1));

      // compensatory time for 'scheduled session' feature
      $settings->add(new admin_setting_configtext('bigbluebuttonbn_scheduled_duration_compensation',
              get_string('config_scheduled_duration_compensation', 'bigbluebuttonbn'),
              get_string('config_scheduled_duration_compensation_description', 'bigbluebuttonbn'),
              10, PARAM_INT));

      // pre-opening time for 'scheduled session' feature
      $settings->add(new admin_setting_configtext('bigbluebuttonbn_scheduled_pre_opening',
              get_string('config_scheduled_pre_opening', 'bigbluebuttonbn'),
              get_string('config_scheduled_pre_opening_description', 'bigbluebuttonbn'),
              10, PARAM_INT));
    }

    //// Configuration for defining the default role/user that will be moderator on new activities
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_moderator_default) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_permission',
                get_string('config_permission', 'bigbluebuttonbn'),
                get_string('config_permission_description', 'bigbluebuttonbn')));

        // UI for 'permissions' feature
        $roles = bigbluebuttonbn_get_roles('all', 'array');
        $owner = array('owner' => get_string('mod_form_field_participant_list_type_owner', 'bigbluebuttonbn'));
        $settings->add(new admin_setting_configmultiselect('bigbluebuttonbn_moderator_default',
                get_string('config_permission_moderator_default', 'bigbluebuttonbn'),
                get_string('config_permission_moderator_default_description', 'bigbluebuttonbn'),
                array_keys($owner), array_merge($owner, $roles)));
    }

    //// Configuration for "send notifications" feature
    if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_sendnotifications_enabled) ) {
        $settings->add( new admin_setting_heading('bigbluebuttonbn_feature_sendnotifications',
                get_string('config_feature_sendnotifications', 'bigbluebuttonbn'),
                get_string('config_feature_sendnotifications_description', 'bigbluebuttonbn')));

        // UI for 'send notifications' feature
        $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_sendnotifications_enabled',
                get_string('config_feature_sendnotifications_enabled', 'bigbluebuttonbn'),
                get_string('config_feature_sendnotifications_enabled_description', 'bigbluebuttonbn'),
                1));
    }

    //// Configuration for extended BN capabilities
    if( bigbluebuttonbn_server_offers_bn_capabilities() ) {
        //// Configuration for 'notify users when recording ready' feature
        if( !isset($BIGBLUEBUTTONBN_CFG->bigbluebuttonbn_recordingready_enabled) ) {
            $settings->add( new admin_setting_heading('bigbluebuttonbn_extended_capabilities',
                    get_string('config_extended_capabilities', 'bigbluebuttonbn'),
                    get_string('config_extended_capabilities_description', 'bigbluebuttonbn')));

            // UI for 'notify users when recording ready' feature
            $settings->add(new admin_setting_configcheckbox('bigbluebuttonbn_recordingready_enabled',
                    get_string('config_extended_feature_recordingready_enabled', 'bigbluebuttonbn'),
                    get_string('config_extended_feature_recordingready_enabled_description', 'bigbluebuttonbn'),
                    0));
        }
    }
}
