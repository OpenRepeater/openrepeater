<?php
if (isset($_POST['action'])){

	$db = new SQLite3('/Volumes/Macintosh HD/Users/aaroncrawford/Downloads/OpenRepeater Dev/db/openrepeater.db');
	
	// Define processing arrays
	$relaysPostArray = array(); // Array of post arrays, stored to be combined in to nested array.
	$relaysNested = array(); // Array of nested relay settings.
	$moduleOptions = array(); //Final module options array that will be serialized and stored in database.
	
	// Process submitted post varialbes
	foreach($_POST as $key=>$value){  
		if ($key != "action") {
			// Process through submitted relay sub arrays and store for later nesting
			$arrayVars = array("relayNum", "relayLabel", "relayGPIO");
			if(in_array($key, $arrayVars)){
				$relaysPostArray[$key]=$value;
				
			} else {
				// Process non-array based variables normally and add to options array.
				$moduleOptions[$key]=$value;			
			}
		}
	}

	// Clear out all current Relay GPIO pins in gpio dababase table for rewrite of new pin data.
	$db->exec('DELETE FROM "gpio_pins" WHERE type = "RemoteRelay";');

	// Process saved post sub arrays into nested array and update gpio pins
	foreach($relaysPostArray['relayNum'] as $key=>$value){
		$relaysNested[$value] = array(
			'gpio' => $relaysPostArray['relayGPIO'][$key],
			'label' => $relaysPostArray['relayLabel'][$key]
			);
			
			// Update GPIO pins table with new pins.
			$sql = 'INSERT INTO "gpio_pins" ("gpio_num","direction","active","description","type") VALUES ("'.$relaysPostArray['relayGPIO'][$key].'","out","low","RELAY: '.$relaysPostArray['relayLabel'][$key].'","RemoteRelay");';
			$db->exec($sql);
	}
	$moduleOptions['relay'] = $relaysNested; // add nested relay array into options array.


	print_r($moduleOptions);

	echo "<hr>";
	
	$settings = serialize($moduleOptions);
	echo $settings;

	echo "<hr>";


   $db->close();
	

	$msgText = "The relay settings have been updated successfully!";
//	$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';

echo $msgText;

	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
//	$memcache_obj = new Memcache;
//	$memcache_obj->connect('localhost', 11211);
//	$memcache_obj->set('update_settings_flag', 1, false, 0);
}
?>