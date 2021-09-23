<?php
require './common.php' ;
use iframe\iframe\Crud;

$crud = new Crud('App_CSVGo');
$crud->addField("name", "Name", 'varchar',array(), array('text'=>'$row["name"]', 'url'=> '"sql.php?name=" . $row["name"]'), 'text', 'url');
$crud->addField("csv_link", "CSV", 'varchar',array(), array('text'=>'"CSV Link"', 'url'=> '"../index.php?name=" . $row["name"]'), 'text', 'url');
$crud->addField("data_link", "View Data", 'varchar',array(), array('text'=>'"View Data"', 'url'=> '"../index.php?mime=html&name=" . $row["name"]'), 'text', 'url');

$crud->setListingQuery("SELECT * FROM App_CSVGo WHERE status='1' ORDER BY added_on DESC");
$crud->setListingFields("name", "description", "added_on", "csv_link", 'data_link', 'status');

render();