<?php
//Generic Files
include "../../../../model.php"; 
include "printFunction.php";
global $app_quot_img;

$package_id=$_GET['package_id'];
$sq_pckg = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$package_id'"));
$sq_dest = mysql_fetch_assoc(mysql_query("select * from destination_master where dest_id='$sq_pckg[dest_id]'"));
$transport_bus = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$sq_pckg[transport_id]'"));
?>

    <!-- landingPage -->
    <section class="landingSec main_block">

      <div class="landingPageTop main_block">
        <img src="<?= $app_quot_img?>" class="img-responsive">
        <h1 class="landingpageTitle"><?= $sq_pckg['package_name'] ?> <em><?='('.$sq_pckg['package_code'].')' ?></em></h1>
        <div class="landingdetailBlock">
          <div class="detailBlock text-center" style="border-top:0px;">
            <div class="detailBlockIcon detailBlockBlue">
              <i class="fa fa-map-marker"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_dest['dest_name'] ?></h3>
              <span class="contentLabel">DESTINATION</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockGreen">
                <i class="fa fa-sun-o"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_pckg['total_days'] ?></h3>
              <span class="contentLabel">TOTAL DAYS</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockYellow">
                <i class="fa fa-moon-o"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_pckg['total_nights'] ?></h3>
              <span class="contentLabel">TOTAL NIGHT</span>
            </div>
          </div>

          
        </div>
      </div>

      <div class="ladingPageBottom main_block side_pad">

        <div class="row">
          <div class="col-md-4">
          </div>
          <div class="col-md-8">
            <div class="print_header_logo main_block">
              <img src="<?= $admin_logo_url ?>" class="img-responsive">
            </div>
            <div class="print_header_contact text-right main_block">
              <span class="title"><?php echo $app_name; ?></span><br>
              <?php if($app_address != ''){?><p class="address no-marg"><?php echo $app_address; ?></p><?php } ?>
              <?php if($app_contact_no != ''){?><p class="no-marg"><i class="fa fa-phone" style="margin-right: 5px;"></i><?php echo $app_contact_no; ?></p><?php } ?>
              <?php if($app_email_id != ''){?><p class="no-marg"><i class="fa fa-envelope" style="margin-right: 5px;"></i><?php echo $app_email_id; ?></p><?php } ?>
              <?php if($app_website != ''){?><p><i class="fa fa-globe" style="margin-right: 5px;"></i><?php echo $app_website; ?></p><?php } ?>
            </div>
          </div>
        </div>
        
      </div>
    </section>

        <!-- SIGHTSEEING -->
      <section class="print_sec main_block">
        <?php 
        $sq_img = mysql_query("select * from custom_package_images where package_id='$package_id'");
        ?>
          <div class="row print_sigthseing_images">
          <?php while ($row_img = mysql_fetch_assoc($sq_img) ) 
            {
              $count_i++;
              $url = $row_img['image_url'];
              $pos = strstr($url,'uploads');
              if ($pos != false)   {
                  $newUrl = preg_replace('/(\/+)/','/',$row_img['image_url']); 
                  $newUrl1 = BASE_URL.str_replace('../', '', $newUrl);
              }
              else{
                  $newUrl1 =  $row_img['image_url']; 
              }
            ?>
            <div class="col-md-4 no-pad">
              <img src="<?= $newUrl1 ?>" class="img-responsive">
            </div>
            <?php
            }?>
          </div>
      </section>  


    <!-- Itinerary -->

    <section class="itinerarySec main_block side_pad">
          
      <div class="print_itinenary main_block no-pad no-marg">
        <?php 
        $count = 1;
        $sq_package_program = mysql_query("select * from custom_package_program where package_id = '$package_id'");
        while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
            if($count%2!=0){
        ?>

        <section class="singleItinenrary leftItinerary col-md-12 no-pad mg_tp_30 mg_bt_30">
          <div class="col-md-6">
            <div class="itneraryImg">
              <img src="http://itourscloud.com/quotation_format_images/dummy-image.jpg" class="img-responsive">
              <h5>Day-<?= $count ?></h5>
              <div class="itineraryDetail">
                <ul>
                  <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
                  <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
                </ul>
              </div>
            </div>
            </div>
            <div class="col-md-6">
              <div class="itneraryText">
                <div class="dayCount">
                  <span><i class="fa fa-map-marker"></i> <?= $row_itinarary['attraction'] ?></span>
                </div>
                <div class="dayWiseProgramDetail">
                  <p><?= $row_itinarary['day_wise_program'] ?></p>
                </div>
              </div>
            </div> 
        </section>

        <hr class="main_block no-marg">
        <?php }else{ ?>
        <section class="singleItinenrary rightItinerary col-md-12 no-pad mg_tp_30 mg_bt_30">
            <div class="col-md-6">
              <div class="itneraryText">
                <div class="dayCount">
                  <span><i class="fa fa-map-marker"></i> <?= $row_itinarary['attraction'] ?></span>
                </div>
                <div class="dayWiseProgramDetail">
                  <p><?= $row_itinarary['day_wise_program'] ?></p>
                </div>
              </div>
            </div> 
          <div class="col-md-6">
            <div class="itneraryImg">
              <img src="http://itourscloud.com/quotation_format_images/dummy-image.jpg" class="img-responsive">
              <h5>Day-<?= $count ?></h5>
              <div class="itineraryDetail">
                <ul>
                  <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
                  <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
                </ul>
              </div>
            </div>
            </div>
        </section>

        <hr class="main_block no-marg">
        <?php } $count++; } ?>
      </div>

    </section>

    <section class="itinerarySec main_block side_pad">
    <div class="print_itinenary main_block no-pad no-marg">
    <?php
    $sq_hotelc = mysql_num_rows(mysql_query("select * from custom_package_hotels where package_id='$package_id'"));
    if($sq_hotelc!=0){?>
    <!-- traveling Information -->
    <section class="travelingDetails main_block mg_tp_30">         
          <!-- Hotel -->
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row mg_bt_20">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/hotel.png" class="img-responsive">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                      <th>City</th>
                      <th>Hotel Name</th>
                      <th>Total Nights</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $sq_hotel = mysql_query("select * from custom_package_hotels where package_id='$package_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel)){
                        $hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
                        $city_name = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_name]'"));
                      ?>                      
                      <tr>
                          <?php
                          $sql = mysql_fetch_assoc(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$row_hotel[hotel_name]'"));
                          $sq_count_h = mysql_num_rows(mysql_query("select * from custom_package_hotels where package_id='$package_id' "));
                          if($sq_count_h ==0){
                            $download_url =  BASE_URL.'images/dummy-image.jpg';
                          }
                          else{  
                                $image = $sql['hotel_pic_url']; 
                                $download_url = preg_replace('/(\/+)/','/',$image);
                          }
                          ?>
                          <td><?php echo $city_name['city_name']; ?></td>
                          <td><?php echo $hotel_name['hotel_name'].$similar_text; ?></td>
                          <td></span><?php echo $row_hotel['total_days']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
            </div>
          </section>
    </section>
    <?php } ?>
    <?php
    $sq_hotelc = mysql_num_rows(mysql_query("select * from custom_package_transport where package_id='$package_id'"));
    if($sq_hotelc!=0){?>
    <!-- traveling Information -->
    <section class="travelingDetails main_block mg_tp_30">         
          <!-- Transport -->
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row mg_bt_20">
              <div class="col-md-6">
              </div>
              <div class="col-md-6">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/car.png" class="img-responsive">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>VEHICLE</th>
                        <th>Cost</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $count = 0;
                      $sq_hotel = mysql_query("select * from custom_package_transport where package_id='$package_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel)){
                        $transport_name = mysql_fetch_assoc(mysql_query("select * from transport_agency_bus_master where bus_id='$row_hotel[vehicle_name]'"));
                        ?>
                        <tr>
                          <td><?= $transport_name['bus_name'].$similar_text ?></td>
                          <td><?= $row_hotel['cost'] ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
            </div>
          </section>
    </section>
    <?php } ?>
    </div>
    </section>

    <!-- Inclusion Exclusion --><!-- Terms and Conditions -->
    <section class="incluExcluTerms main_block fullHeightLand">

      <!-- Inclusion Exclusion -->
      <div class="row side_pad">
        <div class="col-md-1 mg_tp_30">
        </div>
        <div class="col-md-5 mg_tp_30">
          <div class="incluExcluTermsTabPanel main_block">
              <h3 class="incexTitle">Inclusions</h3>
              <div class="tabContent">
                  <pre class="real_text"><?= $sq_pckg['inclusions'] ?></pre>      
              </div>
          </div>
        </div>
        <div class="col-md-5 mg_tp_30">
          <div class="incluExcluTermsTabPanel main_block">
              <h3 class="incexTitle">Exclusions</h3>
              <div class="tabContent">
                  <pre class="real_text"><?= $sq_pckg['exclusions'] ?></pre>      
              </div>
          </div>
        </div>
      </div>
                  
    </section>

</body>
</html>