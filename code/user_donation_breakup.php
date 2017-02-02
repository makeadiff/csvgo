<?php
if(isset($_SERVER['HTTP_HOST']) and $_SERVER['HTTP_HOST'] == 'makeadiff.in') {
	$db_donut = 'makeadiff_cfrapp';
	$db_madapp = 'makeadiff_madapp';
} else {
	$db_donut = 'Project_Donut';
	$db_madapp = 'Project_Madapp';
}

$donut_data = $sql->getById("SELECT U.id,MU.email,MU.phone,COALESCE(SUM(D.donation_amount),0) AS amount, C.name AS city, MU.name
			FROM $db_donut.users U 
			INNER JOIN $db_donut.cities C ON C.id=U.city_id
			INNER JOIN $db_madapp.User MU ON MU.id=U.madapp_user_id
			LEFT JOIN $db_donut.donations D ON U.id=D.fundraiser_id AND D.created_at > '2016-10-01 00:00:00'
			WHERE U.is_deleted = 0
			GROUP BY U.id
			ORDER BY amount DESC");
$exdon_data = $sql->getById("SELECT U.id,MU.email,MU.phone,COALESCE(SUM(D.amount),0) AS amount, C.name AS city, MU.name
			FROM $db_donut.users U 
			INNER JOIN $db_donut.cities C ON C.id=U.city_id
			INNER JOIN $db_madapp.User MU ON MU.id=U.madapp_user_id
			LEFT JOIN $db_donut.external_donations D ON U.id=D.fundraiser_id AND D.created_at > '2016-10-01 00:00:00'
			WHERE U.is_deleted = 0
			GROUP BY U.id
			ORDER BY amount DESC");

$data = $donut_data;

foreach ($exdon_data as $id => $value) {
	if(isset($data[$id])) $data[$id]['amount'] += $exdon_data[$id]['amount'];
	else $data[$id] = $exdon_data[$id];
}

uasort($data, function($a, $b) {
    return $b['amount'] - $a['amount'];
});



