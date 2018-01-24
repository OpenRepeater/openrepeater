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

$pageTitle = "DTMF Reference"; 

include_once("includes/get_settings.php");
include_once("includes/get_modules.php");
$dbConnection->close();

include('includes/header.php');

?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<form class="form-horizontal" role="form" action="functions/ajax_db_update.php" method="post" id="settingsUpdate" name="settingsUpdate" >
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th"></i> DTMF Reference</h2>
					</div>
					<div class="box-content">
						<p>This provides a quick reference of available DTMF commands that can be sent to the repeater from another radio. This list will change depending on the modules that you have enabled. You may print this page for your own reference.</p>
						<br>

						<fieldset>
							  
							<legend>* - Force ID</legend>
							<p>By pressing the star key (*), this will cause the repeater to identify.</p>
							<br>

							
							<?php if ($settings['repeaterDTMF_disable'] == 'True') { ?>
							<a name="remoteDisable"></a>
							<legend>Remote DMTF Disable</legend>
							<p>You have chosen to enable the ability to remotely disable the transmitter via DTMF commands. This is useful for control operators to stop system abuse or to simply make the system inactive. Note that the pin your selected is part of the codes below.</p>

							<p><strong><?php echo $settings['repeaterDTMF_disable_pin'] ?> + 0#</strong> - <span style="color:red;">Disable Transmitter</span> <br><em>Your code + 0 (Disable) + # (Execute Command)</em></p>

							<p><strong><?php echo $settings['repeaterDTMF_disable_pin'] ?> + 1#</strong> - <span style="color:green;">Enable Transmitter</span> <br><em>Your code + 1 (Enable) + # (Execute Command)</em></p>

							<p>NOTE: If a module is running while you wish to disable the transmitter, you must first disable the module OR force the disable command by prefixing with a star (*). So, the disable command would become <strong>* + <?php echo $settings['repeaterDTMF_disable_pin'] ?> + 0#</p>
							
							<br>
							<?php } ?>
							
							
							
							<?php 
							if ($module) {
								foreach($module as $cur_mod) {
									if ($cur_mod['moduleEnabled']==1) { 
										$mod_ini_file = 'modules/'.$cur_mod['svxlinkName'].'/info.ini';
										$dtmf_help_file = 'modules/'.$cur_mod['svxlinkName'].'/dtmf.php';
										echo '<a name="' . $cur_mod['svxlinkName'] . '"></a>';
								
										include($dtmf_help_file);
										echo '<legend>'.$cur_mod['svxlinkID'].'# - '.$cur_mod['moduleName'].'</legend>
										<p>Pressing '.$cur_mod['svxlinkID'].'# will enable the '.$cur_mod['moduleName'].' module. ';
								
										if (file_exists($mod_ini_file)) {
											$mod_ini_array = parse_ini_file($mod_ini_file, true);
											if ($mod_ini_array['Module_Info']['mod_desc']) {
												echo $mod_ini_array['Module_Info']['mod_desc'];
											} 
										}
										echo '</p>';
								
										if ($cur_mod['moduleEnabled']==1 && file_exists($dtmf_help_file)) {
											echo '<h4>Sub Commands:</h4>
											<pre>'.$sub_subcommands.'</pre>
											<br>';
										}
									}
								} /* End Current Module */
							}
							?>
							
							
							<legend># - Deactivate Current Module</legend>
							<p>By pressing the number key (#), this will deactivate the current module. If you are in a module that has a couple levels, then the number key (#) will function like a back key.</p>
							<br>


						</fieldset>

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