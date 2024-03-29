<?php
include "../../model/model.php";

$client_modal_type = $_POST['client_modal_type'];
$branch_admin_id = $_SESSION['branch_admin_id'];
 
?>
<input type="hidden" id="client_modal_type" name="client_modal_type" value="<?= $client_modal_type ?>">
<input type="hidden" id="branch_admin_id" name="branch_admin_id" value="<?= $branch_admin_id ?>" >
<div class="modal fade" id="customer_save_modal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Customer</h4>
      </div>
      <div class="modal-body">
        
        <form id="frm_customer_save">
        <div class="row mg_bt_20">          
            <div class="col-md-4 col-md-offset-2">
              <select name="cust_type" id="cust_type" class="form-control" data-toggle="tooltip" onchange="corporate_fields_reflect();" title="Customer Type">
              <?php get_customer_type_dropdown(); ?>
              </select>
            </div>        
            <div class="col-md-4">
              <select name="cust_source" id="cust_source" class="form-control" data-toggle="tooltip" onchange="corporate_fields_reflect();" title="Customer Source">
              <?php get_customer_source_dropdown(); ?>
              </select>
            </div> 
        </div>
        <div class="row mg_bt_10">
          <div id="corporate_fields"></div>
        </div>
        <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
            <legend>Personal Information</legend>
            <div class="row mg_bt_10">
                <div class="col-sm-4 col-xs-12">
                  <input type="text" id="cust_first_name" name="cust_first_name" onchange="fname_validate(this.id);" placeholder="*First Name" title="First Name">
                </div>
                <div class="col-sm-4 col-xs-12">
                  <input type="text" id="cust_middle_name" name="cust_middle_name" onchange="fname_validate(this.id);" placeholder="Middle Name" title="Middle Name">
                </div>
                <div class="col-sm-4 col-xs-12">
                  <input type="text" id="cust_last_name" name="cust_last_name" onchange="fname_validate(this.id);" placeholder="Last Name" title="Last Name">
                </div>  
            </div>                      
            <div class="row mg_bt_10">
                <div class="col-sm-4 col-xs-12">
                  <select name="cust_gender" id="cust_gender" title="Select Gender">
                    <option value="">Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>           
                <div class="col-sm-4 col-xs-12">
                  <input type="text" id="cust_birth_date" name="cust_birth_date" placeholder="Birth Date" title="Birth Date" onchange="calculate_age_generic('cust_birth_date', 'cust_age') ; " >

                </div>
                <div class="col-sm-4 col-xs-12">
                  <input type="text" id="cust_age" name="cust_age" onchange="validate_balance(this.id);" placeholder="Age" title="Age" readonly>
                </div>
            </div>
            <div class="row mg_bt_10">
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="cust_contact_no" name="cust_contact_no" maxlength="15" onchange="mobile_validate(this.id)" placeholder="*Mobile No" title="Mobile No">
              </div>
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="cust_email_id" name="cust_email_id" placeholder="Email ID" title="Email ID">
              </div>     
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="cust_service_tax_no" name="cust_service_tax_no" onchange="validate_alphanumeric(this.id);"  placeholder="Tax No" title="Tax No" style="text-transform: uppercase;">
              </div> 
            </div>
            <div class="row mg_bt_10">              
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="cust_pan" onchange="validate_alphanumeric(this.id);" name="cust_pan"  placeholder="PAN/TAN No" title="PAN/TAN No" style="text-transform: uppercase;">
              </div>
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="id_no" onchange="validate_alphanumeric(this.id);" name="id_no"  placeholder="ID Number" title="ID Number" style="text-transform: uppercase;">
              </div>
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="id_type" onchange="validate_alphanumeric(this.id);" name="id_type"  placeholder="ID Type" title="ID Type" style="text-transform: uppercase;">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4 col-xs-12">
                <input type="text" id="mem_no" onchange="validate_alphanumeric(this.id);" name="mem_no"  placeholder="Membership Number" title="Membership Number" style="text-transform: uppercase;">
              </div>
              <div class="col-sm-4 col-xs-12">
                  <select name="type_customer" id="type_customer" title="Customer-Persona" required>
                    <option value="">Customer-Persona</option>
                    <option value="Squirrel - No budget for package, Only safari booking, Dormitory Stay, Non AC stay">Squirrel - No budget for package, Only safari booking, Dormitory Stay, Non AC stay</option>
                    <option value="Jackal - Low Budget Less Loyal">Jackal - Low Budget Less Loyal</option>
                    <option value="Langoors - Wants Perfection but Pay Less">Langoors - Wants Perfection but Pay Less</option>
                    <option value="Giraffe - Loyal Humble Follower and Easy to Close">Giraffe - Loyal Humble Follower and Easy to Close</option>
                    <option value="Sloth-Bear - Leisure, Experience Lover, Not a Fan of Nature.">Sloth-Bear - Leisure,Experience Lover,Not a Fan of Nature.</option>
                    <option value="Cheetah - Flexible, Loyal and Smart, Negotiator">Cheetah - Flexible,Loyal and Smart,Negotiator</option>
                    <option value="Elephant - Attention to Details, Honest, Intellectual, Value for Money, Loyal">Elephant - Attention to Details,Honest,Intellectual,Value for Money,Loyal</option>
                    <option value="Leopard - Price Centric, Disloyal, Opportunistic">Leopard - Price Centric,Disloyal,Opportunistic</option>
                    <option value="Hippopotamus - Wealthy, Seek Attention and Perfection, Self lovers.">Hippopotamus - Wealthy,Seek Attention and Perfection,Self lovers.</option>
                    <option value="Tiger - Entrepreneurs, Upper Management, Celebrities. Value for Time and Money">Tiger - Entrepreneurs,Upper Management,Celebrities. Value for Time and Money</option>
                  </select>
              </div>
              <div  class="div-upload col-md-2" style="margin-bottom: 5px;"  id="div_upload_button">
                <div id="pro_upload_g" class="upload-button1"><span>Profile Photo</span></div>
                  <span id="pro_photo_status" ></span>
                  <ul id="files" ></ul>
                <input type="hidden" id="txt_pro_photo_upload_dir1" name="txt_pro_photo_upload_dir1">
              </div>
              <div  class="div-upload col-md-2" style="margin-left: 10px;"  id="div_upload_button">
                <div id="id_upload_g" class="upload-button1"><span>ID Photo</span></div>
                  <span id="id_status1" ></span>
                  <ul id="files" ></ul>
                <input type="hidden" id="txt_id_upload_dir1" name="txt_id_upload_dir1">
              </div>
            </div>
          </div>
          <div class="panel panel-default panel-body app_panel_style mg_tp_30 feildset-panel">
             <legend>Address Information</legend>
             <div class="row mg_bt_10">
              <div class="col-sm-4 col-xs-12">
                <input type="text" name="cust_address1" id="cust_address1" placeholder="Address-1" onchange="validate_address(this.id);" title="Address 1"/>
              </div>
               <div class="col-sm-4 col-xs-12">
                <input type="text" name="cust_address2" id="cust_address2" onchange="validate_address(this.id);" placeholder="Address-2" title="Address 2"/>
              </div>
               <div class="col-sm-4 col-xs-12">
                <input type="text" name="city" id="city" onchange="validate_city(this.id)" placeholder="City" title="City"/>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4 col-xs-12">
                <select name="cust_state" id="cust_state" title="Select State" style="width : 100%">
                  <?php get_states_dropdown() ?>
                </select>
              </div>
              <div class="col-sm-4 col-xs-12">
                <select name="cust_active_flag" id="cust_active_flag" title="Status" class="hidden">
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </div>
         </div>

          <div class="row text-center">
            <div class="col-xs-12">
              <button class="btn btn-sm btn-success" id="btn_save"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Save</button>
            </div>
          </div>

          </form>

      </div>     
    </div>
  </div>
