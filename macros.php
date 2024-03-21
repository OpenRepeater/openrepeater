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

$customJS = 'page-macros.js'; // 'file1.js, file2.js, ... '
$customCSS = 'page-macros.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
$ModulesClass = new Modules();
$moduleList = $ModulesClass->getModulesJSON('short');
$portList = $Database->get_ports('ALL', 'Short');

$macroList = $Database->get_macros();
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
                  <div class="x_title">
                    <h4 class="navbar-left"><?=_('Macros')?></h4>
                    <div class="nav navbar-right">
                      <button type="button" class="btn btn-success add_macro"><i class="fa fa-plus"></i> <?=_('Add Macro')?></button>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  
                  <div class="x_content">

					<p><?=_('NOTE: You can create macros for all available ports and installed modules, but if a port or module is disabled at the time of rebuild then those macros will not be available until the chosen port or module has been reenabled and another rebuild is performed.')?></p>
						
					<div id="no_macros" style="display: none;">
						<h4><?=_('There are no macros setup yet. Click the add button above to create one.')?></h4>
					</div>

                    <table id="macro-table-responsive" class="table table-striped dt-responsive" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th><?=_('Enabled')?></th>
                          <th><?=_('Num')?></th>
                          <th><?=_('Description')?></th>
                          <th><?=_('Module')?> / <?=_('Ports')?></th>
                          <th><?=_('Macro String')?></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>

                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<? ######################################################################### ?>

<script id="macroRowTemplate" type = "text/template">
    <tr id="macroRow%%MACRO%%" class="macroRow" data-macro-number="%%MACRO%%">
      <td>
          <input type="hidden" name="macroKey" value="%%MACRO%%">
          <input type="hidden" name="macroEnabled" value="0">
          <input id="macroEnabled%%MACRO%%" name="macroEnabled" type="checkbox" value="1" class="js-switch form-control macroEnabled">
      </td>
      
      <td>
		  <?php 
			$currentMacroSel = '';
			for ($m = 1 ; $m < 100; $m++) { 
				$currentMacroSel .= '<option value="'.$m.'">D'.$m.'</option>';
			}
		  ?>
          <select id="macroNum%%MACRO%%" name="macroNum" class="form-control macroNum">
		  	<?=$currentMacroSel?>
          </select>
      </td>

      <td>
	  	  <textarea id="macroLabel%%MACRO%%" name="macroLabel" class="form-control macroLabel" placeholder="A macro that does something"></textarea>
      </td>

      <td>
          <select id="macroModuleKey%%MACRO%%" name="macroModuleKey" class="form-control macroModuleKey" required>
	          <option value="" disabled selected><?= _('Select a Module') ?></option>
	          %%MODULE_OPTIONS%%
	      </select>
          <select id="macroPorts%%MACRO%%" name="macroPorts" class="form-control macroPorts" required>
	          <option value="" disabled selected><?= _('Select a Port') ?></option>
	          %%PORT_OPTIONS%%
	      </select>
      </td>

      <td>
	  	  <textarea id="macroString%%MACRO%%" name="macroString" class="form-control macroString" placeholder="1234#"></textarea>
      </td>

      <td>
	  	  <div><a href="#" id="deleteMacro%%MACRO%%" class="deleteMacro"><i class="fa fa-trash-o"></i></a></div>
		  <div class="sectionStatus"><i class="fa"></i></div>
      </td>
    </tr>
</script>


<script>
	var macroList = '<?= json_encode($macroList) ?>';
console.log(macroList);
	var modulesAvailable = '{"1":{"moduleKey":1,"moduleEnabled":1,"svxlinkName":"Help","svxlinkID":0,"displayName":"Help"},"2":{"moduleKey":2,"moduleEnabled":1,"svxlinkName":"Parrot","svxlinkID":1,"displayName":"Parrot"},"3":{"moduleKey":3,"moduleEnabled":0,"svxlinkName":"EchoLink","svxlinkID":2,"displayName":"EchoLink"}}';
	var portsAvailable = '<?= json_encode($portList) ?>';
	var portName = '<?= _('Port') ?>';
	var allPortsName = '<?= _('All Ports') ?>';

	var modal_DeleteMacroTitle = '<?= _('Delete Macro') ?>';
	var modal_DeleteMacroBody = '<?= _('Are you sure you want to delete this macro?') ?>';
	var modal_DeleteMacroBtnOK = '<?= _('Delete Forever') ?>';
	var modal_DeleteMacroProgressTitle = '<?= _('Deleting Macro') ?>';
	var modal_DeleteMacroNotifyTitle = '<?= _('Macro Deleted') ?>';
	var modal_DeleteMacroNotifyDesc = '<?= _('The macro has been successfully deleted.') ?>';
</script>

<?php include('includes/footer.php'); ?>

<?php
// --------------------------------------------------------
// SESSION CHECK TO SEE IF USER IS LOGGED IN.
 } // close ELSE to end login check from top of page
// --------------------------------------------------------
?>