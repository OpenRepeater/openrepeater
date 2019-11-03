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
# EDIT THIS FILE FIRST, THEN RUN! This is primarily for adding ports to the 
# database that the current UI does not support...such as hidraw and serial
# ports. Some examples of which are contained within this file.
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
	
	
	// Clear old settings
	$classDB->delete_row('DELETE from PORTS;');
	$classDB->delete_row('DELETE FROM "gpio_pins" WHERE type = "Port";');
	
	$testArr = [
		1 => [
			'portNum' => '1',
			'portLabel' => 'ICS 1X',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '26',
			'txGPIO' => '498',
			// 'rxGPIO_active' => 'low',
			'rxGPIO_active' => 'high',
			'txGPIO_active' => 'high',

			'portDuplex'  => 'full',
			'portEnabled'  => '1',
			'linkGroup'  => '1',
		],

		2 => [
			'portNum' => '2',
			'portLabel' => 'USB RIM Lite',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'HiDraw',
			'hidrawDev' => '/dev/hidraw0',
			'hidrawRX_cos' => 'VOL_DN',
			'hidrawRX_cos_invert' => true,
			'hidrawRX_ctcss' => 'VOL_UP',
			'hidrawRX_ctcss_invert' => true,
			'hidrawTX_ptt' => 'GPIO3',
			'hidrawTX_ptt_invert' => false,

			'portDuplex' => 'half',
			'portEnabled' => '1',
			'linkGroup' => '1',
		],
	
	
/*
		1 => [
			'portNum' => '1',
			'portLabel' => 'ICS 2X Port 1',
			'rxAudioDev' => 'alsa:plughw:0|1',
			'txAudioDev' => 'alsa:plughw:0|1',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '26',
			'txGPIO' => '498',
			'rxGPIO_active' => 'low',
			'txGPIO_active' => 'high',

			'portDuplex' => 'full',
			'portEnabled' => '1',
			'linkGroup' => '1',
		],
*/

/*
		2 => [
			'portNum' => '2',
			'portLabel' => 'ICS 2X Port 2',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'GPIO',
			'rxMode' => 'cos',
			'rxGPIO' => '23',
			'txGPIO' => '499',
			'rxGPIO_active' => 'low',
			'txGPIO_active' => 'high',
			'portDuplex' => 'full',
			'portEnabled' => '1',
			'linkGroup' => '1',
		],
*/
	
/*
		3 => [
			'portNum' => '3',
			'portLabel' => 'DMK URI',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
			'portType' => 'HiDraw',
			'hidrawDev' => '/dev/hidraw0',
			'hidrawRX_cos' => 'VOL_DN',
			'hidrawRX_cos_invert' => true,
			'hidrawRX_ctcss' => 'VOL_UP',
			'hidrawRX_ctcss_invert' => true,
			'hidrawTX_ptt' => 'GPIO3',
			'hidrawTX_ptt_invert' => false,

			'portDuplex'  => 'half',
			'portEnabled'  => '1',
			'linkGroup'  => '1',
			'rxAudioDev' => 'alsa:plughw:1|0',
			'txAudioDev' => 'alsa:plughw:1|0',
		],
*/

/*
		4 => [
			'portNum' => '4',
			'portLabel' => 'Test Serial',
			'rxAudioDev' => 'alsa:plughw:0|0',
			'txAudioDev' => 'alsa:plughw:0|0',
			'portType' => 'Serial',
			'rxMode' => 'cos',
			'serialDev' => '/dev/ttyUSB1',
			'serialRX_cos' => 'CTS',
			'serialRX_cos_invert' => true,
			'serialTX_ptt' => 'DTR',
			'serialTX_ptt_invert' => false,

			'portDuplex' => 'half',
			'portEnabled' => '0',
		],
*/
		22 => [
			'portNum' => '22',
			'portLabel' => 'VOIP/Network',
			'portType' => 'Network',
			'netHost' => '--RPI IP Address--',
			'netPort' => '5210',
			'netKey' => '--LINK KEY--',
			'netCodec' => 'S16',

			'netLogDisconnectsOnce' => '',

			'netSpeexEncFramesPerPacket' => '',
			'netSpeexEncQuality' => '',
			'netSpeexEncBitrate' => '',
			'netSpeexEncComplexity' => '',
			'netSpeexEncVbr' => '',
			'netSpeexEncVbrQuality' => '',
			'netSpeexEncAbr' => '',
			'netSpeexDecEnhancer' => '',

			'netOpusEncFrameSize' => '',
			'netOpusEncComplexity' => '',
			'netOpusEncBitrate' => '',
			'netOpusEncVbr' => '',

			'portDuplex' => 'half',
			'portEnabled' => '1',
			'linkGroup' => '1',
		],

	];
	
	$result = $classDB->update_ports_table($testArr);

	include('header.php');
	echo '<h2>Ports Updater</h2>';
	if ($result) { echo 'Ports Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; } else { echo "<h3>ERROR UPDATING DATABASE</h3>"; }
	include('footer.php');

} else {
	include('header.php');
	?>
	<h2>Ports Updater</h2>
	<h3>You MUST edit the array in this file to meet your needs before you continue.</h3>
	<p><strong>FILE LOCATION:</strong> <?php echo get_included_files()[0]; ?></p>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<input type="hidden" name="action" value="update">		
		<button class="myButton">Add Ports from PHP Array</button>
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