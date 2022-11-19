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


if (!empty($_POST)) {
	switch ($_POST['request']) {
		case 'get_file':
			$fileArray = [];
			$fileArray['fileContents'] = file_get_contents( $_POST['file_path'] );
			$fileArray['fileDate'] = date( "F d Y H:i:s.", filemtime( $_POST['file_path'] ) );
			echo json_encode($fileArray);
			exit();
	}
}

$customCSS = "orp_logtail.css, page-log.css"; // "file1.css, file2.css, ... "
$customJS = "orp_logtail.js, page-log.js"; // "file1.js, file2.js, ... "

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-edit"></i> <?=_('Activity Log & Files')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="logTabs" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#svxlink_content" id="svxlink_tab" role="tab" data-toggle="tab" aria-expanded="true"><?=_('SVXLink Log')?></a>
                        </li>

                        <li role="presentation"><a href="#config_content" id="config_tab" role="tab" data-toggle="tab" aria-expanded="false"><?=_('Configuration Files')?></a>
                        </li>
                      </ul>

					  <div class="clearfix spacer height10"></div>

                      <div id="logContent" class="tab-content">

                        <div id="svxlink_content" role="tabpanel" class="tab-pane fade active in" aria-labelledby="svxlink_tab">
							<div id="orp_log">
								<button id="copyBtnLog" class="copyBtn" data-toggle="tooltip" data-placement="left" title="<?=_('Copy this to the clipboard')?>"><i class="fa fa-copy"></i> <?=_('Copy')?></button>
								<span class="data"><?=_('Loading')?>...</span>
							</div>
							<div><?=_('File Location')?>: /var/log/svxlink</div>
                        </div>

                        <div id="config_content" role="tabpanel" class="tab-pane fade in" aria-labelledby="config_tab">
							<select id="selConfigFile" class="form-control" style="margin-bottom: 10px;">
								<option><?=_('Choose a File')?></option>
							</select>
							<div id="configFileDisplay" style="display: none;">
								<button id="copyBtnFiles" class="copyBtn" data-toggle="tooltip" data-placement="left" title="<?=_('Copy this to the clipboard')?>"><i class="fa fa-copy"></i> <?=_('Copy')?></button>
								<span class="data"></span>
							</div>
							<div id="configFileDate"></div>
							<div id="configFileLoc" style="display: none;"><?=_('File Location')?>: <span></span></div>
                        </div>

                      </div>
                    </div>

                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<?php
	$configFilesArray = $Database->select_single("SELECT value FROM system_flags WHERE keyID='config_files'");
	$configFilesList = $configFilesArray['value'];	
?>

<script>
	var configFilesList = '<?= $configFilesList ?>';
	var copyMsgTitle = '<?=_('Success')?>';
	var copyMsgText = '<?=_('Your text has been copied to the clipboard')?>';
</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>