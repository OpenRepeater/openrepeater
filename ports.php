<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------

if (isset($_POST['action'])){

	$db = new SQLite3('/var/lib/openrepeater/db/openrepeater.db');
	
	foreach($_POST as $key=>$value){  
		if ($key != "action") {
//			$query = $db->exec("UPDATE set SET value='$value' WHERE keyID='$key'");
			$query = $db->exec("UPDATE ports SET $key = '$value' WHERE portNum = 1;");
		}
	}
   $db->close();
	

	$msgText = "The port settings have been updated successfully!";
	$alert = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>'.$msgText.'</div>';


	/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
	$memcache_obj = new Memcache;
	$memcache_obj->connect('localhost', 11211);
	$memcache_obj->set('update_settings_flag', 1, false, 0);
}
?>

<?php
$pageTitle = "Ports"; 
include('includes/header.php');
include('includes/get_ports.php');
$dbConnection->close();

?>

			<?php echo $alert; ?>

			<form name="update" action="ports.php" method="post" class="form-horizontal">

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-cog"></i> Port 1</h2>
					</div>
					<div class="box-content">
						<?php
							echo "<h4>Ports Defined: ". count($ports)."</h4>";
						?>

						<?php $curPort = 1; ?>

						<br>
						
						<table>
						  <tr>
						    <th style="text-align: left;">Port Label</th>
						  </tr>
						  <tr>
						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['portLabel']; ?>" class="form-control" id="portLabel" name="portLabel" disabled>
						    </td>
						  </tr>
						</table>						

						<br>

						<table>
						  <tr>
						    <th>RX Mode</th>
						    <th>RX GPIO Pin</th>
						    <th>RX Audio Device</th>
						    <th>RX Audio Channel</th>
						  </tr>
						  <tr>
						    <td>
								<select id="rxMode" name="rxMode" data-rel="chosen" style="width:110px;">
									<option value="vox" <?php if ($ports[$curPort]['rxMode'] == 'vox') { echo "selected"; } ?>>VOX</option>
									<option value="gpio" <?php if ($ports[$curPort]['rxMode'] == 'gpio') { echo "selected"; } ?>>COS</option>
								</select>
						    </td>


						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['rxGPIO']; ?>" class="form-control" id="rxGPIO" name="rxGPIO" style="width:110px;">
						    </td>

						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['rxAudioDev']; ?>" class="form-control" id="rxAudioDev" name="rxAudioDev" style="width:110px;">
						    </td>

						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['rxAudioChl']; ?>" class="form-control" id="rxAudioChl" name="rxAudioChl" style="width:110px;">
						    </td>
						  </tr>
						</table>

						<br>

						<table>
						  <tr>
						    <th>TX GPIO Pin</th>
						    <th>TX Audio Device</th>
						    <th>TX Audio Channel</th>
						  </tr>

						  <tr>
						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['txGPIO']; ?>" class="form-control" id="txGPIO" name="txGPIO" style="width:110px;">
						    </td>

						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['txAudioDev']; ?>" class="form-control" id="txAudioDev" name="txAudioDev" style="width:110px;">
						    </td>

						    <td>
								<input type="text" value="<?php echo $ports[$curPort]['txAudioChl']; ?>" class="form-control" id="txAudioChl" name="txAudioChl" style="width:110px;">
						    </td>
						  </tr>
						</table>


						<div class="form-actions">
						  <input type="hidden" name="action" value="update">		
						  <button type="submit" class="btn btn-primary">Update</button>
						  <button type="reset" class="btn">Cancel</button>
						</div>
						
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

			</form>
			
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
