<div class="box box-primary">

  <?php echo form_open_multipart('#', array('name'=>'education_add','id'=>'education_add')); ?>

  <div class="box-body">    
    
     
      <input type="hidden" name="candsid"  id = "candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
       <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
   
    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->eduver)) { echo $mode_of_verification_value->eduver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
      
    <br>
    <div class="text-white div-header">
      Education Details
    </div>
    <br>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College</label>
      <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college'); ?>" class="form-control">
      <?php echo form_error('school_college'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>University/Board <span class="error">*</span></label>
      <?php 
      echo form_dropdown('university_board', $universityname, set_value('university_board'), 'class="custom-select" id="university_board"');
      echo form_error('university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Grade/Class/Marks</label>
      <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks'); ?>" class="form-control">
      <?php echo form_error('grade_class_marks'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification <span class="error">*</span></label>
      <?php 
      echo form_dropdown('qualification', $qualification_name, set_value('qualification'), 'class="custom-select" id="qualification"');
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
    </div>
    <div class="row">
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
    </div>
    <div class="row">
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
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Documents Provided</label>
      <?php 
      echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided'), 'class="custom-select" id="documents_provided"');
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
        echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
        echo form_error('state');
      ?>
    </div>
    </div>
  
    <br>
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
 
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="sbeducation" id='sbeducation' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
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

    $('#education_add').validate({
      rules: {
      qualification : {
        required : true,
        greaterThan: 0   
      },
      university_board : {
        required : true,
         greaterThan: 0   
      },
      month_of_passing : {
        required : true
      },
      year_of_passing : {
        required : true
      },
      enrollment_no : {
        required : true
      }
     },
     messages: {
      qualification : {
        required : "Select Qualification"    
      },
      university_board : {
        required :  "Select University/Board"
      },
      month_of_passing : {
       required :  "Enter Month of Passing"
      },
      year_of_passing : {
         required : "Enter Year of Passing"
      },     
      enrollment_no : {
        required : "Enter Enrollment No"
      }
    
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'Education/save_education'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbeducation').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbeducation').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
});
 
</script>