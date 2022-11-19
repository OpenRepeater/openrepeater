<?php
	include('../module_header.php');
?>

            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('FRN Gateway Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('User Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Username')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="testuser">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Callsign')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="W1AW">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Email Address')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="email" class="form-control" placeholder="email@domain.com">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Password')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
							<input type="password" id="password" name="password" class="form-control" value="1234" data-toggle="password">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Server Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Server')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="001english-eu.ddns.net" placeholder="001english-eu.ddns.net">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Port')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="10026" placeholder="10026">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Net')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="Test" placeholder="English">
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
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Country')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="United States" placeholder="United States">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('City')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="Newington" placeholder="Newington">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('State/Prov.')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is the same as the FRN "Part of City" field.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="CT" placeholder="CT">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Description')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="ORP Test Server" placeholder="ORP FreeRadioNetwork Station">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Band & Channel')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('help goes here...')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="444.6500FM CTC123.0" placeholder="444.5000FM CTC100.0">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Module Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Module Timeout')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is how many seconds of inactivity to wait for until the module is disabled.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="300" placeholder="300 secs">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>


              </div>
            </div>


<?php include('../module_footer.php'); ?>