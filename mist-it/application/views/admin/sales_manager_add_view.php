<div class="content-wrapper">
    <section class="content-header">
      <h1>Sales Manager</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>sales_manager"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <div class="box-body">
              <?php echo form_open(ADMIN_SITE_URL.'sales_manager/save', array('name'=>'add_new','id'=>'add_new')); ?>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>First Name<span class="error"> *</span></label>
                  <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" class="form-control">
                  <?php echo form_error('first_name'); ?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Last Name<span class="error"> *</span></label>
                  <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" class="form-control">
                  <?php echo form_error('last_name'); ?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Username <span class="error"> *</span></label>
                  <input type="text"  name="username" id="username" value="<?php echo set_value('username'); ?>" class="form-control">
                  <?php echo form_error('username'); ?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Email ID<span class="error"> *</span></label>
                  <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
                  <?php echo form_error('email_id'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Mobile No<span class="error"> *</span></label>
                 <input type="text" name="mobile_no" id="mobile_no" value="<?php echo set_value('mobile_no'); ?>" class="form-control">
                  <?php echo form_error('mobile_no'); ?>
                </div>
                
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-6 form-group">
                  <label>Address</label>
                  <input type="text" name="address" id="address" value="<?php echo set_value('address'); ?>" class="form-control">
                  <?php echo form_error('address'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Password</label>
                  <input type="password" name="password" id="password" value="<?php echo set_value('password'); ?>" class="form-control">
                  <?php echo form_error('password'); ?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label>Confirm password</label>
                  <input type="password" name="crm_password" id="crm_password" value="<?php echo set_value('crm_password'); ?>" class="form-control">
                  <?php echo form_error('crm_password'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <input type="submit" name="btn_add" id='btn_add' value="Submit" class="btn btn-success">
                  <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<script>
$(document).ready(function(){

  $('#add_new').validate({ 
    rules: {
        first_name : {
          required : true,
          lettersonly : true
        },
        last_name : {
          required : true,
          lettersonly : true
        },
        email_id : {
          required : "Enter Email ID",
          email : true
        },
        username : {
          required : true,
          minlength : 5,
          maxlength : 35,
          remote: {
            url: "is_email_id_valid",
            type: "post",
            data: { email_id: function() { return $( "#username" ).val(); } }
          }
        },
        mobile_no : {
          digits : true,
          minlength : 10,
          maxlength : 11
        },
        password : {
          required : true,
          minlength : 6
        },
        crm_password : {
          equalTo: "#password"
        }
      },
    messages: {
      id : {
        required : "Select Group"
      },
      first_name : {
        required : "Enter First Name"
      },
      last_name : {
        required : "Enter Last Name"
      },
      email_id : {
        required : "Enter Email ID",
        email : "Enter Valid Email ID"
      },
      username : {
        required : "Enter Username",
        remote : "{0} Username Exists"
      },
      mobile_no : {
        digits : "Enter Valid Mobile No."
      },
      password : {
        required : "Enter Password"
      },
      crm_password : {
        equalTo : "Comfirm password same as Password"
      }     
    },
    submitHandler: function(form) 
    {  
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'sales_manager/save'; ?>',
          data : $( form ).serialize(),
          type: 'post',
          dataType:'json',
          beforeSend:function(){
            $('#btn_add').attr('disabled','disabled');
          },
          complete:function(){
            //$('#btn_add').removeAttr('disabled');                
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