<div class="content-page">
<div class="content">
<div class="container-fluid">

     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Create HR Database</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>hr_database">HR Database</a></li>
                  <li class="breadcrumb-item active">HR Database Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>hr_database"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>


      <div class="row">
       <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
          <?php echo form_open_multipart('#', array('name'=>'save_users','id'=>'save_users')); ?>
            <div class="row">
              
              <div class="col-xs-4 col-md-4 col-sm-12">
                <label>Company Name<span class="error">*</span></label>
                <?php
                 echo form_dropdown('nameofthecompany', $company_list, set_value('nameofthecompany'), 'class="custom-select" id="nameofthecompany"');
                  echo form_error('nameofthecompany'); 
                ?>
              </div>
               <div class="col-xs-4 col-md-4 col-sm-12">
                <label>Deputed Company</label>
                <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company'); ?>" class="form-control">
                <?php echo form_error('deputed_company'); ?>
              </div>

                <div class="col-xs-4 col-md-4 col-sm-12">
                   <label>Verifier's Name</label>
                  <input type="text" name="verifiers_name" id="verifiers_name" value="<?php echo set_value('verifiers_name')?>" class="form-control">
                  <?php echo form_error('verifiers_name'); ?>
                </div>

                

                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Verifier's Email ID</label>
                  <input type="text" name="verifiers_email_id" id="verifiers_email_id" value="<?php echo set_value('verifiers_email_id')?>" class="form-control">
                  <?php echo form_error('verifiers_email_id'); ?>
                </div>

                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Verifier's Contact No</label>
                  <input type="text" name="verifiers_contact_no" id="verifiers_contact_no" value="<?php echo set_value('verifiers_contact_no')?>" class="form-control">
                  <?php echo form_error('verifiers_contact_no'); ?>
                </div>                 
                

                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Verifier's Designation</label>
                  <input type="text" name="verifiers_designation" id="verifiers_designation" value="<?php echo set_value('verifiers_designation')?>" class="form-control">
                  <?php echo form_error('verifiers_designation'); ?>
                </div>
               <div class="clearfix"></div>


                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Remark</label>
                   <textarea name="remark" id="remark" rows="2" class="form-control "></textarea>
                     <?php echo form_error('remark'); ?>
                </div>
               
              </div>
              <br>
              <div class="box-body">
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_add_hr_database" name="btn_add_hr_database" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
   </div> 
</div>
<script>
$(document).ready(function(){

  $('.singleSelect').multiselect({
      buttonWidth: '300px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200,
      onChange: function() {
          $('#deputed_company').val($("#nameofthecompany option:selected").text());
      }
  });

  $('#save_users').validate({
    rules : { 
     nameofthecompany : {
        required : true,
        greaterThan: 0
      },
      verifiers_contact_no : {
        number : true
      },
      verifiers_email_id : {
        email: true
      }
   
    },
    messages: {
      nameofthecompany : {
        required : "Enter Company Name",
        greaterThan: "Enter Company Name"
      },
      verifiers_contact_no : {
        number : "Enter Number Only"
      },
      verifiers_email_id : {
        email: "Enter Valid Email ID"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'hr_database/save_hr_database'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_add_hr_database').attr('disabled',true);
        },
        complete:function(){
          //$('#btn_add_hr_database').attr('disabled',false);
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
});
</script>