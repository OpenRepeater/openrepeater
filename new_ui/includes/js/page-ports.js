$(function() {

	// Variables
	var advLogicOptions = buildSelectOptions(logicOptions);
	var advRxOptions = buildSelectOptions(rxOptions);
	var advTxOptions = buildSelectOptions(txOptions);
	

	/*
	**********************************************************************
	 PORT ADD/REMOVE/UPDATE FUNCTIONS
	**********************************************************************
	*/

	// Loop through JSON array of ports and build display
	fullPortObj = JSON.parse(portList);
	$.each(fullPortObj, function(index, curPort) {
		displayPort(curPort);
	});


	// Dynamically Build Port
	function displayPort(port) {
		var $template = $('#rowTemplate').html();
		$template = $template.replace(/%%currPortNum%%/g, port.portNum)
			.replace(/%%currPortLabel%%/g, port.portLabel);


// 			$template.hide();                        // Hide while updating

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
		if(port.linkGroup == '') {
			$('#linkGroupOff_Port'+port.portNum).parents('label').addClass('active');
			$('#linkGroupOff_Port'+port.portNum).attr('checked',true);
// 			$(portID + ' .portLabelLinkGrp.1' ).show();
		}
		if(port.linkGroup == '1') {
			$('#linkGroup1_Port'+port.portNum).parents('label').addClass('active');
			$('#linkGroup1_Port'+port.portNum).attr('checked',true);
			$(portID + ' .portLabelLinkGrp.1' ).show();
		}
		if(port.linkGroup == '2') {
			$('#linkGroup2_Port'+port.portNum).parents('label').addClass('active');
			$('#linkGroup2_Port'+port.portNum).attr('checked',true);
			$(portID + ' .portLabelLinkGrp.2' ).show();
		}
		if(port.linkGroup == '3') {
			$('#linkGroup3_Port'+port.portNum).parents('label').addClass('active');
			$('#linkGroup3_Port'+port.portNum).attr('checked',true);
			$( portID + ' .portLabelLinkGrp.3' ).show();
		}
		if(port.linkGroup == '4') {
			$('#linkGroup4_Port'+port.portNum).parents('label').addClass('active');
			$('#linkGroup4_Port'+port.portNum).attr('checked',true);
			$(portID + ' .portLabelLinkGrp.4' ).show();
		}


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
		if ( $('#rxMode' + port.portNum).val(port.rxMode) == 'vox' ) { $('#voxMsg' + port.portNum).show(); } else { $('#voxMsg' + port.portNum).hide(); }
		$('#rxGPIO' + port.portNum).val(port.rxGPIO);
		$('#rxGPIO_active' + port.portNum).val(port.rxGPIO_active);
		$('#txGPIO' + port.portNum).val(port.txGPIO);
		$('#txGPIO_active' + port.portNum).val(port.txGPIO_active);

/* HIDRAW TAB SETTINGS */
/* SERIAL TAB SETTINGS */
/* MODULE TAB SETTINGS */




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


		/* DISPLAY PORT */

		$(portID).fadeIn(1000); // Fade In to Display

	}


	// Reset Switchery Toggles After Dynamic Build
	function resetSwitchery(checkboxID) {
 		 $(checkboxID).next().remove('span'); // remove inital switcher span
 		 var elems = $(document).find(checkboxID);
 		 var switchery = new Switchery(elems[0]);
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
			title: '<i class="fa fa-trash"></i> Delete Port',
			body: '<p>Are you sure you want to delete<br><strong>'+portDesc+'</strong>?</p>',
			btnOK: 'Delete Forever',
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			console.log('submit');
			orpModalWaitBar('Deleting Port');

			setTimeout(function() {
				$('#orp_modal').modal('hide');
				$('#portNum' + portNum).slideUp(1000);
			}, 2000);
		});

/*
		var portLabel = $(this).val().trim();
		if ( portLabel == '' ) {
			$('#portNum' + portNum + ' .panel-title span').html('(no label set yet)');
		} else {
			$('#portNum' + portNum + ' .panel-title span').html(portLabel);
		}
*/

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


	// 	Link Group Change
	$('.linkGroup input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		switch ($(this).val()) {
			case '':
				$('#portNum' + portNum + ' .portLabelLinkGrp.1').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.2').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.3').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.4').hide();
				break;
			case '1':
				$('#portNum' + portNum + ' .portLabelLinkGrp.1').show();
				$('#portNum' + portNum + ' .portLabelLinkGrp.2').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.3').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.4').hide();
				break;
			case '2':
				$('#portNum' + portNum + ' .portLabelLinkGrp.1').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.2').show();
				$('#portNum' + portNum + ' .portLabelLinkGrp.3').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.4').hide();
				break;
			case '3':
				$('#portNum' + portNum + ' .portLabelLinkGrp.1').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.2').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.3').show();
				$('#portNum' + portNum + ' .portLabelLinkGrp.4').hide();
				break;
			case '4':
				$('#portNum' + portNum + ' .portLabelLinkGrp.1').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.2').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.3').hide();
				$('#portNum' + portNum + ' .portLabelLinkGrp.4').show();
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
		} else {
			$('#voxMsg' + portNum).fadeOut(1000);
		}
	});



	/*
	**********************************************************************
	 UPDATE DATABASE SETTINGS VIA AJAX CALL
	**********************************************************************
	*/

	$('.portForm').change(function() {
		var formID = $(this).attr('id');
		var portNum = $(this).attr('data-port-form');;
		
		// Remove empty form entries and serialize results
		var formArray = $( '#' + formID + " :input")
		    .filter(function(index, element) {
		        return $(element).val() != '';
		    })
		    .serializeArray();		
		
		// Set portEnabled to 0 if not enabled
		if (typeof formArray.find(item => item.name === 'portEnabled') == 'undefined') {
			formArray.push({name: 'portEnabled', value: '0'});
		}

		
// 		formArray.find(item => item.name === 'portEnabled').value = "something else";

// 		console.log('form changed: '+portNum);
		console.log(formArray);


		$('#orp_restart_btn').show();

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
	 ADVANCED SVXLINK FIELDS
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
				
		console.log(returnString);
		// Replease with AJAX Call
		
		$('#orp_restart_btn').show();

	});



})