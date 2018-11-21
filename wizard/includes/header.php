<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>OpenRepeater<?php if ($pageTitle) { echo " | ".$pageTitle; } ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- The styles -->
	<link id="bs-css" href="../theme/css/bootstrap-classic.css" rel="stylesheet">
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>

	<link href='../theme/css/bootstrap-responsive.css' rel='stylesheet'>
	<link href='../theme/css/openrepeater.css' rel='stylesheet'>
	<link href='../theme/css/jquery-ui-1.8.21.custom.css' rel='stylesheet'>
	<link href='../theme/css/fullcalendar.css' rel='stylesheet'>
	<link href='../theme/css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='../theme/css/chosen.css' rel='stylesheet'>
	<link href='../theme/css/uniform.default.css' rel='stylesheet'>
	<link href='../theme/css/colorbox.css' rel='stylesheet'>
	<link href='../theme/css/jquery.cleditor.css' rel='stylesheet'>
	<link href='../theme/css/jquery.noty.css' rel='stylesheet'>
	<link href='../theme/css/noty_theme_default.css' rel='stylesheet'>
	<link href='../theme/css/elfinder.min.css' rel='stylesheet'>
	<link href='../theme/css/elfinder.theme.css' rel='stylesheet'>
	<link href='../theme/css/jquery.iphone.toggle.css' rel='stylesheet'>
	<link href='../theme/css/opa-icons.css' rel='stylesheet'>
	<link href='../theme/css/uploadify.css' rel='stylesheet'>
	
	<link href='wizard.css' rel='stylesheet'>


	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="../theme/img/favicon.ico">

</head>

<body>
	<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>

	<!-- Server Notifications -->

	<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>

				<div class="header-logo">
					<a href="../dashboard.php"><img src="../theme/img/OpenRepeaterLogo-Header.png"></a>
					<span>BETA</span>
				</div>

				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> <?php echo $_SESSION['username'];?></span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="../login.php?action=setPassword">Change Password</a></li>
						<li class="divider"></li>
						<li><a href="../login.php?action=logout">Logout</a></li>
					</ul>
				</div>
				<!-- user dropdown ends -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<?php } ?>
	<div class="container-fluid">
		<div class="row-fluid">
		<?php if(!isset($no_visible_elements) || !$no_visible_elements) { ?>

			<div id="content" class="span10">
			<!-- content starts -->
			<?php } ?>
