<div class="box box-primary">
 <?php echo form_open_multipart('#', array('name'=>'frm_address_edit','id'=>'frm_address_edit')); ?>
  <div class="box-body">    
  
      <input type="hidden" name="update_id"  id = "update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>" class="form-control">

      <input type="hidden" name="candsid"  id = "candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($addressver_details['iniated_date'])); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
       <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
 
    <div class="text-white div-header">
      Address Case Details
    </div>
   <br>
   <div class="clearfix"></div>
   <div class="row">

    
   <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Address type <span class="error">*</span></label>
      <?php
       echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="custom-select" id="address_type"');
        echo form_error('address_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Stay From <span class="error">*</span> (MM/YYYY)</label>
      <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from'); ?>" class="form-control">
      <?php echo form_error('stay_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Stay To <span class="error">*</span> (MM/YYYY)</label>
      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to'); ?>" class="form-control">
      <?php echo form_error('stay_to'); ?>
    </div>
    <div class="clearfix"></div>

    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address<span class="error">*</span></label>
      <textarea name="address" rows="1" id="address" class="form-control"><?php echo set_value('address'); ?></textarea>
      <?php echo form_error('address'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City<span class="error">*</span></label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Pincode<span class="error">*</span></label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State<span class="error">*</span></label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
        echo form_error('state');
      ?>
    </div> 
    </div>
   
    <br>
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachments <span class="error">(Not More Than 20 MB)</span></label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="btn_address" id='btn_address' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
    <div class="clearfix"></div>
    <div class="clearfix"></div> 
  </div>
   <?php echo form_close(); ?>
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


  $('#frm_address_edit').validate({
      rules: {
      address : {
        required : true
      },
      stay_from : {
        required : true
      },
      stay_to : {
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
     
      state : {
         required : true,
         greaterThan: 0
      },
      address_type : {
         required : true,
         greaterThan: 0
      }
    },
    messages: {
     
      address : {
        required : "Enter address"
      },
      stay_from : {
        required : "Enter Stay From"
      },
      stay_to : {
        required : "Enter Stay To"
      },
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
       state : {
        required : "Please select state",
        greaterThan:  "Please select state"
      },
       address_type : {
         required : "Please select address type",
         greaterThan: "Please select address type"
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'candidate_mail/update_address'; ?>',
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
          $('#btn_address').attr('disabled',false);
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