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

# Settings Table Operations
if ( isset($_POST['settings']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['settings']);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['location']) ) {
	$classDB = new Database();
	$locationArray = json_decode($_POST['location']);
	$updateArray['Location_Info'] = json_encode($locationArray);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}



# Ports Operations
if ( isset($_POST['updatePort']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['updatePort'], true);
	$result = $classDB->update_ports_table($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['deletePort']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['deletePort'], true);
	$result = $classDB->delete_ports($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['linkGroups']) ) {
	$classDB = new Database();
	$linkGroupsArray = json_decode($_POST['linkGroups']);
	$updateArray['LinkGroup_Settings'] = json_encode($linkGroupsArray);
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