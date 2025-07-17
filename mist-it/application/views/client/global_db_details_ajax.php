<?php if(!empty($global_db_details)) { foreach ($global_db_details as $key => $value)  { ?>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Address Type</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['address_type']); ?>">
    </div>
</div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Address</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['street_address']); ?>">
    </div>
</div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Verified By</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['verified_by']); ?>">
    </div>
</div>


<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Mode of verification</label>
        <input type="email" class="form-control" disabled  value="<?php echo ucwords($value['mode_of_verification']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Closure Date</label>
        <input type="email" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['closuredate']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Status</label>
        <input type="email" class="form-control" disabled style='color: <?= status_color($value['status_value']) ?>' value="<?php echo ucwords($value['status_value']); ?>">
    </div>
</div>
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="form-group label-floating">
        <label class="control-label">Remarks</label>
        <input type="email" class="form-control" disabled  value="<?php echo ucwords($value['remarks']); ?>">
    </div>
</div>
<hr>
<?php } } else {
    echo "<h4 style='text-align: center;'>NO Record Found</h4>";
} ?>