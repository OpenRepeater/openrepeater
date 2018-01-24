<?php
# Copyright ©2018 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php

$config_array[$useLogic]['TYPE'] = 'Repeater';
$config_array[$useLogic]['RX'] = 'Rx1';
$config_array[$useLogic]['TX'] = 'Tx1';
$config_array[$useLogic][$modulesListKey] = $modulesListValue;
$config_array[$useLogic]['CALLSIGN'] = $settings['callSign'];
$config_array[$useLogic]['SHORT_IDENT_INTERVAL'] = $settings['ID_Short_IntervalMin'];
$config_array[$useLogic]['LONG_IDENT_INTERVAL'] = $settings['ID_Long_IntervalMin'];
$config_array[$useLogic]['EVENT_HANDLER'] = '/usr/share/svxlink/events.tcl';
$config_array[$useLogic]['DEFAULT_LANG'] = 'en_US';
$config_array[$useLogic]['RGR_SOUND_DELAY'] = '1';
$config_array[$useLogic]['REPORT_CTCSS'] = $settings['rxTone'];
$config_array[$useLogic]['TX_CTCSS'] = 'ALWAYS';
$config_array[$useLogic]['MACROS'] = 'Macros';
$config_array[$useLogic]['FX_GAIN_NORMAL'] = '0';
$config_array[$useLogic]['FX_GAIN_LOW'] = '-12';
$config_array[$useLogic]['IDLE_TIMEOUT'] = '1';
$config_array[$useLogic]['OPEN_ON_SQL'] = '1';
$config_array[$useLogic]['OPEN_SQL_FLANK'] = 'OPEN';
$config_array[$useLogic]['IDLE_SOUND_INTERVAL'] = '0';

if ($settings['repeaterDTMF_disable'] == 'True') {
	$config_array[$useLogic]['ONLINE_CMD'] = $settings['repeaterDTMF_disable_pin'];
}
?>
