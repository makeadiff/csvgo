<?php
function preProcessQuery($query) {
	global $QUERY, $year;

	$replace_options = [ 'city_id', 'center_id', 'batch_id', 'level_id', 'year', 'vertical_id', 'event_id' ];

	if(!isset($QUERY['year'])) $QUERY['year'] = $year;

	$replace_conditionals = [];

	foreach ($replace_options as $key) {
		if(isset($QUERY[$key])) {
			$replace_conditionals['%' . strtoupper($key) . '%'] = $QUERY[$key];
		}
	}

	$replaced_query = str_replace(array_keys($replace_conditionals), array_values($replace_conditionals), $query);

	return $replaced_query;
}