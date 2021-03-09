<?php
// $customJS = 'page-ports.js'; // 'file1.js, file2.js, ... '
// $customCSS = 'page-ports.css'; // 'file1.css, file2.css, ... '

include('includes/header.php');
?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_full">
                <h3><i class="fa fa-edit"></i> <?=_('Activity Log')?></h3>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-xs-12">

                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">svxlink.conf</a>
                        </li>
<!--
                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Profile</a>
                        </li>
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Profile</a>
                        </li>
-->
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
							<pre>
Sat Aug  1 18:42:23 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 18:42:27 2020: TX_Port1: Turning the transmitter OFF
Sat Aug  1 18:50:00 2020: ORP_FullDuplexLogic_Port1: Sending short identification...
Sat Aug  1 18:50:00 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 18:50:04 2020: TX_Port1: Turning the transmitter OFF
Sat Aug  1 19:00:00 2020: ORP_FullDuplexLogic_Port1: Sending long identification...
Sat Aug  1 19:00:00 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 19:00:08 2020: TX_Port1: Turning the transmitter OFF
Sat Aug  1 19:10:00 2020: ORP_FullDuplexLogic_Port1: Sending short identification...
Sat Aug  1 19:10:00 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 19:10:04 2020: TX_Port1: Turning the transmitter OFF
Sat Aug  1 19:20:00 2020: ORP_FullDuplexLogic_Port1: Sending short identification...
Sat Aug  1 19:20:00 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 19:20:04 2020: TX_Port1: Turning the transmitter OFF
Sat Aug  1 19:30:00 2020: ORP_FullDuplexLogic_Port1: Sending short identification...
Sat Aug  1 19:30:00 2020: TX_Port1: Turning the transmitter ON
Sat Aug  1 19:30:04 2020: TX_Port1: Turning the transmitter OFF
							</pre>

                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                          <p>...</p>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                          <p>...</p>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
           </div>
          </div>
        </div>
        <!-- /page content -->

<?php include('includes/footer.php'); ?>