<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <input type="hidden" name="frist_cands_id" id="frist_cands_id" value="<?php echo set_value('frist_cands_id',$empt_details['cands_id']); ?>">
  <input type="hidden" name="frist_comp_url" id="frist_comp_url" value="<?php echo set_value('frist_comp_url','employment/frm_first_qc/'); ?>">
  <input type="hidden" name="frist_qc_id" id="frist_qc_id" value="<?php echo set_value('frist_qc_id',encrypt($empt_details['id'])); ?>">
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Candidate Name<span class="error"> *</span></label>
    <input type="text" name="candsid" readonly="readonly" id="candsid" value="<?php echo set_value('candsid',$empt_details['CandidateName']); ?>" class="form-control">
    <?php echo form_error('candsid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Name<span class="error"> *</span></label>
      <input type="text" name="clientname" readonly="readonly" id="candsid" value="<?php echo set_value('clientname',$empt_details['clientname']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$empt_details['entity_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$empt_details['package_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($empt_details['caserecddate'])); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$empt_details['ClientRefNumber']); ?>" class="form-control">
    </div>
    <div class="clearfix"></div>  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Component Ref No</label>
      <input type="text" name="emp_com_ref" id="emp_com_ref" readonly="readonly" value="<?php echo set_value('emp_com_ref',$empt_details['emp_com_ref']); ?>" class="form-control">
      <?php echo form_error('emp_com_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label><?php echo REFNO; ?></label>
      <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$empt_details['cmp_ref_no']); ?>" class="form-control">
      <?php echo form_error('cmp_ref_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Initiation date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($empt_details['iniated_date'])); ?>" class="form-control" placeholder='DD-MM-YYYY'>
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
      <label> Name of Company</label>
      <?php
        echo form_dropdown('info_verfstatus', $company, set_value('info_verfstatus',$empt_details['empver_nameofthecompany']), 'class="form-control" id="info_verfstatus" readonly="readonly"');
        echo form_error('info_verfstatus');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Name of Company</label>
      <?php
        echo form_dropdown('res_nameofthecompany', $company, set_value('res_nameofthecompany',$empt_details['res_nameofthecompany']), 'class="form-control" id="res_nameofthecompany" readonly="readonly"');
        echo form_error('res_nameofthecompany');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="company_action" data-val="res_nameofthecompany" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="company_action" data-val="res_nameofthecompany" value="no">No</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Deputed Company</label>
      <input type="text" name="info_deputed_company" id="info_deputed_company" readonly="readonly" value="<?php echo set_value('info_deputed_company',$empt_details['empver_deputed_company']); ?>" class="form-control">
      <?php echo form_error('info_deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Deputed Company</label>
      <input type="text" name="res_deputed_company" id="res_deputed_company" readonly="readonly" value="<?php echo set_value('res_deputed_company',$empt_details['res_deputed_company']); ?>" class="form-control">
      <?php echo form_error('res_deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="deputed_company_action" data-val="res_deputed_company" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="deputed_company_action" data-val="res_deputed_company" value="no">No</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Employment Type</label>
      <?php
       echo form_dropdown('employment_type',$this->employment_type , set_value('employment_type',$empt_details['empver_employment_type']), 'class="form-control" id="employment_type" readonly="readonly"');
        echo form_error('employment_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Employment Type</label>
      <?php
       echo form_dropdown('res_employment_type',$this->employment_type,set_value('res_employment_type',$empt_details['res_employment_type']), 'class="form-control" id="res_employment_type" readonly="readonly"');
        echo form_error('res_employment_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="employment_type_action" data-val="res_employment_type" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="employment_type_action" data-val="res_employment_type" value="no">No</label>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Employed From <span class="error"> *</span></label>
      <input type="text" name="empfrom" id="empfrom" readonly="readonly" value="<?php echo set_value('empfrom',$empt_details['empver_empfrom']);?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('empfrom'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Employed From <span class="error"> *</span> <i class="fa fa-info-circle removeDatePicker" data-val='empfrom' data-toggle="tooltip" title="Remove Calender"></i></label>
      <input type="text" name="employed_from" id="employed_from" value="<?php echo set_value('employed_from',$empt_details['employed_from']);?>" class="form-control" readonly="readonly" placeholder='DD-MM-YYYY'>
      <?php echo form_error('employed_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empfrom_action" data-val="employed_from" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empfrom_action" data-val="employed_from" value="no">No</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Employed To <span class="error"> *</span></label>
      <input type="text" name="empto" id="empto" readonly="readonly" value="<?php echo set_value('empto',$empt_details['empver_empto']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('empto'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Employed To <span class="error"> *</span> <i class="fa fa-info-circle removeDatePicker" data-val='empfrom' data-toggle="tooltip" title="Remove Calender"></i></label>
      <input type="text" name="employed_to" id="employed_to" readonly="readonly" value="<?php echo set_value('employed_to',$empt_details['employed_to']);?>" class="form-control " placeholder='DD-MM-YYYY'>
      <?php echo form_error('employed_to'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empto_action" data-val="employed_to" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empto_action" data-val="employed_to" value="no">No</label>
    </div>
    <div class="clearfix"></div>
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" readonly="readonly" value="<?php echo set_value('designation',$empt_details['empver_designation']); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation<span class="error"> *</span></label>
      <input type="text" name="emp_designation" id="emp_designation" readonly="readonly" value="<?php echo set_value('emp_designation',$empt_details['emp_designation']); ?>" class="form-control">
      <?php echo form_error('emp_designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="designation_action" data-val="emp_designation" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="designation_action" data-val="emp_designation" value="no">No</label>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employee code</label>
      <input type="text" name="empid" readonly="readonly" id="empid" value="<?php echo set_value('empid',$empt_details['empver_empid']); ?>" class="form-control">
      <?php echo form_error('empid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employee code</label>
      <input type="text" name="res_empid" readonly="readonly" id="res_empid" value="<?php echo set_value('res_empid',$empt_details['res_empid']); ?>" class="form-control">
      <?php echo form_error('res_empid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empid_action" data-val="res_empid" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empid_action" data-val="res_empid" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="empid_action" data-val="res_empid" value="not-disclosed">Not Disclosed</label>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Reporting Manager's Name</label>
      <input type="text" name="info_reporting_manager" readonly="readonly" id="info_reporting_manager" value="<?php echo set_value('info_reporting_manager'); ?>" class="form-control">
      <?php echo form_error('info_reporting_manager'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Reporting Manager's Name</label>
      <input type="text" name="reportingmanager" readonly="readonly" id="reportingmanager" value="<?php echo set_value('reportingmanager',$empt_details['reportingmanager']); ?>" class="form-control">
      <?php echo form_error('reportingmanager'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reportingmanager_action" data-val="reportingmanager" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reportingmanager_action" data-val="reportingmanager" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reportingmanager_action" data-val="reportingmanager" value="not-disclosed">Not Disclosed</label>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="reasonforleaving" rows="1" id="reasonforleaving" readonly="readonly" class="form-control"><?php echo set_value('reasonforleaving',$empt_details['empreasonforleaving']);?></textarea>
      <?php echo form_error('reasonforleaving'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="res_reasonforleaving" rows="1" id="res_reasonforleaving" readonly="readonly" class="form-control"><?php echo set_value('res_reasonforleaving',$empt_details['res_reasonforleaving']);?></textarea>
      <?php echo form_error('res_reasonforleaving'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reasonforleaving_action" data-val="res_reasonforleaving" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reasonforleaving_action" data-val="res_reasonforleaving" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="reasonforleaving_action" data-val="res_reasonforleaving" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remuneration<span class="error"> *</span></label>
      <input type="text" name="remuneration" id="remuneration" readonly="readonly" value="<?php echo set_value('remuneration',$empt_details['empver_remuneration']); ?>" class="form-control">
      <?php echo form_error('remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remuneration<span class="error"> *</span></label>
      <input type="text" name="res_remuneration" id="res_remuneration" readonly="readonly" value="<?php echo set_value('res_remuneration',$empt_details['res_remuneration']); ?>" class="form-control">
      <?php echo form_error('res_remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="remuneration_action" data-val="res_remuneration" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="remuneration_action" data-val="res_remuneration" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="remuneration_action" data-val="res_remuneration" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Integrity/Disciplinary Issues</label>
      <input type="text" name="info_integrity_disciplinary_issue" class="form-control" readonly="readonly" id="info_integrity_disciplinary_issue" value="<?php echo set_value('info_integrity_disciplinary_issue'); ?>">
      <?php echo form_error('info_integrity_disciplinary_issue'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Integrity/Disciplinary Issues</label>
      <input type="text" name="integrity_disciplinary_issue" class="form-control" readonly="readonly" id="integrity_disciplinary_issue" value="<?php echo set_value('integrity_disciplinary_issue',$empt_details['integrity_disciplinary_issue']); ?>">
      <?php echo form_error('integrity_disciplinary_issue'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="intigrity_action" data-val="integrity_disciplinary_issue" value="yes" >Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="intigrity_action" data-val="integrity_disciplinary_issue" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" readonly="readonly" name="intigrity_action" data-val="integrity_disciplinary_issue" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Exit Formalities Completed?</label>
      <?php
        $formal_option_array = array("" => "","yes" => "Yes","no" => "No","partial" => "Partial",'completed'=> 'Completed','pending' => 'Pending','na'=>"NA",'not-disclosed'=> 'Not-Disclosed');
        echo form_dropdown('info_exitformalities', $formal_option_array, set_value('info_exitformalities'), 'class="form-control " id="info_exitformalities" readonly="readonly"');
        echo form_error('info_exitformalities'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Exit Formalities Completed?</label>
      <?php
        echo form_dropdown('exitformalities', $formal_option_array, set_value('exitformalities',$empt_details['exitformalities']), 'class="form-control " id="exitformalities" readonly="readonly"');
        echo form_error('exitformalities'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="exitformalities" readonly="readonly" name="exit_action" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="exitformalities" readonly="readonly" name="exit_action" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="exitformalities" readonly="readonly" name="exit_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Eligible for Rehire?</label>
      <input type="text" name="info_eligforrehire" id="info_eligforrehire" value="" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Eligible for Rehire?</label>
      <input type="text" name="eligforrehire" id="eligforrehire" value="<?php echo set_value('res_remuneration',$empt_details['eligforrehire']); ?>" class="form-control" readonly="readonly">
      <?php echo form_error('eligforrehire'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="eligforrehire" readonly="readonly" name="eligforrehire_action" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="eligforrehire" readonly="readonly" name="eligforrehire_action" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="eligforrehire" readonly="readonly" name="eligforrehire_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>
      
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Family Owned?</label>
      <?php
      $fmlyowned = array('0' => 'Select Role','yes' => 'Yes','no' => 'No','not-disclosed'=> 'Not-Disclosed');
        echo form_dropdown('info_fmlyowned', array(), set_value('info_fmlyowned'), 'class="form-control" id="info_fmlyowned" readonly="readonly"');
        echo form_error('info_fmlyowned');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Family Owned?</label>
      <?php
        echo form_dropdown('fmlyowned', $fmlyowned, set_value('fmlyowned',$empt_details['fmlyowned']), 'class="form-control " id="fmlyowned" readonly="readonly"');
        echo form_error('fmlyowned');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="fmlyowned" readonly="readonly" name="fml_action" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="fmlyowned" readonly="readonly" name="fml_action" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="fmlyowned" readonly="readonly" name="fml_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Web check status</label>
      <input type="text" name="info_justdialwebcheck" readonly="readonly" id="info_justdialwebcheck" class="form-control" value="">
      <?php echo form_error('info_justdialwebcheck');?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Web check status</label>
      <input type="text" name="justdialwebcheck" id="justdialwebcheck" readonly="readonly" value="<?php echo set_value('res_remuneration',$empt_details['justdialwebcheck']); ?>" class="form-control" value="">
      <?php echo form_error('justdialwebcheck');?>
    </div>  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="justdialwebcheck" readonly="readonly" name="fml_action" value="yes" checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="justdialwebcheck" readonly="readonly" name="fml_action" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="justdialwebcheck" readonly="readonly" name="fml_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Registered with MCA?</label>
      <input type="text" name="info_mcaregn" id="info_mcaregn" class="form-control" value="">
      <?php  echo form_error('mcaregn'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Registered with MCA?</label>
      <input type="text" name="mcaregn" id="mcaregn" value="<?php echo set_value('res_remuneration',$empt_details['mcaregn']); ?>" class="form-control" value="" readonly="readonly">
      <?php  echo form_error('mcaregn'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="mcaregn" readonly="readonly" name="mcaregn_action" value="yes"checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="mcaregn" readonly="readonly" name="mcaregn_action" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="mcaregn" readonly="readonly" name="mcaregn_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Domain Name </label>
      <input type="text" name="info_domainname" readonly="readonly" id="info_domainname" value="<?php echo set_value('info_domainname');?>" class="form-control">
      <?php echo form_error('info_domainname'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Domain Name </label>
      <input type="text" name="domainname" id="domainname" readonly="readonly" value="<?php echo set_value('domainname',$empt_details['domainname']);?>" class="form-control">
      <?php echo form_error('domainname'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainname" readonly="readonly" name="domainname_action" value="yes"checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainname" readonly="readonly" name="domainname_action" value="no" >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainname" name="domainname_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Domain Purchased</label>
      <input type="text" name="info_domainpurch" id="info_domainpurch" value="<?php echo set_value('info_domainpurch');?>" class="form-control" readonly="readonly">
      <?php echo form_error('info_domainpurch'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Domain Purchased</label>
      <input type="text" name="domainpurch" id="domainpurch" readonly="readonly" value="<?php echo set_value('domainpurch',$empt_details['domainpurch']);?>" class="form-control" placeholder="MM-DD-YYYY">
      <?php echo form_error('domainpurch'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainpurch" readonly="readonly" name="domainpurch_action" value="yes"checked>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainpurch" readonly="readonly" name="domainpurch_action" value="no">No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" data-val="domainpurch" readonly="readonly" name="domainpurch_action" value="not-disclosed">Not Disclosed</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
        <label>Executive Name<span class="error"> *</span></label>
        <input type="text" name="executive_name" id="executive_name" value="<?php echo set_value('executive_name',$empt_details['executive_name']); ?>" class="form-control" readonly="readonly">
        <?php echo form_error('executive_name'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Mode of verification</label>
      <?php
      $modeofverification = array('Verbal'=> 'Verbal','Personal visit'=>'Personal visit','Others'=>'Others');
        echo form_dropdown('modeofverification', $modeofverification, set_value('modeofverification',$empt_details['modeofverification']), 'class="form-control" id="modeofverification" readonly="readonly"');
        echo form_error('modeofverification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks</label>
      <textarea name="remarks" id="remarks" rows="1" class="form-control add_res_remarks" readonly="readonly"><?php echo set_value('remarks',$empt_details['remarks']);?></textarea>
      <?php echo form_error('remarks'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
      <label> Verifiers Role</label>
      <?php
      $ver_role = array('0' => 'Select Role','hr' => 'HR','supervisor' => 'Supervisor','other' => 'Other');
        echo form_dropdown('verifiers_role', $ver_role, set_value('verifiers_role',$empt_details['verifiers_role']), 'class="form-control" id="verifiers_role" readonly="readonly"');
        echo form_error('verifiers_role');
      ?>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
      <label >Verifiers Name</label>
      <input type="text" name="verfname" readonly="readonly" id="verfname" value="<?php echo set_value('verfname',$empt_details['verfname']);?>" class="form-control">
      <?php echo form_error('verfname'); ?>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
      <label >Contact No</label>
      <input type="text" name="verifiers_contact_no" readonly="readonly" id="verifiers_contact_no" value="<?php echo set_value('verifiers_contact_no',$empt_details['verifiers_contact_no']);?>" maxlength="15" class="form-control">
      <?php echo form_error('verifiers_contact_no'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label >Email ID</label>
      <input type="text" name="verifiers_email_id" readonly="readonly" id="verifiers_email_id" value="<?php echo set_value('verifiers_email_id',$empt_details['verifiers_email_id']);?>" maxlength="50" class="form-control">
      <?php echo form_error('verifiers_email_id'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label >Verifiers Designation</label>
      <input type="text" name="verfdesgn" readonly="readonly" id="verfdesgn" value="<?php echo set_value('verfdesgn',$empt_details['verifiers_role']);?>" class="form-control">
      <?php echo form_error('verfdesgn'); ?>
    </div>
    
    <div class="clearfix"></div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label> Closure Date</label>
      <input type="text" name="closuredate" readonly="readonly" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($empt_details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>

    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control" readonly="readonly">
      <?php echo form_error('attchments_ver'); ?>
    </div>

    </div>
</div>
<script>$(':radio:not(:checked)').attr('disabled', true);</script>