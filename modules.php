<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: index.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------


if ( isset($_POST['updateModuleSettings']) ) {
	// Update Module Settings, return to main module page
	# AUTOLOAD CLASSES
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
	$ModulesClass = new Modules();
	$results = $ModulesClass->save_module_settings($_POST);
	$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
}

if ( isset($_GET['settings']) ) {
	// If modules settings page is request, display that if it exist
	# AUTOLOAD CLASSES
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
	$ModulesClass = new Modules();
	echo $ModulesClass->display_settings($_GET['settings']);
							
} else {	
$customJS = 'page-modules.js, jquery-ui.min.js, jquery.ui.touch-punch.min.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-modules.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
$ModulesClass = new Modules();
$moduleList = $ModulesClass->getModulesJSON();
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('Modules')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Installed Modules')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success upload_file" data-upload-type="module"><i class="fa fa-upload"></i> <?=_('Upload Module')?></button>
                      <a class="btn btn-success" href="https://openrepeater.com/downloads/modules" target="_blank"><i class="fa fa-search"></i> <?=_('Find Modules on OpenRepeater.com')?></a>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div id="moduleWrap" class="x_content">

					<div id="moduleList"></div>
					<div id="moduleListSort"></div>

					<? ### ROW TEMPLATE ######################################################## ?>
					<div id="rowTemplate" class="moduleRow" data-module-id="" data-svxlink-id="" data-svxlink-name="" style="display:none;">
						<span class="largeDigit">++</span>
					
						<div class="col-md-4 col-sm-4 col-xs-12">
							<h3><i class="fa fa-plug"></i> <span class="modName">Template</span></h3>
							<span class="modType badge"></span>
							<div style="clear: both;">
								<input type="checkbox" class="js-switch modActive" />
								<div class="btn-group btn-group-sm">
									<a class="btn btn-default settings" href="#"><i class="fa fa-cog"></i> <?=_('Settings')?></a>
									<a class="btn btn-default dtmf" href="#"><i class="fa fa-tty"></i> <?=_('DTMF')?></a>
								</div>
								<div class="btn-group btn-group-sm delete-grp">
									<button class="btn btn-danger delete" type="button"><i class="fa fa-trash"></i> <?=_('Delete')?></button>
								</div>
							</div>
						</div>
						<div class="modInfo col-md-8 col-sm-8 col-xs-12">
							<span class="modDesc"></span>
						</div>
						<div class="clearfix"></div>
			
					</div>
					<? ######################################################################### ?>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- /page content -->

<!-- Upload Dialog Modal -->
<script>
	var moduleList = '<?= $moduleList ?>';
	var modTypeCore = '<?=_('Core')?>';
	var modTypeAddOn = '<?=_('Add-on')?>';
	var modTypeDaemon = '<?=_('Daemon')?>';

	var modal_UploadTitle = '<?=_('Upload New Module')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload an OpenRepeater module to add additional functionality. These are specially packaged SVXLink Modules along with the required sound files and user interface components zipped up in an easy to install file. Modules must be compressed with a ".zip" extension. Upon upload the modules will be unzipped, verified, and installed. You will then need to active the module, configure any settings (if applicable), and rebuild the configuration.')?>';
	var uploadSuccessTitle = '<?=_('Module Installed')?>';
	var uploadSuccessText = '<?=_('New module was successfully uploaded and installed. You must activate first to use it.')?>';

	var modDelConfirmTitle = '<?=_('Delete Module')?>';
	var modDelConfirmBody = '<?=_('Are you sure you wish to delete the following Module?')?>';
	var modDelConfirmBtn = '<?=_('Delete')?>';
	var modDelSuccessTitle = '<?=_('Success')?>';
	var modDelSuccessBody = '<?=_('The module has been successfully deleted.')?>';
	var modDelErrorTitle = '<?=_('Error')?>';
	var modDelErrorBody = '<?=_('There was a problem deleting the module.')?>';
</script>

<?php include('includes/footer.php'); ?>

<?php
} // end else


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>