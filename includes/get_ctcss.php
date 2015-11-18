<?php
// Read all the CTCSS Tones from SQLite into a PHP array

include_once("/etc/openrepeater/database.php");

$results = $dbConnection->query('SELECT * FROM "ctcss" ORDER BY "toneFreqHz" ASC')or die();

$ctcss = array();
while($row = $results->fetchArray(SQLITE3_ASSOC) ){
	$ctcss[$row['toneFreqHz']] = $row['toneFreqHz'];
}

?>
