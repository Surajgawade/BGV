<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit User</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>users">User</a></li>
                  <li class="breadcrumb-item active">User Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= VENDOR_SITE_URL?>users"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
          <div class="card-body">
            <?php echo form_open('#', array('name'=>'save_users','id'=>'save_users')); ?>
              <div class="box box-primary">
 
              <input type="hidden" name="hidden_uid" value="<?php echo set_value('hidden_uid',$user_details['id'])?>">
              
              <div class="row">   
                <div class="col-sm-4 form-group">
                  <label>Mobile Phone</label>
                  <input type="text" name="mobile_no" id="mobile_no" minlength ="10" maxlength="10" value="<?php echo set_value('mobile_no',$user_details['mobile_no'])?>" class="form-control">
                  <?php echo form_error('mobile_no'); ?>
                </div>

                 <div class="col-sm-6 form-group">
                  <label>Email ID<span class="error"> *</span></label>
                  <input type="text" name="email" id="email" value="<?php echo set_value('email',$user_details['email_id'])?>" class="form-control">
                  <?php echo form_error('email'); ?>
                </div>                
              </div>
              <div class="row">    
                <div class="col-sm-6 form-group">
                  <label>First Name <span class="error"> *</span></label>
                  <input type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname',$user_details['first_name'])?>" class="form-control">
                  <?php echo form_error('firstname'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Last Name <span class="error"> *</span></label>
                  <input type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname',$user_details['last_name'])?>" class="form-control">
                  <?php echo form_error('lastname'); ?>
                </div>
              </div>
              <div class="row">
              
                  
                <div class="col-sm-4 form-group">
                  <label>Password</label>
                  <input type="password" name="password" id="password" value="<?php echo set_value('password')?>" class="form-control">
                  <?php echo form_error('password'); ?>
                </div>


                <div class="col-sm-4 form-group">
                  <label>Confirm Password</label>
                  <input type="password" name="crmpassword" id="crmpassword" value="<?php echo set_value('crmpassword')?>" class="form-control">
                  <?php echo form_error('crmpassword'); ?>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6 form-group">
                  <label>Address</label>
                  <input type="text" name="address" id="address" value="<?php echo set_value('address',$user_details['address'])?>" class="form-control">
                  <?php echo form_error('address'); ?>
                </div>

                <div class="col-sm-3 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city',$user_details['city'])?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Pin Code</label>
                  <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode',$user_details['pincode'])?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state',$user_details['state']), 'class="custom-select" id="state"');
                    echo form_error('state');
                  ?>
                </div>
               
                <div class="col-sm-6 form-group">
                  <label>User Image </label>
                  <input type="file" name="user_image" id="user_image" class="form-control">
                  <label class="error">Note : Existing attachments(images/files) will be replaced.</label>
                </div>
              </div>
              <?php 
                if(isset($user_details['profile_pic']) && !empty($user_details['profile_pic']))
                  {   ?>


                 <img src="<?php echo SITE_URL.VENDOR_PROFILE_PIC_PATH.$user_details['profile_pic']; ?>">

                <?php   }  ?>


                <div class="clearfix"></div>

               <br>
               
                <div class="col-sm-6 form-group">
                  <button type="submit" id="btn_update_user" name="btn_update_user" class="btn btn-primary">Submit</button>
                </div>
                 <div class="clearfix"></div>
              <?php echo form_close(); ?>
     
        </div>
      </div>
     </div>
   </div>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){

  $('#save_users').validate({
    rules : { 
      mobile_phone : {
        required : true,
        digits : true,
        minlength : 10,
        maxlength : 10
      },
      
      firstname : {
        required : true,
        lettersonly : true
      },
      lastname : {
        required : true,
        lettersonly : true
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
      mobile_no : {
        required : "Enter Mobile No",
        digits : "Enter Digit Only",
        minlength : "Mobile No min 10 Number",
        minlength : "Mobile No max 10 Number"
      },
     
      firstname : {
        required : "Enter First Name"
      },
      lastname : {
        required : "Enter Last Name"
      }
    },
    submitHandler: function(form) 
    {


      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'users/update_users'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update_user').attr('disabled',true);
        },
        complete:function(){
          $('#btn_update_user').attr('disabled',false);
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