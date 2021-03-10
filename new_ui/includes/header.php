<?php
################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$Database = new Database();
$settings = $Database->get_settings();
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
	  
    <title>OpenRepeater</title>

    <!-- Bootstrap -->
    <link href="/new_ui/includes/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/new_ui/includes/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/new_ui/includes/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/new_ui/includes/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="/new_ui/includes/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="/new_ui/includes/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="/new_ui/includes/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="/new_ui/includes/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="/new_ui/includes/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- PNotify -->
    <link href="/new_ui/includes/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

    <!-- Datatables -->
    <link href="/new_ui/includes/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="/new_ui/includes/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">


	<?php
	// Display custom CSS if defined by page
	if (isset($customCSS)) {
		echo "<!-- custom CSS for current page -->\n";
		$customCSS = preg_replace('/\s+/', '', $customCSS);
		$cssArray = explode(',',$customCSS);
		foreach ($cssArray as $cssfile) {
		  echo "\t<link href='/new_ui/includes/css/".$cssfile."' rel='stylesheet'>\n";
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
    <link href="/new_ui/includes/css/custom.css" rel="stylesheet">
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
	                	
                  <li><a href="/new_ui/dashboard.php"><i class="fa fa-tachometer"></i> <?=_('Dashboard')?> </a></li>
                  <li><a href="/new_ui/settings.php"><i class="fa fa-cog"></i> <?=_('General Settings')?> </a></li>
                  <li><a href="/new_ui/identification.php"><i class="fa fa-volume-up"></i> <?=_('Identification')?> </a></li>
                  <li><a href="/new_ui/courtesy.php"><i class="fa fa-bell"></i> <?=_('Courtesy Tones')?> </a></li>

                  <li><a><i class="fa fa-plug"></i> <?=_('Modules')?> <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="/new_ui/modules.php"><?=_('All Modules')?></a></li>
                      <li><a href="/new_ui/modules/EchoLink/settings.php">EchoLink</a></li>
                      <li><a href="/new_ui/modules/RemoteRelay/settings.php">Remote Relay</a></li>
                      <li><a href="/new_ui/modules/SiteStatus/settings.php">Site Status</a></li>
                      <li><a href="/new_ui/modules/TxFan/settings.php">TX Cooling Fan</a></li>
                      <li><a href="/new_ui/modules/MetarInfo/settings.php">METAR</a></li>
                      <li><a href="/new_ui/modules/VoiceMail/settings.php">Voice Mail <span class="badge badge-info">Dev</span></a></li>
                      <li><a href="/new_ui/modules/Frn/settings.php">FRN Gateway <span class="badge badge-info">Dev</span></a></li>
                      <li><a href="/new_ui/modules/RigCtl/settings.php">Rig Control <span class="badge badge-info">Dev</span></a></li>
                      <li><a href="/new_ui/modules/calendar.php">Announcements <span class="badge badge-info">Dev</span></a></li>
                    </ul>
                  </li>

                  <li><a href="/new_ui/ports.php"><i class="fa fa-sitemap"></i> <?=_('Interface')?> </a></li>
                  <li><a href="/new_ui/log.php"><i class="fa fa-edit"></i> <?=_('Log')?> </a></li>
                  <li><a href="/new_ui/macros.php"><i class="fa fa-play-circle"></i> <?=_('Macros')?> </a></li>
                  <li><a href="/new_ui/dtmf.php"><i class="fa fa-tty"></i> <?=_('DTMF Reference')?> </a></li>

                  <li><a href="/new_ui/wizard.php"><i class="fa fa-magic"></i> Wizard </a></li>
                  <li><a href="/new_ui/404.php"><i class="fa fa-exclamation-triangle"></i> 404 </a></li>

                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a class="change_password" data-toggle="tooltip" data-placement="top" title="<?=_('Change Password')?>">
                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?=_('Backup & Restore')?>" href="/new_ui/backup.php">
                <span class="fa fa-database" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?=_('Support OpenRepeater')?>" href="https://openrepeater.com/donate" target="_blank">
                <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="<?=_('Log Out')?>" href="/new_ui/index.php">
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
                    <?=_('Admin')?> <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a class="change_password"><i class="fa fa-lock pull-right"></i><?=_('Change Password')?></a></li>
                    <li><a href="/new_ui/backup.php"><i class="fa fa-database pull-right"></i><?=_('Backup & Restore')?></a></li>
                    <li><a href="/new_ui/index.php"><i class="fa fa-sign-out pull-right"></i><?=_('Log Out')?></a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
<button type="button" id="orp_restart_btn" class="btn btn-round btn-restart pulse"><i class="fa fa-refresh"></i> <?=_('Rebuild')?></button>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->