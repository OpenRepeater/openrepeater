<?php

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

// Get Settings from SQLite
include_once("../includes/get_settings.php");

// Get Modules from SQLite
include_once("../includes/get_modules.php");

// Get Port Settings from SQLite
include_once("../includes/get_ports.php");

// Get GPIOs from SQLite that need to be set for OS (/sys/class/gpio/)
include_once("../includes/get_gpios.php");

/* ---------------------------------------------------------- */
/* SVXLINK CONFIGURATION SETTINGS */

// Functions
function built_rx($curPort, $portsArray) {
	$audio_dev = explode("|", $portsArray[$curPort]['rxAudioDev']);
	
	$rx_section = '# '.$portsArray[$curPort]['portLabel'].' Receive
	[Rx'.$curPort.']
	TYPE=Local
	AUDIO_DEV='.$audio_dev[0].'
	AUDIO_CHANNEL='.$audio_dev[1].'
	';

	if (strtolower($portsArray[$curPort]['rxMode']) == 'vox') {
	$rx_section .= '
		# VOX Squelch Mode
		SQL_DET=VOX
		VOX_FILTER_DEPTH=150
		VOX_THRESH=300
		SQL_HANGTIME=1000
		';
	} else {
	$rx_section .= '
		# COS Squelch Mode
		SQL_DET=GPIO
		GPIO_SQL_PIN=gpio'.$portsArray[$curPort]['rxGPIO'].'
		SQL_HANGTIME=10
		';
	}

	$rx_section .= '
	SQL_START_DELAY=1
	SQL_DELAY=10
	SIGLEV_SLOPE=1
	SIGLEV_OFFSET=0
	SIGLEV_OPEN_THRESH=30
	SIGLEV_CLOSE_THRESH=10
	DEEMPHASIS=1
	PEAK_METER=0
	DTMF_DEC_TYPE=INTERNAL
	DTMF_MUTING=1
	DTMF_HANGTIME=100
	DTMF_SERIAL=/dev/ttyS0

	';
	return $rx_section;
}

function built_tx($curPort, $portsArray, $settingsArray) {
	$audio_dev = explode("|", $portsArray[$curPort]['txAudioDev']);

	$tx_section = '# '.$portsArray[$curPort]['portLabel'].' Transmit
	[Tx'.$curPort.']
	TYPE=Local
	AUDIO_DEV='.$audio_dev[0].'
	AUDIO_CHANNEL='.$audio_dev[1].'

	PTT_TYPE=GPIO
	PTT_PORT=GPIO
	PTT_PIN=gpio'.$portsArray[$curPort]['txGPIO'].'
	PTT_HANGTIME='.($settingsArray['txTailValueSec'] * 1000).'

	TIMEOUT=300
	TX_DELAY=500
	';

	if ($settingsArray['txTone']) {
		$tx_section .= '
		CTCSS_FQ='.$settingsArray['txTone'].'
		CTCSS_LEVEL=9
		';
	}

	$tx_section .= '
	PREEMPHASIS=0
	DTMF_TONE_LENGTH=100
	DTMF_TONE_SPACING=50
	DTMF_TONE_PWR=-18

	';
	return $tx_section;
}

/* --- GLOBAL SETTINGS --- */

	switch ($settings['orp_Mode']) {
	    case "repeater":
			$useLogic = 'RepeaterLogic';
	        break;
	    case "simplex":
			$useLogic = 'SimplexLogic';
	        break;
	}


	$svx_global = '[GLOBAL]
	MODULE_PATH=/usr/lib/arm-linux-gnueabihf/svxlink
	LOGICS='.$useLogic.'
	CFG_DIR=svxlink.d
	TIMESTAMP_FORMAT="%c"
	CARD_SAMPLE_RATE=16000
	#LOCATION_INFO=LocationInfo
	#LINKS=LinkToR4

	';



/* ---------------------------------------------------------- */
/* --- BUILD MODULE SETTINGS --- */


	$modulesArray = array();
	foreach($module as $cur_mod) { 
		//$mod_settings_file = 'modules/'.$cur_mod['svxlinkName'].'/settings.php';
		if ($cur_mod['moduleEnabled']==1) {
			
			// Add Module name to array to output list in logic section
			$modulesArray[] = 'Module'.$cur_mod['svxlinkName'];
			
			// INCLUDE MODULE BUILD FILES HERE...IF THEY EXIST
			
		} 
	}
	
	// Buil Module List from Array
	if(!empty($modulesArray)) {
		$modulesList = 'MODULES=' . implode(",", $modulesArray);
	} else {
		$modulesList = '#MODULES=NONE';
	}


/* ---------------------------------------------------------- */
/* --- PORT SETTINGS - Generates RX & TX sections for each port --- */

$svx_ports = '';

foreach ($ports as $key => $val) {
	$svx_ports .= built_rx($key, $ports);
	$svx_ports .= built_tx($key, $ports, $settings);
}

// Note that while this section can build multipe TX & RX sections from ports table, there is no utilization of this feature yet in other logic.

/* ---------------------------------------------------------- */
/* --- LOGIC SETTINGS --- */

