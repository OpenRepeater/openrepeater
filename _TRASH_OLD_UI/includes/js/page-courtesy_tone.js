function setCourtesyTone (fileName, activeNum, totalFiles, fileLabel) {
	$('#courtesy').val(fileName);
	$('#current_tone').html(fileLabel);

	for (i = 1; i <= totalFiles; i++) {
		$('#courtesyToneRow'+i).removeClass('active');
	}

	$('#courtesyToneRow'+activeNum).addClass('active');
	updateCourtesyTone ();
}




$(function() {
    if($('#courtesyMode').val() == 'disabled') {
        $('#disabled').show(); 
    } 

    if($('#courtesyMode').val() == 'beep') {
        $('#beep').show(); 
    } 

    if($('#courtesyMode').val() == 'custom') {
        $('#custom').show(); 
    } 

    $('#courtesyMode').change(function(){

        if($('#courtesyMode').val() == 'disabled') {
            $('#disabled').show(); 
        } else {
            $('#disabled').hide(); 
        } 

        if($('#courtesyMode').val() == 'beep') {
            $('#beep').show(); 
        } else {
            $('#beep').hide(); 
        } 

        if($('#courtesyMode').val() == 'custom') {
            $('#custom').show(); 
        } else {
            $('#custom').hide(); 
        } 


	    var $form = $("#courtesyModeUpdate");
	    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
	    $.ajax({
	        url: $form.attr("action"),
	        data: $form.serialize(),
	        type: method,
	        success: function() {
				$('.server_bar_wrap').show(); 
	        }
	    });


    });
});


// UPDATE SUB FORM VIA AJAX
// ----------------------------------------------------------

function updateCourtesyTone () {
    //submit changes to db
    var $form = $("#courtesyToneUpdate");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        success: function() {
			$('.server_bar_wrap').show(); 
        }
    });
}

$('#courtesyToneUpdate').on('change', function() {
	updateCourtesyTone ();
});

