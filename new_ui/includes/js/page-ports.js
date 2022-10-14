$(function() {

	// Variables
	var advLogicOptions = buildSelectOptions(logicOptions);
	var advRxOptions = buildSelectOptions(rxOptions);
	var advTxOptions = buildSelectOptions(txOptions);

	var linkGroup1_PortCount = $('#linkGroup1').attr('data-port-count');
	var linkGroup2_PortCount = $('#linkGroup2').attr('data-port-count');
	var linkGroup3_PortCount = $('#linkGroup3').attr('data-port-count');
	var linkGroup4_PortCount = $('#linkGroup4').attr('data-port-count');

	/*
	**********************************************************************
	 PORT ADD/REMOVE/UPDATE FUNCTIONS
	**********************************************************************
	*/

	// Loop through JSON array of ports and build display
	fullPortObj = JSON.parse(portList);
console.log(portList);
console.log(fullPortObj);
	$.each(fullPortObj, function(index, curPort) {
		displayPort(curPort);
	});


	// Dynamically Build Port
	function displayPort(port) {
		var $template = $('#rowTemplateAnalog').html();
		$template = $template.replace(/%%currPortNum%%/g, port.portNum)
			.replace(/%%currPortLabel%%/g, port.portLabel);

	    $("#portList").append($template);

		portID = '#portNum' + port.portNum; 
		
		// Expand First Port
		if(port.portNum == '1') {
			$('#accordionHeading1').click();
		}

		$(portID).attr('data-port-number', port.portNum);

		// Enable/Disable Port
		if(port.portEnabled != '1') { $(portID).addClass('portDisabled') }
		
		// Show Port Labels if applicable
		if(port.portDuplex == 'full') { $(portID + ' .portLabelDuplexFull').show() }
		if(port.portDuplex == 'half') { $(portID + ' .portLabelDuplexHalf').show() }



		/* GENERAL TAB SETTINGS */

		// Set Port Type and Show Corresponding Tab
		switch(port.portType) {
			case 'GPIO':
				$('#portType'+port.portNum+'GPIO').parents('label').addClass('active');
				$('#portType'+port.portNum+'GPIO').attr('checked',true);
				$('#tabGPIO'+port.portNum).show();
				break;

			case 'HiDraw':
				$('#portType'+port.portNum+'HiDraw').parents('label').addClass('active');
				$('#portType'+port.portNum+'HiDraw').attr('checked',true);
				$('#tabHidraw'+port.portNum).show();
				break;

			case 'Serial':
				$('#portType'+port.portNum+'Serial').parents('label').addClass('active');
				$('#portType'+port.portNum+'Serial').attr('checked',true);
				$('#tabSerial'+port.portNum).show();
				break;
		}

		// Set Port Duplex Buttons
		if(port.portDuplex == 'half') {
			$('#portDuplex'+port.portNum+'Half').parents('label').addClass('active');
			$('#portDuplex'+port.portNum+'Half').attr('checked',true);
		}
		if(port.portDuplex == 'full') {
			$('#portDuplex'+port.portNum+'Full').parents('label').addClass('active');
			$('#portDuplex'+port.portNum+'Full').attr('checked',true);
		}


		// Set Link Group Buttons
		addLinkGroup('1', port.portNum, port.linkGroup);
		addLinkGroup('2', port.portNum, port.linkGroup);
		addLinkGroup('3', port.portNum, port.linkGroup);
		addLinkGroup('4', port.portNum, port.linkGroup);
		$('#linkGroup_Port'+port.portNum).val(port.linkGroup);
		showLinkGroups();


		// Setup Port Enable/Disable Switch
		if(port.portEnabled == '1') {
			$('#portEnabled'+port.portNum).prop( 'checked', true );
			$('#deletePort'+port.portNum).hide();
		} else {
			$('#portEnabled'+port.portNum).prop( 'checked', false );
			$('#deletePort'+port.portNum).show();
		}
		resetSwitchery('#portEnabled'+port.portNum);


		/* AUDIO TAB SETTINGS */

		$('#rxAudioDev' + port.portNum).val(port.rxAudioDev);
		$('#txAudioDev' + port.portNum).val(port.txAudioDev);


		/* GPIO TAB SETTINGS */

		$('#rxMode' + port.portNum).val(port.rxMode);
		if ( port.rxMode == 'vox' ) { 
			$('#voxMsg' + port.portNum).show();
			$('#rxGPIO_Grp' + port.portNum).hide();
		} else {
			$('#voxMsg' + port.portNum).hide();
			$('#rxGPIO_Grp' + port.portNum).show();
		}
		$('#rxGPIO' + port.portNum).val(port.rxGPIO);
		$('#rxGPIO_active' + port.portNum).val(port.rxGPIO_active);
		$('#txGPIO' + port.portNum).val(port.txGPIO);
		$('#txGPIO_active' + port.portNum).val(port.txGPIO_active);


		/* HIDRAW TAB SETTINGS */
		$('#hidrawDev' + port.portNum).val(port.hidrawDev);
		
		$('#hidrawRX_cos' + port.portNum).val(port.hidrawRX_cos);
		if(port.hidrawRX_cos_invert == 'true') {
			$('#hidrawRX_cos_invert'+port.portNum).prop( 'checked', true );
		} else {
			$('#hidrawRX_cos_invert'+port.portNum).prop( 'checked', false );
		}
		resetSwitchery('#hidrawRX_cos_invert'+port.portNum);
		
		$('#hidrawTX_ptt' + port.portNum).val(port.hidrawTX_ptt);
		if(port.hidrawTX_ptt_invert == 'true') {
			$('#hidrawTX_ptt_invert'+port.portNum).prop( 'checked', true );
		} else {
			$('#hidrawTX_ptt_invert'+port.portNum).prop( 'checked', false );
		}
		resetSwitchery('#hidrawTX_ptt_invert'+port.portNum);


		/* SERIAL TAB SETTINGS */
		
		$('#serialDev' + port.portNum).val(port.serialDev);
		
		$('#serialRX_cos' + port.portNum).val(port.serialRX_cos);
		if(port.serialRX_cos_invert == 'true') {
			$('#serialRX_cos_invert'+port.portNum).prop( 'checked', true );
		} else {
			$('#serialRX_cos_invert'+port.portNum).prop( 'checked', false );
		}
		resetSwitchery('#serialRX_cos_invert'+port.portNum);
		
		$('#serialTX_ptt' + port.portNum).val(port.serialTX_ptt);
		if(port.serialTX_ptt_invert == 'true') {
			$('#serialTX_ptt_invert'+port.portNum).prop( 'checked', true );
		} else {
			$('#serialTX_ptt_invert'+port.portNum).prop( 'checked', false );
		}
		resetSwitchery('#serialTX_ptt_invert'+port.portNum);


		/* MODULE TAB SETTINGS */

		// Future Code Here...


		/* OVERRIDE TAB SETTINGS */

		if (typeof port.SVXLINK_ADVANCED_LOGIC !== 'undefined') {
			$.each(port.SVXLINK_ADVANCED_LOGIC, function(parmName, parmValue) {
				buildAdvFields(port.portNum, 'local', parmName, parmValue);
			});
		}
		if (typeof port.SVXLINK_ADVANCED_RX !== 'undefined') {
			$.each(port.SVXLINK_ADVANCED_RX, function(parmName, parmValue) {
				buildAdvFields(port.portNum, 'rx', parmName, parmValue);
			});
		}

		if (typeof port.SVXLINK_ADVANCED_TX !== 'undefined') {
			$.each(port.SVXLINK_ADVANCED_TX, function(parmName, parmValue) {
				buildAdvFields(port.portNum, 'tx', parmName, parmValue);
			});
		}


		// DISPLAY PORT
		$(portID).fadeIn(1000);
	}


	// Reset Switchery Toggles After Dynamic Build
	function resetSwitchery(checkboxID) {
 		 $(checkboxID).next().remove('span'); // remove inital switcher span
 		 var elems = $(document).find(checkboxID);
 		 var switchery = new Switchery( elems[0], { color: '#8dc63f' } );
 		 switchery.handleOnchange();
	}


	// Add Port Function and Modal Display
	$('.addPort').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-plus"></i> ' + modal_AddPortTitle,
			body: modal_AddPortBody,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var addPortType = $('#addPortType').val();

			$('#orp_modal').modal('hide');

			switch(addPortType) {
				case 'local':
					templatePortObj = JSON.parse('{"portNum":10,"portLabel":"New Port","rxAudioDev":"alsa:plughw:0|1","txAudioDev":"alsa:plughw:1|1","portType":"GPIO","portEnabled":1,"rxMode":"cos","rxGPIO":"26","txGPIO":"498","rxGPIO_active":"low","txGPIO_active":"high","linkGroup":"1"}');
					displayPort(templatePortObj);
					break;
				case 'voip':
					templatePortObj = JSON.parse('{"portNum":20,"portLabel":"New VOIP Port","rxAudioDev":"alsa:plughw:0|1","txAudioDev":"alsa:plughw:1|1","portType":"VOIP","portEnabled":1,"rxMode":"cos","rxGPIO":"26","txGPIO":"498","rxGPIO_active":"low","txGPIO_active":"high","linkGroup":"1"}');
					displayPort(templatePortObj);
					break;
			}
		});

	});
	

	// Load Board Preset Function and Modal Display
	$('.loadBoard').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-download"></i> Load Preset',
			body: '<p>What type of port do you wish to add?</p><select id="loadBoardPreset" name="loadBoardPreset" class="form-control"><option value="ICS_2X_ID_Num" selected>ICS 2X</option></select>',
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var loadBoardPreset = $('#loadBoardPreset').val();

			console.log(loadBoardPreset);
			$('#orp_modal').modal('hide');
		});
	});

	// Delete Port Function and Modal Display
	$('.deletePort').click(function(e) {
		e.preventDefault();
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var portDesc = $('#portNum' + portNum + ' .panel-title').text();

		console.log(portDesc);

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> '+modal_DeletePortTitle,
			body: '<p>'+modal_DeletePortBody+'<br><strong>'+portDesc+'</strong></p>',
			btnOK: modal_DeletePortBtnOK,
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			deleteString = { deletePort: portNum };
			console.log( JSON.stringify(deleteString) );

			orpModalWaitBar(modal_DeletePortProgressTitle);

			setTimeout(function() {
				$('#orp_modal').modal('hide');

				$('#portNum' + portNum).slideUp(500);

				//Display Message
				new PNotify({
					title: modal_DeletePortNotifyTitle,
					text: modal_DeletePortNotifyDesc,
					type: 'success',
					styling: 'bootstrap3'
				});
			}, 2000);
		});
	});


	// 	Port Label Change
	$('.portLabel').keyup(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var portLabel = $(this).val().trim();
		if ( portLabel == '' ) {
			$('#portNum' + portNum + ' .panel-title span').html('(no label set yet)');
		} else {
			$('#portNum' + portNum + ' .panel-title span').html(portLabel);
		}
	});


	// 	Port Type Change
	$('.portType input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		switch ($(this).val()) {
		case 'GPIO':
			$('#portNum' + portNum + ' .tabGPIO').show();
			$('#portNum' + portNum + ' .tabHidraw').hide();
			$('#portNum' + portNum + ' .tabSerial').hide();
			break;
		case 'HiDraw':
			$('#portNum' + portNum + ' .tabGPIO').hide();
			$('#portNum' + portNum + ' .tabHidraw').show();
			$('#portNum' + portNum + ' .tabSerial').hide();
			break;
		case 'Serial':
			$('#portNum' + portNum + ' .tabGPIO').hide();
			$('#portNum' + portNum + ' .tabHidraw').hide();
			$('#portNum' + portNum + ' .tabSerial').show();
			break;
		}
	});


	// 	Port Duplex Change
	$('.portDuplex input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		switch ($(this).val()) {
			case 'half':
				$('#portNum' + portNum + ' .portLabelDuplexHalf').show();
				$('#portNum' + portNum + ' .portLabelDuplexFull').hide();
				break;
			case 'full':
				$('#portNum' + portNum + ' .portLabelDuplexHalf').hide();
				$('#portNum' + portNum + ' .portLabelDuplexFull').show();
				break;
		}
	});


	// 	Port Enable/Disable
	$('.portEnabled').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
        if(this.checked) {
			$('#portNum'+portNum+' a.deletePort').hide();
			$('#portNum'+portNum).removeClass('portDisabled');
        } else {
			$('#portNum'+portNum+' a.deletePort').show();
			$('#portNum'+portNum).addClass('portDisabled');
        }
	});


	// 	Show/Hide VOX Warning Message
	$('.rxMode').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		if ( $('#rxMode' + portNum).val() == 'vox' ) {
			$('#voxMsg' + portNum).fadeIn(1000);
			$('#rxGPIO_Grp' + portNum).fadeOut(1000);
		} else {
			$('#voxMsg' + portNum).fadeOut(1000);
			$('#rxGPIO_Grp' + portNum).fadeIn(1000);
		}
	});



	/*
	**********************************************************************
	 ADVANCED SVXLINK FIELDS
	**********************************************************************
	*/

	// Add Addtional SVXLink Field
	var x = 1; //initlal text box count
	$(".add_field_button").click(function(e) {
		e.preventDefault();
		var portLabel = $(this).val().trim();
		var wrapper = $(this).parent('div').attr('id');
		var curPort = $('#'+wrapper).attr('data-port-num');
		var realCount = $('#'+wrapper).attr('data-real-count');
		var sectionType = $('#'+wrapper).attr('data-section-type');
		if(realCount < max_fields) {
			$('#'+wrapper+'DELETE').remove(); // Remove delete field if it exists
			buildAdvFields(curPort, sectionType, null, null);
		}
	});


	// Remove SVXLink Field
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
		
		$("#port"+curPort+"form").trigger("change"); // Trigger port form to resubmit
	})


	// Build SVXLink Field from template
	function buildAdvFields(curPortNum, fieldType, settingName = null, settingValue = null) {
		switch(fieldType) {
			case 'local':
				var typeOptions = advLogicOptions;
				arrayName = 'SVXLINK_ADVANCED_LOGIC';
				break;
			case 'rx':
				var typeOptions = advRxOptions;
				arrayName = 'SVXLINK_ADVANCED_RX';
				break;
			case 'tx':
				var typeOptions = advTxOptions;
				arrayName = 'SVXLINK_ADVANCED_TX';
				break;
		}

		var portSectID = 'port'+ curPortNum + fieldType;
		var realCount = $('#'+portSectID).attr('data-real-count');
		var ceilingCount = $('#'+portSectID).attr('data-ceiling-count');

		if(realCount < max_fields) {
			realCount++; ceilingCount++;
		    var settingNameID = 'adv_'+fieldType+'_'+curPortNum+'_'+ceilingCount+'_name';
		    var settingValueID = 'adv_'+fieldType+'_'+curPortNum+'_'+ceilingCount+'_value';

			var $template = $('#advFieldsTemplate').html();
			$template = $template.replace(/%%ARRAY_NAME%%/g, arrayName)
				.replace(/%%TYPE%%/g, fieldType)
				.replace(/%%PORT%%/g, curPortNum)
				.replace(/%%ROW%%/g, ceilingCount)
				.replace(/%%OPTIONS%%/g, typeOptions);

		    $('#'+portSectID+' .innerWrap').append($template);
			
			$('#'+settingNameID).val(settingName);
			$('#'+settingValueID).val(settingValue);

// 			$('#'+portSectID).attr('data-port-num', curPortNum);
			$('#'+portSectID).attr('data-real-count', realCount);
			$('#'+portSectID).attr('data-ceiling-count',ceilingCount);
			$('#'+portSectID).attr('data-section-type', fieldType);
		}
	}


	// Build Select Options for Data Passed from PHP
	function buildSelectOptions(input) {
		var selectOptions = '<option disabled>---</option>';

		// The main Logic array, build with Option Groups
		if ( typeof input["Common Variables"] !== 'undefined' ) {
			$.each(input, function(optionGroupIndex, optionGroupSubObj) {
				selectOptions += '<optgroup label="'+optionGroupIndex+'">';
				$.each(optionGroupSubObj, function(optionIndex, optionValue) {
					selectOptions += '<option>'+optionValue+'</option>';
				});
				selectOptions += '</optgroup>';			
			});

		// One of the other arrays with plain options (no option groups)
		} else {
			$.each(input, function(optionIndex, optionValue) {
				selectOptions += '<option>'+optionValue+'</option>';
			});
		}

		return selectOptions;
	}



	/*
	**********************************************************************
	 LINK GROUPS
	**********************************************************************
	*/

	// Loop through JSON array of Link Groups and update display
	linkGroupObj = JSON.parse(linkGroupSettings);
	$.each(linkGroupObj, function(index, curLinkGroup) {

		if(curLinkGroup.defaultActive == '1') {
			$('#LG'+index+'_defaultActive').prop( 'checked', true );
		} else {
			$('#LG'+index+'_defaultActive').prop( 'checked', false );
		}
 		resetSwitchery('#LG'+index+'_defaultActive');

		$('#LG'+index+'_timeout').val(curLinkGroup.timeout);
	});


	// Pass Link Group Settings to Database
	$('.linkGroupForm').change(function() {

		// Primitive approach but it works. 
		if ( $('#LG1_defaultActive').prop('checked') == true ) { var lg1_active = 1; } else { var lg1_active = 0; }
		if ( $('#LG1_timeout').val() != '' ) { var lg1_timeout = $('#LG1_timeout').val(); } else { var lg1_timeout = 0; }
		
		if ( $('#LG2_defaultActive').prop('checked') == true ) { var lg2_active = 1; } else { var lg2_active = 0; }
		if ( $('#LG2_timeout').val() != '' ) { var lg2_timeout = $('#LG2_timeout').val(); } else { var lg2_timeout = 0; }
		
		if ( $('#LG3_defaultActive').prop('checked') == true ) { var lg3_active = 1; } else { var lg3_active = 0; }
		if ( $('#LG3_timeout').val() != '' ) { var lg3_timeout = $('#LG3_timeout').val(); } else { var lg3_timeout = 0; }
		
		if ( $('#LG4_defaultActive').prop('checked') == true ) { var lg4_active = 1; } else { var lg4_active = 0; }
		if ( $('#LG4_timeout').val() != '' ) { var lg4_timeout = $('#LG4_timeout').val(); } else { var lg4_timeout = 0; }
		
		var returnString = '{"1":{"defaultActive":"'+lg1_active+'","timeout":"'+lg1_timeout+'"},"2":{"defaultActive":"'+lg2_active+'","timeout":"'+lg2_timeout+'"},"3":{"defaultActive":"'+lg3_active+'","timeout":"'+lg3_timeout+'"},"4":{"defaultActive":"'+lg4_active+'","timeout":"'+lg4_timeout+'"}}';
				
		// Replace with AJAX Call
		console.log(returnString);

		// TESTING FOR SAVE STATUS SIMULAITON
		// sectionStatus('linkGroupForm', 'x_panel', 'processing');
		sectionStatus('linkGroupForm', 'x_panel', 'saved');
		// sectionStatus('linkGroupForm', 'x_panel', 'error');
		
		$('#orp_restart_btn').show();

	});


	// 	Link Group Change
	$('#portList').on('change', '.linkGroup', function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var linkNum = $(this).attr('data-linkGroup-num');
		if ( $(this).prop('checked') == true ) {
			$('#portNum' + portNum + ' .portLabelLinkGrp.'+linkNum).show(); // Show badge
			eval('linkGroup'+linkNum+'_PortCount++;'); // Dynamic Port Variable, incremented.
		} else {
			$('#portNum' + portNum + ' .portLabelLinkGrp.'+linkNum).hide(); // Hide badge
			eval('linkGroup'+linkNum+'_PortCount--;'); // Dynamic Port Variable, decreased.
		}
		$( '#linkGroup'+linkNum).attr('data-port-count', eval('linkGroup'+linkNum+'_PortCount') ); // Set updated count on Link Group
		updateLinkGroupField(portNum);
		showLinkGroups();
	});


	// Setup linkGroups on Page Load
	function addLinkGroup(linkNum, portNum, linkGroupInput) {
		if( $.inArray( parseInt(linkNum), linkGroupInput) > -1 ) {
			eval('linkGroup'+linkNum+'_PortCount++;'); // Dynamic Port Variable, incremented.
			$( '#linkGroup'+linkNum).attr('data-port-count', eval('linkGroup'+linkNum+'_PortCount') ); // Set updated count on Link Group
			$('#linkGroup'+linkNum+'_Port'+portNum).attr('checked', true);
			$('#portNum' + portNum + ' .portLabelLinkGrp.'+linkNum).show();
		} else {
			$('#linkGroup'+linkNum+'_Port'+portNum).attr('checked', false);
			$('#portNum' + portNum + ' .portLabelLinkGrp.'+linkNum).hide();			
		}
		resetSwitchery('#linkGroup'+linkNum+'_Port'+portNum);
	}


	// Update linkGroup Field (hidden)
	function updateLinkGroupField(portNum) {
		var linkGroupArray = [];
	    for(L = 1; L < 5; L++) {
			if( $('#linkGroup'+L+'_Port'+portNum).prop('checked') ){ linkGroupArray.push(L); }
		}
		$('#linkGroup_Port'+portNum).val(linkGroupArray);

        console.log(linkGroupArray);
	}


	function showLinkGroups() {
		var linkGroupsVisible = 0;	

	    // Loop through 4 link groups
	    for(L = 1; L < 5; L++) {
			$('#linkGroup' + L).attr( 'data-port-count', eval('linkGroup' + L + '_PortCount') );
			if(eval('linkGroup' + L + '_PortCount') >= 2) {
				$('#LG' + L + '_count').html( eval('linkGroup' + L + '_PortCount') );
				$('#linkGroup' + L).fadeIn(500);
				linkGroupsVisible++;
			} else {
				$('#linkGroup' + L).fadeOut(500);
			}
		}
		
		// Remove existing column classes
		$('.lg_wrapper').removeClass (function (index, className) {
			return (className.match (/(^|\s)col-\S+/g) || []).join(' ');
		});

		// Add new column classes based on visable link groups
		switch(linkGroupsVisible) {
			case 0:
				$('#no_lg_msg').show();				
				break;
			case 1:
				$('#no_lg_msg').hide();				
				$('.lg_wrapper').addClass('col-md-6 col-sm-6 col-xs-12');
				break;
			case 2:
				$('#no_lg_msg').hide();				
				$('.lg_wrapper').addClass('col-md-6 col-sm-6 col-xs-12');
				break;
			case 3:
				$('#no_lg_msg').hide();				
				$('.lg_wrapper').addClass('col-md-4 col-sm-4 col-xs-12');
				break;
			default:
				$('#no_lg_msg').hide();				
				$('.lg_wrapper').addClass('col-md-3 col-sm-6 col-xs-12');			
		}

	}



	/*
	**********************************************************************
	 UPDATE DATABASE SETTINGS VIA AJAX CALL
	**********************************************************************
	*/

	$('.portForm').change(function() {
		var formID = $(this).attr('id');
		var portNum = $(this).attr('data-port-form');;

		updateLinkGroupField(portNum); // HACK: Refire this function since execution is not in order. Updates hidden linkGroup field.
		
		// Get current port entries, remove empty fields and create an object of results.
		var portFieldsObj = {};
		$.each($('#' + formID + " :input")
		    .filter(function(index, element) {
		        return $(element).val() != '';
		    })
			.serializeArray(), 
			function(_, kv) {
				portFieldsObj[kv.name] = kv.value;
			}
		);

		// Set portEnabled to 0 if not defined
		if (typeof portFieldsObj.portEnabled === 'undefined') {
			portFieldsObj.portEnabled = '0';
		}

		// Update linkGroup values to be an integer sub array for storage
		if (portFieldsObj.linkGroup != '') {
			portFieldsObj.linkGroup = $.map(portFieldsObj.linkGroup.split(','), function(value){ return parseInt(value, 10); });
		}

		// Nest object under port number to match input array format and return as JSON string for port.
		var portJSON = '{"' + portFieldsObj.portNum + '":' + JSON.stringify(portFieldsObj) + '}';

		// Replace with AJAX Call
		console.log(portJSON);

		// TESTING FOR SAVE STATUS SIMULAITON
		// sectionStatus(formID, 'portSection', 'processing');
		sectionStatus(formID, 'portSection', 'saved');
		// sectionStatus(formID, 'portSection', 'error');

		$('#orp_restart_btn').show();

	});


})