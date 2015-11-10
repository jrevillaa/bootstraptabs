<?php

include('../../../config.php');

$PAGE->requires->js('/course/format/bootstraptabs/js/Chart.js');

$id = required_param('id',PARAM_INT);

$url = new moodle_url('/course/format/bootstraptabs/index.php',array('id'=>$id));

$PAGE->set_url($url);

$PAGE->set_url($url);

require_login();

$context = context_system::instance();

$name = get_string('pluginname','format_bootstraptabs');


$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);


echo $OUTPUT->header();



		global $USER, $DB;
		$keys = array_keys($USER->profile);

		$list = explode(PHP_EOL,$DB->get_record('user_info_field',  array('categoryid' =>  1))->param1);

		$total_alumnos = 0;
		$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    	

		echo '<div id="canvas-holder">';
		echo '<canvas id="chart-area" width="450" height="450"/>';
		echo '</div>';
		//echo '<br>total de alumnos = ' . $total_alumnos;

    	
    	//tabala leyenda
    	$leyenda = '';
    	$leyenda .= html_writer::start_tag('ul', array('class'=>'leyenda'));

		foreach ($list as $value) {
			
			$sql_group = "SELECT  mdl_user.username, mdl_user_info_data.data FROM mdl_course 
			INNER JOIN mdl_context ON mdl_context.instanceid = mdl_course.id 
			INNER JOIN mdl_role_assignments ON mdl_context.id = mdl_role_assignments.contextid 
			INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid 
			INNER JOIN mdl_user ON mdl_user.id = mdl_role_assignments.userid 
			INNER JOIN mdl_user_info_data ON mdl_user_info_data.userid = mdl_role_assignments.userid 
			WHERE mdl_role.id = 5 AND mdl_course.id = $id AND mdl_user_info_data.data = '$value'" ;

			$data = $DB->get_records_sql($sql_group);
			$temp = $DB->get_record('links_entry',  array('name' => $value));
			//print_r($temp);
			if(is_object($temp)){
				$info[$value] = array(count($data), $temp->colorhead);
			}else{
				$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
				$info[$value] =  array(count($data), $color);	
			}
				
			$leyenda .= html_writer::start_tag('li');

			$leyenda .= html_writer::tag('span', '', array('class'=>'color', 'style'=>'background:'.$info[$value][1].';'));
			$leyenda .= html_writer::tag('span', $value . ': ' . count($data) , array('class'=>'name'));

			$leyenda .= html_writer::end_tag('li');

				//echo $value . ' = ' . count($data) . ' ' . $info[$value][1] . '<br>';
				$total_alumnos += count($data);
			

		}

		$leyenda .= html_writer::start_tag('li', array('class'=>'total-alumnos'));
		$leyenda .= html_writer::tag('span', 'TOTAL: ' . $total_alumnos, array('class'=>'name bold'));
		$leyenda .= html_writer::end_tag('li');

		$leyenda .= html_writer::end_tag('ul');

		echo $leyenda;

        //echo '<pre>';
        //echo print_r(array_keys($USER->profile));

        //echo print_r($list);

        //echo print_r($DB->get_records_sql($sql_group));
        //echo '</pre>';

        

		echo '<script>';
		echo 'var pieData = [';
		foreach ($info as $key => $value) {
			if($value[0] > 0){
				echo '{';
				echo 'value:' . $value[0] . ',';
				echo 'color:"' . $value[1] . '",';
				echo 'highlight: "#FF5A5E",';
				echo 'label: "' . $key . '"';
				echo '},';
			}
		}
		echo '];';
		echo 'window.onload = function(){';
		echo 'var ctx = document.getElementById("chart-area").getContext("2d");';
		echo 'window.myPie = new Chart(ctx).Doughnut(pieData);';
		echo '};';
		echo '</script>';

echo $OUTPUT->footer();	