<?php
/*
* This is the file that gets called for this module when OpenRepeater rebuilds the configuration files for SVXLink.
* Settings for the config file are created as a PHP associative array, when the file is called it will convert it into
* the requiried INI format and write the config file to the appropriate location with the correct naming.
*/

// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'ID' => $cur_mod['svxlinkID'],
	'TIMEOUT' => '60',				
	'FIFO_LEN' => '60',
	'REPEAT_DELAY' => '1000',
];

?>