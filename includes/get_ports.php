<?php
// Read all the Ports from SQLite into a PHP array

include_once("/etc/openrepeater/database.php");

$results2 = $dbConnection->query('SELECT * FROM "ports" ORDER BY "portNum" ASC')or die();

$ports = array();

while ($row2 = $results2->fetchArray(SQLITE3_ASSOC) ) {
    $portNum = $row2['portNum'];
    foreach($row2 as $key => $value) {
		$ports[$portNum][$key] = $value;
        //print "$key = $value <br />";
     }
}

/*
Ports are read in from the database and stored in a multi-dimensional array. Once this file is included, port settings can be called
by using the following syntax: $ports[1]['portLabel'];
*/

?>
