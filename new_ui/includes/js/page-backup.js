var fileCount = 0;
var totalDirSize = 0;

$(function() {

	// Loop through JSON array of backups and build display
	fullBackupObj = JSON.parse(backupList);
	totalDirSize = fullBackupObj.totalDirSize;
	if ( totalDirSize == '0 B' ) {
		// No results so hide table and show message
		$('#backup-table-responsive').hide();
		$('#no_backups').fadeIn(500);
	} else {
		// Backup object contains results so display them. 
		delete fullBackupObj['totalDirSize'];
		$.each(fullBackupObj, function(index, curFile) {
			curFile['fileIndex'] = index;
			buildBackupRow(curFile);
			fileCount++;
		});

		$('#backup-table-responsive').DataTable({
			responsive: true,
			bFilter: false,
	        bSort: true,
	        aaSorting: [],
	        paging: false,
			"order": [1, 'desc'],
			"columns": [
				null,
				null,
				null,
				{ "orderable": false },
			],
			"language": {
				"info": fileCountLabel + ": _TOTAL_ | " + allBackupsSizeLabel + ': <span>' + formatFileSize(totalDirSize) + '</span>'
			}
	    });
	}


	function buildBackupRow(input) {
		var $template = $('#backupRowTemplate').html();
		$template = $template.replace(/%%INDEX%%/g, input.fileIndex)
			.replace(/%%FILENAME%%/g, input.fileName)
			.replace(/%%FULLDATE%%/g, formatDateTime(input.fileDate, 'longDateTime'))
			.replace(/%%DATE%%/g, formatDateTime(input.fileDate))
			.replace(/%%ISODATE%%/g, input.fileDate)
			.replace(/%%SIZE%%/g, formatFileSize(input.fileSize))
			.replace(/%%RAWSIZE%%/g, input.fileSize)
			.replace(/%%URL%%/g, input.downloadURL);

	    $('#backup-table-responsive tbody').append($template);		
	}


/*
$('.testBtn').on('click', function(e) {
	console.log('add');
	addRow([{
		'filename': 'testfile.orp',
		'datetime': '20210421191210',
		'size': '757884'
	}]);
});
*/



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
	
					// If no rows remain, then hide table and show message
					if ( $('.backupRow').length == 0 ) {
						$('#backup-table-responsive').hide();
						$('#no_backups').fadeIn(500);
					}
				}
			});

		});
	});



// 	$(".dropzone").dropzone({ url: "/file/post" });

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
			createString = { createBackup: 'New File' };
			console.log( JSON.stringify(createString) );

			orpModalWaitBar(modal_CreateBackupProgressTitle);

			setTimeout(function() {
				$('#orp_modal').modal('hide');

				//$('#backupRow' + fileIndex).slideUp(500);
				//$('#backupRow' + fileIndex).remove();

				//Display Message
				orpNotify('success', modal_CreateBackupNotifyTitle, modal_CreateBackupNotifyDesc);

				// Set visibility for first created row.
				if(!$('#backup-table-responsive').is(":visible")){
					$('#no_backups').hide();
					$('#backup-table-responsive').fadeIn(500);
				}

			}, 2000);
		});		
	});


/*
	// UPLOAD BACKUP FUNCTION AND MODAL
	$('.uploadBackup').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'large',
			title: '<i class="fa fa-upload"></i> ' + modal_UploadBackupTitle,
			body: modal_UploadBackupBody,
			btnOK: modal_UploadBackupBtnOKText,
		};

		orpModalDisplay(modalDetails);

		// FUNCTION GOES HERE
		
	});
*/


});



function addRow(response) {
	$.each(response, function(index, curFile) {
		fileCount++;
		newID = 'backupRow'+fileCount;
	
		var $template = $('#backupRowTemplate').html();
		$template = $template.replace(/%%INDEX%%/g, fileCount)
			.replace(/%%FILENAME%%/g, curFile.filename)
			.replace(/%%FULLDATE%%/g, formatDateTime(curFile.datetime, 'longDateTime'))
			.replace(/%%DATE%%/g, formatDateTime(curFile.datetime))
			.replace(/%%ISODATE%%/g, curFile.datetime)
			.replace(/%%SIZE%%/g, formatFileSize(curFile.size))
			.replace(/%%RAWSIZE%%/g, curFile.size)
			.replace(/%%URL%%/g, '#');

		t = $('#backup-table-responsive').DataTable();
		var row = t.row.add($($template)).select().draw();
	    setTimeout(function(){t.row(row).deselect();}, 5000);
	
		totalDirSize = totalDirSize + curFile.size;
	});

	$('.dataTables_info span').html(formatFileSize(totalDirSize));
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
function formatDateTime(input, type='auto') {
	var inputUTC = DateTime.fromFormat(input, "yyyy-MM-dd'T'HH:mm:ss z", { setZone: true });
	switch(type) {
		case 'longDateTime':
			return inputUTC.setLocale(phpLocal).setZone(phpTimezone).toLocaleString(DateTime.DATETIME_MED)
		default:
			return inputUTC.setLocale(phpLocal).setZone('UTC').toRelativeCalendar();
	}
};
