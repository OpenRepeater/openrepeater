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

if( isset($_POST['action']) ) {
	if ($_POST['action'] == 'loadBoardPreset'){
		$board_selected = $_POST['board_id'];
		$board_preset = new BoardPresets();
		$board_name = $board_preset->load_board_settings($board_selected);
	
		$msgText = "The presets have been successfully loaded for the <strong>" . $board_name . "</strong> board.";
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';
	}
}
?>

<?php
$board_presets = new BoardPresets();
$board_select_options = $board_presets->get_select_options();


$pageTitle = "Interface"; 

$customJS = "page-ports.js"; // "file1.js, file2.js, ... "
$customCSS = "page-ports.css"; // "file1.css, file2.css, ... "

include('includes/header.php');
$ports = $Database->get_ports();

$SoundDevices = new SoundDevices();
$device_list = $SoundDevices->get_device_list();
$device_in_count = $SoundDevices->get_device_in_count();
$device_out_count = $SoundDevices->get_device_out_count();

// Disabled temporarily for speed.
// $audio_details = $SoundDevices->get_device_list('details');


#### PHP LOOPS TO READ AUDIO DEVICES AND SAVE TO PHP VARIABLES AS SELECT OPTIONS TO PASS TO JAVASCRIPT
// Inputs
$phpAudioInputOptions = null;
for ($device = 0; $device <  count($device_list); $device++) {
   if ($device_list[$device]['direction'] == "IN") {
		$rxValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
		$rxSelected = "";
		$phpAudioInputOptions .= '<option value="'.$rxValue.'"'.$rxSelected.'>INPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')<\/option>';
	}
}

// Outputs
$phpAudioOutputOptions = null;
for ($device = 0; $device <  count($device_list); $device++) {
   if ($device_list[$device]['direction'] == "OUT") {
		$txValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
		$txSelected = "";
		$phpAudioOutputOptions .= '<option value="'.$txValue.'"'.$txSelected.'>OUTPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')<\/option>';
	}
}
?>

<!-- PASS PHP VARIABLES ABOVE INTO JAVASCRIPT VARIABLES, USED WHEN NEW PORT FIELDS ARE ADDED DYNAMICALLY -->
<script type="text/javascript">
var jsAudioInputOptions='<?php echo $phpAudioInputOptions; ?>';
var jsAudioOutputOptions='<?php echo $phpAudioOutputOptions; ?>';
</script>


			<div class="alert alert-success"><strong>DEPRECIATED: </strong>Most of the items on this page have been depreciated. Please view <a href="/ports.php">interfaces page</a> on new UI for more options. This page should only be used to select board presets which is the only feature not added in new UI yet.</div>

			<div id="alertWrap"><?php if(isset($alert)) { echo $alert; } ?></div>



<!-- Code Removed -->

			
			<form class="form-horizontal" role="form" action="ports.php" method="post" id="loadBoardPreset" name="loadBoardPreset" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-tasks"></i> Board Presets</h2>
					</div>
					<div class="box-content">

						<fieldset>
							<div class="control-group">
								<label class="control-label" for="board_id">Select Board Preset:</label>
								<div class="controls">
									<select id="board_id" name="board_id" required>
										<option value="" selected>Select One...</option>
										<?php echo $board_select_options; ?>
									</select>
									<input type="hidden" name="action" value="loadBoardPreset">											
									<span class="help-inline"> Choose a supported interface board to load presets.</span>
								</div>
							</div>
						</fieldset>


						<!-- Button triggered modal -->
						<button type="button" class="btn" data-toggle="modal" data-target="#loadPreset"><i class="icon-circle-arrow-up"></i> Load Preset</button>
						
						<!-- Modal - LOAD BOARD PRESET DIALOG -->
						<div class="modal fade" id="loadPreset" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
							<h3 class="modal-title" id="myModalLabel">Load Presets?</h3>
						      </div>
							      <div class="modal-body">
								  	<p>Are you sure that you would like to load these presets? All the current ports will be removed and replaced by the settings of your selected board. Note that loading a preset will disable any modules that you currently have enabled. If the board that you have selected has any custom module settings, existing settings for that module may be overwrite by new settings. Proceed only if you are certain this is OK.</p>
								  	<p><strong>Note: If you board does not have onboard sound and uses more than one USB sound card, you may need to adjust your sound settings depending on how you have the USB devices plugged into your USB ports and/or how they are detected by Linux.</strong></p>
								</div>
						      <div class="modal-footer">
								<button class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button class="btn btn-success" onclick="loadBoardPreset()"><i class="icon-circle-arrow-up icon-white"></i> Load</button>
						      </div>
						    </div><!-- /.modal-content -->
						  </div><!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>






			<!-- Button triggered modal -->
			<button type="button" class="btn" data-toggle="modal" data-target="#advancedDetails"><i class="icon-list-alt"></i> Advanced Details</button>
			
			<!-- Modal - ADVANCED DETAIL DIALOG -->
			<div class="modal fade" id="advancedDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
				<h3 class="modal-title" id="myModalLabel">Advanced Details</h3>
			      </div>
			      <div class="modal-body">
					<?php 
						//echo $audio_details;
						echo "Disabled temporarily for speed."
					?>
			      </div>
			      <div class="modal-footer">
				  	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->



			<!-- Hidden field for javascript to get number of detected audio devices -->
			<input type="hidden" id="detectedRX" value="<?php echo $device_in_count; ?>">
			<input type="hidden" id="detectedTX" value="<?php echo $device_in_count; ?>">

			
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>