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


if (isset($_POST['action'])){
	if ($_POST['action'] == "upload_file") {
		// This is the handler for file uploads. It uploads the file to a temporary path then
		// converts it to the appropriate WAV formate and puts it in the custom identifications folder.
		
		$temp_dir = "/tmp/";
		$final_file_dir = "/usr/share/openrepeater/sounds/identification/";
		$maxFileSize = 45000000; // size in bytes

		$allowedExts = array("wav", "mp3");
		$temp_ext = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp_ext);
		$filename_no_ext = pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);

		if ((($_FILES["file"]["type"] == "audio/wav") || ($_FILES["file"]["type"] == "audio/mpeg") || ($_FILES["file"]["type"] == "audio/mp3")) && ($_FILES["file"]["size"] < $maxFileSize) && in_array($extension, $allowedExts)) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			} else {

				$soxInFile = $temp_dir . $_FILES["file"]["name"];
				$soxOutFile = $final_file_dir . $filename_no_ext . ".wav";

				if (file_exists($soxOutFile)) {
					$alert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>' . $filename_no_ext . ' already exists.</div>';
				} else {
					move_uploaded_file($_FILES["file"]["tmp_name"], $temp_dir . $_FILES["file"]["name"]);

					$soxCommand = 'sox "'.$soxInFile.'" -r16000 -b16 -esigned-integer -c1 "'.$soxOutPath.$soxOutFile.'"';
					exec($soxCommand);

					unlink($temp_dir . $_FILES["file"]["name"]);

					$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>You have successfully uploaded '.$_FILES["file"]["name"].' and it has been converted to the proper sound format.</div>';
				}
			}
		} else {
			$alert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><strong>' . $filename_no_ext . '</strong> is in an invalid file format. Please try again.</div>';
		}

	} else if ($_POST['action'] == "rename_file") {
		// NEED TO VALIDATE ACCEPTABLE CHARACTERS

		$path = "sounds/identification/";
		$oldfile = $path . $_POST["oldfile"] . ".wav"; 
		$newfile = $path . $_POST["newfile"] . ".wav";
		rename($oldfile, $newfile);
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>Rename ' . $oldfile . ' to ' . $newfile . '.</div>';
	} else if ($_POST['action'] == "delete_file") {
		// NEED TO BUILD IN CHECK TO SEE IF FILE IS CURRENTLY SELECTED 
		// IF IT IS RETURN AND ERROR.

		$file = $_POST["delfile"].".wav"; 
		$path = "sounds/identification/";
		unlink($path . $file);
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>File Deleted: ' . $file . '</div>';
	}
}

?>

<?php
$pageTitle = "Identification"; 

$customJS = "page-identification.js, morse-resampler.js, morse-XAudioServer.js, morse.js, morse-main.js"; // "file1.js, file2.js, ... "
$customCSS = "page-identification.css"; // "file1.css, file2.css, ... "

