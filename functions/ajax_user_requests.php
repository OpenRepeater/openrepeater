<?php
################################################################################
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
################################################################################
# LOGGED OUT FUNCTIONS
################################################################################

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

	if ( isset($_POST['login']) ) {
		$loginArray = json_decode($_POST['login'], true);

		if( isset($loginArray['username']) && isset($loginArray['password']) ) {

			$Users = new Users();
			
			$loginResult = $Users->login($loginArray['username'], $loginArray['password']);
			
			if($loginResult) {
				echo json_encode(['result'=>'success', 'page_url'=>$Users->startPageURL]);
				
			} else {
				// Failed Login - Username/Password Mismatch
				echo  json_encode(['result'=>'failed','reason'=>'invalid_login']);					
			}
		
		} else {
			echo  json_encode(['result'=>'failed','reason'=>'no_user_info_sent']);
		}

	}



################################################################################
# LOGGED IN FUNCTIONS
################################################################################
} else {
################################################################################

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

	if ( isset($_POST['logout']) ) {
		$Users = new Users();
		$logoutResults = $Users->logout();
		if($logoutResults) {
			echo json_encode(['result'=>'success', 'login_url'=>$Users->loginURL]);
		}
	}


	if ( isset($_POST['validatePassword']) ) {
		$Users = new Users();
		$pwArray = json_decode($_POST['validatePassword'], true);
		// User must be logged in for this function. ID will be retrieved from user session. 
		$pwResults = $Users->validateCurrentPW($pwArray['existingPassword']);
		if($pwResults == true) {
			echo json_encode(['result'=>'success', 'message'=>_('Old Password Correct')]);
		} else {
			echo json_encode(['result'=>'error', 'message'=>_('Old password entered is incorrect for this account. Please try again.')]);				
		}
	}


	if ( isset($_POST['changePassword']) ) {
		$Users = new Users();
		$pwArray = json_decode($_POST['changePassword'], true);
		// User must be logged in for this function. ID will be retrieved from user session. 
		$pwResults = $Users->change_password($pwArray['existingPassword'], $pwArray['newPassword'], $pwArray['confirmPassword']);

		switch ($pwResults) {
			case 'passwdChanged':
				echo json_encode(['result'=>'success', 'message'=>_('Password Successfully Changed')]);
				break;
			case 'errorDB':
				echo json_encode(['result'=>'error', 'message'=>_('Could not write password to database for unknown reason.')]);
				break;
			case 'errorPassMismatch':
				echo json_encode(['result'=>'error', 'message'=>_('One of the passwords did not match up. Try again.')]);
				break;
		}
	}



################################################################################
} // close ELSE to end login/session check from top of page
################################################################################
?>