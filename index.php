<?php
require './common.php';

$name = i($QUERY, 'name');
$mime = i($QUERY, 'mime', 'csv'); // Could be 'octect-stream'
$no_cache = i($QUERY, 'no_cache', false);

if(!$name) die("Please provide the name of the CSV report you wish to get");

$query = $sql->getOne("SELECT query FROM App_CSVGo WHERE name='$name'");
if(!$query) die("Can't find a CSV Report by the name of '$name'.");

$replaced_query = preProcessQuery($query);

// Setup for Caching.
list($data, $cache_key) = getCacheAndKey($name, array('mime' => $mime, 'name' => $name, 'sp_page' => i($QUERY, 'sp_page', 0)));

header("Content-type:text/$mime");

if($mime == 'csv' or $mime == 'plain') {
	if($mime == 'csv') header('Content-Disposition: attachment; filename="'.$name.'.csv"');

	if(!$data or $no_cache) {
		$data = $sql->getAll($replaced_query);
		setCache($cache_key, $data);
	}

	print array2csv($data);
} else {
	$pager = new SqlPager($replaced_query, 100);
	if(!$data or $no_cache) {
		$data = $pager->getPage();
		setCache($cache_key, $data);
	}

	render('html.php');
}