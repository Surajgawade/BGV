<div class="content-wrapper">
    <section class="content-header">
      <h1>University Details</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>universities/fake_university"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"></h3>
              <div style="float: right;">
                <button type="button" class="btn btn-default btn-sm edit_btn_click" data-frm_name='frm_update_univer' data-editUrl='<?= ($this->permission['access_education_fake_edit']) ? encrypt($univers_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
                <button type="button" class="btn btn-default btn-sm deleteURL" data-accessUrl="<?= ($this->permission['access_education_fake_delete']) ? ADMIN_SITE_URL.'universities/delete_fake_university/'.encrypt($univers_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
              </div>
            </div>
            <?php echo form_open_multipart('#', array('name'=>'frm_update_univer','id'=>'frm_update_univer')); ?>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label >University Name <span class="error"> *</span></label>
                <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$univers_details['id']);?>" >
                <input type="text" name="u_name" readonly="readonly" id="u_name" value="<?php echo set_value('u_name',$univers_details['u_name']);?>" class="form-control ">
                <?php echo form_error('u_name'); ?>
              </div>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label>University Website URL</label>
                <input type="text" name="u_url" id="u_url" value="<?php echo set_value('u_url',$univers_details['u_url']); ?>" class="form-control">
                <?php echo form_error('u_url'); ?>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label>Street Address</label>
                <textarea name="u_address" rows="1" id="u_address" class="form-control"><?php echo set_value('u_address',$univers_details['u_address']); ?></textarea>
                <?php echo form_error('u_address'); ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>State</label>
                <?php
                  echo form_dropdown('u_state', $states, set_value('u_state',$univers_details['u_state']), 'class="form-control singleSelect" id="u_state"');
                  echo form_error('u_state');
                ?>
              </div>
              <div class="clearfix"></div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </section>
</div>
<script>
$(document).ready(function(){
  $('input,textarea,select').prop('disabled', true);
  
  $('#frm_update_univer').validate({
    rules : { 
      u_name : {
        required : true
      },
      update_id: {
        required : true
      },
      u_state : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      u_name : {
        required : "Enter University"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'universities/update_fake_university'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update').attr('disabled',true);
        },
        complete:function(){
        //  $('#btn_update').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }else {
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

});
</script>