<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

session_name("open_repeater_wizard");   // In case there are several session based applications

if (!isset($_POST["page"])) {
	// Default Settings are not visiable to the user. They will get written into database as defaults that they can change later on.
	$default_settings = array(
		"orp_Mode"				=>		"repeater",
		"courtesy"				=>		"4_Up.wav",
		"repeaterTimeoutSec"	=>		"230",
		"rxTone"				=>		"",
		"txTailValueSec"		=>		"2",
		"txTone"				=>		"",
		"courtesyMode"			=>		"beep",
		"ID_Short_Mode"			=>		"morse",
		"ID_Long_Mode"			=>		"voice",
		"ID_Morse_Suffix"		=>		"/R",
		"ID_Morse_WPM"			=>		"25",
		"ID_Morse_Pitch"		=>		"600",
		"ID_Long_IntervalMin"	=>		"60",
		"ID_Long_AppendTime"	=>		"False",
		"ID_Long_AppendTone"	=>		"False",
		"ID_Long_AppendMorse"	=>		"True",
		"ID_Long_CustomFile"	=>		"Sample_Long_ID_Clip.wav",
		"ID_Short_IntervalMin"	=>		"10",
		"ID_Short_AppendMorse"	=>		"False",
		"ID_Short_CustomFile"	=>		"Sample_Short_ID_Clip.wav",
		"ID_Morse_Amplitude"	=>		"200",
		"repeaterDTMF_disable"	=>		"False",
		"repeaterDTMF_disable_pin"	=>	"1234"
		);

	$process_page = "not_set";
	$page = "wizard_page1"; // Starting Page
	$_SESSION["agree"] = "";
	$_SESSION["default_settings"] = $default_settings; // Define Extra Default Settings that will be included at end of wizard
	$_SESSION["new_repeater_settings"] = array(); // Define Settings Array
	$_SESSION["new_repeater_ports"] = array(); // Define Ports Array
	$_SESSION["interface"] = array(); // Define Interface Settings Array
		
	// read sound devices into session array
	$SoundDevices = new SoundDevices();
	$device_list = $SoundDevices->get_device_list();
	$_SESSION["sound_devices"] = array();
	$_SESSION["sound_devices"] = $device_list;

} else {
	$process_page = $_POST['process_page'];
	$page = $_POST['page'];
}


$errormessage = null;

// Handle the incoming request based on the page it came from or the default set above.


// ---------------------------------------------------------------------------------------
// Process Previous Page Data
// ---------------------------------------------------------------------------------------

