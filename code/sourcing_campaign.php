<?php
$data = $sql->getAll("SELECT DISTINCT U.id,U.name, C.name AS city, V.name AS vertical, D.data AS campaign 
	FROM User U
	INNER JOIN City C ON C.id = U.city_id
	INNER JOIN Data D ON D.item_id = U.id AND D.name='sourcing_campaign_id' AND D.year=2021
	INNER JOIN UserGroup UG ON UG.user_id = U.id AND UG.year=2021 AND UG.main='1'
	INNER JOIN `Group` G ON UG.group_id=G.id
	INNER JOIN Vertical V ON V.id = G.vertical_id
	WHERE U.user_type = 'volunteer' AND U.status = '1'
	ORDER BY C.name, U.name");

// :TODO: Get vertical of user

$campaign_preformance = $sql->getById("SELECT campaign, COUNT(id) AS sourced_count FROM User WHERE joined_on >= '2021-09-19 00:00:00' GROUP BY campaign");

foreach($data as $index => $user) {
	if(isset($campaign_preformance[$user['campaign']])) {
		$data[$index]['sourced_count'] = $campaign_preformance[$user['campaign']];
	} else {
		$data[$index]['sourced_count'] = 0;
	}
}
