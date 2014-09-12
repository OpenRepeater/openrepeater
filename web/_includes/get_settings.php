<?php
// Read all the settings from MySQL into a PHP array
$results = $GLOBALS['app']['db']->executeQuery("SELECT * FROM  settings", [])->fetchAll();

$settings = array();
foreach ($results as $row) {
	$key = $row['keyID'];
	$settings[$key] = $row['value'];
}