switch ($process_page) {

case "not_set": // write this pages cotents into array and go to next page
	break;

case "wizard_page1": // write this pages cotents into array and go to next page
	if (isset($_POST['agree'])) {
		$_SESSION["agree"] = $_POST['agree']; // Sets to yes, just in case the user goes back to page
	} else {
		$alert = '<div class="alert alert-error">Sorry, but you must read and agree to the terms to continue.</div>';
		$page = 'wizard_page1'; // keep on this page to fix error
	}
	break;

case "wizard_page2": // write this pages cotents into array
	if (preg_match('/\S/',$_POST["callSign"])) {
		foreach ($_POST as $key => $value) {
			if (!in_array($key, array('page', 'process_page'))) { // Don't add these $_POST vars to session array
				if ($key == 'callSign') { $value = strtoupper($value); }
				$_SESSION["new_repeater_settings"][$key] = $value;
			}
		}
	} else {
		$alert = '<div class="alert alert-error">You must set the repeater\'s callsign</div>';
		$page = 'wizard_page2'; // keep on this page to fix error
	}
	break;

case "wizard_page3": // write this pages cotents into array and go to next page
	if ($_POST["board_id"] == 'manual') {
		$_SESSION["interface"]["type"] = 'manual';

		if (preg_match('/\S/',$_POST["txGPIO"])) {
			foreach ($_POST as $key => $value) {
				if (!in_array($key, array('page', 'process_page'))) { // Don't add these $_POST vars to session array
					$_SESSION["new_repeater_ports"][$key] = $value;
				}
			}
		} else {
			$alert = '<div class="alert alert-error">There was a problem with your post, please check your entry and try again.</div>';
			$page = 'wizard_page3'; // keep on this page to fix error
		}

	} else {
		$_SESSION["interface"]["type"] = 'preset';
		$_SESSION["interface"]["board_id"] = $_POST["board_id"];
	}
	break;

case "wizard_update":
	if ($_POST["page"] != "wizard_page3") { // If back button wasn't press then proceed...
		$merged_settings = array_merge($_SESSION["new_repeater_settings"], $_SESSION["default_settings"]);

		$Database = new Database();
		$board_presets = new BoardPresets();

		// Update Settings Table
		foreach($merged_settings as $key=>$value){  
			$query = $Database->update("UPDATE settings SET value='$value' WHERE keyID='$key'");
		}

		if ($_SESSION["interface"]["type"] == 'preset') {
			// Process Preset Board Settings
			$board_select_options = $board_presets->load_board_settings($_SESSION["interface"]["board_id"]);

		} else {
			// Process Manual port setup
		
			// Update Ports Table
			$query = $Database->delete_row('DELETE from PORTS;');		
		
			$portNum='1';
			$portLabel=$_SESSION["new_repeater_ports"]['portLabel'];
			$rxMode=$_SESSION["new_repeater_ports"]['rxMode'];
			$rxGPIO=$_SESSION["new_repeater_ports"]['rxGPIO'];
			$txGPIO=$_SESSION["new_repeater_ports"]['txGPIO'];
			$rxAudioDev=$_SESSION["new_repeater_ports"]['rxAudioDev'];
			$txAudioDev=$_SESSION["new_repeater_ports"]['txAudioDev'];
			$rxGPIO_active=$_SESSION["new_repeater_ports"]['rxGPIO_active'];
			$txGPIO_active=$_SESSION["new_repeater_ports"]['txGPIO_active'];
		
			$sql = "INSERT INTO ports (portNum,portLabel,rxMode,rxGPIO,txGPIO,rxAudioDev,txAudioDev,rxGPIO_active,txGPIO_active) VALUES ('$portNum','$portLabel','$rxMode','$rxGPIO','$txGPIO','$rxAudioDev','$txAudioDev','$rxGPIO_active','$txGPIO_active')";

			$Database->insert($sql);
	
			// Update GPIO Pins Table
			$Database->delete_row('DELETE from gpio_pins;');
	
			if ($rxMode == "gpio") {
				$sql_rx = "INSERT INTO gpio_pins (gpio_num,direction,active,description,type) VALUES ('$rxGPIO','in','$rxGPIO_active','PORT $portNum RX: $portLabel','Port')";
				$Database->insert($sql_rx);
			}
	
			$sql_tx = "INSERT INTO gpio_pins (gpio_num,direction,active,description,type) VALUES ('$txGPIO','out','$txGPIO_active','PORT $portNum TX: $portLabel','Port')";
			$Database->insert($sql_tx);	
	
			// Update Modules Table
			$Database->delete_row('DELETE from modules;');
	
			// Help Module
			$sql_module = "INSERT INTO modules (moduleKey,moduleEnabled,svxlinkName,svxlinkID,moduleOptions) VALUES ('1','1','Help','0','')";
			$Database->insert($sql_module);	
	
			// Parrot Module
			$sql_module = "INSERT INTO modules (moduleKey,moduleEnabled,svxlinkName,svxlinkID,moduleOptions) VALUES ('2','1','Parrot','1','')";
			$Database->insert($sql_module);	
	
			// EchoLink Module
			$serialized_options = 'a:10:{s:7:"timeout";s:2:"60";s:8:"callSign";s:0:"";s:8:"password";s:0:"";s:5:"sysop";s:12:"OpenRepeater";s:8:"location";s:0:"";s:11:"description";s:34:"Welcome to an Open Repeater Server";s:6:"server";s:20:"servers.echolink.org";s:8:"max_qsos";s:1:"4";s:11:"connections";s:1:"4";s:12:"idle_timeout";s:3:"300";}';
			$sql_module = "INSERT INTO modules (moduleKey,moduleEnabled,svxlinkName,svxlinkID,moduleOptions) VALUES ('3','0','EchoLink','2','$serialized_options')";
			$Database->insert($sql_module);	

		}
	
// ******** REBUILD CONFIG FILES ****************

		break;

	} else {
		break;	// Back button was pressed...do nothing	
	}

case "wizard_complete":
	// Run SVXLink_Update to create new config files
	header("Location: ../functions/svxlink_update.php");
	// Note: svxlink_update will check for wizard sessions and destory/logout when done.
	break;

}

