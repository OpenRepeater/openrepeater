<?php
################################################################################
# EDIT THIS FILE FIRST, THEN RUN! This is for adding settings for the location
# information to share with Echolink for proper reporting
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
	$loc_array = [
		'Echolink_Status_Servers' => 'aprs.echolink.org:5199',
	// 	'APRS_SERVER_LIST' => 'noam.aprs2.net:14580',
		'APRS_ServerList' => '',
		'Latitude' => '39.32.48N',
		'Longitude' => '77.54.43W',
		'APRS_Station_Type' => 'repeater', // repeater OR link, NOTE: Callsign is pulled from settings and prefixed accordingly. 
		'Frequency' => '444.65',
		'Tone' => '0',
		'TX_Power' => '1',
		'Antenna_Gain' => '6',
		'Antenna_Height' => '20f',
		'Antenna_Dir' => '-1',
		'APRS_Path' => 'WIDE1-1',
		'Beacon_Interval' => '10',
		'Statistics_Interval' => '10',	
	];
	
	$result = $classDB->update_settings(['Location_Info' => serialize($loc_array)]);

	include('header.php');
	echo '<h2>Location Updater</h2>';
	if ($result) { echo 'Database Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; } else { echo "<h1>ERROR UPDATING DATABASE</h1>"; }
	include('footer.php');

} else {
	include('header.php');
	?>
	<h2>Location Updater</h2>
	<h3>You MUST edit the array in this file to meet your needs before you continue.</h3>
	<p><strong>FILE LOCATION:</strong> <?php echo get_included_files()[0]; ?></p>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<input type="hidden" name="action" value="update">		
		<button class="myButton">Add/Update Location from PHP Array</button>
	</form>
	<?php
	include('footer.php');
}
?>