<?php
//ob_start();
//passthru("/var/www/openrepeater/dev/orp_helper.sh --help");
//$arecord_results = ob_get_clean();
?>

<?php
if (!empty($_POST)) {
    //echo htmlspecialchars($_POST["option"]);	

	$option = $_POST["option"];	

	ob_start();
	passthru('sudo /usr/bin/orp_helper ' . $option);
	$script_results = ob_get_clean();

		
//	$script_results = exec('/var/www/openrepeater/dev/orp_helper.sh ' . $option);
	

}



?>

<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
	<select name="option" onchange="this.form.submit()">
		<option>...</option>
		<optgroup label="General">		
			<option value="/usr/bin/orp_helper help">Help</option>
		</optgroup>
		<optgroup label="SVXLink">		
			<option value="start">Start</option> <!-- FIX -->
			<option value="stop">Stop</option> <!-- FIX -->
			<option value="restart">Restart</option> <!-- FIX -->
			<option value="svxlink status">Status</option>
			<option value="enable">Enable</option> <!-- FIX -->
			<option value="disable">Disable</option> <!-- FIX -->
		</optgroup>
		<optgroup label="System">		
			<option value="system stop">Stop</option> <!-- FIX -->
			<option value="system restart">Restart</option>
			<option value="system uptime">Uptime</option>
			<option value="system user">Current User</option>
		</optgroup>
		<optgroup label="Audio">		
			<option value="audio inputs">Inputs</option>
			<option value="audio outputs">Outputs</option>
			<option value="audio version">Get ALSA Version</option>
		</optgroup>

		<option value="sudo /usr/bin/orp_helper svxlink status">SVXLink Status</option>
	</select>
</form>

<hr>
<?php echo "<pre>".$script_results."</pre>"; ?>
