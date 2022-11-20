var fileCount = 0;

$(function() {

	var settingsObj = JSON.parse(settingsJSON);

	Short_ID_Update(settingsObj.ID_Short_Mode);
	Long_ID_Update(settingsObj.ID_Long_Mode);


	$('#id_library').DataTable({
		responsive: true,
		bFilter: false,
        bSort: true,
        aaSorting: [],
        info: true,
        paging: true,
        pageLength: 10,
        lengthMenu: [10, 25],
        searching: false,
		order: [0, 'asc'],
		columns: [
			null,
			{ orderable: false },
		],
		language: {
			emptyTable: "No custom identification sounds in library. Please upload your first.",
			lengthMenu: "Show _MENU_ Sounds",
			info: "Showing _START_ to _END_ of _TOTAL_ Sounds",
			infoEmpty: "",
			paginate: {
				previous: "Previous",
				next: "Next"
			},
		}

    });

	fullIDObj = JSON.parse(identificationAudio);
	$.each(fullIDObj, function(index, curFile) {
		curFile['fileIndex'] = index;
		addIDRow(curFile);
		fileCount++;
	});

	// Set Custom IDs to defaults
	$('#ID_Short_CustomFile').val(settingsObj.ID_Short_CustomFile);
	$('#ID_Long_CustomFile').val(settingsObj.ID_Long_CustomFile);
	sortSelectOptions('#ID_Short_CustomFile');
	sortSelectOptions('#ID_Long_CustomFile');



	/* ------------------------------------------------------------------------- */
	// SHORT ID MODE CHANGE
	$('input[type=radio][name=ID_Short_Mode]').on('change', function() {
		Short_ID_Update($(this).val());
	});
	
	function Short_ID_Update(mode) {
		switch (mode) {
			case 'disabled':
				$('#ID_Short_Interval_Grp').hide();
				$('#ID_Short_Custom_Audio_Grp').hide();
				$('#ID_After_TX_Grp').hide();
				$('#ID_Short_Append_Morse_Grp').hide();
				break;
	
			case 'morse':
				$('#ID_Short_Interval_Grp').show();
				$('#ID_Short_Custom_Audio_Grp').hide();
				$('#ID_After_TX_Grp').show();
				$('#ID_Short_Append_Morse_Grp').hide();
				break;
	
			case 'voice':
				$('#ID_Short_Interval_Grp').show();
				$('#ID_Short_Custom_Audio_Grp').hide();
				$('#ID_After_TX_Grp').show();
				$('#ID_Short_Append_Morse_Grp').show();
				break;
	
			case 'custom':
				$('#ID_Short_Interval_Grp').show();
				$('#ID_Short_Custom_Audio_Grp').show();
				$('#ID_After_TX_Grp').show();
				$('#ID_Short_Append_Morse_Grp').show();
				break;
		}	
	}
	
	
	/* ------------------------------------------------------------------------- */
	// LONG ID MODE CHANGE
	$('input[type=radio][name=ID_Long_Mode]').on('change', function() {
	  Long_ID_Update($(this).val());
	});
	
	function Long_ID_Update(mode) {
		switch (mode) {
			case 'disabled':
				$('#ID_Long_Interval_Grp').hide();
				$('#ID_Long_Custom_Audio_Grp').hide();
				$('#ID_Long_Annc_Time_Grp').hide();
				$('#ID_Long_Append_Morse_Grp').hide();
				break;
	
			case 'morse':
				$('#ID_Long_Interval_Grp').show();
				$('#ID_Long_Custom_Audio_Grp').hide();
				$('#ID_Long_Annc_Time_Grp').show();
				$('#ID_Long_Append_Morse_Grp').hide();
				break;
	
			case 'voice':
				$('#ID_Long_Interval_Grp').show();
				$('#ID_Long_Custom_Audio_Grp').hide();
				$('#ID_Long_Annc_Time_Grp').show();
				$('#ID_Long_Append_Morse_Grp').show();
				break;
	
			case 'custom':
				$('#ID_Long_Interval_Grp').show();
				$('#ID_Long_Custom_Audio_Grp').show();
				$('#ID_Long_Annc_Time_Grp').show();
				$('#ID_Long_Append_Morse_Grp').show();
				break;
		}
		
	}



	/* ------------------------------------------------------------------------- */
	// RENAME FILE FUNCTION AND MODAL

	// FILTER INPUT: Only allow characters A-Z, a-z, 0-9, space and hyphen. ORP uses underscores in the background to replace spaces for actual file names.
	$('#orp_modal').on('keypress', '#newFileName', function(e) {
        var txt = String.fromCharCode(e.which);
        if(!txt.match(/[A-Za-z0-9 -]/)) { return false; }
	});

	// Disable OK button if new matches old
	$('#orp_modal').on('keyup', '#newFileName', function(e) {
        if ( $('#newFileName').val() == $('#newFileName').attr('data-row-label') || $('#newFileName').val() == '' )  {
			$('#orp_modal_ok').prop('disabled', true); // Disable OK Button	        
        } else {
			$('#orp_modal_ok').prop('disabled', false); // Enable OK Button	        
        }
	});

	$('#id_library').on('click', '.renameIdentification', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-row-file');
		var fileLabel = $(this).parents('tr').attr('data-row-name');
		var rowID = $(this).parents('tr').attr('id');

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-repeat"></i> ' + modal_RenameTitle,
			body:  modal_RenameBody + '<input type="text" id="newFileName" name="newFileName" class="form-control" data-row-file="' + fileName + '" data-row-label="' + fileLabel + '" value="' + fileLabel + '" placeholder="' + modal_RenamePlaceholder + '">',
			btnOK: modal_RenameBtnOK,
		};

		orpModalDisplay(modalDetails);
		$('#orp_modal_ok').prop('disabled', true); // Disable OK Button	        

		// Wait for modal and select input
		var waitForModal = setInterval(function() {
			$('#newFileName').focus().select();
			clearInterval(waitForModal);
		}, 500); 

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var newClipName = $("#newFileName").val()
			var newClipFile = newClipName.replace(/ /g, '_') + '.wav';

			orpModalWaitBar(modal_RenameProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_file_system.php',
				data: {'action': 'rename', 'fileType': 'identification', 'renameFile': fileName, 'newName': newClipFile},
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					if (response.status == 'success') {
						$('#'+rowID).attr('data-row-name',newClipName);
						$('#'+rowID).attr('data-row-file',newClipFile);
						$('#'+rowID+' span.audio_name').text(newClipName);

						$('#'+rowID+' .orp_player source').attr('src', response.newURL);
						$('#'+rowID+' audio').on('load'); // Reload the new filename into player
						$('#'+rowID+' a.identificationURL').attr('href', response.newURL);
						
						// Update name in dropdown select boxes
						$('#ID_Short_CustomFile option[value="'+fileName+'"]').val(newClipFile).text(newClipName);
						$('#ID_Long_CustomFile option[value="'+fileName+'"]').val(newClipFile).text(newClipName);

						sortSelectOptions('#ID_Short_CustomFile');
						sortSelectOptions('#ID_Long_CustomFile');

						//Display Message
						orpNotify('success', modal_RenameNotifyTitle, modal_RenameNotifyDesc);
					}

					$('#orp_modal').modal('hide');
		
				}
			});

		});

	});

	

	/* ------------------------------------------------------------------------- */
	// DELETE FILE FUNCTION AND MODAL
