<input type="hidden" name="vendor_log_id" id = "vendor_log_id" value="<?php echo $vendor_log_id  ?>">

<div class="col-sm-6 form-group">
   <label>Attachment </label><span class = "error">(jpeg,jpg,png,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .tiff" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments_file'); ?>" class="form-control">
        <?php echo form_error('attchments_file'); ?>
</div>