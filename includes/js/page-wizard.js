wizardSettingsObj = JSON.parse(wizardSettingsJSON);

console.log(wizardSettingsObj);



$(function() {

	// ###################################################
	// STEP 1 - WELCOME
	
	$('#termsAgree').prop( 'disabled', 'disabled' );
	resetSwitchery('#termsAgree', true);
	
	$('.buttonPrevious').hide();
	$('.buttonNext').addClass('buttonDisabled');
	$('.buttonFinish').hide();

	// Enable agreement toggle when terms are scrolled.
	$('#scrollTerms').on('scroll', function() {
	    if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
			$('#termsAgree').prop( 'disabled', false );
			$('#termsAgree').removeAttr('disabled');
			$('#termsAgree').prop( 'checked', true );
			resetSwitchery('#termsAgree', false);
	    }
	});

	// Enable next button on agreement
	$('#termsAgree').on('change', function() {
        if(this.checked) {
			wizardSettingsObj.termsAgree = 'yes';
			$('.buttonNext').removeClass('buttonDisabled');

        } else {
			wizardSettingsObj.termsAgree = 'no';
			$('.buttonNext').addClass('buttonDisabled');
        }

		console.log( JSON.stringify(wizardSettingsObj) );

	});

	$('.buttonNext').click(function() {
		$('.buttonPrevious').show();
		$('.buttonPrevious').show();
		$('.buttonNext').addClass('buttonDisabled');
	});


	// ###################################################
	// STEP 2



	$('#wizard').on('keyup', '#callSign', function(e) {
		e.preventDefault();
		var callSign = $('#callSign').val().toUpperCase();
		if (callSign.length > 2) {
			wizardSettingsObj.settings.callSign = callSign;
			$('.buttonNext').removeClass('buttonDisabled');			
		} else {
			$('.buttonNext').addClass('buttonDisabled');
		}

		console.log( JSON.stringify(wizardSettingsObj) );
	});



	// Set Wizard Type (Standard or Restore)
	switch(wizardSettingsObj.wizardType) {
		case 'standard':
			$('#wizardTypeStandard').parents('label').addClass('active');
			$('#wizardTypeStandard').attr('checked',true);
			break;

		case 'restore':
			$('#wizardTypeRestore').parents('label').addClass('active');
			$('#wizardTypeRestore').attr('checked',true);
			break;
	}




	// ###################################################
	// STEP 3


	// Set Configuration Method
	switch(wizardSettingsObj.configMethod) {
		case 'preset':
			$('#configMethodPreset').parents('label').addClass('active');
			$('#configMethodPreset').attr('checked',true);
			$('#boardPresetWrap').show();
			$('#portSettingsWrap').hide();
			break;

		case 'manual':
			$('#configMethodManual').parents('label').addClass('active');
			$('#configMethodManual').attr('checked',true);
			$('#boardPresetWrap').hide();
			$('#portSettingsWrap').show();
			break;
	}

	// Configuration Method Change
	$('.configMethod input[type=radio]').change(function() {
		switch ($(this).val()) {
			case 'preset':
				$('#boardPresetWrap').slideDown(500);;
				$('#portSettingsWrap').slideUp(500);;
				break;
			case 'manual':
				$('#boardPresetWrap').slideUp(500);;
				$('#portSettingsWrap').slideDown(500);;
				break;
		}
		wizardSettingsObj.configMethod = $(this).val();

		console.log( JSON.stringify(wizardSettingsObj) );
	});


	$('#gpioWrap').hide();
	$('#hidrawWrap').hide();
	$('#serialWrap').hide();
	
	$('#portLabel').val(wizardSettingsObj.ports.port1.portLabel);


	// Set Port Type
	switch(wizardSettingsObj.ports.port1.portType) {
		case 'GPIO':
			$('#portTypeGPIO').parents('label').addClass('active');
			$('#portTypeGPIO').attr('checked',true);
			$('#gpioWrap').show();
			break;

		case 'HiDraw':
			$('#portTypeHiDraw').parents('label').addClass('active');
			$('#portTypeHiDraw').attr('checked',true);
			$('#hidrawWrap').show();
			break;

		case 'Serial':
			$('#portTypeSerial').parents('label').addClass('active');
			$('#portTypeSerial').attr('checked',true);
			$('#serialWrap').show();
			break;
	}

	// 	Port Type Change
	$('.portType input[type=radio]').change(function() {
		switch ($(this).val()) {
			case 'GPIO':
				$('#gpioWrap').show();
				$('#hidrawWrap').hide();
				$('#serialWrap').hide();
				break;
			case 'HiDraw':
				$('#gpioWrap').hide();
				$('#hidrawWrap').show();
				$('#serialWrap').hide();
				break;
			case 'Serial':
				$('#gpioWrap').hide();
				$('#hidrawWrap').hide();
				$('#serialWrap').show();
				break;
		}
		wizardSettingsObj.ports.port1.portType = $(this).val();

		console.log( JSON.stringify(wizardSettingsObj) );
	});



	// Set Logic Mode
	switch(wizardSettingsObj.ports.port1.portDuplex) {
		case 'half':
			$('#portDuplexHalf').parents('label').addClass('active');
			$('#portDuplexHalf').attr('checked',true);
			break;

		case 'full':
			$('#portDuplexFull').parents('label').addClass('active');
			$('#portDuplexFull').attr('checked',true);
			break;
	}

	// 	Logic Mode Change
	$('.portDuplex input[type=radio]').change(function() {
		wizardSettingsObj.ports.port1.portDuplex = $(this).val();
/*
		switch ($(this).val()) {
			case 'half':
				$('#gpioWrap').show();
				$('#hidrawWrap').hide();
				$('#serialWrap').hide();
				break;
			case 'full':
				$('#gpioWrap').hide();
				$('#hidrawWrap').show();
				$('#serialWrap').hide();
				break;
		}
*/
		console.log( JSON.stringify(wizardSettingsObj) );
	});


	// 	GPIO Settings
	$('#rxMode').val(wizardSettingsObj.ports.port1.rxMode);

	if (wizardSettingsObj.ports.port1.rxMode != 'vox') {
		$('#voxMsg').hide();
	}

	if (wizardSettingsObj.ports.port1.rxMode != 'cos') {
		$('#rxGPIO_Grp').hide();
	}

	$('#rxGPIO').val(wizardSettingsObj.ports.port1.rxGPIO);

	$('#rxGPIO_active').val(wizardSettingsObj.ports.port1.rxGPIO_active);

	$('#txGPIO').val(wizardSettingsObj.ports.port1.txGPIO);

	$('#txGPIO_active').val(wizardSettingsObj.ports.port1.txGPIO_active);


	// Hidraw Settings
	$('#hidrawDev').val(wizardSettingsObj.ports.port1.hidrawDev);

	$('#hidrawRX_cos').val(wizardSettingsObj.ports.port1.hidrawRX_cos);

	if (wizardSettingsObj.ports.port1.hidrawRX_cos_invert == 'true') {
		$('#hidrawRX_cos_invert').prop( 'checked', true );
		resetSwitchery('#hidrawRX_cos_invert');
	}

	$('#hidrawTX_ptt').val(wizardSettingsObj.ports.port1.hidrawTX_ptt);

	if (wizardSettingsObj.ports.port1.hidrawTX_ptt_invert == 'true') {
		$('#hidrawTX_ptt_invert').prop( 'checked', true );
		resetSwitchery('#hidrawTX_ptt_invert');
	}


	// Serial Settings
	$('#serialDev').val(wizardSettingsObj.ports.port1.serialDev);

	$('#serialRX_cos').val(wizardSettingsObj.ports.port1.serialRX_cos);

	if (wizardSettingsObj.ports.port1.serialRX_cos_invert == 'true') {
		$('#serialRX_cos_invert').prop( 'checked', true );
		resetSwitchery('#serialRX_cos_invert');
	}

	$('#serialTX_ptt').val(wizardSettingsObj.ports.port1.serialTX_ptt);

	if (wizardSettingsObj.ports.port1.serialTX_ptt_invert == 'true') {
		$('#serialTX_ptt_invert').prop( 'checked', true );
		resetSwitchery('#serialTX_ptt_invert');
	}



	// Audio Settings
	$('#rxAudioDev').val(wizardSettingsObj.ports.port1.rxAudioDev);

	$('#txAudioDev').val(wizardSettingsObj.ports.port1.txAudioDev);


/*
 = /dev/hidraw1
 = MUTE_PLAY
hidrawRX_cos_invert = true
 = GPIO3
hidrawTX_ptt_invert = true
*/

/*
"serialDev": "/dev/ttyUSB2",
"serialRX_cos": "CTS",
"serialRX_cos_invert": "true",
"serialTX_ptt": "RTS",
"serialTX_ptt_invert": "true",
*/



console.log(wizardSettingsObj.ports.port1.rxMode);







/*
	$('#wizard').on('change', '#wizardForm', function(e) {
		e.preventDefault();
		
		var callSign = $('#callSign').val().toUpperCase();
		
		wizardSettingsObj.settings.callSign = 'new';
	
		console.log(callSign);

console.log(wizardSettingsObj);
	});
*/



	// Reset Switchery Toggles After Dynamic Build
	function resetSwitchery(checkboxID, disabledState = false) {
 		 $(checkboxID).next().remove('span'); // remove inital switcher span
 		 var elems = $(document).find(checkboxID);
 		 if (disabledState == true) {
			var switchery = new Switchery( elems[0], { disabled: true, color: '#8dc63f' } );
			switchery.disable();
	 	} else {
			var switchery = new Switchery( elems[0], { disabled: false, color: '#8dc63f' } );
			switchery.destroy();
			switchery.enable();
	 	}
 		 switchery.handleOnchange();
	}

});