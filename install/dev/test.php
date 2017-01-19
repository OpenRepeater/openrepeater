<?php 

include_once("/etc/openrepeater/database.php");

$dbConnection->exec('DELETE FROM "gpio_pins" WHERE type = "Port";');


$sql = 'INSERT INTO "gpio_pins" ("gpio_num","direction","active","description","type") VALUES ("101","out","low","RX GPIO Pin","Port");';
$dbConnection->exec($sql);



$dbConnection->close();
echo "done";
?>