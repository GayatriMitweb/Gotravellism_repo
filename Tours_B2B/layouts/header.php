<?php
global $currency, $app_name;;
include "get_cache_currencies.php";
include "array_column.php";
$array_master = new array_master;
$sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency'"));
$to_currency_rate = $sq_to['currency_rate'];
$register_id = $_SESSION['register_id'];
$customer_id = $_SESSION['customer_id'];

//Get Approved Credit Limit Amount
$sq_credit = mysql_fetch_assoc(mysql_query("select credit_amount from b2b_creditlimit_master where register_id='$register_id' and approval_status='Approved' order by entry_id desc"));
$sq_reg = mysql_fetch_assoc(mysql_query("SELECT * FROM `b2b_registration` where register_id='$register_id'"));
//Get Booking + Payment amount to calculate outstanding amount
$sq_booking = mysql_query("select * from b2b_booking_master where customer_id='$customer_id'");
$net_total = 0;
$paid_amount = 0;
while($row_booking = mysql_fetch_assoc($sq_booking)){
	$hotel_total = 0;
	$transfer_total = 0;
  $activity_total = 0;
  $tours_total = 0;
  $cart_checkout_data = json_decode($row_booking['cart_checkout_data']);
  for($i=0;$i<sizeof($cart_checkout_data);$i++){

		if($cart_checkout_data[$i]->service->name == 'Hotel'){
      $tax_arr = explode(',',$cart_checkout_data[$i]->service->hotel_arr->tax);
      for($j=0;$j<sizeof($cart_checkout_data[$i]->service->item_arr);$j++){
        $room_types = explode('-',$cart_checkout_data[$i]->service->item_arr[$j]);
        $room_cost = $room_types[2];
        $h_currency_id = $room_types[3];
        
        $tax_amount = ($room_cost * $tax_arr[1] / 100);
        $total_amount = $room_cost + $tax_amount;
        //Convert into default currency
        $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
        $from_currency_rate = $sq_from['currency_rate'];
        $total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
      
        $hotel_total += $total_amount;
      }
    }
		if($cart_checkout_data[$i]->service->name == 'Transfer'){
			for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
			$tax_arr = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->taxation);
			$transfer_cost = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->transfer_cost);
				$room_cost = $transfer_cost[0];
				$h_currency_id = $transfer_cost[1];
				
				$tax_amount = ($room_cost * $tax_arr[1] / 100);
				$total_amount = $room_cost + $tax_amount;

				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
				$total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
			
				$transfer_total += $total_amount;
			}
    }
		if($cart_checkout_data[$i]->service->name == 'Activity'){
			for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
			$tax_arr = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->taxation);
			$transfer_cost = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->transfer_type);
				$room_cost = $transfer_cost[1];
				$h_currency_id = $transfer_cost[2];
				
				$tax_amount = ($room_cost * $tax_arr[1] / 100);
				$total_amount = $room_cost + $tax_amount;

				//Convert into default currency
				$sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
				$from_currency_rate = $sq_from['currency_rate'];
				$total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
			
				$activity_total += $total_amount;
			}
    }
    if($cart_checkout_data[$i]->service->name == 'Combo Tours'){
        for($j=0;$j<sizeof($cart_checkout_data[$i]->service);$j++){
        
            $taxation_arr = explode('-',$cart_checkout_data[$i]->service->service_arr[$j]->taxation);
            $room_cost = $cart_checkout_data[$i]->service->service_arr[$j]->total_cost;
            $h_currency_id = $cart_checkout_data[$i]->service->service_arr[$j]->currency_id;
            
            $tax_amount = ($room_cost * $taxation_arr[1] / 100);
            $total_amount = $room_cost + $tax_amount;

            //Convert into default currency
            $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$h_currency_id'"));
            $from_currency_rate = $sq_from['currency_rate'];
            $total_amount = ($from_currency_rate / $to_currency_rate * $total_amount);
        
            $tours_total += $total_amount;
        }
    }
  }
	$net_total += $hotel_total + $transfer_total + $activity_total + $tours_total;
  if($row_booking['coupon_code'] != ''){
		$sq_hotel_count = mysql_num_rows(mysql_query("select offer,offer_amount from hotel_offers_tarrif where coupon_code='$row_booking[coupon_code]'"));
		if($sq_hotel_count > 0){
			$sq_coupon = mysql_fetch_assoc(mysql_query("select offer as offer,offer_amount from hotel_offers_tarrif where coupon_code='$row_booking[coupon_code]'"));
		}else{
			$sq_coupon = mysql_fetch_assoc(mysql_query("select offer_in as offer,offer_amount from excursion_master_offers where coupon_code='$row_booking[coupon_code]'"));
		}
    if($sq_coupon['offer']=="Flat"){
      $net_total = $net_total - $sq_coupon['offer_amount'];
    }else{
      $net_total = $net_total - ($net_total*$sq_coupon['offer_amount']/100);
    }
  }
  // Paid Amount
  $sq_payment_info = mysql_fetch_assoc(mysql_query("SELECT sum(payment_amount) as sum from b2b_payment_master where booking_id='$row_booking[booking_id]' and (clearance_status!='Pending' or clearance_status!='Cancelled')"));
  $payment_amount = $sq_payment_info['sum'];
  $paid_amount +=$sq_payment_info['sum'];
}
$outstanding =  $net_total - $paid_amount;
$available_credit = ($outstanding >= 0) ? ($sq_credit['credit_amount'] - $outstanding) : $sq_credit['credit_amount'];
$available_credit = ($available_credit < 0)? 0.00 : $available_credit;
$_SESSION['credit_amount'] = $available_credit;
$credit_amount = $_SESSION['credit_amount'];
?>
<!DOCTYPE html>
<html>
    <head>
      <!-- Page Title -->
      <title><?= $app_name ?></title>

      <!-- Meta Tags -->
      <meta charset="utf-8" />
      <meta name="keywords" content="HTML5 Template" />
      <meta
        name="description"
        content="iTours - Travel, Tour Booking HTML5 Template"
      />
      <meta name="author" content="SoapTheme" />

      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="shortcut icon" href="<?php echo BASE_URL ?>Tours_B2B/images/favicon.png" type="image/x-icon" />

      <!-- Theme Styles -->
      <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
      <link rel="stylesheet" href="<?php echo BASE_URL ?>css/font-awesome-4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/bootstrap-4.min.css" />
      <link rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/owl.carousel.min.css" />
      <link id="main-style" rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/itours-styles.css" />
      <link id="main-style" rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/itours-components.css" />
      <link id="main-style" rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/vi.alert.css" />
      <link rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/pagination.css" />
      <link rel="stylesheet" href="<?php echo BASE_URL ?>Tours_B2B/css/jquery-confirm.css">
      <link rel="stylesheet" href="<?php echo BASE_URL ?>css/jquery.datetimepicker.css">
      

      <!-- Javascript Page Loader -->
    </head>
    <body>
        <input type="hidden" id="base_url" name="base_url" value="<?= BASE_URL ?>">
        <input type="hidden" id="register_id" value="<?= $register_id ?>"/>
        <input type="hidden" id="customer_id" value="<?= $customer_id ?>"/>
        <input type="hidden" id="global_currency" value="<?= $currency ?>"/>
      <div class="c-pageWrapper">
             <!-- ********** Component :: Header ********** -->
      <div class="clearfix">
          <!-- **** Top Header ***** -->
          <div class="c-pageHeaderTop">
            <div class="pageHeader_top mobileSidebar">

              <!-- Menubar close btn for Mobile -->
              <button class="closeSidebar forMobile"></button>
              <!-- Menubar close btn for Mobile End -->

              <div class="container">
                <div class="row">

                  <div class="col-md-4 col-12 section-1">
                    <span class="staticText">Helpline : <?= $app_contact_no ?></span>
                  </div>

                  <div class="col-md-4 col-12 section-2">
                    <div class="credBalance">
                      <span class="s1">Credit Balance:</span>
                      <span class="currency-icon"></span></span><span class="s2" id="credit_amount"></span>
                    </div>
                  </div>

                  <div class="col-md-4 col-12 section-3">
                    <div class="topListing">
                      <ul>
                        <li>
                          <div class="c-select2DD st-clear">
                          <div id='currency_dropdown'></div>
                          </div>
                        </li>
                        <li>
                          <div class="c-select2DD st-clear">
                            <select name="state">
                              <option value="English">English</option>
                            </select>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>

                </div>
              </div>

              <!-- Menubar for Mobile -->
              <div class="menuBar forMobile">
                <ul>
                  <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Hotels">Hotels</a></li>
                  <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Activities">Activities</a></li>
                  <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Transfer">Transfer</a></li>
                  <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=ComboTours">Combo Tours</a></li>
                  <li><a class="menuLink" href="<?php echo BASE_URL ?>Tours_B2B/login.php">Logout</a></li>
                </ul>
              </div>
              <!-- Menubar for Mobile End -->

            </div>
          </div>
          <!-- **** Top Header End ***** -->

          <!-- **** Bottom Header ***** -->
          <div class="c-pageHeader">
            <div class="pageHeader_btm">
              <div class="container">
                <div class="row align-items-center">
                  <div class="col-sm-4 col-7 p0-right">

                    <!-- Menubar Hamb btn for Mobile -->
                    <button class="mobile_hamb"></button>
                    <!-- Menubar Hamb btn for Mobile End -->

                    <a href="<?= $b2b_index_url ?>" class="btm_logo">
                      <img src='<?php echo BASE_URL ?>images/Admin-Area-Logo.png' alt="iTours" />
                    </a>
                  </div>
                  <div class="col-sm-8 col-5 text-right p0-left">
                    <div class="menuBar">
                      <ul>
                        <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Hotels">Hotels</a></li>
                        <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Activities">Activities</a></li>
                        <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=Transfer">Transfer</a></li>
                        <li><a class="menuLink" href="<?= $b2b_index_url ?>?service=ComboTours">Combo Tours</a></li>
                      </ul>
                    </div>

                    <button class="btnUtil" type="button" data-toggle="modal" data-target="#shopping_list_modal" aria-haspopup="true" aria-expanded="false" onclick='display_cart("cart_item_count");'>
                      <img src="<?php echo BASE_URL ?>Tours_B2B/images/svg/supermarket.svg" alt="iTours" />
                      <span class="notify" id='cart_item_count'></span>
                    </button>


                    <!-- ***** User Profile DD ***** -->
                    <div class="c-userProf dropdown">
                      <button class="btnUtil" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo BASE_URL ?>Tours_B2B/images/svg/user.svg" alt="iTours" />
                      </button>

                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="userHeading">
                          <div class="profIcon">
                            <img src="<?php echo BASE_URL ?>Tours_B2B/images/svg/user.svg" alt="iTours" />
                          </div>
                          <h4 class="userName"><?=$sq_reg['company_name']?></h4>
                          <span class="userCode"><?= $sq_reg['mobile_no'] ?></span>
                        </div>
                        <div class="userNav">
                          <a href="<?php echo BASE_URL ?>Tours_B2B/view/user-profile/profile.php" class="userNavLink">
                            <i class="icon user itours-user"></i>
                            <span>Profile</span>
                          </a>
                          <a href="<?php echo BASE_URL ?>Tours_B2B/view/user-profile/profile.php" class="userNavLink">
                            <i class="icon user itours-shopping-cart"></i>
                            <span>Booking Summary</span>
                          </a>
                          <a href="<?php echo BASE_URL ?>Tours_B2B/view/user-profile/profile.php" class="userNavLink">
                            <i class="icon user itours-ledger"></i>
                            <span>Account Ledger</span>
                          </a>
                          <a href="<?php echo BASE_URL ?>Tours_B2B/login.php" class="userNavLink">
                            <i class="icon user itours-logout"></i>
                            <span>Logout</span>
                          </a>
                        </div>
                      </div>
                    </div>
                    <!-- ***** User Profile DD End ***** -->

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- **** Bottom Header End ***** -->
      </div>
      <!-- ********** Component :: Header End ********** -->
      <input type="hidden" id='cache_currencies' value='<?= $data ?>'/>