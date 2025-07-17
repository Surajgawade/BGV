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
            <?php    if ($this->permission['access_report_list_aq'] == 1) { ?>
             
              <div id="table-wrapper">
              <div id="table-scroll">
             <table id="tbl_datatable_aq_component" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>Component</th>
                    <th style = 'outline: thin solid'>New Cases</th>
                    <th style = 'outline: thin solid'>AQ</th>
                    <th style = 'outline: thin solid'>Closure</th>
                 
                  </tr>
                </thead>
                 <tbody>
                 <?php 
                  $i = 1;
              
                 
                    foreach ($components as $key  => $component)
                    { 
                        switch ($component) {
                          case 'Address':
                            $component_link = "address";
                            break;
                          case 'Employment':
                            $component_link = "employment";
                            break;
                          case 'Education':
                            $component_link = "education";
                            break;  
                          case 'Reference':
                            $component_link = "reference";
                            break; 
                          case 'Court':
                            $component_link = "court_verificatiion";
                            break; 
                          case 'Global database':
                            $component_link = "global_database";
                            break; 
                          case 'PCC':
                            $component_link = "pcc";
                            break;        
                          case 'Identity':
                            $component_link = "identity";
                            break;
                          case 'Credit Report':
                            $component_link = "credit_report";
                            break; 
                          case 'Drugs':
                            $component_link = "drugs_narcotics";
                            break;    
                          default:
                             $component_link = "Not Found";
                            break;
                        }

                 
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>".$component."</td>";
                      echo  "<td><a href = '".ADMIN_SITE_URL.$component_link."#new_cases' target= '_blank' title = '$component'> <div id = 'new_".$key."'></div></a></td>";
                      echo  "<td><a href = '".ADMIN_SITE_URL.$component_link.'/'."approval_queue' target= '_blank' title = '$component'> <div id = 'aq_".$key."'></div></a></td>";
                      echo  "<td><a href = '".ADMIN_SITE_URL.$component_link.'/'."approval_queue#result_log_tabs' target= '_blank' title = '$component'> <div id = 'closure_".$key."'></div></a></td>";
                     
                      echo  "</tr>";
                                          
                    }
                    echo  "<tr>";
                    echo  "<td style = 'outline: thin solid'> Total </td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'new_total'></div></td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'aq_total'></div></td>"; 
                    echo  "<td style = 'outline: thin solid'><div id = 'closure_total'></div></td>"; 
                    echo  "</tr>";
                    
                    ?>
                   <tr>
                </tbody>
              </table>
              <?php  }else {
                    echo 'You have not permission to access this page';

              }?>
            </div>
           </div>
            
            </div>
          </div>
        </div>
      </div>
  
   
<script>  
var $ = jQuery;
$(document).ready(function() {
  
 
  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."reports/aq_component_details"; ?>', 
      data : '',
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($components as $key  => $component)
            { 
            ?>
                $("#new_<?php echo $key ?>").html('');
                $("#aq_<?php echo $key ?>").html('');
                $("#closure_<?php echo $key ?>").html('');

             
            <?php      
            }
            ?>

            $("#new_total").html('');
            $("#aq_total").html('');
            $("#closure_total").html('');
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;


            var total_new = 0;
            var total_aq = 0;
            var total_closure = 0;
              
        
            <?php
            foreach ($components as $key  => $component)
            { 
            ?>
           

               if(typeof(type.new_<?php echo $key ?>) != "undefined")
               { 
                  $("#new_<?php echo $key ?>").html(parseInt(type.new_<?php echo $key ?>));  
                  total_new += parseInt(type.new_<?php echo $key ?>); 
                
               }
             
               if(typeof(type.aq_<?php echo $key ?>) != "undefined")
               { 
                  $("#aq_<?php echo $key ?>").html(parseInt(type.aq_<?php echo $key ?>));  
                  total_aq += parseInt(type.aq_<?php echo $key ?>); 
                 
               }
             
               if(typeof(type.closure_<?php echo $key ?>) != "undefined")
               {
                  $("#closure_<?php echo $key ?>").html(parseInt(type.closure_<?php echo $key ?>));
                  total_closure += parseInt(type.closure_<?php echo $key ?>); 
                  
               } 
              
            
              /* if(wiped == "0" && insuffed == "0" && closeded == "0" && initiationed == "0")
               { 
                
                  $(".show_hide_<?php echo $key ?>").hide();
               
               }
               else
               {  
                  $(".show_hide_<?php echo $key ?>").show(); 
               } 
*/

            <?php      
            }               
            ?>
           
             
           $("#new_total").html(total_new);   


            $("#aq_total").html(total_aq);   

            $("#closure_total").html(total_closure);  

         

            
         }  
            
      }
   });

 

});

</script>