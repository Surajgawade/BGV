<div class="box box-primary">
<br>
<div class="text-white div-header">
  Identity Case Details
</div>
<br>
<?php echo form_open_multipart('#', array('name'=>'frm_identity','id'=>'frm_identity')); ?>
  <div class="box-body">
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Name</label>
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
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
      <label>Candidate Ref No</label>
      <input type="text" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Comp Int Date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>

     <?php
   
    if(!empty($mode_of_verification))
    {
        $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <div class="col-sm-4 form-group">
        <label>Mode of Verification</label>
         <input type="text" name="mode_of_veri" readonly="readonly" id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->identity)) { echo $mode_of_verification_value->identity; } ?>" class="form-control">
        <?php echo form_error('mod_of_veri'); ?>
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
        echo form_dropdown('gender', GENDER, set_value('gender',$get_cands_details['gender']), 'class="select2 gen"  id="gender"');
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
  <!--<div class="col-md-8 col-sm-12 col-xs-8 form-group">
    <input type="checkbox" name="candidate_extract_identity" id="candidate_extract_identity" class="check_identity" value="{TT_sticky_header}" onchange="stickyheaddsadaer_identity(this)"/>
    <em> Check this box to get details from candidate</em>
  </div>
  <div class="col-md-8 col-sm-12 col-xs-8 form-group">
    <input type="checkbox" name="address_extract_identity" id="address_extract_identity"  class="check_identity" value="{TT_sticky_header}" onchange="stickyheaddsadaer_address_identity(this)"/>
    <em> Check this box to get details from Address</em>
  </div>-->
   
    <div class="row"> 
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Doc Submitted<span class="error">*</span></label>
      <?php
       echo form_dropdown('doc_submited', array('' => 'Select','aadhar card' => 'Aadhar Card',
        'pan card' => 'PAN Card' ,'passport' => 'Passport'), set_value('doc_submited'), 'class="select2" id="doc_submited"');
        echo form_error('doc_submited'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Id Number</label>
      <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number'); ?>" class="form-control">
      <?php echo form_error('id_number'); ?>
    </div>
   <!-- <div class="col-sm-4 form-group">
      <label>Street Address<span class="error">*</span></label>
      <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
      <?php echo form_error('street_address'); ?>
    </div>
    </div>
    <div class="col-sm-4 form-group">
      <label>City</label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city',$get_cands_details['cands_city']); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state',$get_cands_details['cands_state']), 'class="form-control singleSelect" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$get_cands_details['cands_pincode']); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>-->
    </div>
 
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
      <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Executive Name<span class="error">*</span></label>
      <?php
        echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id'), 'class="select2 cls_disabled" id="has_case_id"');
        echo form_error('has_case_id');
      ?>
    </div>
    </div>
    <input type="hidden" name="upload_capture_image_identity" id="upload_capture_image_identity" value="">  
    <div class="col-sm-4 form-group">
      <input type="submit" name="btn_identity" id='btn_identity' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_identity">Copy Attachment</button>
    </div>
  </div>
<?php echo form_close(); ?>
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
$(document).ready(function(){
  $('.gen').prop('disabled', true);
  
  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);

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

    $('#frm_identity').validate({ 
      rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      doc_submited : {
        required : true,
        greaterThan: 0
      },
      id_number : {
        required : true,
        panvalidation : true,
        aadharvalidation : true
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      identity_com_ref : {
        required : true
      },
      has_case_id :{
        required : true,
        greaterThan : 0
      },
      iniated_date:{
        required : true
      }
    },
    messages: {
      clientid : {
        required : "Select Client"
      },
      candsid : {
        required : "Select Candidate"
      },
      doc_submited : {
        required : "Select Doc Submitted"
      },
      id_number : {
        required : "Enter Identity Number",
        panvalidation : "Enter proper Pan card",
        aadharvalidation : "Enter proper Aadhar card"
      },
      identity_com_ref : {
        required : "Enter Component Verification Number"
      },
      has_case_id : {
        greaterThan : "Select Executive"
      },
      iniated_date:{
        required : "Enter Initiate Date"
      },
    },
    submitHandler: function(form) 
    {
      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'identity/save_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_identity').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
        //  $('#btn_save').attr('disabled',false);
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
});
</script>
<script type="text/javascript">
     $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });

</script>