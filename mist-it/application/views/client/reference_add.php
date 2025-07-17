<div class="box box-primary">
  <div class="box-body">    
    <?php echo form_open_multipart('#', array('name'=>'reference_add','id'=>'reference_add')); ?>

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
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->refver)) { echo $mode_of_verification_value->refver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
     
  
    <br>
    <div class="text-white div-header">
      Reference Details 
    </div>
   <br>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Name of Reference<span class="error"> *</span></label>
      <input type="text" name="name_of_reference" id="name_of_reference" value="<?php echo set_value('name_of_reference'); ?>" class="form-control">
      <?php echo form_error('name_of_reference'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Contact Number<span class="error"> *</span></label>
      <input type="text" name="contact_no" minlength="8" maxlength="13" id="contact_no" value="<?php echo set_value('contact_no'); ?>" class="form-control">
      <?php echo form_error('contact_no'); ?>
    </div>
   </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Email ID</label>
      <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
      <?php echo form_error('email_id'); ?>
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
      <input type="submit" name="sbreference" id='sbreference' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div> 
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

    $('#reference_add').validate({
      rules: {
        name_of_reference :{
         required : true
        },
        designation : {
          required : true
        },
        contact_no : {
          required : true
        }
    },
    messages: {
      name_of_reference : {
        required : "Enter Name of Reference"  
        },
      designation : {
        required : "Enter Designation"  
        },
      contact_no : {
        required : "Enter Contact No"  
        }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'reference/save_reference'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbreference').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbreference').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>')
          {
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