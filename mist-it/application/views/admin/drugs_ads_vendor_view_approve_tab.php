<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h4>Candidate Deatils</h4>
</div>
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="drugs_id" id="drugs_id" value="<?php echo set_value('drugs_id',$details['id']); ?>">
<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['drug_com_ref']); ?>">


<input type="hidden" name="entity_id" id="entity_id1" value="<?php echo set_value('entity_id',$details['entity_id']); ?>">
<input type="hidden" name="package_id" id="package_id1" value="<?php echo set_value('package_id',$details['package_id']); ?>">
<input type="hidden" name="clientid" id="clientid1" value="<?php echo set_value('clientid',$details['clientid']); ?>">
<input type="hidden" name="drugs_result_id" id="drugs_result_id1" value="<?php echo set_value('drugs_result_id',$details['drugs_result_id']); ?>">

<input type="hidden" name="view_vendor_master_log_id" id="view_vendor_master_log_id" value="<?php echo set_value('view_vendor_master_log_id',$details['view_vendor_master_log_id']); ?>">

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
  <input type="text" name="drugs_com_ref" id="drugs_com_ref" value="<?php echo set_value('drugs_com_ref',$details['drug_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
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
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <label>Documents</label>
    <ol>
    <?php 
    foreach ($attachments_file as $key => $value) {
    $url  = SITE_URL.DRUGS.$details['clientid'].'/';
   
    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."></a>"; ?></li> <?php
      
    }
    ?>
    </ol>
</div>       

<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>  Others </h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>

 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','closed'=>'Closed');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="form-control" id="status"');
        echo form_error('status');
      ?>
    </div>

</div>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .tiff" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control">
        <?php echo form_error('attchments'); ?>
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
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
 <label>Vendor attachments</label>
  <ol>

 <?php 
    foreach ($attachments as $key => $value) {
     
    $folder_name = "vendor_file";
    $url  = SITE_URL.DRUGS.$folder_name.'/';  
    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."></a>"; ?></li> <?php
      
    }
    ?>
</ol>
</div> 

<div class="clearfix"></div>

<script>$('.cls_readonly').prop('disabled',true);</script>
<script type="text/javascript">

  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });
  </script>


<script type="text/javascript">
  

 $(".status").change(function() {    
   var status = $(".status option:selected").text();
 
   if(status == 'Insufficiency' || status == 'WIP')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');
   }
   if(status == 'Closed')
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
   if(f_status == 'Closed')
   {

     $('.fin_status').val(f_status);
     $('.showbutton1').show();
     $('.showbutton').hide();
 
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