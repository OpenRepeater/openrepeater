 <?php
# Copyright ©2018 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

/*
This script reads settings from the OpenRepeater database and builds new configuration
files for SVXLink. 
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
require_once('../includes/classes/SVXLink_TCL.php');
require_once('../includes/classes/SVXLink_GPIO.php');

$classDB = new Database();
$classSVXLink = new SVXLink($settings, $ports, $module);
$classSVXLinkTCL = new SVXLink_TCL($settings);
$classSVXLinkGPIO = new SVXLink_GPIO($gpio);

/* ---------------------------------------------------------- */
/* --- LOGIC SETTINGS --- */

$classSVXLink->delete_custom_evnets(); // Purge Previous Custom Event Files

switch ($settings['orp_Mode']) {

	###############################################
	# Repeater Setup
	###############################################

    case "repeater":

		foreach ($ports as $key => $val) {
			// Build Ports
			$config_array += $classSVXLink->build_rx($key); // Build RX
			$config_array += $classSVXLink->build_tx($key); // Build TX


			// If there is more than one port, build 1st port as repeater, and additional ports as simplex links
			if ($key == 1) {
				// Build Repeater Logic
				$new_logic_name = 'ORP_RepeaterLogic_Port' . $key;
				$new_logic_filename = $new_logic_name . '.tcl';
	
				$config_array += $classSVXLink->build_logic_repeater($new_logic_name, $key);
	
				$new_event = $classSVXLinkTCL->alias_RepeaterLogic($new_logic_name);
				$classSVXLink->write_config($new_event, $new_logic_filename, 'text');
				
				$logicListArray = array($new_logic_name);
			
				
			} else {
				// Build Link/Simplex Logic
				$new_logic_name = 'ORP_SimplexLogic_Port' . $key;
				$new_logic_filename = $new_logic_name . '.tcl';
	
				$config_array += $classSVXLink->build_logic_simplex($new_logic_name, $key);
	
				$new_event = $classSVXLinkTCL->alias_SimplexLogic($new_logic_name);
				$classSVXLink->write_config($new_event, $new_logic_filename, 'text');

				$logicListArray[] = $new_logic_name;
					
			}
			
		}


		if (count($logicListArray) > 1) {
			// BUILD LINK SECTION
			$config_array += $classSVXLink->build_link( 'LinkSection', $logicListArray );			
		}


		// GLOBAL SETTINGS
		$config_array['GLOBAL'] += $classSVXLink->build_global();

		// Build GPIO Config
		$gpioConfigFile = $classSVXLinkGPIO->build_gpio_config();

		# Insert TCL Overrides
		$tclOverride = $classSVXLinkTCL->build_custom_tcl();


		// WRITE CONFIGURATION & TCL FILES
		$classSVXLink->write_config($config_array, 'svxlink.conf', 'ini');
		$classSVXLink->write_config($tclOverride, 'CustomLogic.tcl', 'text');
		$classSVXLink->write_config($gpioConfigFile, 'gpio.conf', 'text');

        break;


	###############################################
	# Simplex Setup
	###############################################

    case "simplex":
		$useLogic = 'SimplexLogic';
		
		### WORK IN PROGRESS
		
        break;


	###############################################
	# Advanced Setup
	###############################################

    case "advanced":
		// Process advanced mode overrides
		include_once("../includes/get_advanced.php");
	
		// WRITE CONFIGURATION & TCL FILES
		$classSVXLink->write_config($advanced['svxlink_config'], 'svxlink.conf', 'text'); // Overridden svxlink.confg
		unlink('/etc/openrepeater/svxlink/local-events.d/CustomLogic.tcl'); // Delete custom TCL overrides if they exist
		$classSVXLink->write_config($advanced['gpio_config'], 'gpio.conf', 'text'); // Overridden GPIO config
        break;

}

	
/* ---------------------------------------------------------- */
/* FINISH UP */

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
