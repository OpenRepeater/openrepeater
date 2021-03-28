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

$customJS = 'page-ports.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-ports.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
$ports = $Database->get_ports();
$SVXLink = new SVXLink(null, null, null);

$SoundDevices = new SoundDevices();
$device_list = $SoundDevices->get_device_list();
$device_in_count = $SoundDevices->get_device_in_count();
$device_out_count = $SoundDevices->get_device_out_count();
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
                    <div class="accordion" id="portList" role="tablist" aria-multiselectable="true">
					  	<!-- Template rows will be inserted here by jQuery -->




<!--
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
-->

                    </div> <!-- end of accordion -->


                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><i class="fa fa-link"></i> <?=_('Link Group Settings')?></h4></div>

				  <form id="linkGroupForm" class="linkGroupForm">

                  <div class="x_content">

		              <div class="col-md-3 col-sm-6 col-xs-12">
					  	<div class="x_title"><h4><?=_('Link Group 1')?></h4></div>
		
			              <div class="form-group">
			                <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Activate')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When enabled, the link will be connected automatically during startup. With this setup the behavior of the timeout (if set) will be inverted. This means if a link is manually disconnected by a user, it will be automatically reconnected after the time specified by the timeout setting. If there is no timeout set, no automatic reactivation will be made.')?>"></i>
			                </label>
			                <div class="col-md-6 col-sm-6 col-xs-6">
			                  <input id="LG1_defaultActive" name="LG1_defaultActive" type="checkbox" class="js-switch LG_defaultActive" /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input id="LG1_timeout" name="LG1_timeout" type="number" class="form-control" placeholder="0">
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
			                  <input id="LG2_defaultActive" name="LG2_defaultActive" type="checkbox" class="js-switch LG_defaultActive" /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input id="LG2_timeout" name="LG2_timeout" type="number" class="form-control" placeholder="0">
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
			                  <input id="LG3_defaultActive" name="LG3_defaultActive" type="checkbox" class="js-switch LG_defaultActive" /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input id="LG3_timeout" name="LG3_timeout" type="number" class="form-control" placeholder="0">
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
			                  <input id="LG4_defaultActive" name="LG4_defaultActive" type="checkbox" class="js-switch LG_defaultActive" /> 
			                </div>
			              </div>
		
						  <div class="clearfix"></div>
		
		                  <div class="form-group">
		                    <label class="control-label col-md-6 col-sm-6 col-xs-6"><?=_('Timeout')?>
							  <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of seconds after which the link will be automatically deactivated if there have been no activity.')?>"></i>
		                    </label>
		                    <div class="col-md-6 col-sm-6 col-xs-6">
		                      <input id="LG4_timeout" name="LG4_timeout" type="number" class="form-control" placeholder="0">
		                    </div>
		                  </div>
		              </div>
		

                  </div>

				  </form>
                </div>


              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->







