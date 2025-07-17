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
       <input type="text" name="build_date" id="build_date"  value="<?php echo set_value('build_date',$empt_details['build_date']); ?>" class="form-control cls_disabled" placeholder='DD-MM-YYYY'>
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
  
  <div class="col-sm-4 form-group">
      <label>Company Name <span class="error"> *</span> </label>
    
      <?php
       echo form_dropdown('nameofthecompany',$company, set_value('nameofthecompany'), 'class="form-control cls_disabled" id="nameofthecompany"');
        echo form_error('nameofthecompany'); 
      ?> 
      
  </div>
    <input type="hidden" name="company_id" id="company_id" value="<?php echo set_value('company_id',$empt_details['nameofthecompany']); ?>" class="form-control"> 
   <input type="hidden" name="selected_company_name" id="selected_company_name" value="<?php echo set_value('selected_company_name',$empt_details['actual_company_name']); ?>" class="form-control"> 
  <div class="col-sm-4 form-group">
    <label>Deputed Company</label>
    <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company',$empt_details['deputed_company']); ?>" class="form-control cls_disabled">
    <?php echo form_error('deputed_company'); ?>
  </div>
  <div class="col-sm-4 form-group">
    <label>Previous Employee Code  </label>
    <input type="text" name="empid" id="empid" value="<?php echo set_value('empid',$empt_details['empid']); ?>" class="form-control cls_disabled">
    <span class="error error_previous_emp_code" style="display: none"> Enter Previous Employee caode</span>
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
      <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email',$empt_details['compant_contact_email']); ?>" class="form-control cls_disabled">
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
    <label>City/Branch(Location) </label>
    <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality',$empt_details['citylocality']); ?>" class="form-control cls_disabled">
    <span class="error error_city" style="display: none"> Enter City/Branch(Location) </span>
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
      <label>Experience/Relieving Letter</label>
      <input type="file" name="attchments_reliving[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
      <?php echo form_error('attchments'); ?>
      <span class="error error_relieving_letter" style="display: none">Attach Experience/Relieving Letter</span>

    </div>
    <div class="col-sm-4 form-group">
      <label>LOA<span class="error error_loa" style="display: none"> *</span></label>
      <input type="file" name="attchments_loa[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
       <span class="error error_relieving_letter" style="display: none">Attach LOA</span>
      <?php echo form_error('attchments'); ?>
    </div>
  <div class="col-sm-6 form-group">
    <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
    <input type="file" name="attchments_CS[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
    <?php echo form_error('attchments'); ?>
  </div>
  <div class="col-sm-6 form-group">
    <label >Executive Name<span class="error"> *</span></label>
    <?php
      echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$empt_details['has_case_id']), 'class="select2 cls_disabled" id="has_case_id"');
      echo form_error('has_case_id');
    ?>
  </div>
  </div>

    <div class="col-sm-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="col-sm-6 form-group"> 
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
     // $url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
      $url  = SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="clearfix"></div> 
      <div class="clearfix"></div> 
  <div class="col-sm-8 form-group">
    <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
  </div>
  <div class="clearfix"></div>
<?php echo form_close(); ?>
<div style="float: right;">
<button class="btn btn-info btn-md InsuffRaiseModel">Raise Insuff</button><br>
</div>
</div>


