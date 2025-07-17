<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo CRMNAME; ?> Client</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo CRMNAME; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />
	<base href="<?=CANDIDATE_SITE_URL?>" target="_blank">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" id="candidate_login" method="post">
					<span class="login100-form-title p-b-26">
				 <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="50" width="125" alt="logo">
                  </span>
					<span class="login100-form-title p-b-26">
					<?php echo CRMNAME; ?> - Candidate Login
					</span>
					<div class="wrap-input100 validate-input venuser_name" data-validate = "Enter Username">
						<input class="input100" type="text" name="venuser_name" id="venuser_name">
						<span class="focus-input100" data-placeholder="Email ID"></span>
					</div>

					<div class="wrap-input100 validate-input venpassword" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="venpassword" id="venpassword">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button id="login" class="login100-form-btn" value="Login">Login</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							Forgot Password?
						</span>

						<a class="txt1" target="_self" href="<?php echo CANDIDATE_SITE_URL.'Candidate_login/forgot'; ?>">
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

		$('#candidate_login').validate({ 
		    rules: {
		        venuser_name : {
		          required : true,
		          email : true
		        },
		        venpassword : {
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
					url:"login",
					type:'post',
					data:$('#candidate_login').serialize(),
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
		              url:"<?php echo CANDIDATE_SITE_URL.'dashboard/forgot_password'; ?>",
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