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
            <?php    if ($this->permission['access_report_list_client'] == 1) { ?>
               <div class="row">
               <div class="col-sm-4 form-group">
                <?php
                  $month = array('0' => 'Select Month','01' => 'January','02' => 'Feb','03' => 'March','04' => 'April','05' => 'May','06' => 'June','07' => 'July','08' => 'August','09' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
                    echo form_dropdown('month_client_allocation',$month, set_value('month_client_allocation',date('m')),'class="select2" id="month_client_allocation"');?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php
                  $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
                   echo form_dropdown('year_client_allocation', $year, set_value('year_client_allocation',date('Y')), 'class="select2" id="year_client_allocation"');
                   ?>
                </div> 

                <div class="col-sm-4 form-group">
                     <input type="button" name="searchrecords_client_allocation" id="searchrecords_client_allocation" class="btn btn-md btn-info" value="Filter">
                  </div>
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_client_allocation" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>Client Name</th>
                    <th style = 'outline: thin solid'>Initiation</th>
                    <th style = 'outline: thin solid'>WIP</th>
                    <th style = 'outline: thin solid'>Insuff</th>
                    <th style = 'outline: thin solid'>Closed</th>
                  </tr>
                </thead>
                 <tbody>
                 <?php 
                  $i = 1;
                  unset($clients_list[0]);
                 
                   
                    foreach ($clients_list as $key  => $clients_lists)
                    { 
                 
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>".$clients_lists."</td>";
                      echo  "<td><div id = 'initiationed_".$key."'></div></td>";
                      echo  "<td><div id = 'wiped_".$key."'></div></td>";
                      echo  "<td><div id = 'insuffed_".$key."'></div></td>";
                      echo  "<td><div id = 'closeded_".$key."'></div></td>";
                      echo  "</tr>";
                                          
                    }
                    echo  "<tr>";
                    echo  "<td style = 'outline: thin solid'> Total </td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'inititationed_total'></div></td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'wiped_total'></div></td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'insuffed_total'></div></td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'closeded_total'></div></td>"; 
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
  

   var month = $('#month_client_allocation').val();
   var year =  $('#year_client_allocation').val();

 


  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/client_allocation_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($clients_list as $key  => $clients_lists)
            { 
            ?>
                $("#inititationed_<?php echo $key ?>").html('');
                $("#wiped_<?php echo $key ?>").html('');
                $("#insuffed_<?php echo $key ?>").html('');
                $("#closeded_<?php echo $key ?>").html('');

             
            <?php      
            }
            ?>
            $("#inititationed_total").html('');
            $("#wiped_total").html('');
            $("#insuffed_total").html('');
            $("#closeded_total").html('');
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;


            var total_initiationed = 0;
            var total_wiped = 0;
            var total_insuffed = 0;
            var total_closeded = 0;
              
        
            <?php
            foreach ($clients_list as $key  => $clients_lists)
            { 
            ?>
            var initiationed = 0; 
            var wiped = 0; 
            var insuffed = 0;
            var closeded = 0;

               if(typeof(type.initiation_<?php echo $key ?>) != "undefined")
               { 
                  $("#initiationed_<?php echo $key ?>").html(parseInt(type.initiation_<?php echo $key ?>));  
                  total_initiationed += parseInt(type.initiation_<?php echo $key ?>); 
                  initiationed = parseInt(type.initiation_<?php echo $key ?>);
               }
             
               if(typeof(type.wip_<?php echo $key ?>) != "undefined")
               { 
                  $("#wiped_<?php echo $key ?>").html(parseInt(type.wip_<?php echo $key ?>));  
                  total_wiped += parseInt(type.wip_<?php echo $key ?>); 
                  wiped = parseInt(type.wip_<?php echo $key ?>);
               }
             
               if(typeof(type.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#insuffed_<?php echo $key ?>").html(parseInt(type.insufficiency_<?php echo $key ?>));
                  total_insuffed += parseInt(type.insufficiency_<?php echo $key ?>); 
                  insuffed += parseInt(type.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#closeded_<?php echo $key ?>").html(parseInt(type.closed_<?php echo $key ?>));
                  total_closeded += parseInt(type.closed_<?php echo $key ?>); 
                  closeded += parseInt(type.closed_<?php echo $key ?>); 
               } 
               
            
               if(wiped == "0" && insuffed == "0" && closeded == "0" && initiationed == "0")
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
           
            
            $("#inititationed_total").html(total_initiationed);   


            $("#wiped_total").html(total_wiped);   

            $("#insuffed_total").html(total_insuffed);  

            $("#closeded_total").html(total_closeded);   

            
         }  
            
      }
   });

 

  $('#searchrecords_client_allocation').on('click', function() {

      var month = $('#month_client_allocation').val();
      var year =  $('#year_client_allocation').val();

      
  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/client_allocation_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($clients_list as $key  => $clients_lists)
            { 
            ?>
                $("#inititationed_<?php echo $key ?>").html('');
                $("#wiped_<?php echo $key ?>").html('');
                $("#insuffed_<?php echo $key ?>").html('');
                $("#closeded_<?php echo $key ?>").html('');

             
            <?php      
            }
            ?>
            $("#inititationed_total").html('');
            $("#wiped_total").html('');
            $("#insuffed_total").html('');
            $("#closeded_total").html('');
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;

            var total_initiationed = 0;
            var total_wiped = 0;
            var total_insuffed = 0;
            var total_closeded = 0;
              
        
            <?php
            foreach ($clients_list as $key  => $clients_lists)
            { 
            ?>
            var initiationed = 0; 
            var wiped = 0; 
            var insuffed = 0;
            var closeded = 0;

               
               if(typeof(type.initiation_<?php echo $key ?>) != "undefined")
               { 
                  $("#initiationed_<?php echo $key ?>").html(parseInt(type.initiation_<?php echo $key ?>));  
                  total_initiationed += parseInt(type.initiation_<?php echo $key ?>); 
                  initiationed = parseInt(type.initiation_<?php echo $key ?>);
               }
             
               if(typeof(type.wip_<?php echo $key ?>) != "undefined")
               { 
                  $("#wiped_<?php echo $key ?>").html(parseInt(type.wip_<?php echo $key ?>));  
                  total_wiped += parseInt(type.wip_<?php echo $key ?>); 
                  wiped = parseInt(type.wip_<?php echo $key ?>);
               }
             
               if(typeof(type.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#insuffed_<?php echo $key ?>").html(parseInt(type.insufficiency_<?php echo $key ?>));
                  total_insuffed += parseInt(type.insufficiency_<?php echo $key ?>); 
                  insuffed += parseInt(type.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#closeded_<?php echo $key ?>").html(parseInt(type.closed_<?php echo $key ?>));
                  total_closeded += parseInt(type.closed_<?php echo $key ?>); 
                  closeded += parseInt(type.closed_<?php echo $key ?>); 
               } 
               
            
               if(wiped == "0" && insuffed == "0" && closeded == "0"  && initiationed == "0")
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
            
            $("#inititationed_total").html(total_initiationed);   
            
            $("#wiped_total").html(total_wiped);   

            $("#insuffed_total").html(total_insuffed);  

            $("#closeded_total").html(total_closeded);   

            
         }  
            
      }
    });
 

  });

});

</script>