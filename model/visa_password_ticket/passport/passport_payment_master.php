<?php 
$flag = true;
class passport_payment_master{

public function passport_payment_master_save()
{
	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];
	$branch_admin_id = $_POST['branch_admin_id'];

	$payment_date = date('Y-m-d', strtotime($payment_date));

	if($payment_mode == 'Cheque'){ 
		$clearance_status = "Pending"; } 
	else {  $clearance_status = ""; }	

	$financial_year_id = $_SESSION['financial_year_id'];

	begin_t();

	$sq_max = mysql_fetch_assoc(mysql_query("select max(payment_id) as max from passport_payment_master"));
	$payment_id = $sq_max['max'] + 1;

	$sq_payment = mysql_query("insert into passport_payment_master (payment_id, passport_id, financial_year_id, branch_admin_id, payment_date, payment_amount, payment_mode, bank_name, transaction_id, bank_id, clearance_status) values ('$payment_id', '$passport_id', '$financial_year_id', '$branch_admin_id', '$payment_date', '$payment_amount', '$payment_mode', '$bank_name', '$transaction_id', '$bank_id', '$clearance_status') ");
	if(!$sq_payment){
		rollback_t();
		echo "error--Sorry, Payment not saved!";
		exit;
	}
	else{

		//Finance Save
		$this->finance_save($payment_id, $branch_admin_id);

		//Bank and Cash Book Save
    	$this->bank_cash_book_save($payment_id, $branch_admin_id);

		if($GLOBALS['flag']){
			commit_t();
			//Payment email notification
	    	$this->payment_email_notification_send($passport_id, $payment_amount, $payment_mode, $payment_date);

	    	//Payment sms notification
			if($payment_amount != 0){
				$this->payment_sms_notification_send($passport_id, $payment_amount, $payment_mode);
			}

			echo "Passport Payment has been successfully saved.";
			exit;	
		}
		else{
			rollback_t();
			exit;
		}
		
	}

	
}

public function finance_save($payment_id, $branch_admin_id)
{
	$row_spec = 'sales';
	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];

	$sq_passport_info = mysql_fetch_assoc(mysql_query("select customer_id from passport_master where passport_id='$passport_id'"));

	$payment_date = date('Y-m-d', strtotime($payment_date));
	$year2 = explode("-", $payment_date);
	$yr2 =$year2[0];

	$sq_Passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$passport_id'"));
	$customer_id = $sq_Passport_info['customer_id'];  
	global $transaction_master;


    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

     //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

    $sq_Passport_total = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$passport_id'")); 
    $sq_Passport = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from passport_payment_master where passport_id='$passport_id'"));
	$balance_amount = $sq_Passport_total['Passport_total_cost'] - $sq_Passport['payment_amount'];

    $module_name = "Passport Booking";
    $module_entry_id = $payment_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date;
    $payment_particular = get_sales_paid_particular(get_passport_booking_payment_id($payment_id,$yr2), $payment_date, $payment_amount1, $sq_passport_info['customer_id'], $payment_mode, get_passport_booking_id($passport_id,$yr2));
		$ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $pay_gl;
    $payment_side = "Debit";
    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
		$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

    $module_name = "Passport Booking";
    $module_entry_id = $payment_id;
    $transaction_id = $transaction_id1;
    $payment_amount = $payment_amount1;
    $payment_date = $payment_date;
    $payment_particular = get_sales_particular(get_passport_booking_payment_id($payment_id,$yr2), $payment_date, $payment_amount1, $sq_passport_info['customer_id']);
		$ledger_particular = get_ledger_particular('By','Cash/Bank');
    $gl_id = $cust_gl;
    $payment_side = "Credit";
    $clearance_status = "";
		$transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
}

public function bank_cash_book_save($payment_id, $branch_admin_id)
{
	global $bank_cash_book_master;

	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];

	$sq_passport_info = mysql_fetch_assoc(mysql_query("select customer_id from passport_master where passport_id='$passport_id'"));

	$module_name = "Passport Booking";
	$module_entry_id = $payment_id;
	$payment_date = $payment_date;
	$payment_amount = $payment_amount;
	$payment_mode = $payment_mode;
	$bank_name = $bank_name;
	$transaction_id = $transaction_id;
	$bank_id = $bank_id;
	$particular = get_sales_paid_particular(get_passport_booking_payment_id($payment_id,$yr2), $payment_date, $payment_amount, $sq_passport_info['customer_id'], $payment_mode, get_passport_booking_id($passport_id,$yr2));
	$clearance_status = ($payment_mode=="Cheque") ? "Pending" : "";
	$payment_side = "Debit";
	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

	$bank_cash_book_master->bank_cash_book_master_save($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type, $branch_admin_id);
}

