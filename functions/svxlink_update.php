<?php

/*

This script reads settings from the OpenRepeater database and builds new configuration
files for SVXLink. It currently builds the following configuration files:
- svxlink.conf
- ModuleEchoLink.conf
- local TCL overrides
*/

// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

//UNUSED VARIABLES IN DATABASE...FROM OLD PROGRAM, NOT YET IMPLEMENTED WITH SVXLINK
// phoneticCallSign
// repeaterTimeoutSec
// rxFreq
// timeoutMsg
// txFreq
// voiceID

// Get Settings from SQLite
include_once("../includes/get_settings.php");

// Get Port Settings from SQLite
include_once("../includes/get_ports.php");

/* ---------------------------------------------------------- */
/* SVXLINK CONFIGURATION SETTINGS */

// Functions
function built_rx($curPort, $portsArray) {
	$rx_section = '# '.$portsArray[$curPort]['portLabel'].' Receive
	[Rx'.$curPort.']
	TYPE=Local
	AUDIO_DEV='.$portsArray[$curPort]['rxAudioDev'].'
	AUDIO_CHANNEL='.$portsArray[$curPort]['rxAudioChl'].'
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
	$tx_section = '# '.$portsArray[$curPort]['portLabel'].' Transmit
	[Tx'.$curPort.']
	TYPE=Local
	AUDIO_DEV='.$portsArray[$curPort]['txAudioDev'].'
	AUDIO_CHANNEL='.$portsArray[$curPort]['txAudioChl'].'

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
	DTMF_TONE_AMP=-18

	';
	return $tx_section;
}

/* --- GLOBAL SETTINGS --- */

	$svx_global = '[GLOBAL]
	MODULE_PATH=/usr/lib/svxlink
	LOGICS=RepeaterLogic
	CFG_DIR=svxlink.d
	TIMESTAMP_FORMAT="%c"
	CARD_SAMPLE_RATE=16000
	#LOCATION_INFO=LocationInfo
	#LINKS=LinkToR4

	';

/* --- REPEATER LOGIC SETTINGS --- */

	// Build List of Modules to run
	$modulesArray = array();
	if ($settings['help_enabled'] == "True") { $modulesArray[] = 'ModuleHelp'; }
	if ($settings['parrot_enabled'] == "True") { $modulesArray[] = 'ModuleParrot'; }
	if ($settings['echolink_enabled'] == "True") { $modulesArray[] = 'ModuleEchoLink'; }
	if ($settings['voicemail_enabled'] == "True") { $modulesArray[] = 'ModuleTclVoiceMail'; }

	if(!empty($modulesArray)) {
		$modulesList = 'MODULES=' . implode(",", $modulesArray);
	} else {
		$modulesList = '#MODULES=NONE';
	}

	$svx_repeaterLogic = '[RepeaterLogic]
	TYPE=Repeater
	RX=Rx1
	TX=Tx1
	'.$modulesList.'
	CALLSIGN='.$settings['callSign'].'
	SHORT_IDENT_INTERVAL='.$settings['idTimeValueMin'].'
	LONG_IDENT_INTERVAL='.$settings['idLongTimeValueMin'].'
	EVENT_HANDLER=/usr/share/svxlink/events.tcl
	DEFAULT_LANG=en_US
	RGR_SOUND_DELAY=1
	REPORT_CTCSS='.$settings['rxTone'].'
	TX_CTCSS=ALWAYS
	MACROS=Macros
	FX_GAIN_NORMAL=0
	FX_GAIN_LOW=-12
	IDLE_TIMEOUT=1
	OPEN_ON_SQL=1
	OPEN_SQL_FLANK=OPEN
	IDLE_SOUND_INTERVAL=0

	';

/* --- PORT SETTINGS - Generates RX & TX sections for each port --- */


// Define GPIO pin arrays
$gpioRxArray = array();
$gpioTxArray = array();

$svx_ports = '';
foreach ($ports as $key => $val) {
	$svx_ports .= built_rx($key, $ports);
	$svx_ports .= built_tx($key, $ports, $settings);
	
	//Define GPIO pins in arrays for later writing to config file.
	$gpioRxArray[] = $ports[$key]['rxGPIO'];
	$gpioTxArray[] = $ports[$key]['txGPIO'];
}

//Note that while this section can build multipe TX & RX sections from ports table, there is no utilization of this feature yet in other logic.

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
/* BUILD CUSTOM TCL OVERRIDES...ie COURTESY TONES, ETC */

$courtesyToneMode = "custom"; //none, beep (default), custom

