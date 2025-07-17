<div class="result_error" id="result_error"></div>
<div class="row">
<div class="col-sm-4 form-group">
  <label >Company Name<span class="error"> *</span></label>
  <input type="text" name="coname" id="coname" value="<?php echo set_value('coname');?>" class="form-control ">
  <?php echo form_error('coname'); ?>
</div>
<div class="col-sm-4 form-group">
  <label >Address<span class="error"> *</span></label>
  <input type="text" name="address" id="address" value="<?php echo set_value('address');?>" class="form-control ">
  <?php echo form_error('address'); ?>
</div>
<div class="col-sm-4 form-group">
  <label>City<span class="error"> *</span></label>
  <input type="text" name="city" id="city" value="<?php echo set_value('city');?>" class="form-control ">
  <?php echo form_error('city'); ?>
</div>
</div>
<div class="row">
<div class="col-sm-8 form-group">
  <label>Select State<span class="error"> *</span></label>
  <?php
    echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
    echo form_error('state');
  ?>
</div>
<div class="col-sm-4 form-group">
  <label>Pincode<span class="error"> *</span></label>
  <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode');?>" class="form-control ">
  <?php echo form_error('pincode'); ?>
</div>
</div>
<div class="row">
<div class="col-sm-6 form-group">
  <label>To Email ID</label>
  <input type="text" name="co_email_id" id="co_email_id" value="<?php echo set_value('co_email_id');?>" class="form-control ">
  <?php echo form_error('co_email_id'); ?>
</div>
<div class="col-sm-6 form-group">
  <label>CC Email ID</label>
  <input type="text" name="cc_email_id" id="cc_email_id" value="<?php echo set_value('cc_email_id');?>" class="form-control ">
  <?php echo form_error('cc_email_id'); ?>
</div>
</div>
<div class="clearfix"></div>
<script>
$(document).ready(function(){

  $('#add_company').validate({ 
      rules: {
        coname : {
          required : true
        },
        address : {
          required : true
        },
        city : {
          required : true,
          lettersonly : true
        },
        pincode : {
          required : true,
          number : true,
          minlength : 6,
          maxlength : 6
        },
        state : {
          required : true,
          greaterThan: 0
        },
        co_email_id : {
          multiemails : true
        },
        cc_email_id : {
           multiemails : true
        }
      },
      messages: {
        coname : {
          required : "Enter Name"
        },
        address : {
          required : "Enter Address"
        },
        city : {
          required : "Enter Address"
        },
        pincode : {
          required : "Enter Pincode"
        },
        state : {
          required : "Select State",
          greaterThan : "Select State",
        },
        co_email_id:{
         multiemails : "Enter Valid Email Id"
        },
        cc_email_id :{
           multiemails : "Enter Valid Email ID"
        }
      },
      submitHandler: function(form) 
      {      
          $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'company_database/save_company'; ?>',
          data: new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#company_add').attr('disabled','disabled');
          },
          complete:function(){
            $('#company_add').removeAttr('disabled');           
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#add_company')[0].reset();
              $('#addAddCompanyModel').modal('hide');
              var newOption = new Option(jdata.coname, jdata.insert_id, true, true);
    // Append it to the select
              $('#nameofthecompany').append(newOption).trigger('change');
              $('#selected_company_name').val(jdata.coname);
            }
            else{
              show_alert(message,'error'); 
            }
          }
        });      
      }
  });
});


 jQuery.validator.addMethod("multiemails", function (value, element) {
    if (this.optional(element)) {
    return true;
    }

    var emails = value.split(','),
    valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
    value = jQuery.trim(emails[i]);
    valid = valid && jQuery.validator.methods.email.call(this, value, element);
    }
    return valid;
    }, "Invalid email format: please use a comma to separate multiple email addresses.");
    
    jQuery.validator.addMethod('matches', function (value, element) {
    return this.optional(element) || /^[a-z0-9](\.?[a-z0-9]){5,}@m$/i.test(value);
    }, "use be a  e-mail address");

</script>