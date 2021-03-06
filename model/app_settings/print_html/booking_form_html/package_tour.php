<?php
include "../../../model.php"; 
include "../print_functions.php";
require("../../../../classes/convert_amount_to_word.php");

$booking_id=$_GET['booking_id'];
$quotation_id=$_GET['quotation_id'];
$branch_status = $_GET['branch_status'];
$role = $_SESSION['role'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$year = $_GET['year'];

$branch_details = mysql_fetch_assoc(mysql_query("select * from branches where branch_id='$branch_admin_id'"));

$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Package Sale' and active_flag ='Active'"));
$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));

$package_booking_info = mysql_fetch_assoc(mysql_query("select *  from package_tour_booking_master where booking_id='$booking_id' "));
$inclusions = ($package_booking_info['quotation_id'] == 0)?$package_booking_info['inclusions'] : $sq_quotation['inclusions'];
$exclusions = ($package_booking_info['quotation_id'] == 0)?$package_booking_info['exclusions'] : $sq_quotation['exclusions'];

$tour_name = $package_booking_info['tour_name'];
$from_date = date("d-m-Y", strtotime($package_booking_info['tour_from_date']));
$to_date = date("d-m-Y", strtotime($package_booking_info['tour_to_date']));
//Total days
$total_days1=strtotime($package_booking_info['tour_to_date']) - strtotime($package_booking_info['tour_from_date']);
$total_days = round($total_days1 / 86400);

$_SESSION['generated_by'] = $app_name;
$booking_date =  date("d-m-Y", strtotime($package_booking_info['booking_date']));

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$package_booking_info[customer_id]'"));
if($sq_customer['type']=='Corporate'){
    $customer_name = $sq_customer['company_name']; } 
else{
    $customer_name = $sq_customer['first_name'].' '.$sq_customer['middle_name'].' '.$sq_customer['last_name'];
}

$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id= '$package_booking_info[emp_id]'"));
$booker_name1 = $sq_emp['first_name'].' '.$sq_emp['last_name'];
if($package_booking_info['emp_id'] == '0'){
  $booker_name = 'Admin';
} 
else{ 
  $booker_name = $booker_name1; 
}

$sq_total_members = mysql_num_rows(mysql_query("select traveler_id from package_travelers_details where booking_id='$booking_id'"));

$visa_name= ($package_booking_info['visa_country_name']!="") ? $package_booking_info['visa_country_name']: NA;
$insuarance_name= ($package_booking_info['insuarance_company_name']!="") ? $package_booking_info['insuarance_company_name']: NA;
//Tour TOtal
$tour_amount= ($package_booking_info['actual_tour_expense']!="") ? $package_booking_info['actual_tour_expense']: 0;

$sq_tour_refund = mysql_fetch_assoc(mysql_query("select * from package_refund_traveler_estimate where booking_id='$booking_id'"));
$can_amount = $sq_tour_refund['total_refund'] - ($sq_tour_refund['total_train_amount'] + $sq_tour_refund['total_plane_amount']);

$total_tour_amount = $tour_amount - $can_amount ;
//Travel Total 
$travel_amount= ($package_booking_info['total_travel_expense']!="") ? $package_booking_info['total_travel_expense']: 0;

$sq_tour_refund = mysql_fetch_assoc(mysql_query("select * from package_refund_traveler_estimate where booking_id='$booking_id'"));
$can_amount = $sq_tour_refund['total_refund'] - ($sq_tour_refund['total_visa_amount'] + $sq_tour_refund['total_insuarance_amount'] + $sq_tour_refund['total_tour_amount']);

