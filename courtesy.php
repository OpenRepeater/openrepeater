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


$customJS = 'page-courtesy.js, orp-audio-player.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-courtesy.css, orp-audio-player.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');

$AudioFiles = new AudioFiles();
$courtesyToneAudio = $AudioFiles->get_audio_filesJSON('courtesy_tones');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-bell"></i> <?=_('Courtesy Tones')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Library')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success upload_file" data-upload-type="courtesy_tone"><i class="fa fa-upload"></i> <?=_('Upload Tone')?></button>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <table id="courtesy-datatable-responsive" class="audio-table table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
					  <thead>
						  <tr>
							  <th><?=_('Name')?></th>
							  <th class="button_grp" style="text-align: right;"><?=_('Actions')?></th>
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

<script id="courtesyRowTemplate" type = "text/template">
	<tr id="clip%%INDEX%%" data-row-name="%%FILE_LABEL%%" data-row-file="%%FILE_NAME%%">
		<td>
			<div id="player%%INDEX%%" class="orp_player">
			    <audio preload="true">
			        <source src="%%FILE_URL%%">
			    </audio>
			    <button class="play"><span></span></button>
			</div>

			<span class="audio_name">%%FILE_LABEL%%</span>
		</td>

		<td>			
			<div class="btn-group btn-group-sm audio-actions" role="group">
				<button class="select_file btn btn-success" type="button"><i class="fa fa-repeat"></i> <?=_('Select')?></button>
				<button class="rename_file btn btn-secondary" type="button"><i class="fa fa-repeat"></i> <?=_('Rename')?></button>
				<button class="delete_file btn btn-danger" type="button"><i class="fa fa-remove"></i> <?=_('Delete')?></button>
			</div>
		</td>
	</tr>
</script>


<script>
	var courtesyToneAudio = '<?= $courtesyToneAudio ?>';
	var currentCourtesyTone = '<?= $settings['courtesy'] ?>';

	var modal_RenameTitle = '<?=_('Rename Tone')?>';
	var modal_RenameBody = '<p><?=_('Please enter the new file name')?></p>';
	var modal_RenamePlaceholder = '<?=_('New File Name')?>';
	var modal_RenameBtnOK = '<?=_('Rename')?>';
	var modal_RenameProgressTitle = '<?= _('Renaming Courtesy Tone') ?>';
	var modal_RenameNotifyTitle = '<?= _('Tone Renamed') ?>';
	var modal_RenameNotifyDesc = '<?= _('The courtesy tone has been successfully renamed.') ?>';

	var modal_DeleteCourtesyTitle = '<?= _('Delete Tone') ?>';
	var modal_DeleteCourtesyBody = '<?= _('Are you sure you want to delete this courtesy tone?') ?>';
	var modal_DeleteCourtesyBtnOK = '<?= _('Delete Forever') ?>';
	var modal_DeleteCourtesyProgressTitle = '<?= _('Deleting Courtesy Tone') ?>';
	var modal_DeleteCourtesyNotifyTitle = '<?= _('Courtesy Tone Deleted') ?>';
	var modal_DeleteCourtesyNotifyDesc = '<?= _('The courtesy tone has been successfully deleted.') ?>';

	var modal_UploadTitle = '<?=_('Upload Tone')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload your own custom courtesy tone files. The file should be in MP3 or WAV format and should resemble a short beep.')?>';
	var uploadSuccessTitle = '<?=_('Upload Complete')?>';
	var uploadSuccessText = '<?=_('New courtesy tone was successfully uploaded to library.')?>';
</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>