<div class="box box-primary">

  <div class="box-body">    
    
     
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
      <input type="hidden" name="component_name" id="component_name" value="Drugs" class="form-control" >
      <input type="hidden" name="comp_ref_no" id="comp_ref_no" value="<?php echo set_value('comp_ref_no',$details[0]['drug_com_ref']); ?>" class="form-control" >
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

    <div class="col-md-6 col-sm-8 col-xs-6 form-group">
      <label>Appointment Date</label>
      <input type="text" name="appointment_date" id="appointment_date" value="<?php echo set_value('appointment_date'); ?>" class="form-control myDatepicker" Placeholder='DD-MM-YYYY'>
      <?php echo form_error('appointment_date'); ?>
    </div>
    <div class="col-md-6 col-sm-8 col-xs-6 form-group">
      <label>Appointment Time</label>
      <input type="text" name="appointment_time" id="appointment_time" value="<?php echo set_value('appointment_time'); ?>" class="form-control myTimepicker" Placeholder='HH:MM'>
      <?php echo form_error('appointment_time'); ?>
    </div>
   <div class="clearfix"></div>

    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Facility Name/Code</label>
      <input type="text" name="facility_name" id="facility_name" value="<?php echo set_value('facility_name'); ?>" class="form-control">
      <?php echo form_error('facility_name'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address<span class="error"> *</span></label>
      <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
      <?php echo form_error('street_address'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City<span class="error"> *</span></label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State<span class="error"> *</span></label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Pincode<span class="error"> *</span></label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Attachments & other</h3>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
   
    
    <div class="clearfix"></div>
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">

       <input type="checkbox" name="drugs_check" id="drugs_check_1" value="1" />
       <input type="checkbox" name="drugs_check" id="drugs_check_2" value="2" checked="checked"  style="display: none;" />
           <em> Check this box to send fill information from  candidate</em>

     </div>
    
    <div class="clearfix"></div>  
   
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
        required : function(){
       
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      candsid : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      pincode : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        },   
        number : true,
        minlength : 6,
        maxlength : 6
      },
      street_address : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      city : {
         required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        lettersonly : true
      },
     
      state : {
         required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
             return true;
          }
        },
         greaterThan: 0
      }
     
    },
    messages: {
      clientid : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Select Client";
          }
        }
        
      },
      candsid : {

        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Select Candidate";
          }
        }
        
      },
       
      street_address : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Enter address";
          }
        }
      },
      city : {
      required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Enter City";
          }
        }
      },
      pincode : {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Enter Pincode";
          }
        }
      },

      state: {
        required : function(){
         
          if (($("input[name ='drugs_check']:checked").length) > 0) 
          {
            return false;
          }
          else
          {
            return "Select State"; 
          }
        }
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'Drugs/save_drugs'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbdrugs').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbdrugs').attr('disabled',false);
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

 $('#drugs_check_1').change(function() {

        if ($(this).is(':checked')) {
            $('#drugs_check_2').prop('checked', false);
        }
        else {
            $('#drugs_check_1').prop('checked', false);
            $('#drugs_check_2').prop('checked', true);
        }
    });

    $('#drugs_check_2').change(function() {

        if ($(this).is(':checked')) {
            $('#drugs_check_1').prop('checked', false);
        }
        else {
            $('#drugs_check_2').prop('checked', false);
            $('#drugs_check_1').prop('checked', true);
        }
    });
</script>