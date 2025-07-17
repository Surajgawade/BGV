
<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>charges</label>
  <input type="text" name="charges" id="charges" value="" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Addtnl charges</label>
  <input type="text" name="additional_charges" id="additional_charges" value="" class="form-control">
</div>

</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Remark</label>
 <textarea name="remark" id="remark" rows="1" class="form-control"></textarea>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_file[]"  multiple="multiple" accept=".png, .jpg, .jpeg"  id="attchments_file" value="<?php echo set_value('attchments_file'); ?>" class="form-control">
      <?php echo form_error('attchments_file'); ?>
    </div>

</div>