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

	// Show/Hide applicable Long ID Settings, Update via AJAX
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



// RX GPIO Validation
$("#rxGPIO").keypress(function(event) {
	return /\d/.test(String.fromCharCode(event.keyCode));
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
	return /\d/.test(String.fromCharCode(event.keyCode));
});

$( "#txGPIO" ).blur(function() {
	if($('#txGPIO').val() == $('#rxGPIO').val()) {
		alert( "Sorry, but you cannot use the same GPIO pin for COS and PTT control. Please try again." );	  
		$('#txGPIO').val("");
		$('#txGPIO').focus();
	}
});