<? ### ROW TEMPLATE ######################################################## ?>
<script id="rowTemplate" type = "text/template">
  <div id="portNum%%currPortNum%%" class="panel portSection">
    <a class="panel-heading collapsed" role="tab" id="accordionHeading%%currPortNum%%" data-toggle="collapse" data-parent="#accordion" href="#accordionCollapse%%currPortNum%%" aria-expanded="false" aria-controls="accordionCollapse%%currPortNum%%">
	  <div class="col-md-6 col-sm-6 col-xs-6">
          <h4 class="panel-title"><strong><?=_('Port')?> #%%currPortNum%%</strong>: <span>%%currPortLabel%%<span></h4>
	  </div>
	  <div class="col-md-6 col-sm-6 col-xs-6 right">
        <span class="right">

          <span class="portLabelDuplexFull label label-danger" data-toggle="tooltip" data-placement="top" title="<?=_('Full Duplex Port')?>" style="display: none;"><?=_('Duplex')?></span>
          <span class="portLabelDuplexHalf label label-primary" data-toggle="tooltip" data-placement="top" title="<?=_('Half Duplex Port')?>" style="display: none;"><?=_('Simplex')?></span>

		  <span class="portLabelLinkGrp 1 badge bg-green" data-toggle="tooltip" data-placement="top" title="<?=_('Belongs to Link Group')?> 1" style="display: none;"><i class="fa fa-link"></i> <span>1</span></span>
		  <span class="portLabelLinkGrp 2 badge bg-purple" data-toggle="tooltip" data-placement="top" title="<?=_('Belongs to Link Group')?> 2" style="display: none;"><i class="fa fa-link"></i> <span>2</span></span>
		  <span class="portLabelLinkGrp 3 badge bg-blue-sky" data-toggle="tooltip" data-placement="top" title="<?=_('Belongs to Link Group')?> 3" style="display: none;"><i class="fa fa-link"></i> <span>3</span></span>
		  <span class="portLabelLinkGrp 4 badge bg-orange" data-toggle="tooltip" data-placement="top" title="<?=_('Belongs to Link Group')?> 4" style="display: none;"><i class="fa fa-link"></i> <span>4</span></span>

        </span> 
	  </div>
	  <div class="clearfix"></div>
    </a>

    <div id="accordionCollapse%%currPortNum%%" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordionHeading%%currPortNum%%">

      <form id="port%%currPortNum%%form" class="portForm" data-port-form="%%currPortNum%%">

      <div class="panel-body">


        <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab" class="nav nav-tabs tabs" role="tablist">
            <li role="presentation" class="tabGeneral active">
            	<a href="#tab_general%%currPortNum%%" id="general-tab%%currPortNum%%" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-gear"></i> <?=_('General')?></a>
            </li>
            <li role="presentation" class="tabAudio">
            	<a href="#tab_audio%%currPortNum%%" role="tab" id="audio-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-volume-up"></i> <?=_('Audio')?></a>
            </li>
            <li role="presentation" id="tabGPIO%%currPortNum%%" class="tabGPIO" style="display: none;">
            	<a href="#tab_gpio%%currPortNum%%" role="tab" id="gpio-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('GPIO')?></a>
            </li>
            <li role="presentation" id="tabHidraw%%currPortNum%%" class="tabHidraw" style="display: none;">
            	<a href="#tab_hidraw%%currPortNum%%" role="tab" id="hidraw-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('Hidraw')?></a>
            </li>
            <li role="presentation" id="tabSerial%%currPortNum%%" class="tabSerial" style="display: none;">
            	<a href="#tab_serial%%currPortNum%%" role="tab" id="serial-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-exchange"></i> <?=_('Serial')?></a>
            </li>
            <li role="presentation" class="tabModules">
            	<a href="#tab_modules%%currPortNum%%" role="tab" id="mdoules-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-plug"></i> <?=_('Modules')?></a>
            </li>
            <li role="presentation" class="tabOverrides">
            	<a href="#tab_override%%currPortNum%%" role="tab" id="override-tab%%currPortNum%%" data-toggle="tab" aria-expanded="false"><i class="fa fa-wrench"></i> <?=_('Overrides')?></a>
            </li>
          </ul>
          <div id="myTabContent" class="tab-content">

			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade active in" id="tab_general%%currPortNum%%" aria-labelledby="general-tab%%currPortNum%%">

			  <div class="clearfix spacer height20"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

			  	  <input type="hidden" name="portNum" value="%%currPortNum%%">

                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">
                    	<?=_('Port Label')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('A short description of the port. Good practice would be to label it after the port on the hardware interface or the band/frequency of the radio connected to it.')?>"></i>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
					  <input class="form-control portLabel" type="text" id="portLabel%%currPortNum%%" name="portLabel" value="%%currPortLabel%%" placeholder="<?=_('Port 1')?>">
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
								<input type="radio" id="portType%%currPortNum%%GPIO" name="portType" value="GPIO"><?=_('GPIO')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portType%%currPortNum%%HiDraw" name="portType" value="HiDraw"><?=_('Hidraw')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portType%%currPortNum%%Serial" name="portType" value="Serial"><?=_('USB Serial')?>
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
								<input type="radio" id="portDuplex%%currPortNum%%Half" name="portDuplex" value="half"><?=_('Half Duplex')?>
							</label>
							<label class="btn btn-default">
								<input type="radio" id="portDuplex%%currPortNum%%Full" name="portDuplex" value="full"><?=_('Full Duplex')?>
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
							<label class="btn btn-default">
								<input type="radio" id="linkGroupOff_Port%%currPortNum%%" name="linkGroup" value=""><?=_('OFF')?>
							</label>
							<label class="btn btn-success">
								<input type="radio" id="linkGroup1_Port%%currPortNum%%" name="linkGroup" value="1">1
							</label>
							<label class="btn btn-success">
								<input type="radio" id="linkGroup2_Port%%currPortNum%%" name="linkGroup" value="2">2
							</label>
							<label class="btn btn-success">
								<input type="radio" id="linkGroup3_Port%%currPortNum%%" name="linkGroup" value="3">3
							</label>
							<label class="btn btn-success">
								<input type="radio" id="linkGroup4_Port%%currPortNum%%" name="linkGroup" value="4">4
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
					  <input type="checkbox" id="portEnabled%%currPortNum%%" name="portEnabled" class="js-switch portEnabled" value="1"> 
					  <a href="#" id="deletePort%%currPortNum%%" class="deletePort"><i class="fa fa-trash-o"></i> Delete</a>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

            </div>

			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade" id="tab_audio%%currPortNum%%" aria-labelledby="audio-tab%%currPortNum%%">
			  <div class="clearfix spacer height20"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-5 col-sm-5 col-xs-12"><?=_('RX Audio (Input)')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Audio coming out of the receiver into the controller.')?>"></i>
                	</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
					  <?php $rxAudioDev = (isset($currPortSettings['rxAudioDev'])) ? $currPortSettings['rxAudioDev'] : ''; ?>
					  <select id="rxAudioDev%%currPortNum%%" name="rxAudioDev" class="form-control rxAudioDev">
						<option value="">---</option>
						<?php
						for ($device = 0; $device <  count($device_list); $device++) {
						   if ($device_list[$device]['direction'] == "IN") {
								$rxValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
								echo '<option value="'.$rxValue.'">INPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
							}
						}
						?>
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
					  <select id="txAudioDev%%currPortNum%%" name="txAudioDev" class="form-control txAudioDev">
						<option value="">---</option>
						<?php
						for ($device = 0; $device <  count($device_list); $device++) {
						   if ($device_list[$device]['direction'] == "OUT") {
								$txValue = 'alsa:plughw:'.$device_list[$device]['card'].'|'.$device_list[$device]['channel'];
								echo '<option value="'.$txValue.'">INPUT '.$device_list[$device]['card'].': '.$device_list[$device]['label'].' ('.$device_list[$device]['channel_label'].')</option>';
							}
						}
						?>
                      </select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

            </div>

			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade" id="tab_gpio%%currPortNum%%" aria-labelledby="gpio-tab%%currPortNum%%">

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-6 col-sm-6 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-6 col-sm-6 col-xs-12">
                    	<?=_('Receive Control Mode')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting controls how this port is triggered by an incoming signal from the receiver. Carrier Operated Squelch (COS) is the recommended control method.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
						<select id="rxMode%%currPortNum%%" name="rxMode" class="form-control rxMode">
							<option value="cos">COS</option>
							<option value="vox">VOX</option>
						</select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

                  <div id="voxMsg%%currPortNum%%">
                  <div class="col-md-12 col-sm-12 col-xs-12">
	                  <div class="alert alert-success alert-dismissible fade in" role="alert">
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	                    </button>
	                    <strong><?=_('WARNING:')?></strong> <?=_('The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It is strongly recommended that you use the COS Mode if at all possible.')?>
	                  </div>
                  </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-5 col-sm-4 col-xs-12">
                    	<?=_('Receive COS Pin')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that receives the COS signal from the receiver.')?>"></i>
                    </label>
                    <div class="col-md-3 col-sm-4 col-xs-6">
					  <input id="rxGPIO%%currPortNum%%" class="form-control" type="text" name="rxGPIO" placeholder="<?=_('GPIO')?>">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
					  <select id="rxGPIO_active%%currPortNum%%" name="rxGPIO_active" class="form-control rxGPIO_active">
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
					  <input id="txGPIO%%currPortNum%%" class="form-control" type="text" name="txGPIO" placeholder="<?=_('GPIO')?>">
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
					  <select id="txGPIO_active%%currPortNum%%" name="txGPIO_active" class="form-control txGPIO_active">
					  	<option value="high"><?=_('Active High')?></option>
					  	<option value="low"><?=_('Active Low')?></option>
					  </select>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

            </div>


			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade" id="tab_hidraw%%currPortNum%%" aria-labelledby="hidraw-tab%%currPortNum%%">

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-12 col-sm-12 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Hidraw Device')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The HID device that you wish to toggle pins on.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <?php $hidrawDev = (isset($currPortSettings['hidrawDev'])) ? $currPortSettings['hidrawDev'] : ''; ?>
					  <select id="hidrawDev%%currPortNum%%" name="hidrawDev[%%currPortNum%%]" class="form-control hidrawDev">
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
					  <select id="hidrawRX_cos%%currPortNum%%" name="hidrawRX_cos[%%currPortNum%%]" class="form-control hidrawRX_cos">
						<option value="">---</option>
					  	<option value="VOL_UP" <?=($hidrawRX_cos == 'VOL_UP') ? ' selected' : '';?>>VOL_UP</option>
					  	<option value="VOL_DN" <?=($hidrawRX_cos == 'VOL_DN') ?  ' selected' : '';?>>VOL_DN</option>
					  	<option value="MUTE_PLAY" <?=($hidrawRX_cos == 'MUTE_PLAY') ? ' selected' : '';?>>MUTE_PLAY</option>
					  	<option value="MUTE_REC" <?=($hidrawRX_cos == 'MUTE_REC') ?  ' selected' : '';?>>MUTE_REC</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $hidrawRX_cos_invert = (isset($currPortSettings['hidrawRX_cos_invert'])) ? $currPortSettings['hidrawRX_cos_invert'] : 'false'; ?>
					  <input type="checkbox" name="hidrawRX_cos_invert[%%currPortNum%%]" class="js-switch" value="true"<?=($hidrawRX_cos_invert == 'true' ? ' checked' : '')?>>
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
					  <select id="hidrawTX_ptt%%currPortNum%%" name="hidrawTX_ptt[%%currPortNum%%]" class="form-control hidrawTX_ptt">
						<option value="">---</option>
					  	<option value="GPIO1" <?=($hidrawTX_ptt == 'GPIO1') ? ' selected' : '';?>>GPIO1</option>
					  	<option value="GPIO2" <?=($hidrawTX_ptt == 'GPIO2') ?  ' selected' : '';?>>GPIO2</option>
					  	<option value="GPIO3" <?=($hidrawTX_ptt == 'GPIO3') ? ' selected' : '';?>>GPIO3</option>
					  	<option value="GPIO4" <?=($hidrawTX_ptt == 'GPIO4') ?  ' selected' : '';?>>GPIO4</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $hidrawTX_ptt_invert = (isset($currPortSettings['hidrawTX_ptt_invert'])) ? $currPortSettings['hidrawTX_ptt_invert'] : 'false'; ?>
					  <input type="checkbox" name="hidrawTX_ptt_invert[%%currPortNum%%]" class="js-switch" value="true"<?=($hidrawTX_ptt_invert == 'true' ? ' checked' : '')?>>
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

            </div>

			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade" id="tab_serial%%currPortNum%%" aria-labelledby="serial-tab%%currPortNum%%">

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-12 col-sm-12 col-xs-12">

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Serial Device')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The serial device that you wish to toggle pins on.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <?php $serialDev = (isset($currPortSettings['serialDev'])) ? $currPortSettings['serialDev'] : ''; ?>
					  <select id="serialDev%%currPortNum%%" name="serialDev[%%currPortNum%%]" class="form-control serialDev">
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
					  <select id="serialRX_cos%%currPortNum%%" name="serialRX_cos[%%currPortNum%%]" class="form-control serialRX_cos">
						<option value="">---</option>
					  	<option value="DCD" <?=($serialRX_cos == 'DCD') ? ' selected' : '';?>>DCD</option>
					  	<option value="CTS" <?=($serialRX_cos == 'CTS') ?  ' selected' : '';?>>CTS</option>
					  	<option value="DSR" <?=($serialRX_cos == 'DSR') ? ' selected' : '';?>>DSR</option>
					  	<option value="RI" <?=($serialRX_cos == 'RI') ?  ' selected' : '';?>>RI</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $serialRX_cos_invert = (isset($currPortSettings['serialRX_cos_invert'])) ? $currPortSettings['serialRX_cos_invert'] : 'false'; ?>
					  <input type="checkbox" name="serialRX_cos_invert[%%currPortNum%%]" class="js-switch" value="true"<?=($serialRX_cos_invert == 'true' ? ' checked' : '')?>>
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
					  <select id="serialTX_ptt%%currPortNum%%" name="serialTX_ptt[%%currPortNum%%]" class="form-control serialTX_ptt">
						<option value="">---</option>
					  	<option value="RTS" <?=($serialTX_ptt == 'RTS') ?  ' selected' : '';?>>RTS</option>
					  	<option value="DTR" <?=($serialTX_ptt == 'DTR') ? ' selected' : '';?>>DTR</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $serialTX_ptt_invert = (isset($currPortSettings['serialTX_ptt_invert'])) ? $currPortSettings['serialTX_ptt_invert'] : 'false'; ?>
					  <input type="checkbox" name="serialTX_ptt_invert[%%currPortNum%%]" class="js-switch" value="true"<?=($serialTX_ptt_invert == 'true' ? ' checked' : '')?>>
                      <label><?=_('Invert Pin')?></label>
                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>

			  </div>

            </div>

			<!-- ******************************* -->

            <div role="tabpanel" class="tab-pane fade" id="tab_modules%%currPortNum%%" aria-labelledby="modules-tab%%currPortNum%%">

			  <div class="clearfix spacer height30"></div>

			  <div class="col-md-12 col-sm-12 col-xs-12">

			  	WIP...
			  	
			  </div>

            </div>

			<!-- ******************************* -->

			<div role="tabpanel" class="tab-pane fade" id="tab_override%%currPortNum%%" aria-labelledby="override-tab%%currPortNum%%">

			  <div class="clearfix spacer height10"></div>

			  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Logic Section')?></h4>
                    <div class="clearfix"></div>
                  </div>

				  <div class="input_fields_wrap" id="port%%currPortNum%%local" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="local">
						<div class="innerWrap"></div>
						<button class="btn btn-success btn-xs add_field_button"><i class="fa fa-plus-circle"></i> <?=_('Add Field')?></button>
				  </div>
				  <div class="clearfix spacer height20"></div>
			  </div>

			  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('RX Section')?></h4>
                    <div class="clearfix"></div>
                  </div>

					<div class="input_fields_wrap" id="port%%currPortNum%%rx" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="rx">
						<div class="innerWrap"></div>
						<button class="btn btn-success btn-xs add_field_button"><i class="fa fa-plus-circle"></i> <?=_('Add Field')?></button>
					</div>

				  <div class="clearfix spacer height20"></div>
			  	
			  </div>


			  <div class="col-md-4 col-sm-4 col-xs-12 advOptionGrp">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('TX Section')?></h4>
                    <div class="clearfix"></div>
                  </div>

					<div class="input_fields_wrap" id="port%%currPortNum%%tx" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="tx">
						<div class="innerWrap"></div>
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
</script>
<? ######################################################################### ?>

