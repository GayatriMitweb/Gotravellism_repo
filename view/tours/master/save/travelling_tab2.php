<form id="frm_tour_master_save2">

	<div class="row">
		<div class="col-md-12 app_accordion">
  			<div class="panel-group main_block" id="accordion" role="tablist" aria-multiselectable="true">

				<!-- Train Information -->
				<div class="accordion_content main_block mg_bt_10">
					<div class="panel panel-default main_block">
						<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
					        <div class="Normal main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1" id="collapsed1">                  
					        	<div class="col-md-12"><span>Train Information</span></div>
					        </div>
					    </div>
					      <div id="collapse1" class="panel-collapse collapse in main_block" role="tabpanel" aria-labelledby="heading1">
					          <div class="panel-body">
					            <div class="row mg_bt_10">
					                <div class="col-md-12 text-right text_center_xs">
					                    <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_group_tour_save_dynamic_train')"><i class="fa fa-plus"></i></button>
					                    <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_group_tour_save_dynamic_train')"><i class="fa fa-trash"></i></button>
					                </div>
					            </div>
					            <div class="row mg_bt_10">
					                <div class="col-md-12">
					                    <div class="table-responsive">
					                      <table id="tbl_group_tour_save_dynamic_train" name="tbl_group_tour_save_dynamic_train" class="table table-bordered no-marg pd_bt_51">
					                          <tr>
					                              <td><input class="css-checkbox" id="chk_train1" type="checkbox"><label class="css-label" for="chk_train1"><label></td>
					                              <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
					                              <td class="col-md-4"><select onchange="validate_location('train_from_location1','train_to_location1')" id="train_from_location1" class="app_select2 form-control" name="train_from_location1" title="From Location" style="width: 100%;">
					                                <option value="">*From</option>
					                                <?php 
					                                    $sq_city = mysql_query("select * from city_master where active_flag='Active'");
					                                    while($row_city = mysql_fetch_assoc($sq_city))
					                                    {
					                                ?>
					                                    <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
					                                <?php   
					                                    }    
					                                ?>
					                            </select></td>
					                               <td class="col-md-4"><select id="train_to_location1" class="app_select2 form-control" onchange="validate_location('train_to_location1','train_from_location1')" title="To Location" name="train_to_location1" style="width: 100%;">
					                                <option value="">*To</option>
					                                <?php 
					                                    $sq_city = mysql_query("select * from city_master where active_flag='Active'");
					                                    while($row_city = mysql_fetch_assoc($sq_city))
					                                    {
					                                ?>
					                                        <option value="<?php echo $row_city['city_name'] ?>"><?php echo $row_city['city_name'] ?></option>
					                                <?php   
					                                    }    
					                                ?>
					                            </select></td>
					                            <td class="col-md-4"><select name="train_class" id="train_class1" title="Class">
													<option value="">*Class</option>
													<option value="1A">1A</option>
													<option value="2A">2A</option>
													<option value="3A">3A</option>
													<option value="FC">FC</option>
													<option value="CC">CC</option>
													<option value="SL">SL</option>
													<option value="2S">2S</option>
					                            </select></td>
					                          </tr>                                
					                      </table>
					                  </div>
					              </div>
					            </div>
					          </div>
					      </div>
					</div>
				</div>
				<!-- Hotel Information -->
				<div class="accordion_content main_block mg_bt_10">
		          <div class="panel panel-default main_block">
		          	<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
		                <div class="Normal collapsed main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2" id="collapsed2">                  
		                  <div class="col-md-12"><span>Hotel Information</span></div>
		                </div>
		            </div>
		            <div id="collapse2" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading2">
		                <div class="panel-body">
		                  <div class="row mg_bt_10">
		                      <div class="col-md-12 text-right text_center_xs">
		                          <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_package_hotel_master')"><i class="fa fa-plus"></i></button>
		                          <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_package_hotel_master')"><i class="fa fa-trash"></i></button>
		                      </div>
		                  </div>
		                  <div class="row mg_bt_10">
		                      <div class="col-md-12">
		                          <div class="table-responsive">
		                            <table id="tbl_package_hotel_master" name="tbl_package_hotel_master" class="table table-bordered no-marg pd_bt_51">
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
											<td><input type="text" id="hotel_tota_days1" onchange="validate_balance(this.id)" name="hotel_tota_days1" placeholder="*Total Night" title="Total Night"></td></td>
                  						</tr>                                
		                            </table>
		                        </div>
		                    </div>
		                  </div>
		                </div>
		            </div>
		          </div>
		        </div>

				<!-- Flight Information -->
				<div class="accordion_content main_block mg_bt_10">
		          <div class="panel panel-default main_block">
		          	<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
		                <div class="Normal collapsed main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse3" id="collapsed3">                  
		                  <div class="col-md-12"><span>Flight Information</span></div>
		                </div>
		            </div>
		            <div id="collapse2" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading3">
		                <div class="panel-body">
		                  <div class="row mg_bt_10">
		                      <div class="col-md-12 text-right text_center_xs">
		                          <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_group_tour_quotation_dynamic_plane')"><i class="fa fa-plus"></i></button>
		                          <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_group_tour_quotation_dynamic_plane')"><i class="fa fa-trash"></i></button>
		                      </div>
		                  </div>
		                  <div class="row mg_bt_10">
		                      <div class="col-md-12">
		                          <div class="table-responsive">
		                            <table id="tbl_group_tour_quotation_dynamic_plane" name="tbl_group_tour_quotation_dynamic_plane" class="table table-bordered no-marg pd_bt_51">
		                                <tr>
		                                    <td><input class="css-checkbox" id="chk_plan-" type="checkbox"><label class="css-label" for="chk_plan1"> <label></td>
		                                    <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
		                                    <td><select id="from_city-1" name="from_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('from_city-1','to_city-1');airport_reflect(this.id)">
		                                      <?php get_cities_dropdown(); ?>
		                                  </select></td>
		                                  <td><select id="plane_from_location-1" class="app_select2 form-control" title="Sector From" name="plane_from_location-1" style="width: 200px;">
		                                    <option value="">*Sector From</option>
		                                </select></td>
		                                <td><select id="to_city-1" name="to_city-1" style="width: 150px;" class="app_select2 form-control" title="Select City Name" onchange="validate_location('to_city-1','from_city-1');airport_reflect1(this.id)">
		                                    <?php get_cities_dropdown(); ?>
		                                    </select></td>
		                                     <td><select id="plane_to_location-1" class="app_select2 form-control"  title="Sector To" name="plane_to_location-1" style="width: 200px;">
		                                      <option value="">*Sector To</option>
		                                  </select></td>
		                                  <td><select id="airline_name-1" class="app_select2 form-control" title="Airline Name" name="airline_name-1" style="width: 200px;">
		                                        <option value="">*Airline Name</option>
		                                        <?php get_airline_name_dropdown(); ?>
		                                    </select>
		                                    </td>
		                                  <td><select name="plane_class-1" id="plane_class-1" title="Class" style="width: 100px;">
		                                    <option value="">*Class</option>
		                                    <option value="Economy">Economy</option>
		                                        <option value="Premium Economy">Premium Economy</option>
		                                        <option value="Business">Business</option>
		                                        <option value="First Class">First Class</option>
		                                  </select></td>              
		                                </tr>                                
		                            </table>
		                        </div>
		                    </div>
		                  </div>
		                </div>
		            </div>
		          </div>
		        </div>

				<!-- Cruise Information -->
		        <div class="accordion_content main_block">
				  <div class="panel panel-default main_block">
				  	<div class="panel-heading main_block" role="tab" id="heading_<?= $count ?>">
				        <div class="Normal collapsed main_block" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4" id="collapsed4">                  
				          <div class="col-md-12"><span>Cruise Information</span></div>
				        </div>
				    </div>
				    <div id="collapse4" class="panel-collapse collapse main_block" role="tabpanel" aria-labelledby="heading4">
				        <div class="panel-body">
				          <div class="row mg_bt_10">
				              <div class="col-md-12 text-right text_center_xs">
				                  <button type="button" class="btn btn-excel btn-sm" onClick="addRow('tbl_dynamic_cruise')"><i class="fa fa-plus"></i></button>
				                  <button type="button" class="btn btn-pdf btn-sm" onClick="deleteRow('tbl_dynamic_cruise')"><i class="fa fa-trash"></i></button>
				              </div>
				          </div>
				          <div class="row mg_bt_10">
				              <div class="col-md-12">
				                  <div class="table-responsive">
				                  <table id="tbl_dynamic_cruise" name="tbl_dynamic_cruise" class="table table-bordered no-marg">
				                      <tr>
				                        <td><input class="css-checkbox" id="chk_cruise1" type="checkbox"><label class="css-label" for="chk_cruise1"><label></td>
				                        <td><input maxlength="15" value="1" type="text" name="username" placeholder="Sr. No." class="form-control" disabled /></td>
				                        <td class="col-md-6"><input type="text" id="route" name="route" onchange="validate_specialChar(this.id);" placeholder="*Route" title="Route"></td>
				                        <td class="col-md-6"><input type="text" id="cabin" name="cabin"  onchange="validate_specialChar(this.id);" placeholder="Cabin" title="Cabin"></td>
				                      </tr>                                
				                  </table>
				                  </div>
				              </div>
				          </div>
				        </div>
				    </div>
				  </div>
				</div>
			</div> 
		</div>
	</div>

	<div class="row text-center mg_tp_20">
		<div class="col-md-12">
			<button class="btn btn-info btn-sm ico_left" type="button" onclick="switch_to_tab1()" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Previous</button>
			&nbsp;&nbsp;
			<button class="btn btn-sm btn-info ico_right" id="btn_quotation_save">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</form>

