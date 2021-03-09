<?php
$activeState =  'high';
?>


<?php
	include('../module_header.php');
?>

            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('TX Cooling Fan Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Module Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Fan Mode')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Select the operating mode. You can either choose to follow the PTT or count down.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
							<select class="form-control">
								<option value="FOLLOW_PTT">Follow PTT</option>
								<option value="COUNT_DOWN">Count Down</option>
							</select>
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Fan Delay')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('Delay in second until the fan turns on when set to count down mode.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="20" placeholder="Delay in seconds">
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-12 col-xs-12"><?=_('PTT GPIO Pin(s)')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('GPIOs where the PTT(s) can be monitored. 2 paths are required, if there is only 1 PTT, assign them the same GPIO.')?>"></i>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-6">
                          <input type="number" class="form-control" value="498" placeholder="PTT 1">
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-6">
                          <input type="number" class="form-control" value="498" placeholder="PTT 2">
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Fan GPIO Pin')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('GPIO pin used to control fan circuit.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="25" placeholder="Fan GPIO Pin">
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Fan Active State')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting is dependent upon they hardware/circuit design your are using. Active High enables the fan gpio pin with +3.3 volts. Active Low enables by setting the GPIO pin to ground (0 volts).')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs7">

							<div class="btn-group portDuplex" data-toggle="buttons">
								<label class="btn btn-default<?=($activeState == 'high' ? ' active' : '')?>">
									<input type="radio" name="portDuplex" value="high" checked><?=_('Active High')?>
								</label>
								<label class="btn btn-default<?=($activeState == 'low' ? ' active' : '')?>">
									<input type="radio" name="portDuplex" value="low"><?=_('Active Low')?>
								</label>
							</div>
	
                        </div>
                      </div>

                    </form>
                  </div>
                </div>

              </div>



              <div class="col-md-6 col-xs-12">

              </div>
              </div>


<?php include('../module_footer.php'); ?>