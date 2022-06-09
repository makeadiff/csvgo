<?php
$all_groups = $sql->getById("SELECT id,name FROM `Group` WHERE status='1'");

$are_you_continuting_question_id = 629;
$continuing_choice_id = 998;
$not_continuing_choice_id = 999;
$survey_event_id = 154;

$query = "SELECT U.id,U.name, U.email, C.name AS city, 'No' AS filled,

		(SELECT added_on FROM Survey_Response WHERE responder_id = U.id AND survey_question_id = $are_you_continuting_question_id LIMIT 0,1) AS added_on,
		(SELECT survey_choice_id FROM Survey_Response SR WHERE responder_id = U.id AND survey_question_id = $are_you_continuting_question_id LIMIT 0,1) AS continuing,
		(SELECT G.name FROM `Group` G INNER JOIN UserGroup UG ON UG.group_id = G.id WHERE UG.year = $year AND UG.user_id=U.id AND UG.main = '1') AS main_role,

		(SELECT SC.name FROM Survey_Response SR INNER JOIN Survey_Choice SC ON SR.survey_choice_id = SC.id 
			WHERE responder_id = U.id AND SR.survey_question_id = 661 LIMIT 0,1) AS upcoming_city,
		(SELECT response FROM Survey_Response SR WHERE responder_id = U.id AND SR.survey_question_id = 662 LIMIT 0,1) AS location,
		(SELECT SC.name FROM Survey_Response SR INNER JOIN Survey_Choice SC ON SR.survey_choice_id = SC.id 
			WHERE responder_id = U.id AND SR.survey_question_id = 663 LIMIT 0,1) AS weekday_availability,
		(SELECT SC.name FROM Survey_Response SR INNER JOIN Survey_Choice SC ON SR.survey_choice_id = SC.id 
			WHERE responder_id = U.id AND SR.survey_question_id = 664 LIMIT 0,1) AS weekend_availability,
		(SELECT data from UserData WHERE user_id = U.id AND name='reason_to_discontinue_2021') AS discontinue_reason
		
	FROM User U 
	INNER JOIN City C ON C.id=U.city_id
	WHERE U.user_type='volunteer' AND U.status='1'
	ORDER BY C.name, U.name";

$data = $sql->getAll($query);

foreach($data as $i => $row) {
	$row['filled'] = 'No';
	if($row['continuing']) {
		$row['filled'] = 'Yes';
		if($row['continuing'] == $continuing_choice_id) $row['continuing'] = 'Yes';
		else $row['continuing'] = 'No';
	}
	$row['discontinue_reason'] = str_replace(['::', '\\'], '', $row['discontinue_reason']);

	$data[$i] = $row;
}
 
