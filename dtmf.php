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


$pageTitle = "DTMF Reference"; 

include_once("includes/get_settings.php");
$dbConnection->close();

include('includes/header.php');

?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="settingsUpdate" name="settingsUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i> DTMF Reference</h2>
					</div>
					<div class="box-content">
						<p>This provides a quick reference of available DTMF commands that can be sent to the repeater from another radio. This list will change depending on the modules that you have enabled.</p>
						<br>

						  <fieldset>
							  
							  
<legend>* - Force ID</legend>
<p>By pressing the star key (*), this will cause the repeater to identify.</p>
<br>

<?php if ($settings['help_enabled'] == "True") : ?>
<legend>0# - Help Module</legend>
<p>Pressing 0# will enable the help module which you may then select a module to get help about, including the help modules. Help is provided verbally over the air. If you do not want system users to get help with DTMF commands, disable the help module.</p>
<h4>Sub Commands:</h4>
<pre>
<?php echo "0#		Overview of the Help Module\r"; ?>
<?php if ($settings['parrot_enabled'] == "True") { echo "1#		Help on Parrot Module\r"; } ?>
<?php if ($settings['echolink_enabled'] == "True") { echo "2#		Help on EchoLink Module\r"; } ?>
<?php echo "#		Exit Help"; ?>
</pre>
<br>
<?php endif; ?>

<?php if ($settings['parrot_enabled'] == "True") : ?>
<legend>1# - Parrot Module</legend>
<p>Pressing 1# will enable the Parrot Module. After activated, the system will announce that it is ready. From here on you can simply key your radio and start to speak. After you unkey, the repeater will play back what it just record. This is probably a good way to test your audio and signal strength into the system. Remember to press # to exit the module when you are done.</p>
<h4>Sub Commands:</h4>
<pre>
#		Exit Parrot
</pre>
<br>
<?php endif; ?>

<?php if ($settings['echolink_enabled'] == "True") : ?>
<legend>2# - EchoLink Module</legend>
<p>Pressing 2# will enable the EchoLink Module. The EchoLink® network allows licensed Amateur Radio stations to communicate with one another over the Internet using VOIP technology. This module allows worldwide connections to be made between other repeaters or to individuals using EchoLink nodes.</p>
<h4>Sub Commands:</h4>
<pre>
9999# 		Connect to EchoLink by Node ID. (Node 9999 is ECHOTEST)
#		Disconnect from last connected station
##		Disconnect station and deactivate EchoLink Module
---------------------------------------------------
0#		Play the help message
1#		List all connected stations
2#		Play local EchoLink node id
31#		Connect to a random link or repeater
32#		Connect to a random conference
4#		Reconnect to the last disconnected station
50#		Deactivate listen only mode
51#		Activate listen only mode
<?php /* ?>6*??		Use the connect by callsign feature<?php */ ?>
7#		Use to disconnect a particular connected station from list
</pre>
<br>
<?php endif; ?>

<legend># - Deactivate Current Module</legend>
<p>By pressing the number key (#), this will deactivate the current module. If you are in a module that has a couple levels, then the number key (#) will function like a back key.</p>
<br>


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