<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------
?>
<?php
	
$pageTitle = "Dashboard";

$customCSS = "sys_info.css"; // "file1.css, file2.css, ... "
$customJS = "page-dashboard.js"; // "file1.js, file2.js, ... "

include('includes/header.php');
	
require_once('includes/classes/System.php');
$classSystem = new System();
	
?>

	<div id="overlay">
		<div class="msg_box">
			<span class="msg_1"></span><span class="msg_2"></span>
		</div>
	</div>
			
			<div class="row-fluid sortable">
				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-globe"></i> Core System Info</h2>
					</div>
					<div class="box-content">
						<div id="system_static">
							
						  	<center>
							  	<h4 style="text-align: center">Loading...</h4>
							  	<img src="theme/img/ajax-loaders/ajax-loader-1.gif" align="middle">
						  	</center>

						</div>
						
						<div id="system_dynamic"></div>						
					  	
						<hr>
						<!-- Button triggered modal -->
						<button class="btn btn-success" data-toggle="modal" data-target="#systemRestart"><i class="icon-refresh icon-white"></i> Restart</button>
						<!-- Button triggered modal -->
						<button class="btn btn-danger" data-toggle="modal" data-target="#systemShutdown"><i class="icon-off icon-white"></i> Shutdown</button>
					</div>


					<div class="box-header well">
						<h2><i class="icon-retweet"></i> SVXLink</h2>
					</div>
					<div id="svxlink_info" class="box-content">

					  	<center>
						  	<h4 style="text-align: center">Loading...</h4>
						  	<img src="theme/img/ajax-loaders/ajax-loader-1.gif" align="middle">
					  	</center>

					</div>

				</div><!--/span-->



				<!-- Modal - RESTART SYSTEM -->
				<div class="modal fade" id="systemRestart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
					<h3 class="modal-title" id="myModalLabel">System Restart</h3>
				      </div>
				      <div class="modal-body">
						<h4>Are You Sure You Want to Do This?</h4>
						<p>This will restart the entire controller system. While the system is rebooting the web interface will be unresponsive and the repeater will not operate. In rare cases, the system may not fully power cycle which means it will be unavailable until it can be reset in person.</p>
				      </div>
				      <div class="modal-footer">
							<button class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button class="btn btn-success" onclick="reboot_orp_system();"><i class="icon-refresh icon-white"></i> Restart</button>
				      </div>
				    </div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->
			
				<!-- Modal - SHUTDOWN SYSTEM -->
				<div class="modal fade" id="systemShutdown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
					<h3 class="modal-title" id="myModalLabel"><span style="color:red;">WARNING:</span> System Shutdown</h3>
				      </div>
				      <div class="modal-body">
						<h4>Are You Sure You Want to Do This?</h4>
						<p>By shutting down the entire controller system you will not be able to initiate a system restart unless you are physically present at the controller to do so. Proceed with caution.</p>
				      </div>
				      <div class="modal-footer">
							<button class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button class="btn btn-danger" onclick="shutdown_orp_system();"><i class="icon-off icon-white"></i> Shutdown</button>
				      </div>
				    </div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->


				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-tasks"></i> Memory Usage</h2>
					</div>
					<div id="memory_info" class="box-content">

					  	<center>
						  	<h4 style="text-align: center">Loading...</h4>
						  	<img src="theme/img/ajax-loaders/ajax-loader-1.gif" align="middle">
					  	</center>

						<div class="info_clear"></div>
						
					</div>
				</div><!--/span-->


				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-hdd"></i> Disk Usage</h2>
					</div>
					<div id="disk_info" class="box-content">

					  	<center>
						  	<h4 style="text-align: center">Loading...</h4>
						  	<img src="theme/img/ajax-loaders/ajax-loader-1.gif" align="middle">
					  	</center>

 					</div>
				</div><!--/span-->


			</div>
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
