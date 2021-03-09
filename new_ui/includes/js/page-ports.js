$(function() {

	// ADD PORT FUNCTION AND MODAL
	$('.addPort').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-plus"></i> ' + modal_AddPortTitle,
			body: modal_AddPortBody,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var addPortType = $('#addPortType').val();

			$('#orp_modal').modal('hide');

			switch(addPortType) {
				case 'local':
					$("#accordion").append(portLocalTemplate);
					break;
			}
		});

	});
	

	// LOAD BOARD PRESET FUNCTION AND MODAL
	$('.loadBoard').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-download"></i> Load Preset',
			body: '<p>What type of port do you wish to add?</p><select id="loadBoardPreset" name="loadBoardPreset" class="form-control"><option value="ICS_2X_ID_Num" selected>ICS 2X</option></select>',
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var loadBoardPreset = $('#loadBoardPreset').val();

			console.log(loadBoardPreset);
			$('#orp_modal').modal('hide');
		});
	});


	// DELETE PORT FUNCTION AND MODAL
	$('.deletePort').click(function(e) {
		e.preventDefault();
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var portDesc = $('#portNum' + portNum + ' .panel-title').text();

		console.log(portDesc);

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> Delete Port',
			body: '<p>Are you sure you want to delete<br><strong>'+portDesc+'</strong>?</p>',
			btnOK: 'Delete Forever',
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			console.log('submit');
			orpModalWaitBar('Deleting Port');

			setTimeout(function() {
				$('#orp_modal').modal('hide');
				$('#portNum' + portNum).slideUp(1000);
			}, 2000);
		});

