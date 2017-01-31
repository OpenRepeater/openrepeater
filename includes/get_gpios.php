<?php
// Read all the Ports from SQLite into a PHP array

include_once("/etc/openrepeater/database.php");

$gpio_results = $dbConnection->query('SELECT * FROM "gpio_pins" ORDER BY "gpio_num" ASC')or die();

$gpio = array();

while ($gpio_row = $gpio_results->fetchArray(SQLITE3_ASSOC) ) {
    $pin_number = $gpio_row['gpio_num'];
    foreach($gpio_row as $key => $value) {
		$gpio[$pin_number][$key] = $value;
     }
}

/*
GPIOs are read in from the database and stored in a multi-dimensional array. Once this file is included, GPIO settings can be called
by using the following syntax: $gpio[22]['direction'];
*/

?>
