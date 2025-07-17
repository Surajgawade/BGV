<div class="content">
  <div class="container-fluid">
   <div class="row">
     
      <ol class="breadcrumb" >
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= VENDOR_SITE_URL?>Users"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
        <div class="col-sm-12 col-md-12">
         <div class="card">
            <div class="content">
              <?php echo form_open('#', array('name'=>'save_users','id'=>'save_users')); ?>
                

                 <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>User Name <span class="error"> *</span></label>
                  <input type="text" name="user_name" id="user_name" value="" class="form-control">
                  <?php echo form_error('user_name'); ?>
                </div>

                 <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Email ID<span class="error"> *</span></label>
                  <input type="text" name="email" id="email" value="" class="form-control">
                  <?php echo form_error('email'); ?>
                </div>                
                <div class="clearfix"></div>
               
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>First Name <span class="error"> *</span></label>
                  <input type="text" name="firstname" id="firstname" value="" class="form-control">
                  <?php echo form_error('firstname'); ?>
                </div>
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Last Name <span class="error"> *</span></label>
                  <input type="text" name="lastname" id="lastname" value="" class="form-control">
                  <?php echo form_error('lastname'); ?>
                </div>
                 <div class="clearfix"></div>

                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Mobile Phone</label>
                  <input type="text" name="mobile_phone" id="mobile_phone" maxlength="11" value="" class="form-control">
                  <?php echo form_error('mobile_phone'); ?>
                </div>
                
                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Password<span class="error"> *</span></label>
                  <input type="password" name="password" id="password" value="" class="form-control">
                  <?php echo form_error('password'); ?>
                </div>
                <div class="col-xs-4 col-md-4 col-sm-12">
                  <label>Confirm Password<span class="error"> *</span></label>
                  <input type="password" name="crmpassword" id="crmpassword" value="" class="form-control">
                  <?php echo form_error('crmpassword'); ?>
                </div>
                <div class="clearfix"></div>


                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Address</label>
                  <input type="text" name="address" id="address" value="" class="form-control">
                  <?php echo form_error('address'); ?>
                </div>
                <div class="col-xs-3 col-md-3 col-sm-12">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-xs-3 col-md-3 col-sm-12">
                  <label>Pin Code</label>
                  <input type="text" name="pincode" id="pincode" maxlength="6" value="" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-3 col-md-3 col-sm-12">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control" id="state"');
                    echo form_error('state');
                  ?>
                </div>
               
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>User Image </label>
                  <input type="file" name="user_image" id="user_image" class="form-control">
                  <label class="error">Note : Existing attachments(images/files) will be replaced.</label>
                </div>
                <div class="clearfix"></div>

               
               
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_add_user" name="btn_add_user" class="btn btn-primary">Submit</button>
                </div>
                 <div class="clearfix"></div>
              <?php echo form_close(); ?>
     
        </div>
      </div>
     </div>
   </div>
</div>
<script>
$(document).ready(function(){

  $('#save_users').validate({
    rules : { 
      user_name : {
        required : true,
        minlength : 5,
        alpha_number_dot : true,
        remote: {
          url: "<?php echo VENDOR_SITE_URL.'users/check_username' ?>",
          type: "post",
          data: { username: function() { return $( "#user_name" ).val(); } }
        }
      },
      email : {
        required : true,
        email : true
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
        minlength : 7,
      },
      crmpassword : {
        required : true,
        equalTo: "#password"
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
        digits : true,
        minlength : 11,
        maxlength : 11
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
      user_name : {
        required : "Enter Username",
        minlength : "User name content min 5 charecter",
        remote : "{0} username exist, please try another"
      },
      email : {
        required : "Enter Email ID",
        email : "Enter Valid Email ID"
      },
      firstname : {
        required : "Enter First Name"
      },
      lastname : {
        required : "Enter Last Name"
      },
      password : {
        required : "Enter Password",
        minlength : "Password content min 7 charecter long",
      },
      crmpassword : {
        required : "Enter Comfirm Password",
        equalTo : "Comfirm password same as Password"
      },
      tbl_groups_id : {
        required : "Select Role",
        greaterThan : "Select Role"
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