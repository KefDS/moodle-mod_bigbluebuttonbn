<?php
/**
 * View for BigBlueButton interaction  
 *
 * @package   mod_bigbluebuttonbn
 * @author    Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 * @copyright 2015 Blindside Networks Inc.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v2 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$bn = required_param('bn', PARAM_INT);  // bigbluebuttonbn instance ID
$tc = optional_param('tc', 0, PARAM_INT);  // target course ID

if ($bn) {
    $bigbluebuttonbn = $DB->get_record('bigbluebuttonbn', array('id' => $bn), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $bigbluebuttonbn->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('bigbluebuttonbn', $bigbluebuttonbn->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('view_error_url_missing_parameters', 'bigbluebuttonbn'));
}

$context = bigbluebuttonbn_get_context_module($cm->id);

if ( isset($SESSION) && isset($SESSION->bigbluebuttonbn_bbbsession)) {
    require_login($course, true, $cm);
    $bbbsession = $SESSION->bigbluebuttonbn_bbbsession;
}

/// Print the page header
$PAGE->set_context($context);
$PAGE->set_url('/mod/bigbluebuttonbn/import_view.php', array('id' => $cm->id, 'bigbluebuttonbn' => $bigbluebuttonbn->id));
$PAGE->set_title(format_string($bigbluebuttonbn->name));
$PAGE->set_cacheable(false);
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

// Create view object which collects all the information the renderer will need.
//$viewobj = new mod_bigbluebuttonbn_view_object();

$output = $PAGE->get_renderer('mod_bigbluebuttonbn');

echo $OUTPUT->header();

//echo $output->view_page($course, $bigbluebuttonbn, $cm, $context, $viewobj);

$output = '';

$output .= '<h4>Import recording links</h4>';

$options = bigbluebuttonbn_import_get_courses_for_select($bbbsession);
$selected = bigbluebuttonbn_selected_course($options, $tc);
if( empty($options) ) {
    $output .= html_writer::tag('div', get_string('view_error_import_no_courses', 'bigbluebuttonbn'));

} else {
    //$output .= html_writer::select($options, $name, $selected, true, $attributes);
    $output .= html_writer::tag('div', html_writer::select($options, 'import_recording_links_select', $selected, true));

    $recordings = bigbluebuttonbn_getRecordingsArrayByCourse($selected, $bbbsession['endpoint'], $bbbsession['shared_secret']);
    //exclude the ones that are already imported
    $recordings = bigbluebuttonbn_import_exlcude_recordings_already_imported($selected, $recordings);
    if( empty($recordings) ) {
        $output .= html_writer::tag('div', get_string('view_error_import_no_recordings', 'bigbluebuttonbn'));
    } else {
        $output .= html_writer::tag('span', '', ['id' => 'import_recording_links_table' ,'name'=>'import_recording_links_table']);
        $output .= bigbluebutton_output_recording_table($bbbsession, $recordings, ['importing']);
    }

    $jsvars = array(
            'bn' => $bn,
            'tc' => $selected
    );
    $PAGE->requires->data_for_js('bigbluebuttonbn', $jsvars);

    $jsmodule = array(
            'name'     => 'mod_bigbluebuttonbn',
            'fullpath' => '/mod/bigbluebuttonbn/import_module.js',
            'requires' => array('datasource-get', 'datasource-jsonschema', 'datasource-polling'),
    );
    $PAGE->requires->js_init_call('M.mod_bigbluebuttonbn.import_view_init', array(), false, $jsmodule);
}

echo $output;


echo $OUTPUT->footer();

function bigbluebuttonbn_selected_course($options, $tc=0) {
    if( empty($options) ) {
        $selected = null;
    } else if(array_key_exists($tc, $options)) {
        $selected = $tc;
    } else {
        $selected = array_keys($options)[0];
    }
    return $selected;
}