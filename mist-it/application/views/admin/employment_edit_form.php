<?php
if(strpos($empt_details['empfrom'],"-") == 4) {
   $empfrom  = date("Y-m-d", strtotime($empt_details['empfrom']));
} else {
   $empfrom  =  $empt_details['empfrom'];
}

if(strpos($empt_details['empto'],"-") == 4) {
   $empto  = date("Y-m-d", strtotime($empt_details['empto']));
} else {
   $empto  =  $empt_details['empto'];
}
?>

  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
          Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect btn-sm edit_btn_click1" data-frm_name='frm_emp_update' data-editUrl="<?= ($this->permission['access_employment_list_edit']) ? encrypt($empt_details['id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
              <li><button class="btn btn-secondary waves-effect btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'employment/delete/'.encrypt($empt_details['id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
              
            </ol>
          </div>
      </div>
    </div>
  </div>
<div class="box-body">
<?php echo form_open_multipart('#', array('name'=>'frm_emp_update','id'=>'frm_emp_update')); ?>
  <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
  <input type="hidden" name="clientid" id="clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
     <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
     <input type="hidden" name="candidateid" readonly="readonly" id="candidateid" value="<?php echo set_value('candidateid',$get_cands_details['candsid']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
  <div class="row">       
  <div class="col-sm-4 form-group">
    <label>Candidate Name<span class="error"> *</span></label>
    <input type="text" name="candsid" readonly="readonly" id="candsid" value="<?php echo set_value('candsid',$empt_details['CandidateName']); ?>" class="form-control">
    <?php echo form_error('candsid'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Client Name<span class="error"> *</span></label>
    <input type="text" name="clientname" readonly="readonly" id="candsid" value="<?php echo set_value('clientname',$empt_details['clientname']); ?>" class="form-control">
  </div>
  <div class="col-sm-4 form-group">
    <label>Entity</label>
    <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$get_cands_details['entity_name']); ?>" class="form-control">
  </div>
  </div>
  <div class="row"> 
  <div class="col-sm-4 form-group">
    <label>Package</label>
    <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$get_cands_details['package_name']); ?>" class="form-control">
  </div>
  <div class="col-sm-4 form-group">
    <label>Case received date</label>
    <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($get_cands_details['caserecddate'])); ?>" class="form-control">
  </div>
  <div class="col-sm-4 form-group">
    <label>Client Ref No</label>
    <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control">
  </div>
 </div>
 <div class="row">   
  <div class="col-sm-4 form-group">
    <label>Component Ref No</label>
    <input type="text" name="emp_com_ref" id="emp_com_ref" readonly="readonly" value="<?php echo set_value('emp_com_ref',$empt_details['emp_com_ref']); ?>" class="form-control">
    <?php echo form_error('emp_com_ref'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Candidate Ref No</label>
    <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$empt_details['cmp_ref_no']); ?>" class="form-control">
    <?php echo form_error('cmp_ref_no'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Initiation date<span class="error"> *</span></label>
    <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($empt_details['iniated_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
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
         <input type="text" name="mod_of_veri" readonly="readonly" id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->empver)) { echo $mode_of_verification_value->empver; } ?>" class="form-control cls_disabled">
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
       <input type="text" name="build_date" id="build_date"  value="<?php echo set_value('build_date',$empt_details['build_date']); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
       <?php echo form_error('build_date'); ?>
    </div> 

      <div class="col-sm-4 form-group">
        <label>Status </label>
        <input type="text" name="status" id="status" readonly="readonly" value="<?php echo set_value('status',$empt_details['verfstatus']); ?>" class="form-control">
        <?php echo form_error('status'); ?>
      </div>
    </div>
   <div class="row">
      <div class="col-sm-4 form-group">
        <label>Re Initiation date<span class="error"> *</span></label>
        <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',$empt_details['emp_re_open_date']); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
        <?php echo form_error('re_iniated_date'); ?>
      </div>
  </div>

  <div class="text-white div-header">
    Previous Employment Details
  </div>
  <br>
<div class="row">
 <!--<div class="col-sm-4 form-group">
  <label>Company Name<span class="error"> *</span></label>
       <input type="text" name="actual_company_name" id="actual_company_name" value="<?php echo set_value('actual_company_name',$empt_details['actual_company_name']); ?>" class="form-control cls_disabled" readonly>
  </div>-->
  <div class="col-sm-4 form-group">
      <label>Company Name <span class="error"> *</span> </label>
    
      <?php
       echo form_dropdown('nameofthecompany',$company, set_value('nameofthecompany'), 'class="form-control" id="nameofthecompany"');
        echo form_error('nameofthecompany'); 
      ?> 
      
  </div>
   <input type="hidden" name="selected_company_name" id="selected_company_name" value="<?php echo set_value('selected_company_name',$empt_details['actual_company_name']); ?>" class="form-control"> 
  <div class="col-sm-4 form-group">
    <label>Deputed Company</label>
    <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company',$empt_details['deputed_company']); ?>" class="form-control cls_disabled">
    <?php echo form_error('deputed_company'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Previous Employee Code <span class="error error_previous_emp_code" style="display: none"> *</span> </label>
    <input type="text" name="empid" id="empid" value="<?php echo set_value('empid',$empt_details['empid']); ?>" class="form-control cls_disabled">
    <?php echo form_error('empid'); ?>
  </div>
  </div>
  <div class="row">
  <div class="col-sm-4 form-group">
    <label>Employment Type</label>
    <?php echo form_dropdown('employment_type', $this->employment_type, set_value('employment_type',$empt_details['employment_type']), 'class="select2 cls_disabled" id="employment_type"');
      echo form_error('employment_type'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Employed From 
        <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="text_value"> : Text <span class="error"> *</span>     
      <!--<i class="fa fa-info-circle removeDatePicker" data-val='empfrom' data-toggle="tooltip" title="Remove Calender"></i>-->
    </label>
    <input type="date" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom',$empfrom); ?>" class="form-control cls_disabled">
    <?php echo form_error('empfrom'); ?>
  </div>
  
  <div class="col-sm-4 form-group">
    <label>Employed To<input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="text_value"> : Text <span class="error"> *</span> </label>
    <input type="date" name="empto" id="empto" value="<?php echo set_value('empto',$empto); ?>" class="form-control cls_disabled">
    <?php echo form_error('empto'); ?>
  </div>
  </div>
  <div class="row">
  <div class="col-sm-4 form-group">
    <label>Designation <span class="error"> *</span></label>
    <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$empt_details['designation']); ?>" class="form-control cls_disabled">
    <?php echo form_error('designation'); ?>
  </div>
   <div class="col-sm-4 form-group">
    <label>Remuneration</label>
    <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration',$empt_details['remuneration']); ?>" class="form-control cls_disabled">
    <?php echo form_error('remuneration'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label for="reasonforleaving">Reason for Leaving </label>
    <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control cls_disabled"><?php echo set_value('reasonforleaving',$empt_details['reasonforleaving']);?></textarea>
    <?php echo form_error('reasonforleaving'); ?>
  </div>
  </div>
  
  <div class="row">
    <div class="col-sm-4 form-group">
      <label>UAN No</label>
      <input type="text" name="uan_no" id="uan_no" value="<?php echo set_value('uan_no',$empt_details['uan_no']); ?>" class="form-control">
      <?php echo form_error('uan_no'); ?>
    </div> 
    <div class="col-sm-4 form-group">
      <label>UAN Remark</label>
      <textarea name="uan_remark" rows="1" id="uan_remark" class="form-control cls_disabled"><?php echo set_value('uan_remark',$empt_details['uan_remark']);?></textarea>
      <?php echo form_error('uan_remark'); ?>
    </div> 
  </div>   

  <div class="text-white div-header">
      Company Contact Details
  </div>
  <br>
  <div class="row">
  <div class="col-sm-3 form-group">
    <label>Name of HR</label>
    <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name',$empt_details['compant_contact_name']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact_name'); ?>
  </div>
  <div class="col-sm-3 form-group">
    <label>HR's Designation</label>
    <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation',$empt_details['compant_contact_designation']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact_designation'); ?>
  </div>
   <div class="col-sm-3 form-group">
      <label>HR's Email ID</label>
      <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email',$empt_details['compant_contact_email']); ?>" class="form-control">
      <?php echo form_error('compant_contact_email'); ?>
    </div>
   <div class="col-sm-3 form-group">
    <label>HR's Contact No</label>
    <input type="text" name="compant_contact" maxlength="13" maxlength="6" id="compant_contact" value="<?php echo set_value('compant_contact',$empt_details['compant_contact']); ?>" class="form-control cls_disabled">
    <?php echo form_error('compant_contact'); ?>
  </div>
  </div>
  <div class="row">
  <div class="col-sm-4 form-group">
    <label>Street Address</label>
    <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control cls_disabled"><?php echo set_value('locationaddr',$empt_details['locationaddr']); ?></textarea>
    <?php echo form_error('locationaddr'); ?>
  </div>
  <div class="col-sm-3 orm-group">
    <label>City/Branch(Location) <span class="error error_city" style="display: none"> *</span></label>
    <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality',$empt_details['citylocality']); ?>" class="form-control cls_disabled">
    <?php echo form_error('citylocality'); ?>
  </div>
  <div class="col-sm-3 form-group">
    <label>State</label>
    <?php echo form_dropdown('state', $states, set_value('state',strtolower($empt_details['state'])), 'class="select2 cls_disabled" id="state"');
      echo form_error('state');?>
  </div>
  <div class="col-sm-2 form-group">
    <label>Pincode</label>
    <input type="text" name="pincode" mxnlength="6" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$empt_details['pincode']); ?>" class="form-control cls_disabled">
    <?php echo form_error('pincode'); ?>
  </div>
  </div>  
  <div class="text-white div-header">
     Reporting Manager Details
  </div>
  <br>
  <div class="row">
  <div class="col-sm-3 form-group">
      <label>Manager Name</label>
      <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name',$empt_details['r_manager_name']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_name'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Manager's contact</label>
      <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no',$empt_details['r_manager_no']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_no'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Designation</label>
      <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation',$empt_details['r_manager_designation']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_designation'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Manager's Email ID</label>
      <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email',$empt_details['r_manager_email']); ?>" class="form-control cls_disabled">
      <?php echo form_error('r_manager_email'); ?>
    </div>
    </div>
    <div class="text-white div-header">
      Supervisor Details
    </div>
    <br>
    <input type="hidden" name="hdn_counter" id="hdn_counter" value="1">
    <div id="supervisor_details"><div class="add_supervisor_details_row"></div>
    </div><br>

  <div class="text-white div-header">
    Attachments & other
  </div>
  <br>
  <div class="row">
  <div class="col-sm-4 form-group">
    <label>Other</label>
    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
    <?php echo form_error('attchments'); ?>
  </div>

  <div class="col-sm-4 form-group">
      <label>Experience/Relieving Letter<span class="error error_relieving_letter" style="display: none"> *</span></label>
      <input type="file" name="attchments_reliving[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>LOA<span class="error error_loa" style="display: none"> *</span></label>
      <input type="file" name="attchments_loa[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
  <!--<div class="col-sm-4 form-group">
    <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
    <input type="file" name="attchments_CS[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
    <?php echo form_error('attchments'); ?>
  </div>-->
   <div class="col-sm-4 form-group">
      <label>Copy Attachment Selection<span class="error">*</span></label>
      <?php
        $copy_attachment_selection = array(''=>'Selection Attachment','0'=>'Others','3'=>'Experience/Relieving Letter','4'=>'LOA');
        echo form_dropdown('copy_attachment_selection', $copy_attachment_selection, set_value('copy_attachment_selection'), 'class="form-control" id="copy_attachment_selection"');
        echo form_error('copy_attachment_selection');
      ?>
    </div>
  <div class="col-sm-4 form-group">
    <label >Executive Name<span class="error"> *</span></label>
    <?php
      echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$empt_details['has_case_id']), 'class="select2 cls_disabled" id="has_case_id"');
      echo form_error('has_case_id');
    ?>
  </div>
  </div>
  

  <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
       <th style='border: 1px solid black;padding: 8px;'>Uploaded AT</th>
       <th style='border: 1px solid black;padding: 8px;'>Action</th>
    </tr>
    <tr>
      <?php 
        $i = 1;  
        foreach ($attachments as $key => $value) {
       

        $url  = SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
        if($value['type'] == 0)
        {
          echo '<tr>'; 
        ?>

         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'>Others</td>

        <?php
          echo "<td style='border: 1px solid black; text-align:center;'><a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a></td>";
          echo '</tr>';
        }

        if($value['type'] == 2)
        {
          echo '<tr>'; 
        ?>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'>Insuff Cleared</td>

        <?php
          echo "<td style='border: 1px solid black; text-align:center;'><a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a></td>";
          echo '</tr>';
        }

        if($value['type'] == 3)
        {
           echo '<tr>'; 
        ?>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'>Experience/Relieving Letter</td>

        <?php
           echo "<td style='border: 1px solid black; text-align:center;'><a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a></td>";
          echo '</tr>';
        }

        if($value['type'] == 4)
        {
           echo '</tr>';
        ?>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'>LOA</td>

        <?php
          echo "<td style='border: 1px solid black; text-align:center;'><a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a></td>";
          echo '</tr>';
        }
      } 
      ?>

    </tr>
  </table>
    <br>
    <div class="clearfix"></div> 
    <input type="hidden" name="upload_capture_image_employment" id="upload_capture_image_employment" value="">
  <div class="col-sm-8 form-group">
    <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_emplyment"id="c_attachment" disabled>Copy Attachment</button>

  </div>

  <div class="clearfix"></div>

 


  
