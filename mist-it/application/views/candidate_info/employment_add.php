<div class="box box-primary">

  <div class="box-body">    
    
     
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
      <input type="hidden" name="component_name" id="component_name" value="Employment" class="form-control" >
      <input type="hidden" name="comp_ref_no" id="comp_ref_no" value="<?php echo set_value('comp_ref_no',$details[0]['emp_com_ref']); ?>" class="form-control" >
      <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
      <input type="hidden" name="employment_id"  value="<?php echo set_value('employment_id',$id); ?>" class="form-control">

   
    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->empver)) { echo $mode_of_verification_value->empver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>

  
      <div class="box-header">
      <h3 class="box-title">Previous Employment Details</h3>
    </div>
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Company Name <span class="error"> *</span> </label>
      <input type="text" name="nameofthecompany" id="nameofthecompany" value="<?php echo set_value('nameofthecompany'); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Deputed Company</label>
      <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company'); ?>" class="form-control">
      <?php echo form_error('deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Previous Employee Code</label>
      <input type="text" name="empid" id="empid" value="<?php echo set_value('empid'); ?>" class="form-control">
      <?php echo form_error('empid'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Employment Type</label>
      <?php
       echo form_dropdown('employment_type',$this->employment_type, set_value('employment_type'), 'class="form-control" id="employment_type"');
        echo form_error('employment_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employed From <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="text_value"> : Text 
      </label>
      <input type="date" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom'); ?>" class="form-control">
      <?php echo form_error('empfrom'); ?>
    </div>                  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employed To  <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="text_value"> : Text </label>
      <input type="date" name="empto" id="empto" value="<?php echo set_value('empto'); ?>" class="form-control" >
      <?php echo form_error('empto'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation</label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remuneration</label>
      <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration'); ?>" class="form-control">
      <?php echo form_error('remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control"><?php echo set_value('reasonforleaving');?></textarea>
      <?php echo form_error('reasonforleaving'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Company contact Details</h3>
    </div>
   
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>Company Contact Name</label>
      <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name'); ?>" class="form-control">
      <?php echo form_error('compant_contact_name'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>Company Contact Designation</label>
      <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation'); ?>" class="form-control">
      <?php echo form_error('compant_contact_designation'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>Company Contact Email ID</label>
      <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email'); ?>" class="form-control">
      <?php echo form_error('compant_contact_email'); ?>
    </div>
     <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>Company Contact No.</label>
      <input type="text" name="compant_contact"  minlength="6" maxlength="13" id="compant_contact" value="<?php echo set_value('compant_contact'); ?>" class="form-control">
      <?php echo form_error('compant_contact'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address</label>
      <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control"><?php echo set_value('locationaddr'); ?></textarea>
      <?php echo form_error('locationaddr'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>City</label>
      <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality'); ?>" class="form-control">
      <?php echo form_error('citylocality'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="form-control" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Reporting Manager Details</h3>
    </div>
    
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager Name</label>
      <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name'); ?>" class="form-control">
      <?php echo form_error('r_manager_name'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's contact</label>
      <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no'); ?>" class="form-control">
      <?php echo form_error('r_manager_no'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Designation</label>
      <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation'); ?>" class="form-control">
      <?php echo form_error('r_manager_designation'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's Email ID</label>
      <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email'); ?>" class="form-control">
      <?php echo form_error('r_manager_email'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Supervisor Details</h3>
    </div>
    <input type="hidden" name="hdn_counter" id="hdn_counter" value="1">
    <div id="supervisor_details"><div class="add_supervisor_details_row"></div></div>

 
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
      <h3 class="box-title">Attachments & other</h3>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
  
    <div class="clearfix"></div>  
  </div>

    <div class="clearfix"></div>
    
  </div>
</div>

<script type="text/javascript">
  var counter = $('#hdn_counter').val();

  $.ajax({
   
        type:'POST',
        url:'<?php echo CANDIDATE_SITE_URL.'Employment/load_supervisor_details/ena/'; ?>'+counter,
        data:'',
        beforeSend :function(){
          //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
        },
        success:function(html)
        {

          jQuery('#supervisor_details').append(html);
        }
  });

  $(document).on('click', '.add_supervisor_details_row', function(){
  counter++;
  if(counter != 0 && counter < 6)
  {
    $.ajax({
          type:'POST',
          url:'<?php echo CANDIDATE_SITE_URL.'Employment/load_supervisor_details/ena/'; ?>'+counter,
          data:'',
          beforeSend :function(){
            //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#supervisor_details').append(html);
          }
    });
  }
  else
  {
    show_alert('Max 5 row can add','warning');
  }
}).trigger('click');

$(document).on('click', '.remove_supervisor_details_row', function(){
    if(counter != 1){
      $('#counter_id_'+$(this).data('id')).remove();
      counter--;
    }else{
      show_alert("Can't Remove",'warning');
    }
}).trigger('click');

$('input[name = "calender_display_employee_from"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empfrom"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empfrom"]').prop('type','text');
      }
     
  $('input[name = "calender_display_employee_to"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empto"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empto"]').prop('type','text');
      } 

      $('input').attr('autocomplete','on');
      $('.readonly').prop('readonly', true); 
    });
 });
$('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });
</script>