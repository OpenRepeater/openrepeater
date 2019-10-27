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
		if (isset($result)) { $nested_array = []; }
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

	// TABLE COLUMN EXISTS - Return True/False
	public function exists_column($table, $column_name) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');

		// Get column names for table. 	
		$column_results = $db->query('PRAGMA table_info("' . $table . '")');			
		while ($colArray = $column_results->fetchArray()) {
			// Set Primary Key for parent array
			if ($colArray['pk'] == 1) { $primary_key =  $colArray['name']; }
			$columns[] = $colArray['name'];
		}

		if (in_array($column_name, $columns)) { return true; } else { return false; }
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

	// COUNT TABLE COLUMNS - Return Number
	public function count_table_columns($table) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$column_results = $db->query('PRAGMA table_info("' . $table . '")');
		while ($colArray = $column_results->fetchArray()) {
			// Set Primary Key for parent array
			if ($colArray['pk'] == 1) { $primary_key =  $colArray['name']; }
			$columns[] = $colArray['name'];
		}
 		$numColumns = count($columns);
		return $numColumns;
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
	
	public function update_settings($settingsArray) {
		foreach($settingsArray as $key=>$value){  
			// SPECIAL FORMATING
			if ($key == "callSign") { $value = strtoupper($value); }
			$query = $this->update("UPDATE settings SET value='$value' WHERE keyID='$key'");	
		}
		
		if ($query) { return true; } else { return false; }
	}
	


	###############################################
	# Ports Table
	###############################################

	public function get_ports($portNum = 'ALL') {
		if ($portNum == 'ALL') {
			$sql = 'SELECT * FROM ports ORDER BY "portNum" ASC';
		} else {
			$sql = 'SELECT * FROM ports WHERE portNum = "' . $portNum . '";';
		}
		$ports = $this->select_all('ports', $sql);
		foreach($ports as $curPort) {
			$curPortNum = $curPort['portNum'];
			$curOptions = unserialize($curPort['portOptions']);
			foreach($curOptions as $key=>$value) {
				$ports[$curPortNum][$key] = $value;
			}
			unset($ports[$curPortNum]['portOptions']);
		}
		return $ports;	
	}


	public function clear_ports_table() {
		$sql = 'DELETE FROM ports;';
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}

	public function delete_ports($portNum = 'ALL') {
		if ($portNum == 'ALL') {
			$sql = 'DELETE FROM ports;';
		} else {
			$currPortArray = $this->get_ports($portNum);
			if ($currPortArray[$portNum]['portType'] == 'GPIO') { $this->delete_gpio_pins('Port ' . $portNum);}
			$sql = 'DELETE FROM ports WHERE portNum = "' . $portNum . '";';
		}
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}


	public function update_ports_table( $input_array = array() ) {
		$primaryColumns = ['portNum','portLabel','rxAudioDev','txAudioDev','portType','portEnabled'];
		foreach($input_array as $portArr){  
			$portNum = $portArr['portNum'];
			$gpioFlag = 0;
			$currColumns = [];
			$portOptions = [];
			foreach($portArr as $portColName => $portColValue){  
				if ( in_array($portColName, $primaryColumns) )
				    $currColumns[$portColName] = $portColValue;
				else
				    $portOptions[$portColName] = $portColValue;
				    if ($portColName == 'rxGPIO' || $portColName == 'txGPIO') { $gpioFlag++; }
			}
			$currColumns['portOptions'] = serialize($portOptions);

			if ( $this->exists('ports','portNum', $portNum) == true ) {
				// If port exists, update it
				foreach($currColumns as $col=>$value){  
					$results = $this->update("UPDATE ports SET $col='$value' WHERE portNum='$portNum'");	
				}
			} else {
				// If port doesn't exist, create it
				$columns = implode(",",array_keys($currColumns));
				$escaped_values = array_values($currColumns);
				$values  = "'" . implode("','", $escaped_values) . "'";
				$sql = "INSERT INTO ports(".$columns.") VALUES(".$values.");";
				$results = $this->insert($sql);
			}
			
			// Clear previous GPIOs for this port.
			$this->delete_gpio_pins( 'Port ' . $portArr['portNum'] );

			// Set new GPIOs if needed
			if ($gpioFlag > 0) {
				if ($portOptions['rxGPIO'] > 0) {
					$build_gpio_row = []; // reset array
					$build_gpio_row[] = ['gpio_num' => $portOptions['rxGPIO'],'direction' => 'in','active' => $portOptions['rxGPIO_active'],'description' => 'RX: ' . $portArr['portLabel'],'type' => 'Port ' . $portArr['portNum']];
					$this->update_gpio_table( $build_gpio_row );
				}

				if ($portOptions['txGPIO'] > 0) {
					$build_gpio_row = []; // reset array
					$build_gpio_row[] = ['gpio_num' => $portOptions['txGPIO'],'direction' => 'out','active' => $portOptions['txGPIO_active'],'description' => 'TX: ' . $portArr['portLabel'],'type' => 'Port ' . $portArr['portNum']];
					$this->update_gpio_table( $build_gpio_row );
				}

			}
		}
		return $results;
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


	public function delete_gpio_pins($type = 'ALL') {
		if ($type == 'ALL') {
			$sql = 'DELETE FROM gpio_pins;';
		} else {
			$sql = 'DELETE FROM gpio_pins WHERE type = "' . $type . '";';
		}
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}


	public function update_gpio_table( $input_array = array() ) {
		foreach($input_array as $gpioArr){  
			// Update Existing Pin
			if ( $this->exists('gpio_pins','gpio_num', $gpioArr['gpio_num']) == true ) {
				$sql = "UPDATE gpio_pins SET direction='".$gpioArr['direction']."', active='".$gpioArr['active']."', description='".$gpioArr['description']."', type='".$gpioArr['type']."' WHERE gpio_num='".$gpioArr['gpio_num']."';";
				$results = $this->update($sql);
				
			// Create Pin if it does not exist
			} else {
				$column_names = [];
				$column_values = [];
				$columns = implode(",",array_keys($gpioArr));
				$escaped_values = array_values($gpioArr);
				$values  = "'" . implode("','", $escaped_values) . "'";
				$sql = "INSERT INTO gpio_pins(".$columns.") VALUES(".$values.");";
				$results = $this->insert($sql);
			}
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
	# Macro Table
	###############################################

	public function get_macros() {
		$modulesArray = $this->get_modules();
		$sql = 'SELECT * FROM "macros" ORDER BY "svxlinkID" ASC';
		$macros = $this->select_all('macros', $sql);
		$macroOutput = [];
		foreach ($macros as $curMacroNum => $curMacroArray) {
			$macroOutput[$curMacroNum] = $curMacroArray;
			$moduleID = $curMacroArray['macroModuleID'];
			$macroOutput[$curMacroNum]['macroModuleName'] = $modulesArray[$moduleID]['svxlinkName'];
		}
		return $macroOutput;	
	}


	public function clear_macros_table() {
		$sql = 'DELETE FROM macros;';
		$delete_result = $this->delete_row($sql);
		return $delete_result;
	}
	

	public function update_macro_table( $input_array = array() ) {
		foreach($input_array as $macroArr){  
			if ( $this->exists('macros','macroKey', $macroArr['macroKey']) == true ) {
				$sql = "UPDATE macros SET macroEnabled='".$macroArr['macroEnabled']."', macroNum='".$macroArr['macroNum']."', macroLabel='".$macroArr['macroLabel']."', macroModuleID='".$macroArr['macroModuleID']."', macroString='".$macroArr['macroString']."', macroPorts='".$macroArr['macroPorts']."' WHERE macroKey='".$macroArr['macroKey']."';";
				$results = $this->update($sql);
				
			} else {
				$column_names = [];
				$column_values = [];
				$columns = implode(",",array_keys($macroArr));
				$escaped_values = array_values($macroArr);
				$values  = "'" . implode("','", $escaped_values) . "'";
				$sql = "INSERT INTO macros(".$columns.") VALUES(".$values.");";
				$results = $this->insert($sql);
			}
		}
		return $results;
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
	# Modify Database Structure
	###############################################

	// ADD RECORD
	// Currently only supports settings table structure. Future Add support for passing Associated Arrays parameters
	public function add_record($table, $key, $value) {
		if ($this->exists($table, 'keyID', $key) == false) {
			$insert_sql = "INSERT INTO $table ('keyID','value') VALUES ('$key','$value')";
			$results = $this->insert($insert_sql);
			if ($results == true) {
				// Record Added Successfully
				return true;
			} else {
				// Error Adding Record
				return false;
			}

		} else {
			// Record Already Exists
			return false;
		}
	}

	// REMOVE RECORD
	// Currently only supports settings table structure. Future Add support for passing Associated Arrays parameters
	public function remove_record($table, $key) {
		if ($this->exists($table, 'keyID', $key) == true) {
			$delete_sql = "DELETE FROM $table WHERE keyID = '$key'";			
			$results = $this->delete_row($delete_sql);
			if ($results == true) {
				// Record Deleted Successfully
				return true;
			} else {
				// Error Deleting Record
				return false;
			}
			
		} else {
			//Record doesn't exist to remove
			return false;
		}
	}


	// ADD NEW COLUMN TO TABLE
	public function add_table_column($table, $column_name) {
		if ($this->exists_column($table, $column_name) == false) {
			$old_num_columns = $this->count_table_columns($table);

			$update_sql = "ALTER TABLE $table ADD COLUMN $column_name";
			$update_sql .= " TEXT";
			$results = $this->update($update_sql);

			$new_num_columns = $this->count_table_columns($table);

			if ($new_num_columns > $old_num_columns) {
				// Column Added Successfully
				return true;
			} else {
				// Error Adding Column
				return false;
			}
		} else {
			// Column Already Exists
			return false;
		}
	}


	// REMOVE COLUMN FROM TABLE
	public function remove_table_column($table, $column_name) {
		// Get table structure, remove specified column, and build insert SQL
		$tableStructure = $this->get_table_structure($table);
		$tableStructure = array_diff_key($tableStructure, array_flip([$column_name]));
		foreach ($tableStructure as $columnName => $columnArray) {
			$columnList[] = $columnName;
		    $currentColDetails = "'" . $columnName . "'";
		    $currentColDetails .= " " . $columnArray['type'];
		    if ($columnArray['pk'] == 1) { $currentColDetails .= " PRIMARY KEY"; }
		    if ($columnArray['notnull'] == 1) { $currentColDetails .= " NOT NULL"; }
		    if ($columnArray['dflt_value'] != "") { $currentColDetails .= " DEFAULT " . $columnArray['dflt_value']; }
		
		    $columnDetails[] = $currentColDetails;
		}
		$columnsDetailsCSV = implode(', ', $columnDetails);
		$columnsCSV = implode(',', $columnList);

		// Build SQL Transcation to create new table
		$sql = "BEGIN TRANSACTION;";
		$sql .= "CREATE TEMPORARY TABLE '" . $table . "_backup' ($columnsDetailsCSV);";
		$sql .= "INSERT INTO " . $table . "_backup SELECT $columnsCSV FROM " . $table . ";";
		$sql .= "DROP TABLE " . $table . ";";
		$sql .= "CREATE TABLE '" . $table . "' ($columnsDetailsCSV);";
		$sql .= "INSERT INTO " . $table . " SELECT $columnsCSV FROM " . $table . "_backup;";
		$sql .= "DROP TABLE " . $table . "_backup;";
		$sql .= "COMMIT;";
		
		// Execute SQL
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$results = $db->exec($sql) or die('Unable to modify table structure.');
	}


	// GET TABLE STRUCTURE - Return Array
	public function get_table_structure($table) {
		$db = new SQLite3($this->db_loc) or die('Unable to open database');
		$table_results = $db->query('PRAGMA table_info("' . $table . '")');
		$tableColumns = [];
 		while ($colArray = $table_results->fetchArray()) {
			$name = $colArray['name'];
			$tableColumns[$name]['type'] = $colArray['type'];
			$tableColumns[$name]['notnull'] = $colArray['notnull'];
			$tableColumns[$name]['dflt_value'] = $colArray['dflt_value'];
			$tableColumns[$name]['pk'] = $colArray['pk'];
			$tableColumns[$name]['cid'] = $colArray['cid'];
		}
		return $tableColumns;
	}


	// UPGRADE PORTS TABLE - Data structure change from ver 2.1.2 to 3.0.0+
	public function upgrade_ports_table_structure() {
		// Check if ports table need updatings, if so proceed.
		if ( $this->exists_column('ports', 'portOptions') == false ) {
			// Add new columns
			$this->add_table_column('ports', 'portType');
			$this->add_table_column('ports', 'portEnabled');
			$this->add_table_column('ports', 'portOptions');
		
			// Migrate old columns into options field as serialized array
			$sql = 'SELECT * FROM "ports" ORDER BY "portNum" ASC';
			$ports = $this->select_all('ports', $sql);
			foreach($ports as $curPort){  
				$curPortID = $curPort['portNum'];
				if ($curPort['rxGPIO'] > 0) { $portType = 'GPIO'; } else { $portType = ''; }
				$options_array = array_diff_key( $curPort, array_flip( ['portNum', 'portLabel', 'rxAudioDev', 'txAudioDev', 'portType', 'portEnabled',  'portOptions'] ) );
				$newOptions = serialize($options_array);
				$sql = "UPDATE ports SET portType='$portType', portOptions='$newOptions', portEnabled='1' WHERE portNum='$curPortID'";
				$this->insert($sql);
			}

			// Remove Old Columns...moved into portOptions
			$this->remove_table_column('ports', 'rxMode');
			$this->remove_table_column('ports', 'rxGPIO');
			$this->remove_table_column('ports', 'txGPIO');
			$this->remove_table_column('ports', 'rxGPIO_active');
			$this->remove_table_column('ports', 'txGPIO_active');

		}
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