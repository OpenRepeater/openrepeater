$(function() {
	
	$('#courtesy-table-responsive').DataTable({
		responsive: true,
		bFilter: true,
        bSort: true,
        aaSorting: [],
        paging: true,
		"columns": [
			null,
			{ "orderable": false },
		]
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
	

	// DELETE FILE FUNCTION AND MODAL
	$('.delete_file').click(function(e) {
		e.preventDefault();
		var modalDetails = {
			modalSize: 'small',
			title: '<i class="fa fa-remove"></i> ' + modal_DeleteTitle,
			body: modal_DeleteBody,
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

})