
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="add_com_ref" id="add_com_ref"  value="<?php echo set_value('add_com_ref',$details['add_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Address Type</label>
  <input type="text" name="address_type" id="address_type"  value="<?php echo set_value('address_type',$details['address_type']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact 1</label>
  <input type="text" name="contact_no" id="contact_no"  value="<?php echo set_value('contact_no',$details['CandidatesContactNumber']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact 2</label>
  <input type="text" name="contact_no2" id="contact_no2"  value="<?php echo set_value('contact_no2',$details['ContactNo1']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact 3</label>
  <input type="text" name="contact_no3" id="contact_no3"  value="<?php echo set_value('contact_no3',$details['ContactNo2']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay From</label>
  <input type="text" name="stay_from" id="stay_from"  value="<?php echo set_value('stay_form',$details['stay_from']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay To</label>
  <input type="text" name="stay_to" id="stay_to"  value="<?php echo set_value('stay_to',$details['stay_to']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Pincode</label>
  <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>State</label>
  <input type="text" name="state" id="state" value="<?php echo set_value('state',$details['state']); ?>" class="form-control cls_readonly">
</div>

</div>
<div class="row">
<div class="col-md-9 col-sm-12 col-xs-9 form-group">
  <label>Street Address</label>
   <textarea name="address" id="address" rows="2" class="form-control cls_readonly"><?php echo set_value('address',$details['address']);?></textarea>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>City </label>
  <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
</div>

</div>
 

<!--<h5>Documents</h5>
<?php  if(!empty($attachments_file)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments_file as $key => $value) {
       
        $url  = SITE_URL.ADDRESS.$details['clientid'].'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

      <?php } ?>

    </tr>
  </table>-->

<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>  Others </h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>


<div class="col-md-3 col-sm-12 col-xs-12 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>
<?php if($details['final_status'] != 'approve'){  ?>
 <div class="col-md-3 col-sm-12 col-xs-12 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','candidate shifted'=>'Candidate Shifted','unable to verify'=>'Unable to Verify','denied verification'=>'Denied Verification','resigned'=>'Resigned','candidate not responding'=>'Candidate not Responding','clear'=>'Clear');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select status cls_disabled" id="status"');
        echo form_error('status');
      ?>
</div>
<?php }else { 
 echo '<div class="col-md-4 col-sm-12 col-xs-4 form-group">';
  echo '<label>Check Status</label>';
  echo "<input type='text' name='status' id='status' value='".ucwords($details['final_status'])."' class='form-control cls_readonly'>";
  echo '</div>';
}?>

<div class="col-md-6 col-sm-12 col-xs-6 form-group">
   
    <button  class="btn btn-info btn-sm" data-url
      ="<?= VENDOR_SITE_URL.'addrver/addrver_vendor_attachment/'.$details['id']  ?>" data-toggle="modal" id="arr_vendor_attachment" type="button" >Attachment</button>
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
        $url  = SITE_URL.ADDRESS.$folder_name.'/';
        
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


  var status = $('#status').val();
  
  if(status == "wip")
  {
     $("#arr_vendor_attachment").css("display", "none");
  }
  else
  {
     $("#arr_vendor_attachment").css("display", "block");
  } 

 $(document).on('change', '#status', function(){

  var status = $(this).val();

    if(status == "wip")
    {
      $("#arr_vendor_attachment").css("display", "none");
    }
    else
    {
       $("#arr_vendor_attachment").css("display", "block");
    }
});


</script>
