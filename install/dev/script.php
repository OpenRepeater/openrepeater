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
	passthru("/var/www/openrepeater/dev/orp_helper.sh " . $option);
	$script_results = ob_get_clean();

		
//	$script_results = exec('/var/www/openrepeater/dev/orp_helper.sh ' . $option);
	
	echo $script_results;

}



?>

<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
	<select name="option" onchange="this.form.submit()">
		<option></option>
		<option value="--help">Help</option>
		<option value="--reboot">Reboot</option>
		<option value="--status">Status</option>
		<option value="--file">Audi</option>
	</select>
</form>
