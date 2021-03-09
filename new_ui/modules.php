<?php include('includes/fakeDB.php'); ?>


<?php 
$customJS = 'page-modules.js, jquery-ui.min.js, jquery.ui.touch-punch.min.js, dropzone.js, upload-file.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-modules.css, upload-file.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('Modules')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Installed Modules')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success upload_file" data-upload-type="module"><i class="fa fa-upload"></i> <?=_('Upload Module')?></button>
                      <a class="btn btn-success" href="https://openrepeater.com/downloads/modules" target="_blank"><i class="fa fa-search"></i> <?=_('Find Modules on OpenRepeater.com')?></a>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

					<?php 
					// 	print_r($fakeModules);
						$nonSortableModules = '<div id="moduleList">';
						$sortableModules = '<div id="moduleListSort">';
						
						foreach($fakeModules as $cur_mod) { 
							if ($cur_mod['moduleEnabled'] == 1) {
								$moduleClass = '';
								$moduleEnabled = ' checked';
							} else {
								$moduleClass = ' deactive';
								$moduleEnabled = '';
							}

							$curModHTML = '
							<div class="moduleRow' . $moduleClass . '" data-module-id="' . $cur_mod['moduleKey'] . '" data-svxlink-id="' . $cur_mod['svxlinkID'] . '">
								<span class="largeDigit">' . $cur_mod['svxlinkID'] . '</span>

								<div class="col-md-4 col-sm-4 col-xs-12">
									<h3><i class="fa fa-plug"></i> ' . $cur_mod['displayName'] . '</h3>
									<!-- <h4 class="svxlinkID">' . $cur_mod['svxlinkID'] . '#</h4> -->';
									if (!isset($cur_mod['tempType'])) { $cur_mod['tempType'] = 'addon';	}

									switch ($cur_mod['tempType']) {
									    case 'core':
											$curModHTML .= '<span class="badge bg-orange">'._('Core').'</span>';
									        break;
									    case 'daemon':
											$curModHTML .= '<span class="badge bg-red">'._('Daemon').'</span>';
									        break;
									    default:
											$curModHTML .= '<span class="badge bg-green">'._('Add-on').'</span>';
									}										
										
										

									$curModHTML .= '
									<div style="clear: both;">
										<input type="checkbox" class="js-switch modActive"'.$moduleEnabled.' /> 
										<div class="btn-group btn-group-sm">';
											if (isset($cur_mod['tempSettings'])) {
												$curModHTML .= '<a class="btn btn-default" href="/modules/' . trim($cur_mod['svxlinkName']) . '/settings.php"><i class="fa fa-cog"></i> '._('Settings').'</a>';
											}
											if (isset($cur_mod['tempDTMF'])) {
												$curModHTML .= '<a class="btn btn-default" href="/dtmf.php#' . $cur_mod['svxlinkName'] . '"><i class="fa fa-tty"></i> '._('DTMF').'</a>';
											}
										$curModHTML .= '
										</div>';

										// Delete options is not avaialbe for core modules
										if ( empty($cur_mod['tempType']) ) {
											$curModHTML .= '
											<div class="btn-group btn-group-sm delete-grp">
												<button class="btn btn-danger" type="button"><i class="fa fa-trash"></i> '._('Delete').'</button>
											</div>';
										}

									$curModHTML .= '
									</div>
								</div>
								<div class="col-md-8 col-sm-8 col-xs-12">
									' . $cur_mod['tempDesc'] . '
								</div>
								<div class="clearfix"></div>
							</div>';
					
					
							if ($cur_mod['moduleKey'] == '1' || $cur_mod['svxlinkID'] =='' ) {
								$nonSortableModules .= $curModHTML;
							} else {
								$sortableModules .= $curModHTML;
							}
						}
						$nonSortableModules .= '</div>';
						$sortableModules .= '</div>';
						
						echo $nonSortableModules;
						echo $sortableModules;
					?>

                  </div>
                </div>
              </div>
            </div>
            
            
          </div>
        </div>
        <!-- /page content -->

<!-- Upload Dialog Modal -->
<script>
	var modal_UploadTitle = '<?=_('Upload New Module')?>';
	var modal_dzDefaultText = '<?=_('Drag files here or click to browse for files.')?>';
	var modal_dzCustomDesc = '<?=_('Upload an OpenRepeater module to add additional functionality. These are specially packaged SVXLink Modules along with the required sound files and user interface components zipped up in an easy to install file. Modules must be compressed with a ".zip" extension. Upon upload the modules will be unzipped, verified, and installed. You will then need to active the module, configure any settings (if applicable), and rebuild the configuration.')?>';
	var uploadSuccessTitle = '<?=_('Module Installed')?>';
	var uploadSuccessText = '<?=_('New module was successfully uploaded and installed. You must activate first to use it.')?>';
</script>

<?php include('includes/footer.php'); ?>