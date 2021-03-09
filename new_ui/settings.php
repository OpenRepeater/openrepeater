<?php include('includes/fakeDB.php'); ?>


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
                  <div class="x_title"><h4><?=_('Basic Settings')?></h4></div>
                  
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Call Sign')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This will be used for identification.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="W1AW">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('TX Tail')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The amount of time before the transmitter drops.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="2" placeholder="<?=_('Seconds')?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Timeout')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Timeout timer in seconds. (i.e. 4 minutes would equal 240 seconds.)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="240" placeholder="<?=_('Seconds')?>">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>





                <div class="x_panel">
                  <div class="x_title"><h4><?=_('DTMF Remote Disable')?></h4></div>

                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Use Remote Disable?')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Enable this to be able to disable the transmitter by entering DTMF command.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input id="remoteDisable" type="checkbox" class="js-switch" checked /> 
                        </div>
                      </div>

                      <div class="form-group remoteDisableGroup collapse">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Pin Code')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This pin code will be used as part of the DTMF command to remotely disable. This should be unique and the longer the better.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <input type="password" class="form-control" data-toggle="password" value="1234" placeholder="<?=_('1234')?>">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('CTCSS Settings')?></h4></div>

                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Enable Software CTCSS')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This will enable software based CTCSS Encoding.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input id="useCTCSS" type="checkbox" class="js-switch" /> 
                        </div>
                      </div>


                  <div class="alert alert-warning useCTCSSgroup collapse" role="alert">
                    <strong><?=_('These are settings experimental.')?></strong> <?=_('It is recommend that you leave these set to none and set your CTCSS tones in your radios.')?>
                  </div>

                      <div class="form-group useCTCSSgroup collapse">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('RX Tone (Hz)')?>
						  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The CTCSS tone you have to transmit to "open" the repeater.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <select id="rxCTCSS" class="form-control">
                            <option value="0">(none)</option>
							<?php
								$toneSuffix = ' ' . _('Hz');
								foreach($fakeCTCSS as $curCTCSS) {
									$curValue = $curCTCSS;
									$curLabel = number_format((float)$curCTCSS, 1, '.', '') . $toneSuffix;
							        echo '<option value="' . $curValue . '">' . $curLabel . '</option>';
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
                          <select id="txCTCSS" class="form-control">
                            <option value="0">(none)</option>
							<?php
								$toneSuffix = ' ' . _('Hz');
								foreach($fakeCTCSS as $curCTCSS) {
									$curValue = $curCTCSS;
									$curLabel = number_format((float)$curCTCSS, 1, '.', '') . $toneSuffix;
							        echo '<option value="' . $curValue . '">' . $curLabel . '</option>';
								}
							?>
                          </select>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>



              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Location Settings')?></h4></div>

                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('EchoLink Status Servers')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('EchoLink server which receives the current position and status messages (via UDP)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="aprs.echolink.org:5199" placeholder="aprs.echolink.org:5199">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('APRS Server List')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Server of the APRS network that receives the position reports/status information (via TCP)')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="" placeholder="i.e. noam.aprs2.net:14580">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Latitude/Longitude')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Geographical position in decimal format')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" id="locLatitude" class="form-control" value="41.714762" placeholder="<?=_('Latitude')?>">

                          <div class="input-group">
                          <input type="number" id="locLongitude" class="form-control" value="-72.727193" placeholder="<?=_('Longitude')?>">
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
						  <select class="form-control">
							<option value="repeater"><?=_('Repeater')?></option>
							<option value="link"><?=_('Link')?></option>
						  </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Frequency')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Frequency output of the transmitter in MHz.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="444.65" placeholder="444.65">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Tone')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Indicates whether a ring tone or CTCSS tone is required for radio communication.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <select class="form-control">
                            <option value="0">(none)</option>
							<?php
								$toneSuffix = ' ' . _('Hz');
								foreach($fakeCTCSS as $curCTCSS) {
									$curValue = $curCTCSS;
									$curLabel = number_format((float)$curCTCSS, 1, '.', '') . $toneSuffix;
							        echo '<option value="' . $curValue . '">' . $curLabel . '</option>';
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
                          <input type="number" class="form-control" value="100" placeholder="100">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Antenna Gain')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The antenna gain in dBd (not dBi).')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="9" placeholder="9">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-12 col-xs-12"><?=_('Antenna Height')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The antenna height above the ground.')?>"></i>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-6">
                          <input type="number" class="form-control" value="25" placeholder="25">
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6">
						  <?php $Antenna_Units = 'f'; ?>
						  <select class="form-control">
							<option value="f"<?=($Antenna_Units == 'f')?' selected':'';?>><?=_('Feet')?></option>
							<option value="m"<?=($Antenna_Units == 'm')?' selected':'';?>><?=_('Meters')?></option>
						  </select>
                        </div>

                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Antenna Direction')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Enter the main radiation direction of the transmitting antenna in degrees.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Antenna_Dir = '360'; ?>
						  <select class="form-control">
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
                          <input type="text" class="form-control" value="WIDE1-1" placeholder="WIDE1-1">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Beacon Interval')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The send interval in minutes. Values smaller than 10 are automatically set to 10 minutes.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="10" placeholder="10">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Statistics Interval')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Interval that statistics are sent into the APRS network')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <?php $Statistics_Interval = '10'; ?>
						  <select class="form-control">
							<option value="5"<?=($Statistics_Interval == '5')?' selected':'';?>><?=_('5 mins')?></option>
							<option value="10"<?=($Statistics_Interval == '10')?' selected':'';?>><?=_('10 mins (default)')?></option>
							<option value="15"<?=($Statistics_Interval == '15')?' selected':'';?>><?=_('15 mins')?></option>
							<option value="20"<?=($Statistics_Interval == '20')?' selected':'';?>><?=_('20 mins')?></option>
							<option value="25"<?=($Statistics_Interval == '25')?' selected':'';?>><?=_('25 mins')?></option>
							<option value="30"<?=($Statistics_Interval == '30')?' selected':'';?>><?=_('30 mins')?></option>
							<option value="35"<?=($Statistics_Interval == '35')?' selected':'';?>><?=_('35 mins')?></option>
							<option value="40"<?=($Statistics_Interval == '40')?' selected':'';?>><?=_('40 mins')?></option>
							<option value="45"<?=($Statistics_Interval == '45')?' selected':'';?>><?=_('45 mins')?></option>
							<option value="50"<?=($Statistics_Interval == '50')?' selected':'';?>><?=_('50 mins')?></option>
							<option value="55"<?=($Statistics_Interval == '55')?' selected':'';?>><?=_('55 mins')?></option>
							<option value="60"<?=($Statistics_Interval == '60')?' selected':'';?>><?=_('60 mins')?></option>
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