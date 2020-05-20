<?php
$city_id = $QUERY['city_id'];

$common = new Common;
$verticals = $common->getVerticals();
$groups = idNameFormat($common->getGroups(['type' => 'fellow']));
$fellows = $sql->getAll("SELECT DISTINCT U.id, U.name, US.group_id
	FROM User U
	INNER JOIN FAM_UserStage US ON US.user_id = U.id
	INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
	WHERE US.stage_id = 4
		AND US.status = 'selected'
		AND UGP.status = 'pending'
		AND UGP.year = $year
		AND US.year = $year
		AND U.city_id = $city_id
		AND US.group_id IN (2,4,5,11,15,19,272,269,370,375,378,389)
	ORDER BY U.name ASC");

// Badges Template
$badges = [
	'teacher'	=> false, 
	'mentor'	=> false, 
	'fundraiser'=> false, 
	'cfr_coach'	=> false, 
	'wingman'	=> false, 
	'dc_lead'	=> false, 
	'credited'	=> false, 
];

$last_year = $year - 1;
foreach($fellows as $i => $f) {
	$historical_roles = $sql->getAll("SELECT UG.year, GROUP_CONCAT(DISTINCT G.name SEPARATOR ',') AS roles
				FROM `Group` G
				INNER JOIN UserGroup UG ON UG.group_id=G.id
				WHERE UG.user_id={$f['id']} AND G.id NOT IN (368, 387)
				GROUP BY UG.year
				ORDER BY UG.year DESC");
	$f['history'] = keyFormat($historical_roles, ['year', 'roles']);
	$f['role'] = $groups[$f['group_id']];

	$all_groups = implode(";", array_values($f['history']));
	$fellow_badge = $badges;

	if(stripos($all_groups, "ES Volunteer") !== false) $fellow_badge['teacher'] = true;
	if((stripos($all_groups, "ES Mentors") !== false) or (stripos($all_groups, "Foundation Mentor") !== false)) $fellow_badge['mentor'] = true;
	if(stripos($all_groups, "FR Volunteer") !== false) $fellow_badge['cfr_coach'] = true;
	if(stripos($all_groups, "Wingman") !== false) $fellow_badge['wingman'] = true;
	if(stripos($all_groups, "Dream Camp Lead") !== false) $fellow_badge['dc_lead'] = true;
	$money_raised = intval($sql->getOne("SELECT SUM(amount) FROM Donut_Donation 
											WHERE fundraiser_user_id=$f[id] AND added_on > '$last_year-05-01 00:00:00'"));
	if($money_raised > 200) $fellow_badge['fundraiser'] = true;
	
	$f['raised'] = $money_raised;
	$f['badges'] = $fellow_badge;
	$fellows[$i] = $f;
}

$data = $fellows;