$total_travel_amount = $travel_amount - $can_amount ;
$net_amount = $total_tour_amount + $total_travel_amount;
?>

    <!-- header -->
    <section class="print_header main_block">
      <div class="col-md-4 no-pad">
      <span class="title"><i class="fa fa-file-text"></i> CONFIRMATION FORM</span>
        <div class="print_header_logo">
          <img src="<?php echo $admin_logo_url; ?>" class="img-responsive mg_tp_10">
        </div>
      </div>
      <div class="col-md-8 no-pad">
        <div class="print_header_contact text-right">
          <span class="title"><?php echo $app_name; ?></span><br>
          <p><?php echo ($branch_status=='yes' && $role!='Admin') ? $branch_details['address1'].','.$branch_details['address2'].','.$branch_details['city'] : $app_address ?></p>
          <p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i> <?php echo ($branch_status=='yes' && $role!='Admin') ? 
           $branch_details['contact_no'] : $app_contact_no ?></p>
          <p><i class="fa fa-envelope" style="margin-right: 5px;"></i> <?php echo $app_email_id; ?></p>
        </div>
      </div>
    </section>

    <!-- print-detail -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block noType">
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-calendar" aria-hidden="true"></i><br>
                  <span>BOOKING DATE</span><br>
                  <?= $booking_date ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-hourglass-half" aria-hidden="true"></i><br>
                  <span>DURATION</span><br>
                   <?php echo ($total_days).'N/'.($total_days+1).'D'; ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-users" aria-hidden="true"></i><br>
                  <span>TOTAL GUEST (s)</span><br>
                  <?php echo $sq_total_members; ?><br>
                </div>
              </li>
              <li class="col-md-3 mg_tp_10 mg_bt_10">
                <div class="print_quo_detail_block">
                  <i class="fa fa-tags" aria-hidden="true"></i><br>
                  <span>PRICE</span><br>
                  <?=  number_format($net_amount, 2) ?><br>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Package -->
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>BOOKING DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-7 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>TOUR NAME :</span> <?= $tour_name ?> </li>
            <li><span>CUSTOMER NAME :</span> <?= $customer_name ?></li>
          </ul>
        </div>
        <div class="col-md-5 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>BOOKING ID :</span> <?= get_package_booking_id($booking_id,$year) ?></li>
            <li><span>CONTACT NUMBER :</span> <?= $package_booking_info['mobile_no'] ?></li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
            <ul class="main_block noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TOUR DATE : </span><?= $from_date.' To '.$to_date ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>INSURANCE : </span><?= $insuarance_name ?></li>
            </ul>
            <ul class="main_block noType">
              
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>VISA : </span><?= $visa_name ?></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    
    <!-- Passenger -->
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>PASSENGERS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>Full_Name</th>
                <th>Gender</th>
              </tr>
            </thead>
            <tbody>
            <?php 
             $sq_members1 = mysql_query("select * from package_travelers_details where booking_id = '$booking_id'");
             while($row_members1 = mysql_fetch_assoc($sq_members1))
             { ?>   
                  <tr>
                    <td><?php echo $row_members1['first_name'].' '.$row_members1['middle_name'].' '.$row_members1['last_name']; ?></td>
                    <td><?php echo $row_members1['gender']; ?></td>
                  </tr>
             <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>

    <!-- Accomodation -->
    <?php
    $sq_count = mysql_num_rows(mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'"));
    if($sq_count != 0){
    ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>ACCOMMODATION</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>City</th>
                <th>Hotel_NAME</th>
                <th>Check_In</th>
                <th>Check_Out</th>
                <th>Rooms</th>
                <th>Category</th>
                <th>Type</th>
                <th>Meal_plan</th>
              </tr>
            </thead>
            <tbody>   
            <?php 
            $sq_entry = mysql_query("select * from package_hotel_accomodation_master where booking_id='$booking_id'");
            while($row_entry = mysql_fetch_assoc($sq_entry))
           {
              $city_id = $row_entry['city_id'];
              $hotel_id = $row_entry['hotel_id'];
              $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$city_id'"));
              $sq_hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$hotel_id'"));
            ?>
              <tr>
                <td><?php echo $sq_city['city_name']; ?></td>
                <td><?php echo $sq_hotel_name['hotel_name'].$similar_text; ?></td>
                <td><?php echo get_datetime_user($row_entry['from_date']); ?></td>
                <td><?php echo get_datetime_user($row_entry['to_date']); ?></td>
                <td><?php echo $row_entry['rooms']; ?></td>
                <td><?php echo $row_entry['catagory']; ?></td> 
                <td><?php echo $row_entry['room_type']; ?></td>
                <td><?php echo $row_entry['meal_plan']; ?></td>
              </tr>
              <?php 
              } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
    <?php } ?>

    
    <!-- transport -->
    <?php
    $sq_count = mysql_num_rows(mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'"));
    if($sq_count != 0){
    ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>TRANSPORT</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>Vehicle_Name</th>
                <th>From_Date</th>
                <th>To_Date</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $sq_entry = mysql_query("select * from package_tour_transport_master where booking_id='$booking_id'");
            while($row_entry = mysql_fetch_assoc($sq_entry))
            {
              $q_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_entry[transport_bus_id]'"));
              ?>
              <tr>
                <td><?= $q_transport['bus_name'].$similar_text ?></td>
                <td><?= get_date_user($row_entry['transport_from_date']) ?></td>
                <td><?= get_date_user($row_entry['transport_to_date']) ?></td>
              </tr>
           <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
    <?php } ?>

    <!-- Excursion -->
    <?php
    $sq_count = mysql_num_rows(mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'"));
    if($sq_count != 0){
    ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>Excursion</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>City_Name</th>
                <th>Excursion_name</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $sq_entry = mysql_query("select * from package_tour_excursion_master where booking_id='$booking_id'");
            while($row_entry = mysql_fetch_assoc($sq_entry)){
              $q_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_entry[city_id]'"));
              $q_transport = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id='$row_entry[exc_id]'"));
              ?>
              <tr>
                <td><?= $q_city['city_name'] ?></td>
                <td><?= $q_transport['service_name'] ?></td>
              </tr>
           <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
    <?php } ?>
    
    
  <?php
    //Train
  $sq_train = mysql_num_rows(mysql_query("select booking_id from package_train_master where booking_id='$booking_id'"));
  $sq_air = mysql_num_rows(mysql_query("select booking_id from package_plane_master where booking_id='$booking_id'"));
  if($sq_train > 0 || $sq_air > 0)
  {
    $sq_train = mysql_num_rows(mysql_query("select booking_id from package_train_master where booking_id='$booking_id'"));
    $train_count = 0;

    if($sq_train>0)
    { ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>Train</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>From</th>
                <th>To</th>
                <th>TRAIN</th>
                <th>SEATS</th>
                <th>CLASS</th>
                <th>PRIORITY</th>
                <th>DEPARTURE D/T</th>
              </tr>
            </thead>
            <tbody>  
            <?php $sq_train_details = mysql_query("select * from package_train_master where booking_id='$booking_id'");
                  while($row_train_details = mysql_fetch_assoc($sq_train_details))
                  {?> 
                  <tr>
                    <td><?php echo $row_train_details['from_location']; ?></td>
                    <td><?php echo $row_train_details['to_location']; ?></td>
                    <td><?php echo $row_train_details['train_no']; ?></td>
                    <td><?php echo $row_train_details['seats']; ?></td>
                    <td><?php echo $row_train_details['train_class']; ?></td>
                    <td><?php echo $row_train_details['train_priority']; ?></td>
                    <td><?php echo date("d-m-Y H:i", strtotime($row_train_details['date'])); ?></td>
                  </tr>
        <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
  <?php }
  } ?>

  <?php 
  //Flight
   $sq_air = mysql_num_rows(mysql_query("select booking_id from package_plane_master where booking_id='$booking_id'"));
   $air_count = 0;

   if($sq_air>0)
   { ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>Flight</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>From</th>
                <th>To</th>
                <th>Airline</th>
                <th>SEATS</th>
                <th>DEPARTURE D/T</th>
                <th>ARRIVAL D/T</th>
              </tr>
            </thead>
            <tbody> 
            <?php $sq_air_details = mysql_query("select * from package_plane_master where booking_id='$booking_id'");
                  while($row_air_details = mysql_fetch_assoc($sq_air_details))
                  {
                      $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_air_details[company]'")); ?> 
                  <tr>
                    <td><?php echo $row_air_details['from_location']; ?></td>
                    <td><?php echo $row_air_details['to_location']; ?></td>
                    <td><?php echo $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')'; ?></td>
                    <td><?php echo $row_air_details['seats']; ?></td>
                    <td><?php echo date("d-m-Y H:i", strtotime($row_air_details['date'])); ?></td>
                    <td><?php echo date("d-m-Y H:i", strtotime($row_air_details['arraval_time'])); ?></td>
                  </tr>
              <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
    <?php } ?> 

    <?php 
    //Cruise
  $sq_cruise = mysql_num_rows(mysql_query("select booking_id from package_cruise_master where booking_id='$booking_id'"));
  if($sq_cruise>0)
  {?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>Cruise</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
         <div class="table-responsive">
          <table class="table table-bordered no-marg" id="tbl_emp_list">
            <thead>
              <tr class="table-heading-row">
                <th>DEPARTURE D/T</th>
                <th>ARRIVAL D/T</th>
                <th>ROUTE</th>
                <th>CABIN</th>
                <th>SHARING</th>
                <th>SEATS</th>                
              </tr>
            </thead>
            <tbody> 
            <?php $sq_cruise_details = mysql_query("select * from package_cruise_master where booking_id='$booking_id'");
                  while($row_cruise_details = mysql_fetch_assoc($sq_cruise_details))
                  { ?> 
                  <tr>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($row_cruise_details['dept_datetime'])); ?></td>
                    <td><?php echo date("d-m-Y H:i:s", strtotime($row_cruise_details['arrival_datetime'])); ?></td>
                    <td><?php echo $row_cruise_details['route']; ?></td>
                    <td><?php echo $row_cruise_details['cabin']; ?></td>
                    <td><?php echo $row_cruise_details['sharing']; ?></td>
                    <td><?php echo $row_cruise_details['seats']; ?></td>
                  </tr>
              <?php } ?>
            </tbody>
          </table>
         </div>
       </div>
      </div>
    </section>
    <?php } ?>

    <!-- Inclusion -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Inclusion</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
            <?php echo $inclusions; ?>
          </div>
        </div>
      </div>
    </section> 


    <!-- Exclusion -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Exclusion</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
            <?php echo $exclusions; ?>
          </div>
        </div>
      </div>
    </section> 

    <!-- Terms and Conditions -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Terms and Conditions</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="print_text_bolck">
          <span><?= $sq_terms_cond['terms_and_conditions'] ?></span>
          </div>
        </div>
      </div>
    </section>


    <!-- Booking Summary -->
    <section class="print_sec main_block">
      <div class="row">
        <div class="col-md-12">
          <div class="section_heding">
            <h2>Booking Summary</h2>
            <div class="section_heding_img">
              <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
            </div>
          </div>
          <div class="row">
        <div class="col-md-7 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>BOOKING DATE :</span> <?= get_date_user($package_booking_info['booking_date']) ?> </li>
          </ul>
        </div>
        <div class="col-md-5 mg_bt_20">
          <ul class="print_info_list no-pad noType">
            <li><span>DUE DATE :</span> <?= get_date_user($package_booking_info['due_date']) ?></li>
          </ul>
        </div>
      </div>
          <div class="row">
          <div class="col-md-12">
          <div class="print_text_bolck">
              <span><?= $package_booking_info['special_request'] ?></span>
          </div>
        </div>
      </div>
    </section>

    <!-- Payment Detail -->
    <?php 
    $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$package_booking_info[tour_taxation_id]'"));

    $total_hotel_expense = ($package_booking_info['total_hotel_expense']!="") ? $package_booking_info['total_hotel_expense']: 0;
    $total_tour_expense = ($package_booking_info['total_tour_expense']!="") ? $package_booking_info['total_tour_expense']: 0;
    $total_travel_expense = ($package_booking_info['total_travel_expense']!="") ? $package_booking_info['total_travel_expense']: 0;

    $subtotal = ($package_booking_info['subtotal']!="") ? $package_booking_info['subtotal']: 0;
    $tour_fee = ($package_booking_info['tour_fee']!="") ? $package_booking_info['tour_fee']: 0;
    $gst = ($sq_taxation['tax_in_percentage']!="") ? $sq_taxation['tax_in_percentage']: 0;
    $gst_amt = ($subtotal * $gst)/100;
    $visa_total_amount= ($package_booking_info['visa_total_amount']!="") ? $package_booking_info['visa_total_amount']: 0.00;
    $insuarance_total_amount= ($package_booking_info['insuarance_total_amount']!="") ? $package_booking_info['insuarance_total_amount']: 0.00;
    ?>
    <section class="print_sec main_block">
      <div class="section_heding">
        <h2>PAYMENT DETAILS</h2>
        <div class="section_heding_img">
          <img src="<?php echo BASE_URL.'images/heading_border.png'; ?>" class="img-responsive">
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="print_amount_block">
            <ul class="main_block no-pad text-right noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TOUR AMOUNT : </span><?php echo number_format($subtotal,2); ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TAX AMOUNT : </span><?php echo number_format($package_booking_info['tour_service_tax_subtotal'],2); ?></li>
            </ul>
            <ul class="main_block no-pad text-right noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>TRAIN AMOUNT : </span><?php echo number_format($package_booking_info['total_train_expense'],2); ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>FLIGHT AMOUNT : </span><?php echo number_format($package_booking_info['total_plane_expense'],2); ?></li>
            </ul>
            <ul class="main_block no-pad text-right noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>CRUISE AMOUNT : </span><?php echo number_format($package_booking_info['total_cruise_expense'],2); ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>VISA AMOUNT : </span><?php echo number_format($visa_total_amount,2); ?></li>
            </ul>
            <ul class="main_block no-pad text-right noType">
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>INSURANCE AMOUNT : </span><?php echo number_format($insuarance_total_amount,2); ?></li>
              <li class="col-md-6 mg_tp_10 mg_bt_10"><span>DUE DATE : </span><?= get_date_user($package_booking_info['due_date'])  ?></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
          <div class="print_amount_block">
            <ul class="main_block no-pad text-right noType">
              <li class="col-md-12 mg_tp_10 mg_bt_10 font_5"><span>TOTAL AMOUNT : </span><?php echo number_format($total_tour_amount + $total_travel_amount,2); ?></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5 text-center">
          <div class="print_quotation_creator">
            <span>CUSTOMER'S SIGNATURE</span><br>
          </div>
        </div>
        <div class="col-md-7 text-right">
          <div class="print_quotation_creator text-center">
            <span>BOOKED BY </span><br><?php echo $booker_name; ?>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>