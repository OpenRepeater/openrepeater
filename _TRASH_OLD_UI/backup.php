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
$BackupRestore = new BackupRestore();

if ( isset( $_POST['validate_restore'] ) ) {
	$results = $BackupRestore->pre_restore_validation($_POST['validate_restore']);
	exit; // This is an AJAX request

} else if ( isset( $_POST['restore'] ) ) {
	$results = $BackupRestore->restore_backup();
	$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

} else if ( isset( $_POST['delete'] ) ) {
	$results = $BackupRestore->deleteBackup($_POST['deleteSelectedFile']);
	$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';

} else if ( isset( $_POST['createBackup'] ) ) {
	$results = $BackupRestore->create_backup();
	$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
} else if ( isset( $_POST['upload_file'] ) ) {
	$results = $BackupRestore->upload_backup_files($_FILES['file']);
	$alert = '<div class="alert alert-'.$results['msgType'].'">'.$results['msgText'].'</div>';
}
?>

<?php
$pageTitle = "Backup & Restore"; 

$customJS = "page-backup.js"; // "file1.js, file2.js, ... "
$customCSS = "page-backup.css";

include('includes/header.php');
?>

			<?php if (isset($alert)) { echo $alert; } ?>

			<!-- Button triggered modal -->
			<button class="btn backup" data-toggle="modal" data-target="#createBackup"><i class="icon-hdd"></i> Create Backup</button>

			<!-- Button triggered modal -->
			<button class="btn upload" data-toggle="modal" data-target="#uploadFile"><i class="icon-arrow-up"></i> Upload Offline Backup</button>

			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-th-list"></i> Local Backup Library</h2>
					</div>
					<div class="box-content">

					<?php $BackupRestore->display_backup_files(); ?>

					</div>
				</div><!--/span-->
			</div><!--/row-->



<!-- Modal - CREATE BACKUP DIALOG -->
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="modal fade" id="createBackup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h3 class="modal-title" id="myModalLabel">Create Backup</h3>
      </div>
      <div class="modal-body">
		<p>Are you ready to create a backup now? Once created, the backup will show up in the Local Backup Library on this controller. From there you can restore the backup. This is useful to make snapshots to use as a restore point. It is also recommend that you download some backups as well to use in the event of a failure such as a corrupted disk.</p>
      </div>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success" name="createBackup"><i class="icon-hdd icon-white"></i> Create Backup</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form>


<!-- Modal - UPLOAD DIALOG -->
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<h3 class="modal-title" id="myModalLabel">Upload an Offline Backup</h3>
      </div>
      <div class="modal-body">
		<p>Upload an offline OpenRepeater backup file. These are special package files that end with an ".orp" extension. These can be either copies that you've previously download from this controller for offline archiving or backups that you wish to transfer from another OpenRepeater controller. Once uploaded, they will appear in the Local Backup Library where you can then initiate a restore.</p>
		<input type="file" name="file[]" id="file" accept=".orp" required>
      </div>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" class="btn btn-success"name="upload_file"><i class="icon-arrow-up icon-white"></i> Upload</button>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form>


<!-- Modal - RESTORE DIALOG -->
<form id="restoreForm" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="modal fade" id="restoreFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title" id="myModalLabel">Restore Backup</h3>
      </div>
      <div class="modal-body">
	  	<center>
		  	<h4 style="text-align: center">Please Wait</h4>
		  	<img src="theme/img/ajax-loaders/ajax-loader-7.gif" align="middle">
	  	</center>
      </div>
      <div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	<button type="submit" id="restoreButton" class="btn btn-danger" name="restore" disabled><i class="icon-refresh icon-white"></i> Restore</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form>

<form class="form-inline" role="form" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="validateRestoreFile">
	<input type="hidden" id="validate_restore" name="validate_restore" value="">
</form>
<!-- /.modal -->



<!-- Modal - DELETE DIALOG -->
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="modal fade" id="deleteFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3 class="modal-title" id="myModalLabel">Delete Backup?</h3>
      </div>
      <div class="modal-body">
	Are you sure that you want to delete the backup <strong>'<span>FILENAME</span>'</strong>? This cannot be undone!
	<input type="hidden" id="deleteSelectedFile" name="deleteSelectedFile" value="">
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

    
<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>