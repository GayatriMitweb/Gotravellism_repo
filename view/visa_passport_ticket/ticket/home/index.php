<?php 
include "../../../../model/model.php";
$role = $_SESSION['role'];
$role_id = $_SESSION['role_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$branch_status = $_POST['branch_status'];
?>
<input type="hidden" id="whatsapp_switch"  value="<?= $whatsapp_switch ?>" >
<div class="row text-right mg_bt_20">
	<div class="col-xs-12">
		<button class="btn btn-excel btn-sm" onclick="excel_report()" data-toggle="tooltip" title="Generate Excel"><i class="fa fa-file-excel-o"></i></button>
		<button class="btn btn-info btn-sm ico_left" onclick="save_modal()" id="train_btn"><i class="fa fa-plus"></i>&nbsp;&nbsp;Ticket</button>
	</div>
</div>

<div class="app_panel_content Filter-panel">
	<div class="row">
		<input type="hidden" value="<?= $emp_id ?>" id="emp_id"/>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
		        <select name="cust_type_filter" id="cust_type_filter" style="width:100%" onchange="dynamic_customer_load(this.value,'company_filter');company_name_reflect();" title="Customer Type">
		            <?php get_customer_type_dropdown(); ?>
		        </select>
	    </div>
	    <div  id="company_div" class="hidden">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10" id="customer_div">    
	    </div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<select name="ticket_id_filter" id="ticket_id_filter" style="width:100%" title="Booking ID">
		        <option value="">Booking ID</option>
		        <?php  $query = "select * from ticket_master where 1 ";
		        include "../../../../model/app_settings/branchwise_filteration.php";
		        $query .= " order by ticket_id desc ";
		        $sq_ticket = mysql_query($query);
		        while($row_ticket = mysql_fetch_assoc($sq_ticket)){

		        	$date = $row_ticket['created_at'];
				      $yr = explode("-", $date);
				      $year =$yr[0];
		          $sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_ticket[customer_id]'"));
		          ?>
		          <option value="<?= $row_ticket['ticket_id'] ?>"><?= get_ticket_booking_id($row_ticket['ticket_id'],$year).' : '.$sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
		          <?php
		        }
		        ?>
		    </select>
		</div>	
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="from_date" name="from_date" class="form-control" placeholder="From Date" title="From Date">
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mg_bt_10">
			<input type="text" id="to_date" name="to_date" class="form-control" placeholder="To Date" title="To Date">
		</div>
		<div class="col-md-2 col-sm-6 col-xs-12 mg_bt_10">
			<button class="btn btn-sm btn-info ico_right" onclick="ticket_customer_list_reflect()">Proceed&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
		</div>
	</div>
</div>

<div id="div_ticket_customer_list_reflect" class="main_block loader_parent mg_tp_10">
<div class="table-responsive mg_tp_10">
	<table id="flight_book" class="table table-hover" style="margin: 20px 0 !important;">         
	</table>
</div>
</div>

<div id="div_ticket_modal"></div>

<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>
<script>
	$('#customer_id_filter, #ticket_id_filter,#cust_type_filter').select2();
	$('#from_date, #to_date').datetimepicker({ timepicker:false, format:'d-m-Y' });
	dynamic_customer_load('','');
	function calculate_total_amount(){

		var adult_fair = $('#adult_fair').val();
		var children_fair = $('#children_fair').val();
		var infant_fair = $('#infant_fair').val();

		var adults = $('#adults').val();
		var childrens = $('#childrens').val();
		var infant = $('#infant').val();

		if(adult_fair==""){ adult_fair = 0; }
		if(children_fair==""){ children_fair = 0; }
		if(infant_fair==""){ infant_fair = 0; }

		var basic_cost = parseFloat(adult_fair) + parseFloat(children_fair) + parseFloat(infant_fair);
		$('#basic_cost').val(basic_cost);

		var basic_cost_markup = $('#basic_cost_markup').val();
		var basic_cost_discount = $('#basic_cost_discount').val();
		var yq_tax = $('#yq_tax').val();
		var yq_tax_markup = $('#yq_tax_markup').val();
		var yq_tax_discount = $('#yq_tax_discount').val();
		var g1_plus_f2_tax = $('#g1_plus_f2_tax').val();
		var service_charge = $('#service_charge').val();
		var service_tax = $('#service_tax').val();
		var tds = $('#tds').val();

		if(basic_cost_markup==""){ basic_cost_markup = 0; }
		if(basic_cost_discount==""){ basic_cost_discount = 0; }
		if(yq_tax==""){ yq_tax = 0; }
		if(yq_tax_markup==""){ yq_tax_markup = 0; }
		if(yq_tax_discount==""){ yq_tax_discount = 0; }
		if(g1_plus_f2_tax==""){ g1_plus_f2_tax = 0; }
		if(service_charge==""){ service_charge = 0; }
		if(tds==""){ tds = 0; }

		if(adults==0){ $('#adult_fair').val(0); $('#adult_fair').prop('readonly', true); }
		else{ $('#adult_fair').prop('disabled', false); 
			  $('#adult_fair').prop('readonly', false);
			  if($('#adult_fair').val() == 0)$('#adult_fair').val('');
	      }

		if(childrens==0){  $('#children_fair').val(0); $('#children_fair').prop('readonly', true); }
		else{  $('#children_fair').prop('disabled', false); 
			   $('#children_fair').prop('readonly', false);	
			   if($('#children_fair').val() == 0)$('#children_fair').val('');
	       	}

		if(infant==0){ $('#infant_fair').val(0); $('#infant_fair').prop('readonly', true); }
		else{ $('#infant_fair').prop('disabled', false);
			  $('#infant_fair').prop('readonly', false);
			  if($('#infant_fair').val() == 0)$('#infant_fair').val('');
		    }

		var service_tax_subtotal = (parseFloat(service_charge)/100)*parseFloat(service_tax);
		if(service_tax_subtotal==""){ service_tax_subtotal = 0; }
		service_tax_subtotal = Math.round(service_tax_subtotal);
		$('#service_tax_subtotal').val(service_tax_subtotal.toFixed(2));

		var ticket_total_cost = parseFloat(basic_cost) + parseFloat(basic_cost_markup) - parseFloat(basic_cost_discount) + parseFloat(yq_tax) + parseFloat(yq_tax_markup) - parseFloat(yq_tax_discount) + parseFloat(g1_plus_f2_tax) + parseFloat(service_charge) + parseFloat(service_tax_subtotal) - parseFloat(tds);
		ticket_total_cost = ticket_total_cost.toFixed(2);
		$('#ticket_total_cost').val(ticket_total_cost);

	}
	var columns = [
		{title:"ID"},
		{title:"S_No."},
		{title:"Customer_Name"},
		{title:"Mobile"},
		{title:"Trip_Type"},
		{title:"Amount", className:"text-right info"},
		{title:"Cncl_Amount", className:"text-right danger"},
		{title:"Total", className:"text-right success"},
		{title:"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Actions&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", className:"text-center action-width"},
];
	function ticket_customer_list_reflect()
	{
		$('#div_ticket_customer_list_reflect').append('<div class="loader"></div>');
		var customer_id = $('#customer_id_filter').val()
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var ticket_id_filter = $('#ticket_id_filter').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();

		$.post('home/ticket_list_reflect.php', { customer_id : customer_id, ticket_id_filter : ticket_id_filter,from_date : from_date, to_date : to_date, cust_type : cust_type, company_name : company_name  , branch_status : branch_status }, function(data){
			//$('#div_ticket_customer_list_reflect').html(data);
			pagination_load(data,columns, true,true, 20,'flight_book');
			$('.loader').remove();
		});
	}
	ticket_customer_list_reflect();

	function save_modal()
	{
		var branch_status = $('#branch_status').val();
		$('#train_btn').button('loading');
		$.post('home/save/index.php', {branch_status : branch_status }, function(data){
			$('#div_ticket_modal').html(data);
		$('#train_btn').button('reset');
		});
	}
	function ticket_update_modal(ticket_id)
	{
		var branch_status = $('#branch_status').val();
		$.post('home/update/index.php', { ticket_id : ticket_id, branch_status : branch_status }, function(data){
			$('#div_ticket_modal').html(data);
		});
	}
    function ticket_display_modal(ticket_id)
    {
		$.post('home/view/index.php', { ticket_id : ticket_id }, function(data){
			$('#div_ticket_modal').html(data);
		});  	
    }
	
	function company_name_reflect()
	{  
		var cust_type = $('#cust_type_filter').val();
		var branch_status = $('#branch_status').val();
	  	$.post('home/company_name_load.php', { cust_type : cust_type, branch_status : branch_status }, function(data){
	  		if(cust_type=='Corporate'){
		  		$('#company_div').addClass('company_class');	
		    }
		    else
		    {
		    	$('#company_div').removeClass('company_class');		
		    }
	    	$('#company_div').html(data);
	    });
	}
	company_name_reflect();
	function customer_info_load(offset='')
	{
	   var customer_id = $('#customer_id'+offset).val();
	   var base_url = $('#base_url').val();
	   if(customer_id==0 && customer_id!='')
	   {
			$('#cust_details').addClass('hidden');
		    $('#new_cust_div').removeClass('hidden');

			$.ajax({
			type:'post',
			url:base_url+'view/load_data/new_customer_info.php',
			data:{},
			success:function(result){
				 
				$('#new_cust_div').html(result);
			}
		});
		}
	else{
		if(customer_id!=''){
			$('#new_cust_div').addClass('hidden');
			$('#cust_details').removeClass('hidden');
			$.ajax({
				type:'post',
				url:base_url+'view/load_data/customer_info_load.php',
				data:{ customer_id : customer_id },
				success:function(result){
					result = JSON.parse(result);
					$('#mobile_no'+offset).val(result.contact_no);
					$('#email_id'+offset).val(result.email_id);
					if(result.company_name != ''){
						$('#company_name'+offset).removeClass('hidden');
						$('#company_name'+offset).val(result.company_name);	
					}
					else
					{
						$('#company_name'+offset).addClass('hidden');
					}
					if(result.payment_amount != '' || result.payment_amount != '0'){
						$('#credit_amount'+offset).removeClass('hidden');
						$('#credit_amount'+offset).val(result.payment_amount);	
					}
					else{
						$('#credit_amount'+offset).addClass('hidden');
					}
				}
			});
		}
    }
	}
	function adolescence_reflect(id) 
	{
	  var dateString1=$("#"+id).val();

	  var today = new Date(); 
	  var birthDate = php_to_js_date_converter(dateString1);
	  var age = today.getFullYear() - birthDate.getFullYear();
	  var m = today.getMonth() - birthDate.getMonth();
	  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
	    age--;
	  } 

	  var millisecondsPerDay = 1000 * 60 * 60 * 24;
	  var millisBetween = today.getTime() - birthDate.getTime();
	  var days = millisBetween / millisecondsPerDay;

	  var count=id.substr(10);	  
	  var adl = "";
	  var no_days = Math.floor(days);
	  
	  if(no_days<=730 && no_days>0){ adl = "Infant"; }
	  if(no_days>730 && no_days<=4384){ adl = "Children"; }
	  if(no_days>4384){ adl = "Adult"; } 
	  $('#adolescence'+count).val(adl);
  
	}
    
	function excel_report()
	{
		var customer_id = $('#customer_id_filter').val()
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var ticket_id = $('#ticket_id_filter').val();
		var cust_type = $('#cust_type_filter').val();
		var company_name = $('#company_filter').val();
		var branch_status = $('#branch_status').val();
		window.location = 'home/excel_report.php?customer_id='+customer_id+'&ticket_id='+ticket_id+'&from_date='+from_date+'&to_date='+to_date+'&cust_type='+cust_type+'&company_name='+company_name+'&branch_status='+branch_status;
	}
	//*******************Get Dynamic Customer Name Dropdown**********************//
	function dynamic_customer_load(cust_type, company_name)
	{
	  var cust_type = $('#cust_type_filter').val();
	  var company_name = $('#company_filter').val();
	  var branch_status = $('#branch_status').val();
	    $.get("home/get_customer_dropdown.php", { cust_type : cust_type , company_name : company_name, branch_status : branch_status}, function(data){
	    $('#customer_div').html(data);
	  });   
	}
</script>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<style>
.action_width{
	width:220px !important;
	text-align:left;
}
</style>