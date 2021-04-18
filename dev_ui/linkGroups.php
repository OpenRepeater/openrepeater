<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php'); // If they aren't logged in, send them to login page.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------
?>

<?php
################################################################################
# EDIT THIS FILE FIRST, THEN RUN! This is for adding settings for the LinkGroups
#
# Note: This is an early development script for allowing updating of settings in
# the database for testing purposes. This feature is supported in the current
# backend code, but the UI will not be available until a future release. This is
# a work around in the time being for use and/or testing. This file must be
# edited first, then run from the browser. In so doing the settings in this file
# will be written to the database and will be available on the next rebuild of
# the configuration.
################################################################################

if (isset($_POST['action'])){
	# AUTOLOAD CLASSES
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
	
	$classDB = new Database();
	
	# Edit the values for the settings in this array as you wish them to be saved to DB
	$linkgrp_array = [
		1 => [
			'defaultActive' => '1',
			'timeout' => '0', // Timeout/Timein in seconds. Set to 0 for no timeout
		],
		2 => [
			'defaultActive' => '0',
			'timeout' => '0', // Timeout/Timein in seconds. Set to 0 for no timeout
		],
	];
	
	$result = $classDB->update_settings(['LinkGroup_Settings' => json_encode($linkgrp_array)]);

	include('header.php');
	echo '<h2>LinkGroup Updater</h2>';
	if ($result) { echo 'Database Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; } else { echo "<h1>ERROR UPDATING DATABASE</h1>"; }
	include('footer.php');

} else {
	include('header.php');
	?>
	<h2>LinkGroup Updater</h2>
	<h3>You MUST edit the array in this file to meet your needs before you continue.</h3>
	<p><strong>FILE LOCATION:</strong> <?php echo get_included_files()[0]; ?></p>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<input type="hidden" name="action" value="update">		
		<button class="myButton">Add/Update LinkGroups from PHP Array</button>
	</form>
	<?php
	include('footer.php');
}
?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>