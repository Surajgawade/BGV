<div class="content-page">
<div class="content">
<div class="container-fluid">

     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">TAT Holiday Date</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>holidays">Holiday</a></li>
                  <li class="breadcrumb-item active">Holiday Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>holidays"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
    </div>

      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
          <div class="card-body">
              <?php echo form_open('#', array('name'=>'frm_holiday','id'=>'frm_holiday')); ?>
               <div class="row">  
                <div class="col-sm-6 form-group">
                  <label>Select Date<span class="error">*</span></label>
                  <input type="text" name="holiday_date" id="holiday_date" value="<?php echo set_value('holiday_date'); ?>" class="form-control">
                  <?php echo form_error('holiday_date'); ?>
                </div>

                <div class="col-sm-6 form-group">
                  <label >Remark<span class="error"> *</span></label>
                  <textarea name="remark" id="remark" class="form-control"></textarea>
                  <?php echo form_error('remark'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-6 form-group">
                  <button type="submit" id="btn_add" name="btn_add" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
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


var date_input=$('input[name="holiday_date"]'); 
var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
date_input.datepicker({
  format: 'dd-mm-yyyy',
  container: container,
  weekStart: 1,
  daysOfWeekHighlighted: "1,2,3,4,5",
  daysOfWeekDisabled: "0,6",
  todayHighlight: true,
  autoclose: true,
});

  $('#frm_holiday').validate({ 
      rules: {
        holiday_date : {
          required : true
        },
        remark :{
          required : true
        }
      },
      messages: {
        holiday_date : {
          required : "Select Date"
        },
        remark : {
          required : "Enter Date Remark"
        }     
      },
      submitHandler: function(form) 
      {
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'holidays/save'; ?>',
            data : $( form ).serialize(),
            type: 'post',
            dataType:'json',
            beforeSend:function(){
              $('#btn_add').attr('disabled','disabled');
            },
            complete:function(){
            //  $('#btn_add').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                window.location = jdata.redirect;
                return;
              }
              else{
                show_alert(message,'error'); 
              }
            }
          });           
      }
  });
});
</script>