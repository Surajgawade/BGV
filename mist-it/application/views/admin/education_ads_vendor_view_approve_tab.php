<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Candidate Deatils</h5>
</div>

<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">

<input type="hidden" name="education_id" id="education_id" value="<?php echo set_value('education_id',$details['id']); ?>">


<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['education_com_ref']); ?>">


<input type="hidden" name="entity_id" id="entity_id1" value="<?php echo set_value('entity_id',$details['entity_id']); ?>">
<input type="hidden" name="package_id" id="package_id1" value="<?php echo set_value('package_id',$details['package_id']); ?>">
<input type="hidden" name="clientid" id="clientid1" value="<?php echo set_value('clientid',$details['clientid']); ?>">
<input type="hidden" name="education_result_id" id="education_result_id1" value="<?php echo set_value('education_result_id',$details['education_result_id']); ?>">

<input type="hidden" name="cl_id" id="cl_id" value="<?php echo set_value('cl_id',$details['view_vendor_master_log_id']); ?>">

<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="education_com_ref" id="education_com_ref"  value="<?php echo set_value('education_com_ref',$details['education_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

 <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>School/College</label>
        <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college',$details['school_college']); ?>" class="form-control cls_readonly">
        <?php echo form_error('school_college'); ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>University/Board <span class="error">*</span></label>
        <?php 
          echo form_dropdown('university_board', $university, set_value('university_board',$details['university_board']), 'class="custom-select cls_readonly" id="university_board"');
          echo form_error('university_board'); 
        ?>
      </div>

      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Grade/Class/Marks</label>
        <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks',$details['grade_class_marks']); ?>" class="form-control cls_readonly">
        <?php echo form_error('grade_class_marks'); ?>
      </div>
    </div>  

    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Qualification</label>
        <?php 
        echo form_dropdown('qualification', $qualification, set_value('qualification',$details['qualification']), 'class="custom-select  cls_readonly" id="qualification"');
        echo form_error('qualification'); 
        ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Major</label>
        <input type="text" name="major" id="major" value="<?php echo set_value('major',$details['major']); ?>" class="form-control cls_readonly">
        <?php echo form_error('major'); ?>
      </div>
      <?php ?>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Course Start Date</label>
        <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date',convert_db_to_display_date($details['course_start_date'])); ?>" class="form-control myDatepicker cls_readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('course_start_date'); ?>
      </div>

      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Course End Date</label>
        <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date',convert_db_to_display_date($details['course_end_date'])); ?>" class="form-control myDatepicker cls_readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('course_end_date'); ?>
      </div>
     </div> 
    <div class="row"> 
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Month of Passing</label>
        <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing',$details['month_of_passing']); ?>" class="form-control cls_readonly">
        <?php echo form_error('month_of_passing'); ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Year of Passing</label>
        <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing',$details['year_of_passing']); ?>" class="form-control cls_readonly">
        <?php echo form_error('year_of_passing'); ?>
      </div>
    

      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Roll No</label>
        <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no',$details['roll_no']); ?>" class="form-control cls_readonly">
        <?php echo form_error('roll_no'); ?>
      </div>
       <div class="clearfix"></div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Enrollment No</label>
        <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no',$details['enrollment_no']); ?>" class="form-control cls_readonly">
        <?php echo form_error('enrollment_no'); ?>
      </div>
     </div>
      <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>PRN Number</label>
        <input type="text" name="PRN_no" id="PRN_no" value="<?php echo set_value('PRN_no',$details['PRN_no']); ?>" class="form-control cls_readonly">
        <?php echo form_error('PRN_no'); ?>
      </div>
      <?php  
       if(!empty($details['documents_provided']))
       {
       $documents_provided = json_decode($details['documents_provided']);
       }
       else
       {
        $documents_provided = $details['documents_provided'];
       }
      ?>

      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Documents Provided</label>
        <?php 
        echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided',$documents_provided), 'class="custom-select cls_readonly" id="documents_provided"');
        echo form_error('documents_provided'); 
        ?>
      </div>

      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>City</label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
        <?php echo form_error('city'); ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="custom-select singleSelect cls_readonly" id="state"');
          echo form_error('state');
        ?>
      </div>
</div>
    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <label>Documents</label>
    <ol>
    <?php 
    foreach ($attachments_file as $key => $value) {
    $url  = SITE_URL.EDUCATION.$details['clientid'].'/';
   
    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."></a>"; ?></li> <?php
      
    }
    ?>
    </ol>
    </div>

      
      <div class="clearfix"></div>
    
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>  Others </h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
   <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$details['has_case_id']), 'class="form-control cls_readonly"  id="has_case_id"');
          echo form_error('has_case_id');
        ?>
</div>-->

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>

 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','clear'=>'Clear','major discrepancy'=>'Major discrepancy','minor discrepancy'=>'Minor discrepancy','no record found'=>'No record found','unable to verify'=>'Unable to verify');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select cls_readonly" id="status"');
        echo form_error('status');
      ?>
 </div>
  
</div>

<div class="row">
  <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control cls_readonly" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
    </div> 



</div>

 <div class="col-md-6 col-sm-12 col-xs-6 form-group">
 <label>Vendor attachments</label>
  <ol>

 <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
    $folder_name = "vendor_file";
    $url  = SITE_URL.EDUCATION.$folder_name.'/';  
    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."></a>"; ?></li> <?php
      
    }
    ?>
</ol>
</div>

<script>$('.cls_readonly').prop('disabled',true);</script>

<script type="text/javascript">
 $(".status").change(function() {    
   var status = $(".status option:selected").text();
 
   if(status == 'Insufficiency' || status == 'WIP')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
   }
   if((status == "clear") || (status == "major discrepancy") || (status == "minor discrepancy") || (status == "no record found")  || (status == "unable to verify"))
   {

     $('.fin_status').val(status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }
  
});

 var f_status = '<?php echo ucwords($details['final_status'])?>';

   if(f_status == 'Insufficiency' || f_status == 'Wip')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
   }
   if((f_status == "Clear") || (f_status == "Major Discrepancy") || (f_status == "Minor Discrepancy") || (f_status == "No Record Found")  || (f_status == "Unable To Verify"))
   {

     $('.fin_status').val(f_status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }


 </script>
<script type="text/javascript">
 function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}
</script>