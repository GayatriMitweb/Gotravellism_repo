<?php
include '../../../model/model.php';
include '../../layouts/header.php';

$currency = $_SESSION['session_currency_id'];
$sq_to = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency'"));
$to_currency_rate = $sq_to['currency_rate'];

$b2b_agent_code = $_SESSION['b2b_agent_code'];
$tours_array = json_decode($_SESSION['tours_array']);
$checkDate = date('d M Y', strtotime($tours_array[0]->tour_date));
$date1 = date("Y-m-d", strtotime($tours_array[0]->tour_date));
$pax = $tours_array[0]->adult+$tours_array[0]->child_wobed+$tours_array[0]->child_wibed+$tours_array[0]->infant;
$dest_id = $tours_array[0]->dest_id;
$tour_id = $tours_array[0]->tour_id;

//City Search
if($dest_id!=''){
    $sq_dest = mysql_fetch_assoc(mysql_query("select dest_name,dest_id from destination_master where dest_id = '$dest_id' and status!='Inactive'"));
    $query = "select * from custom_package_master where dest_id = '$dest_id' and status!='Inactive'";
}
//Hotel Search
if($tour_id!=''){
    $sq_tour = mysql_fetch_assoc(mysql_query("select package_id, package_name from custom_package_master where package_id='$tour_id' and status!='Inactive'"));
    $query = "select * from custom_package_master where package_id='$tour_id' and status!='Inactive'";
}
?>
<!-- ********** Component :: Page Title ********** -->
<div class="c-pageTitleSect">
  <div class="container">
    <div class="row">
      <div class="col-md-7 col-12">

        <!-- *** Search Head **** -->
        <div class="searchHeading">
          <span class="pageTitle">Combo Tours</span>

          <div class="clearfix for-transfer">
            <?php if($dest_id != ''){ ?>
            <div class="sortSection">
              <span class="sortTitle st-search">
                <i class="icon it itours-pin-alt"></i>
                Destination: <strong><?= $sq_dest['dest_name'] ?></strong>
              </span>
            </div>
            <?php } ?>
            <?php if($tour_id != ''){ ?>
            <div class="sortSection">
              <span class="sortTitle st-search">
                <i class="icon it itours-pin-alt"></i>
                Tour Name: <strong><?= $sq_tour['package_name'] ?></strong>
              </span>
            </div>
            <?php } ?>

          </div>

          <div class="clearfix">

            <div class="sortSection">
              <span class="sortTitle st-search">
                <i class="icon it itours-timetable"></i>
                Date: <strong><?= $checkDate ?></strong>
              </span>
            </div>

            <div class="sortSection">
              <span class="sortTitle st-search">
                <i class="icon it itours-person"></i>
                <?php echo $tours_array[0]->adult; ?> Adult, <?php echo $tours_array[0]->child_wobed + $tours_array[0]->child_wibed ; ?> Children, <?php echo $tours_array[0]->extra_bed; ?> Extra Bed, <?php echo $tours_array[0]->infant; ?> Infant
                <input type="hidden" id="total_passengers" value="<?= $tours_array[0]->adult.'-'.$tours_array[0]->child_wobed.'-'.$tours_array[0]->child_wibed.'-'.$tours_array[0]->extra_bed.'-'.$tours_array[0]->infant ?>"/>
              </span>
            </div>

          </div>

          <div class="clearfix">

            <div class="sortSection">
              <span class="sortTitle">
                <i class="icon it itours-sort-1"></i>
                Sort Tours by:
              </span>
              <div class="dropdown selectable">
                <button class="btn-dd dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Most Popular
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" onClick="get_price_filter_data('tours_result_array','1','fromRange_cost','toRange_cost');"> Price - High to Low</a>
                  <a class="dropdown-item" onClick="get_price_filter_data('tours_result_array','2','fromRange_cost','toRange_cost');"> Price - Low to High</a>
                </div>
              </div>
            </div>
            
            <div class="sortSection">
              <span class="sortTitle st-search">
                <i class="icon it itours-search"></i>
                <span>Showing <span class="results_count"></span></span>
              </span>
            </div>

          </div>
        </div>
        <!-- *** Search Head End **** -->

      </div>

      <div class="col-md-5 col-12 c-breadcrumbs">
        <ul>
          <li>
            <a href="#">Home</a>
          </li>
          <li class="st-active">
            <a href="#">Combo Tours Search Result</a>
          </li>
        </ul>
      </div>

    </div>
  </div>
