<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php'); // If they aren't logged in, send them to login page.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------


################################################################################
# This is for adding settings for the location information to share with
# Echolink for proper reporting
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
	$loc_array = [
		'Echolink_Status_Servers' => $_POST['Echolink_Status_Servers'],
	// 	'APRS_SERVER_LIST' => 'noam.aprs2.net:14580',
		'APRS_ServerList' => $_POST['APRS_ServerList'],
		'Latitude' => $_POST['Latitude'], // In decimal format
		'Longitude' => $_POST['Longitude'], // In decimal format
		'APRS_Station_Type' => $_POST['APRS_Station_Type'], // repeater OR link, NOTE: Callsign is pulled from settings and prefixed accordingly. 
		'Frequency' => $_POST['Frequency'],
		'Tone' => $_POST['Tone'],
		'TX_Power' => $_POST['TX_Power'],
		'Antenna_Gain' => $_POST['Antenna_Gain'],
		'Antenna_Height' => $_POST['Antenna_Height'],
		'Antenna_Height_Units' => $_POST['Antenna_Height_Units'],
		'Antenna_Dir' => $_POST['Antenna_Dir'],
		'APRS_Path' => $_POST['APRS_Path'],
		'Beacon_Interval' => $_POST['Beacon_Interval'],
		'Statistics_Interval' => $_POST['Statistics_Interval'],	
	];
	
	$result = $classDB->update_settings(['Location_Info' => serialize($loc_array)]);

	if ($result) { $message = 'Database Updated on <strong>' . date('Y-m-d h:i:sa') . '</strong>'; }

}

# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
$classDB = new Database();
$locationInfo = $classDB->get_settings('Location_Info');
if(!empty($locationInfo)) {
	$locationInfo = unserialize($locationInfo);
}

include('header.php');
?>


<h2>Location Updater</h2>
<?=(!empty($message)) ? '<div class="message">'.$message.'</div>' : '';?>

