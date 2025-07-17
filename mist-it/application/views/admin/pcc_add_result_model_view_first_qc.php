<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <div class="row">
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
      <input type="text" name="pcc_com_ref" id="pcc_com_ref" readonly="readonly" value="<?php echo set_value('pcc_com_ref',$details['pcc_com_ref']); ?>" class="form-control">
      <?php echo form_error('pcc_com_ref'); ?>
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
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Mode of verification</label>
      <?php
      $modeofverification = array('Verbal'=> 'Verbal','Personal visit'=>'Personal visit','Others'=>'Others');
        echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Application ID/Ref</label>
      <input type="text" name="application_id_ref" maxlength="40" id="application_id_ref" value="<?php echo set_value('application_id_ref');?>" class="form-control">
      <?php echo form_error('application_id_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Submission Date</label>
      <input type="text" name="submission_date" id="submission_date" value="<?php echo set_value('submission_date');?>" class="form-control myDatepicker">
      <?php echo form_error('submission_date'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Police Station</label>
      <input type="text" name="police_station" maxlength="100" id="police_station" value="<?php echo set_value('police_station');?>" class="form-control">
      <?php echo form_error('police_station'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Police station visit date</label>
      <input type="text" name="police_station_visit_date" id="police_station_visit_date" value="<?php echo set_value('police_station_visit_date');?>" class="form-control myDatepicker">
      <?php echo form_error('police_station_visit_date'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Name/Designation of the police personnel</label>
      <input type="text" name="name_designation_police" maxlength="40" id="name_designation_police" value="<?php echo set_value('name_designation_police');?>" class="form-control">
      <?php echo form_error('name_designation_police'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Contact number of the police personnel</label>
      <input type="text" name="contact_number_police" maxlength="14" minlength="8" id="contact_number_police" value="<?php echo set_value('contact_number_police');?>" class="form-control">
      <?php echo form_error('contact_number_police'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date</label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate');?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remarks</label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" maxlength="500"></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>
  </div>
</div>
<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
</script>