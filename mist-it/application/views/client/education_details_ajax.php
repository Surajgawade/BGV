<?php if(!empty($education_details)) { foreach ($education_details as $key => $value)  { ?>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">School/College Name</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['school_college']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">University</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['university_board']; ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Qualification</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['qualification']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Major</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['major']; ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">Month and Year of Passing</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['year_of_passing']; ?>">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">Grade</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['grade_class_marks']; ?>">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">Course Start</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['course_start_date']); ?>">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group label-floating">
        <label class="control-label">Course End</label>
        <input type="text" class="form-control" disabled  value="<?php echo convert_db_to_display_date($value['course_end_date']); ?>">
    </div>
</div>
<div class="clearfix"></div>

<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Roll No</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['roll_no']; ?>">
    </div>
</div>
<div class="col-md-6">
    <div class="form-group label-floating">
        <label class="control-label">Enrollment No</label>
        <input type="text" class="form-control" disabled  value="<?php echo $value['enrollment_no']; ?>">
    </div>
</div>
<div class="clearfix"></div>

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