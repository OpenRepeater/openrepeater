            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('EchoLink Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Basic Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('EchoLink Callsign')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The callsign to use to login to the EchoLink directory server.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="W1AW-R">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('EchoLink Password')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The EchoLink directory server password to use.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
							<input type="password" id="password" name="password" class="form-control" data-toggle="password">
                        </div>
                      </div>



                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Sysop Name')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The name of the person or club that is responsible for this system.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="ORP Test Server">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Location')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The location of the station.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="West Virginia">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12"><?=_('Description')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('A longer description that is sent to remote stations upon connection. This description should typically include detailed station information like QTH, transceiver frequency/power, antenna, CTCSS tone frequency etc.')?>"></i>
                        </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
	                      <textarea class="form-control" style="resize: vertical; min-height: 75px;">This is a test server for OpenRepeater Project</textarea>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Module Timeout')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is how many seconds of inactivity to wait for until the module is disabled.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="300 secs">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Auto Connect (Optional)')?></h4></div>

                  <div class="x_content">
				  	<p><?=_('Set these settings to automatically connect to an EchoLink Node. OpenRepeater will auto connect only when no other station is connected. Please ensure that you have permission to make persistent connections to the desired node and that their systems will allow this.')?></p>

                    <br />
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Use Auto Connect')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Automatically connect to selected node.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="checkbox" class="js-switch" checked /> 
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Auto Connect Node ID')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Set this variable to an EchoLink ID that you want to automatically connect to.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="<?=_('Node ID')?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Auto Connect Retry Time')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Set this to the time in seconds that you want in between auto connect attempts.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="120" placeholder="<?=_('Seconds')?>">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>



              <div class="col-md-6 col-xs-12">

                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
					<strong><?=_('Note About EchoLink')?>:</strong> 
					<?=_('The EchoLink® network allows licensed Amateur Radio stations to communicate with one another over the Internet using VOIP technology.  This module allows worldwide connections to be made between other repeaters or to individuals using EchoLink nodes. In order to use EchoLink the following must be done:')?>
					<ul>
						<li><?=_('You must validate your callsign with the EchoLink network to enter in the settings below. For repeater operation this is you callsign followed by a "-R" (i.e. X#XXX-R)')?></li>
						<li><?=_('This OpenRepeater controller must be connected to the internet in order for this to function.')?></li>
						<li><?=_('EchoLink requires that your router or firewall allow inbound and outbound UDP to ports 5198 and 5199, and outbound TCP to port 5200.  If you are using a home-network router, you will also need to configure the router to "forward" UDP ports 5198 and 5199 to the IP address assigned to this OpenRepeater controller.')?></li>
					</ul>
					<?=_('Visit the EchoLink Website')?> (<a href="http://www.echolink.org/" class="alert-link" target="_blank">http://www.echolink.org/</a>) <?=_('for more details on setting up your network and validating your callsign.')?>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Proxy Settings (Optional)')?></h4></div>

                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Proxy Server')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('If set, connect to the given EchoLink proxy server host. All EchoLink connections, both incoming and outgoing, will then go through the proxy.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="120" placeholder="<?=_('Seconds')?>">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Proxy Port')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Set the TCP port used for connecting to an EchoLink proxy server. Default is 8100.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="8100" placeholder="8100">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Proxy Password')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Set the EchoLink proxy password used when connecting to an EchoLink proxy server. Use the password PUBLIC for public proxy servers.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
						  <input type="password" id="proxy_password" name="proxy_password" class="form-control" data-toggle="password">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>

              </div>
              </div>
