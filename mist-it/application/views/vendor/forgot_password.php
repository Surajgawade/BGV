<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo isset($header_title) ? $header_title : CRMNAME; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />
	<meta name="description" content="<?php echo CRMNAME; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>


<body style='background: #f5f5f5;font-family: "Poppins", sans-serif;color: #5b626b;font-size: 13px;'>

	<div class="wrapper-page" style="margin: 5.5% auto;max-width: 460px;position: relative;">


       <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">
                        <a href="<?php ADMIN_SITE_URL  ?>" class="logo logo-admin"><img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35"></a>
                    </h3>

                    <h3 class="text-center m-3">
                        Vendor Format Password
                    </h3>

                    <div class="p-3">
                        
                        <form class="form-horizontal m-t-30 validate-form" id="forgot_password" method="post">
                        	
                            <div class="form-group validate-input user_name" data-validate = "Enter Username">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Enter username">
                            </div>
                           
                            <div class="form-group validate-input password" data-validate = "Enter password">
		                                <div id='captImg' style="display: inline;">
									<?php echo $captchaImg;  
		                            
		                            ?>
								</div>
							    <?php	echo "<a href='javascript:void(0);' class='refreshCaptcha'><img id = 'ref_symbol' width='50px' src =".SITE_IMAGES_URL."refresh_captcha.png></a>"; ?>
                            </div>
                       
			             	<div class="form-group validate-input code" data-validate="Enter password">
						 <label for="username">Code</label>
						<input class="form-control" type="text" name="code" id="code" placeholder="Enter Code">
						
					</div>

                            <div class="form-group row m-t-20">
                                <div class="col-6">
                                  
                                </div>
                                <div class="col-6 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit" id="login" value="Login">Submit</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="<?php echo VENDOR_SITE_URL.'vendor_login'; ?>" class="text-muted"><i class="mdi mdi-lock"></i>Login</a>
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

		var reg_x_request_sent = false;

		$('#forgot_password').validate({ 
		      rules: {
		        user_name : {
		          required : true,
		          email: true
		        },
                code : {
		          required : true
		        }
		        
		      },
		      messages: {
		        user_name : {
		          required : "Enter Email ID",
		          email : "Enter Valid Email ID"
		        },
		         code : {
		          required : "Enter Code",
		        }
		           
		      },
		      submitHandler: function(form) 
		      {      
		          $.ajax({
		              url:"<?php echo VENDOR_SITE_URL.'Vendor_login/forgot_password'; ?>",
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

	<script>
$(document).ready(function(){
    $('.refreshCaptcha').on('click', function(){
        $.get('<?php echo VENDOR_SITE_URL.'Vendor_login/refresh_captcha'; ?>', function(data){
            $('#captImg').html(data);
        });
    });
});
</script>
</body>
</html>