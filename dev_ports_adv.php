<?php
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');

$classDB = new Database();
$ports = $classDB->get_ports();

###################################################
if (!empty($_POST)) {

	if ( isset( $_POST['SVXLINK_ADVANCED_LOGIC']) ) {
		foreach ($_POST['SVXLINK_ADVANCED_LOGIC'] as $curPortNum => $curPortSettingsArray) {
			$ports[$curPortNum]['SVXLINK_ADVANCED_LOGIC'] = []; // Reset Sub Array
			foreach ($curPortSettingsArray as $settingNumber => $settingArray) {
				if($settingNumber == 'delete') { 
					unset($ports[$curPortNum]['SVXLINK_ADVANCED_LOGIC']);
				} else {
					$ports[$curPortNum]['SVXLINK_ADVANCED_LOGIC'][$settingArray['name']] = $settingArray['value'];
				}
			}
		}
	}

	if ( isset($_POST['SVXLINK_ADVANCED_RX']) ) {
		foreach ($_POST['SVXLINK_ADVANCED_RX'] as $curPortNum => $curPortSettingsArray) {
			$ports[$curPortNum]['SVXLINK_ADVANCED_RX'] = []; // Reset Sub Array
			foreach ($curPortSettingsArray as $settingNumber => $settingArray) {
				if($settingNumber == 'delete') { 
					unset($ports[$curPortNum]['SVXLINK_ADVANCED_RX']);
				} else {
					$ports[$curPortNum]['SVXLINK_ADVANCED_RX'][$settingArray['name']] = $settingArray['value'];
				}
			}
		}
	}

	if ( isset($_POST['SVXLINK_ADVANCED_TX']) ) {
		foreach ($_POST['SVXLINK_ADVANCED_TX'] as $curPortNum => $curPortSettingsArray) {
			$ports[$curPortNum]['SVXLINK_ADVANCED_TX'] = []; // Reset Sub Array
			foreach ($curPortSettingsArray as $settingNumber => $settingArray) {
				if($settingNumber == 'delete') {
					unset($ports[$curPortNum]['SVXLINK_ADVANCED_TX']);
				} else {
					$ports[$curPortNum]['SVXLINK_ADVANCED_TX'][$settingArray['name']] = $settingArray['value'];
				}
			}
		}
	}

	// Write into DB
	$classDB->update_ports_table($ports);
}
###################################################


