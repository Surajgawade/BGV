<div class="modal-body">

   <?php 

  
   if($url == "StopCheck")
   { 
     if(empty($empt_details['remarks']))
     {
        $empt_details['remarks'] = "Communication received from client requesting to Stop Check.";
     }
   }
    if($url == "Clear")
   {
     if(empty($empt_details['remarks']))
     {
        $empt_details['remarks'] = "Attached for your reference.";
     }
   }
    if($url == "OverseasCheck")
   {
      if(empty($empt_details['remarks']))
      {
        $empt_details['remarks'] = "Verification not done since it is an Overseas Check.";
      }
   }
   if($url == "WorkedWithTheSameOrganization")
   {
      if(empty($empt_details['remarks']))
      {
         $empt_details['remarks'] = "Candidate was working with the same company.";
      }
   }
   if(($url == "StopCheck") || ($url == "OverseasCheck") || ($url == "NotApplicable") || ($url == "NA") ||  ($url == "WorkedWithTheSameOrganization") || ($url == "UnableToVerify"))
   {
     $css_style = 'style="display: none;"';
      echo '<input type="radio" class="rto_clicked" name="company_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="deputed_company_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="empfrom_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="empto_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="designation_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="empid_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="reportingmanager_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="reasonforleaving_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="remuneration_action" checked value="" style="display:none">';
   }
   else
   {
     $css_style = '';
   }
   


  ?>


  <?php


$empver_empfrom  =  $empt_details['empver_empfrom'];



if(strpos($empt_details['employed_from'],"-") == 4 || strpos($empt_details['employed_from'],"-") == 2)
{
   
   $employed_from  = date("d-m-Y", strtotime($empt_details['employed_from']));
}
else
{
   $employed_from  =  $empt_details['employed_from'];
}


$empver_empto  =  $empt_details['empver_empto'];


if(strpos($empt_details['employed_to'],"-") == 4 || strpos($empt_details['employed_from'],"-") == 2)
{
   
   $employed_to  = date("d-m-Y", strtotime($empt_details['employed_to']));
}
else
{
   $employed_to  =  $empt_details['employed_to'];
}


