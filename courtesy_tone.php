<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################
$classAudioFiles = new AudioFiles();

if (isset($_POST['action'])){
	if ($_POST['action'] == "upload_file") {
		$results = $classAudioFiles->audio_upload_files('courtesy_tones', $_FILES['file']);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

	} else if ($_POST['action'] == "rename_file") {
		$results = $classAudioFiles->audio_rename_file('courtesy_tones',$_POST['oldFileName'],$_POST['newFileLabel']);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

	} else if ($_POST['action'] == "delete_file") {
		$results = $classAudioFiles->audio_delete_files('courtesy_tones',$_POST["delfile"]);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
	}
}
?>

<?php
$pageTitle = "Courtesy Tones"; 

$customJS = "page-courtesy_tone.js"; // "file1.js, file2.js, ... "
$customCSS = "page-courtesy_tone.css";

include('includes/header.php');
?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<form class="form-inline" role="form" action="functions/ajax_db_update.php" method="post" id="courtesyModeUpdate">
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-cog"></i> Courtesy Tone Mode</h2>
					</div>
					<div class="box-content">

						  <fieldset>
							
							<div class="control-group">
								<div class="controls">
								<label class="control-label" for="courtesyMode">Select Tone Mode: </label>
									<select	id="courtesyMode" name="courtesyMode">
										<option value="disabled" <?php if ($settings['courtesyMode'] == 'disabled') { echo "selected"; } ?>>-Disabled-</option>
										<option value="beep" <?php if ($settings['courtesyMode'] == 'beep') { echo "selected"; } ?>>Basic Beep</option>
										<option value="custom" <?php if ($settings['courtesyMode'] == 'custom') { echo "selected"; } ?>>Custom Courtesy Tone</option>
									</select>

									<input type="hidden" name="action" value="update">		

								</div>
							</div>
							
							
							
						  </fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>

			<div id="disabled" style="display: none;"> <!-- Expand Setting -->
				<div class="alert alert-error"><strong>Courtesy Tone Disabled:</strong> No courtesy tone will be played when the carrier is dropped. This may be preferred for a &quot;Quite&quot; machine or for testing. Otherwise, please select a different mode above.</div>
			</div>
			
			<div id="beep" style="display: none;"> <!-- Expand Setting -->
				<div class="alert alert-success"><strong>Basic Beep:</strong> You have selected a basic beep for your courtesy tone. For more advance tones, please choose the custom mode from the menu above.</div>
			</div>
			
			<div id="custom" style="display: none;"> <!-- Expand Setting -->

			<div class="row-fluid sortable">

				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-music"></i> Current Courtesy Tone</h2>
					</div>
					<div class="box-content">
					<h2 id="current_tone" class="current_tone"><?php echo $classAudioFiles->pretty_filename($settings['courtesy']);?></h2>

					<!-- Button triggered modal -->
					<button class="btn upload" data-toggle="modal" data-target="#uploadFile"><i class="icon-arrow-up"></i> Upload New Tone</button>

					</div>
				</div><!--/span-->
				
				
			</div><!--/row-->

			<form class="form-inline" role="form" action="functions/ajax_db_update.php" method="post" id="courtesyToneUpdate">
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th-list"></i> Courtesy Tone Library</h2>
					</div>
					<div class="box-content">

					<?php 
						// Display Table for Audio Files, and modals at the end.
						$audioTable = $classAudioFiles->display_audio_files('courtesy_tones', $settings['courtesy'], 'courtesy');
						echo $audioTable['table'];
					?>

					</div>
				</div><!--/span-->
			</div><!--/row-->
			</form>
			
			</div><!-- End Expand Setting -->

			
<?php echo $audioTable['modals']; ?>

<!-- Modal - UPLOAD DIALOG -->

<form action="courtesy_tone.php" method="post" enctype="multipart/form-data">
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h3 class="modal-title" id="myModalLabel">Upload Custom Courtesy Tone</h3>
      </div>
      <div class="modal-body">
		<p>Upload your own custom courtesy tone files. The file should be in MP3 or WAV format and should resemble a short beep.</p>
		<input type="hidden" name="action" value="upload_file">
		<input type="file" name="file[]" id="file" required>
      </div>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success"><i class="icon-arrow-up icon-white"></i> Upload</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form>
    
<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>