// ---------------------------------------------------------------------------------------
// Prepare Current Page Content
// ---------------------------------------------------------------------------------------

$stepTotal = 5;
$stepCurrent = 0; // changed below

switch ($page) {

// ----------------------------------------

case "wizard_page1":
		$stepCurrent = 1;

		if ($_SESSION["agree"] == "yes") { $agree_status = " checked"; } else { $agree_status = ""; }
        $wizardContent = "
			<legend>Welcome to OpenRepeater</legend>
			<p>Welcome to the OpenRepeater setup wizard. This wizard will guide you through the essential settings to get your OpenRepeater controller up and running. It will not set all of the settings and it will set many to defaults. <em>Note that none of your entries will be applied until you have completed the wizard, applied your changes, and rebuilt and restart the controller.</em> Any other setting you will be able to modify after the controller is setup.
			<p>Thanks again for your support of the OpenRepeater Project!</p>
			<p><em>~The OpenRepeater Team~</em></p>

			<p>&nbsp;</p>";

        $wizardContent .= '<legend>Before You Get Started</legend>';

        $wizardContent .= '<div class="scrollTerms">'; // Start of Scroll Box

		ob_start();
		include "includes/agreement.php";
		$wizardContent .= ob_get_clean();

        $wizardContent .= '</div>'; // END of Scroll Box

        $wizardContent .= '
		<div>
			<input type="checkbox" name="agree" value="yes"'.$agree_status.'> I have read the requirements for hardware and I understand about setting up a repeater and the potential to cause interference.
		</div>
		';

        $wizardNav = '
			<input type=hidden name="process_page" value="wizard_page1">
			<button name="page" type="submit" value="wizard_page2" class="btn btn-primary">Continue</button>
        ';

        break;

// ----------------------------------------

case "wizard_page2":
		$stepCurrent = 2;

        $title = "Enter Callsign";
        $wizardContent = '
			<legend>Basic Identification Settings</legend>
			
			<p>Please enter the callsign that will be used to identify this repeater.</p>
			<p><em>Note: Only enter the call sign like X0XXX. Do not add any suffixes like "/R" to the end. There are other identification options you can choose later to set suffixes for Morse Code IDs.</em></p>
			<p>&nbsp;</p>
			
			<label class="control-label" for="callSign">Call Sign</label>
			<div class="controls">
			  <input class="input-xlarge" style="text-transform: uppercase" id="callSign" type="text" name="callSign" value="' . (isset($_SESSION['new_repeater_settings']['callSign']) ? $_SESSION['new_repeater_settings']['callSign'] : '') . '" autofocus required>
			  <span class="help-inline">This call sign will be used for identification.</span>
			</div>
		';

        $wizardNav = '
			<input type=hidden name="process_page" value="wizard_page2">
			<button name="page" type="submit" value="wizard_page3" class="btn btn-primary">Continue</button>
			<button name="page" type="submit" value="wizard_page1" class="btn">Back</button>
		';

        break;

// ----------------------------------------

case "wizard_page3":
		$stepCurrent = 3;

		// Load Board Presets
		$board_presets = new BoardPresets();
		$board_select_options = $board_presets->get_select_options();

        $title = "Setup Your Ports";

        $wizardContent = '';

        $wizardContent .= '
			<legend>Setup Supported Interface Board or Manual Configuration</legend>
			<p>If you have a supported interface board and have it connected to your single board computer you can choose it from the list. Otherwise, if you have a board that is not supported or you have built your own interface hardware, then choose <em>Manual Configuration</em>.</p>
		';

        $wizardContent .= '
			<div class="control-group">
				<label class="control-label" for="board_id">Select Interface:</label>
				<div class="controls">
					<select id="board_id" name="board_id" style="min-width:300px;" required>
						<option value="" selected>Select One...</option>
						<option value = "manual">Manual Configuration</option>
						' . $board_select_options . '
					</select>
				</div>
			</div>
		';
		
		#############################################################

        $wizardContent .= '<div id="port_manual_grp" style="display: none;">';
        $wizardContent .= '
			<legend>Manually Setup the 1<sup>st</sup> Port</legend>
			<p>Ports are the audio and logic I/Os that interface the OpenRepeater controller with the transmitter and receiver to make the repeater function. This is done through other external circuitry. Since you have chosen to set this up manually, you must specify the settings for this hardware. It utilizes both a sound card and the GPIO pins to make up the port, usually a paired receiver and transmitter hence a repeater. Here you will setup the first port required to make the controller initially function. You will be able to add other ports later if you require them and your hardware supports them.</p>
		';

isset($_SESSION['new_repeater_ports']['rxMode']) ? $rxMode = $_SESSION['new_repeater_ports']['rxMode'] : $rxMode = 'gpio';
isset($_SESSION['new_repeater_ports']['rxAudioDev']) ? $rxAudioDev = $_SESSION['new_repeater_ports']['rxAudioDev'] : $rxAudioDev = '';
isset($_SESSION['new_repeater_ports']['rxGPIO_active']) ? $rxGPIO_active = $_SESSION['new_repeater_ports']['rxGPIO_active'] : $rxGPIO_active = 'high';
isset($_SESSION['new_repeater_ports']['rxGPIO']) ? $rxGPIO = $_SESSION['new_repeater_ports']['rxMode'] : $rxGPIO = '';
isset($_SESSION['new_repeater_ports']['txAudioDev']) ? $txAudioDev = $_SESSION['new_repeater_ports']['txAudioDev'] : $txAudioDev = '';
isset($_SESSION['new_repeater_ports']['txGPIO_active']) ? $txGPIO_active = $_SESSION['new_repeater_ports']['txGPIO_active'] : $txGPIO_active = 'low';
isset($_SESSION['new_repeater_ports']['txGPIO']) ? $txGPIO = $_SESSION['new_repeater_ports']['txGPIO'] : $txGPIO = '';


		$rxModeOptions = '';
		if ($rxMode == 'gpio') { $rxModeOptions .= '<option value="gpio" selected>COS (Carrier Operated Switch)</option>'; } else { $rxModeOptions .= '<option value="gpio">COS (Carrier Operated Switch)</option>'; }
		if ($rxMode == 'vox') { $rxModeOptions .= '<option value="vox" selected>VOX (Voice Operated Transmit)</option>'; } else { $rxModeOptions .= '<option value="vox">VOX (Voice Operated Transmit)</option>'; }

		$rxDeviceOptions = "";
		for ($device = 0; $device <  count($_SESSION["sound_devices"]); $device++) {
		   if ($_SESSION["sound_devices"][$device]['direction'] == "IN") {
				$rxValue = 'alsa:plughw:'.$_SESSION["sound_devices"][$device]['card'].'|'.$_SESSION["sound_devices"][$device]['channel'];
				$currentRX = $rxAudioDev;
				if ($rxValue == $currentRX) { $rxSelected = " selected"; } else { $rxSelected = ""; }
				$rxDeviceOptions .= '<option value="'.$rxValue.'"'.$rxSelected.'>INPUT: '.$_SESSION["sound_devices"][$device]['label'].' ('.$_SESSION["sound_devices"][$device]['channel_label'].')</option>';
			}
		}

        $wizardContent .= '
			<legend>Receiver Settings (RX)</legend>

			<input type="hidden" value="Port 1" name="portLabel">';

		if ($rxGPIO_active == 'low' || '') {
			$rxGPIO_active_options = '<option value = "low" selected>Active Low</option><option value = "high">Active High</option>';			
		} else {
			$rxGPIO_active_options = '<option value = "low">Active Low</option><option value = "high" selected>Active High</option>';			
		}

		if ($rxGPIO_active == 'high' || '') {
			$txGPIO_active_options = '<option value = "low">Active Low</option><option value = "high" selected>Active High</option>';			
		} else {
			$txGPIO_active_options = '<option value = "low" selected>Active Low</option><option value = "high">Active High</option>';			
		}


        $wizardContent .= '
			<p>The receiver settings are what interface the OpenRepeater controller with your receive radio, or the input of the repeater. The most common and most reliable receive mode would be COS (Carrier Operated Switch). When the repeater’s squelch opens (or tone squelch if you have a receive tone set in the radio) an electronic trigger from the radio interfaces with some basic circuitry to trigger an input GPIO pin on the OpenRepeater Controller to go low to ground (active state) and pull high when the squelch is closed. Audio from the output of the receiver is routed into the selected audio input for the port. Together these will make up the input side of the port and be repeated to the transmit side of the port and other ports or Echolink if enabled.</p>

			<div class="control-group">
				<label class="control-label" for="rxMode">Receive Mode</label>
				<div class="controls">
					<select id="rxMode" name="rxMode" style="width:300px;" autofocus required>
						<option value = "">---</option>'.$rxModeOptions.'
					</select>
				</div>
				<span class="help">This will determine how the repeater is activated. The COS Mode is recommended.</span>

				<div id="rxVOX_warn" style="display: none;">
					<div class="alert alert-danger">
					<strong>WARNING:</strong> The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible. 
					</div>
				</div>
			</div>


			<div id="rxGPIO_grp" style="display: none;">
			<div class="control-group">
				<label class="control-label" for="rxGPIO">Receive GPIO Pin</label>
				<div class="controls">
					<input type="text" value="'.$rxGPIO.'" class="form-control" id="rxGPIO" name="rxGPIO" maxlength="3" style="width:143px;" placeholder="PIN #">
					<select id="rxGPIO_active" name="rxGPIO_active" style="width:143px;">'.$rxGPIO_active_options.'</select>					
				</div>
				<span class="help">The GPIO input pin that will trigger the COS and whether it should be active high or low.</span>
				<span class="help">See online documentation for wiring.</span>
			</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="rxAudioDev">Receive Audio Input</label>
				<div class="controls">
					<select id="rxAudioDev" name="rxAudioDev" style="width:300px;" required>
						<option value = "">---</option>'.$rxDeviceOptions.'
					</select>
				</div>
				<span class="help">The audio input that processes receive audio.</span>
			</div>
		';



		$txDeviceOptions = "";
		for ($device = 0; $device <  count($_SESSION["sound_devices"]); $device++) {
		   if ($_SESSION["sound_devices"][$device]['direction'] == "OUT") {
				$txValue = 'alsa:plughw:'.$_SESSION["sound_devices"][$device]['card'].'|'.$_SESSION["sound_devices"][$device]['channel'];
				$currentTX = $txAudioDev;
				if ($txValue == $currentTX) { $txSelected = " selected"; } else { $txSelected = ""; }
				$txDeviceOptions .= '<option value="'.$txValue.'"'.$txSelected.'>OUTPUT: '.$_SESSION["sound_devices"][$device]['label'].' ('.$_SESSION["sound_devices"][$device]['channel_label'].')</option>';
			}
		}

        $wizardContent .= '
			<legend>Transmitter Settings (TX)</legend>
			<p>The transmitter settings are used to interface the OpenRepeater controller with transmitter to rebroadcast transmissions and identification. The GPIO pin is use to trigger the PTT on the radio with some basic interface circuitry. The audio output of the controller interfaces with the audio/mic input on the transmitter.</p>

			<div class="control-group">
				<label class="control-label" for="txGPIO">Transmit GPIO Pin</label>
				<div class="controls">
					<input type="text" value="'.$txGPIO.'" class="form-control" id="txGPIO" name="txGPIO" maxlength="3" style="width:143px;" placeholder="PIN #" required>
					<select id="txGPIO_active" name="txGPIO_active" style="width:143px;">'.$txGPIO_active_options.'</select>					
				</div>
				<span class="help">The GPIO output pin that controls PTT on the transmitter and whether it should be active</span>
				<span class="help">high or low. See online documentation for wiring.</span>
			</div>

			<div class="control-group">
				<label class="control-label" for="txAudioDev">Transmit Audio Output</label>
				<div class="controls">
					<select id="txAudioDev" name="txAudioDev" style="width:300px;" required>
						<option value = "">---</option>'.$txDeviceOptions.'
					</select>
				</div>
				<span class="help">The audio output that sends audio to transmitter.</span>
			</div>
		';
        $wizardContent .= '</div>'; // End Manual Port Grouping

		#############################################################

        $wizardNav = '
			<input type=hidden name="process_page" value="wizard_page3">
			<button name="page" type="submit" value="wizard_confirmation" class="btn btn-primary">Continue</button>
			<button name="page" type="submit" value="wizard_page2" class="btn">Back</button>
		';

        break;

// ----------------------------------------

case "wizard_confirmation":
		$stepCurrent = 4;

        $title = "Here is what you have entered so far ...";

        $wizardContent = '
			<legend>Confirm Settings</legend>
			<p>Here is what you have entered. Please confirm that this is correct, if not use the back navigation at the bottom of each page to go back and make corrections. This will be the minimum requirements to get OpenRepeater up an running. Once you have verified it is working, you can change other settings. Upon continuing, the settings you have chosen below will be applied to the repeater configuration.</p>
			<p>&nbsp;</p>
		';

        $wizardContent .= 'Repeater Callsign: <strong>'.$_SESSION["new_repeater_settings"]['callSign'].'</strong>';
        $wizardContent .= '<hr>';
        
		if ($_SESSION["interface"]["type"] == 'preset') {
			// Display Preset Board Settings
			$board_id = $_SESSION["interface"]["board_id"];

			$board_presets = new BoardPresets();
			$board_info = $board_presets->get_board_definitions($board_id);

	        $wizardContent .= '<h3>Interface Board</h3>';
	        $wizardContent .= 'Manufacture: <strong>' . $board_info['manufacturer'] . '</strong>';
	        $wizardContent .= '<br>';
	        $wizardContent .= 'Model: <strong>' . $board_info['model'] . '</strong>';
			$wizardContent .= '<br>';
	        $wizardContent .= 'Version: <strong>' . $board_info['version'] . '</strong>';
	        $wizardContent .= '<br>';
			
		} else {
			// Display manual port setup
	        $wizardContent .= '<h3>Manual Port Settings</h3>';

			if ($_SESSION["new_repeater_ports"]['rxMode'] == "gpio") {
		        $wizardContent .= 'Receive Mode: <strong>COS (Carrier Operated Switch)</strong>';
		        $wizardContent .= '<br>';
		        $wizardContent .= 'Receive GPIO Pin: <strong>'.$_SESSION["new_repeater_ports"]['rxGPIO'].' </strong> ('.$_SESSION["new_repeater_ports"]['rxGPIO_active'].')';
				$wizardContent .= '<br>';
	        } else 	if ($_SESSION["new_repeater_ports"]['rxMode'] == "vox") {
		        $wizardContent .= 'Receive Mode: <strong>VOX (Voice Operated Transmit)</strong>';
		        $wizardContent .= '<br>';
	        }
	
			for ($device = 0; $device <  count($_SESSION["sound_devices"]); $device++) {
			   if ($_SESSION["sound_devices"][$device]['direction'] == "IN") {
					$rxValue = 'alsa:plughw:'.$_SESSION["sound_devices"][$device]['card'].'|'.$_SESSION["sound_devices"][$device]['channel'];
					$currentRX = $_SESSION['new_repeater_ports']['rxAudioDev'];
					if ($rxValue == $currentRX) { 
				        $wizardContent .= 'Receive Audio Output: <strong>INPUT: '.$_SESSION["sound_devices"][$device]['label'].' ('.$_SESSION["sound_devices"][$device]['channel_label'].')</strong>';
					}
				}
			}
	        $wizardContent .= '<br>';
	
	
	        $wizardContent .= 'Transmit GPIO Pin: <strong>'.$_SESSION["new_repeater_ports"]['txGPIO'].'</strong> ('.$_SESSION["new_repeater_ports"]['txGPIO_active'].')';
	        $wizardContent .= '<br>';
	
			for ($device = 0; $device <  count($_SESSION["sound_devices"]); $device++) {
			   if ($_SESSION["sound_devices"][$device]['direction'] == "OUT") {
					$txValue = 'alsa:plughw:'.$_SESSION["sound_devices"][$device]['card'].'|'.$_SESSION["sound_devices"][$device]['channel'];
					$currentTX = $_SESSION['new_repeater_ports']['txAudioDev'];
					if ($txValue == $currentTX) { 
				        $wizardContent .= 'Transmit Audio Output: <strong>OUTPUT: '.$_SESSION["sound_devices"][$device]['label'].' ('.$_SESSION["sound_devices"][$device]['channel_label'].')</strong>';
				        $wizardContent .= '<br>';
					}
				}
			}

		}





        $wizardNav = '
			<input type=hidden name="process_page" value="wizard_update">
			<button name="page" type="submit" value="wizard_complete" class="btn btn-primary">Save Configuration</button>
			<button name="page" type="submit" value="wizard_page3" class="btn">Back</button>
		';

        break;

// ----------------------------------------

case "wizard_complete":
		$stepCurrent = 5;

        $title = "Here is what you have entered so far ...";
        $wizardContent = '
			<legend>Build Configuration Files</legend>
			<p>Almost Finished…Upon clicking the button below, the repeater configuration files will be generated and you will be logged out of the wizard. Upon logging in again, you will be presented with the full control panel. Check to make sure that the OpenRepeater controller is working in the basic setup before configuring other options. If the repeater is not working, then a system restart might be required in some cases. If you need further help, please visit <a href="http://openrepeater.com" target="_blank">OpenRepeater.com</a> for more support.</p>
			<p>&nbsp;</p>
		';

        $wizardNav = '
			<input type=hidden name="process_page" value="wizard_complete">
			<button name="page" type="submit" value="wizard_complete" class="btn btn-primary">Finish & Logout</button>
		';

        break;


}



// ---------------------------------------------------------------------------------------
// Page Output Template
// ---------------------------------------------------------------------------------------

$stepText = "Step ".$stepCurrent." of ".$stepTotal;
$stepPercent = round(($stepCurrent/$stepTotal)*100)."%"; //create pecentage for inline CSS for progress bar

$pageTitle = "Setup Wizard"; 
// Wizard CSS is loaded by custom header in wizard folder
include('includes/header.php'); // Load custom wizard header
?>

			<h3><?php echo $stepText; ?></h3>
			<div class="progress progress-striped progress-success active">
				<div class="bar" style="width: <?php echo $stepPercent; ?>;"></div>
			</div>

			<?php if(isset($alert)) { echo $alert; } ?>

			<form method=POST>

			<div class="row-fluid sortable wizard">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-cog"></i> Setup Wizard</h2>
					</div>
					<div class="box-content">

						  <fieldset>
							  <?php print ($wizardContent); ?>

							<div class="form-actions">
							  <?php print ($wizardNav); ?><br />
							</div>
						  </fieldset>



					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			</form>
    
<?php include('includes/footer.php'); // Load custom wizard footer ?>




<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>