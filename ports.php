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
                  <div class="x_title">
			        <div class="sectionStatus linkGroupStatus"><i class="fa"></i></div>
                    <h4><i class="fa fa-link"></i> <?=_('Link Group Settings')?></h4>
                  </div>

				  <form id="linkGroupForm" class="linkGroupForm">

                  <div class="x_content">

		            <div id='linkGroup1' data-port-count="0" style="display: none;">
		              <div class="lg_wrapper">
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

						  <div class="clearfix"></div>

						  <div class="lgCount col-md-12 col-sm-12 col-xs-12">
						    <p><?=_('Ports in Group')?>: <span id="LG1_count">0</span></p>
						  </div>
		              </div>
		            </div>

		            <div id='linkGroup2' data-port-count="0" style="display: none;">
		              <div class="lg_wrapper">
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

						  <div class="clearfix"></div>

						  <div class="lgCount col-md-12 col-sm-12 col-xs-12">
						    <p><?=_('Ports in Group')?>: <span id="LG2_count">0</span></p>
						  </div>
		              </div>
		            </div>

		            <div id='linkGroup3' data-port-count="0" style="display: none;">
		              <div class="lg_wrapper">
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

						  <div class="clearfix"></div>

						  <div class="lgCount col-md-12 col-sm-12 col-xs-12">
						    <p><?=_('Ports in Group')?>: <span id="LG3_count">0</span></p>
						  </div>
		              </div>
		            </div>

		            <div id='linkGroup4' data-port-count="0" style="display: none;">
		              <div class="lg_wrapper">
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

						  <div class="clearfix"></div>

						  <div class="lgCount col-md-12 col-sm-12 col-xs-12">
						    <p><?=_('Ports in Group')?>: <span id="LG4_count">0</span></p>
						  </div>
		              </div>
		            </div>

                    <div id="no_lg_msg" class="col-md-12 col-sm-12 col-xs-12">
                      <h4><?=_('You must selected a link group under at least 2 ports to make them available.')?></h4>
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
<script id="rowTemplateAnalog" type = "text/template">
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

        <div class="sectionStatus portStatus"><i class="fa"></i></div>

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
                    	<?=_('Link Groups')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This allows you to add the port to one or more Link Groups. Ports in the same link group will be linked together. This is useful for linking two repeaters on different bands together or adding RF links to a repeater.')?>"></i>
                    </label>

                    <div class="col-md-8 col-sm-8 col-xs-12">

						<table class="lg_toggle_table">
						<tbody>
						  <tr>
						    <td><input type="checkbox" id="linkGroup1_Port%%currPortNum%%" class="js-switch linkGroup" data-linkGroup-num="1"></td>
						    <td><input type="checkbox" id="linkGroup2_Port%%currPortNum%%" class="js-switch linkGroup" data-linkGroup-num="2"></td>
						    <td><input type="checkbox" id="linkGroup3_Port%%currPortNum%%" class="js-switch linkGroup" data-linkGroup-num="3"></td>
						    <td><input type="checkbox" id="linkGroup4_Port%%currPortNum%%" class="js-switch linkGroup" data-linkGroup-num="4"></td>
						  </tr>
						  <tr>
						    <td><?=_('1')?></td>
						    <td><?=_('2')?></td>
						    <td><?=_('3')?></td>
						    <td><?=_('4')?></td>
						  </tr>
						</tbody>
						</table>

						<input type="hidden" id="linkGroup_Port%%currPortNum%%" name="linkGroup" class="linkGroup">

                    </div>
                  </div>

				  <div class="clearfix spacer height20"></div>
				  
                  <div class="form-group portEnabledWrap">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">
                    	<?=_('Enable Port')?> 
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This allows the port to be disabled, but retain the current settings.')?>"></i>
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
					  <input type="hidden" name="portEnabled" value="0">
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
					  <select id="rxAudioDev%%currPortNum%%" name="rxAudioDev" class="form-control rxAudioDev"></select>
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
					  <select id="txAudioDev%%currPortNum%%" name="txAudioDev" class="form-control txAudioDev"></select>
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

                  <div id="rxGPIO_Grp%%currPortNum%%" class="form-group">
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
					  <select id="hidrawDev%%currPortNum%%" name="hidrawDev" class="form-control hidrawDev">
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
					  <select id="hidrawRX_cos%%currPortNum%%" name="hidrawRX_cos" class="form-control hidrawRX_cos">
						<option value="">---</option>
					  	<option value="VOL_UP">VOL_UP</option>
					  	<option value="VOL_DN">VOL_DN</option>
					  	<option value="MUTE_PLAY">MUTE_PLAY</option>
					  	<option value="MUTE_REC">MUTE_REC</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <?php $hidrawRX_cos_invert = (isset($currPortSettings['hidrawRX_cos_invert'])) ? $currPortSettings['hidrawRX_cos_invert'] : 'false'; ?>
					  <input id="hidrawRX_cos_invert%%currPortNum%%" name="hidrawRX_cos_invert" type="checkbox" class="js-switch" value="true">
                      <label class="invertPinLabel"><?=_('Invert Pin')?></label>
                    </div>
                  </div>
                  
				  <div class="clearfix spacer height20"></div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="hidrawTX_ptt%%currPortNum%%" name="hidrawTX_ptt" class="form-control hidrawTX_ptt">
						<option value="">---</option>
					  	<option value="GPIO1">GPIO1</option>
					  	<option value="GPIO2">GPIO2</option>
					  	<option value="GPIO3">GPIO3</option>
					  	<option value="GPIO4">GPIO4</option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="hidrawTX_ptt_invert%%currPortNum%%" name="hidrawTX_ptt_invert" type="checkbox" class="js-switch" value="true">
                      <label class="invertPinLabel"><?=_('Invert Pin')?></label>
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
					  <select id="serialDev%%currPortNum%%" name="serialDev" class="form-control serialDev">
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
					  <select id="serialRX_cos%%currPortNum%%" name="serialRX_cos" class="form-control serialRX_cos">
						<option value="">---</option>
					  	<option value="DCD"><?=_('DCD')?></option>
					  	<option value="CTS"><?=_('CTS')?></option>
					  	<option value="DSR"><?=_('DSR')?></option>
					  	<option value="RI"><?=_('RI')?></option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="serialRX_cos_invert%%currPortNum%%" name="serialRX_cos_invert" type="checkbox" class="js-switch" value="true">
                      <label class="invertPinLabel"><?=_('Invert Pin')?></label>
                    </div>
                  </div>
                  
				  <div class="clearfix spacer height20"></div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?=_('Transmit PTT Pin')?>
                    	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The pin that sends the PTT signal to the transmitter. You have the option of inverting the control logic of this pin.')?>"></i>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
					  <select id="serialTX_ptt%%currPortNum%%" name="serialTX_ptt" class="form-control serialTX_ptt">
						<option value="">---</option>
					  	<option value="RTS"><?=_('RTS')?></option>
					  	<option value="DTR"><?=_('DTR')?></option>
					  </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
					  <input id="serialTX_ptt_invert%%currPortNum%%" name="serialTX_ptt_invert" type="checkbox" class="js-switch" value="true">
                      <label class="invertPinLabel"><?=_('Invert Pin')?></label>
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

				  <div class="input_fields_wrap advLocal_wrap" id="port%%currPortNum%%local" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="local">
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

					<div class="input_fields_wrap advRX_wrap" id="port%%currPortNum%%rx" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="rx">
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

					<div class="input_fields_wrap advTX_wrap" id="port%%currPortNum%%tx" data-port-num="%%currPortNum%%" data-real-count="0" data-ceiling-count="0" data-section-type="tx">
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
		<select id="adv_%%TYPE%%_%%PORT%%_%%ROW%%_name" class="form-control advOptionKey">%%OPTIONS%%</select>
		<input class="form-control advOptionValue" type="text" id="adv_%%TYPE%%_%%PORT%%_%%ROW%%_value" placeholder="<?=_('Value')?>">
		<button class="form-control remove_field">
			<i class="fa fa-minus-circle" data-toggle="tooltip" data-placement="top" title="Delete Row"></i>
		</button>
	</div>
