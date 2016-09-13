<?php
$rel = dirname(dirname(__FILE__));
require($rel . '/../reports/includes/application.php');

function preProcessQuery($query) {
	global $QUERY, $year;

	$replace_options = array('city_id', 'center_id', 'batch_id', 'level_id', 'year'	);

	if(!isset($QUERY['year'])) $QUERY['year'] = $year;

	$replace_conditionals = array();

	foreach ($replace_options as $key) {
		if(isset($QUERY[$key])) {
			$replace_conditionals['%' . strtoupper($key) . '%'] = $QUERY[$key];
		}
	}

	$replaced_query = str_replace(array_keys($replace_conditionals), array_values($replace_conditionals), $query);

	return $replaced_query;
}