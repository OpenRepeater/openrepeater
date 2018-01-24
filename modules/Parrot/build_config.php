<?php

// Build Config
$module_config_array['Module'.$cur_mod['svxlinkName']] = [
	'NAME' => $cur_mod['svxlinkName'],
	'ID' => $cur_mod['svxlinkID'],
	'TIMEOUT' => '60',				
	'FIFO_LEN' => '60',
	'REPEAT_DELAY' => '1000',
];

?>