<?php
/*
$customJS = 'dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'upload-file.css'; // 'file1.css, file2.css, ... '
*/

$customJS = 'page-identification.js, orp-audio-player.js, dropzone.js, upload-file.js, morse-resampler.js, morse-XAudioServer.js, morse.js, morse-main.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-identification.css, orp-audio-player.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>




<?php 
	$temp_path = 'temporary/identification/';
	$temp_array = ['Sample_Short_ID_Clip.wav', 'Sample_Long_ID_Clip.wav'];
	$callSign = 'W1AW';
?>




        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-volume-up"></i> <?=_('Identification')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">



              <div class="col-md-6 col-xs-12">


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Short ID Settings')?></h4></div>

                  <div class="x_content">

                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <div class="btn-group btn-group-sm id-type" data-toggle="buttons">
                          <label class="btn btn-default"><input type="radio" name="ID_Short_Mode" id="ID_Short_Mode1"  value="disabled"><?=_('Disabled')?></label>
                          <label class="btn btn-default"><input type="radio" name="ID_Short_Mode" id="ID_Short_Mode2" value="morse"><?=_('Morse Only')?></label>
                          <label class="btn btn-default"><input type="radio" name="ID_Short_Mode" id="ID_Short_Mode3" value="voice" ><?=_('Basic Voice')?></label>
                          <label class="btn btn-default active"><input type="radio" name="ID_Short_Mode" id="ID_Short_Mode4" value="custom" checked><?=_('Custom')?></label>
                        </div>
                      </div>

                      <div id="ID_Short_Interval_Grp" class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Short ID Time')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" id="ID_Short_IntervalMin" class="form-control" value="60" placeholder="<?=_('Minutes')?>">
                        </div>
                      </div>

                      <div id="ID_Short_Custom_Audio_Grp" class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Audio File')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <select id="ID_Short_Custom_Audio" class="form-control">
							<?php
								foreach($temp_array as $temp_key => $temp_file) { 
									$temp_name = str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) );
									echo '<option value="'.$temp_file.'">'.$temp_name.'</option>';
								}
							?>
						  </select>
                        </div>
                      </div>

                      <div id="ID_After_TX_Grp" class="form-group col-md-6 col-sm-6 col-xs-12">
                        <div class="gray-box">
                        <label class="control-label col-md-9"><?=_('ID after TX')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-3">
                          <input id="ID_After_TX" type="checkbox" class="js-switch" checked /> 
                        </div>
                        </div>
                      </div>

                      <div id="ID_Short_Append_Morse_Grp" class="form-group col-md-6 col-sm-6 col-xs-12">
                        <div class="gray-box">
                        <label class="control-label col-md-9"><?=_('Append Morse ID')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-3">
                          <input id="ShortMorse" type="checkbox" class="js-switch" checked /> 
                        </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>





                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Long ID Settings')?></h4></div>

                  <div class="x_content">

                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <div class="btn-group btn-group-sm id-type" data-toggle="buttons">
                          <label class="btn btn-default"><input type="radio" name="ID_Long_Mode" id="ID_Long_Mode1"  value="disabled"><?=_('Disabled')?></label>
                          <label class="btn btn-default"><input type="radio" name="ID_Long_Mode" id="ID_Long_Mode2" value="morse"><?=_('Morse Only')?></label>
                          <label class="btn btn-default"><input type="radio" name="ID_Long_Mode" id="ID_Long_Mode3" value="voice" ><?=_('Basic Voice')?></label>
                          <label class="btn btn-default active"><input type="radio" name="ID_Long_Mode" id="ID_Long_Mode4" value="custom" checked><?=_('Custom')?></label>
                        </div>
                      </div>

                      <div id="ID_Long_Interval_Grp" class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Long ID Time')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of minutes between long identifications. The purpose of the long identification is to transmit some more information about the station. A good value for a repeater is every 60 minutes.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" id="ID_Long_IntervalMin" class="form-control" value="60" placeholder="<?=_('Minutes')?>">
                        </div>
                      </div>

                      <div id="ID_Long_Custom_Audio_Grp" class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Audio File')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <select id="ID_Long_Custom_Audio" class="form-control">
							<?php
								foreach($temp_array as $temp_key => $temp_file) { 
									$temp_name = str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) );
									echo '<option value="'.$temp_file.'">'.$temp_name.'</option>';
								}
							?>
						  </select>
                        </div>
                      </div>



