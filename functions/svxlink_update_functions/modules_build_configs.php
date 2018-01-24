<?php
# Copyright ©2018 - Aaron Crawford, N3MBH - info(at)openrepeater(dot)com
# Licended under GPL v2 or later

// This is a sub-function file and gets included into svxlink_update.php

	
$modulesArray = array();
foreach($module as $cur_mod) { 
	if ($cur_mod['moduleEnabled']==1) {
		$module_config_array = array();
		
		// Add Module name to array to output list in logic section
		$modulesArray[] = 'Module'.$cur_mod['svxlinkName'];

		
		// Build Module Configuration
		$mod_build_file = '../modules/'.$cur_mod['svxlinkName'].'/build_config.php';
		if (file_exists($mod_build_file)) {
			// Module has a build file...use it.
			include($mod_build_file);

		} else {
			// Module doesn't have a build file so create minimal configuration
			$module_config_array['Module'.$cur_mod['svxlinkName']] = [
				'NAME' => $cur_mod['svxlinkName'],
				'ID' => $cur_mod['svxlinkID'],
				'TIMEOUT' => '60',				
			];
		}
		
		// Write out Module Config File for SVXLink
		file_put_contents('/etc/openrepeater/svxlink/svxlink.d/Module'.$cur_mod['svxlinkName'].'.conf', $orpFileHeader . build_ini($module_config_array) );

	} 
}

// Build Module List from Array
if(!empty($modulesArray)) {
	$modulesListKey = 'MODULES';
	$modulesListValue = implode(",", $modulesArray);
} else {
	$modulesListKey = '#MODULES';
	$modulesListValue = 'NONE';
}

?>