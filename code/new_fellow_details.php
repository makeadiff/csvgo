<?php
//*
$city_id = $QUERY['city_id'];

$year = 2021; // :HARDCODE: Should be ($year - 1)
$photo_folders = [ realpath(__DIR__ . '/../../continuation_signup/photo_uploads'), realpath(__DIR__ . '/../../../storage/users/profile_photos/600x800') ];

$common = new Common;
$verticals = $common->getVerticals();
$groups = idNameFormat($common->getGroups(['type' => 'fellow']));
$fellows = $sql->getAll("SELECT DISTINCT U.id, U.name, US.group_id, IF(UGP.city_id = 0, U.city_id, UGP.city_id) AS city_id
	FROM User U
	INNER JOIN FAM_UserStage US ON US.user_id = U.id
	INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
	WHERE US.stage_id = 4
		AND US.status = 'selected'
		AND UGP.status = 'pending'
		AND UGP.year = $year
		AND US.year = $year
		AND (
				(UGP.city_id = 0 AND U.city_id = $city_id) OR
				(UGP.city_id != 0 AND UGP.city_id = $city_id)
			)
		AND US.group_id IN (2,4,5,11,15,19,272,269,370,375,378,389)
	ORDER BY U.name ASC");
// dump($fellows);exit;

// Badges Template
$badges = [
	'teacher'	=> false,
	'mentor'	=> false,
	'fundraiser'=> false,
	'cfr_coach'	=> false,
	'wingman'	=> false,
	'campaigns_volunteer'=>false,
	'hc_volunteer'		=> false,
	'finance_volunteer'	=> false,
	'dc_lead'	=> false,
	'credited'	=> false,
];

// Get data from spreadsheets 
// From the sheet https://docs.google.com/spreadsheets/d/1ueuL-F4D3_i97ptWXXBp_FGaho9gsmPQJrqQV25gAsI/edit#gid=0
// $sheet_url = "https://docs.google.com/spreadsheets/d/e/2PACX-1vRoJ1EiHoDyFnDPYpZJmI1lUVazW-RsR2l_K_yCWTmE3fGrlbT8zf-fFKm63Xdd4gbs_VIFccbHwBLi/pub?gid=1045827812&single=true&output=csv";
// require 'includes/classes/ParseCSV.php';
// $sheet = new ParseCSV($sheet_url);
// $data_points = [];
// foreach ($sheet as $row) {
// 	if(!$row['A']) continue;

// 	$value = '';
// 	if(!empty($row['G'])) {
// 		if(stripos($row['G'], 'cause') or stripos($row['G'], 'above')) $value = 'cause';
// 		else if(stripos($row['G'], 'leadership') or stripos($row['G'], 'ownership')) $value = 'leadership';
// 		else if(stripos($row['G'], 'scense') or stripos($row['G'], 'family')) $value = 'family';
// 	}

// 	$data_points[$row['A']] = [
// 		'user_id'	=> $row['A'],
// 		// 'name'		=> $row['B'],
// 		'why'		=> (!empty($row['F'])) ? $row['F'] : '',
// 		'value'		=> $value
// 	];
// }

$last_year = $year - 1;
foreach($fellows as $i => $f) {
	$user_id = $f['id'];
	$historical_roles = $sql->getAll("SELECT UG.year, GROUP_CONCAT(DISTINCT G.name SEPARATOR ',') AS roles
				FROM `Group` G
				INNER JOIN UserGroup UG ON UG.group_id=G.id
				WHERE UG.user_id={$user_id} AND G.id NOT IN (368, 387)
				GROUP BY UG.year
				ORDER BY UG.year DESC");
	$f['history'] = keyFormat($historical_roles, ['year', 'roles']);
	$f['role'] = $groups[$f['group_id']];

	$all_groups = implode(";", array_values($f['history']));
	$fellow_badge = $badges;

	if(
		(stripos($all_groups, "ES Volunteer") !== false) or 
		(stripos($all_groups, "Foundational Skills Volunteer") !== false) or 
		(stripos($all_groups, "Aftercare ASV") !== false) or 
		(stripos($all_groups, "Aftercare Wingman") !== false) or 
		(stripos($all_groups, "Transition Readiness Wingman") !== false) or 
		(stripos($all_groups, "Transition Readiness ASV") !== false)) $fellow_badge['teacher'] = true;
	if((stripos($all_groups, "ES Mentors") !== false) or (stripos($all_groups, "Foundation Mentor") !== false)) $fellow_badge['mentor'] = true;
	if(stripos($all_groups, "FR Volunteer") !== false) $fellow_badge['cfr_coach'] = true;
	if(stripos($all_groups, "Wingman") !== false) $fellow_badge['wingman'] = true;
	if(stripos($all_groups, "Dream Camp Lead") !== false) $fellow_badge['dc_lead'] = true;
	if(stripos($all_groups, "HC Volunteer") !== false) $fellow_badge['hc_volunteer'] = true;
	if(stripos($all_groups, "Finance Volunteer") !== false) $fellow_badge['finance_volunteer'] = true;
	if(stripos($all_groups, "Campaigns Volunteer") !== false) $fellow_badge['campaigns_volunteer'] = true;
	
	$money_raised = intval($sql->getOne("SELECT SUM(amount) FROM Donut_Donation WHERE fundraiser_user_id=$f[id] AND added_on > '$last_year-05-01 00:00:00'"));
	if($money_raised > 200) $fellow_badge['fundraiser'] = true; // :TODO: Get actual number from FR
	
	$f['raised'] = $money_raised;
	$f['badges'] = $fellow_badge;
	// $f['value'] = (!empty($data_points[$user_id]['value']) ? $data_points[$user_id]['value'] : '');
	// $f['why_fellow'] = (!empty($data_points[$user_id]['why']) ? $data_points[$user_id]['why'] : '');

	// Find Photos
	$photo_url = "";
	foreach($photo_folders as $folder) {
		$photo_file = joinPath($folder, $user_id . '.jpg');

		if(file_exists($photo_file)) {
			$makeadiff_folder = realpath(dirname(dirname(dirname(__DIR__))));
			$photo_url = str_replace($makeadiff_folder, "https://makeadiff.in", $photo_file);
			break;
		}
	}
	$f['photo_url'] = $photo_url;

	$fellows[$i] = $f;
}
// */

$data = $fellows;
