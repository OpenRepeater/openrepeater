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

	foreach($portLabel as $a => $b) {
		$newPortNum = $a+1;
		
		// Write settings into Port DB Table
		$sql = "INSERT INTO ports (portNum,portLabel,rxMode,rxGPIO,txGPIO,rxAudioDev,txAudioDev,rxGPIO_active,txGPIO_active) VALUES ('$newPortNum','$portLabel[$a]','$rxMode[$a]','$rxGPIO[$a]','$txGPIO[$a]','$rxAudioDev[$a]','$txAudioDev[$a]','$rxGPIO_active[$a]','$txGPIO_active[$a]')";
		$Database->update($sql);	
		
		// Write RX pin to GPIO Pin table in DB
		$gpio_rx = "INSERT INTO gpio_pins (gpio_num,direction,active,description,type) VALUES ('$rxGPIO[$a]','in','$rxGPIO_active[$a]','PORT $newPortNum RX: $portLabel[$a]','Port');";
		$Database->update($gpio_rx);	
		
		// Write TX pin to GPIO Pin table in DB
		$gpio_tx = "INSERT INTO gpio_pins (gpio_num,direction,active,description,type) VALUES ('$txGPIO[$a]','out','$txGPIO_active[$a]','PORT $newPortNum TX: $portLabel[$a]','Port');";
		$Database->update($gpio_tx);	
	}

	return true;
	
} else {
	return false;
} 


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>