public function passport_payment_master_update()
{
	$payment_id = $_POST['payment_id'];
	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];
	$payment_old_value = $_POST['payment_old_value'];	

	$payment_date = date('Y-m-d', strtotime($payment_date));

	$financial_year_id = $_SESSION['financial_year_id'];

	$sq_payment_info = mysql_fetch_assoc(mysql_query("select * from passport_payment_master where payment_id='$payment_id'"));

	$clearance_status = ($sq_payment_info['payment_mode']=='Cash' && $payment_mode!="Cheque") ? "Pending" : $sq_payment_info['clearance_status'];
	if($payment_mode=="Cash"){ $clearance_status = ""; }

	begin_t();

	$sq_payment = mysql_query("update passport_payment_master set passport_id='$passport_id', financial_year_id='$financial_year_id', payment_date='$payment_date', payment_amount='$payment_amount', payment_mode='$payment_mode', bank_name='$bank_name', transaction_id='$transaction_id', bank_id='$bank_id', clearance_status='$clearance_status' where payment_id='$payment_id' ");
	if(!$sq_payment){
		rollback_t();
		echo "error--Sorry, Payment not update!";
		exit;
	}
	else{

		//Finance update
		$this->finance_update($sq_payment_info, $clearance_status);

		//Bank and Cash Book Save
    	$this->bank_cash_book_update($clearance_status);

		if($GLOBALS['flag']){
			commit_t();
			//Payment email notification
			$this->payment_update_email_notification_send($payment_id);

			echo "Passport Payment has been successfully updated.";
			exit;	
		}
		else{
			rollback_t();
			exit;
		}
		
	}
	
}

public function finance_update($sq_payment_info, $clearance_status1)
{
	$row_spec = 'sales';
	$payment_id = $_POST['payment_id'];
	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_old_value = $_POST['payment_old_value'];
	$payment_amount1 = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id1 = $_POST['transaction_id'];	
	$bank_id = $_POST['bank_id'];

	$payment_date = date('Y-m-d', strtotime($payment_date));
	$year2 = explode("-", $payment_date);
	$yr2 =$year2[0];

	$sq_passport_info = mysql_fetch_assoc(mysql_query("select customer_id from passport_master where passport_id='$passport_id'"));
	$customer_id = $sq_passport_info['customer_id']; 
	global $transaction_master;

    //Getting cash/Bank Ledger
    if($payment_mode == 'Cash') {  $pay_gl = 20; }
    else{ 
	    $sq_bank = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$bank_id' and user_type='bank'"));
	    $pay_gl = $sq_bank['ledger_id'];
     } 

     //Getting customer Ledger
	$sq_cust = mysql_fetch_assoc(mysql_query("select * from ledger_master where customer_id='$customer_id' and user_type='customer'"));
	$cust_gl = $sq_cust['ledger_id'];

    $sq_Passport_total = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$passport_id'")); 
    $sq_Passport = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from passport_payment_master where passport_id='$passport_id'"));
	$balance_amount = $payment_amount1 - $payment_old_value;


	if($payment_amount1 > $payment_old_value)
	{
		$balance_amount = $payment_amount1 - $payment_old_value;
		//////Payment Amount///////
	    $module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_amount1;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $payment_amount1, $customer_id, $payment_mode, get_passport_booking_id($passport_id,$yr2));
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    ////////Balance Amount//////
	    $module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = "";
	    $payment_amount = $balance_amount;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $balance_amount, $customer_id);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_gl;
	    $payment_side = "Credit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);  

		//Reverse first payment amount
		$module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_old_value;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $payment_old_value, $customer_id, $payment_mode, get_passport_booking_id($passport_id,$yr2));
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Credit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
	}
	else if($payment_amount1 < $payment_old_value){
			$balance_amount = $payment_old_value - $payment_amount1;
			//////Payment Amount///////
	    $module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = $transaction_id1;
	    $payment_amount = $payment_amount1;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $payment_amount1, $customer_id, $payment_mode, get_passport_booking_id($passport_id,$yr2));
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Debit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);

	    ////////Balance Amount//////
	    $module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = "";
	    $payment_amount = $balance_amount;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $balance_amount, $customer_id);
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $cust_gl;
	    $payment_side = "Debit";
	    $clearance_status = "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);  
	    
		//Reverse first payment amount
		$module_name = "Passport Booking";
	    $module_entry_id = $passport_id;
	    $transaction_id = "";
	    $payment_amount = $payment_old_value;
	    $payment_date = $payment_date;
	    $payment_particular = get_sales_paid_particular(get_passport_booking_id($passport_id,$yr2), $payment_date, $payment_old_value, $customer_id, $payment_mode, get_passport_booking_id($passport_id,$yr2));
			$ledger_particular = get_ledger_particular('By','Cash/Bank');
	    $gl_id = $pay_gl;
	    $payment_side = "Credit";
	    $clearance_status = ($payment_mode!="Cash") ? "Pending" : "";
	    $transaction_master->transaction_save($module_name, $module_entry_id, $transaction_id, $payment_amount, $payment_date, $payment_particular, $gl_id, $payment_side, $clearance_status, $row_spec,$branch_admin_id,$ledger_particular);
	}
	else{
		//Do nothing
	}


}

