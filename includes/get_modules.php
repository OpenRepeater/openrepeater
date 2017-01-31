<?php
// Read all the Ports from SQLite into a PHP array

include_once("/etc/openrepeater/database.php");

$module_results = $dbConnection->query('SELECT * FROM "modules" ORDER BY "svxlinkID" ASC')or die();

$module = array();

while ($module_row = $module_results->fetchArray(SQLITE3_ASSOC) ) {
    $module_key = $module_row['moduleKey'];
    foreach($module_row as $key => $value) {
		$module[$module_key][$key] = $value;
	}
}

/*
GPIOs are read in from the database and stored in a multi-dimensional array. Once this file is included, GPIO settings can be called
by using the following syntax: $module[22]['direction'];
*/
//echo $module[4]['moduleKey'];
?>
