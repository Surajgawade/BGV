<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo CRMNAME; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo CRMNAME; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
</head>
<body style='background: #f5f5f5;font-family: "Poppins", sans-serif;color: #5b626b;font-size: 13px;'>

	<div class="wrapper-page" style="margin: 5.5% auto;max-width: 460px;position: relative;">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">
                        <a href="<?php ADMIN_SITE_URL  ?>" class="logo logo-admin"><img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35"></a>
                    </h3>

                    <h3 class="text-center m-3">
                        Admin Login
                    </h3>
					<span class="login60-form-title p-b-26">Welcome Back<?php echo $lastloggnedin; ?></span>
                    <div class="p-3">
                        <form class="form-horizontal m-t-30 validate-form" id="admin_login" method="post">
                            <div class="form-group validate-input user_name" data-validate = "Enter Username">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Enter username">
                            </div>
							
                            <div class="form-group validate-input password" data-validate="Enter password">
                                <label for="userpassword">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                            </div>
							
							<div class="container-login-form-btn">
								<button class="btn login100-form-btn btn-primary w-md waves-effect waves-light" type="submit" id="login" value="Login">Login</button>
							</div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">

                                    <a href="<?php echo ADMIN_SITE_URL.'login/forgot'; ?>" class="text-muted mx-4"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                <!--    <a href="<?php echo ADMIN_SITE_URL.'dashboard/change_password'; ?>" class="text-muted"><i class="mdi mdi-lock"></i> Change Password</a>-->

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
	
	
	<script src="<?= SITE_JS_URL; ?>jquery.min.js"></script>
	<script src="<?= SITE_JS_URL; ?>bootstrap.min.js"></script>
	<script src="<?php echo SITE_JS_URL.'jquery.validate.min.js' ?>"></script>
  	<script src="<?php echo SITE_JS_URL.'notify.js' ?>"></script>
  	<script>
	$(document).ready(function(){

		$('#admin_login').validate({ 
		    rules: {
		        user_name : {
		          required : true
		        },
		        password : {
		          required : true
		        }
		    },
            errorPlacement: function(error, element) {
            	var name = $(element).attr("name");
            	$('.'+name).addClass('alert-validate');
            	$('#'+name).removeClass('has-val');
		    },
		    success: function(label,element) {
                var name = $(element).attr("name");
            	$('.'+name).removeClass('alert-validate');
            	$('#'+name).addClass('has-val');
            },
		    submitHandler: function(form) 
			{      
			  	$.ajax({
					url:"<?php echo ADMIN_SITE_URL.'dashboard/login'; ?>",
					type:'post',
					data:$('#admin_login').serialize(),
					dataType:'json',
					beforeSend:function(){
						$('#login').attr("disabled", true);
						$('#login').text('Logging...');
					},
					complete:function(){
						$('#login').attr("disabled", false);
						$('#login').text('Login');
					},
					success:function(jdata) {
				        if(jdata.status == '<?php echo SUCCESS_CODE; ?>') {
				          window.location = jdata.redirect;
				          return;
				        } 
				        else {
				          $('#password').val('');
				          show_alert(jdata.message,'error');
				        }
			    	}
				});       
			}
		}); 

		var reg_x_request_sent = false;

		$('#forgot_password').validate({ 
		      rules: {
		        email_id_frg : {
		          required : true,
		          email: true
		        }
		      },
		      messages: {
		        email_id_frg : {
		          required : "Enter Email ID",
		          email : "Enter Valid Email ID"
		        }   
		      },
		      submitHandler: function(form) 
		      {      
		          $.ajax({
		              url:"<?php echo ADMIN_SITE_URL.'dashboard/forgot_password'; ?>",
		              type:'post',
		              data:$('#forgot_password').serialize(),
		              dataType:'json',
		              beforeSend:function(){
		                $('#lost_pass_btn').val('Checking...');
		              },
		              complete:function(){
		                $('#lost_pass_btn').val('Submit');
		              },
		              success:function(jdata){
		                if(jdata.status == <?php echo SUCCESS_CODE; ?>)
		                {
		                   show_alert(jdata.message,'success');
		                   $('#forgot_password')[0].reset();
		                }
		                else
		                {
		                  show_alert(jdata.message,'error');
		                  $('#forgot_password')[0].reset();
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
	window.setTimeout(function() {
	$(".alert").fadeTo(500, 0).slideUp(500, function(){
	    $(this).remove(); 
	});
	}, 4000);
	</script>
</body>
</html>