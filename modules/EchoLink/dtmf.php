<?php
/*
* This is the file that gets called for this module when OpenRepeater displays the DTMF commands. This file is optional,
* but highly recommended if your module has DTMF commands. 
*/

$sub_subcommands = '9999# 		Connect to EchoLink by Node ID. (Node 9999 is ECHOTEST)
#		Disconnect from last connected station
##		Disconnect station and deactivate EchoLink Module
---------------------------------------------------
0#		Play the help message
1#		List all connected stations
2#		Play local EchoLink node id
31#		Connect to a random link or repeater
32#		Connect to a random conference
4#		Reconnect to the last disconnected station
50#		Deactivate listen only mode
51#		Activate listen only mode
';
//$sub_subcommands .= '6*??		Use the connect by callsign feature';
$sub_subcommands .= '7#		Use to disconnect a particular connected station from list';

?>