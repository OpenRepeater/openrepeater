<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	echo '{"login":"timeout"}';
} else { // If they are, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

# Settings Table Operations
if ( isset($_POST['settings']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['settings']);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['location']) ) {
	$classDB = new Database();
	$locationArray = json_decode($_POST['location']);
	$updateArray['Location_Info'] = json_encode($locationArray);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}



# Ports Operations
if ( isset($_POST['updatePort']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['updatePort'], true);
	$result = $classDB->update_ports_table($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['deletePort']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['deletePort'], true);
	$result = $classDB->delete_ports($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['linkGroups']) ) {
	$classDB = new Database();
	$linkGroupsArray = json_decode($_POST['linkGroups']);
	$updateArray['LinkGroup_Settings'] = json_encode($linkGroupsArray);
	$result = $classDB->update_settings($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['getSoundDevices']) ) {
	$SoundDevices = new SoundDevices();
	$result = $SoundDevices->get_device_list('JSON');
/*
	// FAKE Results for Docker Testing
	$result = '[{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Channel 1"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":1,"channel_label":"Channel 2"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":2,"channel_label":"Channel 3"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":3,"channel_label":"Channel 4"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":4,"channel_label":"Channel 5"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":5,"channel_label":"Channel 6"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":6,"channel_label":"Channel 7"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":7,"channel_label":"Channel 8"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":8,"channel_label":"Channel 9"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"IN","channel":9,"channel_label":"Channel 10"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Mono"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"IN","channel":0,"channel_label":"Left"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"IN","channel":1,"channel_label":"Right"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"IN","channel":0,"channel_label":"Mono"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Channel 1"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Channel 2"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":2,"channel_label":"Channel 3"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":3,"channel_label":"Channel 4"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":4,"channel_label":"Channel 5"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":5,"channel_label":"Channel 6"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":6,"channel_label":"Channel 7"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":7,"channel_label":"Channel 8"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":8,"channel_label":"Channel 9"},{"card":"0","label":"USBStreamer","type":"USB Audio","direction":"OUT","channel":9,"channel_label":"Channel 10"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"1","label":"USB PnP Sound Device","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Right"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"2","label":"Fe-Pi Audio","type":"Fe-Pi HiFi sgtl5000-0","direction":"OUT","channel":1,"channel_label":"Right"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"OUT","channel":0,"channel_label":"Left"},{"card":"3","label":"USB Audio Device","type":"USB Audio","direction":"OUT","channel":1,"channel_label":"Right"}]';
*/
	if ($result) { echo $result; } else { echo '{"status":"error"}'; }	
	exit;
}



# Module Operations
if ( isset($_POST['moduleState']) ) {
	$classModules = new Modules();
	$moduleStateArray = json_decode($_POST['moduleState'], true);
	$moduleID = $moduleStateArray['moduleKey'];
	$moduleEnabled = $moduleStateArray['moduleEnabled'];
	if ($moduleEnabled == 1) {
		$result = $classModules->activateMod($moduleID);
		if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	} else {
		$result = $classModules->deactivateMod($moduleID);
		if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	}
	exit;
}

if ( isset($_POST['deleteModule']) ) {
	$classModules = new Modules();
	$deleteKey = json_decode($_POST['deleteModule'], true);
	$svxlinkName = $classModules->get_module_svxlink_name($deleteKey);
	$result = $classModules->remove_module($svxlinkName);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['moduleWrite']) ) {
	$classModules = new Modules();
	$updateArray = json_decode($_POST['moduleWrite'], true);
	$result = $classModules->write_modules($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}



# Macro Operations
if ( isset($_POST['updateMacro']) ) {
	$classDB = new Database();
	$updateArray = json_decode($_POST['updateMacro'], true);
	$result = $classDB->update_macro_table($updateArray);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}

if ( isset($_POST['deleteMacro']) ) {
	$classDB = new Database();
	$updateData = json_decode($_POST['deleteMacro']);
	$result = $classDB->delete_macro($updateData);
	if ($result) { echo '{"status":"success"}'; } else { echo '{"status":"error"}'; }
	exit;
}


# Backup/Restore Operations
if ( isset($_POST['createBackup']) ) {
	$classBackupRestore = new BackupRestore();
	$result = $classBackupRestore->create_backup();
	echo $result;
	exit;
}

if ( isset($_POST['restoreValidation']) ) {
	$classBackupRestore = new BackupRestore();
	$restoreFile = $_POST['restoreValidation'];
	$result = $classBackupRestore->pre_restore_validation($restoreFile);
	echo $result;
	exit;
}

if ( isset($_POST['restoreBackup']) ) {
	$classBackupRestore = new BackupRestore();
	$result = $classBackupRestore->restore_backup();
	echo $result;
	exit;
}



echo '{"status":"invalid"}';
exit;


// DEPRECIATED: Old UI update
$result = $classDB->update_settings($_POST);
if ($result) { return true; } else { return false; }


// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>