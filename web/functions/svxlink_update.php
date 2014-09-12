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

// AUDIO DEVICES
$rxAudioDev = array('alsa:plughw:0','0'); // audio device, audio channel
$txAudioDev = array('alsa:plughw:0','1'); // audio device, audio channel

// Get Settings from MySQL
include_once("../_includes/get_settings.php");


/* ---------------------------------------------------------- */
/* SVXLINK CONFIGURATION SETTINGS */

/* --- GLOBAL SETTINGS --- */

	$svx_global = '[GLOBAL]
	MODULE_PATH=/usr/lib/svxlink
	LOGICS=RepeaterLogic
	CFG_DIR=svxlink.d
	TIMESTAMP_FORMAT="%c"
	CARD_SAMPLE_RATE=48000
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

/* --- RECEIVER SETTINGS --- */

	$svx_receive = '[Rx1]
	TYPE=Local
	AUDIO_DEV='.$rxAudioDev[0].'
	AUDIO_CHANNEL='.$rxAudioDev[1].'

	SQL_DET=GPIO
	GPIO_SQL_PIN=gpio23

	SQL_START_DELAY=1
	SQL_DELAY=0
	SQL_HANGTIME=20
	SIGLEV_SLOPE=1
	SIGLEV_OFFSET=0
	SIGLEV_OPEN_THRESH=30
	SIGLEV_CLOSE_THRESH=10
	DEEMPHASIS=0
	PEAK_METER=1
	DTMF_DEC_TYPE=INTERNAL
	DTMF_MUTING=1
	DTMF_HANGTIME=100
	DTMF_SERIAL=/dev/ttyS0

	';

/* --- TRANSMITTER SETTINGS --- */

	$svx_transmit = '[Tx1]
	TYPE=Local
	AUDIO_DEV='.$txAudioDev[0].'
	AUDIO_CHANNEL='.$txAudioDev[1].'

	PTT_PORT=GPIO
	PTT_PIN=gpio18
	PTT_HANGTIME='.($settings['txTailValueSec'] * 1000).'

	TIMEOUT=300
	TX_DELAY=500

	CTCSS_FQ='.$settings['txTone'].'
	#CTCSS_FQ=136.5
	CTCSS_LEVEL=9
	PREEMPHASIS=0
	DTMF_TONE_LENGTH=100
	DTMF_TONE_SPACING=50
	DTMF_TONE_AMP=-18

	';

	

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

$cw_amplitude = "150";
$cw_wpm = "25";
$cw_pitch = "1200";

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
		  playFile "/var/www/admin/courtesy_tones/'.$settings['courtesy'].'"
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
/* WRITE CONFIGURATION & TCL FILES */

file_put_contents('../svxlink/svxlink.conf', $svx_global . $svx_repeaterLogic . $svx_receive . $svx_transmit);
file_put_contents('../svxlink/svxlink.d/ModuleEchoLink.conf', $moduleEchoLink);
file_put_contents('../svxlink/local-events.d/CustomLogic.tcl', $tclOverride);



/* CLEAR SETTINGS UPDATE FLAG TO CLEAR BANNER AT TOP OF PAGE */
$memcache_obj = new Memcache;
$memcache_obj->connect('localhost', 11211);
$memcache_obj->set('update_settings_flag', 0, false, 0);


echo '
<h1>Done Building Server Configuration</h1>
<a href="'.$_POST['return_url'].'">Return to Admin Page</a>';
?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
