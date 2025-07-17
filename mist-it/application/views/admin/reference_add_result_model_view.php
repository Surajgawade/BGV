<div class="modal-body">
   <?php  
   if($url == "StopCheck")
   { 
      $details['remarks'] = "Communication received from client requesting to Stop Check.";
   }
   
    if($url == "OverseasCheck")
   {
     $details['remarks'] = "Verification cannot be obtained since it is an Overseas Check.";
   }
  
 
   if(($url == "StopCheck") || ($url == "NA") || ($url == "OverseasCheck") || ($url == "UnableToVerify"))
   {
     $css_style = 'style="display: none;"';
   }
   else
   {
     $css_style = '';
   }

  ?>
  
  <div class="result_error" id="result_error"></div>
  
    <div <?php echo $css_style;  ?>>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Ability to Handle Pressure<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="handle_pressure_value" name="handle_pressure_value" class="form-control handle_pressure_value" rows="1" value="<?php echo set_value('handle_pressure_value'); ?>" maxlength="500"><?php echo set_value('handle_pressure_value',$details['handle_pressure_value']); ?></textarea>
    </div>
</div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Attendance/Punctuality<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="attendance_value" name="attendance_value" class="form-control attendance_value" rows="1" value="<?php echo set_value('attendance_value'); ?>" maxlength="500"><?php echo set_value('attendance_value',$details['attendance_value']); ?></textarea>
    </div>
</div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Integrity, Character & Honesty<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="integrity_value" name="integrity_value" class="form-control integrity_value" rows="1" value="<?php echo set_value('integrity_value'); ?>" maxlength="500"><?php echo set_value('integrity_value',$details['integrity_value']); ?></textarea>
    </div>
    
 </div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Leadership Skills<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
      <textarea id="leadership_skills_value" name="leadership_skills_value" class="form-control leadership_skills_value" rows="1" value="<?php echo set_value('leadership_skills_value'); ?>" maxlength="500"><?php echo set_value('leadership_skills_value',$details['leadership_skills_value']); ?></textarea>
    </div>
</div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Responsibilities & Duties<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="responsibilities_value" name="responsibilities_value" class="form-control responsibilities_value" rows="1" value="<?php echo set_value('responsibilities_value'); ?>" maxlength="500"><?php echo set_value('responsibilities_value',$details['responsibilities_value']); ?></textarea>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 form-group">
      <label>Specific Achievements<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
      <textarea id="achievements_value" name="achievements_value" class="form-control achievements_value" rows="1" value="<?php echo set_value('achievements_value'); ?>" maxlength="500"><?php echo set_value('achievements_value',$details['achievements_value']); ?></textarea>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 form-group">
      <label>Strengths<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
      <textarea id="strengths_value" name="strengths_value" class="form-control strengths_value" rows="1" value="<?php echo set_value('strengths_value'); ?>" maxlength="500"><?php echo set_value('strengths_value',$details['strengths_value']); ?></textarea>
    </div>
 </div>

<div class="row">
    <div class="col-sm-4 form-group">
      <label>Weaknesses<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="weakness_value" name="weakness_value" class="form-control weakness_value" rows="1" value="<?php echo set_value('weakness_value'); ?>" maxlength="500"><?php echo set_value('weakness_value',$details['weakness_value']); ?></textarea>
    </div>
    
</div>
<div class="row">
    <div class="col-sm-4 form-group">
      <label>Team Player<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
      <textarea id="team_player_value" name="team_player_value" class="form-control team_player_value" rows="1" value="<?php echo set_value('team_player_value'); ?>" maxlength="500"><?php echo set_value('team_player_value',$details['team_player_value']); ?></textarea>
    </div>
  </div>
  <div class="row">
     <div class="col-sm-4 form-group">
      <label>Overall Performance<span class="error"> *</span></label>
    </div>
    <div class="col-sm-4 form-group">
      <input type="number" min="0" max="10" name="overall_performance" id="overall_performance" value="<?php echo set_value('overall_performance',$details['overall_performance']); ?>" class="form-control">
    </div>

 </div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label>Additional Comments</label>
    </div>
    <div class="col-sm-4 form-group">
       <textarea id="additional_comments" name="additional_comments" class="form-control additional_comments" rows="1" value="<?php echo set_value('additional_comments'); ?>" maxlength="500"><?php echo set_value('additional_comments',$details['additional_comments']); ?></textarea>
      <?php echo form_error('additional_comments'); ?>
    </div>
    
 </div>

   <div class="row">
    <div class="col-sm-4 form-group">
      <label> Mode of verification<span class="error"> *</span></label>
      <?php
      $modeofverification = array(''=> 'Select','email'=> 'Email','summary email'=>'Summary Email');
        echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="select2" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
  </div>
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
  <div class="row">
    <div class="col-sm-4 form-group">
      <label> Closure Date<span class="error"> *</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate', $closuredate );?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    
    <div class="col-sm-4 form-group">
      <label>Remarks<span class="error"> *</span></label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks ref_rem_update" rows="1" value="<?php echo set_value('remarks'); ?>" maxlength="500"><?php echo set_value('remarks',$details['remarks']); ?></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>
  
    <div class="col-sm-4 form-group">
      <label>Attachment  <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
</div>
 <div class="row"> 
    
       <div class="col-sm-4 form-group">
          <label>Attachments</label>
         <br>
     
            <?php        
                    if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url_attachment = "'".SITE_URL.REFERENCES.$details['clientid'].'/';
                                $actual_file  = $attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
                                echo '<a href="javascript:;" onClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url_attachment.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';

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


$('.hide_show').hide();
$(':input[type="number"]').change(function () { 
  var rating = $(this).val();
  var name = $(this).attr('id');
  if(rating <= 10)
  {
    if(rating > 0 && rating < 5)
      $('.'+name).show();
    else
      $('.'+name).hide();
  } else {
    show_alert('select rating between 0 to 10');
  }

});


$('#mode_of_verification').on('change',function(){
                
          var mode_of_verification  =   $('#mode_of_verification').val();

          if(mode_of_verification == 'email')
           {
               if('<?php echo $url; ?>' == "Clear")
                {
                  $('.ref_rem_update').val('Received email from '+'<?php echo $details['name_of_reference'] ?>'+' who verified all details as Clear.' ); 
 
                }
              
           } 
           if(mode_of_verification == 'summary email')
           {
               if('<?php echo $url; ?>' == "Clear")
                {
                  $('.ref_rem_update').val('Spoke to '+'<?php echo $details['name_of_reference'] ?>'+' who verified all details as clear hence summary email sent.'); 
 
                }
              
           }   

        
      });

</script>
<script type="text/javascript">
   $(".select2").select2();
</script>