<div class="modal-body">
  <div class="result_error" id="result_error"></div>
 

  <?php
   
   if($url == "OverseasCheck")
   { 
      if(empty($details['remarks']))
      { 
      $details['remarks'] = "Court verification cannot be obtained since it is an Overseas Check.";
      }
   }
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

    if(($url == "StopCheck") ||  ($url == "NA") ||  ($url == 'OverseasCheck'))
   {
     $css_style = 'style="display: none;"';
   }
   else
   {
     $css_style = '';
   }


  ?>   
  <div class="row">
   <div class="col-sm-4 form-group" <?php echo $css_style ?>>
      <label> Mode of verification<span class="error">*</span></label>
    <?php
        $modeofverification = array('written'=>'Written');

        echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="select2" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
      
    </div>
   <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Verified date</label>
      <input type="text" name="verified_date" id="verified_date" value="<?php echo set_value('verified_date',convert_db_to_display_date($details['verified_date']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('verified_date'); ?>
    </div>-->
    <div class="col-sm-4 form-group" <?php echo $css_style ?>>
      <label> Advocate's Name <span class="error">*</span></label>
      <input type="text" name="advocate_name" id="advocate_name" value="<?php echo set_value('advocate_name',$details['advocate_name']);?>" class="form-control" >
      <?php echo form_error('advocate_name'); ?>
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
      <label> Closure Date<span class="error">*</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',$closuredate);?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4 form-group" >
      <label>Attachment <span class="error">(jpeg,jpg,png files only)</span></label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Remarks<span class="error">*</span></label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" maxlength="500"><?php echo set_value('attchments_ver',$details['remarks']); ?></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>

  </div>
       <div class="col-sm-4 form-group">
          <label>Attachments</label>
         <br>
     
            <?php        
                    if($attachments)
                        {
                          
                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url  = "'".SITE_URL.COURT_VERIFICATION.$details['clientid'].'/';
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

                       <?php        
                        if($vendor_attachments)
                        {
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url = "'".SITE_URL.COURT_VERIFICATION.$folder_name.'/';
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

<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
</script>
<script type="text/javascript">
   $(".select2").select2();
   var adv_namae =  $('#adv_name').val();
    $('#advocate_name').val(adv_namae);
</script>