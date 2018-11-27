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

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

$Database = new Database();
$ModulesClass = new Modules();

?>

		<?php
		if ( isset($_POST['updateModuleSettings']) ) {
			$results = $ModulesClass->save_module_settings($_POST);
			$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
		}

		if ( isset($_GET['deactivate']) ) {
			$module_id = $_GET['deactivate'];
			$results = $ModulesClass->deactivateMod($module_id);
			$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
		}

		if ( isset($_GET['activate']) ) {
			$module_id = $_GET['activate'];
			$results = $ModulesClass->activateMod($module_id);
			$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
		}

		if ( isset($_GET['settings']) ) {
			// If modules settings page is request, display that if it exist
			echo $ModulesClass->display_settings($_GET['settings']);
									
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
					
					<?php echo $ModulesClass->display_all(); ?>
					
					</div>
				</div><!--/span-->
			</div><!--/row-->
		<?php } ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>