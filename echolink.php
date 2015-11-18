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


include_once("includes/get_settings.php");
$dbConnection->close();


$pageTitle = "EchoLink Settings"; 

$customJS = "page-echolink.js"; // "file1.js, file2.js, ... "
$customCSS = "page-echolink.css";

include('includes/header.php'); 
?>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="echolinkSettings">
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-signal"></i> EchoLink Settings</h2>
					</div>
					<div class="box-content">
				
						  <fieldset>
						  <?php if ($settings['echolink_enabled'] != 'True') {
							  echo '<div class="alert alert-danger"><strong>Oh snap!</strong> It doesn\'t look like you have EchoLink enabled. You must enable the EchoLink Module first to see the settings. You can do that here: <a href="settings.php#modules">Modules</a></div>';
						  } else { ?>
							<legend>EchoLink Module Information</legend>

								<div class="alert alert-info"><p><strong>Note About EchoLink:</strong> 
								The EchoLink® network allows licensed Amateur Radio stations to communicate with one another over the Internet using VOIP technology.  This module allows worldwide connections to be made between other repeaters or to individuals using EchoLink nodes. In order to use EchoLink the following must be done:
								<ul>
								<li>You must validate your callsign with the EchoLink network to enter in the settings below. For repeater operation this is you callsign followed by a "-R" (i.e. <em>X#XXX</em><strong>-R</strong>)</li>
								<li>This OpenRepeater controller must be connected to the interent in order for this to function.</li>
								<li>EchoLink requires that your router or firewall allow inbound and outbound UDP to ports 5198 and 5199, and outbound TCP to port 5200.  If you are using a home-network router, you will also need to configure the router to "forward" UDP ports 5198 and 5199 to the IP address assigned to this OpenRepeater controller.</li>
								</ul>

								Visit the EchoLink Website (<a href="http://www.echolink.org/" target="_blank">http://www.echolink.org/</a>) for more details on setting up your network and validating your callsign. </p></div>

							
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
							  
						  <?php } // End check for echolink enabled ?>

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