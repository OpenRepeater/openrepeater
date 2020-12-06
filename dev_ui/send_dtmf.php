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
$ports = $classDB->get_ports();

if(!empty($locationInfo)) {
	$locationInfo = unserialize($locationInfo);
}


include('header.php');
?>


<h2>Send DTMF Commands</h2>
<?=(!empty($message)) ? '<div class="message">'.$message.'</div>' : '';?>


<div id="locationForm">
 	<div>
		<?php $APRS_Station_Type = (!empty($locationInfo['APRS_Station_Type'])) ? $locationInfo['APRS_Station_Type'] : ''; ?>
		<label for="APRS_Station_Type">Logic Section</label>
		<select id="logic_section" name="logic_section">
			<?php
				$base_path = '/usr/share/svxlink/orp_pty/';
				foreach ($ports as $key => $val) {
					echo '<hr>';
					switch ($val['portDuplex']) {
						case 'full':
							$curOptionVal = $base_path . 'ORP_FullDuplexLogic_Port' . $val['portNum'] . '/dtmf_ctrl';
							break;
						case 'half':
							$curOptionVal = $base_path . 'ORP_HalfDuplexLogic_Port' . $val['portNum'] . '/dtmf_ctrl';
							break;
					}
					$curOptionName = 'Port ' . $val['portNum'] . ': ' . $val['portLabel'];
					echo '<option value="' . $curOptionVal . '">' . $curOptionName . '</option>';
				}	
			?>
		</select>
	</div>

	<div>
		<label for="Frequency">DTMF Codes</label>
		<textarea id="dtmf_input" name="dtmf_input" style="text-transform:uppercase;" placeholder="Enter DTMF codes" required></textarea>
	</div>

	<div>
		<label>&nbsp;</label>
		<button id="send_dtmf" class="myButton">Send DTMF</button>
	</div>

</div>


<?php include('footer.php'); ?>

<script src="send_dtmf.js"></script>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>