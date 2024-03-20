<?php
################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$Database = new Database();
$settings = $Database->get_settings();

$Modules = new Modules();
?>


<?php
// DUMMY FUNCTION TO LATER BE REPLACE BY GETTEXT FOR TRANSLATIONS
/*
function _($input) {
	return $input;
}
*/
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
	<link rel="shortcut icon" href="favicon.ico">
	  
    <title>OpenRepeater</title>

    <!-- Bootstrap -->
    <link href="/includes/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/includes/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/includes/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/includes/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="/includes/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="/includes/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="/includes/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="/includes/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/includes/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- PNotify -->
    <link href="/includes/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="/includes/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="/includes/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

    <!-- Datatables -->
    <link href="/includes/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="/includes/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/includes/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/includes/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/includes/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="/includes/vendors/datatables.net-select/css/select.dataTables.min.css" rel="stylesheet">

	<?php
	// Display custom CSS if defined by page
	if (isset($customCSS)) {
		echo "<!-- custom CSS for current page -->\n";
		$customCSS = preg_replace('/\s+/', '', $customCSS);
		$cssArray = explode(',',$customCSS);
		foreach ($cssArray as $cssfile) {
		  echo "\t<link href='/includes/css/".$cssfile."' rel='stylesheet'>\n";
		}
	}
	?>

	<?php
	// Display custom Module CSS if defined
	if (isset($moduleCSS)) {
		echo "<!-- custom CSS for module -->\n";
		$moduleCSS = preg_replace('/\s+/', '', $moduleCSS);
		$module_cssArray = explode(',',$moduleCSS);
		foreach ($module_cssArray as $module_cssfile) {
		  echo "\t<link href='".$module_cssfile."' rel='stylesheet'>\n";
		}
	}
	?>




    <!-- Custom Theme Style -->
    <link href="/includes/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="dashboard.php" class="site_title"><span></span></a>
            </div>

            <div class="clearfix"></div>

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

              <div class="menu_section">

                <ul class="nav side-menu">
	                	
                  <li><a class="navLink" href="/dashboard.php"><i class="fa fa-tachometer"></i> <?=_('Dashboard')?> </a></li>
                  <li><a class="navLink" href="/settings.php"><i class="fa fa-cog"></i> <?=_('General Settings')?> </a></li>
                  <li><a class="navLink" href="/identification.php"><i class="fa fa-volume-up"></i> <?=_('Identification')?> </a></li>
                  <li><a class="navLink" href="/courtesy.php"><i class="fa fa-bell"></i> <?=_('Courtesy Tones')?> </a></li>

                  <li><a><i class="fa fa-plug"></i> <?=_('Modules')?> <span class="fa fa-chevron-down"></span></a>
                    <ul id="navModules" class="nav child_menu">
                      <li><a class="navLink" href="/modules.php"><?=_('All Modules')?></a></li>

                      <?= $Modules->nav_setting_links() ?>

                    </ul>
                  </li>

                  <li><a class="navLink" href="/ports.php"><i class="fa fa-sitemap"></i> <?=_('Interface')?> </a></li>
                  <li><a class="navLink" href="/log.php"><i class="fa fa-edit"></i> <?=_('Log & Files')?> </a></li>
                  <li><a class="navLink" href="/macros.php"><i class="fa fa-play-circle"></i> <?=_('Macros')?> </a></li>
                  <li><a class="navLink" href="/dtmf.php"><i class="fa fa-tty"></i> <?=_('DTMF Reference')?> </a></li>

                </ul>
              </div>

              <div class="menu_section">
			  	<h3>Developement</h3>
                <ul class="nav side-menu">
                  <li><a href="/dev/edit.php" target="_blank"><i class="fa fa-database"></i> Edit Database </a></li>
                  <li><a href="/dev_ui/" target="_blank"><i class="fa fa-wrench"></i> Dev UI </a></li>
                  <li><a href="/wizard/index.php"><i class="fa fa-magic"></i> Wizard </a></li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a class="change_password" data-toggle="tooltip" data-placement="top" title="<?=_('Change Password')?>">
                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?=_('Backup & Restore')?>" href="/backup.php">
                <span class="fa fa-database" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?=_('Support OpenRepeater')?>" href="https://openrepeater.com/donate" target="_blank">
                <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
              </a>
              <a class="logoutORP" data-toggle="tooltip" data-placement="top" title="<?=_('Log Out')?>">
                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="/includes/images/user.png" alt="<?= $_SESSION['username'] ?>">
                    <?= $_SESSION['username'] ?> <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a class="change_password"><i class="fa fa-lock pull-right"></i><?=_('Change Password')?></a></li>
                    <li><a href="/backup.php"><i class="fa fa-database pull-right"></i><?=_('Backup & Restore')?></a></li>
                    <li><a class="logoutORP"><i class="fa fa-sign-out pull-right"></i><?=_('Log Out')?></a></li>
                  </ul>
                </li>

	<!--  -->

<?php
	# Server Notifications
// 	$current_page_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$updateFlage = $Database->get_update_flag();
?>

                <li role="presentation" class="dropdown">
<button type="button" id="orp_restart_btn" class="btn btn-round btn-restart pulse"<?= ($updateFlage == false) ? ' style="display: none;"':''; ?>><i class="fa fa-refresh"></i> <?=_('Rebuild')?></button>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->