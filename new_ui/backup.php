<?php
$customJS = 'page-backup.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-backup.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
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


<!-- table table-striped dt-responsive nowrap dataTable no-footer dtr-inline -->
<!-- table table-striped table-bordered dt-responsive nowrap -->
                    <table id="backup-table-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th><?=_('Name')?></th>
                          <th><?=_('Date')?></th>
                          <th><?=_('Size')?></th>
                          <th><?=_('Action')?></th>
                        </tr>
                      </thead>
                      <tbody>
						<?php for ($k = 0 ; $k < 4; $k++){ ?>
                        <tr>
                          <td><strong>n3mbh_2019-11-30_11-45-08.orp</strong></td>
                          <td>November 30 2019 11:45:14</td>
                          <td>721.87 KB</td>
                          <td>
			                  <ul class="nav nav-pills" role="tablist">
			                    <li role="presentation" class="dropdown">
			                      <a id="drop6" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false"><i class="fa fa-cog"></i> <span class="caret"></span></a>
			                      <ul id="menu3" class="dropdown-menu animated fadeInDown" role="menu" aria-labelledby="drop6">
			                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-refresh"></i> <?=_('Restore')?></a>
			                        </li>
			                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-download"></i> <?=_('Download')?></a>
			                        </li>
			                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-remove"></i> <?=_('Delete')?></a>
			                        </li>
			                      </ul>
			                    </li>
			                  </ul>
                          </td>
                        </tr>
						<?php } ?>
                      </tbody>
                    </table>

                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<script>
	var modal_CreateBackupTitle = '<?=_('Create Backup')?>';
	var modal_CreateBackupBody = '<p><?=_('Are you ready to create a backup now? Once created, the backup will show up in the Local Backup Library on this controller. From there you can restore the backup. This is useful to make snapshots to use as a restore point. It is also recommend that you download some backups as well to use in the event of a failure such as a corrupted drive.')?></p>';
	var modal_CreateBackupBtnOKText = '<?=_('Create Backup')?>';
</script>


<!-- Upload Dialog Modal -->
<script>
	var modal_UploadTitle = '<?=_('Upload a Restore File')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload an offline OpenRepeater backup file. These are special package files that end with an ".orp" extension. These can be either copies that you have previously download from this controller for offline archiving or backups that you wish to transfer from another OpenRepeater controller. Once uploaded, they will appear in the Local Backup Library where you can then initiate a restore.')?>';
	var uploadSuccessTitle = '<?=_('Restore File Added')?>';
	var uploadSuccessText = '<?=_('A restore file was successfully uploaded and added to the backup library. You may now use this file to restore from.')?>';
</script>

<?php include('includes/footer.php'); ?>