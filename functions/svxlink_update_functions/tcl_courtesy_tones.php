<?php
# Copyright ©2017 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php


/* ---------------------------------------------------------- */
/* COURTESY TONE FUNCTIONS */

function playSilence() {
	$playSilence = '
		proc send_rgr_sound {} {
			playSilence 200
		}
	';
	return $playSilence;
}

function playBeep() {
	$playBeep = '
		proc send_rgr_sound {} {
			playTone 660 500 200;
			playSilence 200
		}
	';
	return $playBeep;
}

function playCustomTone($filename) {
	$playTone = '
		proc send_rgr_sound {} {
			playFile "/var/lib/openrepeater/sounds/courtesy_tones/'.$filename.'"
			playSilence 200
		}
	';
	return $playTone;
}

/* ---------------------------------------------------------- */
/* COURTESY TONES */


		$tclLogicNameSpace .= '
		# Executed when the squelch has closed and the RGR_SOUND_DELAY timer has expired.';


switch ($settings['courtesyMode']) {

    case "disabled":
		// No Courtesy Tone Played 
		$tclLogicNameSpace .= playSilence();
        break;

    case "beep":
		// Generic Beep Played
		$tclLogicNameSpace .= playBeep();
        break;

    case "custom":
		// Play Custom Courtesy Tone
		$tclLogicNameSpace .= playCustomTone($settings['courtesy']);
        break;
}

?>
