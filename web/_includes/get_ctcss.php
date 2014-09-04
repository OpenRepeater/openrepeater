<?php
// Read all the CTCSS Tones from MySQL into a PHP array

include_once("database.php");
$dbConnection = mysql_connect($MySQLHost, $MySQLUsername, $MySQLPassword);
mysql_select_db($MySQLDB, $dbConnection);

$results = mysql_query("SELECT * FROM  ctcss")or die();

$ctcss = array();
while($row = mysql_fetch_assoc($results)) {
	$ctcss[$row['toneFreqHz']] = $row['code'];
}
mysql_close($dbConnection);

?>
