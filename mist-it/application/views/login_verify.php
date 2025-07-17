<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>favicon.ico" type="image/ico" />
	<title>Mobile Verification - Login </title>
	<link href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_CSS_URL; ?>metismenu.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>icons.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL;?>style.css" rel="stylesheet" type="text/css">
 

<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}


</style>

</head>

<body>
	<div class="wrapper-page">
		<div class="card">
			<div class="card-body">
				<h3 class="text-center m-0">
                    <a href="#" class="logo logo-admin">
                      <img src="<?php echo SITE_IMAGES_URL; ?>logo.png" height="60" alt="logo">
                    </a>
                </h3>
				<div class="p-3">
					<h4 class="text-muted font-22 text-center">Welcome</h4>
					    <div class="m-t-40 text-left">
							<div id="location_access">
							    <p  class="error"> Location services not enabled. Refresh the page once done.  </p>
							</div>
						</div>

                        <div class="m-t-40 text-left">
						    
						
							<p>We have partnered with your current employer to conduct your address verification.</p>
                            <table border="1">
                            <tr><td><b>Employer </b> </td><td><?php if(isset($details['clientname'])){ echo ucwords($details['clientname']);} else{ '';  } ?><br></td></tr>
                          	<tr><td><b>Name of the candidate  </td><td> </b> <?php if(isset($details['CandidateName'])){ echo ucwords($details['CandidateName']);} else{ '';  } ?><br></td></tr>
                          	<tr><td><b>Contact details  </td><td> </b> <?php if(isset($details['candidate_contact'])){ echo $details['candidate_contact'];} else{ '';  } ?> <?php if(isset($details['candidate_email'])){ echo " | ".$details['candidate_email'];} else{ '';  } ?> <br></td></tr>

                          	<tr><td><b>Provided address </td><td></b> <?php if(isset($details['address'])){ echo ucwords($details['address']);} else{ '';  } ?> , <?php if(isset($details['city'])){ echo $details['city'];} else{ '';  } ?> ,  <?php if(isset($details['state'])){ echo $details['state'];} else{ '';  } ?> , <?php if(isset($details['pincode'])){ echo $details['pincode'];} else{ '';  } ?></td></tr>
                          
							</table>
						</div>

						<div class="m-t-40 text-left">
							
							<p> <span class="error"> <i><b>Note:</b></i> <br> 
					            </span>
								1. Ensure location service is enabled.<br>
								<!--2. If the "Verify" button is still disabled <a href="<?php echo SITE_URL.'uploads/DigitVerify_Location_Enable.pdf';?>" style="text-decoration: underline;"><b> Click here </b></a><br>-->
							    2. Details should only be updated while present at the provided address .
							</p>
						</div>

						<div class="m-t-40 text-left">
							
							<p><input type="checkbox" id="acknowledge" name="acknowledge" class="form-check-input" style="margin-left: auto;">&nbsp; &nbsp; &nbsp;<b> Accept and continue.<button type="button" class="btn btn-link" style = "color: blue;" data-toggle="modal" data-target="#terms_condition">Terms & Conditions</button>
							</p>
						</div>

					    <?php 

                           if(!empty($details['id'])) {
                              $address_id = base64_encode($details['id']);
                              $mobile_no = base64_encode($details['candidate_contact']);
                           }
                           else{
                               $address_id = base64_encode("NA");
                               $mobile_no = base64_encode($details['candidate_contact']);
                           }   

					     echo form_open(SITE_URL.'av/'.$address_id.'/'.$mobile_no, array('name'=>'frm_location_verify','id'=>'frm_location_verify','class' => 'form-horizontal m-t-30')); ?>

					    <input type="hidden" name="address_id" id="address_id" value='<?php echo set_value('address_id',$details['id']); ?>'>
					    <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
						
						<div class="form-group">
							<div class="col-3">
								<button disabled="disabled" class="btn btn-secondary w-md waves-effect waves-light" id="btn_verify" name="btn_verify" type="submit">Proceed</button>
							</div>
						</div>
						<?php echo form_close(); ?>
						<div class="m-t-40 text-center">
							<p>©<?php echo date('Y'); ?> MIST IT Services Pvt Ltd. All Rights reserved</p>
						</div>
				</div>
			</div>
		</div>
	</div>
	</div>

<div id="terms_condition" class="modal fade" role="dialog">
  <div class="modal-dialog">
  		        <button type="button" class="close" data-dismiss="modal">&times;</button>

    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Terms and Conditions</h4>
      </div>
    
      <div class="modal-body" style="font: message-box;">
           <p>I the undersigned currently employed or to be employed at <strong style="color:blue;"><?php if(isset($details['clientname'])){ echo ucwords($details['clientname']);} else{ '';  } ?></strong>  confirm that the details provided by me during hiring are true to the best of my knowledge. I understand that any false information, omissions or misrepresentation of facts provided by me may result in rejection of my application or discharge at any time during my employment. I authorize the company or its agents to check validity/existence, including but not limited to consumer reporting bureaus, background verification companies, law enforcement authorities, etc. to verify and of this information, including but not limited to criminal history, address details, previous employment and financial standing. I agree to signing and following a Non-Disclosure agreement and conforming to the rules and regulations of the company and acknowledge that the company shall have the right to terminate my services without notice for any misconduct or for any reason including but not limited to unsatisfactory performance, lack of funds, reorganization or elimination of the position.</p>
      </div>
      <div class="modal-footer">
         
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
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
	<script src="<?php echo SITE_JS_URL.'bootstrap-notify.js';  ?>"></script>
