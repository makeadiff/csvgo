<?php
require('../../common.php');
require_once('../includes/application.php');

accessControl(['group_type' => 'national']);

$config['site_folder'] = dirname(__FILE__);
$template->page = str_replace("control/", "", $template->page);
$template->css_folder = 'control/css';
$template->js_folder = 'control/js';
// $template->template = 'None';

if(!isset($_GET['status']))$_GET['status'] = 1;

if(!empty($_SESSION['user_id'])) $_SESSION['admin_id'] = $_SESSION['user_id'];