</div>
<!-- ********** Component :: Page Title End ********** -->


<!-- ********** Component :: Tours Listing  ********** -->
<div class="c-containerDark">
  <div class="container">
      <!-- ********** Component :: Modify Filter  ********** -->
      <div class="row c-modifyFilter">
          <div class="col">
            <!-- Modified Search Filter -->
            <div class="accordion c-accordion" id="modifySearch_filter">
            <div class="card">

              <div class="card-header" id="headingThree">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#jsModifySearch_filter" aria-expanded="false" aria-controls="jsModifySearch_filter">
                Modify Search >> <span class="results_count"></span><?php echo ' available for '.$pax.' Pax'; ?>
                </button>
                <input type="hidden" value="<?= $pax ?>" id="total_pax"/>
              </div>
              <div id="jsModifySearch_filter" class="collapse" aria-labelledby="jsModifySearch_filter" data-parent="#modifySearch_filter">
                <div class="card-body">
                <form id="frm_tours_search">
                  <div class="row">
                        <input type='hidden' id='page_type' value='search_page' name='search_page'/>
                        <!-- *** Destination Name *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label>Select Destination</label>
                            <div class="c-select2DD">
                              <select id='tours_dest_filter' class="full-width js-roomCount" onchange="package_dynamic_reflect(this.id);">
                                <?php if($dest_id!=''){?>
                                  <option value="<?php echo $sq_dest['dest_id'] ?>"><?php echo $sq_dest['dest_name'] ?></option>
                                  <?php  } ?>
                                <option value="">Destination</option>
                                <?php 
                                $sq_query = mysql_query("select * from destination_master where status != 'Inactive'"); 
                                while($row_dest = mysql_fetch_assoc($sq_query)){ ?>
                                    <option value="<?php echo $row_dest['dest_id']; ?>"><?php echo $row_dest['dest_name']; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <!-- *** Destination Name End *** -->
                        <!-- *** tours Name *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label>Select Tour</label>
                            <div class="c-select2DD">
                              <select id='tours_name_filter' class="full-width js-roomCount">
                                <?php if($tour_id != ''){ ?>
                                  <option value="<?php echo $sq_tour['package_id'] ?>"><?php echo $sq_tour['package_name'] ?></option>
                                  <option value=''>Tour Name</option>
                                  <?php }
                                    else{
                                        if($dest_id!=''){
                                        $querys = "select * from custom_package_master where dest_id = '$dest_id' and status!='Inactive'"; }
                                        else{
                                        $querys = "select * from custom_package_master where 1 and status!='Inactive'";
                                        } ?>
                                        <option value="">Tour Name</option>
                                        <?php
                                        $sq_act = mysql_query($querys);
                                        while($row_act = mysql_fetch_assoc($sq_act)){
                                        ?>
                                        <option value="<?php echo $row_act['package_id'] ?>"><?php echo $row_act['package_name'] ?></option>
                                        <?php }
                                    } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <!-- *** tours Name End *** -->
                        
                        <!-- *** Date *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                          <div class="form-group">
                            <label>*Select Travel Date</label>
                            <div class="datepicker-wrap">
                              <input type="text" name="travelDate" class="input-text full-width" placeholder="mm/dd/yy" value="<?= $tours_array[0]->tour_date ?>" id="travelDate" required/>
                            </div>
                          </div>
                        </div>
                        <!-- *** Date End *** -->
                        
                        <!-- *** Adult *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>*Adults</label>
                            <div class="selector">
                            <select name="tadult" id='tadult' class="full-width" required>
                                <option value='<?= $tours_array[0]->adult ?>'><?= $tours_array[0]->adult ?></option>
                                <?php for($m=0;$m<=10;$m++){
                                   if($m != $tours_array[0]->adult){ ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php } } ?>
                            </select>
                            </div>
                        </div>
                        </div>
                        <!-- *** Adult End *** -->
                        <!-- *** Child W/o Bed *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Child Without Bed(2-5 Yrs)</label>
                            <div class="selector">
                            <select name="child_wobed" id='child_wobed' class="full-width">
                                <option value='<?= $tours_array[0]->child_wobed ?>'><?= $tours_array[0]->child_wobed ?></option>
                                <?php for($m=0;$m<=10;$m++){
                                   if($m != $tours_array[0]->child_wobed){ ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php } } ?>
                            </select>
                            </div>
                        </div>
                        </div>
                        <!-- *** Child W/o Bed End *** -->
                        <!-- *** Child With Bed *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Child With Bed(5-12 Yrs)</label>
                            <div class="selector">
                            <select name="child_wibed" id='child_wibed' class="full-width">
                                <option value='<?= $tours_array[0]->child_wibed ?>'><?= $tours_array[0]->child_wibed ?></option>
                                <?php for($m=0;$m<=10;$m++){
                                   if($m != $tours_array[0]->child_wibed){ ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php } } ?>
                            </select>
                            </div>
                        </div>
                        </div>
                        <!-- *** Child With Bed End *** -->
                        <!-- *** Extra Bed *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Extra Bed</label>
                            <div class="selector">
                            <select name="extra_bed" id='extra_bed' class="full-width">
                                <option value='<?= $tours_array[0]->extra_bed ?>'><?= $tours_array[0]->extra_bed ?></option>
                                <?php for($m=0;$m<=10;$m++){
                                   if($m != $tours_array[0]->extra_bed){ ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php } } ?>
                            </select>
                            </div>
                        </div>
                        </div>
                        <!-- *** Extra Bed End *** -->
                        <!-- *** Infant *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Infants(0-2 Yrs)</label>
                            <div class="selector">
                            <select name="tinfant" id='tinfant' class="full-width">
                                <option value='<?= $tours_array[0]->infant ?>'><?= $tours_array[0]->infant ?></option>
                                <?php for($m=0;$m<=10;$m++){
                                   if($m != $tours_array[0]->infant){ ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php } } ?>
                            </select>
                            </div>
                        </div>
                        </div>
                        <!-- *** Infant End *** -->
                        <div class="col-md-3 col-sm-6 col-12">
                            <button class="c-button lg colGrn m20-top">
                                <i class="icon itours-search"></i> SEARCH NOW
                            </button>
                        </div>
                    </div>
                </form>
                </div>
              </div>
            </div>
            </div>
            <!-- Modified Search Filter End -->
    </div>
    </div>

    <div class="row">
      <!-- ***** Tours Listing Filter ***** -->
      <div class="col-md-3 col-sm-12">
        <!-- ***** Price Filter ***** -->
        <div class="accordion c-accordion" id="filterPrice">
        <div class="card">

          <div class="card-header">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#jsFilterPrice" aria-expanded="true" aria-controls="jsFilterPrice">
                Price Range :(<span class='currency-icon'></span>)
              </button>
          </div>
          <div id="jsFilterPrice" class="collapse show" aria-labelledby="jsFilterPrice" data-parent="#filterPrice">
              <div class="card-body">
                <div class="c-priceRange">
                  <input type="hidden" class="slider-input" data-step="1"/>
                </div>
              </div>
            </div>
        </div>
        </div>
        <!-- ***** Price Filter End ***** -->
      </div>
      <!-- ***** Tours Listing Filter End ***** -->

      <!-- ***** Tours Listing ***** -->
        <?php
        $adult_count = $tours_array[0]->adult;
        $child_wocount = $tours_array[0]->child_wobed;
        $child_wicount = $tours_array[0]->child_wibed;
        $extra_bed = $tours_array[0]->extra_bed;
        $infant_count = $tours_array[0]->infant;
        $tours_result_array = array();
        $final_arr = array();

        $actual_ccosts_array = array();
        $all_costs_array = array();
        $sq_query = mysql_query($query);
        while(($row_query  = mysql_fetch_assoc($sq_query))){
            $hotels_array = array();
            $transport_array = array();
            $program_array = array();
            $package_id = $row_query['package_id'];
            $currency_id = $row_query['currency_id'];
            $taxation = json_decode($row_query['taxation']);
            $sq_dest = mysql_fetch_assoc(mysql_query("select dest_name from destination_master where dest_id = '$row_query[dest_id]' and status!='Inactive'"));
            $sq_terms = mysql_fetch_assoc(mysql_query("select terms_and_conditions from terms_and_conditions where type = 'Package Quotation' and active_flag!='Inactive'"));

            $sq_from = mysql_fetch_assoc(mysql_query("select * from roe_master where currency_id='$currency_id'"));
            $from_currency_rate = $sq_from['currency_rate'];

            //Single Hotel Image
            $sq_singleImage = mysql_fetch_assoc(mysql_query("select * from default_package_images where dest_id='$row_query[dest_id]'"));
            if($sq_singleImage['image_url']!=''){
                $image = $sq_singleImage['image_url'];
                $pos = strstr($url,'uploads');
                if ($pos != false){
                    $newUrl1 = preg_replace('/(\/+)/','/',$image); 
                    $newUrl = BASE_URL.str_replace('../', '', $newUrl1);
                }
                else{
                    $newUrl =  $image; 
                }
            }else{
                $newUrl = BASE_URL.'images/dummy-image.jpg';
            }
            //Package Hotels
            $sq_hotel = mysql_query("select * from custom_package_hotels where package_id = '$row_query[package_id]'");
            while($row_hotel = mysql_fetch_assoc($sq_hotel)){
              $sq_hcity = mysql_fetch_assoc(mysql_query("select city_name,city_id from city_master where city_id = '$row_hotel[city_name]'"));
              $sq_hhotel = mysql_fetch_assoc(mysql_query("select hotel_name,hotel_id from hotel_master where hotel_id = '$row_hotel[hotel_name]'"));
              array_push($hotels_array,array(
                'city' => $sq_hcity['city_name'],
                'hotel' => $sq_hhotel['hotel_name'],
                'hotel_type' => $row_hotel['hotel_type'],
                'nights' => $row_hotel['total_days'],
              ));
            }
            //Package Transports
            $sq_trans = mysql_query("select * from custom_package_transport where package_id = '$row_query[package_id]'");
            while($row_trans = mysql_fetch_assoc($sq_trans)){
              $sq_vehicle = mysql_fetch_assoc(mysql_query("select bus_id,bus_name from transport_agency_bus_master where bus_id = '$row_trans[vehicle_name]'"));
              array_push($transport_array,array(
                'vehicle' => $sq_vehicle['bus_name'],
              ));
            }
            //Package Program
            $sq_prg = mysql_query("select * from custom_package_program where package_id = '$row_query[package_id]'");
            while($row_prg = mysql_fetch_assoc($sq_prg)){
              array_push($program_array,array(
                'attraction' => $row_prg['attraction'],
                'day_wise_program' => $row_prg['day_wise_program'],
                'stay' => $row_prg['stay'],
                'meal_plan' => $row_prg['meal_plan'],
              ));
            }
            
            $total_cost1 = ($adult_count*$row_query['adult_cost']) + ($child_wocount*$row_query['child_without']) + ($child_wicount*$row_query['child_with']) + ($extra_bed*$row_query['extra_bed']) + ($infant_count*$row_query['infant_cost']);

            $total_cost1 = ceil($total_cost1);
            array_push($all_costs_array,array('amount' => $total_cost1,'id'=>$currency_id));
            $c_amount1 = $from_currency_rate / $to_currency_rate * $total_cost1;
            array_push($actual_ccosts_array,$c_amount1);

            //Final cost push into array
            array_push($tours_result_array,array(
              'image' => $newUrl,
              "package_id"=>$row_query['package_id'],
              "package_name"=>$row_query['package_name'],
              "package_code"=>$row_query['package_code'],
              "dest_name"=>$sq_dest['dest_name'],
              'adult_cost'=>$row_query['adult_cost'],
              'child_without'=>$row_query['child_without'],
              'child_with'=>$row_query['child_with'],
              'extra_bed'=>$row_query['extra_bed'],
              'infant_cost'=>$row_query['infant_cost'],
              "total_cost"=>$total_cost1,
              'taxation'=>$taxation,
              'total_nights' => $row_query['total_nights'],
              'total_days' => $row_query['total_days'],
              'inclusions'=>$row_query['inclusions'],
              'exclusions'=> $row_query['exclusions'],
              'terms_condition'=>$sq_terms['terms_and_conditions'],
              "currency_id" => $currency_id,
              "best_lowest_cost"=>array('id'=>$currency_id,
                                        'cost'=>$total_cost1),
              "hotels_array" =>$hotels_array,
              "transport_array"=>$transport_array,
              "program_array"=>$program_array
            ));

        }
        $all_costs_array1 = $array_master->array_column($all_costs_array, 'amount');
        $min_array = $all_costs_array[array_search(min($all_costs_array), $all_costs_array)];
        $max_array = $all_costs_array[array_search(max($all_costs_array), $all_costs_array)];
        ?>
        <input type='hidden' value='<?= json_encode($tours_result_array,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>' id='tours_result_array' name='tours_result_array'/>
        <input type='hidden' class='best-cost-currency' id='bestlow_cost' value='<?= min($actual_ccosts_array)?>'/>
        <input type='hidden' class='best-cost-currency' id='besthigh_cost' value='<?= max($actual_ccosts_array) ?>'/>
        <input type='hidden' class='best-cost-id' id='bestlow_cost' value='<?= $min_array['id'] ?>'/>
        <input type='hidden' class='best-cost-id' id='besthigh_cost' value='<?= $max_array['id']  ?>'/>
        <input type="hidden" id='price_rangevalues'/>
      
        <div class="col-md-9 col-sm-12">
            <div id="tours_result_block">

            </div>
        </div>
      <!-- ***** Tours Listing End ***** -->
    </div>
  </div>
</div>
<!-- ********** Component :: Tours Listing End ********** -->
<?php include '../../layouts/footer.php'; ?>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="../../js/jquery.range.min.js"></script>
<script type="text/javascript" src="../../js/pagination.min.js"></script>
<script>
  $('#travelDate').datetimepicker({ timepicker:false,format:'m/d/Y',minDate:new Date() });
  $(document).ready(function () {
    $('body').delegate('.lblfilterChk','click', function(){
      get_price_filter_data('tours_result_array','3','0','0');
    })
  });
// Get currency changed values in hotel result
function get_currency_change(currency_id,JSONItems,fromRange_cost,toRange_cost,get_price_filter_data_result){
  var base_url = $('#base_url').val();
  var final_arr = [];
  
  JSONItems.forEach(function (item){
    var currency_rates = get_currency_rates(item.best_lowest_cost.id,currency_id).split('-');
    var to_currency_rate =  currency_rates[0];
    var from_currency_rate = currency_rates[1];
    var amount = parseFloat(to_currency_rate / from_currency_rate * item.best_lowest_cost.cost).toFixed(2);
    if(compare(amount,fromRange_cost,toRange_cost)){
      final_arr.push(item);
    }
  });
  get_price_filter_data_result(final_arr);
}

// Get Hotel results data
function get_price_filter_data(tours_result_array,type,fromRange_cost,toRange_cost){
  var base_url = $('#base_url').val();

  setTimeout(() => {
    var selected_value = document.getElementById(tours_result_array).value;
    var JSONItems = JSON.parse(selected_value);
    var final_arr = [];
    if (typeof Storage !== 'undefined') {
      if (localStorage) {
        var currency_id = localStorage.getItem('global_currency');
      } else {
        var currency_id = window.sessionStorage.getItem('global_currency');
      }
    }
    if(type==1){
      final_arr = (JSONItems).sort(function(a,b){
        //First Value
        var currency_rates = get_currency_rates(a.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var aamount = parseFloat(to_currency_rate / from_currency_rate * a.best_lowest_cost.cost).toFixed(2);
        //Second value      
        var currency_rates = get_currency_rates(b.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var bamount = parseFloat(to_currency_rate / from_currency_rate * b.best_lowest_cost.cost).toFixed(2);

        return bamount-aamount;
      });
      get_price_filter_data_result(final_arr);
    }
    else if(type==2){
      final_arr = (JSONItems).sort(function(a,b){
        //First Value
        var currency_rates = get_currency_rates(a.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var aamount = parseFloat(to_currency_rate / from_currency_rate * a.best_lowest_cost.cost).toFixed(2);
        //Second value      
        var currency_rates = get_currency_rates(b.best_lowest_cost.id,currency_id).split('-');
        var to_currency_rate =  currency_rates[0];
        var from_currency_rate = currency_rates[1];
        var bamount = parseFloat(to_currency_rate / from_currency_rate * b.best_lowest_cost.cost).toFixed(2);

        return aamount - bamount;
      });
      get_price_filter_data_result(final_arr);
    }
    else if(type==3){
      
			var vehicle_type_array = [];
			var checkboxes = document.getElementsByName('vehicle_type');
			for (var checkbox of checkboxes) {
				if (checkbox.checked)
				vehicle_type_array.push(checkbox.value);
      }
      final_arr = (JSONItems).filter(function(a){
        return vehicle_type_array.includes(a.vehicle_type);
      });
      $('#selected_vehicle_type_array').val(vehicle_type_array);
      get_price_filter_data_result(final_arr);
    }
    else{
      var currency_id = $('#currency').val();
      get_currency_change(currency_id,JSONItems,fromRange_cost,toRange_cost,get_price_filter_data_result);    
    }
    
  }, 1000);
}
//Display Hotel results data 
function get_price_filter_data_result(final_arr){
  var base_url = $('#base_url').val();
  $.post(base_url+'Tours_B2B/view/tours/tours_results.php', { final_arr: final_arr }, function (data) {
    $('#tours_result_block').html(data);
	});
}
get_price_filter_data('tours_result_array','2','0','0');

///////////Debounce function for range slider////////////
function getSliderValue(){
  var ranges = $('.slider-input').val().split(',');
  
  $('.slider-input').attr({
    min: parseFloat(ranges[0]).toFixed(2),
    max: parseFloat(ranges[1]).toFixed(2)
  });
  if(ranges[0]!='' && ranges[1]!='' && ranges[0]!=='NaN' && ranges[1]!=='NaN'){
    get_price_filter_data('tours_result_array','4',ranges[0],ranges[1]);
  }
}
const setSliderValue = function (fun) {
	let timer;
	return function () {
		let context = this;
		args = arguments;
		clearTimeout(timer);
		timer = setTimeout(() => {
			fun.apply(context, args);
		}, 800);
	};
};
const passSliderValue = setSliderValue(getSliderValue);
//Make session for best hotel costs
clearTimeout(a);
var a = setTimeout(function() {

  var best_price_list = document.querySelectorAll(".best-cost-currency");
  var best_price_id = document.querySelectorAll(".best-cost-id");
  var bestAmount_arr = [];
  for(var i=0;i<best_price_list.length;i++){
    bestAmount_arr.push({
      'amount':best_price_list[i].value,
      'id':best_price_id[i].value});
  }
  sessionStorage.setItem('tours_best_price',JSON.stringify(bestAmount_arr));
},100);
</script>
