<?php
/*
*  This is a custom form processor, it takes an input array submitted by this module's settings form and does some extra
*  preprocessing before the array gets passed back to the Modules Class to be serialized and saved into the database.
*  data comes into this file as '$inputArray' and MUST be passed back out as '$outputArray' in order for the data to be saved.
*/

### Data comes in as '$inputArray' ###	

// Process submitted post varialbes
foreach($inputArray as $key=>$value){  
	if(in_array($key, array("relayNum", "relayLabel", "relayGPIO"))){
		// Process through submitted relay sub arrays and store for later nesting
		$relaysPostArray[$key]=$value;
		
	} else {
		// Process non-array based variables normally and add to options array.
		$moduleOptions[$key]=$value;			
	}
}

// LOOP: Process saved post sub arrays into nested array and update gpio pins
foreach($relaysPostArray['relayNum'] as $key=>$value){
	$relaysNested[$value] = [
		'gpio' => $relaysPostArray['relayGPIO'][$key],
		'label' => $relaysPostArray['relayLabel'][$key]
	];

	// Also build array to update GPIO Pins DB table for pin registration with OS
	$gpio_array[$value] = [
		'gpio_num' => $relaysPostArray['relayGPIO'][$key],
		'direction' => 'out',
		'active' => $moduleOptions['relays_gpio_active_state'],
		'description' => 'RELAY ' . $value . ': ' . $relaysPostArray['relayLabel'][$key]
	];
}

// Update GPIO pins table with new pins.
$this->update_gpios('RemoteRelay',$gpio_array);		

// add nested relay array into options array.
$moduleOptions['relay'] = $relaysNested; 

// Pass NEW array back out as $outputArray
$outputArray = $moduleOptions;

### Data MUST leave as '$outputArray' ###
?>