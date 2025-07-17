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
  <input type="text" name="court_com_ref" id="court_com_ref"  value="<?php echo set_value('add_com_ref',$details['court_com_ref']); ?>" class="form-control cls_readonly">
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
      <div class="clearfix"></div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Pincode</label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_readonly">
        <?php echo form_error('pincode'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="form-control singleSelect cls_readonly" id="state"');
          echo form_error('state');
        ?>
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
      
        $url  = SITE_URL.COURT_VERIFICATION.$details['clientid'].'/';
        
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
<h5>  Others </h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
   <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$details['has_case_id']), 'class="form-control cls_readonly"  id="has_case_id"');
          echo form_error('has_case_id');
        ?>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>


 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
     $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','clear'=>'Clear','possible match'=>'Possible Match');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select" id="status"');
        echo form_error('status');
      ?>
 </div>
</div>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,pdf,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg,.pdf, .tiff" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control">
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
        $url  = SITE_URL.COURT_VERIFICATION.$folder_name.'/';
        
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
  </script>