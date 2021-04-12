

<p class="footer_note">Note: This is an early development UI with PHP scripts for allowing updating of settings in the database for testing purposes. These are for new features that have been added in the current backend code, but the main UI will not be available until a future release. This is a work around in the time being for advanced use and/or testing. When running, the settings in these files will be written to the database and will be available on the next rebuild of the configuration.</p>


<?php $current_page_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<form action="../functions/svxlink_update.php" method="post" style="margin:0;">
	<input type="hidden" name="return_url" value="<?php echo $current_page_url; ?>">
	<button type="submit" class="myButton rebuild">Rebuild Config</button>
</form>


</div> <!-- END content_wrap -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php if ( isset($jsCode) ) { ?>
	<script type='text/javascript'>
	$(document).ready(function() {
		var max_fields = 10; //maximum input boxes allowed
		var baseRow = '<?php echo $jsCode; ?>';
		var logicOptions = '<?php echo $jsLogicOptions; ?>';
		var rxOptions = '<?php echo $jsRxOptions; ?>';
		var txOptions = '<?php echo $jsTxOptions; ?>';
		
		var x = 1; //initlal text box count
		$(".add_field_button").click(function(e) {
			var wrapper = $(this).parent('div').attr('id');
			var curPort = $('#'+wrapper).attr('data-port-num');
			var ceilingCount = $('#'+wrapper).attr('data-ceiling-count');
			var realCount = $('#'+wrapper).attr('data-real-count');
			var sectionType = $('#'+wrapper).attr('data-section-type');
			e.preventDefault();
			if(realCount < max_fields) {
				$('#'+wrapper+'DELETE').remove(); // Remove delete field if it exists
				ceilingCount++; realCount++;
				$('#'+wrapper).attr('data-ceiling-count',ceilingCount);
				$('#'+wrapper).attr('data-real-count', realCount);
	
				var newRow = baseRow.replace(/%%PORT%%/g, curPort);
				var newRow = newRow.replace(/%%ROW%%/g, ceilingCount);
	
				switch(sectionType) {
					case 'local':
						var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_LOGIC');
						var newRow = newRow.replace(/%%OPTIONS%%/g, logicOptions);
						break;
					case 'rx':
						var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_RX');
						var newRow = newRow.replace(/%%OPTIONS%%/g, rxOptions);
						break;
					case 'tx':
						var newRow = newRow.replace(/%%ARRAY_NAME%%/g, 'SVXLINK_ADVANCED_TX');
						var newRow = newRow.replace(/%%OPTIONS%%/g, txOptions);
						break;
				} 
	
				$('#'+wrapper).append(newRow); //add row
			}
		});
		
		$(".input_fields_wrap").on("click",".remove_field", function(e){ //user click on remove text
			var wrapper = $(this).closest('.input_fields_wrap').attr('id');
			var curPort = $('#'+wrapper).attr('data-port-num');
			var realCount = $('#'+wrapper).attr('data-real-count');
			var sectionType = $('#'+wrapper).attr('data-section-type');
	
			e.preventDefault();
			realCount--;
			$('#'+wrapper).attr('data-real-count', realCount);
			$(this).parent('div').remove();
			if (realCount == 0) {
				switch(sectionType) {
					case 'local':
						var deleteFieldName = 'SVXLINK_ADVANCED_LOGIC['+curPort+'][delete]'; break;
					case 'rx':
						var deleteFieldName = 'SVXLINK_ADVANCED_RX['+curPort+'][delete]'; break;
					case 'tx':
						var deleteFieldName = 'SVXLINK_ADVANCED_TX['+curPort+'][delete]'; break;
						break;
				} 
	
				var deleteField = '<input type="hidden" id="'+wrapper+'DELETE" name="'+deleteFieldName+'" value="DELETE">';
				$('#'+wrapper).append(deleteField); //add row
			}
		})
	
	});
	</script>
<?php } ?>


</body>
</html>