?>
 <input type="hidden" name="result_update_id" id="result_update_id" value="<?php echo set_value('result_update_id',$empt_details['sr_id']); ?>" class="form-control" >
  <input type="hidden" name="action_val" id="action_val" value="<?php echo set_value('action_val',$empt_details['var_filter_status']); ?>" class="form-control" >
  <div class="result_error" id="result_error"></div>
  <input type="hidden" name="frist_comp_url" id="frist_comp_url" value="<?php echo set_value('frist_comp_url','employment/frm_first_qc/'); ?>">
  <input type="hidden" name="frist_qc_id" id="frist_qc_id" value="<?php echo set_value('frist_qc_id',encrypt($empt_details['id'])); ?>">
   <input type="hidden" name="action_url" id="action_url" value="<?php echo set_value('action_url',$url); ?>">
  
    <div <?php echo $css_style ?> >
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Infomation Provided</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Verify Infomation</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Action</label>
    </div>
    </div>
    <div class="row">  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>

     <input type="hidden" name="info_verfstatus" id="info_verfstatus" value="<?php echo set_value('info_verfstatus',$empt_details['empver_nameofthecompany']);?>" class="form-control">
     <input type="hidden" name="res_nameofthecompany" id="res_nameofthecompany" value="<?php echo set_value('res_nameofthecompany',$empt_details['res_nameofthecompany']);?>" class="form-control">
      <label> Name of Company</label>
      <input type="text" name="co_name" id="co_name" value="<?php echo set_value('co_name',$empt_details['co_name']);?>" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label> Name of Company</label>
      <input type="text" name="res_co_name" id="res_co_name" value="<?php echo set_value('res_co_name',$empt_details['res_co_name']);?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="company_action" data-val="res_nameofthecompany" value="yes" <?php if(isset($empt_details['nameofthecompany_action'])){ if($empt_details['nameofthecompany_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="company_action" data-val="res_nameofthecompany" value="no" <?php if(isset($empt_details['nameofthecompany_action'])){ if($empt_details['nameofthecompany_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
    </div>
    </div> 

    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Deputed Company</label>
      <input type="text" name="info_deputed_company" id="info_deputed_company" value="<?php echo set_value('info_deputed_company',$empt_details['empver_deputed_company']); ?>" class="form-control" readonly>
      <?php echo form_error('info_deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Deputed Company</label>
      <input type="text" name="res_deputed_company" id="res_deputed_company" value="<?php echo set_value('res_deputed_company',$empt_details['res_deputed_company']); ?>" class="form-control">
      <?php echo form_error('res_deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="deputed_company_action" data-val="res_deputed_company" value="yes" <?php if(isset($empt_details['deputed_company_action'])){ if($empt_details['deputed_company_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="deputed_company_action" data-val="res_deputed_company" value="no" <?php if(isset($empt_details['deputed_company_action'])){ if($empt_details['deputed_company_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
    </div>
    </div>

  
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Employed From <span class="error"> *</span> </label>
        
      <input type="text" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom',$empver_empfrom);?>" class="form-control" readonly>
      <?php echo form_error('empfrom'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Employed From  <span class="error"> *</span>  <input type="radio" name="calender_display_employee_from_res" id="calender_display_employee_from_res" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_from_res" id="calender_display_employee_from_res" value="text_value"> : Text </label>
      <input type="text" name="res_empfrom" id="res_empfrom" value="<?php echo set_value('res_empfrom',$employed_from);?>" class="form-control">
      <?php echo form_error('res_empfrom'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empfrom_action" data-val="res_empfrom" value="yes"  <?php if(isset($empt_details['employed_from_action'])){ if($empt_details['employed_from_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empfrom_action" data-val="res_empfrom" value="no" <?php if(isset($empt_details['employed_from_action'])){ if($empt_details['employed_from_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
    </div>
    </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Employed To <span class="error"> *</span> </label>
    
      <input type="text" name="empto" id="empto" value="<?php echo set_value('empto',$empver_empto);?>" class="form-control" readonly>
      <?php echo form_error('empto'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Employed To <span class="error"> *</span>   <input type="radio" name="calender_display_employee_to_res" id="calender_display_employee_to_res" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_to_res" id="calender_display_employee_to_res" value="text_value"> : Text</label>
      <input type="text" name="res_empto" id="res_empto" value="<?php echo set_value('res_empto',$employed_to);?>" class="form-control" >
      <?php echo form_error('res_empto'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empto_action" data-val="res_empto" value="yes"  <?php if(isset($empt_details['employed_to_action'])){ if($empt_details['employed_to_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empto_action" data-val="res_empto" value="no" <?php if(isset($empt_details['employed_to_action'])){ if($empt_details['employed_to_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
    </div>
    </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$empt_details['empver_designation']); ?>" class="form-control" readonly>
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="emp_designation" id="emp_designation" value="<?php echo set_value('emp_designation',$empt_details['emp_designation']); ?>" class="form-control">
      <?php echo form_error('emp_designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="designation_action" data-val="emp_designation" value="yes" <?php if(isset($empt_details['emp_designation_action'])){ if($empt_details['emp_designation_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="designation_action" data-val="emp_designation" value="no"  <?php if(isset($empt_details['emp_designation_action'])){ if($empt_details['emp_designation_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
    </div>
    </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Employee code</label>
      <input type="text" name="empid" id="empid" value="<?php echo set_value('empid',$empt_details['empver_empid']); ?>" class="form-control" readonly>
      <?php echo form_error('empid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Employee code<span class="error"> *</span></label>
      <input type="text" name="res_empid" id="res_empid" value="<?php echo set_value('res_empid',$empt_details['res_empid']); ?>" class="form-control">
      <?php echo form_error('res_empid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empid_action" data-val="res_empid" value="yes" <?php if(isset($empt_details['empid_action'])){ if($empt_details['empid_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empid_action" data-val="res_empid" value="no" <?php if(isset($empt_details['empid_action'])){ if($empt_details['empid_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="empid_action" data-val="res_empid" value="not-disclosed" <?php if(isset($empt_details['empid_action'])){ if($empt_details['empid_action']== 'not-disclosed') {echo "checked='checked'";}} ?>>Not Disclosed</label>
    </div>
    </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Reporting Manager's Name</label>
      <input type="text" name="reportingmanager" id="reportingmanager" value="<?php echo set_value('reportingmanager',$empt_details['r_manager_name']); ?>" class="form-control" readonly>
      <?php echo form_error('reportingmanager'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Reporting Manager's Name<span class="error"> *</span></label>
      <input type="text" name="res_reportingmanager" id="res_reportingmanager" value="<?php echo set_value('res_reportingmanager',$empt_details['reportingmanager']); ?>" class="form-control">
      <?php echo form_error('res_reportingmanager'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reportingmanager_action" data-val="res_reportingmanager" value="yes" <?php if(isset($empt_details['reportingmanager_action'])){ if($empt_details['reportingmanager_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reportingmanager_action" data-val="res_reportingmanager" value="no" <?php if(isset($empt_details['reportingmanager_action'])){ if($empt_details['reportingmanager_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reportingmanager_action" data-val="res_reportingmanager" value="not-disclosed" <?php if(isset($empt_details['reportingmanager_action'])){ if($empt_details['reportingmanager_action']== 'not-disclosed') {echo "checked='checked'";}} ?>>Not Disclosed</label>
    </div>
   </div>
   <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control" readonly><?php echo set_value('reasonforleaving',$empt_details['empreasonforleaving']);?></textarea>
      <?php echo form_error('reasonforleaving'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="res_reasonforleaving" rows="1" id="res_reasonforleaving" class="form-control"><?php echo set_value('res_reasonforleaving',$empt_details['res_reasonforleaving']);?></textarea>
      <?php echo form_error('res_reasonforleaving'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reasonforleaving_action" data-val="res_reasonforleaving" value="yes" <?php if(isset($empt_details['reasonforleaving_action'])){ if($empt_details['reasonforleaving_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reasonforleaving_action" data-val="res_reasonforleaving" value="no" <?php if(isset($empt_details['reasonforleaving_action'])){ if($empt_details['reasonforleaving_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="reasonforleaving_action" data-val="res_reasonforleaving" value="not-disclosed" <?php if(isset($empt_details['reasonforleaving_action'])){ if($empt_details['reasonforleaving_action']== 'not-disclosed') {echo "checked='checked'";}} ?>>Not Disclosed</label>
    </div>
  </div>
  <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Remuneration<span class="error"> *</span></label>
      <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration',$empt_details['empver_remuneration']); ?>" class="form-control" readonly>
      <?php echo form_error('remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Remuneration<span class="error"> *</span></label>
      <input type="text" name="res_remuneration" id="res_remuneration" value="<?php echo set_value('res_remuneration',$empt_details['res_remuneration']); ?>" class="form-control">
      <?php echo form_error('res_remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="remuneration_action" data-val="res_remuneration" value="yes" <?php if(isset($empt_details['remuneration_action'])){ if($empt_details['remuneration_action']== 'yes') {echo "checked='checked'";}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="remuneration_action" data-val="res_remuneration" value="no"  <?php if(isset($empt_details['remuneration_action'])){ if($empt_details['remuneration_action']== 'no') {echo "checked='checked'";}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="remuneration_action" data-val="res_remuneration" value="not-disclosed" <?php if(isset($empt_details['remuneration_action'])){ if($empt_details['remuneration_action']== 'not-disclosed') {echo "checked='checked'";}} ?>>Not Disclosed</label>
    </div>
  </div>
  <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Integrity/Disciplinary Issues<span class="error"> *</span></label>

    <?php  $integritydisciplinary = array("" => "Select","yes" => "Yes","no" => "No",'not disclosed'=> 'Not Disclosed');

    echo form_dropdown('info_integrity_disciplinary_issue', $integritydisciplinary, set_value('info_integrity_disciplinary_issue',$empt_details['info_integrity_disciplinary_issue']), 'class="select2" id="info_integrity_disciplinary_issue"');
        echo form_error('info_integrity_disciplinary_issue');
    ?>
     
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?> style="display :none;" id="integrity_disciplinary">
      <label>Reason </label>
      <input type="text" name="integrity_disciplinary_issue" class="form-control" id="integrity_disciplinary_issue" value="<?php echo set_value('integrity_disciplinary_issue',$empt_details['integrity_disciplinary_issue']); ?>">
      <?php echo form_error('integrity_disciplinary_issue'); ?>
    </div>
   
  </div>
  <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Exit Formalities Completed? <span class="error"> *</span></label>
      <?php
        $formal_option_array = array("" => "Select","yes" => "Yes","no" => "No",'not disclosed'=> 'Not Disclosed');
        echo form_dropdown('info_exitformalities', $formal_option_array, set_value('info_exitformalities',$empt_details['info_exitformalities']), 'class="select2" id="info_exitformalities"');
        echo form_error('info_exitformalities'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?> style="display :none;" id="exit_formality">
      <label>Reason</label>

       <input type="text" name="exitformalities" class="form-control" id="exitformalities" value="<?php echo set_value('exitformalities',$empt_details['exitformalities']); ?>">
      <?php echo form_error('exitformalities'); ?>
     
    </div>
  
 </div>
  <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Eligible for Rehire?<span class="error"> *</span></label>
       <?php
        $info_eligforrehire_option_array = array("" => "Select","yes" => "Yes","no" => "No",'not disclosed'=> 'Not Disclosed');
        echo form_dropdown('info_eligforrehire', $info_eligforrehire_option_array, set_value('info_eligforrehire',$empt_details['info_eligforrehire']), 'class="select2" id="info_eligforrehire"');
        echo form_error('info_eligforrehire'); 
      ?>
     
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?> style="display :none;" id="eligible_for_rehire">
      <label >Reason</label>
      <input type="text" name="eligforrehire" id="eligforrehire" value="<?php echo set_value('res_remuneration',$empt_details['eligforrehire']); ?>" class="form-control">
      <?php echo form_error('eligforrehire'); ?>
    </div>
   </div>

   <div class="row">
      <div class="col-sm-4 form-group">
        <label> Web check status<span class="error"> *</span></label>
              <?php
             $justdialwebcheck = array('' => 'Select Check Status','yes' => 'Yes','no' => 'No');
                echo form_dropdown('justdialwebcheck', $justdialwebcheck, set_value('justdialwebcheck',$empt_details['justdialwebcheck']), 'class="custom-select cls_dis" id="justdialwebcheck"');
                echo form_error('justdialwebcheck');
              ?>   
          </div>

          <div class="col-sm-4 form-group">
              <label>Registered with MCA? <span class="error"> *</span></label>

            <?php
              $info_mcaregn = array('' => 'Select','yes' => 'Yes','no' => 'No');
                echo form_dropdown('mcaregn', $info_mcaregn, set_value('mcaregn',$empt_details['mcaregn']), 'class="custom-select cls_dis" id="mcaregn"');
                echo form_error('mcaregn');
            ?>
          </div>
          

         <!-- <div class="col-sm-3 form-group">
            <label >Domain Purchased</label>
            <input type="text" name="domainpurch" id="domainpurch" value="<?php echo set_value('domainpurch',$empt_details['domainpurch']);?>" class="form-control cls_dis myDatepickerUSFor" placeholder="MM-DD-YYYY">
            <?php echo form_error('domainpurch'); ?>
          </div>-->
        </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Family Owned?</label>
      <?php
      if(!empty($empt_details['fmlyowned']))
      {
         $familyowned = $empt_details['fmlyowned'];
      }
      else
      {
         $familyowned = "no";
      }
     $fmlyowned = array('' => 'Select','yes' => 'Yes','no' => 'No');
        echo form_dropdown('fmlyowned', $fmlyowned, set_value('fmlyowned',$familyowned), 'class="select2" id="fmlyowned"');
        echo form_error('fmlyowned');
      ?>
    </div>
   
    <div class="col-md-4 col-sm-12 col-xs-34 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label> Mode of verification <span class="error"> *</span></label>
      <?php

        $emp_modeofverification = array('' => 'Select','email' => 'Email','summary email' => 'Summary Email','site visit' => 'Site Visit','verbal' => 'Verbal');

        echo form_dropdown('modeofverification', $emp_modeofverification, set_value('modeofverification',$empt_details['modeofverification']), 'class="select2" id="modeofverification"');
        echo form_error('modeofverification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label>Employment Type</label>
      <?php
       echo form_dropdown('res_employment_type',$this->employment_type,set_value('res_employment_type',$empt_details['res_employment_type']), 'class="select2" id="res_employment_type" ');
        echo form_error('res_employment_type'); 
      ?>
    </div>
    
    </div>
    <div class="row"> 
    <div class="col-md-2 col-sm-12 col-xs-2 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label> Verifiers Role <span class="error"> *</span></label>
      <?php
      $ver_role = array('0' => 'Select Role','hr' => 'HR','admin' => 'Admin','finance' => 'Finance','proprietor' => 'Proprietor','supervisor' => 'Supervisor','pf' => 'PF','other' => 'Other');
        echo form_dropdown('verifiers_role', $ver_role, set_value('verifiers_role',$empt_details['verifiers_role']), 'class="select2" id="verifiers_role"');
        echo form_error('verifiers_role');
      ?> 
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Verifier Name <span class="error"> *</span></label>
      <input type="text" name="verfname" id="verfname" value="<?php echo set_value('verfname',$empt_details['verfname']);?>" class="form-control">
      <?php echo form_error('verfname'); ?>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Contact No <span class="error"> *</span></label>
      <input type="text" name="verifiers_contact_no" id="verifiers_contact_no" value="<?php echo set_value('verifiers_contact_no',$empt_details['verifiers_contact_no']);?>" maxlength="20" class="form-control">
      <?php echo form_error('verifiers_contact_no'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Email ID <span class="error"> *</span></label>
      <input type="text" name="verifiers_email_id" id="verifiers_email_id" value="<?php echo set_value('verifiers_email_id',$empt_details['verifiers_email_id']);?>" maxlength="50" class="form-control">
      <?php echo form_error('verifiers_email_id'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group" <?php if($url == 'UnableToVerify') { echo 'style="display: none;"'; } ?>>
      <label >Verifiers Designation <span class="error"> *</span></label>
      <input type="text" name="verfdesgn" id="verfdesgn" value="<?php echo set_value('verfdesgn',$empt_details['verfdesgn']);?>" class="form-control">
      <?php echo form_error('verfdesgn'); ?>
    </div>

     </div>
    
    </div>
    <div class="row"> 
    
    <div class="col-sm-4 form-group" <?php echo $css_style ?> >
        <label >Domain Name <span class="error"> *</span></label>
        <input type="text" name="domainname" id="domainname" value="<?php echo set_value('domainname',$empt_details['domainname']);?>" class="form-control cls_dis">
        <?php echo form_error('domainname'); ?>
    </div>  

    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label> Closure Date <span class="error"> *</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($empt_details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks <span class="error"> *</span></label>
      <textarea name="remarks" id="remarks" rows="1" class="form-control add_res_remarks emp_rem_update"><?php echo set_value('remarks',$empt_details['remarks']);?></textarea>
      <?php echo form_error('remarks'); ?>
    </div>

  </div>
  <div class="row"> 

    <div class="col-md-4 col-sm-12 col-xs-6 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>

  </div>
       <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachments</label>
         <br>
           <ul class="sortable">
                    <?php        
                    if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
                                $actual_file  = $attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group thumb" id="item-'.$attachments[$i]['id'].'">';
                                echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" ><br>'.$attachments[$i]['file_name'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';

                                if($attachments[$i]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='remove_file[]' value='".$attachments[$i]['id']."' id='remove_file' > Remove Attachment</label>";
                                }

                                if($attachments[$i]['status'] == "2")
                                {
                                   echo "<label><input type='checkbox' name='add_file[]' value='".$attachments[$i]['id']."' id='add_file' > Add Attachment</label>";
                                }
                                echo '</div>';
                            }
                        }
                        ?>

                        <?php        
                        if($vendor_attachments)
                        {
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url = "'".SITE_URL.EMPLOYMENT.$folder_name.'/';
                                $actual_file  = $vendor_attachments[$j]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group thumb" id="item-'.$vendor_attachments[$j]['id'].'">';
                                echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';

                                if($vendor_attachments[$j]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='vendor_remove_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_remove_file' > Remove Attachment</label>";
                                }

                                if($vendor_attachments[$j]['status'] == "2")
                                {
                                   echo "<label><input type='checkbox' name='vendor_add_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_add_file' > Add Attachment</label>";
                                }
                                 
                               echo '</div>';
                              
                            }
                        }
                        ?> 

               </ul>           
        </div>
      <div class="clearfix"></div>

    </div>
</div>

<script>

$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  endDate: new Date()
});

$('.myDatepickerUSFor').datepicker({
  format: 'mm-dd-yyyy',
  autoclose: true,
  endDate: new Date()
});

$('#verifiers_email_id').keyup(function(){

      var verifiers_email_id  = $('#verifiers_email_id').val(); 
      
      if(verifiers_email_id.indexOf('@') != -1)
      {
          var domain_name  = verifiers_email_id.substring(verifiers_email_id.indexOf('@') + 1); 

          if(domain_name == "gmail.com" || domain_name == "yahoo.com" || domain_name == "yahoo.in" || domain_name == "rediffmail.com"){
             $('#domainname').val('');
          }
          else{
             $('#domainname').val(domain_name);
          }
      }

});

$('#modeofverification').on('change',function(){

   var mode_of_verification  =   $('#modeofverification').val();

         if(mode_of_verification == 'email')
           {

               if("<?php echo $url; ?>" == "Clear")
                {
                   var verifiers_designation  = $('#verfdesgn').val(); 

                   $('#verfname').keyup(function(){
                      

                   var verified_name  = document.getElementById("verfname").value;
     
                  $('.emp_rem_update').val('Received email from '+verified_name+' - '+verifiers_designation+' who verified all details as Clear.'); 
 
                   });

                   $('#verfdesgn').keyup(function(){

                      var verifiers_designation  = $('#verfdesgn').val(); 

                      var verified_name  = $('#verfname').val(); 

                     $('.emp_rem_update').val('Received email from '+verified_name+' - '+verifiers_designation+' who verified all details as Clear.'); 
   

                   });
              }
           }

            if(mode_of_verification == 'summary email')
            {
               if("<?php echo $url; ?>" == "Clear")
                {

                   var verifiers_designation  = $('#verfdesgn').val(); 

                  $('#verfname').keyup(function(){

                   var verified_name  = document.getElementById("verfname").value;
     
                  $('.emp_rem_update').val('Spoke to '+verified_name+' - '+verifiers_designation+' who verified all details as clear hence summary email sent.'); 
 
                   });

                    $('#verfdesgn').keyup(function(){

                      var verifiers_designation  = $('#verfdesgn').val(); 

                      var verified_name  = $('#verfname').val(); 

                     $('.emp_rem_update').val('Spoke to '+verified_name+' - '+verifiers_designation+' who verified all details as clear hence summary email sent.'); 
   

                   });
 
                }
           }   

           if(mode_of_verification == 'site visit')
           {
               if("<?php echo $url; ?>" == "Clear")
                {

                   var verifiers_designation  = $('#verfdesgn').val(); 

                  $('#verfname').keyup(function(){


                   var verified_name  = document.getElementById("verfname").value;
     
                  $('.emp_rem_update').val('Site visit done and met '+verified_name+' - '+verifiers_designation+' who verified all details as clear.'); 
 
                
                   });

                     $('#verfdesgn').keyup(function(){

                      var verifiers_designation  = $('#verfdesgn').val(); 

                      var verified_name  = $('#verfname').val(); 

                     $('.emp_rem_update').val('Site visit done and met '+verified_name+' - '+verifiers_designation+' who verified all details as clear.'); 
   

                   });
 
                }
           }   
  });


var date_fieled_check_res = "<?php echo $empt_details['empver_empfrom']; ?>";

var date_fieled_check = "<?php echo $empt_details['employed_from']; ?>";

if(date_fieled_check_res.indexOf('-') == 4)
{

  $('input[name="empfrom"]').prop('type','date'); 
}
else
{

  $('input[name="empfrom"]').prop('type','text');
  $('#empfrom').val(date_fieled_check_res);
}

if(date_fieled_check.indexOf('-') == 4)
{

  $('input[name="employed_from"]').prop('type','date'); 
}
else
{

  $('input[name="employed_from"]').prop('type','text');
  $('#employed_from').val(date_fieled_check);
}

var date_fieled_check1_res = "<?php echo $empt_details['empver_empto']; ?>";

var date_fieled_check1 = "<?php echo $empt_details['employed_to']; ?>";

if(date_fieled_check1_res.indexOf('-') == 4)
{

  $('input[name="empto"]').prop('type','date'); 
}
else
{

  $('input[name="empto"]').prop('type','text');
  $('#empto').val(date_fieled_check1_res);
}

if(date_fieled_check1.indexOf('-') == 4)
{

  $('input[name="employed_to"]').prop('type','date'); 
}
else
{

  $('input[name="employed_to"]').prop('type','text');
  $('#employed_to').val(date_fieled_check1);
}

$('input[name = "calender_display_employee_from"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empfrom"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empfrom"]').prop('type','text');
      }
     
    
  });

$('input[name = "calender_display_employee_from_res"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="res_empfrom"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="res_empfrom"]').prop('type','text');
      }
     
    
  });

   $('input[name = "calender_display_employee_to"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empto"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empto"]').prop('type','text');
      }
     
    
  });

    $('input[name = "calender_display_employee_to_res"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="res_empto"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="res_empto"]').prop('type','text');
      }
     
    
  });

  $(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1); 
    var actual_value=document.getElementById(nameArr).value;
    var rtb_val = $(this).val();
   
    if(rtb_val == 'no'){
      if(txt_val == 'res_nameofthecompany')
      {
      $('#res_co_name').val('');
      $('#res_co_name').removeAttr("readonly");
      }
      $("#"+txt_val).val("");
      $("#"+txt_val).removeAttr("readonly");
    } 
    else if(rtb_val == 'yes'){
      if(txt_val == 'res_nameofthecompany')
      {
        $('#res_co_name').val($('#co_name').val());
        $('#res_co_name').attr("readonly","true");
      }
      $("#"+txt_val).val(actual_value);
      $("#"+txt_val).attr("readonly","true");
    }
      else if(rtb_val == 'not-disclosed') {
      $("#"+txt_val).val("Not Disclosed");
      $("#"+txt_val).attr("readonly","true");
    }
});  

</script>
<script src="<?= SITE_JS_URL ?>jquery-ui.min.js"></script>
<script type="text/javascript">
  $("ul.sortable" ).sortable({
    revert: 100,
    placeholder: 'placeholder'
  });

  $( "ul.sortable" ).disableSelection();
</script>
<script type="text/javascript">
   $(".select2").select2();
</script>