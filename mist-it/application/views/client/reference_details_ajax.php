<?php if(!empty($reference_details)) { foreach ($reference_details as $key => $value)  { ?>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Reference Name</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['name_of_reference']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Designation </label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['designation']); ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Contact Number</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['contact_no']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Email ID</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['email_id']; ?>">
    </div>
</div>

<div class="clearfix"></div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Verification Status</label>
        <input type="text" class="form-control" disabled style='color: <?= status_color($value['status_value']) ?>' value="<?php echo ucwords($value['status_value']); ?>">
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php } } else {
    echo "<h4 style='text-align: center;'>NO Record Found</h4>";
} ?>