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
            <?php    if ($this->permission['access_report_list_hourly'] == 1) { ?>
               <div class="row">
                  <div class="col-sm-3 form-group">
                     <input type="text" name="start_date_hourly" id="start_date_hourly" class="form-control myDatepicker" placeholder="Date Range" data-date-format="dd-mm-yyyy" value= "<?php echo date('d-m-Y'); ?>">
                  </div>
                  <div class="col-sm-3 form-group">
                     <input type="text" name="end_date_hourly" id="end_date_hourly" class="form-control myDatepicker" placeholder="Date Range" data-date-format="dd-mm-yyyy" value= "<?php echo date('d-m-Y'); ?>">
                  </div>

                  <div class="col-sm-4 form-group">
                     <input type="button" name="searchrecords_hourly" id="searchrecords_hourly" class="btn btn-md btn-info" value="Filter">
                  </div>
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_hourly" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>Time</th>
                    <?php 
                    $i = 1;
                    unset($user_list[0]);                   
                    foreach ($user_list as $key  => $user_lists)
                    { 

                        echo "<th class = show_hide_".$key." style = 'outline: thin solid'>".$user_lists."</th>";
                    }
                    ?>
                  </tr>
                </thead>
                 <tbody>
                     <tr>
                        <td style = 'outline: thin solid'>8 pm to 9 am {Previous to current}</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'eight_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                       <td style = 'outline: thin solid'>9:00 AM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'nine_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                       <td style = 'outline: thin solid'>10:00 AM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'ten_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                       <td style = 'outline: thin solid'>11:00 AM</td>
                       <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'eleven_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                       <td style = 'outline: thin solid'>12:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'twelve_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>1:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'thirteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>2:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'fourteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>3:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'fifteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>4:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'sixteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>5:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'seventeen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>6:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'eighteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>7:00 PM</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key."><div id = 'nineteen_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                     <tr>
                        <td style = 'outline: thin solid'>Total</td>
                        <?php 
                        unset($user_list[0]);                   
                        foreach ($user_list as $key  => $user_lists)
                        { 
                           echo "<td class = show_hide_".$key." style = 'outline: thin solid'><div id = 'total_hourly_all_".$key."'></div></td>";
                        }
                        ?>
                     </tr>
                </tbody>
              </table>

              <?php  }else { echo 'You have not permission to access this page';}?>
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
</script>   
   
