
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">
<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">


<!--<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>-->


<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="identity_com_ref" id="identity_com_ref"  value="<?php echo set_value('identity_com_ref',$details['identity_com_ref']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-4 form-group">
        <label>Doc Submitted</label>
        <?php
         echo form_dropdown('doc_submited', array('' => 'Select','aadhar_card' => 'Aadhar Card',
          'pan_card' => 'PAN Card'), set_value('doc_submited',$details['doc_submited']), 'class="form-control cls_disabled" id="doc_submited"');
          echo form_error('doc_submited'); 
        ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Id Number</label>
        <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number',$details['id_number']); ?>" class="form-control cls_disabled">
        <?php echo form_error('id_number'); ?>
      </div>
      <div class="col-md-3 col-sm-12 col-xs-4 form-group">
        <label>Street Address</label>
        <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control cls_disabled"><?php echo set_value('street_address',$details['street_address']); ?></textarea>
        <?php echo form_error('street_address'); ?>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>City</label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_disabled">
        <?php echo form_error('city'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="form-control cls_disabled singleSelect" id="state"');
          echo form_error('state');
        ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Pincode</label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_disabled">
        <?php echo form_error('pincode'); ?>
      </div>
    
<div class="clearfix"></div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h4>  Others </h4>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
  <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$details['has_case_id']), 'class="form-control cls_readonly"  id="has_case_id"');
          echo form_error('has_case_id');
        ?>
</div>-->

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>

<!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>charges</label>
  <input type="text" name="Charges" id="Charges" value="<?php echo set_value('Charges',$details['costing']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Addtnl charges</label>
  <input type="text" name="additional_charges" id="additional_charges" value="<?php echo set_value('additional_charges',$details['additional_costing']); ?>" class="form-control">
</div>-->

 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','insufficiency'=>'Insufficiency','closed'=>'Closed');
        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="form-control" id="status"');
        echo form_error('status');
      ?>
    </div>

<div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment </label><span class = "error">(jpeg,jpg,png,tiff files only)</span>
     <input type="file" name="attchments_file[]" accept=".png, .jpg, .jpeg, .tiff" multiple="multiple" id="attchments_file" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
</div>
<div class="clearfix"></div>

  <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
    $folder_name = "vendor_file";
    $url  = SITE_URL.IDENTITY.$folder_name.'/'; 
    ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a> <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."></a>"; ?></li> <?php
      
    }
    ?>
</ol>

 <?php 
   if(isset($attachments[0]['file_name']))
   {
      $attachments_file  =  $attachments[0]['file_name'];
   }
   else
   {
      $attachments_file  =  '';
   } 
  ?>

  <input type="text" name="attchments" id="attchments" value="<?php echo set_value('attchments',$attachments_file); ?>" class="form-control">


<div class="clearfix"></div>

<script>$('.cls_readonly').prop('disabled',true);</script>