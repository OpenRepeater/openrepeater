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

$audio_details = $SoundDevices->get_device_list('details');


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


			<div id="alertWrap"><?php if(isset($alert)) { echo $alert; } ?></div>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="settingsUpdate" name="settingsUpdate" >
				<select id="orp_Mode" name="orp_Mode">
					<option value="repeater"<?php if ($settings['orp_Mode'] == 'repeater') { echo ' selected'; } ?>>1 Repeater + Simplex Links (all ports linked)</option>
					<option value="simplex"<?php if ($settings['orp_Mode'] == 'simplex') { echo ' selected'; } ?>>Simplex Node</option>
				</select>
				<label class="control-label" for="orp_Mode">Logic Mode for Port(s)</label>
			</form>



			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-cog"></i> Port Settings</h2>
					</div>
					<div class="box-content">
																			
						<div id="portsWrap">
						<?php 
						$idNum = 1;
						
						if ($ports) {
							foreach($ports as $cur_port) { ?>

								<?php if ($cur_port['portType'] == 'GPIO') { ?>
									<div id="port<?=$cur_port['portNum']?>wrap">
									<form id="port<?=$cur_port['portNum']?>form" class="portForm form-inline" role="form" action="functions/port_db_update.php" method="post">
										
									<input type="hidden" name="portNum" value="<?=$cur_port['portNum']?>">

									<input type="hidden" name="portType[<?=$cur_port['portNum']?>]" value="GPIO">
									<p id="port<?=$cur_port['portNum']?>" class="portRow<?php if ($idNum == 1) { echo ' first'; } else { echo ' additional'; } ?>" data-port-number="<?=$cur_port['portNum']?>">
										<span>
										
										<input id="portLabel<?=$cur_port['portNum']?>" type="text" required="required" name="portLabel[<?=$cur_port['portNum']?>]" placeholder="Port Label" value="<?php echo $cur_port['portLabel']; ?>" class="portLabel">
										</span>
										<span class="rx">
										<input id="rxGPIO<?=$cur_port['portNum']?>" type="text" required="required" name="rxGPIO[<?=$cur_port['portNum']?>]" placeholder="GPIO"  value="<?php echo $cur_port['rxGPIO']; ?>" class="rxGPIO">
										<select id="rxAudioDev<?=$cur_port['portNum']?>" name="rxAudioDev[<?=$cur_port['portNum']?>]" class="rxAudioDev">
											<option>---</option>
											<?php
											for ($device = 0; $device <  count($device_list); $device++) {
											   if ($device_list[$device]['direction'] == "IN") {
													$rxValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
													$currentRX = $cur_port['rxAudioDev'];
													if ($rxValue == $currentRX) { $rxSelected = " selected"; } else { $rxSelected = ""; }
													echo '<option value="'.$rxValue.'"'.$rxSelected.'>INPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
												}
											}
											?>
										</select>
										</span>
										<span class="tx">
										<input id="txGPIO<?=$cur_port['portNum']?>" type="text" required="required" name="txGPIO[<?=$cur_port['portNum']?>]" placeholder="GPIO" value="<?php echo $cur_port['txGPIO']; ?>" class="txGPIO">
										<select id="txAudioDev<?=$cur_port['portNum']?>" name="txAudioDev[<?=$cur_port['portNum']?>]" class="txAudioDev">
											<option>---</option>
											<?php
											for ($device = 0; $device <  count($device_list); $device++) {
											   if ($device_list[$device]['direction'] == "OUT") {
													$txValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
													$currentTX = $cur_port['txAudioDev'];
													if ($txValue == $currentTX) { $txSelected = " selected"; } else { $txSelected = ""; }
													echo '<option value="'.$txValue.'"'.$txSelected.'>OUTPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
												}
											}
											?>
										</select>
										</span>
	
										<!-- Button triggered modal -->
										<button type="button" class="btn port_settings" data-toggle="modal" data-target="#portDetails<?=$cur_port['portNum']?>" title="Extra settings for this port"><i class="icon-cog"></i></button>
	
										<?php if ($idNum == 1) { 
											echo '<a href="#" class="addPort">Add</a>';
										} else {
											echo '<a href="#" class="removePort">Remove</a>';
										} ?>								
									</p>

									<!-- Modal - ADVANCED DETAIL DIALOG -->
									<div class="modal fade" id="portDetails<?=$cur_port['portNum']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-header">
										  <h3 class="modal-title" id="myModalLabel">Extra Settings (Port <?=$cur_port['portNum']?>)</h3>
									      </div>
									      <div class="modal-body">
										  	<fieldset>
											  <div class="control-group">
												<label class="control-label" for="rxGPIO_active<?=$cur_port['portNum']?>">RX Control Mode</label>
												<div class="controls">
													<select id="rxMode<?=$cur_port['portNum']?>" name="rxMode[<?=$cur_port['portNum']?>]" class="rxMode">
														<option value="cos" <?php if ($cur_port['rxMode'] == 'cos') { echo "selected"; } ?>>COS</option>
														<option value="vox" <?php if ($cur_port['rxMode'] == 'vox') { echo "selected"; } ?>>VOX</option>
													</select>
												</div>
											  </div>
											  <div style="clear: both;"></div>
	
											  <br>
											  <div class="alert alert-danger">
												<strong>WARNING:</strong> The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible. 
											  </div>
	
											  <div class="control-group">
												<label class="control-label" for="rxGPIO_active<?=$cur_port['portNum']?>">RX Active GPIO State</label>
												<div class="controls">
												  <select id="rxGPIO_active<?=$cur_port['portNum']?>" name="rxGPIO_active[<?=$cur_port['portNum']?>]" class="rxGPIO_active">
												  	<option value="high" <?php if ($cur_port['rxGPIO_active'] == 'high') { echo ' selected'; } ?>>Active High</option>
												  	<option value="low" <?php if ($cur_port['rxGPIO_active'] == 'low') { echo ' selected'; } ?>>Active Low</option>
												  </select>
												</div>
											  </div>
											  <div style="clear: both;"></div>
											  
											  <div class="control-group">
												<label class="control-label" for="txGPIO_active<?=$cur_port['portNum']?>">TX Active GPIO State</label>
												<div class="controls">
												  <select id="txGPIO_active<?=$cur_port['portNum']?>" name="txGPIO_active[<?=$cur_port['portNum']?>]" class="txGPIO_active">
												  	<option value="high" <?php if ($cur_port['txGPIO_active'] == 'high') { echo ' selected'; } ?>>Active High</option>
												  	<option value="low" <?php if ($cur_port['txGPIO_active'] == 'low') { echo ' selected'; } ?>>Active Low</option>
												  </select>
												</div>
											  </div>
											  <div style="clear: both;"></div>
	
											  <div class="control-group">
												<label class="control-label" for="portEnabled<?=$cur_port['portNum']?>">Port Enabled/Disabled</label>
												<div class="controls">
												  <select id="portEnabled<?=$cur_port['portNum']?>" name="portEnabled[<?=$cur_port['portNum']?>]">
												  	<option value="1" <?php if ($cur_port['portEnabled'] == '1') { echo ' selected'; } ?>>Enabled</option>
												  	<option value="0" <?php if ($cur_port['portEnabled'] == '0') { echo ' selected'; } ?>>Disabled</option>
												  </select>
												</div>
											  </div>
											  <div style="clear: both;"></div>
	
											  <div class="control-group">
												<label class="control-label" for="linkGroup<?=$cur_port['portNum']?>">Link Group</label>
												<div class="controls">
												  <select id="linkGroup<?=$cur_port['portNum']?>" name="linkGroup[<?=$cur_port['portNum']?>]">
												  	<option value="" <?php if ($cur_port['linkGroup'] == '') { echo ' selected'; } ?>>None</option>
												  	<option value="1" <?php if ($cur_port['linkGroup'] == '1') { echo ' selected'; } ?>>Group 1</option>
												  	<option value="2" <?php if ($cur_port['linkGroup'] == '2') { echo ' selected'; } ?>>Group 2</option>
												  	<option value="3" <?php if ($cur_port['linkGroup'] == '3') { echo ' selected'; } ?>>Group 3</option>
												  	<option value="4" <?php if ($cur_port['linkGroup'] == '4') { echo ' selected'; } ?>>Group 4</option>
												  </select>
												</div>
											  </div>
											  <div style="clear: both;"></div>
	
											</fieldset>
									      </div>
									      <div class="modal-footer">
										  	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-remove"></i> Close</button>
									      </div>
									    </div><!-- /.modal-content -->
									  </div><!-- /.modal-dialog -->
									</div>
									<!-- /.modal -->

									</form>
									</div>

								<?php } else { ?>
									<div id="port<?=$cur_port['portNum']?>wrap">
									<p id="port<?=$cur_port['portNum']?>" class="portRow<?php if ($idNum == 1) { echo ' first'; } else { echo ' additional'; } ?> special" data-port-number="<?=$cur_port['portNum']?>">
										<span>
											<strong>Port #<?=$cur_port['portNum']?>:</strong> <?=$cur_port['portLabel']?>
										</span>
		
										<?php if ($idNum == 1) { 
											echo '<a href="#" class="addPort">Add</a>';
										} else {
											echo '<a href="#" class="removePort">Remove</a>';
										} ?>								
									</p>
									</div>

								<?php } ?>


							<?php 
							$idNum++;
							}	
						} else {
							echo '<div id="noPorts">';
							echo '<a href="#" class="addPort">Add first port</a>';
							echo '</div>';
						}
						?>

						</div>

						<div id="portCount"></div>
						
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
			
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
					<?php echo $audio_details; ?>
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