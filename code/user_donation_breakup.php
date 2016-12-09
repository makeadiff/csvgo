<?php
if(isset($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] == 'makeadiff.in') {
	$db_donut = 'makeadiff_cfrapp';
	$db_madapp = 'makeadiff_madapp';
} else {
	$db_donut = 'Project_Donut';
	$db_madapp = 'Project_Madapp';
}

$donut_data = $sql->getById("SELECT U.id,MU.email,MU.phone,SUM(D.donation_amount) AS amount, C.name AS city
			FROM $db_donut.users U 
			INNER JOIN $db_donut.cities C ON C.id=U.city_id
			INNER JOIN $db_madapp.User MU ON MU.id=U.madapp_user_id
			INNER JOIN $db_donut.donations D ON U.id=D.fundraiser_id
			GROUP BY D.fundraiser_id");
$exdon_data = $sql->getById("SELECT U.id,MU.email,MU.phone,SUM(D.amount) AS amount, C.name AS city
			FROM $db_donut.users U 
			INNER JOIN $db_donut.cities C ON C.id=U.city_id
			INNER JOIN $db_madapp.User MU ON MU.id=U.madapp_user_id
			INNER JOIN $db_donut.external_donations D ON U.id=D.fundraiser_id
			GROUP BY D.fundraiser_id");

$data = array();
foreach ($donut_data as $user_id => $row) {
	if(isset($exdon_data[$user_id])) {
		$row['amount'] += $exdon_data[$user_id]['amount'];
	}

	$data[] = $row;
}

$difference = array_diff_key($exdon_data, $donut_data);
foreach ($difference as $user_id => $row) {
	$data[] = $row;	
}

// print json_encode($data);
