<?php
// Read all the settings from SQLite into a PHP array
include_once("/etc/openrepeater/database.php");

$results = $dbConnection->query('SELECT * from advanced');

while($row = $results->fetchArray(SQLITE3_ASSOC) ){
	$key = $row['keyID'];
	$advanced[$key] = trim(str_replace("\r", "", $row['value'])) . "\n"; // Remove Window Newline characters and trim off leading and trailing whitespace.
}

// Advanced Values are in Key/Value pairs and are called like this by the key name.
// $advanced['sqlite_key_name'];

?>
