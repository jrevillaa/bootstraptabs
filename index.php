<?php

include('../../../config.php');

$url = new moodle_url('/course/format/bootstraptabs/index.php');

$PAGE->set_url($url);

require_login();

$context = context_system::instance();

$name = get_string('pluginname','format_bootstraptabs');



$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);


echo $OUTPUT->header();

$newlink = new moodle_url('/local/links/edit.php',array('id'=>0));
$newtype = new moodle_url('/local/links/type_edit.php',array('id'=>0));

echo html_writer::start_tag('div', array('id'=>'add-new'));
echo html_writer::end_tag('div');

echo html_writer::start_tag('table',array('align'=>'center'));
	echo html_writer::start_tag('thead');
		echo html_writer::start_tag('tr');

			echo html_writer::tag('td','Compañeros de Clase');
		echo html_writer::end_tag('tr');
	echo html_writer::end_tag('thead');

		global $USER;
        echo '<pre>';
        echo print_r($USER->profile['Empresa']);
        echo '</pre>';


echo html_writer::end_tag('table');

echo $OUTPUT->footer();	