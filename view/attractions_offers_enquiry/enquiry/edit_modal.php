<?php
include "../../../model/model.php";
$login_id = $_SESSION['login_id'];
$role = $_SESSION['role'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$branch_status = $_POST['branch_status'];
$enq_id = $_POST['enquiry_id'];


$enq_details = mysql_fetch_assoc(mysql_query("Select * from enquiry_master where enquiry_id =". $enq_id));
?>
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>">
<input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>">
<input type="hidden" id="login_id" name="login_id" value="<?= $login_id ?>">
<input type="hidden" id="enquiry_id" name="enquiry_id" value="<?= $enq_id ?>">
<div class="modal fade" id="enquiry_edit_modal" role="dialog" aria-labelledby="enquiry_edit_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Enquiry Details</h4>
      </div>
      <div class="modal-body">
        <form id="frm_emquiry_edit">
                <div class="row mg_bt_20">
                    <div class="col-sm-4 col-sm-offset-4">
                        <select name="enquiry_type_u" id="enquiry_type_u" title="Enquiry For" onchange="enquiry_fields_reflect()" class="form-control" disabled>
                            <option value="<?= $enq_details['enquiry_type'] ?>" selected><?= $enq_details['enquiry_type']?></option>
                            <option value="Package Booking">Package Booking</option>
                            <option value="Flight Ticket">Flight Ticket</option>
                            <option value="Bus">Bus</option>
                            <option value="Car Rental">Car Rental</option>
                            <option value="Group Booking">Group Booking</option>
                            <option value="Hotel">Hotel</option>
                            <option value="Passport">Passport</option>
                            <option value="Train Ticket">Train Ticket</option>
                            <option value="Visa">Visa</option>
                        </select>
                    </div>
                </div>
                <div class="row mg_tp_10">
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="txt_name_u" name="txt_name_u" onchange="fname_validate(this.id)" placeholder="*Customer Name" title="Customer Name" value="<?= $enq_details[name] ?>">
                    </div>
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="type_customer1" name="type_customer1" placeholder="Customer-Persona" title="Customer-Persona" value="<?= $enq_details[type_customer] ?>" readonly>
                    </div>
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="txt_mobile_no_u" onchange="mobile_validate(this.id);" name="txt_mobile_no_u" placeholder="*Mobile No" title="Mobile No" value="<?= $enq_details[mobile_no] ?>"> 
                    </div>
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="txt_landline_no_u" onchange="mobile_validate(this.id);" name="txt_landline_no_u" placeholder="WhatsApp No with country code" title="WhatsApp No with country code" value="<?= $enq_details[landline_no] ?>"> 
                    </div>           
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="txt_email_id_u" name="txt_email_id_u" placeholder="Email ID" title="Email ID" value="<?= $enq_details[email_id] ?>">
                    </div>
                    <div class="col-md-3 col-sm-6 mg_bt_10">
                        <input type="text" class="form-control" id="location_u" name="location_u" placeholder="Location" title="Location" value="<?= $enq_details[location] ?>">
                    </div>
                </div>
                <?php
                $enq_json = json_decode($enq_details['enquiry_content']);

                
                ?>
                <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_20">
                  <legend>Service Information</legend>
                  <div id="div_enquiry_fields_u"></div>
                </div>
                <div class="panel panel-default panel-body app_panel_style feildset-panel mg_tp_30">
                 <legend>Office Information</legend>
                <div class="row"> 
                    <div class="col-sm-4 mg_bt_10">
                        <input type="text" class="form-control" id="txt_enquiry_date_u"  name="txt_enquiry_date_u" placeholder="*Enquiry Date" title="Enquiry Date" onchange="check_valid_date(this.id)" value="<?= date("d-m-Y", strtotime($enq_details[enquiry_date])) ?>">
                    </div>
                    <div class="col-sm-4 mg_bt_10">
                        <input type="text" class="form-control" id="txt_followup_date_u" name="txt_followup_date_u" placeholder="*Followup Date" title="Followup Date & Time" value="<?= date("d-m-Y H:i", strtotime($enq_details[followup_date]))?>">
                    </div>
                    <div class="col-sm-4 mg_bt_10">
                        <select name="reference_id_u" id="reference_id_u" style="width:100%" title="Reference">
                            <?php $sq_ref_v = mysql_fetch_assoc(mysql_query("select reference_name from references_master where reference_id=".$enq_details['reference_id']));
                            
                            if($sq_ref_v != ""){
                                ?>
                                <option value="<?= $enq_details['reference_id'] ?> " selected><?= $sq_ref_v['reference_name']?></option>
                                <?php
                            }
                            else{
                            ?>
                            <option value="">Reference</option>
                            <?php
                            } 
                            $sq_ref = mysql_query("select * from references_master where active_flag!='Inactive' order by reference_name asc");
                            while($row_ref = mysql_fetch_assoc($sq_ref)){
                              ?>
                              <option value="<?= $row_ref['reference_id'] ?>"><?= $row_ref['reference_name'] ?></option>
                              <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-sm-6 mg_bt_10_xs">
                        <select name="assigned_emp_id_u" id="assigned_emp_id_u" title="Allocate To" style="width:100%">
                        <?php 
                        $admin_select_flag = 0;
                        if($enq_details['assigned_emp_id'] == "0"){?>
                          <option value="0" selected>Admin</option>
                        <?php
                        $admin_select_flag = 1;
                        }
                        $sq_ass_v = mysql_fetch_assoc(mysql_query("select first_name from emp_master where emp_id=".$enq_details['assigned_emp_id']));
                            
                            if($sq_ass_v != "" && $admin_select_flag == 0){
                                ?>
                                <option value="<?= $enq_details['assigned_emp_id'] ?> " selected><?= $sq_ass_v['first_name']?></option>
                                <?php
                            }
                            else{
                            ?>
                            <option value="">*Allocate To</option>
                            <?php
                            } 
                              if($login_id == "1"){
                              ?>
                              <option value="0">Admin</option>
                              <?php
                              }
                               if($role=='Admin' || $emp_id =='12' || ($branch_status!='yes' && $role=='Branch Admin')){
                              $query = "select * from emp_master where active_flag='Active' order by first_name desc";
                            $sq_emp = mysql_query($query);
                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                ?>
                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                <?php
                              }
                            }
                           elseif($branch_status=='yes' && $role=='Branch Admin'){
                              $query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
                            $sq_emp = mysql_query($query);
                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                ?>
                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                <?php
                              }
                            }
                            else{ 
                            $query1 = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id' and active_flag='Active'")); ?>

                                 <option value="<?= $query1['emp_id'] ?>"><?= $query1['first_name'].' '.$query1['last_name'] ?>
                                 </option>

                            <?php
                            }
                            ?>
                              
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mg_bt_10_xs">
                      <select name="enquiry_u" id="enquiry_u" title="Enquiry Type" class="form-control">
                      <option value="<?= $enq_details['enquiry'] ?> " selected><?= $enq_details['enquiry'] ?></option>
                      <option value="<?= "Strong" ?>">Strong</option>
                        <option value="<?= "Hot" ?>">Hot</option>
                        <option value="<?= "Cold" ?>">Cold</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                        <textarea class="form-control" id="txt_enquiry_specification_u" onchange="validate_spaces(this.id);" name="txt_enquiry_specification_u" placeholder="Other Enquiry specification (If any)" title="Enquiry Specification"><?php
                            if($enq_details['enquiry_specification'] != "NA") echo $enq_details['enquiry_specification']; ?></textarea>
                    </div>
                </div>
              </div>

            <div class="row text-center mg_tp_20">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-success" id="btn_enq_edit"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
                </div>
            </div>
        </form>


        </div>
    
    </div>
  </div>
