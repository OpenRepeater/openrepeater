$(document).ready(function() {

	// Remote Disable Group - Visability Toggle
	if ($('#repeaterDTMF_disable').is(':checked')){
		console.log('other checked');
		$('.remoteDisableGroup').removeClass('collapse');
	}
    $('#repeaterDTMF_disable').change(function() {
        if(this.checked) {
			$('.remoteDisableGroup').removeClass('collapse');
        } else {
			$('.remoteDisableGroup').addClass('collapse');
        }
    });


	// CTCSS Group - Visability Toggle
	if ( $('#useCTCSS').is(':checked') ) {
		// One or more CTCSS tones are set
		$('.useCTCSSgroup').removeClass('collapse');
	}
    $('#useCTCSS').change(function() {
        if(this.checked) {
			$('.useCTCSSgroup').removeClass('collapse');
        } else {
			$('.useCTCSSgroup').addClass('collapse');
			$('#rxTone').val('0');
			$('#txTone').val('0');
        }
    });


	$('#getGPS').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-lock"></i> ' + modal_gpsTitle,
			body: modal_gpsBody,
			btnOK: modal_gpsButton,
		};
		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			orpModalWaitBar();

			// TEMP SIMULATION OF REBUILD TIME
			setTimeout(function() {
				$('#orp_modal').modal('hide');
				$('#orp_restart_btn').hide();

				$('#locLatitude').val('41.714762');
				$('#locLongitude').val('-72.727193');

				orpNotify('success', modal_gpsSuccessMsgTitle, modal_gpsSuccessMsg);

			}, 2000);
		});
	});



	/*
	**********************************************************************
	 UPDATE DATABASE SETTINGS VIA AJAX CALL
	**********************************************************************
	*/

	$('.settingsForm').change(function() {
		var formID = $(this).attr('id');
		sectionStatus(formID, 'x_panel', 'processing');

		// Get current settings, remove empty fields and create an object of results.
		var settingsFieldsObj = {};
		$.each($('#' + formID + " :input")
		    .filter(function(index, element) {
		        return $(element).val() != '';
		    })
			.serializeArray(), 
			function(_, kv) {
				settingsFieldsObj[kv.name] = kv.value;
			}
		);

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: {'settings': JSON.stringify(settingsFieldsObj)},
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					sectionStatus(formID, 'x_panel', 'error');
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
				} else if (response.status == 'success') {
					sectionStatus(formID, 'x_panel', 'saved');
					rebuildActive();
				} else {
					sectionStatus(formID, 'x_panel', 'error');
				}
			}
		});

	});


	$('.locationForm').change(function() {
		var formID = $(this).attr('id');
		sectionStatus(formID, 'x_panel', 'processing');

		// Get current location settings and create an object of results. Leave empty fields intact.
		var locationFieldsObj = {};
		$.each($('#' + formID + " :input")
			.serializeArray(), 
			function(_, kv) {
				locationFieldsObj[kv.name] = kv.value;
			}
		);

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: {'location': JSON.stringify(locationFieldsObj)},
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					sectionStatus(formID, 'x_panel', 'error');
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
				} else if (response.status == 'success') {
					sectionStatus(formID, 'x_panel', 'saved');
					rebuildActive();
				} else {
					sectionStatus(formID, 'x_panel', 'error');
				}
			}
		});

	});


});