<script id="advFieldsTemplate" type = "text/template">
	<div>
		<select id="adv_%%TYPE%%_%%PORT%%_%%ROW%%_name" name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][name]" class="form-control advOptionKey">%%OPTIONS%%</select>
		<input class="form-control advOptionValue" type="text" id="adv_%%TYPE%%_%%PORT%%_%%ROW%%_value" name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][value]" placeholder="<?=_('Value')?>">
		<button class="form-control remove_field">
			<i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Delete Row"></i>
		</button>
	</div>
</script>



<script>
	var portList = '<?= json_encode($ports) ?>';
	var linkGroupSettings = '<?= $settings['LinkGroup_Settings'] ?>';
	var max_fields = 10; //maximum input boxes allowed per section

	var logicOptions = <?=$SVXLink->get_adv_svxlink_options('logic')?>;
	var rxOptions = <?=$SVXLink->get_adv_svxlink_options('rx')?>;
	var txOptions = <?=$SVXLink->get_adv_svxlink_options('tx')?>;


	var modal_AddPortTitle = '<?=_('Add Port')?>';
	var modal_AddPortBody = '<p><?=_('What type of port do you wish to add?')?></p><select id="addPortType" name="addPortType" class="form-control"><option value="local" selected><?=_('Local Analog Port')?></option><option value="voip"><?=_('Test VOIP')?></option></select>';

</script>


<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>