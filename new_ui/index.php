<?php
// DUMMY FUNCTION TO LATER BE REPLACE BY GETTEXT FOR TRANSLATIONS
function _($input) {
	return $input;
}
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

    <title><?=_('Welcome')?> | OpenRepeater</title>

    <!-- Bootstrap -->
    <link href="/includes/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/includes/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/includes/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/includes/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/includes/css/custom.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_logo">OpenRepeater</div>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form>
              <h1><?=_('Login')?></h1>
              <div>
                <input type="text" class="form-control" placeholder="<?=_('Username')?>" required="" />
              </div>
              <div>
				<input type="password" class="form-control" id="proxy_password" name="password" data-toggle="password" placeholder="<?=_('Password')?>" required="">

              </div>
              <div>
                <a class="btn btn-success btn-lg" href="settings.php"><?=_('Log in')?></a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p><a target="_blank" href="https://openrepeater.com">OpenRepeater</a> <?=_('ver')?>: 3.0.0</p>
              </div>
            </form>
          </section>
        </div>

      </div>
    </div>

    <!-- jQuery -->
    <script src="/includes/vendors/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="/includes/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Show Password Plugin -->
<!--
    <script src="/includes/js/bootstrap-show-password/bootstrap-show-password.min.js"></script>
    <script type="text/javascript">
		$("#password").password('toggle');
	</script>
-->

  </body>
</html>
