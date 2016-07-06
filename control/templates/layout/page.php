<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $config['site_title'] ?> : Admin</title>
	<!-- Core CSS - Include with every page -->
	<link href="<?php echo $config['site_url'] ?>bower_components/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['site_url'] ?>bower_components/bootstrap/dist/css/bootstrap-theme.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['site_url'] ?>images/silk_theme.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['site_home'] ?>admin/css/style.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $config['site_home'] ?>admin/themes/sb-admin-v2/font-awesome/css/font-awesome.css" rel="stylesheet">
	<link href="<?php echo $config['site_home'] ?>admin/themes/sb-admin-v2/css/sb-admin.css" rel="stylesheet">
	<?php echo $css_includes ?>
</head>
<body>
	<div id="wrapper">

		<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php"><?php echo $config['site_title'] ?> Admin Area</a>
			</div>
			<!-- /.navbar-header -->

			<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-gear fa-fw"></i>  <i class="fa fa-caret-down"></i>
					</a>
					
					<ul class="dropdown-menu dropdown-user">
						<li><a href="<?php echo $config['site_url'] ?>"><i class="fa fa-home fa-fw"></i> View Site</a></li>
						<li><a href="setting.php"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
						<li class="divider"></li>
						<li><a href="index.php?action=logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
						</li>
					</ul>
					<!-- /.dropdown-user -->
				</li>
				<!-- /.dropdown -->
			</ul>
			<!-- /.navbar-top-links -->

			<div class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav" id="side-menu">
						<li><a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
						<li><a href="sql.php"><i class="fa fa-dashboard fa-fw"></i> SQL PlayArea</a></li>
					</ul>
					<!-- /#side-menu -->
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->
		</nav>

		<div id="page-wrapper">

<div class="message-area" id="error-message" <?php echo ($QUERY['error']) ? '':'style="display:none;"';?>><?php
	if(!empty($PARAM['error'])) print strip_tags($PARAM['error']); //It comes from the URL
	else print $QUERY['error']; //Its set in the code(validation error or something).
?></div>
<div class="message-area" id="success-message" <?php echo ($QUERY['success']) ? '':'style="display:none;"';?>><?php echo strip_tags(stripslashes($QUERY['success']))?></div>

<?php 
/////////////////////////////////// The Template file will appear here ////////////////////////////

if(!empty($GLOBALS['page'])) {
	print $GLOBALS['page']->code['top'];
	$GLOBALS['page']->printAction();
	print $GLOBALS['page']->code['bottom'];
} else {
	include($GLOBALS['template']->template); 
}

/////////////////////////////////// The Template file will appear here ////////////////////////////
?>        
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

	<script src="<?php echo $config['site_url'] ?>bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
	<script src="<?php echo $config['site_url'] ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo $config['site_home'] ?>js/application.js" type="text/javascript"></script>
	<script src="<?php echo $config['site_home'] ?>admin/themes/sb-admin-v2/js/sb-admin.js"></script>
	<script src="<?php echo $config['site_home'] ?>admin/themes/sb-admin-v2/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<?php echo $js_includes ?>
</body>
</html>
