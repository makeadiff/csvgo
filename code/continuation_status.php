<?php
$all_groups = $sql->getById("SELECT id,name FROM `Group` WHERE status='1'");

$are_you_continuting_question_id = 629;
$continuing_choice_id = 998;
$not_continuing_choice_id = 999;

$query = "SELECT U.id,U.name, U.email, C.name AS city, 'No' AS filled,
		(SELECT survey_choice_id FROM Survey_Response SR WHERE responder_id = U.id AND survey_question_id = $are_you_continuting_question_id LIMIT 0,1) AS continuing,
		(SELECT group_id FROM FAM_UserGroupPreference WHERE user_id = U.id AND year = $year AND preference = 1 LIMIT 0,1) AS first_preference,
		(SELECT group_id FROM FAM_UserGroupPreference WHERE user_id = U.id AND year = $year AND preference = 2 LIMIT 0,1) AS second_preference,
		(SELECT added_on FROM FAM_UserGroupPreference WHERE user_id = U.id AND year = $year AND preference = 1 LIMIT 0,1) AS added_on,
		(SELECT G.name FROM `Group` G INNER JOIN UserGroup UG ON UG.group_id = G.id WHERE UG.year = $year AND UG.user_id=U.id AND UG.main = '1') AS main_role
	FROM User U 
	INNER JOIN City C ON C.id=U.city_id
	WHERE U.user_type='volunteer' AND U.status='1'
	ORDER BY C.name, U.name";

$data = $sql->getAll($query);

header("Content-type: text/plain;");
foreach($data as $i => $row) {
	$row['filled'] = 'No';
	if($row['continuing']) {
		$row['filled'] = 'Yes';
		if($row['continuing'] == $continuing_choice_id) $row['continuing'] = 'Yes';
		else $row['continuing'] = 'No';
	}

	if($row['first_preference']) $row['first_preference'] = $all_groups[$row['first_preference']];
	if($row['second_preference']) $row['second_preference'] = $all_groups[$row['second_preference']];

	$data[$i] = $row;
}
 
