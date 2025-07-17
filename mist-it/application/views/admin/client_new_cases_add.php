<div class="content-page">
    <div class="content">
     <div class="container-fluid">
    
     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Create New Task</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>client_new_cases">Task Manager</a></li>
                  <li class="breadcrumb-item active">Task Manager Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                   <li><button class="btn btn-secondary waves-effect btn_clicked btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>client_new_cases"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>

    <div class="row">
      <div class="col-12">
        <div class="card m-b-20">
          <div class="card-body">
        <?php echo form_open_multipart('#', array('name'=>'frm_task_manager','id'=>'frm_task_manager')); ?>
            <div class="text-white div-header">
                Case Details
            </div>
            <br>
            <div class="row">
              <div class="col-sm-4">
                <label>Client<span class="error"> *</span></label>
                <?php
                
                  echo form_dropdown('client_id', $clients, set_value('client_id'), 'class="select2" id="client_id"');
                  echo form_error('client_id');
                ?>
              </div>

              <div class="col-sm-4">
                <label>Total Cases<span class="error"> *</span></label>
                <input type="number" name="total_cases" id="total_cases" value="" class="form-control">
                <?php echo form_error('total_cases'); ?>
              </div>

              <div class="col-sm-4">
                <label>Task Type<span class="error"> *</span></label>
                <?php
                  
                  echo form_dropdown('type', array('' => 'Select','new case' => 'New Case','insuff cleared' => 'Insuff Cleared','closures' => '
                    Closures'), set_value('type'), 'class="select2" id="type"');
                  echo form_error('type');
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <label>Attachment</label>
                <input type="file" name="ekm_file[]" accept=".eml,.msg" id="ekm_file" value="" class="form-control" multiple="multiple" >
                <?php echo form_error('ekm_file'); ?>
              </div>    
             
              
              <div class="col-sm-4">
                <label>Task Assign</label>
                <?php
                  unset($assign_ids[0]);    
                  echo form_multiselect('assign_id[]', $assign_ids, set_value('assign_id'), 'class="select2" id="assign_id"');
                  echo form_error('assign_id');
                ?>
              </div>
              <div class="col-sm-4">
                <label>Remarks<span class="error"> *</span></label>
                <textarea class="form-control" name="remarks" id="remarks" rows="1" maxlength="250"></textarea>
                <?php echo form_error('remarks'); ?>
              </div> 
           </div> 
           
           <br>
           <br>
           <div class="box-body">
                  <div class="col-sm-4 form-group">
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_add_task" name="btn_add_task" class="btn btn-primary">Submit</button>
                </div>
              </div>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
</div>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>
$(document).ready(function(){

  $('#frm_task_manager').validate({
    rules : {
      client_id : {
        required : true,
        greaterThan : 0
      },
      total_cases : {
        required : true,
        digits : true,
        greaterThan : 0
      },
      type : {
        required : true,
      },
       remarks : {
        required : true,
      },
      ekm_file : {
        required : true,
        extension : '.eml|.msg'
      }
    },
    messages: {
      client_id : {
        required : "Select Client"
      },
      total_cases : {
        required : "Select No of Cases",
        greaterThan : "Select Case greater then zero"
      },
      type : {
        required : "Select Task Type"
      },
      ekm_file : {
        required : "Please Attach File",
        extension : '.eml|.msg'
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'client_new_cases/frm_save'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_add_task').attr('disabled',true);
        },
        complete:function(){
        //  $('#btn_add_user').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            setTimeout(function () {
             window.location.href= '<?php echo ADMIN_SITE_URL.'client_new_cases'; ?>';
            }, 1000);
            
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
<script type="text/javascript">
$(document).ready(function(){
    $("#ekm_file").on('change',function(){

        var fileInput = document.getElementById('ekm_file').files[0].name;   

        document.getElementById('remarks').innerText = fileInput;
  
    });
});
</script>