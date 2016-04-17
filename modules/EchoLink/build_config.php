<?php

// REMOVE THESE LINES WHEN INCLUDED IN SVXLINK_UPDATE.PHP
include_once("../../includes/get_modules.php");
$module_id = 3;


$options = unserialize($module[$module_id]['moduleOptions']);
print_r($options);



$echolink_clean_desc = preg_replace('/\r\n?/', "\\n", $options['description']);


$module_config_output = "
[Module".$module[$module_id]['svxlinkName']."]
NAME=".$module[$module_id]['svxlinkName']."
ID=".$module[$module_id]['svxlinkID']."
TIMEOUT=".$options['timeout']."
";

$module_config_output .= '
	SERVERS='.$options['server'].'
	CALLSIGN='.$options['callSign'].'
	PASSWORD='.$options['password'].'
	SYSOPNAME='.$options['sysop'].'
	LOCATION='.$options['location'].'
	MAX_QSOS='.$options['max_qsos'].'
	MAX_CONNECTIONS='.$options['connections'].'
	LINK_IDLE_TIMEOUT='.$options['idle_timeout'].'
	DESCRIPTION="'.$echolink_clean_desc.'"
	USE_GSM_ONLY=1

';

//Array ( [timeout] => 60 [server] => servers.echolink.org [callSign] => N3MBH-R [password] => PASSWORD [sysop] => OpenRepeater [location] => WV [description] => Welcome to an Open Repeater Server [max_qsos] => 4 [connections] => 4 [idle_timeout] => 300 )

// Clean up tabs/white spaces
$module_config_output = preg_replace('/\t+/', '', $module_config_output);


//if (isset($moduleEchoLink)) { file_put_contents('/etc/openrepeater/svxlink/svxlink.d/ModuleEchoLink.conf', $orpFileHeader . $moduleEchoLink); }
?>

<hr>
<pre><?php echo $module_config_output; ?></pre>
