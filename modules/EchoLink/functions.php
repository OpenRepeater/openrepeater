<?php
//if (isset($_POST['action'])){

	$db = new SQLite3('/Volumes/Macintosh HD/Users/aaroncrawford/Downloads/OpenRepeater Dev/db/openrepeater.db');
	
//	$moduleOptions = array(); //Final module options array that will be serialized and stored in database.
	
/*
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
*/

	$moduleOptions = array(
		'timeout' => '60',
		'server' => 'servers.echolink.org',
		'callSign' => 'N3MBH-R',
		'password' => 'PASSWORD',
		'sysop' => 'OpenRepeater',
		'location' => 'WV',
		'description' => 'Welcome to an Open Repeater Server',
		'max_qsos' => '4',
		'connections' => '4',
		'idle_timeout' => '300'
		);
	
	
	$moduleEchoLink = '[ModuleEchoLink]
	NAME=EchoLink
	ID=2
	TIMEOUT=60
	SERVERS=servers.echolink.org
	CALLSIGN='.$settings['echolink_callSign'].'
	PASSWORD='.$settings['echolink_password'].'
	SYSOPNAME='.$settings['echolink_sysop'].'
	LOCATION='.$settings['echolink_location'].'
	MAX_QSOS=4
	MAX_CONNECTIONS=4
	LINK_IDLE_TIMEOUT=300
	DESCRIPTION="'.$echolink_clean_desc.'"
	USE_GSM_ONLY=1

	';


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


//}
?>