</div>

<script>
$('#enquiry_edit_modal').modal('show');
$('#assigned_emp_id_u').select2();
$("#txt_enquiry_date_u").datetimepicker({ timepicker:false, format:'d-m-Y' });
$("#txt_followup_date_u" ).datetimepicker({ format:'d-m-Y H:i' });

function enquiry_fields_reflect()
{
  var enquiry_id = $('#enquiry_id').val();
  var enquiry_type = $('#enquiry_type_u').val();

  $.post('enquiry_fields_reflect.php', { enquiry_id : enquiry_id, enquiry_type : enquiry_type }, function(data){
      $('#div_enquiry_fields_u').html(data);
  });
}
enquiry_fields_reflect();

///////////////////////***Enquiry Master update start*********//////////////
$(function(){
  $('#frm_emquiry_edit').validate({
    rules:{
            txt_followup_date_u : { required : true },
            txt_enquiry_date_u : { required : true },
            enquiry_u : { required : true },
            txt_mobile_no_u : { required : true },
    },
    submitHandler:function(form,event){
      event.preventDefault();
       var base_url = $('#base_url').val();   
       var name = $("#txt_name_u").val(); 
       var type_customer = $('#type_customer1').val();
       var enquiry_id = $("#enquiry_id").val(); 
       var enquiry = $('#enquiry_u').val(); 
       var mobile_no = $("#txt_mobile_no_u").val(); 
       var email_id = $("#txt_email_id_u").val();
       var location = $("#location_u").val();
       var landline_no = $("#txt_landline_no_u").val(); 
       var enquiry_date = $("#txt_enquiry_date_u").val();
       var followup_date = $("#txt_followup_date_u").val();
       var reference  = $('#reference_id_u').val();
       var assigned_emp_id = $("#assigned_emp_id_u").val();
       var enquiry_specification = $('#txt_enquiry_specification_u').val();
       var enquiry_content = $('#div_enquiry_fields_u').find('select, input, textarea').serializeArray();
       var err_msg = "";

       for(arr1 in enquiry_content){
          var row = enquiry_content[arr1];
          var field = row['name'];
          var field_val = $('#'+field).val();
          if(field_val==""){
             var placeholder = $('#'+field).attr('name');
             if(placeholder != 'Children Without Bed' && placeholder !='Children With Bed' && placeholder !='budget'){
             err_msg += placeholder+" is required!<br>"; }
          }
       }
       if(err_msg!=""){
          error_msg_alert(err_msg);
          return false;
       }

       $('#btn_enq_edit').button('loading');
       $.post( 
                   base_url+"controller/attractions_offers_enquiry/enquiry_master_update_c.php",
                   { enquiry_id : enquiry_id, mobile_no : mobile_no, email_id : email_id,location :location, landline_no : landline_no ,enquiry : enquiry, enquiry_date : enquiry_date , followup_date : followup_date, reference : reference,enquiry_content : enquiry_content, enquiry_specification : enquiry_specification,assigned_emp_id : assigned_emp_id, name : name, type_customer : type_customer},
                   function(data){
                          $('#enquiry_edit_modal').modal('hide');
                           $('#btn_enq_edit').button('reset');
                           msg_alert(data);
                           console.log();
                           enquiry_proceed_reflect();
                   });
    }
  });
});
///////////////////////***Enquiry Master save end*********//////////////
</script>
<script src="<?= BASE_URL ?>js/app/footer_scripts.js"></script>