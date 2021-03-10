$(document).ready(function() {

	// Remote Disable Group - Visability Toggle
	if ($('#remoteDisable').is(':checked')){
		$('.remoteDisableGroup').removeClass('collapse');
	}
    $('#remoteDisable').change(function() {
        if(this.checked) {
			$('.remoteDisableGroup').removeClass('collapse');
        } else {
			$('.remoteDisableGroup').addClass('collapse');
        }
    });


	// CTCSS Group - Visability Toggle
	if ( $('#rxTone').val() > 0 || $('#txTone').val() > 0 ) {
		// One or more CTCSS tones are set
		$('#useCTCSS').trigger('click');
		$('.useCTCSSgroup').removeClass('collapse');
	}
    $('#useCTCSS').change(function() {
        if(this.checked) {
			$('.useCTCSSgroup').removeClass('collapse');
        } else {
			$('.useCTCSSgroup').addClass('collapse');
			$('#rxCTCSS').val('0');
			$('#txCTCSS').val('0');
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

				new PNotify({
					title: modal_gpsSuccessMsgTitle,
					text: modal_gpsSuccessMsg,
					type: 'success',
					styling: 'bootstrap3'
				});
				/*
				new PNotify({
					title: modal_gpsFailMsgTitle,
					text: modal_gpsFailMsg,
					type: 'error',
					styling: 'bootstrap3'
				});
				*/

			}, 2000);
		});
	});


});