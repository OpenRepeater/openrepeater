<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	echo '{"login":"timeout"}';
} else { // If they are, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################
// $classDB = new Database();

if ( isset($_POST['getSoundDevices']) ) {
	$SoundDevices = new SoundDevices();
	$result = $SoundDevices->get_device_list('JSON');

/*
	// FAKE Results for Docker Testing
	$result = '[{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Channel 1"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":1,"channel_label":"Channel 2"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":2,"channel_label":"Channel 3"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":3,"channel_label":"Channel 4"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":4,"channel_label":"Channel 5"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":5,"channel_label":"Channel 6"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":6,"channel_label":"Channel 7"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":7,"channel_label":"Channel 8"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":8,"channel_label":"Channel 9"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":9,"channel_label":"Channel 10"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Mono"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"IN","channel":0,"channel_label":"Left"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"IN","channel":1,"channel_label":"Right"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Mono"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Channel 1"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Channel 2"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":2,"channel_label":"Channel 3"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":3,"channel_label":"Channel 4"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":4,"channel_label":"Channel 5"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":5,"channel_label":"Channel 6"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":6,"channel_label":"Channel 7"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":7,"channel_label":"Channel 8"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":8,"channel_label":"Channel 9"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":9,"channel_label":"Channel 10"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Right"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"OUT","channel":1,"channel_label":"Right"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Right"}]';
*/
	if ($result) { echo $result; } else { echo '{"status":"error"}'; }	
	exit;
}



// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>