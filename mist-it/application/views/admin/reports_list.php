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

#table-scroll {
   overflow-y: auto;
   height: 500px;
}
#table-scroll thead th {
   position: sticky;
   top: 0;
}

</style>
<div class="content-page">
  <div class="content">
    <div class="container-fluid">
  
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Report - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>report">Report</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            </div>
          </div>
    </div>

    <div class="nav-tabs-custom">
      <ul class="nav nav-pills nav-justified">

        <?php  
          echo "<li class='nav-item waves-effect waves-light active'  role='presentation'><a  class = 'nav-link active' href='#user_activity_count' data-toggle='tab'>User Activity</a></li>";
          echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."reports/hourly_activity/' data-can_id='".$this->user_info['id']."'  data-tab_name='hourly_activity_count'><a class = 'nav-link' href='#hourly_activity_count' role='presentation'  data-toggle='tab'>Hourly Activity</a></li>";
          echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."reports/user_cases_activity/' data-can_id='".$this->user_info['id']."' data-tab_name='users_cases_count'><a class = 'nav-link' href='#users_cases_count' role='presentation'  data-toggle='tab'>User Cases</a></li>";
          echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation'  data-url='".ADMIN_SITE_URL."reports/client_allocation/' data-can_id='".$this->user_info['id']."' data-tab_name='client_allocation_count'><a class = 'nav-link' href='#client_allocation_count' role='presentation'  data-toggle='tab'>Client Allocation</a></li>";
          echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation'  data-url='".ADMIN_SITE_URL."reports/vendor_allocation/' data-can_id='".$this->user_info['id']."' data-tab_name='vendor_allocation_count'><a class = 'nav-link' href='#vendor_allocation_count' role='presentation'  data-toggle='tab'>Vendor Allocation</a></li>";
           echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation'  data-url='".ADMIN_SITE_URL."reports/aq_component/' data-can_id='".$this->user_info['id']."' data-tab_name='aq_components'><a class = 'nav-link' href='#aq_components' role='presentation'  data-toggle='tab'>AQ</a></li>";


        ?>
      </ul>
    </div>

   <div class="tab-content">
    <div id="user_activity_count" class="tab-pane  active">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3 form-group">
                  <input type="text" name="start_date" id="start_date" class="form-control myDatepicker" placeholder="Date Range" data-date-format="dd-mm-yyyy" value= "<?php echo date('d-m-Y'); ?>">
                </div>
                <div class="col-sm-3 form-group">
                  <input type="text" name="end_date" id="end_date" class="form-control myDatepicker" placeholder="Date Range" data-date-format="dd-mm-yyyy" value= "<?php echo date('d-m-Y'); ?>">
                </div>

                <div class="col-sm-4 form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                </div>
              </div>

              <div id="table-wrapper">
              <div id="table-scroll">
             
              <table id="tbl_datatable_user_activity" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th style = 'outline: thin solid'>User</th>
                    <th style = 'outline: thin solid'>Type</th>
                    <th style = 'outline: thin solid'>Candidate</th>
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
                    <th style = 'outline: thin solid'>Final QC</th>
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
                      echo  "<td rowspan='6' style = 'outline: thin solid'>".$user_lists."<br>
                       <div id = 'total_all_".$key."'></div></td>";
                      echo  "<td style = 'outline: thin solid'>"."Add"."</td>";
                      echo  "<td><div id = 'candidate_add_".$key."'></div></td>";
                      echo  "<td><div id = 'address_add_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_add_".$key."'></div></td>";
                      echo  "<td><div id = 'education_add_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_add_".$key."'></div></td>";
                      echo  "<td><div id = 'court_add_".$key."'></div></td>";
                      echo  "<td><div id = 'global_add_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_add_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_add_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_add_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_add_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'total_add_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."WIP"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'address_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'education_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'court_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'global_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_wip_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_wip_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'total_wip_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Insufficiency"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'address_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'education_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'court_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'global_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_insufficiency_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_insufficiency_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'total_insufficiency_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Insufficiency Cleared"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'address_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'education_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'court_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'global_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_cleared_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_cleared_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'total_cleared_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Closed"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'address_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'employment_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'education_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'reference_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'court_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'global_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'pcc_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'identity_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'credit_closed_".$key."'></div></td>";
                      echo  "<td><div id = 'drugs_closed_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'total_closed_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "<tr class = show_hide_".$key.">";
                      echo  "<td style = 'outline: thin solid'>"."Approved"."</td>";
                      echo  "<td><div id = 'candidate_approved_".$key."'></div></td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td>"."-"."</td>";
                      echo  "<td><div id = 'final_qc_approved_".$key."'></div></td>";
                      echo  "</tr>";
                      echo  "</tr>";
                                        
                    }
                    echo  "</tr>";
                    
                    ?>
                   <tr>
                </tbody>
              </table>

              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  

    <div id="hourly_activity_count" class="tab-pane fade in">

     </div>
      <div id="users_cases_count" class="tab-pane fade in">

      </div>
      <div id="client_allocation_count" class="tab-pane fade in" >

      </div>
      <div id="vendor_allocation_count" class="tab-pane fade in" >
  
      </div>

      <div id="aq_components" class="tab-pane fade in" >
  
      </div>
      

 