<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAU_tcKKvL8NP97duFTiS4-Urc6S1JKJ4g&#038;ver=1"></script>

<script type="text/javascript">
	function show_alert(content, alert_type = 'info') {
	    if(content) {
	        $.notify({ message: content },{ type: alert_type });
	    }
	}

	/*function checkconnection() {
		var status = navigator.onLine;
		if (status) {
		    $('#btn_verify').removeAttr('disabled');
		} else {
		    $('#btn_verify').attr('disabled','disabled');
		}
    }
 
 checkconnection();*/
/*	public void checkConnection()
    {
        ConnectivityManager connectivityManager=(ConnectivityManager)

                this.getSystemService(Context.CONNECTIVITY_SERVICE);


  NetworkInfo wifi=connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_WIFI);


 NetworkInfo  network=connectivityManager.getNetworkInfo(ConnectivityManager.TYPE_MOBILE);


        if (wifi.isConnected())
        {
           //Internet available

        }
        else if(network.isConnected())
        {
             //Internet available


        }
        else
        {
             //Internet is not available
        }
    }*/
</script>
	
	<script>

	function show_alert(content, alert_type = 'info') {
	    if(content) {
	        $.notify({ message: content },{ type: alert_type });
	    }
	}
    getLocation();
        function getLocation() {
            if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
                return;
            }
        }
        var latitude,longitude;
        function showPosition(position) {
            latitude = position.coords.latitude;
            longitude= position.coords.longitude;
         
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            $('#btn_verify').removeAttr('disabled');
            $('#location_access').text('');

            checkboxStatus();

        }
        function showError(error) {
            let err = null;
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    err = "Please enable GPS to proceed"
                    break;
                case error.POSITION_UNAVAILABLE:
                    err = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    err = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    err = "An unknown error occurred."
                break;
            }
            alert(err);
            getLocation();
            return;
        }

	window.setTimeout(function() {
	    $(".alert").fadeTo(500, 0).slideUp(500, function() {
	        $(this).remove();
	    });
	}, 4000);


$('#acknowledge').on('change', function() {
    var value = this.checked ? $('#acknowledge').val() : '';
    if(value == "on")
    {
        $('#btn_verify').removeAttr('disabled'); 
        $('#btn_verify').removeClass('btn btn-secondary');
        $('#btn_verify').addClass('btn btn-success');
    }
    else
    {
        $("#btn_verify").attr('disabled','disabled'); //disable
        $('#btn_verify').removeClass('btn btn-success'); 
        $('#btn_verify').addClass('btn btn-secondary');
    }
});

function checkboxStatus() {
    if ($('#acknowledge').is(':checked') == true) {

        $('#btn_verify').removeAttr('disabled');
 
    } else {
    
        $("#btn_verify").attr('disabled','disabled'); //disable 
    }
}

	</script>

	<!--<script type="text/javascript">
		var apiGeolocationSuccess = function(position) {
    alert("API geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
};

var tryAPIGeolocation = function() {
    jQuery.post( "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyDCa1LUe1vOczX1hO_iGYgyo8p_jYuGOPU", function(success) {
        apiGeolocationSuccess({coords: {latitude: success.location.lat, longitude: success.location.lng}});
    })
        .fail(function(err) {
            alert("API Geolocation error! \n\n"+err);
        });
};

var browserGeolocationSuccess = function(position) {
    alert("Browser geolocation success!\n\nlat = " + position.coords.latitude + "\nlng = " + position.coords.longitude);
};

var browserGeolocationFail = function(error) {
    switch (error.code) {
        case error.TIMEOUT:
            alert("Browser geolocation error !\n\nTimeout.");
            break;
        case error.PERMISSION_DENIED:
            if(error.message.indexOf("Only secure origins are allowed") == 0) {
                tryAPIGeolocation();
            }
            break;
        case error.POSITION_UNAVAILABLE:
            // dirty hack for safari
            if(error.message.indexOf("Origin does not have permission to use Geolocation service") == 0) {
                tryAPIGeolocation();
            } else {
                alert("Browser geolocation error !\n\nPosition unavailable.");
            }
            break;
    }
};

var tryGeolocation = function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            browserGeolocationSuccess,
            browserGeolocationFail,
            {maximumAge: 50000, timeout: 20000, enableHighAccuracy: true});
    }
};

tryGeolocation();
	</script>-->
	<!--<script type="text/javascript">
		function do_something(coords) {
			alert(coords.latitude);
			alert(coords.longitude);
    // Do something with the coords here
}


        $.getJSON('https://ipinfo.io/geo', function(response) { 
        var loc = response.loc.split(',');

        var coords = {
            latitude: loc[0],
            longitude: loc[1]
        };
        do_something(coords);
        });
      

	</script>-->
</body>

</html>