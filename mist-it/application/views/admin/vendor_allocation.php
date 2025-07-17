<style type="text/css">
  #table-wrapper {
  position:relative;
}
#table-scroll {
  overflow:auto;  
  margin-top:20px;
}
#table-wrapper table {
  width:100%;

}
#table-wrapper table * {
  color:black;
}
#table-wrapper table thead th .text {
  position:absolute;   
  top:-20px;
  z-index:2;
  height:20px;
  width:35%;
  border:1px solid red;
}
</style>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">

              <?php    if ($this->permission['access_report_list_vendor'] == 1) { ?>
               <div class="row">
               <div class="col-sm-4 form-group">
                <?php
                    $month = array('0' => 'Select Month','01' => 'January','02' => 'Feb','03' => 'March','04' => 'April','05' => 'May','06' => 'June','07' => 'July','08' => 'August','09' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
                    echo form_dropdown('vendor_month',$month, set_value('vendor_month',date('m')),'class="select2" id="vendor_month"');?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php
                   $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
                   echo form_dropdown('vendor_year', $year, set_value('vendor_year',date('Y')), 'class="select2" id="vendor_year"');
                   ?>
                </div>

                <div class="col-sm-4 form-group">
                     <input type="button" name="searchrecords_vendor_allocation" id="searchrecords_vendor_allocation" class="btn btn-md btn-info" value="Filter">
                  </div>
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_vendor_allocation" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>Date</th>
                    <th style = 'outline: thin solid'>Type</th>
                    <th style = 'outline: thin solid'>1</th>
                    <th style = 'outline: thin solid'>2</th>
                    <th style = 'outline: thin solid'>3</th>
                    <th style = 'outline: thin solid'>4</th>
                    <th style = 'outline: thin solid'>5</th>
                    <th style = 'outline: thin solid'>6</th>
                    <th style = 'outline: thin solid'>7</th>
                    <th style = 'outline: thin solid'>8</th>
                    <th style = 'outline: thin solid'>9</th>
                    <th style = 'outline: thin solid'>10</th>
                    <th style = 'outline: thin solid'>11</th>
                    <th style = 'outline: thin solid'>12</th>
                    <th style = 'outline: thin solid'>13</th>
                    <th style = 'outline: thin solid'>14</th>
                    <th style = 'outline: thin solid'>15</th>
                    <th style = 'outline: thin solid'>16</th>
                    <th style = 'outline: thin solid'>17</th>
                    <th style = 'outline: thin solid'>18</th>
                    <th style = 'outline: thin solid'>19</th>
                    <th style = 'outline: thin solid'>20</th>
                    <th style = 'outline: thin solid'>21</th>
                    <th style = 'outline: thin solid'>22</th>
                    <th style = 'outline: thin solid'>23</th>
                    <th style = 'outline: thin solid'>24</th>
                    <th style = 'outline: thin solid'>25</th>
                    <th style = 'outline: thin solid'>26</th>
                    <th style = 'outline: thin solid'>27</th>
                    <th style = 'outline: thin solid'>28</th>
                    <th style = 'outline: thin solid'>29</th>
                    <th style = 'outline: thin solid'>30</th>
                    <th style = 'outline: thin solid'>31</th>
                    <th style = 'outline: thin solid'>Total</th>
                    <th style = 'outline: thin solid'>C/F</th>
                  </tr>
                </thead>
                 <tbody>
                 <?php 
                  $i = 1;
                 
                  echo  "<tr >";
                    foreach ($vendors_list as $key  => $vendors_lists)
                    { 

                      echo  "<tr  style = 'outline: thin solid'>";
                      echo  "<tr class = show_hide_".$vendors_lists['id'].">";
                      echo  "<td  rowspan='4' style = 'outline: thin solid'>".$vendors_lists['vendor_name']." <br>( ".$vendors_lists['vendors_components']." ) </td>";
                      echo  "<td style = 'outline: thin solid'>"."Initiated"."</td>";
                      echo  "<td><div id = 'initiated_one_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_two_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_three_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_fourth_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_five_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_six_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_seven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_eight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_nine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_ten_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_eleven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twelve_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_thirteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_fourteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_fifteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_sixteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_seventeen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_eightteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_nineteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twenty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentytwo_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentythree_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentyfour_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentyfive_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentysix_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentyseven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentyeight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_twentynine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_thirty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'initiated_thirtyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'initiated_total_vendor_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'>-</td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$vendors_lists['id'].">";
                      echo  "<td style = 'outline: thin solid'>"."WIP"."</td>";
                      echo  "<td><div id = 'wip_one_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_two_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_three_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_fourth_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_five_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_six_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_seven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_eight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_nine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_ten_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_eleven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twelve_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_thirteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_fourteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_fifteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_sixteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_seventeen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_eightteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_nineteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twenty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentytwo_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentythree_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentyfour_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentyfive_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentysix_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentyseven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentyeight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_twentynine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_thirty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'wip_thirtyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'wip_total_vendor_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'currentwip_".$vendors_lists['id']."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$vendors_lists['id'].">";
                      echo  "<td style = 'outline: thin solid'>"."Insufficiency"."</td>";
                      echo  "<td><div id = 'insufficiency_one_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_two_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_three_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_fourth_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_five_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_six_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_seven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_eight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_nine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_ten_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_eleven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twelve_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_thirteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_fourteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_fifteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_sixteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_seventeen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_eightteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_nineteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twenty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentytwo_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentythree_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentyfour_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentyfive_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentysix_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentyseven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentyeight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_twentynine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_thirty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'insufficiency_thirtyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'insufficiency_total_vendor_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'currentinsuff_".$vendors_lists['id']."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$vendors_lists['id'].">";
                      echo  "<td style = 'outline: thin solid'>"."Closed"."</td>";
                      echo  "<td><div id = 'closed_one_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_two_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_three_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_fourth_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_five_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_six_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_seven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_eight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_nine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_ten_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_eleven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twelve_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_thirteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_fourteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_fifteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_sixteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_seventeen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_eightteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_nineteen_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twenty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentytwo_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentythree_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentyfour_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentyfive_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentysix_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentyseven_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentyeight_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_twentynine_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_thirty_".$vendors_lists['id']."'></div></td>";
                      echo  "<td><div id = 'closed_thirtyone_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'closed_total_vendor_".$vendors_lists['id']."'></div></td>";
                      echo  "<td style = 'outline: thin solid'>-</td>";
                      echo  "</tr>";
                     
                      echo  "</tr>";
                                          
                    }
                    echo  "</tr>";
                  
                    
                    ?>
                   <tr>
                </tbody>
              </table>

              <?php  }else { echo 'You have not permission to access this page'; }?>
            </div>
           </div>
            
            </div>
          </div>
        </div>
      </div>
<script>
  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true, 
  });
 
  $(".select2").select2();
