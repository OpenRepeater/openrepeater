<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------
?>

<?php
$pageTitle = "Repeater Log";

$customCSS = "logtail.css"; // "file1.css, file2.css, ... "
$customJS = "logtail.js"; // "file1.js, file2.js, ... "
 
include('includes/header.php');

?>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Active Repeater Log</h2>
					</div>
					<div class="box-content">
						<pre id="data">Loading...</pre>
					</div>
				</div><!--/span-->
			</div><!--/row-->

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
