<?php

$options = unserialize($cur_mod['moduleOptions']);
print_r($options);


$module_config_output = "
###############################################################################
#  OpenRepeater RemoteRelay Module
#  Coded by Aaron Crawford (N3MBH) & Juan Hagen (F8ASB)
#  For DTMF Control of up to 8 relay via GPIO pins as defined below.
#
#  General Usage (3 choices):
#  OFF = 0 | ON = 1 | MOMENTARY = 2
#  Example: 21#  -> Turns ON Relay 2 (if defined)
#
#  Visit the project at OpenRepeater.com
###############################################################################

[Module".$cur_mod['svxlinkName']."]
NAME=".$cur_mod['svxlinkName']."
PLUGIN_NAME=Tcl
ID=".$cur_mod['svxlinkID']."
TIMEOUT=".$options['timeout']."
";

if($options['momentary_delay']) {
	$module_config_output .= "
	# Momentary Delay in Milliseconds
	MOMENTARY_DELAY=".$options['momentary_delay']."
";	
}

if($options['access_pin']) {
	$module_config_output .= "
	# Define Access Code. To disable, comment out (#).
	ACCESS_PIN=".$options['access_pin'];
	
	if($options['access_attempts_allowed']) {
		$module_config_output .= "
		ACCESS_ATTEMPTS_ALLOWED=".$options['access_attempts_allowed'];
	}
	$module_config_output .= "\n";
}

$module_config_output .= "
# Setting this to 1 will turn off all relays when modules is exited or times out, timeout value is set above. Set to 0 or comment out to leave relays enabled upon module exit.
";
if($options['relays_off_deactivation'] == "1") {
	$module_config_output .= "RELAYS_OFF_DEACTIVATION=1\n\n";
} else {
	$module_config_output .= "RELAYS_OFF_DEACTIVATION=0\n\n";	
}


// Build Relay Variables in Config with comments.
if ($options['relay']) {
	ksort($options['relay']);
	$module_config_output .= "# Define Relay GPIO pins. To disable, comment out (#) or set to 0\n";
	foreach($options['relay'] as $cur_parent_array => $cur_child_array) {
		$module_config_output .= "# RELAY ".$cur_parent_array." (Label: ".
$cur_child_array['label'].")\n";
		$module_config_output .= "GPIO_RELAY_".$cur_parent_array."=".$cur_child_array['gpio']."\n\n";
	}	
}

?>