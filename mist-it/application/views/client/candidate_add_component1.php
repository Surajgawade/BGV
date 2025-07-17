<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"> 
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, 
     user-scalable=0'>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>apple-touch-icon.png" type="image/ico" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title><?php echo isset($header_title) ? ucwords(strtolower($header_title)) : CRMNAME ?></title>
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>metismenu.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>icons.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>style.css">
   <link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />

    <link href="<?php echo SITE_CSS_URL; ?>daterangepicker.css" rel="stylesheet">
    <link href="<?php echo SITE_CSS_URL; ?>datepicker3.css" rel="stylesheet">
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />


<style type="text/css">
  
 @media screen and (max-width : 760px){
 .dropdown-content li{
        display: inline-block;
        font-size: 1em;
        margin:  0;
        width: 100%;
    }
    .dropdown-content{
        display: block;
        margin: 0;
        text-align: right;
    }   
}

#pending_submit_check {
 /* font-family: Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#pending_submit_check td, #pending_submit_check th {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: center;
}

</style>
</head>
<body> 

  
    <div class="content-page">
      <div class="content">
        <div class="container-fluid">
       <?php  if($company_logo != ''){ ?>
        <div style="display: flex;">
      
        <div style="margin-left: 25px">
            <span>
                 <img src="<?php echo COMPANYLOGO ?>" alt="" height="50" style="background: white;">
            </span>
              
        </div>
        <div style="margin-left: 500px"> 
              <span>
                    <img src="<?php echo SITE_URL . CLIENT_LOGO .'/'.$company_logo ?>" alt="" height="70">
              </span>
        </div>
     </div>
      <?php }else{  ?>

        <div style="text-align: center;">
            <a href="<?php echo COMPANYLOGO ?>" class="logo">
                <span>
                    <img src="<?php echo COMPANYLOGO ?>" alt="" height="50" style="background: white;">
                </span>
            </a>
        </div>

      <?php } ?>

        <br>      
          <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">
                  <div class="text-white div-header">
                    Candidate Details  
                  </div>
                   <br>
                   <div style="float: right;">
                       <button class="btn btn-secondary waves-effect  edit_btn_click" data-frm_name='update_candidates' data-editUrl="<?= $candidate_details['cands_info_id'] ?>"><i class="fa fa-edit"></i> Edit</button>
                  </div>
                <?php echo form_open('#', array('name'=>'update_candidates','id'=>'update_candidates')); ?> 
                 
                <div class="clearfix"></div>
                  <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['cands_info_id']); ?>" class="form-control">

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label>Candidate Name <span class="error"> *</span></label>
                      <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control" readonly>
                      <?php echo form_error('CandidateName'); ?>
                    </div>
                
                    <div class="col-sm-4 form-group">
                      <label>Date of Birth <span class="error"> *</span></label>
                      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
                      <?php echo form_error('DateofBirth'); ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Father's Name <span class="error"> *</span></label>
                      <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control cls_disabled">
                      <?php echo form_error('NameofCandidateFather'); ?>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-4 form-group">
                        <label>Gender<span class="error"> *</span></label>
                       <?php
                          echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="custom-select cls_disabled" id="gender"');
                          echo form_error('gender');
                        ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Primary Contact<span class="error"> *</span></label>
                      <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control" readonly>
                      <?php echo form_error('CandidatesContactNumber'); ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label>Alternative Contact No</label>
                      <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control cls_disabled">
                      <?php echo form_error('ContactNo1'); ?>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
                    </div>
                  </div>
                 <?php echo form_close(); ?>

                </div>
              </div>




              <div class="card m-b-20">
                <div class="card-body">
                  <div class="text-white div-header">
                    Required Details
                  </div>
                   <br>
                    <table  id="pending_submit_check">
                      
                      <tr>
                        <th><b>Check</b></th>
                        <th><b>Type</b></th>
                        <th><b>Status</b></th>
                      </tr>
                    
                        
                         <?php 

                         if(!empty($scope_of_work[0]['scope_of_work']))
                         { 


                            $scope_of_work = json_decode($scope_of_work[0]['scope_of_work']);

                            $component_id = explode(',',$client_components['component_id']);  
                            if(in_array('addrver',$component_id))
                            {
                            
                              if($candidate_details['address_component_check'] == 1)
                              {
                                 if($candidate_details['address_component_check'] == 1)
                                 {
                                   $status_address = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['address_component_check'] == 0)
                                 {
                                   $status_address = "<td style='color: green;'>Submitted</td>";
                                 }  
                                 echo  "<tr><td>Address</td><td>".$scope_of_work->addrver."</td>".$status_address."</tr>";
                              }
                            }

                            if(in_array('empver',$component_id))
                            {
                              if($candidate_details['employment_component_check'] == 1)
                              {
                                 if($candidate_details['employment_component_check'] == 1)
                                 {
                                   $status_employment = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['employment_component_check'] == 0)
                                 {
                                   $status_employment = "<td style='color: green;'>Submitted</td>";
                                 } 
                                 echo  "<tr><td>Employment</td><td>".$scope_of_work->empver."</td>".$status_employment."</tr>"; 
                              }
                            }
                            if(in_array('eduver',$component_id))
                            {
                             
                              if($candidate_details['education_component_check'] == 1)
                              {

                                 if($candidate_details['education_component_check'] == 1)
                                 {
                                   $status_education = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['education_component_check'] == 0)
                                 {
                                   $status_education = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Education</td><td>".$scope_of_work->eduver."</td>".$status_education."</tr>"; 
                              } 
                            }

                            if(in_array('courtver',$component_id))
                            {
                             
                              if($candidate_details['court_component_check'] == 1)
                              { 
                            
                                 if($candidate_details['court_component_check'] == 1)
                                 {
                                   $status_court = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['court_component_check'] == 0)
                                 {
                                   $status_court = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Address</td><td>".$scope_of_work->courtver."</td>".$status_court."</tr>"; 
                               
                              }
                            }

                            if(in_array('globdbver',$component_id))
                            {
                              if($candidate_details['global_component_check'] == 1)
                              {

                                 if($candidate_details['global_component_check'] == 1)
                                 {
                                   $status_global = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['global_component_check'] == 0)
                                 {
                                   $status_global = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Address</td><td>".$scope_of_work->globdbver."</td>".$status_global."</tr>";
                              
                              }
                            }
                            if(in_array('crimver',$component_id))
                            {
                                if($candidate_details['pcc_component_check'] == 1)
                                {
                                   if($candidate_details['pcc_component_check'] == 1)
                                   {
                                     $status_pcc = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['pcc_component_check'] == 0)
                                   {
                                     $status_pcc = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Address</td><td>".$scope_of_work->crimver."</td>".$status_pcc."</tr>";
                                } 
                            } 

                            if(in_array('identity',$component_id))
                            {
                             
                              if($candidate_details['identity_component_check'] == 1)
                              { 
                                 if($candidate_details['identity_component_check'] == 1)
                                 {
                                   $status_identity = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['identity_component_check'] == 0)
                                 {
                                   $status_identity = "<td style='color: green;'>Submitted</td>";
                                 }

                                 echo  "<tr><td>Identity</td><td>".$scope_of_work->identity."</td>".$status_identity."</tr>";
                              }
                            }

                            if(in_array('refver',$component_id))
                            {
                             

                              if($candidate_details['reference_component_check'] == 1)
                              {
                           
                                 if($candidate_details['reference_component_check'] == 1)
                                 {
                                   $status_reference = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['reference_component_check'] == 0)
                                 {
                                   $status_reference = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Reference</td><td>".$scope_of_work->refver."</td>".$status_reference."</tr>";
                                
                              }
                            }
                               
                            if(in_array('narcver',$component_id))
                            {
                                
                                if($candidate_details['drugs_component_check'] == 1)
                                { 
                               
                                   if($candidate_details['drugs_component_check'] == 1)
                                   {
                                     $status_drugs = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['drugs_component_check'] == 0)
                                   {
                                     $status_drugs = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Drugs</td><td>".$scope_of_work->narcver."</td>".$status_drugs. "</tr>";
                                }
                            }

                            if(in_array('cbrver',$component_id))
                            { 

                              if($candidate_details['credit_report_component_check'] == 1)
                              {  
                            
                                 if($candidate_details['credit_report_component_check'] == 1)
                                 {
                                   $status_credit_report = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['credit_report_component_check'] == 0)
                                 {
                                   $status_credit_report = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Credit</td><td>".$scope_of_work->cbrver."</td>".$status_credit_report."</tr>";
                              }
                            }

                            if(in_array('addrver',$component_id))
                            {
                                

                                if($candidate_details['address_component_check'] == 0)
                                {
                                   if($candidate_details['address_component_check'] == 1)
                                   {
                                     $status_address = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['address_component_check'] == 0)
                                   {
                                     $status_address = "<td style='color: green;'>Submitted</td>";
                                   }  
                                   echo  "<tr><td>Address</td><td>".$scope_of_work->addrver."</td>".$status_address."</tr>";
                                }
                            }

                            if(in_array('empver',$component_id))
                            {
                                if($candidate_details['employment_component_check'] == 0)
                                {
                                   if($candidate_details['employment_component_check'] == 1)
                                   {
                                     $status_employment = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['employment_component_check'] == 0)
                                   {
                                     $status_employment = "<td style='color: green;'>Submitted</td>";
                                   } 
                                   echo  "<tr><td>Employment</td><td>".$scope_of_work->empver."</td>".$status_employment."</tr>"; 
                                }
                            }

                            if(in_array('eduver',$component_id))
                            {
                                if($candidate_details['education_component_check'] == 0)
                                {

                                   if($candidate_details['education_component_check'] == 1)
                                   {
                                     $status_education = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['education_component_check'] == 0)
                                   {
                                     $status_education = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Education</td><td>".$scope_of_work->eduver."</td>".$status_education."</tr>"; 
                                } 
                            }

                            if(in_array('courtver',$component_id))
                            {
                                if($candidate_details['court_component_check'] == 0)
                                { 
                              
                                   if($candidate_details['court_component_check'] == 1)
                                   {
                                     $status_court = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['court_component_check'] == 0)
                                   {
                                     $status_court = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Address</td><td>".$scope_of_work->courtver."</td>".$status_court."</tr>"; 
                                 
                                }
                            }

                            if(in_array('globdbver',$component_id))
                            {
                              if($candidate_details['global_component_check'] == 0)
                              {

                                 if($candidate_details['global_component_check'] == 1)
                                 {
                                   $status_global = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['global_component_check'] == 0)
                                 {
                                   $status_global = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Address</td><td>".$scope_of_work->globdbver."</td>".$status_global."</tr>";
                              
                              }
                            }


                            if(in_array('crimver',$component_id))
                            {

                              if($candidate_details['pcc_component_check'] == 0)
                              {
                                 if($candidate_details['pcc_component_check'] == 1)
                                 {
                                   $status_pcc = "<td style='color: red;'><b>Pending<b></td>";
                                 }
                                 if($candidate_details['pcc_component_check'] == 0)
                                 {
                                   $status_pcc = "<td style='color: green;'>Submitted</td>";
                                 }
                                 echo  "<tr><td>Address</td><td>".$scope_of_work->crimver."</td>".$status_pcc."</tr>";
                              } 
                            }

                            if(in_array('identity',$component_id))
                            { 
                             
                                if($candidate_details['identity_component_check'] == 0)
                                { 
                                   if($candidate_details['identity_component_check'] == 1)
                                   {
                                     $status_identity = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['identity_component_check'] == 0)
                                   {
                                     $status_identity = "<td style='color: green;'>Submitted</td>";
                                   }

                                   echo  "<tr><td>Identity</td><td>".$scope_of_work->identity."</td>".$status_identity."</tr>";
                                }
                            }

                            if(in_array('refver',$component_id))
                            { 

                                if($candidate_details['reference_component_check'] == 0)
                                {
                             
                                   if($candidate_details['reference_component_check'] == 1)
                                   {
                                     $status_reference = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['reference_component_check'] == 0)
                                   {
                                     $status_reference = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Reference</td><td>".$scope_of_work->refver."</td>".$status_reference."</tr>";
                                  
                                }
                            }

                            if(in_array('narcver',$component_id))
                            { 


                                if($candidate_details['drugs_component_check'] == 0)
                                { 
                               
                                   if($candidate_details['drugs_component_check'] == 1)
                                   {
                                     $status_drugs = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['drugs_component_check'] == 0)
                                   {
                                     $status_drugs = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Drugs</td><td>".$scope_of_work->narcver."</td>".$status_drugs. "</tr>";
                                } 
                            }

                            if(in_array('cbrver',$component_id))
                            { 

                                if($candidate_details['credit_report_component_check'] == 0)
                                {  
                              
                                   if($candidate_details['credit_report_component_check'] == 1)
                                   {
                                     $status_credit_report = "<td style='color: red;'><b>Pending<b></td>";
                                   }
                                   if($candidate_details['credit_report_component_check'] == 0)
                                   {
                                     $status_credit_report = "<td style='color: green;'>Submitted</td>";
                                   }
                                   echo  "<tr><td>Credit</td><td>".$scope_of_work->cbrver."</td>".$status_credit_report."</tr>";
                                }
                            }        
                           
                         }
                         ?>
                
                    </table>
                 </div>
             </div>

       
              <div class="card m-b-20">
                <div class="card-body">
                  <div class="text-white div-header">
                    Components Details  
                  </div>
                   <br>
                    <span class= "error">Select check to View or Add </span>
                   <br>
                   <br> 
                   <div class="nav-tabs-custom">
      
                     <ul class="nav nav-pills nav-justified dropdown-content" role="tablist">
                        <?php
                        $components = json_decode($client_components['component_name'],true);
                     
                        $selected_component = explode(',', $client_components['component_id']);
                       // $is_check = array_column($this->user_info['menu'], 'controllers');
                       
                        $i = 0; 
                        foreach ($components as $key => $component) 
                        {
                          if($key != 'crimver' && $key != 'courtver' && $key != 'globdbver'){
                            
                            if(in_array($key, $selected_component))
                            {
                              if($i == 0){
                                $active_tab = "active";
                              }
                              else{
                                $active_tab = "";
                              }
                              $tabs_panels[] = $key;
                              $component  =   explode(' ',trim($component));
                              $component  =   explode('/',trim($component[0]));
                              echo "<li role='presentation' data-url='".CLIENT_SITE_URL."candidate_mail/ajax_tab_data/' data-can_id=".$candidate_details['cands_info_id']." data-tab_name=".$key."  class='view_component_tab nav-item waves-effect waves-light ".$key."'><a class='nav-link ".$active_tab."' href='#".$key."' aria-controls='home' role='tab' data-toggle='tab'>  ".$component[0]."</a></li>";
                            }
                          }
                          $i++;
                        }
                        
                        ?>
                      </ul>
                      <br>
                      <div class="tab-content">

                        <?php
                          $i = 0;
                          foreach ($tabs_panels as $key => $tabs_panel) 
                          { 
                              if($i == 0){
                                $active_tab = "active show";
                                $tab = $tabs_panel;
                              }
                              else{
                                $active_tab = "";
                              }
                              echo "<div id='".$tabs_panel."' class='tab-pane fade in ".$active_tab."'>";
                              
                              echo "</div>";
                              $i++;  
                          }
                        ?>
                      </div> 
                    </div>
                 </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  

  

<script src="<?php echo SITE_JS_URL;?>jquery.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>metisMenu.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.slimscroll.js"></script>
<script src="<?php echo SITE_JS_URL;?>waves.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>notify.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.validate.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>demo.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap-multiselect.js"></script>
<script src="<?php echo SITE_JS_URL; ?>daterangepicker.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-datepicker.js"></script>

<!-- Required datatable js -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>dataTables.checkboxes.min.js"></script>
<!-- Buttons examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jszip.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/pdfmake.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/vfs_fonts.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.html5.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.print.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="<?php echo  SITE_URL; ?>assets/pages/datatables.init.js"></script>
<script src="<?php echo  SITE_URL; ?>assets/plugins/select2/js/select2.min.js"></script>
    
<script src="<?php echo  SITE_JS_URL; ?>app.js"></script>

<script type="text/javascript">
$('.cls_disabled').prop('disabled', true);
$(".content-page").css({"margin-left":"0px"});
$('.addrver').click(function(){

}).trigger('click');



$('#update_candidates').validate({ 
    rules: {
     candidate_id : {
        required : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      NameofCandidateFather : {
        required : true,
        lettersonly : true
      },
      DateofBirth : {
        required : true
      },
      CandidatesContactNumber : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      gender : {
        required : true,
        greaterThan: 0
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      }
     
    },
    messages: {
        candidate_id : {
          required : "Enter Candidate ID"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
        NameofCandidateFather : {
          required : "Enter Name of Father",
          lettersonly : "Allowed Only letter"
        },
        DateofBirth : {
            required : "Enter Date of Birth"
        },
        gender : {
           required : "Select Gender"
        },
        CandidatesContactNumber : {
          required : "Please Enter Primary Contact"
        }
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'Candidate_mail/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
            
            },
            complete:function(){
            
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                location.reload();
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
      }
  });

</script>
</body>
</html>