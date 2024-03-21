<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
session_start();
if ((!isset($_SESSION['username'])) || (!isset($_SESSION['userID']))){
	header('location: index.php'); // If they aren't logged in, send them to login page.
} elseif (!isset($_SESSION['callsign'])) {
	header('location: wizard/index.php'); // If they are logged in, but they haven't set a callsign then send them to setup wizard.
} else { // If they are logged in and have set a callsign, show the page.
// --------------------------------------------------------


function dtmf($inputString) {
	$characters = str_split($inputString);
	$html_code = '<ul class="dtmf-interface js-dtmf-interface">';
	foreach($characters as $currChar) {
		if($currChar=='*') {
			$html_code .= '<li class="star">' . $currChar . '</li>';	
		} else if($currChar=='+' || $currChar=='&') {
			$html_code .= '<li class="non-btn">' . $currChar . '</li>';	
		} else {
			$html_code .= '<li>' . $currChar . '</li>';	
		}
	}
	$html_code .= '</ul>';
	return $html_code;
}	
?>


<?php
$customJS = 'page-dtmf.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-dtmf.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
$portList = $Database->get_ports();
$macroList = $Database->get_macros();
$ModulesClass = new Modules();
$moduleList = $ModulesClass->getModulesJSON();
?>



<?php
	$portOpitonList = '';
	$base_path = '/usr/share/svxlink/orp_pty/';
	foreach ($portList as $key => $val) {
		if ($val['portEnabled'] == 1) {
			switch ($val['portDuplex']) {
				case 'full':
					$curOptionVal = $base_path . 'ORP_FullDuplexLogic_Port' . $val['portNum'] . '/dtmf_ctrl';
					break;
				case 'half':
					$curOptionVal = $base_path . 'ORP_HalfDuplexLogic_Port' . $val['portNum'] . '/dtmf_ctrl';
					break;
			}
			$curOptionName = 'PORT ' . $val['portNum'] . ': ' . $val['portLabel'];
			$portOpitonList .= '<option value="' . $curOptionVal . '">' . $curOptionName . '</option>';
		}
	}	
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-tty"></i> <?=_('DTMF Reference')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

			<div class="alert alert-warning">
			<h4><i class="fa fa-warning"></i> Warning!</h4> This page is still in early development. So, there may be things that don't function as one might expect. 
			</div>

<?= '<pre>' . $portOpitonList . '</pre>'?>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Commands')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" id="dialPadBtn" class="btn btn-success"><i class="fa fa-th"></i> <?=_('Dial Pad')?></button>
                      <button type="button" class="btn btn-success" onclick="window.print();return false;"><i class="fa fa-print"></i> <?=_('Print')?></button>
                                          </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="false">


<? ################################################################################ ?>
<? # FORCE ID SECTION ?>

                      <div class="panel">
                        <a class="panel-heading" role="tab" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
						  <div class="col-md-12 col-sm-12 col-xs-12">
		                      <h4 class="panel-title dtmf-title"><?php echo dtmf('*'); ?><?=_('Force ID')?></h4>
						  </div>
						  <div class="clearfix"></div>
                        </a>
                      </div>

<? ################################################################################ ?>
<? # REMOTE DTMF DISABLE SECTION ?>

					  <?php $tempDisableCode = '1234'; ?>
					  <?php if(isset($tempDisableCode)) { ?>
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
						  <div class="col-md-10 col-sm-10 col-xs-10">
		                      <h4 class="panel-title dtmf-title"><?=_('Remote DMTF Disable')?></h4>
						  </div>
						  <div class="col-md-2 col-sm-2 col-xs-2 right">
		                    <span class="right">
		                    </span> 
						  </div>
						  <div class="clearfix"></div>
                        </a>
                        <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                          <div class="panel-body">
						  	<p><?=_('You have chosen to enable the ability to remotely disable the transmitter via DTMF commands. This is useful for control operators to stop system abuse or to simply make the system inactive. Note that the pin code that you selected is part of the full codes below.')?></p>
							<div class="spacer height20"></div>
							<?php
							$cur_mod_html = '';
							
							$cur_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf( $tempDisableCode . '+0#' ) . '</div>';
							$cur_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p><span style="color:red;">' . _('Disable Transmitter ') . '</span></p></div>';
							$cur_mod_html .= '<div class="clearfix"></div>';
							
							$cur_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf( $tempDisableCode . '+1#' ) . '</div>';
							$cur_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p><span style="color:green;">' . _('Enable Transmitter') . '</span></p></div>';
							$cur_mod_html .= '<div class="clearfix"></div>';
							
							echo $cur_mod_html;	
							?>
							<div class="spacer height20"></div>
						  	<p><?=_('NOTE: If a module is running while you wish to disable the transmitter, you must first disable the module OR force the disable command by prefixing with a star (*). So, the disable command would become:')?> <strong>* + <?=$tempDisableCode?> + 0#</strong></p>
							<div class="spacer height20"></div>
                          </div>
                        </div>
                      </div>
					  <?php } ?>

<? ################################################################################ ?>
<? # MACROS SECTION ?>

					  <?php if(isset($macroList)) { ?>
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="heading2" data-toggle="collapse" data-parent="#accordion" href="#dtmf_macros" aria-expanded="false" aria-controls="dtmf_macros">
						  <div class="col-md-10 col-sm-10 col-xs-10">
		                      <h4 class="panel-title dtmf-title">Macros</h4>
						  </div>
						  <div class="col-md-2 col-sm-2 col-xs-2 right">
		                    <span class="right">
		                    </span> 
						  </div>
						  <div class="clearfix"></div>
                        </a>
                        <div id="dtmf_macros" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                          <div class="panel-body">
							<?php
								$cur_mod_html = '';
								foreach($macroList as $cur_macro) {
									if ($cur_macro['macroEnabled'] == '1') {
										$cur_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf( 'D' . $cur_macro['macroNum'] . '#' ) . '</div>';
										$cur_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p>' . $cur_macro['macroLabel'] . '</p></div>';
										$cur_mod_html .= '<div class="clearfix"></div>';
									}
								}
								$cur_mod_html .= '<div class="spacer height20"></div>';
								echo $cur_mod_html;	
							?>

                          </div>
                        </div>
                      </div>
					  <?php } ?>


<?php 
echo '<pre>';
print_r($macroList);
echo '</pre>';
?>

<?php 
echo '<pre>';
print_r($portList);
echo '</pre>';
?>



<? ################################################################################ ?>
<? # HELP MODULE SECTION ?>

					  <?php
					  # HELP MODULE
						if($fakeModules['1']['moduleEnabled'] == '1') {
							$help_mod_html = '	
							  <div class="panel">
							    <a class="panel-heading collapsed" role="tab" id="Help" data-toggle="collapse" data-parent="#accordion" href="#collapse-Help" aria-expanded="false" aria-controls="collapse-Help">
								  <div class="col-md-10 col-sm-10 col-xs-10">
							          <h4 class="panel-title dtmf-title">' . dtmf('0#') . ' ' . _('Help Module') .'</h4>
								  </div>
								  <div class="col-md-2 col-sm-2 col-xs-2 right">
							        <span class="right">
							        </span> 
								  </div>
								  <div class="clearfix"></div>
							    </a>
							    <div id="collapse-Help" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Help">
							      <div class="panel-body">';
						
									$help_mod_html .= '	
									  <div class="col-md-12 col-sm-12 col-xs-12">
									  	<p>Pressing 0# will enable the Help module.</p>
									  </div>';
						
									$help_mod_html .= '	
									  <div class="col-md-12 col-sm-12 col-xs-12">
									  	<h4>Sub Commands:</h4>
									  </div>';
							
									$help_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf('0#') . '</div>';
									$help_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p>' . _('Overview of the Help Module') . '</p></div>';
									$help_mod_html .= '<div class="clearfix"></div>';
									$help_mod_html .= '<div class="spacer height10 visible-xs-block"></div>';

									foreach($fakeModules as $cur_mod) {
										if ( $cur_mod['moduleEnabled'] == '1' && $cur_mod['svxlinkID'] > 0 ) {
											$help_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf($cur_mod['svxlinkID'] . '#') . '</div>';
											$help_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p>' . _('Help on') . ' ' . $cur_mod['displayName'] . ' ' . _('Module') . '</p></div>';
											$help_mod_html .= '<div class="clearfix"></div>';
											$help_mod_html .= '<div class="spacer height10 visible-xs-block"></div>';
										}
								
									}										

									$help_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf('#') . '</div>';
									$help_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p>' . _('Exit Help') . '</p></div>';
									$help_mod_html .= '<div class="clearfix"></div>';
									$help_mod_html .= '<div class="spacer height10 visible-xs-block"></div>';

									$help_mod_html .= '<div class="spacer height20"></div>';
													
									$help_mod_html .= '
							      </div>
							    </div>
							  </div>';
						
							echo $help_mod_html;
						}
					  ?>

<? ################################################################################ ?>
<? # LOOP THROUGH MODULE SECTIONS ?>

					  <?php
					  $moduleList = json_decode($moduleList, true);

					  foreach($moduleList as $cur_mod) { 
						if($cur_mod['moduleEnabled'] == '1' && $cur_mod['svxlinkID'] > 0 && $cur_mod['dtmf'] == true) {
							$cur_mod_html = '	
							  <div class="panel">
							    <a class="panel-heading collapsed" role="tab" id="' . $cur_mod['svxlinkName'] . '" data-toggle="collapse" data-parent="#accordion" href="#collapse-' . $cur_mod['svxlinkName'] . '" aria-expanded="false" aria-controls="collapse-' . $cur_mod['svxlinkName'] . '">
								  <div class="col-md-10 col-sm-10 col-xs-10">
							          <h4 class="panel-title dtmf-title">' . dtmf($cur_mod['svxlinkID'] . '#') . $cur_mod['displayName'] . ' ' . _('Module') .'</h4>
								  </div>
								  <div class="col-md-2 col-sm-2 col-xs-2 right">
							        <span class="right">
							        </span> 
								  </div>
								  <div class="clearfix"></div>
							    </a>
							    <div id="collapse-' . $cur_mod['svxlinkName'] . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="' . $cur_mod['svxlinkName'] . '">
							      <div class="panel-body">';
						
									$cur_mod_html .= '	
									  <div class="col-md-12 col-sm-12 col-xs-12">
									  	<p>Pressing ' . $cur_mod['svxlinkID'] . '# will enable the ' . $cur_mod['displayName'] . ' module.</p>
									  </div>';
						
									if (isset($cur_mod['tempSubCommands'])) {
										$cur_mod_html .= '	
										  <div class="col-md-12 col-sm-12 col-xs-12">
										  	<h4>Sub Commands:</h4>
										  </div>';
								
										foreach($cur_mod['tempSubCommands'] as $cur_sub_cmd => $cur_sub_cmd_desc) {
											if (substr($cur_sub_cmd, 0, 7) == 'divider') {
												$cur_mod_html .= '<div class="col-md-12 col-sm-12 col-xs-12"><hr></div>';

											} else if (substr($cur_sub_cmd, 0, 7) == 'heading') {
												$cur_mod_html .= '<div class="col-md-12 col-sm-12 col-xs-12"><h5>' . $cur_sub_cmd_desc . '</h5></div>';

											} else {
												$cur_mod_html .= '<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 col-print-5">' . dtmf($cur_sub_cmd) . '</div>';
												$cur_mod_html .= '<div class="col-lg-8 col-md-7 col-sm-6 col-xs-12 col-print-7"><p>' . $cur_sub_cmd_desc . '</p></div>';
											}
											$cur_mod_html .= '<div class="clearfix"></div>';
											$cur_mod_html .= '<div class="spacer height10 visible-xs-block"></div>';
										}
								
										$cur_mod_html .= '<div class="spacer height20"></div>';
										
									}
						
							
									$cur_mod_html .= '
							      </div>
							    </div>
							  </div>';
						
							echo $cur_mod_html;
						}
					
					  }
					  ?>


<?php 
/*
echo '<pre>';
print_r($moduleList);
echo '</pre>';
*/
?>


<? ################################################################################ ?>


                    </div>
                    <!-- end of accordion -->


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->



<script>
	var portOpitonList = '<?= $portOpitonList ?>';
	console.log(portOpitonList);
</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>