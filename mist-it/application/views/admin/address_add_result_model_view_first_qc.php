<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <div class="row">

    
      <input type="hidden" name="frist_comp_url" readonly="readonly" id="frist_comp_url" value="<?php echo 'address'; ?>" class="form-control">
      <input type="hidden" name="frist_qc_id" readonly="readonly" id="frist_qc_id" value="<?php echo set_value('frist_qc_id',$details['id']); ?>" class="form-control">
      <input type="hidden" name="frist_cands_id" readonly="readonly" id="frist_cands_id" value="<?php echo set_value('frist_cands_id',$details['candsid']); ?>" class="form-control">


    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Candidate Name<span class="error"> *</span></label>
    <input type="text" name="candsid" readonly="readonly" id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control">
    <?php echo form_error('candsid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Name<span class="error"> *</span></label>
      <input type="text" name="clientname" readonly="readonly" id="candsid" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$details['package_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($details['caserecddate'])); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control">
    </div>
    <div class="clearfix"></div>  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Component Ref No</label>
      <input type="text" name="add_com_ref" id="add_com_ref" readonly="readonly" value="<?php echo set_value('add_com_ref',$details['add_com_ref']); ?>" class="form-control">
      <?php echo form_error('add_com_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label><?php echo REFNO; ?></label>
      <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$details['cmp_ref_no']); ?>" class="form-control">
      <?php echo form_error('cmp_ref_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Initiation date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($details['iniated_date'])); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Infomation Provided</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Verify Infomation</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Action</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Address Type</label>
      <?php
        echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type',$details['address_type']), 'class="form-control" id="address_type"');
        echo form_error('address_type');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Address Type</label>
      <?php
        echo form_dropdown('res_address_type', ADDRESS_TYPE, set_value('res_address_type',$details['res_address_type']), 'class="form-control" id="res_address_type"');
        echo form_error('res_address_typ');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Address</label>
      <input type="text" name="address" id="address" value="<?php echo set_value('address',$details['address']); ?>" class="form-control">
      <?php echo form_error('address'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Address</label>
      <input type="text" name="res_address" id="res_address" value="<?php echo set_value('res_address',$details['res_address']); ?>" class="form-control">
      <?php echo form_error('res_address'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$details['stay_from']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('stay_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="res_stay_from" id="res_stay_from" value="<?php echo set_value('res_stay_from',$details['res_stay_from']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_stay_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to',$details['stay_to']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('stay_to'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="res_stay_to" id="res_stay_to" value="<?php echo set_value('res_stay_to',$details['res_stay_to']);?>" class="form-control " placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_stay_to'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Mode of verification</label>
      <?php
      $mode_of_verification = array('verbal'=> 'Verbal','online'=>'Online','written'=>'Written','latterhead' => 'Latterhead');
        echo form_dropdown('mode_of_verification', $mode_of_verification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="form-control" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Resident Status</label>
      <?php
      $resident_status = array('Verbal'=> 'Verbal','Personal visit'=>'Personal visit','Others'=>'Others');
        echo form_dropdown('resident_status', $resident_status, set_value('resident_status',$details['resident_status']), 'class="form-control" id="resident_status"');
        echo form_error('resident_status');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Landmark</label>
      <textarea name="landmark" id="landmark" rows="1" class="form-control add_res_landmark"><?php echo set_value('landmark',$details['landmark']);?></textarea>
      <?php echo form_error('landmark'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour 1</label>
      <input type="text" name="neighbour_1" id="neighbour_1" value="<?php echo set_value('neighbour_1',$details['neighbour_1']);?>" class="form-control">
      <?php echo form_error('neighbour_1'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour Details 1</label>
      <input type="text" name="neighbour_details_1" id="neighbour_details_1" value="<?php echo set_value('neighbour_details_1',$details['neighbour_details_1']);?>" class="form-control">
      <?php echo form_error('neighbour_details_1'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour 2</label>
      <input type="text" name="neighbour_2" id="neighbour_2" value="<?php echo set_value('neighbour_2',$details['neighbour_2']);?>" class="form-control">
      <?php echo form_error('neighbour_2'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour Details 2</label>
      <input type="text" name="neighbour_details_2" id="neighbour_details_2" value="<?php echo set_value('neighbour_details_2',$details['neighbour_details_2']);?>" class="form-control">
      <?php echo form_error('neighbour_details_2'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verified By</label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Addr. Proof Collected</label>
      <input type="text" name="addr_proof_collected" id="addr_proof_collected" value="<?php echo set_value('addr_proof_collected',$details['addr_proof_collected']);?>" class="form-control">
      <?php echo form_error('addr_proof_collected'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date</label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks</label>
      <textarea name="remarks" id="remarks" rows="1" class="form-control add_res_remarks"><?php echo set_value('remarks',$details['remarks']);?></textarea>
      <?php echo form_error('remarks'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
  </div>
</div>
<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  endDate: new Date()
});
</script>