<?php
$customJS = 'page-wizard.js';
$customCSS = 'page-wizard.css';

include('../includes/header.php');

$wizardSettingsJSON = file_get_contents('includes/database_defaults.json');
$wizardSettingsArray = json_decode($wizardSettingsJSON, false);

?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-magic"></i> <?=_('Setup Wizard')?></h3>
              </div>
            </div>


            <div class="clearfix"></div>

			<div class="alert alert-warning">
			<h4><i class="fa fa-warning"></i> Warning!</h4> The wizard is still in early development. The best way to work around the wizard in the meantime for new setups is to edit the database and add a callsign to the database's settings table, logout and then login again. Then you should be able to manually edit the settings as normal. 
			</div>

<? print_r($wizardSettingsArray); ?>
<? //var_dump($wizardSettingsArray->ports->{'1'}->portLabel); ?>


            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">

				  	<br>

                    <!-- Smart Wizard -->
                    <div id="wizard" class="form_wizard wizard_horizontal">

<? ################################################################################ ?>

                      <ul class="wizard_steps">
                        <li>
                          <a href="#step-1">
                            <span class="step_no"><?=_('1')?></span>
                            <span class="step_descr">
                                              <?=_('Step 1')?><br />
                                              <small><?=_('Welcome')?></small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-2">
                            <span class="step_no"><?=_('2')?></span>
                            <span class="step_descr">
                                              <?=_('Step 2')?><br />
                                              <small><?=_('Enter Information')?></small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-3">
                            <span class="step_no"><?=_('3')?></span>
                            <span class="step_descr">
                                              <?=_('Step 3')?><br />
                                              <small><?=_('Setup Hardware')?></small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-4">
                            <span class="step_no"><?=_('4')?></span>
                            <span class="step_descr">
                                              <?=_('Step 4')?><br />
                                              <small><?=_('Verify Settings')?></small>
                                          </span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-5">
                            <span class="step_no"><?=_('5')?></span>
                            <span class="step_descr">
                                              <?=_('Step 5')?><br />
                                              <small><?=_('Finish Up')?></small>
                                          </span>
                          </a>
                        </li>
                      </ul>

<form id="wizardForm">
<? ################################################################################ ?>

<!-- <div id="step-1"> -->
<div id="step-3">
	<h3 class="StepTitle"><strong><?=_('Step 1')?></strong> - <?=_('Welcome to OpenRepeater')?></h3>

	<p><?=_('Welcome to the OpenRepeater setup wizard. This wizard will guide you through the essential settings to get your OpenRepeater controller up and running. It will not set all of the settings and it will set many to defaults. Note that none of your entries will be applied until you have completed the wizard, applied your changes, and rebuilt and restart the controller. Any other setting you will be able to modify after the controller is setup.')?></p>
	
	<p><?=_('Thanks again for your support of the OpenRepeater Project!')?></p>
	
	<p><em><?=_('~The OpenRepeater Team~')?></em></p>
	
	<h4><?=_('Before You Get Started')?></h4>
	
	<div id="scrollTerms" class="scrollTerms"><? include('includes/agreement.php') ?></div>

	<input type="hidden" name="termsAgree" value="no">
	<input id="termsAgree" name="termsAgree" type="checkbox" value="yes" class="js-switch form-control termsAgree" <?= ($wizardSettingsArray->termsAgree == 'yes') ? 'checked': ''; ?>>

	<?=_('I have read the requirements for hardware and I understand about setting up a repeater and the potential to cause interference. (Scroll to accept.)')?>

</div>

<? ################################################################################ ?>

