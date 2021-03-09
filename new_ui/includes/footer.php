        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <?=_('Designed by')?>: Aaron Crawford, N3MBH | <a target="_blank" href="https://openrepeater.com">OpenRepeater</a> <?=_('ver')?>: 3.0.0
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>



      <!-- ORP General Modal Wrapper -->
      <div id="orp_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" id="orp_modal_cancel" class="btn btn-default" data-dismiss="modal"><?=_('Cancel')?></button>
              <button type="button" id="orp_modal_ok" class="btn btn-primary"><?=_('OK')?></button>
            </div>

          </div>
        </div>
      </div>
	  <!-- END Modal -->

    <!-- jQuery -->
    <script src="/includes/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/includes/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/includes/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/includes/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/includes/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/includes/vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/includes/vendors/moment/min/moment.min.js"></script>
    <script src="/includes/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="/includes/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="/includes/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="/includes/vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="/includes/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="/includes/vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="/includes/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="/includes/vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="/includes/vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="/includes/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- jQuery Knob -->
    <script src="/includes/vendors/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- starrr -->
    <script src="/includes/vendors/starrr/dist/starrr.js"></script>



    <!-- Datatables -->
    <script src="/includes/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/includes/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="/includes/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/includes/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="/includes/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/includes/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/includes/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/includes/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="/includes/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="/includes/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/includes/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="/includes/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="/includes/vendors/jszip/dist/jszip.min.js"></script>
    <script src="/includes/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="/includes/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- jQuery Smart Wizard -->
    <script src="/includes/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>

    <!-- PNotify -->
    <script src="/includes/vendors/pnotify/dist/pnotify.js"></script>
    <script src="/includes/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="/includes/vendors/pnotify/dist/pnotify.nonblock.js"></script>

    <!-- Bootstrap Show Password Plugin -->
    <script src="/includes/js/bootstrap-show-password/bootstrap-show-password.min.js"></script>
    <script type="text/javascript">
		$("#password").password('toggle');
		$("#proxy_password").password('toggle');
		$("#password2").password('toggle');

	</script>

    
    <!-- Custom Theme Scripts -->
    <script src="/includes/js/custom.js"></script>
    <script src="/includes/js/orp_modals.js"></script>
	<script>
		var modal_PleaseWaitText = '<?=_('Please Wait')?>';
		$(function() {
			// Rebuild & Restart Modal
			$('#orp_restart_btn').click(function(e) {
				e.preventDefault();
				var modalDetails = {
					modalSize: 'large',
					title: '<i class="fa fa-refresh"></i> <?=_('Rebuild & Restart')?>',
					body: '<h4><?=_('Are You Sure?')?></h4><p><?=_('This will generate new configuration files based on the setting you have updated here. After the files have been created the repeater will be restarted. You should check to make sure that the repeater is currently at idle and that there are no active conversations taking place. Do you still wish to proceed?')?></p>',
					btnOK: '<?=_('Rebuild Now')?>',
					btnOKclass: 'btn-danger',
				};
				orpModalDisplay(modalDetails);
		
				$('#orp_modal_ok').off('click'); // Remove other click events
				$('#orp_modal_ok').click(function() {
					orpModalWaitBar();

					// TEMP SIMULATION OF REBUILD TIME
					setTimeout(function() {
						$('#orp_modal').modal('hide');
						$('#orp_restart_btn').hide();
						new PNotify({
							title: '<?=_('Rebuild Complete')?>',
							text: '<?=_('New configurations files have been created and the controller has restarted')?>',
							type: 'success',
							styling: 'bootstrap3'
						});

					}, 6000);
				});
			});

			// Change Password Modal
			<?
				$change_pw_form = '';
				$change_pw_form .= '<div class="form-group"><label>' . _('Old Password') . '</label>';
				$change_pw_form .= '<div><input type="password" id="password" name="password" value="old_password" class="form-control" data-toggle="password"></div></div>';
				
				$change_pw_form .= '<hr><div class="form-group"><label>' . _('New Password') . '</label>';
				$change_pw_form .= '<div><input type="password" id="password1" name="password1" value="new_password" class="form-control" data-toggle="password"></div></div>';
				
				$change_pw_form .= '<div class="form-group"><label>' . _('Confirm Password') . '</label>';
				$change_pw_form .= '<div><input type="password" id="password2" name="password2" value="new_password" class="form-control" data-toggle="password"></div></div>';
// <input type="password" id="password" name="password" class="form-control" value="1234" data-toggle="password">
				
			?>
			
			$('.change_password').click(function(e) {
				e.preventDefault();
				var modalDetails = {
					modalSize: 'small',
					title: '<i class="fa fa-lock"></i> <?=_('Change Password')?>',
					body: '<?=$change_pw_form?>',
					btnOK: '<?=_('Change')?>',
				};
				orpModalDisplay(modalDetails);
		
				$('#orp_modal_ok').off('click'); // Remove other click events
				$('#orp_modal_ok').click(function() {
					orpModalWaitBar();

					// TEMP SIMULATION OF REBUILD TIME
					setTimeout(function() {
						$('#orp_modal').modal('hide');
						$('#orp_restart_btn').hide();
						new PNotify({
							title: '<?=_('Success')?>',
							text: '<?=_('Your Password has been changed.')?>',
							type: 'success',
							styling: 'bootstrap3'
						});

					}, 2000);
				});
			});

		});
	</script>



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
	?>

	<?php 
	// Display custom Module JavaScript if defined.
	if (isset($moduleJS)) {
		echo "<!-- custom Module JavaScript script -->\n";
		$moduleJS = preg_replace('/\s+/', '', $moduleJS);
		$module_jsArray = explode(',',$moduleJS);
		foreach ($module_jsArray as $module_jsfile) {
		  echo "\t<script src='".$module_jsfile."'></script>\n";
		}
	}
	?>


  </body>
</html>