<?php echo form_close(); ?>




<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
  <div style="float: right;">
      <button class="btn btn-info" id="hr_database" data-url="<?= ADMIN_SITE_URL.'employment/get_hr_database_details/'.$empt_details['nameofthecompany'] ?>" >HR DB</button>
    
      <button class="btn btn-info" data-url="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/6/'.$empt_details['id'] ?>" data-toggle="sln" id="sln">SLA</button>
      <button class="btn btn-info" id="generic_main" data-url="<?= ADMIN_SITE_URL.'employment/generic_mail/'.$empt_details['id'] ?>" >Generic Mail</button>
      <button class="btn btn-info" id="emp_initiation_mail" data-url="<?= ADMIN_SITE_URL.'employment/emp_email_tem_view/'.$empt_details['id'] ?>">Initiation Mail</button>
     <!-- <button class="btn btn-info" data-url="<?= ADMIN_SITE_URL.'employment/follw_up/'.$empt_details['id'] ?>" data-toggle="follow_up" id="follow_up">Follow Up Mail</button>-->
      <button class="btn btn-info"  id="summery_mail" data-url="<?= ADMIN_SITE_URL.'employment/summary_mail/'.$empt_details['id'] ?>" data-toggle="summery_mail" >Summary Mail</button> 
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/6/'.$empt_details['id'] ?>/employment/" <?=$check_insuff_raise?>   data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>
</div>