include_once("includes/get_settings.php");
include('includes/header.php');
$dbConnection->close();
?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<?php if (!$settings['callSign']) { ?><div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><strong>Oops!</strong> It's going to be a little hard to send identification without a callsign defined. Please <a href="settings.php">set it here</a>.</div><?php } else { ?>


			<form class="form-inline" role="form" action="functions/ajax_db_update.php" method="post" id="short_ID_settings">
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-time"></i> Short ID Settings</h2>
					</div>
					<div class="box-content">

						  <fieldset>

							<div class="control-group">
								<div class="controls mode">
									<span>Mode: </span>
									<select	id="ID_Short_Mode" name="ID_Short_Mode">
										<option value="disabled" <?php if ($settings['ID_Short_Mode'] == 'disabled') { echo "selected"; } ?>>-Disabled-</option>
										<option value="morse" <?php if ($settings['ID_Short_Mode'] == 'morse') { echo "selected"; } ?>>Morse Identification Only</option>
										<option value="voice" <?php if ($settings['ID_Short_Mode'] == 'voice') { echo "selected"; } ?>>Basic Voice Identification</option>
										<option value="custom" <?php if ($settings['ID_Short_Mode'] == 'custom') { echo "selected"; } ?>>Custom Identification</option>
									</select>
								</div>
							</div>


							<div id="short-id-grp-enable"> <!-- START enable/disable short ID setting -->

								<div id="general" style="display: none;"> <!-- Expand Setting -->
										<legend>General Settings</legend>
										<div class="control-group">
											<label class="control-label" for="ID_Short_IntervalMin">Short Identification Time Interval</label>
											<div class="controls">
											  <div class="input-append">
												<input id="ID_Short_IntervalMin" name="ID_Short_IntervalMin" size="16" type="text" value="<?php echo $settings['ID_Short_IntervalMin']; ?>" required><span class="add-on">mins</span>
											  </div>
											  <span class="help-inline">The purpose of the short identification is to just announce that the station is on the air. Typically just the callsign is transmitted. This value is the number of minutes between short identifications. For a repeater, a good value is ten minutes. Please make sure that you identify as frequently as required.</span>
											</div>
										</div>

								</div>
								
								<div id="morse" style="display: none;"> <!-- Expand Setting -->
									<legend>Morse Identification Only</legend>
										<p>Your short identifications will be made with morse code only, at the interval set above. The morse code settings (such as WPM, pitch, and suffix) can be set below in the <a href="#globalMorse">Global Morse ID Settings</a> section.</p>
								</div>
								
								<div id="voice" style="display: none;"> <!-- Expand Setting -->
									<legend>Basic Voice Identification</legend>
										<p>Your short identification will be made with the system callsign spelled out in phonetics at the interval set above. You have the option to append morse identification afterwards if you so desire.</p>
								</div>
								
								<div id="custom" style="display: none;"> <!-- Expand Setting -->
									<legend>Custom Identification</legend>
										<p>Your short identification will be made with a custom audio file that you can record and upload into the identifications library. You may upload as many MP3 or WAV files as you'd like. Upon upload, the system will convert these into the appropriate WAV format. You may preview the audio clips then select the one you would like to use for short ID. This will be played at the interval set above. You have the option to append morse identification afterwards if you so desire.</p>									
									
									<input type="hidden" name="ID_Short_CustomFile" id="ID_Short_CustomFile" value="<?php echo $settings['ID_Short_CustomFile']; ?>">
									
									<table class="table table-striped table-condensed bootstrap-datatable">
									  <thead>
										  <tr>
											  <th>Name</th>
											  <th>Preview</th>
											  <th>Status</th>
											  <th>Actions</th>
										  </tr>
									  </thead>   
									  <tbody>
			
			<?php
			
			$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/sounds/identification/';
			
			$files = array();
			
			if ($handle = opendir("/usr/share/openrepeater/sounds/identification")) {
				
				$totalFiles = 0;
				while (false !== ($file = readdir($handle))) {
					if ('.' === $file) continue;
					if ('..' === $file) continue;
					$files[] = $file;
					$totalFiles++;
				}
				closedir($handle);
			
				
				natsort($files);
			
				$file_counter = 0;
			
				foreach($files as $file) {	
			
				$file_counter++;
				$fullurl = $url . $file;
						
				$html_string = '
				<tr>
					<td><h2>' . str_replace('.wav','',$file) . '</h2></td>
			
					<td class="center">
					<audio controls>
						<source src="'.$fullurl.'" type=audio/mpeg>
						Your browser does not support the audio element.
					</audio>
					</td>
			
					<td class="center">';
					
				if ($settings['ID_Short_CustomFile'] == $file) {	
					$html_string .= '<span id="shortIDstatus'.$file_counter.'"class="label label-success">Active</span>';
				} else {
					//created the active flag and hides it so it can be show by javascript/ajax upon selection
					$html_string .= '<span id="shortIDstatus'.$file_counter.'"class="label label-success" style="display:none;">Active</span>';						}

				$html_string .= '
					</td>
			
					<td class="center">
			
						<button type="button" class="btn btn-success" name="ID_Long_CustomFile" onclick="setCustomShortID(\''.$file.'\','.$file_counter.','.$totalFiles.'); return false;"><i class="icon-ok icon-white"></i> Select</button>

						<!-- Button triggered modal -->
						<button id="shortRenameBtn'.$file_counter.'" class="btn" data-toggle="modal" data-target="#renameFile'.$file_counter.'">
							<i class="icon-pencil"></i> Rename</button>';
			
				if ($settings['ID_Short_CustomFile'] == $file) {	
					// Disable delete button if currently active
					$html_string .= '
						<!-- Button triggered modal -->
						<button id="shortDeleteBtn'.$file_counter.'" class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'" disabled>
							<i class="icon-trash icon-white"></i> Delete</button>';
				} else {
					$html_string .= '
						<!-- Button triggered modal -->
						<button id="shortDeleteBtn'.$file_counter.'" class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'">
							<i class="icon-trash icon-white"></i> Delete</button>';
				}

				$html_string .= '
					</td>
				</tr>';
				
				// Note that modal dialogs will be generated in long ID section below.
			
				echo $html_string;
			    }

			}
			?>
									  </tbody>
							    </table>            

								<!-- Button triggered modal -->
								<button class="btn" data-toggle="modal" data-target="#uploadFile"><i class="icon-arrow-up"></i> Upload</button>
																											
								</div>
								
								<div id="append" class="appendGrp" style="display: none;"> <!-- Expand Setting -->
									<div class="appendOption">
									<label for="ID_Short_AppendMorse">Append Morse ID: </label>
									<select	id="ID_Short_AppendMorse" name="ID_Short_AppendMorse">
										<option value="False" <?php if ($settings['ID_Short_AppendMorse'] == 'False') { echo "selected"; } ?>>No</option>
										<option value="True" <?php if ($settings['ID_Short_AppendMorse'] == 'True') { echo "selected"; } ?>>Yes</option>
									</select>
									</div>
								</div>

							</div> <!-- END enable/disable short ID setting -->
								
						</fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>


			<form class="form-inline" role="form" action="functions/ajax_db_update.php" method="post" id="long_ID_settings">
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-time"></i> Long ID Settings</h2>
					</div>
					<div class="box-content">

						  <fieldset>


							<div class="control-group">
								<div class="controls mode">
									<span>Mode: </span>
									<select	id="ID_Long_Mode" name="ID_Long_Mode">
										<option value="disabled" <?php if ($settings['ID_Long_Mode'] == 'disabled') { echo "selected"; } ?>>-Disabled-</option>
										<option value="morse" <?php if ($settings['ID_Long_Mode'] == 'morse') { echo "selected"; } ?>>Morse Identification Only</option>
										<option value="voice" <?php if ($settings['ID_Long_Mode'] == 'voice') { echo "selected"; } ?>>Basic Voice Identification</option>
										<option value="custom" <?php if ($settings['ID_Long_Mode'] == 'custom') { echo "selected"; } ?>>Custom Identification</option>
									</select>
								</div>
							</div>


							<div id="long-id-grp-enable"> <!-- START enable/disable Long ID setting -->

								<div id="general" style="display: none;"> <!-- Expand Setting -->
									<legend>General Settings</legend>
										<div class="control-group">
											<label class="control-label" for="ID_Long_IntervalMin">Long ID Time</label>
											<div class="controls">
											  <div class="input-append">
												<input id="ID_Long_IntervalMin" name="ID_Long_IntervalMin" size="16" type="text" value="<?php echo $settings['ID_Long_IntervalMin']; ?>" required><span class="add-on">mins</span>
											  </div>
											  <span class="help-inline">The number of minutes between long identifications. The purpose of the long identification is to transmit some more information about the station. A good value for a repeater is every 60 minutes.</span>
											</div>
										</div>
								</div>



								<div id="morse" style="display: none;"> <!-- Expand Setting -->
									<legend>Morse Identification Only</legend>
										<p>Your long identifications will be made with morse code only, at the interval set above. The morse code settings (such as WPM, pitch, and suffix) can be set below in the <a href="#globalMorse">Global Morse ID Settings</a> section.</p>
								</div>

								<div id="voice" style="display: none;"> <!-- Expand Setting -->
									<legend>Basic Voice Identification</legend>
										<p>Your long identification will be made with the system callsign spelled out in phonetics at the interval set above. You have the option to append other items such as the current time and morse identification afterwards if you so desire.</p>
								</div>

								<div id="custom" style="display: none;"> <!-- Expand Setting -->
									<legend>Custom Identification</legend>
										<p>Your long identification will be made with a custom audio file that you can record and upload into the identifications library. You may upload as many MP3 or WAV files as you'd like. Upon upload, the system will convert these into the appropriate WAV format. You may preview the audio clips then select the one you would like to use for long ID. This will be played at the interval set above. You have the option to append other items such as the current time and morse identification afterwards if you so desire.</p>									

									
									
									<input type="hidden" name="ID_Long_CustomFile" id="ID_Long_CustomFile" value="<?php echo $settings['ID_Long_CustomFile']; ?>">
									
									<table class="table table-striped table-condensed bootstrap-datatable">
									  <thead>
										  <tr>
											  <th>Name</th>
											  <th>Preview</th>
											  <th>Status</th>
											  <th>Actions</th>
										  </tr>
									  </thead>   
									  <tbody>
			
			<?php
			
			$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/sounds/identification/';
			
			$files = array();
			
			if ($handle = opendir("/usr/share/openrepeater/sounds/identification")) {
				
				$totalFiles = 0;
				while (false !== ($file = readdir($handle))) {
					if ('.' === $file) continue;
					if ('..' === $file) continue;
					$files[] = $file;
					$totalFiles++;
				}
				closedir($handle);
			
				
				natsort($files);
			
				$file_counter = 0;
				$html_modal = '';

			
				foreach($files as $file) {	
			
				$file_counter++;
				$fullurl = $url . $file;
						
				$html_string = '
				<tr>
					<td><h2>' . str_replace('.wav','',$file) . '</h2></td>
			
					<td class="center">
					<audio controls>
						<source src="'.$fullurl.'" type=audio/mpeg>
						Your browser does not support the audio element.
					</audio>
					</td>
			
					<td class="center">';

					if ($settings['ID_Long_CustomFile'] == $file) {	
						$html_string .= '<span id="longIDstatus'.$file_counter.'"class="label label-success">Active</span>';
					} else {
						//created the active flag and hides it so it can be show by javascript/ajax upon selection
						$html_string .= '<span id="longIDstatus'.$file_counter.'"class="label label-success" style="display:none;">Active</span>';						}

				$html_string .= '
					</td>
			
					<td class="center">
			
						<button type="button" class="btn btn-success" name="ID_Long_CustomFile" onclick="setCustomLongID(\''.$file.'\','.$file_counter.','.$totalFiles.'); return false;"><i class="icon-ok icon-white"></i> Select</button>
			
						<!-- Button triggered modal -->
						<button class="btn" data-toggle="modal" data-target="#renameFile'.$file_counter.'">
							<i class="icon-pencil"></i> 
							Rename
						</button>';
			
					if ($settings['ID_Long_CustomFile'] == $file) {	
						// Disable delete button if currently active
						$html_string .= '
						<!-- Button triggered modal -->
						<button id="longDeleteBtn'.$file_counter.'" class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'" disabled>
							<i class="icon-trash icon-white"></i> Delete</button>';
					} else {
						$html_string .= '
						<!-- Button triggered modal -->
						<button id="longDeleteBtn'.$file_counter.'" class="btn btn-danger" data-toggle="modal" data-target="#deleteFile'.$file_counter.'">
							<i class="icon-trash icon-white"></i> Delete</button>';
					}

				$html_string .= '
					</td>
				</tr>';
			
				$html_modal .= '
			
				<!-- Modal - RENAME DIALOG -->
				<form action="identification.php" method="post">
			
				<div class="modal fade" id="renameFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
					<h3 class="modal-title" id="myModalLabel">Rename Custom Identification Clip</h3>
				      </div>
				      <div class="modal-body">
					<input type="hidden" name="action" value="rename_file">
					<input class="input disabled" id="disabledInput" type="text" placeholder="' . str_replace('.wav','',$file) . '" disabled="">
					<input type="hidden" name="oldfile" value="' . str_replace('.wav','',$file) . '">
					<span style="margin-right:5px;margin-left:5px;margin-top:-12px;" class="icon32 icon-arrowthick-e"/></span>		
					<input type="text" name="newfile" value="' . str_replace('.wav','',$file) . '">
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
				<form action="identification.php" method="post">
			
				<div class="modal fade" id="deleteFile'.$file_counter.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myModalLabel">Delete Custom Identification Clip</h3>
				      </div>
				      <div class="modal-body">
					Are you sure that you want to delete the custom identification clip <strong>' . str_replace('.wav','',$file) . '</strong>? This cannot be undo!
					<input type="hidden" name="delfile" value="' . str_replace('.wav','',$file) . '">
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

			}
			?>
									  </tbody>
							    </table>            

								<!-- Button triggered modal -->
								<button class="btn" data-toggle="modal" data-target="#uploadFile"><i class="icon-arrow-up"></i> Upload</button>
									
								</div>
								
								
								<div id="append" class="appendGrp" style="display: none;"> <!-- Expand Setting -->

									<div class="appendOption">
									<label for="ID_Long_AppendTime">Announce Current Time: </label>
									<select	id="ID_Long_AppendTime" name="ID_Long_AppendTime">
										<option value="False" <?php if ($settings['ID_Long_AppendTime'] == 'False') { echo "selected"; } ?>>No</option>
										<option value="True" <?php if ($settings['ID_Long_AppendTime'] == 'True') { echo "selected"; } ?>>Yes</option>
									</select>
									</div>

									<?php
									/* FUTURE UPDATE TO ANNOUNCE CTCSS TONE
									<div class="appendOption">
									<label for="ID_Long_AppendTone">Announce CTCSS (PL) Tone: </label>
									<select	id="ID_Long_AppendTone" name="ID_Long_AppendTone">
										<option value="False" <?php if ($settings['ID_Long_AppendTone'] == 'False') { echo "selected"; } ?>>No</option>
										<option value="True" <?php if ($settings['ID_Long_AppendTone'] == 'True') { echo "selected"; } ?>>Yes</option>
									</select>
									</div>
									*/
									?>

									<div class="appendOption">
									<label for="ID_Long_AppendMorse">Append Morse ID: </label>
									<select	id="ID_Long_AppendMorse" name="ID_Long_AppendMorse">
										<option value="False" <?php if ($settings['ID_Long_AppendMorse'] == 'False') { echo "selected"; } ?>>No</option>
										<option value="True" <?php if ($settings['ID_Long_AppendMorse'] == 'True') { echo "selected"; } ?>>Yes</option>
									</select>
									</div>

								</div>

							</div> <!-- END enable/disable Long ID setting -->

						
						  </fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>


			<div id="morse-id-grp-enable" style="display: none;"> <!-- START enable/disable Morse ID setting -->
			<form name="morse_form" class="sform form-inline" role="form" action="functions/ajax_db_update.php" method="post" id="morse_ID_settings">
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-volume-up"></i> Global Morse ID Settings</h2>
					</div>
					<div class="box-content">

						<a name="globalMorse"></a>

						  <fieldset>

							<p>Use the following callsign variation for ALL morse code identification.</p>
							
							<input type="radio" name="ID_Morse_Suffix" id="suffix1" value="" <?php if ($settings['ID_Morse_Suffix'] == '') { echo "checked"; } ?>>
							<label class="radio-inline" for="suffix1" style="margin-right: 50px;">N3MBH</label>
							
							<input type="radio" name="ID_Morse_Suffix" id="suffix2" value="/R" <?php if ($settings['ID_Morse_Suffix'] == '/R') { echo "checked"; } ?>>
							<label class="radio-inline" for="suffix2" style="margin-right: 50px;"> <?php echo strtoupper($settings['callSign']); ?><strong>/R</strong></label>
							
							<input type="radio" name="ID_Morse_Suffix" id="suffix3" value="/RPT" <?php if ($settings['ID_Morse_Suffix'] == '/RPT') { echo "checked"; } ?>>
							<label class="radio-inline" for="suffix3" style="margin-right: 50px;"> <?php echo strtoupper($settings['callSign']); ?><strong>/RPT</strong></label>	
							

							<hr>
							<p>Set the speed (WPM) and tone of ALL morse code identification.</p>

							<select name="ID_Morse_WPM">
								<option value="10" <?php if ($settings['ID_Morse_WPM'] == '10') { echo "selected"; } ?>>10 WPM</option>
								<option value="15" <?php if ($settings['ID_Morse_WPM'] == '15') { echo "selected"; } ?>>15 WPM</option>
								<option value="20" <?php if ($settings['ID_Morse_WPM'] == '20') { echo "selected"; } ?>>20 WPM</option>
								<option value="25" <?php if ($settings['ID_Morse_WPM'] == '25') { echo "selected"; } ?>>25 WPM</option>
								<option value="30" <?php if ($settings['ID_Morse_WPM'] == '30') { echo "selected"; } ?>>30 WPM</option>
							</select>
							
							<select name="ID_Morse_Pitch" id="pitch">
								<option value="400" <?php if ($settings['ID_Morse_Pitch'] == '400') { echo "selected"; } ?>>400 Hz</option>
								<option value="600" <?php if ($settings['ID_Morse_Pitch'] == '600') { echo "selected"; } ?>>600 Hz</option>
								<option value="800" <?php if ($settings['ID_Morse_Pitch'] == '800') { echo "selected"; } ?>>800 Hz</option>
								<option value="1000" <?php if ($settings['ID_Morse_Pitch'] == '1000') { echo "selected"; } ?>>1000 Hz</option>
								<option value="1200" <?php if ($settings['ID_Morse_Pitch'] == '1200') { echo "selected"; } ?>>1200 Hz</option>
							</select>
							
							<input class="btn btn-primary" id="play" type="button" value="Preview" onClick="javascript:playM()"/>
							
							<input type="hidden" name="call" id="callsign" value="<?php echo strtoupper($settings['callSign']); ?>">
							<input type="hidden" id="morseCallsign" name="morseCallsign">
							<input type="hidden" name="output">
							<p><em>NOTE: The preview option is only a browser simulation of what the audio should sound like on the live repeater.</em></p>

						  </fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>

			</div> <!-- END enable/disable Morse ID setting -->



			<?php } // Ending tag for check of callsign definition ?>

			
<?php if(isset($html_modal)) { echo $html_modal; } ?>

<!-- Modal - UPLOAD DIALOG -->

<form action="identification.php" method="post" enctype="multipart/form-data">
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h3 class="modal-title" id="myModalLabel">Upload Custom Identification Clip</h3>
      </div>
      <div class="modal-body">
		<p>Upload your own custom recorded audio files for identification. The file should be in MP3 or WAV format and any excess 'dead air' should be trimmed off of the clip and audio levels normalized.</p>
		<input type="hidden" name="action" value="upload_file">
		<input type="file" name="file" id="file" required>
		<p><em><br>DO NOT UPLOAD FILES THAT CONTAIN MUSIC, CLIPS OF MUSIC, OR OTHER COPYRIGHTED MATERIAL...IT'S ILLEGAL.</em></p>
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