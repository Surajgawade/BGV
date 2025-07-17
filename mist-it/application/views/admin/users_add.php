<style type="text/css">
  .bg_type_color {
    background-color: #00000033;
  }
</style>

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Add User</h4>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
              <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>users">User</a></li>
              <li class="breadcrumb-item active">User Add</li>
            </ol>

            <div class="state-information d-none d-sm-block">
              <ol class="breadcrumb">
                <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL ?>users"><i class="fa fa-arrow-left"></i> Back</button></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <?php echo form_open_multipart('#', array('name' => 'save_users', 'id' => 'save_users')); ?>
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h5 class="box-title">User Login & Role</h5>
                </div>

                <div class="row">
                  <div class="col-sm-4 form-group">
                    <label>User Name <span class="error"> *</span></label>
                    <input type="text" name="user_name" id="user_name" value="" class="form-control">
                    <?php echo form_error('user_name'); ?>
                  </div>

                  <div class="col-sm-8 form-group">
                    <label>Primary Email <span class="error"> *</span></label>
                    <div class="col-sm-12 input-group">
                      <input type="text" name="email" id="email" value="" class="form-control input-sm">
                      <span class="input-group-btn" style="width: 0px;"></span>
                      <input type="text" name="email_constant" id="email_constant" value="@<?php echo SHORTWEBSITE ?>" class="form-control input-sm bg_type_color" readonly>
                      <?php echo form_error('email'); ?>
                    </div>
                  </div>
                  <!-- <div class="col-xs-3 col-md-3 col-sm-12">
                  <label>&nbsp;</label>
                 
                </div> -->

                 
                </div>
                <div class="row">
                  <div class="col-sm-3 form-group">
                    <label>Title</label>
                    <?php
                    echo form_dropdown('title', $title, set_value('title'), 'class="form-control select2" id="title"');
                    echo form_error('title');
                    ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>First Name <span class="error"> *</span></label>
                    <input type="text" name="firstname" id="firstname" value="" class="form-control">
                    <?php echo form_error('firstname'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Last Name <span class="error"> *</span></label>
                    <input type="text" name="lastname" id="lastname" value="" class="form-control">
                    <?php echo form_error('lastname'); ?>
                  </div>

                  <div class="col-sm-3 form-group">
                    <label>Mobile Phone</label>
                    <input type="text" name="mobile_phone" id="mobile_phone" maxlength="15" value="" class="form-control">
                    <?php echo form_error('mobile_phone'); ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3 form-group">
                    <label>Password<span class="error"> *</span></label>
                    <input type="password" name="password" id="password" value="" class="form-control">
                    <?php echo form_error('password'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Confirm Password<span class="error"> *</span></label>
                    <input type="password" name="crmpassword" id="crmpassword" value="" class="form-control">
                    <?php echo form_error('crmpassword'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Select Role<span class="error"> *</span></label>
                    <select name="tbl_roles_id" id="tbl_roles_id" class="select2"></select>
                    <?php echo form_error('tbl_roles_id'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Reporting Manager<span class="error"> *</span></label>
                    <?php
                    echo form_dropdown("reporting_manager", $clientmgr, set_value('clientmgr'), 'class="select2" id="reporting_manager"');
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
                    <input type="text" name="designation" id="designation" value="" class="form-control">
                    <?php echo form_error('designation'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Department<span class="error"> *</span></label>
                    <?php
                    echo form_dropdown('department', array('' => 'Select', 'operations' => 'Operations', 'audit' => 'Audit', 'client services' => 'Client Services', 'sales' => 'Sales', 'quality' => 'Quality'), set_value('department'), 'class="select2" id="department"');
                    echo form_error('department');
                    ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Joining Date</label>
                    <input type="text" name="joining_date" id="joining_date" value="" class="form-control myDatepicker">
                    <?php echo form_error('joining_date'); ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Relieving Date</label>
                    <input type="text" name="relieving_date" id="relieving_date" value="" class="form-control myDatepicker">
                    <?php echo form_error('relieving_date'); ?>
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
                    echo form_dropdown('state', $states, set_value('state'), 'class="select2" id="state"');
                    echo form_error('state');
                    ?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <label>Office Phone</label>
                    <input type="text" name="office_phone" id="office_phone" maxlength="15" value="" class="form-control">
                    <?php echo form_error('office_phone'); ?>
                  </div>
                  <div class="col-sm-6 form-group">
                    <label>User Image </label>
                    <input type="file" name="user_image" id="user_image" class="form-control">
                    <label class="error">Note : Existing attachments(images/files) will be replaced.</label>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-3">
                    <label>IP Address Allow</label>
                    <input type="text" name="ip_address" id="ip_address" class="form-control">
                    <?php echo form_error('ip_address'); ?>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-sm-12">
                  <button type="submit" id="btn_add_user" name="btn_add_user" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
        <div class="test"></div>
      </div>
    </div>
  </div>
</div>
<script>
  $('#email_constant').attr("style", "pointer-events: none;");
  $(document).ready(function() {

    $('#save_users').validate({
      rules: {
        user_name: {
          required: true,
          minlength: 5,
          alpha_number_dot: true,
          remote: {
            url: "<?php echo ADMIN_SITE_URL . 'users/check_username' ?>",
            type: "post",
            data: {
              username: function() {
                return $("#user_name").val();
              }
            }
          }
        },
        designation: {
          required: true
        },
        firstname: {
          required: true,
          lettersonly: true
        },
        lastname: {
          required: true,
          lettersonly: true
        },
        password: {
          required: true,
          minlength: 7,
        },
        crmpassword: {
          required: true,
          equalTo: "#password"
        },
        tbl_groups_id: {
          required: true,
          greaterThan: 0
        },
        tbl_roles_id: {
          required: true,
          greaterThan: 0
        },
        email: {
          required: true
        },
        reporting_manager: {
          required: true,
          greaterThan: 0
        },
        mobile_phone: {
          minlength: 10,
          maxlength: 15
        },
        office_phone: {
          digits: true,
          minlength: 6,
          maxlength: 15
        },
        department: {
          required: true,
        },
        city: {
          lettersonly: true
        },
        pincode: {
          digits: true,
          minlength: 6,
          maxlength: 6
        },
        user_image: {
          extension: 'jpeg|jpg|png'
        }
      },
      messages: {
        designation: {
          required: "Enter Designation"
        },
        user_name: {
          required: "Enter Username",
          minlength: "User name content min 5 charecter",
          remote: "{0} username exist, please try another"
        },
        email: {
          required: "Enter Email ID"
        },
        firstname: {
          required: "Enter First Name"
        },
        lastname: {
          required: "Enter Last Name"
        },
        password: {
          required: "Enter Password",
          minlength: "Password content min 7 charecter long",
        },
        department: {
          required: "Please select Department"
        },
        crmpassword: {
          required: "Enter Comfirm Password",
          equalTo: "Comfirm password same as Password"
        },
        tbl_groups_id: {
          required: "Select Role",
          greaterThan: "Select Role"
        },
        tbl_roles_id: {
          required: "Select Role",
          greaterThan: "Select Role"
        },
        reporting_manager: {
          required: "Select Reporting Manager",
          greaterThan: "Select Reporting Manager"
        }
      },
      submitHandler: function(form) {
        $.ajax({
          url: '<?php echo ADMIN_SITE_URL . 'users/save_users'; ?>',
          data: new FormData(form),
          type: 'post',
          contentType: false,
          cache: false,
          processData: false,
          dataType: 'json',
          beforeSend: function() {
            $('#btn_add_user').attr('disabled', true);
          },
          complete: function() {
            // $('#btn_add_user').attr('disabled',false);
          },
          success: function(jdata) {
            var message = jdata.message || '';
            (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error, 'error'): '';
            if (jdata.status == '<?php echo SUCCESS_CODE; ?>') {
              show_alert(message, 'success');
              window.location = jdata.redirect;
              return;
            }
            if (jdata.status == <?php echo ERROR_CODE; ?>) {
              show_alert(message, 'error');
            }
          }
        });
      }
    });

    $.ajax({
      url: '<?php echo ADMIN_SITE_URL . 'roles/roles_list'; ?>',
      data: '',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        //$('#tbl_roles_id').attr('disabled','disabled');
      },
      complete: function() {
        //$('#tbl_roles_id').removeAttr('disabled');                
      },
      success: function(jdata) {
        $.each(jdata.message, function(key, value) {
          $('#tbl_roles_id').append($("<option></option>").attr("value", key).text(value));
        });
      }
    });
  });
</script>
<script type="text/javascript">
  $(".select2").select2();

  $(".select2-limiting").select2({
    maximumSelectionLength: 2
  });
</script>