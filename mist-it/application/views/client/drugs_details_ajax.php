<?php  if(!empty($drugs_details)) { foreach ($drugs_details as $key => $value)  { ?>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Appointment  Date</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['appointment_date']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Appointment  Time</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['appointment_time']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Spoc Phone Number</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['spoc_no']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Drug Test Panel/Code</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['drug_test_code']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Facility Name/Code</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['facility_name']; ?>">
    </div>
</div>


<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Address</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['street_address']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">City</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['city']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Pincode</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['pincode']; ?>">
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">State</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['state']; ?>">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">Mode of verification</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['mode_of_verification']); ?>">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Status</label>
        <input type="text" class="form-control" disabled style='color: <?= status_color($value['status_value']) ?>' value="<?php echo ucwords($value['status_value']); ?>">
    </div>
</div>

<div class="clearfix"></div>
<hr>
<?php } } else {
    echo "<h4 style='text-align: center;'>NO Record Found</h4>";
} ?>