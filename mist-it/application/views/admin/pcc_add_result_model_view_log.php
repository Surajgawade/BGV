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
        $details['remarks'] = "No record found against the subject.";
      }
   }
  if($url == "OverseasCheck")
   {
      if(empty($details['remarks']))
      {
       $details['remarks'] = "Police verification cannot be obtained since it is an Overseas Check.";
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

  ?>  
  <input type="hidden" name="result_update_id" id="result_update_id" value="<?php echo set_value('result_update_id',$details['id']); ?>" class="form-control" >
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-34 form-group" <?php echo $css_style ?>>
      <label> Mode of verification <span class="error">*</span></label>
      <?php
      $modeofverification = array('written'=>'Written','verbal'=>'Verbal');
    
      echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="select2" id="mode_of_verification"');
      
        echo form_error('mode_of_verification');
      ?>
       <!-- <input type="text" name="mode_of_verification" readonly="readonly" id="mode_of_verification" value="<?php echo set_value('mode_of_verification',$details['mode_of_veri']);?>" class="form-control">
      <?php echo form_error('mode_of_verification'); ?>-->
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Application ID/Ref</label>
      <input type="text" name="application_id_ref" maxlength="40" id="application_id_ref" value="<?php echo set_value('application_id_ref',$details['application_id_ref']);?>" class="form-control">
      <?php echo form_error('application_id_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Submission Date </label>
      <input type="text" name="submission_date" id="submission_date" value="<?php echo set_value('submission_date',convert_db_to_display_date($details['submission_date']));?>" class="form-control myDatepicker">
      <?php echo form_error('submission_date'); ?>
    </div>
   </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Police Station <span class="error">*</span></label>
      <input type="text" name="police_station" maxlength="100" id="police_station" value="<?php echo set_value('police_station',$details['police_station']);?>" class="form-control">
      <?php echo form_error('police_station'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Police station visit date</label>
      <input type="text" name="police_station_visit_date" id="police_station_visit_date" value="<?php echo set_value('police_station_visit_date',convert_db_to_display_date($details['police_station_visit_date']));?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
      <?php echo form_error('police_station_visit_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Name/Designation of the police personnel <span class="error">*</span></label>
      <input type="text" name="name_designation_police" maxlength="40" id="name_designation_police" value="<?php echo set_value('name_designation_police',$details['name_designation_police']);?>" class="form-control">
      <?php echo form_error('name_designation_police'); ?>
    </div>
    </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" <?php echo $css_style ?>>
      <label> Contact number of the police personnel<span class="error">*</span></label>
      <input type="text" name="contact_number_police" maxlength="14" minlength="8" id="contact_number_police" value="<?php echo set_value('contact_number_police',$details['contact_number_police']);?>" class="form-control">
      <?php echo form_error('contact_number_police'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date <span class="error">*</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remarks <span class="error">*</span></label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" maxlength="500"><?php echo set_value('remarks',$details['remarks']); ?></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png,pdf files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
 
    
    </div>

    <div class="clearfix"></div>
       <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachments</label>
         <br>
     
            <?php        
                    if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.PCC.$details['clientid'].'/';
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
                        if($vendor_attachments)
                        {
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url = "'".SITE_URL.PCC.$folder_name.'/';
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
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
</script>
<script type="text/javascript">
   $(".select2").select2();
</script>