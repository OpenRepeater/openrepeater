<?php
/* 
 *	Settings Page for Module 
 *	
 *	This is included into a full page wrapper to be displayed. 
 */
?>

<!-- BEGIN FORM CONTENTS -->
	<fieldset>
		<legend>Module Settings</legend>
	
		  <div class="control-group">
			<label class="control-label" for="timeout">Module Timeout</label>
			<div class="controls">
			  <div class="input-append">
				<input id="timeout" name="timeout" size="16" type="text" value="<?php echo $moduleSettings['timeout']; ?>" required><span class="add-on">secs</span>
			  </div>
			</div>
		    <span class="help-inline">This is how many seconds of inactivity to wait for until the module is disabled.</span>
		  </div>
	
		  <div class="control-group">
			<label class="control-label" for="momentary_delay">Momentary Relay Delay</label>
			<div class="controls">
			  <div class="input-append">
				<input id="momentary_delay" name="momentary_delay" size="16" type="text" value="<?php echo $moduleSettings['momentary_delay']; ?>" required><span class="add-on">ms</span>
			  </div>
			</div>
		    <span class="help-inline">This value is how long in milliseconds the relays will engage for momentary mode. A good starting value is probably in the 100-200ms range. This will depend on what you are controlling.</span>
		  </div>
	
		  <div class="control-group">
			<label class="control-label" for="access_pin">DTMF Access Pin</label>
			<div class="controls">
			  <input class="input-xlarge" id="access_pin" type="text" name="access_pin" value="<?php echo $moduleSettings['access_pin']; ?>">
			</div>
		    <span class="help-inline">When set, the Remote Relay Module will prompt for this pin when the module is activated. Leave empty to not require a pin for access.</span>
		  </div>
	
		  <div class="control-group">
			<label class="control-label" for="access_attempts_allowed">DTMF Access Attempts Allowed</label>
			<div class="controls">
			  <input class="input-xlarge" id="access_attempts_allowed" type="text" name="access_attempts_allowed" value="<?php echo $moduleSettings['access_attempts_allowed']; ?>" required>
			</div>
		    <span class="help-inline">The number of pin entry attempts allowed before module is deactivated.</span>
		  </div>
	
		  <div class="control-group">
			<label class="control-label" for="relays_off_deactivation">Turn off ALL relays when module is deactivated</label>
			<div class="controls">
			  <input type="radio" name="relays_off_deactivation" value="1" <?php if ($moduleSettings['relays_off_deactivation'] == "1") { echo 'checked="checked"'; } ?>><span> Yes </span>
			  <input type="radio" name="relays_off_deactivation" value="0" <?php if ($moduleSettings['relays_off_deactivation'] == "0") { echo 'checked="checked"'; } ?>><span> No </span>
			</div>
		    <span class="help-inline">If enabled, all relays will be turned off when the module is deactivated either by DTMF command or the module times out. Disable to leave the relays in their current state when the module is exited.</span>
		  </div>
	
	
	
	
	
		<legend>Define Relays</legend>
	
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
					echo '<a href="#" id="addRelay" title="Add a relay"><i class="icon-plus-sign"></i></a>';
				} else {
					echo '<a href="#" id="removeRelay" title="Remove this relay"><i class="icon-minus-sign"></i></a>';
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
	
	<hr>
	
	
	
		  <div class="control-group">
			<label class="control-label" for="relays_gpio_active_state"> Global Relay Active High or Low State: </label>
			<div class="controls">
			  <input type="radio" name="relays_gpio_active_state" value="high" <?php if ($moduleSettings['relays_gpio_active_state'] == "high") { echo 'checked="checked"'; } ?>><span> Active High </span>
			  <input type="radio" name="relays_gpio_active_state" value="low" <?php if ($moduleSettings['relays_gpio_active_state'] == "low") { echo 'checked="checked"'; } ?>><span> Active Low </span>
			</div>
			  <span class="help-inline">This setting is dependent upon they hardware/circuit design your are using. Active High enables relays with +3.3 volts on selected GPIO pins. Active Low enables relays by setting selected GPIO pins to ground (0 volts). All relay pins will operate in the same manner.</span>
		  </div>
	
	</fieldset>					
	
<!-- END FORM CONTENTS -->