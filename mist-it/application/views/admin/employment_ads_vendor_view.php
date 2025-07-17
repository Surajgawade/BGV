<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Candidate Deatils</h5>
</div>

<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="view_vendor_master_log_id" id="view_vendor_master_log_id" value="<?php echo set_value('view_vendor_master_log_id',$details['view_vendor_master_log_id']); ?>">
<div class="row">

<div class="col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Comp ref no</label>
  <input type="text" name="emp_com_ref" id="emp_com_ref"  value="<?php echo set_value('emp_com_ref',$details['emp_com_ref']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

</div>
  <hr style="border-top: 2px solid #bb4c4c;">
  <div class="box-header">
    <h5 class="box-title">Previous Employment Details</h5>
  </div>
  <div class="row">
<div class="col-md-4 col-sm-8 col-xs-4 form-group">
    <label>Company Name<span class="error"> *</span></label>
    <?php
     echo form_dropdown('nameofthecompany', $company, set_value('nameofthecompany',$details['nameofthecompany']), 'class="form-control singleSelect cls_disabled" id="nameofthecompany"');
      echo form_error('nameofthecompany'); 
    ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Deputed Company</label>
    <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company',$details['deputed_company']); ?>" class="form-control cls_disabled">
    <?php echo form_error('deputed_company'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Previous Employee Code <span class="error"> *</span></label>
    <input type="text" name="empid" id="empid" value="<?php echo set_value('empid',$details['empid']); ?>" class="form-control cls_disabled">
    <?php echo form_error('empid'); ?>
  </div>
 </div>
<div class="row">
  <div class="col-md-4 col-sm-8 col-xs-4 form-group">
    <label>Employment Type</label>
    <?php echo form_dropdown('employment_type', $this->employment_type, set_value('employment_type',$details['employment_type']), 'class="form-control cls_disabled" id="employment_type"');
      echo form_error('employment_type'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Employed From <span class="error"> *</span> <i class="fa fa-info-circle removeDatePicker" data-val='empfrom' data-toggle="tooltip" title="Remove Calender"></i></label>
    <input type="text" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom',$details['empfrom']); ?>" class="form-control cls_disabled" placeholder='DD-MM-YYYY'>
    <?php echo form_error('empfrom'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Employed To <span class="error"> *</span> <i class="fa fa-info-circle removeDatePicker" data-val='empto' data-toggle="tooltip" title="Remove Calender"></i> </label>
    <input type="text" name="empto" id="empto" value="<?php echo set_value('empto',$details['empto']); ?>" class="form-control cls_disabled" placeholder='DD-MM-YYYY'>
    <?php echo form_error('empto'); ?>
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Designation<span class="error"> *</span></label>
    <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$details['designation']); ?>" class="form-control cls_disabled">
    <?php echo form_error('designation'); ?>
  </div>
   <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Remuneration<span class="error"> *</span></label>
    <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration',$details['remuneration']); ?>" class="form-control cls_disabled">
    <?php echo form_error('remuneration'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label for="reasonforleaving">Reason for Leaving</label>
    <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control cls_disabled"><?php echo set_value('reasonforleaving',$details['reasonforleaving']);?></textarea>
    <?php echo form_error('reasonforleaving'); ?>
  </div>

    
  </div>
  <hr style="border-top: 2px solid #bb4c4c;">

  <div class="box-header">
    <h5 class="box-title">Company Contact Details</h5>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Company Contact No</label>
    <input type="text" name="compant_contact" maxlength="13" maxlength="6" id="compant_contact" value="<?php echo set_value('compant_contact',$details['compant_contact']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Company Contact Name</label>
    <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name',$details['compant_contact_name']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact_name'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Company Contact Designation</label>
    <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation',$details['compant_contact_designation']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact_designation'); ?>
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Street Address</label>
    <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control cls_disabled"><?php echo set_value('locationaddr',$details['locationaddr']); ?></textarea>
    <?php echo form_error('locationaddr'); ?>
  </div>
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>City</label>
    <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality',$details['citylocality']); ?>" class="form-control cls_disabled">
    <?php echo form_error('citylocality'); ?>
  </div>
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>State</label>
    <?php echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="form-control cls_disabled" id="state"');
      echo form_error('state');?>
  </div>
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Pincode</label>
    <input type="text" name="pincode" mxnlength="6" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_disabled">
    <?php echo form_error('pincode'); ?>
  </div>
 </div>  
  <hr style="border-top: 2px solid #bb4c4c;">

  <div class="box-header">
    <h5 class="box-title">Reporting Manager Details</h5>
  </div>

  <div class="row">
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager Name</label>
      <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name',$details['r_manager_name']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_name'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's contact</label>
      <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no',$details['r_manager_no']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_no'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Designation</label>
      <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation',$details['r_manager_designation']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_designation'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's Email ID</label>
      <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email',$details['r_manager_email']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_email'); ?>
    </div>
  
</div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h4>  Others </h4>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
  <?php
      echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$details['has_case_id']), 'class="custom-select cls_readonly" id="has_case_id"');
      echo form_error('has_case_id');
    ?>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>



<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Check Status</label>
    <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','closed'=>'Closed');
      echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="form-control" id="status"');
      echo form_error('status');
    ?>
</div>

<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,pdf files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control">
        <?php echo form_error('attchments'); ?>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>Vendor Closure Date</label><span class="error">*</span>
    <input type="text" name="vendor_date" value="<?php echo set_value('vendor_date',convert_db_to_display_date($details['vendor_date'])); ?>" class="form-control myDatepicker">
  </div>
</div>

</div>

<h5>Vendor Attachments</h5>
  <?php  if(!empty($attachments)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
       
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments as $key => $value) {
       
        $folder_name = "vendor_file";
        $url  = SITE_URL.EMPLOYMENT.$folder_name.'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>
      <?php } ?>
    </tr>
  </table>
<script>$('.cls_readonly').prop('disabled',true);</script>
<script>
$('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });
</script>