<?php
$customJS = 'page-courtesy.js, orp-audio-player.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-courtesy.css, orp-audio-player.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
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
					
<?php $temp_array = ['temporary/courtesy_tones/3_Down.wav', 'temporary/courtesy_tones/3_Up.wav', 'temporary/courtesy_tones/3_Up_(Chord).wav', 'temporary/courtesy_tones/4_Down.wav', 'temporary/courtesy_tones/4_Up.wav', 'temporary/courtesy_tones/Apollo.wav', 'temporary/courtesy_tones/Bee_Boo.wav', 'temporary/courtesy_tones/Beep.wav', 'temporary/courtesy_tones/Blast_Off.wav', 'temporary/courtesy_tones/Boop.wav', 'temporary/courtesy_tones/Bumble_Bee.wav', 'temporary/courtesy_tones/Comet.wav', 'temporary/courtesy_tones/Duncecap.wav', 'temporary/courtesy_tones/Moonbounce.wav', 'temporary/courtesy_tones/NBC_(Fast).wav', 'temporary/courtesy_tones/NBC_(Medium).wav', 'temporary/courtesy_tones/NBC_(Slow).wav', 'temporary/courtesy_tones/Nextel.wav', 'temporary/courtesy_tones/Over_Here.wav', 'temporary/courtesy_tones/Piano_Chord.wav', 'temporary/courtesy_tones/RC210_Number_01.wav', 'temporary/courtesy_tones/RC210_Number_02.wav', 'temporary/courtesy_tones/RC210_Number_03.wav', 'temporary/courtesy_tones/RC210_Number_04.wav', 'temporary/courtesy_tones/RC210_Number_05.wav', 'temporary/courtesy_tones/RC210_Number_06.wav', 'temporary/courtesy_tones/RC210_Number_07.wav', 'temporary/courtesy_tones/RC210_Number_08.wav', 'temporary/courtesy_tones/RC210_Number_09.wav', 'temporary/courtesy_tones/RC210_Number_10.wav', 'temporary/courtesy_tones/Sat_Pass.wav', 'temporary/courtesy_tones/Shooting_Star.wav', 'temporary/courtesy_tones/Stardust.wav', 'temporary/courtesy_tones/Target.wav', 'temporary/courtesy_tones/TelRing.wav', 'temporary/courtesy_tones/Tumbleweed.wav', 'temporary/courtesy_tones/Waterdrop.wav', 'temporary/courtesy_tones/Whippoorwhill.wav', 'temporary/courtesy_tones/XP_Error.wav', 'temporary/courtesy_tones/XPok.wav', 'temporary/courtesy_tones/Yellow_Jacket.wav']; ?>
					
					
                    <table id="datatable-responsive" class="audio-table table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
					  <thead>
						  <tr>
							  <th>Name</th>
							  <th class="button_grp">Actions</th>
						  </tr>
					  </thead>   

                      <tbody>

						<?php
							foreach($temp_array as $temp_key => $temp_file) { 
								$temp_name = str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) );
						?>

							<tr id="<?=$temp_key?>" data-row-name="<?=str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) )?>" data-row-file="<?=pathinfo($temp_file, PATHINFO_FILENAME)?>">
								<td>
									<div id="player<?=$temp_key?>" class="orp_player">
									    <audio preload="true">
									        <source src="<?=$temp_file?>">
									    </audio>
									    <button class="play"><span></span></button>
									</div>

									<span class="audio_name"><?=$temp_name?></span>
								</td>

								<td>			
									<div class="btn-group btn-group-sm audio-actions" role="group">
										<button class="select_file btn btn-success" type="button"><i class="fa fa-repeat"></i> <?=_('Select')?></button>
										<button class="rename_file btn btn-secondary" type="button"><i class="fa fa-repeat"></i> <?=_('Rename')?></button>
										<button class="delete_file btn btn-danger" type="button"><i class="fa fa-remove"></i> <?=_('Delete')?></button>
									</div>
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
	var modal_RenameTitle = '<?=_('Rename Tone')?>';
	var modal_RenameBody = '<p><?=_('What type of port do you wish to add?')?></p><select id="addPortType" name="addPortType" class="form-control"><option value="local" selected><?=_('Local Analog Port')?></option></select>';

	var modal_DeleteTitle = '<?=_('Delete Tone')?>';
	var modal_DeleteBody = '<p><?=_('What type of port do you wish to add?')?></p><select id="addPortType" name="addPortType" class="form-control"><option value="local" selected><?=_('Local Analog Port')?></option></select>';

</script>


<!-- Upload Dialog Modal -->
<script>
	var modal_UploadTitle = '<?=_('Upload Tone')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload your own custom courtesy tone files. The file should be in MP3 or WAV format and should resemble a short beep.')?>';
	var uploadSuccessTitle = '<?=_('Upload Complete')?>';
	var uploadSuccessText = '<?=_('New courtesy tone was successfully uploaded to library.')?>';
</script>

<?php include('includes/footer.php'); ?>