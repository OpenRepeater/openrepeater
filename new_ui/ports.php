<?php include('includes/fakeDB.php'); ?>

<?php
$logicCommonArray = ['MODULES','CALLSIGN','SHORT_VOICE_ID_ENABLE','SHORT_CW_ID_ENABLE','SHORT_ANNOUNCE_ENABLE','SHORT_ANNOUNCE_FILE','LONG_VOICE_ID_ENABLE','LONG_CW_ID_ENABLE','LONG_ANNOUNCE_ENABLE','LONG_ANNOUNCE_FILE','CW_AMP','CW_PITCH','CW_CPM','CW_WPM','PHONETIC_SPELLING','TIME_FORMAT','SHORT_IDENT_INTERVAL','LONG_IDENT_INTERVAL','IDENT_ONLY_AFTER_TX','EXEC_CMD_ON_SQL_CLOSE','EVENT_HANDLER','DEFAULT_LANG','RGR_SOUND_DELAY','REPORT_CTCSS','TX_CTCSS','MACROS','FX_GAIN_NORMAL','FX_GAIN_LOW','QSO_RECORDER','SEL5_MACRO_RANGE','ONLINE_CMD','STATE_PTY','DTMF_CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, RX, TX

$logicSimplexArray = ['MUTE_RX_ON_TX','MUTE_TX_ON_RX','RGR_SOUND_ALWAYS'];
# SETTINGS REMOVED:
# TYPE (same as common section)

$logicRepeaterArray = ['NO_REPEAT','IDLE_TIMEOUT','OPEN_ON_1750','OPEN_ON_CTCSS','OPEN_ON_DTMF','OPEN_ON_SEL5','CLOSE_ON_SEL5','OPEN_ON_SQL','OPEN_ON_SQL_AFTER_RPT_CLOSE','OPEN_SQL_FLANK','IDLE_SOUND_INTERVAL','SQL_FLAP_SUP_MIN_TIME','SQL_FLAP_SUP_MAX_COUNT','ACTIVATE_MODULE_ON_LONG_CMD','IDENT_NAG_TIMEOUT','IDENT_NAG_MIN_TIME'];
# SETTINGS REMOVED:
# TYPE (same as common section)

$receiverArray = ['AUDIO_DEV_KEEP_OPEN','SQL_DET','SQL_START_DELAY','SQL_DELAY','SQL_HANGTIME','SQL_EXTENDED_HANGTIME','SQL_EXTENDED_HANGTIME_THRESH','SQL_TIMEOUT','VOX_FILTER_DEPTH','VOX_THRESH','CTCSS_MODE','CTCSS_FQ','CTCSS_OPEN_THRESH','CTCSS_CLOSE_THRESH','CTCSS_SNR_OFFSET','CTCSS_BPF_LOW','CTCSS_BPF_HIGH','SERIAL_PORT','SERIAL_PIN','SERIAL_SET_PINS','EVDEV_DEVNAME','EVDEV_OPEN','EVDEV_CLOSE','GPIO_PATH','GPIO_SQL_PIN','SIGLEV_DET','HID_DEVICE','HID_SQL_PIN','SIGLEV_SLOPE','SIGLEV_OFFSET','SIGLEV_BOGUS_THRESH','TONE_SIGLEV_MAP','SIGLEV_OPEN_THRESH','SIGLEV_CLOSE_THRESH','SIGLEV_MIN','SIGLEV_MAX','SIGLEV_DEFAULT','SIGLEV_TOGGLE_INTERVAL','SIGLEV_RAND_INTERVAL','DEEMPHASIS','SQL_TAIL_ELIM','PREAMP','PEAK_METER','DTMF_DEC_TYPE','DTMF_MUTING','DTMF_HANGTIME','DTMF_SERIAL','DTMF_PTY','DTMF_MAX_FWD_TWIST','DTMF_MAX_REV_TWIST','DTMF_DEBUG','1750_MUTING','SEL5_TYPE','SEL5_DEC_TYPE','RAW_AUDIO_UDP_DEST','OB_AFSK_ENABLE','OB_AFSK_VOICE_GAIN','IB_AFSK_ENABLE','CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, AUDIO_DEV, AUDIO_CHANNEL


$transmitterArray = ['AUDIO_DEV_KEEP_OPEN','PTT_TYPE','PTT_PORT','PTT_PIN','GPIO_PATH','PTT_PTY','HID_DEVICE','HID_PTT_PIN','SERIAL_SET_PINS','PTT_HANGTIME','TIMEOUT','TX_DELAY','CTCSS_FQ','CTCSS_LEVEL','PREEMPHASIS','DTMF_TONE_LENGTH','DTMF_TONE_SPACING','DTMF_DIGIT_PWR','TONE_SIGLEV_MAP','TONE_SIGLEV_LEVEL','MASTER_GAIN','OB_AFSK_ENABLE','OB_AFSK_VOICE_GAIN','OB_AFSK_LEVEL','OB_AFSK_TX_DELAY','IB_AFSK_ENABLE','IB_AFSK_LEVEL','IB_AFSK_TX_DELAY','CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, AUDIO_DEV, AUDIO_CHANNEL


function build_select_options($inputArrays, $selectVal = null ) {
	if ($selectVal == null) { $selectHTML = '<option selected disabled>---</option>'; } else { $selectHTML = '<option disabled>---</option>'; }
	
	if (count($inputArrays) != count($inputArrays, COUNT_RECURSIVE)) { 
		foreach($inputArrays as $optionGroup => $optionGrpArray) {
			$selectHTML .= '<optgroup label="'.$optionGroup.'">';
				asort($optionGrpArray);
				foreach($optionGrpArray as $optionName) {
					if ($optionName == $selectVal) { $selState = ' selected'; } else { $selState = ''; }
					$selectHTML .= '<option'.$selState.'>'.$optionName.'</option>';
				}
			$selectHTML .= '</optgroup>';			
		}
	} else {
		sort($inputArrays);
		foreach($inputArrays as $optionName) {
			if ($optionName == $selectVal) { $selState = ' selected'; } else { $selState = ''; }
			$selectHTML .= '<option'.$selState.'>'.$optionName.'</option>';
		}
	}

	return $selectHTML;
}

?>


<?php
$customJS = 'page-ports.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-ports.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-sitemap"></i> <?=_('Interface Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Ports')?></h4>
					<ul class="nav nav-pills navbar-right" style="padding: 0;" role="tablist">
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-plus"></i></a>
                        <ul class="dropdown-menu" role="menu">
	                        <li role="presentation">
	                        	<a href="#" class="addPort"><?=_('Add a NEW Port')?></a>
	                        </li>
	                        <li role="presentation">
	                        	<a href="#" class="loadBoard"><?=_('Load a Board Preset')?></a>
	                        </li>
                        </ul>
                      </li>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">

					<?php foreach($fakePorts as $currPortNum => $currPortSettings) { ?>

                      <div class="panel portSection<?=($currPortSettings['portEnabled'] == '1' ? '' : ' portDisabled')?>" id="portNum<?=$currPortNum?>" data-port-number="<?=$currPortNum?>">
                        <a class="panel-heading<?=($currPortNum == '1' ? '' : ' collapsed')?>" role="tab" id="accordionHeading<?=$currPortNum?>" data-toggle="collapse" data-parent="#accordion" href="#accordionCollapse<?=$currPortNum?>" aria-expanded="<?=($currPortNum == '1' ? 'true' : 'false')?>" aria-controls="accordionCollapse<?=$currPortNum?>">
						  <div class="col-md-6 col-sm-6 col-xs-6">
		                      <h4 class="panel-title"><strong><?=_('Port')?> #<?=$currPortNum?></strong>: <span><?=$currPortSettings['portLabel']?><span></h4>
						  </div>
						  <div class="col-md-6 col-sm-6 col-xs-6 right">
		                    <span class="right">
		                      <span class="portLabelDuplexFull label label-danger" data-toggle="tooltip" data-placement="top" title="<?=_('Full Duplex Port')?>"<?=($currPortSettings['portDuplex'] != 'full' ? ' style="display: none;"' : '')?>><?=_('Duplex')?></span>
		                      <span class="portLabelDuplexHalf label label-primary" data-toggle="tooltip" data-placement="top" title="<?=_('Half Duplex Port')?>"<?=($currPortSettings['portDuplex'] != 'half' ? ' style="display: none;"' : '')?>><?=_('Simplex')?></span>
							  <?php
							  	if (isset($currPortSettings['linkGroup']))
									$currLinkGroup = $currPortSettings['linkGroup'];
								else
									$currLinkGroup = '';
								
								$linkGroupHtml = '<span class="portLabelLinkGrp badge';
								switch ($currLinkGroup) {
									case '1':
										$linkGroupHtml .= ' bg-green';
										break;
									case '2':
										$linkGroupHtml .= ' bg-purple';
										break;
									case '3':
										$linkGroupHtml .= ' bg-blue-sky';
										break;
									case '4':
										$linkGroupHtml .= ' bg-orange';
										break;
								}
								$linkGroupHtml .= '" data-toggle="tooltip" data-placement="top" ';
								$linkGroupHtml .= 'title="' . _('Belongs to Link Group') . ' ' . $currLinkGroup . '"';
								if ($currLinkGroup == '') {
									$linkGroupHtml .= ' style="display: none;"';
								}
								$linkGroupHtml .= '><i class="fa fa-link"></i> <span>' . $currLinkGroup . '</span>';
								$linkGroupHtml .= '</span>';
								echo $linkGroupHtml;
							  ?>

		                      
		                    </span> 
						  </div>
						  <div class="clearfix"></div>
                        </a>

                        <div id="accordionCollapse<?=$currPortNum?>" class="panel-collapse collapse<?=($currPortNum == '1' ? ' in' : '')?>" role="tabpanel" aria-labelledby="accordionHeading<?=$currPortNum?>">

	                      <form id="port<?=$currPortNum?>form" class="portForm" data-port-form="<?=$currPortNum?>">

                          <div class="panel-body">


		                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
		                      <ul id="myTab" class="nav nav-tabs tabs" role="tablist">
		                        <li role="presentation" class="tabGeneral active">
		                        	<a href="#tab_general<?=$currPortNum?>" id="general-tab<?=$currPortNum?>" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-gear"></i> <?=_('General')?></a>
		                        </li>
		                        <li role="presentation" class="tabAudio">
		                        	<a href="#tab_audio<?=$currPortNum?>" role="tab" id="audio-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-volume-up"></i> <?=_('Audio')?></a>
		                        </li>
		                        <li role="presentation" class="tabGPIO"<?=($currPortSettings['portType'] != 'GPIO' ? ' style="display: none;"' : '')?>>
		                        	<a href="#tab_gpio<?=$currPortNum?>" role="tab" id="gpio-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('GPIO')?></a>
		                        </li>
		                        <li role="presentation" class="tabHidraw"<?=($currPortSettings['portType'] != 'HiDraw' ? ' style="display: none;"' : '')?>>
		                        	<a href="#tab_hidraw<?=$currPortNum?>" role="tab" id="hidraw-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('Hidraw')?></a>
		                        </li>
		                        <li role="presentation" class="tabSerial"<?=($currPortSettings['portType'] != 'Serial' ? ' style="display: none;"' : '')?>>
		                        	<a href="#tab_serial<?=$currPortNum?>" role="tab" id="serial-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('Serial')?></a>
		                        </li>
		                        <li role="presentation" class="tabModules">
		                        	<a href="#tab_modules<?=$currPortNum?>" role="tab" id="mdoules-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-plug"></i> <?=_('Modules')?></a>
		                        </li>
		                        <li role="presentation" class="tabOverrides">
		                        	<a href="#tab_override<?=$currPortNum?>" role="tab" id="override-tab<?=$currPortNum?>" data-toggle="tab" aria-expanded="false"><i class="fa fa-wrench"></i> <?=_('Overrides')?></a>
		                        </li>
		                      </ul>
		                      <div id="myTabContent" class="tab-content">

								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade active in" id="tab_general<?=$currPortNum?>" aria-labelledby="general-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height20"></div>

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Port Label')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('A short description of the port. Good practice would be to label it after the port on the hardware interface or the band/frequency of the radio connected to it.')?>"></i>
				                        </label>
				                        <div class="col-md-8 col-sm-8 col-xs-12">
										  <input class="form-control portLabel" type="text" name="portLabel1" value="<?=$currPortSettings['portLabel']?>" placeholder="<?=_('Port 1')?>">
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Triggering Type')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is the type of hardware that the interface is using for trigger pins for COS and PTT. These pins will be selectable on a different tab.')?>"></i>
				                        </label>
				                        <div class="col-md-8 col-sm-8 col-xs-12">
											<div class="btn-group portType" data-toggle="buttons">
												<label class="btn btn-default<?=($currPortSettings['portType'] == 'GPIO' ? ' active' : '')?>">
													<input type="radio" name="portType" value="GPIO"<?=($currPortSettings['portType'] == 'GPIO' ? ' checked' : '')?>><?=_('GPIO')?>
												</label>
												<label class="btn btn-default<?=($currPortSettings['portType'] == 'HiDraw' ? ' active' : '')?>">
													<input type="radio" name="portType" value="HiDraw"<?=($currPortSettings['portType'] == 'HiDraw' ? ' checked' : '')?>><?=_('Hidraw')?>
												</label>
												<label class="btn btn-default<?=($currPortSettings['portType'] == 'Serial' ? ' active' : '')?>">
													<input type="radio" name="portType" value="Serial"<?=($currPortSettings['portType'] == 'Serial' ? ' checked' : '')?>><?=_('USB Serial')?>
												</label>
											</div>

				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Logic Mode')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting determines if the port will operate in full or half duplex mode. Some options may not be available in half duplex.')?>"></i>
				                        </label>
				                        <div class="col-md-8 col-sm-8 col-xs-12">
											<div class="btn-group portDuplex" data-toggle="buttons">
												<label class="btn btn-default<?=($currPortSettings['portDuplex'] == 'half' ? ' active' : '')?>">
													<input type="radio" name="portDuplex" value="half"<?=($currPortSettings['portDuplex'] == 'half' ? ' checked' : '')?>><?=_('Half Duplex')?>
												</label>
												<label class="btn btn-default<?=($currPortSettings['portDuplex'] == 'full' ? ' active' : '')?>">
													<input type="radio" name="portDuplex" value="full"<?=($currPortSettings['portDuplex'] == 'full' ? ' checked' : '')?>><?=_('Full Duplex')?>
												</label>
											</div>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Link Group')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This allows you to add the port to a Link Group. Ports in the same link group will be linked together. This is useful for linking two repeaters on different bands together or adding RF links to a repeater.')?>"></i>
				                        </label>
				                        <div class="col-md-8 col-sm-8 col-xs-12">
											<div class="btn-group linkGroup" data-toggle="buttons">
												<label class="btn btn-default<?=($currLinkGroup == '' ? ' active' : '')?>">
													<input type="radio" name="linkGroup" value=""<?=($currLinkGroup == '1' ? ' checked' : '')?>><?=_('OFF')?>
												</label>
												<label class="btn btn-success<?=($currLinkGroup == '1' ? ' active' : '')?>">
													<input type="radio" name="linkGroup" value="1"<?=($currLinkGroup == '1' ? ' checked' : '')?>>1
												</label>
												<label class="btn btn-success<?=($currLinkGroup == '2' ? ' active' : '')?>">
													<input type="radio" name="linkGroup" value="2"<?=($currLinkGroup == '2' ? ' checked' : '')?>>2
												</label>
												<label class="btn btn-success<?=($currLinkGroup == '3' ? ' active' : '')?>">
													<input type="radio" name="linkGroup" value="3"<?=($currLinkGroup == '3' ? ' checked' : '')?>>3
												</label>
												<label class="btn btn-success<?=($currLinkGroup == '4' ? ' active' : '')?>">
													<input type="radio" name="linkGroup" value="4"<?=($currLinkGroup == '4' ? ' checked' : '')?>>4
												</label>
											</div>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>
									  
				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Enable Port')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This allows the port to be disabled, but retain the current settings.')?>"></i>
				                        </label>
				                        <div class="col-md-8 col-sm-8 col-xs-12">
										  <input type="checkbox" name="portEnabled" class="js-switch portEnabled" value="1"<?=($currPortSettings['portEnabled'] == '1' ? ' checked' : '')?>> 
										  <a href="#" class="deletePort"<?=($currPortSettings['portEnabled'] == '1' ? ' style="display:none;"' : '')?>><i class="fa fa-trash-o"></i> Delete</a>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

		                        </div>

								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade" id="tab_audio<?=$currPortNum?>" aria-labelledby="audio-tab<?=$currPortNum?>">
								  <div class="clearfix spacer height20"></div>

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-5 col-sm-5 col-xs-12"><?=_('RX Audio (Input)')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Audio coming out of the receiver into the controller.')?>"></i>
			                        	</label>
				                        <div class="col-md-7 col-sm-7 col-xs-12">
										  <?php $rxAudioDev = (isset($currPortSettings['rxAudioDev'])) ? $currPortSettings['rxAudioDev'] : ''; ?>
										  <select id="rxAudioDev1" name="rxAudioDev[]" class="form-control rxAudioDev">
											<option value="">---</option>
											<option value="alsa:plughw:0|0"<?=($rxAudioDev == 'alsa:plughw:0|0') ? 'selected' : '';?>>INPUT 0: Fe-Pi Audio (Left)</option>
											<option value="alsa:plughw:0|1"<?=($rxAudioDev == 'alsa:plughw:0|1') ? 'selected' : '';?>>INPUT 0: Fe-Pi Audio (Right)</option>
											<option value="alsa:plughw:1|0"<?=($rxAudioDev == 'alsa:plughw:1|0') ? 'selected' : '';?>>INPUT 1: USB PnP Sound Device (Left)</option>
											<option value="alsa:plughw:1|1"<?=($rxAudioDev == 'alsa:plughw:1|1') ? 'selected' : '';?>>INPUT 1: USB PnP Sound Device (Right)</option>
				                          </select>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-5 col-sm-5 col-xs-12"><?=_('TX Audio (Output)')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Audio coming out of the controller going into the transmitter.')?>"></i>
				                        </label>
				                        <div class="col-md-7 col-sm-7 col-xs-12">
										  <?php $txAudioDev = (isset($currPortSettings['txAudioDev'])) ? $currPortSettings['txAudioDev'] : ''; ?>
										  <select id="txAudioDev1" name="txAudioDev[]" class="form-control txAudioDev">
											<option value="">---</option>
											<option value="alsa:plughw:0|0"<?=($rxAudioDev == 'alsa:plughw:0|0') ? 'selected' : '';?>>OUTPUT 0: Fe-Pi Audio (Left)</option>
											<option value="alsa:plughw:0|1"<?=($rxAudioDev == 'alsa:plughw:0|1') ? 'selected' : '';?>>OUTPUT 0: Fe-Pi Audio (Right)</option>
											<option value="alsa:plughw:1|0"<?=($rxAudioDev == 'alsa:plughw:1|0') ? 'selected' : '';?>>OUTPUT 1: USB PnP Sound Device (Left)</option>
											<option value="alsa:plughw:1|1"<?=($rxAudioDev == 'alsa:plughw:1|1') ? 'selected' : '';?>>OUTPUT 1: USB PnP Sound Device (Right)</option>
				                          </select>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

		                        </div>

								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade" id="tab_gpio<?=$currPortNum?>" aria-labelledby="gpio-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height30"></div>

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-6 col-sm-6 col-xs-12">
				                        	<?=_('Receive Control Mode')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting controls how this port is triggered by an incoming signal from the receiver. Carrier Operated Squelch (COS) is the recommended control method.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
											<?php $rxMode = (isset($currPortSettings['rxMode'])) ? $currPortSettings['rxMode'] : 'cos'; ?>
											<select id="rxMode<?=$currPortNum?>" name="rxMode[<?=$currPortNum?>]" class="form-control rxMode">
												<option value="gpio"<?=($rxMode == 'cos') ? 'selected' : '';?>>COS</option>
												<option value="vox"<?=($rxMode == 'vox') ? 'selected' : '';?>>VOX</option>
											</select>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

				                      <div class="col-md-12 col-sm-12 col-xs-12">
						                  <div class="alert alert-success alert-dismissible fade in" role="alert">
						                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						                    </button>
						                    <strong><?=_('WARNING:')?></strong> <?=_('The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible.')?>
						                  </div>
					                  </div>


				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Receive COS Pin')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that receives the COS signal from the receiver.')?>"></i>
				                        </label>
				                        <div class="col-md-4 col-sm-4 col-xs-6">
										  <?php $rxGPIO = (isset($currPortSettings['rxGPIO'])) ? $currPortSettings['rxGPIO'] : ''; ?>
										  <input id="rxGPIO<?=$currPortNum?>" class="form-control" type="text" name="rxGPIO" value="<?=$rxGPIO?>" placeholder="<?=_('GPIO')?>">
				                        </div>
				                        <div class="col-md-4 col-sm-4 col-xs-6">
										  <?php $rxGPIO_active = (isset($currPortSettings['rxGPIO_active'])) ? $currPortSettings['rxGPIO_active'] : 'low'; ?>
										  <select id="rxGPIO_active<?=$currPortNum?>" name="rxGPIO_active[<?=$currPortNum?>]" class="form-control rxGPIO_active">
										  	<option value="high" <?=($rxGPIO_active == 'high') ? ' selected' : '';?>><?=_('Active High')?></option>
										  	<option value="low" <?=($rxGPIO_active == 'low') ?  ' selected' : '';?>><?=_('Active Low')?></option>
										  </select>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>
								  				                      

								  <div class="col-md-6 col-sm-6 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-4 col-sm-4 col-xs-12">
				                        	<?=_('Transmit PTT Pin')?> 
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter.')?>"></i>
				                        </label>
				                        <div class="col-md-4 col-sm-4 col-xs-6">
										  <?php $txGPIO = (isset($currPortSettings['txGPIO'])) ? $currPortSettings['txGPIO'] : ''; ?>
										  <input id="txGPIO<?=$currPortNum?>" class="form-control" type="text" name="txGPIO[<?=$currPortNum?>]" value="<?=$txGPIO?>" placeholder="<?=_('GPIO')?>">
				                        </div>
				                        <div class="col-md-4 col-sm-4 col-xs-6">
										  <?php $txGPIO_active = (isset($currPortSettings['txGPIO_active'])) ? $currPortSettings['txGPIO_active'] : 'low'; ?>
										  <select id="txGPIO_active<?=$currPortNum?>" name="txGPIO_active[<?=$currPortNum?>]" class="form-control txGPIO_active">
										  	<option value="high" <?=($txGPIO_active == 'high') ? ' selected' : '';?>><?=_('Active High')?></option>
										  	<option value="low" <?=($txGPIO_active == 'low') ?  ' selected' : '';?>><?=_('Active Low')?></option>
										  </select>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

		                        </div>


								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade" id="tab_hidraw<?=$currPortNum?>" aria-labelledby="hidraw-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height30"></div>

								  <div class="col-md-12 col-sm-12 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Hidraw Device')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The HID device that you wish to toggle pins on.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $hidrawDev = (isset($currPortSettings['hidrawDev'])) ? $currPortSettings['hidrawDev'] : ''; ?>
										  <select id="hidrawDev<?=$currPortNum?>" name="hidrawDev[<?=$currPortNum?>]" class="form-control hidrawDev">
											<option value="">---</option>
										  	<option value="/dev/hidraw0" <?=($hidrawDev == '/dev/hidraw0') ? ' selected' : '';?>>/dev/hidraw0</option>
										  	<option value="/dev/hidraw1" <?=($hidrawDev == '/dev/hidraw1') ?  ' selected' : '';?>>/dev/hidraw1</option>
										  	<option value="/dev/hidraw2" <?=($hidrawDev == '/dev/hidraw2') ? ' selected' : '';?>>/dev/hidraw2</option>
										  	<option value="/dev/hidraw3" <?=($hidrawDev == '/dev/hidraw3') ?  ' selected' : '';?>>/dev/hidraw3</option>
										  	<option value="/dev/hidraw4" <?=($hidrawDev == '/dev/hidraw4') ? ' selected' : '';?>>/dev/hidraw4</option>
										  	<option value="/dev/hidraw5" <?=($hidrawDev == '/dev/hidraw5') ?  ' selected' : '';?>>/dev/hidraw5</option>
										  	<option value="/dev/hidraw6" <?=($hidrawDev == '/dev/hidraw6') ? ' selected' : '';?>>/dev/hidraw6</option>
										  	<option value="/dev/hidraw7" <?=($hidrawDev == '/dev/hidraw7') ?  ' selected' : '';?>>/dev/hidraw7</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
				                      </div>
				                      

									  <div class="clearfix"></div>
									  <div class="clearfix ln_solid"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Receive COS Pin')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that receives the COS signal from the receiver. You have the option of inverting the control logic of this pin.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $hidrawRX_cos = (isset($currPortSettings['hidrawRX_cos'])) ? $currPortSettings['hidrawRX_cos'] : ''; ?>
										  <select id="hidrawRX_cos<?=$currPortNum?>" name="hidrawRX_cos[<?=$currPortNum?>]" class="form-control hidrawRX_cos">
											<option value="">---</option>
										  	<option value="VOL_UP" <?=($hidrawRX_cos == 'VOL_UP') ? ' selected' : '';?>>VOL_UP</option>
										  	<option value="VOL_DN" <?=($hidrawRX_cos == 'VOL_DN') ?  ' selected' : '';?>>VOL_DN</option>
										  	<option value="MUTE_PLAY" <?=($hidrawRX_cos == 'MUTE_PLAY') ? ' selected' : '';?>>MUTE_PLAY</option>
										  	<option value="MUTE_REC" <?=($hidrawRX_cos == 'MUTE_REC') ?  ' selected' : '';?>>MUTE_REC</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12">
										  <?php $hidrawRX_cos_invert = (isset($currPortSettings['hidrawRX_cos_invert'])) ? $currPortSettings['hidrawRX_cos_invert'] : 'false'; ?>
										  <input type="checkbox" name="hidrawRX_cos_invert[<?=$currPortNum?>]" class="js-switch" value="true"<?=($hidrawRX_cos_invert == 'true' ? ' checked' : '')?>>
					                      <label><?=_('Invert Pin')?></label>
				                        </div>
				                      </div>
				                      
									  <div class="clearfix spacer height20"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $hidrawTX_ptt = (isset($currPortSettings['hidrawTX_ptt'])) ? $currPortSettings['hidrawTX_ptt'] : ''; ?>
										  <?(isset($currPortSettings['hidrawTX_ptt'])) ? $hidrawTX_ptt=$currPortSettings['hidrawTX_ptt'] : $hidrawTX_ptt ='';?>
										  <select id="hidrawTX_ptt<?=$currPortNum?>" name="hidrawTX_ptt[<?=$currPortNum?>]" class="form-control hidrawTX_ptt">
											<option value="">---</option>
										  	<option value="GPIO1" <?=($hidrawTX_ptt == 'GPIO1') ? ' selected' : '';?>>GPIO1</option>
										  	<option value="GPIO2" <?=($hidrawTX_ptt == 'GPIO2') ?  ' selected' : '';?>>GPIO2</option>
										  	<option value="GPIO3" <?=($hidrawTX_ptt == 'GPIO3') ? ' selected' : '';?>>GPIO3</option>
										  	<option value="GPIO4" <?=($hidrawTX_ptt == 'GPIO4') ?  ' selected' : '';?>>GPIO4</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12">
										  <?php $hidrawTX_ptt_invert = (isset($currPortSettings['hidrawTX_ptt_invert'])) ? $currPortSettings['hidrawTX_ptt_invert'] : 'false'; ?>
										  <input type="checkbox" name="hidrawTX_ptt_invert[<?=$currPortNum?>]" class="js-switch" value="true"<?=($hidrawTX_ptt_invert == 'true' ? ' checked' : '')?>>
					                      <label><?=_('Invert Pin')?></label>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

		                        </div>

								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade" id="tab_serial<?=$currPortNum?>" aria-labelledby="serial-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height30"></div>

								  <div class="col-md-12 col-sm-12 col-xs-12">

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Serial Device')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The serial device that you wish to toggle pins on.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $serialDev = (isset($currPortSettings['serialDev'])) ? $currPortSettings['serialDev'] : ''; ?>
										  <select id="serialDev<?=$currPortNum?>" name="serialDev[<?=$currPortNum?>]" class="form-control serialDev">
											<option value="">---</option>
										  	<option value="/dev/hidraw0" <?=($serialDev == '/dev/ttyUSB0') ? ' selected' : '';?>>/dev/ttyUSB0</option>
										  	<option value="/dev/hidraw1" <?=($serialDev == '/dev/ttyUSB1') ?  ' selected' : '';?>>/dev/ttyUSB1</option>
										  	<option value="/dev/hidraw2" <?=($serialDev == '/dev/ttyUSB2') ? ' selected' : '';?>>/dev/ttyUSB2</option>
										  	<option value="/dev/hidraw3" <?=($serialDev == '/dev/ttyUSB3') ?  ' selected' : '';?>>/dev/ttyUSB3</option>
										  	<option value="/dev/hidraw4" <?=($serialDev == '/dev/ttyUSB4') ? ' selected' : '';?>>/dev/ttyUSB4</option>
										  	<option value="/dev/hidraw5" <?=($serialDev == '/dev/ttyUSB5') ?  ' selected' : '';?>>/dev/ttyUSB5</option>
										  	<option value="/dev/hidraw6" <?=($serialDev == '/dev/ttyUSB6') ? ' selected' : '';?>>/dev/ttyUSB6</option>
										  	<option value="/dev/hidraw7" <?=($serialDev == '/dev/ttyUSB7') ?  ' selected' : '';?>>/dev/ttyUSB7</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
				                      </div>
				                      

									  <div class="clearfix"></div>
									  <div class="clearfix ln_solid"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Receive COS Pin')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that receives the COS signal from the receiver. You have the option of inverting the control logic of this pin.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $serialRX_cos = (isset($currPortSettings['serialRX_cos'])) ? $currPortSettings['serialRX_cos'] : ''; ?>
										  <select id="serialRX_cos<?=$currPortNum?>" name="serialRX_cos[<?=$currPortNum?>]" class="form-control serialRX_cos">
											<option value="">---</option>
										  	<option value="DCD" <?=($serialRX_cos == 'DCD') ? ' selected' : '';?>>DCD</option>
										  	<option value="CTS" <?=($serialRX_cos == 'CTS') ?  ' selected' : '';?>>CTS</option>
										  	<option value="DSR" <?=($serialRX_cos == 'DSR') ? ' selected' : '';?>>DSR</option>
										  	<option value="RI" <?=($serialRX_cos == 'RI') ?  ' selected' : '';?>>RI</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12">
										  <?php $serialRX_cos_invert = (isset($currPortSettings['serialRX_cos_invert'])) ? $currPortSettings['serialRX_cos_invert'] : 'false'; ?>
										  <input type="checkbox" name="serialRX_cos_invert[<?=$currPortNum?>]" class="js-switch" value="true"<?=($serialRX_cos_invert == 'true' ? ' checked' : '')?>>
					                      <label><?=_('Invert Pin')?></label>
				                        </div>
				                      </div>
				                      
									  <div class="clearfix spacer height20"></div>

				                      <div class="form-group">
				                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
				                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
				                        </label>
				                        <div class="col-md-6 col-sm-6 col-xs-12">
										  <?php $serialTX_ptt = (isset($currPortSettings['serialTX_ptt'])) ? $currPortSettings['serialTX_ptt'] : ''; ?>
										  <select id="serialTX_ptt<?=$currPortNum?>" name="serialTX_ptt[<?=$currPortNum?>]" class="form-control serialTX_ptt">
											<option value="">---</option>
										  	<option value="RTS" <?=($serialTX_ptt == 'RTS') ?  ' selected' : '';?>>RTS</option>
										  	<option value="DTR" <?=($serialTX_ptt == 'DTR') ? ' selected' : '';?>>DTR</option>
										  </select>
				                        </div>
				                        <div class="col-md-3 col-sm-3 col-xs-12">
										  <?php $serialTX_ptt_invert = (isset($currPortSettings['serialTX_ptt_invert'])) ? $currPortSettings['serialTX_ptt_invert'] : 'false'; ?>
										  <input type="checkbox" name="serialTX_ptt_invert[<?=$currPortNum?>]" class="js-switch" value="true"<?=($serialTX_ptt_invert == 'true' ? ' checked' : '')?>>
					                      <label><?=_('Invert Pin')?></label>
				                        </div>
				                      </div>

									  <div class="clearfix spacer height20"></div>

								  </div>

		                        </div>

								<!-- ******************************* -->

		                        <div role="tabpanel" class="tab-pane fade" id="tab_modules<?=$currPortNum?>" aria-labelledby="modules-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height30"></div>

								  <div class="col-md-12 col-sm-12 col-xs-12">

								  	WIP...
								  	
								  </div>

		                        </div>

								<!-- ******************************* -->

								<?php
								$logic_html = '';
								if (isset($currPortSettings['SVXLINK_ADVANCED_LOGIC'])) {
									$settingNum = 0;
									foreach ( $currPortSettings['SVXLINK_ADVANCED_LOGIC'] as $curSettingName => $curSettingValue) {
										$settingNum++;
										$logic_html .= '<div>';
										$logic_html .= '<select name="SVXLINK_ADVANCED_LOGIC['.$currPortNum.']['.$settingNum.'][name]" class="form-control advOptionKey">';
										$logic_html .= build_select_options(['Common Variables' => $logicCommonArray, 'Simplex Logic Only' => $logicSimplexArray, 'Repeater Logic Only' => $logicRepeaterArray], $curSettingName);
										$logic_html .= '</select>';
										$logic_html .= '<input class="form-control advOptionValue" type="text" name="SVXLINK_ADVANCED_LOGIC['.$currPortNum.']['.$settingNum.'][value]" placeholder="' . _('Value') . '" value="' . $curSettingValue . '">';
										$logic_html .= '<button class="form-control remove_field"><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="' . _('Delete Row') . '"></i></button>';
										$logic_html .= '</div>';
									}
									$local_count = $settingNum;
								} else {
									$local_count = 0;
								}
								
								$rx_html = '';
								if (isset($currPortSettings['SVXLINK_ADVANCED_RX'])) {
									$settingNum = 0;
									foreach ( $currPortSettings['SVXLINK_ADVANCED_RX'] as $curSettingName => $curSettingValue) {
										$settingNum++;
										$rx_html .= '<div>';
										$rx_html .= '<select name="SVXLINK_ADVANCED_RX['.$currPortNum.']['.$settingNum.'][name]" class="form-control advOptionKey">';
										$rx_html .= build_select_options($receiverArray, $curSettingName);
										$rx_html .= '</select>';
										$rx_html .= '<input class="form-control advOptionValue" type="text" name="SVXLINK_ADVANCED_RX['.$currPortNum.']['.$settingNum.'][value]" placeholder="' . _('Value') . '" value="' . $curSettingValue . '">';
										$rx_html .= '<button class="form-control remove_field"><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="' . _('Delete Row') . '"></i></button>';
										$rx_html .= '</div>';
									}
									$rx_count = $settingNum;
								} else {
									$rx_count = 0;
								}
							
								$tx_html = '';
								if (isset($currPortSettings['SVXLINK_ADVANCED_TX'])) {
									$settingNum = 0;
									foreach ( $currPortSettings['SVXLINK_ADVANCED_TX'] as $curSettingName => $curSettingValue) {
										$settingNum++;

										$tx_html .= '<div>';
										$tx_html .= '<select name="SVXLINK_ADVANCED_TX['.$currPortNum.']['.$settingNum.'][name]" class="form-control advOptionKey">';
										$tx_html .= build_select_options($transmitterArray, $curSettingName);
										$tx_html .= '</select>';
										$tx_html .= '<input class="form-control advOptionValue" type="text" name="SVXLINK_ADVANCED_TX['.$currPortNum.']['.$settingNum.'][value]" placeholder="' . _('Value') . '" value="' . $curSettingValue . '">';
										$tx_html .= '<button class="form-control remove_field"><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="' . _('Delete Row') . '"></i></button>';
										$tx_html .= '</div>';
									}
									$tx_count = $settingNum;
								} else {
									$tx_count = 0;
								}
								?>


								<div role="tabpanel" class="tab-pane fade" id="tab_override<?=$currPortNum?>" aria-labelledby="override-tab<?=$currPortNum?>">

								  <div class="clearfix spacer height10"></div>

								  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
					                  <div class="x_title">
					                    <h4 class="navbar-left"><?=_('Logic Section')?></h4>
					                    <div class="clearfix"></div>
					                  </div>

									  <div class="input_fields_wrap" id="port<?=$currPortNum?>local" data-port-num="<?=$currPortNum?>" data-real-count="<?=$local_count?>" data-ceiling-count="<?=$local_count?>" data-section-type="local">
											<div class="innerWrap"><?=$logic_html?></div>
											<button class="btn btn-success btn-xs add_field_button"><i class="fa fa-plus-circle"></i> <?=_('Add Field')?></button>
									  </div>
									  <div class="clearfix spacer height20"></div>
								  </div>

								  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
					                  <div class="x_title">
					                    <h4 class="navbar-left"><?=_('RX Section')?></h4>
					                    <div class="clearfix"></div>
					                  </div>

										<div class="input_fields_wrap" id="port<?=$currPortNum?>rx" data-port-num="<?=$currPortNum?>" data-real-count="<?=$rx_count?>" data-ceiling-count="<?=$rx_count?>" data-section-type="rx">
											<div class="innerWrap"><?=$rx_html?></div>
											<button class="btn btn-success btn-xs add_field_button"><i class="fa fa-plus-circle"></i> <?=_('Add Field')?></button>
										</div>

									  <div class="clearfix spacer height20"></div>
								  	
								  </div>


								  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
					                  <div class="x_title">
					                    <h4 class="navbar-left"><?=_('TX Section')?></h4>
					                    <div class="clearfix"></div>
					                  </div>

										<div class="input_fields_wrap" id="port<?=$currPortNum?>tx" data-port-num="<?=$currPortNum?>" data-real-count="<?=$tx_count?>" data-ceiling-count="<?=$tx_count?>" data-section-type="tx">
											<div class="innerWrap"><?=$tx_html?></div>
											<button class="btn btn-success btn-xs add_field_button"><i class="fa fa-plus-circle"></i> <?=_('Add Field')?></button>
										</div>

									  <div class="clearfix spacer height20"></div>
								  	
								  </div>

		                        </div>

								<!-- ******************************* -->

		                      </div>
		                    </div>


                          </div>

	                      </form>

                        </div>
                      </div>

					<?php } // end foreach loop ?>






                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="accordionHeadingThree" data-toggle="collapse" data-parent="#accordion" href="#accordionCollapseThree" aria-expanded="false" aria-controls="accordionCollapseThree" style="background-color: aqua;">
						  <div class="col-md-6 col-sm-6 col-xs-6">
		                      <h4 class="panel-title">Port #3 - VOIP Port</h4>
						  </div>
						  <div class="col-md-6 col-sm-6 col-xs-6 right">
		                    <span class="right">
		                      <span class="label label-primary" data-toggle="tooltip" data-placement="top" title="<?=_('Half Duplex Port')?>">Simplex</span>
		                      <span class="badge bg-green" data-toggle="tooltip" data-placement="top" title="<?=_('Belongs to Link Group')?> 1"><i class="fa fa-link"></i> 1</span>
		                    </span> 
						  </div>
						  <div class="clearfix"></div>
                        </a>
                        <div id="accordionCollapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordionHeadingThree">
                          <div class="panel-body">
                            <p>Demo Only</p>
                          </div>
                        </div>
                      </div>

                    </div> <!-- end of accordion -->


                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><i class="fa fa-link"></i> <?=_('Link Group Settings')?></h4></div>

                  <div class="x_content">

		              <div class="col-md-3 col-sm-6 col-xs-12">
					  	<div class="x_title"><h4><?=_('Link Group 1')?></h4></div>
		
			              <div class="form-group">
			                <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Activate')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When enabled, the link will be connected automatically during startup. With this setup the behavior of the timeout (if set) will be inverted. This means if a link is manually disconnected by a user, it will be automatically reconnected after the time specified by the timeout setting. If there is no timeout set, no automatic reactivation will be made.')?>"></i>
			                </label>
			                <div class="col-md-6 col-sm-6 col-xs-6">
			                  <input id="remoteDisable" type="checkbox" class="js-switch" checked /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input type="number" class="form-control" value="25" placeholder="25">
		                    </div>
		                  </div>
		              </div>
		
		
		              <div class="col-md-3 col-sm-6 col-xs-12">
					  	<div class="x_title"><h4><?=_('Link Group 2')?></h4></div>
		
			              <div class="form-group">
			                <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Activate')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When enabled, the link will be connected automatically during startup. With this setup the behavior of the timeout (if set) will be inverted. This means if a link is manually disconnected by a user, it will be automatically reconnected after the time specified by the timeout setting. If there is no timeout set, no automatic reactivation will be made.')?>"></i>
			                </label>
			                <div class="col-md-6 col-sm-6 col-xs-6">
			                  <input id="remoteDisable" type="checkbox" class="js-switch" checked /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input type="number" class="form-control" value="25" placeholder="25">
		                    </div>
		                  </div>
		              </div>
		
		
		              <div class="col-md-3 col-sm-6 col-xs-12">
					  	<div class="x_title"><h4><?=_('Link Group 3')?></h4></div>
		
			              <div class="form-group">
			                <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Activate')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When enabled, the link will be connected automatically during startup. With this setup the behavior of the timeout (if set) will be inverted. This means if a link is manually disconnected by a user, it will be automatically reconnected after the time specified by the timeout setting. If there is no timeout set, no automatic reactivation will be made.')?>"></i>
			                </label>
			                <div class="col-md-6 col-sm-6 col-xs-6">
			                  <input id="remoteDisable" type="checkbox" class="js-switch" checked /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input type="number" class="form-control" value="25" placeholder="25">
		                    </div>
		                  </div>
		              </div>
		
		
		              <div class="col-md-3 col-sm-6 col-xs-12">
					  	<div class="x_title"><h4><?=_('Link Group 4')?></h4></div>
		
			              <div class="form-group">
			                <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Activate')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When enabled, the link will be connected automatically during startup. With this setup the behavior of the timeout (if set) will be inverted. This means if a link is manually disconnected by a user, it will be automatically reconnected after the time specified by the timeout setting. If there is no timeout set, no automatic reactivation will be made.')?>"></i>
			                </label>
			                <div class="col-md-6 col-sm-6 col-xs-6">
			                  <input id="remoteDisable" type="checkbox" class="js-switch" checked /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input type="number" class="form-control" value="25" placeholder="25">
		                    </div>
		                  </div>
		              </div>
		

                  </div>
                </div>


              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


