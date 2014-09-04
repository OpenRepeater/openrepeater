<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------


if (isset($_POST['action'])){
	include_once("_includes/database.php");
	$dbUpdateSetting = mysql_connect($MySQLHost, $MySQLUsername, $MySQLPassword);
	mysql_select_db($MySQLDB, $dbUpdateSetting);

	foreach($_POST as $key=>$value){  
		if ($key != "action") {
			mysql_query("UPDATE settings SET value='$value' WHERE keyID='$key'");
		}
	}
	mysql_close($dbUpdateSetting);

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
include_once("_includes/get_settings.php");
include_once("_includes/get_ctcss.php");
include('_includes/header.php'); 
?>


			<div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a> <span class="divider">/</span></li>
					<li class="active"><?php echo $pageTitle; ?></li>
				</ul>
			</div>

			<?php if (isset($alert)) { echo $alert; } ?>

			<div class="alert alert-info"><p><strong>Caution About Setting Up Uncoordinated Repeaters:</strong> Having two repeaters operate on the same radio frequencies is problematic as they can interfere with each other, even with selective calling methods enabled. To help minimize this issue, regional repeater coordination organizations have been created. In some jurisdictions, coordination may be required by law or regulation. In the USA, coordination is optional and done on a voluntary basis, but Part 97 rule 205(c) prefers a coordinated repeater over an uncoordinated repeater in disputes over interference. Coordination in the USA is overseen by the National Frequency Coordinators' Council (NFCC), a non-profit organization that certifies regional coordinators.</p><p>When setting up a temporary uncoordinated repeater, it is best to research and check the frequencies first before putting the repeater on the air. This can be done by both listening to the frequencies for repeater activity and checking current repeater directory resources for repeaters in the area you plan to operate. If you do happen to encounter interference, please be respectful and move to another frequency. If you plan to setup a permanent/fixed repeater installation, coordination is highly advised.</p></div>

			<form name="update" action="settings.php" method="post" class="form-horizontal">

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> General Settings</h2>
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

<!-- DISABLED SETTING - NOT CURRENTLY AVAILABLE IN SVXLINK 
							  <div class="control-group">
								<label class="control-label" for="courtesyTone">Courtesy Tone</label>
								<div class="controls">
								  <input class="input-xlarge disabled" id="courtesyTone" type="text" placeholder="<?php echo str_replace('.mp3','',$settings['courtesy']);?>" disabled="">
								  <span class="help-inline">This is set on the <a href="courtesy_tone.php">Courtesy Tones</a> page</span>
								</div>
							  </div>
-->

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

<!-- DISABLED SETTING - NOT CURRENTLY AVAILABLE IN SVXLINK 
							  <div class="control-group">
								<label class="control-label" for="timeoutMsg">Timeout Message</label>
								<div class="controls">
								  <textarea class="input-xlarge disabled" id="timeoutMsg" name="timeoutMsg"><?php echo $settings['timeoutMsg']; ?></textarea>
								  <span class="help-inline">(...)</span>
								</div>
							  </div>
-->


							<legend>Identification Settings</legend>

							  <div class="control-group">
								<label class="control-label" for="idTimeValueMin">Short ID Time</label>
								<div class="controls">
								  <div class="input-append">
									<input id="idTimeValueMin" name="idTimeValueMin" size="16" type="text" value="<?php echo $settings['idTimeValueMin']; ?>" required><span class="add-on">mins</span>
								  </div>
								  <span class="help-inline">The number of minutes between short identifications. The purpose of the short identification is to just announce that the station is on the air. Typically just the callsign is transmitted. For a repeater a good value is ten minutes and for a simplex node one time every 60 minutes is probably enough. The LONG_IDENT_INTERVAL must be an even multiple of the SHORT_IDENT_INTERVAL so if LONG_IDENT_INTERVAL is 60 then the legal values for SHORT_IDENT_INTERVAL are: 1, 2, 3, 4, 5, 6, 10, 12, 15, 20, 30, 60. If unset or set to 0, disable short identifications.</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="idTimeValueMin">Long ID Time</label>
								<div class="controls">
								  <div class="input-append">
									<input id="idTimeValueMin" name="idLongTimeValueMin" size="16" type="text" value="<?php echo $settings['idLongTimeValueMin']; ?>" required><span class="add-on">mins</span>
								  </div>
								  <span class="help-inline">The number of minutes between long identifications. The purpose of the long identification is to transmit some more information about the station status (new voice mails etc). The time of day is also transmitted. A good value here is 60 minutes. If unset or set to 0, disable long identifications.</span>
								</div>
							  </div>


<!-- DISABLED SETTING - NOT CURRENTLY AVAILABLE IN SVXLINK 
							  <div class="control-group">
								<label class="control-label" for="voiceID">Voice ID</label>
								<div class="controls">
								  <textarea class="input-xlarge disabled" id="voice ID" name="voiceID"><?php echo $settings['voiceID']; ?></textarea>
								  <span class="help-inline">Use the custom variable place holder <strong>%%CALLSIGN%%</strong> to display the callsign entered above within your voice ID.</span>
								</div>
							  </div>
-->

<!-- DISABLED SETTING - NOT CURRENTLY AVAILABLE IN SVXLINK
							  <div class="control-group">
								<label class="control-label">Phonetics</label>
								<div class="controls">
									<?php
										$checkbox_name = "phoneticCallSign";
										$checkbox_string = '<input type="hidden" name="'.$checkbox_name.'" value="False" />';
										if ($settings[$checkbox_name] == "True") {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" checked />';
										} else {
											$checkbox_string .= '<input type="checkbox" name="'.$checkbox_name.'" value="True" class="iphone-toggle" data-no-uniform="true" />';
										}
										echo $checkbox_string;
									?>
									<span class="help-inline">Selecting this option will replace the standard call sign with the phonetic spelling of that call sign.</span>
								</div>
							  </div>
 -->

							<legend>Radio Settings</legend>
							<p>These are settings do not affect the function of the repeater controller. They are simply here to allow record keeping.<br></p>


							  <div class="control-group">
								<label class="control-label" for="rxFreq">RX Frequency (input)</label>
								<div class="controls">
								  <div class="input-append">
									<input id="rxFreq" name="rxFreq" size="16" type="text" value="<?php echo $settings['rxFreq']; ?>"><span class="add-on">MHz</span>
								  </div>
								  <span class="help-inline">The frequency you transmit on to get to the repeater (offset).</span>
								</div>
							  </div>

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
								<label class="control-label" for="txFreq">TX Frequency (output)</label>
								<div class="controls">
								  <div class="input-append">
									<input id="txFreq" name="txFreq" size="16" type="text" value="<?php echo $settings['txFreq']; ?>"><span class="add-on">MHz</span>
								  </div>
								  <span class="help-inline">The frequency you listen to the repeater on.</span>
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


							<div class="form-actions">
							  <input type="hidden" name="action" value="update">		
							  <button type="submit" class="btn btn-primary">Update</button>
							  <button type="reset" class="btn">Cancel</button>
							</div>
						  </fieldset>

					</div>
				</div><!--/span-->			
			</div><!--/row-->

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> Module Settings</h2>
					</div>
					<div class="box-content">

						  <fieldset>

							<legend>Modules</legend>

							  <div class="control-group">
								<label class="control-label">Enable EchoLink</label>
								<div class="controls">
									<?php
										$checkbox_name = "echolink_enabled";
										if ($settings[$checkbox_name] == "True") { $checkbox_string = 'checked '; } else { $checkbox_string = ' '; }
									?>
									<input type="checkbox" class="iphone-toggle" data-no-uniform="true" <?php echo $checkbox_string; ?>disabled />
								  <span class="help-inline">The EchoLink module and it's associated settings can be edited on this page: <a href="echolink.php">EchoLink Settings</a>.</span>
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

							  <div class="control-group">
								<label class="control-label">Enable Voicemail</label>
								<div class="controls">
									<?php
										$checkbox_name = "voicemail_enabled";
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

    
<?php include('_includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>