</script>

<?php
$lg_raw = $settings['LinkGroup_Settings'];
$lg_array = json_decode($lg_raw);
$lg_count = 0;
foreach ($lg_array as $value) { $lg_count++; }
?>

<script>
	var portList = '<?= json_encode($ports) ?>';
	if (<?=$lg_count ?> > 0) { var linkGroupSettings = '<?= $settings['LinkGroup_Settings'] ?>'; } else { var linkGroupSettings = '{}'; }
	var max_fields = 10; //maximum input boxes allowed per section

	var logicOptions = <?=$SVXLink->get_adv_svxlink_options('logic')?>;
	var rxOptions = <?=$SVXLink->get_adv_svxlink_options('rx')?>;
	var txOptions = <?=$SVXLink->get_adv_svxlink_options('tx')?>;

	var modal_AddPortTitle = '<?=_('Add Port')?>';
	var modal_AddPortBody = '<p><?=_('What type of port do you wish to add?')?></p><select id="addPortType" name="addPortType" class="form-control"><option value="local" selected><?=_('Local Analog Port')?></option></select>';
// 	<option value="voip"><?=_('Test VOIP')?></option>

	var modal_DeletePortTitle = '<?= _('Delete Port') ?>';
	var modal_DeletePortBody = '<?= _('Are you sure you want to delete this port?') ?>';
	var modal_DeletePortBtnOK = '<?= _('Delete Forever') ?>';
	var modal_DeletePortProgressTitle = '<?= _('Deleting Port') ?>';
	var modal_DeletePortNotifyTitle = '<?= _('Port Deleted') ?>';
	var modal_DeletePortNotifyDesc = '<?= _('The port has been successfully deleted.') ?>';
	var modal_DeletePortErrorTitle = '<?= _('Error Deleting') ?>';
	var modal_DeletePortErrorDesc = '<?= _('There was an error deleting the requested port. Please try again later.') ?>';

	var modal_AudioScanWarningTitle = '<?= strtoupper( _('Warning') ) ?>';
	var modal_AudioScanWarningBody = '<?= _('By proceeding, SVXLink will need to be briefly stopped in order to query the system for available sound devices. This will interrupt any active communications. Please ensure that there is no activity before proceeding.') ?>';
	var modal_AudioScanWarningBtnOK = '<?= _('Proceed') ?>';
	var modal_AudioScanWarningBtnCancel = '<?= _('Get Me Out of Here!') ?>';
	var modal_LoadAudioTitle = '<?= _('Reading Sound Devices') ?>';

</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>