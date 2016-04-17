<?php
/* 
 *	Settings Page for Module 
 *	
 *	This is included into a full page wrapper to be displayed. 
 */
 ?>
			
 			<?php $mod_function_file = 'modules/'.$module[$module_id]['svxlinkName'].'/functions.php'; ?>			
			<form class="form-inline" role="form" action="<?php echo $mod_function_file; ?>" method="post" id="relaysUpdate">

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-off"></i> Remote Relay Module</h2>
					</div>
					<div class="box-content">


					
					
Testing the page
<hr>

<?php 
$options = $module[$module_id]['moduleOptions'];
echo $options;
echo "<hr>";
$settings_array = unserialize($options);
print_r($settings_array);

?>

						<fieldset>
							<legend>Basic Settings</legend>

							  <div class="control-group">
								<label class="control-label" for="timeout">Timeout</label>
								<div class="controls">
								  <div class="input-append">
									<input id="timeout" name="timeout" size="16" type="text" value="<?php echo $settings_array['timeout']; ?>" required><span class="add-on">secs</span>
								  </div>
								  <span class="help-inline">...</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="momentary_delay">Momentary Delay</label>
								<div class="controls">
								  <div class="input-append">
									<input id="momentary_delay" name="momentary_delay" size="16" type="text" value="<?php echo $settings_array['momentary_delay']; ?>" required><span class="add-on">ms</span>
								  </div>
								  <span class="help-inline">...</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="access_pin">Access Pin</label>
								<div class="controls">
								  <input class="input-xlarge" id="access_pin" type="text" name="access_pin" value="<?php echo $settings_array['access_pin']; ?>" required>
								  <span class="help-inline">...</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="access_attempts_allowed">Timeout</label>
								<div class="controls">
								  <input class="input-xlarge" id="access_attempts_allowed" type="text" name="access_attempts_allowed" value="<?php echo $settings_array['access_attempts_allowed']; ?>" required>
								  <span class="help-inline">...</span>
								</div>
							  </div>

							  <div class="control-group">
								<label class="control-label" for="relays_off_deactivation">Off when deactivated</label>
								<div class="controls">
								  <input class="input-xlarge" id="relays_off_deactivation" type="text" name="relays_off_deactivation" value="<?php echo $settings_array['relays_off_deactivation']; ?>" required>
								  <span class="help-inline">...</span>
								</div>
							  </div>





<hr>

						<div id="relaysWrap">
						<?php 
						$idNum = 1; // This will be replaced by a loop to load exsiting values 
						
						if ($settings_array['relay']) {
							ksort($settings_array['relay']);
							foreach($settings_array['relay'] as $cur_parent_array => $cur_child_array) { ?>


								<p class="relayRow<?php if ($idNum == 1) { echo ' first'; } else { echo ' additional'; } ?>">
									<span class="num">
										<input type="hidden" name="relayNum[]" value="<?php echo $cur_parent_array; ?>">
										<?php echo $idNum; ?>
									</span>
									
									<span>									
										<input id="relayLabel<?php echo $idNum; ?>" type="text" required="required" name="relayLabel[]" placeholder="Relay Label" value="<?php echo $cur_child_array['label']; ?>" class="relayLabel">
										<input id="relayGPIO<?php echo $idNum; ?>" type="text" required="required" name="relayGPIO[]" placeholder="GPIO"  value="<?php echo $cur_child_array['gpio']; ?>" class="relayGPIO">
									</span>
									<?php if ($idNum == 1) { 
										echo '<a href="#" id="addRelay">Add</a>';
									} else {
										echo '<a href="#" id="removeRelay">Remove</a>';
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

Add: active high/low setting


						</fieldset>					

						<div class="form-actions">
						  <input type="hidden" name="action" value="update">		
						  <button type="button" class="btn btn-primary" onclick="updateDB()">Update relays</button>
						  <input type="submit">
						</div>
				
					</div>
				</div><!--/span-->
			</div><!--/row-->
			
			</form>