/*
	$('.deleteIdentification').click(function(e) {
		e.preventDefault();

		var curRowName = $(this).parents('tr').attr('data-row-name');
		var curRowFile = $(this).parents('tr').attr('data-row-file');
		var curRowID = $(this).parents('tr').prop('id');

		// Check to see if this file is in use and return a count
		var in_use_count = 0;
		if( $('input[name="ID_Short_Mode"]:checked').val() == 'custom' && $('#ID_Short_Custom_Audio').val() == curRowFile ) {
			in_use_count++;
		}
		if( $('input[name="ID_Long_Mode"]:checked').val() == 'custom' && $('#ID_Long_Custom_Audio').val() == curRowFile ) {
			in_use_count++;
		}

		// If file is not in use, then delete it. Otherwise, post error alert.
		if( in_use_count == 0 ) {
			var modalDetails = {
				modalSize: 'small',
				title: '<i class="fa fa-remove"></i> ' + modal_DeleteTitle,
				body: modal_DeleteBody + '<p><strong>' + curRowName + '</strong></p>',
				btnOK: modal_DeleteBtnOK,
				btnOKclass: modal_DeleteBtnOKclass,
			};
	
			orpModalDisplay(modalDetails);
	
			$('#orp_modal_ok').off('click'); // Remove other click events
			$('#orp_modal_ok').click(function() {
				
				$('#orp_modal').modal('hide');
	
	
				// Do some AJAX thing to remove the file. 
	
				$('#'+curRowID).remove(); // Remove Row
				
				$("#ID_Short_Custom_Audio option[value='"+curRowFile+"']").remove();			
				$("#ID_Long_Custom_Audio option[value='"+curRowFile+"']").remove();
	
			});


		} else {
			// Display in use error message.
			var modalDetails = {
				modalSize: 'small',
				title: '<i class="fa fa-exclamation-triangle"></i> ' + modal_DeleteErrorTitle,
				body: modal_DeleteErrorBody + '<p><strong>' + curRowName + '</strong></p>',
				btnOK: modal_DeleteErrorBtnOK,
			};
	
			orpModalDisplay(modalDetails);
	
			$('#orp_modal_ok').off('click'); // Remove other click events
			$('#orp_modal_ok').click(function() {
				$('#orp_modal').modal('hide');
			});
			
		}

	});
*/

