<?php
/*
* This is the file that gets called for this module when OpenRepeater rebuilds the configuration files for SVXLink.
* Settings for the config file are created as a PHP associative array, when the file is called it will convert it into
* the requiried INI format and write the config file to the appropriate location with the correct naming.
*/

$options = unserialize($cur_mod['moduleOptions']);


// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'PLUGIN_NAME' => 'Tcl',
	'ID' => $cur_mod['svxlinkID'],
	'TIMEOUT' => $options['timeout'],				
];


if($options['momentary_delay']) {
	// Momentary Delay in Milliseconds
	$module_config_array['Module'.$cur_mod['svxlinkName']] += [
		'MOMENTARY_DELAY' => $options['momentary_delay'],
	];
}

if($options['access_pin']) {
	// Define Access Code. To disable, comment out.
	$module_config_array['Module'.$cur_mod['svxlinkName']] += [
		'ACCESS_PIN' => $options['access_pin'],
	];

	if($options['access_attempts_allowed']) {
		$module_config_array['Module'.$cur_mod['svxlinkName']] += [
			'ACCESS_ATTEMPTS_ALLOWED' => $options['access_attempts_allowed'],
		];
	}
}

// Setting this to 1 will turn off all relays when modules is exited or times out, timeout value is set above. Set to 0 or comment out to leave relays enabled upon module exit.
if($options['relays_off_deactivation'] == "1") {
	$module_config_array['Module'.$cur_mod['svxlinkName']] += [
		'RELAYS_OFF_DEACTIVATION' => '1',
	];

} else {
	$module_config_array['Module'.$cur_mod['svxlinkName']] += [
		'RELAYS_OFF_DEACTIVATION' => '0',
	];
}


// Build Relay Variables in Config.
if ($options['relay']) {
	ksort($options['relay']);
	foreach($options['relay'] as $cur_parent_array => $cur_child_array) {
		$module_config_array['Module'.$cur_mod['svxlinkName']] += [
			'GPIO_RELAY_'.$cur_parent_array => $cur_child_array['gpio'],
		];
	}	
}

?>