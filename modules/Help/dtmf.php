<?php
$sub_subcommands = '';

foreach($module as $cur_help_mod) {
	if ($cur_help_mod['moduleEnabled']==1) {
		if ($cur_help_mod['moduleName']=="Help") {
			$sub_subcommands .= "0#		Overview of the Help Module\r";
		} else {
			$sub_subcommands .= $cur_help_mod['svxlinkID']."#		Help on ".$cur_help_mod['moduleName']." Module\r";
			
		}
	}
}

$sub_subcommands .= '#		Exit Help';
?>