$cw_amplitude = "200";
$cw_wpm = "20"; // 10, 15, 20, 25
$cw_pitch = "800"; // 400, 600, 800, 1000, 1200 (Hz)

$shortID_callSignID = "True";
$shortID_cwID = "True";

$longID_callSignID = "True";
$longID_cwID = "True";
$longID_time = "True";

// FILE HEADER
$tclOverride = '
###############################################################################
#
# Overridden generic Logic event handlers
#
###############################################################################
';

// LOGIC NAME SPACE
$tclOverride .= '
#
# This is the namespace in which all functions and variables below will exist.
#
namespace eval Logic {
';

// --------------------------------------------------------------
$tclOverride .= '
		#
		# Executed when a short identification should be sent
		#   hour    - The hour on which this identification occur
		#   minute  - The hour on which this identification occur
		#
		proc send_short_ident {{hour -1} {minute -1}} {
		  global mycall;
		  variable CFG_TYPE;
		  playSilence 200;
';

		  if ($shortID_callSignID == "True") {
			$tclOverride .= '
			spellWord $mycall;
			if {$CFG_TYPE == "Repeater"} {
				playMsg "Core" "repeater";
			}
			playSilence 500;
			';
		  }

		  if ($shortID_cwID == "True") {

			$tclOverride .= '
			CW::setAmplitude '.$cw_amplitude.'
			CW::setWpm '.$cw_wpm.'
			CW::setPitch '.$cw_pitch.'
			CW::play $mycall
			playSilence 500;
			';
		  }

		$tclOverride .= '
		}

		#
		# Executed when a long identification (e.g. hourly) should be sent
		#   hour    - The hour on which this identification occur
		#   minute  - The hour on which this identification occur
		#
		proc send_long_ident {hour minute} {
		  global mycall;
		  global loaded_modules;
		  global active_module;
		  variable CFG_TYPE;
		  playSilence 200;
		  ';

		  if ($longID_callSignID == "True") {
			$tclOverride .= '
		    spellWord $mycall;
		    if {$CFG_TYPE == "Repeater"} {
		    	playMsg "Core" "repeater";
		    }
		    playSilence 500;
			';
		  }

		  if ($longID_time == "True") {
			$tclOverride .= '
		    playMsg "Core" "the_time_is";
		    playSilence 100;
		    playTime $hour $minute;
		    playSilence 500;
			';
		  }

		  if ($longID_cwID == "True") {
			$tclOverride .= '
			CW::setAmplitude '.$cw_amplitude.'
			CW::setWpm '.$cw_wpm.'
			CW::setPitch '.$cw_pitch.'
			CW::play $mycall
			playSilence 500;
			';
		  }

		  if ($longID_xxx == "True") {
			$tclOverride .= '
			';
		  }

		$tclOverride .= '

			# Call the "status_report" function in all modules if no module is active
		  if {$active_module == ""} {
			foreach module [split $loaded_modules " "] {
			  set func "::";
			  append func $module "::status_report";
			  if {"[info procs $func]" ne ""} {
				$func;
			  }
			}
		  }
		  playSilence 500;
		}
';
// --------------------------------------------------------------

switch ($courtesyToneMode) {
    case "none":
		$tclOverride .= '
		#
		# Executed when the squelch just have closed and the RGR_SOUND_DELAY timer has
		# expired.
		#
		proc send_rgr_sound {} {
		  playSilence 500
		}
		';
        break;

    case "custom":
		// PLAY COURTESY TONE
		$tclOverride .= '
		#
		# Executed when the squelch just have closed and the RGR_SOUND_DELAY timer has
		# expired.
		#
		proc send_rgr_sound {} {
		  playFile "/usr/share/openrepeater/sounds/courtesy_tones/'.$settings['courtesy'].'"
		  playSilence 200
		}
		';
        break;
    default:
        $tclOverride .= '';
}

// LOGIC NAME SPACE - END
$tclOverride .= '
# end of namespace
}
';

// --------------------------------------------------------------