switch ($settings['orp_Mode']) {
    case "repeater":
		include('svxlink_update_functions/main_repeater_logic.php');
        break;
    case "simplex":
		include('svxlink_update_functions/main_simplex_logic.php');
        break;
}

/* ---------------------------------------------------------- */
/* MODULE: ECHOLINK CONFIGURATION SETTINGS */

if ($settings['echolink_enabled'] == "True") {

	$echolink_clean_desc = preg_replace('/\r\n?/', "\\n", $settings['echolink_desc']);

	$moduleEchoLink = '[ModuleEchoLink]
	NAME=EchoLink
	ID=2
	TIMEOUT=60
	SERVERS=servers.echolink.org
	CALLSIGN='.$settings['echolink_callSign'].'
	PASSWORD='.$settings['echolink_password'].'
	SYSOPNAME='.$settings['echolink_sysop'].'
	LOCATION='.$settings['echolink_location'].'
	MAX_QSOS=4
	MAX_CONNECTIONS=4
	LINK_IDLE_TIMEOUT=300
	DESCRIPTION="'.$echolink_clean_desc.'"
	USE_GSM_ONLY=1

	';
}

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
/* WRITE GPIO CONFIGURATION FILE */

// Define GPIO pin arrays
$gpioInHighArray = array();
$gpioInLowArray = array();
$gpioOutHighArray = array();
$gpioOutLowArray = array();

// Loop through each GPIO in database and assign to appropriate arrays
foreach ($gpio as $key => $val) {	
	if ($gpio[$key]['direction'] == "in") {
		if ($gpio[$key]['active'] == "low") {
			$gpioInLowArray[] = $gpio[$key]['gpio_num'];		
		} else {
			$gpioInHighArray[] = $gpio[$key]['gpio_num'];					
		}
	}

	if ($gpio[$key]['direction'] == "out") {
		if ($gpio[$key]['active'] == "low") {
			$gpioOutLowArray[] = $gpio[$key]['gpio_num'];		
		} else {
			$gpioOutHighArray[] = $gpio[$key]['gpio_num'];					
		}
	}

}

// Reformat arrays into space delminated lists of gpio pin numbers
$gpioInHighString = implode(" ", $gpioInHighArray);
$gpioInLowString = implode(" ", $gpioInLowArray);
$gpioOutHighString = implode(" ", $gpioOutHighArray);
$gpioOutLowString = implode(" ", $gpioOutLowArray);

// Build File Contents
$gpioConfigFile = '
	# Configuration file for the SVXLink server GPIO Pins
	
	#Set what GPIO pins point IN and have an Active HIGH state (3.3v = ON, 0v = OFF)
	GPIO_IN_HIGH="'.$gpioInHighString.'"

	#Set what GPIO pins point IN and have an Active LOW state (0v = ON, 3.3v = OFF)
	GPIO_IN_LOW="'.$gpioInLowString.'"

	#Set what GPIO pins point OUT and have an Active HIGH state (3.3v = ON, 0v = OFF)
	GPIO_OUT_HIGH="'.$gpioOutHighString.'"

	#Set what GPIO pins point OUT and have an Active LOW state (0v = ON, 3.3v = OFF) 
	GPIO_OUT_LOW="'.$gpioOutLowString.'"
';	

// TODO: Need to add function to check existing GPIO pins in /sys/class/gpio 
// and see if new pins in ports table exist since system boot and if not add them.

#Clean up tabs/white spaces
$svx_global = preg_replace('/\t+/', '', $svx_global);
$svx_logic = preg_replace('/\t+/', '', $svx_logic);
$svx_ports = preg_replace('/\t+/', '', $svx_ports);
if (isset($moduleEchoLink)) { $moduleEchoLink = trim(preg_replace('/\t+/', '', $moduleEchoLink)); }
$gpioConfigFile = trim(preg_replace('/\t+/', '', $gpioConfigFile));

/* ---------------------------------------------------------- */
/* WRITE CONFIGURATION & TCL FILES */


// Generate header message for top of ALL files output.
$orpFileHeader = '
###############################################################################
#
#  This file was auto generated by OpenRepeater. 
#  DO NOT MAKE CHANGES IN THIS FILE AS THEY WILL BE OVERWRITTEN
#
###############################################################################
';

file_put_contents('/etc/openrepeater/svxlink/svxlink.conf', $orpFileHeader . $svx_global . $svx_logic . $svx_ports);
if (isset($moduleEchoLink)) { file_put_contents('/etc/openrepeater/svxlink/svxlink.d/ModuleEchoLink.conf', $orpFileHeader . $moduleEchoLink); }
file_put_contents('/etc/openrepeater/svxlink/local-events.d/CustomLogic.tcl', $orpFileHeader . $tclOverride);
file_put_contents('/etc/openrepeater/svxlink/svxlink_gpio.conf', $orpFileHeader . $gpioConfigFile);


/* CLOSE DATABSE CONNECTION */
$dbConnection->close();


/* CLEAR SETTINGS UPDATE FLAG TO CLEAR BANNER AT TOP OF PAGE */
$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);
$memcache_obj->set('update_settings_flag', 0, false, 0);

$shellout = shell_exec('sudo /usr/bin/openrepeater_svxlink_restart');

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
