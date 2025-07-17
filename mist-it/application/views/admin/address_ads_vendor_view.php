<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Candidate Deatils</h5>
</div>
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="view_vendor_master_log_id" id="view_vendor_master_log_id" value="<?php echo set_value('view_vendor_master_log_id',$details['view_vendor_master_log_id']); ?>">


<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="add_com_ref" id="add_com_ref"  value="<?php echo set_value('add_com_ref',$details['add_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay From</label>
  <input type="text" name="stay_from" id="stay_from"  value="<?php echo set_value('stay_form',$details['stay_from']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay To</label>
  <input type="text" name="stay_to" id="stay_to"  value="<?php echo set_value('stay_to',$details['stay_to']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Address Type</label>
  <input type="text" name="address_type" id="address_type"  value="<?php echo set_value('address_type',$details['address_type']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Street Address</label>
  <input type="text" name="address" id="address"  value="<?php echo set_value('address',$details['address']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>City </label>
  <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
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
       
        $url  = SITE_URL.ADDRESS.$details['clientid'].'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

    <?php 
    } 
    ?>
    </tr>
  </table>
      
<div class="clearfix"></div>


<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Others</h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
  <input type="text" name="state" id="state" value="<?php echo set_value('state',$details['state']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>


 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
       $check_status = array('wip'=> 'WIP','candidate shifted'=>'Candidate Shifted','unable to verify'=>'Unable to Verify','denied verification'=>'Denied Verification','resigned'=>'Resigned','candidate not responding'=>'Candidate not Responding','clear'=>'Clear');
      echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select" status" id="status"');
      echo form_error('status');
      ?>
 </div>
</div>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
   
    <button  class="btn btn-info btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'address/address_vendor_attachment/'.$details['view_vendor_master_log_id']  ?>" data-toggle="modal" id="arr_vendor_attachment" type="button" >Attachment</button>
</div>
 
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
  </div>

  
   <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>Vendor Closure Date</label><span class="error">*</span>
    <input type="text" name="vendor_date" value="<?php echo set_value('vendor_date',convert_db_to_display_date($details['vendor_date'])); ?>" class="form-control myDatepicker">
  </div>
</div>

<h5>Vendor Attachments</h5>
  <?php  if(!empty($attachments)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
       
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
         </tr>
      <?php } ?>
    </tr>
  </table>
   

      
<div class="clearfix"></div>

<script>$('.cls_readonly').prop('disabled',true);</script>
<script type="text/javascript">

  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

 $(".status").change(function() {    
  var status = $(".status option:selected").text();
  if(status == 'candidate shifted' || status == 'unable to verify' || status == 'WIP' || status == 'Clear' || f_status == 'Denied Verification' || f_status == 'Resigned' || f_status == 'Candidate not Responding')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
  
    
   }
  /* if(status == 'Closed')
   {

     $('.fin_status').val(status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }*/
  
});

 var f_status = '<?php echo ucwords($details['final_status'])?>';
  if(status == 'candidate shifted' || status == 'unable to verify' || status == 'WIP' || status == 'Clear'  || status == 'Denied Verification' || status == 'Resigned' || status == 'Candidate not Responding')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
     
   }
   /*if(f_status == 'Closed')
   {

     $('.fin_status').val(f_status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }*/

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