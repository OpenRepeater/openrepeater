$(function() {

// Show items hidden on page load if they are suppose to be visible
if($('#rxMode').val() == 'gpio') { 
    $('#rxGPIO_grp').show();
    $('#rxGPIO').prop('required',true); 
} else if($('#rxMode').val() == 'vox') {
    $('#rxGPIO_grp').hide(); 
    $('#rxGPIO').prop('required',false);
    $('#rxGPIO').val("");

	$('#rxVOX_warn').show();	
} else {
    $('#rxGPIO_grp').hide(); 
    $('#rxGPIO').prop('required',false);
    $('#rxGPIO').val("");

	$('#rxVOX_warn').hide();	
} 

	// Show/Hide applicable port method settings (Preset/Manual)
    $('#board_id').change(function(){

        if($('#board_id').val() == 'manual') {
            $('#port_manual_grp').show(); 

            $('#rxMode').prop('required',true);
            $('#rxGPIO').prop('required',true);
            $('#rxGPIO_active').prop('required',true);
            $('#rxAudioDev').prop('required',true);
            $('#txGPIO').prop('required',true);
            $('#txGPIO_active').prop('required',true);
            $('#txAudioDev').prop('required',true);

            $('#rxMode').val('gpio');
			$('#rxVOX_warn').hide();
            $('#rxGPIO_grp').show();

        } else {
            $('#port_manual_grp').hide();

            $('#rxMode').prop('required',false);
            $('#rxGPIO').prop('required',false);
            $('#rxGPIO_active').prop('required',false);
            $('#rxAudioDev').prop('required',false);
            $('#txGPIO').prop('required',false);
            $('#txGPIO_active').prop('required',false);
            $('#txAudioDev').prop('required',false);
        } 

    });


	// Show/Hide applicable rxMode fields/warnings
    $('#rxMode').change(function(){

        if($('#rxMode').val() == 'gpio') {
            $('#rxGPIO_grp').show();
            $('#rxGPIO').prop('required',true); 

			$('#rxVOX_warn').hide();	
        } else if($('#rxMode').val() == 'vox') {
            $('#rxGPIO_grp').hide(); 
            $('#rxGPIO').prop('required',false);
            $('#rxGPIO').val("");

			$('#rxVOX_warn').show();	
        } else {
            $('#rxGPIO_grp').hide(); 
            $('#rxGPIO').prop('required',false);
            $('#rxGPIO').val("");

			$('#rxVOX_warn').hide();	
        } 

    });

});


/*     ---Disabled because of firefox issues
// RX GPIO Validation
$("#rxGPIO").keypress(function(event) {
	var text = event.charCode || event.keyCode;
	return /\d/.test(String.fromCharCode(text));
});

$( "#rxGPIO" ).blur(function() {
	if($('#txGPIO').val() == $('#rxGPIO').val()) {
		alert( "Sorry, but you cannot use the same GPIO pin for COS and PTT control. Please try again." );	  
		$('#rxGPIO').val("");
		$('#rxGPIO').focus();
	}
});


// TX GPIO Validation
$("#txGPIO").keypress(function(event) {
	var text = event.charCode || event.keyCode;
	return /\d/.test(String.fromCharCode(text));
});

$( "#txGPIO" ).blur(function() {
	if($('#txGPIO').val() == $('#rxGPIO').val()) {
		alert( "Sorry, but you cannot use the same GPIO pin for COS and PTT control. Please try again." );	  
		$('#txGPIO').val("");
		$('#txGPIO').focus();
	}
});
*/