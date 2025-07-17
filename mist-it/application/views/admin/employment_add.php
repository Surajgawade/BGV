<div class="box box-primary">
<br>
<div class="text-white div-header">
 Employment Case Details
</div>
<br>
<div class="clearfix"></div>
<?php echo form_open_multipart('#', array('name'=>'frm_employment','id'=>'frm_employment')); ?>
  <div class="box-body">
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Name</label>
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
       <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="text" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
    </div>
    <div class="col-sm-4 form-group">
      <label>Client</label>
      <input type="text" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
    </div>
    <div class="col-sm-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$get_cands_details['entity_name']); ?>" class="form-control">
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$get_cands_details['package_name']); ?>" class="form-control">
    </div>
    <div class="col-sm-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($get_cands_details['caserecddate'])); ?>" class="form-control">
    </div>
    <div class="col-sm-4 form-group">
      <label>Client Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control">
    </div>
    </div>
   
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control">
    </div>
    <div class="col-sm-4 form-group">
      <label>Comp Int Date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>

      <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <div class="col-sm-4 form-group">
        <label>Mode of Verification</label>
         <input type="text" name="mod_of_veri" readonly="readonly" id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->empver)) { echo $mode_of_verification_value->empver; } ?>" class="form-control cls_disabled">
        <?php echo form_error('mod_of_veri'); ?>
      </div>
    </div>

    <div class="text-white div-header">
       Previous Employment Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Company Name <span class="error"> *</span> </label>
      <?php
       echo form_dropdown('nameofthecompany',array(), set_value('nameofthecompany'), 'class="form-control select2" id="nameofthecompany"');
        echo form_error('nameofthecompany'); 
      ?>
    </div>
     
    <input type="hidden" name="selected_company_name" id="selected_company_name" value="" class="form-control">                     

    <div class="col-sm-4 form-group">
      <label>Deputed Company</label>
      <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company'); ?>" class="form-control">
      <?php echo form_error('deputed_company'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Previous Employee Code</label> 
      <input type="text" name="empid" id="empid" value="<?php echo set_value('empid'); ?>" class="form-control">
      <span class="error error_previous_emp_code" style="display: none">Enter Previous Code</span>
      <?php echo form_error('empid'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Employment Type</label>
      <?php
       echo form_dropdown('employment_type',$this->employment_type, set_value('employment_type'), 'class="custom-select" id="employment_type"');
        echo form_error('employment_type'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Employed From <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="text_value"> : Text   <span class="error"> *</span>
      </label>
      <input type="date" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom'); ?>" class="form-control">
      <?php echo form_error('empfrom'); ?>
    </div>                  
    <div class="col-sm-4 form-group">
      <label>Employed To  <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="text_value"> : Text   <span class="error"> *</span></label>
      <input type="date" name="empto" id="empto" value="<?php echo set_value('empto'); ?>" class="form-control" >
      <?php echo form_error('empto'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Designation  <span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Remuneration</label>
      <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration'); ?>" class="form-control">
      <?php echo form_error('remuneration'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label for="reasonforleaving">Reason for Leaving </label>
      <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control"><?php echo set_value('reasonforleaving');?></textarea>
      <?php echo form_error('reasonforleaving'); ?>
    </div>
    </div>
  <div class="row">
    <div class="col-sm-4 form-group">
      <label>UAN No</label>
      <input type="text" name="uan_no" id="uan_no" value="<?php echo set_value('uan_no'); ?>" class="form-control">
      <?php echo form_error('uan_no'); ?>
    </div> 
  </div>   
    <div class="text-white div-header">
       Company contact Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-3 form-group">
      <label>Name of HR</label>
      <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name'); ?>" class="form-control">
      <?php echo form_error('compant_contact_name'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>HR's Designation</label>
      <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation'); ?>" class="form-control">
      <?php echo form_error('compant_contact_designation'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>HR's Email ID</label>
      <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email'); ?>" class="form-control">
      <?php echo form_error('compant_contact_email'); ?>
    </div>
    
     <div class="col-sm-3 form-group">
      <label>HR's Contact No.</label>
      <input type="text" name="compant_contact"  minlength="6" maxlength="13" id="compant_contact" value="<?php echo set_value('compant_contact'); ?>" class="form-control">
      <?php echo form_error('compant_contact'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-3 form-group">
      <label>Street Address</label>
      <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control"><?php echo set_value('locationaddr'); ?></textarea>
      <?php echo form_error('locationaddr'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>City/Branch(location)</label>
      <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality'); ?>" class="form-control">
      <span class="error error_city" style="display: none">Enter City/Branch(location)</span>
      <?php echo form_error('citylocality'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    </div>
    

    <div class="text-white div-header">
       Reporting Manager Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-3 form-group">
      <label>Manager Name</label>
      <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name'); ?>" class="form-control">
      <?php echo form_error('r_manager_name'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Manager's contact</label>
      <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no'); ?>" class="form-control">
      <?php echo form_error('r_manager_no'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Designation</label>
      <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation'); ?>" class="form-control">
      <?php echo form_error('r_manager_designation'); ?>
    </div>
    <div class="col-sm-3 form-group">
      <label>Manager's Email ID</label>
      <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email'); ?>" class="form-control">
      <?php echo form_error('r_manager_email'); ?>
    </div>
    </div>

    <div class="text-white div-header">
      Supervisor Details
    </div>
    <br>
    <input type="hidden" name="hdn_counter" id="hdn_counter" value="1">
    <div id="supervisor_details"><div class="add_supervisor_details_row"></div></div>

  <!--<div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor Name </label>
    <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("supervisor_name[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_name'); ?>
  </div>
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor's contact </label>
    <input type="text" name="supervisor_contact_details[]" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_contact_details'); ?>
  </div>
  <div class="col-sm-2 form-group">
    <label>Designation </label>
    <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" class="form-control">
  </div>
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>Supervisor's Email ID </label>
    <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_id[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_email_id'); ?>
  </div>
   
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor Name </label>
    <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("supervisor_name[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_name'); ?>
  </div>
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor's contact </label>
    <input type="text" name="supervisor_contact_details[]" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_contact_details'); ?>
  </div>
  <div class="col-sm-2 form-group">
    <label>Designation </label>
    <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" class="form-control">
  </div>
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>Supervisor's Email ID </label>
    <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_id[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_email_id'); ?>
  </div>
   
   <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor Name </label>
    <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("supervisor_name[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_name'); ?>
  </div>
  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
    <label>Supervisor's contact </label>
    <input type="text" name="supervisor_contact_details[]" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_contact_details'); ?>
  </div>
  <div class="col-sm-2 form-group">
    <label>Designation </label>
    <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" class="form-control">
  </div>
  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
    <label>Supervisor's Email ID </label>
    <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_id[]"); ?>" class="form-control">
    <?php echo form_error('supervisor_email_id'); ?>
  </div>
  
 <div class="clearfix"></div>-->
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Others</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Experience/Relieving Letter</label>
      <input type="file" name="attchments_reliving[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <span class="error error_relieving_letter" style="display: none">Please Attach Experience/Relieving Letter</span>
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>LOA</label>
      <input type="file" name="attchments_loa[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <span class="error error_loa" style="display: none">Please Attach LOA</span>
      <?php echo form_error('attchments'); ?>
    </div>
    <!--<div class="col-sm-4 form-group">
      <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
      <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>-->
    <div class="col-sm-4 form-group">
      <label>Copy Attachment Selection<span class="error">*</span></label>
      <?php
        $copy_attachment_selection = array(''=>'Selection Attachment','0'=>'Others','3'=>'Experience/Relieving Letter','4'=>'LOA');
        echo form_dropdown('copy_attachment_selection', $copy_attachment_selection, set_value('copy_attachment_selection'), 'class="form-control" id="copy_attachment_selection"');
        echo form_error('copy_attachment_selection');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Executive Name<span class="error">*</span></label>
      <?php
        echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id'), 'class="custom-select cls_disabled" id="has_case_id"');
        echo form_error('has_case_id');
      ?>
    </div>
    </div>  
    <input type="hidden" name="upload_capture_image_employment" id="upload_capture_image_employment" value="">
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_emplyment" id="c_attachemnt" disabled>Copy Attachment</button>
    </div>
  </div>
<?php echo form_close(); ?>
</div>
<div id="addAddCompanyModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'add_company','id'=>'add_company')); ?>
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add Company</h4>
    </div>
    <div class="modal-body append_model"></div>
    <div class="modal-footer">
      <button type="submit" id="company_add" name="company_add" class="btn btn-success">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="capture_image_copy_emplyment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_employment','id'=>'frm_upload_copied_image_employment')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy_employment" name="copy_employment">
      <div class="status_employment">
        <p id="errorMsg_employment" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result_employment"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_employment" name="upload_copied_image_employment">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<script>
var counter = $('#hdn_counter').val();
$(document).ready(function(){


     $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,  
  });


  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200,
      onChange: function() {
          $('#deputed_company').val($("#nameofthecompany option:selected").text());
      }
  });

   $('#copy_attachment_selection').change(function(e){
        var copy_attachment_selection = $('#copy_attachment_selection').val(); 
        if(copy_attachment_selection == '')
        {
           $('#c_attachemnt').attr('disabled','true');
        }
        else
        {
           $('#c_attachemnt').removeAttr('disabled');
        }
    });

  $('#frm_employment').validate({ 
        rules: {
          clientid : {
            required : true,
            greaterThan: 0
          },
          candsid : {
            required : true,
            greaterThan: 0
          },
          nameofthecompany : {
            required : true,
            greaterThan: 0
          },
          employercontactno : {
            number : true
          },
          pincode : {
            number : true,
            minlength : 6,
            maxlength : 6
          },
          r_manager_no : {
            number : true,
            minlength : 9,
            maxlength : 12
          },
          r_manager_email : {
            email : true
          },
          citylocality : {
            lettersonly : true
          },
          compant_contact : {
            number : true,
            minlength : 6
          },
          empfrom : {
            required : true
          },
          empto : {
            required : true
          },
          designation : {
            required : true
          },
          compant_contact_email : {
          email: true
          },
          has_case_id :{
            required : true,
            greaterThan : 0
          },
          iniated_date:{
            required : true
          }
        },
        messages: {
          clientid : {
            required : "Select Client"
          },
          candsid : {
            required : "Select Candidate"
          },
          nameofthecompany : {
            required : "Select Company Name",
            greaterThan : "Select Company Name"  
          },
          empfrom : {
            required : "Enter Employment From"
          },
          empto : {
            required : "Enter Employment To"
          },
          designation : {
            required : "Enter Designation"
          },
          has_case_id : {
            greaterThan : "Select Executive"
          },
          iniated_date:{
             required : "Enter Initiate Date"
          }
        },
        submitHandler: function(form) 
        {
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'employment/save_employment'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#save').attr('disabled','disabled');
              },
              complete:function(){
               // $('#save').attr('disabled',false);
              },
              success: function(jdata){
                var message =  jdata.message || '';
                (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                  show_alert(message,'success');
                  location.reload();
                  //window.location = jdata.redirect;
                  return;
                }
                if(jdata.status == <?php echo ERROR_CODE; ?>){
                  show_alert(message,'error'); 
                }
              }
            });       
        }
  });
  

  $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'employment/load_supervisor_details/ena/'; ?>'+counter,
        data:'',
        beforeSend :function(){
          //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
        },
        success:function(html)
        {
          jQuery('#supervisor_details').append(html);
        }
  });

});

$(document).on('click', '.add_supervisor_details_row', function(){
  counter++;
  if(counter != 0 && counter < 6)
  {
    $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/load_supervisor_details/ena/'; ?>'+counter,
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
    
  });
 });
</script>
<script type="text/javascript">
  // Select2
  $(".select2").select2();
         
    $("#nameofthecompany").select2({
      placeholder: "Select Company",
      multiple: false,
      minimumInputLength: 3,
      ajax: 
      {
          url: "<?php echo ADMIN_SITE_URL.'employment/get_company_details' ?>",
          dataType: 'json',
          data: function(term) {
              return {
                  q: term
              };
          },
          processResults: function(response) {
            var myResults = [];
              $.each(response, function (index, item) {
                    myResults.push({
                        'id': item.id,
                        'text': item.company_name
                    });
                });
              return {results: myResults};
          },
          cache: true
      },
    
  });
    

  $('#nameofthecompany').change(function(){
    
      var selected_company =  $(this).find(":selected").text();

      $('#selected_company_name').val(selected_company);
    
      if(selected_company == "Other")
      {
        var id = '<?php echo ADMIN_SITE_URL.'company_database/addAddCompanyModelEmployment'?>';
          $('.append_model').load(id,function(){
            $('#addAddCompanyModel').modal('show');
            $('#addAddCompanyModel').addClass("show");
            $('#addAddCompanyModel').css({background: "#0000004d"});
          }); 
      }

    $.ajax({  
                     
      url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
      type: "post",
      async:false,
      data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
      success: function(response){
        if(response.branch_location == '1'){  
          $('.error_city').css('display','inline-block'); 
        }else{
          $('.error_city').css('display','none');
        }
        if(response.previous_emp_code == '1'){ 
          $('.error_previous_emp_code').css('display','inline-block'); 
        }else{
          $('.error_previous_emp_code').css('display','none');
        }
        if(response.experience_letter == '1'){ 
          $('.error_relieving_letter').css('display','inline-block'); 
        }else{
          $('.error_relieving_letter').css('display','none');
        }
        if(response.loa == '1'){ 
          $('.error_loa').css('display','inline-block'); 
        }else{
          $('.error_loa').css('display','none');
        }
                     
      }
    });
  
  });  

/*  jQuery.validator.addMethod("field_value_city",function(value, element){ 
    var check_result;
      $.ajax({  
        url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
        type: "post",
        async:false,
        data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
        success: function(response){
          if(response.branch_location == '1'){
            if ($('#citylocality').val().length === 0) {
               check_result = false;
            }
            else{
              check_result = true;
            }            
          }
          else{
            check_result = true;
          }
        }
      });
      return check_result;
  }, "Insert City/Branch");

  jQuery.validator.addMethod("field_value_previous_emp_code",function(value, element){ 
    var check_result;
      $.ajax({  
        url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
        type: "post",
        async:false,
        data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
        success: function(response){
          if(response.previous_emp_code == '1'){
            if ($('#empid').val().length === 0) {
               check_result = false;
            }
            else{
              check_result = true;
            }            
          }
          else{
            check_result = true;
          }
        }
      });
      return check_result;
  }, "Insert Previous Employee Code");

   jQuery.validator.addMethod("field_value_reliving",function(value, element){ 
    var check_result;
      $.ajax({  
        url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
        type: "post",
        async:false,
        data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
        success: function(response){
          if(response.experience_letter == '1'){
            if ($('#attchments_reliving').val().length === 0) {
               check_result = false;
            }
            else{
              check_result = true;
            }            
          }
          else{
            check_result = true;
          }
        }
      });
      return check_result;
  }, "Plase Attach Experience Letter");

  jQuery.validator.addMethod("field_value_loa",function(value, element){ 
    var check_result;
      $.ajax({  
        url: "<?php echo ADMIN_SITE_URL.'employment/get_required_fields' ?>",
        type: "post",
        async:false,
        data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
        success: function(response){
          if(response.loa == '1'){
            if ($('#attchments_loa').val().length === 0) {
               check_result = false;
            }
            else{
              check_result = true;
            }            
          }
          else{
            check_result = true;
          }
        }
      });
      return check_result;
  }, "Plase Attach LOA");*/
</script>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
  
  const errorEl = document.getElementById('errorMsg_employment')

  async function askWritePermission() {
    try {
      const { state } = await navigator.permissions.query({ name: 'clipboard-write', allowWithoutGesture: false })
      return state === 'granted'
    } catch (error) {
      errorEl.textContent = `Compatibility error (ONLY CHROME > V66): ${error.message}`
      console.log(error)
      return false
    }
  }

  const canWrite = await askWritePermission()
  const setToClipboard = blob => {
    const data = [new ClipboardItem({ [blob.type]: blob })]
    console.log(data);
    console.log(navigator.clipboard);
    return navigator.clipboard.write(data)
  }
})

$('#upload_copied_image_employment').on('click',function() {
      var get_image_name =  $('#upload_capture_image_employment').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result_employment img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_employment').val(get_image_base);
          $('#capture_image_copy_emplyment').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });
</script>
<style>
.image-content-result_employment {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result_employment::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result_employment:not(:empty)::after,
.image-content-result_employment:focus::after {
  display: none;
}

.image-content-result_employment * {
  max-width: 100%;
  border: 0;
}
</style>