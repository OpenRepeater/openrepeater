<?php
$options = unserialize($cur_mod['moduleOptions']);

// Add Linebreaks into Description
$echolink_clean_desc = preg_replace('/\r\n?/', "\\n", $options['description']);

// Build Config
$module_config_output = "
[Module".$cur_mod['svxlinkName']."]
NAME=".$cur_mod['svxlinkName']."
ID=".$cur_mod['svxlinkID']."
TIMEOUT=".$options['timeout']."
";

$module_config_output .= '
	SERVERS='.$options['server'].'
	CALLSIGN='.$options['callSign'].'
	PASSWORD='.$options['password'].'
	SYSOPNAME='.$options['sysop'].'
	LOCATION=[ORP] '.$options['location'].'
	MAX_QSOS='.$options['max_qsos'].'
	MAX_CONNECTIONS='.$options['connections'].'
	LINK_IDLE_TIMEOUT='.$options['idle_timeout'].'
	DESCRIPTION="'.$echolink_clean_desc.'"
	USE_GSM_ONLY=1

';

?>