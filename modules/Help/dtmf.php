<?php
/*
* This is the file that gets called for this module when OpenRepeater displays the DTMF commands. This file is optional,
* but highly recommended if your module has DTMF commands. 
*/

$sub_subcommands = '';

foreach($modules as $cur_mod_array) {
	if ($cur_mod_array['moduleEnabled']==1) {
		$curr_mod_ini = $this->read_ini($cur_mod_array['svxlinkName']);
		if (isset($curr_mod_ini['Module_Info']['display_name'])) {
			$currDisplayName = $curr_mod_ini['Module_Info']['display_name'];
		} else {
		    $currDisplayName = $cur_mod_array['svxlinkName'];
		}

		if ($currDisplayName=="Help") {
			$sub_subcommands .= $cur_mod_array['svxlinkID'] . "#		Overview of the Help Module\r";
		} else {
			$sub_subcommands .= $cur_mod_array['svxlinkID']."#		Help on ".$currDisplayName." Module\r";
			
		}
	}
}

$sub_subcommands .= '#		Exit Help';


?>