</div>
<script src="<?php echo BASE_URL ?>js/app/field_validation.js"></script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
  $('#cust_state').select2();
  var date = new Date();
  var yest = date.setDate(date.getDate()-1);
  $('#cust_birth_date').datetimepicker({ timepicker:false, maxDate:yest, format:'d-m-Y' });
  $('#customer_save_modal').modal('show');

$(function(){

$('#frm_customer_save').validate({
  rules:{
      cust_first_name : { required : true },
      active_flag : { required : true },
      corpo_company_name : { required : true },
      cust_type : { required : true},
      cust_contact_no : { required : true},
  },
  submitHandler:function(form){
      var first_name = $('#cust_first_name').val();
      var middle_name = $('#cust_middle_name').val();
      var last_name = $('#cust_last_name').val();
      var gender = $('#cust_gender').val();
      var birth_date = $('#cust_birth_date').val();
      var age = $('#cust_age').val();
      var contact_no = $('#cust_contact_no').val();
      var email_id = $('#cust_email_id').val();
      var address = $('#cust_address1').val();
      var address2 = $('#cust_address2').val();
      var city = $('#city').val();
      var active_flag = $('#cust_active_flag').val();
      var service_tax_no = $('#cust_service_tax_no').val();  
      var landline_no = $('#cust_landline_no').val();
      var alt_email_id = $('#cust_alt_email_id').val();
      var company_name = $('#corpo_company_name').val();
      var cust_type = $('#cust_type').val();
      var base_url = $('#base_url').val();
      var state = $('#cust_state').val();
      var cust_pan  = $('#cust_pan').val();
      var branch_admin_id = $('#branch_admin_id').val();
      var cust_source = $('#cust_source').val();
      var pro_photo = $('#txt_pro_photo_upload_dir1').val();
      var id_photo = $('#txt_id_upload_dir1').val();
      var id_no = $('#id_no').val();
      var id_type = $('#id_type').val();
      var mem_no = $('#mem_no').val();
      var type_customer = $('#type_customer').val();
      $('#btn_save').button('loading');
      
      $.ajax({
        type: 'post',
        url: base_url+'controller/customer_master/customer_save.php',
        data:{ first_name : first_name, middle_name : middle_name, last_name : last_name, gender : gender, birth_date : birth_date, age : age, contact_no : contact_no, email_id : email_id, address : address,address2 : address2,city:city,  active_flag : active_flag ,service_tax_no : service_tax_no, landline_no : landline_no, alt_email_id : alt_email_id,company_name : company_name, cust_type : cust_type,state : state, cust_pan : cust_pan, branch_admin_id : branch_admin_id,cust_source:cust_source, pro_photo:pro_photo, id_photo : id_photo, id_no : id_no, id_type : id_type, mem_no : mem_no, type_customer : type_customer},
        success: function(result){

          var result_arr = result.split('==');
          var error_arr = result.split('--');
          var client_modal_type = $('#client_modal_type').val();
          if(client_modal_type=="master"){
            customer_list_reflect();
          }
          else{
            if(error_arr.length==1){
              customer_dropdown_reload(result_arr[1]);  
            }
            
          }
          $('#customer_save_modal').modal('hide');          
          msg_alert(result_arr[0]);
        }
      });
  }
});

});

