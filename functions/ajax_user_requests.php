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

	if( isset($_POST['type']) ) {
		$type = $_POST['type'];
	
		if($type == 'login') {
	
			if( isset($_POST['username']) && isset($_POST['password']) ) {
				$username = $_POST['username'];
				$rawPassword = $_POST['password'];
	
				$Users = new Users();
				
				$loginResult = $Users->login($username, $rawPassword);
				
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

	if( isset($_POST['type']) ) {
		$type = $_POST['type'];
	
		if($type == 'logout') {
			$Users = new Users();
			$logoutResults = $Users->logout();
			if($logoutResults) {
				echo json_encode(['result'=>'success', 'login_url'=>$Users->loginURL]);
			}
		}
		
	}



################################################################################
} // close ELSE to end login/session check from top of page
################################################################################
?>