<div id="capture_image_copy_emplyment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_employment','id'=>'frm_upload_copied_image_employment')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy_employment" name="copy_employment">
      <div class="status_employment">
        <p id="errorMsg_employment" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result_employment"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_employment" name="upload_copied_image_employment">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>

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
$('.cls_disabled').prop('disabled', true);

/*$(document).on('click', '.edit_btn_click2', function(){

    var editUrl = $(this).attr('data-editUrl');

    if(editUrl != "")
    {
       $('.cls_dis').prop('disabled', false);
    }
    else
        show_alert('Access Denied, You don’t have permission to access this page');

});
*/

var date_fieled_check = "<?php echo $empt_details['empfrom']; ?>";

if(date_fieled_check.indexOf('-') == 4)
{

  $('input[name="empfrom"]').prop('type','date'); 
}
else
{

  $('input[name="empfrom"]').prop('type','text');
  $('#empfrom').val(date_fieled_check);
}

var date_fieled_check1 = "<?php echo $empt_details['empto']; ?>";

if(date_fieled_check1.indexOf('-') == 4)
{
  $('input[name="empto"]').prop('type','date'); 
}
else
{
    $('input[name="empto"]').prop('type','text');
    $('#empto').val(date_fieled_check1);
}
$(document).on('click', '.edit_btn_click1', function(){
    var editUrl = $(this).attr('data-editUrl');
    var frm_name = $(this).attr('data-frm_name');
    if(editUrl != "")
    {
        $('#'+frm_name+" :input,textarea,select").prop("disabled", false);
        $('.multiselect').removeClass('disabled');
        $('#c_attachment').attr('disabled','true');
    }
    else
        show_alert('Access Denied, You don’t have permission to access this page');
});


