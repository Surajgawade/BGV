<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h5 class="page-title">Edit Group</h5>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>groups">Group</a></li>
                  <li class="breadcrumb-item active">Edit Group</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>groups"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div style="float: right;">
                <button class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name="frm_update" data-editUrl="<?= ($this->permission['access_admin_group_edit']) ? encrypt($group_details['id']) : ''?>"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_group_delete']) ? ADMIN_SITE_URL.'groups/delete/'.encrypt($group_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
              </div>
           <div class="clearfix"></div>
            <div class="box-body">
              <?php echo form_open('#', array('name'=>'frm_update','id'=>'frm_update')); ?>
                <div class = "row">
                <div class="col-sm-6 form-group">
                  <label>Group Name <span class="error"> *</span></label>
                  <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$group_details['id']);?>">
                  <input type="hidden" name="adminmunSel" id="adminmunSel" value="<?php echo set_value('admin_menu_id',$group_details['tbl_admin_menu_id']) ?>">
                  <input type="hidden" name="reportManSel" id="reportManSel" value="<?php echo set_value('admin_menu_id',$group_details['reporting_manager']) ?>">
                  <input type="text" name="group_name" id="group_name" value="<?php echo set_value('group_name',$group_details['group_name']);?>" class="form-control">
                  <?php echo form_error('group_name'); ?>
                </div>
                 <div class="col-sm-6 form-group">
                  <label >Description</label>
                  <textarea name="description" rows="1" id="description" class="form-control"><?php echo set_value('description',$group_details['description']); ?></textarea>
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
                <div class="col-sm-6  form-group">
                  <button type="submit" id="btn_add_roles" name="btn_add_roles" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>
$(document).ready(function(){

  $("#frm_update :input").prop("disabled", true);

  $('.multiSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });

  var admin_menu_id = $('#adminmunSel').val();
  admin_menu_id =  admin_menu_id.split(',').map(Number)

  var reportManSel = $('#reportManSel').val();
  reportManSel =  reportManSel.split(',').map(Number)

  $('#admin_menu_id').multiselect('select',admin_menu_id);
  $('#reporting_manager').multiselect('select',reportManSel);

  $('#frm_update').validate({ 
      rules: {
        update_id : {
          required : true,
          greaterThen : 0
        },
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
        update_id :{
          required : "Update ID Missing"
        },
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
            url : '<?php echo ADMIN_SITE_URL.'groups/update_group'; ?>',
            data : $( form ).serialize(),
            type: 'post',
            dataType:'json',
            beforeSend:function(){
              $('#btn_add_roles').attr('disabled','disabled');
            },
            complete:function(){
              //$("#frm_update :input").prop("disabled", true);
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
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