<div id="step-2">
	<h3 class="StepTitle"><strong><?=_('Step 2')?></strong> - <?=_('Enter Information')?></h3>
	<div class="clearfix spacer height20"></div>

	<!-- ******************************* -->

	<div class="x_title"><h4><i class="fa fa-gear"></i> <?=_('Basic Identification Settings')?></h4></div>

	<div class="clearfix spacer height20"></div>

		<div class="row">

			<div class="col-md-6 col-xs-12">
				<p><?=_('Please enter the callsign that will be used to identify this repeater.')?></p>
				<p><?=_('Note: Only enter the call sign like X0XXX. Do not add any suffixes like "/R" to the end. There are other identification options you can choose later to set suffixes for Morse Code IDs.')?></p>
				<div class="clearfix spacer height20"></div>
			</div>

			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					<label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Call Sign')?>
						<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This will be used for identification.')?>"></i>
					</label>
					<div class="col-md-6 col-sm-9 col-xs-7">
						<input id="callSign" name="callSign" type="text" class="form-control" style="text-transform: uppercase" value="" placeholder="W1AW" required>
					</div>
				</div>
				<div class="clearfix spacer height20"></div>
			</div>

		</div>

</div>

<? ################################################################################ ?>

<!--                        <div id="step-3"> -->
                       <div id="step-1">
                        <h3 class="StepTitle"><strong><?=_('Step 3')?></strong> - <?=_('Setup Hardware')?></h3>


			<div class="clearfix spacer height20"></div>

            <div class="col-md-8 center-margin">
				<div class="btn-group wizardType" style="margin-left: auto; margin-right: auto; display: block; width: 430px;" data-toggle="buttons">
					<label class="btn btn-success btn-lg">
						<input type="radio" id="wizardTypeStandard" name="configMethod" value="standard"><i class="fa fa-magic"></i> <?=_('Use Setup Wizard')?>
					</label>
					<label class="btn btn-success btn-lg">
						<input type="radio" id="wizardTypeRestore" name="configMethod" value="restore"><i class="fa fa-database"></i> <?=_('Restore from Backup')?>
					</label>
				</div>
            </div>

			<div class="clearfix spacer height20"></div>






<?=_('Setup Supported Interface Board or Manual Configuration')?>
<?=_('If you have a supported interface board and have it connected to your single board computer you can choose it from the list. Otherwise, if you have a board that is not supported or you have built your own interface hardware, then choose Manual Configuration.')?>


			<div class="clearfix spacer height20"></div>

            <div class="col-md-12">
				<div class="btn-group configMethod" style="margin-left: auto; margin-right: auto; display: block; width: 450px;" data-toggle="buttons">
					<label class="btn btn-success btn-lg">
						<input type="radio" id="configMethodPreset" name="configMethod" value="preset"><i class="fa fa-puzzle-piece"></i> <?=_('Use a Board Preset')?>
					</label>
					<label class="btn btn-success btn-lg">
						<input type="radio" id="configMethodManual" name="configMethod" value="manual"><i class="fa fa-cogs"></i> <?=_('Manual Configuration')?>
					</label>
				</div>
            </div>

			<div class="clearfix spacer height20"></div>

			<!-- ******************************* -->

			<div id="boardPresetWrap">

BOARD PRESET PLACEHOLDER

			</div> <!-- End boardPresetWrap -->

			<!-- ******************************* -->

			<div id="portSettingsWrap">

			  <div class="x_title"><h4><i class="fa fa-gear"></i> <?=_('General')?></h4></div>

Manually Setup the 1st Port
Ports are the audio and logic I/Os that interface the OpenRepeater controller with the transmitter and receiver to make the repeater function. This is done through other external circuitry. Since you have chosen to set this up manually, you must specify the settings for this hardware. It utilizes both a sound card and the GPIO pins to make up the port, usually a paired receiver and transmitter hence a repeater. Here you will setup the first port required to make the controller initially function. You will be able to add other ports later if you require them and your hardware supports them.

			  <div class="clearfix spacer height20"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">
                    	<?=_('Port Label')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('A short description of the port. Good practice would be to label it after the port on the hardware interface or the band/frequency of the radio connected to it.')?>"></i>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
					  <input class="form-control portLabel" type="text" id="portLabel" name="portLabel" value="" placeholder="<?=_('Port 1')?>">
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
							<label class="btn btn-default">
								<input type="radio" id="portTypeGPIO" name="portType" value="GPIO"><?=_('GPIO')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portTypeHiDraw" name="portType" value="HiDraw"><?=_('Hidraw')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portTypeSerial" name="portType" value="Serial"><?=_('USB Serial')?>
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
							<label class="btn btn-default">
								<input type="radio" id="portDuplexHalf" name="portDuplex" value="half"><?=_('Half Duplex')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portDuplexFull" name="portDuplex" value="full"><?=_('Full Duplex')?>
							</label>
						</div>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

			  <div class="clearfix"></div>


			<!-- ******************************* -->

