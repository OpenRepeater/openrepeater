<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

// ----------------------------------------------------------------------------------------------------------------------------------
// start/stop/restart options
// ----------------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['action'])){
	if ($_POST['action'] == "start"){
$shellout = shell_exec('sudo /usr/bin/svxlink_start');
}
elseif (isset($_POST['action'])){
	if ($_POST['action'] == "stop"){
$shellout = shell_exec('sudo /usr/bin/svxlink_stop');
}
elseif (isset($_POST['action'])){
	if ($_POST['action'] == "restart"){
$shellout = shell_exec('sudo /usr/bin/svxlink_restart');
}
else
if (isset($_POST['action'])){
	if ($_POST['action'] == "reboot"){
$shellout = shell_exec('sudo /usr/bin/system_reboot');
}

header('location: ../dashboard.php');
?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>