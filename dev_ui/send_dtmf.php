<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: ../login.php'); // If they aren't logged in, send them to login page.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------

# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
$classDB = new Database();
$ports = $classDB->get_ports();

include('header.php');
?>


<h2>Send DTMF Commands</h2>
<?=(!empty($message)) ? '<div class="message">'.$message.'</div>' : '';?>

<div id="dtmfForm">
 	<div>
		<label for="logic_section">Logic Section</label>
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
		<label for="dtmf_input">DTMF Codes</label>
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