<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Add User</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>users">User</a></li>
                  <li class="breadcrumb-item active">User Add</li>
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
          <?php echo form_open_multipart('#', array('name'=>'save_users','id'=>'save_users')); ?>
            <div class="box box-primary">

              <div class="row">
                <div class="col-sm-4 form-group">
                  <label>Mobile Phone <span class="error"> *</span></label>
                  <input type="text" name="mobile_no" id="mobile_no" minlength ="10" maxlength="10" value="" class="form-control">
                  <?php echo form_error('mobile_no'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Email ID</label>
                  <input type="text" name="email" id="email" value="" class="form-control">
                  <?php echo form_error('email'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                  <label>First Name <span class="error"> *</span></label>
                  <input type="text" name="firstname" id="firstname" value="" class="form-control">
                  <?php echo form_error('firstname'); ?>
                </div>
                <div class="col-sm-6 form-group">
                  <label>Last Name <span class="error"> *</span></label>
                  <input type="text" name="lastname" id="lastname" value="" class="form-control">
                  <?php echo form_error('lastname'); ?>
                </div>

            </div>
            <div class="row">
                

                <div class="col-sm-4 form-group">
                  <label>Password<span class="error"> *</span></label>
                  <input type="password" name="password" id="password" value="" class="form-control">
                  <?php echo form_error('password'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Confirm Password<span class="error"> *</span></label>
                  <input type="password" name="crmpassword" id="crmpassword" value="" class="form-control">
                  <?php echo form_error('crmpassword'); ?>
                </div>
               
            </div>
            
            <div class="row">
           
                <div class="col-sm-6 form-group">
                  <label>Address</label>
                  <input type="text" name="address" id="address" value="" class="form-control">
                  <?php echo form_error('address'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-sm-3 form-group">
                  <label>Pin Code</label>
                  <input type="text" name="pincode" id="pincode" maxlength="6" value="" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
                    echo form_error('state');
                  ?>
                </div>
                
                <div class="col-sm-6 form-group">
                  <label>User Image </label>
                  <input type="file" name="user_image" id="user_image" class="form-control">
                  <label class="error">Note : Existing attachments(images/files) will be replaced.</label>
                </div>
          </div>

          </div>
              <div class="row">
                <div class="col-sm-6 form-group">
                  <button type="submit" id="btn_add_user" name="btn_add_user" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
          <?php echo form_close(); ?>
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
      mobile_no : {
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
      password : {
        required : true,
        minlength : 5
      },
      crmpassword : {
        required : true,
        equalTo: "#password"
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
      },
      password : {
        required : "Enter Password",
        minlength : "Password content min 5 charecter long",
      },
      crmpassword : {
        required : "Enter Comfirm Password",
        equalTo : "Comfirm password same as Password"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'users/save_users'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_add_user').attr('disabled',true);
        },
        complete:function(){
          $('#btn_add_user').attr('disabled',false);
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