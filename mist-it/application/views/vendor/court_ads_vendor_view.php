
 <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">
<div class="row">

<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Comp ref no</label>
  <input type="text" name="court_com_ref" id="court_com_ref"  value="<?php echo set_value('add_com_ref',$details['court_com_ref']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Father Name</label>
  <input type="text" name="candidate_father_name"  id="candidate_father_name" value="<?php echo set_value('candidate_father_name',$details['NameofCandidateFather']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
  <label>Candidate  DOB</label>
  <input type="text" name="candidate_dob"  id="candidate_dob" value="<?php echo set_value('candidate_dob',convert_db_to_display_date($details['DateofBirth'])); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Street Address</label>
    <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control cls_readonly"><?php echo set_value('street_address',$details['street_address']); ?></textarea>
    <?php echo form_error('street_address'); ?>
</div>

 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>City</label>
    <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
    <?php echo form_error('city'); ?>
  </div>
</div>
<div class="row">
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Pincode</label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_readonly">
        <?php echo form_error('pincode'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="custom-select singleSelect cls_readonly" id="state"');
          echo form_error('state');
        ?>
      </div>
</div>
<!--
<h5>Documents</h5>
<?php  if(!empty($attachments_file)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments_file as $key => $value) {
       
        $url  = SITE_URL.COURT_VERIFICATION.$details['clientid'].'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

      <?php } ?>

    </tr>
  </table>-->
 
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
<?php if($details['final_status'] != 'approve'){  ?>
 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','clear'=>'Clear','possible match'=>'Possible Match');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select cls_disabled" id="status"');
        echo form_error('status');
      ?>
</div>
<?php }else { 
 echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
  echo '<label>Check Status</label>';
  echo "<input type='text' name='status' id='status' value='".ucwords($details['final_status'])."' class='form-control cls_readonly'>";
  echo '</div>';
}?>
</div>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,pdf,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .tiff,.pdf" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
</div>

<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control cls_disabled" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
</div> 
</div>

<h5>Vendor Attachments</h5>
  <?php  if(!empty($attachments)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
       <th style='border: 1px solid black;padding: 8px;'>Action</th>
       <th style='border: 1px solid black;padding: 8px;'>Undo</th>
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments as $key => $value) {
       
        $folder_name = "vendor_file";
        $url  = SITE_URL.COURT_VERIFICATION.$folder_name.'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'><?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id'].">"; if($value['status'] == "1" &&  $details['final_status'] != "approve") { echo "<i class='fa fa-trash'></i>"; } echo "</a>"; ?></td>
         <td style='border: 1px solid black;padding: 8px;'><?php echo "<a href='javascript:void(0)' class='undo_file' data-id=".$value['id'].">";  if($value['status'] == "2" &&  $details['final_status'] != "approve") { echo "<i class='fa fa-undo'></i>"; } echo "</a>"; ?></td>
         </tr>
        

      <?php } ?>

    </tr>
  </table>
     <?php 
   
   if(!empty($attachments))
   {
      foreach ($attachments as $key => $value) {
        if($value['status'] == 1){
        
          $attachments_file  =  $value['file_name'];
          break;
        }  
        else{
          
           $attachments_file  =  '';
        }
      }
   }
   else
   {

      $attachments_file  =  '';
   } 
  
  ?>

  <input type="hidden" name="attchments" id="attchments" value="<?php echo set_value('attchments',$attachments_file); ?>" class="form-control">

<div class="clearfix"></div>

<?php  

if($details['generate'] == 1 && $details['final_status'] == "clear")
{
  $css_style = "style= 'display: block'";

}
else
{
    $css_style = "style= 'display: none'";

}
?>
<input type="hidden" name="generate" id = "generate" value="<?php echo set_value('generate',$details['generate']); ?>" class="form-control">
<br>
<div id = "advocate_form" class="advocate_form" <?php echo $css_style ?>>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Date of Verification</label>
      <input type="text" name="date_of_verification" id="date_of_verification" value="<?php echo set_value('date_of_verification',date('d-m-Y')); ?>" class="form-control myDatepicker">
  </div>

 <table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
    <thead>
       <tr colspan = "2">Civil Proceedings</tr>
    </thead>  
     <tbody>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Jurisdiction</td></tr>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Civil Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><input type="text" name="civil_civil_proceed" id="civil_civil_proceed" class="form-control" placeholder="Input text ......."></td></tr>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">High Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><input type="text" name="civil_high_proceed" id="civil_high_proceed" class="form-control" placeholder="Input text ......."></td></tr>
     </tbody>
 </table>
<br>

  <table style="font-family: arial, sans-serif;border-collapse: collapse;width: 100%;">
    <thead>
       <tr colspan = "2">Criminal Proceedings</tr>
    </thead>  
     <tbody>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Jurisdiction</td></tr>
        <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Magistrate Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><input type="text" name="criminal_magistrate_proceed" id="criminal_magistrate_proceed" class="form-control" placeholder="Input text ......."></td></tr>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">Sessions Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><input type="text" name="criminal_sessions_proceed" id="criminal_sessions_proceed" class="form-control" placeholder="Input text ......."></td></tr>
       <tr><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;">High Court</td><td style="border: 1px solid #dddddd;text-align: left;padding: 8px;"><input type="text" name="criminal_high_proceed" id="criminal_high_proceed" class="form-control" placeholder="Input text ......."></td></tr>
     </tbody>
 </table>
