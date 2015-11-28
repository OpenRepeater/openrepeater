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

include_once("functions/audio_functions.php");

if (isset($_POST['action'])){
	if ($_POST['action'] == "select_file") {
		$results = audio_select('courtesy_tones',$_POST['file']);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

	} else if ($_POST['action'] == "upload_file") {
		// This is the handler for file uploads. It uploads the file to a temporary path then
		// converts it to the appropriate WAV formate and puts it in the courtesy tones folder.
		
		$results = audio_upload_files('courtesy_tones', $_FILES['file']);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

	} else if ($_POST['action'] == "rename_file") {
		$results = audio_rename_file('courtesy_tones',$_POST['oldFileName'],$_POST['newFileLabel']);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

	} else if ($_POST['action'] == "delete_file") {
		$results = audio_delete_files('courtesy_tones', $_POST["delfile"]);
		$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
	}
}

?>

<?php
$pageTitle = "Courtesy Tones"; 

$customJS = "page-courtesy_tone.js"; // "file1.js, file2.js, ... "
$customCSS = "page-courtesy_tone.css";

include_once("includes/get_settings.php");
include('includes/header.php');
$dbConnection->close();
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
					<h2 class="current_tone"><?php echo audio_current($settings['courtesy']);?></h2>

					<!-- Button triggered modal -->
					<button class="btn upload" data-toggle="modal" data-target="#uploadFile"><i class="icon-arrow-up"></i> Upload New Tone</button>

					</div>
				</div><!--/span-->
				
				
			</div><!--/row-->

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th-list"></i> Courtesy Tone Library</h2>
					</div>
					<div class="box-content">
						<table class="table table-striped table-condensed bootstrap-datatable datatable">
						  <thead>
							  <tr class="audio_row">
								  <th>Name</th>
								  <th>Preview</th>
								  <th class="button_grp">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>

<?php


$audioLib = audio_get_files('courtesy_tones');

if ($audioLib) {
	
	$file_counter = 0;
	$html_modal = '';

	foreach($audioLib as $fileArray) {	

		$file_counter++;
	
	
		// START TABLE ROW
		if ($settings['courtesy'] == $fileArray['fileName']) {	
			$html_string = '<tr id="courtesyToneRow'.$file_counter.'" class="audio_row active">';
		} else {
			$html_string = '<tr id="courtesyToneRow'.$file_counter.'" class="audio_row">';
		}

		$html_string .= '
			<td><h2>' . $fileArray['fileLabel'] . '</h2></td>
	
			<td class="center">
			<audio controls>
				<source src="' . $fileArray['fileURL'] . '" type=audio/mpeg>
				Your browser does not support the audio element.
			</audio>
			</td>
		
			<td class="button_grp">
	
				<form action="courtesy_tone.php" method="post" style="position:block;float:left;">
				<input type="hidden" name="action" value="select_file">
				<input type="hidden" name="file" value="'.$fileArray['fileName'].'">
				<button class="btn btn-success" type="submit"><i class="icon-ok icon-white"></i> Select</button>
				</form>
	
				<!-- Button triggered modal -->
				<button class="btn" data-toggle="modal" data-target="#renameFile'.$file_counter.'">
					<i class="icon-pencil"></i> 
					Rename
				</button>
	
				<!-- Button triggered modal -->
				<button class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'">
					<i class="icon-trash icon-white"></i> 
					Delete
				</button>
			</td>
		</tr>';
	
		$html_modal .= '
	
		<!-- Modal - RENAME DIALOG -->
		<form action="courtesy_tone.php" method="post">
	
		<div class="modal fade" id="renameFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel">Rename Courtesy Tone</h3>
		      </div>
		      <div class="modal-body">
			<input type="hidden" name="action" value="rename_file">
			<input class="input disabled" id="disabledInput" type="text" placeholder="' . $fileArray['fileLabel'] . '" disabled="">
			<input type="hidden" name="oldFileName" value="' . $fileArray['fileName'] . '">
			<span style="margin-right:5px;margin-left:5px;margin-top:-12px;" class="icon32 icon-arrowthick-e"/></span>		
			<input type="text" name="newFileLabel" value="' . $fileArray['fileLabel'] . '">
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-success"><i class="icon-pencil icon-white"></i> Rename</button>
	
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>
		</form>									
		<!-- /.modal -->
	
		<!-- Modal - DELETE DIALOG -->
		<form action="courtesy_tone.php" method="post">
	
		<div class="modal fade" id="deleteFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 class="modal-title" id="myModalLabel">Delete Courtesy Tone</h3>
		      </div>
		      <div class="modal-body">
			Are you sure that you want to delete the courtesy tone <strong>' . $fileArray['fileLabel'] . '</strong>? This cannot be undo!
			<input type="hidden" name="delfile[]" value="' . $fileArray['fileName'] . '">
			<input type="hidden" name="action" value="delete_file">
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-danger"><i class="icon-trash icon-white"></i> Delete</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>
		</form>
		<!-- /.modal -->';
	
		echo $html_string;
    }
} else {
	echo "no files";
}
?>
						  </tbody>
					  </table>            
					</div>
				</div><!--/span-->
			</div><!--/row-->

			</div><!-- End Expand Setting -->

			
<?php echo $html_modal;	?>

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