<?php
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";

$quotation_id = $_GET['quotation_id'];
global $app_quot_img;
$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Group Quotation' and active_flag ='Active'"));

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from group_tour_quotation_master where quotation_id='$quotation_id'"));
$sq_package_program = mysql_query("select * from group_tour_program where tour_id ='$sq_quotation[tour_group_id]'");
$sq_tour = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id='$sq_quotation[tour_group_id]'"));
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
$tour_cost = $sq_quotation['tour_cost'] + $sq_quotation['markup_cost_subtotal'];
?>

<section class="headerPanel main_block">
  <div class="headerImage">
    <img src="<?= $app_quot_img?>" class="img-responsive">
    <div class="headerImageOverLay"></div>
  </div>

  <!-- Header --> 
  <section class="print_header main_block side_pad mg_tp_30">
    <div class="col-md-4 no-pad">
      <div class="print_header_logo">
        <img src="<?= $admin_logo_url ?>" class="img-responsive mg_tp_10">
      </div>
    </div>
    <div class="col-md-4 no-pad text-center mg_tp_30">
      <span class="title"><i class="fa fa-pencil-square-o"></i> GROUP QUOTATION</span>
    </div>
  <?php 
  include "standard_header_html.php";
  ?>

    <!-- print-detail -->
    <section class="print_sec main_block side_pad">
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block">
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-calendar" aria-hidden="true"></i><br>
                  <span>QUOTATION DATE</span><br>
                  <?= get_date_user($sq_quotation['quotation_date']) ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-hourglass-half" aria-hidden="true"></i><br>
                  <span>DURATION</span><br>
                  <?php echo ($sq_quotation['total_days']-1).'N/'.$sq_quotation['total_days'].'D' ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-users" aria-hidden="true"></i><br>
                  <span>TOTAL GUEST</span><br>
                  <?= $sq_quotation['total_passangers'] ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-tags" aria-hidden="true"></i><br>
                  <span>PRICE</span><br>
                  <?= number_format($sq_quotation['quotation_cost'] ,2) ?><br>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

  </section>


    <!-- Package -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="section_heding">
        <h2>TOUR DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="print_info_list">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TOUR NAME :</span> <?= $sq_quotation['tour_name'] ?> </li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CUSTOMER NAME :</span> <?= $sq_quotation['customer_name'] ?></li>
            </ul>
            <ul class="print_info_list">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>QUOTATION ID :</span> <?= get_quotation_id($quotation_id,$year) ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>E-MAIL ID :</span> <?= $sq_quotation['email_id'] ?></li>
            </ul>
          <hr class="main_block">
            <ul class="main_block">
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>ADULT : </span><?= $sq_quotation['total_adult'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>CHILDREN : </span><?= $sq_quotation['total_children'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>INFANT : </span><?= $sq_quotation['total_infant'] ?></li>
            </ul>
            <ul class="main_block">
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>TOTAL : </span><?= $sq_quotation['total_passangers'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>WITH BED : </span><?= $sq_quotation['children_with_bed'] ?></li>
              <li class="col-md-4 mg_tp_10 mg_bt_10"><span>WITHOUT BED : </span><?= $sq_quotation['children_without_bed'] ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Costing -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="row">
        <div class="col-md-6">
          <div class="section_heding">
            <h2>COSTING</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_info_block">
            <ul class="main_block">
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>TOUR COST : </span><?= number_format($tour_cost,2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>TAX : </span><?= number_format($sq_quotation['service_tax_subtotal'],2) ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>QUOTATION COST : </span><?= number_format($sq_quotation['quotation_cost'],2) ?></li> 
          </div>
        </div>

  <!-- Bank Detail -->
      <div class="col-md-6">
        <div class="section_heding">
          <h2>BANK DETAILS</h2>
          <div class="section_heding_img">
            <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
          </div>
        </div>
        <div class="print_info_block">
          <ul class="main_block">
            <li class="col-md-12 mg_tp_10 mg_bt_10"><span>BANK NAME : </span><?= $bank_name_setting ?></li>
            <li class="col-md-12 mg_tp_10 mg_bt_10"><span>A/C NAME : </span><?= $acc_name ?></li>
            <li class="col-md-12 mg_tp_10 mg_bt_10"><span>BRANCH : </span><?= $bank_branch_name ?></li>
            <li class="col-md-12 mg_tp_10 mg_bt_10"><span>A/C NO : </span><?= $bank_acc_no ?></li>
            <li class="col-md-12 mg_tp_10 mg_bt_10"><span>IFSC : </span><?= $bank_ifsc_code ?></li>
              <li class="col-md-12 mg_tp_10 mg_bt_10"><span>Swift Code : </span><?= $bank_swift_code ?></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

    <!-- Tour Itinenary -->
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="section_heding">
        <h2>TOUR ITINERARY</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_itinenary main_block no-pad no-marg">
          <?php 
            $count = 1;
            while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
            ?>
            <section class="print_single_itinenary main_block">
              <div class="print_itinenary_count print_info_block">DAY - <?= $count ?></div>
              <div class="print_itinenary_desciption print_info_block">
                <div class="print_itinenary_attraction">
                  <span class="print_itinenary_attraction_icon"><i class="fa fa-map-marker"></i></span>
                  <samp class="print_itinenary_attraction_location"><?= $row_itinarary['attraction'] ?></samp>
                </div>
                <p><?= $row_itinarary['day_wise_program'] ?></p>
              </div>
              <div class="print_itinenary_details">
                <div class="print_info_block">
                  <ul class="main_block no-pad">
                    <li class="col-md-12 mg_tp_10 mg_bt_10"><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
                    <li class="col-md-12 mg_tp_10 mg_bt_10"><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
                  </ul>
                </div>
              </div>
            </section>
            <?php $count++; } ?>
            </div>
        </div>
      </div>
    </section>

    
    <!-- Traveling Sections -->
    <section class="print_sec main_block">

      <?php
      $sq_train_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_train_entries where quotation_id='$quotation_id'"));
      if($sq_train_count>0){ ?>
      <section class="print_sec main_block side_pad mg_tp_30">
        <div class="section_heding">
          <h2>Travelling Information</h2>
          <div class="section_heding_img">
            <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
          </div>
        </div>

      <!-- Train -->
        <div class="row">
          <div class="col-md-12 subTitle">
            <h3>Train</h3>
          </div>
          <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered no-marg" id="tbl_emp_list">
              <thead>
                <tr class="table-heading-row">
                  <th>From</th>
                  <th>To</th>
                  <th>Class</th>
                  <th>Departure</th>
                  <th>Arrival</th>
                </tr>
              </thead>
              <tbody>  
              <?php 
              $sq_train = mysql_query("select * from group_tour_quotation_train_entries where quotation_id='$quotation_id'");
              while($row_train = mysql_fetch_assoc($sq_train)){  
                ?>
                <tr>
                  <td><?= $row_train['from_location'] ?></td>
                  <td><?= $row_train['to_location'] ?></td>
                  <td><?= $row_train['class'] ?></td>
                  <td><?= date('d-m-Y H:i', strtotime($row_train['departure_date'])) ?></td>
                  <td><?= date('d-m-Y H:i', strtotime($row_train['arrival_date'])) ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      <?php } ?>

      <!-- Flight -->
      <?php 
      $sq_plane_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_plane_entries where quotation_id='$quotation_id'"));
      if($sq_plane_count>0){ 
      ?>
        <div class="row mg_tp_30">
          <div class="col-md-12 subTitle">
            <h3>Flight</h3>
          </div>
          <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered no-marg" id="tbl_emp_list">
              <thead>
                <tr class="table-heading-row">
                  <th>From</th>
                  <th>To</th>
                  <th>Airline</th>
                  <th>Class</th>
                  <th>Departure</th>
                  <th>Arrival</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $sq_plane = mysql_query("select * from group_tour_quotation_plane_entries where quotation_id='$quotation_id'");
                  while($row_plane = mysql_fetch_assoc($sq_plane)){
                  $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane[airline_name]'"));
                ?>   
                <tr>
                  <td><?= $row_plane['from_location'] ?></td>
                  <td><?= $row_plane['to_location'] ?></td>
                  <td><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></td>
                  <td><?= $row_plane['class'] ?></td>
                  <td><?= date('d-m-Y H:i:s', strtotime($row_plane['dapart_time'])) ?></td>
                  <td><?= date('d-m-Y H:i:s', strtotime($row_plane['arraval_time'])) ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      <?php } ?>

      <!-- Cruise -->
      <?php
      $sq_cr_count = mysql_num_rows(mysql_query("select * from group_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));
      if($sq_cr_count>0){ ?>
        <div class="row mg_tp_30">
          <div class="col-md-12 subTitle">
            <h3>Cruise</h3>
          </div>
          <div class="col-md-12">
          <div class="table-responsive">
            <table class="table table-bordered no-marg" id="tbl_emp_list">
              <thead>
                <tr class="table-heading-row">
                  <th>Departure</th>
                  <th>Arrival</th>
                  <th>Route</th>
                  <th>Cabin</th>
                  <th>Sharing</th>
                </tr>
              </thead>
              <tbody>  
              <?php 
              $sq_cruise = mysql_query("select * from group_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
              while($row_cruise = mysql_fetch_assoc($sq_cruise)){  
                ?>
                <tr>
                  <td><?= date('d-m-Y H:i', strtotime($row_cruise['dept_datetime'])) ?></td>
                  <td><?= date('d-m-Y H:i', strtotime($row_cruise['arrival_datetime'])) ?></td>
                  <td><?= $row_cruise['route'] ?></td>
                  <td><?= $row_cruise['cabin'] ?></td>
                  <td><?= $row_cruise['sharing'] ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
        </div>
      </section>
      <?php } ?>

    </section>


    <!-- Inclusion -->
    <section class="print_sec main_block side_pad mg_tp_30">
    <?php if($sq_quotation['incl'] != ''){ ?>
      <div class="row">
        <div class="col-md-6">
          <div class="section_heding">
            <h2>Inclusions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
            <?= $sq_quotation['incl'] ?>
          </div>
        </div>
    <?php } ?>


    <!-- Exclusion -->
    <?php if($sq_quotation['excl'] != ''){ ?>
        <div class="col-md-6">
          <div class="section_heding">
            <h2>Exclusions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
            <?= $sq_quotation['excl'] ?>
          </div>
        </div>
      </div>
    <?php } ?>
    </section>


    
    <!-- Terms and Conditions -->
    <?php if($sq_terms_cond['terms_and_conditions'] != ''){ ?>
    <section class="print_sec main_block side_pad mg_tp_30">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Terms and Conditions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
          <?= $sq_terms_cond['terms_and_conditions'] ?>
          </div>
        </div>
      </div>
    <?php } ?>


    <div class="row mg_tp_30">
      <div class="col-md-7"></div>
      <div class="col-md-5 mg_tp_30">
        <div class="print_quotation_creator text-center">
          <span>PREPARE BY </span><br><?= $emp_name?>
        </div>
      </div>
    </div>
    </section>
  </body>
</html>