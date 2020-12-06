// Main Scripts
$(function() {

	$('#dtmf_input').focus();


	$("#dtmf_input").keyup(function(e){
		var keyCode = e.which;

		if (keyCode == 13) { // Enter
			e.preventDefault();
			$('#send_dtmf').click()
		} else if (keyCode == 27) { // Esc
			e.preventDefault();
			$('#dtmf_input').val('');
			$('#dtmf_input').focus();
		}
	});


	$("#dtmf_input").keypress(function(e){
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


	$('#send_dtmf').live('click', function(e) {
		var logic_path = $('#logic_section').val();
		var dtmf = $('#dtmf_input').val().toUpperCase();

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_orp_helper.php',
			data: { type: 'send_dtmf', logicPath: logic_path, dtmfString: dtmf },
			dataType: 'JSON',
			success: function(response) {
				var status = response.status;
				if(status == 'true') {
					console.log ('Command Sent to SVXLink');
				} else {
					console.log ('Command Failed to Send');
				}
			}
		});

		$('#dtmf_input').val('');
		$('#dtmf_input').focus();
	});


});