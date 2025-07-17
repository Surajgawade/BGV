<div class="modal-body">
  <div class="result_error" id="result_error"></div>
 

      <?php  
   
   if($url == "StopCheck")
   { 
      if(empty($details['remarks']))
      {
      $details['remarks'] = "Communication received from client requesting to Stop Check.";
      }
   }
   if($url == "Clear")
   {
      if(empty($details['remarks']))
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


   if(($url == "StopCheck") || ($url == "NA"))
   {
     $css_style = 'style="display: none;"';
   }
   else
   {
     $css_style = '';
   }
 
  if($details['doc_submited'] == "aadhar card")
  {
  ?>

<div class="row">
    <div class="col-sm-4 form-group">
      <label> Phone Number 1</label>
      <input type="text" name="contact_no_1" id="contact_no_1" value="<?php echo set_value('contact_no_1', $candidate_details['CandidatesContactNumber']);?>" class="form-control" readonly>
      <?php echo form_error('contact_no_1'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label> Phone Number 2</label>
      <input type="text" name="contact_no_2" id="contact_no_2" value="<?php echo set_value('contact_no_2', $candidate_details['ContactNo1']);?>" class="form-control" readonly>
      <?php echo form_error('contact_no_2'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label> Phone Number 3</label>
      <input type="text" name="contact_no_3" id="contact_no_3" value="<?php echo set_value('contact_no_3', $candidate_details['ContactNo2']);?>" class="form-control" readonly>
      <?php echo form_error('contact_no_3'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 form-group">
      <label>State</label>
      <input type="text" name="cands_state" id="cands_state" value="<?php echo set_value('cands_state',$candidate_details['cands_state']);?>" class="form-control" readonly>
      <?php echo form_error('cands_state'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Gender<span class="error"> *</span></label>
      <input type="text" name="gender" id="gender" value="<?php echo set_value('gender',$candidate_details['gender']);?>" class="form-control" readonly>
      <?php echo form_error('gender'); ?>
      
    </div>
    <div class="col-sm-4 form-group">
      <label>Date of Birth <span class="error"> *</span></label>
      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' readonly>
      <?php echo form_error('DateofBirth'); ?>
    </div>
</div>
<?php
    $dob=$candidate_details['DateofBirth'];
    $diff = (date('Y') - date('Y',strtotime($dob)));
    ?>
<div class="row">
    <div class="col-sm-4 form-group">
      <label>Age</label>
      <input type="text" name="age" id="age" value="<?php echo set_value('age', $diff);?>" class="form-control" readonly>
      <?php echo form_error('age'); ?>
    </div>
</div>
<br>
<?php  } ?>
   <div class="row">
    <div class="col-sm-4 form-group" <?php echo $css_style ?>>
      <label> Mode of verification <span class="error">*</span></label>
    <?php
         $selected = "written";
         $modeofverification = array(''=>'Select','written'=>'Written');
        echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification',$selected), 'class="select2" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <?php  
    if(!empty($details['closuredate']))
     {
        $closuredate =  convert_db_to_display_date($details['closuredate']);
     }
     else{
        $closuredate = date('d-m-Y');
     }
     ?>
    
    <div class="col-sm-4 form-group">
      <label> Closure Date <span class="error">*</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate', $closuredate);?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Remarks <span class="error">*</span></label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" maxlength="500"><?php echo set_value('remarks',$details['remarks']);?></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>
   </div>
  <div class="row">
    <div class="col-sm-4 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>

  </div>
    
       <div class="col-sm-4  form-group">
          <label>Attachments</label>
         <br>
     
            <?php        
                    if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.IDENTITY.$details['clientid'].'/';
                                $actual_file  = $attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
                                echo '<a href="javascript:;" onClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';

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
        </div>
      <div class="clearfix"></div>
  </div>
</div>
<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
</script>
<script type="text/javascript">
   $(".select2").select2();
</script>