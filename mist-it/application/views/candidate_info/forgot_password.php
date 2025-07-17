<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo CRMNAME; ?> Forgot Password</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo CRMNAME; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />

	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">

				<form class="login100-form validate-form" id="forgot_password"  name = "forgot_password" method="post">
			      <span class="login100-form-title p-b-26">
				 <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="50" width="125" alt="logo">
                  </span>
					<span class="login100-form-title p-b-26">
			         Candidate Forgot Password
					</span>
					<div class="wrap-input100 validate-input user_name" data-validate = "Enter Username">
						<input class="input100" type="text" name="user_name" id="user_name" >
						<span class="focus-input100" data-placeholder="User Name"></span>

						 
					</div>

					  <div class="wrap-input101 validate-input password" data-validate="Enter password">
						<div id='captImg' style="display: inline;">
							<?php echo $captchaImg;  
                            
                            ?>
						</div>
					    <?php	echo "<a href='javascript:void(0);' class='refreshCaptcha'><img id = 'ref_symbol' width='50px' src =".SITE_IMAGES_URL."refresh_captcha.png></a>"; ?>
					   </div>

					<div class="wrap-input100 validate-input password" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="text" name="code" id="code">
						<span class="focus-input100" data-placeholder="Enter Code"></span>
						  <?php echo form_error('code'); ?>
					</div>

					<div class="container-login100-form-btn">
						
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button id="login" class="login100-form-btn" value="Login">Submit</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							Log In
						</span>

						<a class="txt1" href="<?php echo CANDIDATE_SITE_URL.'candidate_login'; ?>">
							Click here
						</a>
					</div>
				</form>
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
		              url:"<?php echo CANDIDATE_SITE_URL.'candidate_login/forgot_password'; ?>",
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
        $.get('<?php echo CANDIDATE_SITE_URL.'login/refresh_captcha'; ?>', function(data){
            $('#captImg').html(data);
        });
    });
});
</script>
</body>
</html>