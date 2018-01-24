<?php
# Copyright ©2018 - Aaron Crawford, N3MBH - support(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php


function build_ini($input_array) {
	$section_separator = '###############################################################################';
	
	$ini_return = "";
	$section_count = 0;
	foreach($input_array as $ini_section => $ini_section_array) {
		$section_count++;
		if ($section_count > 1) { $ini_return .= $section_separator . "\n\n";}
	    $ini_return .= "[" . $ini_section . "]\n";
		foreach($ini_section_array as $key => $value) {
			$ini_return .= $key . "=" . $value . "\n";
		}
	    $ini_return .= "\n";
	}

	return $ini_return;
}

###############################################################################

function built_rx($curPort, $portsArray) {
	$audio_dev = explode("|", $portsArray[$curPort]['rxAudioDev']);
	
	$rx_array = [
		'TYPE' => 'Local',
		'AUDIO_DEV' => $audio_dev[0],
		'AUDIO_CHANNEL' => $audio_dev[1],
	];

	if (strtolower($portsArray[$curPort]['rxMode']) == 'vox') {
		// VOX Squelch Mode
		$rx_array += [
			'SQL_DET' => 'VOX',
			'VOX_FILTER_DEPTH' => '150',
			'VOX_THRESH' => '300',
			'SQL_HANGTIME' => '1000',
		];

	} else {
		// COS Squelch Mode
		$rx_array += [
			'SQL_DET' => 'GPIO',
			'GPIO_SQL_PIN' => 'gpio' . $portsArray[$curPort]['rxGPIO'],
			'SQL_HANGTIME' => '10',
		];
	}

	$rx_array += [
		'SQL_START_DELAY' => '1',
		'SQL_DELAY' => '10',
		'SIGLEV_SLOPE' => '1',
		'SIGLEV_OFFSET' => '0',
		'SIGLEV_OPEN_THRESH' => '30',
		'SIGLEV_CLOSE_THRESH' => '10',
		'DEEMPHASIS' => '1',
		'PEAK_METER' => '0',
		'DTMF_DEC_TYPE' => 'INTERNAL',
		'DTMF_MUTING' => '1',
		'DTMF_HANGTIME' => '100',
		'DTMF_SERIAL' => '/dev/ttyS0',
	];

	return $rx_array;
}

###############################################################################

function built_tx($curPort, $portsArray, $settingsArray) {
	$audio_dev = explode("|", $portsArray[$curPort]['txAudioDev']);

	$tx_array = [
		'TYPE' => 'Local',
		'AUDIO_DEV' => $audio_dev[0],
		'AUDIO_CHANNEL' => $audio_dev[1],
		'PTT_TYPE' => 'GPIO',
		'PTT_PORT' => 'GPIO',
		'PTT_PIN' => 'gpio'.$portsArray[$curPort]['txGPIO'],
		'PTT_HANGTIME' => ($settingsArray['txTailValueSec'] * 1000),
		'TIMEOUT' => '300',
		'TX_DELAY' => '500',
	];

	if ($settingsArray['txTone']) {
		$tx_array += [
			'CTCSS_FQ' => $settingsArray['txTone'],
			'CTCSS_LEVEL' => '9',
		];
	}

	$tx_array += [
		'PREEMPHASIS' => '0',
		'DTMF_TONE_LENGTH' => '100',
		'DTMF_TONE_SPACING' => '50',
		'DTMF_TONE_PWR' => '-18',
	];

	return $tx_array;
}

?>