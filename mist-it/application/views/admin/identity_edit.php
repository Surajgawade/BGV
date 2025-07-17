
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
           Identity Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect btn-sm  edit_btn_click" data-frm_name='frm_identity_update' data-editUrl="<?= ($this->permission['access_identity_list_edit']) ? encrypt($selected_data['identity_id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
             
              <li><button class="btn btn-secondary waves-effect btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'identity/delete/'.encrypt($selected_data['identity_id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_identity_update','id'=>'frm_identity_update')); ?>
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Candidate Name</label>
        <input type="hidden" name="identity_id" value="<?php echo set_value('identity_id',$selected_data['identity_id']); ?>" >
        <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" >
        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
        <input type="text" name="CandidateName" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Client</label>
        <input type="text" name="clientname" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
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
        <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control readonly">
      </div>
      </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Component Ref No</label>
        <input type="text" name="identity_com_ref" id="identity_com_ref"  value="<?php echo set_value('identity_com_ref',$selected_data['identity_com_ref']); ?>" class="form-control readonly">
        <?php echo form_error('identity_com_ref'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Candidate Ref No</label>
        <input type="text" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Comp Int Date<span class="error"> *</span></label>
        <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($selected_data['iniated_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
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
         <input type="text" name="mode_of_veri" readonly="readonly" id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->identity)) { echo $mode_of_verification_value->identity; } ?>" class="form-control cls_disabled">
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
       <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',$selected_data['build_date']); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
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
       <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',$selected_data['identity_re_open_date']); ?>" class="form-control cls_disabled myDatepicker" placeholder='DD-MM-YYYY'>
      </div>

     </div>

      <div class="text-white div-header">
         Candidate Details
      </div>
      <br>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Date of Birth</label>
        <input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo set_value('date_of_birth',convert_db_to_display_date($get_cands_details['DateofBirth'])); ?>" class="form-control myDatepicker readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('date_of_birth'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Gender</label>
       <?php
          echo form_dropdown('gender', GENDER, set_value('gender',$get_cands_details['gender']), 'class="select2 gen" id="gender"');
          echo form_error('gender');
        ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Father's Name</label>
        <input type="text" name="father_name" id="father_name" value="<?php echo set_value('father_name',$get_cands_details['NameofCandidateFather']); ?>" class="form-control readonly">
        <?php echo form_error('father_name'); ?>
      </div>
      </div>

      <div class="text-white div-header">
        Provided Documents
      </div>
      <br>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Doc Submitted</label>
        <?php
         echo form_dropdown('doc_submited', array('' => 'Select','aadhar card' => 'Aadhar Card',
          'pan card' => 'PAN Card','passport' => 'Passport'), set_value('doc_submited',$selected_data['doc_submited']), 'class="select2 cls_disabled" id="doc_submited"');
          echo form_error('doc_submited'); 
        ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Id Number</label>
        <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number',strtoupper($selected_data['id_number'])); ?>" class="form-control cls_disabled">
        <?php echo form_error('id_number'); ?>
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
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$selected_data['has_case_id']), 'class="select2 cls_disabled" id="has_case_id"');
          echo form_error('has_case_id');
        ?>
      </div>
      </div> 
     <div class="row">
    <div class="col-sm-4 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.IDENTITY.$selected_data['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="col-sm-4 form-group"> 
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
     // $url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
      $url  = SITE_URL.IDENTITY.$selected_data['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    </div> 
      <input type="hidden" name="upload_capture_image_identity" id="upload_capture_image_identity" value=""> 
      <div class="col-sm-8 form-group">
        <input type="submit" name="btn_identity_edit" id='btn_identity_edit' value="Update" class="btn btn-success cls_disabled">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_identity">Copy Attachment</button>
      </div>
    
  <?php echo form_close(); ?>
  <div class="col-sm-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/11/'.$selected_data['identity_id'] ?>/identity" <?=$check_insuff_raise?> data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>

<div id="capture_image_copy_identity" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_identity','id'=>'frm_upload_copied_image_identity')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-identity"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_identity" name="upload_copied_image_identity">Upload</button>
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
.image-content-result-identity {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-identity::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-identity:not(:empty)::after,
.image-content-result-identity:focus::after {
  display: none;
}

.image-content-result-identity * {
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
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);
  $('.cls_disabled').prop('disabled', true);
  $('.gen').prop('disabled', true);

   $('#upload_copied_image_identity').on('click',function() {
      var get_image_name =  $('#upload_capture_image_identity').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-identity img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_identity').val(get_image_base);
          $('#capture_image_copy_identity').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });


  $('#frm_identity_update').validate({ 
    rules: {
      identity_id : {
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
      doc_submited : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      identity_com_ref : {
        required : true
      },
      iniated_date :{
        required : true
      },
      id_number : {
        required : true,
        panvalidation : true,
        aadharvalidation : true
      }
      
    },
    messages: {
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
      doc_submited : {
        required : "Select Doc Submitted"
      },
      identity_com_ref : {
        required : "Enter Component Verification Number"
      },
      iniated_date :{
        required : "Enter Initiate Date",
      },
      id_number : {
        required : "Enter Identity Number",
        panvalidation : "Enter proper Pan card",
        aadharvalidation : "Enter proper Aadhar card"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'identity/update_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_identity_edit').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
        //  $('#btn_identity_edit').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            //window.location = jdata.redirect;
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
});

 $('.remove_file').on('click',function(){
  var id =  <?= ($this->permission['access_identity_list_edit']) ? $selected_data['identity_id'] : '"permission denied"'?>;
  if(id != "permission denied")
  {
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'identity/remove_uploaded_file/' ?>"+remove_id,
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

     jQuery.validator.addMethod("panvalidation", function (value, element) {
        var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
        var doc_submited = $('#doc_submited').val();
        if(doc_submited == "pan card")
        {
            if (regex.test(value)) {
                 valid = true;
            } else {
               
                valid = false;
            }
        }
        else
        {
            valid = true;
        }
        return valid;
    });

     jQuery.validator.addMethod("aadharvalidation", function (value, element) {
        var regex = /([0-9]){12}$/;
        var doc_submited = $('#doc_submited').val();
        if(doc_submited == "aadhar card")
        {
            if (regex.test(value)) {
                 valid = true;
            } else {
               
                valid = false;
            }
        }
        else
        {
            valid = true;
        }
        return valid;
    });
</script>
<script type="text/javascript">
  $(".select2").select2();
</script>