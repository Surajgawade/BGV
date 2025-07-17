<?php
 if(($url == "StopCheck") || ($url == "NA") || ($url == "UnableToVerify"))
   {
     $css_style = 'style="display: none;"';
   }
   else
   {
     $css_style = '';
   }

  ?>

<div class="modal-body">
  <div class="row" <?php echo $css_style; ?>>
    <?php      
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
   
    <div class="col-sm-4 form-group">
      <label> Mode of verification</label>
      <input type="text" name="mode_of_verification" id="mode_of_verification" readonly="readonly" value="<?php if(isset($mode_of_verification_value->narcver)) { echo $mode_of_verification_value->narcver; } ?>" class="form-control">
      <?php echo form_error('mode_of_verification'); ?>
  
   </div>
    <div class="col-sm-4 form-group">
      <label>Amphetamine Screen, Urine</label>
      <?php
        echo form_dropdown('amphetamine_screen', array('negative' => 'Negative','positive' => 'Positive'), set_value('amphetamine_screen',$details['amphetamine_screen']), 'class="form-control" id="amphetamine_screen"');
        echo form_error('amphetamine_screen');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Cannabinoids Screen, Urine</label>
      <?php
        echo form_dropdown('cannabinoids_screen', array('negative' => 'Negative','positive' => 'Positive'), set_value('cannabinoids_screen',$details['cannabinoids_screen']), 'class="form-control" id="cannabinoids_screen"');
        echo form_error('cannabinoids_screen');
      ?>
    </div>
  </div>
  <div class="row" <?php echo $css_style; ?>>
    <div class="col-sm-4 form-group">
      <label>Cocaine Screen, Urine</label>
      <?php
        echo form_dropdown('cocaine_screen', array('negative' => 'Negative','positive' => 'Positive'), set_value('cocaine_screen',$details['cocaine_screen']), 'class="form-control" id="cocaine_screen"');
        echo form_error('cocaine_screen');
      ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-4 form-group">
      <label>Opiates Screen, Urine</label>
      <?php
        echo form_dropdown('opiates_screen', array('negative' => 'Negative','positive' => 'Positive'), set_value('opiates_screen',$details['opiates_screen']), 'class="form-control" id="opiates_screen"');
        echo form_error('opiates_screen');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Phencyclidine Screen, Urine</label>
      <?php
        echo form_dropdown('phencyclidine_screen', array('negative' => 'Negative','positive' => 'Positive'), set_value('phencyclidine_screen',$details['phencyclidine_screen']), 'class="form-control" id="phencyclidine_screen"');
        echo form_error('phencyclidine_screen');
      ?>
    </div>
 </div>
 <div class="row">
   <div class="col-sm-4 form-group">
      <label>Remarks</label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" maxlength="500"><?php echo set_value('remarks',$details['remarks']); ?></textarea>
      <?php echo form_error('attchments_ver'); ?>
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
      <label> Closure Date</label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate', $closuredate );?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
  
    <div class="col-sm-4 form-group">
      <label>Attachment <span class="error">(jpeg,jpg,png,pdf files only)</span></label>
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
                                $url  = "'".SITE_URL.DRUGS.$details['clientid'].'/';
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

                            
                        if($vendor_attachments)
                        {
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url = "'".SITE_URL.DRUGS.$folder_name.'/';
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