pro_photo_upload();
function pro_photo_upload(){

    var btnUpload=$('#pro_upload_g');
    $(btnUpload).find('span').text('Profile Photo');
    new AjaxUpload(btnUpload, {

      action: 'upload_pro_photo.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
         error_msg_alert('Only JPG, PNG files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response1){
        
        if(response1==="error"){          
          error_msg_alert("File is not uploaded.");           
          $(btnUpload).find('span').text('Profile Photo');
        }
        else if(response1==="error1"){       
          error_msg_alert("Max Filesize limit exceeds!");           
          $(btnUpload).find('span').text('Profile Photo');
        }
        else{
          $(btnUpload).find('span').text('Uploaded');
          $("#txt_pro_photo_upload_dir1").val(response1);
        }
      }
    });
}
id_upload();
function id_upload(){

    var btnUpload=$('#id_upload_g');
    $(btnUpload).find('span').text('ID Photo');
    new AjaxUpload(btnUpload, {

      action: 'upload_id_photo.php',
      name: 'uploadfile',
      onSubmit: function(file, ext){
        if (! (ext && /^(jpg|png|jpeg)$/.test(ext))){ 
         error_msg_alert('Only JPG, PNG files are allowed');
         return false;
        }
        $(btnUpload).find('span').text('Uploading...');
      },
      onComplete: function(file, response1){
        
        if(response1==="error"){          
          error_msg_alert("File is not uploaded.");           
          $(btnUpload).find('span').text('ID Photo');
        }
        else if(response1==="error1"){       
          error_msg_alert("Max Filesize limit exceeds!");           
          $(btnUpload).find('span').text('ID Photo');
        }
        else{
          $(btnUpload).find('span').text('Uploaded');
          $("#txt_id_upload_dir1").val(response1);
        }
      }
    });
}
</script>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
