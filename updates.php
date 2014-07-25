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
$pageTitle = "Updates"; 
include('_includes/header.php');
?>


			<div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a> <span class="divider">/</span></li>
					<li class="active"><?php echo $pageTitle; ?></li>
				</ul>
			</div>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-picture"></i> Updates</h2>
					</div>
					<div class="box-content">
						
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
