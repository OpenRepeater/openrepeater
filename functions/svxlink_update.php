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


/* ---------------------------------------------------------- */
/* --- LOAD CLASSES --- */

# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');

// Access database for Settings, Modules, Ports, and GPIOs
$classDB = new Database();
$settings = $classDB->get_settings();
$module = $classDB->get_modules();
$ports = $classDB->get_ports();
$gpio = $classDB->get_gpios();

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

				$new_event = $classSVXLinkTCL->override_courtesy_tone($new_event);

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

		# Insert Logic TCL Overrides
 		$logicOverride = $classSVXLinkTCL->logic_override();


		// WRITE CONFIGURATION & TCL FILES
		$classSVXLink->write_config($config_array, 'svxlink.conf', 'ini');
 		$classSVXLink->write_config($logicOverride, 'Logic.tcl', 'text');
		$classSVXLink->write_config($gpioConfigFile, 'gpio.conf', 'text');

        break;


	###############################################
	# Simplex Setup
	###############################################

    case "simplex":

		foreach ($ports as $key => $val) {
			// Build Ports
			$config_array += $classSVXLink->build_rx($key); // Build RX
			$config_array += $classSVXLink->build_tx($key); // Build TX

			// Simplex Logic
			$new_logic_name = 'ORP_SimplexLogic_Port' . $key;
			$new_logic_filename = $new_logic_name . '.tcl';

			$config_array += $classSVXLink->build_logic_simplex($new_logic_name, $key, true);

			$new_event = $classSVXLinkTCL->alias_SimplexLogic($new_logic_name);
			$classSVXLink->write_config($new_event, $new_logic_filename, 'text');
		}

		// GLOBAL SETTINGS
		$config_array['GLOBAL'] += $classSVXLink->build_global();

		// Build GPIO Config
		$gpioConfigFile = $classSVXLinkGPIO->build_gpio_config();

		# Insert Logic TCL Overrides
 		$logicOverride = $classSVXLinkTCL->logic_override();

		// WRITE CONFIGURATION & TCL FILES
		$classSVXLink->write_config($config_array, 'svxlink.conf', 'ini');
 		$classSVXLink->write_config($logicOverride, 'Logic.tcl', 'text');
		$classSVXLink->write_config($gpioConfigFile, 'gpio.conf', 'text');

        break;


	###############################################
	# Advanced Setup
	###############################################

    case "advanced":
		// Process advanced mode overrides
		$adv_results = $classDB->select_key_value('SELECT * from advanced', 'keyID', 'value');
		foreach($adv_results as $adv_key => $adv_value) {
			// Remove Window Newline characters and trim off leading and trailing whitespace.
			$advanced[$adv_key] = trim(str_replace("\r", "", $adv_value)) . "\n";
		}
	
		// WRITE CONFIGURATION & TCL FILES
		$classSVXLink->write_config($advanced['svxlink_config'], 'svxlink.conf', 'text'); // Overridden svxlink.confg
		unlink('/etc/openrepeater/svxlink/local-events.d/CustomLogic.tcl'); // Delete custom TCL overrides if they exist
		$classSVXLink->write_config($advanced['gpio_config'], 'gpio.conf', 'text'); // Overridden GPIO config
        break;

}

	
/* ---------------------------------------------------------- */
/* FINISH UP */

/* CLEAR SETTINGS UPDATE FLAG TO CLEAR BANNER AT TOP OF PAGE */
$classDB->set_update_flag(false);

########
# Redundant GPIO teardown/setup to work around SVXLink issue
$shellout = shell_exec('sudo /usr/sbin/orp_helper svxlink gpio_down');
$shellout = shell_exec('sudo /usr/sbin/orp_helper svxlink gpio_up');
########

$shellout = shell_exec('sudo /usr/sbin/orp_helper svxlink restart');

/* WHAT PAGE TO GO BACK TO */
if (isset($_POST["return_url"])) {
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
