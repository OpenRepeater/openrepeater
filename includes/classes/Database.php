<?php
#####################################################################################################
# Database Class
#####################################################################################################

class Database {

    private $db_loc = '/var/lib/openrepeater/db/openrepeater.db';


	###############################################
	# Settings Table
	###############################################

	public function get_settings($returnSetting = 'all') {
		if ($returnSetting == 'all') {
			$sql_query = 'SELECT * FROM settings';
		} else {
			$sql_query = "SELECT * FROM settings WHERE keyID = '".$returnSetting."'";
		}

		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query($sql_query) or die('Query failed');
		
		if ($returnSetting == 'all') {
			// Return ALL settings as associative array
			while ($row = $result->fetchArray()) {
				$key = $row['keyID'];
				$settings[$key] = $row['value'];
			}
			$db->close();
			return $settings;

		} else {
			// Return requested setting value as string
			$row = $result->fetchArray();
			$db->close();
			return $row['value'];		
		}
	}
	
	

	###############################################
	# Ports Table
	###############################################

	public function get_ports() {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query('SELECT * FROM "ports" ORDER BY "portNum" ASC') or die('Query failed');
		
		$ports = [];
		while ($row = $result->fetchArray()) {			
			$portNum = $row['portNum'];
			foreach($row as $key => $value) {
				$ports[$portNum][$key] = $value;
			}
		}
		$db->close();
		return $ports;	
	}


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

	public function get_gpios() {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query('SELECT * FROM "gpio_pins" ORDER BY "gpio_num" ASC') or die('Query failed');
		
		$gpio = [];
		while ($row = $result->fetchArray()) {			
		    $pin_number = $row['gpio_num'];
		    foreach($row as $key => $value) {
				$gpio[$pin_number][$key] = $value;
		     }
		}

		$db->close();
		return $gpio;	
	}


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

	public function get_modules() {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query('SELECT * FROM "modules" ORDER BY "svxlinkID" ASC') or die('Query failed');
		
		$module = [];
		while ($row = $result->fetchArray()) {			
		    $module_key = $row['moduleKey'];
		    foreach($row as $key => $value) {
				$module[$module_key][$key] = $value;
			}
		}

		$db->close();
		return $module;	
	}


	public function active_module($id) {
		$db = new SQLite3($this->db_loc);
		if(isset($id)) {
			// Target Module
			$sql = "UPDATE modules SET moduleEnabled='1' WHERE moduleKey='$id'";
		} else {
			// Deactivate ALL Modules
			$sql = "UPDATE modules SET moduleEnabled='1'";
		}
		$db->exec($sql);
		$db->close();
	}


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
	# CTCSS Table
	###############################################

	// Read all the CTCSS Tones from SQLite into a PHP array
	public function get_ctcss() {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query('SELECT * FROM "ctcss" ORDER BY "toneFreqHz" ASC') or die('Query failed');
		
		$ctcss = [];
		while ($row = $result->fetchArray()) {			
			$ctcss[$row['toneFreqHz']] = $row['toneFreqHz'];
		}

		$db->close();
		return $ctcss;	
	}



	###############################################
	# Version Table
	###############################################

	public function get_version() {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query('SELECT * FROM version_info') or die('Query failed');
		$db->close();
		
		// Return requested setting value as string
		$row = $result->fetchArray();
		return $row['version_num'];		
	}



	###############################################
	# Export
	###############################################

	public function db_dump() {
		exec('sqlite3 '.$this->db_loc.' .dump > /var/www/openrepeater/backup/orp_test.sql', $output);
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