<div id="gpioWrap">

			  <div class="x_title"><h4><i class="fa fa-exchange"></i> <?=_('GPIO')?></h4></div>

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12">
                    	<?=_('Receive Control Mode')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting controls how this port is triggered by an incoming signal from the receiver. Carrier Operated Squelch (COS) is the recommended control method.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
						<select id="rxMode" name="rxMode" class="form-control rxMode">
							<option value="cos">COS</option>
							<option value="vox">VOX</option>
						</select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

                  <div id="voxMsg">
                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  <div class="alert alert-success alert-dismissible fade in" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	                    </button>
	                    <strong><?=_('WARNING:')?></strong> <?=_('The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It is strongly recommended that you use the COS Mode if at all possible.')?>
	                  </div>
                  </div>
                  </div>

                  <div id="rxGPIO_Grp" class="form-group">
                    <label class="control-label col-md-5 col-sm-4 col-xs-12">
                    	<?=_('Receive COS Pin')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that receives the COS signal from the receiver.')?>"></i>
                    </label>
                    <div class="col-md-3 col-sm-4 col-xs-6">
					  <input id="rxGPIO" class="form-control" type="text" name="rxGPIO" placeholder="<?=_('GPIO')?>">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
					  <select id="rxGPIO_active" name="rxGPIO_active" class="form-control rxGPIO_active">
					  	<option value="high"><?=_('Active High')?></option>
					  	<option value="low"><?=_('Active Low')?></option>
					  </select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>
			  				                      

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-5 col-sm-4 col-xs-12">
                    	<?=_('Transmit PTT Pin')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter.')?>"></i>
                    </label>
                    <div class="col-md-3 col-sm-4 col-xs-6">
					  <input id="txGPIO" class="form-control" type="text" name="txGPIO" placeholder="<?=_('GPIO')?>">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
					  <select id="txGPIO_active" name="txGPIO_active" class="form-control txGPIO_active">
					  	<option value="high"><?=_('Active High')?></option>
					  	<option value="low"><?=_('Active Low')?></option>
					  </select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

			  <div class="clearfix"></div>
			  
</div>

			<!-- ******************************* -->

<div id="hidrawWrap">

			  <div class="x_title"><h4><i class="fa fa-exchange"></i> <?=_('Hidraw')?></h4></div>

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-12 col-sm-12 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Hidraw Device')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The HID device that you wish to toggle pins on.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="hidrawDev" name="hidrawDev" class="form-control hidrawDev">
						<option value="">---</option>
					  	<option value="/dev/hidraw0">/dev/hidraw0</option>
					  	<option value="/dev/hidraw1">/dev/hidraw1</option>
					  	<option value="/dev/hidraw2">/dev/hidraw2</option>
					  	<option value="/dev/hidraw3">/dev/hidraw3</option>
					  	<option value="/dev/hidraw4">/dev/hidraw4</option>
					  	<option value="/dev/hidraw5">/dev/hidraw5</option>
					  	<option value="/dev/hidraw6">/dev/hidraw6</option>
					  	<option value="/dev/hidraw7">/dev/hidraw7</option>
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
					  <select id="hidrawRX_cos" name="hidrawRX_cos" class="form-control hidrawRX_cos">
						<option value="">---</option>
					  	<option value="VOL_UP">VOL_UP</option>
					  	<option value="VOL_DN">VOL_DN</option>
					  	<option value="MUTE_PLAY">MUTE_PLAY</option>
					  	<option value="MUTE_REC">MUTE_REC</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $hidrawRX_cos_invert = (isset($currPortSettings['hidrawRX_cos_invert'])) ? $currPortSettings['hidrawRX_cos_invert'] : 'false'; ?>
					  <input id="hidrawRX_cos_invert" name="hidrawRX_cos_invert" type="checkbox" class="js-switch" value="true">
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>
                  
				  <div class="clearfix spacer height20"></div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="hidrawTX_ptt" name="hidrawTX_ptt" class="form-control hidrawTX_ptt">
						<option value="">---</option>
					  	<option value="GPIO1">GPIO1</option>
					  	<option value="GPIO2">GPIO2</option>
					  	<option value="GPIO3">GPIO3</option>
					  	<option value="GPIO4">GPIO4</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="hidrawTX_ptt_invert" name="hidrawTX_ptt_invert" type="checkbox" class="js-switch" value="true">
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

			  <div class="clearfix"></div>

