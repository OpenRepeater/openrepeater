<?php
if (!empty($_POST)) {
	$option = $_POST["option"];	

	ob_start();
	passthru('sudo orp_helper ' . $option);
	$script_results = ob_get_clean();
}
?>



<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
	<select name="option" onchange="this.form.submit()">
		<option>...</option>
		<optgroup label="General">		
			<option value="help">Help</option>
		</optgroup>
		<optgroup label="SVXLink">		
			<option value="svxlink start">Start</option> <!-- FIX -->
			<option value="svxlink stop">Stop</option> <!-- FIX -->
			<option value="svxlink restart">Restart</option> <!-- FIX -->
			<option value="svxlink status">Status</option>
			<option value="enable">Enable</option> <!-- FIX -->
			<option value="disable">Disable</option> <!-- FIX -->
		</optgroup>
		<optgroup label="System">		
			<option value="system stop">Shutdown</option> <!-- FIX -->
			<option value="system restart">Restart</option>
			<option value="system uptime">Uptime</option>
			<option value="system user">Current User</option>
		</optgroup>
		<optgroup label="Info">		
			<option value="info timezone">Timezone</option>
			<option value="info cpu_type">CPU Type</option>
			<option value="info cpu_speed">CPU Speed</option>
			<option value="info cpu_load">CPU Load</option>
			<option value="info cpu_temp">CPU Temperature</option>
			<option value="info uptime">Uptime</option>
			<option value="info memory_usage">Memory Usage</option>
			<option value="info disk_usage">Disk Usage</option>
			<option value="info os">Operating System</option>
		</optgroup>
		<optgroup label="Audio">		
			<option value="audio inputs">Inputs</option>
			<option value="audio outputs">Outputs</option>
			<option value="audio version">Get ALSA Version</option>
		</optgroup>
	</select>
</form>

<hr>



<?php
	echo '<h2>orp_helper ' . $option . '</h2>';
	echo "<pre>".$script_results."</pre>";
?>
