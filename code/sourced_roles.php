<?php
// $roles = [
// 	'Academic Support Volunteer(5th-10th)' => 9,
// 	'teacher' => 9,
// 	'teaching' => 9,
// 	'Academic Support Volunteer(11th-12th)' => 349,
// 	'campaigns' => 339,
// 	'Campaigns Volunteer' => 339,
// 	'finance' => 341,
// 	'Finance Volunteer' => 341,
// 	'Foundation Skill Volunteer(5th-7th)' => 376,
// 	'fundraising' => 369,
// 	'Fundraising Volunteer' => 369,
// 	'hc' => 340,
// 	'Human Capital Volunteer' => 340,
// 	'other' => 0,
// 	'wingman' => 365,
// 	'Wingman(11th,12th)' => 348,
// 	'Wingman(Undergrads and Graduates)' => 365,
// ];
$roles = [
	376	=> ['Foundation Skill Volunteer(5th-7th)'], // Foundation Skills Volunteer
	9 	=> ['Academic Support Volunteer(5th-10th)', 'teacher', 'teaching'], // ES Volunteer
	349 => ['Academic Support Volunteer(11th-12th)'], // TR ASV
	348 => ['Wingman(11th,12th)'], // TR Wingman
	365 => ['wingman', 'Wingman(Undergrads and Graduates)'], // Aftercare Wingman
	340 => ['hc', 'Human Capital Volunteer'], // HC Volunteer
	369 => ['fundraising', 'Fundraising Volunteer'],
	341 => ['finance', 'Finance Volunteer'],
	339 => ['campaigns','Campaigns Volunteer'],
	0   => ['other'],
];
$sourced_primary = $sql->getById("SELECT CONCAT(city_id,'_',applied_role) AS key_index, COUNT(id) AS applied_count FROM User 
						WHERE joined_on >= '2021-09-19 00:00:00' 
						GROUP BY city_id,applied_role");
$sourced_secondary = $sql->getById("SELECT CONCAT(city_id,'_',applied_role_secondary) AS key_index, COUNT(id) AS applied_count FROM User 
						WHERE joined_on >= '2021-09-19 00:00:00' 
						GROUP BY city_id,applied_role_secondary");

$all_cities = $sql->getById("SELECT id,name FROM City WHERE id NOT IN (0, 14, 26, 28)");
$all_groups = $sql->getById("SELECT id,name FROM `Group` WHERE type='volunteer' AND status='1'");
$all_groups[0] = 'None Selected';

$data = [];

foreach($all_cities as $city_id => $city_name) {
	foreach($roles as $group_id => $role_options) {
		$primary_count = 0;
		$secondary_count = 0;
		foreach($role_options as $role) {
			if(isset($sourced_primary[$city_id . '_' . $role])) {
				$primary_count += $sourced_primary[$city_id . '_' . $role];
			}

			if(isset($sourced_secondary[$city_id . '_' . $role])) {
				$secondary_count += $sourced_secondary[$city_id . '_' . $role];
			}
		}

		$data[] = [
			'city_id' 	=> $city_id,
			'city' 		=> $all_cities[$city_id],
			'group_id'	=> $group_id,
			'role' 		=> $all_groups[$group_id],
			'primary'	=> $primary_count,
			'secodary'	=> $secondary_count,
			'total'		=> $primary_count + $secondary_count
		];
	}
}

