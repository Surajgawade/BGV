<?php   if($this->user_info['tbl_roles_id'] == '1') {  ?>
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
<div class="content-page">
  <div class="content">
    <div class="container-fluid"> 

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
          
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-4 form-group">
                  <?php
                  $month = array('0' => 'Select Month','1' => 'January','2' => 'Feb','3' => 'March','4' => 'April','5' => 'May','6' => 'June','7' => 'July','8' => 'August','9' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
                    echo form_dropdown('month',$month, set_value('month',date('m')),'class="select2" id="month"');?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php
                  $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
                   echo form_dropdown('year', $year, set_value('year',date('Y')), 'class="select2" id="year"');
                   ?>
                </div> 
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_client" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th>SrID</th>
                    <th>Client Name</th>
                    <th>Spoc Name</th>
                    <th>Total</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th>
                    <th>22</th>
                    <th>23</th>
                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>
                    <th>30</th>
                    <th>31</th>
                  </tr>
                </thead>
                <?php

                  $i = 1;
                  unset($clients[0]);
                  foreach ($clients as $client)
                    {
                      echo  "<tr id = show_hide_".$client['id'].">";
                      echo  "<td>".$i."</td>";
                      echo  "<td>".$client['clientname']."</td>";
                      echo  "<td>".$client['spocname']."</td>";
                      echo  "<td><div id = 'total_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'first_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'second_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'three_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'four_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'five_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'six_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'seven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eight_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'nine_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'ten_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eleven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twelve_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'fourteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'fiftteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'sixteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'seventeen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eightteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'nineteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twenty_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyone_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentytwo_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentythree_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyfour_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyfive_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentysix_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyseven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyeight_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentynine_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirty_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirtyone_".$client['id']."'></div></td>";
                      echo "</tr>";

                      $i++;
                  }
                    echo  "<tr id = 'show_hide_total'>";
                    echo  "<td colspan = '3' style='text-align:center'>Total</td>"; 
                    echo  "<td><div id = 'total_total'></div></td>"; 
                    echo  "<td><div id = 'first_total'></div></td>";
                    echo  "<td><div id = 'second_total'></div></td>";
                    echo  "<td><div id = 'three_total'></div></td>";
                    echo  "<td><div id = 'four_total'></div></td>";
                    echo  "<td><div id = 'five_total'></div></td>";
                    echo  "<td><div id = 'six_total'></div></td>";
                    echo  "<td><div id = 'seven_total'></div></td>";
                    echo  "<td><div id = 'eight_total'></div></td>";
                    echo  "<td><div id = 'nine_total'></div></td>";
                    echo  "<td><div id = 'ten_total'></div></td>";
                    echo  "<td><div id = 'eleven_total'></div></td>";
                    echo  "<td><div id = 'twelve_total'></div></td>";
                    echo  "<td><div id = 'thirteen_total'></div></td>";
                    echo  "<td><div id = 'fourteen_total'></div></td>";
                    echo  "<td><div id = 'fiftteen_total'></div></td>";
                    echo  "<td><div id = 'sixteen_total'></div></td>";
                    echo  "<td><div id = 'seventeen_total'></div></td>";
                    echo  "<td><div id = 'eightteen_total'></div></td>";
                    echo  "<td><div id = 'nineteen_total'></div></td>";
                    echo  "<td><div id = 'twenty_total'></div></td>";
                    echo  "<td><div id = 'twentyone_total'></div></td>";
                    echo  "<td><div id = 'twentytwo_total'></div></td>";
                    echo  "<td><div id = 'twentythree_total'></div></td>";
                    echo  "<td><div id = 'twentyfour_total'></div></td>";
                    echo  "<td><div id = 'twentyfive_total'></div></td>";
                    echo  "<td><div id = 'twentysix_total'></div></td>";
                    echo  "<td><div id = 'twentyseven_total'></div></td>";
                    echo  "<td><div id = 'twentyeight_total'></div></td>";
                    echo  "<td><div id = 'twentynine_total'></div></td>";
                    echo  "<td><div id = 'thirty_total'></div></td>";
                    echo  "<td><div id = 'thirtyone_total'></div></td>";
                    echo "</tr>";        
                ?>
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


<script>  
var $ = jQuery;
$(document).ready(function() {
  

   var month = $('#month').val();
   var year =  $('#year').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Dashboard/client_details"; ?>', 
      data : 'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
              foreach ($clients as $client)
              { ?>
            
                $("#total_<?php echo $client['id'] ?>").html('');
                $("#first_<?php echo $client['id'] ?>").html('');
                $("#second_<?php echo $client['id'] ?>").html('');
                $("#three_<?php echo $client['id'] ?>").html('');
                $("#four_<?php echo $client['id'] ?>").html('');
                $("#five_<?php echo $client['id'] ?>").html('');
                $("#six_<?php echo $client['id'] ?>").html('');
                $("#seven_<?php echo $client['id'] ?>").html('');
                $("#eight_<?php echo $client['id'] ?>").html('');
                $("#nine_<?php echo $client['id'] ?>").html('');
                $("#ten_<?php echo $client['id'] ?>").html('');
                $("#eleven_<?php echo $client['id'] ?>").html('');  
                $("#twelve_<?php echo $client['id'] ?>").html('');
                $("#thirteen_<?php echo $client['id'] ?>").html('');
                $("#fourteen_<?php echo $client['id'] ?>").html('');  
                $("#fiftteen_<?php echo $client['id'] ?>").html('');  
                $("#sixteen_<?php echo $client['id'] ?>").html('');
                $("#seventeen_<?php echo $client['id'] ?>").html('');    
                $("#eightteen_<?php echo $client['id'] ?>").html('');    
                $("#nineteen_<?php echo $client['id'] ?>").html('');     
                $("#twenty_<?php echo $client['id'] ?>").html('');    
                $("#twentyone_<?php echo $client['id'] ?>").html(''); 
                $("#twentytwo_<?php echo $client['id'] ?>").html('');    
                $("#twentythree_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfive_<?php echo $client['id'] ?>").html('');
                $("#twentysix_<?php echo $client['id'] ?>").html(''); 
                $("#twentyseven_<?php echo $client['id'] ?>").html('');       
                $("#twentyeight_<?php echo $client['id'] ?>").html('');  
                $("#twentynine_<?php echo $client['id'] ?>").html(''); 
                $("#thirty_<?php echo $client['id'] ?>").html('');  
                $("#thirtyone_<?php echo $client['id'] ?>").html('');                 
                
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
            var total_total =  0;
            var first_total =  0; 
            var second_total =  0;
            var three_total =  0;
            var four_total =  0;
            var five_total =  0;
            var six_total =  0;
            var seven_total =  0;
            var eight_total =  0;
            var nine_total =  0;
            var ten_total =  0;
            var eleven_total =  0;
            var twelve_total =  0;
            var thirteen_total =  0;
            var fourteen_total =  0;
            var fiftteen_total =  0;
            var sixteen_total =  0;
            var seventeen_total =  0;
            var eightteen_total =  0;
            var nineteen_total =  0;
            var twenty_total =  0;
            var twentyone_total =  0;
            var twentytwo_total =  0;
            var twentythree_total =  0;
            var twentyfour_total =  0;
            var twentyfive_total =  0;
            var twentysix_total =  0;
            var twentyseven_total =  0;
            var twentyeight_total =  0;
            var twentynine_total =  0;
            var thirty_total =  0;
            var thirtyone_total =  0;
             
            <?php
              foreach ($clients as $client)
              { ?>

                var total_<?php echo $client['id'] ?> = 0;

                if(typeof(type.one_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.one_<?php echo $client['id'] ?>);
                   first_total += parseInt(type.one_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.two_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.two_<?php echo $client['id'] ?>);
                   second_total += parseInt(type.two_<?php echo $client['id'] ?>);
                }
                if(typeof(type.three_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.three_<?php echo $client['id'] ?>);
                    three_total += parseInt(type.three_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.four_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.four_<?php echo $client['id'] ?>);
                   four_total += parseInt(type.four_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.five_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.five_<?php echo $client['id'] ?>);
                   five_total += parseInt(type.five_<?php echo $client['id'] ?>);
                }
                if(typeof(type.six_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.six_<?php echo $client['id'] ?>);
                   six_total += parseInt(type.six_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seven_<?php echo $client['id'] ?>);
                   seven_total += parseInt(type.seven_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.eight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eight_<?php echo $client['id'] ?>);
                   eight_total += parseInt(type.eight_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.nine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nine_<?php echo $client['id'] ?>);
                   nine_total += parseInt(type.nine_<?php echo $client['id'] ?>);
                }
                if(typeof(type.ten_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.ten_<?php echo $client['id'] ?>);
                   ten_total += parseInt(type.ten_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eleven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eleven_<?php echo $client['id'] ?>);
                   eleven_total += parseInt(type.eleven_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twelve_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twelve_<?php echo $client['id'] ?>);
                   twelve_total += parseInt(type.twelve_<?php echo $client['id'] ?>);
                }
                if(typeof(type.thirteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirteen_<?php echo $client['id'] ?>);
                   thirteen_total += parseInt(type.thirteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fourteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fourteen_<?php echo $client['id'] ?>);
                   fourteen_total += parseInt(type.fourteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fifteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fifteen_<?php echo $client['id'] ?>);
                   fiftteen_total += parseInt(type.fifteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.sixteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.sixteen_<?php echo $client['id'] ?>);
                   sixteen_total += parseInt(type.sixteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seventeen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seventeen_<?php echo $client['id'] ?>);
                   seventeen_total += parseInt(type.seventeen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eightteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eightteen_<?php echo $client['id'] ?>);
                    eightteen_total += parseInt(type.eightteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.nineteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nineteen_<?php echo $client['id'] ?>);
                   nineteen_total += parseInt(type.nineteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twenty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twenty_<?php echo $client['id'] ?>);
                   twenty_total += parseInt(type.twenty_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyone_<?php echo $client['id'] ?>);
                   twentyone_total += parseInt(type.twentyone_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentytwo_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                   twentytwo_total += parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentythree_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentythree_<?php echo $client['id'] ?>);
                    twentythree_total += parseInt(type.twentythree_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyfour_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                   twentyfour_total += parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                }  
                if(typeof(type.twentyfive_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                   twentyfive_total += parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentysix_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentysix_<?php echo $client['id'] ?>);
                   twentysix_total += parseInt(type.twentysix_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentyseven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyseven_<?php echo $client['id'] ?>);
                   twentyseven_total += parseInt(type.twentyseven_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twentyeight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyeight_<?php echo $client['id'] ?>);
                  twentyeight_total += parseInt(type.twentyeight_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.twentynine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentynine_<?php echo $client['id'] ?>);
                   twentynine_total += parseInt(type.twentynine_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.thirty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirty_<?php echo $client['id'] ?>);
                  thirty_total += parseInt(type.thirty_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.thirtyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                    thirtyone_total += parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                }   
               
                if(total_<?php echo $client['id'] ?> == "0")
                {
                   $("#show_hide_<?php echo $client['id'] ?>").hide();
                }
                else
                {
                   $("#show_hide_<?php echo $client['id'] ?>").show(); 
                }
            
                $("#total_<?php echo $client['id'] ?>").html(total_<?php echo $client['id'] ?>);
                $("#first_<?php echo $client['id'] ?>").html(type.one_<?php echo $client['id'] ?>);
                $("#second_<?php echo $client['id'] ?>").html(type.two_<?php echo $client['id'] ?>);
                $("#three_<?php echo $client['id'] ?>").html(type.three_<?php echo $client['id'] ?>);
                $("#four_<?php echo $client['id'] ?>").html(type.four_<?php echo $client['id'] ?>);
                $("#five_<?php echo $client['id'] ?>").html(type.five_<?php echo $client['id'] ?>);
                $("#six_<?php echo $client['id'] ?>").html(type.six_<?php echo $client['id'] ?>);
                $("#seven_<?php echo $client['id'] ?>").html(type.seven_<?php echo $client['id'] ?>);
                $("#eight_<?php echo $client['id'] ?>").html(type.eight_<?php echo $client['id'] ?>);
                $("#nine_<?php echo $client['id'] ?>").html(type.nine_<?php echo $client['id'] ?>);
                $("#ten_<?php echo $client['id'] ?>").html(type.ten_<?php echo $client['id'] ?>);
                $("#eleven_<?php echo $client['id'] ?>").html(type.eleven_<?php echo $client['id'] ?>);  
                $("#twelve_<?php echo $client['id'] ?>").html(type.twelve_<?php echo $client['id'] ?>);
                $("#thirteen_<?php echo $client['id'] ?>").html(type.thirteen_<?php echo $client['id'] ?>);
                $("#fourteen_<?php echo $client['id'] ?>").html(type.fourteen_<?php echo $client['id'] ?>);  
                $("#fiftteen_<?php echo $client['id'] ?>").html(type.fifteen_<?php echo $client['id'] ?>);  
                $("#sixteen_<?php echo $client['id'] ?>").html(type.sixteen_<?php echo $client['id'] ?>);
                $("#seventeen_<?php echo $client['id'] ?>").html(type.seventeen_<?php echo $client['id'] ?>);    
                $("#eightteen_<?php echo $client['id'] ?>").html(type.eightteen_<?php echo $client['id'] ?>);    
                $("#nineteen_<?php echo $client['id'] ?>").html(type.nineteen_<?php echo $client['id'] ?>);     
                $("#twenty_<?php echo $client['id'] ?>").html(type.twenty_<?php echo $client['id'] ?>);    
                $("#twentyone_<?php echo $client['id'] ?>").html(type.twentyone_<?php echo $client['id'] ?>); 
                $("#twentytwo_<?php echo $client['id'] ?>").html(type.twentytwo_<?php echo $client['id'] ?>);    
                $("#twentythree_<?php echo $client['id'] ?>").html(type.twentythree_<?php echo $client['id'] ?>); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(type.twentyfour_<?php echo $client['id'] ?>); 
                $("#twentyfive_<?php echo $client['id'] ?>").html(type.twentyfive_<?php echo $client['id'] ?>);
                $("#twentysix_<?php echo $client['id'] ?>").html(type.twentysix_<?php echo $client['id'] ?>); 
                $("#twentyseven_<?php echo $client['id'] ?>").html(type.twentyseven_<?php echo $client['id'] ?>);       
                $("#twentyeight_<?php echo $client['id'] ?>").html(type.twentyeight_<?php echo $client['id'] ?>);  
                $("#twentynine_<?php echo $client['id'] ?>").html(type.twentynine_<?php echo $client['id'] ?>); 
                $("#thirty_<?php echo $client['id'] ?>").html(type.thirty_<?php echo $client['id'] ?>);  
                $("#thirtyone_<?php echo $client['id'] ?>").html(type.thirtyone_<?php echo $client['id'] ?>);                 
                
                total_total += parseInt(total_<?php echo $client['id'] ?>);
                
                
              <?php      
              }
                            
            ?>
          
            $("#total_total").html(total_total);
            $("#first_total").html(first_total);
            $("#second_total").html(second_total);
            $("#three_total").html(three_total);
            $("#four_total").html(four_total);
            $("#five_total").html(five_total);
            $("#six_total").html(six_total);
            $("#seven_total").html(seven_total);
            $("#eight_total").html(eight_total);
            $("#nine_total").html(nine_total);
            $("#ten_total").html(ten_total);
            $("#eleven_total").html(eleven_total);
            $("#twelve_total").html(twelve_total);
            $("#thirteen_total").html(thirteen_total);
            $("#fourteen_total").html(fourteen_total);
            $("#fiftteen_total").html(fiftteen_total);
            $("#sixteen_total").html(sixteen_total);
            $("#seventeen_total").html(seventeen_total);
            $("#eightteen_total").html(eightteen_total);
            $("#nineteen_total").html(nineteen_total);
            $("#twenty_total").html(twenty_total);
            $("#twentyone_total").html(twentyone_total);
            $("#twentytwo_total").html(twentytwo_total);
            $("#twentythree_total").html(twentythree_total);
            $("#twentyfour_total").html(twentyfour_total);
            $("#twentyfive_total").html(twentyfive_total);
            $("#twentysix_total").html(twentysix_total);
            $("#twentyseven_total").html(twentyseven_total);
            $("#twentyeight_total").html(twentyeight_total);
            $("#twentynine_total").html(twentynine_total);
            $("#thirty_total").html(thirty_total);
            $("#thirtyone_total").html(thirtyone_total);
            $("#show_hide_total").show(); 
            
            return true;
        }
        else
        {
              <?php
              foreach ($clients as $client)
              { 
              ?>
              $("#show_hide_<?php echo $client['id'] ?>").hide();
              $("#show_hide_total").hide();    
                            
              <?php      
              }              
              ?>
          return false;
        }
      }
    });
$('#month,#year').on('change', function(){  
   var month = $('#month').val();
   var year =  $('#year').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Dashboard/client_details"; ?>', 
      data : 'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
              foreach ($clients as $client)
              { ?>
            
                $("#total_<?php echo $client['id'] ?>").html('');
                $("#first_<?php echo $client['id'] ?>").html('');
                $("#second_<?php echo $client['id'] ?>").html('');
                $("#three_<?php echo $client['id'] ?>").html('');
                $("#four_<?php echo $client['id'] ?>").html('');
                $("#five_<?php echo $client['id'] ?>").html('');
                $("#six_<?php echo $client['id'] ?>").html('');
                $("#seven_<?php echo $client['id'] ?>").html('');
                $("#eight_<?php echo $client['id'] ?>").html('');
                $("#nine_<?php echo $client['id'] ?>").html('');
                $("#ten_<?php echo $client['id'] ?>").html('');
                $("#eleven_<?php echo $client['id'] ?>").html('');  
                $("#twelve_<?php echo $client['id'] ?>").html('');
                $("#thirteen_<?php echo $client['id'] ?>").html('');
                $("#fourteen_<?php echo $client['id'] ?>").html('');  
                $("#fiftteen_<?php echo $client['id'] ?>").html('');  
                $("#sixteen_<?php echo $client['id'] ?>").html('');
                $("#seventeen_<?php echo $client['id'] ?>").html('');    
                $("#eightteen_<?php echo $client['id'] ?>").html('');    
                $("#nineteen_<?php echo $client['id'] ?>").html('');     
                $("#twenty_<?php echo $client['id'] ?>").html('');    
                $("#twentyone_<?php echo $client['id'] ?>").html(''); 
                $("#twentytwo_<?php echo $client['id'] ?>").html('');    
                $("#twentythree_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfive_<?php echo $client['id'] ?>").html('');
                $("#twentysix_<?php echo $client['id'] ?>").html(''); 
                $("#twentyseven_<?php echo $client['id'] ?>").html('');       
                $("#twentyeight_<?php echo $client['id'] ?>").html('');  
                $("#twentynine_<?php echo $client['id'] ?>").html(''); 
                $("#thirty_<?php echo $client['id'] ?>").html('');  
                $("#thirtyone_<?php echo $client['id'] ?>").html(''); 

                
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
            var total_total =  0;
            var first_total =  0; 
            var second_total =  0;
            var three_total =  0;
            var four_total =  0;
            var five_total =  0;
            var six_total =  0;
            var seven_total =  0;
            var eight_total =  0;
            var nine_total =  0;
            var ten_total =  0;
            var eleven_total =  0;
            var twelve_total =  0;
            var thirteen_total =  0;
            var fourteen_total =  0;
            var fiftteen_total =  0;
            var sixteen_total =  0;
            var seventeen_total =  0;
            var eightteen_total =  0;
            var nineteen_total =  0;
            var twenty_total =  0;
            var twentyone_total =  0;
            var twentytwo_total =  0;
            var twentythree_total =  0;
            var twentyfour_total =  0;
            var twentyfive_total =  0;
            var twentysix_total =  0;
            var twentyseven_total =  0;
            var twentyeight_total =  0;
            var twentynine_total =  0;
            var thirty_total =  0;
            var thirtyone_total =  0;
             

            <?php
              foreach ($clients as $client)
              { ?>
                
                var total_<?php echo $client['id'] ?> = 0;

                if(typeof(type.one_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.one_<?php echo $client['id'] ?>);
                   first_total += parseInt(type.one_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.two_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.two_<?php echo $client['id'] ?>);
                   second_total += parseInt(type.two_<?php echo $client['id'] ?>);
                }
                if(typeof(type.three_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.three_<?php echo $client['id'] ?>);
                    three_total += parseInt(type.three_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.four_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.four_<?php echo $client['id'] ?>);
                   four_total += parseInt(type.four_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.five_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.five_<?php echo $client['id'] ?>);
                   five_total += parseInt(type.five_<?php echo $client['id'] ?>);
                }
                if(typeof(type.six_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.six_<?php echo $client['id'] ?>);
                   six_total += parseInt(type.six_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seven_<?php echo $client['id'] ?>);
                   seven_total += parseInt(type.seven_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.eight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eight_<?php echo $client['id'] ?>);
                   eight_total += parseInt(type.eight_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.nine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nine_<?php echo $client['id'] ?>);
                   nine_total += parseInt(type.nine_<?php echo $client['id'] ?>);
                }
                if(typeof(type.ten_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.ten_<?php echo $client['id'] ?>);
                   ten_total += parseInt(type.ten_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eleven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eleven_<?php echo $client['id'] ?>);
                   eleven_total += parseInt(type.eleven_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twelve_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twelve_<?php echo $client['id'] ?>);
                   twelve_total += parseInt(type.twelve_<?php echo $client['id'] ?>);
                }
                if(typeof(type.thirteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirteen_<?php echo $client['id'] ?>);
                   thirteen_total += parseInt(type.thirteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fourteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fourteen_<?php echo $client['id'] ?>);
                   fourteen_total += parseInt(type.fourteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fifteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fifteen_<?php echo $client['id'] ?>);
                   fiftteen_total += parseInt(type.fifteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.sixteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.sixteen_<?php echo $client['id'] ?>);
                   sixteen_total += parseInt(type.sixteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seventeen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seventeen_<?php echo $client['id'] ?>);
                   seventeen_total += parseInt(type.seventeen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eightteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eightteen_<?php echo $client['id'] ?>);
                    eightteen_total += parseInt(type.eightteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.nineteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nineteen_<?php echo $client['id'] ?>);
                   nineteen_total += parseInt(type.nineteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twenty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twenty_<?php echo $client['id'] ?>);
                   twenty_total += parseInt(type.twenty_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyone_<?php echo $client['id'] ?>);
                   twentyone_total += parseInt(type.twentyone_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentytwo_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                   twentytwo_total += parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentythree_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentythree_<?php echo $client['id'] ?>);
                    twentythree_total += parseInt(type.twentythree_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyfour_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                   twentyfour_total += parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                }  
                if(typeof(type.twentyfive_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                   twentyfive_total += parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentysix_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentysix_<?php echo $client['id'] ?>);
                   twentysix_total += parseInt(type.twentysix_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentyseven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyseven_<?php echo $client['id'] ?>);
                   twentyseven_total += parseInt(type.twentyseven_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twentyeight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyeight_<?php echo $client['id'] ?>);
                  twentyeight_total += parseInt(type.twentyeight_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.twentynine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentynine_<?php echo $client['id'] ?>);
                   twentynine_total += parseInt(type.twentynine_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.thirty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirty_<?php echo $client['id'] ?>);
                  thirty_total += parseInt(type.thirty_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.thirtyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                    thirtyone_total += parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                }   

                if(total_<?php echo $client['id'] ?> == "0")
                {
                   $("#show_hide_<?php echo $client['id'] ?>").hide();
                } 
                else
                {
                   $("#show_hide_<?php echo $client['id'] ?>").show(); 
                }
             
                $("#total_<?php echo $client['id'] ?>").html(total_<?php echo $client['id'] ?>);
                $("#first_<?php echo $client['id'] ?>").html(type.one_<?php echo $client['id'] ?>);
                $("#second_<?php echo $client['id'] ?>").html(type.two_<?php echo $client['id'] ?>);
                $("#three_<?php echo $client['id'] ?>").html(type.three_<?php echo $client['id'] ?>);
                $("#four_<?php echo $client['id'] ?>").html(type.four_<?php echo $client['id'] ?>);
                $("#five_<?php echo $client['id'] ?>").html(type.five_<?php echo $client['id'] ?>);
                $("#six_<?php echo $client['id'] ?>").html(type.six_<?php echo $client['id'] ?>);
                $("#seven_<?php echo $client['id'] ?>").html(type.seven_<?php echo $client['id'] ?>);
                $("#eight_<?php echo $client['id'] ?>").html(type.eight_<?php echo $client['id'] ?>);
                $("#nine_<?php echo $client['id'] ?>").html(type.nine_<?php echo $client['id'] ?>);
                $("#ten_<?php echo $client['id'] ?>").html(type.ten_<?php echo $client['id'] ?>);
                $("#eleven_<?php echo $client['id'] ?>").html(type.eleven_<?php echo $client['id'] ?>);  
                $("#twelve_<?php echo $client['id'] ?>").html(type.twelve_<?php echo $client['id'] ?>);
                $("#thirteen_<?php echo $client['id'] ?>").html(type.thirteen_<?php echo $client['id'] ?>);
                $("#fourteen_<?php echo $client['id'] ?>").html(type.fourteen_<?php echo $client['id'] ?>);  
                $("#fiftteen_<?php echo $client['id'] ?>").html(type.fifteen_<?php echo $client['id'] ?>);  
                $("#sixteen_<?php echo $client['id'] ?>").html(type.sixteen_<?php echo $client['id'] ?>);
                $("#seventeen_<?php echo $client['id'] ?>").html(type.seventeen_<?php echo $client['id'] ?>);    
                $("#eightteen_<?php echo $client['id'] ?>").html(type.eightteen_<?php echo $client['id'] ?>);    
                $("#nineteen_<?php echo $client['id'] ?>").html(type.nineteen_<?php echo $client['id'] ?>);     
                $("#twenty_<?php echo $client['id'] ?>").html(type.twenty_<?php echo $client['id'] ?>);    
                $("#twentyone_<?php echo $client['id'] ?>").html(type.twentyone_<?php echo $client['id'] ?>); 
                $("#twentytwo_<?php echo $client['id'] ?>").html(type.twentytwo_<?php echo $client['id'] ?>);    
                $("#twentythree_<?php echo $client['id'] ?>").html(type.twentythree_<?php echo $client['id'] ?>); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(type.twentyfour_<?php echo $client['id'] ?>); 
                $("#twentyfive_<?php echo $client['id'] ?>").html(type.twentyfive_<?php echo $client['id'] ?>);
                $("#twentysix_<?php echo $client['id'] ?>").html(type.twentysix_<?php echo $client['id'] ?>); 
                $("#twentyseven_<?php echo $client['id'] ?>").html(type.twentyseven_<?php echo $client['id'] ?>);       
                $("#twentyeight_<?php echo $client['id'] ?>").html(type.twentyeight_<?php echo $client['id'] ?>);  
                $("#twentynine_<?php echo $client['id'] ?>").html(type.twentynine_<?php echo $client['id'] ?>); 
                $("#thirty_<?php echo $client['id'] ?>").html(type.thirty_<?php echo $client['id'] ?>);  
                $("#thirtyone_<?php echo $client['id'] ?>").html(type.thirtyone_<?php echo $client['id'] ?>);  

                  total_total += parseInt(total_<?php echo $client['id'] ?>);               
                
                 <?php      
              }
                            
            ?>
            
            $("#total_total").html(total_total);
            $("#first_total").html(first_total);
            $("#second_total").html(second_total);
            $("#three_total").html(three_total);
            $("#four_total").html(four_total);
            $("#five_total").html(five_total);
            $("#six_total").html(six_total);
            $("#seven_total").html(seven_total);
            $("#eight_total").html(eight_total);
            $("#nine_total").html(nine_total);
            $("#ten_total").html(ten_total);
            $("#eleven_total").html(eleven_total);
            $("#twelve_total").html(twelve_total);
            $("#thirteen_total").html(thirteen_total);
            $("#fourteen_total").html(fourteen_total);
            $("#fiftteen_total").html(fiftteen_total);
            $("#sixteen_total").html(sixteen_total);
            $("#seventeen_total").html(seventeen_total);
            $("#eightteen_total").html(eightteen_total);
            $("#nineteen_total").html(nineteen_total);
            $("#twenty_total").html(twenty_total);
            $("#twentyone_total").html(twentyone_total);
            $("#twentytwo_total").html(twentytwo_total);
            $("#twentythree_total").html(twentythree_total);
            $("#twentyfour_total").html(twentyfour_total);
            $("#twentyfive_total").html(twentyfive_total);
            $("#twentysix_total").html(twentysix_total);
            $("#twentyseven_total").html(twentyseven_total);
            $("#twentyeight_total").html(twentyeight_total);
            $("#twentynine_total").html(twentynine_total);
            $("#thirty_total").html(thirty_total);
            $("#thirtyone_total").html(thirtyone_total);
            $("#show_hide_total").show();

            return true;
        }
        else
        {
              <?php
              foreach ($clients as $client)
              { 
              ?>
              $("#show_hide_<?php echo $client['id'] ?>").hide(); 
              $("#show_hide_total").hide();                   
              <?php      
              }              
              ?>
          return false;
        }
      }
    });
  });

});

