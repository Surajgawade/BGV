<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Address Verification</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<style type="text/css">
			.text-center {
				background: #ededed;
				height: 30px;
			}
			.container {
				margin-left: auto;
			}
			h4 {
				padding: 5px;
			}
		</style>
	</head>
	<body>
		<?php
		$upath = 'https://www.mistitservices.in/mist-it/uploads/address/address_verification/';
		?>
		<div class="container" style="width: 1000px;">
			<div class="row">
				<div class="col-md-8 ">
					<div class="caption text-center">
						<h4> Photo Capture</h4>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-4">
					<div class="caption text-center">
						<h4>Selfie</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['selfie']; ?>" alt="Selfie" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['selfie'],-23,19); ?> <br>
							Location : <?php echo ($details['selfie_lat_long'] != "") ? $details['selfie_lat_long'] : $details['address_proof_lat_long']; ?></p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="caption text-center">
						<h4>Address Proof</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['address_proof']; ?>" alt="Address Proof" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['address_proof'],-23,19); ?> <br>
							Location : <?php echo ($details['address_proof_lat_long'] != "") ? $details['address_proof_lat_long'] : $details['selfie_lat_long']; ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="caption text-center">
						<h4>Location Picture 1</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['location_picture_1']; ?>" alt="Location Picture 1" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['location_picture_1'],-23,19); ?> <br>
							Location : <?php echo ($details['location_picture_lat_long_1'] != "") ? $details['location_picture_lat_long_1'] : $details['address_proof_lat_long_1']; ?></p>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="caption text-center">
						<h4>Location Picture 2</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['location_picture_2']; ?>" alt="Location Picture 2" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['location_picture_2'],-23,19); ?> <br>
							Location : <?php echo ($details['location_picture_lat_long_2'] != "") ? $details['location_picture_lat_long_2'] : $details['address_proof_lat_long_2']; ?></p>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
	
				<div class="col-md-4">
					<div class="caption text-center">
						<h4>House Picture</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['house_pic_door']; ?>" alt="House Picture" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['house_pic_door'],-23,19); ?> <br>
							Location : <?php echo ($details['house_pic_door_lat_long'] != '') ? $details['house_pic_door_lat_long'] : $details['address_proof_lat_long']; ?></p>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="caption text-center">
						<h4>Signature</h4>
					</div>
					<div class="thumbnail">
						<img src="<?php echo $upath.$details['signature']; ?>" alt="Signature" style="width:100%;height:300px;">
						<div class="caption">
							<p>Date and Time : <?php echo substr($details['house_pic_door'],-23,19); ?> <br>
							Location : <?php echo ($details['signature_lat_long'] != '') ? $details['signature_lat_long'] : $details['latitude'].','. $details['longitude']; ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>