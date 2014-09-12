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
include('_includes/header.php');
?>


			<div>
				<ul class="breadcrumb">
					<li class="active">Home</li>
				</ul>
			</div>

<!--
<div id="realtimechart" style="height:190px;"></div>

<p><br><a href=#>Download Log File</a></p>
-->

<?php 
include('_includes/sys_info.php');
?>

			<div class="row-fluid sortable">

				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-globe"></i> System Info</h2>
					</div>
					<div class="box-content">
						<table width=100%>
							<tr>
								<td colspan="2"><strong>Hostname:</strong></td>
								<td colspan="2" id="host"><?php echo $host; ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>System Time:</strong></td>
								<td colspan="2" id="time"><?php echo $current_time; ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>Kernel:</strong></td>
								<td colspan="2" id="kernel"><?php echo $system . ' ' . $kernel; ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>Processor:</strong></td>
								<td colspan="2" id="processor"><?php echo getCPU_Type(); ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>CPU Frequency:</strong></td>
								<td colspan="2" id="freq"><?php echo getCPU_Speed(); ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>CPU Load:</strong></td>
								<td colspan="2" id="cpuload"><?php echo getCPU_Load(); ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>CPU Temperature:</strong></td>
								<td colspan="2" id="cpu_temperature"><?php echo getCPU_Temp('C'); ?></td>
							</tr>
							<tr>
								<td colspan="2"><strong>Uptime:</strong></td>
								<td colspan="2" id="uptime"><?php echo getUptime(); ?></td>
							</tr>
						</table>
					</div>
				</div><!--/span-->


				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-tasks"></i> Memory Usage</h2>
					</div>
					<div class="box-content">
						<table width=100%>
							<tr>
								<td colspan="2" class="head right">Memory:</td>
								<td colspan="2" class="head" id="total_mem"><?php echo $total_mem . ' kB'; ?></td>
							</tr>
							<tr>
								<td class="column1">Used</td>
								<td class="right" id="used_mem"><?php echo $used_mem . ' kB'; ?></td>
								<td class="column3"><div id="bar_wrap"><div id="bar1" style = "width:<?php echo $percent_used . '%'; ?>;">&nbsp;</div></div></td>
								<td class="right column4" id="percent_used"><?php echo $percent_used . '%'; ?></td>
							</tr>
							<tr>
								<td>Free</td>
								<td class="right" id="free_mem"><?php echo $free_mem . ' kB'; ?></td>
								<td><div id="bar_wrap"><div id="bar2" style = "width:<?php echo $percent_free . '%'; ?>;"></div></div></td>
								<td class="right" id="percent_free"><?php echo $percent_free . '%'; ?></td>
							</tr>
							<tr>
								<td>Buffered</td>
								<td class="right" id="buffer_mem"><?php echo $buffer_mem . ' kB'; ?></td>
								<td><div id="bar_wrap"><div id="bar3" style = "width:<?php echo $percent_buff . '%'; ?>;"></div></div></td>
								<td class="right" id="percent_buff"><?php echo $percent_buff . '%'; ?></td>
							</tr>
							<tr>
								<td>Cached</td>
								<td class="right" id="cache_mem"><?php echo $cache_mem . ' kB'; ?></td>
								<td><div id="bar_wrap"><div id="bar4" style = "width:<?php echo $percent_cach . '%'; ?>;"></div></div></td>
								<td class="right" id="percent_cach"><?php echo $percent_cach . '%'; ?></td>
							</tr>

							<tr>
								<td colspan="4" class="darkbackground">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2" class="head right">Swap:</td>
								<td colspan="2" class="head" id="total_swap"><?php echo $total_swap . ' kB'; ?></td>
							</tr>
							<tr>
								<td>Used</td>
								<td class="right" id="used_swap"><?php echo $used_swap . ' kB'; ?></td>
								<td><div id="bar_wrap"><div id="bar5" style = "width:<?php echo $percent_swap . '%'; ?>;"></div></div></td>
								<td class="right" id="percent_swap"><?php echo $percent_swap . '%'; ?></td>
							</tr>
							<tr>
								<td>Free</td>
								<td class="right" id="free_swap"><?php echo $free_swap . ' kB'; ?></td>
								<td><div id="bar_wrap"><div id="bar6" style = "width:<?php echo $percent_swap_free . '%'; ?>;"></div></div></td>
								<td class="right" id="percent_swap_free"><?php echo $percent_swap_free . '%'; ?></td>
							</tr>
							<tr>
								<td colspan="4" class="darkbackground">&nbsp;</td>
							</tr>
						</table>
					</div>
				</div><!--/span-->

				<div class="box span4">
					<div class="box-header well">
						<h2><i class="icon-hdd"></i> Disk Usage</h2>
					</div>
					<div class="box-content">

						<table id="tblDiskSpace">
							<tr>
								<td colspan="4" class="head center">Disk Usage</td>
							</tr> 

							<?php
							for ($i = 1; $i < $count; $i++) {
								$total = number_format(intval(preg_replace("/[^0-9]/", "", trim($size[$i])))) . " MB";
								$usedspace = number_format(intval(preg_replace("/[^0-9]/", "", trim($used[$i])))) . " MB";
								$freespace = number_format(intval(preg_replace("/[^0-9]/", "", trim($avail[$i])))) . " MB";
								echo "\n\t\t\t<tr>";
								echo "\n\t\t\t\t<td class=\"head\" colspan=\"4\">" . $mount[$i] . " (" . $typex[$i] . ")</td>";
								echo "\n\t\t\t</tr>";
								echo "\n\t\t\t<tr>";
								echo "\n\t\t\t\t<td>&nbsp;</td>";
								echo "\n\t\t\t\t<td>Total Size</td>";
								echo "\n\t\t\t\t<td class=\"right\">" . $total . "</td>";
								echo "\n\t\t\t\t<td class=\"right\">&nbsp;</td>";
								echo "\n\t\t\t</tr>";
								echo "\n\t\t\t<tr>";
								echo "\n\t\t\t\t<td>&nbsp;</td>";
								echo "\n\t\t\t\t<td>Used</td>";
								echo "\n\t\t\t\t<td class=\"right\">" . $usedspace . "</td>";
								echo "\n\t\t\t\t<td class=\"right\">" . $percent[$i] . "</td>";
								echo "\n\t\t\t</tr>";
								echo "\n\t\t\t<tr>";
								echo "\n\t\t\t\t<td>&nbsp;</td>";
								echo "\n\t\t\t\t<td>Available</td>";
								echo "\n\t\t\t\t<td class=\"right\">" . $freespace . "</td>";
								echo "\n\t\t\t\t<td class=\"right\">" . (100-(floatval($percent_part[$i]))) . "%</td>";
								echo "\n\t\t\t</tr>";
								if ($i < $count-1) {
									echo "\n\t\t\t<tr><td colspan=\"4\">&nbsp;</td></tr>";
								}
							}
							?>
						</table>

					</div>
				</div><!--/span-->

			</div><!--/row-->

       
<?php include('_includes/footer.php'); ?>


<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>
