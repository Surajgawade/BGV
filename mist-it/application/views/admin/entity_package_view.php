<div class="result_error" id="result_error"></div>
<div class="row">  
  <div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isPackage' class="entity_package" value="isEntity" checked>Add Entity</label>
    <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isEntity' class="entity_package" value="isPackage">Add Package</label>
  </div>
  <div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <?php
     echo form_dropdown('tbl_client_id',$client_list, set_value('tbl_client_id'), 'class="form-control" id="tbl_client_id"');
      echo form_error('tbl_client_id'); 
    ?>
  </div>
  <div class="clearfix"></div>
  <div id="isEntity">
  <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="idEntity">
    <label >Entity<span class="error"> *</span></label>
    <input type="text" name="Entity" id="Entity" value="<?php echo set_value('Entity');?>" class="form-control ">
    <?php echo form_error('Entity'); ?>
  </div>
  </div>
  <div class="clearfix"></div>
  <div id="isPackage" style="display: none;">
    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
      <label >Select Entity<span class="error"> *</span></label>
      <?php
       echo form_dropdown('entity_list',$entity_list, set_value('entity_list'), 'class="form-control" id="entity_list"');
        echo form_error('entity_list'); 
      ?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
      <label>Package<span class="error"> *</span></label>
      <input type="text" name="package" id="package" value="<?php echo set_value('package');?>" class="form-control ">
      <?php echo form_error('package'); ?>
    </div>
  </div>
</div>