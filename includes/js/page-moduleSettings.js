$(function() {

	$(document).prop('title', newPageTitle);	



	$('#moduleSettingsUpdate').change(function() {
		console.log('change');
		$('#saveModuleSettingsBtn').addClass('modulePulse');
	});

});