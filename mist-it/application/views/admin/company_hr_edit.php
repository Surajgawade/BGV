<div class="content-page">
<div class="content">
<div class="container-fluid">

   <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit HR Database</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>hr_database">HR Database</a></li>
                  <li class="breadcrumb-item active">HR Database Edit</li>
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
                <h3 class="box-title">Hr Database Edit</h3>
                <div style="float: right;">
                  <button class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name='update_hr_database' data-editUrl='<?= ($this->permission['access_admin_list_edit']) ? encrypt($hr_database_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
    
                   <button class="btn btn-secondary waves-effect btn-sm delete" name="delete" id="<?= ($this->permission['access_admin_list_delete']) ? encrypt($hr_database_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
  
                </div>
             <div class="clearfix"></div>
              <?php echo form_open_multipart('#', array('name'=>'update_hr_database','id'=>'update_hr_database')); ?>
              <div class="row">
                 <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Deputed Name</label>
                  <input type="hidden" name="hr_database_id" id="hr_database_id" value="<?php echo set_value('hr_database_id',$hr_database_details['id'])?>">
                  <input type="text"  name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company',$hr_database_details['deputed_company']); ?>"  class="form-control">
                  <?php echo form_error('deputed_company'); ?>
                </div>
                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Verifier's Name</label>
                  <input type="text" name="verifiers_name" id="verifiers_name" value="<?php echo set_value('verifiers_name',$hr_database_details['verifiers_name'])?>" class="form-control">
                  <?php echo form_error('verifiers_name'); ?>
                </div>
                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Verifier's Email ID</label>
                  <input type="text" name="verifiers_email_id" id="verifiers_email_id" value="<?php echo set_value('verifiers_email_id',$hr_database_details['verifiers_email_id'])?>" class="form-control">
                  <?php echo form_error('verifiers_email_id'); ?>
                </div>                
                <div class="clearfix"></div>
                <div class="col-xs-3 col-md-4 col-sm-12">
                  <label>Verifier's Contact No</label>
                 <input type="text" name="verifiers_contact_no" id="verifiers_contact_no" value="<?php echo set_value('verifiers_contact_no',$hr_database_details['verifiers_contact_no'])?>" class="form-control">
                  <?php echo form_error('verifiers_contact_no'); ?>
                </div>
                <div class="col-xs-3 col-md-4 col-sm-12">
                  <label>Verifier's Designation</label>
                  <input type="text" name="verifiers_designation" id="verifiers_designation" value="<?php echo set_value('verifiers_designation',$hr_database_details['verifiers_designation'])?>" class="form-control">
                  <?php echo form_error('verifiers_designation'); ?>
                </div>
                 
                 <div class="col-xs-3 col-md-4 col-sm-12">
                  <label>Remark</label>
                   <textarea name="remark" id="remark" rows="2" class="form-control "><?php echo set_value('remark',$hr_database_details['remark']);?></textarea>
                     <?php echo form_error('remark'); ?>
                </div>
         
              </div>
              <br>


              <div class="box-body">
                <div class="col-md-6 col-sm-12">
                  <button update="submit" id="btn_update_hr_databse" name="btn_update_hr_databse" class="btn btn-primary">Update</button>
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
$("#update_hr_database :input").prop("disabled", true);

$(document).ready(function(){

  $('#update_hr_database').validate({
    rules : {
      hr_database_id : {
        required : true
      },
      verifiers_contact_no : {
        number : true
      },
      verifiers_email_id : {
        email: true
        }
    },
    messages: {
      hr_database_id : {
        required : "Enter HR Database ID"
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
        url : '<?php echo ADMIN_SITE_URL.'hr_database/update_hr_database'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update_hr_databse').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_update_hr_databse').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            setTimeout(function(){
                  window.location = "<?php echo ADMIN_SITE_URL.'/hr_database';?>";
            },1000);
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
