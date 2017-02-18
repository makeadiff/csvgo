<?php
require './common.php' ;

$crud = new Crud('App_CSVGo');
$crud->addField("name", "Name", 'varchar',array(), array('text'=>'$row["name"]', 'url'=> '"sql.php?name=" . $row["name"]'), 'text', 'url');
$crud->addField("last_run_on", "CSV", 'varchar',array(), array('text'=>'"CSV Link"', 'url'=> '"../index.php?name=" . $row["name"]'), 'text', 'url');

$crud->setListingQuery("SELECT * FROM App_CSVGo WHERE status='1'");
$crud->setListingFields("name", "vertical_id", "description", "added_on", "last_run_on", 'status');

render();