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
      <div class="col-md-8 no-pad">
        <img src="<?= $app_quot_img?>" class="img-responsive">
      </div>
      <div class="col-md-4 no-pad">
      </div>
      <h1 class="landingpageTitle"><?= $sq_pckg['package_name'] ?><?=' ('.$sq_pckg['package_code'].')' ?></h1>
      <div class="packageDeatailPanel">
        <div class="landingPageBlocks">
        
            <div class="detailBlock">
              <div class="detailBlockIcon detailBlockBlue">
                <i class="fa fa-map-marker"></i>
              </div>
              <div class="detailBlockContent">
                <h3 class="contentValue"><?= $sq_dest['dest_name'] ?></h3>
                <span class="contentLabel">DESTINATION</span>
              </div>
            </div>

            <div class="detailBlock">
              <div class="detailBlockIcon detailBlockGreen">
                <i class="fa fa-sun-o"></i>
              </div>
              <div class="detailBlockContent">
                <h3 class="contentValue"><?= $sq_pckg['total_days'] ?></h3>
                <span class="contentLabel">TOTAL DAYS</span>
              </div>
            </div>

            <div class="detailBlock">
              <div class="detailBlockIcon detailBlockGreen">
                <i class="fa fa-moon-o"></i>
              </div>
              <div class="detailBlockContent">
                <h3 class="contentValue"><?= $sq_pckg['total_nights'] ?></h3>
                <span class="contentLabel">TOTAL NIGHTS</span>
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
            <div class="col-md-6 no-pad">
              <img src="<?= $newUrl1 ?>" class="img-responsive">
            </div>
            <?php
            }?>
          </div>
      </section>  


    <!-- Itinerary -->
    <?php 
      $count = 1;
      $checkPageEnd = 0;
      $sq_package_program = mysql_query("select * from custom_package_program where package_id = '$package_id'");
      while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
        if($checkPageEnd%3==0 || $checkPageEnd==0){
          $go = $checkPageEnd + 2;
          $flag = 0;
    ?>
    <section class="pageSection main_block">
      <!-- background Image -->
      <img src="<?= BASE_URL ?>images/quotation/p5/pageBG.png" class="img-responsive pageBGImg">
    
      <section class="itinerarySec pageSectionInner main_block mg_tp_30">

          
          <?php 
            }
            $itinerarySide= ($count%2!=0)?"leftItinerary":"rightItinerary";
          ?>
          <section class="print_single_itinenary <?= $itinerarySide ?>">
              <div class="itneraryImg">
                <div class="itneraryImgblock">
                  <img src="http://itourscloud.com/quotation_format_images/dummy-image.jpg" class="img-responsive">
                </div>
                <div class="itneraryDayAccomodation">
                  <span><i class="fa fa-bed"></i> : <?=  $row_itinarary['stay'] ?></span>
                  <span><i class="fa fa-cutlery"></i> : <?= $row_itinarary['meal_plan'] ?></span>
                </div>
              </div>
              <div class="itneraryText">
                <div class="itneraryDayInfo">
                  <i class="fa fa-map-marker" aria-hidden="true"></i><span> Day <?= $count ?> : <?= $row_itinarary['attraction'] ?> </span>
                </div>
                <div class="itneraryDayPlan">
                  <p><?= $row_itinarary['day_wise_program'] ?></p>
                </div>
              </div>
          </section>

          <?php 
          if($go == $checkPageEnd){
            $flag = 1;
          ?>

      </section>
    </section>
    <?php 
      } $count++; $checkPageEnd++; } 
      if($flag == 0){
    ?>
      </section>
    </section>
    <?php  } ?>

    <?php
    $sq_hotelc = mysql_num_rows(mysql_query("select * from custom_package_hotels where package_id='$package_id'"));
    if($sq_hotelc!=0){?>
    <!-- traveling Information -->
    <section class="pageSection main_block">
      <!-- background Image -->
      <img src="<?= BASE_URL ?>images/quotation/p5/pageBG.png" class="img-responsive pageBGImg">

      <section class="travelingDetails main_block mg_tp_30 pageSectionInner">

        <!-- Hotel -->
        <section class="transportDetailsPanel transportDetailsleft main_block">
          <div class="travsportInfoBlock">
            <div class="transportIcon">
              <img src="<?= BASE_URL ?>images/quotation/p4/TI_hotel.png" class="img-responsive">
            </div>
            <div class="transportDetails">
             <div class="col-md-12 no-pad">
                <div class="table-responsive">
                  <table class="table tableTrnasp no-marg" id="tbl_emp_list">
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
          </div>
        </section>

      </section>
    </section>

    <?php } ?>
    <?php
    $sq_hotelc = mysql_num_rows(mysql_query("select * from custom_package_transport where package_id='$package_id'"));
    if($sq_hotelc!=0){?>
    <!-- traveling Information -->
    <section class="pageSection main_block">
      <!-- background Image -->
      <img src="<?= BASE_URL ?>images/quotation/p5/pageBG.png" class="img-responsive pageBGImg">

      <section class="travelingDetails main_block mg_tp_30 pageSectionInner">

        <!-- Transport -->
        <section class="transportDetailsPanel transportDetailsleft main_block">
          <div class="travsportInfoBlock">
            <div class="transportIcon">
              <img src="<?= BASE_URL ?>images/quotation/p4/TI_car.png" class="img-responsive">
            </div>
            <div class="transportDetails">
             <div class="col-md-12 no-pad">
                <div class="table-responsive">
                  <table class="table tableTrnasp no-marg" id="tbl_emp_list">
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
          </div>
        </section>

      </section>
    </section>
    <?php } ?>
    <!-- Inclusion -->
  <?php if($sq_pckg['inclusions'] != ''){?>
    <section class="pageSection main_block">
      <!-- background Image -->
        <img src="<?= BASE_URL ?>images/quotation/p5/pageBGF.jpg" class="img-responsive pageBGImg">

        <section class="incluExcluTerms pageSectionInner main_block mg_tp_30">

          <!-- Inclusion -->
          <div class="row">
            <div class="col-md-12 mg_tp_30 mg_bt_30">
              <div class="incluExcluTermsTabPanel inclusions main_block">
                  <h3 class="incexTitle">Inclusions</h3>
                  <div class="tabContent">
                      <pre class="real_text"><?= $sq_pckg['inclusions'] ?></pre>      
                  </div>
              </div>
            </div>
          </div>
                      
        </section>
    </section>
<?php } ?>


  <!--  Exclusion -->
  <?php if($sq_pckg['exclusions'] != ''){?>
    <section class="pageSection main_block">
      <!-- background Image -->
        <img src="<?= BASE_URL ?>images/quotation/p5/pageBGF.jpg" class="img-responsive pageBGImg">

        <section class="incluExcluTerms pageSectionInner main_block mg_tp_30">

          <!-- Exclusion -->
          <div class="row">
            <div class="col-md-12 mg_tp_30 mg_bt_30">
              <div class="incluExcluTermsTabPanel exclusions main_block">
                  <h3 class="incexTitle">Exclusions</h3>
                  <div class="tabContent">
                      <pre class="real_text"><?= $sq_pckg['exclusions'] ?></pre>      
                  </div>
              </div>
            </div>
          </div>
                      
        </section>
    </section>
<?php } ?>

</body>
</html>