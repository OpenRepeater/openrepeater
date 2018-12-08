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

foreach($_POST as $key=>$value){  
	// SPECIAL FORMATING
	if ($key == "callSign") { $value = strtoupper($value); }
	if ($key == "echolink_callSign") { $value = strtoupper($value); }
	
	$query = $Database->update("UPDATE settings SET value='$value' WHERE keyID='$key'");	
}

if ($query) { return true; } else { return false; }


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>