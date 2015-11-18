<?php
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
		  playFile "/usr/share/openrepeater/sounds/courtesy_tones/'.$filename.'"
		  playSilence 200
		}
		';
	return $playTone;
}

/* ---------------------------------------------------------- */
/* COURTESY TONES */


		$tclOverride .= '
		#
		# Executed when the squelch just have closed and the RGR_SOUND_DELAY timer has
		# expired.
		#
		';


switch ($settings['courtesyMode']) {

    case "disabled":
		// No Courtesy Tone Played 
		$tclOverride .= playSilence();
        break;

    case "beep":
		// Generic Beep Played
		$tclOverride .= playBeep();
        break;

    case "custom":
		// Play Custom Courtesy Tone
		$tclOverride .= playCustomTone($settings['courtesy']);
        break;
}

?>