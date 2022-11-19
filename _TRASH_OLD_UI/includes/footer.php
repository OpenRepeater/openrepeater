		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
			<!-- content ends -->
			</div><!--/#content.span10-->
		<?php } ?>
		</div><!--/fluid-row-->
		<?php if(!isset($no_visible_elements) || !$no_visible_elements)	{ ?>
		
		<hr>

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>

		<footer>
			<p class="pull-left">Designed by: Aaron Crawford, N3MBH (<a href="https://alleghenycreative.com" target="_blank">Allegheny Creative, LLC</a>)</p>
			<p class="pull-right">Powered by: <a href="http://www.svxlink.org/" target="_blank">SvxLink</a> | <a href="https://openrepeater.com" target="_blank">OpenRepeater</a> ver: <?php echo $_SESSION['version_num']; ?></p>
		</footer>
		<?php } ?>

	</div><!--/.fluid-container-->

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->

	<!-- jQuery -->
	<script src="/includes/js/jquery-1.7.2.min.js"></script>
	<!-- jQuery UI -->
	<script src="/includes/js/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="/includes/js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="/includes/js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="/includes/js/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="/includes/js/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="/includes/js/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="/includes/js/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="/includes/js/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="/includes/js/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="/includes/js/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="/includes/js/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="/includes/js/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="/includes/js/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="/includes/js/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="/includes/js/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src='/includes/js/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='/includes/js/jquery.dataTables.min.js'></script>

	<!-- chart libraries start -->
	<script src="/includes/js/excanvas.js"></script>
	<script src="/includes/js/jquery.flot.min.js"></script>
	<script src="/includes/js/jquery.flot.pie.min.js"></script>
	<script src="/includes/js/jquery.flot.stack.js"></script>
	<script src="/includes/js/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="/includes/js/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<script src="/includes/js/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="/includes/js/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="/includes/js/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="/includes/js/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="/includes/js/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="/includes/js/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="/includes/js/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="/includes/js/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="/includes/js/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="/includes/js/jquery.history.js"></script>
	<!-- application script for OpenRepeater -->
	<script src="/includes/js/openrepeater.js"></script>
	
	<?php 
	// Display custom JavaScript if defined by page
	if (isset($customJS)) {
		echo "<!-- custom JavaScript script for current page -->\n";
		$customJS = preg_replace('/\s+/', '', $customJS);
		$jsArray = explode(',',$customJS);
		foreach ($jsArray as $jsfile) {
		  echo "\t<script src='/includes/js/".$jsfile."'></script>\n";
		}
	}

	// Display module admin JavaScript if defined
	if (isset($moduleJS)) {
		echo "<!-- custom JavaScript for module admin page -->\n";
		echo "\t<script src='".$moduleJS."'></script>\n";
	}	
	?>
	
</body>
</html>
