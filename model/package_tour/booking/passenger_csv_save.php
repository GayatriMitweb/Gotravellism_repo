<?php 

class passenger_csv_save{

public function passenger_csv_save1()
{
    $cust_csv_dir = $_POST['cust_csv_dir'];
    $pass_info_arr = array();

    $flag = true;

    $cust_csv_dir = explode('uploads', $cust_csv_dir);
    $cust_csv_dir = BASE_URL.'uploads'.$cust_csv_dir[1];

    begin_t();

    $count = 1;

    $arrResult  = array();
    $handle = fopen($cust_csv_dir, "r");
    if(empty($handle) === false) {       

        $max_booking_id1 = mysql_fetch_assoc(mysql_query("select max(booking_id) as max from package_tour_booking_master"));
        $max_booking_id = $max_booking_id1['max']+1;

        while(($data = fgetcsv($handle, ",")) !== FALSE){
            if($count == 1) { $count++; continue; }
            if($count>0){
                
            $sq = mysql_query("select max(traveler_id) as max from package_travelers_details");
            $value = mysql_fetch_assoc($sq);
            $max_traveler_id = $value['max'] + 1;
            $arr = array(
                'm_honorific' => $data[0],
                'm_first_name' => $data[1],
                'm_middle_name' => $data[2],
                'm_last_name' => $data[3],
                'm_gender' => $data[4],
                'm_birth_date1' => $data[5],
                'm_age' => $data[6],
                'm_adolescence' => $data[7],
                'm_passport_no' => $data[8],
                'm_passport_issue_date1' => $data[9],
                'm_passport_expiry_date1'  => $data[10]
                );

            array_push($pass_info_arr, $arr);
                //$m_birth_date = date('Y-m-d',strtotime($m_birth_date1));
                //$m_passport_issue_date = date('Y-m-d',strtotime($m_passport_issue_date1));
                //$m_passport_expiry_date = date('Y-m-d',strtotime($m_passport_expiry_date1));

				/*$sq = mysql_query("insert into package_travelers_details (traveler_id, booking_id, m_honorific, first_name, middle_name, last_name, gender, birth_date, age, adolescence, passport_no, passport_issue_date, passport_expiry_date, status) values ('$max_traveler_id', '$max_booking_id', '$m_honorific', '$m_first_name', '$m_middle_name', '$m_last_name', '$m_gender', '$m_birth_date', '$m_age', '$m_adolescence', '$m_passport_no', '$m_passport_issue_date', '$m_passport_expiry_date', 'Active')");   

                if(!$sq)
                {
                  $GLOBALS['flag'] = false;
                  echo "Error at row".($i+1)." for traveler members.";
                }  */
			        
            }  
            

            $count++;

        }
       
        fclose($handle);
    }
echo json_encode($pass_info_arr);

    /*if($flag){
      commit_t();
      echo "Customer information imported";
      exit;
    }
    else{
      rollback_t();
      exit;
    }*/

}

}
?>