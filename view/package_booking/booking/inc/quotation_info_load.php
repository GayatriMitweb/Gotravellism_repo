<?php
include "../../../../model/model.php";
$quotation_id = $_POST['quotation_id'];

$quot_info_arr = array();
$train_info_arr =array();
$flight_info_arr =array();
$cruise_info_arr =array();
$hotel_info_arr =array();
$transport_info_arr = array();
$exc_info_arr = array();

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
$sq_costing = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id'"));

$quot_info_arr['total_passangers'] = $sq_quotation['total_passangers'];
$quot_info_arr['tour_name'] = $sq_quotation['tour_name'];

$quot_info_arr['package_id'] = $sq_quotation['package_id'];

$quot_info_arr['children_without_bed'] = $sq_quotation['children_without_bed'];

$quot_info_arr['children_with_bed'] = $sq_quotation['children_with_bed'];

$quot_info_arr['from_date'] = get_date_user($sq_quotation['from_date']);

$quot_info_arr['to_date'] = get_date_user($sq_quotation['to_date']);

$quot_info_arr['total_days'] = $sq_quotation['total_days'];

$quot_info_arr['booking_type'] = $sq_quotation['booking_type'];

$quot_info_arr['tour_cost'] = $sq_costing['tour_cost'] + $sq_costing['transport_cost'] + $sq_costing['excursion_cost'] + $sq_quotation['guide_cost']+ $sq_quotation['misc_cost'];
$quot_info_arr['markup_cost'] = $sq_costing['markup_subtotal'];
$quot_info_arr['taxation_id'] = $sq_costing['taxation_id'];

$sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$sq_costing[taxation_id]'"));
$sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
$quot_info_arr['tax_type'] =  $sq_tax_type['tax_type'];

$quot_info_arr['tax_in_percentage'] = $sq_taxation['tax_in_percentage'];
$quot_info_arr['service_tax'] = $sq_costing['service_tax'];

$quot_info_arr['service_tax_subtotal'] = $sq_costing['service_tax_subtotal'];
$quot_info_arr['total_tour_cost'] = $sq_costing['total_tour_cost'] + $sq_quotation['guide_cost']+ $sq_quotation['misc_cost'];

//Transport
$sq_transport = mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
while($row_transport = mysql_fetch_assoc($sq_transport)){

	$q_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_transport[vehicle_name]'"));
	$start_date = get_date_user($row_transport['start_date']);
	$end_date = get_date_user($row_transport['end_date']);

	$arr = array(
		'start_date' => $start_date,
		'end_date' => $end_date,
		'vehicle_name' => $q_transport['bus_name'],
		'vehicle_id' => $row_transport['vehicle_name']
	);
	array_push($transport_info_arr, $arr);
}

//Excursion
$sq_exc = mysql_query("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'");
while($row_exc = mysql_fetch_assoc($sq_exc)){

	$sq_exc_id = mysql_fetch_assoc(mysql_query("select * from itinerary_paid_services where service_id = '$row_exc[excursion_name]'"));
	$exc_name = $sq_exc_id['service_name'];
	$sq_city_id = mysql_fetch_assoc(mysql_query("select * from city_master where city_id = '$row_exc[city_name]'"));
	$city_name1 = $sq_city_id['city_name'];

 $arr2 = array(

	 'city_id' => $row_exc['city_name'],
	 'city_name' => $city_name1,
	 'exc_id' => $row_exc['excursion_name'],
	 'exc_name' => $exc_name

 );
	array_push($exc_info_arr, $arr2);
}

$sq_train = mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'");
while($row_train = mysql_fetch_assoc($sq_train)){

	$dep_date = get_datetime_user($row_train['departure_date']);
	$arr = array(
		'departure_date' => $dep_date,
		'from_location' => $row_train['from_location'],
		'to_location' => $row_train['to_location']
	);
	 array_push($train_info_arr, $arr);
}



$sq_flight = mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'");

while($row_flight = mysql_fetch_assoc($sq_flight)){
	$sq_city = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_flight[from_city]'")); 	
	$sq_city1 = mysql_fetch_assoc(mysql_query("select city_name from city_master where city_id='$row_flight[to_city]'"));
	$dep_date = get_datetime_user($row_flight['dapart_time']);

	$arr_date = get_datetime_user($row_flight['arraval_time']);

	$arr1 = array(

		'from_city_id' => $row_flight['from_city'],
		'to_city_id' => $row_flight['to_city'],
		'from_city' => $sq_city['city_name'],
		'to_city' => $sq_city1['city_name'],

		'departure_date' => $dep_date,

		'arrival_date' => $arr_date,

		'from_location' => $row_flight['from_location'],

		'to_location' => $row_flight['to_location'],

		'airline_name' => $row_flight['airline_name']

	);

 	array_push($flight_info_arr, $arr1);

}

$sq_cruise = mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'");

while($row_cruise = mysql_fetch_assoc($sq_cruise)){

	$dept_datetime = get_datetime_user($row_cruise['dept_datetime']);
	$arrival_datetime = get_datetime_user($row_cruise['arrival_datetime']);

		$c_arr = array(
			'departure_date' => $dept_datetime,
			'arrival_date' => $arrival_datetime,
			'route' => $row_cruise['route'],
			'cabin' => $row_cruise['cabin'],
			'sharing' => $row_cruise['sharing']
		);

	 array_push($cruise_info_arr, $c_arr);

}


$sq_hotel = mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'");



while($row_hotel = mysql_fetch_assoc($sq_hotel)){

	$sq_hotel_id = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id = '$row_hotel[hotel_name]'"));
 	$hotel_name1 = $sq_hotel_id['hotel_name'];
 	$sq_city_id = mysql_fetch_assoc(mysql_query("select * from city_master where city_id = '$row_hotel[city_name]'"));
 	$city_name1 = $sq_city_id['city_name'];

	$arr2 = array(

		'city_id' => $row_hotel['city_name'],
		'city_name' => $city_name1,
		'from_date' => $quot_info_arr['from_date'],
		'to_date' => $quot_info_arr['to_date'],
		'hotel_id1' => $row_hotel['hotel_name'],
		'total_rooms' => $row_hotel['total_rooms'],
		'hotel_name1' => $hotel_name1

	);

 array_push($hotel_info_arr, $arr2);

}

	
$quot_info_arr['transport_info_arr'] = $transport_info_arr;
$quot_info_arr['exc_info_arr'] = $exc_info_arr;
$quot_info_arr['train_info_arr'] = $train_info_arr;

$quot_info_arr['hotel_info_arr'] = $hotel_info_arr;

$quot_info_arr['flight_info_arr'] = $flight_info_arr;

$quot_info_arr['cruise_info_arr'] = $cruise_info_arr;



echo json_encode($quot_info_arr);

?>