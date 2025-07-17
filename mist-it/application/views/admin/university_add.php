<div class="content-page">
<div class="content">
<div class="container-fluid">

   <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">University Details</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>universities">University</a></li>
                  <li class="breadcrumb-item active">University Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>universities"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <?php echo form_open_multipart('#', array('name'=>'frm_add','id'=>'frm_add')); ?>
             <div class="row">

              <div class="col-md-8 col-sm-4 col-xs-8 form-group">
                <label >University Name <span class="error"> *</span></label>
                <input type="text" name="universityname" id="universityname" value="<?php echo set_value('universityname');?>" class="form-control ">
                <?php echo form_error('universityname'); ?>
              </div>
             </div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="btn_add" id='btn_add' value="Submit" class="btn btn-success">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
   
</div>
</div>
</div>
  
</div>
<script>
$(document).ready(function(){
  
  $('#frm_add').validate({
    rules : { 
      universityname : {
        required : true
      }
    },
    messages: {
      universityname : {
        required : "Enter University"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'universities/add_university'; ?>',
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
         // $('#btn_add').attr('disabled',false);
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