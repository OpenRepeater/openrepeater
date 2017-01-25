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
$customJS = "sys_info_chart_data.php, page-dashboard.js"; // "file1.js, file2.js, ... "

include('includes/header.php');
?>
<!--
<div id="realtimechart" style="height:190px;"></div>
-->
<?php 
include('includes/sys_info.php');
include('functions/ajax_system.php');
?>
			<div class="row-fluid sortable">
				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-globe"></i> Core System Info</h2>
					</div>
					<div class="box-content">
						<div id="info_label">Hostname:</div>
						<div id="info_value"><?php echo $host; ?></div>
						<div id="info_clear"></div>
						
						<div id="info_label">System Time:</div>
						<div id="info_value"><?php echo $current_time; ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">Kernel:</div>
						<div id="info_value"><?php echo $system . ' ' . $kernel; ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPUs/Cores:</div>
						<div id="info_value"><?php echo getCPU_Type(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Frequency:</div>
						<div id="info_value"><?php echo getCPU_Speed(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Load:</div>
						<div id="info_value"><?php echo getCPU_Load(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Temperature:</div>
						<div id="info_value"><?php echo getCPU_Temp('F'); ?> / <?php echo getCPU_Temp('C'); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">Uptime:</div>
						<div id="info_value"><?php echo getUptime(); ?></div>
						<div id="info_clear"></div>

						<hr>
						<!-- Button triggered modal -->
						<button class="btn btn-success" data-toggle="modal" data-target="#systemRestart"><i class="icon-refresh icon-white"></i> Restart</button>
						<!-- Button triggered modal -->
						<button class="btn btn-danger" data-toggle="modal" data-target="#systemShutdown"><i class="icon-off icon-white"></i> Shutdown</button>
					</div>


					<?php 
						### Get inital SVXLink Status on page load and decide what to display below ###
						$svxlink_status = exec_orp_helper('svxlink', 'status');
						if ($svxlink_status == 'active') {
							$status_string = '<span class="label label-success">Active</span>';
							$control_btn_text = '<i class="icon-stop"></i> Stop Repeater';
						} else if ($svxlink_status == 'failed') {
							$status_string = '<span class="label label-warning">FAILED</span> - <a href="log.php">View Log</a>';							
							$control_btn_text = '<i class="icon-play"></i> Start Repeater';
						} else {
							$status_string = '<span class="label label">Deactivated</span>';
							$control_btn_text = '<i class="icon-play"></i> Start Repeater';
						}
					?>

					<div class="box-header well">
						<h2><i class="icon-retweet"></i> SVXLink</h2>
					</div>
					<div class="box-content">
						<div id="info_label">SVXLink Status:</div>
						<div id="info_value" class="rptStatus"><?php echo $status_string; ?></div>

						<div id="info_clear"></div>
						
						<button id="rptControlBtn" class="btn" onclick="toggleRepeaterState();"><?php echo $control_btn_text; ?></button>
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
					<div class="box-content">

						<div id="mem_group">
							<?php echo '<strong>Used:</strong> ' . $used_mem . ' KB<span id="mem_percent">' . $percent_used . '%</span>'; ?>
							<div id="bar_wrap">
								<div id="bar1" style = "width:<?php echo $percent_used . '%'; ?>;"></div>
							</div>
						</div>
						
						<div id="mem_group">
							<?php echo '<strong>Free:</strong> ' . $free_mem . ' KB<span id="mem_percent">' . $percent_free . '%</span>'; ?>
							<div id="bar_wrap">
								<div id="bar2" style = "width:<?php echo $percent_free . '%'; ?>;"></div>
							</div>
						</div>
							
						<div id="mem_group">
							<?php echo '<strong>Buffered:</strong> ' . $buffer_mem . ' KB<span id="mem_percent">' . $percent_buff . '%</span>'; ?>
							<div id="bar_wrap">
								<div id="bar3" style = "width:<?php echo $percent_buff . '%'; ?>;"></div>
							</div>
							
						</div>
							
						<div id="mem_group">
							<?php echo '<strong>Cached:</strong> ' . $cache_mem . ' KB<span id="mem_percent">' . $percent_cach . '%</span>'; ?>
							<div id="bar_wrap">
								<div id="bar4" style = "width:<?php echo $percent_cach . '%'; ?>;"></div>
							</div>
						</div>
						
						<div id="total_mem">Total Memory: <?php echo $total_mem . ' KB'; ?></div>

					</div>
				</div><!--/span-->



				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-hdd"></i> Disk Usage</h2>
					</div>
					<div class="box-content">
						<?php
						for ($i = 1; $i < $count; $i++) {
							$clean_size = intval(trim($size[$i]));
							if ($clean_size > 1000000000 ) {
								$capacity = number_format(($clean_size * .000000001), 2, '.', ',') . " PB";
							} elseif ($clean_size > 1000000 ) {
								$capacity = number_format(($clean_size * .000001), 2, '.', ',') . " TB";
							} elseif ($clean_size > 1000 ) {
								$capacity = number_format(($clean_size * .001), 1, '.', ',') . " GB";
							} else {
								$capacity = number_format($clean_size, 1, '.', ',') . " MB";
							}

							$drive = $mount[$i] . " (" . $typex[$i] . ")";
						}
						?>

						<div id="drive_label"><?php echo $drive; ?><span id="drive_size">Capacity: <?php echo $capacity; ?></span></div>
						<div id="donutchart" style="height: 300px;"></div>
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
