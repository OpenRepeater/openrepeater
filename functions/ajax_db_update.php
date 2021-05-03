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
$classDB = new Database();

# Update Settings
if ( isset($_POST['settings']) ) {
	$updateArray = json_decode($_POST['settings']);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['location']) ) {
	$locationArray = json_decode($_POST['location']);
	$updateArray['Location_Info'] = json_encode($locationArray);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}




// DEPRECIATED: Old UI update
$result = $classDB->update_settings($_POST);
if ($result) { return true; } else { return false; }


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>