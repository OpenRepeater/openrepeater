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
?>

<?php
$customJS = 'page-settings.js'; // 'file1.js, file2.js, ... '
// $customCSS = 'page-ports.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-cog"></i> <?=_('General Repeater Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
				  <div class="sectionStatus"><i class="fa"></i></div>
                  <div class="x_title"><h4><?=_('Basic Settings')?></h4></div>
                  
                  <div class="x_content">
                    <br />
                    <form id="settingsFormBasic" class="settingsForm form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Call Sign')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This will be used for identification.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input id="callSign" name="callSign" type="text" class="form-control" style="text-transform: uppercase" value="<?= $settings['callSign'] ?>" placeholder="W1AW" required>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('TX Tail')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The amount of time before the transmitter drops.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <div class="input-group">
							<input id="txTailValueSec" name="txTailValueSec" type="number" class="form-control" value="<?= $settings['txTailValueSec'] ?>" placeholder="<?=_('Seconds')?>" aria-describedby="basic-addon2" required>
						    <span class="input-group-addon" id="basic-addon2"><?=_('Secs')?></span>
						  </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Timeout')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Timeout timer in seconds. (i.e. 4 minutes would equal 240 seconds.)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <div class="input-group">
							<input id="repeaterTimeoutSec" name="repeaterTimeoutSec" type="number" class="form-control" value="<?= $settings['repeaterTimeoutSec'] ?>" placeholder="<?=_('Seconds')?>" aria-describedby="basic-addon2">
						    <span class="input-group-addon" id="basic-addon2"><?=_('Secs')?></span>
						  </div>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>





                <div class="x_panel">
				  <div class="sectionStatus"><i class="fa"></i></div>
                  <div class="x_title"><h4><?=_('DTMF Remote Disable')?></h4></div>

                  <div class="x_content">
                    <br />
                    <form id="settingsFormRemoteDisable" class="settingsForm form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Use Remote Disable?')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Enable this to be able to disable the transmitter by entering DTMF command.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
	                      <input type="hidden" name="repeaterDTMF_disable" value="False">
                          <input id="repeaterDTMF_disable" name="repeaterDTMF_disable" type="checkbox" class="js-switch" value="True" <?= ($settings['repeaterDTMF_disable'] == 'True') ? ' checked': ''; ?>/>
                        </div>
                      </div>

                      <div class="form-group remoteDisableGroup collapse">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Pin Code')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This pin code will be used as part of the DTMF command to remotely disable. This should be unique and the longer the better.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <input id="repeaterDTMF_disable_pin" name="repeaterDTMF_disable_pin" type="password" class="form-control" data-toggle="password" value="<?= $settings['repeaterDTMF_disable_pin'] ?>" placeholder="<?=_('1234')?>">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


                <div class="x_panel">
				  <div class="sectionStatus"><i class="fa"></i></div>
                  <div class="x_title"><h4><?=_('CTCSS Settings')?></h4></div>

                  <div class="x_content">
                    <form id="settingsFormCTCSS" class="settingsForm form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Enable Software CTCSS')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This will enable software based CTCSS Encoding.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input id="useCTCSS" type="checkbox" class="js-switch"<?= ($settings['rxTone'] > 0 || $settings['rxTone'] > 0) ? ' checked': ''; ?> /> 
                        </div>
                      </div>


                  <div class="alert alert-warning useCTCSSgroup collapse" role="alert">
                    <strong><?=_('These are experimental settings.')?></strong> <?=_('It is recommend that you leave these set to none and set your CTCSS tones in your radios.')?>
                  </div>

                      <div class="form-group useCTCSSgroup collapse">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('RX Tone (Hz)')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The CTCSS tone you have to transmit to "open" the repeater.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <select id="rxTone" name="rxTone" class="form-control">
                            <option value="0"<?= ($settings['rxTone'] == 0) ? ' selected': ''; ?>><?= _('(none)') ?></option>
							<?php
								$toneSuffix = ' ' . _('Hz');
								foreach($Database->get_ctcss() as $freq => $code) {
									$curValue = number_format((float)$freq, 1, '.', '');
									$curLabel = $curValue . $toneSuffix;
									$option_string = ($settings['rxTone'] == $freq) ? ' selected': '';
							        echo '<option value="' . $curValue . '"'.$option_string.'>' . $curLabel . '</option>';
								}
							?>
                          </select>
                        </div>
                      </div>

                      <div class="form-group useCTCSSgroup collapse">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('TX Tone (Hz)')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The CTCSS tone you need to hear the repeater.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">

                          <select id="txTone" name="txTone" class="form-control">
                            <option value="0"<?= ($settings['txTone'] == 0) ? ' selected': ''; ?>><?= _('(none)') ?></option>
							<?php
								$toneSuffix = ' ' . _('Hz');
								foreach($Database->get_ctcss() as $freq => $code) {
									$curValue = number_format((float)$freq, 1, '.', '');
									$curLabel = $curValue . $toneSuffix;
									$option_string = ($settings['txTone'] == $freq) ? ' selected': '';
							        echo '<option value="' . $curValue . '"'.$option_string.'>' . $curLabel . '</option>';
								}
							?>
                          </select>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>

