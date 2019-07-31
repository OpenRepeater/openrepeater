<?php
#####################################################################################################
# Database Class
#####################################################################################################

class Database {

    private $db_loc = '/var/lib/openrepeater/db/openrepeater.db';


	###############################################
	# Run SQL Statements
	###############################################

	// SELECT ALL - Return all values of table as nested array. Optional custom SQL.
	public function select_all($table_name, $custom_sql) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');

		if (isset($custom_sql)) {
			$sql = $custom_sql;			
		} else {
			$sql = 'SELECT * FROM "' . $table_name . '";';
		}

		// Get column names for table. 	
		$column_results = $db->query('PRAGMA table_info("' . $table_name . '")');			
		while ($colArray = $column_results->fetchArray()) {
			// Set Primary Key for parent array
			if ($colArray['pk'] == 1) { $primary_key =  $colArray['name']; }
			$columns[] = $colArray['name'];
		}

		$result = $db->query($sql) or die('Query failed');
	
		// Return all data as nested associative array
		while ($rowArray = $result->fetchArray()) {
			$row_pk = $rowArray[$primary_key];
			foreach($columns as $key => $value) {
				$nested_array[$row_pk][$value] = $rowArray[$value];
			}
		}
		return $nested_array;	
	}

	// SELECT KEY/VALUE PAIR - Return table as key/value associative array
	public function select_key_value($sql, $keyCol = NULL, $valueCol = NULL) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$result = $db->query($sql) or die('Unable to select key/value pair.');
		
		// Return key/value pairs as associative array
		while ($rowArray = $result->fetchArray()) {
			$key = $rowArray[$keyCol];
			$select_array[$key] = $rowArray[$valueCol];
		}
		return $select_array;
	}

	// SELECT SINGLE - Return single 
	public function select_single($sql) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$results = $db->querySingle($sql, true) or die('Unable to select single record from database');
		return $results;
	}

	// VALUE EXISTS - Return True/False
	public function exists($table, $column, $value) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$sql = 'SELECT COUNT(*) FROM "'.$table.'" WHERE "'.$column.'" = "'.$value.'";';
		$result = $db->querySingle($sql, true) or die('Unable to locate value in database');
		if ( $result['COUNT(*)'] > 0 ) { return true; } else { return false; }
	}

	// INSERT ROW - Return True/False
	public function insert($sql) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$results = $db->query($sql) or die('Unable to insert record into database.');
		if ( $db->changes() > 0 ) { 
			$this->set_update_flag(true);
			return true;
		} else {
			return false;
		}
	}

	// UPDATE - Return True/False
	public function update($sql) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$results = $db->query($sql) or die('Unable to update database.');
		if ( $db->changes() > 0 ) { 
			$this->set_update_flag(true);
			return true;
		} else {
			return false;
		}
	}

	// DELETE ROW - Return True/False
	public function delete_row($sql) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$results = $db->query($sql) or die('Unable to delete from database.');
		if ( $db->changes() > 0 ) { return true; } else { return false; }
	}



	###############################################
	# Settings Table
	###############################################

	public function get_settings($returnSetting = 'all') {
		if ($returnSetting == 'all') {
			// Return ALL settings as associative array
			$sql = "SELECT * FROM settings";
			$results = $this->select_key_value($sql, 'keyID', 'value');
			return $results;
		} else {
			// Return requested setting value as string
			$sql = "SELECT * FROM settings WHERE keyID = '".$returnSetting."'";
			$results = $this->select_single($sql, 'keyID', 'value');
			return $results['value'];
		}
	}
	
	

	###############################################
	# Ports Table
	###############################################

	public function get_ports() {
		$sql = 'SELECT * FROM "ports" ORDER BY "portNum" ASC';
		$ports = $this->select_all('ports', $sql);
		return $ports;	
	}


	public function clear_ports_table() {
		$sql = 'DELETE FROM ports;';
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}


	public function update_ports_table( $input_array = array() ) {
		foreach($input_array as $portArr){  
			$column_names = [];
			$column_values = [];
			$columns = implode(",",array_keys($portArr));
			$escaped_values = array_values($portArr);
			$values  = "'" . implode("','", $escaped_values) . "'";
			$sql = "INSERT INTO ports(".$columns.") VALUES(".$values.");";
			$results = $this->insert($sql);
		}
	}



	###############################################
	# GPIO Table
	###############################################

	public function get_gpios() {
		$sql = 'SELECT * FROM "gpio_pins" ORDER BY "gpio_num" ASC';
		$gpios = $this->select_all('gpio_pins', $sql);
		return $gpios;	
	}


	public function clear_gpio_table() {
		$sql = 'DELETE FROM gpio_pins;';
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}


	public function update_gpio_table( $input_array = array() ) {
		foreach($input_array as $gpioArr){  
			$column_names = [];
			$column_values = [];
			$columns = implode(",",array_keys($gpioArr));
			$escaped_values = array_values($gpioArr);
			$values  = "'" . implode("','", $escaped_values) . "'";
			$sql = "INSERT INTO gpio_pins(".$columns.") VALUES(".$values.");";
			$results = $this->insert($sql);
		}
	}



	###############################################
	# Module Table
	###############################################

	public function get_modules() {
		$sql = 'SELECT * FROM "modules" ORDER BY "svxlinkID" ASC';
		$modules = $this->select_all('modules', $sql);
		return $modules;	
	}


	public function active_module($id = NULL) {
		if(isset($id)) {
			// Target Module
			$sql = "UPDATE modules SET moduleEnabled='1' WHERE moduleKey='$id'";
		} else {
			// Deactivate ALL Modules
			$sql = "UPDATE modules SET moduleEnabled='1'";
		}
		$results = $this->insert($sql);
		return $results;
	}


	public function deactive_module($id = NULL) {
		if(isset($id)) {
			// Target Module
			$sql = "UPDATE modules SET moduleEnabled='0' WHERE moduleKey='$id'";
		} else {
			// Deactivate ALL Modules
			$sql = "UPDATE modules SET moduleEnabled='0'";
		}
		$results = $this->insert($sql);
		return $results;
	}


	public function update_preset_modules( $input_array = array() ) {
		foreach($input_array as $moduleArray){  
			$sql = "UPDATE modules SET moduleEnabled='1', moduleOptions='".$moduleArray['moduleOptions']."' WHERE moduleKey='".$moduleArray['moduleKey']."';";
			$results = $this->insert($sql);
		}
	}



	###############################################
	# CTCSS Table
	###############################################

	// Read all the CTCSS Tones from SQLite into a PHP array
	public function get_ctcss() {
		$sql = 'SELECT * FROM ctcss';
		$ctcss = $this->select_key_value($sql, 'toneFreqHz', 'toneFreqHz');
		return $ctcss;	
	}



	###############################################
	# Version Table
	###############################################

	public function get_version() {
		$sql = 'SELECT * FROM version_info';
		$result = $this->select_single($sql);
		return $result['version_num'];		
	}



	###############################################
	# Export
	###############################################

	public function db_export($db_tables, $sql_file) {
		$orp_version = $this->get_version();

		// SQL File Header, start file
		$sql_file_header = str_repeat('-',80) . "\n-- OpenRepeater Backup (ver $orp_version)\n" . str_repeat('-',80);
		exec('echo "' . $sql_file_header . '"  > ' . $sql_file);

		// Loop through each table
		foreach ($db_tables as $cur_table) {
			// Section Header
			$sql_section_header = "\n\n" . str_repeat('-',80) . "\n-- Table: $cur_table\n" . str_repeat('-',80);
			exec('echo "' . $sql_section_header . '"  >> ' . $sql_file);

			// Dump current table
			exec('sqlite3 ' . $this->db_loc . ' ".dump ' . $cur_table . '" >> ' . $sql_file);
		}

	}



	###############################################
	# Import
	###############################################

	public function db_import($db_tables, $sql_file) {
		// Empty Afected Tabled
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		// Loop through each table
		foreach ($db_tables as $cur_table) {
			$db->query("DELETE FROM $cur_table;") or die('Unable to delete current record.');
		}
		$db->close();

		// Import SQL file
		exec('cat ' . $sql_file . ' | sqlite3 ' . $this->db_loc);
	}



	###############################################
	# Memcache Flag
	###############################################

	public function set_update_flag($flag) {
		/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
		$memcached_obj = new Memcached;
		$memcached_obj->addServer('localhost', 11211);
		if($flag == true) {
			$memcached_obj->set('update_settings_flag', 1); // Set Flag
		} else {
			$memcached_obj->set('update_settings_flag', 0); // Clear Flag			
		}
	}

	public function get_update_flag() {
		$memcached_obj = new Memcached;
		$memcached_obj->addServer('localhost', 11211);
		$state = $memcached_obj->get('update_settings_flag');
		if ($state == 1) {
			return true;
		} else {
			return false;
		}
	}

}
?>