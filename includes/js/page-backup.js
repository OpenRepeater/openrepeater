var fileCount = 0;
var totalDirSize = 0;

$(function() {

	fullBackupObj = JSON.parse(backupList);
	totalDirSize = fullBackupObj.totalDirSize;

	$('#backup-table-responsive').DataTable({
		responsive: true,
		bFilter: true,
        bSort: true,
        aaSorting: [],
        info: true,
        paging: false,
        searching: false,
		order: [1, 'desc'],
		columns: [
			null,
			null,
			null,
			{ orderable: false },
		],
		language: {
			emptyTable: "There are no snapshots made yet. Click the Create Backup button above to create one.",
			lengthMenu: "Show _MENU_ Snapshots",
			info: fileCountLabel + ": _TOTAL_ | " + allBackupsSizeLabel + ': <span>' + formatFileSize(totalDirSize) + '</span>',
			infoEmpty: "",
		}
    });

	delete fullBackupObj['totalDirSize'];
	$.each(fullBackupObj, function(index, curFile) {
		curFile['fileIndex'] = index;
		addRow(curFile);
		fileCount++;
	});



	/* ------------------------------------------------------------------------- */
	// Delete Backup Function and Modal Display
	$('#backup-table-responsive').on('click', '.deleteBackup', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-backup-file');
		var fileIndex = $(this).parents('tr').attr('data-backup-number');
		var fileSize = $(this).parents('tr').attr('data-file-size');

		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-trash"></i> ' + modal_DeleteBackupTitle,
			body: '<p>'+modal_DeleteBackupBody+':<br><strong>'+fileName+'</strong></p>',
			btnOK: modal_DeleteBackupBtnOK,
			btnOKclass: 'btn-danger',
			progressWait: false,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {

			orpModalWaitBar(modal_DeleteBackupProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_file_system.php',
				data: {'action': 'delete', 'fileType': 'backup', 'deleteFiles':[fileName]}, // future support for multifile delete
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
						totalDirSize = totalDirSize - fileSize;
						$('#backupRow' + fileIndex).slideUp(500);
						$('#backup-table-responsive').DataTable().row('#backupRow' + fileIndex).remove().draw();
						$('.dataTables_info span').html(formatFileSize(totalDirSize));
		
						//Display Message
						orpNotify('success', modal_DeleteBackupNotifyTitle, modal_DeleteBackupNotifyDesc);
					}
				}
			});

		});
	});


	/* ------------------------------------------------------------------------- */
	// CREATE BACKUP FUNCTION AND MODAL
	$('.createBackup').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'large',
			title: '<i class="fa fa-database"></i> ' + modal_CreateBackupTitle,
			body: modal_CreateBackupBody,
			btnOK: modal_CreateBackupBtnOKText,
		};

		orpModalDisplay(modalDetails);

		$('#orp_modal_ok').off('click'); // Remove other click events
		$('#orp_modal_ok').click(function() {

			orpModalWaitBar(modal_CreateBackupProgressTitle);

			$.ajax({
				type: 'POST',
				url: '/functions/ajax_db_update.php',
				data: {'createBackup': ''},
				success: function(jsonResponse){
					var response = JSON.parse(jsonResponse);
					if (response.login == 'timeout') {
						orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
					} else if (response.status == 'success') {
						addRow({
							addRow: true,
							fileIndex: fileCount,
							fileLabel: response.fileLabel,
							fileName: response.fileName,
							downloadURL: response.downloadURL,
							fileDate: response.fileDate,
							fileSize: response.fileSize,
						});
						fileCount++;
						$('#orp_modal').modal('hide');
					} else {

					}
				}
			});
		});		
	});


	/* ------------------------------------------------------------------------- */
	// RESTORE BACKUP FUNCTION AND MODAL

	$('#backup-table-responsive').on('click', '.restoreBackup', function(e) {
		e.preventDefault();
		var fileName = $(this).parents('tr').attr('data-backup-file');
		var rowID = $(this).parents('tr').attr('id');

		orpModalDisplay({modalSize: 'medium'});
		orpModalWaitBar('<i class="fa fa-database"></i> ' + modal_RestoreProgressTitle);

		$.ajax({
			type: 'POST',
			url: '/functions/ajax_db_update.php',
			data: { 'restoreValidation': fileName },
			success: function(jsonResponse){
				var response = JSON.parse(jsonResponse);
				if (response.login == 'timeout') {
					orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);

				} else if (response.status == 'ok') {
					var backupDetails = '<h4>' + modal_RestoreDetailsHeading + '</h4>';
					    backupDetails += '<p>' + modal_RestoreDetailsVersion + ': <strong>' + response.backup_orp_verion + '</strong><br>';
					    backupDetails += modal_RestoreDetailsDate + ': <strong>' + formatDateTime(response.backup_date, 'longDateTime') + '</strong><br>';
					    backupDetails += modal_RestoreDetailsCallsign + ': <strong>' + response.backup_callsign + '</strong></p>';
					if (response.versionMatch == true) {
						// Backup Validation Successful
						var modalDetails = {
							modalSize: 'medium',
							title: '<i class="fa fa-database"></i> ' + modal_RestoreValidationTitle,
							body: modal_RestoreValidationBody + backupDetails,
							btnOK: modal_RestoreBtnOKText,
						};
					} else {
						// Version Mismatch
						var modalDetails = {
							modalSize: 'medium',
							title: '<i class="fa fa-database"></i> ' + modal_RestoreValidationMismatchTitle,
							body: modal_RestoreValidationMismatchBody + backupDetails,
							btnOK: modal_RestoreBtnOKText,
						};						
					}
					orpModalDisplay(modalDetails);
					$('#orp_modal_ok').off('click'); // Remove other click events
					$('#orp_modal_ok').click(function() {
						// Initiate Actual Restore...no turning back.
						orpModalWaitBar('<i class="fa fa-database"></i> ' + modal_RestoreProgressTitle);
						$.ajax({
							type: 'POST',
							url: '/functions/ajax_db_update.php',
							data: {'restoreBackup': ''},
							success: function(jsonResponse){
								var response = JSON.parse(jsonResponse);
								if (response.status == 'restore_success') {
									$('#orp_modal').modal('hide');
									orpNotify('success', modal_RestoreNotifyTitle, modal_RestoreNotifyDesc);
									rebuildActive();
								} else {
			
								}
							}
						});
					});		

				} else if (response.status == 'error_noFile' || response.status == 'error_incomplete') {
					// Validation Failure
					var modalDetails = {
						modalSize: 'medium',
						title: '<i class="fa fa-database"></i> ' + modal_RestoreValidationFailedTitle,
						body: modal_RestoreValidationFailedBody,
					};
					orpModalDisplay(modalDetails);
					$('#orp_modal_ok').off('click'); // Remove other click events
					$('#orp_modal_ok').click(function() {
						$('#orp_modal').modal('hide');
					});		

				} else {

				}
			}
		});
	});
});



