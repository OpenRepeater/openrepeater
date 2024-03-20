<?php
	include('../module_header.php');
?>

            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-plug"></i> <?=_('Site Status Settings')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Configure Digital Sensors')?></h4></div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

						<p>(May still add a toggle variable here to turn this section on & off)</p>
						<p>Some simple examples of digital events (On/Off, Open/shut) - Door open/close, Generator active/standby, Primary power available (vs running on generator), water sensor, fire sensor</p>

						<h3>DYNAMIC FIELDS GO HERE...</h3>

                    </form>
                  </div>
                </div>

              </div>



              <div class="col-md-6 col-xs-12">

                <div class="x_panel">
                  <div class="x_title"><h4><?=_('Configure Analog Sensors')?></h4></div>

                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask">

						<p>(May still add a toggle variable here to turn this section on & off)</p>
						<p>Some simple examples of Analog events (sliding scale inputs) - Fuel levels for generator, battery voltage, temperature, primary power supply voltage</p>
						
						<h3>DYNAMIC FIELDS GO HERE...</h3>

                    </form>
                  </div>
                </div>

              </div>
              </div>


<?php include('../module_footer.php'); ?>