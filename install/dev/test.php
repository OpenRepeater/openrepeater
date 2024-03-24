<?php
session_start();

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################


	$default_settings = array(
		"orp_Mode"				=>		"repeater",
		"courtesy"				=>		"4_Up.wav",
		"repeaterTimeoutSec"	=>		"230",
		"rxTone"				=>		"",
		"txTailValueSec"		=>		"2",
		"txTone"				=>		"",
		"courtesyMode"			=>		"beep",
		"ID_Short_Mode"			=>		"morse",
		"ID_Long_Mode"			=>		"voice",
		"ID_Morse_Suffix"		=>		"/R",
		"ID_Morse_WPM"			=>		"25",
		"ID_Morse_Pitch"		=>		"600",
		"ID_Long_IntervalMin"	=>		"60",
		"ID_Long_AppendTime"	=>		"False",
		"ID_Long_AppendTone"	=>		"False",
		"ID_Long_AppendMorse"	=>		"True",
		"ID_Long_CustomFile"	=>		"Sample_Long_ID_Clip.wav",
		"ID_Short_IntervalMin"	=>		"10",
		"ID_Short_AppendMorse"	=>		"False",
		"ID_Short_CustomFile"	=>		"Sample_Short_ID_Clip.wav",
		"ID_Morse_Amplitude"	=>		"-13.98",
		"repeaterDTMF_disable"	=>		"False",
		"repeaterDTMF_disable_pin"	=>	"1234"
		);

echo json_encode($default_settings);


### Version Compare Tests

/*
$mod_min_orp_ver = "3.0.0";
$orp_min_ver_req = "3.0.0";

// Check if modules version meets requirement for installatiton
if ( version_compare($this->orpMinVerReq, $mod_min_orp_ver, 'eq') ) {
	echo 'Proceed with install';
} else {
	echo 'This module cannot be installed because it does not meet the minimum version requirements of this version of OpenRepeater.';
}
*/



################################################################################

### Module Tests

/*
$Modules = new Modules();

$result = $Modules->get_module_id('RemoteRelay');

var_dump($result);
*/


/*
$Database = new Database();
print_r($Database->get_modules());
*/


################################################################################

### Backup/Restoore Tests

/*
$BackupRestore = new BackupRestore();
echo $BackupRestore->create_backup();
*/


################################################################################

### Rename File Tests

/*
$JSON_input = '{"renameFile":"dummy2.wav","newName":"new File 2.wav","fileType":"identification"}';
$input = json_decode($JSON_input, true);
print_r($input);

echo '<br>';
*/

/*
$inputArr = [
	'deleteFiles'=>['fake1.orp'],
	'fileType'=>'backup'
];
print_r($inputArr);
*/



/*
$FileSystem = new FileSystem();
echo $FileSystem->renameFile($input);
*/


################################################################################

### Delete File Tests

/*
$JSON_input = '{"deleteFiles":"","fileType":"backup"}';
$input = json_decode($JSON_input, true);
print_r($input);

echo '<br>';

$FileSystem = new FileSystem();
echo $FileSystem->deleteFiles($input);
*/

/*
SAMPLE RESULTS:
{"fake1.orp":"success"}
{"fake1.orp":"success","fake2.orp":"success"}
{"fake1.orp":"error","fake2.orp":"error"}
{"fake1.orp":"error","fake2.orp":"success"}
{"status":"empty"}
*/


################################################################################

### Upload Tests
/*
$FileSystem = new FileSystem();
echo $FileSystem->get_audio_filesJSON('identification');
*/

################################################################################

### Module Tests

/*
$Modules = new Modules();

$sampleJSON = '{"2":{"moduleKey":"2","svxlinkID":"2"},"3":{"moduleKey":"3","svxlinkID":"1"}}';

$sampleArray = json_decode($sampleJSON, true);

$result = $Modules->write_modules($sampleArray);

var_dump($result);
*/

/*
$Database = new Database();
print_r($Database->get_modules());
*/


################################################################################

### Memcache Test

/*
$Database = new Database();

// $Database->set_update_flag(false);
$Database->set_update_flag(false);

$flagState = $Database->get_update_flag();

var_dump($flagState);

echo gethostname();

// 0.0.0.0:11211
*/


/*
$memcached_obj = new Memcached;
$memcached_obj->addServer('Memcached', 11211);
$memcached_obj->set('update_settings_flag', 1); // Set Flag

var_dump($memcached_obj->get('update_settings_flag'));

$state = $memcached_obj->get('update_settings_flag');
if ($state == 1) {
	echo "true1";
} else {
	echo "false1";
}
*/

################################################################################

### User Password Tests

/*
$Users = new Users();
// $test = $Users->change_password('openrepeater','test1','test12');
$test = $Users->change_password('test1','test2','test2');
// $test = $Users->setPassword('2','test1');
var_dump($test);
*/

################################################################################

### Test Login Function
/*
$Users = new Users();
$test = $Users->login('n3mbh', 'testPW2');
var_dump($test);
*/

################################################################################

### Add User Test
/*
$Users = new Users();
$test = $Users->add('n3mbh', 'testpw', 'testpw', 'admin');
echo $test;
*/

################################################################################

### Set Password
/*
$Users = new Users();
$Users->setPassword('2', 'testPW');
*/

################################################################################

### Enable/Disable User
/*
$Users = new Users();
// $enable = $Users->enableUser('2');
$enable = $Users->disableUser('2','1');
var_dump($enable);
*/

################################################################################

### Verify User Password
/*
$Users = new Users();
$verifyPassword = $Users->verifyPassword('testPW', '90f9f7f2ebabb344c73b3772c57d00237ed1173162519c7e3a210888c8f0c932', '70365305');
// $verifyPassword = $Users->verifyPassword($password, $pwHash, $salt);
var_dump($verifyPassword);
*/


######################################################################
echo '<hr>';

// $Users->startUserSession ('2', 'admin2', '3.x', 'N3MBH');
// $Users->endUserSession();


// CANNOT DO THIS AT TOP OF FILES
if ((isset($_SESSION['username'])) || (isset($_SESSION['userID']))){
	echo 'LOGGED IN';
} else {
	echo 'NOT LOGGED IN';	
}

######################################################################

echo "<br><hr>done";

?>
