<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Candidate Name<span class="error"> *</span></label>
    <input type="text" name="candsid" readonly="readonly" id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control">
    <?php echo form_error('candsid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Name<span class="error"> *</span></label>
      <input type="text" name="clientname" readonly="readonly" id="candsid" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$details['package_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($details['caserecddate'])); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control">
    </div>
    <div class="clearfix"></div>  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Component Ref No</label>
      <input type="text" name="education_com_ref" id="education_com_ref" readonly="readonly" value="<?php echo set_value('education_com_ref',$details['education_com_ref']); ?>" class="form-control">
      <?php echo form_error('education_com_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label><?php echo REFNO; ?></label>
      <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$details['cmp_ref_no']); ?>" class="form-control">
      <?php echo form_error('cmp_ref_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Initiation date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($details['iniated_date'])); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Infomation Provided</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Verify Infomation</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Action</label>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification</label>
      <?php 
        echo form_dropdown('qualification', array(), set_value('qualification'), 'class="form-control singleSelect res_qualification" id="qualification"');
        echo form_error('qualification'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification</label>
      <?php 
        echo form_dropdown('res_qualification', array(), set_value('res_qualification',$details['res_qualification']), 'class="form-control singleSelect res_qualification" id="res_qualification"');
        echo form_error('res_qualification'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College</label>
      <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college',$details['school_college']); ?>" class="form-control">
      <?php echo form_error('school_college'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College</label>
      <input type="text" name="res_school_college" id="res_school_college" value="<?php echo set_value('res_school_college',$details['res_school_college']); ?>" class="form-control">
      <?php echo form_error('res_school_college'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> University/Board</label>
      <?php
        echo form_dropdown('university_board', array(), set_value('university_board',$details['university_board']), 'class="form-control  res_university_board" id="university_board"');
        echo form_error('university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>University/Board</label>
      <?php
        echo form_dropdown('res_university_board', array(), set_value('res_university_board',$details['res_university_board']), 'class="form-control  res_university_board" id="res_university_board"');
      echo form_error('res_university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="university_board_action" data-val="res_university_board" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="university_board_action" data-val="res_university_board" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="university_board_action" data-val="res_university_board" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Major</label>
      <input type="text" name="major" id="major" value="<?php echo set_value('major',$details['major']); ?>" class="form-control">
      <?php echo form_error('major'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Major</label>
      <input type="text" name="res_major" id="res_major" value="<?php echo set_value('res_major',$details['res_major']); ?>" class="form-control">
      <?php echo form_error('res_major'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="major_action" data-val="res_major" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="major_action" data-val="res_major" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="major_action" data-val="res_major" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Month of Passing</label>
      <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing',$details['month_of_passing']); ?>" class="form-control">
      <?php echo form_error('month_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Month of Passing</label>
      <input type="text" name="res_month_of_passing" id="res_month_of_passing" value="<?php echo set_value('res_month_of_passing',$details['res_month_of_passing']); ?>" class="form-control">
      <?php echo form_error('res_month_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Year of Passing</label>
      <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing',$details['year_of_passing']); ?>" class="form-control">
      <?php echo form_error('year_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Year of Passing</label>
      <input type="text" name="res_year_of_passing" id="res_year_of_passing" value="<?php echo set_value('res_year_of_passing',$details['res_year_of_passing']); ?>" class="form-control">
      <?php echo form_error('res_year_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Grade/Class/Marks</label>
      <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks',$details['grade_class_marks']); ?>" class="form-control">
      <?php echo form_error('grade_class_marks'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Grade/Class/Marks</label>
      <input type="text" name="res_grade_class_marks" id="res_grade_class_marks" value="<?php echo set_value('res_grade_class_marks',$details['res_grade_class_marks']); ?>" class="form-control">
      <?php echo form_error('res_grade_class_marks'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="grade_class_marks_action" data-val="res_grade_class_marks" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="grade_class_marks_action" data-val="res_grade_class_marks" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="grade_class_marks_action" data-val="res_grade_class_marks" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Course Start Date<span class="error"> *</span></label>
      <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date',$details['course_start_date']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('course_start_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Course Start Date<span class="error"> *</span></label>
      <input type="text" name="res_course_start_date" id="res_course_start_date" value="<?php echo set_value('res_course_start_date',$details['res_course_start_date']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_course_start_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_start_date_action" data-val="res_course_start_date" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_start_date_action" data-val="res_course_start_date" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_start_date_action" data-val="res_course_start_date" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Course End Date <span class="error"> *</span></label>
      <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date',$details['course_end_date']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('course_end_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Course End Date<span class="error"> *</span></label>
      <input type="text" name="res_course_end_date" id="res_course_end_date" value="<?php echo set_value('res_course_end_date',$details['res_course_end_date']);?>" class="form-control " placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_course_end_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_end_date_action" data-val="res_course_end_date" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_end_date_action" data-val="res_course_end_date" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="course_end_date_action" data-val="res_course_end_date" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Roll No</label>
      <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no',$details['roll_no']); ?>" class="form-control">
      <?php echo form_error('roll_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Roll No</label>
      <input type="text" name="res_roll_no" id="res_roll_no" value="<?php echo set_value('res_roll_no',$details['res_roll_no']); ?>" class="form-control">
      <?php echo form_error('res_roll_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="roll_no_action" data-val="res_roll_no" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="roll_no_action" data-val="res_roll_no" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="roll_no_action" data-val="res_roll_no" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Enrollment No</label>
      <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no',$details['enrollment_no']); ?>" class="form-control">
      <?php echo form_error('enrollment_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Enrollment No</label>
      <input type="text" name="res_enrollment_no" id="res_enrollment_no" value="<?php echo set_value('res_enrollment_no',$details['res_enrollment_no']); ?>" class="form-control">
      <?php echo form_error('res_enrollment_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="enrollment_no_action" data-val="res_enrollment_no" value="yes">Yes </label>
      <label class="radio-inline" checked><input type="radio" class="rto_clicked" name="enrollment_no_action" data-val="res_enrollment_no" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="enrollment_no_action" data-val="res_enrollment_no" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PRN No</label>
      <input type="text" name="PRN_no" id="PRN_no" value="<?php echo set_value('PRN_no',$details['PRN_no']); ?>" class="form-control">
      <?php echo form_error('PRN_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PRN No</label>
      <input type="text" name="res_PRN_no" id="res_PRN_no" value="<?php echo set_value('res_PRN_no',$details['res_PRN_no']); ?>" class="form-control">
      <?php echo form_error('res_PRN_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="PRN_no_action" data-val="res_PRN_no" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="PRN_no_action" data-val="res_PRN_no" value="no" >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="PRN_no_action" data-val="res_PRN_no" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Mode of verification</label>
      <input type="text" name="res_mode_of_verification" id="res_mode_of_verification" value="<?php echo set_value('res_mode_of_verification',$details['res_mode_of_verification']);?>" class="form-control">
      <?php echo form_error('res_mode_of_verification'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Online URL</label>
      <input type="text" name="res_online_URL" id="res_online_URL" value="<?php echo set_value('res_online_URL',$details['res_online_URL']);?>" class="form-control">
      <?php echo form_error('res_online_URL'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verified By</label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier Designation</label>
      <input type="text" name="verifier_designation" id="verifier_designation" value="<?php echo set_value('verifier_designation',$details['verifier_designation']);?>" class="form-control">
      <?php echo form_error('verifier_designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier's Contact Details</label>
      <input type="text" name="verifier_contact_details" id="verifier_contact_details" value="<?php echo set_value('verifier_contact_details',$details['verifier_contact_details']);?>" class="form-control">
      <?php echo form_error('verifier_contact_details'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Genuineness</label>
      <input type="text" name="res_genuineness" id="res_genuineness" value="<?php echo set_value('res_genuineness',$details['res_genuineness']);?>" class="form-control">
      <?php echo form_error('res_genuineness'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date</label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks</label>
      <textarea name="res_remarks" id="res_remarks" rows="1" class="form-control add_res_remarks"><?php echo set_value('res_remarks',$details['res_remarks']);?></textarea>
      <?php echo form_error('res_remarks'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){

  $.getJSON("<?php echo ADMIN_SITE_URL.'education/university_dropdown'; ?>",function(data){
    var stringToAppend = "";
    $.each(data.university_list,function(key,val) {
      stringToAppend += "<option value='" + key + "'>" + val + "</option>";
    });
    $(".res_university_board").html(stringToAppend);
    $(".res_university_board option[value='<?= $details['university_board']?>']").attr('selected', 'selected');
  });

  $.getJSON("<?php echo ADMIN_SITE_URL.'education/qualification_dropdown'; ?>",function(data){
    var stringToAppend = "";
    $.each(data.qualification_list,function(key,val) {
      stringToAppend += "<option value='" + key + "'>" + val + "</option>";
    });
    $(".res_qualification").html(stringToAppend);
    $(".res_qualification option[value='<?= $details['qualification']?>']").attr('selected', 'selected');
  });

  $('.myDatepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    endDate: new Date()
  });
});
</script>