</script>

<?php }else{  ?>


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
<div class="content-page">
  <div class="content">
    <div class="container-fluid"> 

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
          
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-4 form-group">
                  <?php
                  $month = array('0' => 'Select Month','1' => 'January','2' => 'Feb','3' => 'March','4' => 'April','5' => 'May','6' => 'June','7' => 'July','8' => 'August','9' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
                    echo form_dropdown('month',$month, set_value('month',date('m')),'class="select2" id="month"');?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php
                  $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
                   echo form_dropdown('year', $year, set_value('year',date('Y')), 'class="select2" id="year"');
                   ?>
                </div> 
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_client" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th>SrID</th>
                    <th>Client Name</th>
                    <th>Spoc Name</th>
                    <th>Total</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th>
                    <th>22</th>
                    <th>23</th>
                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>
                    <th>30</th>
                    <th>31</th>
                  </tr>
                </thead>
                <?php

                  $i = 1;
                 
                  unset($clients[0]);
                  foreach ($clients as $client)
                  {
                     if($client['sales_manager'] == $this->user_info['id'])
                     {
                      echo  "<tr id = show_hide_".$client['id'].">";
                      echo  "<td>".$i."</td>";
                      echo  "<td>".$client['clientname']."</td>";
                      echo  "<td>".$client['spocname']."</td>";
                      echo  "<td><div id = 'total_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'first_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'second_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'three_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'four_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'five_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'six_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'seven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eight_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'nine_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'ten_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eleven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twelve_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'fourteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'fiftteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'sixteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'seventeen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'eightteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'nineteen_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twenty_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyone_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentytwo_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentythree_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyfour_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyfive_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentysix_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyseven_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentyeight_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'twentynine_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirty_".$client['id']."'></div></td>";
                      echo  "<td><div id = 'thirtyone_".$client['id']."'></div></td>";
                      echo "</tr>";
                  
                      $i++;
                     }
                  }
                    echo  "<tr id = 'show_hide_total'>";
                    echo  "<td colspan = '3' style='text-align:center'>Total</td>"; 
                    echo  "<td><div id = 'total_total'></div></td>"; 
                    echo  "<td><div id = 'first_total'></div></td>";
                    echo  "<td><div id = 'second_total'></div></td>";
                    echo  "<td><div id = 'three_total'></div></td>";
                    echo  "<td><div id = 'four_total'></div></td>";
                    echo  "<td><div id = 'five_total'></div></td>";
                    echo  "<td><div id = 'six_total'></div></td>";
                    echo  "<td><div id = 'seven_total'></div></td>";
                    echo  "<td><div id = 'eight_total'></div></td>";
                    echo  "<td><div id = 'nine_total'></div></td>";
                    echo  "<td><div id = 'ten_total'></div></td>";
                    echo  "<td><div id = 'eleven_total'></div></td>";
                    echo  "<td><div id = 'twelve_total'></div></td>";
                    echo  "<td><div id = 'thirteen_total'></div></td>";
                    echo  "<td><div id = 'fourteen_total'></div></td>";
                    echo  "<td><div id = 'fiftteen_total'></div></td>";
                    echo  "<td><div id = 'sixteen_total'></div></td>";
                    echo  "<td><div id = 'seventeen_total'></div></td>";
                    echo  "<td><div id = 'eightteen_total'></div></td>";
                    echo  "<td><div id = 'nineteen_total'></div></td>";
                    echo  "<td><div id = 'twenty_total'></div></td>";
                    echo  "<td><div id = 'twentyone_total'></div></td>";
                    echo  "<td><div id = 'twentytwo_total'></div></td>";
                    echo  "<td><div id = 'twentythree_total'></div></td>";
                    echo  "<td><div id = 'twentyfour_total'></div></td>";
                    echo  "<td><div id = 'twentyfive_total'></div></td>";
                    echo  "<td><div id = 'twentysix_total'></div></td>";
                    echo  "<td><div id = 'twentyseven_total'></div></td>";
                    echo  "<td><div id = 'twentyeight_total'></div></td>";
                    echo  "<td><div id = 'twentynine_total'></div></td>";
                    echo  "<td><div id = 'thirty_total'></div></td>";
                    echo  "<td><div id = 'thirtyone_total'></div></td>";
                    echo "</tr>";        
                ?>
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


<script>  
var $ = jQuery;
$(document).ready(function() {
  

   var month = $('#month').val();
   var year =  $('#year').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Dashboard/client_details"; ?>', 
      data : 'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
              foreach ($clients as $client)
              { ?>
            
                $("#total_<?php echo $client['id'] ?>").html('');
                $("#first_<?php echo $client['id'] ?>").html('');
                $("#second_<?php echo $client['id'] ?>").html('');
                $("#three_<?php echo $client['id'] ?>").html('');
                $("#four_<?php echo $client['id'] ?>").html('');
                $("#five_<?php echo $client['id'] ?>").html('');
                $("#six_<?php echo $client['id'] ?>").html('');
                $("#seven_<?php echo $client['id'] ?>").html('');
                $("#eight_<?php echo $client['id'] ?>").html('');
                $("#nine_<?php echo $client['id'] ?>").html('');
                $("#ten_<?php echo $client['id'] ?>").html('');
                $("#eleven_<?php echo $client['id'] ?>").html('');  
                $("#twelve_<?php echo $client['id'] ?>").html('');
                $("#thirteen_<?php echo $client['id'] ?>").html('');
                $("#fourteen_<?php echo $client['id'] ?>").html('');  
                $("#fiftteen_<?php echo $client['id'] ?>").html('');  
                $("#sixteen_<?php echo $client['id'] ?>").html('');
                $("#seventeen_<?php echo $client['id'] ?>").html('');    
                $("#eightteen_<?php echo $client['id'] ?>").html('');    
                $("#nineteen_<?php echo $client['id'] ?>").html('');     
                $("#twenty_<?php echo $client['id'] ?>").html('');    
                $("#twentyone_<?php echo $client['id'] ?>").html(''); 
                $("#twentytwo_<?php echo $client['id'] ?>").html('');    
                $("#twentythree_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfive_<?php echo $client['id'] ?>").html('');
                $("#twentysix_<?php echo $client['id'] ?>").html(''); 
                $("#twentyseven_<?php echo $client['id'] ?>").html('');       
                $("#twentyeight_<?php echo $client['id'] ?>").html('');  
                $("#twentynine_<?php echo $client['id'] ?>").html(''); 
                $("#thirty_<?php echo $client['id'] ?>").html('');  
                $("#thirtyone_<?php echo $client['id'] ?>").html('');                 
                
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
            var total_total =  0;
            var first_total =  0; 
            var second_total =  0;
            var three_total =  0;
            var four_total =  0;
            var five_total =  0;
            var six_total =  0;
            var seven_total =  0;
            var eight_total =  0;
            var nine_total =  0;
            var ten_total =  0;
            var eleven_total =  0;
            var twelve_total =  0;
            var thirteen_total =  0;
            var fourteen_total =  0;
            var fiftteen_total =  0;
            var sixteen_total =  0;
            var seventeen_total =  0;
            var eightteen_total =  0;
            var nineteen_total =  0;
            var twenty_total =  0;
            var twentyone_total =  0;
            var twentytwo_total =  0;
            var twentythree_total =  0;
            var twentyfour_total =  0;
            var twentyfive_total =  0;
            var twentysix_total =  0;
            var twentyseven_total =  0;
            var twentyeight_total =  0;
            var twentynine_total =  0;
            var thirty_total =  0;
            var thirtyone_total =  0;
             
            <?php
            foreach ($clients as $client)
            { 
               if($client['sales_manager'] == $this->user_info['id'])
               {
                 ?>

               

                var total_<?php echo $client['id'] ?> = 0;

                if(typeof(type.one_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.one_<?php echo $client['id'] ?>);
                   first_total += parseInt(type.one_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.two_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.two_<?php echo $client['id'] ?>);
                   second_total += parseInt(type.two_<?php echo $client['id'] ?>);
                }
                if(typeof(type.three_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.three_<?php echo $client['id'] ?>);
                    three_total += parseInt(type.three_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.four_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.four_<?php echo $client['id'] ?>);
                   four_total += parseInt(type.four_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.five_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.five_<?php echo $client['id'] ?>);
                   five_total += parseInt(type.five_<?php echo $client['id'] ?>);
                }
                if(typeof(type.six_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.six_<?php echo $client['id'] ?>);
                   six_total += parseInt(type.six_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seven_<?php echo $client['id'] ?>);
                   seven_total += parseInt(type.seven_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.eight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eight_<?php echo $client['id'] ?>);
                   eight_total += parseInt(type.eight_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.nine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nine_<?php echo $client['id'] ?>);
                   nine_total += parseInt(type.nine_<?php echo $client['id'] ?>);
                }
                if(typeof(type.ten_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.ten_<?php echo $client['id'] ?>);
                   ten_total += parseInt(type.ten_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eleven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eleven_<?php echo $client['id'] ?>);
                   eleven_total += parseInt(type.eleven_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twelve_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twelve_<?php echo $client['id'] ?>);
                   twelve_total += parseInt(type.twelve_<?php echo $client['id'] ?>);
                }
                if(typeof(type.thirteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirteen_<?php echo $client['id'] ?>);
                   thirteen_total += parseInt(type.thirteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fourteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fourteen_<?php echo $client['id'] ?>);
                   fourteen_total += parseInt(type.fourteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fifteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fifteen_<?php echo $client['id'] ?>);
                   fiftteen_total += parseInt(type.fifteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.sixteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.sixteen_<?php echo $client['id'] ?>);
                   sixteen_total += parseInt(type.sixteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seventeen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seventeen_<?php echo $client['id'] ?>);
                   seventeen_total += parseInt(type.seventeen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eightteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eightteen_<?php echo $client['id'] ?>);
                    eightteen_total += parseInt(type.eightteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.nineteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nineteen_<?php echo $client['id'] ?>);
                   nineteen_total += parseInt(type.nineteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twenty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twenty_<?php echo $client['id'] ?>);
                   twenty_total += parseInt(type.twenty_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyone_<?php echo $client['id'] ?>);
                   twentyone_total += parseInt(type.twentyone_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentytwo_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                   twentytwo_total += parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentythree_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentythree_<?php echo $client['id'] ?>);
                    twentythree_total += parseInt(type.twentythree_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyfour_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                   twentyfour_total += parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                }  
                if(typeof(type.twentyfive_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                   twentyfive_total += parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentysix_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentysix_<?php echo $client['id'] ?>);
                   twentysix_total += parseInt(type.twentysix_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentyseven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyseven_<?php echo $client['id'] ?>);
                   twentyseven_total += parseInt(type.twentyseven_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twentyeight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyeight_<?php echo $client['id'] ?>);
                  twentyeight_total += parseInt(type.twentyeight_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.twentynine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentynine_<?php echo $client['id'] ?>);
                   twentynine_total += parseInt(type.twentynine_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.thirty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirty_<?php echo $client['id'] ?>);
                  thirty_total += parseInt(type.thirty_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.thirtyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                    thirtyone_total += parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                }   
               
                if(total_<?php echo $client['id'] ?> == "0")
                {
                   $("#show_hide_<?php echo $client['id'] ?>").hide();
                }
                else
                {
                   $("#show_hide_<?php echo $client['id'] ?>").show(); 
                }
            
                $("#total_<?php echo $client['id'] ?>").html(total_<?php echo $client['id'] ?>);
                $("#first_<?php echo $client['id'] ?>").html(type.one_<?php echo $client['id'] ?>);
                $("#second_<?php echo $client['id'] ?>").html(type.two_<?php echo $client['id'] ?>);
                $("#three_<?php echo $client['id'] ?>").html(type.three_<?php echo $client['id'] ?>);
                $("#four_<?php echo $client['id'] ?>").html(type.four_<?php echo $client['id'] ?>);
                $("#five_<?php echo $client['id'] ?>").html(type.five_<?php echo $client['id'] ?>);
                $("#six_<?php echo $client['id'] ?>").html(type.six_<?php echo $client['id'] ?>);
                $("#seven_<?php echo $client['id'] ?>").html(type.seven_<?php echo $client['id'] ?>);
                $("#eight_<?php echo $client['id'] ?>").html(type.eight_<?php echo $client['id'] ?>);
                $("#nine_<?php echo $client['id'] ?>").html(type.nine_<?php echo $client['id'] ?>);
                $("#ten_<?php echo $client['id'] ?>").html(type.ten_<?php echo $client['id'] ?>);
                $("#eleven_<?php echo $client['id'] ?>").html(type.eleven_<?php echo $client['id'] ?>);  
                $("#twelve_<?php echo $client['id'] ?>").html(type.twelve_<?php echo $client['id'] ?>);
                $("#thirteen_<?php echo $client['id'] ?>").html(type.thirteen_<?php echo $client['id'] ?>);
                $("#fourteen_<?php echo $client['id'] ?>").html(type.fourteen_<?php echo $client['id'] ?>);  
                $("#fiftteen_<?php echo $client['id'] ?>").html(type.fifteen_<?php echo $client['id'] ?>);  
                $("#sixteen_<?php echo $client['id'] ?>").html(type.sixteen_<?php echo $client['id'] ?>);
                $("#seventeen_<?php echo $client['id'] ?>").html(type.seventeen_<?php echo $client['id'] ?>);    
                $("#eightteen_<?php echo $client['id'] ?>").html(type.eightteen_<?php echo $client['id'] ?>);    
                $("#nineteen_<?php echo $client['id'] ?>").html(type.nineteen_<?php echo $client['id'] ?>);     
                $("#twenty_<?php echo $client['id'] ?>").html(type.twenty_<?php echo $client['id'] ?>);    
                $("#twentyone_<?php echo $client['id'] ?>").html(type.twentyone_<?php echo $client['id'] ?>); 
                $("#twentytwo_<?php echo $client['id'] ?>").html(type.twentytwo_<?php echo $client['id'] ?>);    
                $("#twentythree_<?php echo $client['id'] ?>").html(type.twentythree_<?php echo $client['id'] ?>); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(type.twentyfour_<?php echo $client['id'] ?>); 
                $("#twentyfive_<?php echo $client['id'] ?>").html(type.twentyfive_<?php echo $client['id'] ?>);
                $("#twentysix_<?php echo $client['id'] ?>").html(type.twentysix_<?php echo $client['id'] ?>); 
                $("#twentyseven_<?php echo $client['id'] ?>").html(type.twentyseven_<?php echo $client['id'] ?>);       
                $("#twentyeight_<?php echo $client['id'] ?>").html(type.twentyeight_<?php echo $client['id'] ?>);  
                $("#twentynine_<?php echo $client['id'] ?>").html(type.twentynine_<?php echo $client['id'] ?>); 
                $("#thirty_<?php echo $client['id'] ?>").html(type.thirty_<?php echo $client['id'] ?>);  
                $("#thirtyone_<?php echo $client['id'] ?>").html(type.thirtyone_<?php echo $client['id'] ?>);                 
                
                total_total += parseInt(total_<?php echo $client['id'] ?>);
                
                
              <?php
               } 
               
            }
                            
            ?>
          
            $("#total_total").html(total_total);
            $("#first_total").html(first_total);
            $("#second_total").html(second_total);
            $("#three_total").html(three_total);
            $("#four_total").html(four_total);
            $("#five_total").html(five_total);
            $("#six_total").html(six_total);
            $("#seven_total").html(seven_total);
            $("#eight_total").html(eight_total);
            $("#nine_total").html(nine_total);
            $("#ten_total").html(ten_total);
            $("#eleven_total").html(eleven_total);
            $("#twelve_total").html(twelve_total);
            $("#thirteen_total").html(thirteen_total);
            $("#fourteen_total").html(fourteen_total);
            $("#fiftteen_total").html(fiftteen_total);
            $("#sixteen_total").html(sixteen_total);
            $("#seventeen_total").html(seventeen_total);
            $("#eightteen_total").html(eightteen_total);
            $("#nineteen_total").html(nineteen_total);
            $("#twenty_total").html(twenty_total);
            $("#twentyone_total").html(twentyone_total);
            $("#twentytwo_total").html(twentytwo_total);
            $("#twentythree_total").html(twentythree_total);
            $("#twentyfour_total").html(twentyfour_total);
            $("#twentyfive_total").html(twentyfive_total);
            $("#twentysix_total").html(twentysix_total);
            $("#twentyseven_total").html(twentyseven_total);
            $("#twentyeight_total").html(twentyeight_total);
            $("#twentynine_total").html(twentynine_total);
            $("#thirty_total").html(thirty_total);
            $("#thirtyone_total").html(thirtyone_total);
            $("#show_hide_total").show(); 
            
            return true;
        }
        else
        {
              <?php
              foreach ($clients as $client)
              { 
              ?>
              $("#show_hide_<?php echo $client['id'] ?>").hide();
              $("#show_hide_total").hide();    
                            
              <?php      
              }              
              ?>
          return false;
        }
      }
    });
$('#month,#year').on('change', function(){  
   var month = $('#month').val();
   var year =  $('#year').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Dashboard/client_details"; ?>', 
      data : 'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
              foreach ($clients as $client)
              { ?>
            
                $("#total_<?php echo $client['id'] ?>").html('');
                $("#first_<?php echo $client['id'] ?>").html('');
                $("#second_<?php echo $client['id'] ?>").html('');
                $("#three_<?php echo $client['id'] ?>").html('');
                $("#four_<?php echo $client['id'] ?>").html('');
                $("#five_<?php echo $client['id'] ?>").html('');
                $("#six_<?php echo $client['id'] ?>").html('');
                $("#seven_<?php echo $client['id'] ?>").html('');
                $("#eight_<?php echo $client['id'] ?>").html('');
                $("#nine_<?php echo $client['id'] ?>").html('');
                $("#ten_<?php echo $client['id'] ?>").html('');
                $("#eleven_<?php echo $client['id'] ?>").html('');  
                $("#twelve_<?php echo $client['id'] ?>").html('');
                $("#thirteen_<?php echo $client['id'] ?>").html('');
                $("#fourteen_<?php echo $client['id'] ?>").html('');  
                $("#fiftteen_<?php echo $client['id'] ?>").html('');  
                $("#sixteen_<?php echo $client['id'] ?>").html('');
                $("#seventeen_<?php echo $client['id'] ?>").html('');    
                $("#eightteen_<?php echo $client['id'] ?>").html('');    
                $("#nineteen_<?php echo $client['id'] ?>").html('');     
                $("#twenty_<?php echo $client['id'] ?>").html('');    
                $("#twentyone_<?php echo $client['id'] ?>").html(''); 
                $("#twentytwo_<?php echo $client['id'] ?>").html('');    
                $("#twentythree_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(''); 
                $("#twentyfive_<?php echo $client['id'] ?>").html('');
                $("#twentysix_<?php echo $client['id'] ?>").html(''); 
                $("#twentyseven_<?php echo $client['id'] ?>").html('');       
                $("#twentyeight_<?php echo $client['id'] ?>").html('');  
                $("#twentynine_<?php echo $client['id'] ?>").html(''); 
                $("#thirty_<?php echo $client['id'] ?>").html('');  
                $("#thirtyone_<?php echo $client['id'] ?>").html(''); 

                
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
            var total_total =  0;
            var first_total =  0; 
            var second_total =  0;
            var three_total =  0;
            var four_total =  0;
            var five_total =  0;
            var six_total =  0;
            var seven_total =  0;
            var eight_total =  0;
            var nine_total =  0;
            var ten_total =  0;
            var eleven_total =  0;
            var twelve_total =  0;
            var thirteen_total =  0;
            var fourteen_total =  0;
            var fiftteen_total =  0;
            var sixteen_total =  0;
            var seventeen_total =  0;
            var eightteen_total =  0;
            var nineteen_total =  0;
            var twenty_total =  0;
            var twentyone_total =  0;
            var twentytwo_total =  0;
            var twentythree_total =  0;
            var twentyfour_total =  0;
            var twentyfive_total =  0;
            var twentysix_total =  0;
            var twentyseven_total =  0;
            var twentyeight_total =  0;
            var twentynine_total =  0;
            var thirty_total =  0;
            var thirtyone_total =  0;
             

            <?php
              foreach ($clients as $client)
              {
               if($client['sales_manager'] == $this->user_info['id'])
               {   ?>
                
                var total_<?php echo $client['id'] ?> = 0;

                if(typeof(type.one_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.one_<?php echo $client['id'] ?>);
                   first_total += parseInt(type.one_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.two_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.two_<?php echo $client['id'] ?>);
                   second_total += parseInt(type.two_<?php echo $client['id'] ?>);
                }
                if(typeof(type.three_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.three_<?php echo $client['id'] ?>);
                    three_total += parseInt(type.three_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.four_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.four_<?php echo $client['id'] ?>);
                   four_total += parseInt(type.four_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.five_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.five_<?php echo $client['id'] ?>);
                   five_total += parseInt(type.five_<?php echo $client['id'] ?>);
                }
                if(typeof(type.six_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.six_<?php echo $client['id'] ?>);
                   six_total += parseInt(type.six_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seven_<?php echo $client['id'] ?>);
                   seven_total += parseInt(type.seven_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.eight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eight_<?php echo $client['id'] ?>);
                   eight_total += parseInt(type.eight_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.nine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nine_<?php echo $client['id'] ?>);
                   nine_total += parseInt(type.nine_<?php echo $client['id'] ?>);
                }
                if(typeof(type.ten_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.ten_<?php echo $client['id'] ?>);
                   ten_total += parseInt(type.ten_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eleven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eleven_<?php echo $client['id'] ?>);
                   eleven_total += parseInt(type.eleven_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twelve_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twelve_<?php echo $client['id'] ?>);
                   twelve_total += parseInt(type.twelve_<?php echo $client['id'] ?>);
                }
                if(typeof(type.thirteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirteen_<?php echo $client['id'] ?>);
                   thirteen_total += parseInt(type.thirteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fourteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fourteen_<?php echo $client['id'] ?>);
                   fourteen_total += parseInt(type.fourteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.fifteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.fifteen_<?php echo $client['id'] ?>);
                   fiftteen_total += parseInt(type.fifteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.sixteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.sixteen_<?php echo $client['id'] ?>);
                   sixteen_total += parseInt(type.sixteen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.seventeen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.seventeen_<?php echo $client['id'] ?>);
                   seventeen_total += parseInt(type.seventeen_<?php echo $client['id'] ?>);
                }
                if(typeof(type.eightteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.eightteen_<?php echo $client['id'] ?>);
                    eightteen_total += parseInt(type.eightteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.nineteen_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.nineteen_<?php echo $client['id'] ?>);
                   nineteen_total += parseInt(type.nineteen_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twenty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twenty_<?php echo $client['id'] ?>);
                   twenty_total += parseInt(type.twenty_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyone_<?php echo $client['id'] ?>);
                   twentyone_total += parseInt(type.twentyone_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentytwo_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                   twentytwo_total += parseInt(type.twentytwo_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentythree_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentythree_<?php echo $client['id'] ?>);
                    twentythree_total += parseInt(type.twentythree_<?php echo $client['id'] ?>);
                }
                if(typeof(type.twentyfour_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                   twentyfour_total += parseInt(type.twentyfour_<?php echo $client['id'] ?>);
                }  
                if(typeof(type.twentyfive_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                   twentyfive_total += parseInt(type.twentyfive_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentysix_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentysix_<?php echo $client['id'] ?>);
                   twentysix_total += parseInt(type.twentysix_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.twentyseven_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyseven_<?php echo $client['id'] ?>);
                   twentyseven_total += parseInt(type.twentyseven_<?php echo $client['id'] ?>);

                }
                if(typeof(type.twentyeight_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentyeight_<?php echo $client['id'] ?>);
                  twentyeight_total += parseInt(type.twentyeight_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.twentynine_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.twentynine_<?php echo $client['id'] ?>);
                   twentynine_total += parseInt(type.twentynine_<?php echo $client['id'] ?>);
                } 
                if(typeof(type.thirty_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirty_<?php echo $client['id'] ?>);
                  thirty_total += parseInt(type.thirty_<?php echo $client['id'] ?>);

                } 
                if(typeof(type.thirtyone_<?php echo $client['id'] ?>) != "undefined")
                {
                   total_<?php echo $client['id'] ?> +=  parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                    thirtyone_total += parseInt(type.thirtyone_<?php echo $client['id'] ?>);
                }   

                if(total_<?php echo $client['id'] ?> == "0")
                {
                   $("#show_hide_<?php echo $client['id'] ?>").hide();
                } 
                else
                {
                   $("#show_hide_<?php echo $client['id'] ?>").show(); 
                }
             
                $("#total_<?php echo $client['id'] ?>").html(total_<?php echo $client['id'] ?>);
                $("#first_<?php echo $client['id'] ?>").html(type.one_<?php echo $client['id'] ?>);
                $("#second_<?php echo $client['id'] ?>").html(type.two_<?php echo $client['id'] ?>);
                $("#three_<?php echo $client['id'] ?>").html(type.three_<?php echo $client['id'] ?>);
                $("#four_<?php echo $client['id'] ?>").html(type.four_<?php echo $client['id'] ?>);
                $("#five_<?php echo $client['id'] ?>").html(type.five_<?php echo $client['id'] ?>);
                $("#six_<?php echo $client['id'] ?>").html(type.six_<?php echo $client['id'] ?>);
                $("#seven_<?php echo $client['id'] ?>").html(type.seven_<?php echo $client['id'] ?>);
                $("#eight_<?php echo $client['id'] ?>").html(type.eight_<?php echo $client['id'] ?>);
                $("#nine_<?php echo $client['id'] ?>").html(type.nine_<?php echo $client['id'] ?>);
                $("#ten_<?php echo $client['id'] ?>").html(type.ten_<?php echo $client['id'] ?>);
                $("#eleven_<?php echo $client['id'] ?>").html(type.eleven_<?php echo $client['id'] ?>);  
                $("#twelve_<?php echo $client['id'] ?>").html(type.twelve_<?php echo $client['id'] ?>);
                $("#thirteen_<?php echo $client['id'] ?>").html(type.thirteen_<?php echo $client['id'] ?>);
                $("#fourteen_<?php echo $client['id'] ?>").html(type.fourteen_<?php echo $client['id'] ?>);  
                $("#fiftteen_<?php echo $client['id'] ?>").html(type.fifteen_<?php echo $client['id'] ?>);  
                $("#sixteen_<?php echo $client['id'] ?>").html(type.sixteen_<?php echo $client['id'] ?>);
                $("#seventeen_<?php echo $client['id'] ?>").html(type.seventeen_<?php echo $client['id'] ?>);    
                $("#eightteen_<?php echo $client['id'] ?>").html(type.eightteen_<?php echo $client['id'] ?>);    
                $("#nineteen_<?php echo $client['id'] ?>").html(type.nineteen_<?php echo $client['id'] ?>);     
                $("#twenty_<?php echo $client['id'] ?>").html(type.twenty_<?php echo $client['id'] ?>);    
                $("#twentyone_<?php echo $client['id'] ?>").html(type.twentyone_<?php echo $client['id'] ?>); 
                $("#twentytwo_<?php echo $client['id'] ?>").html(type.twentytwo_<?php echo $client['id'] ?>);    
                $("#twentythree_<?php echo $client['id'] ?>").html(type.twentythree_<?php echo $client['id'] ?>); 
                $("#twentyfour_<?php echo $client['id'] ?>").html(type.twentyfour_<?php echo $client['id'] ?>); 
                $("#twentyfive_<?php echo $client['id'] ?>").html(type.twentyfive_<?php echo $client['id'] ?>);
                $("#twentysix_<?php echo $client['id'] ?>").html(type.twentysix_<?php echo $client['id'] ?>); 
                $("#twentyseven_<?php echo $client['id'] ?>").html(type.twentyseven_<?php echo $client['id'] ?>);       
                $("#twentyeight_<?php echo $client['id'] ?>").html(type.twentyeight_<?php echo $client['id'] ?>);  
                $("#twentynine_<?php echo $client['id'] ?>").html(type.twentynine_<?php echo $client['id'] ?>); 
                $("#thirty_<?php echo $client['id'] ?>").html(type.thirty_<?php echo $client['id'] ?>);  
                $("#thirtyone_<?php echo $client['id'] ?>").html(type.thirtyone_<?php echo $client['id'] ?>);  

                  total_total += parseInt(total_<?php echo $client['id'] ?>);               
                
                 <?php  
               }    
              }
                            
            ?>
            
            $("#total_total").html(total_total);
            $("#first_total").html(first_total);
            $("#second_total").html(second_total);
            $("#three_total").html(three_total);
            $("#four_total").html(four_total);
            $("#five_total").html(five_total);
            $("#six_total").html(six_total);
            $("#seven_total").html(seven_total);
            $("#eight_total").html(eight_total);
            $("#nine_total").html(nine_total);
            $("#ten_total").html(ten_total);
            $("#eleven_total").html(eleven_total);
            $("#twelve_total").html(twelve_total);
            $("#thirteen_total").html(thirteen_total);
            $("#fourteen_total").html(fourteen_total);
            $("#fiftteen_total").html(fiftteen_total);
            $("#sixteen_total").html(sixteen_total);
            $("#seventeen_total").html(seventeen_total);
            $("#eightteen_total").html(eightteen_total);
            $("#nineteen_total").html(nineteen_total);
            $("#twenty_total").html(twenty_total);
            $("#twentyone_total").html(twentyone_total);
            $("#twentytwo_total").html(twentytwo_total);
            $("#twentythree_total").html(twentythree_total);
            $("#twentyfour_total").html(twentyfour_total);
            $("#twentyfive_total").html(twentyfive_total);
            $("#twentysix_total").html(twentysix_total);
            $("#twentyseven_total").html(twentyseven_total);
            $("#twentyeight_total").html(twentyeight_total);
            $("#twentynine_total").html(twentynine_total);
            $("#thirty_total").html(thirty_total);
            $("#thirtyone_total").html(thirtyone_total);
            $("#show_hide_total").show();

            return true;
        }
        else
        {
              <?php
              foreach ($clients as $client)
              { 
              ?>
              $("#show_hide_<?php echo $client['id'] ?>").hide(); 
              $("#show_hide_total").hide();                   
              <?php      
              }              
              ?>
          return false;
        }
      }
    });
  });

});

</script>


<?php } ?>   