$('#copy_attachment_selection').change(function(e){
        var copy_attachment_selection = $('#copy_attachment_selection').val(); 
        if(copy_attachment_selection == '')
        {
           $('#c_attachment').attr('disabled','true');
        }
        else
        {
           $('#c_attachment').removeAttr('disabled');
        }
    });


$('#company_web_status_add').click(function(){

  $('#company_web_status').validate({ 
    rules: {
      comp_table_id : {
        required : true
      },
      justdialwebcheck : {
       required : true
      },
      mcaregn : {
        required : true
      },
      domainname : {
       required : true
     //  valDomain : true
      }
    
    },
    messages: {
      comp_table_id : {
        required : "Update ID missing"
      },
      
      justdialwebcheck : {
       required : "Select Check Status"
      },
      mcaregn : {
        required :"Select Reg with MCA"
      },
      domainname : {
       required : "Select Domain Name"
      // valDomain : "Enter Valid Domain Name"
      }
    },
    submitHandler: function(form) 
    {      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'employment/update_company_web_status'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#company_web_status_add').attr('disabled','disabled');
        },
        complete:function(){
         // $('#company_web_status_add').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            $("#web_status").modal('hide');
           location.reload();
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
 }); 
  
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

 $('.remove_file').on('click',function(){
  var id =  <?= ($this->permission['access_employment_list_edit']) ? $empt_details['id'] : '"permission denied"'?>;
  if(id != "permission denied")
  {  

      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'employment/remove_uploaded_file/' ?>"+remove_id,
              type: 'GET',
              beforeSend:function(){
                //show_alert('deleting...','info');
              },
              complete:function(){
                $('#'+remove_id).remove();                
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == <?php echo SUCCESS_CODE; ?>)
                {
                  show_alert(message,'success'); 
                }
                else
                {
                  show_alert(message,'danger'); 
                }
                location.reload(); 
              }
          });
      } 
      else 
      {
        return;
      }
    }
    else 
    {
       alert('You have not permission to delete the file'); 
    } 
    });         
 
