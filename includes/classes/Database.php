<?php
#####################################################################################################
# Database Class
#####################################################################################################

class Database {

    private $db_loc = '/var/lib/openrepeater/db/openrepeater.db';


	###############################################
	# Ports Table
	###############################################

	public function clear_ports_table() {
		$db = new SQLite3($this->db_loc);
		$db->exec("DELETE FROM ports;");
		$db->close();
	}


	public function update_ports_table( $input_array = array() ) {

		$db = new SQLite3($this->db_loc);

		foreach($input_array as $portArr){  
			$column_names = [];
			$column_values = [];

			$columns = implode(",",array_keys($portArr));
			$escaped_values = array_values($portArr);
			$values  = "'" . implode("','", $escaped_values) . "'";
			$sql = "INSERT INTO ports(".$columns.") VALUES(".$values.");";

			$query = $db->exec($sql);		
		}

		$db->close();
	}


	###############################################
	# GPIO Table
	###############################################

	public function clear_gpio_table() {
		$db = new SQLite3($this->db_loc);
		$db->exec("DELETE FROM gpio_pins;");
		$db->close();
	}


	public function update_gpio_table( $input_array = array() ) {

		$db = new SQLite3($this->db_loc);

		foreach($input_array as $gpioArr){  
			$column_names = [];
			$column_values = [];

			$columns = implode(",",array_keys($gpioArr));
			$escaped_values = array_values($gpioArr);
			$values  = "'" . implode("','", $escaped_values) . "'";
			$sql = "INSERT INTO gpio_pins(".$columns.") VALUES(".$values.");";

			$query = $db->exec($sql);		
		}
		$db->close();
	}


	###############################################
	# Module Table
	###############################################

	public function deactive_module($id) {
		$db = new SQLite3($this->db_loc);
		if(isset($id)) {
			// Target Module
			$sql = "UPDATE modules SET moduleEnabled='0' WHERE moduleKey='$id'";
		} else {
			// Deactivate ALL Modules
			$sql = "UPDATE modules SET moduleEnabled='0'";
		}
		$db->exec($sql);
		$db->close();
	}


	public function update_preset_modules( $input_array = array() ) {

		$db = new SQLite3($this->db_loc);

		foreach($input_array as $moduleArray){  
			$sql = "UPDATE modules SET moduleEnabled='1', moduleOptions='".$moduleArray['moduleOptions']."' WHERE moduleName='".$moduleArray['moduleName']."';";
			$query = $db->exec($sql);
		}
		$db->close();
	}


	###############################################
	# Memcache Flag
	###############################################

	public function set_update_flag($flag) {
		/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
		$memcache_obj = new Memcache;
		$memcache_obj->connect('localhost', 11211);
		if($flag == true) {
			$memcache_obj->set('update_settings_flag', 1, false, 0); // Set Flag
		} else {
			$memcache_obj->set('update_settings_flag', 0, false, 0); // Clear Flag			
		}
	}


}
?>