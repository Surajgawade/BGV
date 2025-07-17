
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

</html>
<?php  exit(); ?>