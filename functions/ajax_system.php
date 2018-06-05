<?php
// This is part of an AJAX operation to pass data back and forth between JavaScript, PHP, and BASH (linux system). It lets ORP do things the web server normally wouldn't have privilages to do.

// Check to make sure options are passed by JavaScript and if so, call the function and echo results
if (!empty($_POST)) {
	$service = $_POST['post_service'];
	$option = $_POST['post_option'];
	echo exec_orp_helper($service, $option);
}
	
// Function to pass and return commands from orp_helper script in BASH
function exec_orp_helper ($opt1, $opt2) {
	$command = 'sudo /usr/sbin/orp_helper ';
	$command .= ' ' . $opt1;
	$command .= ' ' . $opt2;

	ob_start(); 
	passthru($command);
	return trim(ob_get_clean());
}


// Pass System Info updates back to javascript
if( isset($_GET['update']) ) {
	if ($_GET['update']=='info') {
		require_once('../includes/classes/System.php');
		$classSystem = new System();
		$outputArray = array_merge($classSystem->system_info(),$classSystem->memory_usage());
		
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($outputArray);
	}
}

?>
