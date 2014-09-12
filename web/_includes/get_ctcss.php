<?php
// Read all the CTCSS Tones from MySQL into a PHP array
$results = $GLOBALS['app']['db']->executeQuery("SELECT * FROM ctcss", [])->fetchAll();

$ctcss = array();

foreach ($results as $row) {
	$ctcss[$row['toneFreqHz']] = $row['code'];
}
