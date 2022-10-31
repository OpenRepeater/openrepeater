<?php
# Copyright �2021 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

/*
This script reads settings from the OpenRepeater database and builds new configuration
files for SVXLink. Responses are sent back to UI via AJAX.
*/

/* ---------------------------------------------------------- */
/* SESSION CHECK TO SEE IF USER IS LOGGED IN. */
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	// If user is not logged in...STOP...and return not_logged_in status
	$response = (object)[]; // New response object
	$response->status = 'not_logged_in';	
	echo json_encode($response);

} else { // If they are, process the rebuild
	/* ---------------------------------------------------------- */

	// Declare Config Arrays
	$config_array = [];
	$config_array['GLOBAL'] = []; // Declare empty for prioritization

	/* ---------------------------------------------------------- */
	/* --- LOAD CLASSES --- */

	# AUTOLOAD CLASSES
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');

	// Access database for Settings, Modules, Ports, GPIOs, etc.
	$classDB = new Database();
	$classSystem = new System();

	$settings = $classDB->get_settings();
	$module = $classDB->get_modules();
	$ports = $classDB->get_ports();
	$gpio = $classDB->get_gpios();
	$devices = $classDB->get_devices();
	$macros = $classDB->get_macros();

	$classFunctions = new Functions();
	$classSVXLink = new SVXLink($settings, $ports, $module);
	$classSVXLinkTCL = new SVXLink_TCL($settings);
	$classSVXLinkGPIO = new SVXLink_GPIO($gpio);
	$classSVXLinkDevices = new SVXLink_Devices($devices);

	/* ---------------------------------------------------------- */
	/* --- LOGIC SETTINGS --- */

	$classSVXLink->delete_custom_evnets(); // Purge Previous Custom Event Files

	foreach ($ports as $key => $val) {
		if ( empty($val['portEnabled']) ) { $val['portEnabled'] = 1; } // Set port to enabled if variable doesn't exist
		if ( $val['portEnabled'] == 1 ) {
			switch ($val['portType']) {
				### LOCAL PORTS ###
				case 'GPIO':
				case 'HiDraw':
				case 'Serial':
					// Build Ports
					$config_array += $classSVXLink->build_rx( $key, $val['portType'] ); // Build RX
					$config_array += $classSVXLink->build_tx( $key, $val['portType'] ); // Build TX
		
					if ($val['portDuplex'] == 'full') {
						// Full Duplex Logic
						$config_array += $classSVXLink->build_full_duplex_logic($key);

						$new_logic_name = $classSVXLink->logicFullPrefix . $key;
						$new_logic_filename = $new_logic_name . '.tcl';
						$new_event = $classSVXLinkTCL->alias_RepeaterLogic($new_logic_name);

						$new_event = $classSVXLinkTCL->override_courtesy_tone($new_event);

					} else {
						// Half Duplex Logic
						$config_array += $classSVXLink->build_half_duplex_logic($key);

						$new_logic_name = $classSVXLink->logicHalfPrefix . $key;
						$new_logic_filename = $new_logic_name . '.tcl';
						$new_event = $classSVXLinkTCL->alias_SimplexLogic($new_logic_name);
					}
					$classFunctions->write_config($new_event, $new_logic_filename, 'text');

					break;

				### NETWORK PORTS ###
				case 'Network':
					$config_array += $classSVXLink->build_netRX( $key ); // Build Network RX
					$config_array += $classSVXLink->build_netTX( $key ); // Build Network TX

					$new_logic_name = ''; // Temp
					break;
			}


			// Add to LinkGroup
			if (is_array($val['linkGroup'])) {
				foreach ($val['linkGroup'] as $curLink) {
					$linkGroupArray[$curLink][$key] = $new_logic_name;
				}
			}

		}
	}
	$moduleConfigFileArray = $classSVXLink->configFileArray;


	// BUILD LINK SECTION - If link group contains 2 or more ports...built config
	if (isset($linkGroupArray)) {
		$linkArray = [];
		foreach ($linkGroupArray as $grpNumber => $grpArray) {
			if (count($grpArray) > 1) {
				$linkResults = $classSVXLink->build_link( $grpNumber, $grpArray );
				if ($linkResults != false) {
					$config_array += $linkResults;
				}

			}
		}
	}


	// BUILD MACRO SECTION
	$config_array += $classSVXLink->build_macro($macros);
	if (isset($classSVXLink->macros)) {
		foreach($classSVXLink->macros as $curLogicSect => $curMacroSect) {
			$config_array[$curLogicSect]['MACROS'] = $curMacroSect;
		}
	}


	// GLOBAL SETTINGS
	$config_array['GLOBAL'] += $classSVXLink->build_global();

	// LOCATION SETTINGS
	$locationResults = $classSVXLink->build_location();
	if ($locationResults != false) {
		$config_array += $locationResults;
		$config_array['GLOBAL']['LOCATION_INFO'] = $classSVXLink->location;
	}

	// Build GPIO Config
	$gpioConfigFile = $classSVXLinkGPIO->build_gpio_config();

	// Build Devices Config
	$devicesConfigFile = $classSVXLinkDevices->build_devices_config();

	// Insert Logic TCL Overrides
	$logicOverride = $classSVXLinkTCL->logic_override();

	// WRITE CONFIGURATION & TCL FILES
	$classFunctions->write_config($config_array, 'svxlink.conf', 'ini');
	$classFunctions->write_config($logicOverride, 'Logic.tcl', 'text');
	$classFunctions->write_config($gpioConfigFile, 'gpio.conf', 'text');

	$classFunctions->write_config($devicesConfigFile, 'devices.conf', 'text');

	// Write Active Config Files & Modules to DB for later reference. 
	$configFileArray = $classFunctions->configFileArray;
	$classFunctions->save_config_list(array_merge($configFileArray,$moduleConfigFileArray));


	/* ---------------------------------------------------------- */
	/* FINISH UP */

 	$shellout = $classSystem->svxlink_state('restart');

	######################################
	# Return AJAX Response
	######################################
	// Note: if user is not logged in, then a 'not_logged_in' status will be returned. (See top of file.)

	$response = (object)[]; // New response object

	// Get last item of shell/orp_helper response for SVXLink status.
	$pieces = explode(PHP_EOL, $shellout);
	$filtered = array_filter($pieces);
	$end = array_pop($filtered);
	$response->svxlink = trim($end); // Add to response object.

	// Wizard was run. Go ahead and destroy session and logout
	if (isset($_SESSION["new_repeater_settings"])) {
		session_destroy();
		$response->status = 'wizard';	

		// Clear update flag to remove rebuilt button
		$classDB->set_update_flag(false);

	} else {
		// Otherwise normal status
		$response->status = 'success';	

		// Clear update flag to remove rebuilt button
		$classDB->set_update_flag(false);
	}

	echo json_encode($response);

/*
### Fake Response for Docker Testing
$fakeResponse = '{"status":"success","svxlink":"inactive"}';
echo $fakeResponse;
*/


?>

<?php
	/* ---------------------------------------------------------- */
	// SESSION CHECK TO SEE IF USER IS LOGGED IN.
} // close ELSE to end login check from top of page
/* ---------------------------------------------------------- */
?>