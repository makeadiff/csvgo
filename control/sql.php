<?php
require './common.php' ;
$html = new HTML;

$action 	= i($QUERY,'action');
$name 		= i($QUERY,'name');
$description= i($PARAM, "description");
$sql_query 	= i($PARAM, "sql_query");
$db 		= i($PARAM, "db", "madapp");
$id 		= i($QUERY, "id", "0");

$sql_error_query 	= '';
$sql_error_message 	= '';

if(!$action and $name) {
	list($id, $db, $sql_query, $description) = $sql->getList("SELECT id, db, query,description FROM App_CSVGo WHERE name='$name' LIMIT 0,1");
	$sql_query = stripslashes($sql_query);
}

$data = array();
if($action) {
	//$data = $sql->getAll($sql_query);
	$replaced_query = preProcessQuery($sql_query);
	$org_query = $sql_query;
	$sql_query = $replaced_query;
	$sql->options['error_handling'] = 'callback';
	$sql->options['error_callback'] = 'sql_error';

	$pager = new SqlPager($sql_query, 100);
	$data = $pager->getPage();

	if($QUERY['action'] == 'Save') {

		if($id and $id != 0) {
			$csvgo_id = $id;
			$sql->update("App_CSVGo", array(
				'name'		=> unformat($QUERY['name']),
				'description'	=> $QUERY['description'],
				'query'		=> $QUERY['sql_query'],
				'db'		=> $db,
			), "id=$csvgo_id");

		} else {
			$csvgo_id = $sql->insert("App_CSVGo", array(
				'name'		=> unformat($QUERY['name']),
				'description'	=> $QUERY['description'],
				'query'		=> $QUERY['sql_query'],
				'db'		=> $db,
				'added_on'	=> 'NOW()',
				'status'	=> '1'
			));
		}

		$QUERY['success'] = "Saved the report query '$QUERY[name]' successfully($csvgo_id).";
	}
}

render();

function sql_error($query, $error_message) {
	global $sql_error_message, $sql_error_query, $QUERY, $PARAM;
	$sql_error_query = $query;
	$sql_error_message = $error_message; // . "<pre class='sql-error'>" . $PARAM['sql_query'] . "</pre><br /><a onclick='$(\".sql-error\").show();'>Show Raw Query</a>";
}