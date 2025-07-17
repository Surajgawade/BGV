<div class="box-header">
  <h5 class="box-title">Insuff Raised Details</h5>
</div>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
  <label>Raise Date<span class="error"> *</span></label>
  <input type="hidden" name="id" id="id" value="<?php echo set_value('id',$insuff_details['id']);?>">
  <input type="text" name="txt_insuff_raise" id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',convert_db_to_display_date($insuff_details['insuff_raised_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
  <?php echo form_error('txt_insuff_raise'); ?>
</div>
<div class="col-md-6 col-sm-8 col-xs-6 form-group">
  <label>Reason</label>
  <?php
   echo form_dropdown('insff_reason', $insuff_reason_list, set_value('insff_reason',$insuff_details['insff_reason']), 'class="form-control setinsff_reason" id="insff_reason"');
    echo form_error('insff_reason'); 
  ?>
</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
  <label>Remark<span class="error"> *</span></label>
  <textarea  name="insuff_raise_remark" rows="1" maxlength="500" id="insuff_raise_remark"  class="form-control insuff_raise_remark"><?php echo set_value('insuff_raise_remark',$insuff_details['insuff_raise_remark']); ?></textarea>
  <?php echo form_error('insuff_raise_remark'); ?>
</div>
</div>
<hr style="border-top: 2px solid #bb4c4c;width: 100%">
<div class="box-header">
  <h5 class="box-title">Insuff Cleared Details</h5>
</div>
<div class="row">
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Clear Date<span class="error"> *</span></label>
  <input type="text" name="insuff_clear_date" id="insuff_clear_date" value="<?php echo set_value('insuff_clear_date',convert_db_to_display_date($insuff_details['insuff_clear_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
  <?php echo form_error('insuff_clear_date'); ?>
</div>
<div class="col-md-8 col-sm-12 col-xs-8 form-group">
  <label>Remark<span class="error"> *</span></label>
  <textarea  name="insuff_remarks" rows="1" maxlength="500" id="insuff_remarks"  class="form-control"><?php echo set_value('insuff_remarks',$insuff_details['insuff_remarks']); ?></textarea>
  <?php echo form_error('insuff_remarks'); ?>
</div>
</div>
<script>
var today = new Date();
$('.myDatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    endDate: today
});
</script>