<?php

session_start();

// Change Password
if (isset($_POST['action'])){
	if ($_POST['action'] == "setPassword"){
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		// passwords don't match
		if ($password1 != $password2) {
			header('location: login.php?action=setPassword&error=mismatch');
			die();
		}

		// password is too long
		$password = trim($_POST['password1']);
		if (strlen($password) > 28) {
			header('location: login.php?action=setPassword&error=toolong');
			die();
		}

		// password is too short
		if (strlen($password) < 8) {
			header('location: login.php?action=setPassword&error=tooshort');
			die();
		}

		$resetQuery = "SELECT username, salt FROM users WHERE username = 'admin';";
        $resetData = $GLOBALS['app']['db']->executeQuery($resetQuery)->fetch();

		if (count($resetData) < 1){
			header('location: login.php?action=setPassword');
		}

		$resetHash = hash('sha256', $salt . hash('sha256', $password));
		$hash = hash('sha256', $password);

		function createSalt(){
			$string = md5(uniqid(rand(), true));
			return substr($string, 0, 8);
		}

		$salt = createSalt();
		$hash = hash('sha256', $salt . $hash);
        $query = "UPDATE users SET salt=?, password=? WHERE username='admin'";
		$GLOBALS['app']['db']->executeUpdate($query, [$salt, $hash]);
		header('location: index.php');
	}
}


// Process User Login
if ((isset($_POST['username'])) && (isset($_POST['password']))){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$loginQuery = "SELECT UserID, password, salt FROM users WHERE username = ?;";
    $loginData = $GLOBALS['app']['db']->executeQuery($loginQuery, [$username])->fetch();

	if (count($loginData) < 1){
		header('location: login.php?error=incorrectLogin');
	}

	$loginHash = hash('sha256', $loginData['salt'] . hash('sha256', $password));
	if ($loginHash != $loginData['password']){
		header('location: login.php?error=incorrectLogin');
	} else {
		session_regenerate_id();
		$_SESSION['username'] = $username;
		$_SESSION['userID'] = $loginData['UserID'];
		header('location: index.php');
	}
}

//Display Login Form
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	if (isset($_GET['error']) && $_GET['error'] == 'incorrectLogin'){
		$login_msg = '<div class="alert alert-error">Sorry, the Username and Password combination did not match</div>';
	} else {
		$login_msg = '<div class="alert alert-info">Please login with your Username and Password.</div>';
	}

	$no_visible_elements=true;
	include('_includes/header.php');
	print '
	<div class="row-fluid">
		<div class="span12 center login-header">
			<h2>Welcome</h2>
		</div><!--/span-->
	</div><!--/row-->

	<div class="row-fluid">
		<div class="well span5 center login-box">
			'.$login_msg.'
			<form name="login" action="login.php" method="post" class="form-horizontal">
				<fieldset>
					<div class="input-prepend" title="Username" data-rel="tooltip">
						<span class="add-on"><i class="icon-user"></i></span><input autofocus class="input-large span10" name="username" id="username" type="text" placeholder="Username" />
					</div>
					<div class="clearfix"></div>

					<div class="input-prepend" title="Password" data-rel="tooltip">
						<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="password" id="password" type="password" placeholder="Password" />
					</div>
					<div class="clearfix"></div>

					<div class="input-prepend">
					<label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>
					</div>
					<div class="clearfix"></div>

					<p class="center span5">
					<button type="submit" class="btn btn-primary">Login</button>
					</p>
				</fieldset>
			</form>
		</div><!--/span-->
	</div><!--/row-->
	';
	include('_includes/footer.php');
	die();
}


// Display Change Password Form
if (isset($_GET['action'])){
	if ($_GET['action'] == "logout"){
		$_SESSION = array();
		session_destroy();
		header('Location: login.php');
	} else if ($_GET['action'] == "setPassword"){

	if ($_GET['error'] == 'mismatch') {
		$login_msg = '<div class="alert alert-error">Sorry, Your passwords do not match. Please try again!</div>';
	} else if ($_GET['error'] == 'toolong') {
		$login_msg = '<div class="alert alert-error">Your password must be 28 characters or shorter. Please try again!</div>';
	} else if ($_GET['error'] == 'tooshort') {
		$login_msg = '<div class="alert alert-error">Your password must be at least 8 characters long. Please try again!</div>';
	} else {
		$login_msg = '<div class="alert alert-info">Please Enter Your New Password and Confirm.</div>';
	}

	$no_visible_elements=true;
	include('_includes/header.php');
	print '
	<div class="row-fluid">
		<div class="span12 center login-header">
			<h2>Change Password</h2>
		</div><!--/span-->
	</div><!--/row-->

	<div class="row-fluid">
		<div class="well span5 center login-box">
			'.$login_msg.'
			<form name="changePassword" action="login.php" method="post" class="form-horizontal">
				<fieldset>
					<input type="hidden" name="action" value="setPassword">		
					<div class="input-prepend" title="Username" data-rel="tooltip">
						<span class="add-on"><i class="icon-lock"></i></span><input autofocus class="input-large span10" name="password1" id="username" type="password" placeholder="New Password" />
					</div>
					<div class="clearfix"></div>

					<div class="input-prepend" title="Password" data-rel="tooltip">
						<span class="add-on"><i class="icon-lock"></i></span><input class="input-large span10" name="password2" id="password" type="password" placeholder="Confirm New Password" />
					</div>
					<div class="clearfix"></div>

					<p class="center span5">
					<button type="submit" class="btn btn-primary">Change Password</button>
					</p>
				</fieldset>
			</form>
		</div><!--/span-->
	</div><!--/row-->
	';
	include('_includes/footer.php');
	}
}
?>
