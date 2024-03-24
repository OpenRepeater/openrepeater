        <!-- footer content -->
        <footer>
          <div class="pull-right">
            <?=_('Designed by')?>: Aaron Crawford, N3MBH | <a target="_blank" href="https://openrepeater.com">OpenRepeater</a> <?=_('ver') . ': ' . $_SESSION['version_num'] ?>
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
              <button type="button" id="orp_modal_close_x" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
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

      <script>
        var modal_DefaultOK = '<?=_('OK')?>';
        var modal_DefaultCanel = '<?=_('Cancel')?>';
      </script>
	  <!-- END Modal -->

    <!-- jQuery -->
<!--     <script src="/includes/vendors/jquery/dist/jquery.min.js"></script> -->
    <script src="/includes/js/libraries/jquery/jquery-3.6.1.min.js"></script>
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
    <!-- Luxon DateTime Library, and variables -->
	<script src="/includes/js/libraries/luxon/luxon.min.js"></script>
	<script>
	var DateTime = luxon.DateTime;
	var phpTimezone = '<?= date_default_timezone_get() ?>';
	var phpLocal = 'en-US';
	</script>
    <!-- bootstrap-daterangepicker -->
	<!-- DEPRECIATED -->
	<!--
    <script src="/includes/vendors/moment/min/moment.min.js"></script>
    <script src="/includes/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
	-->
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
    <script src="/includes/vendors/datatables.net-select/js/dataTables.select.min.js"></script>
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
			/* *********************************************** */
			// Rebuild & Restart Modal
			/* *********************************************** */
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
					$.ajax({
						type: 'POST',
						url: '../../functions/ajax_svxlink_update.php',
						success: function(result) {
							var response = $.parseJSON(result); // create an object with the key of the array

							if (response.status == 'success' && response.svxlink == 'active') {
								$('#orp_modal').modal('hide');
								rebuildDeactive();
								
								//Display Message
								orpNotify('success', '<?=_('Rebuild Complete')?>', '<?=_('New configurations files have been created and the controller has restarted.')?>');
							}

							if (response.status == 'success' && response.svxlink == 'inactive') {
								$('#orp_modal').modal('hide');
								rebuildDeactive();
								
								//Display Message
								orpNotify('info', '<?=_('SVXLink Not Running')?>', '<?=_('New configurations files have been created, but SVXLink could not be restarted.')?>');
							}

							if (response.status == 'not_logged_in') {
								$('#orp_modal').modal('hide');
								rebuildActive();
								
								//Display Message
								orpNotify('error', '<?=_('Not Logged In')?>', '<?=_('Could not build your configuration because your login timed out. Please login and try again.')?>');
							}

						},
						error: function(jqXHR, textStatus, errorThrown) {
							$('#orp_modal').modal('hide');
							rebuildActive();

							//Display Message
							orpNotify('error', '<?=_('Error')?>', '<?=_('There was an error communicating with the controller. Please try again.')?>');

							console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
						}
					});
				});
			});

			/* *********************************************** */
			// Change Password Modal
			/* *********************************************** */
			<?php
				$change_pw_form = '';
				$change_pw_form .= '<div id="passwordMsg"class="alert alert-info" role="alert"></div>';
				$change_pw_form .= '<div class="form-group has-feedback"><input type="password" id="oldPassword" name="oldPassword" class="form-control" data-toggle="password" placeholder="' . _('Old Password') . '"><span id="oldPasswordStatus" class="fa fa-check form-control-feedback right hidden" aria-hidden="true"></span></div>';
				$change_pw_form .= '<hr><div class="form-group has-feedback"><input type="password" id="password1" name="password1" class="form-control" data-toggle="password" placeholder="' . _('New Password') . '"><span id="password1Status" class="fa fa-check form-control-feedback right hidden" aria-hidden="true"></span></div>';
				$change_pw_form .= '<div class="form-group has-feedback"><input type="password" id="password2" name="password2" class="form-control" data-toggle="password" placeholder="' . _('Confirm Password') . '"><span id="password2Status" class="fa fa-check form-control-feedback right hidden" aria-hidden="true"></span></div>';

				$change_pw_form .= '<div id="pw_hint"><h5>'._('Password must meet the following requirements').':</h5><ul>';
				$change_pw_form .= '<li id="letter" class="invalid"><strong>'._('At least one letter').'</strong></li>';
				$change_pw_form .= '<li id="capital" class="invalid"><strong>'._('At least one capital letter').'</strong></li>';
				$change_pw_form .= '<li id="number" class="invalid"><strong>'._('At least one number').'</strong></li>';
				$change_pw_form .= '<li id="length" class="invalid"><strong>'._('Be at least 8 characters').'</strong></li>';
				$change_pw_form .= '</ul></div>';
			?>

			
			function validatePW () {
				var pswd1 = $('#password1').val();
				var pswdOld = $('#oldPassword').val();
				var errorLevel = 0;
				$('#passwordMsg').slideUp(500).html('');
				// Validate the length
				if ( pswd1.length < 8 ) {
				    $('#length').removeClass('valid').addClass('invalid');
					errorLevel++;
				} else {
				    $('#length').removeClass('invalid').addClass('valid');
				}
				// Validate letter
				if ( pswd1.match(/[A-z]/) ) {
				    $('#letter').removeClass('invalid').addClass('valid');
				} else {
				    $('#letter').removeClass('valid').addClass('invalid');
					errorLevel++;
				}
				// Validate capital letter
				if ( pswd1.match(/[A-Z]/) ) {
				    $('#capital').removeClass('invalid').addClass('valid');
				} else {
				    $('#capital').removeClass('valid').addClass('invalid');
					errorLevel++;
				}				
				// Validate number
				if ( pswd1.match(/\d/) ) {
				    $('#number').removeClass('invalid').addClass('valid');
				} else {
				    $('#number').removeClass('valid').addClass('invalid');
					errorLevel++;
				}
				if (errorLevel == 0) {
					// Lastly validate New Password doesnt matched Old Password, case insensitive
					if ( pswd1.toUpperCase() === pswdOld.toUpperCase() ) {
						$('#passwordMsg').html('<?=_('New Password cannot be like Old Password.')?>').slideDown(500);
						$('#password1').val('');
						return false;
					} else {
						$('#password2').slideDown(500); // Show Confirm PW Field
						$('#pw_hint').slideUp(500); // Hide PW Hints
	
						$('#password1Status').removeClass('hidden');
						setTimeout(function() {
							$('#password1').removeClass('validPW');
						}, 5000);
						return true;		
					}
				} else {
					$('#password2').slideUp(500); // Hide Confirm PW Field
					$('#password1Status').addClass('hidden');
					$('#orp_modal_ok').prop('disabled', true); // Disable OK Button
					return false;
				}
			}


			function passwordsMatch () {
				var pswd1 = $('#password1').val();
				var pswd2 = $('#password2').val();
				if (pswd1 === pswd2) {
					$('#password2Status').removeClass('hidden');
					return true; 
				} else { 
					$('#password2Status').addClass('hidden');
					$('#orp_modal_ok').prop('disabled', true); // Disable OK Button
					return false;
				}
			}


			
			$('.change_password').click(function(e) {
				e.preventDefault();
				var modalDetails = {
					modalSize: 'small',
					title: '<i class="fa fa-lock"></i> <?=_('Change Password')?>',
					body: '<?=$change_pw_form?>',
					btnOK: '<?=_('Change')?>',
				};
				orpModalDisplay(modalDetails);		
// 				$("#password1").password('toggle');
				$('#passwordMsg').hide(); // Hide Error Section
				$('#password2').hide(); // Hide Confirm PW Field
				$('#orp_modal_ok').prop('disabled', true); // Disable OK Button

				$('#oldPassword').blur(function() {
					$('#passwordMsg').slideUp(500).html('');
					$.ajax({
						url:'/functions/ajax_user_requests.php',
						type:'post',
						data: {'validatePassword': JSON.stringify( { existingPassword: $('#oldPassword').val() } )},
						success: function(response){ // success here means successful communication, not successful results.
							var validate = $.parseJSON(response);
							if(validate.result == 'success') {
								$('#oldPasswordStatus').removeClass('hidden');
							}
							if(validate.result == 'error') {
								$('#passwordMsg').html(validate.message).slideDown(500);
								$('#oldPassword').val('').focus();
								$('#oldPasswordStatus').addClass('hidden');
							}
						}
					});
				});


				$('#password1').keyup(function() {
					validatePW();
				}).focus(function() {
					validatePW();
					$('#pw_hint').slideDown(500);
				}).blur(function() {
					$('#pw_hint').slideUp(500);
				});


				$('#password2').keyup(function() {
					if ( passwordsMatch() ) {
						if ( validatePW() ) {
							$('#orp_modal_ok').prop('disabled', false); // Re-enable OK Button
						}
					}
				}).focus(function() {
					if ( passwordsMatch() ) {
						if ( validatePW() ) {
							$('#orp_modal_ok').prop('disabled', false); // Re-enable OK Button
						}
					}
				}).blur(function() {
// 					console.log('hide');
				});

		
				$('#orp_modal_ok').off('click'); // Remove other click events
				$('#orp_modal_ok').click(function() {
					dataObj = { existingPassword: $('#oldPassword').val(), newPassword: $('#password1').val(), confirmPassword: $('#password2').val() };
					orpModalWaitBar();
					$.ajax({
						url:'/functions/ajax_user_requests.php',
						type:'post',
						data: {'changePassword': JSON.stringify(dataObj)},
						success: function(response){ // success here means successful communication, not successful results.
							var pwchange = $.parseJSON(response);
							if(pwchange.result == 'success') {
								$('#orp_modal').modal('hide');
								orpNotify('success', '<?=_('Success')?>', pwchange.message);
							}
							if(pwchange.result == 'error') {
								$('#orp_modal').modal('hide');
								orpNotify('error', '<?=_('Error')?>', pwchange.message);
							}
						}
					});
				});
			});



			/* *********************************************** */
			// Logout Request
			/* *********************************************** */

			$('.logoutORP').click(function(e) {
				e.preventDefault();
				$.ajax({
					url:'../functions/ajax_user_requests.php',
					type:'post',
					data: { 'logout': '' },
					success: function(response){ // success here means successful communication, not successful login.
						var logout = $.parseJSON(response);
						if(logout.result == 'success') {
							window.location = logout.login_url;
						} else {
							// Invalid response from controller
							console.log('Invalid response from controller');
						}
	
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
					}
				});
			});



		});
	</script>

	<script id="loaderTemplate" type = "text/template">
		<tbody><tr><td style="display:none;">
			<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 2712 1723" xmlns="http://www.w3.org/2000/svg">
				<path id="tower" d="m307.694 265.156c-4.47-2.054-7.574-6.572-7.574-11.814 0-7.177 5.818-12.995 12.995-12.995s12.995 5.818 12.995 12.995c0 5.273-3.14 9.813-7.652 11.85l18.042 44.873 5.32 13.223 22.416 55.713c-13.416-5.532-28.925-7.243-45.083-5.006-16.925 2.345-34.276 8.977-50.114 19.79l20.103-66.843 4.434-14.743zm-17.801 104.83 5.532-17.929c7.42-2.373 14.884-3.95 22.259-4.692 7.224-.729 14.33-.652 21.196.17l6.523 15.372c-8.569-1.117-17.604-1.146-26.862-.01-9.505 1.165-19.149 3.552-28.648 7.089zm14.482-46.928c3.977-.718 7.943-1.23 11.883-1.484 3.9-.252 7.765-.249 11.58-.052l5.897 13.898c-5.452-.367-11.037-.333-16.703.145-5.757.485-11.568 1.423-17.377 2.783zm10.15-32.895 7.983 18.806c-2.297-.034-4.612-.002-6.938.106-2.343.112-4.695.3-7.052.555z" transform="matrix(9.4782992493 0 0 9.4782992493 -1611.79688757 -2010.15877943)"/>
				
				<g id="wavesAll" transform="matrix(1.0000008 0 0 1.0000008 -7719.00594 -3279.622295)">
					<path id="waves1" class="waves" d="m9321.42 3605.39c5.48 20.79 8.42 42.63 8.42 65.17 0 22.62-2.98 44.54-8.51 65.43-8.22 31.02 10.29 62.86 41.31 71.08 31.01 8.21 62.86-10.29 71.08-41.31 8.05-30.39 12.38-62.29 12.38-95.2 0-32.78-4.29-64.55-12.26-94.8-8.18-31.02-40-49.57-71.03-41.39-31.02 8.17-49.57 40-41.39 71.02zm-492.82 130.36c-5.49-20.81-8.45-42.66-8.45-65.19 0-22.67 2.99-44.61 8.54-65.53 8.21-31.01-10.29-62.86-41.31-71.08-31.01-8.21-62.86 10.29-71.08 41.31-8.06 30.42-12.41 62.33-12.41 95.3 0 32.79 4.31 64.59 12.3 94.87 8.19 31.02 40.03 49.56 71.05 41.37s49.55-40.03 41.36-71.05z"/>
					
					<path id="waves2" class="waves" d="m9624.12 3816.32c12.33-46.54 18.96-95.37 18.96-145.76 0-50.27-6.59-98.99-18.85-145.4-8.2-31.02-40.03-49.55-71.05-41.36-31.02 8.2-49.55 40.03-41.36 71.05 9.76 36.94 15 75.71 15 115.71 0 40.1-5.27 78.96-15.08 115.99-8.22 31.01 10.29 62.86 41.3 71.08 31.02 8.21 62.87-10.3 71.08-41.31zm-1098.26-291.43c-12.31 46.52-18.94 95.31-18.94 145.67 0 50.22 6.58 98.87 18.83 145.25 8.18 31.02 40.02 49.55 71.04 41.37 31.02-8.19 49.55-40.03 41.37-71.05-9.74-36.9-14.98-75.61-14.98-115.57 0-40.09 5.28-78.92 15.07-115.93 8.21-31.02-10.31-62.86-41.33-71.07-31.01-8.2-62.86 10.32-71.06 41.33z"/>
					
					<path id="waves3" class="waves" d="m9702.28 3504.3c14.02 53.06 21.52 108.77 21.52 166.26 0 57.58-7.53 113.4-21.61 166.55-8.22 31.01 10.29 62.86 41.3 71.08 31.02 8.22 62.87-10.29 71.08-41.3 16.61-62.66 25.49-128.45 25.49-196.33 0-67.76-8.85-133.42-25.38-195.97-8.2-31.02-40.03-49.54-71.05-41.35-31.02 8.2-49.55 40.04-41.35 71.06zm-1254.62 332.25c-13.98-52.99-21.46-108.61-21.46-165.99 0-57.5 7.5-113.23 21.55-166.31 8.21-31.02-10.31-62.86-41.33-71.07-31.01-8.21-62.86 10.31-71.06 41.32-16.56 62.58-25.42 128.27-25.42 196.06 0 67.62 8.83 133.17 25.3 195.63 8.18 31.02 40 49.57 71.02 41.39 31.03-8.18 49.58-40.01 41.4-71.03z"/>
					
					<path id="waves4" class="waves" d="m10005 3917.44c20.9-78.79 32-161.54 32-246.88 0-85.23-11.1-167.85-31.9-246.54-8.2-31.02-40.04-49.54-71.06-41.34s-49.54 40.04-41.34 71.06c18.3 69.2 28.08 141.86 28.08 216.82 0 75.05-9.82 147.83-28.17 217.11-8.21 31.02 10.3 62.87 41.31 71.08s62.86-10.3 71.08-41.31zm-1860.13-493.3c-20.78 78.64-31.91 161.23-31.91 246.42 0 85.03 11.1 167.48 31.81 246.01 8.18 31.02 40.01 49.56 71.03 41.38 31.03-8.19 49.57-40.02 41.39-71.04-18.22-69.06-27.97-141.56-27.97-216.35 0-74.92 9.78-147.55 28.05-216.71 8.2-31.02-10.32-62.86-41.34-71.05-31.02-8.2-62.86 10.32-71.06 41.34z"/>
					
					<path id="waves5" class="waves" d="m10083.1 3403.18c22.6 85.34 34.7 174.94 34.7 267.38 0 92.54-12.1 182.25-34.8 267.68-8.2 31.02 10.3 62.87 41.3 71.08 31.1 8.21 62.9-10.3 71.1-41.31 25.2-94.94 38.6-194.62 38.6-297.45 0-102.71-13.4-202.28-38.5-297.1-8.2-31.01-40-49.54-71-41.34-31.1 8.2-49.6 40.04-41.4 71.06zm-2016.37 534.11c-22.47-85.13-34.48-174.54-34.48-266.73 0-92.35 12.05-181.85 34.57-267.1 8.19-31.02-10.34-62.85-41.36-71.05-31.02-8.19-62.85 10.34-71.05 41.36-25.02 94.72-38.42 194.18-38.42 296.79 0 102.45 13.36 201.79 38.32 296.39 8.19 31.02 40.02 49.56 71.04 41.38 31.02-8.19 49.56-40.02 41.38-71.04z"/>
					
					<path id="waves6" class="waves" d="m7763.94 3325.66c-29.27 110.8-44.94 227.16-44.94 347.18 0 119.86 15.64 236.08 44.85 346.77 8.18 31.03 40.01 49.57 71.04 41.38 31.02-8.18 49.56-40.02 41.37-71.04-26.7-101.22-41-207.51-41-317.11 0-109.76 14.32-216.16 41.09-317.49 8.19-31.02-10.34-62.86-41.36-71.05s-62.86 10.34-71.05 41.36zm2621.86 692.9c29.4-111.05 45.2-227.69 45.2-348 0-120.2-15.7-236.72-45.1-347.67-8.2-31.01-40-49.53-71-41.32-31.1 8.21-49.6 40.05-41.4 71.07 26.9 101.45 41.2 208 41.2 317.92 0 110.02-14.4 216.68-41.3 318.23-8.2 31.02 10.3 62.87 41.4 71.08 31 8.22 62.8-10.29 71-41.31z"/>
				</g>
			</svg>
		</td></tr></tbody>
	</script>

	<script>
		function exitOverlay(text) {
			setTimeout(function(){ // after slight delay, display overlay.
			    $("<table id='orp_loader_overlay'></table>").css({
			        "position": "fixed",
			        "top": 0,
			        "left": 0,
			        "width": "100%",
			        "height": "100%",
			        "background-color": "rgba(0,0,0,.9)",
			        "z-index": 10000,
			        "vertical-align": "middle",
			        "text-align": "center",
			        "color": "#fff",
			        "font-size": "30px",
			        "font-weight": "bold",
			        "cursor": "wait"
			    }).appendTo("body");
	
				var $loaderTemplate = $('#loaderTemplate').html();
			    $('#orp_loader_overlay').append($loaderTemplate);
			    $('#orp_loader_overlay td').fadeIn(5000);

			}, 1000);
		}
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

    <script>
      var notify_LoggedOutTitle = '<?=_('Logged Out')?>';
      var notify_LoggedOutText = '<?=_('There was an error saving your settings because you have been logged out. Please log back in and try again.')?>';
    </script>

  </body>
</html>