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
?>

<?php
$pageTitle = "General Settings"; 

$customJS = "page-settings.js"; // "file1.js, file2.js, ... "

// include_once("includes/get_ctcss.php");

include('includes/header.php');
$ctcss = $Database->get_ctcss();

?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="settingsUpdate" name="settingsUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> General Repeater Settings</h2>
					</div>
					<div class="box-content">

						  <fieldset>
							<legend>Basic Settings</legend>

							  <div class="control-group">
								<label class="control-label" for="callSign">Call Sign</label>
								<div class="controls">
								  <input class="input-xlarge" style="text-transform: uppercase" id="callSign" type="text" name="callSign" value="<?php echo $settings['callSign']; ?>" required>
								  <span class="help-inline">This call sign will be used for identification.</span>
								</div>
							  </div>


							  <div class="control-group">
								<label class="control-label" for="txTailValueSec">TX Tail</label>
								<div class="controls">
								  <div class="input-append">
									<input id="txTailValueSec" name="txTailValueSec" size="16" type="text" value="<?php echo $settings['txTailValueSec']; ?>" required><span class="add-on">secs</span>
								  </div>
								  <span class="help-inline">The amount of time before the transmitter drops</span>
								</div>
							  </div>
							  

							<legend>Timeout Settings</legend>

							  <div class="control-group">
								<label class="control-label" for="repeaterTimeoutSec">Repeater Timeout</label>
								<div class="controls">
								  <div class="input-append">
									<input id="repeaterTimeoutSec" name="repeaterTimeoutSec" size="16" type="text" value="<?php echo $settings['repeaterTimeoutSec']; ?>"><span class="add-on">secs</span>
								  </div>
								  <span class="help-inline">(i.e. 4 minutes would equal 240 seconds)</span>
								</div>
							  </div>

							<legend>DTMF Remote Disable</legend>

							  <div class="control-group">
								<label class="control-label" for="repeaterDTMF_disable">Use Remote Disable?</label>
								<div class="controls">
								  <select id="repeaterDTMF_disable" name="repeaterDTMF_disable">
								  	<option value="False"<?php if ($settings['repeaterDTMF_disable'] == 'False') { echo ' selected'; } ?>>Disable Function</option>
								  	<option value="True"<?php if ($settings['repeaterDTMF_disable'] == 'True') { echo ' selected'; } ?>>Enable Function</option>
								  </select>
								  <span class="help-inline">Enable this to be able to disable the transmitter by entering DTMF command.</span>
								</div>
							  </div>
 
							  <div id="dtmf_disable" style="display: none;"> <!-- Expand Setting -->
 
							  <div class="control-group">
								<label class="control-label" for="repeaterDTMF_disable_pin">Pin Code</label>
								<div class="controls">
								  <input class="input-xlarge" id="repeaterDTMF_disable_pin" type="text" name="repeaterDTMF_disable_pin" value="<?php echo $settings['repeaterDTMF_disable_pin']; ?>" maxlength="10" required>
								  <span class="help-inline">The pin will be used at part of DTMF command. This should be unique and the longer the better.</span>
								</div>
							  </div>
							  
							  <p>For command detials, visit the <a href="dtmf.php#remoteDisable">Remote DMTF Disable</a> section on the DTMF Reference page.</p>
							  
							  </div> <!-- END Expand Setting -->

							<legend>CTCSS Settings</legend>
							<p>These are settings experimental. It is recommend that you leave these set to none and set your CTCSS tones in your radios.<br></p>

							  <div class="control-group">
								<label class="control-label" for="rxTone">RX Tone (Hz)</label>
								<div class="controls">
								  <select id="rxTone" name="rxTone" data-rel="chosen">
									<?php 
										$option_string = '<option value=""';
										if ($settings['rxTone'] == '') { 
											$option_string .= ' selected';
										}
										$option_string .= '>(none)</option>';
										echo $option_string;

										foreach($ctcss as $freq => $code) {
											$option_string = '<option value="'.$freq.'"';
											if ($settings['rxTone'] == $freq) { 
												$option_string .= ' selected';
											}
											$option_string .= '>'.$freq.'</option>';
											echo $option_string;
										}
									?>
								  </select>
								  <span class="help-inline">The CTCSS tone you have to transmit to "open" the repeater.</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="txTone">TX Tone (Hz)</label>
								<div class="controls">
								  <select id="txTone" name="txTone" data-rel="chosen">
									<?php 
										$option_string = '<option value=""';
										if ($settings['txTone'] == '') { 
											$option_string .= ' selected';
										}
										$option_string .= '>(none)</option>';
										echo $option_string;

										foreach($ctcss as $freq => $code) {
											$option_string = '<option value="'.$freq.'"';
											if ($settings['txTone'] == $freq) { 
												$option_string .= ' selected';
											}
											$option_string .= '>'.$freq.'</option>';
											echo $option_string;
										}
									?>
								  </select>
								  <span class="help-inline">The CTCSS tone you need to hear the repeater.</span>
								</div>
							  </div>

						  </fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>   

    
<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>