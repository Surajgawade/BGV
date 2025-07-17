<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h5 class="page-title">Create Group</h5>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>groups">Group</a></li>
                  <li class="breadcrumb-item active">Add Group</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>roles"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>

   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <?php echo form_open('#', array('name'=>'frm_save','id'=>'frm_save')); ?>
              <div class = "row">
                <div class="col-sm-6 form-group">
                  <label>Group Name <span class="error"> *</span></label>
                  <input type="text" name="group_name" id="group_name" value="<?php set_value('update_id');?>" class="form-control">
                  <?php echo form_error('group_name'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label >Description</label>
                  <textarea name="description" rows="1" id="description" class="form-control"><?php echo set_value('description'); ?></textarea>
                  <?php echo form_error('description'); ?>
                </div>
              </div> 
              <div class = "row">
                <div class="col-sm-6 form-group">
                  <label>Select Pages<span class="error"> *</span></label>
                  <br>
                  <?php
                    echo form_multiselect('admin_menu_id[]', $menus , set_value('admin_menu_id'), 'class="form-control multiSelect" id="admin_menu_id"');
                    echo form_error('admin_menu_id');
                  ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Select Manager<span class="error"> *</span></label>
                  <br>
                  <?php
                    unset($clientmgr[0]);
                    echo form_multiselect("reporting_manager[]", $clientmgr, set_value('clientmgr'), 'class="form-control multiSelect" id="reporting_manager"');
                    echo form_error('reporting_manager');
                  ?>
                </div>
              </div>

                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_add_roles" name="btn_add_roles" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
  
    <div class="test"></div>
</div>
</div>
</div>

<script>
$(document).ready(function(){

  $('#frm_save').validate({ 
      rules: {
        group_name : {
          required : true
        },
        'admin_menu_id[]' : {
          required : true
        },
        'reporting_manager[]' : {
          required : true
        }
      },
      messages: {
        group_name : {
          required : "Enter Group Name"
        },
        'admin_menu_id[]' : {
          required : "Select Page Permission"
        },
        'reporting_manager[]' : {
          required : "Select Manager"
        }    
      },
      submitHandler: function(form) 
      {
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'groups/ajax_save'; ?>',
            data : $( form ).serialize(),
            type: 'post',
            dataType:'json',
            beforeSend:function(){
              $('#btn_add_roles').attr('disabled','disabled');
            },
            complete:function(){
              //$('#btn_add_roles').removeAttr('disabled');                
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
});
</script>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>