<?php
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

    <title><?=_('Page Not Found')?> | OpenRepeater</title>

    <!-- Bootstrap -->
    <link href="includes/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="includes/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="includes/vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="includes/css/custom.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
				<div class="header-logo">
					<a href="dashboard.php"><img src="includes/images/OpenRepeaterLogo-Header.svg" style="width: 90%; max-width: 450px;"></a>
				</div>


              <h1 class="error-number">404</h1>
              <h2><?=_('Sorry but we couldn\'t find this page')?></h2>
              <p><?=_('This page you are looking for does not exist.')?></p>
            </div>
          </div>
        </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="includes/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="includes/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="includes/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="includes/vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="includes/js/custom.js"></script>
  </body>
</html>
