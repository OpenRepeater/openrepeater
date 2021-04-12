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

if(isset($_POST)==true && empty($_POST)==false) {
	$Database = new Database();

	// Define sub arrays
	$portNum=$_POST['portNum'];
	$portLabel=$_POST['portLabel'];			
	$rxAudioDev=$_POST['rxAudioDev'];					
	$txAudioDev=$_POST['txAudioDev'];
	$portType=$_POST['portType'];
	$portEnabled=$_POST['portEnabled'];

	$rxMode=$_POST['rxMode'];
	$rxGPIO=$_POST['rxGPIO'];					
	$txGPIO=$_POST['txGPIO'];
	$rxGPIO_active=$_POST['rxGPIO_active'];					
	$txGPIO_active=$_POST['txGPIO_active'];					
	$linkGroup=$_POST['linkGroup'];


	$insertArray = [];
	foreach($portLabel as $portNum => $portArr) {

		// Static Columns
		$insertArray[$portNum] = ['portNum' => $portNum];
		$insertArray[$portNum] += ['portLabel' => $portLabel[$portNum]];
		$insertArray[$portNum] += ['rxAudioDev' => $rxAudioDev[$portNum]];
		$insertArray[$portNum] += ['txAudioDev' => $txAudioDev[$portNum]];
		$insertArray[$portNum] += ['portType' => 'GPIO'];
		$insertArray[$portNum] += ['portEnabled' => $portEnabled[$portNum]];

		// Columns to be saved to JSON object later
		$insertArray[$portNum] += ['rxMode' => $rxMode[$portNum]];
		$insertArray[$portNum] += ['rxGPIO' => $rxGPIO[$portNum]];
		$insertArray[$portNum] += ['txGPIO' => $txGPIO[$portNum]];
		$insertArray[$portNum] += ['rxGPIO_active' => $rxGPIO_active[$portNum]];
		$insertArray[$portNum] += ['txGPIO_active' => $txGPIO_active[$portNum]];
// 		$insertArray[$portNum] += ['linkGroup' => $linkGroup[$portNum]];
		$insertArray[$portNum] += [ 'linkGroup' => [intval($linkGroup[$portNum])] ];
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