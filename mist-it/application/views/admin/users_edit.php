<style type="text/css">
  .bg_type_color
  {
    background-color: #00000033;
  }
</style>

<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit User</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>users">User</a></li>
                  <li class="breadcrumb-item active">User Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>users"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
      </div>

  <div class="row">
    <div class="col-12">
      <div class="card m-b-20">
       <div class="card-body">
        <div class="page-title-box">
          <h4 class="page-title">User Login & Role</h4>
            <div class="state-information">
              <ol class="breadcrumb">
                <li><button class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name='update_user' data-editUrl='<?= ($this->permission['access_admin_list_edit']) ? encrypt($user_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button></li>
                <li> <button class="btn btn-secondary waves-effect btn-sm delete" name="delete" id="<?= ($this->permission['access_admin_list_delete']) ? encrypt($user_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button></li>
              </ol>
            </div>
        </div>
      
              <?php echo form_open_multipart('#', array('name'=>'update_user','id'=>'update_user')); ?>

              <?php 
              $email = $user_details['email'];
              $parts = explode('@', $email);
              $user = $parts[0];
              $domain = "@".$parts[1];
              ?>
              <div class="row">
                 <div class="col-sm-4 form-group">
                  <label>User Name <span class="error"> *</span></label>
                  <input type="hidden" name="hidden_uid" value="<?php echo set_value('hidden_uid',$user_details['id'])?>">
                  <input type="text" readonly name="user_name" id="user_name" value="<?php echo set_value('user_name',$user_details['user_name']); ?>"  class="form-control">
                  <?php echo form_error('user_name'); ?>
                </div>
                <div class="col-sm-8 form-group">
                  
                  <label>Primary Email <span class="error"> *</span></label>
                  <div class="col-sm-12 input-group">
                  <input type="text" name="email" id="email" value="<?php echo set_value('email',$user)?>" class="form-control  input-sm">
                  <span class="input-group-btn" style="width: 0px;"></span>
                  <input type="text" name="email_constant" id="email_constant" value="<?php echo set_value('email_constant',$domain)?>" class="form-control  input-sm bg_type_color" readonly>
                  <?php echo form_error('email'); ?>
                 </div>
                </div>
                                
                </div>
                <div class="row">
                <div class="col-sm-3 form-group">
                  <label>Title</label>
                  <?php
                    echo form_dropdown('title', $title, set_value('title',$user_details['title']), 'class="select2" id="title"');
                    echo form_error('title');
                  ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>First Name <span class="error"> *</span></label>
                  <input type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname',$user_details['firstname'])?>" class="form-control">
                  <?php echo form_error('firstname'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Last Name <span class="error"> *</span></label>
                  <input type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname',$user_details['lastname'])?>" class="form-control">
                  <?php echo form_error('lastname'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Mobile Phone</label>
                  <input type="text" name="mobile_phone" id="mobile_phone" maxlength="15" value="<?php echo set_value('mobile_phone',$user_details['mobile_phone'])?>" class="form-control">
                  <?php echo form_error('mobile_phone'); ?>
                </div> 
                </div>
                <div class="row">
                <div class="col-sm-3 form-group">
                  <label>Password</label>
                  <input type="password" name="password" id="password" value="" class="form-control">
                  <?php echo form_error('password'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Confirm Password</label>
                  <input type="password" name="crmpassword" id="crmpassword" value="" class="form-control">
                  <?php echo form_error('crmpassword'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Select Role<span class="error"> *</span></label>
                  <input type="hidden" name="selected_role" id="selected_role" value="<?php echo set_value('selected_role',$user_details['tbl_roles_id'])?>">
                  <select name="tbl_roles_id" id="tbl_roles_id" class="select2"></select>
                  <?php echo form_error('tbl_roles_id'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Reporting Manager<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown("reporting_manager", $clientmgr, set_value('clientmgr',$user_details['reporting_manager']), 'class="select2" id="reporting_manager"');
                    echo form_error('reporting_manager');
                  ?>
                </div>
                </div>
            
            
              <div class="box-header with-border">
                <h5 class="box-title">More Information</h5>
              </div>
              <div class="row">
                <div class="col-sm-3 form-group">
                  <label>Designation<span class="error"> *</span></label>
                  <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$user_details['designation'])?>" class="form-control">
                  <?php echo form_error('designation'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Department</label>
                  <?php
                    echo form_dropdown('department', array('' => 'Select','operations' => 'Operations','audit' => 'Audit','client services' => 'Client Services','sales' => 'Sales','quality' => 'Quality'), set_value('department',$user_details['department']), 'class="select2" id="department"');
                    echo form_error('department');
                  ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Joining Date</label>
                  <input type="text" name="joining_date" id="joining_date" value="<?php echo set_value('joining_date', convert_db_to_display_date($user_details['joining_date'])) ?>" class="form-control myDatepicker">
                  <?php echo form_error('joining_date'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Relieving Date</label>
                  <input type="text" name="relieving_date" id="relieving_date" value="<?php echo set_value('relieving_date',convert_db_to_display_date($user_details['relieving_date']))?>" class="form-control myDatepicker">
                  <?php echo form_error('relieving_date'); ?>
                </div>             
                </div>
                <div class="row">
                <div class="col-sm-6">
                  <label>Address</label>
                  <input type="text" name="address" id="address" value="<?php echo set_value('address', $user_details['address'])?>" class="form-control">
                  <?php echo form_error('address'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city', $user_details['city'])?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Pin Code</label>
                  <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode', $user_details['pincode'])?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                </div>
                 <div class="row">
                <div class="col-sm-3 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state',$user_details['state']), 'class="custom-select" id="state"');
                    echo form_error('state');
                  ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Office Phone</label>
                  <input type="text" name="office_phone" id="office_phone" maxlength="15" value="<?php echo set_value('office_phone', $user_details['office_phone'])?>" class="form-control">
                  <?php echo form_error('office_phone'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>User Image </label>
                  <input type="file" name="user_image" id="user_image" class="form-control">
                  <label class="error">Note : Existing attachments(images/files) will be replaced.</label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3 form-group">
                  <label>Account Status</label>
                  <?php
                    echo form_dropdown('status', $this->account_status, set_value('status',$user_details['status']), 'class="select2" id="state"');
                    echo form_error('status');
                  ?>
                </div>
                 <div class="col-sm-3 form-group">
                  <label>IP Address Allow</label>
                  <input type="text" name="ip_address" id="ip_address"  value="<?php echo set_value('ip_address', $user_details['ip_address'])?>" class="form-control">
                 <?php echo form_error('ip_address'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Bill Date Permission</label>

                  <?php
                
                    $bill_date_permission = array('' => 'Select','yes' => 'Yes','no' => 'No');

                   echo form_dropdown('bill_date_permission', $bill_date_permission ,set_value('bill_date_permission',$user_details['bill_date_permission']), 'class="select2" id="bill_date_permission"');
                    echo form_error('bill_date_permission');

                    ?>
                 </div>
                
                  <div class="col-sm-3 form-group">
                  <label>Import Permission</label>
    
                     <input type="checkbox" name="import_check" id="import_check_1" value="1" <?php  if($user_details['import_permission'] == 1){ echo 'checked="checked"';}  ?> />
                     <input type="checkbox" name="import_check" id="import_check_2" value="2" <?php  if($user_details['import_permission'] == 2 || $user_details['import_permission'] == ""){ echo 'checked="checked"';}  ?> style="display: none;" />
                 </div>
              </div>
              


              <div class="box-body">
                <div class="col-md-6 col-sm-12">
                  <button update="submit" id="btn_update_user" name="btn_update_user" class="btn btn-primary">Update</button>
                </div>
              </div>
              <?php echo form_close(); ?>
            </div>
        </div>
      </div>

    <div class="test"></div>
</div>
</div>
</div>
</div>
<script>
$("#update_user :input").prop("disabled", true);
  $('#email_constant').attr("style", "pointer-events: none;");
$(document).ready(function(){

  $('#update_user').validate({
    rules : {
      designation : {
        required : true
      },
      firstname : {
        required : true,
        lettersonly : true
      },
      lastname : {
        required : true,
        lettersonly : true
      },
      password : {
        minlength : 7,
      },
      crmpassword : {
        equalTo: "#password"
      },
     email : {
        required : true,
      },
      tbl_groups_id : {
        required : true,
        greaterThan : 0
      },
      tbl_roles_id : {
        required : true,
        greaterThan: 0
      },
      reporting_manager : {
        required : true,
        greaterThan : 0
      },
      mobile_phone : {
        minlength : 10,
        maxlength : 15
      },
      office_phone : {
        digits : true,
        minlength : 6,
        maxlength : 15
      },
      city : {
        lettersonly : true
      },
      pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      user_image : {
        extension : 'jpeg|jpg|png'
      }
    },
    messages: {
      designation : {
        required : "Enter Designation",
      },
      firstname : {
        required : "Enter First Name"
      },
      lastname : {
        required : "Enter Last Name"
      },
      email : {
        required : "Enter Email ID"
      },
      password : {
        minlength : "Password content min 7 charecter long",
      },
      crmpassword : {
        equalTo : "Comfirm password same as Password"
      },
      tbl_groups_id : {
        required : "Select Role",
        greaterThan : "select Group"
      },
      tbl_roles_id : {
        required : "Select Role",
        greaterThan: "Select Role"
      },
      reporting_manager : {
        required : "Select Reporting Manager",
        greaterThan  : "Select Reporting Manager"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'users/update_users'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update_user').attr('disabled','disabled');
        },
        complete:function(){
       //   $('#btn_update_user').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            $("#update_user :input").prop("disabled", true);
             setTimeout(function(){
               window.location = "<?php echo ADMIN_SITE_URL.'users/';?>";
            },1000);
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });
   
  $.ajax({
    url : '<?php echo ADMIN_SITE_URL.'roles/roles_list'; ?>',
    data : '',
    type: 'post',
    dataType:'json',
    beforeSend:function(){
      //$('#tbl_roles_id').attr('disabled','disabled');
    },
    complete:function(){
      //$('#tbl_roles_id').removeAttr('disabled');                
    },
    success: function(jdata){
      $.each(jdata.message, function(key, value) {
         $('#tbl_roles_id').append($("<option></option>").attr("value",key).text(value));
      });
      $('#tbl_roles_id option[value='+$('#selected_role').val()+']').attr("selected", "selected");
    }
  });

});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete', function(){  
           var user_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'users/delete/';?>",  
                     method:"POST",  
                     data:{user_id:user_id},  
                     success: function(jdata){
                    var message =  jdata.message || '';
                    (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                    if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                      show_alert(message,'success');

                     setTimeout(function(){
                             window.location = "<?php echo ADMIN_SITE_URL.'users/';?>";
                       },1000);
                    }
                    if(jdata.status == <?php echo ERROR_CODE; ?>){
                      show_alert(message,'error'); 
                    }
                  }
                });  
           }  
           else  
           {  
                return false;       
           }  
      }); 

$('#import_check_1').change(function() {

        if ($(this).is(':checked')) {
            $('#import_check_2').prop('checked', false);
        }
        else {
            $('#import_check_1').prop('checked', false);
            $('#import_check_2').prop('checked', true);
        }
    });

    $('#import_check_2').change(function() {

        if ($(this).is(':checked')) {
            $('#import_check_1').prop('checked', false);
        }
        else {
            $('#import_check_2').prop('checked', false);
            $('#import_check_1').prop('checked', true);
        }
    });       
</script>
<script type="text/javascript">
    $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });
</script>