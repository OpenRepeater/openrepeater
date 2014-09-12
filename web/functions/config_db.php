<?php
// WRITE NEW TABLES TO DATABASE
include_once("/var/www/admin/_includes/database.php");

$mysqlImportFilename ='/var/www/admin/functions/default.sql';

//Export the database and output the status
$command='mysql -h' .$MySQLHost .' -u' .$MySQLUsername .' -p' .$MySQLPassword .' ' .$MySQLDB .' < ' .$mysqlImportFilename;
exec($command,$output=array(),$worked);
switch($worked){
	case 0:
	echo 'Import file ' .$mysqlImportFilename .' successfully imported to database ' .$MySQLDB .'.';
	break;
	case 1:
	echo 'There was an error during import. Please make sure that your settings are correct.';
	break;
}
?>