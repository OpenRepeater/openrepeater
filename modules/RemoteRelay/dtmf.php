<?php
/*
* This is the file that gets called for this module when OpenRepeater displays the DTMF commands. This file is optional,
* but highly recommended if your module has DTMF commands. 
*/

$module_id = $cur_mod['moduleKey'];
$options = unserialize($cur_mod['moduleOptions']);

$sub_subcommands = 'STATUS REPORT
0#		Speaks state of all relays (On/Off)

';


if ($options['relay']) {
	ksort($options['relay']);
	$sub_subcommands .= 'INDIVIDUAL RELAY CONTROL';
	foreach($options['relay'] as $cur_parent_array => $cur_child_array) {
$sub_subcommands .= '
'.$cur_parent_array.'0#		Relay '.$cur_parent_array.' Off
'.$cur_parent_array.'1#		Relay '.$cur_parent_array.' On
'.$cur_parent_array.'2#		Relay '.$cur_parent_array.' Momentary
';
	}	
}


$sub_subcommands .= '
GROUP RELAY CONTROL
100#		All Relays Off
101#		All Relays On
102#		All Relays Momentary

DIAGNOSTICS
999#		Relay Test Procedure

#		Deactivate Relay Module';

?>