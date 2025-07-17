<?php if(!empty($employment_details)) {  foreach ($employment_details as $key => $value)  { ?>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Employee ID/Code</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['empid']; ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Name of the Company</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['coname']; ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Address</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['locationaddr']); ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Employer Contact No</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['compant_contact']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">City</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['citylocality']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Employed From</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['empfrom']); ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Employed To</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['empto']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Designation</label>
        <input type="text" class="form-control" disabled  value="<?php echo ucwords($value['designation']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Remuneration</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['remuneration']; ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Reporting Manager</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['r_manager_name']; ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Reason for Leaving</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['reasonforleaving']; ?>">
    </div>
</div>
<div class="col-md-4">
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