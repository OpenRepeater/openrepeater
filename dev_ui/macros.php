<?php
################################################################################
# EDIT THIS FILE FIRST, THEN RUN! This is for adding settings for the macros to
# the database.
#
# Note: This is an early development script for allowing updating of settings in
# the database for testing purposes. This feature is supported in the current
# backend code, but the UI will not be available until a future release. This is
# a work around in the time being for use and/or testing. This file must be
# edited first, then run from the browser. In so doing the settings in this file
# will be written to the database and will be available on the next rebuild of
# the configuration.
################################################################################

if (isset($_POST['action'])){
	# AUTOLOAD CLASSES
	require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
	
	$classDB = new Database();
	

	# Edit the values for the settings in this array as you wish them to be saved to DB
	$macroArray = [
		0 => [
			'macroEnabled' => '1', // 0 or 1
			'macroNum' => '1', // Desired DTMF Number
			'macroLabel' => 'Sample EchoLink Macro', // Helpful Description
			'macroModuleID' => '3', // "moduleKey" id number from modules table
			'macroString' => '9999#', // Marco String
			'macroPorts' => '2', // Use either a port number or 'ALL' to use for all ports.
		],
		1 => [
			'macroEnabled' => '1', // 0 or 1
			'macroNum' => '8', // Desired DTMF Number
			'macroLabel' => 'Sample Parrot Macro', // Helpful Description
			'macroModuleID' => '2', // "moduleKey" id number from modules table
			'macroString' => '0123456789##', // Marco String
			'macroPorts' => 'ALL', // Use either a port number or 'ALL' to use for all ports.
		]
	];


	# Clear existing macros
	$classDB->clear_macros_table();

	# Write macros array to database table
	$result = $classDB->update_macro_table($macroArray);
	
	include('header.php');
	echo '<h2>Macro Updater</h2>';
	if ($result) { echo 'Database Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; } else { echo "<h1>ERROR UPDATING DATABASE</h1>"; }
	include('footer.php');

} else {
	include('header.php');
	?>
	<h2>Macro Updater</h2>
	<h3>You MUST edit the array in this file to meet your needs before you continue.</h3>
	<p><strong>FILE LOCATION:</strong> <?php echo get_included_files()[0]; ?></p>

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<input type="hidden" name="action" value="update">		
		<button class="myButton">Add/Update Macros from PHP Array</button>
	</form>
	<?php
	include('footer.php');
}
?>