<?php
################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$Database = new Database();
$settings = $Database->get_settings();

$Users = new Users();

/*
$username = 'n3mbh';
$userInfo = $Database->select_single("SELECT * FROM users WHERE username = '$username'");
print_r($userInfo);
*/

# Test Login Function
/*
$test = $Users->login('n3mbh', 'testPW2');
var_dump($test);
*/


/*
# Add User
$test = $Users->add('n3mbh', 'testpw', 'testpw', 'admin');
echo $test;
*/


/*
# Set Password
$Users->setPassword('2', 'testPW');
*/


/*
# Enable/Disable User
// $enable = $Users->enableUser('2');
$enable = $Users->disableUser('2','1');
var_dump($enable);
*/


/*
$verifyPassword = $Users->verifyPassword('testPW', '90f9f7f2ebabb344c73b3772c57d00237ed1173162519c7e3a210888c8f0c932', '70365305');
// $verifyPassword = $Users->verifyPassword($password, $pwHash, $salt);
var_dump($verifyPassword);
*/



######################################################################
echo '<hr>';

// $Users->startUserSession ('2', 'admin2', '3.x', 'N3MBH');
// $Users->endUserSession();


// CANNOT DO THIS AT TOP OF FILES
function verifyUserSession () {
	session_start();
	if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
		echo 'NOT LOGGED IN';	
		// header('location: login.php'); // If they aren't logged in, send them to login page.
/*
	} elseif (!isset($_SESSION['callsign'])) {
		header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
*/
	} else { // If they are logged in and have set a callsign, show the page.
		echo 'LOGGED IN';
	}
}

verifyUserSession ();

?>
<?php 

echo "<br><hr>done";
?>
