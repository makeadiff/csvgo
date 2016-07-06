<?php
require './common.php' ;

$action = i($QUERY,'action');
$name = i($QUERY,'name');
$sql_query = i($PARAM, "sql_query");
$description = i($PARAM, "description");

if($name and !$description) {
	list($sql_query, $description) = $sql->getList("SELECT query,description FROM App_CSVGo WHERE name='$name'");
}

$data = array();
if($action) {
	//$data = $sql->getAll($sql_query);
	$pager = new SqlPager($sql_query, 100);
	$data = $pager->getPage();

	if($QUERY['action'] == 'Save') {
		$csvgo_id = $sql->insert("App_CSVGo", array(
				'name'		=> unformat($QUERY['name']),
				'description'	=> $QUERY['description'],
				'query'		=> $QUERY['sql_query'],
				'added_on'	=> 'NOW()',
				'status'	=> '1'
			));
		$QUERY['success'] = "Saved the report query '$QUERY[name]' successfully($csvgo_id).";
	}
}

render();