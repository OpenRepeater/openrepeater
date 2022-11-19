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

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$pageTitle = "DTMF Reference"; 

include('includes/header.php');

$Database = new Database();
$settings = $Database->get_settings();
$ports = $Database->get_ports();

$ModulesClass = new Modules();
$modules = $ModulesClass->get_modules();


// Create count array for Link Groups
foreach ($ports as $curPort) {
	if (isset($curPort['linkGroup']) && $curPort['linkGroup'] > 0 && $curPort['portEnabled'] == 1){
		$linkGrpCount[$curPort['linkGroup']]++;			
	}
}
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

							<p>NOTE: If a module is running while you wish to disable the transmitter, you must first disable the module OR force the disable command by prefixing with a star (*). So, the disable command would become <strong>* + <?php echo $settings['repeaterDTMF_disable_pin'] ?> + 0#</strong></p>
							
							<br>
							<?php } ?>
							
							<?php echo $ModulesClass->display_dtmf_codes(); ?>
							
							<legend># - Deactivate Current Module</legend>
							<p>By pressing the number key (#), this will deactivate the current module. If you are in a module that has a couple levels, then the number key (#) will function like a back key.</p>
							<br>


							<?php
							// Display link groups that should be active
							if (isset($linkGrpCount)) {
								echo '<legend>Links</legend>';
								echo '<p>You can enable or disable linking using the codes below.</p>';

								foreach ($linkGrpCount as $curLinkNum => $curLinkCount) {
									if ($curLinkCount > 1) {
										?>
										<h4>Link Group <?php echo $curLinkNum ?></h4>
										
										<p><strong>8<?php echo $curLinkNum ?> + 0#</strong> - <span style="color:red;">Disable Link Group <?php echo $curLinkNum ?></span> <br><em>Link Group ID + 0 (Disable) + # (Execute Command)</em></p>

										<p><strong>8<?php echo $curLinkNum ?> + 1#</strong> - <span style="color:green;">Enable Link Group <?php echo $curLinkNum ?></span> <br><em>Link Group ID + 1 (Enable) + # (Execute Command)</em></p>
										<?php
									}
								}
								
							}							
							?>

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