/* ------------------------------------------------------------------------- */
// UPLOAD CALLBACK FUNCTION

function uploadCallback (jsonResponse) {
	var response = JSON.parse(jsonResponse);
	if (response.status == 'success') {
		$.each(response.data, function(index, curFile) {
			addRow({
				addRow: true,
				fileIndex: fileCount,
				fileLabel: curFile.fileLabel,
				fileName: curFile.fileName,
				downloadURL: curFile.downloadURL,
				fileDate: curFile.fileDate,
				fileSize: curFile.fileSize,
			});
			fileCount++;
		});
	} else if (response.status == 'error') {
		// orpNotify('error',notify_LoggedOutTitle , notify_LoggedOutText);
		console.log('Upload Error');
	}
}

function addRow(input) {
	var $template = $('#backupRowTemplate').html();
	$template = $template.replace(/%%INDEX%%/g, input.fileIndex)
		.replace(/%%FILENAME%%/g, input.fileName)
		.replace(/%%FULLDATE%%/g, formatDateTime(input.fileDate, 'longDateTime'))
		.replace(/%%DATE%%/g, formatDateTime(input.fileDate, 'relativeDateTime'))
		.replace(/%%ISODATE%%/g, formatDateTime(input.fileDate, 'isoDateTime'))
		.replace(/%%SIZE%%/g, formatFileSize(input.fileSize))
		.replace(/%%RAWSIZE%%/g, input.fileSize)
		.replace(/%%URL%%/g, input.downloadURL);

	t = $('#backup-table-responsive').DataTable();

	// Render row, highlight if a new/uploaded row
	if (input.addRow == true) {
		var row = t.row.add($($template)).select().draw();
	    setTimeout(function(){t.row(row).deselect();}, 10000);
		totalDirSize = totalDirSize + input.fileSize;
		$('.dataTables_info span').html(formatFileSize(totalDirSize));
	} else {
		var row = t.row.add($($template)).draw();		
	}
}



// Format File Size
function formatFileSize(size) {
	if ( parseInt(size) != 0 ) {
		var i = Math.floor( Math.log(size) / Math.log(1024) );
		return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB', 'PB'][i];			
	} else {
		return '0 B';
	}
};


// Format Date/Time function using Luxon library
// Input time should be in UTC, server sets time zone and local
function formatDateTime(input, type='none') {
	var inputUTC = DateTime.fromFormat(input, "yyyy-MM-dd'T'HH:mm:ss z", { setZone: true });
	switch(type) {
		case 'longDateTime':
			return inputUTC.setLocale(phpLocal).setZone(phpTimezone).toLocaleString(DateTime.DATETIME_MED);
			break;
		case 'isoDateTime':
			return inputUTC.toUnixInteger();
			break;
		case 'relativeDateTime':
			return inputUTC.setLocale(phpLocal).setZone(phpTimezone).toRelativeCalendar();
			break;
		default:
			return input;
	}
};
