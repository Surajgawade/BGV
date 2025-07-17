<!DOCTYPE html>
<html lang="en">
<head>
<title>
	<?php echo isset($header_title) ? ucwords(strtolower($header_title)) : CRMNAME; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo CRMNAME ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />
	<base href="<?=VENDOR_SITE_URL?>" target="_blank">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= SITE_CSS_URL?>main.css">
</head>
<body style='background: #f5f5f5;font-family: "Poppins", sans-serif;color: #5b626b;font-size: 13px;'>
	<div class="wrapper-page" style="margin: 5.5% auto;max-width: 460px;position: relative;">
       
          <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">
                        <a href="<?php VENDOR_SITE_URL  ?>" class="logo logo-admin"><img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35"></a>
                    </h3>

                    <h3 class="text-center m-3">
                        Vendor Login
                    </h3>

                    <div class="p-3">
                        
                        <form class="form-horizontal m-t-30 validate-form" id="admin_login" method="post">
                        	
                            <div class="form-group validate-input venuser_name" data-validate = "Enter Username">
                                <label for="venuser_name">Username</label>
                                <input type="text" class="form-control" name="venuser_name" id="venuser_name" placeholder="Enter username">
                            </div>

                        

                            <div class="form-group validate-input venpassword" data-validate="Enter password">
                                <label for="venpassword">Password</label>
                                <input type="password" class="form-control" name="venpassword" id="venpassword" placeholder="Enter password">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-6">
                                  
                                </div>
                                <div class="col-6 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit" id="login" value="Login">Log In</button>
                                </div>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <a href="<?php echo VENDOR_SITE_URL.'Vendor_login/forgot'; ?>" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
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
					url:"<?php echo VENDOR_SITE_URL.'Vendor_login/login'; ?>",
					type:'post',
					data:new FormData(form),
					 contentType:false,
			          cache: false,
			          processData:false,
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
						show_alert(jdata.message,'success');
				        window.location = jdata.redirect;
				        } 
				        else {
				          $('#password').val('');
				          show_alert(jdata.message,'error');
				        }
			    	}
				});
				//$("#admin_login").submit()       
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
		              url:"<?php echo ADMIN_SITE_URL.'Vendor_login/forgot_password'; ?>",
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