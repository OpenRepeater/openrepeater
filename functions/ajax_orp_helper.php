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

	# Geo AJAX Results
	if($_POST['type'] == 'get_gps'){
		$Functions = new Functions();
		$results = $Functions->get_geo_location();

		if ($results) { echo json_encode($results); } else { echo json_encode(['status'=>'false']); }		
	}
} 


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>