<?php
$locationInfo = $settings['Location_Info'];
if(!empty($locationInfo)) {
	$locationInfo = json_decode($locationInfo);
}
?>

              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
				  <div class="sectionStatus"><i class="fa"></i></div>
                  <div class="x_title"><h4><?=_('Location Settings')?></h4></div>

                  <div class="x_content">
                    <form id="locationForm" class="locationForm form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('EchoLink Status Servers')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('EchoLink server which receives the current position and status messages (via UDP)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Echolink_Status_Servers = (!empty($locationInfo->Echolink_Status_Servers)) ? $locationInfo->Echolink_Status_Servers : ''; ?>
                          <input id="Echolink_Status_Servers" name="Echolink_Status_Servers" type="text" class="form-control" value="<?=$Echolink_Status_Servers ?>" placeholder="aprs.echolink.org:5199">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('APRS Server List')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Server of the APRS network that receives the position reports/status information (via TCP)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $APRS_ServerList = (!empty($locationInfo->APRS_ServerList)) ? $locationInfo->APRS_ServerList : ''; ?>
                          <input id="APRS_ServerList" name="APRS_ServerList" type="text" class="form-control" value="<?=$APRS_ServerList?>" placeholder="i.e. noam.aprs2.net:14580">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Latitude/Longitude')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Geographical position in decimal format')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Latitude = (!empty($locationInfo->Latitude)) ? $locationInfo->Latitude : ''; ?>
                          <input id="Latitude" name="Latitude" type="number" class="form-control" value="<?=$Latitude?>" placeholder="41.714762">

                          <div class="input-group">
						  <?php $Longitude = (!empty($locationInfo->Longitude)) ? $locationInfo->Longitude : ''; ?>
                          <input id="Longitude" name="Longitude" type="number" class="form-control" value="<?=$Longitude?>" placeholder="-72.727193">
                          <span class="input-group-btn">
						  	<button id="getGPS" type="button" class="btn btn-primary"><i class="fa fa-globe"></i> <?=_('GPS')?></button>
						  </span>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('APRS Station Type')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Enter the type of station this is. This will append the appropriate suffix to the callsign when reporting to APRS network.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						<?php $APRS_Station_Type = (!empty($locationInfo->APRS_Station_Type)) ? $locationInfo->APRS_Station_Type : ''; ?>
						  <select id="APRS_Station_Type" name="APRS_Station_Type" class="form-control">
							<option value="repeater"<?=($APRS_Station_Type == 'repeater')?' selected':'';?>><?=_('Repeater')?></option>
							<option value="link"<?=($APRS_Station_Type == 'link')?' selected':'';?>><?=_('Link')?></option>
						  </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Frequency')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Frequency output of the transmitter in MHz.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Frequency = (!empty($locationInfo->Frequency)) ? $locationInfo->Frequency : ''; ?>
						  <div class="input-group">
                            <input id="Frequency" name="Frequency" type="number" class="form-control" value="<?=$Frequency?>" placeholder="444.65"  aria-describedby="basic-addon2">
						    <span class="input-group-addon" id="basic-addon2"><?=_('MHz')?></span>
						  </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Tone')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Indicates whether a ring tone or CTCSS tone is required for radio communication.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">

						  <?php $Tone = (!empty($locationInfo->Tone)) ? $locationInfo->Tone : '0'; ?>
                          <select id="Tone" name="Tone" class="form-control">
						  	<option value="0"<?=($Tone === '0')?' selected':'';?>><?=_('None')?></option>
							<?php
								foreach($Database->get_ctcss() as $freq => $code) {
									$option_string = '<option value="'.(int)$freq.'"';
									$option_string .= $Tone == (int)$freq ? ' selected>': '>';
									$option_string .= number_format((float)$freq, 1, '.', '').' '._('Hz').'</option>';
									echo $option_string;
								}
							?>
                          </select>


                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('TX Power')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The transmission power in Watts.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $TX_Power = (!empty($locationInfo->TX_Power)) ? $locationInfo->TX_Power : ''; ?>
						  <div class="input-group">
						  	<input id="TX_Power" name="TX_Power" type="number" class="form-control" value="<?=$TX_Power?>" min="0" max="2000" placeholder="100" aria-describedby="basic-addon2">
						    <span class="input-group-addon" id="basic-addon2"><?=_('Watts')?></span>
						  </div>

                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Antenna Gain')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The antenna gain in dBd (not dBi).')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Antenna_Gain = (!empty($locationInfo->Antenna_Gain)) ? $locationInfo->Antenna_Gain : ''; ?>
						  <div class="input-group">
							<input id="Antenna_Gain" name="Antenna_Gain" type="number" class="form-control" value="<?=$Antenna_Gain?>" min="0" max="100" placeholder="9" aria-describedby="basic-addon2">
						    <span class="input-group-addon" id="basic-addon2"><?=_('dBd')?></span>
						  </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-12 col-xs-12"><?=_('Antenna Height')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The antenna height above the ground.')?>"></i>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-6">
						  <?php $Antenna_Height = (!empty($locationInfo->Antenna_Height)) ? $locationInfo->Antenna_Height : ''; ?>
                          <input id="Antenna_Height" name="Antenna_Height" type="number" class="form-control" value="<?=$Antenna_Height?>" min="0" placeholder="25">
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6">
						  <?php $Antenna_Height_Units = (!empty($locationInfo->Antenna_Height_Units)) ? $locationInfo->Antenna_Height_Units : 'f'; ?>
						  <select name="Antenna_Height_Units" class="form-control">
							<option value="f"<?=($Antenna_Height_Units == 'f')?' selected':'';?>><?=_('Feet')?></option>
							<option value="m"<?=($Antenna_Height_Units == 'm')?' selected':'';?>><?=_('Meters')?></option>
						  </select>
                        </div>

                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Antenna Direction')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Enter the main radiation direction of the transmitting antenna in degrees.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Antenna_Dir = (!empty($locationInfo->Antenna_Dir)) ? $locationInfo->Antenna_Dir : ''; ?>
						  <select id="Antenna_Dir" name="Antenna_Dir" class="form-control" value="<?=$Antenna_Dir?>">
							<option value=""<?=($Antenna_Dir == '')?' selected':'';?>><?=_('None')?></option>
							<option value="-1"<?=($Antenna_Dir == '-1')?' selected':'';?>><?=_('Omni')?></option>
							<option value="360"<?=($Antenna_Dir == '360')?' selected':'';?>><?=_('North')?></option>
							<option value="22.5"<?=($Antenna_Dir == '22.5')?' selected':'';?>>--<?=_('NNE')?></option>
							<option value="45"<?=($Antenna_Dir == '45')?' selected':'';?>>-<?=_('NE')?></option>
							<option value="67.5"<?=($Antenna_Dir == '67.5')?' selected':'';?>>--<?=_('ENE')?></option>
							<option value="90"<?=($Antenna_Dir == '90')?' selected':'';?>><?=_('East')?></option>
							<option value="112.5"<?=($Antenna_Dir == '112.5')?' selected':'';?>>--<?=_('ESE')?></option>
							<option value="135"<?=($Antenna_Dir == '135')?' selected':'';?>>-<?=_('SE')?></option>
							<option value="157.5"<?=($Antenna_Dir == '157.5')?' selected':'';?>>--<?=_('SSE')?></option>
							<option value="180"<?=($Antenna_Dir == '180')?' selected':'';?>><?=_('South')?></option>
							<option value="202.5"<?=($Antenna_Dir == '202.5')?' selected':'';?>>--<?=_('SSW')?></option>
							<option value="225"<?=($Antenna_Dir == '225')?' selected':'';?>>-<?=_('SW')?></option>
							<option value="247.5"<?=($Antenna_Dir == '247.5')?' selected':'';?>>--<?=_('WSW')?></option>
							<option value="270"<?=($Antenna_Dir == '270')?' selected':'';?>><?=_('West')?></option>
							<option value="292.5"<?=($Antenna_Dir == '292.5')?' selected':'';?>>--<?=_('WNW')?></option>
							<option value="315"<?=($Antenna_Dir == '315')?' selected':'';?>>-<?=_('NW')?></option>
							<option value="337.5"<?=($Antenna_Dir == '337.5')?' selected':'';?>>--<?=_('NNW')?></option>
							<option value="360"><?=_('North')?></option>
						  </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('APRS Path')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The APRS path, only important if the beacon is transmitted from a neighboring APRS digi, e.g. WIDE1-1')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $APRS_Path = (!empty($locationInfo->APRS_Path)) ? $locationInfo->APRS_Path : ''; ?>
                          <input id="APRS_Path" name="APRS_Path" type="text" class="form-control" value="<?=$APRS_Path?>" placeholder="WIDE1-1">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Beacon Interval')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The send interval in minutes. Values smaller than 10 are automatically set to 10 minutes.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Beacon_Interval = (!empty($locationInfo->Beacon_Interval)) ? $locationInfo->Beacon_Interval : ''; ?>
						  <div class="input-group">
							<input id="Beacon_Interval" name="Beacon_Interval" type="number" class="form-control" value="<?=$Beacon_Interval?>" min="10" placeholder="10"  aria-describedby="basic-addon2">
						    <span class="input-group-addon" id="basic-addon2"><?=_('Mins')?></span>
						  </div>

                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Statistics Interval')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Interval that statistics are sent into the APRS network')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Statistics_Interval = (!empty($locationInfo->Statistics_Interval)) ? $locationInfo->Statistics_Interval : ''; ?>
						  <select id="Statistics_Interval" name="Statistics_Interval" class="form-control">
							<option value="5"<?=($Statistics_Interval == '5')?' selected':'';?>>5 mins</option>
							<option value="10"<?=($Statistics_Interval == '10')?' selected':'';?>>10 mins (default)</option>
							<option value="15"<?=($Statistics_Interval == '15')?' selected':'';?>>15 mins</option>
							<option value="20"<?=($Statistics_Interval == '20')?' selected':'';?>>20 mins</option>
							<option value="25"<?=($Statistics_Interval == '25')?' selected':'';?>>25 mins</option>
							<option value="30"<?=($Statistics_Interval == '30')?' selected':'';?>>30 mins</option>
							<option value="35"<?=($Statistics_Interval == '35')?' selected':'';?>>35 mins</option>
							<option value="40"<?=($Statistics_Interval == '40')?' selected':'';?>>40 mins</option>
							<option value="45"<?=($Statistics_Interval == '45')?' selected':'';?>>45 mins</option>
							<option value="50"<?=($Statistics_Interval == '50')?' selected':'';?>>50 mins</option>
							<option value="55"<?=($Statistics_Interval == '55')?' selected':'';?>>55 mins</option>
							<option value="60"<?=($Statistics_Interval == '60')?' selected':'';?>>60 mins</option>
						  </select>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>

           </div>
          </div>
        </div>
        <!-- /page content -->


        
<script>
	var modal_gpsTitle = '<?=_('Get Location')?>';
	var modal_gpsBody = '<p><?=_('First, if you have a GPS connected we will try to get the GPS coordinates from it. If not, we will attempt to lookup your approximate location by your IP address. Otherwise, you may be required to enter this manually.')?></p>';
	var modal_gpsButton = '<?=_('Lookup')?>';
	var modal_gpsSuccessMsgTitle = '<?=_('Success')?>';
	var modal_gpsSuccessMsg = '<?=_('We have successfully acquired your location.')?>';
	var modal_gpsFailMsgTitle = '<?=_('There was a problem')?>';
	var modal_gpsFailMsg = '<?=_('There was a problem getting your location...but you can always enter it manually.')?>';

</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>