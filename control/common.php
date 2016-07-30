<?php
$single_user = 0;
require('../../common.php');
require_once('../../support/includes/application.php');

if($config['server_host'] == 'localhost') {
	$config['site_url'] = 'http://localhost/Sites/community/makeadiff/makeadiff.in/';
} else {
	$config['site_url'] = 'http://makeadiff.in/';
}

$config['site_folder'] = dirname(__FILE__);
$template->page = str_replace("control/", "", $template->page);
$template->css_folder = 'control/css';
$template->js_folder = 'control/js';
// $template->template = 'None';

if(!isset($_GET['stauts']))$_GET['status'] = 1;

$_SESSION['admin_id'] = $_SESSION['user_id'];

if(empty($_SESSION['admin_id'])) {
	if($template->page != 'login.php') {
		showMessage("Please login to continue...", "control/login.php", "error");
	}
}
