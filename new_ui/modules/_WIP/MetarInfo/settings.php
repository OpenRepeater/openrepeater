<?php
	include('../module_header.php');
?>

            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('METAR Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Basic Settings')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

                      <div class="form-group">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12"><?=_('Airports')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('ICAO codes for preferred airports.')?>"></i>
                        </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <input id="tags_1" type="text" class="form-control tags" value="ESSB,EDDP,SKSM,EDDS,EDDM,EDDF,KJAC,KTOL">
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Default Airport')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('List the abbreviation of one of the airports above to read when module starts.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" value="ESSB" placeholder="ESSB">
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-5"><?=_('Module Timeout')?>
                        	<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=_('This is how many seconds of inactivity to wait for until the module is disabled.')?>"></i>
                        </label>
                        <div class="col-md-6 col-sm-9 col-xs-7">
                          <input type="text" class="form-control" placeholder="300 secs">
                        </div>
                      </div>

                    </form>
                  </div>
                </div>

              </div>



              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Reference')?></h4></div>

                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

					<p>ESSB,EDDP,SKSM,EDDS,EDDM,EDDF,KJAC,KTOL</p>
					
                    <p>Still would like to rework this design to include a table with not only the ICAO code, but the airport name and maybe it's number and the ability to reorder the list. Could probably also have a radio button next to each to select the default.</p>

                    </form>
                  </div>
                </div>

              </div>
              </div>


<?php include('../module_footer.php'); ?>