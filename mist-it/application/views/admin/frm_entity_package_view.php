<input type="hidden" name="client_id" value="<?php echo set_value('client_id',isset($frm_details['client_id'])); ?>" id="client_id" class="append_client_id" value="<?php echo set_value('client_id'); ?>">
<input type="hidden" name="selected_entity" id="selected_entity" value="<?php echo set_value('selected_entity',$frm_details['entity']); ?>">
<input type="hidden" name="selected_package" id="selected_package" value="<?php echo set_value('selected_package',$frm_details['package']); ?>">

<div class="clearfix"></div>
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Address <span class="error"> *</span></label>
  <input type="text" name="clientaddress" id="clientaddress" value="<?php echo set_value('clientaddress'); ?>" class="form-control">
  <?php echo form_error('clientaddress'); ?>
</div>
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label>City <span class="error"> *</span></label>
  <input type="text" name="clientcity" id="clientcity" value="<?php echo set_value('clientcity'); ?>" class="form-control">
  <?php echo form_error('clientcity'); ?>
</div>
<div class="col-md-2 col-sm-12 col-xs-2 form-group">
  <label>PIN Code <span class="error"> *</span></label>
  <input type="text" name="clientpincode" id="clientpincode" maxlength="6" value="<?php echo set_value('clientpincode'); ?>" class="form-control">
  <?php echo form_error('clientpincode'); ?>
</div>
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>State <span class="error"> *</span></label>
  <?php
    echo form_dropdown("clientstate", $states, set_value('clientstate'), 'class="form-control" id="clientstate"');
    echo form_error('clientstate');
  ?>
</div>
<div class="clearfix"></div>

<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Package Amount</label>
  <input type="text" name="package_amount" id="package_amount" maxlength="10" value="<?php echo set_value('package_amount'); ?>" class="form-control" value='0'>
  <?php echo form_error('package_amount'); ?>
</div> 
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Candidate Report Type</label>
  <div class="radio">  
    <label><input type="radio" name="report_type" id="report_type" value="full-report" checked>Full Report</label>
    <label><input type="radio" name="report_type" id="report_type" value="only-annexure" >Only Annexure</label>
  </div>
</div> 

<div class="clearfix"></div>
<div class="box-header with-border">
<h3 class="box-title">Client Spoc Details</h3>
<button type="button" style="float: right;" class="btn btn-info btn-xs" id="addSpocModal"><i class="fa fa-plus"></i> Add Spoc</button>
</div>
<div class="getSpocDiv">
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Spoc Name</label>
    <input type="text" name="spoc_name[]" id="spoc_name" maxlength="40" class="form-control">
    <?php echo form_error('spoc_name'); ?>
  </div> 
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Spoc Email ID</label>
    <input type="email" name="spoc_email[]" id="spoc_email" maxlength="40"  class="form-control">
    <?php echo form_error('spoc_email'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Spoc Mobile No</label>
    <input type="text" name="spoc_mobile_no[]" id="spoc_mobile_no" maxlength="10" minlength="10"  class="form-control">
    <?php echo form_error('spoc_mobile_no'); ?>
  </div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>CC Group</label>
    <input type="text" name="spoc_manager_email[]" id="spoc_manager_email" maxlength="40"  class="form-control" multiple>
    <?php echo form_error('spoc_manager_email'); ?>
  </div>
</div>
<div id="appendSpocModal"></div>
<div class="clearfix"></div>

<div class="box-header with-border">
<h3 class="box-title">Component Details</h3>
</div>
<div class="box-body">
<div class='col-md-2 col-sm-12 col-xs-2 form-group'>
  <label>Components <span class="error"> *</span></label>
</div>
<div class='col-md-2 col-sm-12 col-xs-2 form-group'>
  <label>Scope of work</label>
</div>
<div class='col-md-2 col-sm-12 col-xs-2 form-group'>
  <label>Mode of verification</label>
</div>
<div class='col-md-1 col-sm-12 col-xs-1 form-group'>
  <label>TAT</label>
</div>
<!--<div class='col-md-2 col-sm-12 col-xs-2 form-group'>
  <label>Interim Report</label>
</div>
<div class='col-md-1 col-sm-12 col-xs-1 form-group'>
  <label>Final Report</label>
</div>-->
<div class='col-md-1 col-sm-12 col-xs-1 form-group'>
  <label>Price</label>
</div>
<div class='col-md-1 col-sm-12 col-xs-1 form-group'>
  <label>SLA</label>
</div>
<div class="clearfix"></div>
<?php
  foreach ($components as $key => $value)
  {
    $data = array('name'          => "components[]",
                  'id'            => 'components',
                  'value'         => $value['component_key']
              );
    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'><div class='checkbox'><label>";
    echo form_checkbox($data);
    echo "<input type='text' name= 'component_name[]' id='".$value['component_key']."'  class='form-control' value='".$value['show_component_name']."''>";
    echo "</label></div></div>";
    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'>";
    echo form_dropdown("scope_of_work[]", $scope_of_word[$value['component_key']], set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
    echo "</div>";
    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'>";
    echo form_dropdown('mode_of_verification[]', $mode_of_veri[$value['component_key']], set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
    echo "</div>";
    echo "<div class='col-md-1 col-sm-12 col-xs-1 form-group'>";
    echo "<input type='number' min='0' name='tat_".$value['component_key']."' id='".$value['component_key']."' class='form-control'>";
    echo "</div>";
   // echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'>";
   // echo "<input type='number' name='"."interim_report[]' id='".$value['component_key']."' class='form-control' value=''>";
   // echo "</div>";
   // echo "<div class='col-md-1 col-sm-12 col-xs-1 form-group'>";
   // echo "<input type='number' min='0' name='final_report[]' id='".$value['component_key']."' class='form-control' value=''>";
   // echo "</div>";
    echo "<div class='col-md-1 col-sm-12 col-xs-1 form-group'>";
    echo "<input type='number' min='0' name='price[]' id='".$value['component_key']."' class='form-control'>";
    echo "</div>";
     echo "<div class='col-md-1 col-sm-12 col-xs-1 form-group'>";
    echo "<button type='button' name='sla_".$value['component_key']."' id='sla_".$value['component_key']."' class='btn btn-info form-control clkSlaModel'>SLA</button>";
    echo "</div>";
    echo "<div class='clearfix'></div>";
  }
?>
<div class="clearfix"></div>