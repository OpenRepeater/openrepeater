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
include_once("_includes/get_settings.php");
include_once("_includes/get_ctcss.php");
/*
$header_scripts = '
	<script type="text/javascript"> 
	function toggle(chkbox, group) { 
		var visSetting = (chkbox.checked) ? "block" : "none"; 
		document.getElementById(group).style.display = visSetting; 
	} 
	
	function echolink_onload () {
		document.getElementById("echolink_settings").style.display = "none";
		alert("test");
	}
	</script>
	
	';
if ($settings['echolink_enabled'] == "False") {
	$body_onload = 'onload=echolink_onload()';
}
*/
$pageTitle = "EchoLink Settings"; 
include('_includes/header.php'); 
?>


			<div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a> <span class="divider">/</span></li>
					<li class="active"><?php echo $pageTitle; ?></li>
				</ul>
			</div>

			<?php if (isset($alert)) { echo $alert; } ?>



			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> EchoLink Settings</h2>
					</div>
					<div class="box-content">



						<form name="update" action="echolink.php" method="post" class="form-horizontal">
						  <fieldset>

							<legend>Enable EchoLink Module</legend>

								<div class="alert alert-info"><p><strong>Note About EchoLink:</strong> 
								The EchoLink® network allows licensed Amateur Radio stations to communicate with one another over the Internet using VOIP technology.  This module allows worldwide connections to be made between other repeaters or to individuals using EchoLink nodes. In order to use EchoLink the following must be done:
								<ul>
								<li>You must validate your callsign with the EchoLink network to enter in the settings below. For repeater operation this is you callsign followed by a "-R" (i.e. <em>X#XXX</em><strong>-R</strong>)</li>
								<li>This OpenRepeater controller must be connected to the interent in order for this to function.</li>
								<li>EchoLink requires that your router or firewall allow inbound and outbound UDP to ports 5198 and 5199, and outbound TCP to port 5200.  If you are using a home-network router, you will also need to configure the router to "forward" UDP ports 5198 and 5199 to the IP address assigned to this OpenRepeater controller.</li>
								</ul>

								Visit the EchoLink Website (<a href="http://www.echolink.org/" target="_blank">http://www.echolink.org/</a>) for more details on setting up your network and validating your callsign. </p></div>


							  <div class="control-group">
								<label class="control-label">Enable EchoLink</label>
								<div class="controls">
									<?php
										$checkbox_name = "echolink_enabled";
										if ($settings[$checkbox_name] == "True") { $checkbox_string = 'checked '; } else { $checkbox_string = ''; }
									?>
									<input type="hidden" name="<?php echo $checkbox_name; ?>" value="False" />
									<input type="checkbox" name="<?php echo $checkbox_name; ?>" value="True" class="iphone-toggle" data-no-uniform="true" onclick='toggle(this, "echolink_settings")' <?php echo $checkbox_string; ?>/>
								</div>
							  </div>

<!-- <input type="checkbox" name="monitor" onclick='toggle(this, "echolink_settings")' checked />Monitor  -->



						  <div id="echolink_settings"> <!-- EXPANDING FIELDS FOR ECHOLINK SETTINS-->
							
							<legend>Basic Settings</legend>

							  <div class="control-group">
								<label class="control-label" for="prependedInput">EchoLink Callsign</label>
								<div class="controls">
								  <div class="input-prepend">
									<span class="add-on"><i class="icon-user"></i></span><input id="prependedInput" style="text-transform: uppercase" size="16" type="text" name="echolink_callSign" value="<?php echo $settings['echolink_callSign']; ?>" required>
 								    <span class="help-inline">The callsign to use to login to the EchoLink directory server.</span>
								  </div>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="prependedInput">EchoLink Password</label>
								<div class="controls">
								  <div class="input-prepend">
									<span class="add-on"><i class="icon-lock"></i></span><input id="prependedInput" size="16"  type="password" placeholder="Password" name="echolink_password" value="<?php echo $settings['echolink_password']; ?>" required>
 								    <span class="help-inline">The EchoLink directory server password to use.</span>
								  </div>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="callSign">Sysop Name</label>
								<div class="controls">
								  <input class="input-xlarge" id="callSign" type="text" name="echolink_sysop" value="<?php echo $settings['echolink_sysop']; ?>" required>
								  <span class="help-inline">The name of the person or club that is responsible for this system.</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="callSign">Location</label>
								<div class="controls">
								  <input class="input-xlarge" id="callSign" type="text" name="echolink_location" value="<?php echo $settings['echolink_location']; ?>" required>
								  <span class="help-inline">The location of the station.</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="timeoutMsg">Description</label>
								<div class="controls">
								  <textarea class="input-xlarge disabled" id="timeoutMsg" name="echolink_desc" required><?php echo $settings['echolink_desc']; ?></textarea>
								  <span class="help-inline">A longer description that is sent to remote stations upon connection. This description should typically include detailed station information like QTH, transceiver frequency/power, antenna, CTCSS tone frequency etc.</span>
								</div>
							  </div>

							</div> <!-- END OF EXPANDING FIELDS FOR ECHOLINK SETTINS-->


							<div class="form-actions">
							  <input type="hidden" name="action" value="update">		
							  <button type="submit" class="btn btn-primary">Update</button>
							  <button type="reset" class="btn">Cancel</button>
							</div>
						  </fieldset>
						</form>   



					</div>
				</div><!--/span-->
			
			</div><!--/row-->

    
<?php include('_includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>