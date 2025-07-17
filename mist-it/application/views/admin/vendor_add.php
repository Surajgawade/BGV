<div class="content-page">
<div class="content">
<div class="container-fluid">
    
      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Create New Vendor</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>vendors">Vendor</a></li>
                  <li class="breadcrumb-item active">Vendor Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>vendors"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
      </div>

   
      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
          <div class="card-body">
          <?php echo form_open_multipart('#', array('name'=>'save_vendor','id'=>'save_vendor','autocomplete'=>'off')); ?>
         
            <div class="box-header">
              <h5 class="box-title">Vendor Details</h5>
            </div>
            <div class = "row">
            <div class="col-sm-4 form-group">
              <label >Vendor Name <span class="error"> *</span></label>
              <input type="text" name="vendor_name" id="vendor_name" value="<?php echo set_value('vendor_name'); ?>" class="form-control">
              <?php echo form_error('vendor_name'); ?>
            </div>
            </div>
            <div class = "row">
            <div class="col-sm-4 form-group">
              <label>Street Address <span class="error"> *</span></label>
              <input type="text" name="street_address" id="street_address " value="<?php echo set_value('street_address '); ?>" class="form-control">
              <?php echo form_error('street_address'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>City <span class="error"> *</span></label>
              <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
              <?php echo form_error('city'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>State <span class="error"> *</span></label>
              <?php
                echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
                echo form_error('state');
              ?>
            </div>
            </div>
            <div class = "row">
            <div class="col-sm-4 form-group">
              <label>PIN Code <span class="error"> *</span></label>
              <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode'); ?>" class="form-control">
              <?php echo form_error('pincode'); ?>
            </div>
            <div class="col-sm-2 form-group">
              <label>Aggr Start Date</label>
              <input type="text" name="aggr_start_date" id="aggr_start_date" value="<?php echo set_value('aggr_start_date'); ?>" class="form-control myDatepicker">
              <?php echo form_error('aggr_start_date'); ?>
            </div>
            <div class="col-sm-2 form-group">
              <label>Aggr End Date</label>
              <input type="text" name="aggr_end_date" id="aggr_end_date" value="<?php echo set_value('aggr_end_date'); ?>" class="form-control myDatepickerFuture">
              <?php echo form_error('aggr_end_date'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label >Attachment (Please Upload Image)</label>
              <input type="file" name="attchament" accept=".png, .jpg, .jpeg, .pdf" id="attchament" value="<?php echo set_value('attchament');?>" class="form-control">
              <?php echo form_error('attchament'); ?>
            </div>
            </div>
            <div class = "row">
            <div class="col-sm-8 form-group">
              <label>Remark</label>
              <textarea name="vendor_remarks" rows="1" maxlength="250" id="vendor_remarks" class="form-control"><?php echo set_value('vendor_remarks'); ?></textarea>
            </div>
            <div class="col-sm-4 form-group">
              <label>Advocate Name</label>
                <input type="text" name="adv_name" id="adv_name" value="<?php echo set_value('adv_name'); ?>" class="form-control">
               <?php echo form_error('adv_name'); ?>
            </div>
          </div>
            <hr style="border-top: 2px solid #bb4c4c;">
               <div class="box-header with-border">
                <h5 class="box-title">Spoc Details</h5>
                 
              </div>
            <div>
           
          
            <div class="row">
              <div class="col-sm-3 form-group">
              <label>Spoc Name <span class="error"> *</span></label>
              <input type="text" name="sopc_name" id="sopc_name" value="<?php echo set_value('sopc_name'); ?>" class="form-control">
              <?php echo form_error('sopc_name'); ?>
            </div>
            <div class="col-sm-3 form-group">
              <label>Primary Contact <span class="error"> *</span></label>
              <input type="text" name="primary_contact" id="primary_contact" minlength="8" maxlength="12" value="<?php echo set_value('primary_contact'); ?>" class="form-control">
              <?php echo form_error('primary_contact'); ?>
            </div>
            <div class="col-sm-3 form-group">
              <label>Email Id</label>
              <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
              <?php echo form_error('email_id'); ?>
            </div>
            </div> 
            

            <div class="clearfix"></div> 
            <hr style="border-top: 2px solid #bb4c4c;">
            <div class="row">
            <div class='col-sm-2 form-group'>
              <label>Components <span class="error"> *</span></label>
            </div>
            <div class='col-sm-2 form-group'>
              <label>TAT Day's</label>
            </div>
            
            <div class='col-sm-2 form-group'>
                <label>Auto Assign</label>
            </div>
            </div>
            <?php

              foreach ($components as $key => $value)
              {

                $data = array('name'          => 'components[]',
                              'id'            => 'components',
                              'value'         => $value['component_key'],
                              
                          );
                echo "<div class='row'><div class='col-sm-2 form-group'><div class='checkbox'><label>";
                echo form_checkbox($data).$value['component_name'];
                echo "</label></div></div>";
                echo "<div class='col-sm-2 form-group'>";
                echo "<input type='number' min='0' name='vendors_components_tat[]' id='".$value['component_key']."' class='form-control'>";
                echo "</div>";

                echo "<div class='col-sm-4 form-group'>";
                if($value['component_key'] == 'addrver')
                {
                    
                  unset($states[0]);   
                  echo form_multiselect('state_address[]', $states, set_value('state_address'), 'class="custom-select multiSelect state_address" id="state_address"');
                  echo form_error('state_address');
                 
      
                }
                if($value['component_key'] == 'empver')
                {
                    
                  unset($states[0]);   
                  echo form_multiselect('state_employment[]', $employment_states, set_value('state_employment'), 'class="custom-select multiSelect state_employment" id="state_employment"');
                  echo form_error('state_employment');
                 
                }
                if($value['component_key'] == 'courtver')
                {
                  unset($court_clients[0]);  
                  echo form_multiselect('court_clients[]', $court_clients, set_value('court_clients'), 'class="custom-select multiSelect" id="court_clients"');
                  echo form_error('court_clients');

      
                }
                if($value['component_key'] == 'globdbver')
                {
                      
                  unset($global_clients[0]);    
                  echo form_multiselect('global_clients[]', $global_clients, set_value('global_clients'), 'class="custom-select multiSelect" id="global_clients"');
                  echo form_error('global_clients');
      
                }
                if($value['component_key'] == 'cbrver')
                {
                      
                    unset($credit_clients[0]);    
                    echo form_multiselect('credit_clients[]', $credit_clients, set_value('credit_clients'), 'class="custom-select multiSelect" id="credit_clients"');
                    echo form_error('credit_clients');
      
                }

                if($value['component_key'] == 'narcver')
                {
                   
                    unset($panel[0]);                            
                    echo form_multiselect('narcotis_panel[]', $panel, set_value('narcotis_panel'), 'class="select2" id="narcotis_panel"');
                    echo form_error('narcotis_panel');
      
                }
                if($value['component_key'] == 'eduver')
                {
                      
                    $verification_status = array('' => 'Select Verification Status','1' => 'Verifier','2' => 'Stamp','3' => 'Spoc'); 
                    echo "<div class='col-sm-10 form-group'>";   
                    echo form_dropdown('education_verification_status', $verification_status, set_value('education_verification_status'), 'class="custom-select" id="education_verification_status"');
                    echo form_error('education_verification_status');
                    echo "</div>";
      
                }

                if($value['component_key'] == 'crimver')
                {
                       
                    $pcc_mode = array('' => 'Select mode','verbal' => 'verbal');
                    echo form_dropdown('crimver_mov', $pcc_mode, set_value('crimver_mov'), 'class="custom-select" id="crimver_mov"');
                    echo form_error('crimver_mov');

      
                }
                
      
                echo "</div>";

                if($value['component_key'] == 'addrver')
                {
                 
                  echo '<div class="col-sm-4 form-group" >';
                  echo ' <label>City Name(seprated By Comma)</label><br><input type="text" id="address_city" name="address_city" class="form-contol" placeholder="Enter City Name">';
                   echo '</div>';
                }

                if($value['component_key'] == 'empver')
                {
                 
                  echo '<div class="col-sm-4 form-group" >';
                  echo ' <label>City Name(seprated By Comma)</label><br><input type="text" id="employment_city" name="employment_city" class="form-contol" placeholder="Enter City Name">';
                   echo '</div>';
                }
                if($value['component_key'] == 'courtver')
                {
                  echo '<div class="col-sm-4 form-group" >
                
                   <label><input type="checkbox" id="generate" name="generate" class="form-check-input" style="height: 20px;width: 20px;">&nbsp;&nbsp;Generate</label>
                </div>';
                }

                if($value['component_key'] == 'crimver')
                {
                   echo '<div class="col-sm-4 form-group" >';
                   echo ' <label>Email ID</label><br><input type="email" id="pcc_email_id" name="pcc_email_id" class="form-contol" placeholder="Enter Email ID">';
                   echo '</div>';
                }

                echo "</div>";
                
                
              }
            ?>
            <div class="clearfix"></div>
            <hr style="border-top: 2px solid #bb4c4c;">

               <div class="box-header">
                <h3 class="box-title">Vendor Portal User</h3>
                <button type="button" style="float: right;" class="btn btn-info btn-sm" id="addVendorPortalMore"><h5>&nbsp; + &nbsp;</h5></button>
              </div>
              <div class="getVendorpotalDiv">
              <div class="row">
              <input type="hidden" name="client_login_id[]" id="client_login_id" value="<?php echo set_value('client_login_id');?>" class="form-control">
              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >First Name</label>
                <input type="text" name="client_first_name[]" id="client_first_name" value="<?php echo set_value('client_first_name');?>" class="form-control">
                <?php echo form_error('client_first_name'); ?>
              </div>

                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
              <label >Mobile No</label>
              <input type="text" name="client_mobileno[]" maxlength="11" id="client_mobileno" value="<?php echo set_value('client_mobileno');?>" class="form-control">
              <?php echo form_error('client_mobileno'); ?>
            </div>

              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >Email ID</label>
                <input type="text" name="client_email_id[]" id="client_email_id" value="<?php echo set_value('client_email_id');?>" class="form-control">
                <?php echo form_error('client_email_id'); ?>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >Password</label>
                <input type="password" name="client_password[]" id="client_password" value="<?php echo set_value('client_password');?>" class="form-control">
                <?php echo form_error('client_password'); ?>
              </div>
              </div>
              </div>
              <div id='append_vendor_acc'></div>
            <div class="clearfix"></div>
            <div class="box-body">
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
                <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
              </div>
            </div>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>

$(document).ready(function(){

  $('#save_vendor').validate({
    rules : { 
      vendor_name : {
        required : true,
        minlength: 2,
        remote: {
          url: "<?php echo ADMIN_SITE_URL.'vendors/check_vendor_name' ?>",
          type: "post",
          data: { vendor_name: function() { return $( "#vendor_name" ).val(); } }
        }
      },
     // "vendor_managers[]" : {
     //   required : true
     // },
      sopc_name : {
        required : true,
        lettersonly : true
      },
      primary_contact : {
        digits : true,
        minlength : 8,
        maxlength : 12
      },
      "client_email_id[]" : {
        email: true
      },
      email_id : {
        multiemails : true
      },
      alternate_contact : {
        digits : true,
        minlength : 8,
        maxlength : 12
      },
      city : {
        lettersonly : true
      },
      pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      attchament : {
        extension : 'jpeg|jpg|png|pdf'
      },
      'components[]' : {
        required : true
      }
    },
    messages: {
      vendor_name : {
        required : "Enter Username",
        minlength : "User name content min 5 charecter",
        remote : "{0} vendor ame exist, please try another"
      },
      'components[]' : {
        required : 'Select atleast one component'
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'vendors/save_vendor'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#save').attr('disabled',true);
        },
        complete:function(){
         // $('#save').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            window.location = jdata.redirect;
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  $('#addVendorPortalMore').click(function(){
  
    $.ajax({
      url : '<?php echo ADMIN_SITE_URL.'clients/client_portal_view'?>',
      data : '',
      type: 'post',
      beforeSend:function(){
      },
      complete:function(){
      },
      success: function(html){
        $('#append_client_acc').append(html);
      }
    });
  }).trigger('click');

  $('#addSpocMore').click(function(){
    
  }).trigger('click');

  $(document).on('click', '#addSpocMore', function(){
  var as = '<div class="clearfix"></div>'+$('.getSpocDiv').html();
  $('#append_spoc').append(as)
});

   $(document).on('click', '#addVendorPortalMore', function(){
  var as = '<div class="clearfix"></div>'+$('.getVendorpotalDiv').html();
  $('#append_vendor_acc').append(as)
});
});

jQuery.validator.addMethod("multiemails", function (value, element) {
    if (this.optional(element)) {
    return true;
    }

    var emails = value.split(','),
    valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
    value = jQuery.trim(emails[i]);
    valid = valid && jQuery.validator.methods.email.call(this, value, element);
    }
    return valid;
    }, "Invalid email format: please use a comma to separate multiple email addresses.");


</script>