/*
		var portLabel = $(this).val().trim();
		if ( portLabel == '' ) {
			$('#portNum' + portNum + ' .panel-title span').html('(no label set yet)');
		} else {
			$('#portNum' + portNum + ' .panel-title span').html(portLabel);
		}
*/

	});






	$('.portForm').change(function() {
		var formID = $(this).attr('id');
		var portNum = $(this).attr('data-port-form');;
		
		// Remove empty form entries and serialize results
		var formArray = $( '#' + formID + " :input")
		    .filter(function(index, element) {
		        return $(element).val() != '';
		    })
		    .serializeArray();		
		
		// Set portEnabled to 0 if not enabled
		if (typeof formArray.find(item => item.name === 'portEnabled') == 'undefined') {
			formArray.push({name: 'portEnabled', value: '0'});
		}

		
// 		formArray.find(item => item.name === 'portEnabled').value = "something else";

// 		console.log('form changed: '+portNum);
		console.log(formArray);


		$('#orp_restart_btn').show();

	});



	
	var x = 1; //initlal text box count
	$(".add_field_button").click(function(e) {
		e.preventDefault();
		var portLabel = $(this).val().trim();
		var wrapper = $(this).parent('div').attr('id');
console.log(wrapper);
		var curPort = $('#'+wrapper).attr('data-port-num');
		var ceilingCount = $('#'+wrapper).attr('data-ceiling-count');
		var realCount = $('#'+wrapper).attr('data-real-count');
		var sectionType = $('#'+wrapper).attr('data-section-type');
		if(realCount < max_fields) {
			$('#'+wrapper+'DELETE').remove(); // Remove delete field if it exists
			ceilingCount++; realCount++;
			$('#'+wrapper).attr('data-ceiling-count',ceilingCount);
			$('#'+wrapper).attr('data-real-count', realCount);

			var newRow = baseRow.replace(/%%PORT%%/g, curPort);
			var newRow = newRow.replace(/%%ROW%%/g, ceilingCount);

			switch(sectionType) {
				case 'local':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_LOGIC');
					var newRow = newRow.replace(/%%OPTIONS%%/g, logicOptions);
					break;
				case 'rx':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_RX');
					var newRow = newRow.replace(/%%OPTIONS%%/g, rxOptions);
					break;
				case 'tx':
					var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_TX');
					var newRow = newRow.replace(/%%OPTIONS%%/g, txOptions);
					break;
			} 

			$('#'+wrapper + ' .innerWrap').append(newRow); //add row
		}
	});
	
	$(".input_fields_wrap").on("click",".remove_field", function(e){ //user click on remove text
		var wrapper = $(this).closest('.input_fields_wrap').attr('id');
		var curPort = $('#'+wrapper).attr('data-port-num');
		var realCount = $('#'+wrapper).attr('data-real-count');
		var sectionType = $('#'+wrapper).attr('data-section-type');

		e.preventDefault();
		realCount--;
		$('#'+wrapper).attr('data-real-count', realCount);
		$(this).parent('div').remove();
		if (realCount == 0) {
			switch(sectionType) {
				case 'local':
					var deleteFieldName = 'SVXLINK_ADVANCED_LOGIC['+curPort+'][delete]'; break;
				case 'rx':
					var deleteFieldName = 'SVXLINK_ADVANCED_RX['+curPort+'][delete]'; break;
				case 'tx':
					var deleteFieldName = 'SVXLINK_ADVANCED_TX['+curPort+'][delete]'; break;
					break;
			} 

			var deleteField = '<input type="hidden" id="'+wrapper+'DELETE" name="'+deleteFieldName+'" value="DELETE">';
			$('#'+wrapper).append(deleteField); //add row
		}
	})










	// 	Port Label Change
	$('.portLabel').keyup(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var portLabel = $(this).val().trim();
		if ( portLabel == '' ) {
			$('#portNum' + portNum + ' .panel-title span').html('(no label set yet)');
		} else {
			$('#portNum' + portNum + ' .panel-title span').html(portLabel);
		}
	});

	// 	Port Type Change
	$('.portType input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		switch ($(this).val()) {
		case 'GPIO':
			$('#portNum' + portNum + ' .tabGPIO').show();
			$('#portNum' + portNum + ' .tabHidraw').hide();
			$('#portNum' + portNum + ' .tabSerial').hide();
			break;
		case 'HiDraw':
			$('#portNum' + portNum + ' .tabGPIO').hide();
			$('#portNum' + portNum + ' .tabHidraw').show();
			$('#portNum' + portNum + ' .tabSerial').hide();
			break;
		case 'Serial':
			$('#portNum' + portNum + ' .tabGPIO').hide();
			$('#portNum' + portNum + ' .tabHidraw').hide();
			$('#portNum' + portNum + ' .tabSerial').show();
			break;
		}
	});

	// 	Port Type Change
	$('.portDuplex input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		switch ($(this).val()) {
			case 'half':
				$('#portNum' + portNum + ' .portLabelDuplexHalf').show();
				$('#portNum' + portNum + ' .portLabelDuplexFull').hide();
				break;
			case 'full':
				$('#portNum' + portNum + ' .portLabelDuplexHalf').hide();
				$('#portNum' + portNum + ' .portLabelDuplexFull').show();
				break;
		}
	});

	// 	Link Group Change
	$('.linkGroup input[type=radio]').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
		var linkGroup = $(this).val();
		$('#portNum' + portNum + ' .portLabelLinkGrp span').html(linkGroup);
		$('#portNum' + portNum + ' .portLabelLinkGrp').removeClass('bg-green bg-orange bg-purple bg-blue-sky');
		switch ($(this).val()) {
			case '':
				$('#portNum' + portNum + ' .portLabelLinkGrp').hide();
				break;
			case '1':
				$('#portNum' + portNum + ' .portLabelLinkGrp').addClass('bg-green');
				$('#portNum' + portNum + ' .portLabelLinkGrp').show();
				break;
			case '2':
				$('#portNum' + portNum + ' .portLabelLinkGrp').addClass('bg-purple');
				$('#portNum' + portNum + ' .portLabelLinkGrp').show();
				break;
			case '3':
				$('#portNum' + portNum + ' .portLabelLinkGrp').addClass('bg-blue-sky');
				$('#portNum' + portNum + ' .portLabelLinkGrp').show();
				break;
			case '4':
				$('#portNum' + portNum + ' .portLabelLinkGrp').addClass('bg-orange');
				$('#portNum' + portNum + ' .portLabelLinkGrp').show();
				break;
		}
	});

	// 	Port Enable/Disable
	$('.portEnabled').change(function() {
		var portNum = $(this).parents('.portSection').attr('data-port-number');
        if(this.checked) {
			$('#portNum'+portNum+' a.deletePort').hide();
			$('#portNum'+portNum).removeClass('portDisabled');
        } else {
			$('#portNum'+portNum+' a.deletePort').show();
			$('#portNum'+portNum).addClass('portDisabled');
        }
	});

})