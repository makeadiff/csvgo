<?php
$data = $sql->getAll("SELECT U.id,U.name, C.name AS city, D.data AS campaign 
	FROM User U
	INNER JOIN City C ON C.id = U.city_id
	INNER JOIN Data D ON D.item_id = U.id AND D.name='sourcing_campaign_id' AND year=2021
	WHERE U.user_type = 'volunteer' AND U.status = '1'
	ORDER BY C.name, U.name");

$campaign_preformance = $sql->getById("SELECT campaign, COUNT(id) AS sourced_count FROM User WHERE joined_on > '2021-09-01 00:00:00' GROUP BY campaign");

foreach($data as $index => $user) {
	if(isset($campaign_preformance[$user['campaign']])) {
		$data[$index]['sourced_count'] = $campaign_preformance[$user['campaign']];
	} else {
		$data[$index]['sourced_count'] = 0;
	}
}

