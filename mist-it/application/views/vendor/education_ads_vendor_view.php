
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">

<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">

<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="education_com_ref" id="education_com_ref"  value="<?php echo set_value('education_com_ref',$details['education_com_ref']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>School/College</label>
        <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college',$details['school_college']); ?>" class="form-control cls_readonly">
        <?php echo form_error('school_college'); ?>
      </div>
     <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>University/Board <span class="error">*</span></label>
         <input type="text" name="university_board" id="university_board" value="<?php echo set_value('university_board',$details['actual_university_name']); ?>" class="form-control cls_readonly">
        <?php echo form_error('university_board'); ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Grade/Class/Marks</label>
        <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks',$details['grade_class_marks']); ?>" class="form-control cls_readonly">
        <?php echo form_error('grade_class_marks'); ?>
      </div>
     
     <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Qualification</label>
         <input type="text" name="qualification" id="qualification" value="<?php echo set_value('qualification',$details['actual_qualification_name']); ?>" class="form-control cls_readonly">
        <?php  echo form_error('qualification');  ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Major</label>
        <input type="text" name="major" id="major" value="<?php echo set_value('major',$details['major']); ?>" class="form-control cls_readonly">
        <?php echo form_error('major'); ?>
      </div>
      <?php ?>
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Course Start Date</label>
        <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date',convert_db_to_display_date($details['course_start_date'])); ?>" class="form-control myDatepicker cls_readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('course_start_date'); ?>
      </div>
  </div>
  <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Course End Date</label>
        <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date',convert_db_to_display_date($details['course_end_date'])); ?>" class="form-control myDatepicker cls_readonly" placeholder="DD-MM-YYYY">
        <?php echo form_error('course_end_date'); ?>
      </div>
      
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Month of Passing</label>
        <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing',$details['month_of_passing']); ?>" class="form-control cls_readonly">
        <?php echo form_error('month_of_passing'); ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Year of Passing</label>
        <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing',$details['year_of_passing']); ?>" class="form-control cls_readonly">
        <?php echo form_error('year_of_passing'); ?>
      </div>
     

      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Roll No</label>
        <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no',$details['roll_no']); ?>" class="form-control cls_readonly">
        <?php echo form_error('roll_no'); ?>
      </div>
   </div>
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Enrollment No</label>
        <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no',$details['enrollment_no']); ?>" class="form-control cls_readonly">
        <?php echo form_error('enrollment_no'); ?>
      </div>
      
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
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

      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>Documents Provided</label>
        <?php 
        echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided',$documents_provided), 'class="custom-select cls_readonly" id="documents_provided"');
        echo form_error('documents_provided'); 
        ?>
      </div>

      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
        <label>City</label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
        <?php echo form_error('city'); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 col-sm-12 col-xs-3 form-group">
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
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>  Others </h5>
</div>
<div class="row">
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>



<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>


 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','clear'=>'Clear','major discrepancy'=>'Major discrepancy','minor discrepancy'=>'Minor discrepancy','no record found'=>'No record found','unable to verify'=>'Unable to verify');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select cls_disabled" id="status"');
        echo form_error('status');
      ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verified By<span class="error"> *</span></label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier's Designation<span class="error"> *</span></label>
      <input type="text" name="verifier_designation" id="verifier_designation" value="<?php echo set_value('verifier_designation',$details['verifier_designation']);?>" class="form-control">
      <?php echo form_error('verifier_designation'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier's Contact Details</label>
      <input type="text" name="verifier_contact_details" id="verifier_contact_details" value="<?php echo set_value('verifier_contact_details',$details['verifier_contact_details']);?>" class="form-control">
      <?php echo form_error('verifier_contact_details'); ?>
    </div>
</div>

<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,pdf,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .tiff, .pdf" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
</div>
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control cls_disabled" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
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
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      
    }
    ?>
</ol>
</div>

 <?php 
   if(isset($attachments[0]['file_name']))
   {
      $attachments_file  =  $attachments[0]['file_name'];
   }
   else
   {
      $attachments_file  =  '';
   } 
  ?>
  <input type="hidden" name="attchments" id="attchments" value="<?php echo set_value('attchments',$attachments_file); ?>" class="form-control">
  <input type="hidden" name="education_id" id="education_id" value="<?php echo set_value('education_id',$details['education_id']); ?>" class="form-control">
  <input type="hidden" name="education_ver_id" id="education_ver_id" value="<?php echo set_value('education_ver_id',$details['education_ver_id']); ?>" class="form-control">
  

<div class="clearfix"></div>

<script>
$('.cls_readonly').prop('disabled',true);

<?php

if(isset($details['final_status']) && !empty($details['final_status'])) 
{ 

    if($details['final_status'] == "approve")
    {
?>
     $('.cls_disabled').prop('disabled',true);
<?php }

}

?>

</script>
<script type="text/javascript">
  

    $('.remove_file').on('click',function(){
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo VENDOR_SITE_URL.'users/remove_uploaded_file/' ?>"+remove_id,
              type: 'GET',
              beforeSend:function(){
                show_alert('deleting...','info');
              },
              complete:function(){
                $('#'+remove_id).remove();                
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == <?php echo SUCCESS_CODE; ?>)
                {
                  show_alert(message,'success'); 
                }
                else
                {
                  show_alert(message,'danger'); 
                }
                location.reload(); 
              }
          });
      } 
      else 
      {
        return;
      }
    });

</script>