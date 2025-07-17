<div class="box box-primary">

  <div class="box-body">    
    
     
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
      <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
      <input type="hidden" name="component_name" id="component_name" value="Reference" class="form-control" >
      <input type="hidden" name="comp_ref_no" id="comp_ref_no" value="<?php echo set_value('comp_ref_no',$details[0]['reference_com_ref']); ?>" class="form-control" >

      <input type="hidden" name="reference_id"  value="<?php echo set_value('reference_id',$id); ?>" class="form-control">

    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->refver)) { echo $mode_of_verification_value->refver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
      
      
  
    <div class="box-header">
      <h3 class="box-title">Reference Details</h3>
    </div>
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
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Email ID</label>
      <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
      <?php echo form_error('email_id'); ?>
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

});
</script>