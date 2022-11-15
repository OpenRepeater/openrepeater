<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: index.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------

$customJS = 'page-backup.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-backup.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
$BackupRestore = new BackupRestore();
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-database"></i> <?=_('Backup & Restore')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Available Snapshots')?> 
						<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Snapshots are backups that are created and stored on the OpenRepeater controller. With snapshots you can make a backup at a particular point and time and later use that snapshot to restore the system to that point. Other things you can do with snapshots include: downloading snapshots to another computer for safe keeping, uploading offline snapshots to the local system to use to restore, or use snapshots to transfer settings to another OpenRepeater controller.')?>"></i>
                    </h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success createBackup"><i class="fa fa-database"></i> <?=_('Create Backup')?></button>
                      <button type="button" class="btn btn-success upload_file" data-upload-type="restore"><i class="fa fa-upload"></i> <?=_('Upload Backup')?></button>
                    </div>
                    <div class="clearfix"></div>
				  </div>
                  
                  <div class="x_content">

                    <table id="backup-table-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th><?=_('Name')?></th>
                          <th data-type="@data-sort"><?=_('Date')?></th>
                          <th data-type="@data-sort"><?=_('Size')?></th>
                          <th><?=_('Action')?></th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>

                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<? ######################################################################### ?>

<script id="backupRowTemplate" type = "text/template">
    <tr id="backupRow%%INDEX%%" class="backupRow" data-backup-number="%%INDEX%%" data-backup-file="%%FILENAME%%" data-file-size="%%RAWSIZE%%">
      <td><strong>%%FILENAME%%</strong></td>
      <td data-sort="%%ISODATE%%"><span class="dateCol" title="%%FULLDATE%%"><i class="fa fa-calendar"></i> %%DATE%%</span></td>
      <td data-sort="%%RAWSIZE%%"><i class="fa fa-hdd-o"></i> %%SIZE%%</td>
      <td>
          <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="dropdown">
              <a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false"><i class="fa fa-cog"></i> <span class="caret"></span></a>
              <ul id="menu3" class="dropdown-menu animated fadeInDown" role="menu" aria-labelledby="drop6">
                <li role="presentation"><a role="menuitem" class="restoreBackup" tabindex="-1" href="#"><i class="fa fa-refresh"></i> <?=_('Restore')?></a>
                </li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="%%URL%%"><i class="fa fa-download"></i> <?=_('Download')?></a>
                </li>
                <li role="presentation"><a role="menuitem" class="deleteBackup" tabindex="-1"><i class="fa fa-remove"></i> <?=_('Delete')?></a>
                </li>
              </ul>
            </li>
          </ul>
      </td>
    </tr>
</script>


<script>
	var backupList = '<?= $BackupRestore->getBackupFilesJSON() ?>';
	var fileCountLabel = '<?= _('Total Files') ?>';
	var allBackupsSizeLabel = '<?= _('Total Space Used') ?>';

	var modal_DeleteBackupTitle = '<?= _('Delete Backup') ?>';
	var modal_DeleteBackupBody = '<?= _('Are you sure you want to delete this backup?') ?>';
	var modal_DeleteBackupBtnOK = '<?= _('Delete Forever') ?>';
	var modal_DeleteBackupProgressTitle = '<?= _('Deleting Backup') ?>';
	var modal_DeleteBackupNotifyTitle = '<?= _('Backup Deleted') ?>';
	var modal_DeleteBackupNotifyDesc = '<?= _('The backup has been successfully deleted.') ?>';

	var modal_CreateBackupTitle = '<?=_('Create Backup')?>';
	var modal_CreateBackupBody = '<p><?=_('Are you ready to create a backup now? Once created, the backup will show up in the Local Backup Library on this controller. From there you can restore the backup. This is useful to make snapshots to use as a restore point. It is also recommend that you download some backups as well to use in the event of a failure such as a corrupted drive.')?></p>';
	var modal_CreateBackupBtnOKText = '<?=_('Create Backup')?>';
	var modal_CreateBackupProgressTitle = '<?= _('Creating Backup') ?>';
	var modal_CreateBackupNotifyTitle = '<?= _('Backup Created') ?>';
	var modal_CreateBackupNotifyDesc = '<?= _('The backup was created successfully.') ?>';

	var modal_RestoreValidationTitle = '<?=_('Backup Validation Successful')?>';
	var modal_RestoreValidationBody = '<?=_('The backup has been validated and matches the current version of OpenRepeater. You can proceed with the restore process. This will overwrite all your settings with those in the backup.')?>';
	var modal_RestoreDetailsHeading = '<?=_('Backup Details')?>';
	var modal_RestoreDetailsVersion = '<?=_('Version')?>';
	var modal_RestoreDetailsDate = '<?=_('Date')?>';
	var modal_RestoreDetailsCallsign = '<?=_('Callsign')?>';
	var modal_RestoreBtnOKText = '<?=_('Restore')?>';
	var modal_RestoreProgressTitle = '<?=_('Restore in Progress')?>';
	var modal_RestoreValidationMismatchTitle = '<?=_('Version Mismatch')?>';
	var modal_RestoreValidationMismatchBody = '<?=_('The version of OpenRepeater used to make this backup does not match the current version of OpenRepeater that you are trying to restore to. You may continue with the restore, but unexpected results may occur. This could be as simple as some missing data used by newer functionality added between versions. You may wish to make a backup of your current configuration before proceeding...just to be safe.')?>';
	var modal_RestoreValidationFailedTitle = '<?=_('Validation Failure')?>';
	var modal_RestoreValidationFailedBody = '<?=_('OpenRepeater was unable to validate this file so restoring from this backup is not possible. This could be due to a corruption of the data, or the original backup was not successfully created.')?>';
	var modal_RestoreNotifyTitle = '<?=_('Restore Successful')?>';
	var modal_RestoreNotifyDesc = '<?=_('Backup was successfully restored. Remember to rebuild configuration.')?>';

	var modal_UploadTitle = '<?=_('Upload a Restore File')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload an offline OpenRepeater backup file. These are special package files that end with an ".orp" extension. These can be either copies that you have previously download from this controller for offline archiving or backups that you wish to transfer from another OpenRepeater controller. Once uploaded, they will appear in the Local Backup Library where you can then initiate a restore.')?>';
	var uploadSuccessTitle = '<?=_('Restore File Added')?>';
	var uploadSuccessText = '<?=_('A restore file was successfully uploaded and added to the backup library. You may now use this file to restore from.')?>';
</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>