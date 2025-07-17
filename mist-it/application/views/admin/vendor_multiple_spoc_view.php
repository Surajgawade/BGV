<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Spoc Name <span class="error"> *</span></label>
  <input type="text" name="sopc_name[]" id="sopc_name" value="<?php echo set_value('sopc_name'); ?>" class="form-control">
  <?php echo form_error('sopc_name'); ?>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Primary Contact <span class="error"> *</span></label>
  <input type="text" name="primary_contact[]" id="primary_contact" minlength="8" maxlength="12" value="<?php echo set_value('primary_contact'); ?>" class="form-control">
  <?php echo form_error('primary_contact'); ?>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Email Id</label>
  <input type="text" name="email_id[]" id="email_id" maxlength="50" value="<?php echo set_value('email_id'); ?>" class="form-control">
  <?php echo form_error('email_id'); ?>
</div>
<div class="clearfix"></div>