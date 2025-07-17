<?php
$x = 0;
foreach ($client_logins as $key => $value) {
  ?>
  <div class="row">
  <input type="hidden" name="client_login_id[]" id="client_login_id" value="<?php echo set_value('client_login_id',$value['id']);?>" class="form-control">
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label >First Name</label>
    <input type="text" name="client_first_name[]" id="client_first_name" value="<?php echo set_value('client_first_name',$value['first_name']);?>" class="form-control">
    <?php echo form_error('client_first_name'); ?>
  </div>

    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >Mobile No</label>
  <input type="text" name="client_mobileno[]" maxlength="11" id="client_mobileno" value="<?php echo set_value('client_mobileno',$value['mobile_no']);?>" class="form-control">
  <?php echo form_error('client_mobileno'); ?>
</div>

  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label >Email ID</label>
    <input type="text" name="client_email_id[]" id="client_email_id" value="<?php echo set_value('client_email_id',$value['email_id']);?>" class="form-control">
    <?php echo form_error('client_email_id'); ?>
  </div>
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label >Password</label>
    <input type="password" name="client_password[]" id="client_password" value="<?php echo set_value('client_password');?>" class="form-control">
    <?php echo form_error('client_password'); ?>
  </div>


  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >Select Package</label>
  <?php
 
  $option = convert_to_single_dimension_array($entityList,'entity_id','entity_name');
  unset($option[0]);
  echo form_multiselect('client_entity_access['.$x.'][]',$option, set_value('client_entity_access'), 'class="form-control multiSelect" id="client_entity_access'.$x.'"');
  echo form_error('client_entity_access'); 
  ?>
  </div>
  </div>
  <script type="text/javascript">
    
  var client_entity_access = '<?= $value['client_entity_access'] ?>';
  client_entity_access =  client_entity_access.split(',').map(Number)
  $('#client_entity_access<?php echo $x; ?>').multiselect('select',client_entity_access);

  </script>
  <div class="clearfix"></div>
  <?php
  $x++;
}
?>
<?php
if(!empty($client_logins))
{
    $array_count1 =  count($client_logins);
                    
    $array_count = $array_count1; 
}
else
{
  $array_count = 0 ;
}
  ?>

  <input type="hidden" name="client_login_id[]" id="client_login_id" value="<?php echo set_value('client_login_id');?>" class="form-control">
<div class="row">
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >First Name</label>
  <input type="text" name="client_first_name[]" id="client_first_name" value="<?php echo set_value('client_first_name');?>" class="form-control">
  <?php echo form_error('client_first_name'); ?>
</div>
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >Mobile No</label>
  <input type="text" name="client_mobileno[]" maxlength="11" id="client_mobileno" value="<?php echo set_value('client_mobileno');?>" class="form-control">
  <?php echo form_error('client_mobileno'); ?>
</div>

<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >Email ID</label>
  <input type="text" name="client_email_id[]" id="client_email_id" value="<?php echo set_value('client_email_id');?>" class="form-control">
  <?php echo form_error('client_email_id'); ?>
</div>
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label >Password</label>
  <input type="password" name="client_password[]" id="client_password" value="<?php echo set_value('client_password');?>" class="form-control">
  <?php echo form_error('client_password'); ?>
</div>
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
<label >Select Package</label>
<?php

$option = convert_to_single_dimension_array($entityList,'entity_id','entity_name');
unset($option[0]);
echo form_multiselect('client_entity_access['.$array_count.'][]',$option, set_value('client_entity_access'), 'class="form-control multiSelecta" id="client_entity_access"');
echo form_error('client_entity_access'); 
?>
</div>
</div>
<script>
$('.multiSelecta').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
});
//$("input[type=text],input[type=password],select").prop("disabled", true);

  $("#frm_update_client :input").prop("disabled", true);
//frm_update_client

</script>