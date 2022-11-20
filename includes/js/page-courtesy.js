var fileCount = 0;

$(function() {
	
	$('#courtesy-datatable-responsive').DataTable({
		responsive: true,
		bFilter: false,
        bSort: true,
        aaSorting: [],
        info: true,
        paging: true,
        pageLength: 25,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        searching: true,
		order: [0, 'asc'],
		columns: [
			null,
			{ orderable: false },
		],
		language: {
			emptyTable: "No courtesy tones in library. Please upload your first.",
			lengthMenu: "Show _MENU_ Sounds",
			search: "Search:",
			info: "Showing _START_ to _END_ of _TOTAL_ Sounds",
			infoEmpty: "",
			paginate: {
				previous: "Previous",
				next: "Next"
			},
		}
    });

	fullCourtesyObj = JSON.parse(courtesyToneAudio);
	$.each(fullCourtesyObj, function(index, curFile) {
		curFile['fileIndex'] = index;
		addCourtesyRow(curFile);
		fileCount++;
	});


	/* ------------------------------------------------------------------------- */
	// SELECT COURTESY TONE FUNCTION

	$('#courtesy-datatable-responsive').on('click', '.select_file', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-row-file');
		var rowID = $(this).parents('tr').attr('id');

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: {'settings': '{"courtesy":"'+fileName+'"}'},
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
				} else if (response.status == 'success') {
					$('tbody tr').removeClass('primary_selection');
					$('#' + rowID).addClass('primary_selection');
					rebuildActive();
				} else {

				}
			}
		});
	});

	

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

	$('#courtesy-datatable-responsive').on('click', '.rename_file', function(e) {
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
				data: {'action': 'rename', 'fileType': 'courtesy_tone', 'renameFile': fileName, 'newName': newClipFile},
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					if (response.status == 'success') {
						$('#'+rowID).attr('data-row-name',newClipName);
						$('#'+rowID).attr('data-row-file',newClipFile);
						$('#'+rowID+' span.audio_name').text(newClipName);

						$('#'+rowID+' .orp_player source').attr('src', response.newURL);
						$('#'+rowID+' audio').on('load'); // Reload the new filename into player

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

	$('#courtesy-datatable-responsive').on('click', '.delete_file', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-row-file');
		var fileLabel = $(this).parents('tr').attr('data-row-name');
		var rowID = $(this).parents('tr').attr('id');

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> ' + modal_DeleteCourtesyTitle,
			body: '<p>'+modal_DeleteCourtesyBody+':<br><strong>'+fileLabel+'</strong></p>',
			btnOK: modal_DeleteCourtesyBtnOK,
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {

			orpModalWaitBar(modal_DeleteCourtesyProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_file_system.php',
				data: {'action': 'delete', 'fileType': 'courtesy_tone', 'deleteFiles':[fileName]}, // future support for multifile delete
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
						$('#courtesy-datatable-responsive').DataTable().row('#' + rowID).remove().draw();
		
						//Display Message
						orpNotify('success', modal_DeleteCourtesyNotifyTitle, modal_DeleteCourtesyNotifyDesc);
					}
				}
			});

		});
	});

});





/* ------------------------------------------------------------------------- */
// UPLOAD CALLBACK FUNCTION

function uploadCallback (jsonResponse) {
	var response = JSON.parse(jsonResponse);
	if (response.status == 'success') {
		$.each(response.data, function(index, curFile) {
			addCourtesyRow({
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


function addCourtesyRow(input) {
	var $template = $('#courtesyRowTemplate').html();
	$template = $template.replace(/%%INDEX%%/g, input.fileIndex)
		.replace(/%%FILE_LABEL%%/g, input.fileLabel)
		.replace(/%%FILE_NAME%%/g, input.fileName)
		.replace(/%%FILE_URL%%/g, input.fileURL);

	t = $('#courtesy-datatable-responsive').DataTable();

	// Render row, highlight if a new/uploaded row
	if (input.addRow == true) {
		var row = t.row.add($($template)).select().draw();
	    setTimeout(function(){t.row(row).deselect();}, 10000);
	} else {
		var row = t.row.add($($template)).draw();
		if (currentCourtesyTone == input.fileName) {
			$('#clip' + input.fileIndex).addClass('primary_selection');
		}
		
	}
}
