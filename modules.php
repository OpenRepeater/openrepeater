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



//$customCSS = "logtail.css"; // "file1.css, file2.css, ... "
//$customJS = "logtail.js"; // "file1.js, file2.js, ... "

include_once("includes/get_modules.php");
?>

		<?php
		if ($_GET['deactivate']) {
			$module_id = $_GET['deactivate'];
			$module[$module_id]['moduleEnabled'] = 0;

			$sql = 'UPDATE modules SET moduleEnabled=0 WHERE moduleKey='.$module_id;
			$dbConnection->exec($sql);

			/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
			$memcache_obj = new Memcache;
			$memcache_obj->connect('localhost', 11211);
			$memcache_obj->set('update_settings_flag', 1, false, 0);

			$msgText = "The ".$module[$module_id]['moduleName']." Module has been successfully <strong>deactivated</strong>.";
			$alert = '<div class="alert alert-success">'.$msgText.'</div>';
		}

		if ($_GET['activate']) {
			$module_id = $_GET['activate'];
			$module[$module_id]['moduleEnabled'] = 1;

			$sql = 'UPDATE modules SET moduleEnabled=1 WHERE moduleKey='.$module_id;
			$dbConnection->exec($sql);

			/* SET FLAG TO LET REPEATER PROGRAM KNOW TO RELOAD SETTINGS */
			$memcache_obj = new Memcache;
			$memcache_obj->connect('localhost', 11211);
			$memcache_obj->set('update_settings_flag', 1, false, 0);

			$msgText = "The ".$module[$module_id]['moduleName']." Module has been successfully <strong>activated</strong>.";
			$alert = '<div class="alert alert-success">'.$msgText.'</div>';
		}

		if ($_GET['settings']) {
			// If modules settings page is request, display that if it exist
			$module_id = $_GET['settings'];

			$mod_settings_file = 'modules/'.$module[$module_id]['svxlinkName'].'/settings.php';
			if (file_exists($mod_settings_file)) {
				$mod_css_file = 'modules/'.$module[$module_id]['svxlinkName'].'/module.css';
				$mod_js_file = 'modules/'.$module[$module_id]['svxlinkName'].'/module.js';

				if (file_exists($mod_css_file)) {
					$moduleCSS = "../" . $mod_css_file; 
				}

				if (file_exists($mod_js_file)) {
					$moduleJS = "../" . $mod_js_file; 
				}

				$pageTitle = $module[$module_id]['moduleName'] . " Module";
				include('includes/header.php');
				include($mod_settings_file);

			} else {
				$pageTitle = "Modules";
				include('includes/header.php');
			    echo "<h2>No Settings Page found.</h2>";
			}
			
			
		} else {
			//Otherwise show all modules
			$pageTitle = "Modules";
			include('includes/header.php');
		?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Modules</h2>
					</div>
					<div class="box-content">
					
					
					
					
					
					<?php if ($module) { ?>
						<table class="table table-striped">
							<thead>
								<tr>
									<th><div style="width:200px">Module</div></th>
									<th>Description</th>
								</tr>
							</thead>
							
							<tbody>
								<?php
								foreach($module as $cur_mod) { 
									$mod_ini_file = 'modules/'.$cur_mod['svxlinkName'].'/info.ini';
									$mod_settings_file = 'modules/'.$cur_mod['svxlinkName'].'/settings.php';
									$dtmf_help_file = 'modules/'.$cur_mod['svxlinkName'].'/dtmf.php';
								?>
								<tr>
									<td>
										<div><h3><?php echo $cur_mod['moduleName']; ?> (<?php echo $cur_mod['svxlinkID']; ?>#)</h3></div>
										<div><?php if ($cur_mod['moduleEnabled']==1) { echo '<span class="label-success label label-default">Active</span>'; } else {echo '<span class="label-default label">Inactive</span>';} ?></div>
										<div>
											<?php
												if ($cur_mod['moduleEnabled']==1) {
													echo '<a href="?deactivate='.$cur_mod['moduleKey'].'">Deactivate</a>';
												} else {
													echo '<a href="?activate='.$cur_mod['moduleKey'].'">Activate</a>';													
												}
											?>

											<?php
												if ($cur_mod['moduleEnabled']==1 && file_exists($mod_settings_file)) {
													echo ' | <a href="modules.php?settings='.$cur_mod['moduleKey'].'">Settings</a>';
												}
											?>

											<?php
												if ($cur_mod['moduleEnabled']==1 && file_exists($dtmf_help_file)) {
													echo ' | <a href="dtmf.php#'.$cur_mod['svxlinkName'].'">DTMF</a>';
												}
											?>
										</div>
									</td>
									<td>
										<?php
										if (file_exists($mod_ini_file)) {
											$mod_ini_array = parse_ini_file($mod_ini_file, true);
											if ($mod_ini_array['Module_Info']['mod_desc']) {
												echo $mod_ini_array['Module_Info']['mod_desc'];
											} else {
											    echo "<em>(No Information)</em>";
											}
										} else {
										    echo "<em>(No Information)</em>";
										}
										?>
									</td>
								</tr>
								<?php } /* End Current Module */ ?>
						
							</tbody>
						</table>
					
					<?php } else {
						echo "No Modules Installed.";
					}
					?>

					</div>
				</div><!--/span-->
			</div><!--/row-->
		<?php } ?>


<?php include('includes/footer.php'); 
$dbConnection->close();
?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>