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

	$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
	
	foreach($_POST as $key=>$value){  
		if ($key != "action") {
			$query = $db->exec("UPDATE settings SET value='$value' WHERE keyID='$key'");
		}
	}
   $db->close();
	
	$msgText = "The settings have been updated successfully!";
	$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';

	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
	$memcache_obj = new Memcache;
	$memcache_obj->connect('localhost', 11211);
	$memcache_obj->set('update_settings_flag', 1, false, 0);
}
?>

<?php
$pageTitle = "General Settings"; 

$customJS = "page-settings.js"; // "file1.js, file2.js, ... "

include_once("includes/get_settings.php");
include_once("includes/get_ctcss.php");
$dbConnection->close();

include('includes/header.php');

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
								  <input class="input-xlarge" style="text-tran: uppercase" id="callSign" type="text" name="callSign" value="<?php echo $settings['callSign']; ?>" required>
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



							<legend>CTCSS Settings</legend>
							<p>These are settings experimental. It is recommend that you leave these set to none and set your CTCSS tones in your radios.<br></p>

							  <div class="control-group">
								<label class="control-label" for="selectError">RX Tone (Hz)</label>
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
								<label class="control-label" for="selectError">TX Tone (Hz)</label>
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


			<form class="form-horizontal" role="form" action="settings.php" method="post" id="modulesUpdate" name="modulesUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> Module Settings Enable/Disable</h2>
					</div>
					<div class="box-content">

						  <fieldset>
						  	<a name="modules"></a>

							<legend>Modules</legend>

							  <div class="control-group">
								<label class="control-label">Enable EchoLink</label>
								<div class="controls">
									<?php
										$checkbox_name = "echolink_enabled";
										$checkbox_string = '<input type="hidden" name="'.$checkbox_name.'" value="False" />';
										if ($settings[$checkbox_name] == "True") {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" checked />';
										} else {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" />';
										}
										echo $checkbox_string;
									?>
								  <span class="help-inline">The EchoLink module and it's associated settings can be edited on this page: <a href="echolink.php">EchoLink</a>.</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label">Enable Help</label>
								<div class="controls">
									<?php
										$checkbox_name = "help_enabled";
										$checkbox_string = '<input type="hidden" name="'.$checkbox_name.'" value="False" />';
										if ($settings[$checkbox_name] == "True") {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" checked />';
										} else {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" />';
										}
										echo $checkbox_string;
									?>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label">Enable Parrot</label>
								<div class="controls">
									<?php
										$checkbox_name = "parrot_enabled";
										$checkbox_string = '<input type="hidden" name="'.$checkbox_name.'" value="False" />';
										if ($settings[$checkbox_name] == "True") {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" checked />';
										} else {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" />';
										}
										echo $checkbox_string;
									?>
								</div>
							  </div>

							  <?php /*
							  <div class="control-group">
								<label class="control-label">Enable Voicemail</label>
								<div class="controls">
									<input type="hidden" name="voicemail_enabled" value="False" />
									<input type="checkbox" name="voicemail_enabled" value="True." />
								</div>
							  </div>
							  */ ?>

							<div class="form-actions">
							  <input type="hidden" name="action" value="update">		
							  <button type="submit" class="btn btn-primary">Update</button>
							  <button type="reset" class="btn">Cancel</button>
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