// RepeaterLogic Override
$tclOverride .= '
		namespace eval RepeaterLogic {

			proc repeater_up {reason} {
			  global mycall;
			  global active_module;
			  variable repeater_is_up;

			  set repeater_is_up 1;

			  if {($reason != "SQL_OPEN") && ($reason != "CTCSS_OPEN") &&
				  ($reason != "SQL_RPT_REOPEN")} {
				set now [clock seconds];
				if {$now-$Logic::prev_ident < $Logic::min_time_between_ident} {
				  return;
				}
				set Logic::prev_ident $now;
				playSilence 250;

			  ';

			  if ($shortID_callSignID == "True") {
				$tclOverride .= '
				spellWord $mycall;
				playMsg "Core" "repeater";
				playSilence 500;
				';
			  }

			  if ($shortID_cwID == "True") {

				$tclOverride .= '
				CW::setAmplitude '.$cw_amplitude.'
				CW::setWpm '.$cw_wpm.'
				CW::setPitch '.$cw_pitch.'
				CW::play $mycall
				playSilence 500;
				';
			  }

			  $tclOverride .= '

				if {$active_module != ""} {
				  playMsg "Core" "active_module";
				  playMsg $active_module "name";
				}
			  }
			}

			#
			# Executed when the repeater is deactivated
			#   reason  - The reason why the repeater was deactivated
			#             IDLE         - The idle timeout occured
			#             SQL_FLAP_SUP - Closed due to interference
			#
			proc repeater_down {reason} {
			  global mycall;
			  variable repeater_is_up;

			  set repeater_is_up 0;

			  if {$reason == "SQL_FLAP_SUP"} {
				playSilence 500;
				playMsg "Core" "interference";
				playSilence 500;
				return;
			  }

			  set now [clock seconds];
			  if {$now-$Logic::prev_ident < $Logic::min_time_between_ident} {
#				playTone 400 900 50
#				playSilence 100
#				playTone 360 900 50
				playSilence 500
				return;
			  }
			  set Logic::prev_ident $now;

			  playSilence 250;

			  ';

			  if ($shortID_callSignID == "True") {
				$tclOverride .= '
				spellWord $mycall;
				playMsg "Core" "repeater";
				playSilence 500;
				';
			  }

			  if ($shortID_cwID == "True") {

				$tclOverride .= '
				CW::setAmplitude '.$cw_amplitude.'
				CW::setWpm '.$cw_wpm.'
				CW::setPitch '.$cw_pitch.'
				CW::play $mycall
				playSilence 500;
				';
			  }

			  $tclOverride .= '

			  #playMsg "../extra-sounds" "shutdown";
			}


		# end of namespace
		}
';

// --------------------------------------------------------------
$tclOverride .= '
#
# This file has not been truncated
#
';

/* ---------------------------------------------------------- */
/* WRITE GPIO PINS TO STARTUP FILE */

$gpioRxString = implode(" ", $gpioRxArray);
$gpioTxString = implode(" ", $gpioTxArray);

$gpioConfigFile .= '
	# GPIO_PTT_PIN="<num> <num>"
	#     <num> defines the GPIO pin(s) used for PTT / TX.
	# GPIO_SQL_PIN="<num> <num>"
	#     <num> defines the GPIO pin(s) used for Squelch (COS) / RX.
	 
	GPIO_PTT_PIN="'.$gpioTxString.'"
	GPIO_SQL_PIN="'.$gpioRxString.'"
';		

// TODO: Need to add function to check existing GPIO pins in /sys/class/gpio 
// and see if new pins in ports table exist since system boot and if not add them.

#Clean up tabs/white spaces
$svx_global = preg_replace('/\t+/', '', $svx_global);
$svx_repeaterLogic = preg_replace('/\t+/', '', $svx_repeaterLogic);
$svx_ports = preg_replace('/\t+/', '', $svx_ports);
$moduleEchoLink = trim(preg_replace('/\t+/', '', $moduleEchoLink));
$gpioConfigFile = trim(preg_replace('/\t+/', '', $gpioConfigFile));

/* ---------------------------------------------------------- */
/* WRITE CONFIGURATION & TCL FILES */

file_put_contents('/etc/openrepeater/svxlink/svxlink.conf', $svx_global . $svx_repeaterLogic . $svx_ports);
file_put_contents('/etc/openrepeater/svxlink/svxlink.d/ModuleEchoLink.conf', $moduleEchoLink);
file_put_contents('/etc/openrepeater/svxlink/local-events.d/CustomLogic.tcl', $tclOverride);
file_put_contents('/etc/openrepeater/svxlink/svxlink_gpio.conf', $gpioConfigFile);


/* CLOSE DATABSE CONNECTION */
$dbConnection->close();


/* CLEAR SETTINGS UPDATE FLAG TO CLEAR BANNER AT TOP OF PAGE */
$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);
$memcache_obj->set('update_settings_flag', 0, false, 0);

$shellout = shell_exec('sudo /usr/local/bin/svxlink_restart');

header('location: ../dashboard.php');
?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