</div>

			<!-- ******************************* -->

<div id="serialWrap">

			  <div class="x_title"><h4><i class="fa fa-exchange"></i> <?=_('Serial')?></h4></div>

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-12 col-sm-12 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Serial Device')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The serial device that you wish to toggle pins on.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="serialDev" name="serialDev" class="form-control serialDev">
						<option value="">---</option>
					  	<option value="/dev/ttyUSB0">/dev/ttyUSB0</option>
					  	<option value="/dev/ttyUSB1">/dev/ttyUSB1</option>
					  	<option value="/dev/ttyUSB2">/dev/ttyUSB2</option>
					  	<option value="/dev/ttyUSB3">/dev/ttyUSB3</option>
					  	<option value="/dev/ttyUSB4">/dev/ttyUSB4</option>
					  	<option value="/dev/ttyUSB5">/dev/ttyUSB5</option>
					  	<option value="/dev/ttyUSB6">/dev/ttyUSB6</option>
					  	<option value="/dev/ttyUSB7">/dev/ttyUSB7</option>
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
					  <select id="serialRX_cos" name="serialRX_cos" class="form-control serialRX_cos">
						<option value="">---</option>
					  	<option value="DCD"><?=_('DCD')?></option>
					  	<option value="CTS"><?=_('CTS')?></option>
					  	<option value="DSR"><?=_('DSR')?></option>
					  	<option value="RI"><?=_('RI')?></option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="serialRX_cos_invert" name="serialRX_cos_invert" type="checkbox" class="js-switch" value="true">
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>
                  
				  <div class="clearfix spacer height20"></div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="serialTX_ptt" name="serialTX_ptt" class="form-control serialTX_ptt">
						<option value="">---</option>
					  	<option value="RTS"><?=_('RTS')?></option>
					  	<option value="DTR"><?=_('DTR')?></option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="serialTX_ptt_invert" name="serialTX_ptt_invert" type="checkbox" class="js-switch" value="true">
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

			  <div class="clearfix"></div>

</div>

			<!-- ******************************* -->

			  <div class="x_title"><h4><i class="fa fa-volume-up"></i> <?=_('Audio')?></h4></div>

			  <div class="clearfix spacer height20"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-5 col-sm-5 col-xs-12"><?=_('RX Audio (Input)')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Audio coming out of the receiver into the controller.')?>"></i>
                	</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
					  <select id="rxAudioDev" name="rxAudioDev" class="form-control rxAudioDev">

<option>---</option><option value="alsa:plughw:0|0">USBStreamer (Channel 1)</option><option value="alsa:plughw:0|1">USBStreamer (Channel 2)</option><option value="alsa:plughw:0|2">USBStreamer (Channel 3)</option><option value="alsa:plughw:0|3">USBStreamer (Channel 4)</option><option value="alsa:plughw:0|4">USBStreamer (Channel 5)</option><option value="alsa:plughw:0|5">USBStreamer (Channel 6)</option><option value="alsa:plughw:0|6">USBStreamer (Channel 7)</option><option value="alsa:plughw:0|7">USBStreamer (Channel 8)</option><option value="alsa:plughw:0|8">USBStreamer (Channel 9)</option><option value="alsa:plughw:0|9">USBStreamer (Channel 10)</option><option value="alsa:plughw:1|0">USB PnP Sound Device (Mono)</option><option value="alsa:plughw:2|0">Fe-Pi Audio (Left)</option><option value="alsa:plughw:2|1">Fe-Pi Audio (Right)</option><option value="alsa:plughw:3|0">USB Audio Device (Mono)</option>
						  
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
					  <select id="txAudioDev" name="txAudioDev" class="form-control txAudioDev">
						  
