$(function() {

    if($('#repeaterDTMF_disable').val() == 'True') {
        $('#dtmf_disable').show(); 
    } 

    $('#repeaterDTMF_disable').change(function(){
        if($('#repeaterDTMF_disable').val() == 'True') {
            $('#dtmf_disable').show(); 
        } else {
            $('#dtmf_disable').hide(); 
        } 
    });


	$('#settingsUpdate').on('change', function() {
	    //submit changes to db
	    var $form = $("#settingsUpdate");
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