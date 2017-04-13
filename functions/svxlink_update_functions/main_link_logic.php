<?php
# Copyright ©2017 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php


# CHECK IF THERE IS MORE THAN ONE PORT DEFINED. IF THERE IS, EACH ADDITIONAL PORT IS TREATED AS LINK PORT

if (count($ports) > 1) {
	// Define Variables
	$svx_link_logic = '';
	$logicsArrayLinks = array();
	
	// Loop through and build additinal link ports.
	foreach ($ports as $key => $val) {
		if ($key != 1 ) { // Ignore first/main port
			// Create Port Labeling, If more than one link adds number suffix. Count also include main repeater port.
			if (count($ports) > 2) {
				$linkNum = $key - 1;
				$linkSectionLabel = 'LinkLogic'.$linkNum;
			} else {
				$linkSectionLabel = 'LinkLogic';				
			}

			$svx_link_logic .= '###############################################################################
			# Link Logic: "' . trim($ports[$key]['portLabel']) . '"
			###############################################################################
			['.$linkSectionLabel.']
			TYPE=Simplex
			RX=Rx' . $key . '
			TX=Tx' . $key . '
			#MODULES=
			CALLSIGN='.$settings['callSign'].'
			EVENT_HANDLER=/usr/share/svxlink/events.tcl
			#MACROS=Macros
			SHORT_IDENT_INTERVAL='.$settings['ID_Short_IntervalMin'].'
			LONG_IDENT_INTERVAL='.$settings['ID_Long_IntervalMin'].'
			DEFAULT_LANG=en_US
			RGR_SOUND_DELAY=0
			REPORT_CTCSS='.$settings['rxTone'].'
			TX_CTCSS=ALWAYS
			FX_GAIN_NORMAL=0
			FX_GAIN_LOW=-12
			IDLE_TIMEOUT=1
			OPEN_ON_SQL=1
			OPEN_SQL_FLANK=OPEN
			IDLE_SOUND_INTERVAL=0
			
			';

/*
			if ($settings['repeaterDTMF_disable'] == 'True') {
			$svx_link_logic .= 'ONLINE_CMD=' . $settings['repeaterDTMF_disable_pin'] . '
			
			';
*/

			
			
			// Add this link name to array to include in LOGICS in global settings
			$logicsArrayLinks[] = $linkSectionLabel;		
		}
	}
}

?>
