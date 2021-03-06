<?php
include "../../../model/model.php";
$package_id = $_POST['package_id'];
$sq_pckg = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$package_id'"));
$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id='$sq_pckg[currency_id]'"));
$taxation = json_decode($sq_pckg['taxation']);
?>

<form id="frm_package_master_update">

<div class="modal fade" id="update_modal1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Update Package</h4>

      </div>

      <div class="modal-body">

      <input type="hidden" value="<?php echo $sq_pckg['package_id']; ?>" id="package_id1">

        <div class="row mg_bt_20">        
            <div class="col-md-3 col-sm-3"> 
              <?php $sq_query_dest = mysql_fetch_assoc(mysql_query("select * from destination_master where dest_id = '$sq_pckg[dest_id]'"));
              ?>
              <input id="dest_name_u"  name="dest_name_u" title="Select Destination" class="form-control" value="<?php echo $sq_query_dest['dest_name']; ?>" readonly/>
            </div>  
        <?php $status = ($sq_pckg['tour_type'] != '')?'readonly':'';?>
        <div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
            <select name="tour_type" id="tour_type" title="Tour Type" onchange="incl_reflect(this.id,'1')" <?= $status?>>
            <?php if($sq_pckg['tour_type'] != ''){ ?>
                <option value="<?= $sq_pckg['tour_type'] ?>"><?= $sq_pckg['tour_type'] ?></option>
            <?php }
            else{ ?>
                <option value="">Tour Type</option>
                <option value="Domestic">Domestic</option>
                <option value="International">International</option>
            <?php } ?>
            </select>
        </div> 
        <div class="col-md-2 mg_bt_10">
            <select name="currency_code" id="currency_code1" title="Currency" style="width:100%">
            
            <option value='<?= $sq_currency['id'] ?>'><?= $sq_currency['currency_code'] ?></option>
            <?php
                $sq_currency = mysql_query("select * from currency_name_master order by default_currency desc");
                while($row_currency = mysql_fetch_assoc($sq_currency)){
                ?>
                <option value="<?= $row_currency['id'] ?>"><?= $row_currency['currency_code'] ?></option>
                <?php } ?>
            </select>
        </div> 
          <div class="col-md-2 col-sm-6 col-xs-12 mg_bt_10">
              <select name="taxation_type1" id="taxation_type1" title="Taxation Type" required>
              <option value="<?= $taxation[0]->taxation_type ?>"><?= $taxation[0]->taxation_type ?></option>
              <?php get_taxation_type_dropdown($setup_country_id) ?>
              </select>
          </div>
          <div class="col-md-2 col-sm-6 col-xs-12 mg_bt_10">
              <select name="taxation_id1" id="taxation_id1" title="Tax" onchange="generic_tax_reflect(this.id, 'service_tax1', 'calculate_total_amount', '1');" required>
                  <?php 
                  if($taxation[0]->taxation_id!='0'){
                      $taxation_id = $taxation[0]->taxation_id;
                      $sq_taxation = mysql_fetch_assoc(mysql_query("select * from taxation_master where taxation_id='$taxation_id'"));
                      $sq_tax_type = mysql_fetch_assoc(mysql_query("select * from tax_type_master where tax_type_id='$sq_taxation[tax_type_id]'"));
                      ?>
                  <option value="<?= $sq_taxation['taxation_id'] ?>"><?= $sq_tax_type['tax_type'].'-'.$sq_taxation['tax_in_percentage'] ?></option> 
                  <?php } ?>
                  <?php get_taxation_dropdown(); ?>
              </select>
              <input type="hidden" id="service_tax1" name="service_tax1" value="<?= $taxation[0]->service_tax ?>">
          </div>
      </div>

        <div class="row mg_bt_20"> 

            <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

                <input type="text" id="package_name1" name="package_name" class="form-control"  placeholder="Package Name" title="Package Name" value="<?php echo $sq_pckg['package_name']; ?>" />
                <small>Note : Package Name : eg. Kerala amazing</small>

            </div>    

            <div class="col-md-3 col-sm-6 mg_bt_10_xs"> 

                <input type="text" id="package_code1" name="package_code1" class="form-control" placeholder="Package Code" title="Package Code" value="<?php echo $sq_pckg['package_code']; ?>"/>
                <small>Note : Package Code : eg. Ker001</small>

            </div>                  

            <div class="col-md-2 col-sm-6 mg_bt_10_xs"> 

                <input type="text" id="total_nights1" name="total_nights1" class="form-control" onchange="validate_balance(this.id); calculate_days()" value="<?php echo $sq_pckg['total_nights']; ?>" placeholder="Total Nights" title="Total Nights"/>

            </div>   

           <div class="col-md-2 col-sm-6 mg_bt_10_xs"> 
                <input type="text" id="total_days1" name="total_days1" class="form-control" placeholder="Total Days" title="Total Days" value="<?php echo $sq_pckg['total_days']; ?>" readonly/>
           </div>
            <div class="col-md-2 col-sm-6"> 
                <select id="status1"  name="status1" title="Status" class="form-control">
                  <option value="<?php echo $sq_pckg['status']; ?>"><?php echo $sq_pckg['status']; ?></option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div class="row mg_bt_20">
          <div class="col-md-2 col-sm-3 mg_bt_10_xs"> 
                <input type="text" id="adult_cost1" name="adult_cost" class="form-control"  placeholder="Adult Cost" title="Adult Cost" value="<?php echo $sq_pckg['adult_cost']; ?>"/>
          </div>   
          <div class="col-md-2 col-sm-3 mg_bt_10_xs"> 
                <input type="text" id="child_cost1" name="child_cost" class="form-control"  placeholder="Child Cost" title="Child Cost" value="<?php echo $sq_pckg['child_cost']; ?>"/>
          </div>
          <div class="col-md-2 col-sm-3 mg_bt_10_xs">
              <input type="text" id="infant_cost1" name="infant_cost" onchange="validate_balance(this.id);" class="form-control"  placeholder="Infant Cost" title="Infant Cost" value="<?php echo $sq_pckg['infant_cost']; ?>" />
          </div>
          <div class="col-md-2 col-sm-3 mg_bt_10_xs">
              <input type="text" id="child_with1" name="child_with" onchange="validate_balance(this.id);" class="form-control"  placeholder="Child with Bed Cost" title="Child with Bed Cost"  value="<?php echo $sq_pckg['child_with']; ?>"/>
          </div>
          <div class="col-md-2 col-sm-3 mg_bt_10_xs">
              <input type="text" id="child_without1" name="child_without" onchange="validate_balance(this.id);" class="form-control"  placeholder="Child w/o Bed Cost" title="Child w/o Bed Cost" value="<?php echo $sq_pckg['child_without']; ?>" />
          </div>
          <div class="col-md-2 col-sm-3 mg_bt_10_xs">
              <input type="text" id="extra_bed1" name="extra_bed" onchange="validate_balance(this.id);" class="form-control"  placeholder="Extra Bed Cost" title="Extra Bed Cost" value="<?php echo $sq_pckg['extra_bed']; ?>" />
          </div>
      </div>

        <h3 class="editor_title">Tour Itinerary</h3>
        <div class="panel panel-default panel-body app_panel_style mg_bt_30">
            <div class="col-xs-12 text-right mg_tp_10">
              <button type="button" class="btn btn-info btn-sm ico_left mg_bt_10" onclick="addRow('dynamic_table_list_update')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
            </div>
            <div id="div_list1">
              <table style="width:100%" id="dynamic_table_list_update" name="dynamic_table_list_update">
                <?php
                $count = 1;
                $query = "select * from custom_package_program where package_id = '$package_id'";
                $sq_pckg_a = mysql_query($query);
                while($sq_pckg1 = mysql_fetch_assoc($sq_pckg_a)){ ?>
                  <tr>
                    <td width="27px;"><input class="css-checkbox mg_bt_10 labelauty" id="chk_program<?php echo $count; ?>" type="checkbox" checked autocomplete="off" data-original-title="" title="" aria-hidden="true" style="display: none;"><label for="chk_program<?php echo $count; ?>"><span class="labelauty-unchecked-image"></span><span class="labelauty-checked-image"></span></label><label class="css-label" for="chk_program1"> </label></td>
                    <td width="50px;"><input maxlength="15" value="<?php echo $count; ?>" type="text" name="username" placeholder="Sr. No." class="form-control mg_bt_10" disabled="" autocomplete="off" data-original-title="" title=""></td>
                    <td class='col-md-3 pad_8' style='width:100px'><input type="text" id="special_attaraction<?php echo $count; ?>" name="special_attaraction" class="form-control mg_bt_10" placeholder="*Special Attraction" title="Special Attraction" onchange="validate_spaces(this.id);validate_spattration(this.id);"  value="<?php echo $sq_pckg1['attraction']; ?>"></td>

                    <td class='col-md-7 pad_8' style="max-width: 594px;overflow: hidden;"><textarea id="day_program<?php echo $count; ?>" name="day_program" class="form-control mg_bt_10" placeholder="*Day<?php echo $count++;?> Program" title="Day-wise Program"  onchange="validate_spaces(this.id);validate_dayprogram(this.id);" rows="3" value="<?php echo $sq_pckg1['day_wise_program']; ?>"><?php echo $sq_pckg1['day_wise_program']; ?></textarea></td>

                    <td class='col-md-2 pad_8' style='width:100px'><input type="text" id="overnight_stay<?php echo $count; ?>" name="overnight_stay" class="form-control mg_bt_10"  onchange="validate_spaces(this.id);validate_onstay(this.id);" placeholder="*Overnight Stay" title="Overnight Stay"  value="<?php echo $sq_pckg1['stay']; ?>"></td>

                    <td class='col-md-1 pad_8'><select id="meal_plan<?php echo $count; ?>" title="Meal Plan" name="meal_plan" class="form-control mg_bt_10">
                      <?php if($sq_pckg1['meal_plan']!=''){ ?>
                            <option value="<?php echo $sq_pckg1['meal_plan']; ?>"><?php echo $sq_pckg1['meal_plan']; ?></option>
                            <?php } ?>
                            <?php get_mealplan_dropdown(); ?>
                            </select>
                    </td>
                    <td class="hidden"><input type="text" value="<?php echo $sq_pckg1['entry_id']; ?>"></td>
                  </tr>
                  <?php  }  ?>
              </table>
            </div>  
          </div>

        <h3 class="editor_title">Hotel Information</h3>
        <div class="panel panel-default panel-body app_panel_style">
        <small>Note -  Pls ensure you added city wise hotel & tariff using Supplier Master</small>
        <div class="col-xs-12 text-right mg_tp_10">
          <button type="button" class="btn btn-info btn-sm ico_left mg_bt_10" onClick="addRow('tbl_package_hotel_master')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
        </div>
         <table id="tbl_package_hotel_master" name="tbl_package_hotel_master" class="table border_0 table-hover" style="padding: 0 !important;">
         <?php
         $sq_count = mysql_num_rows(mysql_query("select * from custom_package_hotels where package_id = '$package_id'"));
         if($sq_count==0){ ?>
              <tr>
                <td><input id="chk_dest" type="checkbox" checked></td>
                <td><input maxlength="15" value="1" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                <td><select id="city_name" name="city_name1" onchange="hotel_name_list_load(this.id);" class="city_master_dropdown app_select2" style="width:100%" title="Select City Name">
                      <?php get_cities_dropdown(); ?>
                    </select></td>
                <td><select id="hotel_name" name="hotel_name1" onchange="hotel_type_load(this.id);" style="width:100%" title="Select Hotel Name">
                      <option value="">*Hotel Name</option>
                    </select></td>
                <td><input type="text" id="hotel_type" name="hotel_type1" placeholder="*Hotel Type" title="Hotel Type" readonly></td>
                <td><input type="text" id="hotel_tota_days1" onchange="validate_balance(this.id)" name="hotel_tota_days1" placeholder="*Total Night" title="Total Night"></td>
            </tr>
            <script type="text/javascript">
              $('#city_name').select2(); </script>
         <?php }
         else{ $count_hotel =0;
         $sq_pckg_hotel = mysql_query("select * from custom_package_hotels where package_id = '$package_id'");
         while($row_hotel = mysql_fetch_assoc($sq_pckg_hotel)){
            $count_hotel++;
            $sq_pckg = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id = '$row_hotel[hotel_name]'"));   
            $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id = '$row_hotel[city_name]'"));   
         ?>
              <tr>
                  <td><input id="chk_dest<?php echo $count_hotel; ?>" type="checkbox" checked></td>
                  <td><input maxlength="15" value="<?php echo $count_hotel; ?>" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>
                  <td><select id="city_name<?php echo $count_hotel; ?>-u" name="city_name1" onchange="hotel_name_list_load(this.id);" class="city_master_dropdown app_select2" style="width:100%" title="Select City Name">
                      <option value="<?php echo $sq_city['city_id']; ?>"><?php echo $sq_city['city_name']; ?></option>
                        <?php get_cities_dropdown(); ?>
                      </select></td>
                  <td><select id="hotel_name<?php echo $count_hotel; ?>-u" name="hotel_name1" onchange="hotel_type_load(this.id);" style="width:100%" title="Select Hotel Name">
                         <option value="<?php echo $sq_pckg['hotel_id']; ?>"><?php echo $sq_pckg['hotel_name']; ?></option>
                      </select></td>
                  <td><input type="text" id="hotel_type<?php echo $count_hotel; ?>-u" name="hotel_type1" value="<?php echo $row_hotel['hotel_type']; ?>" placeholder="Hotel Type" title="Hotel Type" readonly></td>
                  <td><input type="text" id="hotel_tota_days1" value="<?php echo $row_hotel['total_days']; ?>" name="hotel_tota_days1" placeholder="Total Night" onchange="validate_balance(this.id);" title="Total Night"></td>
                  <td class="hidden"><input type="text" value="<?php echo $row_hotel['entry_id']; ?>"></td>
              </tr> 
              <script type="text/javascript">
              $('#city_name<?php echo $count_hotel; ?>').select2(); </script>
              <?php } } ?>
          </table>
        </div>

        <h3 class="editor_title">Transport Information</h3>
        <div class="panel panel-default panel-body app_panel_style">
        <div class="col-xs-12 text-right mg_tp_10">
          <button type="button" class="btn btn-info btn-sm ico_left mg_bt_10" onClick="addRow('tbl_package_tour_transport')"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
        </div>
         <table id="tbl_package_tour_transport" name="tbl_package_tour_transport" class="table border_0 table-hover" style="padding: 0 !important;">
         <?php
          $sq_count = mysql_num_rows(mysql_query("select * from custom_package_transport where package_id = '$package_id'"));
          if($sq_count == 0){ ?>
            <tr>
                <td><input class="css-checkbox labelauty" id="chk_transport1" type="checkbox" checked="" autocomplete="off" data-original-title="" title="" aria-hidden="true" style="display: none;"><label for="chk_transport1"><span class="labelauty-unchecked-image"></span><span class="labelauty-checked-image"></span></label><label class="css-label" for="chk_transport1"> </label></td>
                <td class="col-md-1"><input maxlength="15" value="1" type="text" name="username" placeholder="Sr No." class="form-control" disabled="" autocomplete="off" data-original-title="" title="" ></td>
                <td class="col-md-3"><select name="vehicle_name1" id="vehicle_name1" style="width:100%" class="form-control app_select2" onchange="get_transport_cost(this.id)">
                <option value="">Select Vehicle</option>
                <?php
                $sq_query = mysql_query("select * from transport_agency_bus_master where active_flag != 'Inactive'"); 
                while($row_dest = mysql_fetch_assoc($sq_query)){ ?>
                    <option value="<?php echo $row_dest['bus_id']; ?>"><?php echo $row_dest['bus_name']; ?></option>
                <?php } ?></select></td>
                <td><input type="text" name="cost1" placeholder="Cost" id="cost1" class="form-control"></td>
            </tr>
          <?php }
          else{
          $count_hotel =0;
          $sq_pckgtr = mysql_query("select * from custom_package_transport where package_id = '$package_id'");
          while($row_tr = mysql_fetch_assoc($sq_pckgtr)){
            $count_hotel++;
            $sq_transport = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id = '$row_tr[vehicle_name]'"));
          ?>
              <tr>
                  <td><input id="chk_transport1<?php echo $count_hotel; ?>" type="checkbox" checked></td>
                  <td class="col-md-1"><input maxlength="15" value="<?php echo $count_hotel; ?>" type="text" name="no" placeholder="Sr. No." class="form-control" disabled /></td>           
                  <td class="col-md-3"><select name="vehicle_name1-u" id="vehicle_name1<?php echo $count_hotel; ?>-u" style="width:100%" class="form-control app_select2" onchange="get_transport_cost(this.id)">
                    <option value="<?php echo $sq_transport['bus_id']; ?>"><?php echo $sq_transport['bus_name']; ?></option>
                    <option value="">Select Vehicle</option>
                    <?php
                    $sq_query = mysql_query("select * from transport_agency_bus_master where active_flag != 'Inactive'"); 
                    while($row_dest = mysql_fetch_assoc($sq_query)){ ?>
                        <option value="<?php echo $row_dest['bus_id']; ?>"><?php echo $row_dest['bus_name']; ?></option>
                    <?php } ?></select></td>
                  <td><input type="text" name="cost1-u" placeholder="Cost" id="cost1<?php echo $count_hotel; ?>-u" class="form-control" value="<?= $row_tr['cost'] ?>"></td>
                  <td class="hidden"><input type="text" value="<?php echo $row_tr['entry_id']; ?>"></td>
              </tr>
              <script type="text/javascript">
                $('#vehicle_name1<?php echo $count_hotel; ?>-u').select2();
              </script>
              <?php } } ?>
          </table>
        </div>
        <div class="row mg_bt_20">
            <div class="col-md-6 col-sm-6 mg_bt_10_sm_xs">
                <h3 class="editor_title">Inclusions</h3>
                <textarea  class="feature_editor" id="inclusions1" name="inclusions" placeholder="Inclusions" title="Inclusions"></textarea>
            </div>
            <div class="col-md-6 col-sm-6"> 
                <h3 class="editor_title">Exclusions</h3>
                <textarea class="feature_editor" id="exclusions1" name="exclusions" class="form-control"  placeholder="Exclusions" title="Exclusions"></textarea>
            </div>
        </div>
        <div class="row mg_bt_10 mg_tp_20 text-center">
            <div class="col-md-12">
              <button class="btn btn-sm btn-success" id="btn_update"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
            </div>
        </div>
      </div>
    </div>
  </div>

