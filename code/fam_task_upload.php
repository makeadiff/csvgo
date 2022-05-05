<?php
$year = 2021; // :HARDCODE: Should be ($year - 1)

$common = new Common;
// $all_cities = idNameFormat($common->getCities());
// $groups = idNameFormat($common->getGroups(['type' => 'fellow']));

$applicants = $sql->getAll("SELECT U.id, U.name, U.email, U.phone, G.name AS last_year_role, C.name AS city, FG.name as first_preference
	FROM User U 
	INNER JOIN City C ON C.id=U.city_id
	INNER JOIN FAM_UserGroupPreference FUGP ON FUGP.user_id=U.id AND FUGP.year=2021 AND FUGP.status='pending' AND FUGP.preference=1
	INNER JOIN `Group` FG ON FG.id = FUGP.group_id
	LEFT JOIN UserGroup UG ON UG.user_id=U.id AND UG.year = 2021 AND UG.main='1' 
	LEFT JOIN `Group` G ON UG.group_id=G.id
	WHERE U.status='1' AND U.user_type IN ('volunteer', 'alumni')
	GROUP BY U.id
	ORDER BY C.name, U.name");

$tasks = $sql->getById("SELECT user_id AS id, common_task_url, preference_1_task_files, preference_2_task_files, preference_3_task_files FROM FAM_UserTask WHERE year=2021");

foreach($applicants as $i => $apl) {
	$user_id = $apl['id'];
	if(!isset($tasks[$user_id])) continue;

	$task = $tasks[$user_id];

	$submitted_tasks = json_decode($task['common_task_url'], true);

	$applicants[$i]['3day'] = empty($submitted_tasks['3day']) ? '' : 'Submitted';
	$applicants[$i]['written'] = empty($submitted_tasks['written']) ? '' : 'Submitted';
	$applicants[$i]['preference_1_task'] = empty($task['preference_1_task_files']) ? '' : 'Submitted';
	$applicants[$i]['preference_2_task'] = empty($task['preference_2_task_files']) ? '' : 'Submitted';
	$applicants[$i]['preference_3_task'] = empty($task['preference_3_task_files']) ? '' : 'Submitted';
}

$data = $applicants;