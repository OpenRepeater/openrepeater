$(function() {

	var dtmfPage = 'dtmf.php';

	$("#moduleListSort").sortable({
		placeholder: "ui-sortable-placeholder",
		update: function(event, ui) {
			var newSortOrder = { };
			$('#moduleListSort .moduleRow').each(function(i) {
				var pid = $(this).attr('data-module-id');
				var svxid = (i + 1) + '';

				$(this).attr('data-svxlink-id', svxid);
				$(this).find('.svxlinkID').html(svxid);
				$(this).find('.largeDigit').html(svxid);

				newSortOrder [pid] = {};
				newSortOrder [pid]['moduleKey'] = pid;
				newSortOrder [pid]['svxlinkID'] = svxid;
			});
			newOrderResults = JSON.stringify(newSortOrder);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_db_update.php',
				data: {'moduleWrite': newOrderResults},
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					if (response.login == 'timeout') {
						orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
					} else if (response.status == 'success') {
						rebuildActive();
					} else {
						// no error response at this time.
					}
				}
			});

		},
	});




	// Loop through JSON array of modules build display
// DEVELOPMENT
console.log('IN: ' + moduleList);
	fullModuleObj = JSON.parse(moduleList);

	// Middle Object to remove soritng by module ID. DB query sorts by svxlinkID
	var svxlinkSortObj = {};
	$.each(fullModuleObj, function(index, curMod) {
		svxlinkSortObj[curMod.svxlinkID]=curMod;
	});

	// Actual Display Loop
	$.each(svxlinkSortObj, function(index, curMod) {
		displayModule(curMod);
	});


	// Module Enable/Disable Function
	$('#moduleWrap').on('change', '.modActive', function() {
		var rowID = $(this).parents('.moduleRow').attr('id');
		var moduleID = $('#'+rowID).attr('data-module-id');
		var svxlinkName = $('#'+rowID).attr('data-svxlink-name');
		if(this.checked) {
			var moduleStateObj = { moduleKey: moduleID, moduleEnabled: '1' };
			var modulePrevState = false;
			$(this).parents('.moduleRow').removeClass('deactive');
			if ( $('#nav_'+svxlinkName).length ) {
				$('#nav_'+svxlinkName).show();			
			} else if ( $('#'+rowID+' .settings').length ){
				var modDisplayName = $('#'+rowID+' .modName').text();
				var svxlinkID = $('#'+rowID).attr('data-svxlink-id');
				var settingsURL = 'modules.php?settings='+moduleID;
				$('#navModules').append('<li><a id="nav_'+svxlinkName+'" data-svxlinkid="'+svxlinkID+'" class="navLink" href="'+settingsURL+'">'+modDisplayName+'</a></li>');
			}
		} else {
			var moduleStateObj = { moduleKey: moduleID, moduleEnabled: '0' };
			var modulePrevState = true;
			$(this).parents('.moduleRow').addClass('deactive');
			$('#nav_'+svxlinkName).hide(); // hide nav menu.
		}
		var moduleJSON = JSON.stringify(moduleStateObj);

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: {'moduleState': moduleJSON},
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
				} else if (response.status == 'success') {
					rebuildActive();
				} else {
					// Future: Reset swtich/row state to previous. See modulePrevState variable
				}
			}
		});

	});


	// Delete Module Function
	$('#moduleWrap').on('click', 'button.delete', function() {
		var moduleID = $(this).parents('.moduleRow').attr('data-module-id');
		var moduleDisplayName = $( '#Row' + moduleID + ' .modName' ).html();

		var deleteString = {'deleteModule': moduleID};

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> ' + modDelConfirmTitle,
			body: modDelConfirmBody + ': <strong>' + moduleDisplayName + '</strong>',
			btnOK: modDelConfirmBtn,
			btnOKclass: 'btn-danger'
		};
		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			orpModalWaitBar();

// DEVELOPMENT
console.log('DELETE: ' + JSON.stringify(deleteString));

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_db_update.php',
				data: {'deleteModule': moduleID},
				success: function(jsonResponse){
					$('#orp_modal').modal('hide');
					var response = JSON.parse(jsonResponse);
					if (response.login == 'timeout') {
						orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
					} else if (response.status == 'success') {
						$( '#Row' + moduleID ).fadeOut('1000');
						orpNotify('success', modDelSuccessTitle, modDelSuccessBody);
						rebuildActive();
					} else {
						orpNotify('success', modDelErrorTitle, modDelErrorBody);
					}
				}
			});

		});

	});

	function existingSVXLinkIDs() {
		curSVXLinkIDs = [];
		$('#moduleWrap .moduleRow').each(function(i) {
			var svxid = $(this).attr('data-svxlink-id');
			if(svxid != '') {
				curSVXLinkIDs.push(svxid);
			}
		});
		return curSVXLinkIDs.sort();
	}

	function nextSVXLinkID() {
		var existingIDs = existingSVXLinkIDs();
		for(i = 0; i<100; i++) {
			if( $.inArray( i.toString(), existingIDs ) == -1 ) {
				// ID is availalbe
				return i.toString();
			}
		}
	}

});