</script>
<script type="text/javascript">
  $(".select2").select2();    
  $("#nameofthecompany").select2({
      placeholder: "Select Company",
      multiple: false,
      minimumInputLength: 3,
      ajax: 
      {
          url: "<?php echo ADMIN_SITE_URL.'employment/get_company_details' ?>",
          dataType: 'json',
          data: function(term) {
              return {
                  q: term
              };
          },
          processResults: function(response) {
            
            var myResults = [];
              $.each(response, function (index, item) {
                    myResults.push({
                        'id': item.id,
                        'text': item.company_name
                    });
                });
              return {results: myResults};
          },
          cache: true
      },
    
  });

  $("#nameofthecompany").val(<?php echo $empt_details['nameofthecompany']; ?>).trigger("change"); 

 
  $('#nameofthecompany').change(function(){
    
    var selected_company =  $(this).find(":selected").text();

    $('#selected_company_name').val(selected_company);
    
    if(selected_company == "Other")
    {
      var id = '<?php echo ADMIN_SITE_URL.'company_database/addAddCompanyModelEmployment'?>';
        $('.append_model').load(id,function(){
          $('#addAddCompanyModel').modal('show');
          $('#addAddCompanyModel').addClass("show");
          $('#addAddCompanyModel').css({background: "#0000004d"});
        }); 
    }


   $.ajax({  
                     
      url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
      type: "post",
      async:false,
      data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
      success: function(response){
        if(response.branch_location == '1'){  
          $('.error_city').css('display','inline-block'); 
        }else{
          $('.error_city').css('display','none');
        }
        if(response.previous_emp_code == '1'){ 
          $('.error_previous_emp_code').css('display','inline-block'); 
        }else{
          $('.error_previous_emp_code').css('display','none');
        }
        if(response.experience_letter == '1'){ 
          $('.error_relieving_letter').css('display','inline-block'); 
        }else{
          $('.error_relieving_letter').css('display','none');
        }
        if(response.loa == '1'){ 
          $('.error_loa').css('display','inline-block'); 
        }else{
          $('.error_loa').css('display','none');
        }
                     
      }
    });
  
  }); 
</script>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
  
  const errorEl = document.getElementById('errorMsg_employment')

  async function askWritePermission() {
    try {
      const { state } = await navigator.permissions.query({ name: 'clipboard-write', allowWithoutGesture: false })
      return state === 'granted'
    } catch (error) {
      errorEl.textContent = `Compatibility error (ONLY CHROME > V66): ${error.message}`
      console.log(error)
      return false
    }
  }

  const canWrite = await askWritePermission()
  const setToClipboard = blob => {
    const data = [new ClipboardItem({ [blob.type]: blob })]
    console.log(data);
    console.log(navigator.clipboard);
    return navigator.clipboard.write(data)
  }
})

$('#upload_copied_image_employment').on('click',function() {
      var get_image_name =  $('#upload_capture_image_employment').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result_employment img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_employment').val(get_image_base);
          $('#capture_image_copy_emplyment').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });
</script>
<style>
.image-content-result_employment {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result_employment::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result_employment:not(:empty)::after,
.image-content-result_employment:focus::after {
  display: none;
}

.image-content-result_employment * {
  max-width: 100%;
  border: 0;
}
</style>