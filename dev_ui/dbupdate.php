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
$classDB->add_record('system_flags','config_files','');


# Add macros table if it doesn't exist
$classDB->insert('CREATE TABLE IF NOT EXISTS macros ( macroKey INTEGER PRIMARY KEY, macroEnabled INTEGER, macroNum INTEGER, macroLabel TEXT, macroModuleID INTEGER, macroString TEXT, macroPorts TEXT);');


# Add devices table if it doesn't exist
$classDB->insert('CREATE TABLE IF NOT EXISTS devices ( device_id INTEGER PRIMARY KEY NOT NULL, device_path TEXT, description TEXT, type TEXT);');


# Update Users Table to Newer Format
if ( !$classDB->exists_column('users', 'enabled') ) {
	$classDB->add_table_column('users', 'enabled', 'INTEGER', '1');
}
if ( !$classDB->exists_column('users', 'user_role') ) {
	$classDB->add_table_column('users', 'user_role', 'TEXT', '');
	$classDB->update("UPDATE users SET user_role='admin' WHERE userID = '1';");
}
if ( !$classDB->exists_column('users', 'user_meta') ) {
	$classDB->add_table_column('users', 'user_meta', 'TEXT', '');
}


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
serial2JSON('Location_Info','settings',$classDB);
serial2JSON('LinkGroup_Settings','settings',$classDB);


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


# Update version table and add SVXLink Release if it doesn't exist. 
if ( !$classDB->exists_column('version_info', 'svxlink_release') ) {
	$version_results = $classDB->select_single('SELECT version_num FROM version_info LIMIT 1');

	switch (true) {
		case stristr($version_results['version_num'],'2.1.0'):
		case stristr($version_results['version_num'],'2.1.1'):
		case stristr($version_results['version_num'],'2.1.2'):
			$svxlink_ver = '17.12.2';
			break;
		case stristr($version_results['version_num'],'2.1.3'):
		case stristr($version_results['version_num'],'2.2'):
		case stristr($version_results['version_num'],'3.0'):
			$svxlink_ver = '19.09.1';
			break;
	}

	$classDB->add_table_column('version_info', 'svxlink_release');
	$classDB->update("UPDATE version_info SET svxlink_release='$svxlink_ver' WHERE ROWID = 1;");
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