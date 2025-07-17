<div class="box box-primary">
<br>
<div class="text-white div-header">
  Address Case Details
</div>
<br>
<?php echo form_open_multipart('#', array('name'=>'frm_address','id'=>'frm_address')); ?>
  <div class="box-body"> 
    <div class="row">   
    <div class="col-sm-4 form-group">
      <label>Candidate Name</label>
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="text" name="CandidateName"  value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
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
      <input type="text" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control readonly">
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
         <input type="text" name="mod_of_veri"  id="mod_of_veri" readonly="readonly" value="<?php if(isset($mode_of_verification_value->addrver)) { echo $mode_of_verification_value->addrver; } ?>" class="form-control cls_disabled">
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
        Candidate Address
    </div>
    <br>
  <div class="col-sm-4 form-group">
    <input type="checkbox" name="TT_sticky_header" id="TT_sticky_header_function" value="{TT_sticky_header}" onchange="stickyheaddsadaer(this)"/>
    <em> Check this box to get details from candidate</em>
  </div>
   <div class="clearfix"></div>
   <div class="row"> 
    <div class="col-sm-4 form-group">
      <label>Stay From </label>
      <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from'); ?>" class="form-control">
      <?php echo form_error('stay_from'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Stay To</label>
      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to'); ?>" class="form-control">
      <?php echo form_error('stay_to'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-sm-4 form-group">
      <label>Address type</label>
      <?php
       echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="select2" id="address_type"');
        echo form_error('address_type'); 
      ?>
    </div>
    </div>
    <div class="row"> 
    <div class="col-sm-4 form-group">
      <label>Street Address<span class="error">*</span></label>
      <textarea name="address" rows="1" id="address" class="form-control"><?php echo set_value('address'); ?></textarea>
      <?php echo form_error('address'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>City<span class="error">*</span></label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    </div>
    <div class="row"> 
    <div class="col-sm-4 form-group">
      <label>Pincode<span class="error">*</span></label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>State<span class="error">*</span></label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
        echo form_error('state');
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
     <input type="hidden" name="upload_capture_image_address" id="upload_capture_image_address" value="">
    <div class="clearfix"></div>  
  
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="btn_address" id='btn_address' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_address">Copy Attachment</button>
    </div>
  </div>
<?php echo form_close(); ?>
</div>

<div id="capture_image_copy_address" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_address','id'=>'frm_upload_copied_image_address')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-address"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_address" name="upload_copied_image_address">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<script type="text/javascript">
  
$('.gen').prop('disabled', true);
  function stickyheaddsadaer(obj) {
  if($(obj).is(":checked")){

    <?php if(!empty($get_cands_details)) {  ?>

     document.getElementById('address').value = '<?php echo  $get_cands_details["prasent_address"]  ?>';
     document.getElementById('city').value = '<?php echo  $get_cands_details["cands_city"] ?>';
     document.getElementById('pincode').value = '<?php echo $get_cands_details["cands_pincode"] ?>';
     document.getElementById('state').value = '<?php echo  $get_cands_details["cands_state"]  ?>';

   <?php } else {  ?>

     document.getElementById('address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = ''; 

       <?php } ?>
  }else{

     document.getElementById('address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';
    
  }
  
}
</script>
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
.image-content-result-address {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-address::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-address:not(:empty)::after,
.image-content-result-address:focus::after {
  display: none;
}

.image-content-result-address * {
  max-width: 100%;
  border: 0;
}
</style>

<script>
$(document).ready(function(){

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);

    $('#upload_copied_image_address').on('click',function() {
      var get_image_name =  $('#upload_capture_image_address').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-address img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_address').val(get_image_base);
          $('#capture_image_copy_address').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });

    $('#frm_address').validate({ 
      rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      iniated_date : {
        required : true
      },
      //stay_to : {
       // required : true
      //},
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      address : {
        required : true
      },
      city : {
         required : true,
        lettersonly : true
      },
      has_case_id :{
        required : true,
        greaterThan : 0
      },
      state : {
         required : true,
         greaterThan: 0
      
      }
    },
    messages: {
      clientid : {
        required : "Select Client"
      },
      candsid : {
        required : "Select Candidate"
      },
      iniated_date : {
        required : "Enter Initiate date"
      },
      //stay_to : {
       // required : "Enter Stay To"
      //},     
      address : {
        required : "Enter address"
      },
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
      has_case_id : {
        greaterThan : "Select Executive"
      },
       state : {
        required : "Please select state",
        greaterThan:  "Please select state"
      }

    },
    submitHandler: function(form) 
    {
 
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'address/save_address'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_address').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
        //  $('#btn_address').attr('disabled',false);
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
</script>

<script type="text/javascript">
    $(".select2").select2();
</script>