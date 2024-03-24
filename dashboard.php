<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: index.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------
?>

<?php
$customJS = 'page-dashboard.js'; // 'file1.js, file2.js, ... '
// $customCSS = 'upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-tachometer"></i> <?=_('Dashboard')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

			<div class="alert alert-warning">
			<h4><i class="fa fa-warning"></i> Warning!</h4> This page is still in early development. So, there may be things that don't function as one might expect. 
			</div>

            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Coming Soon')?></h4></div>
                  
                  <div class="x_content">
                    <br />
                    <form class="form-horizontal form-label-left input_mask">
						
						<p>The dashboard is not designed yet. My plan is to make it widgetized. It should show some of the same information as current versions of ORP, but I would also like to add at some point the ability to add widgets as part of ORP modules. So for example remote Relay could have status indicators for the current state of each relay and maybe the ability to toggle them on/off from the UI.</p>

                    </form>
                  </div>
                </div>

              </div>
           </div>


            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Data Testing Strings')?></h4></div>
                  
                  <div class="x_content">

				  	<div id="sysStatic" style="color: red;"></div>
				  	<div id="sysDynamic" style="color: blue;"></div>
				  	<div id="svxlinkInfo" style="color: gray;"></div>
				  	<div id="memoryInfo" style="color: orange;"></div>
				  	<div id="diskInfo" style="color: green;"></div>

				  	<p><em>Note: there should be five colored sections. If not that indicates missing data. The browsers console can also be monitored.</em></p>

                  </div>
                </div>

              </div>
           </div>



          </div>
        </div>
        <!-- /page content -->

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>