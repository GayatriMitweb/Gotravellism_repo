<?php  
//Generic Files
include "../../../../model.php"; 
include "../../print_functions.php";
require("../../../../../classes/convert_amount_to_word.php"); 

//Parameters
$invoice_no = $_GET['invoice_no'];
$booking_id = $_GET['booking_id'];
$invoice_date = $_GET['invoice_date'];
$customer_id = $_GET['customer_id'];
$service_name = $_GET['service_name'];
$basic_cost1 = $_GET['basic_cost'];
$service_charge = $_GET['service_charge'];
$taxation_type = $_GET['taxation_type'];
$service_tax_per = $_GET['service_tax_per'];
$service_tax = $_GET['service_tax'];
$net_amount = $_GET['net_amount'];
$bank_name = $_GET['bank_name'];
$total_paid = $_GET['total_paid'];
$balance_amount = $_GET['balance_amount'];
$sac_code = $_GET['sac_code'];

$basic_cost = number_format($basic_cost1,2);
$net_amount1 = 0;
$net_amount1 =  $basic_cost1 + $service_charge  + $service_tax;
$amount_in_word = $amount_to_word->convert_number_to_words($net_amount1);
$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id='$booking_id'"));

//Header
if($app_invoice_format == "Standard"){include "../headers/standard_header_html.php"; }
if($app_invoice_format == "Regular"){include "../headers/regular_header_html.php"; }
if($app_invoice_format == "Advance"){include "../headers/advance_header_html.php"; }
?>

<hr class="no-marg">

<div class="col-md-12 mg_tp_20"><p class="border_lt"><span class="font_5">PASSENGER : <?= $sq_hotel['pass_name'] ?></span></p></div>

<div class="main_block inv_rece_table main_block">
    <div class="row">
      <div class="col-md-12">
       <div class="table-responsive">
        <table class="table table-bordered no-marg" id="tbl_emp_list" style="padding: 0 !important;">
          <thead>
            <tr class="table-heading-row">
              <th>SR.NO</th>
              <th>City</th>     
              <th>Hotel</th>     
              <th>Check_IN_date</th>              
              <th>Check_OUT_date</th>
              <th>Room_Type</th>
              <th>Confirmation_NO</th>
            </tr>
          </thead>
          <tbody>   
          <?php 
          $count = 1;
          $sq_hotel_entry = mysql_query("select * from hotel_booking_entries where booking_id='$booking_id'");
          while($row_passenger = mysql_fetch_assoc($sq_hotel_entry))
          {
              $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_passenger[city_id]'"));
              $sq_hotel = mysql_fetch_assoc(mysql_query("select hotel_id, hotel_name from hotel_master where hotel_id='$row_passenger[hotel_id]'"));
            ?>
            <tr class="odd">
              <td><?php echo $count; ?></td>
              <td><?php echo $sq_city['city_name']; ?></td>
              <td><?php echo $sq_hotel['hotel_name']; ?></td>
              <td><?php echo get_datetime_user($row_passenger['check_in']); ?></td>
              <td><?php echo get_datetime_user($row_passenger['check_out']); ?></td>
              <td><?php echo $row_passenger['room_type']; ?></td>
              <td><?php echo $row_passenger['conf_no']; ?></td>
            </tr>
            <?php   
               $count++;
             } ?>
          </tbody>
        </table>
       </div>
     </div>
    </div>
  </div>

<section class="print_sec main_block">

<!-- invoice_receipt_body_calculation -->
<div class="row">
  <div class="col-md-12">
    <div class="main_block inv_rece_calculation border_block">
      <div class="col-md-6"><p class="border_lt"><span class="font_5">AMOUNT </span><span class="float_r"><?= $currency_logo." ".$basic_cost ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">TOTAL </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($net_amount1,2) ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">SERVICE CHARGE </span><span class="float_r"><?= $currency_logo." ".$service_charge ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">ADVANCED PAID </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($total_paid,2) ?></span></p></div>
      <?php if($taxation_type == 'SGST CGST'){ ?>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'SGST'; ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax/2,2) ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($balance_amount,2) ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.($service_tax_per/2).'%)' ?>[<?php echo 'CGST'; ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax/2,2) ?></span></p></div><?php } 
      else{ ?>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">TAX<?= '('.$service_tax_per.'%)' ?>[<?= $taxation_type ?>] </span><span class="float_r"><?= $currency_logo." ".number_format($service_tax,2) ?></span></p></div>
      <div class="col-md-6"><p class="border_lt"><span class="font_5">CURRENT DUE </span><span class="font_5 float_r"><?= $currency_logo." ".number_format($balance_amount,2) ?></span></p></div> <?php } ?>
    </div>
  </div>
</div>
</section>
<?php 
//Footer
include "../generic_footer_html.php"; ?>