<option>---</option><option value="alsa:plughw:0|0">USBStreamer (Channel 1)</option><option value="alsa:plughw:0|1">USBStreamer (Channel 2)</option><option value="alsa:plughw:0|2">USBStreamer (Channel 3)</option><option value="alsa:plughw:0|3">USBStreamer (Channel 4)</option><option value="alsa:plughw:0|4">USBStreamer (Channel 5)</option><option value="alsa:plughw:0|5">USBStreamer (Channel 6)</option><option value="alsa:plughw:0|6">USBStreamer (Channel 7)</option><option value="alsa:plughw:0|7">USBStreamer (Channel 8)</option><option value="alsa:plughw:0|8">USBStreamer (Channel 9)</option><option value="alsa:plughw:0|9">USBStreamer (Channel 10)</option><option value="alsa:plughw:1|0">USB PnP Sound Device (Left)</option><option value="alsa:plughw:1|1">USB PnP Sound Device (Right)</option><option value="alsa:plughw:2|0">Fe-Pi Audio (Left)</option><option value="alsa:plughw:2|1">Fe-Pi Audio (Right)</option><option value="alsa:plughw:3|0">USB Audio Device (Left)</option><option value="alsa:plughw:3|1">USB Audio Device (Right)</option>						  
						  
					  </select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

			  <div class="clearfix"></div>



			</div> <!-- End portSettingsWrap -->

			<!-- ******************************* -->








Select Interface:



Receiver Settings (RX)
The receiver settings are what interface the OpenRepeater controller with your receive radio, or the input of the repeater. The most common and most reliable receive mode would be COS (Carrier Operated Switch). When the repeater’s squelch opens (or tone squelch if you have a receive tone set in the radio) an electronic trigger from the radio interfaces with some basic circuitry to trigger an input GPIO pin on the OpenRepeater Controller to go low to ground (active state) and pull high when the squelch is closed. Audio from the output of the receiver is routed into the selected audio input for the port. Together these will make up the input side of the port and be repeated to the transmit side of the port and other ports or Echolink if enabled.

Receive Mode

This will determine how the repeater is activated. The COS Mode is recommended.

Receive GPIO Pin
  
The GPIO input pin that will trigger the COS and whether it should be active high or low. See online documentation for wiring.

WARNING: The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible.
Receive Audio Input

The audio input that processes receive audio.
Transmitter Settings (TX)
The transmitter settings are used to interface the OpenRepeater controller with transmitter to rebroadcast transmissions and identification. The GPIO pin is use to trigger the PTT on the radio with some basic interface circuitry. The audio output of the controller interfaces with the audio/mic input on the transmitter.

Transmit GPIO Pin
  
The GPIO output pin that controls PTT on the transmitter and whether it should be active high or low. See online documentation for wiring.
Transmit Audio Output

The audio output that sends audio to transmitter.



                      </div>

</form>

<? ################################################################################ ?>

                      <div id="step-4">
                        <h3 class="StepTitle"><strong><?=_('Step 4')?></strong> - <?=_('Verify Settings')?></h3>

Confirm Settings
Here is what you have entered. Please confirm that this is correct, if not use the back navigation at the bottom of each page to go back and make corrections. This will be the minimum requirements to get OpenRepeater up and running. Once you have verified it is working, you can change other settings. Upon continuing, the settings you have chosen below will be applied to the repeater configuration.

 

Repeater Callsign:
Interface Board
Manufacture: ICS Controllers
Model: Pi Repeater 2X
Version: v3.1+


                      </div>

<? ################################################################################ ?>

                      <div id="step-5">
                        <h3 class="StepTitle"><strong><?=_('Step 5')?></strong> - <?=_('Finish Up')?></h3>

                      </div>
                      
<? ################################################################################ ?>
                      
                    </div>
                    <!-- End SmartWizard Content -->


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

<script>
	var wizardSettingsJSON = '<?= json_encode($wizardSettingsArray) ?>';
console.log(wizardSettingsJSON);	
</script>

<?php include('../includes/footer.php'); ?>