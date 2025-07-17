
<hr style="border-top: 2px solid #bb4c4c;">
<div class="box-header">
  <div style="float: right;">
    <button class="btn btn-default btn-sm  edit_btn_click" data-frm_name='frm_identity_update' data-editUrl="<?= ($this->permission['access_edit_de']) ? encrypt($selected_data['id']) : ''?>"><i class="fa fa-edit"></i> Edit</button>
    
    <button class="btn btn-default btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'cd/delete/'.encrypt($selected_data['id']); ?>"><i class="fa fa-trash"></i> Delete</button>
  </div>
</div>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_identity_update','id'=>'frm_identity_update')); ?>
    <div class="box-body">
      <div class="box-header">
        <h3 class="box-title">Case Details</h3>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label >Select Client <span class="error"> *</span></label>
        <?php
          echo form_dropdown('clientid', $clients, set_value('clientid',$selected_data['clientid']), 'class="form-control" id="clientid"');
          echo form_error('clientid');
        ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label >Select Entiy<span class="error"> *</span></label>
        <?php
          echo form_dropdown('entity', array(), set_value('entity',$selected_data['entity']), 'class="form-control" id="entity"');
          echo form_error('entity');
        ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label >Package<span class="error"> *</span></label>
         <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
        <?php echo form_error('package');?>
      </div>
      <div class="clearfix"></div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label><?php echo REFNO; ?> <span class="error"> *</span></label>
        <input type="text" name="CGRefNumber" id="CGRefNumber" readonly="readonly" value="<?php echo set_value('CGRefNumber',$selected_data['cg_ref_no']); ?>" class="form-control">
        <?php echo form_error('CGRefNumber'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Client Ref Number <span class="error"> *</span></label>
        <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$selected_data['ClientRefNumber']); ?>" class="form-control">
        <?php echo form_error('ClientRefNumber'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Case Received Date<span class="error"> *</span></label>
        <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($selected_data['iniated_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('iniated_date'); ?>
      </div>
      <div class="clearfix"></div>
      
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Company/Custome Name <span class="error"> *</span></label>
        <input type="text" name="company_customer_name" maxlength="60" id="company_customer_name" value="<?php echo set_value('company_customer_name',$selected_data['company_customer_name']); ?>" class="form-control">
        <?php echo form_error('company_customer_name'); ?>
      </div>
      
       <?php   
       if($this->user_info['bill_date_permission'] == "yes")
        {
        ?>
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <?php 
        }else
        { ?>
        <div class="col-md-4 col-sm-12 col-xs-4 form-group" style="display: none;">
      <?php 
       } 
      ?>
        <label>Billed Date</label>
        <input type="text" name="bill_date" id="bill_date" value="<?php echo set_value('bill_date',convert_db_to_display_date($selected_data['bill_date'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('bill_date'); ?>
      </div>

      <div class="clearfix"></div>
      <hr style="border-top: 2px solid #bb4c4c;">
      <div class="box-header">
        <h3 class="box-title">Address Details</h3>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Street Address</label>
        <textarea name="street_address" rows="1" id="street_address" maxlength="250" class="form-control"><?php echo set_value('street_address',$selected_data['street_address']); ?></textarea>
      </div> 
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>City</label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$selected_data['city']); ?>" class="form-control">
        <?php echo form_error('city'); ?>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>PIN Code</label>
        <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode',$selected_data['pincode']); ?>" class="form-control">
        <?php echo form_error('pincode'); ?>
      </div>
      <div class="clearfix"></div>

      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$selected_data['state']), 'class="form-control" id="state"');
          echo form_error('state');
        ?>
      </div>
       <div class="clearfix"></div>
        <hr style="border-top: 2px solid #bb4c4c;">

        <div class="box-header">
          <h3 class="box-title">Attachments & other</h3>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Data Entry</label>
          <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
          <?php echo form_error('attchments'); ?>
        </div>
        
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Executive Name<span class="error">*</span></label>
          <?php
            echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$selected_data['has_case_id']), 'class="form-control cls_disabled" id="has_case_id"');
            echo form_error('has_case_id');
          ?>
        </div> 
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label >Remarks</label>
          <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks',$selected_data['remarks']); ?></textarea>
        </div>
        <div class="clearfix"></div>
        <div class="box-body">
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
            <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
            <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
          </div>
        </div>
    </div>
  <?php echo form_close(); ?>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/13/'.$selected_data['id'] ?>/identity" <?=$check_insuff_raise?> data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);
  $('.cls_disabled').prop('disabled', true);

  $('#frm_identity_update').validate({ 
    rules: {
      id : {
        required : true
      },
      has_case_id : {
      required : true,
      greaterThan: 0
      },
      clientid : {
        required : true,
        greaterThan: 0
      },
      doc_submited : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      identity_com_ref : {
        required : true
      },
      street_address : {
        required : true
      },
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      city : {
        required : true,
        lettersonly : true
      }
    },
    messages: {
      has_case_id : {
      required : "Select Executive Name",
      greaterThan : "Select Executive Name"
      },
      clientid : {
        required : "Select Client"
      },
      candsid : {
        required : "Select Candidate"
      },
      doc_submited : {
        required : "Select Doc Submitted"
      },
      identity_com_ref : {
        required : "Enter Component Verification Number"
      },
      street_address : {
        required : "Enter Address"
      },     
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'cg/update_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_identity_edit').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#btn_identity_edit').attr('disabled',false);
          jQuery('.body_loading').hide();
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
});
</script>