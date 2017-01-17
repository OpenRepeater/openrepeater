<?php
# Copyright ©2017 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php

$idPath = "/var/lib/openrepeater/sounds/identification/";

/* ---------------------------------------------------------- */
/* ID FUNCTIONS */

function buildMorseID($amplitue, $wpm, $pitch,  $suffix) {
	$morseID = '
			CW::setAmplitude '.$amplitue.'
			CW::setWpm '.$wpm.'
			CW::setPitch '.$pitch.'
			CW::play $mycall'.$suffix.'
			playSilence 500;
			';
	return $morseID;
}


function buildVoiceID() {
	$voiceID = '
			spellWord $mycall;
			if {$CFG_TYPE == "Repeater"} {
				playMsg "Core" "repeater";
			}
			playSilence 500;
			';
	return $voiceID;
}

function buildCustomID($path, $file) {
	$customID = '
			playFile "'.$path.$file.'"
			playSilence 500
	';
	return $customID;
}

function buildTime() {
	$time = '
			playMsg "Core" "the_time_is";
			playSilence 100;
			playTime $hour $minute;
			playSilence 500;
	';
	return $time;
}

/* ---------------------------------------------------------- */
/* SHORT ID OVERRIDES */



		$tclLogicNameSpace .= '
		# Executed when a short identification should be sent
		proc send_short_ident {{hour -1} {minute -1}} {
			global mycall;
			variable CFG_TYPE;
			playSilence 200;
		';


$shortIdString ='';

switch ($settings['ID_Short_Mode']) {
    case "disabled":
    	// Short ID - DISABLED
        break;

    case "morse":
    	// Short ID - MORSE
		$shortIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
        break;

    case "voice":
    	// Short ID - VOICE ID
		$shortIdString .= buildVoiceID();
		if ($settings['ID_Short_AppendMorse'] == 'True') {
			$shortIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
		}
        break;

    case "custom":
    	// Short ID - CUSTOM ID
		$shortIdString .= buildCustomID($idPath, $settings['ID_Short_CustomFile']);
		if ($settings['ID_Short_AppendMorse'] == 'True') {
			$shortIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
		}
        break;
}


$tclLogicNameSpace .= $shortIdString;

$tclLogicNameSpace .= '
		}
';

/* ---------------------------------------------------------- */
/* LONG ID OVERRIDES */

		$tclLogicNameSpace .= '
		# Executed when a long identification (e.g. hourly) should be sent
		proc send_long_ident {hour minute} {
			global mycall;
			global loaded_modules;
			global active_module;
			variable CFG_TYPE;
			playSilence 200;
			';

$longIdString = '';

switch ($settings['ID_Long_Mode']) {
    case "disabled":
    	// Long ID - DISABLED
        break;

    case "morse":
    	// Long ID - MORSE
		$longIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
        break;

    case "voice":
    	// Long ID - VOICE ID
		$longIdString .= buildVoiceID();
		if ($settings['ID_Long_AppendTime'] == 'True') {
			$longIdString .= buildTime();
		}
		if ($settings['ID_Long_AppendTone'] == 'True') {
			// FUTURE - Option to announce CTCSS / PL Tone;
		}
		if ($settings['ID_Long_AppendMorse'] == 'True') {
			$longIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
		}		
        break;

    case "custom":
    	// Long ID - CUSTOM ID
		$longIdString .= buildCustomID($idPath, $settings['ID_Long_CustomFile']);
		if ($settings['ID_Long_AppendTime'] == 'True') {
			$longIdString .= buildTime();
		}
		if ($settings['ID_Long_AppendTone'] == 'True') {
			// FUTURE - Option to announce CTCSS / PL Tone;
		}
		if ($settings['ID_Long_AppendMorse'] == 'True') {
			$longIdString .= buildMorseID($settings['ID_Morse_Amplitude'], $settings['ID_Morse_WPM'], $settings['ID_Morse_Pitch'], $settings['ID_Morse_Suffix']);
		}		
        break;
}


$tclLogicNameSpace .= $longIdString;

$tclLogicNameSpace .= '
		}
';

?>