public function bank_cash_book_update($clearance_status)
{
	global $bank_cash_book_master;

	$payment_id = $_POST['payment_id'];
	$passport_id = $_POST['passport_id'];
	$payment_date = $_POST['payment_date'];
	$payment_amount = $_POST['payment_amount'];
	$payment_mode = $_POST['payment_mode'];
	$bank_name = $_POST['bank_name'];
	$transaction_id = $_POST['transaction_id'];
	$bank_id = $_POST['bank_id'];
	$payment_date = date('Y-m-d', strtotime($payment_date));
	$year2 = explode("-", $payment_date);
	$yr2 =$year2[0];

	$sq_passport_info = mysql_fetch_assoc(mysql_query("select customer_id from passport_master where passport_id='$passport_id'"));

	$module_name = "Passport Booking";
	$module_entry_id = $payment_id;
	$payment_date = $payment_date;
	$payment_amount = $payment_amount;
	$payment_mode = $payment_mode;
	$bank_name = $bank_name;
	$transaction_id = $transaction_id;
	$bank_id = $bank_id;
	$particular = get_sales_paid_particular(get_passport_booking_payment_id($payment_id,$yr2), $payment_date, $payment_amount, $sq_passport_info['customer_id'], $payment_mode, get_passport_booking_id($passport_id,$yr2));
	$clearance_status = $clearance_status;
	$payment_side = "Debit";
	$payment_type = ($payment_mode=="Cash") ? "Cash" : "Bank";

	$bank_cash_book_master->bank_cash_book_master_update($module_name, $module_entry_id, $payment_date, $payment_amount, $payment_mode, $bank_name, $transaction_id, $bank_id, $particular, $clearance_status, $payment_side, $payment_type);	
}

//////////////////////////////////**Payment email notification send start**/////////////////////////////////////
public function payment_email_notification_send($passport_id, $payment_amount, $payment_mode, $payment_date)
{
	global $secret_key,$encrypt_decrypt;
   $sq_passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$passport_id'"));
   $total_amount = $sq_passport_info['passport_total_cost'];

   $date = $sq_passport_info['created_at'];
   $yr = explode("-", $date);
   $year =$yr[0];

   $sq_customer_info = mysql_fetch_assoc(mysql_query("select email_id,first_name from customer_master where customer_id='$sq_passport_info[customer_id]'"));
   $email_id = $encrypt_decrypt->fnDecrypt($sq_customer_info['email_id'], $secret_key);

   $due_date = ($sq_passport_info['due_date'] == '1970-01-01') ? '' : $sq_passport_info['due_date'];    
   $sq_total_amount = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from passport_payment_master where passport_id='$passport_id' and clearance_status!='Cancelled'"));
   $paid_amount = $sq_total_amount['sum'];

   $payment_id = get_passport_booking_payment_id($payment_id,$year);

   $subject = 'Payment Acknowledgement (Booking ID : '.get_passport_booking_id($passport_id,$year).' )';

   global $model;
   $model->generic_payment_mail('52',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $due_date,$email_id,$subject, $sq_customer_info['first_name']);
}
//////////////////////////////////**Payment email notification send end**/////////////////////////////////////