/* ------------------------------------------------------------------------- */
// UPLOAD CALLBACK FUNCTION

function uploadCallback (jsonResponse) {
	var response = JSON.parse(jsonResponse);
	if (response.status == 'success') {
		var newModule = response.data;

		console.log(newModule);

		var moduleState = {
			moduleKey: newModule.moduleKey,
			moduleEnabled: 0,
			svxlinkName: newModule.mod_name,
			displayName: newModule.display_name,
			svxlinkID: newModule.svxlinkID,
			desc: newModule.mod_desc,
			version: 'Version: ' + newModule.version + ' | Authors: ' + newModule.authors,			
// 			type: 'daemon',
			settings: true,
			dtmf: false
		}

		displayModule(moduleState);

		orpNotify(newModule.status, newModule.display_name , newModule.msgText);

	} else if (response.status == 'error') {
		// orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
		console.log('Upload Error');
	}
}







function displayModule(module) {
	var rowID = 'Row' + module.moduleKey;

	var template = $('#rowTemplate')
	  .clone()  // CLONE THE TEMPLATE
	  .attr('id', rowID);  // MAKE THE ID UNIQUE

	rowID = '#' + rowID; 

	switch(module.type) {
		case 'core':
			if(module.svxlinkID==0) { // Only core module Help with ID 0 is to remain unsortabled.
				template.appendTo($('#moduleList'))  // APPEND TO THE TABLE
				  .hide();                        // Hide while updating
			} else {
				template.appendTo($('#moduleListSort'))  // APPEND TO THE TABLE
				  .hide();                        // Hide while updating
			}
			$(rowID + ' .largeDigit').html(module.svxlinkID); // Set SVXLink Number
			$(rowID + ' .modType').html(modTypeCore); // Set Module Type
			$(rowID + ' .modType').addClass('bg-orange'); // Set Type Class
			$(rowID + ' .delete').remove(); // Remove delete button since core module
			break;

		case 'daemon':
			template.appendTo($('#moduleList'))  // APPEND TO THE TABLE
			  .hide();                        // Hide while updating
			$(rowID + ' .largeDigit').html(''); // Set SVXLink Number
			$(rowID + ' .modType').html(modTypeDaemon); // Set Module Type
			$(rowID + ' .modType').addClass('bg-red'); // Set Type Class
			break;

		default:
			template.appendTo($('#moduleListSort'))  // APPEND TO THE TABLE
			  .hide();                        // Hide while updating
			$(rowID + ' .largeDigit').html(module.svxlinkID); // Set SVXLink Number
			$(rowID + ' .modType').html(modTypeAddOn); // Set Module Type
			$(rowID + ' .modType').addClass('bg-green'); // Set Type Class

	}

	$(rowID).attr('data-module-id', module.moduleKey);
	$(rowID).attr('data-svxlink-id', module.svxlinkID);
	$(rowID).attr('data-svxlink-name', module.svxlinkName);

	$(rowID + ' .modName').html(module.displayName); // Set Module Name

	$(rowID + ' .modDesc').html(module.desc); // Set Description
	
	// Add Version & Authors
	if(module.version != '') {
		$(rowID + ' .modInfo').append('<span class="modVersion">'+module.version+'</span>'); 
	}

	// Setup Module Enable/Disable Switch
	if(module.moduleEnabled == '1') {
		$(rowID + ' .js-switch').prop( 'checked', true );
	} else {
		$(rowID + ' .js-switch').prop( 'checked', false );
		$(rowID).addClass('deactive'); // Setup Module Row as Disabled
	}
	resetSwitchery(rowID + ' .js-switch');

	// Settings Page Link
	if(module.settings == true) {
		$(rowID + ' .settings').attr( 'href', 'modules.php?settings='+module.moduleKey );
// 			?settings=3
	} else {
		$(rowID + ' .settings').remove();
	}
	
	// DTMF Help Link
	if(module.dtmf == true) {
// 		$(rowID + ' .dtmf').attr( 'href', dtmfPage+'#'+module.svxlinkName );
	} else {
		$(rowID + ' .dtmf').remove();
	}

	$(rowID).fadeIn(1000); // Fade In to Display
}


function resetSwitchery(checkboxID) {
		 $(checkboxID).next().remove('span'); // remove inital switcher span
		 var elems = $(document).find(checkboxID);
		 var switchery = new Switchery( elems[0], { color: '#8dc63f' } );
		 switchery.handleOnchange();
}
