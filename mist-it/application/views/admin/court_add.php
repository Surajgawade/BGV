<div class="box box-primary">
<br>
<div class="text-white div-header">
  Court Verification Case Details
</div>
<br>
<?php echo form_open_multipart('#', array('name'=>'frm_court','id'=>'frm_court')); ?>
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
         <input type="text" name="mode_of_veri" readonly="readonly" id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->courtver)) { echo $mode_of_verification_value->courtver; } ?>" class="form-control">
        <?php echo form_error('mod_of_veri'); ?>
      </div>


    </div>
    <div class="text-white div-header">
      Candidate Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Date of Birth<span class="error">*</span></label>
      <input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo set_value('date_of_birth',convert_db_to_display_date($get_cands_details['DateofBirth'])); ?>" class="form-control myDatepicker" readonly placeholder="DD-MM-YYYY">
      <?php echo form_error('date_of_birth'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Gender<span class="error">*</span></label>
     <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$get_cands_details['gender']), 'class="select2 gen" id="gender"');
        echo form_error('gender');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Father's Name<span class="error">*</span></label>
      <input type="text" name="father_name" id="father_name" value="<?php echo set_value('father_name',$get_cands_details['NameofCandidateFather']); ?>" class="form-control readonly">
      <?php echo form_error('father_name'); ?>
    </div>
    </div>
    

    <div class="text-white div-header">
      Candidate Address
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
    <input type="checkbox" name="candidate_extract_court" id="candidate_extract_court" class="check_court" value="" onchange="stickyheaddsadaer(this)"/>
    <em> Check this box to get details from candidate</em>
  </div>
  <div class="col-sm-4 form-group">
    <input type="checkbox" name="address_extract_court" id="address_extract_court"  class="check_court" value="{TT_sticky_header}" onchange="stickyheaddsadaer_address(this)"/>
    <em> Check this box to get details from Address</em>
  </div>
  </div>
   <div class="row">
    <div class="col-sm-4 form-group">
      <label>Address type</label>
      <?php
       echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="custom-select" id="address_type"');
        echo form_error('address_type'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Street Address<span class="error">*</span></label>
      <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
      <?php echo form_error('street_address'); ?>
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
      <label>State</label>
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
    
    <div class="col-sm-4 form-group">
      <input type="submit" name="btn_court" id='btn_court' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
  </div>
<?php echo form_close(); ?>
</div>
<script type="text/javascript">

  $(document).ready(function(){

   $('.gen').prop('disabled', true);

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

    
    $('.check_court').click(function(){
      $('.check_court').not(this).prop('checked',false);
    });
  });
  

  function stickyheaddsadaer(obj) {
  if($(obj).is(":checked")){

    <?php if(!empty($get_cands_details)) {  ?>

     document.getElementById('street_address').value = '<?php echo  $get_cands_details["prasent_address"]  ?>';
     document.getElementById('city').value = '<?php echo  $get_cands_details["cands_city"] ?>';
     document.getElementById('pincode').value = '<?php echo $get_cands_details['cands_pincode'] ?>';
     document.getElementById('state').value = '<?php echo  $get_cands_details["cands_state"]  ?>';

     <?php } else {  ?>

      document.getElementById('address').value = '';
      document.getElementById('city').value = '';
      document.getElementById('pincode').value = '';
      document.getElementById('state').value = '';

      <?php } ?> 
    
  }else{
     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';
    
  }
  
}

function stickyheaddsadaer_address(obj) {
  if($(obj).is(":checked")){
    
    <?php if(!empty($get_address_details)) {  ?> 
 
     document.getElementById('address_type').value = '<?php echo  $get_address_details["address_type"]  ?>';
     document.getElementById('street_address').value = '<?php echo  $get_address_details["address"]  ?>';
     document.getElementById('city').value = '<?php echo  $get_address_details["city"] ?>';
     document.getElementById('pincode').value = '<?php echo $get_address_details['pincode'] ?>';
     document.getElementById('state').value = '<?php echo  $get_address_details["state"]  ?>';

    <?php } else {  ?>

     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';

      <?php } ?> 
    
    
  }else{
     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';
    
  }
  
}


</script>

<script>
$(document).ready(function(){

  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);

    $('#frm_court').validate({ 
      rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      court_com_ref : {
        required : true
      },
      date_of_birth : {
        required : true
      },
      gender : {
        greaterThan: 0,
        required : true
      },
       father_name : {
        required : true
      },
      street_address : {
        required : true
      },
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      city : {
        required : true,
        lettersonly : true
      },
      has_case_id :{
        required : true,
        greaterThan : 0
      },
      iniated_date :{
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
      court_com_ref : {
        required : "Enter Component Verification Number"
      },
      date_of_birth : {
        required : "Select Date of Birth"
      },
       gender : {
        required : "Select Gender",
        greaterThan : "Select Gender"
      },
       father_name : {
        required : "Enter Father Name"
      },
      street_address : {
        required : "Enter Address"
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
      iniated_date :{
           required : "Enter Initiate Date"
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'court_verificatiion/save_court_details'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_court').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
         // $('#btn_save').attr('disabled',false);
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