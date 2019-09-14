<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################
$Database = new Database();

if(isset($_POST)==true && empty($_POST)==false) {
	// Clear old settings
	$Database->delete_row('DELETE from PORTS;');
	$Database->delete_row('DELETE FROM "gpio_pins" WHERE type = "Port";');

	// Define sub arrays
	$portNum=$_POST['portNum'];
	$portLabel=$_POST['portLabel'];			
	$rxMode=$_POST['rxMode'];
	$rxGPIO=$_POST['rxGPIO'];					
	$rxGPIO_active=$_POST['rxGPIO_active'];					
	$txGPIO=$_POST['txGPIO'];
	$txGPIO_active=$_POST['txGPIO_active'];					
	$rxAudioDev=$_POST['rxAudioDev'];					
	$txAudioDev=$_POST['txAudioDev'];

	$insertArray = [];
	foreach($portLabel as $a => $b) {
		$newPortNum = $a+1;

		// Static Columns
		$insertArray[$newPortNum] = ['portNum' => $newPortNum];
		$insertArray[$newPortNum] += ['portLabel' => $portLabel[$a]];
		$insertArray[$newPortNum] += ['rxAudioDev' => $rxAudioDev[$a]];
		$insertArray[$newPortNum] += ['txAudioDev' => $txAudioDev[$a]];
		$insertArray[$newPortNum] += ['portType' => 'GPIO'];

		// Columns to be serialized later
		$insertArray[$newPortNum] += ['rxMode' => $rxMode[$a]];
		$insertArray[$newPortNum] += ['rxGPIO' => $rxGPIO[$a]];
		$insertArray[$newPortNum] += ['txGPIO' => $txGPIO[$a]];
		$insertArray[$newPortNum] += ['rxGPIO_active' => $rxGPIO_active[$a]];
		$insertArray[$newPortNum] += ['txGPIO_active' => $txGPIO_active[$a]];
	}

	// Write settings into Port DB Table
	$Database->update_ports_table($insertArray);

	return true;
	
} else {
	return false;
} 


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>