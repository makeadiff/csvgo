<?php
require('../../common.php');
require_once('../../support/includes/application.php');

if($config['server_host'] == 'localhost') {
	$config['site_url'] = 'http://localhost/Sites/community/makeadiff/makeadiff.in/';
} else {
	$config['site_url'] = 'http://makeadiff.in/';
}

$config['site_folder'] = dirname(__FILE__);
$template->page = str_replace("admin/", "", $template->page);
$template->css_folder = 'admin/css';
$template->js_folder = 'admin/js';
// $template->template = 'None';

if(!isset($_GET['stauts']))$_GET['status'] = 1;

$_SESSION['admin_id'] = $_SESSION['user_id'];

if(empty($_SESSION['admin_id'])) {
	if($template->page != 'login.php') {
		showMessage("Please login to continue...", "admin/login.php", "error");
	}
}
