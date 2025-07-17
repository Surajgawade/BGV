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
<body>
	<div class="account-pages my-5 pt-sm-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 col-lg-6 col-xl-5">
					<div class="card overflow-hidden">
						<br><br>
						<h3 class="text-center m-0">
							<a href="<?php ADMIN_SITE_URL  ?>" class="logo logo-admin"><img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35"></a>
						</h3>
						<br><br>
						<div class="card-body pt-0">
							<div class="ex-page-content text-center">
								<h1 class="text-dark">400</h1>
								<h3 class="">Sorry, page not found</h3>
								<br>
								<a class="btn btn-info mb-4 waves-effect waves-light" href="<?php echo ADMIN_SITE_URL; ?>"><i class="mdi mdi-home"></i> Back to Dashboard</a>
							</div>
						</div>
					</div>
					<div class="mt-5 text-center">
						© <?php echo date('Y');?>  <a href="#"><?php echo CRMNAME; ?></a>.</strong> All rights reserved.
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>