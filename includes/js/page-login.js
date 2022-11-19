$(function() {

	/* Bootstrap Show Password Plugin */
	$("#password").password('toggle');
	$('#password').password('hide');
	$('.add-on').prop('title', showPassTitle);


	// --------------------------------------------------------------------------------------------
	// Main Validation and Submission Functions
	// --------------------------------------------------------------------------------------------

	var maxLoginAttempts = 10;
	var currLoginAttempts = 0;
	var minUsernameLength = 3;
	var minPasswordLength = 3;

	$('#username').focus();

	// ADD PORT FUNCTION AND MODAL
	$('#loginBtn').click(function(e) {
		e.preventDefault();

		currLoginAttempts++;
		console.log(currLoginAttempts);

		var username = $('#username').val().trim();
		var password = $('#password').val().trim();

		/* Put trimmed values back, sometimes whitespace happens with copy/paste */ 
		$('#username').val(username);
		$('#password').val(password);

		$('#password').password('hide'); // Hide password if it shown.


		/* CHECK NUMBER OF LOGIN ATTEMPS */
		if( currLoginAttempts > maxLoginAttempts ) {
			$('#username').val('');
			$('#password').val('');

			$('#username').prop( 'disabled', true );
			$('#password').prop( 'disabled', true );
			$('#loginBtn').addClass('disabled');
			
			displayAlert(tooManyAttemptsMsg);
			return false;
		}


		/* CHECK FOR MISSING FIELDS */
		if( username == '' || password == '' ) {
			displayAlert(missingInfoMsg);

			if( username == '' ) {
				$('#username').focus();
			} else {
				$('#password').focus();
			}
			return false;
		}


		/* CHECK FOR SHORT FIELDS */
		if( minUsernameLength >= username.length || minPasswordLength >= password.length ) {
			if( minUsernameLength >= username.length ) {
				displayAlert(shortUsernamedMsg);
				$('#username').focus();
			} else {
				displayAlert(shortPasswordMsg);
				$('#password').val('');
				$('#password').focus();
			}
			return false;
		}

		
		/* VALIDATION PASSED, NOW CHECK WITH SERVER */
		if( username != '' && password != '' ) {
			$.ajax({
				url:'../functions/ajax_user_requests.php',
				type:'post',
				data: {'login': JSON.stringify( { username: username, password: password } )},
				success: function(response){ // success here means successful communication, not successful login.
					var login = $.parseJSON(response);
					
					if(login.result == 'success') {
						// Successful Login
						$('#loginForm').hide();
						$('#loader').fadeIn(100);						
						setTimeout(function(){
							window.location = login.page_url;
						}, 1000)

					} else if(login.reason == 'invalid_login') {
						// Unsuccessful Login
						displayAlert(incorrectLoginMsg);
						$('#username').val('');
						$('#password').val('');
						$('#username').focus();

					} else {
						// Invalid response from controller
						displayAlert(commErrornMsg);
						console.log('Invalid response from controller');
					}

				},
				error: function(jqXHR, textStatus, errorThrown) {
					displayAlert(commErrornMsg);
					console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
				}
			});

		}
	});


	/* Display Login Alert */
	function displayAlert(message) {
		$('#alert').html(message);
		$('#alert').fadeIn(600);
		setTimeout(function(){
			$('#alert').fadeOut(1000);
		}, 19000)
	}



	// --------------------------------------------------------------------------------------------
	// Key Press Functions
	// --------------------------------------------------------------------------------------------


	/* Disabled Whitespace Entry */
	$('.form-control').bind('input', function(){
		$(this).val(function(_, v){
			return v.replace(/\s+/g, '');
		});
	});
	
	
	/* Process Enter/Spcial Keys */
	$('.form-control').keypress(function (e) {
		var key = e.which;
		var activeID = $(document.activeElement).prop('id');
	
		// Enter Key Pressed
		if(key == 13) {
			if(activeID == 'username') {
				if ( $('#password').val() == '' ) {
					$('#password').focus();
					return false;
				} else {
					$('#loginBtn').click();			
				}
	
			} else if(activeID == 'password') {
				if ( $('#username').val() == '' ) {
					$('#username').focus();
					return false;
				} else {
					$('#loginBtn').click();			
				}
	
			} else {
				return false;  
			}
	
		// Other Keys
		} else {
			/* Hide alert early, for those quick on the trigger. */
			if($('#alert').is(':visible')){
				$('#alert').fadeOut(300);		
			}
		}
	}); 


})