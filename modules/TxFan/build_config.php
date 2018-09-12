<?php

/*
// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'ID' => $cur_mod['svxlinkID'],
	'TIMEOUT' => '60',				
	'FIFO_LEN' => '60',
	'REPEAT_DELAY' => '1000',
];
*/


// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'ID' => $cur_mod['svxlinkID'],
	'PLUGIN_NAME' => 'Tcl',
	'MODE' => 'COUNT_DOWN',
	'DELAY' => '1',
	'PTT_PATH_1' => '/sys/class/gpio/gpio506/value',
	'PTT_PATH_2' => '/sys/class/gpio/gpio507/value',
	'FAN_GPIO' => '/sys/class/gpio/gpio25/value',
];


/*
[ModuleTxFan]
NAME=TxFan
PLUGIN_NAME=Tcl

#Select the operating mode "FOLLOW_PTT" or "COUNT_DOWN"
#MODE="FOLLOW_PTT"
MODE="COUNT_DOWN"

#Number of seconds to delay during COUNT_DOWN mode
DELAY=10

# Path in the file system where the digital inputs can be monitored
# 2 paths are required, if there is only 1 PTT, assign them the same GPIO.
PTT_PATH_1="/sys/class/gpio/gpio506/value"
PTT_PATH_2="/sys/class/gpio/gpio507/value"

FAN_GPIO="/sys/class/gpio/gpio497/value"
*/


?>