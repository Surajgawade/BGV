<div class="box box-primary">
 <?php echo form_open_multipart('#', array('name'=>'drugs_add','id'=>'drugs_add')); ?>
  <div class="box-body">    
      <input type="hidden" name="update_id"  id = "update_id" value="<?php echo set_value('update_id',$drugs_details['drug_narcotis_id']); ?>" class="form-control">  
      <input type="hidden" name="candsid" id="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
       <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
   
    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->narcver)) { echo $mode_of_verification_value->narcver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
      
    <div class="box-header">
      <h3 class="box-title">Appointment Details</h3>
    </div>
  <div class = "row"> 
    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Appointment Date</label>
      <input type="text" name="appointment_date" id="appointment_date" value="<?php echo set_value('appointment_date'); ?>" class="form-control myDatepicker" Placeholder='DD-MM-YYYY'>
      <?php echo form_error('appointment_date'); ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Appointment Time</label>
      <input type="text" name="appointment_time" id="appointment_time" value="<?php echo set_value('appointment_time'); ?>" class="form-control myTimepicker" Placeholder='HH:MM'>
      <?php echo form_error('appointment_time'); ?>
    </div>
  </div>
  <div class = "row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Drug Test Panel/Code</label>
       <?php
        $drug_test_code = array(''=>'Select','5 panel'=> '5 Panel');
        echo form_dropdown('drug_test_code', $drug_test_code, set_value('drug_test_code'), 'class="custom-select" id="drug_test_code"');
        echo form_error('drug_test_code');
      ?>
      <?php echo form_error('drug_test_code'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address<span class="error"> *</span></label>
      <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
      <?php echo form_error('street_address'); ?>
    </div>

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City<span class="error"> *</span></label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
  </div>

   <div class = "row">  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State<span class="error"> *</span></label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Pincode<span class="error"> *</span></label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
  </div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Attachments & other</h3>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="btn_drugs" id='btn_drugs' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
    
    <div class="clearfix"></div>
    
    <div class="clearfix"></div>  
   <?php echo form_close(); ?>
  </div>
</div>



<script>
$(document).ready(function(){

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);

  $('#drugs_add').validate({
      rules: {
      clientid : {
        required : true
      },
      candsid : {
        required :true
      },
      pincode : {
        required : true,  
        number : true,
        minlength : 6,
        maxlength : 6
      },
      street_address : {
        required : true
      },
      city : {
        required : true,
        lettersonly : true
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
      street_address : {
        required : "Enter address"
      },
      city : {
      required :  "Enter City"
      },
      pincode : {
        required :  "Enter Pincode"  
      },
      state: {
        required : "Select State",
        greaterThan: "Please select State"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'candidate_mail/update_drugs'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_drugs').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#btn_drugs').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
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