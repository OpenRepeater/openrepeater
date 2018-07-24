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
	
require_once('includes/classes/System.php');
$classSystem = new System();
	
include('functions/ajax_system.php');
?>

	<div id="overlay">
		<div class="msg_box">
			<span class="msg_1"></span><span class="msg_2"></span>
		</div>
	</div>


			<?php 
				$systemTime = $classSystem->system_time();
				$systemArray = $classSystem->system_info();
			?>
			
			<script type="text/javascript">
				var timeString = "<?=str_replace("-","/",$systemTime['datetime']);?>";
				var startTime = new Date(timeString);
			</script>

			<div class="row-fluid sortable">
				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-globe"></i> Core System Info</h2>
					</div>
					<div class="box-content">

						<div class="info_label">Hostname:</div>
						<div class="info_value" id="host"><?php echo $systemArray['host']; ?></div>
						<div class="info_clear"></div>
						
						<div class="info_label">System Time:</div>
						<div class="info_value">
							<span id="cur_date"><?=$systemTime['date'];?></span><br>
							<span id="cur_time"><?=$systemTime['time'];?></span> <?=$systemTime['tz_short'];?>
						</div>
						<div class="info_clear"></div>
					
						<div class="info_label">Kernel:</div>
						<div class="info_value" id="kernel"><?php echo $systemArray['kernel']; ?></div>
						<div class="info_clear"></div>
					
						<div class="info_label">CPUs/Cores:</div>
						<div class="info_value" id="cpu_cores"><?php echo $systemArray['cpu_cores']; ?></div>
						<div class="info_clear"></div>
					
						<div class="info_label">CPU Frequency:</div>
						<div class="info_value" id="cpu_speed"><?php echo $systemArray['cpu_speed']; ?></div>
						<div class="info_clear"></div>
					
						<div class="mem_group">
							<div>
								<span class="bar_left">
									<strong>CPU Load:</strong>
								</span>
								<span class="bar_right">
									<span id="cpu_load"><?php echo $systemArray['cpu_load']; ?></span>
								</span>
							</div>
							<div class="bar_wrap">
								<div id="bar5" style = "width:<?php echo $systemArray['cpu_load']; ?>;"></div>
							</div>
						</div>


						
						<?php
							// Hide on boards that don't support this
							if ($systemArray['cpuTempF'] != '32°F') { ?>
								<div class="info_label">CPU Temperature:</div>
								<div class="info_value" id="cpuTempBoth"><?php echo $systemArray['cpuTempBoth']; ?></div>
								<div class="info_clear"></div>
							<?php	
							} // close if
						?>
							
					
						<div class="info_label">Uptime:</div>
						<div class="info_value" id="uptime"><?php echo $systemArray['uptime']; ?></div>
						<div class="info_clear"></div>



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
						<div class="info_label">SVXLink Status:</div>
						<div id="rptStatus" class="info_value" ><?php echo $status_string; ?></div>

						<div class="info_clear"></div>
						
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

						<?php $memoryArray = $classSystem->memory_usage(); ?>

						<div class="mem_group">
							<div>
								<span class="bar_left">
									<strong>Used: <span id="percent_used"><?php echo $memoryArray['percent_used']; ?></span>%</strong>
								</span>
								<span class="bar_right">
									<strong>Free: <span id="percent_free"><?php echo $memoryArray['percent_free']; ?></span>%</strong>
								</span>
							</div>
							<div class="bar_wrap ram">
								<div id="bar1" style = "width:<?php echo $memoryArray['percent_used'] . '%'; ?>;"></div>
							</div>
							<div>
								<span class="bar_left">
									<span id="used_mem"><?php echo $memoryArray['used_mem']; ?></span>
								</span>
								<span class="bar_right">
									<span id="free_mem"><?php echo $memoryArray['free_mem']; ?></span>
								</span>
							</div>
						</div>						
						<div id="total_mem">Total Memory: <span><?php echo $memoryArray['total_mem']; ?></span></div>

						<hr>
						
						<div class="left_col">
							<div class="mem_group">
								<div>
									<span class="bar_left">
										<strong>Buffered:</strong>
									</span>
									<span class="bar_right">
										<strong><span id="percent_buff"><?php echo $memoryArray['percent_buff']; ?></span>%</strong>
									</span>
								</div>
								<div class="bar_wrap">
									<div id="bar3" style = "width:<?php echo $memoryArray['percent_buff'] . '%'; ?>;"></div>
								</div>
								<div>
									<span class="bar_left">
										<span id="buffer_mem"><?php echo $memoryArray['buffer_mem']; ?></span>
									</span>
								</div>
							</div>
						</div>
						
						<div class="right_col">
							<div class="mem_group">
								<div>
									<span class="bar_left">
										<strong>Cached:</strong>
									</span>
									<span class="bar_right">
										<strong><span id="percent_cach"><?php echo $memoryArray['percent_cach']; ?></span>%</strong>
									</span>
								</div>
								<div class="bar_wrap">
									<div id="bar4" style = "width:<?php echo $memoryArray['percent_cach'] . '%'; ?>;"></div>
								</div>
								<div>
									<span class="bar_left">
										<span id="cache_mem"><?php echo $memoryArray['cache_mem']; ?></span>
									</span>
								</div>
							</div>
						</div>

						<div class="info_clear"></div>
						
					</div>
				</div><!--/span-->


				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-hdd"></i> Disk Usage</h2>
					</div>
					<div class="box-content">
						<?php
						foreach ( $classSystem->disk_usage() as $driveNum => $driveValues ) {
							if ($driveNum > 1) { echo '<hr>'; }
							$drive = $driveValues['mount'] . " (" . $driveValues['typex'] . ")";
							echo '<div id="drive_label">'.$drive.'<span id="drive_size">Capacity: '.$driveValues['capacity'].'</span></div>';
							echo '<div id="donutchart'.$driveNum.'" style="height: 300px;"></div>';
						}
						?>
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
