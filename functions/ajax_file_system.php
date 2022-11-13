<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, allow coded execution.
// --------------------------------------------------------

# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');

$FileSystem = new FileSystem();

if ( isset($_POST['action']) ) {
	switch ($_POST['action']) {
		case 'upload':
			echo $FileSystem->uploadFiles($_FILES, $_POST);
			break;
		case 'delete':		
			echo $FileSystem->deleteFiles($_POST);
			break;
		case 'rename':		
			echo $FileSystem->renameFile($_POST);
			break;
	}
}


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>