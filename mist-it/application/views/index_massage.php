<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>favicon.ico" type="image/ico" />
	<title>Welcome to Verify</title>
	<link href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>metismenu.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>icons.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>style.css" rel="stylesheet"> </head>

<body>
	<div class="wrapper-page">
		<div class="card">
            <div class="card-body">
            	<h3 class="text-center m-0">
                    <a href="<?php echo SITE_URL; ?>" class="logo logo-admin">
                    	<img src="<?php echo SITE_IMAGES_URL; ?>logo.png" height="60" alt="logo">
                    </a>
                </h3>
                <div class="p-3">
                   	<h5 class="text-center"><?php echo isset($message) ? $message : ""; ?></h5>
	        		<div class="m-t-40 text-center">
		                <p>© <?php echo date('Y'); ?> MIST, All Rights reserved</p>
		            </div>
	        	</div>
	        </div>
        </div>
    </div>
</div>
	<script src="<?php echo SITE_JS_URL.'jquery.min.js'; ?>"></script>
	<script src="<?php echo SITE_JS_URL.'bootstrap.bundle.min.js'; ?>"></script>
	<script src="<?php echo SITE_JS_URL.'metisMenu.min.js'; ?>"></script>
	<script src="<?php echo SITE_JS_URL.'jquery.slimscroll.js'; ?>"></script>
	<script src="<?php echo SITE_JS_URL.'waves.min.js'; ?>"></script>
	<script src="<?php echo SITE_JS_URL.'jquery.validate.min.js' ?>"></script>
	<script src="<?php echo SITE_JS_URL.'notify.js' ?>"></script>
	<script>
	$(document).ready(function() {

		$('#admin_login').validate({
			rules: {
				email_id: {
					required: true,
					email : true
				},
				password: {
					required: true
				}
			},
			messages: {
				email_id: {
					required: "Please enter email address"
				},
				password: {
					required: "Please enter password"
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: $('#admin_login').attr('action'),
					type: 'post',
					data: $('#admin_login').serialize(),
					dataType: 'json',
					beforeSend: function() {
						$('#login').text('Logging...');
						$('#login').attr('disabled','disabled');
					},
					complete: function() {
						$('#login').text('Login');
						$('#login').removeAttr('disabled');
					},
					success: function(jdata) {
						if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
							window.location = jdata.redirect;
							return;
						} else {
							$('#password').val('');
							show_alert(jdata.message, 'error');
						}
					}
				});
			}
		});
		var reg_x_request_sent = false;
		$('#forgot_password').validate({
			rules: {
				email_id_frg: {
					required: true,
					email: true
				}
			},
			messages: {
				email_id_frg: {
					required: "Enter Email ID",
					email: "Enter Valid Email ID"
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: "<?php echo ADMIN_SITE_URL.'dashboard/forgot_password'; ?>",
					type: 'post',
					data: $('#forgot_password').serialize(),
					dataType: 'json',
					beforeSend: function() {
						$('#lost_pass_btn').val('Checking...');
					},
					complete: function() {
						$('#lost_pass_btn').val('Submit');
					},
					success: function(jdata) {
						if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
							show_alert(jdata.message, 'success');
							$('#forgot_password')[0].reset();
						} else {
							show_alert(jdata.message, 'error');
							$('#forgot_password')[0].reset();
						}
					}
				});
			}
		});
		show_alert = function(content, alert_type) {
			if(content) {
				$.notify(content, alert_type);
			}
		}
	});
	window.setTimeout(function() {
		$(".alert").fadeTo(500, 0).slideUp(500, function() {
			$(this).remove();
		});
	}, 4000);
	</script>
</body>

</html>