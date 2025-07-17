<div class="box-primary">

  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
        Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect edit_btn_click" data-frm_name='frm_update_candidates' data-editUrl='<?= ($this->permission['access_candidates_list_edit']) ? encrypt($candidate_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button></li>
               <li><button class="btn btn-secondary waves-effect delete_candidate" data-accessUrl="<?= ($this->permission['access_candidates_list_delete']) ? ADMIN_SITE_URL.'candidates/delete/'.encrypt($candidate_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
  
  <?php echo form_open('#', array('name'=>'frm_update_candidates','id'=>'frm_update_candidates')); ?>
    <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',encrypt($candidate_details['id'])); ?>">
    <input type="hidden" name="cp_update_id" id="cp_update_id" value="<?php echo set_value('cp_update_id',$candidate_details['id']); ?>">
    <input type="hidden" name="selected_entity" id="selected_entity" value="<?php echo $candidate_details['entity'];?>">
    <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $candidate_details['package'];?>">
    <div class="row">
    <div class="col-sm-3 form-group">
      <label >Select Client <span class="error"> *</span></label>
      <?php
        echo form_dropdown('clientid', $clients, set_value('clientid',$candidate_details['clientid']), 'class="select2" id="clientid"');
        echo form_error('clientid');
      ?>
    </div>

     <div class="col-sm-1 form-group" id="show_hide_check">
       <label>&nbsp;&nbsp;</label>
        <button class="btn btn-secondary waves-effect" data-url ="<?= ADMIN_SITE_URL.'candidates/entity_package_view/' ?>"  data-toggle="modal" id="show_entity_package_modal" >Check</button>
      </div>
 
   
    <div class="col-sm-4 form-group">
      <label >Select Entity<span class="error"> *</span></label>
      <?php

        echo form_dropdown('entity', array(), set_value('entity',$candidate_details['entity']), 'class="select2" id="entity"');
        echo form_error('entity');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label >Select Package<span class="error"> *</span></label>
       <select id="package" name="package" class="select2"><option value="0">Select</option></select>
      <?php echo form_error('package');?>
    </div>
   </div>
   <div class="row">
    <div class="col-sm-4 form-group">
      <label>Case Received Date<span class="error"> *</span></label>
      <input type="text" name="caserecddate" id="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($candidate_details['caserecddate'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
        <?php echo form_error('caserecddate'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Client Ref Number <span class="error"> *</span></label>
      <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>" class="form-control">
      <?php echo form_error('ClientRefNumber'); ?>
      <span id="show_error" class="error"></span>
    </div>
    <div class="col-sm-4 form-group">
      <label><?php echo REFNO; ?> <span class="error"> *</span></label>
      <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$candidate_details['cmp_ref_no']); ?>" class="form-control">
      <?php echo form_error('cmp_ref_no'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Overall Status <span class="error"> *</span></label>
      <?php
        echo form_dropdown('overallstatus', $status, set_value('overallstatus',$candidate_details['overallstatus'],1), 'class="select2" id="overallstatus"');
        echo form_error('overallstatus');
      ?>
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
       <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',convert_db_to_display_date($candidate_details['build_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
       <?php echo form_error('build_date'); ?>
    </div> 
    <div class="col-sm-4 form-group">
      <label>Closure Date <span class="error"> *</span></label>
      <input type="text" disabled="" name="overallclosuredate" id="overallclosuredate" value="<?php echo set_value('overallclosuredate',convert_db_to_display_date($candidate_details['overallclosuredate'])); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('overallclosuredate'); ?>
    </div>
    </div>
    
   
    <div class="text-white div-header">
      Joining Details
    </div>
    <br>
    <div class="row"> 
    <div class="col-sm-4 form-group">
      <label>Date of Joining</label>
      <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining',convert_db_to_display_date($candidate_details['DateofJoining'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofJoining'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Designation</label>
      <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas',$candidate_details['DesignationJoinedas']); ?>" class="form-control">
      <?php echo form_error('DesignationJoinedas'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Branch Location</label>
      <input type="text" name="Location" id="Location" value="<?php echo set_value('Location',$candidate_details['Location']); ?>" class="form-control">
      <?php echo form_error('Location'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Department</label>
      <input type="text" name="Department" id="Department" value="<?php echo set_value('Department',$candidate_details['Department']); ?>" class="form-control">
      <?php echo form_error('Department'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Employee Code</label>
      <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode',$candidate_details['EmployeeCode']); ?>" class="form-control">
      <?php echo form_error('EmployeeCode'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Work Experience</label>
       <?php
         $work_experiance = array(''=> 'Select Work Experience','fresher'=>'Fresher','experienced'=>'Experienced');
            
         echo form_dropdown('branch_name', $work_experiance, set_value('branch_name',$candidate_details['branch_name']), 'class="select2" id="branch_name"');
         echo form_error('branch_name');

      ?>
      <?php echo form_error('branch_name'); ?>
    </div>
    </div>

    <div class="row">

    <div class="col-sm-4 form-group">
      <label>Grade</label>
        <input type="text" name="grade" id="grade" value="<?php echo set_value('grade',$candidate_details['grade']); ?>" class="form-control">
      <?php echo form_error('grade'); ?>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <label >Remarks</label>
      <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks',$candidate_details['remarks']); ?></textarea>
    </div>
   </div>

    <div class="text-white div-header">
      Candidate Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Name <span class="error"> *</span></label>
      <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control">
      <?php echo form_error('CandidateName'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Gender<span class="error"> *</span></label>
     <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="select2" id="gender"');
        echo form_error('gender');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Date of Birth <span class="error"> *</span></label>
      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofBirth'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Father's Name <span class="error"> *</span></label>
      <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control">
      <?php echo form_error('NameofCandidateFather'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Mother's Name</label>
      <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName',$candidate_details['MothersName']); ?>" class="form-control">
      <?php echo form_error('MothersName'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Primary Contact<span class="error"> *</span></label>
      <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control">

        <div id = "candidatecontact" class="error"></div>
      <?php echo form_error('CandidatesContactNumber'); ?>
    </div>
    </div>
    <div class="row"> 
    <div class="col-sm-4 form-group">
      <label>Contact No (2)</label>
      <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control">
      <?php echo form_error('ContactNo1'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Contact No (3)</label>
      <input type="text" name="ContactNo2" id="ContactNo2" maxlength="12" value="<?php echo set_value('ContactNo2',$candidate_details['ContactNo2']); ?>" class="form-control">
      <?php echo form_error('ContactNo2'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Email ID</label>
      <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id',$candidate_details['cands_email_id']); ?>" class="form-control">
      <?php echo form_error('cands_email_id'); ?>
    </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-sm-12 col-xs-4 form-group comp_add"  style="display:none;">
        <label>&nbsp;</label>
           <label><span class="error"><h6 style="margin-left:20px;"><input type="checkbox" id="add_pan_card_in_identity" name="add_pan_card_in_identity" class="form-check-input" style="height: 15px;width: 15px;">&nbsp;&nbsp;Add Pan card</h6></span></label>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group comp_add" style="display:none;">
          <label>&nbsp;</label>
            <label><span class="error"><h6 style="margin-left:20px;"><input type="checkbox" id="add_aadhar_card_in_identity" name="add_aadhar_card_in_identity" class="form-check-input" style="height: 15px;width: 15px;">&nbsp;&nbsp;Add aadhar card </h6></span></label>
       </div>
      </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>PAN No.</label>
      <input type="text" name="PANNumber" id="PANNumber" value="<?php echo set_value('PANNumber',$candidate_details['PANNumber']); ?>" class="form-control">
      <?php echo form_error('PANNumber'); ?>
    </div>

    <div class="col-sm-4 form-group">
      <label>AADHAR No.</label>
      <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber',$candidate_details['AadharNumber']); ?>" class="form-control">
      <?php echo form_error('AadharNumber'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Passport No.</label>
      <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber',$candidate_details['PassportNumber']); ?>" class="form-control">
      <?php echo form_error('PassportNumber'); ?>
    </div>
    </div>
   
   

    <div class="text-white div-header">
      Candidate Address
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Street Address</label>
      <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control"><?php echo set_value('prasent_address',$candidate_details['prasent_address']); ?></textarea>
    </div> 
    <div class="col-sm-4 form-group">
      <label>City</label>
      <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city',$candidate_details['cands_city']); ?>" class="form-control">
      <?php echo form_error('cands_city'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>PIN Code</label>
      <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode',$candidate_details['cands_pincode']); ?>" class="form-control">
      <?php echo form_error('cands_pincode'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('cands_state', $states, set_value('cands_state',$candidate_details['cands_state']), 'class="select2" id="cands_state"');
        echo form_error('cands_state');
      ?>
    </div> 
    </div>
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.pdf,.docx,.xls,.xlsx,.tif,.tiff" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    </div>
    
    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
         $url  = SITE_URL.CANDIDATES.$candidate_details['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
        return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <input type="hidden" name="upload_capture_image" id="upload_capture_image" value="">
    <div class="clearfix"></div> 
    <div class="box-body">
      <div class="col-sm-4 form-group">
        <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy">Copy Attachment</button>
      </div>
    </div>
   
  <?php echo form_close(); ?>
  <button class="btn btn-sm btn-info" data-url="<?= ADMIN_SITE_URL.'client_cases/select_candidate_activity_record/'.$candidate_details['id'] ?>" data-toggle="modal" id="showactivityModels" style= "float:right;"> Activity </button>
</div>

<br>


<div id="show_entity_package_modal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Show Entity Package</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="append_entity_package"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div id="showactivityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_activity_details','id'=>'frm_activity_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Result</h4>
      </div>
      <div class="modal-body">
        <div class="row">
           <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $candidate_details['id'] ?>"> 
          <div class="col-sm-6 form-group">
             <label>Activity Status <span class="error"> *</span></label>
            <?php
              $activity = array('WIP'=>'WIP','Not Joining'=>'Not Joining','No Response'=>'No Response','Will share shortly'=>'Will share shortly','Callback'=>'Callback');
              echo form_dropdown('activity_status_cands', $activity, set_value('activity_status_cands'), 'class="select2" id="activity_status_cands"');?>
          </div>
          <div class="col-sm-6 form-group">
            <label>Remarks <span class="error"> *</span></label>
              <textarea name="activity_remark" rows="1" id="activity_remark" class="form-control"><?php echo set_value('activity_remark'); ?></textarea>
              
          </div> 
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="tab-pane" id="tab_candidate_activity_result">
          <table id="tab_candidate_activity_result" class="table table-bordered"></table>
        </div>
      </div>

    
    
      <div class="modal-footer">
        <button type="submit" id="btn_submit_activity_details" name="btn_submit_activity_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="capture_image_copy" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image','id'=>'frm_upload_copied_image')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image" name="upload_copied_image">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
  
  const errorEl = document.getElementById('errorMsg')

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
</script>

<style>
.image-content-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result:not(:empty)::after,
.image-content-result:focus::after {
  display: none;
}

.image-content-result * {
  max-width: 100%;
  border: 0;
}
</style>
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

$(document).ready(function(){

  $('#upload_copied_image').on('click',function() {
      var get_image_name =  $('#upload_capture_image').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image').val(get_image_base);
          $('#capture_image_copy').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });


  $('.open_attachment').on('click',function(){
    var url = $(this).data('href');
    window.open('javascript:window.open('+url+', "_self", "");window.close();', '_self');
    //window.open(url, "_blank", "toolbar=yes,width=900,height=650");
  });
  

  $("#frm_update_candidates :input").prop("disabled", true);

  $("#caserecddate").datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    daysOfWeekDisabled: [0,6],
    endDate: new Date()
  }).on('changeDate', function (selected) {
      var minDate = $(this).val();
      $('#overallclosuredate').datepicker('setStartDate', minDate);
  });

  $('#overallclosuredate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    endDate: new Date()
  });


 $('#btn_update').click(function(){ 

  $('#frm_update_candidates').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      entity : {
        required : true,
        greaterThan: 0
      },
      package: {
        required : true,
        greaterThan: 0
      },
      clientid : {
        required : true,
        greaterThan: 0
      },
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : true
      //  noSpace : true
      },
      gender : {
        required : true,
        greaterThan: 0
      },
      cands_email_id : {
        email : true
      },

      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      DateofBirth : {
        required : true
      },
      NameofCandidateFather : {
        required : true,
        lettersonly : true
      },
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        required : true,
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo2 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      },
      overallstatus : {
        required : true,
        greaterThan : 0
      },
      PANNumber : {
        panvalidation : true
      }
    },
    messages: {
        clientid : {
          required : "Enter Client Name",
          greaterThan: "Enter Client Name"
        },
         entity : {
          required : "Enter Entity  Name",
          greaterThan: "Enter Entity Name"
        },
         package : {
          required : "Enter Package Name",
           greaterThan: "Enter Package Name"
        },
        caserecddate : {
          required : "Select Case Received Date"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
         cands_email_id : {
         email : "Enter Valid Email ID"
       }, 
        ClientRefNumber : {
          required : "Enter Client Ref Number"
        },
        DateofBirth : {
          required : "Select Date of Birth"
        },
        NameofCandidateFather : {
          required : "Enter Name of Father"
        },
        gender : {
          required : "Select Gender"
        },
          CandidatesContactNumber : {
         required : "Please Enter Primary Contact"
        },
        PANNumber :{
          panvalidation : "Enter proper Pan card"
        }
      },
      submitHandler: function(form) 
      {      
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'candidates/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
              jQuery('.body_loading').show();
            },
            complete:function(){
             // $("#frm_update_candidates :input").prop("disabled", true);
              jQuery('.body_loading').hide();
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
});


$('#package').on('change',function(){

var clientid = $('#clientid').val();
var entity = $('#selected_entity').val();
var package = $('#selected_package').val();

if(clientid != 0 && entity != 0 && package != 0)
{
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'candidates/check_identity_component'; ?>',
        data:'clientid='+clientid+'&entity='+entity+'&package='+package,
        success:function(response)
        {
       
          var data =  response.message;
         
          if(data.candidate_component == "present")
          {
             $('.comp_add').css('display','block');
          }
          if(data.candidate_component == "not present")
          {
             $('.comp_add').css('display','none');
          }
         
        }
    });
}
}).trigger('change'); 

  $('#entity').on('change',function(){
    var entity = $(this).val() || $('#selected_entity').val();
    var selected_paclage = $('#selected_package').val();
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_paclage='+selected_paclage,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  }).trigger('change');


  $('#clientid').on('change',function(){
  var clientid = $(this).val();
  var entity = $('#selected_entity').val();
  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid+'&selected_entity='+entity,
          beforeSend :function(){
            jQuery('#entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity').html(html);
          }
      });
  }
}).trigger('change'); 




});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete_candidate', function(){  
           var candidates_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'candidates/delete/';?>",  
                     method:"POST",  
                     data:{candidates_id:candidates_id},  
                     success: function(jdata){
                    var message =  jdata.message || '';
                    (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                    if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                      show_alert(message,'success');

                     setTimeout(function(){
                             window.location = "<?php echo ADMIN_SITE_URL.'clients/';?>";
                       },1000);
                    }
                    if(jdata.status == <?php echo ERROR_CODE; ?>){
                      show_alert(message,'error'); 
                    }
                  }
                });  
           }  
           else  
           {  
                return false;       
           }  
      }); 


  $('#show_entity_package_modal').click(function(){
    var url = $(this).data("url");
    var clientid = $('#clientid').val();

    $('.append_entity_package').load(url+""+clientid,function(){});
    $('#show_entity_package_modal1').modal('show');
    $('#show_entity_package_modal1').addClass("show");
    $('#show_entity_package_modal1').css({background: "#0000004d"}); 

  });

    $("#CandidatesContactNumber").keyup(function(){
     var maxLength = 8;
      if(maxLength <= $(this).val().length) {
        var mobile_no = $(this).val();
        var clientid = $('#clientid').val();
          $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/primary_contact_no_check'; ?>',
            data:'mobileno='+mobile_no+'&clientid='+clientid,
            success:function(jdata) {
              if(jdata == 'false')
              {
                 $('#candidatecontact').html('');
              }
              else
              {
                $('#candidatecontact').html('Already Existing '+jdata);
                
              }
            }
        });
     }
  });

   

   $('#frm_update_candidates').on('click','#show_entity_package_modal',function(){

    var url = $(this).data("url");
    var clientid = $('#clientid').val();

    $('.append_entity_package').load(url+""+clientid,function(){});
    $('#show_entity_package_modal1').modal('show');
    $('#show_entity_package_modal1').addClass("show");
    $('#show_entity_package_modal1').css({background: "#0000004d"}); 
   
    return false;
  });

  
  $('#showactivityModels').click(function(){
  var url = $(this).data("url");

  $('#tab_candidate_activity_result').load(url,function(){});
  $('#showactivityModel').modal('show');
  $('#showactivityModel').addClass("show");
  $('#showactivityModel').css({background: "#0000004d"}); 

  });

  $("#ClientRefNumber").keyup(function(){

     var clientid = $('#clientid').val();
     var username = $('#ClientRefNumber').val();
     var ClientRefNumber = $('#ClientRefNumber').val();
     var entity_name = $('#entity').val();
     var package_name = $('#package').val();
   
    $.ajax({  
      url:"<?php echo ADMIN_SITE_URL.'candidates/is_client_ref_exists' ?>",  
      method:"POST",  
     data:{clientid:clientid,username:username,ClientRefNumber:ClientRefNumber,entity_name:entity_name,package_name:package_name},  
      success: function(response){
        var status    =  response;
        if(status == "false")
        {
          
        $('#show_error').html("Client Ref No Exists");
        }   
         if(status == "true")
        {
          
        $('#show_error').html("");
        }          
       }
     });  
             
  });

    $('.remove_file').on('click',function(){
    var id =  <?= ($this->permission['access_candidates_list_edit']) ? $candidate_details['id'] : '"permission denied"'?>;
    if(id != "permission denied")
    { 
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'candidates/remove_uploaded_file/' ?>"+remove_id,
              type: 'GET',
              beforeSend:function(){
                show_alert('deleting...','info');
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


    $('#frm_activity_details').validate({ 
    rules: {
      candidate_id : {
        required : true
      },
      activity_status : {
        required : true
      },
      activity_remark : {
        required : true
      }
    },
    messages: {
      candidate_id : {
        required : "Update ID missing"
      },
      activity_status : {
        required : "Please Select Activity Status"
      },
      cases_assgin : {
        required : "Please Enter Remark"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'client_cases/save_candidate_activity_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_activity_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_activity_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showactivityModel').modal('hide');
            $('#frm_activity_details')[0].reset();
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


    jQuery.validator.addMethod("panvalidation", function (value, element) {
        var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
          if(value == "")
          {
              valid = true;
          }
          else
          {
            if (regex.test(value)) {
                 valid = true;
            } else {
               
                valid = false;
            }
          }
      return valid;
    });

</script>

<script type="text/javascript">
    $(".select2").select2();
</script>