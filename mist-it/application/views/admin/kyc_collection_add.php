<div class="content-wrapper">
    <section class="content-header">
      <h1>Create KYC</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>KYC_collection"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <?php echo form_open('#', array('name'=>'frm_kyc_add','id'=>'frm_kyc_add')); ?>
              <div class="box-body">
                <div class="box-header">
                  <h3 class="box-title">Case Details</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="form-control" id="clientid"');
                    echo form_error('clientid');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Entiy<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('entity', array(), set_value('entity'), 'class="form-control" id="entity"');
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
                  <label><?php echo REFNO ?> <span class="error"> *</span></label>
                  <input type="text" name="KYCRefNumber" id="KYCRefNumber" readonly="readonly" value="<?php echo set_value('KYCRefNumber',$KYCRefNumber); ?>" class="form-control">
                  <?php echo form_error('KYCRefNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Client Ref Number <span class="error"> *</span></label>
                  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber'); ?>" class="form-control">
                  <?php echo form_error('ClientRefNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Case Received Date<span class="error"> *</span></label>
                  <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                    <?php echo form_error('iniated_date'); ?>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Company/Custome Name <span class="error"> *</span></label>
                  <input type="text" name="company_customer_name" maxlength="60" id="company_customer_name" value="<?php echo set_value('company_customer_name'); ?>" class="form-control">
                  <?php echo form_error('company_customer_name'); ?>
                </div>
                
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Billed Date</label>
                  <input type="text" name="bill_date" id="bill_date" value="<?php echo set_value('bill_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                    <?php echo form_error('bill_date'); ?>
                </div>

                <div class="clearfix"></div>
                <hr style="border-top: 2px solid #bb4c4c;">
                <div class="box-header">
                  <h3 class="box-title">Address Details</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address</label>
                  <textarea name="street_address" rows="1" id="street_address" maxlength="250" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                </div> 
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>PIN Code</label>
                  <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode'); ?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control" id="state"');
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
                      echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id'), 'class="form-control cls_disabled" id="has_case_id"');
                      echo form_error('has_case_id');
                    ?>
                  </div> 
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label >Remarks</label>
                    <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks'); ?></textarea>
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
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<script>
$(document).ready(function(){
  
  $('#frm_kyc_add').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      entity : {
        required : true,
        greaterThan : 0
      },
      package : {
        required : true,
        greaterThan : 0
      },
      KYCRefNumber : {
        required : true
      }, 
      ClientRefNumber : {
        required : true,
        remote: {
          url: "<?php echo ADMIN_SITE_URL.'KYC_collection/is_client_ref_exists' ?>",
          type: "post",
          data: { username: function() { return $( "#ClientRefNumber" ).val(); } }
        }
      },
      iniated_date : {
        required : true,
        validDateFormat : true
      },
      company_customer_name : {
        required : true,
        maxlength: 60
      },
      city : {
        lettersonly : true
      },
      pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      has_case_id : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      clientid : {
        required : "Enter Client Name"
      },
      entity : {
        required : "select Entiy",
        greaterThan : "select Entiy"
      },
      package : {
        required : "select Package",
        greaterThan : "select Package"
      }, 
      KYCRefNumber: {
        required : "Enter KYC Ref Number"
      },
      ClientRefNumber : {
        required : "Enter Client Ref Number",
        remote : "{0} Client Ref Number Exists"
      },
      iniated_date : {
        required : "Select Case Received Date"
      },
      company_customer_name : {
        required : "Enter Company/Custome Name",
        maxlength : 'Maximum 60 Charecter Allowed'
      },
      has_case_id : {
        required : "Select Executive Name"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'KYC_collection/save_form'; ?>',
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
              $('#save').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
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

  $('#clientid').on('change',function(){
    var clientid = $(this).val();
    if(clientid != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            success:function(jdata) {
              if(jdata.status = 200)
              {
                $('#cmp_ref_no').val(jdata.cmp_ref_no);
                $('#entity').empty();
                $.each(jdata.entity_list, function(key, value) {
                  $('#entity').append($("<option></option>").attr("value",key).text(value));
                });
              }
            }
        });
    }
  }).trigger('change');
  
});

$(document).on('change', '#entity', function(){
  var entity = $(this).val();
  var selected_clientid = '';
  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
          data:'entity='+entity+'&selected_clientid='+selected_clientid,
          beforeSend :function(){
            jQuery('#package').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#package').html(html);
          }
      });
  }
});
</script>