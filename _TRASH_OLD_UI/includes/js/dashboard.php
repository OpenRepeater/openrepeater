<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: login.php');
} else { // If they are, show the page.
// --------------------------------------------------------
?>
<?php
	
$pageTitle = "Dashboard";

$customCSS = "sys_info.css"; // "file1.css, file2.css, ... "
$customJS = "sys_info_chart_data.php"; // "file1.js, file2.js, ... "

include('includes/header.php');
?>
<!--
<div id="realtimechart" style="height:190px;"></div>
-->
<?php 
include('includes/sys_info.php');
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
					
						<div id="info_label">Processor:</div>
						<div id="info_value"><?php echo getCPU_Type(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Frequency:</div>
						<div id="info_value"><?php echo getCPU_Speed(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Load:</div>
						<div id="info_value"><?php echo getCPU_Load(); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">CPU Temperature:</div>
						<div id="info_value"><?php echo getCPU_Temp('C'); ?></div>
						<div id="info_clear"></div>
					
						<div id="info_label">Uptime:</div>
						<div id="info_value"><?php echo getUptime(); ?></div>
						<div id="info_clear"></div>
					</div>
				</div><!--/span-->

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
							$capacity = number_format(intval(preg_replace("/[^0-9]/", "", trim($size[$i])))) . " MB";
							$drive = $mount[$i] . " (" . $typex[$i] . ")";
						}
						?>

						<div id="drive_label"><?php echo $drive; ?><span id="drive_size">Capacity: <?php echo $capacity; ?></span></div>
						<div id="donutchart" style="height: 300px;"></div>
 					</div>
				</div>


			</div>
<?php include('includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
