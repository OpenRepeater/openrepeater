$(function() {

	// Variables
	var moduleOptions = buildModuleSelectOptions(JSON.parse(modulesAvailable));
	var portOptions = buildPortSelectOptions(JSON.parse(portsAvailable));
	var macroArray = [];
	var selectedMacros = [];


	// Loop through JSON array of macros and build display
	fullMacroObj = JSON.parse(macroList);
	if ( $.isEmptyObject(fullMacroObj) ) {
		// No results so hide table and show message
		$('#macro-table-responsive').hide();
		$('#no_macros').fadeIn(500);
	} else {
		// Macro object contains results so display them. 
		$.each(fullMacroObj, function(index, curMacro) {
			buildMacroRow(curMacro);
		});
	}


	function buildMacroRow(input) {
		var $template = $('#macroRowTemplate').html();
		$template = $template.replace(/%%MACRO%%/g, input.macroKey)
			.replace(/%%MODULE_OPTIONS%%/g, moduleOptions)
			.replace(/%%PORT_OPTIONS%%/g, portOptions);

	    $('#macro-table-responsive tbody').append($template);		

		if(input.macroEnabled == '1') {
			$('#macroEnabled'+input.macroKey).prop( 'checked', true );
		} else {
			$('#macroEnabled'+input.macroKey).prop( 'checked', false );
		}
 		resetSwitchery('#macroEnabled'+input.macroKey);


		$('#macroNum'+input.macroKey).val(input.macroNum);
		$('#macroNum'+input.macroKey).attr('data-last-value', input.macroNum);

		$('#macroLabel'+input.macroKey).val(input.macroLabel);
		$('#macroModuleKey'+input.macroKey).val(input.macroModuleKey);
		$('#macroString'+input.macroKey).val(input.macroString);
		$('#macroPorts'+input.macroKey).val(input.macroPorts);

		macroArray.push(input.macroKey);
		selectedMacros.push(input.macroNum);
	}


	// Input mask for Macro String
	$('#macro-table-responsive tbody').on('keypress','.macroString', function (e) {
		var keyCode = e.which;

		/*  35 - #, 42 - *, 48-57 - 0-9, 65-68 - A-D, 97-100 - a-z, 8 - (backspace) */
		if ( 
			!( (
				keyCode >= 48 && keyCode <= 57) 
				||(keyCode >= 65 && keyCode <= 68) 
				|| (keyCode >= 97 && keyCode <= 100)
			) 
			&& keyCode != 35 
			&& keyCode != 42 
			&& keyCode != 8 
		) {
			e.preventDefault();
    	}
	});


	function getNextNum(inputArray) {
		i = 1;
		while (i < 100) {
			if( $.inArray(i, inputArray) > -1 ) {
				i++; // Already exists...move to next index
			} else {
				return i; // Return free value
			}
		}
	}


	// Add Macro Function
	$('.add_macro').click(function(e) {
		// Set visibility for first created row.
		if(!$('#macro-table-responsive').is(":visible")){
			$('#no_macros').hide();
			$('#macro-table-responsive').fadeIn(500);
		}

		var newMacroID = getNextNum(macroArray);
		buildMacroRow({
			"macroKey": newMacroID,
			"macroEnabled": "0",
			"macroNum": getNextNum(selectedMacros),
			"macroLabel": "",
			"macroModuleKey": "",
			"macroString": "",
			"macroPorts": ""
		});
		
		$('#macroRow'+newMacroID+' .macroLabel').focus();
		
	});


	// Delete Macro Function and Modal Display
	$('#macro-table-responsive').on('click', '.deleteMacro', function(e) {
		e.preventDefault();
		var macroNum = $(this).parents('tr').attr('data-macro-number');
		var macroDesc = $('#macroLabel' + macroNum).val().trim();

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> ' + modal_DeleteMacroTitle,
			body: '<p>'+modal_DeleteMacroBody+':<br><strong>'+macroDesc+'</strong></p>',
			btnOK: modal_DeleteMacroBtnOK,
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			deleteString = JSON.stringify({ deleteMacro: macroNum });

			orpModalWaitBar(modal_DeleteMacroProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_db_update.php',
				data: {'deleteMacro': macroNum},
				success: function(jsonResponse){
					$('#orp_modal').modal('hide');
					var response = JSON.parse(jsonResponse);
					if (response.login == 'timeout') {
						sectionStatus('macroRow' + macroNum, 'macroRow', 'error');
						orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
					} else if (response.status == 'success') {
						$('#macroRow' + macroNum).slideUp(500);
						$('#macroRow' + macroNum).remove();
	
						//Display Message
						orpNotify('success', modal_DeleteMacroNotifyTitle, modal_DeleteMacroNotifyDesc);
	
						// If no rows remain, then hide table and show message
						if ( $('.macroRow').length == 0 ) {
							$('#macro-table-responsive').hide();
							$('#no_macros').fadeIn(500);
						}
						rebuildActive();
					} else {
						sectionStatus('macroRow' + macroNum, 'macroRow', 'error');
					}
				}
			});

		});
	});



	// Build Select Options for Data Passed from PHP
	function buildModuleSelectOptions(input) {
		var selectOptions = '';
		$.each(input, function(index, subObj) {
			selectOptions += '<option value="'+subObj.moduleKey+'">'+subObj.displayName;
			if (subObj.moduleEnabled != 1) {
				selectOptions += '--DISABLED';
			}
			selectOptions += '</option>';
		});
		return selectOptions;
	}

	function buildPortSelectOptions(input) {
		var selectOptions = '<option value="ALL">'+allPortsName+'</option>';
		$.each(input, function(index, subObj) {
			selectOptions += '<option value="'+index+'">';
			selectOptions += portName+' '+index;
			selectOptions += ' ('+subObj.portLabel+')';
			if (subObj.portEnabled != 1) {
				selectOptions += '--DISABLED';
			}
			selectOptions += '</option>';
		});
		return selectOptions;
	}


	// Reset Switchery Toggles After Dynamic Build
	function resetSwitchery(checkboxID) {
 		 $(checkboxID).next().remove('span'); // remove inital switcher span
 		 var elems = $(document).find(checkboxID);
 		 var switchery = new Switchery( elems[0], { color: '#8dc63f' } );
 		 switchery.handleOnchange();
	}


	$('#macro-table-responsive').on('change', '.macroNum', function() {
		var curMacroNumID = $(this).attr('id');

		oldValue = $('#'+curMacroNumID).attr('data-last-value');
		console.log(oldValue);
		newValue = parseInt( $('#'+curMacroNumID).val() );
		selectedMacros.push(newValue);
		$('#'+curMacroNumID).attr('data-last-value', newValue);
		
	});

	/*
	**********************************************************************
	 UPDATE DATABASE SETTINGS VIA AJAX CALL
	**********************************************************************
	*/

	$('#macro-table-responsive').on('change', '.macroRow', function() {
		var formID = $(this).attr('id');
		var emptyFields = 0;

		// Validate Macro Label
		if ( $('#' + formID + ' .macroLabel').val() == '' ) {
			$('#' + formID + ' .macroLabel').focus();
			emptyFields++;			
		}

		// Validate Selected Moodule
		if ( $('#' + formID + ' .macroModuleKey').find(":selected").val() == '' ) {
			$('#' + formID + ' .macroModuleKey').focus();
			emptyFields++;			
		}

		// Validate Selected Port
		if ( $('#' + formID + ' .macroPorts').find(":selected").val() == '' ) {
			$('#' + formID + ' .macroPorts').focus();
			emptyFields++;			
		}

		// Validate Macro String
		if ( $('#' + formID + ' .macroString').val() == '' ) {
			$('#' + formID + ' .macroString').focus();
			emptyFields++;			
		}

		if (emptyFields == 0) {
			sectionStatus(formID, 'macroRow', 'processing');	
			var macroFieldsObj = {};
			$.each($('#' + formID + " :input")
			    .filter(function(index, element) {
			        return $(element).val() != '';
			    })
				.serializeArray(), 
				function(_, kv) {
					macroFieldsObj[kv.name] = kv.value;
				}
			);
	
			// Nest object under macro key number to match input array format and return as JSON string for macro.
			var macroJSON = '{"' + macroFieldsObj.macroKey + '":' + JSON.stringify(macroFieldsObj) + '}';

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_db_update.php',
				data: {'updateMacro': macroJSON},
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					if (response.login == 'timeout') {
						sectionStatus(formID, 'macroRow', 'error');
						orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
					} else if (response.status == 'success') {
						sectionStatus(formID, 'macroRow', 'saved');
						sectionStatus(formID, 'portSection', 'saved');
						rebuildActive();
					} else {
						sectionStatus(formID, 'macroRow', 'error');
					}
				}
			});
			
		}
	});

})