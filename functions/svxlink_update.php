 <?php
# Copyright ©2018 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

/*
This script reads settings from the OpenRepeater database and builds new configuration
files for SVXLink. It currently builds the following configuration files:
- svxlink.conf
- ModuleEchoLink.conf
- local TCL overrides
- svxlink_gpio.conf
*/

/* ---------------------------------------------------------- */
/* SESSION CHECK TO SEE IF USER IS LOGGED IN. */
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
/* ---------------------------------------------------------- */

// Declare Config Arrays
$config_array = array();
$config_array['GLOBAL'] = array(); // Declare empty for prioritization

// Get Settings from SQLite
include_once("../includes/get_settings.php");

// Get Modules from SQLite
include_once("../includes/get_modules.php");

// Get Port Settings from SQLite
include_once("../includes/get_ports.php");

// Get GPIOs from SQLite that need to be set for OS (/sys/class/gpio/)
include_once("../includes/get_gpios.php");

/* ---------------------------------------------------------- */
/* --- LOAD CLASSES --- */

require_once('../includes/classes/Database.php');
require_once('../includes/classes/SVXLink.php');
require_once('../includes/classes/SVXLink_GPIO.php');

$classDB = new Database();
$classSVXLink = new SVXLink($settings, $ports, $module);
$classSVXLinkGPIO = new SVXLink_GPIO($gpio);

/* ---------------------------------------------------------- */
/* --- PORT SETTINGS - Generates RX & TX sections for each port --- */

foreach ($ports as $key => $val) {
	$config_array += $classSVXLink->build_rx($key); // Build RX
	$config_array += $classSVXLink->build_tx($key); // Build TX
}

// Note that while this section can build multipe TX & RX sections from ports table, there is no utilization of this feature yet in other logic.

/* ---------------------------------------------------------- */
/* --- LOGIC SETTINGS --- */



	switch ($settings['orp_Mode']) {
	    case "repeater":
			$useLogic = 'RepeaterLogic';
			include('svxlink_update_functions/main_repeater_logic.php');
	        break;
	    case "simplex":
			$useLogic = 'SimplexLogic';
			include('svxlink_update_functions/main_simplex_logic.php');
	        break;
	}

/*
include('svxlink_update_functions/main_link_logic.php');
$svx_logic .= $svx_link_logic; // Append link logic to repeater logic
*/


/* --- GLOBAL SETTINGS --- */
	$config_array['GLOBAL'] += $classSVXLink->build_global($useLogic);

	

/* ---------------------------------------------------------- */
/* BUILD CUSTOM TCL OVERRIDES...ie COURTESY TONES, IDENTIFICATION, ETC */

// Define Strings Variables for TCL Namespaces. 
$tclLogicNameSpace = '';
$tclRepeaterLogicNameSpace = '';

// Include PHP files that build custom TCL Logic for the namespaces below
include('svxlink_update_functions/tcl_identification.php');
include('svxlink_update_functions/tcl_courtesy_tones.php');
include('svxlink_update_functions/tcl_TEMP.php');

// TCL Logic Namespace Override
$tclOverride = '
### Overridden Core Logic event handlers created by OpenRepeater
namespace eval Logic {
' . $tclLogicNameSpace . '
# end of namespace
}


### Overridden Repeater Logic event handlers created by OpenRepeater
namespace eval RepeaterLogic {
' . $tclRepeaterLogicNameSpace . '
# end of namespace
}

';

/*
namespace eval EchoLink {

		# Executed when an incoming connection is accepted
		proc remote_greeting {call} {
			playSilence 1000;
			playFile "/usr/share/svxlink/sounds/en_US/EchoLink/greeting.wav"
#			playMsg "greeting";
		}

 end of namespace
}
*/

/* ---------------------------------------------------------- */
/* BUILD GPIO CONFIGURATION FILE */

$gpioConfigFile = $classSVXLinkGPIO->build_gpio_config();

/* ---------------------------------------------------------- */
/* WRITE CONFIGURATION & TCL FILES */

if ($settings['orp_Mode'] == 'advanced') {
	// Process advanced mode overrides
	include_once("../includes/get_advanced.php");

	$classSVXLink->write_config($advanced['svxlink_config'], 'svxlink.conf', 'text'); // Overridden svxlink.confg
	unlink('/etc/openrepeater/svxlink/local-events.d/CustomLogic.tcl'); // Delete custom TCL overrides if they exist
	$classSVXLink->write_config($advanced['gpio_config'], 'gpio.conf', 'text'); // Overridden GPIO config

} else {
	// Otherwise process as usual
	$classSVXLink->write_config($config_array, 'svxlink.conf', 'ini');
	$classSVXLink->write_config($tclOverride, 'CustomLogic.tcl', 'text');
	$classSVXLink->write_config($gpioConfigFile, 'gpio.conf', 'text');
}

/*
echo '<pre>';
print_r($config_array);
echo '</pre>';
*/


/* CLOSE DATABSE CONNECTION */
$dbConnection->close();


/* CLEAR SETTINGS UPDATE FLAG TO CLEAR BANNER AT TOP OF PAGE */
$classDB->set_update_flag(false);



$shellout = shell_exec('sudo /usr/sbin/orp_helper svxlink restart');

/* WHAT PAGE TO GO BACK TO */
if ($_POST["return_url"]) {
	// Return to page that sent here
	$url = strtok($_POST["return_url"], '?'); //Clean parameters from URL
	header('location: '.$url);	
} else if (isset($_SESSION["new_repeater_settings"])) {
	// Wizard was run. Go ahead and destroy session and logout
	session_destroy();
	header('location: ../login.php');		
} else {
	// Otherwise just go to dashboard
	header('location: ../dashboard.php');	
}	
?>

<?php
/* ---------------------------------------------------------- */
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
/* ---------------------------------------------------------- */
?>
