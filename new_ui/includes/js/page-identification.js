$(function() {
	
    $('#id_library').DataTable( {
        "ordering": false,
        "searching":     false,
        "info":   true,
        "paging":   true,
    } );

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


function sortSelectOptions(id) {
	var options = $('#'+id+' option'); 
	options.detach().sort(function(a, b) { 
		var at = $(a).text(); 
		var bt = $(b).text(); 
		return (at > bt) ? 1 : ((at < bt) ? -1 : 0); 
	}); 
	options.appendTo('#'+id);
}



	/* ------------------------------------------------------------------------- */
	// RENAME FILE FUNCTION AND MODAL
	$('.rename_file').click(function(e) {
		e.preventDefault();
		var oldClipName = $(this).parents('tr').attr('data-row-name');
		var oldClipFile = $(this).parents('tr').attr('data-row-file');
		var curRowID = $(this).parents('tr').prop('id');
		
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-repeat"></i> ' + modal_RenameTitle,
			body:  modal_RenameBody + '<input type="text" id="newFileName" name="newFileName" class="form-control" value="' + oldClipName + '" placeholder="New File Name">',
			btnOK: modal_RenameBtnOK,
		};

		orpModalDisplay(modalDetails);
		
		// Wait for modal and select input
		var waitForModal = setInterval(function() {
			$('#newFileName').focus().select();
			clearInterval(waitForModal);
		}, 500); 


		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {
			var newClipName = $("#newFileName").val()
			var newClipFile = newClipName.replace(/ /g, '_') + '.wav';
	
	
			console.log( newClipName );

			console.log(curRowID);

			$('#'+curRowID).attr('data-row-name',newClipName);
			$('#'+curRowID).attr('data-row-file',newClipFile);
			$('#'+curRowID+' span.audio_name').text(newClipName);

$('#'+curRowID+' source').attr('src', 'DUMMY');
$('#'+curRowID+' audio').load(); // Reload the new filename into player


			$('#ID_Short_Custom_Audio option[value="'+oldClipFile+'"]').val(newClipFile).text(newClipName);
			sortSelectOptions('ID_Short_Custom_Audio');

			$('#ID_Long_Custom_Audio option[value="'+oldClipFile+'"]').val(newClipFile).text(newClipName);
			sortSelectOptions('ID_Long_Custom_Audio');



			$('#orp_modal').modal('hide');

/*
			switch(addPortType) {
				case 'local':
					$("#accordion").append(portLocalTemplate);
					break;
			}
*/
		});
	});
	

	/* ------------------------------------------------------------------------- */
	// DELETE FILE FUNCTION AND MODAL
	$('.delete_file').click(function(e) {
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



})