<form id="locationForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

	<div>
		<?php $Echolink_Status_Servers = (!empty($locationInfo['Echolink_Status_Servers'])) ? $locationInfo['Echolink_Status_Servers'] : ''; ?>
		<label for="Echolink_Status_Servers">Echolink Status Servers</label>
		<input type="text" name="Echolink_Status_Servers" value="<?=$Echolink_Status_Servers?>" placeholder="aprs.echolink.org:5199">
	</div>

	<div>
		<?php $APRS_ServerList = (!empty($locationInfo['APRS_ServerList'])) ? $locationInfo['APRS_ServerList'] : ''; ?>
		<label for="APRS_ServerList">APRS Server List</label>
		<input type="text" name="APRS_ServerList" value="<?=$APRS_ServerList?>" placeholder="i.e. noam.aprs2.net:14580">
	</div>

	<div>
		<?php $Latitude = (!empty($locationInfo['Latitude'])) ? $locationInfo['Latitude'] : ''; ?>
		<label for="Latitude">Latitude</label>
		<input type="text" name="Latitude" value="<?=$Latitude?>" placeholder="40.781523" required>
		<em>In decimal format</em>
	</div>

	<div>
		<?php $Longitude = (!empty($locationInfo['Longitude'])) ? $locationInfo['Longitude'] : ''; ?>
		<label for="Longitude">Longitude</label>
		<input type="text" name="Longitude" value="<?=$Longitude?>" placeholder="-73.966529" required>
		<em>In decimal format</em>
	</div>

	<div>
		<?php $APRS_Station_Type = (!empty($locationInfo['APRS_Station_Type'])) ? $locationInfo['APRS_Station_Type'] : ''; ?>
		<label for="APRS_Station_Type">APRS Station Type</label>
		<select name="APRS_Station_Type">
			<option value="repeater"<?=($APRS_Station_Type == 'repeater')?' selected':'';?>>Repeater</option>
			<option value="link"<?=($APRS_Station_Type == 'link')?' selected':'';?>>Link</option>
		</select>
	</div>

	<div>
		<?php $Frequency = (!empty($locationInfo['Frequency'])) ? $locationInfo['Frequency'] : ''; ?>
		<label for="Frequency">Frequency</label>
		<input type="text" name="Frequency" value="<?=$Frequency?>" required>
		<em>MHz</em>
	</div>

	<div>
		<?php $Tone = (!empty($locationInfo['Tone'])) ? $locationInfo['Tone'] : '0'; ?>
		<label for="Tone">Tone</label>
		<select name="Tone">
			<option value="0"<?=($Tone === '0')?' selected':'';?>>None</option>
			<?php
				foreach($classDB->get_ctcss() as $freq => $code) {
					$option_string = '<option value="'.(int)$freq.'"';
					$option_string .= $Tone == (int)$freq ? ' selected>': '>';
					$option_string .= number_format((float)$freq, 1, '.', '').' Hz</option>';
					echo $option_string;
				}
			?>
		</select>
	</div>

	<div>
		<?php $TX_Power = (!empty($locationInfo['TX_Power'])) ? $locationInfo['TX_Power'] : ''; ?>
		<label for="TX_Power">TX Power</label>
		<input type="number" min="0" max="2000" name="TX_Power" value="<?=$TX_Power?>">
		<em>Watts</em>
	</div>

	<div>
		<?php $Antenna_Gain = (!empty($locationInfo['Antenna_Gain'])) ? $locationInfo['Antenna_Gain'] : ''; ?>
		<label for="Antenna_Gain">Antenna Gain</label>
		<input type="number" min="0" max="100" name="Antenna_Gain" value="<?=$Antenna_Gain?>">
		<em>dBd (not dBi)</em>
	</div>

	<div>
		<?php $Antenna_Height = (!empty($locationInfo['Antenna_Height'])) ? $locationInfo['Antenna_Height'] : ''; ?>
		<?php $Antenna_Height_Units = (!empty($locationInfo['Antenna_Height_Units'])) ? $locationInfo['Antenna_Height_Units'] : 'f'; ?>
		<label for="Antenna_Height">Antenna Height</label>
		<input type="number" min="0" name="Antenna_Height" value="<?=$Antenna_Height?>">
		<select name="Antenna_Height_Units">
			<option value="f"<?=($Antenna_Height_Units == 'f')?' selected':'';?>>Feet</option>
			<option value="m"<?=($Antenna_Height_Units == 'm')?' selected':'';?>>Meters</option>
		</select>
	</div>

	<div>
		<?php $Antenna_Dir = (!empty($locationInfo['Antenna_Dir'])) ? $locationInfo['Antenna_Dir'] : ''; ?>
		<label for="Antenna_Dir">Antenna Direction</label>
		<select name="Antenna_Dir" value="<?=$Antenna_Dir?>">
			<option value=""<?=($Antenna_Dir == '')?' selected':'';?>>None</option>
			<option value="-1"<?=($Antenna_Dir == '-1')?' selected':'';?>>Omni</option>
			<option value="360"<?=($Antenna_Dir == '360')?' selected':'';?>>North</option>
			<option value="22.5"<?=($Antenna_Dir == '22.5')?' selected':'';?>>--NNE</option>
			<option value="45"<?=($Antenna_Dir == '45')?' selected':'';?>>-NE</option>
			<option value="67.5"<?=($Antenna_Dir == '67.5')?' selected':'';?>>--ENE</option>
			<option value="90"<?=($Antenna_Dir == '90')?' selected':'';?>>East</option>
			<option value="112.5"<?=($Antenna_Dir == '112.5')?' selected':'';?>>--ESE</option>
			<option value="135"<?=($Antenna_Dir == '135')?' selected':'';?>>-SE</option>
			<option value="157.5"<?=($Antenna_Dir == '157.5')?' selected':'';?>>--SSE</option>
			<option value="180"<?=($Antenna_Dir == '180')?' selected':'';?>>South</option>
			<option value="202.5"<?=($Antenna_Dir == '202.5')?' selected':'';?>>--SSW</option>
			<option value="225"<?=($Antenna_Dir == '225')?' selected':'';?>>-SW</option>
			<option value="247.5"<?=($Antenna_Dir == '247.5')?' selected':'';?>>--WSW</option>
			<option value="270"<?=($Antenna_Dir == '270')?' selected':'';?>>West</option>
			<option value="292.5"<?=($Antenna_Dir == '292.5')?' selected':'';?>>--WNW</option>
			<option value="315"<?=($Antenna_Dir == '315')?' selected':'';?>>-NW</option>
			<option value="337.5"<?=($Antenna_Dir == '337.5')?' selected':'';?>>--NNW</option>
			<option value="360">North</option>
		</select>
	</div>

	<div>
		<?php $APRS_Path = (!empty($locationInfo['APRS_Path'])) ? $locationInfo['APRS_Path'] : ''; ?>
		<label for="APRS_Path">APRS Path</label>
		<input type="text" name="APRS_Path" value="<?=$APRS_Path?>" placeholder="WIDE1-1">
		<em>Examples: WIDE1-1, WIDE2-2</em>
	</div>

	<div>
		<?php $Beacon_Interval = (!empty($locationInfo['Beacon_Interval'])) ? $locationInfo['Beacon_Interval'] : ''; ?>
		<label for="Beacon_Interval">Beacon Interval</label>
		<input type="number" min="10" name="Beacon_Interval" value="<?=$Beacon_Interval?>" required>
		<em>Minutes</em>
	</div>

	<div>
		<?php $Statistics_Interval = (!empty($locationInfo['Statistics_Interval'])) ? $locationInfo['Statistics_Interval'] : ''; ?>
		<label for="Statistics_Interval">Statistics Interval</label>
		<select name="Statistics_Interval">
			<option value="5"<?=($Statistics_Interval == '5')?' selected':'';?>>5 mins</option>
			<option value="10"<?=($Statistics_Interval == '10')?' selected':'';?>>10 mins (default)</option>
			<option value="15"<?=($Statistics_Interval == '15')?' selected':'';?>>15 mins</option>
			<option value="20"<?=($Statistics_Interval == '20')?' selected':'';?>>20 mins</option>
			<option value="25"<?=($Statistics_Interval == '25')?' selected':'';?>>25 mins</option>
			<option value="30"<?=($Statistics_Interval == '30')?' selected':'';?>>30 mins</option>
			<option value="35"<?=($Statistics_Interval == '35')?' selected':'';?>>35 mins</option>
			<option value="40"<?=($Statistics_Interval == '40')?' selected':'';?>>40 mins</option>
			<option value="45"<?=($Statistics_Interval == '45')?' selected':'';?>>45 mins</option>
			<option value="50"<?=($Statistics_Interval == '50')?' selected':'';?>>50 mins</option>
			<option value="55"<?=($Statistics_Interval == '55')?' selected':'';?>>55 mins</option>
			<option value="60"<?=($Statistics_Interval == '60')?' selected':'';?>>60 mins</option>
		</select>
		<em>Interval that statistics are sent into the APRS network</em>
	</div>


	<br>
	<input type="hidden" name="action" value="update">
	<button class="myButton">Add/Update Location</button>
</form>

<?php include('footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>