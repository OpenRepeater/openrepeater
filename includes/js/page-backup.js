function deleteFile (fileName) {
	$('#deleteFile .modal-body span').text(fileName);
	$('#deleteSelectedFile').val(fileName);
}

function restoreFile (fileName) {
	$('#validate_restore').val(fileName);
	ajaxValidateRestore ();
}


// ----------------------------------------------------------
// UPDATE RESTORE DIALOG VIA AJAX

function ajaxValidateRestore () {
    //submit changes to db
    var $form = $("#validateRestoreFile");
    var method = $form.attr("method") ? $form.attr("method").toUpperCase() : "GET";
    $.ajax({
        url: $form.attr("action"),
        data: $form.serialize(),
        dataType: "json",
        type: method,
        success: function(data) {
            if(data.status == 'ok') {
	            if( data.versionMatch == true ) {
					// Validated Package and ORP versions match
					$('#restoreFile .modal-title').text('Backup Validation Successful');

					$('#restoreFile .modal-body').html('<p>The backup has been validated and matches the current version of OpenRepeater. You can proceed with the restore process. This will overwrite all your settings with those in the backup.</p>');

		        } else {
					// Validated Package, BUT version mismatch
					$('#restoreFile .modal-title').text('Version Mismatch');

					$('#restoreFile .modal-body').html('<p>The version of OpenRepeater used to make this backup does not match the current version of OpenRepeater that you are trying to restore to. You may continue with the restore, but unexpected results may occur. This could be as simple as some missing data used by newer functionality added between versions. You may wish to make a backup of your current configuration before proceeding...just to be safe.</p>');
			        
		        }

				// Display version details for either of the above
				$('#restoreFile .modal-body').append('<h4>Backup Details:</h4>');
				$('#restoreFile .modal-body').append('<p>');
				$('#restoreFile .modal-body').append('Version: <strong>' + data.backup_orp_verion + '</strong><br>');
				$('#restoreFile .modal-body').append('Date: <strong>' + data.backup_date + '</strong><br>');
				$('#restoreFile .modal-body').append('Callsign: <strong>' + data.backup_callsign + '</strong>');
				$('#restoreFile .modal-body').append('</p>');

				// Enable Submit/Restore button
				$('#restoreButton').prop('disabled', false);

            } else {
				// Failed to Validate as ORP Package
				$('#restoreFile .modal-title').text('Validation Failure');

				$('#restoreFile .modal-body').html('<p>OpenRepeater was unable to validate this file so restoring from this backup is not possible. This could be due to a corruption of the data, or the original backup was not successfully created.</p>');

            } 

        }
    });
}

// Restore Button, please wait while submitting
$( "#restoreButton" ).click(function() {
	$('#restoreFile .modal-title').text('Restore in Progress');
	
	$('#restoreFile .modal-body').html('<center><h4 style="text-align: center">Please Wait</h4><img src="theme/img/ajax-loaders/ajax-loader-7.gif" align="middle"></center>');
});

// ----------------------------------------------------------
