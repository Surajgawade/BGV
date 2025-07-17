<div class="box box-primary">
<br>
<div class="text-white div-header">
  Reference Verification Details
</div>
<br>
<?php echo form_open_multipart('#', array('name'=>'frm_reference','id'=>'frm_reference')); ?>
  <div class="box-body">
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Name</label>
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="text" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
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
      <input type="text" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
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
         <input type="text" name="mode_of_veri"  id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->refver)) { echo $mode_of_verification_value->refver; } ?>" class="form-control cls_disabled" readonly>
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
       Reference Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Name of Reference<span class="error"> *</span></label>
      <input type="text" name="name_of_reference" id="name_of_reference" value="<?php echo set_value('name_of_reference'); ?>" class="form-control">
      <?php echo form_error('name_of_reference'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Primary Contact Number<span class="error"> *</span></label>
      <input type="text" name="contact_no" minlength="11" maxlength="13" id="contact_no" value="<?php echo set_value('contact_no'); ?>" class="form-control">
      <?php echo form_error('contact_no'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Contact Number 1</label>
      <input type="text" name="contact_no_first" minlength="11" maxlength="13" id="contact_no_first" value="<?php echo set_value('contact_no_first'); ?>" class="form-control">
      <?php echo form_error('contact_no_first'); ?>
    </div>
     <div class="col-sm-4 form-group">
      <label>Contact Number 2</label>
      <input type="text" name="contact_no_second" minlength="11" maxlength="13" id="contact_no_second" value="<?php echo set_value('contact_no_second'); ?>" class="form-control">
      <?php echo form_error('contact_no_second'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Email ID</label>
      <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
      <?php echo form_error('email_id'); ?>
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
   
    <div class="col-sm-4 form-group">
      <input type="submit" name="btn_reference" id='btn_reference' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
  </div>
<?php echo form_close(); ?>
</div>

<script>
$(document).ready(function(){
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);

    $('.gen').prop('disabled', true);
   
    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

  $('#frm_reference').validate({ 
      rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      reference_com_ref : {
        required : true
      },
      name_of_reference : {
        required : true
      },
      designation : {
        required : true
      },
      email_id : {
        email : true
      },
      contact_no : {
        required :true,
        number : true,
        minlength : 11,
        maxlength : 13
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
      reference_com_ref : {
        required : "Enter Component Verification Number"
      },
      name_of_reference : {
        required : "Enter Reference Name"
      },
      designation : {
        required : "Enter Designation"
      },     
      contact_no : {
        required : "Enter Contact No"
      },
       email_id : {
        email : "Enter Valid Email ID"
      },
      has_case_id : {
        greaterThan : "Select Executive"
      },
      iniated_date:{
        required : "Enter Initiate Date"
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/save_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_reference').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          //$('#btn_address').attr('disabled',false);
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

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });

</script>