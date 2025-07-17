<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Candidate Deatils</h5>
</div>
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">


<input type="hidden" name="crimver_id" id="pcc_id" value="<?php echo set_value('pcc_id',$details['id']); ?>">


<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['pcc_com_ref']); ?>">


<input type="hidden" name="entity_id" id="entity_id1" value="<?php echo set_value('entity_id',$details['entity_id']); ?>">
<input type="hidden" name="package_id" id="package_id1" value="<?php echo set_value('package_id',$details['package_id']); ?>">
<input type="hidden" name="clientid" id="clientid1" value="<?php echo set_value('clientid',$details['clientid']); ?>">
<input type="hidden" name="pcc_result_id" id="pcc_result_id1" value="<?php echo set_value('pcc_result_id',$details['pcc_result_id']); ?>">

<input type="hidden" name="cl_id" id="cl_id" value="<?php echo set_value('cl_id',$details['view_vendor_master_log_id']); ?>">

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
  <input type="text" name="pcc_com_ref" id="pcc_com_ref"  value="<?php echo set_value('add_com_ref',$details['pcc_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Father  Name</label>
  <input type="text" name="NameofCandidateFather"  id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$details['NameofCandidateFather']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Date of Birht</label>
  <input type="text" name="DateofBirth"  id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($details['DateofBirth'])); ?>" class="form-control cls_readonly">
</div>


<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Street Address</label>
  <input type="text" name="street_address" id="street_address"  value="<?php echo set_value('street_address',$details['street_address']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">


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
       
        $url  = SITE_URL.PCC.$details['clientid'].'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

      <?php } ?>

    </tr>
  </table>

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
      $check_status = array('wip'=>'WIP','insufficiency'=>'Insufficiency','closed'=>'Closed');
      echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select status" id="status" readonly' );
      echo form_error('status');
      ?>
 </div>
</div>
<div class="row">
 <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control cls_readonly" maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
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
        $url  = SITE_URL.PCC.$folder_name.'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

      <?php } ?>

    </tr>
  </table>

</div>

<script>$('.cls_readonly').prop('disabled',true);</script>
<script type="text/javascript">
  
 $(".status").change(function() {    
   var status = $(".status option:selected").text();
 
   if(status == 'Insufficiency' || status == 'WIP')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
   }
   if(status == 'closed' || status == 'Closed')
   {

     $('.fin_status').val(status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }
  
});

 var f_status = '<?php echo ucwords($details['final_status'])?>';

   if(f_status == 'Insufficiency' || f_status == 'Wip')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
   }
   if(f_status == 'Closed' || f_status == 'Closed')
   {

     $('.fin_status').val(f_status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }

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