<?php
	$portLocalTemplate = '
	  <div class="panel">
	    <a class="panel-heading collapsed" role="tab" id="accordionHeadingThree" data-toggle="collapse" data-parent="#accordion" href="#accordionCollapseThree" aria-expanded="false" aria-controls="accordionCollapseThree" style="background-color: red;">
		  <div class="col-md-6 col-sm-6 col-xs-6">
	          <h4 class="panel-title">Port #3 - VOIP Port</h4>
		  </div>
		  <div class="col-md-6 col-sm-6 col-xs-6 right">
	        <span class="right">
	          <span class="label label-primary" data-toggle="tooltip" data-placement="top" title="' . _('Half Duplex Port') . '">Simplex</span>
	          <span class="badge bg-green" data-toggle="tooltip" data-placement="top" title="' . _('Belongs to Link Group') . ' 1"><i class="fa fa-link"></i> 1</span>
	        </span> 
		  </div>
		  <div class="clearfix"></div>
	    </a>
	    <div id="accordionCollapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordionHeadingThree">
	      <div class="panel-body">
	        <p>Demo Only</p>
	      </div>
	    </div>
	  </div>';



	$jsCode = '<div><select name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][name]" class="form-control advOptionKey">%%OPTIONS%%</select><input class="form-control advOptionValue" type="text" name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][value]" placeholder="' . _('Value') . '" value=""><button class="form-control remove_field"><i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Delete Row"></i></button></div>';

	$jsLogicOptions = build_select_options(['Common Variables' => $logicCommonArray, 'Simplex Logic Only' => $logicSimplexArray, 'Repeater Logic Only' => $logicRepeaterArray]);
	$jsRxOptions = build_select_options($receiverArray);
	$jsTxOptions = build_select_options($transmitterArray);
?>

<script>
	var portLocalTemplate = <?=json_encode($portLocalTemplate)?>;

	var max_fields = 10; //maximum input boxes allowed per section
	var baseRow = <?=json_encode($jsCode)?>;
	var logicOptions = <?=json_encode($jsLogicOptions)?>;
	var rxOptions = <?=json_encode($jsRxOptions)?>;
	var txOptions = <?=json_encode($jsTxOptions)?>;

	var modal_AddPortTitle = '<?=_('Add Port')?>';
	var modal_AddPortBody = '<p><?=_('What type of port do you wish to add?')?></p><select id="addPortType" name="addPortType" class="form-control"><option value="local" selected><?=_('Local Analog Port')?></option></select>';

</script>


<?php include('includes/footer.php'); ?>