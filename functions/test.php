<?php

$courtesyToneMode = "custom"; //none, beep (default), custom

$cw_amplitude = "150";
$cw_wpm = "25";
$cw_pitch = "1200";

$shortID_callSignID = "True";
$shortID_cwID = "True";

$longID_callSignID = "True";
$longID_cwID = "True";
$longID_time = "True";

// --------------------------------------------------------------------

function build_identification($id_type)
{
    return $id_type;
}

// --------------------------------------------------------------------

function build_courtesyTone($mode, $file) {
	$tclOverride = '';
	switch ($mode) {
		case "beep":
			// NO COURTESY TONE
			$tclOverride .= '
			#
			# Executed when the squelch just have closed and the RGR_SOUND_DELAY timer has
			# expired.
			#
			proc send_rgr_sound {} {
			  playTone 440 500 100;
			  playSilence 200;
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
			  playFile "//courtesy_tones/'.$file.'"
			  playSilence 200
			}
			';
			break;
		default:
			// NO COURTESY TONE
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
	}

    return $tclOverride;
}

// --------------------------------------------------------------------

echo build_identification('long');

echo '<hr>';

//$settings['courtesy']
echo build_courtesyTone('none','FILE_NAME');

?>