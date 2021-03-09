<?php include('includes/fakeDB.php'); ?>


<?php
// $customJS = 'page-ports.js'; // 'file1.js, file2.js, ... '
// $customCSS = 'page-ports.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

<?php
# Get port info once and put array.
foreach ($fakePorts as $portKey => $portDetails) {
	$portListArray[$portDetails['portNum']] = $portDetails['portLabel'];
}
?>

<?php
# Get module info once and put array.
foreach ($fakeModules as $moduleKey => $moduleDetails) {
	if ( intval($moduleDetails['svxlinkID']) > 0 ) {
		$moduleListArray[$moduleKey] = $moduleDetails['displayName'];
	}
}
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-edit"></i> <?=_('DTMF Macros	')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Coming Soon')?></h4></div>
                  
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

					<p><?=_('NOTE: You can create macros for all available ports and installed modules, but if a port or module is disabled at the time of rebuild then those macros will not be available until the chosen port and module have been reenabled and another rebuild completed.')?></p>
						
                    <table id="macro-table-responsive" class="table table-striped dt-responsive" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th><?=_('Enabled')?></th>
                          <th><?=_('Num')?></th>
                          <th><?=_('Description')?></th>
                          <th><?=_('Module')?></th>
                          <th><?=_('Macro String')?></th>
                          <th><?=_('Ports')?></th>
                        </tr>
                      </thead>
                      <tbody>
						<?php foreach ($fakeMacroArray as $curMacroKey => $curMacroSubArr) { ?>
                        <tr>
                          <td>
	                          <?php
		                          if ($curMacroSubArr['macroEnabled'] == 1) { $curMacroEnabled = ' checked'; } else { $curMacroEnabled = ''; }
		                      ?>
	                          <input id="remoteDisable" type="checkbox" class="js-switch"<?=$curMacroEnabled?>>
	                      </td>
                          
                          <td>
							  <?php 
								$currentMacroSel = '';
								$macroNum = $curMacroSubArr['macroNum'];
								for ($m = 1 ; $m < 100; $m++) { 
									if($m == $macroNum) {
										$currentMacroSel .= '<option value="'.$m.'" selected>D'.$m.'</option>';
									} else {
										$currentMacroSel .= '<option value="'.$m.'">D'.$m.'</option>';
									}
								}
							  ?>
	                          <select id="rxCTCSS" class="form-control">
							  	<?=$currentMacroSel?>
	                          </select>
                          </td>

                          <td>
						  	  <textarea class="form-control" placeholder="A macro that does something"><?=$curMacroSubArr['macroLabel']?></textarea>
                          </td>

                          <td>
							<?php
								$curModOptions = '';
								foreach($moduleListArray as $modKey => $modLabel) {
								 	if ($curMacroSubArr['macroModuleKey'] == $modKey) {
										$curModOptions .= '<option value="' . $modKey . '" selected>' . $modLabel . '</option>';
								 	} else {
										$curModOptions .= '<option value="' . $modKey . '">' . $modLabel . '</option>';
								 	}
								}
							?>
	                          <select id="rxCTCSS" class="form-control">
							  	<?=$curModOptions?>
	                          </select>
                          </td>

                          <td>
	                          <input type="text" class="form-control" value="<?=$curMacroSubArr['macroString']?>" placeholder="1234#">
                          </td>

                          <td>
							<?php
								$curPortOptions = '';
								if ($curMacroSubArr['macroPorts'] == 'ALL') {
									$curPortOptions .= '<option value="ALL" selected>All Ports</option>';
								} else {
									$curPortOptions .= '<option value="ALL">All Ports</option>';
								}
								
								foreach($portListArray as $portNum => $portLabel) {
									$curPortLabel = _('Port') . ' ' . $portNum . ' (' . $portLabel . ')';
								 	if ($curMacroSubArr['macroPorts'] == $portNum) {
										$curPortOptions .= '<option value="' . $portNum . '" selected>' . $curPortLabel . '</option>';
								 	} else {
										$curPortOptions .= '<option value="' . $portNum . '">' . $curPortLabel . '</option>';
								 	}
								}
							?>

	                          <select id="rxCTCSS" class="form-control">
	                            <?=$curPortOptions?>
	                          </select>
                          </td>
                        </tr>
						<?php } ?>
                      </tbody>
                    </table>

<pre>
<?php print_r($fakeModules); ?>
</pre>

<pre>
<?php 
foreach ($fakeModules as $modDetails) { $modulesArray[$modDetails['svxlinkName']] = $modDetails; }
	print_r($modulesArray);
?>
</pre>

                    </form>
                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<?php include('includes/footer.php'); ?>