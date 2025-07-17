    <input type="hidden" name="address_id" id = "address_id" value="<?php echo $address_id  ?>">
    <input type="hidden" name="clientid" id = "clientid" value="<?php echo $clients_id  ?>">

    <div class="col-sm-4 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>