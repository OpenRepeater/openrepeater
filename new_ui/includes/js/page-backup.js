$(function() {

	$('#backup-table-responsive').DataTable({
		responsive: true,
		bFilter: false,
        bSort: true,
        aaSorting: [],
        paging: false,
		"columns": [
			null,
			null,
			null,
			{ "orderable": false },
		]
    });


$(".dropzone").dropzone({ url: "/file/post" });

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

		// FUNCTION GOES HERE
		
	});


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

})