</div>
</div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

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
  

   var start_date = $('#start_date').val();
   var end_date =  $('#end_date').val();

   <?php    if ($this->permission['access_report_list_user'] == 1) { ?>

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/user_activity_details"; ?>', 
      data : 'start_date='+start_date+'&end_date='+end_date,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#candidate_add_<?php echo $key ?>").html('');
                $("#address_add_<?php echo $key ?>").html('');
                $("#employment_add_<?php echo $key ?>").html('');
                $("#education_add_<?php echo $key ?>").html('');
                $("#reference_add_<?php echo $key ?>").html('');
                $("#court_add_<?php echo $key ?>").html('');
                $("#global_add_<?php echo $key ?>").html('');
                $("#pcc_add_<?php echo $key ?>").html('');
                $("#identity_add_<?php echo $key ?>").html('');
                $("#credit_add_<?php echo $key ?>").html('');
                $("#drugs_add_<?php echo $key ?>").html('');
                $("#total_add_<?php echo $key ?>").html('');  
                $("#address_wip_<?php echo $key ?>").html('');
                $("#employment_wip_<?php echo $key ?>").html('');
                $("#education_wip_<?php echo $key ?>").html('');
                $("#reference_wip_<?php echo $key ?>").html('');
                $("#court_wip_<?php echo $key ?>").html('');
                $("#global_wip_<?php echo $key ?>").html('');
                $("#pcc_wip_<?php echo $key ?>").html('');
                $("#identity_wip_<?php echo $key ?>").html('');
                $("#credit_wip_<?php echo $key ?>").html('');
                $("#drugs_wip_<?php echo $key ?>").html('');
                $("#total_wip_<?php echo $key ?>").html('');   
                $("#address_insufficiency_<?php echo $key ?>").html('');
                $("#employment_insufficiency_<?php echo $key ?>").html('');
                $("#education_insufficiency_<?php echo $key ?>").html('');
                $("#reference_insufficiency_<?php echo $key ?>").html('');
                $("#court_insufficiency_<?php echo $key ?>").html('');
                $("#global_insufficiency_<?php echo $key ?>").html('');
                $("#pcc_insufficiency_<?php echo $key ?>").html('');
                $("#identity_insufficiency_<?php echo $key ?>").html('');
                $("#credit_insufficiency_<?php echo $key ?>").html('');
                $("#drugs_insufficiency_<?php echo $key ?>").html('');
                $("#total_insufficiency_<?php echo $key ?>").html('');           
                $("#address_cleared_<?php echo $key ?>").html('');
                $("#employment_cleared_<?php echo $key ?>").html('');
                $("#education_cleared_<?php echo $key ?>").html('');
                $("#reference_cleared_<?php echo $key ?>").html('');
                $("#court_cleared_<?php echo $key ?>").html('');
                $("#global_cleared_<?php echo $key ?>").html('');
                $("#pcc_cleared_<?php echo $key ?>").html('');
                $("#identity_cleared_<?php echo $key ?>").html('');
                $("#credit_cleared_<?php echo $key ?>").html('');
                $("#drugs_cleared_<?php echo $key ?>").html('');
                $("#total_cleared_<?php echo $key ?>").html('');  
                $("#address_closed_<?php echo $key ?>").html('');
                $("#employment_closed_<?php echo $key ?>").html('');
                $("#education_closed_<?php echo $key ?>").html('');
                $("#reference_closed_<?php echo $key ?>").html('');
                $("#court_closed_<?php echo $key ?>").html('');
                $("#global_closed_<?php echo $key ?>").html('');
                $("#pcc_closed_<?php echo $key ?>").html('');
                $("#identity_closed_<?php echo $key ?>").html('');
                $("#credit_closed_<?php echo $key ?>").html('');
                $("#drugs_closed_<?php echo $key ?>").html('');
                $("#total_closed_<?php echo $key ?>").html('');    
                $("#candidate_approved_<?php echo $key ?>").html('');
                $("#final_qc_approved_<?php echo $key ?>").html('');
                $("#total_all_<?php echo $key ?>").html('');              
                
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
               var total_add = 0;
               var total_wip = 0;
               var total_insufficiency = 0;
               var total_cleared = 0;
               var total_closed = 0;
               var total_approved = 0;
               var total_all = 0;

               if(typeof(type.candidate_add_<?php echo $key ?>) != "undefined")
               {
                  candidate_add_<?php echo $key ?> =  parseInt(type.candidate_add_<?php echo $key ?>);
                  $("#candidate_add_<?php echo $key ?>").html(parseInt(type.candidate_add_<?php echo $key ?>));
                  total_add += parseInt(type.candidate_add_<?php echo $key ?>);
               } 
               if(typeof(type.candidate_approved_<?php echo $key ?>) != "undefined")
               {
                  candidate_approved_<?php echo $key ?> =  parseInt(type.candidate_approved_<?php echo $key ?>);
                  $("#candidate_approved_<?php echo $key ?>").html(parseInt(type.candidate_approved_<?php echo $key ?>));
                 
               } 
               if(typeof(type.candidate_approved_<?php echo $key ?>) != "undefined")
               {
                  candidate_approved_<?php echo $key ?> =  parseInt(type.candidate_approved_<?php echo $key ?>);
                  $("#final_qc_approved_<?php echo $key ?>").html(parseInt(type.candidate_approved_<?php echo $key ?>));
                  total_approved += parseInt(type.candidate_approved_<?php echo $key ?>); 
               } 
              

               if(typeof(type.address_add_<?php echo $key ?>) != "undefined")
               {
                  address_add_<?php echo $key ?> =  parseInt(type.address_add_<?php echo $key ?>);
                  $("#address_add_<?php echo $key ?>").html(parseInt(type.address_add_<?php echo $key ?>));
                  total_add += parseInt(type.address_add_<?php echo $key ?>); 
               } 
               if(typeof(type.employment_add_<?php echo $key ?>) != "undefined")
               {
                  employment_add_<?php echo $key ?> =  parseInt(type.employment_add_<?php echo $key ?>);
                  $("#employment_add_<?php echo $key ?>").html(parseInt(type.employment_add_<?php echo $key ?>));
                  total_add += parseInt(type.employment_add_<?php echo $key ?>); 
               } 
               if(typeof(type.education_add_<?php echo $key ?>) != "undefined")
               {
                  education_add_<?php echo $key ?> =  parseInt(type.education_add_<?php echo $key ?>);
                  $("#education_add_<?php echo $key ?>").html(parseInt(type.education_add_<?php echo $key ?>));
                  total_add += parseInt(type.education_add_<?php echo $key ?>);   
               } 
               if(typeof(type.reference_add_<?php echo $key ?>) != "undefined")
               {
                  reference_add_<?php echo $key ?> =  parseInt(type.reference_add_<?php echo $key ?>);
                  $("#reference_add_<?php echo $key ?>").html(parseInt(type.reference_add_<?php echo $key ?>));
                  total_add += parseInt(type.reference_add_<?php echo $key ?>);     
               } 
               if(typeof(type.court_verification_add_<?php echo $key ?>) != "undefined")
               {
                  court_verification_add_<?php echo $key ?> =  parseInt(type.court_verification_add_<?php echo $key ?>);
                  $("#court_add_<?php echo $key ?>").html(parseInt(type.court_verification_add_<?php echo $key ?>));
                  total_add += parseInt(type.court_verification_add_<?php echo $key ?>);     
               } 
               if(typeof(type.global_database_add_<?php echo $key ?>) != "undefined")
               {
                  global_database_add_<?php echo $key ?> =  parseInt(type.global_database_add_<?php echo $key ?>);
                  $("#global_add_<?php echo $key ?>").html(parseInt(type.global_database_add_<?php echo $key ?>));
                  total_add += parseInt(type.global_database_add_<?php echo $key ?>);  
               }
               if(typeof(type.pcc_add_<?php echo $key ?>) != "undefined")
               {
                  pcc_add_<?php echo $key ?> =  parseInt(type.pcc_add_<?php echo $key ?>);
                  $("#pcc_add_<?php echo $key ?>").html(parseInt(type.pcc_add_<?php echo $key ?>));
                  total_add += parseInt(type.pcc_add_<?php echo $key ?>);   
               }
               if(typeof(type.identity_add_<?php echo $key ?>) != "undefined")
               {
                  identity_add_<?php echo $key ?> =  parseInt(type.identity_add_<?php echo $key ?>);
                  $("#identity_add_<?php echo $key ?>").html(parseInt(type.identity_add_<?php echo $key ?>));
                  total_add += parseInt(type.identity_add_<?php echo $key ?>);   
               }
               if(typeof(type.credit_report_add_<?php echo $key ?>) != "undefined")
               {
                  credit_report_add_<?php echo $key ?> =  parseInt(type.credit_report_add_<?php echo $key ?>);
                  $("#credit_add_<?php echo $key ?>").html(parseInt(type.credit_report_add_<?php echo $key ?>));
                  total_add += parseInt(type.credit_report_add_<?php echo $key ?>);   
 
               }
               if(typeof(type.drugs_verification_add_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_add_<?php echo $key ?> =  parseInt(type.drugs_verification_add_<?php echo $key ?>);
                  $("#drugs_add_<?php echo $key ?>").html(parseInt(type.drugs_verification_add_<?php echo $key ?>));
                  total_add += parseInt(type.drugs_verification_add_<?php echo $key ?>);   

               } 

               if(typeof(type.address_wip_<?php echo $key ?>) != "undefined")
               {
                  address_wip_<?php echo $key ?> =  parseInt(type.address_wip_<?php echo $key ?>);
                  $("#address_wip_<?php echo $key ?>").html(parseInt(type.address_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.address_wip_<?php echo $key ?>);   
 
               } 
               if(typeof(type.employment_wip_<?php echo $key ?>) != "undefined")
               {
                  employment_wip_<?php echo $key ?> =  parseInt(type.employment_wip_<?php echo $key ?>);
                  $("#employment_wip_<?php echo $key ?>").html(parseInt(type.employment_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.employment_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.education_wip_<?php echo $key ?>) != "undefined")
               {
                  education_wip_<?php echo $key ?> =  parseInt(type.education_wip_<?php echo $key ?>);
                  $("#education_wip_<?php echo $key ?>").html(parseInt(type.education_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.education_wip_<?php echo $key ?>); 
               } 
               if(typeof(type.reference_wip_<?php echo $key ?>) != "undefined")
               {
                  reference_wip_<?php echo $key ?> =  parseInt(type.reference_wip_<?php echo $key ?>);
                  $("#reference_wip_<?php echo $key ?>").html(parseInt(type.reference_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.reference_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.court_verification_wip_<?php echo $key ?>) != "undefined")
               {
                  court_verification_wip_<?php echo $key ?> =  parseInt(type.court_verification_wip_<?php echo $key ?>);
                  $("#court_wip_<?php echo $key ?>").html(parseInt(type.court_verification_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.court_verification_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.global_database_wip_<?php echo $key ?>) != "undefined")
               {
                  global_database_wip_<?php echo $key ?> =  parseInt(type.global_database_wip_<?php echo $key ?>);
                  $("#global_wip_<?php echo $key ?>").html(parseInt(type.global_database_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.global_database_wip_<?php echo $key ?>);   

               }
               if(typeof(type.pcc_wip_<?php echo $key ?>) != "undefined")
               {
                  pcc_wip_<?php echo $key ?> =  parseInt(type.pcc_wip_<?php echo $key ?>);
                  $("#pcc_wip_<?php echo $key ?>").html(parseInt(type.pcc_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.pcc_wip_<?php echo $key ?>);   

               }
               if(typeof(type.identity_wip_<?php echo $key ?>) != "undefined")
               {
                  identity_wip_<?php echo $key ?> =  parseInt(type.identity_wip_<?php echo $key ?>);
                  $("#identity_wip_<?php echo $key ?>").html(parseInt(type.identity_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.identity_wip_<?php echo $key ?>);    
               }
               if(typeof(type.credit_report_wip_<?php echo $key ?>) != "undefined")
               {
                  credit_report_wip_<?php echo $key ?> =  parseInt(type.credit_report_wip_<?php echo $key ?>);
                  $("#credit_wip_<?php echo $key ?>").html(parseInt(type.credit_report_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.credit_report_wip_<?php echo $key ?>);    
 
               }
               if(typeof(type.drugs_verification_wip_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_wip_<?php echo $key ?> =  parseInt(type.drugs_verification_wip_<?php echo $key ?>);
                  $("#drugs_wip_<?php echo $key ?>").html(parseInt(type.drugs_verification_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.drugs_verification_wip_<?php echo $key ?>);    

               }

               if(typeof(type.address_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  address_insufficiency_<?php echo $key ?> =  parseInt(type.address_insufficiency_<?php echo $key ?>);
                  $("#address_insufficiency_<?php echo $key ?>").html(parseInt(type.address_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.address_insufficiency_<?php echo $key ?>);
               } 
               if(typeof(type.employment_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  employment_insufficiency_<?php echo $key ?> =  parseInt(type.employment_insufficiency_<?php echo $key ?>);
                  $("#employment_insufficiency_<?php echo $key ?>").html(parseInt(type.employment_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.employment_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.education_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  education_insufficiency_<?php echo $key ?> =  parseInt(type.education_insufficiency_<?php echo $key ?>);
                  $("#education_insufficiency_<?php echo $key ?>").html(parseInt(type.education_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.education_insufficiency_<?php echo $key ?>);
 
               } 
               if(typeof(type.reference_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  reference_insufficiency_<?php echo $key ?> =  parseInt(type.reference_insufficiency_<?php echo $key ?>);
                  $("#reference_insufficiency_<?php echo $key ?>").html(parseInt(type.reference_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.reference_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.court_verification_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  court_verification_insufficiency_<?php echo $key ?> =  parseInt(type.court_verification_insufficiency_<?php echo $key ?>);
                  $("#court_insufficiency_<?php echo $key ?>").html(parseInt(type.court_verification_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.court_verification_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.global_database_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  global_database_insufficiency_<?php echo $key ?> =  parseInt(type.global_database_insufficiency_<?php echo $key ?>);
                  $("#global_insufficiency_<?php echo $key ?>").html(parseInt(type.global_database_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.global_database_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.pcc_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  pcc_insufficiency_<?php echo $key ?> =  parseInt(type.pcc_insufficiency_<?php echo $key ?>);
                  $("#pcc_insufficiency_<?php echo $key ?>").html(parseInt(type.pcc_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.pcc_insufficiency_<?php echo $key ?>);
               }
               if(typeof(type.identity_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  identity_insufficiency_<?php echo $key ?> =  parseInt(type.identity_insufficiency_<?php echo $key ?>);
                  $("#identity_insufficiency_<?php echo $key ?>").html(parseInt(type.identity_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.identity_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  credit_report_insufficiency_<?php echo $key ?> =  parseInt(type.credit_report_insufficiency_<?php echo $key ?>);
                  $("#credit_insufficiency_<?php echo $key ?>").html(parseInt(type.credit_report_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.credit_report_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.drugs_verification_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_insufficiency_<?php echo $key ?> =  parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>);
                  $("#drugs_insufficiency_<?php echo $key ?>").html(parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>);

               } 

               if(typeof(type.address_cleared_<?php echo $key ?>) != "undefined")
               {
                  address_cleared_<?php echo $key ?> =  parseInt(type.address_cleared_<?php echo $key ?>);
                  $("#address_cleared_<?php echo $key ?>").html(parseInt(type.address_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.address_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.employment_cleared_<?php echo $key ?>) != "undefined")
               {
                  employment_cleared_<?php echo $key ?> =  parseInt(type.employment_cleared_<?php echo $key ?>);
                  $("#employment_cleared_<?php echo $key ?>").html(parseInt(type.employment_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.employment_cleared_<?php echo $key ?>);
               } 
               if(typeof(type.education_cleared_<?php echo $key ?>) != "undefined")
               {
                  education_cleared_<?php echo $key ?> =  parseInt(type.education_cleared_<?php echo $key ?>);
                  $("#education_cleared_<?php echo $key ?>").html(parseInt(type.education_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.education_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.reference_cleared_<?php echo $key ?>) != "undefined")
               {
                  reference_cleared_<?php echo $key ?> =  parseInt(type.reference_cleared_<?php echo $key ?>);
                  $("#reference_cleared_<?php echo $key ?>").html(parseInt(type.reference_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.reference_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.court_verification_cleared_<?php echo $key ?>) != "undefined")
               {
                  court_verification_cleared_<?php echo $key ?> =  parseInt(type.court_verification_cleared_<?php echo $key ?>);
                  $("#court_cleared_<?php echo $key ?>").html(parseInt(type.court_verification_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.court_verification_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.global_database_cleared_<?php echo $key ?>) != "undefined")
               {
                  global_database_cleared_<?php echo $key ?> =  parseInt(type.global_database_cleared_<?php echo $key ?>);
                  $("#global_cleared_<?php echo $key ?>").html(parseInt(type.global_database_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.global_database_cleared_<?php echo $key ?>);

               }
               if(typeof(type.pcc_cleared_<?php echo $key ?>) != "undefined")
               {
                  pcc_cleared_<?php echo $key ?> =  parseInt(type.pcc_cleared_<?php echo $key ?>);
                  $("#pcc_cleared_<?php echo $key ?>").html(parseInt(type.pcc_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.pcc_cleared_<?php echo $key ?>);

               }
               if(typeof(type.identity_cleared_<?php echo $key ?>) != "undefined")
               {
                  identity_cleared_<?php echo $key ?> =  parseInt(type.identity_cleared_<?php echo $key ?>);
                  $("#identity_cleared_<?php echo $key ?>").html(parseInt(type.identity_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.identity_cleared_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_cleared_<?php echo $key ?>) != "undefined")
               {
                  credit_report_cleared_<?php echo $key ?> =  parseInt(type.credit_report_cleared_<?php echo $key ?>);
                  $("#credit_cleared_<?php echo $key ?>").html(parseInt(type.credit_report_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.credit_report_cleared_<?php echo $key ?>);
               }
               if(typeof(type.drugs_verification_cleared_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_cleared_<?php echo $key ?> =  parseInt(type.drugs_verification_cleared_<?php echo $key ?>);
                  $("#drugs_cleared_<?php echo $key ?>").html(parseInt(type.drugs_verification_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.drugs_verification_cleared_<?php echo $key ?>);
 
               }
 
               if(typeof(type.address_closed_<?php echo $key ?>) != "undefined")
               {
                  address_closed_<?php echo $key ?> =  parseInt(type.address_closed_<?php echo $key ?>);
                  $("#address_closed_<?php echo $key ?>").html(parseInt(type.address_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.address_closed_<?php echo $key ?>);
 
               } 
               if(typeof(type.employment_closed_<?php echo $key ?>) != "undefined")
               {
                  employment_closed_<?php echo $key ?> =  parseInt(type.employment_closed_<?php echo $key ?>);
                  $("#employment_closed_<?php echo $key ?>").html(parseInt(type.employment_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.employment_closed_<?php echo $key ?>);
               } 
               if(typeof(type.education_closed_<?php echo $key ?>) != "undefined")
               {
                  education_closed_<?php echo $key ?> =  parseInt(type.education_closed_<?php echo $key ?>);
                  $("#education_closed_<?php echo $key ?>").html(parseInt(type.education_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.education_closed_<?php echo $key ?>);

               } 
               if(typeof(type.reference_closed_<?php echo $key ?>) != "undefined")
               {
                  reference_closed_<?php echo $key ?> =  parseInt(type.reference_closed_<?php echo $key ?>);
                  $("#reference_closed_<?php echo $key ?>").html(parseInt(type.reference_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.reference_closed_<?php echo $key ?>);
  
               } 
               if(typeof(type.court_verification_closed_<?php echo $key ?>) != "undefined")
               {
                  court_verification_closed_<?php echo $key ?> =  parseInt(type.court_verification_closed_<?php echo $key ?>);
                  $("#court_closed_<?php echo $key ?>").html(parseInt(type.court_verification_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.court_verification_closed_<?php echo $key ?>);
               } 
               if(typeof(type.global_database_closed_<?php echo $key ?>) != "undefined")
               {
                  global_database_closed_<?php echo $key ?> =  parseInt(type.global_database_closed_<?php echo $key ?>);
                  $("#global_closed_<?php echo $key ?>").html(parseInt(type.global_database_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.global_database_closed_<?php echo $key ?>);

               }
               if(typeof(type.pcc_closed_<?php echo $key ?>) != "undefined")
               {
                  pcc_closed_<?php echo $key ?> =  parseInt(type.pcc_closed_<?php echo $key ?>);
                  $("#pcc_closed_<?php echo $key ?>").html(parseInt(type.pcc_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.pcc_closed_<?php echo $key ?>);

               }
               if(typeof(type.identity_closed_<?php echo $key ?>) != "undefined")
               {
                  identity_closed_<?php echo $key ?> =  parseInt(type.identity_closed_<?php echo $key ?>);
                  $("#identity_closed_<?php echo $key ?>").html(parseInt(type.identity_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.identity_closed_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_report_closed_<?php echo $key ?>) != "undefined")
               {
                  credit_report_closed_<?php echo $key ?> =  parseInt(type.credit_report_closed_<?php echo $key ?>);
                  $("#credit_closed_<?php echo $key ?>").html(parseInt(type.credit_report_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.credit_report_closed_<?php echo $key ?>);

               }
               if(typeof(type.drugs_verification_closed_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_closed_<?php echo $key ?> =  parseInt(type.drugs_verification_closed_<?php echo $key ?>);
                  $("#drugs_closed_<?php echo $key ?>").html(parseInt(type.drugs_verification_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.drugs_verification_closed_<?php echo $key ?>);

               }
               
               $("#total_add_<?php echo $key ?>").html(total_add);
               $("#total_wip_<?php echo $key ?>").html(total_wip);
               $("#total_insufficiency_<?php echo $key ?>").html(total_insufficiency);
               $("#total_cleared_<?php echo $key ?>").html(total_cleared);
               $("#total_closed_<?php echo $key ?>").html(total_closed);
               
               total_all = total_all + total_add + total_wip +  total_insufficiency + total_cleared + total_closed + total_approved; 
               $("#total_all_<?php echo $key ?>").html('('+total_all+')');

               if(total_all == "0")
               { 
                
                  if(typeof(type.candidate_approved_<?php echo $key ?>) == "undefined" || type.candidate_approved_<?php echo $key ?> == "0")
                  { 
                     $(".show_hide_<?php echo $key ?>").hide();
                  }
                  else{
                    
                     $(".show_hide_<?php echo $key ?>").show();   
                  }
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

   <?php  }else {?>
        $('#tbl_datatable_user_activity').hide();
        alert('You have not permission to access this page');

      <?php }?>

$('#searchrecords').on('click', function() {
   var start_date = $('#start_date').val();
   var end_date =  $('#end_date').val();

  $.ajax({
      type:'POST',
      url: '<?php echo ADMIN_SITE_URL."Reports/user_activity_details"; ?>', 
      data : 'start_date='+start_date+'&end_date='+end_date,
      dataType: 'json',
      beforeSend :function(){
          
            <?php
            foreach ($user_list as $key  => $user_lists)
            { 
            ?>
            
                $("#candidate_add_<?php echo $key ?>").html('');
                $("#address_add_<?php echo $key ?>").html('');
                $("#employment_add_<?php echo $key ?>").html('');
                $("#education_add_<?php echo $key ?>").html('');
                $("#reference_add_<?php echo $key ?>").html('');
                $("#court_add_<?php echo $key ?>").html('');
                $("#global_add_<?php echo $key ?>").html('');
                $("#pcc_add_<?php echo $key ?>").html('');
                $("#identity_add_<?php echo $key ?>").html('');
                $("#credit_add_<?php echo $key ?>").html('');
                $("#drugs_add_<?php echo $key ?>").html('');
                $("#total_add_<?php echo $key ?>").html('');  
                $("#address_wip_<?php echo $key ?>").html('');
                $("#employment_wip_<?php echo $key ?>").html('');
                $("#education_wip_<?php echo $key ?>").html('');
                $("#reference_wip_<?php echo $key ?>").html('');
                $("#court_wip_<?php echo $key ?>").html('');
                $("#global_wip_<?php echo $key ?>").html('');
                $("#pcc_wip_<?php echo $key ?>").html('');
                $("#identity_wip_<?php echo $key ?>").html('');
                $("#credit_wip_<?php echo $key ?>").html('');
                $("#drugs_wip_<?php echo $key ?>").html('');
                $("#total_wip_<?php echo $key ?>").html('');   
                $("#address_insufficiency_<?php echo $key ?>").html('');
                $("#employment_insufficiency_<?php echo $key ?>").html('');
                $("#education_insufficiency_<?php echo $key ?>").html('');
                $("#reference_insufficiency_<?php echo $key ?>").html('');
                $("#court_insufficiency_<?php echo $key ?>").html('');
                $("#global_insufficiency_<?php echo $key ?>").html('');
                $("#pcc_insufficiency_<?php echo $key ?>").html('');
                $("#identity_insufficiency_<?php echo $key ?>").html('');
                $("#credit_insufficiency_<?php echo $key ?>").html('');
                $("#drugs_insufficiency_<?php echo $key ?>").html('');
                $("#total_insufficiency_<?php echo $key ?>").html('');           
                $("#address_cleared_<?php echo $key ?>").html('');
                $("#employment_cleared_<?php echo $key ?>").html('');
                $("#education_cleared_<?php echo $key ?>").html('');
                $("#reference_cleared_<?php echo $key ?>").html('');
                $("#court_cleared_<?php echo $key ?>").html('');
                $("#global_cleared_<?php echo $key ?>").html('');
                $("#pcc_cleared_<?php echo $key ?>").html('');
                $("#identity_cleared_<?php echo $key ?>").html('');
                $("#credit_cleared_<?php echo $key ?>").html('');
                $("#drugs_cleared_<?php echo $key ?>").html('');
                $("#total_cleared_<?php echo $key ?>").html('');  
                $("#address_closed_<?php echo $key ?>").html('');
                $("#employment_closed_<?php echo $key ?>").html('');
                $("#education_closed_<?php echo $key ?>").html('');
                $("#reference_closed_<?php echo $key ?>").html('');
                $("#court_closed_<?php echo $key ?>").html('');
                $("#global_closed_<?php echo $key ?>").html('');
                $("#pcc_closed_<?php echo $key ?>").html('');
                $("#identity_closed_<?php echo $key ?>").html('');
                $("#credit_closed_<?php echo $key ?>").html('');
                $("#drugs_closed_<?php echo $key ?>").html('');
                $("#total_closed_<?php echo $key ?>").html('');    
                $("#candidate_approved_<?php echo $key ?>").html('');
                $("#final_qc_approved_<?php echo $key ?>").html('');
                $("#total_all_<?php echo $key ?>").html('');              
                
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
               var total_add = 0;
               var total_wip = 0;
               var total_insufficiency = 0;
               var total_cleared = 0;
               var total_closed = 0;
               var total_approved = 0;
               var total_all = 0;

               if(typeof(type.candidate_add_<?php echo $key ?>) != "undefined")
               {
                  candidate_add_<?php echo $key ?> =  parseInt(type.candidate_add_<?php echo $key ?>);
                  $("#candidate_add_<?php echo $key ?>").html(parseInt(type.candidate_add_<?php echo $key ?>));
                  total_add += parseInt(type.candidate_add_<?php echo $key ?>);
               } 
               if(typeof(type.candidate_approved_<?php echo $key ?>) != "undefined")
               {
                  candidate_approved_<?php echo $key ?> =  parseInt(type.candidate_approved_<?php echo $key ?>);
                  $("#candidate_approved_<?php echo $key ?>").html(parseInt(type.candidate_approved_<?php echo $key ?>));
                 
               } 
               if(typeof(type.candidate_approved_<?php echo $key ?>) != "undefined")
               {
                  candidate_approved_<?php echo $key ?> =  parseInt(type.candidate_approved_<?php echo $key ?>);
                  $("#final_qc_approved_<?php echo $key ?>").html(parseInt(type.candidate_approved_<?php echo $key ?>));
                  total_approved += parseInt(type.candidate_approved_<?php echo $key ?>); 

               } 
              

               if(typeof(type.address_add_<?php echo $key ?>) != "undefined")
               {
                  address_add_<?php echo $key ?> =  parseInt(type.address_add_<?php echo $key ?>);
                  $("#address_add_<?php echo $key ?>").html(parseInt(type.address_add_<?php echo $key ?>));
                  total_add += parseInt(type.address_add_<?php echo $key ?>); 
               } 
               if(typeof(type.employment_add_<?php echo $key ?>) != "undefined")
               {
                  employment_add_<?php echo $key ?> =  parseInt(type.employment_add_<?php echo $key ?>);
                  $("#employment_add_<?php echo $key ?>").html(parseInt(type.employment_add_<?php echo $key ?>));
                  total_add += parseInt(type.employment_add_<?php echo $key ?>); 
               } 
               if(typeof(type.education_add_<?php echo $key ?>) != "undefined")
               {
                  education_add_<?php echo $key ?> =  parseInt(type.education_add_<?php echo $key ?>);
                  $("#education_add_<?php echo $key ?>").html(parseInt(type.education_add_<?php echo $key ?>));
                  total_add += parseInt(type.education_add_<?php echo $key ?>);   
               } 
               if(typeof(type.reference_add_<?php echo $key ?>) != "undefined")
               {
                  reference_add_<?php echo $key ?> =  parseInt(type.reference_add_<?php echo $key ?>);
                  $("#reference_add_<?php echo $key ?>").html(parseInt(type.reference_add_<?php echo $key ?>));
                  total_add += parseInt(type.reference_add_<?php echo $key ?>);     
               } 
               if(typeof(type.court_verification_add_<?php echo $key ?>) != "undefined")
               {
                  court_verification_add_<?php echo $key ?> =  parseInt(type.court_verification_add_<?php echo $key ?>);
                  $("#court_add_<?php echo $key ?>").html(parseInt(type.court_verification_add_<?php echo $key ?>));
                  total_add += parseInt(type.court_verification_add_<?php echo $key ?>);     
               } 
               if(typeof(type.global_database_add_<?php echo $key ?>) != "undefined")
               {
                  global_database_add_<?php echo $key ?> =  parseInt(type.global_database_add_<?php echo $key ?>);
                  $("#global_add_<?php echo $key ?>").html(parseInt(type.global_database_add_<?php echo $key ?>));
                  total_add += parseInt(type.global_database_add_<?php echo $key ?>);  
               }
               if(typeof(type.pcc_add_<?php echo $key ?>) != "undefined")
               {
                  pcc_add_<?php echo $key ?> =  parseInt(type.pcc_add_<?php echo $key ?>);
                  $("#pcc_add_<?php echo $key ?>").html(parseInt(type.pcc_add_<?php echo $key ?>));
                  total_add += parseInt(type.pcc_add_<?php echo $key ?>);   
               }
               if(typeof(type.identity_add_<?php echo $key ?>) != "undefined")
               {
                  identity_add_<?php echo $key ?> =  parseInt(type.identity_add_<?php echo $key ?>);
                  $("#identity_add_<?php echo $key ?>").html(parseInt(type.identity_add_<?php echo $key ?>));
                  total_add += parseInt(type.identity_add_<?php echo $key ?>);   
               }
               if(typeof(type.credit_report_add_<?php echo $key ?>) != "undefined")
               {
                  credit_report_add_<?php echo $key ?> =  parseInt(type.credit_report_add_<?php echo $key ?>);
                  $("#credit_add_<?php echo $key ?>").html(parseInt(type.credit_report_add_<?php echo $key ?>));
                  total_add += parseInt(type.credit_report_add_<?php echo $key ?>);   
 
               }
               if(typeof(type.drugs_verification_add_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_add_<?php echo $key ?> =  parseInt(type.drugs_verification_add_<?php echo $key ?>);
                  $("#drugs_add_<?php echo $key ?>").html(parseInt(type.drugs_verification_add_<?php echo $key ?>));
                  total_add += parseInt(type.drugs_verification_add_<?php echo $key ?>);   

               } 

               if(typeof(type.address_wip_<?php echo $key ?>) != "undefined")
               {
                  address_wip_<?php echo $key ?> =  parseInt(type.address_wip_<?php echo $key ?>);
                  $("#address_wip_<?php echo $key ?>").html(parseInt(type.address_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.address_wip_<?php echo $key ?>);   
 
               } 
               if(typeof(type.employment_wip_<?php echo $key ?>) != "undefined")
               {
                  employment_wip_<?php echo $key ?> =  parseInt(type.employment_wip_<?php echo $key ?>);
                  $("#employment_wip_<?php echo $key ?>").html(parseInt(type.employment_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.employment_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.education_wip_<?php echo $key ?>) != "undefined")
               {
                  education_wip_<?php echo $key ?> =  parseInt(type.education_wip_<?php echo $key ?>);
                  $("#education_wip_<?php echo $key ?>").html(parseInt(type.education_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.education_wip_<?php echo $key ?>); 
               } 
               if(typeof(type.reference_wip_<?php echo $key ?>) != "undefined")
               {
                  reference_wip_<?php echo $key ?> =  parseInt(type.reference_wip_<?php echo $key ?>);
                  $("#reference_wip_<?php echo $key ?>").html(parseInt(type.reference_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.reference_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.court_verification_wip_<?php echo $key ?>) != "undefined")
               {
                  court_verification_wip_<?php echo $key ?> =  parseInt(type.court_verification_wip_<?php echo $key ?>);
                  $("#court_wip_<?php echo $key ?>").html(parseInt(type.court_verification_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.court_verification_wip_<?php echo $key ?>);   
               } 
               if(typeof(type.global_database_wip_<?php echo $key ?>) != "undefined")
               {
                  global_database_wip_<?php echo $key ?> =  parseInt(type.global_database_wip_<?php echo $key ?>);
                  $("#global_wip_<?php echo $key ?>").html(parseInt(type.global_database_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.global_database_wip_<?php echo $key ?>);   

               }
               if(typeof(type.pcc_wip_<?php echo $key ?>) != "undefined")
               {
                  pcc_wip_<?php echo $key ?> =  parseInt(type.pcc_wip_<?php echo $key ?>);
                  $("#pcc_wip_<?php echo $key ?>").html(parseInt(type.pcc_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.pcc_wip_<?php echo $key ?>);   

               }
               if(typeof(type.identity_wip_<?php echo $key ?>) != "undefined")
               {
                  identity_wip_<?php echo $key ?> =  parseInt(type.identity_wip_<?php echo $key ?>);
                  $("#identity_wip_<?php echo $key ?>").html(parseInt(type.identity_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.identity_wip_<?php echo $key ?>);    
               }
               if(typeof(type.credit_report_wip_<?php echo $key ?>) != "undefined")
               {
                  credit_report_wip_<?php echo $key ?> =  parseInt(type.credit_report_wip_<?php echo $key ?>);
                  $("#credit_wip_<?php echo $key ?>").html(parseInt(type.credit_report_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.credit_report_wip_<?php echo $key ?>);    
 
               }
               if(typeof(type.drugs_verification_wip_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_wip_<?php echo $key ?> =  parseInt(type.drugs_verification_wip_<?php echo $key ?>);
                  $("#drugs_wip_<?php echo $key ?>").html(parseInt(type.drugs_verification_wip_<?php echo $key ?>));
                  total_wip += parseInt(type.drugs_verification_wip_<?php echo $key ?>);    

               }

               if(typeof(type.address_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  address_insufficiency_<?php echo $key ?> =  parseInt(type.address_insufficiency_<?php echo $key ?>);
                  $("#address_insufficiency_<?php echo $key ?>").html(parseInt(type.address_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.address_insufficiency_<?php echo $key ?>);
               } 
               if(typeof(type.employment_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  employment_insufficiency_<?php echo $key ?> =  parseInt(type.employment_insufficiency_<?php echo $key ?>);
                  $("#employment_insufficiency_<?php echo $key ?>").html(parseInt(type.employment_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.employment_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.education_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  education_insufficiency_<?php echo $key ?> =  parseInt(type.education_insufficiency_<?php echo $key ?>);
                  $("#education_insufficiency_<?php echo $key ?>").html(parseInt(type.education_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.education_insufficiency_<?php echo $key ?>);
 
               } 
               if(typeof(type.reference_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  reference_insufficiency_<?php echo $key ?> =  parseInt(type.reference_insufficiency_<?php echo $key ?>);
                  $("#reference_insufficiency_<?php echo $key ?>").html(parseInt(type.reference_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.reference_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.court_verification_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  court_verification_insufficiency_<?php echo $key ?> =  parseInt(type.court_verification_insufficiency_<?php echo $key ?>);
                  $("#court_insufficiency_<?php echo $key ?>").html(parseInt(type.court_verification_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.court_verification_insufficiency_<?php echo $key ?>);

               } 
               if(typeof(type.global_database_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  global_database_insufficiency_<?php echo $key ?> =  parseInt(type.global_database_insufficiency_<?php echo $key ?>);
                  $("#global_insufficiency_<?php echo $key ?>").html(parseInt(type.global_database_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.global_database_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.pcc_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  pcc_insufficiency_<?php echo $key ?> =  parseInt(type.pcc_insufficiency_<?php echo $key ?>);
                  $("#pcc_insufficiency_<?php echo $key ?>").html(parseInt(type.pcc_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.pcc_insufficiency_<?php echo $key ?>);
               }
               if(typeof(type.identity_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  identity_insufficiency_<?php echo $key ?> =  parseInt(type.identity_insufficiency_<?php echo $key ?>);
                  $("#identity_insufficiency_<?php echo $key ?>").html(parseInt(type.identity_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.identity_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  credit_report_insufficiency_<?php echo $key ?> =  parseInt(type.credit_report_insufficiency_<?php echo $key ?>);
                  $("#credit_insufficiency_<?php echo $key ?>").html(parseInt(type.credit_report_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.credit_report_insufficiency_<?php echo $key ?>);

               }
               if(typeof(type.drugs_verification_insufficiency_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_insufficiency_<?php echo $key ?> =  parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>);
                  $("#drugs_insufficiency_<?php echo $key ?>").html(parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>));
                  total_insufficiency += parseInt(type.drugs_verification_insufficiency_<?php echo $key ?>);

               } 

               if(typeof(type.address_cleared_<?php echo $key ?>) != "undefined")
               {
                  address_cleared_<?php echo $key ?> =  parseInt(type.address_cleared_<?php echo $key ?>);
                  $("#address_cleared_<?php echo $key ?>").html(parseInt(type.address_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.address_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.employment_cleared_<?php echo $key ?>) != "undefined")
               {
                  employment_cleared_<?php echo $key ?> =  parseInt(type.employment_cleared_<?php echo $key ?>);
                  $("#employment_cleared_<?php echo $key ?>").html(parseInt(type.employment_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.employment_cleared_<?php echo $key ?>);
               } 
               if(typeof(type.education_cleared_<?php echo $key ?>) != "undefined")
               {
                  education_cleared_<?php echo $key ?> =  parseInt(type.education_cleared_<?php echo $key ?>);
                  $("#education_cleared_<?php echo $key ?>").html(parseInt(type.education_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.education_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.reference_cleared_<?php echo $key ?>) != "undefined")
               {
                  reference_cleared_<?php echo $key ?> =  parseInt(type.reference_cleared_<?php echo $key ?>);
                  $("#reference_cleared_<?php echo $key ?>").html(parseInt(type.reference_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.reference_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.court_verification_cleared_<?php echo $key ?>) != "undefined")
               {
                  court_verification_cleared_<?php echo $key ?> =  parseInt(type.court_verification_cleared_<?php echo $key ?>);
                  $("#court_cleared_<?php echo $key ?>").html(parseInt(type.court_verification_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.court_verification_cleared_<?php echo $key ?>);

               } 
               if(typeof(type.global_database_cleared_<?php echo $key ?>) != "undefined")
               {
                  global_database_cleared_<?php echo $key ?> =  parseInt(type.global_database_cleared_<?php echo $key ?>);
                  $("#global_cleared_<?php echo $key ?>").html(parseInt(type.global_database_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.global_database_cleared_<?php echo $key ?>);

               }
               if(typeof(type.pcc_cleared_<?php echo $key ?>) != "undefined")
               {
                  pcc_cleared_<?php echo $key ?> =  parseInt(type.pcc_cleared_<?php echo $key ?>);
                  $("#pcc_cleared_<?php echo $key ?>").html(parseInt(type.pcc_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.pcc_cleared_<?php echo $key ?>);

               }
               if(typeof(type.identity_cleared_<?php echo $key ?>) != "undefined")
               {
                  identity_cleared_<?php echo $key ?> =  parseInt(type.identity_cleared_<?php echo $key ?>);
                  $("#identity_cleared_<?php echo $key ?>").html(parseInt(type.identity_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.identity_cleared_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_cleared_<?php echo $key ?>) != "undefined")
               {
                  credit_report_cleared_<?php echo $key ?> =  parseInt(type.credit_report_cleared_<?php echo $key ?>);
                  $("#credit_cleared_<?php echo $key ?>").html(parseInt(type.credit_report_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.credit_report_cleared_<?php echo $key ?>);
               }
               if(typeof(type.drugs_verification_cleared_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_cleared_<?php echo $key ?> =  parseInt(type.drugs_verification_cleared_<?php echo $key ?>);
                  $("#drugs_cleared_<?php echo $key ?>").html(parseInt(type.drugs_verification_cleared_<?php echo $key ?>));
                  total_cleared += parseInt(type.drugs_verification_cleared_<?php echo $key ?>);
 
               }
 
               if(typeof(type.address_closed_<?php echo $key ?>) != "undefined")
               {
                  address_closed_<?php echo $key ?> =  parseInt(type.address_closed_<?php echo $key ?>);
                  $("#address_closed_<?php echo $key ?>").html(parseInt(type.address_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.address_closed_<?php echo $key ?>);
 
               } 
               if(typeof(type.employment_closed_<?php echo $key ?>) != "undefined")
               {
                  employment_closed_<?php echo $key ?> =  parseInt(type.employment_closed_<?php echo $key ?>);
                  $("#employment_closed_<?php echo $key ?>").html(parseInt(type.employment_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.employment_closed_<?php echo $key ?>);
               } 
               if(typeof(type.education_closed_<?php echo $key ?>) != "undefined")
               {
                  education_closed_<?php echo $key ?> =  parseInt(type.education_closed_<?php echo $key ?>);
                  $("#education_closed_<?php echo $key ?>").html(parseInt(type.education_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.education_closed_<?php echo $key ?>);

               } 
               if(typeof(type.reference_closed_<?php echo $key ?>) != "undefined")
               {
                  reference_closed_<?php echo $key ?> =  parseInt(type.reference_closed_<?php echo $key ?>);
                  $("#reference_closed_<?php echo $key ?>").html(parseInt(type.reference_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.reference_closed_<?php echo $key ?>);
  
               } 
               if(typeof(type.court_verification_closed_<?php echo $key ?>) != "undefined")
               {
                  court_verification_closed_<?php echo $key ?> =  parseInt(type.court_verification_closed_<?php echo $key ?>);
                  $("#court_closed_<?php echo $key ?>").html(parseInt(type.court_verification_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.court_verification_closed_<?php echo $key ?>);
               } 
               if(typeof(type.global_database_closed_<?php echo $key ?>) != "undefined")
               {
                  global_database_closed_<?php echo $key ?> =  parseInt(type.global_database_closed_<?php echo $key ?>);
                  $("#global_closed_<?php echo $key ?>").html(parseInt(type.global_database_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.global_database_closed_<?php echo $key ?>);

               }
               if(typeof(type.pcc_closed_<?php echo $key ?>) != "undefined")
               {
                  pcc_closed_<?php echo $key ?> =  parseInt(type.pcc_closed_<?php echo $key ?>);
                  $("#pcc_closed_<?php echo $key ?>").html(parseInt(type.pcc_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.pcc_closed_<?php echo $key ?>);

               }
               if(typeof(type.identity_closed_<?php echo $key ?>) != "undefined")
               {
                  identity_closed_<?php echo $key ?> =  parseInt(type.identity_closed_<?php echo $key ?>);
                  $("#identity_closed_<?php echo $key ?>").html(parseInt(type.identity_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.identity_closed_<?php echo $key ?>);

               }
               if(typeof(type.credit_report_report_closed_<?php echo $key ?>) != "undefined")
               {
                  credit_report_closed_<?php echo $key ?> =  parseInt(type.credit_report_closed_<?php echo $key ?>);
                  $("#credit_closed_<?php echo $key ?>").html(parseInt(type.credit_report_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.credit_report_closed_<?php echo $key ?>);

               }
               if(typeof(type.drugs_verification_closed_<?php echo $key ?>) != "undefined")
               {
                  drugs_verification_closed_<?php echo $key ?> =  parseInt(type.drugs_verification_closed_<?php echo $key ?>);
                  $("#drugs_closed_<?php echo $key ?>").html(parseInt(type.drugs_verification_closed_<?php echo $key ?>));
                  total_closed += parseInt(type.drugs_verification_closed_<?php echo $key ?>);

               }
               
               $("#total_add_<?php echo $key ?>").html(total_add);
               $("#total_wip_<?php echo $key ?>").html(total_wip);
               $("#total_insufficiency_<?php echo $key ?>").html(total_insufficiency);
               $("#total_cleared_<?php echo $key ?>").html(total_cleared);
               $("#total_closed_<?php echo $key ?>").html(total_closed);
               
               total_all = total_all + total_add + total_wip +  total_insufficiency + total_cleared + total_closed + total_approved; 
               $("#total_all_<?php echo $key ?>").html('('+total_all+')');

               if(total_all == "0")
               { 
                
                  if(typeof(type.candidate_approved_<?php echo $key ?>) == "undefined" || type.candidate_approved_<?php echo $key ?> == "0")
                  { 
                     $(".show_hide_<?php echo $key ?>").hide();
                  }
                  else{
                    
                     $(".show_hide_<?php echo $key ?>").show();   
                  }
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