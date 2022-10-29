<?php
#####################################################################################################
# Users Class
#####################################################################################################

class Users {

	public $loginURL = 'index.php';
	public $startPageURL = 'settings.php';
	private $Database;
	private $currentUserID;
	private $currentUser;


	public function __construct() {
		$this->Database = new Database();
	}



	###############################################
	# Process User Login
	###############################################

	public function login($username, $rawPassword) {
		$userInfo = $this->getUser($username);
		if($userInfo['username']) {
			$userID = $userInfo['userID'];
			$hashPassword = $userInfo['password'];
			$pwSalt = $userInfo['salt'];

			$matchStatus = $this->verifyPassword($rawPassword, $hashPassword, $pwSalt);
			
			if($matchStatus) {
				// Start session and return true.
				$versionNum = $this->Database->get_version();
				$getCallSign = $this->Database->select_key_value('SELECT * FROM settings WHERE "keyID" = "callSign";', 'keyID', 'value');
 				$this->startUserSession ( $userID, $username, $versionNum, $getCallSign['callSign'] );
				return true;
				
			} else {
				$this->logout();
				return false;
			}

		} else {
			return false;
		}
	}


	private function getUser($username) {
		// Verify User and get their info
		$userExists = $this->Database->exists('users', 'username', $username);
		if($userExists) {
			$userInfo = $this->Database->select_single("SELECT * FROM users WHERE username = '$username'");
			return $userInfo;
		} else {
			return false;
		}
	}


	private function verifyPassword($rawPassword, $hashPassword, $pwSalt) {
		$loginHash = hash( 'sha256', $pwSalt . hash('sha256', $rawPassword) );
		if ($loginHash === $hashPassword){
			return true;
	
		} else {
			return false;
		}	
	}


	private function startUserSession ($userID, $username, $versionNum, $callsign) {
		session_start();
		session_regenerate_id();
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $userID;
		$_SESSION['version_num'] = $versionNum;
		if ($callsign) {
			// If callsign is set in DB, then login
			$_SESSION['callsign'] = $callsign;
		}
	}



	###############################################
	# Logout and Destroy Session
	###############################################

	public function logout() {
		return $this->endUserSession();
	}


	public function endUserSession () {
		session_start();
		$_SESSION = array();
		session_destroy();
		return true;
	}



	###############################################
	# Change Password
	###############################################

	public function validateCurrentPW($rawPassword) {
		session_start();
		$this->currentUser = $_SESSION['username'];
		$userInfo = $this->getUser($this->currentUser);
		if($userInfo['username']) {
			$this->currentUserID = $userInfo['userID'];
			$hashPassword = $userInfo['password'];
			$pwSalt = $userInfo['salt'];
			$matchStatus = $this->verifyPassword($rawPassword, $hashPassword, $pwSalt);
			if($matchStatus) { return true; } else { return false; }
		} else {
			return false;
		}
	}


	public function change_password($old_password, $new_password, $confirm_password) {
		$verifyOLD = $this->validateCurrentPW($old_password);
		if($verifyOLD == true && $new_password === $confirm_password) {
			$result = $this->setPassword($this->currentUserID, $new_password);
			if($result == true) { return 'passwdChanged'; } else { return 'errorDB'; }
		} else {
			return 'errorPassMismatch';
		}
	}


	public function setPassword($userID, $rawPassword) {
		$salt = substr( md5(uniqid(rand(), true)), 0, 8);
		$hashPassword = hash( 'sha256', $salt . hash('sha256', $rawPassword) );
		$updateSQL = "UPDATE users SET password='$hashPassword', salt='$salt' WHERE userID='$userID'";
		$result = $this->Database->update($updateSQL);
		return $result;
	}



	###############################################
	# Add User
	###############################################

	public function add($new_username, $new_password, $confirm_password, $user_role) {
		$new_user = [ 'users' => ['username' => $new_username, 'password' => $new_password, 'salt' => 'temp', 'enabled' => '0', 'user_role' => $user_role ] ];

		$this->Database->insert($new_user);

		echo $new_username;
	}



	###############################################
	# Manage User
	###############################################

	public function delete($user_id, $cur_user_id) {
		echo 'nothing';
	}

	
	public function enableUser ($userID) {
		$updateSQL = "UPDATE users SET enabled='1' WHERE userID='$userID'";
		return $this->Database->update($updateSQL); // return true/false
	}


	public function disableUser ($userID, $curUserID) {
		if ($userID == $curUserID) { return false; } // Can't disable yourself now...can we?
		$updateSQL = "UPDATE users SET enabled='0' WHERE userID='$userID'";
		return $this->Database->update($updateSQL); // return true/false
	}


}
?>