</script>   
   
<script>  
var $ = jQuery;
$(document).ready(function() {
  

   var month = $('#vendor_month').val();
   var year =  $('#vendor_year').val();  

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/vendor_allocation_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($vendors_list as $key  => $vendors_lists)
            { 
            ?> 

                $("#initiated_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
                
            
                $("#wip_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
                $("#currentwip_<?php echo $vendors_lists['id'] ?>").html('');


                $("#closed_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');

                $("#insufficiency_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
                $("#currentinsuff_<?php echo $vendors_lists['id'] ?>").html('');


             
            <?php      
            }
            ?>
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;

        
        
            <?php
            foreach ($vendors_list as $key  => $vendors_lists)
            { 
            ?>
               var total_initiated = 0;
               var total_wip = 0;
               var total_insuff = 0;
               var total_closed = 0;

               if(typeof(type.initiated_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_one_<?php echo $vendors_lists['id'] ?>.initiated_one_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_one_<?php echo $vendors_lists['id'] ?>.initiated_one_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.initiated_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_two_<?php echo $vendors_lists['id'] ?>.initiated_two_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_two_<?php echo $vendors_lists['id'] ?>.initiated_two_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_three_<?php echo $vendors_lists['id'] ?>.initiated_three_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_three_<?php echo $vendors_lists['id'] ?>.initiated_three_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_four_<?php echo $vendors_lists['id'] ?>.initiated_four_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_four_<?php echo $vendors_lists['id'] ?>.initiated_four_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_five_<?php echo $vendors_lists['id'] ?>.initiated_five_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_five_<?php echo $vendors_lists['id'] ?>.initiated_five_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_six_<?php echo $vendors_lists['id'] ?>.initiated_six_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_six_<?php echo $vendors_lists['id'] ?>.initiated_six_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_seven_<?php echo $vendors_lists['id'] ?>.initiated_seven_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_seven_<?php echo $vendors_lists['id'] ?>.initiated_seven_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eight_<?php echo $vendors_lists['id'] ?>.initiated_eight_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_eight_<?php echo $vendors_lists['id'] ?>.initiated_eight_<?php echo $vendors_lists['id'] ?>);  
               }  
               if(typeof(type.initiated_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_nine_<?php echo $vendors_lists['id'] ?>.initiated_nine_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_nine_<?php echo $vendors_lists['id'] ?>.initiated_nine_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.initiated_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_ten_<?php echo $vendors_lists['id'] ?>.initiated_ten_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_ten_<?php echo $vendors_lists['id'] ?>.initiated_ten_<?php echo $vendors_lists['id'] ?>);  
               } 
                             
            
               if(typeof(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>.initiated_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>.initiated_eleven_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>.initiated_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>.initiated_twelve_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>.initiated_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>.initiated_thirteen_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>.initiated_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>.initiated_fourteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>.initiated_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>.initiated_fifteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>.initiated_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>.initiated_sixteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>.initiated_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>.initiated_seventeen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>.initiated_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>.initiated_eightteen_<?php echo $vendors_lists['id'] ?>);
               }  
               if(typeof(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>.initiated_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>.initiated_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>.initiated_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>.initiated_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>.initiated_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>.initiated_twentyone_<?php echo $vendors_lists['id'] ?>);
               }
             
               if(typeof(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>.initiated_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>.initiated_twentythree_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>.initiated_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>.initiated_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>.initiated_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>.initiated_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>.initiated_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>.initiated_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }


               if(typeof(type.wip_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_one_<?php echo $vendors_lists['id'] ?>.wip_one_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_one_<?php echo $vendors_lists['id'] ?>.wip_one_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.wip_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_two_<?php echo $vendors_lists['id'] ?>.wip_two_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_two_<?php echo $vendors_lists['id'] ?>.wip_two_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_three_<?php echo $vendors_lists['id'] ?>.wip_three_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_three_<?php echo $vendors_lists['id'] ?>.wip_three_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_four_<?php echo $vendors_lists['id'] ?>.wip_four_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_four_<?php echo $vendors_lists['id'] ?>.wip_four_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_five_<?php echo $vendors_lists['id'] ?>.wip_five_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_five_<?php echo $vendors_lists['id'] ?>.wip_five_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_six_<?php echo $vendors_lists['id'] ?>.wip_six_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_six_<?php echo $vendors_lists['id'] ?>.wip_six_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_seven_<?php echo $vendors_lists['id'] ?>.wip_seven_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_seven_<?php echo $vendors_lists['id'] ?>.wip_seven_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eight_<?php echo $vendors_lists['id'] ?>.wip_eight_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_eight_<?php echo $vendors_lists['id'] ?>.wip_eight_<?php echo $vendors_lists['id'] ?>);  
               }  
               if(typeof(type.wip_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_nine_<?php echo $vendors_lists['id'] ?>.wip_nine_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_nine_<?php echo $vendors_lists['id'] ?>.wip_nine_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.wip_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_ten_<?php echo $vendors_lists['id'] ?>.wip_ten_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_ten_<?php echo $vendors_lists['id'] ?>.wip_ten_<?php echo $vendors_lists['id'] ?>);  
               } 
                             
            
               if(typeof(type.wip_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eleven_<?php echo $vendors_lists['id'] ?>.wip_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_eleven_<?php echo $vendors_lists['id'] ?>.wip_eleven_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.wip_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twelve_<?php echo $vendors_lists['id'] ?>.wip_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twelve_<?php echo $vendors_lists['id'] ?>.wip_twelve_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>.wip_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>.wip_thirteen_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>.wip_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>.wip_fourteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>.wip_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>.wip_fifteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>.wip_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>.wip_sixteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>.wip_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>.wip_seventeen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>.wip_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>.wip_eightteen_<?php echo $vendors_lists['id'] ?>);
               }  
               if(typeof(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>.wip_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>.wip_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twenty_<?php echo $vendors_lists['id'] ?>.wip_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twenty_<?php echo $vendors_lists['id'] ?>.wip_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>.wip_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>.wip_twentyone_<?php echo $vendors_lists['id'] ?>);
               }
             
               if(typeof(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>.wip_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>.wip_twentytwo_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>.wip_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>.wip_twentythree_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>.wip_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>.wip_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>.wip_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>.wip_twentyfive_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>.wip_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>.wip_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>.wip_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>.wip_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>.wip_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>.wip_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>.wip_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>.wip_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirty_<?php echo $vendors_lists['id'] ?>.wip_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirty_<?php echo $vendors_lists['id'] ?>.wip_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>.wip_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>.wip_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }

               
               if(typeof(type.closed_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_one_<?php echo $vendors_lists['id'] ?>.closed_one_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_one_<?php echo $vendors_lists['id'] ?>.closed_one_<?php echo $vendors_lists['id'] ?>);

               }
               if(typeof(type.closed_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_two_<?php echo $vendors_lists['id'] ?>.closed_two_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_two_<?php echo $vendors_lists['id'] ?>.closed_two_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_three_<?php echo $vendors_lists['id'] ?>.closed_three_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_three_<?php echo $vendors_lists['id'] ?>.closed_three_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_four_<?php echo $vendors_lists['id'] ?>.closed_four_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_four_<?php echo $vendors_lists['id'] ?>.closed_four_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_five_<?php echo $vendors_lists['id'] ?>.closed_five_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_five_<?php echo $vendors_lists['id'] ?>.closed_five_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.closed_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_six_<?php echo $vendors_lists['id'] ?>.closed_six_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_six_<?php echo $vendors_lists['id'] ?>.closed_six_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_seven_<?php echo $vendors_lists['id'] ?>.closed_seven_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_seven_<?php echo $vendors_lists['id'] ?>.closed_seven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eight_<?php echo $vendors_lists['id'] ?>.closed_eight_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_eight_<?php echo $vendors_lists['id'] ?>.closed_eight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_nine_<?php echo $vendors_lists['id'] ?>.closed_nine_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_nine_<?php echo $vendors_lists['id'] ?>.closed_nine_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.closed_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_ten_<?php echo $vendors_lists['id'] ?>.closed_ten_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_ten_<?php echo $vendors_lists['id'] ?>.closed_ten_<?php echo $vendors_lists['id'] ?>);

               } 
                             
            
               if(typeof(type.closed_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eleven_<?php echo $vendors_lists['id'] ?>.closed_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_eleven_<?php echo $vendors_lists['id'] ?>.closed_eleven_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.closed_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twelve_<?php echo $vendors_lists['id'] ?>.closed_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twelve_<?php echo $vendors_lists['id'] ?>.closed_twelve_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>.closed_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>.closed_thirteen_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>.closed_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>.closed_fourteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>.closed_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>.closed_fifteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>.closed_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>.closed_sixteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>.closed_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>.closed_seventeen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>.closed_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>.closed_eightteen_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>.closed_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>.closed_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twenty_<?php echo $vendors_lists['id'] ?>.closed_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twenty_<?php echo $vendors_lists['id'] ?>.closed_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>.closed_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>.closed_twentyone_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>.closed_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>.closed_twentytwo_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>.closed_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>.closed_twentythree_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>.closed_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>.closed_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>.closed_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>.closed_twentyfive_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>.closed_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>.closed_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>.closed_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>.closed_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>.closed_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>.closed_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>.closed_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>.closed_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirty_<?php echo $vendors_lists['id'] ?>.closed_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirty_<?php echo $vendors_lists['id'] ?>.closed_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>.closed_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>.closed_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }
                              
               if(typeof(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>.insufficiency_one_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>.insufficiency_one_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>.insufficiency_two_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>.insufficiency_two_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>.insufficiency_three_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>.insufficiency_three_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>.insufficiency_four_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>.insufficiency_four_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>.insufficiency_five_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>.insufficiency_five_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>.insufficiency_six_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>.insufficiency_six_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>.insufficiency_seven_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>.insufficiency_seven_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>.insufficiency_eight_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>.insufficiency_eight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>.insufficiency_nine_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>.insufficiency_nine_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>.insufficiency_ten_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>.insufficiency_ten_<?php echo $vendors_lists['id'] ?>);

               } 
                             
            
               if(typeof(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.currentwip_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#currentwip_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.currentwip_<?php echo $vendors_lists['id'] ?>.currentwip_<?php echo $vendors_lists['id'] ?>));
                  
               }
               if(typeof(type.currentinsuff_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#currentinsuff_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.currentinsuff_<?php echo $vendors_lists['id'] ?>.currentinsuff_<?php echo $vendors_lists['id'] ?>));
                  
               }

                $("#initiated_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_initiated);   

                $("#wip_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_wip);   
            
                $("#insufficiency_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_insuff);  

                $("#closed_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_closed);   

                if(total_wip == "0" && total_insuff == "0" && total_closed == "0" && total_initiated == "0")
                { 
                
                  $(".show_hide_<?php echo $vendors_lists['id'] ?>").hide();

                }
                else
                {  
                  $(".show_hide_<?php echo $vendors_lists['id'] ?>").show(); 
                } 

              
            <?php      
            }               
            ?>
        
            
         }  
            
      }
   });

   

    $('#searchrecords_vendor_allocation').on('click', function() {
      
      var month = $('#vendor_month').val();
      var year =  $('#vendor_year').val();


      $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/vendor_allocation_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($vendors_list as $key  => $vendors_lists)
            { 
            ?> 
                $("#initiated_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#initiated_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
            
                $("#wip_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#wip_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
                $("#currentwip_<?php echo $vendors_lists['id'] ?>").html('');


                $("#closed_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#closed_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');

                $("#insufficiency_one_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_two_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_three_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fourth_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_five_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_six_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_seven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_nine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_ten_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eleven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twelve_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twenty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirty_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>").html('');
                $("#insufficiency_total_vendor_<?php echo $vendors_lists['id'] ?>").html('');
                $("#currentinsuff_<?php echo $vendors_lists['id'] ?>").html('');


             
            <?php      
            }
            ?>
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;

        
        
            <?php
            foreach ($vendors_list as $key  => $vendors_lists)
            { 
            ?>
               var total_initiated = 0;
               var total_wip = 0;
               var total_insuff = 0;
               var total_closed = 0;


               if(typeof(type.initiated_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_one_<?php echo $vendors_lists['id'] ?>.initiated_one_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_one_<?php echo $vendors_lists['id'] ?>.initiated_one_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.initiated_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_two_<?php echo $vendors_lists['id'] ?>.initiated_two_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_two_<?php echo $vendors_lists['id'] ?>.initiated_two_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_three_<?php echo $vendors_lists['id'] ?>.initiated_three_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_three_<?php echo $vendors_lists['id'] ?>.initiated_three_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_four_<?php echo $vendors_lists['id'] ?>.initiated_four_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_four_<?php echo $vendors_lists['id'] ?>.initiated_four_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_five_<?php echo $vendors_lists['id'] ?>.initiated_five_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_five_<?php echo $vendors_lists['id'] ?>.initiated_five_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_six_<?php echo $vendors_lists['id'] ?>.initiated_six_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_six_<?php echo $vendors_lists['id'] ?>.initiated_six_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_seven_<?php echo $vendors_lists['id'] ?>.initiated_seven_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_seven_<?php echo $vendors_lists['id'] ?>.initiated_seven_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eight_<?php echo $vendors_lists['id'] ?>.initiated_eight_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_eight_<?php echo $vendors_lists['id'] ?>.initiated_eight_<?php echo $vendors_lists['id'] ?>);  
               }  
               if(typeof(type.initiated_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_nine_<?php echo $vendors_lists['id'] ?>.initiated_nine_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_nine_<?php echo $vendors_lists['id'] ?>.initiated_nine_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.initiated_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_ten_<?php echo $vendors_lists['id'] ?>.initiated_ten_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_ten_<?php echo $vendors_lists['id'] ?>.initiated_ten_<?php echo $vendors_lists['id'] ?>);  
               } 
                             
            
               if(typeof(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>.initiated_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_eleven_<?php echo $vendors_lists['id'] ?>.initiated_eleven_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>.initiated_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twelve_<?php echo $vendors_lists['id'] ?>.initiated_twelve_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>.initiated_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirteen_<?php echo $vendors_lists['id'] ?>.initiated_thirteen_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>.initiated_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_fourteen_<?php echo $vendors_lists['id'] ?>.initiated_fourteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>.initiated_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_fifteen_<?php echo $vendors_lists['id'] ?>.initiated_fifteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>.initiated_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_sixteen_<?php echo $vendors_lists['id'] ?>.initiated_sixteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>.initiated_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_seventeen_<?php echo $vendors_lists['id'] ?>.initiated_seventeen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>.initiated_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_eightteen_<?php echo $vendors_lists['id'] ?>.initiated_eightteen_<?php echo $vendors_lists['id'] ?>);
               }  
               if(typeof(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>.initiated_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_nineteen_<?php echo $vendors_lists['id'] ?>.initiated_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>.initiated_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twenty_<?php echo $vendors_lists['id'] ?>.initiated_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#initiated_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>.initiated_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_initiated += parseInt(type.initiated_twentyone_<?php echo $vendors_lists['id'] ?>.initiated_twentyone_<?php echo $vendors_lists['id'] ?>);
               }
             
               if(typeof(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>.initiated_twentytwo_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>.initiated_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentythree_<?php echo $vendors_lists['id'] ?>.initiated_twentythree_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>.initiated_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>.initiated_twentyfive_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>.initiated_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentysix_<?php echo $vendors_lists['id'] ?>.initiated_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>.initiated_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>.initiated_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>.initiated_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_twentynine_<?php echo $vendors_lists['id'] ?>.initiated_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>.initiated_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirty_<?php echo $vendors_lists['id'] ?>.initiated_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#initiated_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_initiated += parseInt(type.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>.initiated_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }

               if(typeof(type.wip_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_one_<?php echo $vendors_lists['id'] ?>.wip_one_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_one_<?php echo $vendors_lists['id'] ?>.wip_one_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.wip_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_two_<?php echo $vendors_lists['id'] ?>.wip_two_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_two_<?php echo $vendors_lists['id'] ?>.wip_two_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_three_<?php echo $vendors_lists['id'] ?>.wip_three_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_three_<?php echo $vendors_lists['id'] ?>.wip_three_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_four_<?php echo $vendors_lists['id'] ?>.wip_four_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_four_<?php echo $vendors_lists['id'] ?>.wip_four_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_five_<?php echo $vendors_lists['id'] ?>.wip_five_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_five_<?php echo $vendors_lists['id'] ?>.wip_five_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_six_<?php echo $vendors_lists['id'] ?>.wip_six_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_six_<?php echo $vendors_lists['id'] ?>.wip_six_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_seven_<?php echo $vendors_lists['id'] ?>.wip_seven_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_seven_<?php echo $vendors_lists['id'] ?>.wip_seven_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eight_<?php echo $vendors_lists['id'] ?>.wip_eight_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_eight_<?php echo $vendors_lists['id'] ?>.wip_eight_<?php echo $vendors_lists['id'] ?>);  
               }  
               if(typeof(type.wip_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_nine_<?php echo $vendors_lists['id'] ?>.wip_nine_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_nine_<?php echo $vendors_lists['id'] ?>.wip_nine_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.wip_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_ten_<?php echo $vendors_lists['id'] ?>.wip_ten_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_ten_<?php echo $vendors_lists['id'] ?>.wip_ten_<?php echo $vendors_lists['id'] ?>);  
               } 
                             
            
               if(typeof(type.wip_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eleven_<?php echo $vendors_lists['id'] ?>.wip_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_eleven_<?php echo $vendors_lists['id'] ?>.wip_eleven_<?php echo $vendors_lists['id'] ?>);  
               }
             
               if(typeof(type.wip_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twelve_<?php echo $vendors_lists['id'] ?>.wip_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twelve_<?php echo $vendors_lists['id'] ?>.wip_twelve_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>.wip_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirteen_<?php echo $vendors_lists['id'] ?>.wip_thirteen_<?php echo $vendors_lists['id'] ?>);  
               } 
                if(typeof(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>.wip_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_fourteen_<?php echo $vendors_lists['id'] ?>.wip_fourteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>.wip_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_fifteen_<?php echo $vendors_lists['id'] ?>.wip_fifteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>.wip_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_sixteen_<?php echo $vendors_lists['id'] ?>.wip_sixteen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>.wip_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_seventeen_<?php echo $vendors_lists['id'] ?>.wip_seventeen_<?php echo $vendors_lists['id'] ?>);  
               } 
               if(typeof(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>.wip_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_eightteen_<?php echo $vendors_lists['id'] ?>.wip_eightteen_<?php echo $vendors_lists['id'] ?>);
               }  
               if(typeof(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>.wip_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_nineteen_<?php echo $vendors_lists['id'] ?>.wip_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twenty_<?php echo $vendors_lists['id'] ?>.wip_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twenty_<?php echo $vendors_lists['id'] ?>.wip_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#wip_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>.wip_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_wip += parseInt(type.wip_twentyone_<?php echo $vendors_lists['id'] ?>.wip_twentyone_<?php echo $vendors_lists['id'] ?>);
               }
             
               if(typeof(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>.wip_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentytwo_<?php echo $vendors_lists['id'] ?>.wip_twentytwo_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>.wip_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentythree_<?php echo $vendors_lists['id'] ?>.wip_twentythree_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>.wip_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyfour_<?php echo $vendors_lists['id'] ?>.wip_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>.wip_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyfive_<?php echo $vendors_lists['id'] ?>.wip_twentyfive_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>.wip_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentysix_<?php echo $vendors_lists['id'] ?>.wip_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>.wip_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyseven_<?php echo $vendors_lists['id'] ?>.wip_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>.wip_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentyeight_<?php echo $vendors_lists['id'] ?>.wip_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>.wip_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_twentynine_<?php echo $vendors_lists['id'] ?>.wip_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.wip_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirty_<?php echo $vendors_lists['id'] ?>.wip_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirty_<?php echo $vendors_lists['id'] ?>.wip_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#wip_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>.wip_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_wip += parseInt(type.wip_thirtyone_<?php echo $vendors_lists['id'] ?>.wip_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }

               
               if(typeof(type.closed_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_one_<?php echo $vendors_lists['id'] ?>.closed_one_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_one_<?php echo $vendors_lists['id'] ?>.closed_one_<?php echo $vendors_lists['id'] ?>);

               }
               if(typeof(type.closed_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_two_<?php echo $vendors_lists['id'] ?>.closed_two_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_two_<?php echo $vendors_lists['id'] ?>.closed_two_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_three_<?php echo $vendors_lists['id'] ?>.closed_three_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_three_<?php echo $vendors_lists['id'] ?>.closed_three_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_four_<?php echo $vendors_lists['id'] ?>.closed_four_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_four_<?php echo $vendors_lists['id'] ?>.closed_four_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_five_<?php echo $vendors_lists['id'] ?>.closed_five_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_five_<?php echo $vendors_lists['id'] ?>.closed_five_<?php echo $vendors_lists['id'] ?>);
               } 
               if(typeof(type.closed_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_six_<?php echo $vendors_lists['id'] ?>.closed_six_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_six_<?php echo $vendors_lists['id'] ?>.closed_six_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_seven_<?php echo $vendors_lists['id'] ?>.closed_seven_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_seven_<?php echo $vendors_lists['id'] ?>.closed_seven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eight_<?php echo $vendors_lists['id'] ?>.closed_eight_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_eight_<?php echo $vendors_lists['id'] ?>.closed_eight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_nine_<?php echo $vendors_lists['id'] ?>.closed_nine_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_nine_<?php echo $vendors_lists['id'] ?>.closed_nine_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.closed_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_ten_<?php echo $vendors_lists['id'] ?>.closed_ten_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_ten_<?php echo $vendors_lists['id'] ?>.closed_ten_<?php echo $vendors_lists['id'] ?>);

               } 
                             
            
               if(typeof(type.closed_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eleven_<?php echo $vendors_lists['id'] ?>.closed_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_eleven_<?php echo $vendors_lists['id'] ?>.closed_eleven_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.closed_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twelve_<?php echo $vendors_lists['id'] ?>.closed_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twelve_<?php echo $vendors_lists['id'] ?>.closed_twelve_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>.closed_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirteen_<?php echo $vendors_lists['id'] ?>.closed_thirteen_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>.closed_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_fourteen_<?php echo $vendors_lists['id'] ?>.closed_fourteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>.closed_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_fifteen_<?php echo $vendors_lists['id'] ?>.closed_fifteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>.closed_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_sixteen_<?php echo $vendors_lists['id'] ?>.closed_sixteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>.closed_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_seventeen_<?php echo $vendors_lists['id'] ?>.closed_seventeen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>.closed_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_eightteen_<?php echo $vendors_lists['id'] ?>.closed_eightteen_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>.closed_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_nineteen_<?php echo $vendors_lists['id'] ?>.closed_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twenty_<?php echo $vendors_lists['id'] ?>.closed_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twenty_<?php echo $vendors_lists['id'] ?>.closed_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#closed_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>.closed_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_closed += parseInt(type.closed_twentyone_<?php echo $vendors_lists['id'] ?>.closed_twentyone_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>.closed_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentytwo_<?php echo $vendors_lists['id'] ?>.closed_twentytwo_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>.closed_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentythree_<?php echo $vendors_lists['id'] ?>.closed_twentythree_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>.closed_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyfour_<?php echo $vendors_lists['id'] ?>.closed_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>.closed_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyfive_<?php echo $vendors_lists['id'] ?>.closed_twentyfive_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>.closed_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentysix_<?php echo $vendors_lists['id'] ?>.closed_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>.closed_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyseven_<?php echo $vendors_lists['id'] ?>.closed_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>.closed_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentyeight_<?php echo $vendors_lists['id'] ?>.closed_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>.closed_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_twentynine_<?php echo $vendors_lists['id'] ?>.closed_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.closed_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirty_<?php echo $vendors_lists['id'] ?>.closed_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirty_<?php echo $vendors_lists['id'] ?>.closed_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#closed_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>.closed_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_closed += parseInt(type.closed_thirtyone_<?php echo $vendors_lists['id'] ?>.closed_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }
                              
               if(typeof(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_one_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>.insufficiency_one_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_one_<?php echo $vendors_lists['id'] ?>.insufficiency_one_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_two_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>.insufficiency_two_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_two_<?php echo $vendors_lists['id'] ?>.insufficiency_two_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_three_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>.insufficiency_three_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_three_<?php echo $vendors_lists['id'] ?>.insufficiency_three_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fourth_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>.insufficiency_four_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_four_<?php echo $vendors_lists['id'] ?>.insufficiency_four_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_five_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>.insufficiency_five_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_five_<?php echo $vendors_lists['id'] ?>.insufficiency_five_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_six_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>.insufficiency_six_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_six_<?php echo $vendors_lists['id'] ?>.insufficiency_six_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_seven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>.insufficiency_seven_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_seven_<?php echo $vendors_lists['id'] ?>.insufficiency_seven_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_eight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>.insufficiency_eight_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_eight_<?php echo $vendors_lists['id'] ?>.insufficiency_eight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_nine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>.insufficiency_nine_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_nine_<?php echo $vendors_lists['id'] ?>.insufficiency_nine_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_ten_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>.insufficiency_ten_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_ten_<?php echo $vendors_lists['id'] ?>.insufficiency_ten_<?php echo $vendors_lists['id'] ?>);

               } 
                             
            
               if(typeof(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_eleven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>.insufficiency_eleven_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twelve_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>.insufficiency_twelve_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>.insufficiency_thirteen_<?php echo $vendors_lists['id'] ?>);

               } 
                if(typeof(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fourteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>.insufficiency_fifteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>.insufficiency_sixteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>.insufficiency_seventeen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>.insufficiency_eightteen_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>.insufficiency_nineteen_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twenty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>.insufficiency_twenty_<?php echo $vendors_lists['id'] ?>);

               } 
              
               if(typeof(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               { 
                  $("#insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>));  
                  total_insuff += parseInt(type.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyone_<?php echo $vendors_lists['id'] ?>);

               }
             
               if(typeof(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>.insufficiency_twentytwo_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>.insufficiency_twentythree_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfour_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyfive_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>.insufficiency_twentysix_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyseven_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>.insufficiency_twentyeight_<?php echo $vendors_lists['id'] ?>);

               }  
               if(typeof(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>.insufficiency_twentynine_<?php echo $vendors_lists['id'] ?>);

               } 
               if(typeof(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirty_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>.insufficiency_thirty_<?php echo $vendors_lists['id'] ?>);

               }
               
               if(typeof(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>));
                  total_insuff += parseInt(type.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>.insufficiency_thirtyone_<?php echo $vendors_lists['id'] ?>);

               }

                 
               if(typeof(type.currentwip_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#currentwip_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.currentwip_<?php echo $vendors_lists['id'] ?>.currentwip_<?php echo $vendors_lists['id'] ?>));
                  
               }
               if(typeof(type.currentinsuff_<?php echo $vendors_lists['id'] ?>) != "undefined")
               {
                  $("#currentinsuff_<?php echo $vendors_lists['id'] ?>").html(parseInt(type.currentinsuff_<?php echo $vendors_lists['id'] ?>.currentinsuff_<?php echo $vendors_lists['id'] ?>));
                  
               }


               $("#initiated_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_initiated);   

                $("#wip_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_wip);   
            
                $("#insufficiency_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_insuff);  

                $("#closed_total_vendor_<?php echo $vendors_lists['id'] ?>").html(total_closed);   

                if(total_wip == "0" && total_insuff == "0" && total_closed == "0"  && total_initiated == "0")
                { 
                
                  $(".show_hide_<?php echo $vendors_lists['id'] ?>").hide();

                }
                else
                {  
                  $(".show_hide_<?php echo $vendors_lists['id'] ?>").show(); 
                } 

              
            <?php      
            }               
            ?>
        
            
         }  
            
        }
      });

    });  

});

</script>