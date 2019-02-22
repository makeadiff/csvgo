<?php
$all_cities = $sql->getById("SELECT id,name FROM City WHERE type='actual'");
$all_verticals = $sql->getById("SELECT id,name FROM Survey_Choice WHERE survey_question_id IN (206,207,208)");
$all_verticals[0] = 'None';
$all_traits = $sql->getById("SELECT id,name FROM Survey_Choice WHERE survey_question_id=205");
$survey_id = 6;

$data = $sql->getAll("SELECT U.id,U.name,U.email,U.phone 
	FROM User U 
	INNER JOIN Survey_Response R ON R.responder_id=U.id 
	WHERE R.survey_id=$survey_id 
	GROUP BY responder_id
	ORDER BY MAX(R.added_on)");

foreach ($data as $index => $row) {
	$responses = $sql->getAll("SELECT R.survey_question_id, R.survey_choice_id, R.response
		 FROM Survey_Response R WHERE responder_id=$row[id] AND survey_id=$survey_id");
	$row['first_choice'] = 'None';
	$row['second_choice'] = 'None';
	$row['third_choice'] = 'None';
	$row['mentoring_and_supporting'] = 'No';
	$row['designing_and_implementing'] = 'No';
	$row['self_motivated'] = 'No';
	$row['sufficient_time'] = 'No';
	$row['works_on_my_own'] = 'No';
	$row['perspective_and_passion'] = 'No';

	foreach ($responses as $res) {
		if($res['survey_question_id'] == '206') $row['first_choice'] = $all_verticals[$res['survey_choice_id']];
		elseif($res['survey_question_id'] == '207') $row['second_choice'] = $all_verticals[$res['survey_choice_id']];
		elseif($res['survey_question_id'] == '208') $row['third_choice'] = $all_verticals[$res['survey_choice_id']];
		elseif($res['survey_question_id'] == '205') {
			if($res['survey_choice_id'] == 22) $row['mentoring_and_supporting'] = 'Yes';
			elseif($res['survey_choice_id'] == 23) $row['designing_and_implementing'] = 'Yes';
			elseif($res['survey_choice_id'] == 24) $row['self_motivated'] = 'Yes';
			elseif($res['survey_choice_id'] == 25) $row['sufficient_time'] = 'Yes';
			elseif($res['survey_choice_id'] == 26) $row['works_on_my_own'] = 'Yes';
			elseif($res['survey_choice_id'] == 79) $row['perspective_and_passion'] = 'Yes';
		}
	}
	
 	$data[$index] = $row;
}