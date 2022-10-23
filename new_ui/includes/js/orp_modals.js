/*
var modalDetails = {
	modalSize: 'small',
	title: 'Test Title',
	body: '<h4>test</h4><p>Some text here...</p>',
	progressWait: true
	progressTitle: 'Backing Up',
};
*/


function orpModalDisplay(parameters) {
	orpModalReset();

	$('#orp_modal').removeClass('bs-example-modal-sm bs-example-modal-lg'); // Clear Previous
	$('#orp_modal').removeClass('modal-sm modal-lg'); // Clear Previous
	switch(parameters['modalSize']) {
		case 'small':
			$('#orp_modal').addClass('bs-example-modal-sm');
			$('#orp_modal .modal-dialog').addClass('modal-sm');
			break;
		case 'large':
			$('#orp_modal').addClass('bs-example-modal-lg');
			$('#orp_modal .modal-dialog').addClass('modal-lg');
			break;
	}

	$('#orp_modal .modal-title').html(parameters['title']);
	$('#orp_modal .modal-body').html(parameters['body']);

	$('#orp_modal').modal({backdrop: 'static', keyboard: false}) 

	// OPTIONAL: Hide primary button for user input
	if (parameters['btnOKshow'] == false) {
		$('#orp_modal_ok').hide();
	} else {
		$('#orp_modal_ok').show();
	}

	// OPTIONAL: Change primary button text
	if (parameters['btnOK']) {
		$('#orp_modal_ok').html(parameters['btnOK']);
	} else {
		$('#orp_modal_ok').html(modal_DefaultOK);
	}

	// OPTIONAL: Change primary button class
	if (parameters['btnOKclass']) {
		$('#orp_modal_ok').removeClass('btn-primary');
		$('#orp_modal_ok').addClass(parameters['btnOKclass']);
	} else {
		$('#orp_modal_ok').addClass('btn btn-primary');
	}

	// OPTIONAL: Change secondary/cancel button text
	if (parameters['btnCancel']) {
		$('#orp_modal_cancel').html(parameters['btnCancel']);
	} else {
		$('#orp_modal_cancel').html(modal_DefaultCanel);
	}

	// OPTIONAL: Add Wait/Progress Bar
	$('#orp_modal_ok').click(function() {
		if (parameters['progressWait'] == true) {
			if (parameters['progressTitle']) {
				orpModalWaitBar(parameters['progressTitle']);
			} else {
				orpModalWaitBar();
			}
		}
	});


// 	$('#myModal').modal({backdrop: 'static', keyboard: false}) 
}


function orpModalWaitBar(newTitle) {
	$('#orp_modal  .modal-header .close').hide();
	$('#orp_modal_cancel').prop('disabled', true);

	$('#orp_modal_ok').prop('disabled', true);
	if(newTitle) {
		$('#orp_modal .modal-title').html(newTitle);			
	}
	var newBody = '<center><h5 style="text-align: center">'+modal_PleaseWaitText+'</h5>';
	newBody += '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"></div></div>';
	$('#orp_modal .modal-body').html(newBody);
}


function orpModalReset() {
	$('#orp_modal  .modal-header .close').show();
	$('#orp_modal_cancel').prop('disabled', false);
	$('#orp_modal_cancel').off('click');
	$('#orp_modal_cancel').attr('data-dismiss','modal');
	$('#orp_modal_ok').prop('disabled', false);
	$('#orp_modal').modal({backdrop: 'static', keyboard: false}) 

	// Reset modal size
	$('#orp_modal .modal-dialog').removeClass('modal-sm modal-lg');

	// Reset primary button class
	$('#orp_modal_ok').removeClass('btn-default btn-success btn-info btn-warning btn-danger btn-dark');
	$('#orp_modal_ok').addClass('btn btn-primary');
}


// #########################################################

// ORP function invoking PNotify popup
function orpNotify(type, title, text) {
	var notifyProperties = {
		title: title,
		text: text,
		styling: 'bootstrap3',
	};

	switch(type) {
		case 'success':
			notifyProperties['type'] = 'success';
			notifyProperties['delay'] = 7000;
			notifyProperties['hide'] = true;
			break;
		case 'info':
			notifyProperties['type'] = 'info';
			notifyProperties['delay'] = 7000;
			notifyProperties['hide'] = true;
			break;
		case 'error':
			notifyProperties['type'] = 'error';
			notifyProperties['delay'] = 10000;
			notifyProperties['hide'] = false;
			break;
	}

	new PNotify(notifyProperties);
}


// #########################################################

function rebuildActive() {
	$('#orp_restart_btn').fadeIn(1000);
}


function rebuildDeactive() {
	$('#orp_restart_btn').fadeOut(1000);
}


// #########################################################

function sectionStatus(formID, parentClass, status) {
	switch (status) {
		case 'processing':
			$('#'+formID).closest('.'+parentClass).find('.sectionStatus i')
				.removeClass (function (index, className) {
				    return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
				})
				.addClass('fa-spinner fa-spin');
			break;

		case 'saved':
			$('#'+formID).closest('.'+parentClass).find('.sectionStatus i')
				.removeClass (function (index, className) {
				    return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
				})
				.addClass('fa-check-circle');

				setTimeout(function() {
					$('#'+formID).closest('.'+parentClass).find('.sectionStatus i')
						.removeClass (function (index, className) {
						    return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
						});
				}, 3000);
			break;

		case 'error':
			$('#'+formID).closest('.'+parentClass).find('.sectionStatus i')
				.removeClass (function (index, className) {
				    return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
				})
				.addClass('fa-warning');

				setTimeout(function() {
					$('#'+formID).closest('.'+parentClass).find('.sectionStatus i')
						.removeClass (function (index, className) {
						    return (className.match (/(^|\s)fa-\S+/g) || []).join(' ');
						});
				}, 20000);
			break;
	}
}