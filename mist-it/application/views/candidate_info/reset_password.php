<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />

<title><?php echo CRMNAME; ?> Password</title>
<!-- Bootstrap -->
<link rel="icon" href="<?= SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />

  <link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
  <link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
</head>

<body>
 

    <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">

        <form class="login100-form validate-form" id="update_password"  name = "update_password" method="post">
            <span class="login100-form-title p-b-26">
         <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="50" width="125" alt="logo">
                  </span>
          <span class="login100-form-title p-b-26">
               Client Set New Password
          </span>
          <div class="wrap-input100 validate-input user_name" data-validate = "Enter Username">
           <input type="password" name="password" id="password" class="input100"  required="" />

            <span class="focus-input100" data-placeholder="New Password"></span>   
          </div>

            

          <div class="wrap-input100 validate-input password" data-validate="Enter password">
           
              <input type="password" name="cnf_password" id="cnf_password" class="input100"  required="" />
            <span class="focus-input100" data-placeholder="Confirm Password"></span>
              <?php echo form_error('cnf_password'); ?>
          </div>

          <div class="container-login100-form-btn">
            
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
              <button id="change_pass" class="login100-form-btn" value="Change Password">Change Password</button>
            </div>
          </div>

          
        </form>
      </div>
    </div>
  </div>
 <script src="<?= SITE_JS_URL; ?>jquery.min.js"></script>
  <script src="<?= SITE_JS_URL; ?>bootstrap.min.js"></script>
  <script src="<?php echo SITE_JS_URL.'jquery.validate.min.js' ?>"></script>
    <script src="<?php echo SITE_JS_URL.'notify.js' ?>"></script>

  <script type="text/javascript">
  $(document).ready(function(){
    $('#update_password').validate({ 
          rules: {
            password : {
              required : true
            },
            cnf_password : {
              required : true,
              equalTo : "#password"
            }
          },
          messages: {
            password : {
              required : "Enter Password"
            },
            cnf_password : {
              required : "Enter Confirm Password",
              equalTo : "Password and Confirm Password Not Matched"
            }     
          },
          submitHandler: function(form) 
          {      
              $.ajax({
                  url:"<?php echo CLIENT_SITE_URL.'Client_login/set_password'; ?>",
                  type:'post',
                  data:$('#update_password').serialize(),
                  dataType:'json',
                  beforeSend:function(){
                    $('#change_pass').text('Checking...');
                  },
                  complete:function(){
                    $('#change_pass').text('Change Password');
                  },
                  success:function(jdata){
                    if(jdata.redirect)
                       {
                        window.location = jdata.redirect;
                         return;
                       }
                    else if(jdata.status == <?php echo SUCCESS_CODE; ?>)
                    {  

                       show_alert(jdata.message,'success');
                       $('#update_password')[0].reset();
                    }
                    else
                    {
                      show_alert(jdata.message,'error');
                      $('#update_password')[0].reset();
                    }
                }
            });    
          }
    });
   show_alert = function (content,alert_type){
        if(content){
            $.notify(content, alert_type);
        }
    }
    
  });
  </script>
</body>
</html>