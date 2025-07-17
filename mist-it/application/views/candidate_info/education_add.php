<div class="box box-primary">

  <div class="box-body">    
    
     
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
      <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
      <input type="hidden" name="comp_ref_no" id="comp_ref_no" value="<?php echo set_value('comp_ref_no',$details[0]['education_com_ref']); ?>" class="form-control" >
      <input type="hidden" name="component_name" id="component_name" value="Education" class="form-control" >
      <input type="hidden" name="education_id"  value="<?php echo set_value('education_id',$id); ?>" class="form-control">

   
    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->eduver)) { echo $mode_of_verification_value->eduver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
      
      
  
    <div class="box-header">
      <h3 class="box-title">Education Details</h3>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College</label>
      <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college'); ?>" class="form-control">
      <?php echo form_error('school_college'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>University/Board <span class="error">*</span></label>
      <?php 
      echo form_dropdown('university_board', $universityname, set_value('university_board'), 'class="form-control singleSelect" id="university_board"');
      echo form_error('university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Grade/Class/Marks</label>
      <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks'); ?>" class="form-control">
      <?php echo form_error('grade_class_marks'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification <span class="error">*</span></label>
      <?php 
      echo form_dropdown('qualification', $qualification_name, set_value('qualification'), 'class="form-control singleSelect" id="qualification"');
      echo form_error('qualification'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Major</label>
      <input type="text" name="major" id="major" value="<?php echo set_value('major'); ?>" class="form-control">
      <?php echo form_error('major'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Course Start Date</label>
      <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
      <?php echo form_error('course_start_date'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Course End Date</label>
      <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
      <?php echo form_error('course_end_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Month of Passing <span class="error">*</span></label>
      <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing'); ?>" class="form-control">
      <?php echo form_error('month_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Year of Passing <span class="error">*</span></label>
      <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing'); ?>" class="form-control">
      <?php echo form_error('year_of_passing'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Roll No</label>
      <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no'); ?>" class="form-control">
      <?php echo form_error('roll_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Enrollment No <span class="error">*</span></label>
      <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no'); ?>" class="form-control">
      <?php echo form_error('enrollment_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PRN Number</label>
      <input type="text" name="PRN_no" id="PRN_no" value="<?php echo set_value('PRN_no'); ?>" class="form-control">
      <?php echo form_error('PRN_no'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Documents Provided</label>
      <?php 
      echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided'), 'class="form-control" id="documents_provided"');
      echo form_error('documents_provided'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City</label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Attachments & other</h3>
    </div>
 
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    
    <div class="clearfix"></div>  
   
  </div>

<script>
$(document).ready(function(){

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);

   });
</script>