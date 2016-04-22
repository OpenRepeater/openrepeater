<?php
if (isset($_POST['action'])){
	$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
	
	$moduleOptions = array(); //Final module options array that will be serialized and stored in database.
	
	// Process submitted post varialbes
	foreach($_POST as $key=>$value){  
		if ($key != "action") {
			if($key == "moduleKey"){
				$module_ID = $value;
			} else {
				// Process all other variables normally.
				$moduleOptions[$key]=$value;
			}
		}
	}

	// Serialize settings array for DB storage
	$settings = serialize($moduleOptions);

	// Update Database
	$sql = "UPDATE modules SET moduleOptions = '$settings' WHERE moduleKey = '$module_ID';";
	$db->exec($sql);
	$db->close();

	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
	$memcache_obj = new Memcache;
	$memcache_obj->connect('localhost', 11211);
	$memcache_obj->set('update_settings_flag', 1, false, 0);

	// Go Back to Module Settings Page
	header('location: ../../modules.php?settings='.$module_ID);		
}
?>