//////////////////////////////////**Payment update email notification send start**/////////////////////////////////////
public function payment_update_email_notification_send($payment_id)
{
	global $secret_key,$encrypt_decrypt;
	$sq_payment_info = mysql_fetch_assoc(mysql_query("select * from passport_payment_master where payment_id='$payment_id' and clearance_status!='Cancelled'"));
	$passport_id = $sq_payment_info['passport_id'];
	$payment_amount = $sq_payment_info['payment_amount'];
   	$payment_mode = $sq_payment_info['payment_mode'];
   	$payment_date = $sq_payment_info['payment_date'];
	$update_payment = true;

	$sq_passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id='$passport_id'"));
	$total_amount = $sq_passport_info['passport_total_cost'];
	$date = $sq_passport_info['created_at'];
    $yr = explode("-", $date);
    $year =$yr[0];
	$sq_total_amount = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from passport_payment_master where passport_id='$passport_id' and clearance_status!='Cancelled'"));
    $paid_amount = $sq_total_amount['sum'];
	$due_date = ($sq_passport_info['due_date'] == '1970-01-01') ? '' : $sq_passport_info['due_date'];    
	$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$sq_passport_info[customer_id]'"));
	
	$email_id = $encrypt_decrypt->fnDecrypt($sq_customer_info['email_id'], $secret_key);
	$payment_id = get_passport_booking_payment_id($payment_id,$yr2);
	$subject = 'Passport Booking Payment Correction (Booking ID : '.get_passport_booking_id($passport_id,$year).' )';
	global $model;
	   $model->generic_payment_mail('62',$payment_amount, $payment_mode, $total_amount, $paid_amount, $payment_date, $due_date, $email_id, $subject, $sq_customer_info['first_name']);

}
//////////////////////////////////**Payment update email notification send end**/////////////////////////////////////
	
//////////////////////////////////**Payment sms notification send start**/////////////////////////////////////
public function payment_sms_notification_send($passport_id, $payment_amount, $payment_mode)
{
	global $secret_key,$encrypt_decrypt,$currency;
	$sq_passport_info = mysql_fetch_assoc(mysql_query("select customer_id from passport_master where passport_id='$passport_id'"));
	$customer_id = $sq_passport_info['customer_id'];

	$sq_customer_info = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));
	
	$sq_currency = mysql_fetch_assoc(mysql_query("select * from currency_name_master where id='$currency'"));
	$currency_code = $sq_currency['currency_code'];

	$mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer_info['contact_no'], $secret_key);
	
	$message = "Dear ".$sq_customer_info['first_name']." ".$sq_customer_info['last_name'].", Acknowledge your payment of ".$payment_amount." ".$currency_code.", which we received for Passport services.";
	
    global $model;
    $model->send_message($mobile_no, $message);
}
//////////////////////////////////**Payment sms notification send end**/////////////////////////////////////
public function whatsapp_send(){
	global $app_contact_no,$session_emp_id,$currency_logo;
	global $secret_key,$encrypt_decrypt;
   $booking_id = $_POST['booking_id'];
   $payment_amount = $_POST['payment_amount'];
   $currency = "Rs.";
   $sq_passport_info = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id=".$_POST['booking_id']));
  
   $total_amount = $sq_passport_info['passport_total_cost'];
   $sq_pay = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from passport_payment_master where clearance_status!='Cancelled' and passport_id=".$_POST['booking_id']));
   $total_pay_amt = $sq_pay['sum'];
   $outstanding =  $total_amount - ($total_pay_amt+$payment_amount);
  
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id= '$session_emp_id"));
if($session_emp_id == 0){
   $contact = $app_contact_no;
}
else{
   $contact = $sq_emp_info['mobile_no'];
}

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id=".$sq_passport_info['customer_id']));
$mobile_no = $encrypt_decrypt->fnDecrypt($sq_customer['contact_no'], $secret_key);
$whatsapp_msg = rawurlencode('Hello Dear '.$sq_customer[first_name].',
Hope you are doing great. This is to inform you that we have received your payment. We look forward to provide you a great experience.
*Total Amount* : '.$currency_logo.' '.$total_amount.'
*Paid Amount* : '.$currency_logo.' '.$payment_amount.'
*Balance Amount* : '.$currency_logo.' '.$outstanding.'
  
Please do not hesitate to call us on '.$contact.' if you have any concern. 
Thank you. ');
   $link = 'https://web.whatsapp.com/send?phone='.$mobile_no.'&text='.$whatsapp_msg;
   echo $link;
  }
}
?>