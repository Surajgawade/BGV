 <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
          Case Details
        </div>
        <br>  
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect btn-sm  edit_btn_click" data-frm_name='frm_drugs_update' data-editUrl="<?= ($this->permission['access_drugs_list_edit']) ? encrypt($selected_data['drug_narcotis_id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
            
              <li>    <button class="btn btn-secondary waves-effect btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'court_verificatiion/delete/'.encrypt($selected_data['drug_narcotis_id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_drugs_update','id'=>'frm_drugs_update')); ?>
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Candidate Name</label>
        <input type="hidden" name="drug_narcotis_id" value="<?php echo set_value('drug_narcotis_id',$selected_data['drug_narcotis_id']); ?>" >
        <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" >
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">

        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
        <input type="text" name="CandidateName" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Client</label>
        <input type="text" name="clientname" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control readonly">
      </div>
      <div class="col-md-4 form-group">
        <label>Entity</label>
        <input type="text" name="entity_name" value="<?php echo set_value('entity_name',$get_cands_details['entity_name']); ?>" class="form-control readonly">
      </div>
      </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Package</label>
        <input type="text" name="package_name" value="<?php echo set_value('package_name',$get_cands_details['package_name']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Case received date</label>
        <input type="text" name="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($get_cands_details['caserecddate'])); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Client Ref No</label>
        <input type="text" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control readonly">
      </div>
      </div>
      <div class="row">
      <div class="col-md-4 form-group">
        <label>Component Ref No</label>
        <input type="text" name="drug_com_ref" id="drug_com_ref" readonly value="<?php echo set_value('drug_com_ref',$selected_data['drug_com_ref']); ?>" class="form-control">
        <?php echo form_error('drug_com_ref'); ?>
      </div>
      <div class="col-md-4 form-group">
        <label>Candidate Ref No</label>
        <input type="text" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
      </div>
      <div class="col-md-4 form-group">
        <label>Comp Int Date<span class="error"> *</span></label>
        <input type="text" name="iniated_date" id="iniated_date"  value="<?php echo set_value('iniated_date',convert_db_to_display_date($selected_data['iniated_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
        <?php echo form_error('iniated_date'); ?>
      </div>
      </div>

    <?php      
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Mode of Verification</label>
         <input type="text" name="mode_of_veri" readonly="readonly"  id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->narcver)) { echo $mode_of_verification_value->narcver; } ?>" class="form-control cls_disabled">
        <?php echo form_error('mod_of_veri'); ?>
      </div>

     <?php   
       if($this->user_info['bill_date_permission'] == "yes")
        {
        ?>
        <div class="col-sm-4 form-group">
        <?php 
        }else
        { ?>
        <div class="col-sm-4 form-group" style="display: none;">
      <?php 
       } 
      ?>
      <label>Billed Date</label>
       <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',$selected_data['build_date']); ?>" class="form-control  cls_disabled" placeholder='DD-MM-YYYY'>
       <?php echo form_error('build_date'); ?>
    </div>


      <div class="col-sm-4 form-group">
        <label>Status </label>
        <input type="text" name="status" id="status" readonly="readonly" value="<?php echo set_value('status',$selected_data['verfstatus']); ?>" class="form-control">
        <?php echo form_error('status'); ?>
      </div>
    </div>
    <div class="row">
       <div class="col-sm-4 form-group">
        <label>Re-Initiated date<span class="error"> *</span></label>
       <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',$selected_data['drug_re_open_date']); ?>" class="form-control cls_disabled myDatepicker" placeholder='DD-MM-YYYY'>
        <?php echo form_error('iniated_date'); ?>
      </div>
     </div>
      <div class="text-white div-header">
        Candidate Details
      </div>
      <br>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Date of Birth<span class="error"> *</span></label>
        <input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo set_value('date_of_birth',convert_db_to_display_date($get_cands_details['DateofBirth'])); ?>" class="form-control myDatepicker readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('date_of_birth'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Gender<span class="error"> *</span></label>
       <?php
          echo form_dropdown('gender', GENDER, set_value('gender',$get_cands_details['gender']), 'class="select2 cls_disabled" id="gender"');
          echo form_error('gender');
        ?>
      </div>
  
      </div>
      <div class="text-white div-header">
        <h5 class="box-title">Appointment Details</h5>
      </div>
      <br>
      <div class="row">
      <div class="col-sm-6 form-group">
        <label>Appointment Date</label>
        <input type="text" name="appointment_date" id="appointment_date" value="<?php echo set_value('appointment_date',convert_db_to_display_date($selected_data['appointment_date'])); ?>" class="form-control myDatepicker cls_disabled" Placeholder='DD-MM-YYYY'>
        <?php echo form_error('appointment_date'); ?>
      </div>
      <div class="col-sm-6 form-group">
        <label>Appointment Time</label>
        <input type="text" name="appointment_time" id="appointment_time" value="<?php echo set_value('appointment_time',$selected_data['appointment_time']); ?>" class="form-control myTimepicker cls_disabled" Placeholder='HH:MM'>
        <?php echo form_error('appointment_time'); ?>
      </div>
  
      </div>
   
     <div class="row">
    <div class="col-sm-4 form-group">
      <label>Drug Test Panel/Code<span class="error"> *</span></label>
       <?php
        $drug_test_code = array(''=>'Select','5 panel'=> '5 Panel','7 panel'=> '7 Panel');
        echo form_dropdown('drug_test_code', $drug_test_code, set_value('drug_test_code',$selected_data['drug_test_code']), 'class="select2 cls_disabled" id="drug_test_code"');
        echo form_error('drug_test_code');
      ?> 
    </div>
  
      <div class="col-sm-4 form-group">
        <label>Street Address<span class="error"> *</span></label>
        <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control cls_disabled"><?php echo set_value('street_address',$selected_data['street_address']); ?></textarea>
        <?php echo form_error('street_address'); ?>
      </div>
      </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>City<span class="error"> *</span></label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$selected_data['city']); ?>" class="form-control cls_disabled">
        <?php echo form_error('city'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>State<span class="error"> *</span></label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$selected_data['state']), 'class="select2 cls_disabled" id="state"');
          echo form_error('state');
        ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Pincode<span class="error"> *</span></label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$selected_data['pincode']); ?>" class="form-control cls_disabled">
        <?php echo form_error('pincode'); ?>
      </div>

      </div>
      <div class="text-white div-header">
        Attachments & other
      </div>
      <br>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Data Entry</label>
        <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
        <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Executive Name<span class="error">*</span></label>
        <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$selected_data['has_case_id']), 'class="select2 cls_disabled" id="has_case_id "');
          echo form_error('has_case_id');
        ?>
      </div>
    
    <div class="row">   
    <div class="col-sm-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
    
      $url  = SITE_URL.DRUGS.$selected_data['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="col-sm-6 form-group"> 
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {

      $url  = SITE_URL.DRUGS.$selected_data['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    </div> 
<br>
      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
        <input type="submit" name="btn_drug_update" id='btn_drug_update' value="Submit" class="btn btn-success cls_disabled">
      </div>
    
  <?php echo form_close(); ?>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/8/'.$selected_data['drug_narcotis_id'] ?>/drugs_narcotics" <?=$check_insuff_raise?> data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);
  $('.cls_disabled').prop('disabled', true);
});

function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}
</script>

<script type="text/javascript">
  $(".select2").select2();
</script>