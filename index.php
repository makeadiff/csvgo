<?php
require './common.php';
$name = i($QUERY, 'name');
if(!$name) die("Please provide the name of the CSV report you wish to get");

$query = $sql->getOne("SELECT query FROM App_CSVGo WHERE name='$name'");

$replace_options = array('city_id', 'center_id', 'batch_id', 'level_id', 'year'	);

if(!isset($QUERY['year'])) $QUERY['year'] = $year;

$replace_conditionals = array();

foreach ($replace_options as $key) {
	if(isset($QUERY[$key])) {
		$replace_conditionals['%' . strtoupper($key) . '%'] = $QUERY[$key];
	}
}

$replaced_query = str_replace(array_keys($replace_conditionals), array_values($replace_conditionals), $query);

//print $replaced_query;

$data = $sql->getAll($replaced_query);

header("Content-type: text/plain");
// // header("Content-type:text/octect-stream");
// // header('Content-Disposition: attachment; filename="$name.csv"');

print array2csv($data);