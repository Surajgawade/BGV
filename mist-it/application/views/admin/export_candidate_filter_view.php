<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label >Select Client <span class="error"> *</span></label>
  <?php
    echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="form-control" id="clientid"');
    echo form_error('clientid');
  ?>
</div>
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label >Select Entiy<span class="error"> *</span></label>
  <?php
    echo form_dropdown('entity', array(), set_value('entity'), 'class="form-control" id="entity"');
    echo form_error('entity');
  ?>
</div>
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label >Package<span class="error"> *</span></label>
   <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
  <?php echo form_error('package');?>
</div>
<div class="clearfix"></div>