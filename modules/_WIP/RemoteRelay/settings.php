<?php
$moduleSettings  = [
	'relay' => [
		1 => [
			'gpio' => '20',
			'label' => 'Relay 1'
		],
		2 => [
			'gpio' => '21',
			'label' => 'Relay 2'
		],
		3 => [
			'gpio' => '22',
			'label' => 'Relay 3'
		],
		4 => [
			'gpio' => '23',
			'label' => 'Relay 4'
		],
	],
];

$currPortSettings = [];
$activeState =  'high';

?>


<?php
	$moduleCSS = "/modules/RemoteRelay/module.css";
	$moduleJS = "/modules/RemoteRelay/module.js";

	include('../module_header.php');
?>

            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('Remote Relay Settings')?></h3>
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
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Module Timeout')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is how many seconds of inactivity to wait for until the module is disabled.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="300 secs">
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Momentary Relay Delay')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This value is how long in milliseconds the relays will engage for momentary mode. A good starting value is probably in the 100-200ms range. This will depend on what you are controlling.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="200" placeholder="Milliseconds">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('DTMF Access Code')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('When set, the Remote Relay Module will prompt for this pin code when the module is activated. Leave empty to not require a pin code for access.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
							<input type="password" id="password" name="password" class="form-control" value="1234" data-toggle="password">
                        </div>
                      </div>



                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('DTMF Access Attempts')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('The number of pin code entry attempts allowed before module is deactivated.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="number" class="form-control" value="3">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Global Relay State')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This setting is dependent upon the hardware/circuit design your are using. Active High enables relays with +3.3 volts on selected GPIO pins. Active Low enables relays by setting selected GPIO pins to ground (0 volts). All relay pins will operate in the same manner.')?>"></i>
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


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('ALL relays when deactivated')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('If enabled, all relays will be turned off when the module is deactivated either by DTMF command or the module times out. Disable to leave the relays in their current state when the module is exited.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="checkbox" class="js-switch" checked /> 
                        </div>
                      </div>


                    </form>
                  </div>
                </div>

              </div>



              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Define Relays')?></h4></div>

                  <div class="x_content">

						<div id="relaysWrap">
						<?php 
						$idNum = 1; // This will be replaced by a loop to load exsiting values 
						
						if ($moduleSettings['relay']) {
							ksort($moduleSettings['relay']);
							foreach($moduleSettings['relay'] as $cur_parent_array => $cur_child_array) { ?>
						
						
								<p class="relayRow<?php if ($idNum == 1) { echo ' first'; } else { echo ' additional'; } ?>">
									<span class="num">
										<input type="hidden" name="relayNum[]" value="<?php echo $idNum; ?>">
										<?php echo $idNum; ?>
									</span>
									
									<span>									
										<input id="relayLabel<?php echo $idNum; ?>" type="text" name="relayLabel[]" placeholder="Relay Label" value="<?php echo $cur_child_array['label']; ?>" class="relayLabel" required>
										<input id="relayGPIO<?php echo $idNum; ?>" type="text" name="relayGPIO[]" placeholder="GPIO"  value="<?php echo $cur_child_array['gpio']; ?>" class="relayGPIO" required>
									</span>
						
									<?php if ($idNum == 1) { 
										echo '<a href="#" id="addRelay" title="Add a relay">+</i></a>';
									} else {
										echo '<a href="#" id="removeRelay" title="Remove this relay">-</i></a>';
									} ?>
								</p>
						
						
							<?php 
							$idNum++;
							}	
						} else {
							echo "there are no relays...";
						}
						?>
						
						</div>
						
						<div id="relayCount"></div>


                  </div>
                </div>

              </div>
              </div>


<?php include('../module_footer.php'); ?>