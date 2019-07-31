$(function() {

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



// Variables
var maxSysPorts = 8;
var minReqPorts = 1;


// Sub Functions
function htmlTemplate(n) {
	var html = '<p class="portRow additional">'+
	'<span>'+
	'<input type="text" name="portNum[]" value="' + n + '" style="width:15px;display:none;"> '+
	'<input type="text" required="required" name="portLabel[]" placeholder="Port Label" class="portLabel" value="Port ' + n + '"> '+
	'</span>'+
	'<span class="rx">'+
	'<input type="text" required="required" name="rxGPIO[]" placeholder="rxGPIO" class="rxGPIO"> '+
	'<select name="rxAudioDev[]" class="rxAudioDev">'+
	'	<option>---</option>'+ jsAudioInputOptions +
	'</select> '+
	'</span>'+
	'<span class="tx">'+
	'<input type="text" required="required" name="txGPIO[]" placeholder="txGPIO" class="txGPIO"> '+
	'<select name="txAudioDev[]" class="txAudioDev">'+
	'	<option>---</option>'+ jsAudioOutputOptions +
	'</select> '+
	'</span>'+

	'<!-- Button triggered modal -->'+
	'<button type="button" class="btn port_settings" data-toggle="modal" data-target="#portDetails' + n + '" title="Extra settings for this port"><i class="icon-cog"></i></button>'+
	'<a href="#" id="removePort">Remove</a>'+
	'</p>'+
	
	'<!-- Modal - ADVANCED DETAIL DIALOG -->'+
	'<div class="modal fade" id="portDetails' + n + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
	'<div class="modal-dialog">'+
	'<div class="modal-content">'+
	'<div class="modal-header">'+
	'<h3 class="modal-title" id="myModalLabel">Extra Settings (Port ' + n + ')</h3>'+
	'</div>'+
	'<div class="modal-body">'+
	'<fieldset>'+
	'<div class="control-group">'+
	'<label class="control-label" for="rxGPIO_active' + n + '">RX Control Mode</label>'+
	'<div class="controls">'+
	'	<select id="rxMode' + n + '" name="rxMode[]" class="rxMode">'+
	'		<option value="gpio" selected>COS</option>'+
	'		<option value="vox">VOX</option>'+
	'	</select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+
	'<br>'+
	'<div class="alert alert-danger">'+
	'<strong>WARNING:</strong> The VOX receive mode is experimental. It may provide unpredictable results and keying of the system due to spurious noise and audio levels. It strongly recommended that you use the COS Mode if at all possible.'+
	'</div>'+
	'<div class="control-group">'+
	'<label class="control-label" for="rxGPIO_active' + n + '">RX Active GPIO State</label>'+
	'<div class="controls">'+
	'  <select id="rxGPIO_active' + n + '" name="rxGPIO_active[]" class="rxGPIO_active">'+
	'  	<option value="high" selected>Active High</option>'+
	'  	<option value="low">Active Low</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+
	'<hr>'+
	'<div class="control-group">'+
	'<label class="control-label" for="txGPIO_active' + n + '">TX Active GPIO State</label>'+
	'<div class="controls">'+
	'  <select id="txGPIO_active' + n + '" name="txGPIO_active[]" class="txGPIO_active">'+
	'  	<option value="high" selected>Active High</option>'+
	'  	<option value="low">Active Low</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'</fieldset>'+
	'</div>'+
	'<div class="modal-footer">'+
	'<button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-remove"></i> Close</button>'+
	'</div>'+
	'</div><!-- /.modal-content -->'+
	'</div><!-- /.modal-dialog -->'+
	'</div>'+
	'<!-- /.modal -->';
	return html;
}


function updatePortCount(n) {
	if(n==1) {
	    $('#portCount').html( n + ' Port' ); // singular
	} else {
	    $('#portCount').html( n + ' Ports' ); // plural
	}
}

function checkMaxPort(currentPorts, sysMax, inPorts, outPorts) {
	if (currentPorts >= inPorts || currentPorts >=outPorts) {
		alert("Sorry, but you cannot add anymore ports because you do not have the required audio inputs and outputs required to support these ports. The system has detected that you have "+inPorts+" audio input channel(s) and "+outPorts+" audio output channel(s). Please add more supported audio devices first to be able to add more ports.");
		return false;
	}
	if (currentPorts >= sysMax) {
		alert("Sorry, but you cannot add anymore ports because you already have the maximum number of ports the system will support.");
		return false;
	}
	return true;
}

function checkMinPort(currentPorts, minPorts) {
	if (currentPorts <= minPorts) {
		alert("Sorry, but you cannot remove any more ports because this system requires a minimum of "+minPorts+" port(s).");
		return false;
	}
	return true;
}

//Update Database
function updateDB() {
	// update DB	
    var $form = $("#portsUpdate");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        type: method,
        success: function() {
			$('.server_bar_wrap').show(); 
        }
    });
	
	//Display Message
	var msgText = "The port settings have been updated successfully!";
	$('#alertWrap').html('<div class="alert alert-success">'+msgText+'</div>');
}

function loadBoardPreset() {
//	document.getElementById('loadBoardPreset').submit();
	console.log('submit form');
}


// Main Scripts
$(window).load(function(){
	$(function() {
	    var inputPorts = $('#detectedRX').val();
		var outputPorts = $('#detectedTX').val();
	
	    var portsDiv = $('#portsWrap');
	    var i = $('#portsWrap p').size();
	
		updatePortCount(i);
	
	    
	
	    $('#addPort').live('click', function() {
	            if(checkMaxPort(i, maxSysPorts, inputPorts, outputPorts)) {
	                i++;
	
	                $(htmlTemplate(i)).appendTo(portsDiv);
	
					updatePortCount(i);
				}
	            return false;
	    });
	    
	    $('#removePort').live('click', function() { 
	            if(checkMinPort(i, minReqPorts)) {
	                    $(this).parents('p').remove();
	                    i--;
						updatePortCount(i);
	            }
	            return false;
	    });
	
	});

});//]]> 
