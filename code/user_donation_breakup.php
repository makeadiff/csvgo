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
			WHERE D.created_at > '2016-10-01 00:00:00'
			GROUP BY D.fundraiser_id");
$exdon_data = $sql->getById("SELECT U.id,MU.email,MU.phone,SUM(D.amount) AS amount, C.name AS city
			FROM $db_donut.users U 
			INNER JOIN $db_donut.cities C ON C.id=U.city_id
			INNER JOIN $db_madapp.User MU ON MU.id=U.madapp_user_id
			INNER JOIN $db_donut.external_donations D ON U.id=D.fundraiser_id
			WHERE D.created_at > '2016-10-01 00:00:00'
			GROUP BY D.fundraiser_id");

$data = $donut_data;

foreach ($exdon_data as $id => $value) {
	if(isset($data[$id])) $data[$id]['amount'] += $exdon_data[$id]['amount'];
	else $data[$id] = $exdon_data[$id];
}