<div id="insuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insuff</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="insuff_edit" id="insuff_edit" value="">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$empt_details['emp_com_ref']); ?>">
        <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">  

        <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise"  id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',date(
          'd-m-Y')); ?>" class="form-control myDatepicker1" placeholder='DD-MM-YYYY'>
          <?php echo form_error('txt_insuff_raise'); ?>
        </div>
        <div class="col-md-6 col-sm-8 col-xs-6 form-group">
          <label>Reason</label>
          <?php
           echo form_dropdown('insff_reason', $insuff_reason_list, set_value('insff_reason'), 'class="form-control setinsff_reason" id="insff_reason"');
            echo form_error('insff_reason'); 
          ?>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_raise_remark" rows="1" maxlength="500" id="insuff_raise_remark"  class="form-control insuff_raise_remark"><?php echo set_value('insuff_raise_remark'); ?></textarea>
          <?php echo form_error('insuff_raise_remark'); ?>
        </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<script>
 $.ajax({  
                     
      url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
      type: "post",
      async:false,
      data: { company_name: function() { return $( "#company_id" ).val(); } },
      success: function(response){
        if(response.branch_location == '1'){  
          if($( "#citylocality" ).val() == ''){
          $('.error_city').css('display','inline-block'); 
          }
          else{
          $('.error_city').css('display','none');
          }
        }else{
          $('.error_city').css('display','none');
        }
        if(response.previous_emp_code == '1'){ 
          if($( "#empid" ).val() == ''){
          $('.error_previous_emp_code').css('display','inline-block'); 
          }
          else{
          $('.error_previous_emp_code').css('display','none');
          }
        }else{
          $('.error_previous_emp_code').css('display','none');
        }
        if(response.experience_letter == '1'){ 
          if($( "#attchments_reliving" ).val() == ''){
          $('.error_relieving_letter').css('display','inline-block');
          }
          else{
          $('.error_relieving_letter').css('display','none');
          } 
        }else{
          $('.error_relieving_letter').css('display','none');
        }
        if(response.loa == '1'){
          if($( "#attchments_loa" ).val() == ''){ 
          $('.error_loa').css('display','inline-block'); 
          }
          else{
          $('.error_loa').css('display','none');
          } 
        }else{
          $('.error_loa').css('display','none');
        }
                     
      }
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
    }
    else
        show_alert('Access Denied, You don’t have permission to access this page');
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

 
</script>
<script type="text/javascript">
  var counter = 1;
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
    
    if(selected_company == "Other")
    {
      var id = '<?php echo ADMIN_SITE_URL.'company_database/addAddCompanyModel'?>';
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



  $.ajax({
      type:'POST',
      url:'<?php echo ADMIN_SITE_URL.'employment/load_supervisor_details/dis/'; ?>'+counter,
      data:'details_fields='+$('#update_id').val()+'&hdn_counter'+$('#hdn_counter').val(),
      beforeSend :function(){
        //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
      },
      success:function(html)
      {
        jQuery('#supervisor_details').append(html);
      }
  });

    $('#frm_emp_update').validate({ 
    rules: {
      update_id : {
        required : true
      },
      has_case_id : {
        required : true,
        greaterThan: 0
      },
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      employercontactno : {
        number : true
      },
      pincode : {
        number : true,
        minlength : 6,
        maxlength : 6
      },
      citylocality : {
        lettersonly : true
      },
      compant_contact : {
        number : true,
        minlength : 6
      },
      compant_contact_email : {
        email: true
        },
     
      designation : {
        required : true
      },
      empfrom : {
        required : true
      },
      empto : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      has_case_id : {
        required : "Select Executive Name",
        greaterThan : "Select Executive Name"
      },
      clientid : {
        required : "Select Client"
      },
      candsid : {
        required : "Select Candidate"
      },
      designation : {
        required : "Enter Designation"
      },
      empfrom : {
        required : "Enter Employment From"
      },
      empto : {
        required : "Enter Employment To"
      },
      
      
    },
    submitHandler: function(form) 
    {      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'employment/update_employment'; ?>',
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
          $("#frm_emp_update :input").prop("disabled", true);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
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


   $('#frm_insuff_raise').validate({ 
        rules: {
          update_id : {
            required : true
          },
          insff_reason : {
            required : true,
            greaterThan : 0
          },
          txt_insuff_raise : {
            required : true
          }
        },
        messages: {
          update_id : {
            required : "Update ID missing"
          },
          insff_reason : {
            required : "Select Reason",
            greaterThan : "Select Reason"
          },
          txt_insuff_raise : {
            required : "Select Insuff Date"
          }
        },
        submitHandler: function(form) 
        {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'employment/insuff_raised'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#btn_submit_insuff').attr('disabled','disabled');
              },
              complete:function(){
               // $('#btn_submit_insuff').removeAttr('disabled',false);
              },
              success: function(jdata){
               if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

                var employment_id = $("#update_id" ).val(); 
                  $.ajax({
                      url: '<?php echo ADMIN_SITE_URL.'employment/reject_status'; ?>',
                      data: 'employment_id='+employment_id,
                      type: 'post',
                      dataType:'json',
                      success: function(jdata){
                                  
                      }
                  });

                }
                var message =  jdata.message || '';
                $('#insuffRaiseModel').modal('hide');
                $('#myModalComponentSave').modal('hide');
                $('#frm_insuff_raise')[0].reset();
                if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                  location.reload();
                }else {
                  show_alert(message,'error'); 
                }
              }
            });
          }
     });
   

$(document).on('click','.InsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  $('#insuffRaiseModel').modal('show');
  $('#insuffRaiseModel').addClass("show");
  $('#insuffRaiseModel').css({background: "#0000004d"}); 
});    
  
</script>