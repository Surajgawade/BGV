<div class="modal-body">

 <?php  
   if($url == "StopCheck")
   { 
      if(empty($details['remarks']))
      { 
        $details['remarks'] = "Communication received from client requesting to Stop Check.";
      }
   }
   if($url == "Clear")
   {  if(empty($details['remarks']))
      { 
       $details['remarks'] = "Details matching with records.";
      }
   }
   if($url == "OverseasCheck")
   {
      if(empty($details['remarks']))
      { 
      $details['remarks'] = "Verification could not be obtained since it is an Overseas Check.";
      }
   }
   if($url == "WorkedWithTheSameOrganization")
   { 
      if(empty($details['remarks']))
      {
      $details['remarks'] = "Candidate was working with the same company.";
      }
   }
 
   if(($url == "StopCheck") ||  ($url == "NA") || ($url == "OverseasCheck") || ($url == "UnableToVerify"))
   {
     $css_style = 'style="display: none;"';
      echo '<input type="radio" class="rto_clicked" name="qualification_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="school_college_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="university_board_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="month_of_passing_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="year_of_passing_action" checked value="" style="display:none">';
     
   }
   else
   {
     $css_style = '';
   }

  ?>
<input type="hidden" name="result_update_id" id="result_update_id" value="<?php echo set_value('result_update_id',$details['id']); ?>" class="form-control" >
  <div class="result_error" id="result_error"></div>
  <div <?php echo $css_style ?> >
    <div class="row">


    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Infomation Provided</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Verify Infomation</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Action</label>
    </div>
    </div>
     
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification</label>
      <?php 
        echo form_dropdown('qualification', $qualification, set_value('qualification',$details['qualification']), 'class="select2 singleSelect res_qualification" id="qualification"  disabled');
        echo form_error('qualification'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Qualification<span class="error"> *</span></label>
      <?php 
        echo form_dropdown('res_qualification', $qualification, set_value('res_qualification',$details['res_qualification']), 'class="custom-select singleSelect res_qualification" id="res_qualification"');
        echo form_error('res_qualification'); 
      ?>
    </div>
  
    <?php echo form_error('res_school_college'); ?>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>

      <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="yes" <?php if(isset($details['qualification_action'])){ if($details['qualification_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="no" <?php if(isset($details['qualification_action'])){ if($details['qualification_action']== 'no') { echo 'checked';}} ?>>No </label>
     <!-- <label class="radio-inline"><input type="radio" class="rto_clicked" name="qualification_action" data-val="res_qualification" value="not-obtained" <?php if(isset($details['qualification_action'])){ if($details['qualification_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained </label>-->
     
    </div>
    </div>
    
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College</label>
      <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college',$details['school_college']); ?>" class="form-control" readonly>
      <?php echo form_error('school_college');  ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>School/College<span class="error"> *</span></label>
      <input type="text" name="res_school_college" id="res_school_college" value="<?php echo set_value('res_school_college',$details['res_school_college']); ?>" class="form-control" readonly> 
      <?php echo form_error('res_school_college'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="yes" <?php if(isset($details['school_college_action'])){ if($details['school_college_action']== 'yes') { echo 'checked';}} ?>>Yes </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="no" <?php if(isset($details['school_college_action'])){ if($details['school_college_action']== 'no') { echo 'checked';}} ?>>No </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="school_college_action" data-val="res_school_college" value="not-obtained" <?php if(isset($details['school_college_action'])){ if($details['school_college_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained </label>
         
    </div>
    </div>
    <div class="row"> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> University/Board</label>
      <?php
        echo form_dropdown('university_board',$university, set_value('university_board',$details['university_board']), 'class="select2 res_university_board" id="university_board"  disabled');
        echo form_error('university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>University/Board <span class="error"> *</span></label>
      <?php
        echo form_dropdown('res_university_board',$university, set_value('res_university_board',$details['res_university_board']), 'class="custom-select res_university_board" id="res_university_board"');
      echo form_error('res_university_board'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>

      <label class="radio-inline"><input type="radio" class="rto_clicked" name="university_board_action" data-val="res_university_board" value="yes" <?php if(isset($details['university_board_action'])){ if($details['university_board_action']== 'yes') { echo 'checked';}} ?>>Yes </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="university_board_action" data-val="res_university_board" value="no" <?php if(isset($details['university_board_action'])){ if($details['university_board_action']== 'no') { echo 'checked';}} ?>>No </label>    
  
    </div>
    </div>

 
    <div class="row">

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Month of Passing</label>
      <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing',$details['month_of_passing']); ?>" class="form-control" readonly>
      <?php echo form_error('month_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Month of Passing<span class="error"> *</span></label>
      <input type="text" name="res_month_of_passing" id="res_month_of_passing" value="<?php echo set_value('res_month_of_passing',$details['res_month_of_passing']); ?>" class="form-control" readonly>
      <?php echo form_error('res_month_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>


     <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="yes" <?php if(isset($details['month_of_passing_action'])){ if($details['month_of_passing_action']== 'yes') { echo 'checked';}} ?>>Yes </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="no" <?php if(isset($details['month_of_passing_action'])){ if($details['month_of_passing_action']== 'no') { echo 'checked';}} ?>>No </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="month_of_passing_action" data-val="res_month_of_passing" value="not-obtained" <?php if(isset($details['month_of_passing_action'])){ if($details['month_of_passing_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained </label>

    </div>
    </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Year of Passing</label>
      <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing',$details['year_of_passing']); ?>" class="form-control" readonly>
      <?php echo form_error('year_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Year of Passing<span class="error"> *</span></label>
      <input type="text" name="res_year_of_passing" id="res_year_of_passing" value="<?php echo set_value('res_year_of_passing',$details['res_year_of_passing']); ?>" class="form-control" readonly>
      <?php echo form_error('res_year_of_passing'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>

      <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="yes" <?php if(isset($details['year_of_passing_action'])){ if($details['year_of_passing_action']== 'yes') { echo 'checked';}} ?>>Yes </label>    
     <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="no" <?php if(isset($details['year_of_passing_action'])){ if($details['year_of_passing_action']== 'no') { echo 'checked';}} ?>>No </label>    
   <!-- <label class="radio-inline"><input type="radio" class="rto_clicked" name="year_of_passing_action" data-val="res_year_of_passing" value="not-obtained" <?php if(isset($details['year_of_passing_action'])){ if($details['year_of_passing_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained </label>-->

    </div>
    </div>
   
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Mode of verification<span class="error"> *</span></label>
    
      <?php
        $modeofverification = array('verbal'=> 'Verbal','online'=>'Online','written'=>'Written','letter head'=>'Letter Head');
        echo form_dropdown('res_mode_of_verification', $modeofverification, set_value('res_mode_of_verification',$details['res_mode_of_verification']), 'class="select2" id="res_mode_of_verification"');
        echo form_error('res_mode_of_verification');
      ?>
       
      <?php echo form_error('res_mode_of_verification'); ?>
     
    </div>
  </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verified By<span class="error"> *</span></label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier Designation<span class="error"> *</span></label>
      <input type="text" name="verifier_designation" id="verifier_designation" value="<?php echo set_value('verifier_designation',$details['verifier_designation']);?>" class="form-control">
      <?php echo form_error('verifier_designation'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verifier's Contact Details</label>
      <input type="text" name="verifier_contact_details" id="verifier_contact_details" value="<?php echo set_value('verifier_contact_details',$details['verifier_contact_details']);?>" class="form-control">
      <?php echo form_error('verifier_contact_details'); ?>
    </div>

  </div>  
   <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Genuineness</label>
      <input type="text" name="res_genuineness" id="res_genuineness" value="<?php echo set_value('res_genuineness',$details['res_genuineness']);?>" class="form-control">
      <?php echo form_error('res_genuineness'); ?>
    </div>-->
</div>
 <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date<span class="error"> *</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks<span class="error"> *</span></label>
      <textarea name="res_remarks" id="res_remarks" rows="1" class="form-control add_res_remarks"><?php echo set_value('res_remarks',$details['remarks']);?></textarea>
      <?php echo form_error('res_remarks'); ?>
    </div>
</div>
 <div class="row">

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
</div>
       <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachments</label>
         <br>
     
                       <?php        
                        if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.EDUCATION.$details['clientid'].'/';
                                $actual_file  = $attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
                                echo '<a href="javascript:;" onClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" ><br>'.$attachments[$i]['file_name'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';

                                if($attachments[$i]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='remove_file[]' value='".$attachments[$i]['id']."' id='remove_file' > Remove Attachment</label>";
                                }

                                if($attachments[$i]['status'] == "2")
                                {
                                   echo "<label><input type='checkbox' name='add_file[]' value='".$attachments[$i]['id']."' id='add_file' > Add Attachment</label>";
                                }
                                echo '</div>';
                            }
                        }
                        ?>

                        <?php        
                        if(!empty($university_selected_attachments))
                        {
                          
                            for($i=0; $i < count($university_selected_attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.UNIVERSITY_PIC;
                                $actual_file  = $university_selected_attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
                                echo '<a href="javascript:;" onClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" ><br>'.$university_selected_attachments[$i]['file_name'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';

                                if($university_selected_attachments[$i]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='remove_file[]' value='".$university_selected_attachments[$i]['id']."' id='remove_file' > Remove Attachment</label>";
                                }

                                if($university_selected_attachments[$i]['status'] == "2")
                                {
                                   echo "<label><input type='checkbox' name='add_file[]' value='".$university_selected_attachments[$i]['id']."' id='add_file' > Add Attachment</label>";
                                }
                                echo '</div>';
                            }
                        }
                        ?>


                    <?php        
                    if($vendor_attachments)
                        {
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url = "'".SITE_URL.EDUCATION.$folder_name.'/';
                                $actual_file  = $vendor_attachments[$j]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group thumb" id="item-'.$vendor_attachments[$j]['id'].'">';
                                echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';

                                if($vendor_attachments[$j]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='vendor_remove_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_remove_file' > Remove Attachment</label>";
                                }

                                if($vendor_attachments[$j]['status'] == "2")
                                {
                                   echo "<label><input type='checkbox' name='vendor_add_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_add_file' > Add Attachment</label>";
                                }
                                 
                               echo '</div>';
                              
                            }
                        }
                    ?> 
        </div>
      <div class="clearfix"></div>
  </div>
</div>
<script>
/*$(document).ready(function(){

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
});
*/
  $('.myDatepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    endDate: new Date()
  });


//$('#res_qualification').attr("style", "pointer-events: none;");
//$('#res_university_board').attr("style", "pointer-events: none;");

</script>
<script type="text/javascript">
   $(".select2").select2();
</script>
