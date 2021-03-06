<?php 
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";
global $app_quot_img;

$quotation_id = $_GET['quotation_id'];

$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Flight Quotation' and active_flag ='Active'"));

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from flight_quotation_master where quotation_id='$quotation_id'"));
$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];
if($sq_emp_info['first_name']==''){
  $emp_name = 'Admin';
}
else{
  $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}
?>

  <!-- landingPage -->
  <section class="landingSec main_block">

    <div class="landingPageTop main_block">
      <img src="<?= $app_quot_img ?>" class="img-responsive">
      <span class="landingPageId"><?= get_quotation_id($quotation_id,$year) ?></span>
    </div>

    <div class="ladingPageBottom main_block side_pad">
      <div class="row">
        <div class="col-md-12 mg_tp_30">
            <h3 class="customerFrom">Prepare for</h3>
        </div>
        <div class="col-md-4">
          <div class="landigPageCustomer">
            <span class="customerName mg_tp_10"><i class="fa fa-user"></i> : <?= $sq_quotation['customer_name'] ?></span><br>
            <span class="customerMail mg_tp_10"><i class="fa fa-envelope"></i> : <?= $sq_quotation['email_id'] ?></span><br>
            <span class="customerMobile mg_tp_10"><i class="fa fa-phone"></i> : <?= $sq_quotation['mobile_no'] ?></span><br>
          </div>
        </div>
        <div class="col-md-8 text-right">
        
          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockBlue">
              <i class="fa fa-calendar"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= get_date_user($sq_quotation['quotation_date']) ?></h3>
              <span class="contentLabel">QUOTATION DATE</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockYellow">
              <i class="fa fa-users"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_quotation['total_seats'] ?></h3>
              <span class="contentLabel">TOTAL SEATS</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockRed">
              <i class="fa fa-tag"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= number_format($sq_quotation['quotation_cost'],2) ?></h3>
              <span class="contentLabel">PRICE</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- traveling Information -->
  <section class="pageSection main_block">
      <!-- background Image -->
      <img src="<?= BASE_URL ?>images/quotation/p4/pageBG.jpg" class="img-responsive pageBGImg">
      <section class="travelingDetails main_block mg_tp_30 pageSectionInner">

      <?php 
        $count = 1;
        $sq_plane = mysql_query("select * from flight_quotation_plane_entries where quotation_id='$quotation_id'");
        while($row_plane = mysql_fetch_assoc($sq_plane)){
        $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane[airline_name]'")); 
        $itinerarySide= ($count%2!=0)?"transportDetailsleft":"transportDetailsright";
        ?> 

        <!-- Flight -->
        <section class="transportDetailsPanel main_block side_pad mg_tp_30 mg_bt_30">
          <div class="travsportInfoBlock">
            <div class="transportIcon">
              <img src="<?= BASE_URL ?>images/quotation/p4/TI_flight.png" class="img-responsive">
            </div>
            <div class="transportDetails">
              <div class="table-responsive" style="margin-top:1px;margin-right: 1px;">
                <table class="table tableTrnasp no-marg" id="tbl_emp_list">
                <thead>
                    <tr class="table-heading-row">
                      <th>SECTOR FROM</th>
                      <th>SECTOR TO</th>
                      <th>Airline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $row_plane['from_location'] ?></td>
                      <td><?= $row_plane['to_location'] ?></td>
                      <td><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="table-responsive" style="margin-top:30px;margin-right: 1px;">
                <table class="table tableTrnasp no-marg" id="tbl_emp_list">
                <thead>
                    <tr class="table-heading-row">
                      <th>Class</th>
                      <th>Departure</th>
                      <th>Arrival</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $row_plane['class'] ?></td>
                      <td><?= date('d-m-Y H:i:s', strtotime($row_plane['dapart_time'])) ?></td>
                      <td><?= date('d-m-Y H:i:s', strtotime($row_plane['arraval_time'])) ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </section>
        <?php $count++; } ?>

      </section>
    </section>

    <!-- traveling Information -->
    <?php if($sq_terms_cond['terms_and_conditions'] != ''){?>
  <section class="pageSection main_block">
      <!-- background Image -->
      <img src="<?= BASE_URL ?>images/quotation/p4/pageBG.jpg" class="img-responsive pageBGImg">

        <!-- Terms and Conditions -->
      <section class="incluExcluTerms main_block side_pad mg_tp_30 pageSectionInner">
        <div class="row">
      
          <div class="col-md-12 mg_tp_30">
            <div class="incluExcluTermsTabPanel main_block">
                <h3 class="incexTitle">Terms & Conditions</h3>
                <div class="tncContent">
                    <pre class="real_text"><?= $sq_terms_cond['terms_and_conditions'] ?></pre>      
                </div>
            </div>
          </div>

        </div>   
      </section>
  </section>
    <?php } ?>

      <!-- Costing & Banking Page -->
      <section class="pageSection main_block">
          <!-- background Image -->
          <img src="<?= BASE_URL ?>images/quotation/p4/pageBGF.jpg" class="img-responsive pageBGImg">
          <section class="endPageSection main_block mg_tp_30 pageSectionInner">
            <div class="row">              
              <!-- Guest Detail -->
              <!-- <div class="col-md-12 passengerPanel endPagecenter mg_bt_30">
                    <h3 class="endingPageTitle text-center">Guest</h3>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon">
                        <img src="images/quotation/p4/adult.png" class="img-responsive">
                        <h4 class="no-marg">Adult</h4>
                      </div>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon">
                        <img src="images/quotation/p4/child.png" class="img-responsive">
                        <h4 class="no-marg">Children</h4>
                      </div>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon">
                        <img src="images/quotation/p4/infant.png" class="img-responsive">
                        <h4 class="no-marg">Infant</h4>
                      </div>
                    </div>
              </div>               -->
            </div>              
            <div class="row constingBankingPanelRow">
              <!-- Costing -->
              <div class="col-md-12 constingBankingPanel constingPanel">
                    <h3 class="costBankTitle text-center">Costing Details</h3>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/subtotal.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= number_format($sq_quotation['subtotal']+$sq_quotation['markup_cost_subtotal'],2) ?></h4>
                      <p>SUBTOTAL</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/tax.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= number_format($sq_quotation['service_tax_subtotal'],2) ?></h4>
                      <p>TAX</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon main_block"><img src="<?= BASE_URL ?>images/quotation/p4/quotationCost.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= number_format($sq_quotation['quotation_cost'],2) ?></h4>
                      <p>QUOTATION COST</p>
                    </div>
              </div>
              
            

              <!-- Bank Detail -->
              <div class="col-md-12 constingBankingPanel BankingPanel">
                    <h3 class="costBankTitle text-center">Bank Details</h3>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/bankName.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $bank_name_setting ?></h4>
                      <p>BANK NAME</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/branchName.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $bank_branch_name ?></h4>
                      <p>BRANCH</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accName.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $acc_name ?></h4>
                      <p>A/C NAME</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accNumber.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $bank_acc_no ?></h4>
                      <p>A/C NO</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $bank_ifsc_code ?></h4>
                      <p>IFSC</p>
                    </div>
                    <div class="col-md-4 text-center mg_bt_30">
                      <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                      <h4 class="no-marg"><?= $bank_swift_code ?></h4>
                      <p>Swift Code</p>
                    </div>
              </div>
              
            
            </div>

          </section>
        </section>

      <!-- Costing & Banking Page -->
      <section class="pageSection main_block">
        <!-- background Image -->
        <img src="<?= BASE_URL ?>images/quotation/p4/pageBG.jpg" class="img-responsive pageBGImg">
        <section class="contactSection main_block mg_tp_30 text-center pageSectionInner">
            <div class="companyLogo">
              <img src="<?= $admin_logo_url ?>">
            </div>
        <div class="companyContactDetail">
            <h3><?= $app_name ?></h3>
            <?php if($app_address != ''){?>
            <div class="contactBlock">
              <i class="fa fa-map-marker"></i>
              <p><?php echo $app_address; ?></p>
            </div>
            <?php } ?>
            <?php if($app_contact_no != ''){?>
            <div class="contactBlock">
              <i class="fa fa-phone"></i>
              <p><?php echo $app_contact_no; ?></p>
            </div>
            <?php } ?>
            <?php if($app_email_id != ''){?>
            <div class="contactBlock">
              <i class="fa fa-envelope"></i>
              <p><?php echo $app_email_id; ?></p>
            </div>
            <?php } ?>
            <?php if($app_website != ''){?>
            <div class="contactBlock">
              <i class="fa fa-globe"></i>
              <p><?php echo $app_website; ?></p>
            </div>
            <?php } ?>
            <div class="contactBlock">
              <i class="fa fa-pencil-square-o"></i>
              <p>Prepare By : <?= $emp_name?></p>
            </div>
        </div>
        </section>
      </section>

  </body>

</html>