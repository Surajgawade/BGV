<div class="box box-primary">
  <div class="box-body"> 
       <?php echo form_open_multipart('#', array('name'=>'global_add','id'=>'global_add')); ?>
      
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
    <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->globdbver)) { echo $mode_of_verification_value->globdbver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>

    <br>
    <div class="text-white div-header">
      Global Database Details
    </div>
   <br>
   <div class="clearfix"></div>
   <div class="row">      
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Address type</label>
      <?php
       echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
        echo form_error('address_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address<span class="error">*</span></label>
      <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
      <?php echo form_error('street_address'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City</label>
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
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
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
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>

    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="sbglobal" id='sbglobal' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>

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

    $('#global_add').validate({
     rules: {
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
      state : {
        required : true,
        greaterThan: 0
      }
    },
    messages: {
      street_address : {
        required : "Enter Address"
      },
      city : {
       required :  "Enter City"
      },
      pincode : {
       required :  "Enter Pincode"    
      },
       state : {
        required : "Please select state",
        greaterThan:  "Please select state"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'Globdver/save_global'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbglobal').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbglobal').attr('disabled',false);
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

</script>