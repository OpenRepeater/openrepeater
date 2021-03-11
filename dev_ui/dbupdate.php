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

# Add devices table if it doesn't exist
$classDB->insert('CREATE TABLE IF NOT EXISTS devices ( device_id INTEGER PRIMARY KEY NOT NULL, device_path TEXT, description TEXT, type TEXT);');


# Convert to JSON function
function serial2JSON($setting, $table, $db) {
	$results = $db->select_single("SELECT value FROM $table WHERE keyID='LinkGroup_Settings'");

	// Check if setting is serialized, and if it is converit it to JSON format
	$data = @unserialize($results['value']);
	if ($data !== false) {
	    $converted = json_encode($data);
		$db->update("UPDATE $table SET value='$converted' WHERE keyID='LinkGroup_Settings'");
	}	
}

# Convert the follwoing to JSON format using above function
/*
serial2JSON('Location_Info','settings',$classDB);
serial2JSON('LinkGroup_Settings','settings',$classDB);
*/


# Convert Port Options to JSON
$ports = $classDB->select_all('ports','SELECT * FROM ports');
foreach($ports as $curPort) {
	// Check if setting is serialized, and if it is converit it to JSON format
	$curOptions = @unserialize($curPort['portOptions']);
	if ($curOptions !== false) {
		$curPortNum = $curPort['portNum'];
	    $converted = json_encode($curOptions);
		$classDB->update("UPDATE ports SET portOptions='$converted' WHERE portNum='$curPortNum'");
	}	
}

# Convert Module Options to JSON
$modules = $classDB->select_all('modules','SELECT * FROM modules');
foreach($modules as $curModules) {
	// Check if setting is serialized, and if it is converit it to JSON format
	$curOptions = @unserialize($curModules['moduleOptions']);
	if ($curOptions !== false) {
		$curModuleKey = $curModules['moduleKey'];
	    $converted = json_encode($curOptions);
		$classDB->update("UPDATE modules SET moduleOptions='$converted' WHERE moduleKey='$curModuleKey'");
	}	
}


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