// PULL CODE FROM ABOVE TO CHECK IF IDENTIFICAITON IS IN USE BEFORE REMOVING, ADD TO FUNCTION BELOW.


	// Delete Identification Function and Modal Display
	$('#id_library').on('click', '.deleteIdentification', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-row-file');
		var fileLabel = $(this).parents('tr').attr('data-row-name');
		var rowID = $(this).parents('tr').attr('id');

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> ' + modal_DeleteIdentTitle,
			body: '<p>'+modal_DeleteIdentBody+':<br><strong>'+fileLabel+'</strong></p>',
			btnOK: modal_DeleteIdentBtnOK,
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {

			orpModalWaitBar(modal_DeleteIdentProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_file_system.php',
				data: {'action': 'delete', 'fileType': 'identification', 'deleteFiles':[fileName]}, // future support for multifile delete
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					var deleteCount = 0;
					var errorCount = 0;
					$.each(response, function(curFile, status) {
						if (status == 'success') {
							deleteCount++;
						} else {
							errorCount++;
						}
					});

					$('#orp_modal').modal('hide');

					if (deleteCount > 0) {						
						$('#' + rowID).slideUp(500);
						$('#id_library').DataTable().row('#' + rowID).remove().draw();

						// Remove OLD NAME from dropdown select boxes
						$('#ID_Short_CustomFile option[value="'+fileName+'"]').remove();
						$('#ID_Long_CustomFile option[value="'+fileName+'"]').remove();

						//Display Message
						orpNotify('success', modal_DeleteIdentNotifyTitle, modal_DeleteIdentNotifyDesc);
					}
				}
			});

		});
	});



	/* ------------------------------------------------------------------------- */
	// Preview Morse Settings using external script
	$( "#morsePreview" ).click(function() {
		var callSign = $('#callSign').val();
		var morseSuffix = $('input[name=ID_Morse_Suffix]:checked').val();
		var morseCallSign = callSign + $.trim(morseSuffix);
		var morseWPM = $('#ID_Morse_WPM').val();
		var morsePitch = $('#ID_Morse_Pitch').val();
		
		XAudioJSWebAudioContextHandle.resume(); // Resume audio fix
		playM(morseCallSign, morseWPM, morsePitch);
	});



	/*
	**********************************************************************
	 UPDATE DATABASE SETTINGS VIA AJAX CALL
	**********************************************************************
	*/

	$('.idForm').change(function() {
		var formID = $(this).attr('id');
		sectionStatus(formID, 'x_panel', 'processing');

		// Get current settings, remove empty fields and create an object of results.
		var settingsFieldsObj = {};
		$.each($('#' + formID + " :input")
			.serializeArray(), 
			function(_, kv) {
				settingsFieldsObj[kv.name] = kv.value;
			}
		);

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: {'settings': JSON.stringify(settingsFieldsObj)},
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					sectionStatus(formID, 'x_panel', 'error');
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
				} else if (response.status == 'success') {
					sectionStatus(formID, 'x_panel', 'saved');
					rebuildActive();
				} else {
					sectionStatus(formID, 'x_panel', 'error');
				}
			}
		});

	});

})





/* ------------------------------------------------------------------------- */
// UPLOAD CALLBACK FUNCTION

function uploadCallback (jsonResponse) {
	var response = JSON.parse(jsonResponse);
	if (response.status == 'success') {
		$.each(response.data, function(index, curFile) {
			addIDRow({
				addRow: true,
				fileIndex: fileCount,
				fileLabel: curFile.fileLabel,
				fileName: curFile.fileName,
				fileURL: curFile.downloadURL,
			})
			fileCount++;
		});
	} else if (response.status == 'error') {
		// orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
		console.log('Upload Error');
	}
}


function addIDRow(input) {
	var fileLabel = input.fileName.replace(/\.[^/.]+$/, '').replace(new RegExp('_', 'g'), ' ');
	
	var $template = $('#idRowTemplate').html();
	$template = $template.replace(/%%INDEX%%/g, input.fileIndex)
		.replace(/%%FILE_LABEL%%/g, fileLabel)
		.replace(/%%FILE_NAME%%/g, input.fileName)
		.replace(/%%FILE_URL%%/g, input.fileURL);

	t = $('#id_library').DataTable();

	// Render row, highlight if a new/uploaded row
	if (input.addRow == true) {
		var row = t.row.add($($template)).select().draw();
	    setTimeout(function(){t.row(row).deselect();}, 10000);
	} else {
		var row = t.row.add($($template)).draw();
		
	}

	// Add to dropdown select boxes
	$('#ID_Short_CustomFile').append($('<option>', {
		value: input.fileName,
		text: fileLabel
	}));

	$('#ID_Long_CustomFile').append($('<option>', {
		value: input.fileName,
		text: fileLabel
	}));
}


function sortSelectOptions(selector) {
	var origOptions = $(selector + ' option');
    var selected = $(selector).val(); // cache selected value, before reordering

	var optionsArray = [];
	$.each(origOptions, function(index, curOption) {
		optionsArray.push({value: curOption.value, text: curOption.text});
	});	

	optionsArray.sort(function(a, b){
		let x = a.value.toLowerCase();
		let y = b.value.toLowerCase();
		if (x < y) {return -1;}
		if (x > y) {return 1;}
		return 0;
	});

	$(selector).empty();
	
	$.each(optionsArray, function(index, curOpt) {
		$(selector).append($('<option>', {
			value: curOpt.value,
			text: curOpt.text
		}));
	});

	$(selector).val(selected);
}