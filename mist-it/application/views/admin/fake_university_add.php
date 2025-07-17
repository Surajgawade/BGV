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
            </div>
            <?php echo form_open_multipart('#', array('name'=>'frm_add','id'=>'frm_add')); ?>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label >University Name <span class="error"> *</span></label>
                <input type="text" name="u_name" id="u_name" value="<?php echo set_value('u_name');?>" class="form-control ">
                <?php echo form_error('u_name'); ?>
              </div>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label>University Website URL</label>
                <input type="text" name="u_url" id="u_url" value="<?php echo set_value('u_url'); ?>" class="form-control">
                <?php echo form_error('u_url'); ?>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                <label>Street Address</label>
                <textarea name="u_address" rows="1" id="u_address" class="form-control"><?php echo set_value('u_address'); ?></textarea>
                <?php echo form_error('u_address'); ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>State</label>
                <?php
                  echo form_dropdown('u_state', $states, set_value('u_state'), 'class="form-control singleSelect" id="u_state"');
                  echo form_error('u_state');
                ?>
              </div>
              <div class="clearfix"></div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="btn_add" id='btn_add' value="Submit" class="btn btn-success">
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
  
  $('#frm_add').validate({
    rules : { 
      u_name : {
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
        url : '<?php echo ADMIN_SITE_URL.'universities/save_fake_university'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_add').attr('disabled',true);
        },
        complete:function(){
          //$('#btn_add').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            $('#frm_add')[0].reset();
          }else {
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

});
</script>