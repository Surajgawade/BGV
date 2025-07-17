<?php if(isset($superv_details) && is_array($superv_details) && !empty($superv_details)) 
{
  foreach ($superv_details as $key => $superv_detail) {
?>
<div id='counter_id_<?php echo $count ?>' class='total_count'>
  <div class="row">
<div class="col-sm-2 form-group">
  <label>Supervisor Name </label>
  <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("superv_details[]",$superv_detail['supervisor_name']); ?>" class="form-control" disabled>
  <?php echo form_error('supervisor_name'); ?>
</div>
<div class="col-sm-2 form-group">
  <label>Supervisor's contact</label>
  <input type="text" name="supervisor_contact_details[]" maxlength="12" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]",$superv_detail['supervisor_contact_details']); ?>" class="form-control" disabled>
  <?php echo form_error('supervisor_contact_details'); ?>
</div>
<div class="col-sm-4 form-group">
  <label>Designation </label>
  <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]",$superv_detail['supervisor_designation']); ?>" class="form-control" disabled>
</div>
<div class="col-sm-3 form-group">
  <label>Supervisor's Email ID </label>
  <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_i[]",$superv_detail['supervisor_email_id']); ?>" class="form-control" disabled>
  <?php echo form_error('supervisor_email_id'); ?>
</div>
<div class="col-sm-1 form-group">
  <?php if($count == 1) {?>
  <label>Add</label>
  <button type="button" class="btn btn-info add_supervisor_details_row" disabled data-id='<?php echo $count ?>'>+</button>
<?php } else { ?>
  <label>Remove</label>
  <button type="button" class="btn btn-warning remove_supervisor_details_row" disabled data-id='<?php echo $count ?>'>X</button>
<?php } ?>
</div>
</div>
</div>
<?php $count++; } }else { ?>
<div id='counter_id_<?php echo $count ?>' class='total_count'>
  <div class="row">
<div class="col-sm-2 form-group">
  <label>Supervisor Name </label>
  <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("supervisor_name[]"); ?>" <?=$disabled?> class="form-control">
  <?php echo form_error('supervisor_name'); ?>
</div>
<div class="col-sm-2 form-group">
  <label>Supervisor's contact </label>
  <input type="text" name="supervisor_contact_details[]" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" <?=$disabled?> class="form-control">
  <?php echo form_error('supervisor_contact_details'); ?>
</div>
<div class="col-sm-4 form-group">
  <label>Designation </label>
  <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" <?=$disabled?> class="form-control">
</div>
<div class="col-sm-3 form-group">
  <label>Supervisor's Email ID </label>
  <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_i[]"); ?>" <?=$disabled?> class="form-control">
  <?php echo form_error('supervisor_email_id'); ?>
</div>
<div class="col-sm-1 form-group">
  <?php if($count == 1) {?>
  <label>Add</label>
  <button type="button" class="btn btn-info add_supervisor_details_row" data-id='<?php echo $count ?>'>+</button>
<?php } else { ?>
  <label>Remove</label>
  <button type="button" class="btn btn-warning remove_supervisor_details_row" data-id='<?php echo $count ?>'>X</button>
<?php } ?>
</div>
</div>
</div>
<?php } ?>