</div>   

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
   

  <?php 
      $zipcode = preg_match("/\b\d{6}\b/",$details['street_address'], $matches);
  ?>

  //  $('#initiation_date').html("<?php echo convert_db_to_display_date($details['iniated_date']); ?>");
  
    <?php if($details['clientid'] == 43){ ?>
    $('#reference_number').html("<?php echo $details['ClientRefNumber'];  ?>");
    <?php } else { ?>
    $('#reference_number').html("<?php echo $details['court_com_ref'];  ?>");
    <?php } ?>

    $('#subject_name').html("<?php echo ucwords($details['CandidateName']);  ?>");
    $('#date_of_birth').html("<?php echo convert_db_to_display_date($details['DateofBirth']); ?>");
    $('#father_name').html("<?php echo ucwords($details['NameofCandidateFather']); ?>");
    <?php if( $zipcode == 1){ ?>
    $('#provided_address').html(`<?php echo ucwords($details['street_address']); ?>`);
    <?php } else { ?>
     $('#provided_address').html(`<?php echo ucwords($details['street_address']).' '.ucwords($details['city']).' '.ucwords($details['state']).' '.$details['pincode']; ?>`);
    <?php } ?>

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

      $('.undo_file').on('click',function(){
      var confirmr = confirm("Are you sure,you want to undo this file?");
      if (confirmr == true) 
      {
          var undo_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo VENDOR_SITE_URL.'users/undo_uploaded_file/' ?>"+undo_id,
              type: 'GET',
              beforeSend:function(){
                show_alert('deleting...','info');
              },
              complete:function(){
                $('#'+undo_id).remove();                
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

<script type="text/javascript">

  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,
     
  });
  </script>
  <script type="text/javascript">
    $('#civil_civil_proceed').keyup(function () {
      var civil_civil_proceed =   $('#civil_civil_proceed').val();

      $('#civil_civil_proceed_tbl').html(civil_civil_proceed);
    });

    $('#civil_high_proceed').keyup(function () {
      var civil_high_proceed =   $('#civil_high_proceed').val();

      $('#civil_high_proceed_tbl').html(civil_high_proceed);
    });

    $('#criminal_magistrate_proceed').keyup(function () {
      var criminal_magistrate_proceed =   $('#criminal_magistrate_proceed').val();

      $('#criminal_magistrate_proceed_tbl').html(criminal_magistrate_proceed);
    });

    $('#criminal_sessions_proceed').keyup(function () {
      var criminal_sessions_proceed =   $('#criminal_sessions_proceed').val();

      $('#criminal_sessions_proceed_tbl').html(criminal_sessions_proceed);
    });
   
    $('#criminal_high_proceed').keyup(function () {
      var criminal_high_proceed =   $('#criminal_high_proceed').val();

      $('#criminal_high_proceed_tbl').html(criminal_high_proceed);
    });
    
    $('#status').on('change',function(){
       var status = $('#status').val(); 
      
       if(status == "clear" && <?php echo $details['generate'] ?> == 1 )
       {
           document.getElementById("advocate_form").style.display = "block";
       }
       else
       {
           document.getElementById("advocate_form").style.display = "none";
       }
     
    });
  

 $("#date_of_verification").change(function(){
    var date_of_verification = $("#date_of_verification").val();
    $('#initiation_date').html(date_of_verification);
}); 

 $('#civil_civil_proceed').on('change keydown paste', function(e) {
  if (this.value.length = 1) {}
    var $this_val = $(this).val();
    this_val = $this_val.toLowerCase().replace(/\b[a-z]/g, function(char) {
        return char.toUpperCase();
    });
    $(this).val(this_val);
});

  $('#civil_high_proceed').on('change keydown paste', function(e) {
  if (this.value.length = 1) {}
    var $this_val = $(this).val();
    this_val = $this_val.toLowerCase().replace(/\b[a-z]/g, function(char) {
        return char.toUpperCase();
    });
    $(this).val(this_val);
});
  

    $('#criminal_magistrate_proceed').on('change keydown paste', function(e) {
  if (this.value.length = 1) {}
    var $this_val = $(this).val();
    this_val = $this_val.toLowerCase().replace(/\b[a-z]/g, function(char) {
        return char.toUpperCase();
    });
    $(this).val(this_val);
});
  
    $('#criminal_sessions_proceed').on('change keydown paste', function(e) {
  if (this.value.length = 1) {}
    var $this_val = $(this).val();
    this_val = $this_val.toLowerCase().replace(/\b[a-z]/g, function(char) {
        return char.toUpperCase();
    });
    $(this).val(this_val);
});
  
    $('#criminal_high_proceed').on('change keydown paste', function(e) {
  if (this.value.length = 1) {}
    var $this_val = $(this).val();
    this_val = $this_val.toLowerCase().replace(/\b[a-z]/g, function(char) {
        return char.toUpperCase();
    });
    $(this).val(this_val);
});
  
  </script>