$logicCommonArray = ['MODULES','CALLSIGN','SHORT_VOICE_ID_ENABLE','SHORT_CW_ID_ENABLE','SHORT_ANNOUNCE_ENABLE','SHORT_ANNOUNCE_FILE','LONG_VOICE_ID_ENABLE','LONG_CW_ID_ENABLE','LONG_ANNOUNCE_ENABLE','LONG_ANNOUNCE_FILE','CW_AMP','CW_PITCH','CW_CPM','CW_WPM','PHONETIC_SPELLING','TIME_FORMAT','SHORT_IDENT_INTERVAL','LONG_IDENT_INTERVAL','IDENT_ONLY_AFTER_TX','EXEC_CMD_ON_SQL_CLOSE','EVENT_HANDLER','DEFAULT_LANG','RGR_SOUND_DELAY','REPORT_CTCSS','TX_CTCSS','MACROS','FX_GAIN_NORMAL','FX_GAIN_LOW','QSO_RECORDER','SEL5_MACRO_RANGE','ONLINE_CMD','STATE_PTY','DTMF_CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, RX, TX

$logicSimplexArray = ['MUTE_RX_ON_TX','MUTE_TX_ON_RX','RGR_SOUND_ALWAYS'];
# SETTINGS REMOVED:
# TYPE (same as common section)

$logicRepeaterArray = ['NO_REPEAT','IDLE_TIMEOUT','OPEN_ON_1750','OPEN_ON_CTCSS','OPEN_ON_DTMF','OPEN_ON_SEL5','CLOSE_ON_SEL5','OPEN_ON_SQL','OPEN_ON_SQL_AFTER_RPT_CLOSE','OPEN_SQL_FLANK','IDLE_SOUND_INTERVAL','SQL_FLAP_SUP_MIN_TIME','SQL_FLAP_SUP_MAX_COUNT','ACTIVATE_MODULE_ON_LONG_CMD','IDENT_NAG_TIMEOUT','IDENT_NAG_MIN_TIME'];
# SETTINGS REMOVED:
# TYPE (same as common section)

$receiverArray = ['AUDIO_DEV_KEEP_OPEN','SQL_DET','SQL_START_DELAY','SQL_DELAY','SQL_HANGTIME','SQL_EXTENDED_HANGTIME','SQL_EXTENDED_HANGTIME_THRESH','SQL_TIMEOUT','VOX_FILTER_DEPTH','VOX_THRESH','CTCSS_MODE','CTCSS_FQ','CTCSS_OPEN_THRESH','CTCSS_CLOSE_THRESH','CTCSS_SNR_OFFSET','CTCSS_BPF_LOW','CTCSS_BPF_HIGH','SERIAL_PORT','SERIAL_PIN','SERIAL_SET_PINS','EVDEV_DEVNAME','EVDEV_OPEN','EVDEV_CLOSE','GPIO_PATH','GPIO_SQL_PIN','SIGLEV_DET','HID_DEVICE','HID_SQL_PIN','SIGLEV_SLOPE','SIGLEV_OFFSET','SIGLEV_BOGUS_THRESH','TONE_SIGLEV_MAP','SIGLEV_OPEN_THRESH','SIGLEV_CLOSE_THRESH','SIGLEV_MIN','SIGLEV_MAX','SIGLEV_DEFAULT','SIGLEV_TOGGLE_INTERVAL','SIGLEV_RAND_INTERVAL','DEEMPHASIS','SQL_TAIL_ELIM','PREAMP','PEAK_METER','DTMF_DEC_TYPE','DTMF_MUTING','DTMF_HANGTIME','DTMF_SERIAL','DTMF_PTY','DTMF_MAX_FWD_TWIST','DTMF_MAX_REV_TWIST','DTMF_DEBUG','1750_MUTING','SEL5_TYPE','SEL5_DEC_TYPE','RAW_AUDIO_UDP_DEST','OB_AFSK_ENABLE','OB_AFSK_VOICE_GAIN','IB_AFSK_ENABLE','CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, AUDIO_DEV, AUDIO_CHANNEL


$transmitterArray = ['AUDIO_DEV_KEEP_OPEN','PTT_TYPE','PTT_PORT','PTT_PIN','GPIO_PATH','PTT_PTY','HID_DEVICE','HID_PTT_PIN','SERIAL_SET_PINS','PTT_HANGTIME','TIMEOUT','TX_DELAY','CTCSS_FQ','CTCSS_LEVEL','PREEMPHASIS','DTMF_TONE_LENGTH','DTMF_TONE_SPACING','DTMF_DIGIT_PWR','TONE_SIGLEV_MAP','TONE_SIGLEV_LEVEL','MASTER_GAIN','OB_AFSK_ENABLE','OB_AFSK_VOICE_GAIN','OB_AFSK_LEVEL','OB_AFSK_TX_DELAY','IB_AFSK_ENABLE','IB_AFSK_LEVEL','IB_AFSK_TX_DELAY','CTRL_PTY'];
# SETTINGS REMOVED:
# TYPE, AUDIO_DEV, AUDIO_CHANNEL

function build_select_options($inputArrays, $selectVal = null ) {
	if ($selectVal == null) { $selectHTML = '<option selected disabled>---</option>'; } else { $selectHTML = '<option disabled>---</option>'; }
	
	if (count($inputArrays) != count($inputArrays, COUNT_RECURSIVE)) { 
		foreach($inputArrays as $optionGroup => $optionGrpArray) {
			$selectHTML .= '<optgroup label="'.$optionGroup.'">';
				asort($optionGrpArray);
				foreach($optionGrpArray as $optionName) {
					if ($optionName == $selectVal) { $selState = ' selected'; } else { $selState = ''; }
					$selectHTML .= '<option'.$selState.'>'.$optionName.'</option>';
				}
			$selectHTML .= '</optgroup>';			
		}
	} else {
		sort($inputArrays);
		foreach($inputArrays as $optionName) {
			if ($optionName == $selectVal) { $selState = ' selected'; } else { $selState = ''; }
			$selectHTML .= '<option'.$selState.'>'.$optionName.'</option>';
		}
	}

	return $selectHTML;
}
?>
<html>
	<style>
	div.greyGridTable {
	  border: 2px solid #FFFFFF;
	  width: 100%;
	  text-align: center;
	  border-collapse: collapse;
	}
	.divTable.greyGridTable .divTableCell, .divTable.greyGridTable .divTableHead {
	  border: 1px solid #FFFFFF;
	  padding: 3px 4px;
	}
	.divTable.greyGridTable .divTableBody .divTableCell {
	  font-size: 13px;
	}
	.divTable.greyGridTable .divTableCell:nth-child(even) {
	  background: #EBEBEB;
	}
	.divTable.greyGridTable .divTableHeading {
	  background: #FFFFFF;
	  border-bottom: 4px solid #333333;
	}
	.divTable.greyGridTable .divTableHeading .divTableHead {
	  font-size: 15px;
	  font-weight: bold;
	  color: #333333;
	  text-align: center;
	  border-left: 2px solid #333333;
	}
	.divTable.greyGridTable .divTableHeading .divTableHead:first-child {
	  border-left: none;
	}
	
	.greyGridTable .tableFootStyle {
	  font-size: 14px;
	}
	/* DivTable.com */
	.divTable{ display: table; }
	.divTableRow { display: table-row; }
	.divTableHeading { display: table-header-group;}
	.divTableCell, .divTableHead { display: table-cell; width: 33%;}
	.divTableHeading { display: table-header-group;}
	.divTableFoot { display: table-footer-group;}
	.divTableBody { display: table-row-group;}


	.myButton {
		-moz-box-shadow:inset 0px 1px 0px 0px #a4e271;
		-webkit-box-shadow:inset 0px 1px 0px 0px #a4e271;
		box-shadow:inset 0px 1px 0px 0px #a4e271;
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #89c403), color-stop(1, #77a809));
		background:-moz-linear-gradient(top, #89c403 5%, #77a809 100%);
		background:-webkit-linear-gradient(top, #89c403 5%, #77a809 100%);
		background:-o-linear-gradient(top, #89c403 5%, #77a809 100%);
		background:-ms-linear-gradient(top, #89c403 5%, #77a809 100%);
		background:linear-gradient(to bottom, #89c403 5%, #77a809 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#89c403', endColorstr='#77a809',GradientType=0);
		background-color:#89c403;
		-moz-border-radius:6px;
		-webkit-border-radius:6px;
		border-radius:6px;
		border:1px solid #74b807;
		display:inline-block;
		cursor:pointer;
		color:#ffffff;
		font-family:Arial;
		font-size:15px;
		font-weight:bold;
		padding:6px 24px;
		text-decoration:none;
		text-shadow:0px 1px 0px #528009;
	}
	.myButton:hover {
		background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #77a809), color-stop(1, #89c403));
		background:-moz-linear-gradient(top, #77a809 5%, #89c403 100%);
		background:-webkit-linear-gradient(top, #77a809 5%, #89c403 100%);
		background:-o-linear-gradient(top, #77a809 5%, #89c403 100%);
		background:-ms-linear-gradient(top, #77a809 5%, #89c403 100%);
		background:linear-gradient(to bottom, #77a809 5%, #89c403 100%);
		filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77a809', endColorstr='#89c403',GradientType=0);
		background-color:#77a809;
	}
	.myButton:active {
		position:relative;
		top:1px;
	}

	</style>
<head>
	
</head>

<body>
	
<p>This is a supplemental UI to allow editing of advanced SVXLink settings as they pertain to "ports" (logic section, local RX, and local TX). This will append those settings into the database for each port to be added into the svxlink.conf file upon rebuild. This is a temporary UI until these feature are tested and built into the future front end redesign. Note that some settings may not be made available if they are determined to interfere with or cause ORP to potentially break. For more information on these settings please refer to the <a href="http://www.svxlink.org/doc/man/man5/svxlink.conf.5.html" target="_blank">Online Documentation</a> OR for the most current documentation enter "man svxlink.conf" at the command line.</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

	<input type="hidden" name="ports" value="$ports">

<?php
foreach ($ports as $key => $val) {
	echo '<h1>Port ' . $key . ': ' . $val['portLabel'] . '</h1>';

	$logic_html = '';
	if (isset($val['SVXLINK_ADVANCED_LOGIC'])) {
		$settingNum = 0;
		foreach ( $val['SVXLINK_ADVANCED_LOGIC'] as $curSettingName => $curSettingValue) {
			$settingNum++;
			$logic_html .= '<div>';
			$logic_html .= '<select name="SVXLINK_ADVANCED_LOGIC['.$key.']['.$settingNum.'][name]">';
			$logic_html .= build_select_options(['Common Variables' => $logicCommonArray, 'Simplex Logic Only' => $logicSimplexArray, 'Repeater Logic Only' => $logicRepeaterArray], $curSettingName);
			$logic_html .= '</select>';
	
			$logic_html .= '<input type="text" name="SVXLINK_ADVANCED_LOGIC['.$key.']['.$settingNum.'][value]" value="' . $curSettingValue . '">';
			$logic_html .= '<a href="#" class="remove_field">X</a>';
			$logic_html .= '</div>';
		}
		$local_count = $settingNum;
	} else {
		$local_count = 0;		
	}

	$rx_html = '';
	if (isset($val['SVXLINK_ADVANCED_RX'])) {
		$settingNum = 0;
		foreach ( $val['SVXLINK_ADVANCED_RX'] as $curSettingName => $curSettingValue) {
			$settingNum++;
			$rx_html .= '<div>';
			$rx_html .= '<select name="SVXLINK_ADVANCED_RX['.$key.']['.$settingNum.'][name]">';
			$rx_html .= build_select_options($receiverArray, $curSettingName);
			$rx_html .= '</select>';
				
			$rx_html .= '<input type="text" name="SVXLINK_ADVANCED_RX['.$key.']['.$settingNum.'][value]" value="' . $curSettingValue . '">';
			$rx_html .= '<a href="#" class="remove_field">X</a>';
			$rx_html .= '</div>';
		}
		$rx_count = $settingNum;
	} else {
		$rx_count = 0;		
	}

	$tx_html = '';
	if (isset($val['SVXLINK_ADVANCED_TX'])) {
		$settingNum = 0;
		foreach ( $val['SVXLINK_ADVANCED_TX'] as $curSettingName => $curSettingValue) {
			$settingNum++;
			$tx_html .= '<div>';
			$tx_html .= '<select name="SVXLINK_ADVANCED_TX['.$key.']['.$settingNum.'][name]">';
			$tx_html .= build_select_options($transmitterArray, $curSettingName);
			$tx_html .= '</select>';
				
			$tx_html .= '<input type="text" name="SVXLINK_ADVANCED_TX['.$key.']['.$settingNum.'][value]" value="' . $curSettingValue . '">';
			$tx_html .= '<a href="#" class="remove_field">X</a>';
			$tx_html .= '</div>';
		}
		$tx_count = $settingNum;
	} else {
		$tx_count = 0;		
	}
	?>

	<div class="divTable greyGridTable">
		<div class="divTableHeading">
			<div class="divTableRow">
				<div class="divTableHead">Logic Section</div>
				<div class="divTableHead">RX Section</div>
				<div class="divTableHead">TX Section</div>
			</div>
		</div>
		<div class="divTableBody">
			<div class="divTableRow">
				<div class="divTableCell input_fields_wrap" id="port<?php echo $key; ?>local" data-port-num="<?php echo $key; ?>" data-real-count="<?php echo $local_count; ?>" data-ceiling-count="<?php echo $local_count; ?>" data-section-type="local">
				    <button class="add_field_button">Add Field</button>
					<?php echo $logic_html; ?>
				</div>
				<div class="divTableCell input_fields_wrap" id="port<?php echo $key; ?>rx" data-port-num="<?php echo $key; ?>" data-real-count="<?php echo $rx_count; ?>" data-ceiling-count="<?php echo $rx_count; ?>" data-section-type="rx">
				    <button class="add_field_button">Add Field</button>
					<?php echo $rx_html; ?>
				</div>
				<div class="divTableCell input_fields_wrap" id="port<?php echo $key; ?>tx" data-port-num="<?php echo $key; ?>" data-real-count="<?php echo $tx_count; ?>" data-ceiling-count="<?php echo $tx_count; ?>" data-section-type="tx">
				    <button class="add_field_button">Add Field</button>
					<?php echo $tx_html; ?>
				</div>
			</div>
		</div>
	</div>

	<hr>
	
	<?php
}
?>

	<br>
	<button class="myButton">Update Advanced SVXLink Port Options</button>
</form>

<?php
	$jsCode = '<div><select name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][name]">%%OPTIONS%%</select><input type="text" name="%%ARRAY_NAME%%[%%PORT%%][%%ROW%%][value]" value=""><a href= "#" class="remove_field">X</a></div>';

	$jsLogicOptions = build_select_options(['Common Variables' => $logicCommonArray, 'Simplex Logic Only' => $logicSimplexArray, 'Repeater Logic Only' => $logicRepeaterArray]);
	$jsRxOptions = build_select_options($receiverArray);
	$jsTxOptions = build_select_options($transmitterArray);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type='text/javascript'>
$(document).ready(function() {
	var max_fields = 5; //maximum input boxes allowed
	var baseRow = '<?php echo $jsCode; ?>';
	var logicOptions = '<?php echo $jsLogicOptions; ?>';
	var rxOptions = '<?php echo $jsRxOptions; ?>';
	var txOptions = '<?php echo $jsTxOptions; ?>';
	
	var x = 1; //initlal text box count
	$(".add_field_button").click(function(e) {
		var wrapper = $(this).parent('div').attr('id');
		var curPort = $('#'+wrapper).attr('data-port-num');
		var ceilingCount = $('#'+wrapper).attr('data-ceiling-count');
		var realCount = $('#'+wrapper).attr('data-real-count');
		var sectionType = $('#'+wrapper).attr('data-section-type');
		e.preventDefault();
		if(realCount < max_fields) {
			$('#'+wrapper+'DELETE').remove(); // Remove delete field if it exists
			ceilingCount++; realCount++;
			$('#'+wrapper).attr('data-ceiling-count',ceilingCount);
			$('#'+wrapper).attr('data-real-count', realCount);

			var newRow = baseRow.replace(/%%PORT%%/g, curPort);
			var newRow = newRow.replace(/%%ROW%%/g, ceilingCount);

			switch(sectionType) {
				case 'local':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_LOGIC');
					var newRow = newRow.replace(/%%OPTIONS%%/g, logicOptions);
					break;
				case 'rx':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_RX');
					var newRow = newRow.replace(/%%OPTIONS%%/g, rxOptions);
					break;
				case 'tx':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_TX');
					var newRow = newRow.replace(/%%OPTIONS%%/g, txOptions);
					break;
			} 

			$('#'+wrapper).append(newRow); //add row
		}
	});
	
	$(".input_fields_wrap").on("click",".remove_field", function(e){ //user click on remove text
		var wrapper = $(this).closest('.input_fields_wrap').attr('id');
		var curPort = $('#'+wrapper).attr('data-port-num');
		var realCount = $('#'+wrapper).attr('data-real-count');
		var sectionType = $('#'+wrapper).attr('data-section-type');

		e.preventDefault();
		realCount--;
		$('#'+wrapper).attr('data-real-count', realCount);
		$(this).parent('div').remove();
		if (realCount == 0) {
			switch(sectionType) {
				case 'local':
					var deleteFieldName = 'SVXLINK_ADVANCED_LOGIC['+curPort+'][delete]'; break;
				case 'rx':
					var deleteFieldName = 'SVXLINK_ADVANCED_RX['+curPort+'][delete]'; break;
				case 'tx':
					var deleteFieldName = 'SVXLINK_ADVANCED_TX['+curPort+'][delete]'; break;
					break;
			} 

			var deleteField = '<input type="hidden" id="'+wrapper+'DELETE" name="'+deleteFieldName+'" value="DELETE">';
			$('#'+wrapper).append(deleteField); //add row
		}
	})

});
</script>

</body>
</html>