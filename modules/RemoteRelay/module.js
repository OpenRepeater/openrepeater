//module JS



//alert("Module JS Loaded");

// Variables
var maxSysRelays = 8;
var minReqRelays = 1;


// Sub Functions
function htmlTemplate(n) {
	var html = '<p class="relayRow additional">'+
	'<span>'+
	'<input type="text" name="relayNum[]" value="' + n +'" style="width:15px;display:none;"> '+
	'<input type="text" required="required" name="relayLabel[]" placeholder="Relay Label" class="relayLabel"> '+
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
	'<a href="#" id="removeRelay">Remove</a>'+
	'</p>';
	return html;
}


function updateRelayCount(n) {
	if(n==1) {
	    $('#relayCount').html( n + ' Relay' ); // singular
	} else {
	    $('#relayCount').html( n + ' Relays' ); // plural
	}
}

function checkMaxRelay(currentRelays, sysMax, inRelays, outRelays) {
	if (currentRelays >= inRelays || currentRelays >=outRelays) {
		alert("Sorry, but you cannot add anymore relays because you do not have the required audio inputs and outputs required to suprelay these relays. The system has detected that you have "+inRelays+" audio input channel(s) and "+outRelays+" audio output channel(s). Please add more suprelayed audio devices first to be able to add more relays.");
		return false;
	}
	if (currentRelays >= sysMax) {
		alert("Sorry, but you cannot add anymore relays because you already have the maximum number of relays the system will suprelay.");
		return false;
	}
	return true;
}

function checkMinRelay(currentRelays, minRelays) {
	if (currentRelays <= minRelays) {
		alert("Sorry, but you cannot remove any more relays because this system requires a minimum of "+minRelays+" relay(s).");
		return false;
	}
	return true;
}

//Update Database
function updateDB() {
	// update DB	
    var $form = $("#relaysUpdate");
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
	var msgText = "The relay settings have been updated successfully!";
	$('#alertWrap').html('<div class="alert alert-success">'+msgText+'</div>');
}

// Main Scripts
$(window).load(function(){
$(function() {
        var inputRelays = $('#detectedRX').val();
		var outputRelays = $('#detectedTX').val();

        var relaysDiv = $('#relaysWrap');
        var i = $('#relaysWrap p').size();

		updateRelayCount(i);

        

        $('#addRelay').live('click', function() {
                if(checkMaxRelay(i, maxSysRelays, inputRelays, outputRelays)) {
	                i++;
	
	                $(htmlTemplate(i)).appendTo(relaysDiv);
	
					updateRelayCount(i);
				}
                return false;
        });
        
        $('#removeRelay').live('click', function() { 
                if(checkMinRelay(i, minReqRelays)) {
                        $(this).parents('p').remove();
                        i--;
						updateRelayCount(i);
                }
                return false;
        });
});

});//]]> 
