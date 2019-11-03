<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php'); // If they aren't logged in, send them to login page.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------
?>

<?php

# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');

$classDB = new Database();

# Check if old structure ports table structure and update if needed
$classDB->upgrade_ports_table_structure();

# Check and Update Database Structure with new fields if they don't already exist
$classDB->add_record('settings','ID_Only_When_Active','False');
$classDB->add_record('settings','Location_Info','');
$classDB->add_record('settings','LinkGroup_Settings','');

# Add macros table if it doesn't exist
$classDB->insert('CREATE TABLE IF NOT EXISTS macros ( macroKey INTEGER PRIMARY KEY, macroEnabled INTEGER, macroNum INTEGER, macroLabel TEXT, macroModuleID INTEGER, macroString TEXT, macroPorts TEXT);');

?>

<?php
	
	include('header.php');

	$result = 'dummy';
	if ($result) { echo 'Database Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; } else { echo "<h1>ERROR UPDATING DATABASE</h1>"; }

	include('footer.php');

?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>