$(function() {
	
	// UPLOAD FILE FUNCTION AND MODAL
	$('.upload_file').click(function(e) {
		e.preventDefault();

		// Define Variables
		var uploadType = $(this).attr('data-upload-type');
		switch(uploadType) {
			case 'courtesy_tone':
				var acceptedFilesTypes = '.wav,.mp3';
				var maxFileSizeMB =  10;
				break;
			case 'identification':
				var acceptedFilesTypes = '.wav,.mp3';
				var maxFileSizeMB =  10;
				break;
			case 'module':
				var acceptedFilesTypes = '.zip';
				var maxFileSizeMB =  100;
				break;
			case 'restore':
				var acceptedFilesTypes = '.orp';
				var maxFileSizeMB =  512;
				break;
		}


		// Spawn Upload Modal
		var modalDetails = {
			modalSize: 'large',
			title: '<i class="fa fa-upload"></i> ' + modal_UploadTitle,
			body: '<div id="orpDropzone" class="dropzone"></div>',
			btnOKshow: false,
		};

		orpModalDisplay(modalDetails);


		// Spawn Dropzone Region
		$('div#orpDropzone').dropzone({ 
			url: '/functions/ajax_file_system.php',
			autoProcessQueue: true,
			acceptedFiles: acceptedFilesTypes,
			maxFilesize: maxFileSizeMB,
			uploadMultiple: true,
			createImageThumbnails: false,
			dictDefaultMessage: '<span class="defaultMessage">'+modal_dzDefaultText+'</span><span class="customDesc">'+modal_dzCustomDesc+'</span>',
			params: {'action': 'upload', 'uploadType': uploadType},
			init: function () {
				this.on('processingmultiple', function (file) {
					orpModalWaitBar();
				});
				
				this.on('successmultiple', function (file, response) {
					addRow( JSON.parse(response) );
					resetModal();
				});
				
				this.on('errormultiple', function (file, error, xhr) {
					console.log('error: '+error);
					orpNotify('error', 'Error', error);
					resetModal();
				});
			}
		});

		// Reset Modal
		function resetModal() {
			// Re-enable buttons
			$('#orp_modal_cancel').prop('disabled', false);
			$('#orp_modal_ok').prop('disabled', false);
		
			$('#orp_modal').modal('hide');
		}

	});

})