<script> 
// App_accordion
jQuery(document).ready(function() {			
			jQuery(".panel-heading").click(function(){ 
				jQuery('#accordion .panel-heading').not(this).removeClass('isOpen');
				jQuery(this).toggleClass('isOpen');
				jQuery(this).next(".panel-collapse").addClass('thePanel');
				jQuery('#accordion .panel-collapse').not('.thePanel').slideUp("slow"); 
		    	jQuery(".thePanel").slideToggle("slow").removeClass('thePanel'); 
			});
			
		});

function switch_to_tab1(){ $('a[href="#tab1"]').tab('show');
}
$('select[name^="city_name"]').select2();
$(function(){
$('#frm_tour_master_save2').validate({
	submitHandler:function(form){			//Train Information
	var train_from_location_arr = new Array();
	var train_to_location_arr = new Array();
	var train_class_arr = new Array();
	var table = document.getElementById("tbl_group_tour_save_dynamic_train");
	  var rowCount = table.rows.length;
	  for(var i=0; i<rowCount; i++){

	    var row = table.rows[i];
	    if(row.cells[0].childNodes[0].checked){
	       var train_from_location1 = row.cells[2].childNodes[0].value;         
	       var train_to_location1 = row.cells[3].childNodes[0].value;         
		   var train_class = row.cells[4].childNodes[0].value;  

		if(train_from_location1==""){
			error_msg_alert('Enter train from location in row'+(i+1));
			$('.accordion_content').removeClass("indicator");
			$('#tbl_group_tour_save_dynamic_train').parent('div').closest('.accordion_content').addClass("indicator");
			return false;
		}
		if(train_to_location1==""){
			error_msg_alert('Enter train to location in row'+(i+1));
			$('.accordion_content').removeClass("indicator");
			$('#tbl_group_tour_save_dynamic_train').parent('div').closest('.accordion_content').addClass("indicator");
			return false;
		}
		if(train_class==""){
			error_msg_alert('Enter train class in row'+(i+1));
			$('.accordion_content').removeClass("indicator");
			$('#tbl_group_tour_save_dynamic_train').parent('div').closest('.accordion_content').addClass("indicator");
			return false;
		}
	      
		train_from_location_arr.push(train_from_location1);
		train_to_location_arr.push(train_to_location1);
		train_class_arr.push(train_class);
		}
	  }

	//Plane Information  
	var from_city_id_arr = new Array();
	var plane_from_location_arr = new Array();
	var to_city_id_arr = new Array();
	var plane_to_location_arr = new Array();

	var airline_name_arr = new Array();

	var plane_class_arr = new Array();


	var table = document.getElementById("tbl_group_tour_quotation_dynamic_plane");

	  var rowCount = table.rows.length;

	  

	  for(var i=0; i<rowCount; i++)

	  {

	    var row = table.rows[i];
	    if(row.cells[0].childNodes[0].checked)

	    {
	       var from_city_id1 = row.cells[2].childNodes[0].value;
	       var plane_from_location1 = row.cells[3].childNodes[0].value;   
	       var to_city_id1 = row.cells[4].childNodes[0].value; 
	       var plane_to_location1 = row.cells[5].childNodes[0].value;

	       var airline_name = row.cells[6].childNodes[0].value;  

	       var plane_class = row.cells[7].childNodes[0].value;         
	        if(from_city_id1=="")

		    {

		        error_msg_alert('Enter plane from city in row'+(i+1));
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");
				return false;

		    }

	       if(plane_from_location1=="")

	       {

	          error_msg_alert('Enter plane from location in row'+(i+1));
			  $('.accordion_content').removeClass("indicator");
	          $('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");

	          return false;

	       }

	       if(to_city_id1=="")

		    {

		        error_msg_alert('Enter plane To city in row'+(i+1));
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");

		        return false;

		    }



	       if(plane_to_location1=="")

	       {

	          error_msg_alert('Enter plane to location in row'+(i+1));
			  $('.accordion_content').removeClass("indicator");
	          $('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");

	          return false;

	       }

	       if(airline_name=="")

			{ 
				error_msg_alert('Airline Name is required in row:'+(i+1)); 
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");

				return false;
			}

	       if(plane_class=="")

	       	{ 
				error_msg_alert("Class is required in row:"+(i+1)); 
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_group_tour_quotation_dynamic_plane').parent('div').closest('.accordion_content').addClass("indicator");

	       		return false;
			}

	from_city_id_arr.push(from_city_id1);
	to_city_id_arr.push(to_city_id1);
	plane_from_location_arr.push(plane_from_location1);
	plane_to_location_arr.push(plane_to_location1);
	airline_name_arr.push(airline_name);
	plane_class_arr.push(plane_class);
	}      
}
// Hotel Information
		var city_name_arr = new Array();

          var hotel_name_arr = new Array();

          var hotel_type_arr = new Array();

          var total_days_arr = new Array();

          var table = document.getElementById("tbl_package_hotel_master");

          var rowCount = table.rows.length;

              for(var i=0; i<rowCount; i++)

              {

                  var row = table.rows[i];

                if(row.cells[0].childNodes[0].checked)

                {  

                  var city_name = row.cells[2].childNodes[0].value;

                  var hotel_name = row.cells[3].childNodes[0].value;

                  var hotel_type = row.cells[4].childNodes[0].value;

				  var total_days = row.cells[5].childNodes[0].value;
				  if(city_name=="")

		    {

		        error_msg_alert('Enter hotel from city in row'+(i+1));
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_package_hotel_master').parent('div').closest('.accordion_content').addClass("indicator");
				return false;

		    }

	       if(hotel_name=="")

	       {

	          error_msg_alert('Enter hotel from location in row'+(i+1));
			  $('.accordion_content').removeClass("indicator");
	          $('#tbl_package_hotel_master').parent('div').closest('.accordion_content').addClass("indicator");

	          return false;

		   }
		   if(hotel_type=="")

	       {

	          error_msg_alert('Enter hotel from location in row'+(i+1));
			  $('.accordion_content').removeClass("indicator");
	          $('#tbl_package_hotel_master').parent('div').closest('.accordion_content').addClass("indicator");

	          return false;

	       }

	       if(total_days=="")

		    {

		        error_msg_alert('Enter hotel To city in row'+(i+1));
			  	$('.accordion_content').removeClass("indicator");
	          	$('#tbl_package_hotel_master').parent('div').closest('.accordion_content').addClass("indicator");

		        return false;

		    }



	       


                   city_name_arr.push(city_name);

                   hotel_name_arr.push(hotel_name);

                   hotel_type_arr.push(hotel_type);  

                   total_days_arr.push(total_days);  

                }

              }

    //Cruise Information

	var route_arr = new Array();
	var cabin_arr = new Array();

	var table = document.getElementById("tbl_dynamic_cruise");
	var rowCount = table.rows.length;

	  for(var i=0; i<rowCount; i++)
	  {
	    var row = table.rows[i];	 
	    if(row.cells[0].childNodes[0].checked)
	    {
	       var route = row.cells[2].childNodes[0].value;    
	       var cabin = row.cells[3].childNodes[0].value;        
	       if(route=="")
	       {
	          error_msg_alert('Enter route in row'+(i+1));
			  $('.accordion_content').removeClass("indicator");
	          $('#tbl_dynamic_cruise').parent('div').closest('.accordion_content').addClass("indicator");
	          return false;
	       }	      	 
		   route_arr.push(route);
		   cabin_arr.push(cabin);

	    }      
	  }
		$('.accordion_content').removeClass("indicator");
		$('a[href="#tab3"]').tab('show');

		}
	});

});
function hotel_name_list_load(id)
{

  var city_id = $("#"+id).val();

  var count = id.substring(9);

  $.get( "../../../view/custom_packages/master/package/hotel/hotel_name_load.php" , { city_id : city_id } , function ( data ) {
        $ ("#hotel_name"+count).html( data ) ;                            
  } ) ;   

}
function hotel_type_load(id)

{

  var hotel_id = $("#"+id).val();

  var count = id.substring(10);

  $.get( "../../../view/custom_packages/master/package/hotel/hotel_type_load.php" , { hotel_id : hotel_id } , function ( data ) {

        $ ("#hotel_type"+count).val( data ) ;                            

  } ) ;

}
</script>