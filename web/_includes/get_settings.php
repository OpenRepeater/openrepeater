<?php
// Read all the settings from MySQL into a PHP array

include_once("database.php");
$dbConnection = mysql_connect($MySQLHost, $MySQLUsername, $MySQLPassword);
mysql_select_db($MySQLDB, $dbConnection);

$results = mysql_query("SELECT * FROM  settings")or die();

$settings = array();
while($row = mysql_fetch_assoc($results)) {
	$key = $row['keyID'];
	$settings[$key] = $row['value'];
}
mysql_close($dbConnection);

// Settings are in Key/Value pairs and are called like this by the key name.
// $settings['mysql_key_name'];

?>