</div>

</form>

<script>
$('#update_modal1').modal('show');
$('#vehicle_name1,#currency_code1').select2();
function inclexcl_reflect(offset=''){
  var package_id = $("#package_id1").val();
  var base_url = $("#base_url").val();
  $.post('inclusion_reflect.php', {package_id : package_id }, function(data){
      var incl_arr = JSON.parse(data);
      var incl_id = 'inclusions'+offset;
      var excl_id = 'exclusions'+offset;

      var $iframe = $('#'+incl_id+'-wysiwyg-iframe');
      $iframe.contents().find("body").html('');
      $iframe.ready(function(){
        $iframe.contents().find("body").append(incl_arr['includes']);
      });

      var $iframe1 = $('#'+excl_id+'-wysiwyg-iframe');
      $iframe1.contents().find("body").html('');
      $iframe1.ready(function() {
        $iframe1.contents().find("body").append(incl_arr['excludes']);
      });
    });
}
inclexcl_reflect('1');
/////////////********** Package Master Information Save start **********/////////////
function calculate_days(){
   var total_nights = $("#total_nights1").val();
   var days = parseInt(total_nights) + 1;
   $("#total_days1").val(days);
}

$(function(){

  $('#frm_package_master_update').validate({

    rules:{
        package_name : { required: true },
        total_days : { required: true, number:true },
        total_nights : { required: true, number:true },          
    },

    submitHandler:function(form){

        var base_url = $('#base_url').val();
        var currency_id = $('#currency_code1').val();
        var taxation_type = $('#taxation_type1').val();
        var taxation_id = $('#taxation_id1').val();
        var service_tax = $('#service_tax1').val();
        if(service_tax == ''){
          error_msg_alert('Select Tax(%)!'); return false;
        }
        var package_id = $("#package_id1").val();
        var package_code = $("#package_code1").val();
        var package_name = $("#package_name1").val();
        var total_days = $("#total_days1").val();
        var total_nights = $("#total_nights1").val();
        var adult_cost = $("#adult_cost1").val();
        var child_cost = $("#child_cost1").val();
        var infant_cost = $("#infant_cost1").val();
        var child_with = $("#child_with1").val();
        var child_without = $("#child_without1").val();
        var extra_bed = $("#extra_bed1").val();
        var status = $("#status1").val();
        var transport_id = $("#transport_name2").val();
        var inclusions = $("#inclusions1").val();
        var exclusions = $("#exclusions1").val();

        var checked_programe_arr = new Array();
        var day_program_arr = new Array();
        var special_attaraction_arr = new Array();
        var overnight_stay_arr = new Array();
        var meal_plan_arr = new Array();
        var entry_id_arr = new Array();

        var table = document.getElementById("dynamic_table_list_update");

        var rowCount = table.rows.length;
            for(var i=0; i<rowCount; i++){ 

                var row = table.rows[i];
                var checked_programe = row.cells[0].childNodes[0].checked;
                var special_attaraction = row.cells[2].childNodes[0].value;         
                var day_program = row.cells[3].childNodes[0].value;         
                var overnight_stay = row.cells[4].childNodes[0].value;         
                var meal_plan = row.cells[5].childNodes[0].value;  
                if(row.cells[6]){
                  var entry_id = row.cells[6].childNodes[0].value; 
                }else{
                  var entry_id = '';
                }         
                if(day_program==""){
                    error_msg_alert('Daywise program is mandatory in row'+(i+1));
                    return false;
                }
                var flag1 = validate_spattration(row.cells[2].childNodes[0].id);
                var flag2 = validate_dayprogram(row.cells[3].childNodes[0].id);
                var flag3 = validate_onstay(row.cells[4].childNodes[0].id);         
                if(!flag1 || !flag2 || !flag3){
                    return false;
                }
              
                checked_programe_arr.push(checked_programe);
                special_attaraction_arr.push(special_attaraction);
                day_program_arr.push(day_program);
                overnight_stay_arr.push(overnight_stay);  
                meal_plan_arr.push(meal_plan);  
                entry_id_arr.push(entry_id);
            }
       

        //Hotel information
        var total_night = 0;
        var hotel_check_arr = new Array();
        var city_name_arr = new Array();
        var hotel_name_arr = new Array();
        var hotel_type_arr = new Array();
        var total_days_arr = new Array();
        var hotel_entry_id_arr = new Array();

        var table = document.getElementById("tbl_package_hotel_master");
        var rowCount = table.rows.length;

        for(var i=0; i<rowCount; i++){

            var row = table.rows[i];
            var check_id = row.cells[0].childNodes[0].checked;
            var city_name = row.cells[2].childNodes[0].value;
            var hotel_name = row.cells[3].childNodes[0].value;
            var hotel_type = row.cells[4].childNodes[0].value;
            var total_days1 = row.cells[5].childNodes[0].value;
            if(row.cells[6]){
              var hotel_entry_id = row.cells[6].childNodes[0].value;
            }
            else{
              var hotel_entry_id ='';
            }
            total_night = parseFloat(total_night) + parseFloat(row.cells[5].childNodes[0].value);

            if(check_id == 'true'){
              if(city_name =='') {error_msg_alert("City Name is required"); return false;}
              if(hotel_name =='') {error_msg_alert("Hotel Name is required"); return false;}
              if(hotel_type =='') {error_msg_alert("Hotel Type is required"); return false;}
              if(total_days =='') {error_msg_alert("Total Days is required"); return false;}
            }

            hotel_check_arr.push(check_id);
            city_name_arr.push(city_name);
            hotel_name_arr.push(hotel_name);
            hotel_type_arr.push(hotel_type);
            total_days_arr.push(total_days1);
            hotel_entry_id_arr.push(hotel_entry_id);
        }
        if(parseFloat(total_night) != parseFloat(total_nights)){
            error_msg_alert("Total Nights doesn't match!");
            g_validate_status = false; 
            return false
        }

        //Transport information
        var vehicle_check_arr = new Array();
        var vehicle_name_arr = new Array();
        var cost_name_arr = new Array();
        var tr_entry_arr = new Array();
        var table = document.getElementById("tbl_package_tour_transport");
        var rowCount = table.rows.length;
        for(var i=0; i<rowCount; i++){
          var row = table.rows[i];

          var check_id = row.cells[0].childNodes[0].checked;
          var vehicle_name = row.cells[2].childNodes[0].value;
          var cost = row.cells[3].childNodes[0].value;
          if(row.cells[4]){
            var entry_id = row.cells[4].childNodes[0].value;
          }
          else{
            var entry_id ='';
          }
 
          if(check_id == 'true'){  
            if(vehicle_name==""){
                error_msg_alert('Transport Vehicle is mandatory in row'+(i+1));
                return false;
            }
          }

          vehicle_check_arr.push(check_id);
          vehicle_name_arr.push(vehicle_name);
          cost_name_arr.push(cost);
          tr_entry_arr.push(entry_id);
        }

        $('#btn_update').button('loading');   
     
        $.post(
                   base_url+"controller/custom_packages/package_master_update.php",

                   { package_id : package_id,currency_id:currency_id,taxation_type:taxation_type,taxation_id:taxation_id,service_tax:service_tax, package_code : package_code, package_name : package_name, total_days : total_days, total_nights : total_nights, status : status, transport_id : transport_id, city_name_arr : city_name_arr, hotel_name_arr : hotel_name_arr, hotel_type_arr : hotel_type_arr, total_days_arr : total_days_arr,hotel_check_arr:hotel_check_arr,inclusions : inclusions, exclusions : exclusions,checked_programe_arr:checked_programe_arr, day_program_arr : day_program_arr, special_attaraction_arr : special_attaraction_arr,overnight_stay_arr : overnight_stay_arr,meal_plan_arr : meal_plan_arr, entry_id_arr : entry_id_arr, hotel_entry_id_arr : hotel_entry_id_arr,vehicle_name_arr:vehicle_name_arr,vehicle_check_arr:vehicle_check_arr,cost_name_arr:cost_name_arr,tr_entry_arr:tr_entry_arr,child_cost : child_cost,adult_cost : adult_cost,infant_cost: infant_cost,child_with : child_with,child_without: child_without,extra_bed:extra_bed },

                   function(data){                   
                    var msg = data.split('--');
                    if(msg[0]=="error"){
                      error_msg_alert(msg[1]);
                      $('#btn_update').button('reset');
                      return false;
                    }
                    else{
                      msg_alert(data);
                      $('#update_modal1').modal('hide');
                      $('#btn_update').button('reset');  
                      $('#update_modal1').on('hidden.bs.modal', function(){
                        list_reflect();
                      });
                    }
                   });

    }

  });

});



/**Hotel Name load start**/

function hotel_name_list_load(id)

{

  var city_id = $("#"+id).val();

  var count = id.substring(9);

  $.get( "package/hotel/hotel_name_load.php" , { city_id : city_id } , function ( data ) {

        $ ("#hotel_name"+count).html( data ) ;                            

  } ) ;   

}

function hotel_type_load(id)

{

  var hotel_id = $("#"+id).val();

  var count = id.substring(10);

  $.get( "package/hotel/hotel_type_load.php" , { hotel_id : hotel_id } , function ( data ) {

        $ ("#hotel_type"+count).val( data ) ;                            

  } ) ;   

}

/////////////********** Tour Master Information Save end**********/////////////

</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>