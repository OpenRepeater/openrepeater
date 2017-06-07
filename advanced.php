<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------



if (isset($_POST['action'])){

	if ($_POST['action'] == "changMode") {
		if ($_POST['orp_Mode'] == "advanced") {
			// Setup Advanced Mode, create DB table and update setting to switch to Advanced mode.
			$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
			
			$new_table_sql = "CREATE TABLE IF NOT EXISTS advanced (
			    keyID	TEXT	NOT NULL	PRIMARY KEY,
			    value	TEXT	NOT NULL
			);";
			$new_query = $db->exec($new_table_sql);

			$sql = "UPDATE settings SET value='advanced' WHERE keyID='orp_Mode'";
			$query = $db->exec($sql);

			$db->close();

			$msgText = "Open Repeater has been set to Advanced mode.";
			$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';


		} else if ($_POST['orp_Mode'] == "repeater") {
			// Change mode back to standard ORP mode
			$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
			
			$sql = "UPDATE settings SET value='repeater' WHERE keyID='orp_Mode'";
			$query = $db->exec($sql);

			$db->close();

			$msgText = "Open Repeater has been reverted back to standard repeater mode. Please rebuild your configuration files.";
			$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';

			/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
			$memcache_obj = new Memcache;
			$memcache_obj->connect('localhost', 11211);
			$memcache_obj->set('update_settings_flag', 1, false, 0);

		}


	} else if ($_POST['action'] == "update_advanced") {
		echo "UPDATE";
		$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
		
		foreach($_POST as $key=>$value){  
			if ($key != "action") {
				$sql = "INSERT OR REPLACE INTO advanced (keyID, value) VALUES ('$key', '$value');";
				$query = $db->exec($sql);
			}
		}
	   $db->close();
		
		$msgText = "The settings have been updated successfully!";
		$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';
	
	
		/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
		$memcache_obj = new Memcache;
		$memcache_obj->connect('localhost', 11211);
		$memcache_obj->set('update_settings_flag', 1, false, 0);


		/*
		
		*/

	}
}

?>


<?php
$pageTitle = "Advanced Setup";

//$customCSS = ""; // "file1.css, file2.css, ... "
//$customJS = ""; // "file1.js, file2.js, ... "
 
include_once("includes/get_settings.php");

include('includes/header.php');


if (isset($alert)) { echo $alert; }


?>
<div class="alert alert-danger">
<strong>NOTE: </strong> Advanced mode is not intended for novice users. It is still under development and some functions may not work as expected.
</div>
<?php

switch ($settings['orp_Mode']) {
    // Advance mode set, so display advanced page
    case "advanced":
		include_once("includes/get_advanced.php");
		
		if ($advanced['svxlink_config']) {
			$svxlink_config_value = $advanced['svxlink_config'];
		} else {
			$svxlink_config_value = file_get_contents( "/etc/openrepeater/svxlink/svxlink.conf" );			
		}

		if ($advanced['gpio_config']) {
			$gpio_config_value = $advanced['gpio_config'];
		} else {
			$gpio_config_value = file_get_contents( "/etc/openrepeater/svxlink/svxlink_gpio.conf" );			
		}

    	?>


			<form role="form" action="advanced.php" method="post" id="advancedUpdate" name="advancedUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> Setup Advanced Mode</h2>
					</div>
					<div class="box-content">

						  <fieldset>
							<legend>Custom SVXLink Config</legend>
							  <div class="control-group">
								  <div style="width: auto; margin-right: 10px;">
								  	<textarea name="svxlink_config" class="autogrow" style="width:100%;"><?php echo $svxlink_config_value; ?></textarea>
								  	<p>Help for configuring the "svxlink.conf" file can be found <a href="http://svxlink.sourceforge.net/man/man5/svxlink.conf.5.html" target="_blank">here</a>.</p>
								  </div>
							  </div>

							<legend>Custom GPIO Config</legend>
							  <div class="control-group">
								  <div style="width: auto; margin-right: 10px;">
								  	<textarea name="gpio_config" class="autogrow" style="width:100%;"><?php echo $gpio_config_value; ?></textarea>
								  </div>
							  </div>

						  </fieldset>

						<div class="form-actions">
						  <input type="hidden" name="action" value="update_advanced">		
						  <button type="submit" class="btn btn-primary">Update Advanced Settings</button>
						</div>

					</div>
				</div><!--/span-->			
			</div><!--/row-->
			</form>   


			<form role="form" action="advanced.php" method="post" id="modeUpdate" name="modeUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> Revert to Standard Mode</h2>
					</div>

					<div class="box-content">

					<p>By clicking the button below you can go back to standard mode. After doing so, you may need to reset some of your settings.</p>

					<div class="form-actions">
					  <input type="hidden" name="action" value="changMode">		
					  <input type="hidden" name="orp_Mode" value="repeater">		
					  <button type="submit" class="btn btn-primary">Revert to Standard Mode</button>
					</div>

					</div>


				</div><!--/span-->
			</div><!--/row-->
			</form>


    	<?php
        break;



	// Page invoked but orp mode not set to advance
	default:
    	?>
			<form role="form" action="advanced.php" method="post" id="modeUpdate" name="modeUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-wrench"></i> Setup Advanced Mode</h2>
					</div>

					<div class="box-content">

					<p>Advanced mode isn't setup yet...would you like to do so now?</p>

					<div class="form-actions">
					  <input type="hidden" name="action" value="changMode">		
					  <input type="hidden" name="orp_Mode" value="advanced">		
					  <button type="submit" class="btn btn-primary">YES, I want to enable Advanced Mode</button>
					</div>

					</div>


				</div><!--/span-->
			</div><!--/row-->
			</form>

    	<?php	
}
?>


<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
