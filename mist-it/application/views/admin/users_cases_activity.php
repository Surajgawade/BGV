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
            <?php    if ($this->permission['access_report_list_cases'] == 1) { ?>
               <div class="row">
               <div class="col-sm-4 form-group">
                <?php
                  $month = array('0' => 'Select Month','01' => 'January','02' => 'Feb','03' => 'March','04' => 'April','05' => 'May','06' => 'June','07' => 'July','08' => 'August','09' => 'September','10' => 'Oct','11' => 'Nov','12' => 'Dec');
                    echo form_dropdown('month_user_cases',$month, set_value('month_user_cases',date('m')),'class="select2" id="month_user_cases"');?>
                </div>
                <div class="col-sm-4 form-group">
                  <?php
                  $year  = array('0' => 'Select Year','2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021','2022'=>'2022');
                   echo form_dropdown('year_user_cases', $year, set_value('year_user_cases',date('Y')), 'class="select2" id="year_user_cases"');
                   ?>
                </div> 

                 <div class="col-sm-4 form-group">
                     <input type="button" name="searchrecords_user_cases" id="searchrecords_user_cases" class="btn btn-md btn-info" value="Filter">
                  </div>
              </div>  


              <div id="table-wrapper">
              <div id="table-scroll" class="fixTableHead">
             
              <table id="tbl_datatable_users_cases" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>User</th>
                    <th style = 'outline: thin solid'>Status</th>
                    <th style = 'outline: thin solid'>Address</th>
                    <th style = 'outline: thin solid'>Employment</th>
                    <th style = 'outline: thin solid'>Education</th>
                    <th style = 'outline: thin solid'>Reference</th>
                    <th style = 'outline: thin solid'>Court</th>
                    <th style = 'outline: thin solid'>Global</th>
                    <th style = 'outline: thin solid'>PCC</th>
                    <th style = 'outline: thin solid'>Identity</th>
                    <th style = 'outline: thin solid'>Credit</th>
                    <th style = 'outline: thin solid'>Drugs</th>
                    <th style = 'outline: thin solid'>Total</th>
                  </tr>
                </thead>
                 <tbody>
                 <?php 
                  $i = 1;
                  unset($user_list[0]);
                    echo  "<tr >";
                   
                    foreach ($user_list as $key  => $user_lists)
                    { 
                     echo  "<tr  style = 'outline: thin solid'>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td rowspan='3' style = 'outline: thin solid'>".$user_lists."</td>";
                      echo  "<td style = 'outline: thin solid'>"."WIP"."</td>";
                      echo  "<td><div id = 'address_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'education_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'court_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'global_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_wips_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_wips_".$key."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_wips_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Insuff"."</td>";
                      echo  "<td><div id = 'address_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'education_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'court_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'global_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_insuff_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_insuff_".$key."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_insuff_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Closed"."</td>";
                      echo  "<td><div id = 'address_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'education_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'court_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'global_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_closeds_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_closeds_".$key."'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_closeds_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "</tr>";
                                        
                    }
                    echo  "</tr>";

                     echo  "<tr >";
                      echo  "<td rowspan='3' style = 'outline: thin solid'>Total</td>";
                      echo  "<td style = 'outline: thin solid'>"."WIP"."</td>";
                      echo  "<td><div id = 'address_wips_total'></div></td>";
                      echo  "<td><div id = 'employment_wips_total'></div></td>";
                      echo  "<td><div id = 'education_wips_total'></div></td>";
                      echo  "<td><div id = 'reference_wips_total'></div></td>";
                      echo  "<td><div id = 'court_wips_total'></div></td>";
                      echo  "<td><div id = 'global_wips_total'></div></td>";
                      echo  "<td><div id = 'pcc_wips_total'></div></td>";
                      echo  "<td><div id = 'identity_wips_total'></div></td>";
                      echo  "<td><div id = 'credit_wips_total'></div></td>";
                      echo  "<td><div id = 'drugs_wips_total'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_wips_total'></div></td>";
                      echo  "</tr>";
                      echo  "<tr>";
                      echo  "<td style = 'outline: thin solid'>"."Insuff"."</td>";
                      echo  "<td><div id = 'address_insuff_total'></div></td>";
                      echo  "<td><div id = 'employment_insuff_total'></div></td>";
                      echo  "<td><div id = 'education_insuff_total'></div></td>";
                      echo  "<td><div id = 'reference_insuff_total'></div></td>";
                      echo  "<td><div id = 'court_insuff_total'></div></td>";
                      echo  "<td><div id = 'global_insuff_total'></div></td>";
                      echo  "<td><div id = 'pcc_insuff_total'></div></td>";
                      echo  "<td><div id = 'identity_insuff_total'></div></td>";
                      echo  "<td><div id = 'credit_insuff_total'></div></td>";
                      echo  "<td><div id = 'drugs_insuff_total'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_insuff_total'></div></td>";
                      echo  "</tr>";
                      echo  "<tr>";
                      echo  "<td style = 'outline: thin solid'>"."Closed"."</td>";
                      echo  "<td ><div id = 'address_closeds_total'></div></td>";
                      echo  "<td><div id = 'employment_closeds_total'></div></td>";
                      echo  "<td><div id = 'education_closeds_total'></div></td>";
                      echo  "<td><div id = 'reference_closeds_total'></div></td>";
                      echo  "<td><div id = 'court_closeds_total'></div></td>";
                      echo  "<td><div id = 'global_closeds_total'></div></td>";
                      echo  "<td><div id = 'pcc_closeds_total'></div></td>";
                      echo  "<td><div id = 'identity_closeds_total'></div></td>";
                      echo  "<td><div id = 'credit_closeds_total'></div></td>";
                      echo  "<td><div id = 'drugs_closeds_total'></div></td>";
                      echo  "<td style = 'outline: thin solid'><div id = 'total_closeds_total'></div></td>";
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
  

   var month = $('#month_user_cases').val();
   var year =  $('#year_user_cases').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/user_cases_activity_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#address_wips_<?php echo $key ?>").html('');
                $("#employment_wips_<?php echo $key ?>").html('');
                $("#education_wips_<?php echo $key ?>").html('');
                $("#reference_wips_<?php echo $key ?>").html('');
                $("#court_wips_<?php echo $key ?>").html('');
                $("#global_wips_<?php echo $key ?>").html('');
                $("#pcc_wips_<?php echo $key ?>").html('');
                $("#identity_wips_<?php echo $key ?>").html('');
                $("#credit_wips_<?php echo $key ?>").html('');
                $("#drugs_wips_<?php echo $key ?>").html('');
                $("#total_wips_<?php echo $key ?>").html('');

                $("#address_insuff_<?php echo $key ?>").html('');
                $("#employment_insuff_<?php echo $key ?>").html('');
                $("#education_insuff_<?php echo $key ?>").html('');
                $("#reference_insuff_<?php echo $key ?>").html('');
                $("#court_insuff_<?php echo $key ?>").html('');
                $("#global_insuff_<?php echo $key ?>").html('');
                $("#pcc_insuff_<?php echo $key ?>").html('');
                $("#identity_insuff_<?php echo $key ?>").html('');
                $("#credit_insuff_<?php echo $key ?>").html('');
                $("#drugs_insuff_<?php echo $key ?>").html('');
                $("#total_insuff_<?php echo $key ?>").html('');

                $("#address_closeds_<?php echo $key ?>").html('');
                $("#employment_closeds_<?php echo $key ?>").html('');
                $("#education_closeds_<?php echo $key ?>").html('');
                $("#reference_closeds_<?php echo $key ?>").html('');
                $("#court_closeds_<?php echo $key ?>").html('');
                $("#global_closeds_<?php echo $key ?>").html('');
                $("#pcc_closeds_<?php echo $key ?>").html('');
                $("#identity_closeds_<?php echo $key ?>").html('');
                $("#credit_closeds_<?php echo $key ?>").html('');
                $("#drugs_closeds_<?php echo $key ?>").html('');
                $("#total_closeds_<?php echo $key ?>").html('');
               
                
            <?php      
            }
            ?>

                $("#address_wips_total").html('');
                $("#employment_wips_total").html('');
                $("#education_wips_total").html('');
                $("#reference_wips_total").html('');
                $("#court_wips_total").html('');
                $("#global_wips_total").html('');
                $("#pcc_wips_total").html('');
                $("#identity_wips_total").html('');
                $("#credit_wips_total").html('');
                $("#drugs_wips_total").html('');
                $("#total_wips_total").html('');

                $("#address_insuff_total").html('');
                $("#employment_insuff_total").html('');
                $("#education_insuff_total").html('');
                $("#reference_insuff_total").html('');
                $("#court_insuff_total").html('');
                $("#global_insuff_total").html('');
                $("#pcc_insuff_total").html('');
                $("#identity_insuff_total").html('');
                $("#credit_insuff_total").html('');
                $("#drugs_insuff_total").html('');
                $("#total_insuff_total").html('');

                $("#address_closeds_total").html('');
                $("#employment_closeds_total").html('');
                $("#education_closeds_total").html('');
                $("#reference_closeds_total").html('');
                $("#court_closeds_total").html('');
                $("#global_closeds_total").html('');
                $("#pcc_closeds_total").html('');
                $("#identity_closeds_total").html('');
                $("#credit_closeds_total").html('');
                $("#drugs_closeds_total").html('');
                $("#total_closeds_total").html('');
      
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;


            var address_wips_total = 0;
            var employment_wips_total = 0;
            var education_wips_total = 0;
            var reference_wips_total = 0;
            var court_wips_total = 0;
            var global_wips_total = 0;
            var pcc_wips_total = 0;
            var identity_wips_total = 0;
            var credit_wips_total = 0;
            var drugs_wips_total = 0;
            var total_wips_total = 0;

            var address_insuff_total = 0;
            var employment_insuff_total = 0;
            var education_insuff_total = 0;
            var reference_insuff_total = 0;
            var court_insuff_total = 0;
            var global_insuff_total = 0;
            var pcc_insuff_total = 0;
            var identity_insuff_total = 0;
            var credit_insuff_total = 0;
            var drugs_insuff_total = 0;
            var total_insuff_total = 0;

            var address_closeds_total = 0;
            var employment_closeds_total = 0;
            var education_closeds_total = 0;
            var reference_closeds_total = 0;
            var court_closeds_total = 0;
            var global_closeds_total = 0;
            var pcc_closeds_total = 0;
            var identity_closeds_total = 0;
            var credit_closeds_total = 0;
            var drugs_closeds_total = 0;
            var total_closeds_total = 0;
         
         
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
               var total_wip = 0;
               var total_insuff = 0;
               var total_closed = 0;

               if(typeof(type.address.wip_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_wips_<?php echo $key ?>").html(parseInt(type.address.wip_<?php echo $key ?>));  
                  total_wip += parseInt(type.address.wip_<?php echo $key ?>); 
                  address_wips_total += parseInt(type.address.wip_<?php echo $key ?>); 
               }
               if(typeof(type.employment.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_wips_<?php echo $key ?>").html(parseInt(type.employment.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.employment.wip_<?php echo $key ?>); 
                  employment_wips_total += parseInt(type.employment.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.education.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#education_wips_<?php echo $key ?>").html(parseInt(type.education.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.education.wip_<?php echo $key ?>); 
                  education_wips_total += parseInt(type.education.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.reference.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_wips_<?php echo $key ?>").html(parseInt(type.reference.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.reference.wip_<?php echo $key ?>); 
                  reference_wips_total += parseInt(type.reference.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.court.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#court_wips_<?php echo $key ?>").html(parseInt(type.court.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.court.wip_<?php echo $key ?>); 
                  court_wips_total += parseInt(type.court.wip_<?php echo $key ?>); 

               } 
               if(typeof(type.global.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#global_wips_<?php echo $key ?>").html(parseInt(type.global.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.global.wip_<?php echo $key ?>);
                  global_wips_total += parseInt(type.global.wip_<?php echo $key ?>); 
 
               } 
               if(typeof(type.pcc.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_wips_<?php echo $key ?>").html(parseInt(type.pcc.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.pcc.wip_<?php echo $key ?>); 
                  pcc_wips_total += parseInt(type.pcc.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.identity.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_wips_<?php echo $key ?>").html(parseInt(type.identity.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.identity.wip_<?php echo $key ?>); 
                  identity_wips_total += parseInt(type.identity.wip_<?php echo $key ?>); 
               }
               if(typeof(type.credit.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_wips_<?php echo $key ?>").html(parseInt(type.credit.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.credit.wip_<?php echo $key ?>); 
                  credit_wips_total += parseInt(type.credit.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.drugs.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_wips_<?php echo $key ?>").html(parseInt(type.drugs.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.drugs.wip_<?php echo $key ?>); 
                  drugs_wips_total += parseInt(type.drugs.wip_<?php echo $key ?>); 
               } 
               

               if(typeof(type.address.insufficiency_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_insuff_<?php echo $key ?>").html(parseInt(type.address.insufficiency_<?php echo $key ?>));  
                  total_insuff += parseInt(type.address.insufficiency_<?php echo $key ?>);  
                  address_insuff_total += parseInt(type.address.insufficiency_<?php echo $key ?>); 

               }
               if(typeof(type.employment.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_insuff_<?php echo $key ?>").html(parseInt(type.employment.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.employment.insufficiency_<?php echo $key ?>); 
                  employment_insuff_total += parseInt(type.employment.insufficiency_<?php echo $key ?>); 
 
               } 
               if(typeof(type.education.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#education_insuff_<?php echo $key ?>").html(parseInt(type.education.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.education.insufficiency_<?php echo $key ?>);  
                  education_insuff_total += parseInt(type.education.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.reference.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_insuff_<?php echo $key ?>").html(parseInt(type.reference.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.reference.insufficiency_<?php echo $key ?>); 
                  reference_insuff_total += parseInt(type.reference.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.court.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#court_insuff_<?php echo $key ?>").html(parseInt(type.court.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.court.insufficiency_<?php echo $key ?>); 
                  court_insuff_total += parseInt(type.court.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.global.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#global_insuff_<?php echo $key ?>").html(parseInt(type.global.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.global.insufficiency_<?php echo $key ?>); 
                  global_insuff_total += parseInt(type.global.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.pcc.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_insuff_<?php echo $key ?>").html(parseInt(type.pcc.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.pcc.insufficiency_<?php echo $key ?>); 
                  pcc_insuff_total += parseInt(type.pcc.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.identity.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_insuff_<?php echo $key ?>").html(parseInt(type.identity.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.identity.insufficiency_<?php echo $key ?>); 
                  identity_insuff_total += parseInt(type.identity.insufficiency_<?php echo $key ?>); 
               }
               if(typeof(type.credit.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_insuff_<?php echo $key ?>").html(parseInt(type.credit.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.credit.insufficiency_<?php echo $key ?>); 
                  credit_insuff_total += parseInt(type.credit.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.drugs.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_insuff_<?php echo $key ?>").html(parseInt(type.drugs.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.drugs.insufficiency_<?php echo $key ?>); 
                  drugs_insuff_total += parseInt(type.drugs.insufficiency_<?php echo $key ?>); 
               } 
               
               
               if(typeof(type.address.closed_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_closeds_<?php echo $key ?>").html(parseInt(type.address.closed_<?php echo $key ?>)); 
                  total_closed += parseInt(type.address.closed_<?php echo $key ?>);  
                  address_closeds_total += parseInt(type.address.closed_<?php echo $key ?>);  
               }
               if(typeof(type.employment.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_closeds_<?php echo $key ?>").html(parseInt(type.employment.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.employment.closed_<?php echo $key ?>);  
                  employment_closeds_total += parseInt(type.employment.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.education.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#education_closeds_<?php echo $key ?>").html(parseInt(type.education.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.education.closed_<?php echo $key ?>);  
                  education_closeds_total += parseInt(type.education.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.reference.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_closeds_<?php echo $key ?>").html(parseInt(type.reference.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.reference.closed_<?php echo $key ?>); 
                  reference_closeds_total += parseInt(type.reference.closed_<?php echo $key ?>); 
               } 
               if(typeof(type.court.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#court_closeds_<?php echo $key ?>").html(parseInt(type.court.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.court.closed_<?php echo $key ?>); 
                  court_closeds_total += parseInt(type.court.closed_<?php echo $key ?>); 
               } 
               if(typeof(type.global.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#global_closeds_<?php echo $key ?>").html(parseInt(type.global.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.global.closed_<?php echo $key ?>); 
                  global_closeds_total += parseInt(type.global.closed_<?php echo $key ?>); 
               } 
               if(typeof(type.pcc.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_closeds_<?php echo $key ?>").html(parseInt(type.pcc.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.pcc.closed_<?php echo $key ?>); 
                  pcc_closeds_total += parseInt(type.pcc.closed_<?php echo $key ?>); 
               } 
               if(typeof(type.identity.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_closeds_<?php echo $key ?>").html(parseInt(type.identity.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.identity.closed_<?php echo $key ?>); 
                  identity_closeds_total += parseInt(type.identity.closed_<?php echo $key ?>); 
               }
               if(typeof(type.credit.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_closeds_<?php echo $key ?>").html(parseInt(type.credit.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.credit.closed_<?php echo $key ?>);
                  credit_closeds_total += parseInt(type.credit.closed_<?php echo $key ?>); 
               } 
               if(typeof(type.drugs.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_closeds_<?php echo $key ?>").html(parseInt(type.drugs.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.drugs.closed_<?php echo $key ?>); 
                  drugs_closeds_total += parseInt(type.drugs.closed_<?php echo $key ?>); 
               } 
               total_wips_total += parseInt(total_wip); 
               total_insuff_total += parseInt(total_insuff); 
               total_closeds_total += parseInt(total_closed); 

               $("#total_wips_<?php echo $key ?>").html(total_wip);   

               $("#total_insuff_<?php echo $key ?>").html(total_insuff);  

               $("#total_closeds_<?php echo $key ?>").html(total_closed);   

               if(total_wip == "0" && total_insuff == "0" && total_closed == "0")
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
                $("#total_wips_total").html(total_wips_total);  
                $("#total_insuff_total").html(total_insuff_total);  
                $("#total_closeds_total").html(total_closeds_total);       

                $("#address_wips_total").html(address_wips_total);
                $("#employment_wips_total").html(employment_wips_total);
                $("#education_wips_total").html(education_wips_total);
                $("#reference_wips_total").html(reference_wips_total);
                $("#court_wips_total").html(court_wips_total);
                $("#global_wips_total").html(global_wips_total);
                $("#pcc_wips_total").html(pcc_wips_total);
                $("#identity_wips_total").html(identity_wips_total);
                $("#credit_wips_total").html(credit_wips_total);
                $("#drugs_wips_total").html(drugs_wips_total);
                $("#total_wips_total").html(total_wips_total);

                $("#address_insuff_total").html(address_insuff_total);
                $("#employment_insuff_total").html(employment_insuff_total);
                $("#education_insuff_total").html(education_insuff_total);
                $("#reference_insuff_total").html(reference_insuff_total);
                $("#court_insuff_total").html(court_insuff_total);
                $("#global_insuff_total").html(global_insuff_total);
                $("#pcc_insuff_total").html(pcc_insuff_total);
                $("#identity_insuff_total").html(identity_insuff_total);
                $("#credit_insuff_total").html(credit_insuff_total);
                $("#drugs_insuff_total").html(drugs_insuff_total);
                $("#total_insuff_total").html(total_insuff_total);

                $("#address_closeds_total").html(address_closeds_total);
                $("#employment_closeds_total").html(employment_closeds_total);
                $("#education_closeds_total").html(education_closeds_total);
                $("#reference_closeds_total").html(reference_closeds_total);
                $("#court_closeds_total").html(court_closeds_total);
                $("#global_closeds_total").html(global_closeds_total);
                $("#pcc_closeds_total").html(pcc_closeds_total);
                $("#identity_closeds_total").html(identity_closeds_total);
                $("#credit_closeds_total").html(credit_closeds_total);
                $("#drugs_closeds_total").html(drugs_closeds_total);
                $("#total_closeds_total").html(total_closeds_total);



            
         }  
            
      }
   });

 

   $('#searchrecords_user_cases').on('click', function() {
    
      var month = $('#month_user_cases').val();
      var year =  $('#year_user_cases').val();

      $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/user_cases_activity_details"; ?>', 
      data :  'month='+month+'&year='+year,
      dataType: 'json',
      beforeSend :function(){
        
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#address_wips_<?php echo $key ?>").html('');
                $("#employment_wips_<?php echo $key ?>").html('');
                $("#education_wips_<?php echo $key ?>").html('');
                $("#reference_wips_<?php echo $key ?>").html('');
                $("#court_wips_<?php echo $key ?>").html('');
                $("#global_wips_<?php echo $key ?>").html('');
                $("#pcc_wips_<?php echo $key ?>").html('');
                $("#identity_wips_<?php echo $key ?>").html('');
                $("#credit_wips_<?php echo $key ?>").html('');
                $("#drugs_wips_<?php echo $key ?>").html('');
                $("#total_wips_<?php echo $key ?>").html('');

                $("#address_insuff_<?php echo $key ?>").html('');
                $("#employment_insuff_<?php echo $key ?>").html('');
                $("#education_insuff_<?php echo $key ?>").html('');
                $("#reference_insuff_<?php echo $key ?>").html('');
                $("#court_insuff_<?php echo $key ?>").html('');
                $("#global_insuff_<?php echo $key ?>").html('');
                $("#pcc_insuff_<?php echo $key ?>").html('');
                $("#identity_insuff_<?php echo $key ?>").html('');
                $("#credit_insuff_<?php echo $key ?>").html('');
                $("#drugs_insuff_<?php echo $key ?>").html('');
                $("#total_insuff_<?php echo $key ?>").html('');

                $("#address_closeds_<?php echo $key ?>").html('');
                $("#employment_closeds_<?php echo $key ?>").html('');
                $("#education_closeds_<?php echo $key ?>").html('');
                $("#reference_closeds_<?php echo $key ?>").html('');
                $("#court_closeds_<?php echo $key ?>").html('');
                $("#global_closeds_<?php echo $key ?>").html('');
                $("#pcc_closeds_<?php echo $key ?>").html('');
                $("#identity_closeds_<?php echo $key ?>").html('');
                $("#credit_closeds_<?php echo $key ?>").html('');
                $("#drugs_closeds_<?php echo $key ?>").html('');
                $("#total_closeds_<?php echo $key ?>").html('');
               
                
            <?php      
            }
            ?>


                $("#address_wips_total").html('');
                $("#employment_wips_total").html('');
                $("#education_wips_total").html('');
                $("#reference_wips_total").html('');
                $("#court_wips_total").html('');
                $("#global_wips_total").html('');
                $("#pcc_wips_total").html('');
                $("#identity_wips_total").html('');
                $("#credit_wips_total").html('');
                $("#drugs_wips_total").html('');
                $("#total_wips_total").html('');

                $("#address_insuff_total").html('');
                $("#employment_insuff_total").html('');
                $("#education_insuff_total").html('');
                $("#reference_insuff_total").html('');
                $("#court_insuff_total").html('');
                $("#global_insuff_total").html('');
                $("#pcc_insuff_total").html('');
                $("#identity_insuff_total").html('');
                $("#credit_insuff_total").html('');
                $("#drugs_insuff_total").html('');
                $("#total_insuff_total").html('');

                $("#address_closeds_total").html('');
                $("#employment_closeds_total").html('');
                $("#education_closeds_total").html('');
                $("#reference_closeds_total").html('');
                $("#court_closeds_total").html('');
                $("#global_closeds_total").html('');
                $("#pcc_closeds_total").html('');
                $("#identity_closeds_total").html('');
                $("#credit_closeds_total").html('');
                $("#drugs_closeds_total").html('');
                $("#total_closeds_total").html('');
      
      },
      complete:function(){
        
      },
      success:function(responce)
      {
         
         if(responce.status == <?php echo SUCCESS_CODE; ?>)
         { 
            var type = responce.message;

            var address_wips_total = 0;
            var employment_wips_total = 0;
            var education_wips_total = 0;
            var reference_wips_total = 0;
            var court_wips_total = 0;
            var global_wips_total = 0;
            var pcc_wips_total = 0;
            var identity_wips_total = 0;
            var credit_wips_total = 0;
            var drugs_wips_total = 0;
            var total_wips_total = 0;

            var address_insuff_total = 0;
            var employment_insuff_total = 0;
            var education_insuff_total = 0;
            var reference_insuff_total = 0;
            var court_insuff_total = 0;
            var global_insuff_total = 0;
            var pcc_insuff_total = 0;
            var identity_insuff_total = 0;
            var credit_insuff_total = 0;
            var drugs_insuff_total = 0;
            var total_insuff_total = 0;

            var address_closeds_total = 0;
            var employment_closeds_total = 0;
            var education_closeds_total = 0;
            var reference_closeds_total = 0;
            var court_closeds_total = 0;
            var global_closeds_total = 0;
            var pcc_closeds_total = 0;
            var identity_closeds_total = 0;
            var credit_closeds_total = 0;
            var drugs_closeds_total = 0;
            var total_closeds_total = 0;
         
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
               var total_wip = 0;
               var total_insuff = 0;
               var total_closed = 0;

               if(typeof(type.address.wip_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_wips_<?php echo $key ?>").html(parseInt(type.address.wip_<?php echo $key ?>));  
                  total_wip += parseInt(type.address.wip_<?php echo $key ?>); 
                  address_wips_total += parseInt(type.address.wip_<?php echo $key ?>); 
               }
               if(typeof(type.employment.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_wips_<?php echo $key ?>").html(parseInt(type.employment.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.employment.wip_<?php echo $key ?>); 
                  employment_wips_total += parseInt(type.employment.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.education.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#education_wips_<?php echo $key ?>").html(parseInt(type.education.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.education.wip_<?php echo $key ?>); 
                  education_wips_total += parseInt(type.education.wip_<?php echo $key ?>); 

               } 
               if(typeof(type.reference.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_wips_<?php echo $key ?>").html(parseInt(type.reference.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.reference.wip_<?php echo $key ?>); 
                  reference_wips_total += parseInt(type.reference.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.court.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#court_wips_<?php echo $key ?>").html(parseInt(type.court.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.court.wip_<?php echo $key ?>); 
                  court_wips_total += parseInt(type.court.wip_<?php echo $key ?>); 

               } 
               if(typeof(type.global.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#global_wips_<?php echo $key ?>").html(parseInt(type.global.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.global.wip_<?php echo $key ?>); 
                  global_wips_total += parseInt(type.global.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.pcc.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_wips_<?php echo $key ?>").html(parseInt(type.pcc.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.pcc.wip_<?php echo $key ?>); 
                  pcc_wips_total += parseInt(type.pcc.wip_<?php echo $key ?>); 
               } 
               if(typeof(type.identity.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_wips_<?php echo $key ?>").html(parseInt(type.identity.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.identity.wip_<?php echo $key ?>);
                  identity_wips_total += parseInt(type.identity.wip_<?php echo $key ?>);  
               }
               if(typeof(type.credit.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_wips_<?php echo $key ?>").html(parseInt(type.credit.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.credit.wip_<?php echo $key ?>);
                  credit_wips_total += parseInt(type.credit.wip_<?php echo $key ?>);   
               } 
               if(typeof(type.drugs.wip_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_wips_<?php echo $key ?>").html(parseInt(type.drugs.wip_<?php echo $key ?>));
                  total_wip += parseInt(type.drugs.wip_<?php echo $key ?>);
                  drugs_wips_total += parseInt(type.drugs.wip_<?php echo $key ?>);  
               } 
               

               if(typeof(type.address.insufficiency_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_insuff_<?php echo $key ?>").html(parseInt(type.address.insufficiency_<?php echo $key ?>));  
                  total_insuff += parseInt(type.address.insufficiency_<?php echo $key ?>);  
                  address_insuff_total += parseInt(type.address.insufficiency_<?php echo $key ?>); 
               }
               if(typeof(type.employment.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_insuff_<?php echo $key ?>").html(parseInt(type.employment.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.employment.insufficiency_<?php echo $key ?>);  
                  employment_insuff_total += parseInt(type.employment.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.education.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#education_insuff_<?php echo $key ?>").html(parseInt(type.education.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.education.insufficiency_<?php echo $key ?>);  
                  education_insuff_total += parseInt(type.education.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.reference.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_insuff_<?php echo $key ?>").html(parseInt(type.reference.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.reference.insufficiency_<?php echo $key ?>); 
                  reference_insuff_total += parseInt(type.reference.insufficiency_<?php echo $key ?>); 

               } 
               if(typeof(type.court.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#court_insuff_<?php echo $key ?>").html(parseInt(type.court.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.court.insufficiency_<?php echo $key ?>);
                  court_insuff_total += parseInt(type.court.insufficiency_<?php echo $key ?>); 
 
               } 
               if(typeof(type.global.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#global_insuff_<?php echo $key ?>").html(parseInt(type.global.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.global.insufficiency_<?php echo $key ?>);
                  global_insuff_total += parseInt(type.global.insufficiency_<?php echo $key ?>); 
 
               } 
               if(typeof(type.pcc.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_insuff_<?php echo $key ?>").html(parseInt(type.pcc.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.pcc.insufficiency_<?php echo $key ?>); 
                  pcc_insuff_total += parseInt(type.pcc.insufficiency_<?php echo $key ?>); 
               } 
               if(typeof(type.identity.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_insuff_<?php echo $key ?>").html(parseInt(type.identity.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.identity.insufficiency_<?php echo $key ?>); 
                  identity_insuff_total += parseInt(type.identity.insufficiency_<?php echo $key ?>); 

               }
               if(typeof(type.credit.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_insuff_<?php echo $key ?>").html(parseInt(type.credit.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.credit.insufficiency_<?php echo $key ?>); 
                  credit_insuff_total += parseInt(type.credit.insufficiency_<?php echo $key ?>); 

               } 
               if(typeof(type.drugs.insufficiency_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_insuff_<?php echo $key ?>").html(parseInt(type.drugs.insufficiency_<?php echo $key ?>));
                  total_insuff += parseInt(type.drugs.insufficiency_<?php echo $key ?>); 
                  drugs_insuff_total += parseInt(type.drugs.insufficiency_<?php echo $key ?>); 
               } 
               
               
               if(typeof(type.address.closed_<?php echo $key ?>) != "undefined")
               { 
                  $("#address_closeds_<?php echo $key ?>").html(parseInt(type.address.closed_<?php echo $key ?>)); 
                  total_closed += parseInt(type.address.closed_<?php echo $key ?>); 
                  address_closeds_total += parseInt(type.address.closed_<?php echo $key ?>);  
 
               }
               if(typeof(type.employment.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#employment_closeds_<?php echo $key ?>").html(parseInt(type.employment.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.employment.closed_<?php echo $key ?>);  
                  employment_closeds_total += parseInt(type.employment.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.education.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#education_closeds_<?php echo $key ?>").html(parseInt(type.education.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.education.closed_<?php echo $key ?>);  
                  education_closeds_total += parseInt(type.education.closed_<?php echo $key ?>);  

               } 
               if(typeof(type.reference.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#reference_closeds_<?php echo $key ?>").html(parseInt(type.reference.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.reference.closed_<?php echo $key ?>); 
                  reference_closeds_total += parseInt(type.reference.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.court.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#court_closeds_<?php echo $key ?>").html(parseInt(type.court.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.court.closed_<?php echo $key ?>);
                  court_closeds_total += parseInt(type.court.closed_<?php echo $key ?>);   
               } 
               if(typeof(type.global.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#global_closeds_<?php echo $key ?>").html(parseInt(type.global.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.global.closed_<?php echo $key ?>); 
                  global_closeds_total += parseInt(type.global.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.pcc.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#pcc_closeds_<?php echo $key ?>").html(parseInt(type.pcc.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.pcc.closed_<?php echo $key ?>); 
                  pcc_closeds_total += parseInt(type.pcc.closed_<?php echo $key ?>);  
               } 
               if(typeof(type.identity.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#identity_closeds_<?php echo $key ?>").html(parseInt(type.identity.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.identity.closed_<?php echo $key ?>);
                  identity_closeds_total += parseInt(type.identity.closed_<?php echo $key ?>);   
               }
               if(typeof(type.credit.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#credit_closeds_<?php echo $key ?>").html(parseInt(type.credit.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.credit.closed_<?php echo $key ?>); 
                  credit_closeds_total += parseInt(type.credit.closed_<?php echo $key ?>);
               } 
               if(typeof(type.drugs.closed_<?php echo $key ?>) != "undefined")
               {
                  $("#drugs_closeds_<?php echo $key ?>").html(parseInt(type.drugs.closed_<?php echo $key ?>));
                  total_closed += parseInt(type.drugs.closed_<?php echo $key ?>); 
                  drugs_closeds_total += parseInt(type.drugs.closed_<?php echo $key ?>);
               } 

               total_wips_total += parseInt(total_wip); 
               total_insuff_total += parseInt(total_insuff); 
               total_closeds_total += parseInt(total_closed); 

               $("#total_wips_<?php echo $key ?>").html(total_wip);   

               $("#total_insuff_<?php echo $key ?>").html(total_insuff);  

               $("#total_closeds_<?php echo $key ?>").html(total_closed);   

               if(total_wip == "0" && total_insuff == "0" && total_closed == "0")
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

             $("#total_wips_total").html(total_wips_total);  
                $("#total_insuff_total").html(total_insuff_total);  
                $("#total_closeds_total").html(total_closeds_total);       

                $("#address_wips_total").html(address_wips_total);
                $("#employment_wips_total").html(employment_wips_total);
                $("#education_wips_total").html(education_wips_total);
                $("#reference_wips_total").html(reference_wips_total);
                $("#court_wips_total").html(court_wips_total);
                $("#global_wips_total").html(global_wips_total);
                $("#pcc_wips_total").html(pcc_wips_total);
                $("#identity_wips_total").html(identity_wips_total);
                $("#credit_wips_total").html(credit_wips_total);
                $("#drugs_wips_total").html(drugs_wips_total);
                $("#total_wips_total").html(total_wips_total);

                $("#address_insuff_total").html(address_insuff_total);
                $("#employment_insuff_total").html(employment_insuff_total);
                $("#education_insuff_total").html(education_insuff_total);
                $("#reference_insuff_total").html(reference_insuff_total);
                $("#court_insuff_total").html(court_insuff_total);
                $("#global_insuff_total").html(global_insuff_total);
                $("#pcc_insuff_total").html(pcc_insuff_total);
                $("#identity_insuff_total").html(identity_insuff_total);
                $("#credit_insuff_total").html(credit_insuff_total);
                $("#drugs_insuff_total").html(drugs_insuff_total);
                $("#total_insuff_total").html(total_insuff_total);

                $("#address_closeds_total").html(address_closeds_total);
                $("#employment_closeds_total").html(employment_closeds_total);
                $("#education_closeds_total").html(education_closeds_total);
                $("#reference_closeds_total").html(reference_closeds_total);
                $("#court_closeds_total").html(court_closeds_total);
                $("#global_closeds_total").html(global_closeds_total);
                $("#pcc_closeds_total").html(pcc_closeds_total);
                $("#identity_closeds_total").html(identity_closeds_total);
                $("#credit_closeds_total").html(credit_closeds_total);
                $("#drugs_closeds_total").html(drugs_closeds_total);
                $("#total_closeds_total").html(total_closeds_total);

            
         }  
            
      }
   });

   });

});

</script>