<!-- <div class="divider"></div> -->

                      <div id="ID_Long_Annc_Time_Grp" class="form-group col-md-6 col-sm-6 col-xs-12">
                        <div class="gray-box">
                        <label class="control-label col-md-9"><?=_('Announce Time')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-3">
                          <input id="LongTime" type="checkbox" class="js-switch" checked /> 
                        </div>
                        </div>
                      </div>

                      <div id="ID_Long_Append_Morse_Grp" class="form-group col-md-6 col-sm-6 col-xs-12">
                        <div class="gray-box">
                        <label class="control-label col-md-9"><?=_('Append Morse ID')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('...')?>"></i>
                        </label>
                        <div class="col-md-3">
                          <input id="LongMorse" type="checkbox" class="js-switch" checked /> 
                        </div>
                        </div>
                      </div>


                    </form>
                  </div>
                </div>



                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Global Morse ID Settings')?></h4></div>

                  <div class="x_content">

                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-5"><?=_('Morse Callsign')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Use the following callsign variation for ALL morse code identification.')?>"></i>
                        </label>
                        <div class="btn-group btn-group-sm col-md-7" data-toggle="buttons">
                          <label class="btn btn-default"><input type="radio" name="ID_Morse_Suffix" id="morseSuffix1" value=""><?=$callSign?></label>
                          <label class="btn btn-default active"><input type="radio" name="ID_Morse_Suffix" id="morseSuffix2" value="/R" checked><?=$callSign?><strong>/R</strong></label>
                          <label class="btn btn-default"><input type="radio" name="ID_Morse_Suffix" id="morseSuffix3" value="/RPT"><?=$callSign?><strong>/RPT</strong></label>
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-5"><?=_('Global speed and tone')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Set the speed (WPM) and tone (Hz) of ALL morse code identification.')?>"></i>
                        </label>

                        <div class="col-md-7">
	                        <div class="col-md-12">
								<div class="knob_wrapper">
									<input class="knob" name="ID_Morse_WPM" id="ID_Morse_WPM" data-width="130" data-height="85" data-min="5" data-max="35" data-step="5" data-angleOffset=-90 data-angleArc=180 data-fgColor="#8dc63f" value="25">
									<label><?=_('WPM')?></label>									
								</div>

								<div class="knob_wrapper">
									<input class="knob" name="ID_Morse_Pitch" id="ID_Morse_Pitch" data-width="130" data-height="85" data-min="400" data-max="1200" data-step="100" data-angleOffset=-90 data-angleArc=180 data-fgColor="#8dc63f" value="700">
									<label><?=_('Pitch (Hz)')?></label>
								</div>
	                        </div>

	                        <div class="col-md-12">
								<button id="morsePreview" type="button" class="btn btn-primary"><i class="fa fa-play"></i> <?=_('Preview')?></button>
	                        </div>






							<input type="hidden" id="callSign" value="<?=$callSign?>">
							<input type="hidden" id="morseOutput" value="">



                        </div>

                      </div>

                    </form>
                  </div>

                </div>

              </div>






              <div class="col-md-6  col-xs-12">



                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Identification Clip Library')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success upload_file" data-upload-type="identification"><i class="fa fa-upload"></i> <?=_('Upload ID Clip')?></button>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					
                    <table id="id_library" class="audio-table table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
					  <thead>
						  <tr>
							  <th><?=_('Name')?></th>
							  <th class="button_grp" style="text-align: right;"><?=_('Actions')?></th>
						  </tr>
					  </thead>   

                      <tbody>

						<?php
							foreach($temp_array as $temp_key => $temp_file) { 
								$temp_name = str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) );
						?>

							<tr id="clip<?=$temp_key?>" data-row-name="<?=str_replace( '_', ' ', pathinfo($temp_file, PATHINFO_FILENAME) )?>" data-row-file="<?=$temp_file?>">
								<td>
									<div id="player<?=$temp_key?>" class="orp_player">
									    <audio preload="true">
									        <source src="<?=$temp_path . $temp_file?>">
									    </audio>
									    <button class="play"><span></span></button>
									</div>

									<span class="audio_name"><?=$temp_name?></span>
								</td>

								<td>			
									<div class="btn-group btn-group-sm audio-actions" role="group">
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
	var modal_RenameTitle = '<?=_('Rename Clip')?>';
	var modal_RenameBody = '<p><?=_('Please enter the new file name')?></p>';
	var modal_RenameBtnOK = '<?=_('Rename')?>';

	var modal_DeleteTitle = '<?=_('Delete Clip')?>';
	var modal_DeleteBody = '<p><?=_('Are you sure that you wish to delete the following clip?')?></p>';
	var modal_DeleteBtnOK = '<?=_('Delete')?>';
	var modal_DeleteBtnOKclass = 'btn-danger'

	var modal_DeleteErrorTitle = '<?=_('Delete Error')?>';
	var modal_DeleteErrorBody = '<p><?=_('You cannot delete the following clip as it is in use.')?></p>';
	var modal_DeleteErrorBtnOK = '<?=_('Dismiss')?>';
</script>


<!-- Upload Dialog Modal -->
<script>
	var modal_UploadTitle = '<?=_('Upload Identification')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload your own custom recorded audio files for identification. The file should be in MP3 or WAV format and any excess dead air should be trimmed off of the clip and audio levels normalized. DO NOT UPLOAD FILES THAT CONTAIN MUSIC, CLIPS OF MUSIC, OR OTHER COPYRIGHTED MATERIAL...IT IS ILLEGAL.')?>';
	var uploadSuccessTitle = '<?=_('Upload Complete')?>';
	var uploadSuccessText = '<?=_('New custom identification was successfully uploaded to library.')?>';
</script>

<?php include('includes/footer.php'); ?>