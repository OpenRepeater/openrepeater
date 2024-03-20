<?php
session_start();

################################################################################
# AUTOLOAD CLASSES
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/includes/autoloadClasses.php');
################################################################################

// --------------------------------------------------------
// IF USER IS LOGGED IN, REDIRECT TO START PAGE
if ((isset($_SESSION['username'])) && (isset($_SESSION['userID']))){
	$Users = new Users();
	header('location: ' . $Users->startPageURL); // If they aren't logged in, send them to login page.
}
// --------------------------------------------------------

$Database = new Database();

$versionNum = $Database->get_version();


// DUMMY FUNCTION TO LATER BE REPLACE BY GETTEXT FOR TRANSLATIONS
/*
function _($input) {
	return $input;
}
*/
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex,nofollow">
	<link rel="shortcut icon" href="favicon.ico">

    <title><?=_('Welcome')?> | OpenRepeater</title>

    <!-- Bootstrap -->
    <link href="includes/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="includes/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="includes/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="includes/vendors/animate.css/animate.min.css" rel="stylesheet">

    <link href="includes/css/page-login.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="includes/css/custom.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_logo">OpenRepeater</div>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <h1><?=_('Login')?></h1>

			<div id="loader" style="display:none;">
				<h5 style="text-align: center"><?=_('Please Wait')?></h5>
				<div class="progress">
					<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;"></div>
				</div>
			</div>

            <form id="loginForm">
			  <div id="alert" class="alert alert-warning" role="alert" style="display:none;"></div>

              <div>
                <input id="username" type="text" class="form-control" placeholder="<?=_('Username')?>" required="" />
              </div>

              <div>
				<input id="password" type="password" class="form-control password" name="password" data-toggle="password" placeholder="<?=_('Password')?>" required="">
              </div>

              <div>
                <button id="loginBtn" type="button" class="btn btn-success btn-lg"><?=_('Login')?></button>
              </div>

              <div class="clearfix"></div>

            </form>

            <div class="separator">
              <p><a target="_blank" href="https://openrepeater.com">OpenRepeater</a> <?=_('ver') . ': ' . $versionNum ?></p>
            </div>

          </section>
        </div>

      </div>
    </div>

    <!-- jQuery -->
    <script src="includes/vendors/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="includes/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Show Password Plugin -->
    <script src="includes/js/bootstrap-show-password/bootstrap-show-password.min.js"></script>

	<script>
		var tooManyAttemptsMsg = 'You have made too many login in attempts. Try again later.';
		var missingInfoMsg = 'It looks like you have forgot something important.';
		var shortUsernamedMsg = 'Your username is too short. It needs to be longer than that.';
		var shortPasswordMsg = 'Your password is too short. It needs to be longer than that.';
		var incorrectLoginMsg = 'LOGIN FAILED: Your username and password combination are incorrect';
		var commErrornMsg = 'There was an error communicating with the controller. Please try again.';
		var showPassTitle = 'Click here to show/hide password';
	</script>

    <script src="includes/js/page-login.js"></script>


  </body>
</html>
