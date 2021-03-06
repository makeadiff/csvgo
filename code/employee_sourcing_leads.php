<?php
$all_verticals = $sql->getById("SELECT id,name FROM Vertical WHERE status='1'");
$all_cities = $sql->getById("SELECT id,name FROM City WHERE type='actual'");
$data = $sql->getAll("SELECT name,phone,email,experience,user_id,applied_role,applied_verticals,cv_file,cover_letter FROM Temp_Employee_Signup");
foreach ($data as $index => $row) {
	if($row['user_id']) {
		$user = $sql->getAssoc("SELECT name,phone,email,user_type,joined_on,left_on,city_id FROM User WHERE id=" . $row['user_id']);
		$roles = $sql->getAll("SELECT GROUP_CONCAT(',', G.name) AS roles,UG.year FROM `Group` G INNER JOIN UserGroup UG ON UG.group_id=G.id WHERE UG.user_id=" . $row['user_id'] . " GROUP BY UG.year");

		$row['name'] = $user['name'];
		$row['phone'] = $user['phone'];
		$row['email'] = $user['email'];
		$row['user_id'] = "https://makeadiff.in/madapp/index.php/user/view/" . $row['user_id'];
		$row['volunteer_info'] = "City: " . $all_cities[$user['city_id']] . "\nType: " . $user['user_type'] . "\nJoined On: " . $user['joined_on'] . "\n";
		if($user['left_on'] and $user['left_on'] != '0000-00-00') $row['volunteer_info'] .= "Left On: " . $user['left_on'] . "\n";

		$all_roles = '';
		foreach ($roles as $role) {
			$all_roles .= $role['year'] . ": " . $role['roles'] . "\n";
		}
		$row['volunteer_info'] .= "Roles held: " . $all_roles;
	} else {
		$row['volunteer_info'] = 'None';
	}
	$verts = explode(",",  $row['applied_verticals']);
	$vertical = [];
	foreach ($verts as $v) {
		if(!$v) continue;
		$vertical[] = $all_verticals[$v];
	}
	$row['applied_verticals'] = implode(",", $vertical);
	$row['cover_letter'] = str_replace('"', "'", $row['cover_letter']);

	$row['cv_file'] = 'https://makeadiff.in/careers-form/uploads/' . $row['cv_file'];

	$data[$index] = $row;
}