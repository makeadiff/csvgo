<?php
$year = 2021; // :HARDCODE: Should be ($year - 1)
$photo_folders = [ realpath(__DIR__ . '/../../continuation_signup/photo_uploads'), realpath(__DIR__ . '/../../../storage/users/profile_photos/600x800') ];

$common = new Common;
$all_cities = idNameFormat($common->getCities());
$groups = idNameFormat($common->getGroups(['type' => 'fellow']));
$fellows = $sql->getAll("SELECT DISTINCT U.id, U.name, U.phone,U.email, US.group_id, IF(UGP.city_id = 0, U.city_id, UGP.city_id) AS city_id
	FROM User U
	INNER JOIN FAM_UserStage US ON US.user_id = U.id
	INNER JOIN FAM_UserGroupPreference UGP ON UGP.user_id = U.id
	WHERE US.stage_id = 4
		AND US.status = 'selected'
		AND UGP.status = 'pending'
		AND UGP.year = $year
		AND US.year = $year
		AND US.group_id IN (2,4,5,11,15,19,272,269,370,375,378,389)
	ORDER BY city_id, U.name ASC");

$last_year = $year - 1;
foreach($fellows as $i => $f) {
	$user_id = $f['id'];
	$f['city'] =$all_cities[$f['city_id']];
	$f['role'] = $groups[$f['group_id']];

	$photo_url = "";
	foreach($photo_folders as $folder) {
		$photo_file = joinPath($folder, $user_id . '.jpg');

		if(file_exists($photo_file)) {
			$makeadiff_folder = realpath(dirname(dirname(dirname(__DIR__))));
			$photo_url = str_replace($makeadiff_folder, "https://makeadiff.in", $photo_file);
			break;
		}
	}
	if($photo_url and i($QUERY, 'mime') == 'html') $photo_url = "<a target='_blank' href='$photo_url'>Photo</a>";
	$f['photo_url'] = $photo_url;

	unset($f['city_id']);
	unset($f['group_id']);

	$fellows[$i] = $f;
}

$data = $fellows;
