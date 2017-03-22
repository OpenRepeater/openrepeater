<?php
// Functions to get and set status for rebuild flag. Used to notify user that there are changes that need commited to config.

function get_rebuild_status() {
	include "/etc/openrepeater/database.php";
	$rebuild_required = $dbConnection->querySingle('SELECT value FROM system_flags WHERE keyID="rebuild_required"', true);
	if ($rebuild_required['value'] == 1) { return true; } else { return false; }	
}


function set_rebuild_status($status) {
	if ($status == true) { $value = 1; } else { $value = 0; }
	include_once("/etc/openrepeater/database.php");
	$dbConnection->exec('UPDATE system_flags SET value='.$value.' WHERE keyID="rebuild_required"');
}


//echo get_rebuild_status();
//set_rebuild_status(true);
?>