<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, show the page.
// --------------------------------------------------------


$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');

foreach($_POST as $key=>$value){  
	// SPECIAL FORMATING
	if ($key == "callSign") { $value = strtoupper($value); }
	if ($key == "echolink_callSign") { $value = strtoupper($value); }
	
	$query = $db->exec("UPDATE settings SET value='$value' WHERE keyID='$key'");

}
$db->close();


/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);
$memcache_obj->set('update_settings_flag', 1, false, 0);

return true;

?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>