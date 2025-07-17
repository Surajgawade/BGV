<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>favicon.ico" type="image/ico" />
	<title>Welcome to Verify</title>
	<style type="text/css">
      #map {
        height: 80%;
      }
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
</head>
<body>
	<div id="googlemapbinary"></div>
	<input type="hidden" name="current_lat" id="current_lat" value="<?php echo $details['latitude'] ?>">
	<input type="hidden" name="current_long" id="current_long" value="<?php echo $details['longitude'] ?>">
	<input type="hidden" name="address" id="address" value="<?php echo ($details['address_edit'] !="") ? $details['address_edit'] : $details['address_edit'] ?>">
	<input type="hidden" name="dataUrl" id="dataUrl">
	<div id="map" ></div>
</div>
	<script src="<?php echo SITE_JS_URL.'jquery.min.js'; ?>"></script>
	<script src="https://files.codepedia.info/files/uploads/iScripts/html2canvas.js"></script>

	<script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCetWiu5VcxfP2f77rdIONvFHG_DZjFqBQ&#038;ver=1"></script>
	<script>
	if($("#map"). length) {
		var map;
		var current_lat = $('#current_lat').val();
		var current_long = $('#current_long').val();
		var latlng = new google.maps.LatLng(current_lat,current_long);
		function initialize() {

		    var mapOptions = {
		        center: latlng,
		        zoom: 15,
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
		    var el=document.getElementById("map");
		    map = new google.maps.Map(el, mapOptions);
		    geocoder = new google.maps.Geocoder();
		 
		    var sunCircle = {
		        strokeColor: "#054a9e",
		        strokeOpacity: 0.8,
		        strokeWeight: 1,
		        fillColor: "#025fd1",
		        fillOpacity: 0.35,
		        map: map,
		        center: latlng,
		        radius: 250 // in meters
		    };
		    cityCircle = new google.maps.Circle(sunCircle);
		  	var iconBase = 'https://mistitservices.in/mist-it/assets/images/';
		    var marker = new google.maps.Marker({
				position: latlng,
			//  title: 'Current Address',
				map: map,
				draggable: true,
				icon: iconBase + 'blue_icon.jpg'

			});
		    place_address(geocoder,map)
		}

		function place_address() {

		    var address = document.getElementById('address').value;
		    geocoder.geocode( { 'address': address}, function(results, status) {
		      if (status == 'OK') {
		      	console.log(results[0].geometry);
		        map.setCenter(results[0].geometry.location);
		       
		        var sunCircle = {
			        strokeColor: "#edab02",
			        strokeOpacity: 0.8,
			        strokeWeight: 1,
			        fillColor: "#fab505",
			        fillOpacity: 0.35,
			        map: map,
			        center: results[0].geometry.location,
			        radius: 250 // in meters
			    };
			    cityCircle = new google.maps.Circle(sunCircle);
			  	var iconBase_pink = 'https://mistitservices.in/mist-it/assets/images/';

          var marker = new google.maps.Marker({
					position: results[0].geometry.location,
					// title: 'Input Address',
					map: map,
					draggable: true,
					icon: iconBase_pink + 'pink_icon.png'

				});

		      } else {
		        alert('Geocode was not successful for the following reason: ' + status);
		      }
		    });
		}

		function saveMapToDataUrl() {

		    var element = $("#map");

		    html2canvas(element, {
		        useCORS: true,
		        onrendered: function(canvas) {
		            var dataUrl= canvas.toDataURL("image/png");
		            $('#dataUrl').val(dataUrl);
		            // DO SOMETHING WITH THE DATAURL
		            // Eg. write it to the page
		            
		        }
		    });
		}
		initialize();
	}
	</script>
</body>
</html>