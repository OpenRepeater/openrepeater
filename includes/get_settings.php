<?php
// Read all the settings from SQLite into a PHP array
include_once("/etc/openrepeater/database.php");

$results = $dbConnection->query('SELECT * from settings');

while($row = $results->fetchArray(SQLITE3_ASSOC) ){
	$key = $row['keyID'];
	$settings[$key] = $row['value'];
}

// Settings are in Key/Value pairs and are called like this by the key name.
// $settings['sqlite_key_name'];

?>
