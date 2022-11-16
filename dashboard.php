<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------
?>
<?php
	
$pageTitle = "Dashboard";

$customCSS = "sys_info.css"; // "file1.css, file2.css, ... "
$customJS = "page-dashboard.js"; // "file1.js, file2.js, ... "

include('includes/header.php');
	
require_once('includes/classes/System.php');
$classSystem = new System();
	
?>

	<div id="overlay">
		<div class="msg_box">
			<span class="msg_1"></span><span class="msg_2"></span>
		</div>
	</div>
			



			</div>
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
