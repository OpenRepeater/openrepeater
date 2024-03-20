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

		if ( isset( $_POST['upload_file'] ) ) {
			$results = $ModulesClass->upload_module($_FILES['file']);
			$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
		}

		if ( isset( $_POST['delete'] ) ) {
			$results = $ModulesClass->remove_module($_POST['deleteSelectedModule']);
			$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
		}

		if ( isset($_GET['settings']) ) {
			// If modules settings page is request, display that if it exist
			echo $ModulesClass->display_settings($_GET['settings']);
									
		} else {
			//Otherwise show all modules
			$pageTitle = "Modules";
			$customJS = "page-modules.js";
			$customCSS = "page-modules.css";
			include('includes/header.php');
		?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<button class="btn upload" data-toggle="modal" data-target="#uploadFile">
				<i class="icon-arrow-up"></i> Upload &amp; Install New Module
			</button>

			<button class="btn find_new" onclick=" window.open('https://openrepeater.com/downloads/modules/','_blank')">
				<i class="icon-search"></i> Find Modules on OpenRepeater.com
			</button>

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

			<!-- Modal - UPLOAD DIALOG -->
			<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
			<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
				<h3 class="modal-title" id="myModalLabel">Upload Module</h3>
			      </div>
			      <div class="modal-body">
					<p>Upload an OpenRepeater module to add additional functionality. These are specially packaged SVXLink Modules along with the required sound files and user interface components zipped up in an easy to install file. Modules must be compressed with a ".zip" extension. Upon upload the modules will be unzipped, verified, and installed. You will then need to active the module, configure any settings (if applicable), and rebuild/restart the repeater.</p>
					<input type="file" name="file[]" id="file" accept=".zip" required>
			      </div>
			      <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success"name="upload_file"><i class="icon-arrow-up icon-white"></i> Upload</button>
			
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div>
			</form>


			<!-- Modal - DELETE DIALOG -->
			<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="modal fade" id="deleteModule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="myModalLabel">Delete Module?</h3>
			      </div>
			      <div class="modal-body">
				Are you sure that you want to delete the <strong><span>MODULE_NAME</span></strong> module? This cannot be undone!
				<input type="hidden" id="deleteSelectedModule" name="deleteSelectedModule" value="">
			      </div>
			      <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-danger" name="delete"><i class="icon-trash icon-white"></i> Delete</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div>
			</form>
			<!-- /.modal -->


		<?php
			include('includes/footer.php');
			}
		?>



<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>