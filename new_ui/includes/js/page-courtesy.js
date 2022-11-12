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


	// RENAME FILE FUNCTION AND MODAL
	$('.rename_file').click(function(e) {
		e.preventDefault();
		var rowCleanName = $(this).parents('tr').attr('data-row-name');
		var rowFileName = $(this).parents('tr').attr('data-row-file');
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-repeat"></i> ' + modal_RenameTitle,
			body: rowCleanName + modal_RenameBody,
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
	

	// Delete Courtesy Tone Function and Modal Display
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
			console.log(curFile);
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
		
	}
}
