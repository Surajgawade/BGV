<?php  if(!empty($pcc_details)) { foreach ($pcc_details as $key => $value)  { ?>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Date Of Visit Police Station</label>
        <input type="email" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['police_station_visit_date']); ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Application ID/Ref</label>
        <input type="email" class="form-control" disabled  value="<?php echo $value['application_id_ref']; ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Name And Designation Of The Police Offcier</label>
        <input type="email" class="form-control" disabled  value="<?php echo ucwords($value['name_designation_police']); ?>">
    </div>
</div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Contact Number Of Police</label>
        <input type="email" class="form-control" disabled  value="<?php echo $value['contact_number_police']; ?>">
    </div>
</div>

<div class="clearfix"></div>

<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Mode Of Verification</label>
        <input type="email" class="form-control" disabled  value="<?php echo ucwords($value['mode_of_verification']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Verification Remarks</label>
        <input type="email" class="form-control" disabled  value="<?php echo ucwords($value['remarks']); ?>">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group label-floating">
        <label class="control-label">Verification Status</label>
        <input type="email" class="form-control" disabled style='color: <?= status_color($value['status_value']) ?>' value="<?php echo ucwords($value['status_value']); ?>">
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php } } else {
    echo "<h4 style='text-align: center;'>NO Record Found</h4>";
} ?>