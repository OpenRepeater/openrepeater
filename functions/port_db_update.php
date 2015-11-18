<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

if(isset($_POST)==true && empty($_POST)==false) {
	$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');

	$query = $db->exec("DELETE from PORTS;");

	// define sub arrays
	$portNum=$_POST['portNum'];
	$portLabel=$_POST['portLabel'];			
	$rxMode=$_POST['rxMode'];
	$rxGPIO=$_POST['rxGPIO'];					
	$txGPIO=$_POST['txGPIO'];
	$rxAudioDev=$_POST['rxAudioDev'];					
	$txAudioDev=$_POST['txAudioDev'];

	foreach($portLabel as $a => $b) {
		$newPortNum = $a+1;
		$sql = "INSERT INTO ports (portNum,portLabel,rxMode,rxGPIO,txGPIO,rxAudioDev,txAudioDev) VALUES ('$newPortNum','$portLabel[$a]','$rxMode[$a]','$rxGPIO[$a]','$txGPIO[$a]','$rxAudioDev[$a]','$txAudioDev[$a]')";
		$query = $db->exec($sql);
	}

	$db->close();

	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
	$memcache_obj = new Memcache;
	$memcache_obj->connect('localhost', 11211);
	$memcache_obj->set('update_settings_flag', 1, false, 0);

	return true;
	
} else {
	return false;
} 
?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>