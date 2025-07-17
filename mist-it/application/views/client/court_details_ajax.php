<?php  if(!empty($court_details)) { foreach ($court_details as $key => $value)  { ?>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Address Type</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['address_type']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Address</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['street_address']); ?>">
    </div>
</div>
<div class="clearfix"></div>
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