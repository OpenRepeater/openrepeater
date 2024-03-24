<?php
/*
* This is the file that gets called for this module when OpenRepeater rebuilds the configuration files for SVXLink.
* Settings for the config file are created as a PHP associative array, when the file is called it will convert it into
* the requiried INI format and write the config file to the appropriate location with the correct naming.
*/


$options = json_decode($cur_mod['moduleOptions']);

// Add Linebreaks into Description
$echolink_clean_desc = preg_replace('/\r\n?/', "\\n", trim($options->description));

// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'ID' => $cur_mod['svxlinkID'],
	'TIMEOUT' => $options->timeout,
	'SERVERS' => $options->server,
	'CALLSIGN' => trim( strtoupper($options->callSign) ),
	'DEFAULT_LANG' => $options->default_lang,
	'PASSWORD' => $options->password,
	'SYSOPNAME' => trim($options->sysop),
	'LOCATION' => '[ORP] '.trim($options->location),
	'MAX_QSOS' => $options->max_qsos,
	'MAX_CONNECTIONS' => $options->connections,
	'LINK_IDLE_TIMEOUT' => $options->idle_timeout,
	'DESCRIPTION' => $echolink_clean_desc,
	'USE_GSM_ONLY' => '1',

];

// EchoLink Auto Connect Options
if($options->auto_connect_id) {
	$module_config_array['Module'.$cur_mod['svxlinkName']]['AUTOCON_ECHOLINK_ID'] = $options->auto_connect_id;
	if($options->auto_connect_time) {
		$module_config_array['Module'.$cur_mod['svxlinkName']]['AUTOCON_TIME'] = $options->auto_connect_time;
	} else {
		$module_config_array['Module'.$cur_mod['svxlinkName']]['AUTOCON_TIME'] = '600'; // Otherwise default to 10 mins.
	}

}

// Proxy Server Settings
if($options->proxy_server) {
	$module_config_array['Module'.$cur_mod['svxlinkName']]['PROXY_SERVER'] = $options->proxy_server;
	if($options->proxy_port) {
		$module_config_array['Module'.$cur_mod['svxlinkName']]['PROXY_PORT'] = $options->proxy_port;
	}
	if($options->proxy_password) {
		$module_config_array['Module'.$cur_mod['svxlinkName']]['PROXY_PASSWORD'] = $options->proxy_password;
	}
}

?>
