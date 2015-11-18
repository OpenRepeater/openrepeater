// Variables
var maxSysPorts = 8;
var minReqPorts = 1;


// Sub Functions
function htmlTemplate(n) {
	var html = '<p class="portRow additional">'+
	'<span>'+
	'<input type="text" name="portNum[]" value="' + n +'" style="width:15px;display:none;"> '+
	'<input type="text" required="required" name="portLabel[]" placeholder="Port Label" class="portLabel"> '+
	'</span>'+
	'<span class="rx">'+
	'<select name="rxMode[]" class="rxMode">'+
	'	<option value="vox" >VOX</option>'+
	'	<option value="gpio" selected>COS</option>'+
	'</select> '+
	'<input type="text" required="required" name="rxGPIO[]" placeholder="rxGPIO" class="rxGPIO"> '+
	'<select name="rxAudioDev[]" class="rxAudioDev">'+
	'	<option>---</option>'+
	'	<option value="alsa:plughw:1|0">INPUT: USB PnP Sound Device (Left)</option>'+
	'	<option value="alsa:plughw:1|1">INPUT: USB PnP Sound Device (Right)</option>'+
	'</select> '+
	'</span>'+
	'<span class="tx">'+
	'<input type="text" required="required" name="txGPIO[]" placeholder="txGPIO" class="txGPIO"> '+
	'<select name="txAudioDev[]" class="txAudioDev">'+
	'	<option>---</option>'+
	'	<option value="alsa:plughw:1|0">OUTPUT: USB PnP Sound Device (Left)</option>'+
	'	<option value="alsa:plughw:1|1">OUTPUT: USB PnP Sound Device (Right)</option>'+
	'</select> '+
	'</span>'+
	'<a href="#" id="removePort">Remove</a>'+
	'</p>';
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
