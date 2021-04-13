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
			console.log( newOrderResults );
		},
	});


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
			$(rowID + ' .settings').attr( 'href', '/modules/'+module.svxlinkName+'/settings.php' );
		} else {
			$(rowID + ' .settings').remove();
		}
		
		// DTMF Help Link
		if(module.dtmf == true) {
			$(rowID + ' .dtmf').attr( 'href', dtmfPage+'#'+module.svxlinkName );
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


	// Loop through JSON array of modules build display
	fullModuleObj = JSON.parse(moduleList);
	$.each(fullModuleObj, function(index, curMod) {
		displayModule(curMod);
	});


	// Module Enable/Disable Function
	$('#moduleWrap').on('change', '.modActive', function() {
		var moduleID = $(this).parents('.moduleRow').attr('data-module-id');
		if(this.checked) {
			var moduleState = { moduleKey: moduleID, moduleEnabled: '1' }
			$(this).parents('.moduleRow').removeClass('deactive');
		} else {
			var moduleState = { moduleKey: moduleID, moduleEnabled: '0' }
			$(this).parents('.moduleRow').addClass('deactive');
		}
		console.log(moduleState);
	});


	// Delete Module Function
	$('#moduleWrap').on('click', 'button.delete', function() {
		var moduleID = $(this).parents('.moduleRow').attr('data-module-id');
		var moduleDisplayName = $( '#Row' + moduleID + ' .modName' ).html();

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

			// TEMP SIMULATION OF REBUILD TIME
			setTimeout(function() {
				$('#orp_modal').modal('hide');

				$( '#Row' + moduleID ).fadeOut('1000');

				$('#orp_restart_btn').show();
				new PNotify({
					title: modDelSuccessTitle,
					text: modDelSuccessBody,
					type: 'success',
					styling: 'bootstrap3'
				});

			}, 2000);
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

	// Add Dummy Module
	$('#tempBtn').on('click', function() {
		var nextID = nextSVXLinkID();
		var nextRowID = parseInt( nextSVXLinkID() ) + 1;

		var moduleState = {
			moduleKey: nextRowID.toString(),
			moduleEnabled: '1',
			svxlinkName: 'fakeModule',
			displayName: 'Fake Module',
			svxlinkID: nextID,
			desc: 'desc',
			version: 'Version: 0.1 | Authors: Fake Person (F0AKE)',			
// 			type: 'daemon',
			settings: true,
			dtmf: false
		}

		displayModule(moduleState);
	});

});