<script>  
var $ = jQuery;
$(document).ready(function() {
  

   var start_date = $('#start_date_hourly').val();
   var end_date =  $('#end_date_hourly').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/hourly_activity_details"; ?>', 
      data :  'start_date='+start_date+'&end_date='+end_date,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#eight_<?php echo $key ?>").html('');
                $("#nine_<?php echo $key ?>").html('');
                $("#ten_<?php echo $key ?>").html('');
                $("#eleven_<?php echo $key ?>").html('');
                $("#twelve_<?php echo $key ?>").html('');
                $("#thirteen_<?php echo $key ?>").html('');
                $("#fourteen_<?php echo $key ?>").html('');
                $("#fifteen_<?php echo $key ?>").html('');
                $("#sixteen_<?php echo $key ?>").html('');
                $("#seventeen_<?php echo $key ?>").html('');
                $("#eighteen_<?php echo $key ?>").html('');
                $("#nineteen_<?php echo $key ?>").html('');  
                $("#total_hourly_all_<?php echo $key ?>").html('');
               
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
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
               var total_all = 0;

               if(typeof(type.eight_<?php echo $key ?>) != "undefined")
               {
                  eight_<?php echo $key ?> =  parseInt(type.eight_<?php echo $key ?>);
                  $("#eight_<?php echo $key ?>").html(parseInt(type.eight_<?php echo $key ?>));
                  total_all += parseInt(type.eight_<?php echo $key ?>);
               }
               if(typeof(type.nine_<?php echo $key ?>) != "undefined")
               {
                  nine_<?php echo $key ?> =  parseInt(type.nine_<?php echo $key ?>);
                  $("#nine_<?php echo $key ?>").html(parseInt(type.nine_<?php echo $key ?>));
                  total_all += parseInt(type.nine_<?php echo $key ?>);
               } 
               if(typeof(type.ten_<?php echo $key ?>) != "undefined")
               {
                  ten_<?php echo $key ?> =  parseInt(type.ten_<?php echo $key ?>);
                  $("#ten_<?php echo $key ?>").html(parseInt(type.ten_<?php echo $key ?>));
                  total_all += parseInt(type.ten_<?php echo $key ?>);
               } 
               if(typeof(type.eleven_<?php echo $key ?>) != "undefined")
               {
                  eleven_<?php echo $key ?> =  parseInt(type.eleven_<?php echo $key ?>);
                  $("#eleven_<?php echo $key ?>").html(parseInt(type.eleven_<?php echo $key ?>));
                  total_all += parseInt(type.eleven_<?php echo $key ?>);
               } 
               if(typeof(type.twelve_<?php echo $key ?>) != "undefined")
               {
                  twelve_<?php echo $key ?> =  parseInt(type.twelve_<?php echo $key ?>);
                  $("#twelve_<?php echo $key ?>").html(parseInt(type.twelve_<?php echo $key ?>));
                  total_all += parseInt(type.twelve_<?php echo $key ?>);
               } 
               if(typeof(type.thirteen_<?php echo $key ?>) != "undefined")
               {
                  thirteen_<?php echo $key ?> =  parseInt(type.thirteen_<?php echo $key ?>);
                  $("#thirteen_<?php echo $key ?>").html(parseInt(type.thirteen_<?php echo $key ?>));
                  total_all += parseInt(type.thirteen_<?php echo $key ?>);
               } 
               if(typeof(type.fourteen_<?php echo $key ?>) != "undefined")
               {
                  fourteen_<?php echo $key ?> =  parseInt(type.fourteen_<?php echo $key ?>);
                  $("#fourteen_<?php echo $key ?>").html(parseInt(type.fourteen_<?php echo $key ?>));
                  total_all += parseInt(type.fourteen_<?php echo $key ?>);
               } 
               if(typeof(type.fifteen_<?php echo $key ?>) != "undefined")
               {
                  fifteen_<?php echo $key ?> =  parseInt(type.fifteen_<?php echo $key ?>);
                  $("#fifteen_<?php echo $key ?>").html(parseInt(type.fifteen_<?php echo $key ?>));
                  total_all += parseInt(type.fifteen_<?php echo $key ?>); 
               }
               if(typeof(type.sixteen_<?php echo $key ?>) != "undefined")
               {
                  sixteen_<?php echo $key ?> =  parseInt(type.sixteen_<?php echo $key ?>);
                  $("#sixteen_<?php echo $key ?>").html(parseInt(type.sixteen_<?php echo $key ?>));
                  total_all += parseInt(type.sixteen_<?php echo $key ?>); 
               } 
               if(typeof(type.seventeen_<?php echo $key ?>) != "undefined")
               {
                  seventeen_<?php echo $key ?> =  parseInt(type.seventeen_<?php echo $key ?>);
                  $("#seventeen_<?php echo $key ?>").html(parseInt(type.seventeen_<?php echo $key ?>));
                  total_all += parseInt(type.seventeen_<?php echo $key ?>); 
               } 
               if(typeof(type.eighteen_<?php echo $key ?>) != "undefined")
               {
                  eighteen_<?php echo $key ?> =  parseInt(type.eighteen_<?php echo $key ?>);
                  $("#eighteen_<?php echo $key ?>").html(parseInt(type.eighteen_<?php echo $key ?>));
                  total_all += parseInt(type.eighteen_<?php echo $key ?>); 
               }
               if(typeof(type.nineteen_<?php echo $key ?>) != "undefined")
               {
                  nineteen_<?php echo $key ?> =  parseInt(type.nineteen_<?php echo $key ?>);
                  $("#nineteen_<?php echo $key ?>").html(parseInt(type.nineteen_<?php echo $key ?>));
                  total_all += parseInt(type.nineteen_<?php echo $key ?>); 
               } 
                $("#total_hourly_all_<?php echo $key ?>").html(total_all);   

               if(total_all == "0")
               { 
                  $(".show_hide_<?php echo $key ?>").hide();  
               }
               else
               {  
                  $(".show_hide_<?php echo $key ?>").show(); 
               }

            <?php      
            }               
            ?>
            
         }  
            
      }
   }); 

  

   $('#searchrecords_hourly').on('click', function() {

      var start_date = $('#start_date_hourly').val();
      var end_date =  $('#end_date_hourly').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/hourly_activity_details"; ?>', 
      data :  'start_date='+start_date+'&end_date='+end_date,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#eight_<?php echo $key ?>").html('');
                $("#nine_<?php echo $key ?>").html('');
                $("#ten_<?php echo $key ?>").html('');
                $("#eleven_<?php echo $key ?>").html('');
                $("#twelve_<?php echo $key ?>").html('');
                $("#thirteen_<?php echo $key ?>").html('');
                $("#fourteen_<?php echo $key ?>").html('');
                $("#fifteen_<?php echo $key ?>").html('');
                $("#sixteen_<?php echo $key ?>").html('');
                $("#seventeen_<?php echo $key ?>").html('');
                $("#eighteen_<?php echo $key ?>").html('');
                $("#nineteen_<?php echo $key ?>").html('');  
                $("#total_hourly_all_<?php echo $key ?>").html('');
               
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
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
               var total_all = 0;

               if(typeof(type.eight_<?php echo $key ?>) != "undefined")
               {
                  eight_<?php echo $key ?> =  parseInt(type.eight_<?php echo $key ?>);
                  $("#eight_<?php echo $key ?>").html(parseInt(type.eight_<?php echo $key ?>));
                  total_all += parseInt(type.eight_<?php echo $key ?>);
               }
               if(typeof(type.nine_<?php echo $key ?>) != "undefined")
               {
                  nine_<?php echo $key ?> =  parseInt(type.nine_<?php echo $key ?>);
                  $("#nine_<?php echo $key ?>").html(parseInt(type.nine_<?php echo $key ?>));
                  total_all += parseInt(type.nine_<?php echo $key ?>);
               } 
               if(typeof(type.ten_<?php echo $key ?>) != "undefined")
               {
                  ten_<?php echo $key ?> =  parseInt(type.ten_<?php echo $key ?>);
                  $("#ten_<?php echo $key ?>").html(parseInt(type.ten_<?php echo $key ?>));
                  total_all += parseInt(type.ten_<?php echo $key ?>);
               } 
               if(typeof(type.eleven_<?php echo $key ?>) != "undefined")
               {
                  eleven_<?php echo $key ?> =  parseInt(type.eleven_<?php echo $key ?>);
                  $("#eleven_<?php echo $key ?>").html(parseInt(type.eleven_<?php echo $key ?>));
                  total_all += parseInt(type.eleven_<?php echo $key ?>);
               } 
               if(typeof(type.twelve_<?php echo $key ?>) != "undefined")
               {
                  twelve_<?php echo $key ?> =  parseInt(type.twelve_<?php echo $key ?>);
                  $("#twelve_<?php echo $key ?>").html(parseInt(type.twelve_<?php echo $key ?>));
                  total_all += parseInt(type.twelve_<?php echo $key ?>);
               } 
               if(typeof(type.thirteen_<?php echo $key ?>) != "undefined")
               {
                  thirteen_<?php echo $key ?> =  parseInt(type.thirteen_<?php echo $key ?>);
                  $("#thirteen_<?php echo $key ?>").html(parseInt(type.thirteen_<?php echo $key ?>));
                  total_all += parseInt(type.thirteen_<?php echo $key ?>);
               } 
               if(typeof(type.fourteen_<?php echo $key ?>) != "undefined")
               {
                  fourteen_<?php echo $key ?> =  parseInt(type.fourteen_<?php echo $key ?>);
                  $("#fourteen_<?php echo $key ?>").html(parseInt(type.fourteen_<?php echo $key ?>));
                  total_all += parseInt(type.fourteen_<?php echo $key ?>);
               } 
               if(typeof(type.fifteen_<?php echo $key ?>) != "undefined")
               {
                  fifteen_<?php echo $key ?> =  parseInt(type.fifteen_<?php echo $key ?>);
                  $("#fifteen_<?php echo $key ?>").html(parseInt(type.fifteen_<?php echo $key ?>));
                  total_all += parseInt(type.fifteen_<?php echo $key ?>); 
               }
               if(typeof(type.sixteen_<?php echo $key ?>) != "undefined")
               {
                  sixteen_<?php echo $key ?> =  parseInt(type.sixteen_<?php echo $key ?>);
                  $("#sixteen_<?php echo $key ?>").html(parseInt(type.sixteen_<?php echo $key ?>));
                  total_all += parseInt(type.sixteen_<?php echo $key ?>); 
               } 
               if(typeof(type.seventeen_<?php echo $key ?>) != "undefined")
               {
                  seventeen_<?php echo $key ?> =  parseInt(type.seventeen_<?php echo $key ?>);
                  $("#seventeen_<?php echo $key ?>").html(parseInt(type.seventeen_<?php echo $key ?>));
                  total_all += parseInt(type.seventeen_<?php echo $key ?>); 
               } 
               if(typeof(type.eighteen_<?php echo $key ?>) != "undefined")
               {
                  eighteen_<?php echo $key ?> =  parseInt(type.eighteen_<?php echo $key ?>);
                  $("#eighteen_<?php echo $key ?>").html(parseInt(type.eighteen_<?php echo $key ?>));
                  total_all += parseInt(type.eighteen_<?php echo $key ?>); 
               }
               if(typeof(type.nineteen_<?php echo $key ?>) != "undefined")
               {
                  nineteen_<?php echo $key ?> =  parseInt(type.nineteen_<?php echo $key ?>);
                  $("#nineteen_<?php echo $key ?>").html(parseInt(type.nineteen_<?php echo $key ?>));
                  total_all += parseInt(type.nineteen_<?php echo $key ?>); 
               } 
                $("#total_hourly_all_<?php echo $key ?>").html(total_all);   

               if(total_all == "0")
               { 
                  $(".show_hide_<?php echo $key ?>").hide();  
               }
               else
               {  
                  $(".show_hide_<?php echo $key ?>").show(); 
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