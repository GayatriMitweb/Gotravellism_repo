<?php
include "../../../model/model.php";

$quotation_id = $_POST['quotation_id'];
$hotel_arr = array();
$sq_enq = mysql_fetch_assoc(mysql_query("select * from car_rental_quotation_master where quotation_id='$quotation_id'"));
$sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_enq[taxation_id]'"));
$sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));

$arr = array(
			
	'days_of_traveling' => $sq_enq['days_of_traveling'],
	'traveling_date' => $sq_enq['traveling_date'],
	'vehicle_name' => $sq_enq['vehicle_name'],
	'travel_type' => $sq_enq['travel_type'],
	'places_to_visit' => $sq_enq['places_to_visit'],
	'local_places_to_visit' => $sq_enq['local_places_to_visit'],
	'from_date' => $sq_enq['from_date'],
	'to_date' => $sq_enq['to_date'],
	'trip_type' => $sq_enq['trip_type'],
	'route' => $sq_enq['route'],
	'extra_km_cost' => $sq_enq['extra_km_cost'],
	'extra_hr_cost' => $sq_enq['extra_hr_cost'],
	'daily_km' => $sq_enq['daily_km'],
	'subtotal' => $sq_enq['subtotal'],
	'markup_cost' => $sq_enq['markup_cost'],
	'markup_cost_subtotal' => $sq_enq['markup_cost_subtotal'],
	'taxation_id' => $sq_enq['taxation_id'],
	'service_tax' => $sq_enq['service_tax'],
	'service_tax_subtotal' => $sq_enq['service_tax_subtotal'],
	'permit' => $sq_enq['permit'],
	'toll_parking' => $sq_enq['toll_parking'],
	'driver_allowance' => $sq_enq['driver_allowance'],
	'total_fees' => $sq_enq['total_tour_cost'],
	'total_days' => $sq_enq['total_days'],
	'valid_km' => $sq_enq['valid_km'],
	'extra_km' => $sq_enq['extra_km'],
	'total_pax' => $sq_enq['total_pax'],
	'capacity' => $sq_enq['capacity'],
	'state_entry'=>$sq_enq['state_entry'],
	'other_charges'=>$sq_enq['other_charge'],
	'total_hrs'=>$sq_enq['total_hrs'],
	'total_km'=>$sq_enq['total_km'],
	'rate'=>$sq_enq['rate'],
	'total_max_km'=>$sq_enq['total_max_km'],
	'tax_type'=>$sq_tax_type['tax_type'],
	'traveling_date'=>$sq_enq['traveling_date']

		 );
	array_push($hotel_arr, $arr);

echo json_encode($hotel_arr);
exit;
?>