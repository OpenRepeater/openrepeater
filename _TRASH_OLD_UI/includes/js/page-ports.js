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
	var html = '<div id="port' + n + 'wrap">'+
	'<form id="port' + n + 'form" class="portForm form-inline" action="functions/port_db_update.php" method="post">'+

	'<input type="hidden" name="portType[' + n + ']" value="GPIO">'+
	'<p id="port' + n + '" class="portRow additional" data-port-number="' + n + '">'+
	'<span>'+
	'<input type="text" name="portNum[' + n + ']" value="' + n + '" style="width:15px;display:none;"> '+
	'<input type="text" required="required" name="portLabel[' + n + ']" placeholder="Port Label" class="portLabel" value="Port ' + n + '"> '+
	'</span>'+
	'<span class="rx">'+
	'<input type="text" required="required" name="rxGPIO[' + n + ']" placeholder="rxGPIO" class="rxGPIO"> '+
	'<select name="rxAudioDev[' + n + ']" class="rxAudioDev">'+
	'	<option>---</option>'+ jsAudioInputOptions +
	'</select> '+
	'</span>'+
	'<span class="tx">'+
	'<input type="text" required="required" name="txGPIO[' + n + ']" placeholder="txGPIO" class="txGPIO"> '+
	'<select name="txAudioDev[' + n + ']" class="txAudioDev">'+
	'	<option>---</option>'+ jsAudioOutputOptions +
	'</select> '+
	'</span>'+

	'<!-- Button triggered modal -->'+
	'<button type="button" class="btn port_settings" data-toggle="modal" data-target="#portDetails' + n + '" title="Extra settings for this port"><i class="icon-cog"></i></button>'+
	'<a href="#" class="removePort">Remove</a>'+
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
	'	<select id="rxMode' + n + '" name="rxMode[' + n + ']" class="rxMode">'+
	'		<option value="cos" selected>COS</option>'+
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
	'  <select id="rxGPIO_active' + n + '" name="rxGPIO_active[' + n + ']" class="rxGPIO_active">'+
	'  	<option value="high" selected>Active High</option>'+
	'  	<option value="low">Active Low</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+
	'<div class="control-group">'+
	'<label class="control-label" for="txGPIO_active' + n + '">TX Active GPIO State</label>'+
	'<div class="controls">'+
	'  <select id="txGPIO_active' + n + '" name="txGPIO_active[' + n + ']" class="txGPIO_active">'+
	'  	<option value="high" selected>Active High</option>'+
	'  	<option value="low">Active Low</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+
	
	'<div class="control-group">'+
	'<label class="control-label" for="portEnabled' + n + '">Port Enabled/Disabled</label>'+
	'<div class="controls">'+
	'  <select id="portEnabled' + n + '" name="portEnabled[' + n + ']">'+
	'  	<option value="1">Enabled</option>'+
	'  	<option value="0">Disabled</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+
	
	'<div class="control-group">'+
	'<label class="control-label" for="linkGroup' + n + '">Link Group</label>'+
	'<div class="controls">'+
	'  <select id="linkGroup' + n + '" name="linkGroup[' + n + ']">'+
	'  	<option value="">None</option>'+
	'  	<option value="1">Group 1</option>'+
	'  	<option value="2">Group 2</option>'+
	'  	<option value="3">Group 3</option>'+
	'  	<option value="4">Group 4</option>'+
	'  </select>'+
	'</div>'+
	'</div>'+
	'<div style="clear: both;"></div>'+


	'</fieldset>'+
	'</div>'+
	'<div class="modal-footer">'+
	'<button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-remove"></i> Close</button>'+
	'</div>'+
	'</div><!-- /.modal-content -->'+
	'</div><!-- /.modal-dialog -->'+
	'</div>'+
	'<!-- /.modal -->'+

	'</form>'+
	'</div>';
	return html;
}


function updatePortCount() {
	var n = $( '.portRow').length;

	if(n==1) {
		$('#portCount').html( n + ' Port' ); // singular
	} else {
		$('#portCount').html( n + ' Ports' ); // plural
	}
	return n;
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

function getNextPortNum() {
	var p = 1;
	while (true) {
		if ( $( '#port'+p ).length === 0 ) {
			return p;
		}
		p++;	
	}
}


//Update Database
function updateDB(portFormID) {
	// update DB	
	var $form = $('#'+portFormID);
	var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
	$.ajax({
		url: $form.attr("action"),
		data: $form.serialize(),
		type: method,
		success: function() {
			$('.server_bar_wrap').show(); 

			//Display Message
			var msgText = "The port settings have been updated successfully!";
			$('#alertWrap').html('<div class="alert alert-success">'+msgText+'</div>');
			$('#alertWrap').slideDown(500);
			setTimeout(function() {
				$('#alertWrap').slideUp(500);
			}, 2000);
		}
	});
}

function loadBoardPreset() {
//	document.getElementById('loadBoardPreset').submit();

}


// Main Scripts
$(window).load(function(){
	$(function() {
		var inputPorts = $('#detectedRX').val();
		var outputPorts = $('#detectedTX').val();
		updatePortCount();
	

	
		$(".addPort").live("click", function(e) {
			e.preventDefault();
			if(checkMaxPort(updatePortCount(), maxSysPorts, inputPorts, outputPorts)) {
				var nextPort = getNextPortNum();
				$(htmlTemplate(nextPort)).appendTo('#portsWrap');
				updatePortCount();
			}
			$('#noPorts').slideUp(500);			
			return false;
		});

		$(".removePort").live("click", function(e) {
			e.preventDefault();
			if(checkMinPort(updatePortCount(), minReqPorts)) {
				var portNum = $(this).parents('.portRow').attr('data-port-number');
				$('#port'+portNum+'wrap').slideUp(500);
				$('#port'+portNum+'wrap').remove();
				updatePortCount();
				$.ajax({
					type: 'POST',
					url: '/functions/port_db_delete.php',
					data: { del_id: portNum },
					success: function(result) {
						if (result == 'true') {
							$('.server_bar_wrap').show(); 
							//Display Message
							var msgText = "The port has been successfully removed";
							$('#alertWrap').html('<div class="alert alert-success">'+msgText+'</div>');
							$('#alertWrap').slideDown(500);
							setTimeout(function() {
								$('#alertWrap').slideUp(500);
							}, 2000);
						}
					}
				});
			}
			return false;
		});


		$(".portForm").live("change", function(){
			var portFormID = $(this).attr('id');
			updateDB(portFormID);
		});
	
	});

});//]]> 
