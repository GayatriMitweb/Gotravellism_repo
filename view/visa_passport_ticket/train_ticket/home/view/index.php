<?php 
include "../../../../../model/model.php";
 $train_ticket_id = $_POST['train_ticket_id'];
 $sq_booking = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id='$train_ticket_id'"));
 $date = $sq_booking['created_at'];
	            $yr = explode("-", $date);
	           	$year =$yr[0];
?>
<div class="modal fade profile_box_modal" id="train_ticket_view_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body profile_box_padding">
      	
      	<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#basic_information" aria-controls="home" role="tab" data-toggle="tab" class="tab_name">General Information</a></li>
			    <li role="presentation"><a href="#ticket_information" aria-controls="profile1" role="tab" data-toggle="tab" class="tab_name">Ticket Information</a></li>
			    <li role="presentation"><a href="#payment_information" aria-controls="profile" role="tab" data-toggle="tab" class="tab_name">Receipt Information</a></li>
			    <li class="pull-right"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></li>
			  </ul>

              <div class="panel panel-default panel-body fieldset profile_background">

				  <!-- Tab panes1 -->
				  <div class="tab-content">

				    <!-- *****TAb1 start -->
				    <div role="tabpanel" class="tab-pane active" id="basic_information">
				     <?php include "tab1.php"; ?>
				    </div>
				    <!-- ********Tab1 End******** --> 
	                <div role="tabpanel" class="tab-pane" id="ticket_information">
				     <?php include "tab2.php"; ?>
				    </div>
	                <!-- ***Tab2 Start*** -->
				    <div role="tabpanel" class="tab-pane" id="payment_information">
				       <?php include "tab3.php"; ?>
				    </div>
	                <!-- ***Tab2 End*** -->

				  </div>

			  </div>
        </div>
        
	   </div>
     
      </div>
    </div>
  
</div>
<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#train_ticket_view_modal').modal('show');
</script>  
 
