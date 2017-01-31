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


$pageTitle = "Ports"; 

$customJS = "page-ports.js"; // "file1.js, file2.js, ... "
$customCSS = "page-ports.css"; // "file1.css, file2.css, ... "

include('includes/get_sound.php');
include('includes/header.php');
include('includes/get_ports.php');
$dbConnection->close();

?>



			<div id="alertWrap"></div>

			<form class="form-inline" role="form" action="functions/port_db_update.php" method="post" id="portsUpdate">

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-cog"></i> Port Settings</h2>
					</div>
					<div class="box-content">
																			
						<div id="portsWrap">
						<?php 
						$idNum = 1; // This will be replease by a loop to load exsiting values 
						
						if ($ports) {
							foreach($ports as $cur_port) { ?>

								<p class="portRow<?php if ($idNum == 1) { echo ' first'; } else { echo ' additional'; } ?>">
									<span>
									<input type="text" name="portNum[]" value="<?php echo $idNum; ?>" style="width:15px;display:none;">
									
									<input id="portLabel<?php echo $idNum; ?>" type="text" required="required" name="portLabel[]" placeholder="Port Label" value="<?php echo $cur_port['portLabel']; ?>" class="portLabel">
									</span>
									<span class="rx">
									<select id="rxMode<?php echo $idNum; ?>" name="rxMode[]" class="rxMode">
										<option value="vox" <?php if ($cur_port['rxMode'] == 'vox') { echo "selected"; } ?>>VOX</option>
										<option value="gpio" <?php if ($cur_port['rxMode'] == 'gpio') { echo "selected"; } ?>>COS</option>
									</select>
									<input id="rxGPIO<?php echo $idNum; ?>" type="text" required="required" name="rxGPIO[]" placeholder="GPIO"  value="<?php echo $cur_port['rxGPIO']; ?>" class="rxGPIO">
									<input type="hidden" name="rxGPIO_active[]" value="low">
									<select id="rxAudioDev<?php echo $idNum; ?>" name="rxAudioDev[]" class="rxAudioDev">
										<option>---</option>
										<?php
										for ($device = 0; $device <  count($device_list); $device++) {
										   if ($device_list[$device]['direction'] == "IN") {
												$rxValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
												$currentRX = $cur_port['rxAudioDev'];
												if ($rxValue == $currentRX) { $rxSelected = " selected"; } else { $rxSelected = ""; }
												echo '<option value="'.$rxValue.'"'.$rxSelected.'>INPUT: '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
											}
										}
										?>
									</select>
									</span>
									<span class="tx">
									<input id="txGPIO<?php echo $idNum; ?>" type="text" required="required" name="txGPIO[]" placeholder="GPIO" value="<?php echo $cur_port['txGPIO']; ?>" class="txGPIO">
									<input type="hidden" name="txGPIO_active[]" value="high">
									<select id="txAudioDev<?php echo $idNum; ?>" name="txAudioDev[]" class="txAudioDev">
										<option>---</option>
										<?php
										for ($device = 0; $device <  count($device_list); $device++) {
										   if ($device_list[$device]['direction'] == "OUT") {
												$txValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
												$currentTX = $cur_port['txAudioDev'];
												if ($txValue == $currentTX) { $txSelected = " selected"; } else { $txSelected = ""; }
												echo '<option value="'.$txValue.'"'.$txSelected.'>OUTPUT: '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
											}
										}
										?>
									</select>
									</span>
									<?php if ($idNum == 1) { 
										echo '<a href="#" id="addPort">Add</a>';
									} else {
										echo '<a href="#" id="removePort">Remove</a>';
									} ?>
								</p>


							<?php 
							$idNum++;
							}	
						} else {
							echo "there are no ports...";
						}
						?>

						</div>

						<div id="portCount"></div>

						<br>
						
						<div class="alert alert-info">
						<strong>NOTE: </strong> While you can set more than one port in here, only the first port is currently supported. We are waiting on some updates to the core code so we can build our logic to handle more than one port.
						</div>

						<div class="alert alert-danger">
						<strong>WARNING:</strong> The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible. 
						</div>


						<div class="form-actions">
						  <input type="hidden" name="action" value="update">		
						  <button type="button" class="btn btn-primary" onclick="updateDB()">Update Ports</button>
						</div>
						
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			</form>
			
			<!-- Hidden field for javascript to get number of detected audio devices -->
			<input type="hidden" id="detectedRX" value="<?php echo $device_in_count; ?>">
			<input type="hidden" id="detectedTX" value="<?php echo $device_in_count; ?>">
			
			


			<!-- Button triggered modal -->
			<button class="btn" data-toggle="modal" data-target="#advancedDetails"><i class="icon-list-alt"></i> Advanced Details</button>
			
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


			
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>