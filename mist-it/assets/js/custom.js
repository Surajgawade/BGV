$.validator.addMethod("alpha_number_dot", function(value, element) {
    return this.optional( element ) || /^[a-zA-Z0-9.]+$/.test( value );
}, "Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.");
$.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Only alphabetical characters");
$.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0 && value != "";
}, "No space please");
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
});
$.validator.addMethod("greaterThan",function (value, element, param) {
  return parseFloat(value) != 0;
},"This field required");
$.validator.addMethod("extension", function(value, element, param) {
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, $.validator.format("Please enter a value with a valid extension."));
$.validator.addMethod("validDateFormat", function(value, element) {
     return value.match(/^(0?[1-9]|[12][0-9]|3[0-1])[/., -](0?[1-9]|1[0-2])[/., -](19|20)?\d{4}$/);
}, "Select a date in the format dd-mm-yyyy.");
var place_lat_long;

$(document).on('dblclick', '#datatable_candidate tbody tr', function(){
	if($(this).find('td a:first').attr('href') != "undefined") {
	    window.open($(this).find('td a:first').attr('href'), '_blank');
	}
});

$(document).ready(function() {

	$('#frm_demp_pan_verify').validate({
		rules : {
			name : {
				required : true
			},
			pan_number : {
				required : true,
				minlength : 10
			}
		},
		messages : {
			name : {
				required : "Please enter name"
			},
			pan_number : {
				required : "Please enter pan number",
				minlength : "Enter valid pan number"
			}
		},
		submitHandler: function() {
			$.ajax({
				url: $('#frm_demp_pan_verify').attr('action'),
				type: 'post',
				data: $('#frm_demp_pan_verify').serialize(),
				dataType: 'json',
				beforeSend: function() {
					$('#btn_demo_submit').text('please wait...').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_demo_submit').text('Submit').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
						$('#append_demo_result').html(jdata.form_data);
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});

	$('#frm_export_data').validate({
		rules : {
			client_id : {
				required : true
			}
		},
		messages : {
			client_id : {
				required : "ID"
			}
		},
		submitHandler: function() {
			$.ajax({
				url: $('#frm_export_data').attr('action'),
				type: 'post',
				data: $('#frm_export_data').serialize(),
				dataType: 'json',
				beforeSend: function() {
					$('#btn_export').text('exporting...').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_export').text('Export').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			}).done(function(jdata){
	            var $a = $("<a>");
	            $a.attr("href",jdata.file);
	            $("body").append($a);
	            $a.attr("download",jdata.file_name+".xlsx");
	            $a[0].click();
	            $a.remove();
	       	}); 
		}
	});

	$('#datatable_candidate').DataTable({
		"scrollX": true
	});

	$('#datatable_candidate tbody').on('click', 'td button', function (){
		if(confirm('You need send message sms again') === true) {
			
		    var id = $(this).attr('id'); 
		    if(id != "") {
		    	$.ajax({
					url: 'candidates/trigger_sms_again',
					type: 'post',
					data: {send_id:id},
					dataType: 'json',
					beforeSend: function() {
						$(this).text('sending...');
						$(this).attr('disabled','disabled');
					},
					complete: function() {
						$(this).text('Send');
						$(this).removeAttr('disabled');
					},
					success: function(jdata) {
						if(jdata.status == 200) {
							show_alert(jdata.message, 'success');
						} else {
							show_alert(jdata.message, 'danger');
						}
					},
					error: function (jqXHR, exception) {
						show_alert(jqXHR, 'danger');
					}
				});
		    }
		}
		else {
			show_alert('cancelled','info');
		}	    
	});

	$('.report_pdf').on('click',function(e){

		if( $('#verification_status').val() == 'WIP') {
			if(confirm('The report is not completed, you still want to proceed') === true) {
				e.preventDefault(); 
			    var url = $(this).data('href'); 
			    window.open(url, '_blank');
			}
			else {
				show_alert('cancelled','info');
			}
		}
		else {
			e.preventDefault(); 
		    var url = $(this).data('href'); 
		    window.open(url, '_blank');
		}
	});

	$.ajax({
		url: 'home/index',
		type: 'post',
		data: '',
		dataType: 'json',
		success: function(jdata) {
			if(jdata.status == 200) {
				$('.client_count').text(jdata.client);
				$('.new_check_count').text(jdata.new_check_count);
				$('.clear_check_count').text(jdata.clear_check_count);
				$('.total_count').text(jdata.total);
			} else {
				show_alert(jdata.message, 'danger');
			}
		}
	});

	$('#datatable').DataTable({
		"scrollX": true
	});
	var table = $('#datatable-buttons').DataTable({
	    lengthChange: false
	});

	$('#qc_status').on('change',function(){
		var qc_status = $(this).val();
		if(qc_status == 'Fail SMS Send Again') {
			$('#qc_status_fail_message').val('Due to incomplete address you need to verify the credentials again.');
			$('.qc_status_fail_message_div').show();
		}else {
			$('#qc_status_fail_message').val('');
			$('.qc_status_fail_message_div').hide();
		}
	}).trigger('change');

	$('#btn_update_address').on('click',function(){
		let address =  $('#update_address').val();
		let update_id =$('#cands_update_id').val();
		$.ajax({
			url: 'candidates/update_address',
			type: 'post',
			data: {'update_address':address,'update_id':update_id},
			dataType: 'json',
			beforeSend: function() {
				$('#btn_update_address').text('updating...').attr('disabled','disabled');
			},
			complete: function() {
				$('#btn_update_address').text('Update Address').removeAttr('disabled');
			},
			success: function(jdata) {
				if(jdata.status == 200) {
					show_alert(jdata.message, 'success');
					location.reload();
					return;
				} else {
					show_alert("Not connect. || Verify Network || Requested page not found. [404] || Internal Server Error [500]", 'danger');
				}
			},
			error: function (jqXHR, exception) {
				show_alert(jqXHR, 'danger');
			}
		});
	});

	$('.delete_row').on('click',function(){
		if(confirm('Do you want to delete this record') == true)
		{
			if($(this).data('action_url') != "") {
				$.ajax({
					url: $(this).data('action_url'),
					type: 'post',
					data: '',
					dataType: 'json',
					beforeSend: function() {
						$(this).text('updating...');
						$(this).attr('disabled','disabled');
					},
					complete: function() {
						$(this).text('Submit');
						$(this).removeAttr('disabled');
					},
					success: function(jdata) {
						if(jdata.status == 200) {
							show_alert(jdata.message, 'success');
							if(typeof jdata.redirect != 'undefined') {
								window.location.href = jdata.redirect;
							}else {
								location.reload();
							}
							return;
						} else {
							show_alert("Not connect. || Verify Network || Requested page not found. [404] || Internal Server Error [500]", 'danger');
						}
					},
					error: function (jqXHR, exception) {
						show_alert(jqXHR, 'danger');
					}
				});
			}
			else
			{
				show_alert('Unable to delete this row, please contact admin', 'danger');
			}
		}
		else
		{
			show_alert('You clicked Cancel', 'danger');
		}
	});	
	$('#frm_create_check').validate({
		rules: {
			client_id : {
				required : true
			},
			CMPRefNumber : {
				required : true	
			},
			reference_no : {
				required : true
			},
			candidate_name : {
				required : true
			},
			email_id : {
				required : true,
				email : true
			},
			mobile_1 : {
				required : true,
				digits : true,
				maxlength : 10,
				minlength : 10
			}
		},
		messages: {
			client_id : {
				required : "Select Client"
			},
			CMPRefNumber : {
				required : "System reference number missing"
			},
			reference_no : {
				required : "Please enter reference number",
				maxlength : "Max 20 characters"
			},
			candidate_name : {
				required : "Please enter full name"
			},
			email_id : {
				required : "Please enter email ID",
				email : "Please enter valid email ID"
			},
			mobile_1 : {
				required : "Please enter mobile number",
				digits : "Only digits allowed",
				maxlength : "Enter 10 digit valid number",
				minlength : "Enter 10 digit valid number"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_create_check').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_candidate_create_check').text('loading...').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_candidate_create_check').text('Submit').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						$('#frm_create_check')[0].reset();
						if (typeof jdata.redirect != "undefined" && jdata.redirect) {
					        show_alert(jdata.message, 'success',jdata.redirect);
					    }else {
						    location.reload();
					    }
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
	$('#frm_update_cands_check').validate({
		rules: {
			cands_update_id : {
				required : true
			},
			client_id : {
				required : true
			},
			CMPRefNumber : {
				required : true	
			},
			reference_no : {
				required : true
			},
			candidate_name : {
				required : true
			},
			email_id : {
				required : true,
				email : true
			},
			mobile_1 : {
				required : true,
				digits : true,
				maxlength : 10,
				minlength : 10
			}
		},
		messages: {
			cands_update_id : {
				required : "Update ID Missing"
			},
			client_id : {
				required : "Select Client"
			},
			CMPRefNumber : {
				required : "System reference number missing"
			},
			reference_no : {
				required : "Please enter reference number",
				maxlength : "Max 20 characters"
			},
			candidate_name : {
				required : "Please enter full name"
			},
			email_id : {
				required : "Please enter email ID",
				email : "Please enter valid email ID"
			},
			mobile_1 : {
				required : "Please enter mobile number",
				digits : "Only digits allowed",
				maxlength : "Enter 10 digit valid number",
				minlength : "Enter 10 digit valid number"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_update_cands_check').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_candidate_update_check').text('loading...').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_candidate_update_check').text('Update Details').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						$('#frm_update_cands_check')[0].reset();
						if (typeof jdata.redirect != "undefined" && jdata.redirect) {
					        show_alert(jdata.message, 'success',jdata.redirect);
					    }else {
						    location.reload();
					    }
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});

	$('.load_first_tab').on('change',function(){
		let can_id = $(this).data('can_id');
		let tab_name = $(this).data('tab_name');
		if(can_id != 0)
		{
          	$.ajax({
            	type:'GET',
            	url:'candidates/ajax_tab_data/'+can_id+'/'+tab_name,
            	beforeSend :function(){
              		$('#'+tab_name).html("Please wait...");
            	},
            	success:function(html) {
              		$('#'+tab_name).html(html);
            	}
          });
      	}
  	}).trigger('change');

	$('#frm_canidate_qc').validate({
		rules: {
			verification_status : {
				required : true
			},
			comment : {
				required : true
			},
			cands_update_id : {
				required : true
			}
		},
		messages: {
			verification_status : {
				required : "Select Status"
			},
			comment : {
				required : "Enter remarks"
			},
			cands_update_id : {
				required : "ID Missing"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_canidate_qc').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_candidate_qc').text('updating...');
					$('#btn_candidate_qc').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_candidate_qc').text('Submit');
					$('#btn_candidate_qc').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
						location.reload();
						return;
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
	$('#frm_client_details').validate({
		rules: {
			client_name : {
				required: true
			},
			email_id: {
				required: true,
				email : true,
				remote: {
                	url: "clients/is_email_exits",
	                type: "post",
	                data: { email_id: function() { return $( "#email_id" ).val(); } }
	            }
			},
			password: {
				required: true
			},
			crm_password: {
				required: true,
				equalTo : "#password"
			},
			first_name : {
				required : true
			},
			address : {
				required : true
			},
			profile_pic : {
				extension : 'jpg|jpeg|png'
			}
		},
		messages: {
			client_name : {
				required: "Please enter client name"
			},
			email_id: {
				required: "Please enter email id",
				email : true,
				remote : "Email ID exists"
			},
			password: {
				required: "Please enter password"
			},
			crm_password: {
				required: "Please enter confirm password",
				equalTo : "password and confirm password not matched"
			},
			first_name : {
				required : "Please enter first name"
			},
			address : {
				required : "Please enter address"
			},
			profile_pic : {
				extension : "Select image type EX (jpg|jpeg|png)"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_client_details').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_submit').text('Submiting...');
					$('#btn_submit').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_submit').text('Submit');
					$('#btn_submit').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						window.location = jdata.redirect;
						return;
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
	$('#frm_client_update').validate({
		rules: {
			client_id : {
				required : true
			},
			client_name : {
				required: true
			},
			crm_password: {
				equalTo : "#password"
			},
			first_name : {
				required : true
			},
			address : {
				required : true
			},
			profile_pic : {
				extension : 'jpg|jpeg|png'
			}
		},
		messages: {
			client_name : {
				required: "Please enter client name"
			},
			crm_password: {
				equalTo : "password and confirm password not matched"
			},
			first_name : {
				required : "Please enter first name"
			},
			address : {
				required : "Please enter address"
			},
			profile_pic : {
				extension : "Select image type EX (jpg|jpeg|png)"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_client_update').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_update').text('updating...');
					$('#btn_update').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_update').text('Submit');
					$('#btn_update').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
						location.reload();
						return;
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
	$('#frm_candidates_profile').validate({
		rules: {
			candidate_name : {
				required : true
			},
			email_id : {
				required : true,
				email : true
			},
			mobile_1 : {
				required : true,
				digits : true,
				maxlength : 10,
				minlength : 10
			},
			reference_no : {
				required : true
			},
			address : {
				required : true,
				maxlength : 500
			},
			address_type : {
				required : true,
				maxlength : 50
			}
		},
		messages: {
			candidate_name : {
				required : "Please enter full name"
			},
			email_id : {
				required : "Please enter email ID",
				email : "Please enter valid email ID"
			},
			mobile_1 : {
				required : "Please enter mobile number",
				digits : "Only digits allowed",
				maxlength : "Enter 10 digit valid number",
				minlength : "Enter 10 digit valid number"
			},
			reference_no : {
				required : "Please enter reference number",
				maxlength : "Max 20 characters"
			},
			address : {
				required : "Please enter address",
				maxlength : "Max 250 characters"
			},
			address_type : {
				required : "Please enter address type",
				maxlength : "Max 50 characters"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_candidates_profile').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_candidate_submit').text('loading...');
					$('#btn_candidate_submit').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_candidate_submit').text('Submit');
					$('#btn_candidate_submit').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
						$('#frm_candidates_profile')[0].reset();
						location.reload();
						return;
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
	$('#frm_bulk_upload').validate({
		rules: {
			file_title : {
				required : true
			},
			bulk_upload_file : {
				required : true,
				extension : 'csv'
			}
		},
		messages: {
			file_title : {
				required : "Please enter full name"
			},
			bulk_upload_file : {
				required : "Select file to upload",
				extension : "Only CSV file allowd"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_bulk_upload').attr('action'),
				type: 'post',
				data: new FormData(form),
				dataType: 'json',
				contentType:false,
	            cache: false,
	            processData:false,
				beforeSend: function() {
					$('#btn_upload').text('updating...');
					$('#btn_upload').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_upload').text('Submit');
					$('#btn_upload').removeAttr('disabled');
				},
				success: function(jdata) {
					if(typeof jdata.file_error != 'undefined') {
						show_alert(jdata.file_error, 'danger');
					}
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
						$('#frm_bulk_upload')[0].reset();
						//location.reload();
						return;
					} else {
						show_alert(jdata.message, 'danger');
					}
				}
			});
		}
	});
});
$(document).on('click', '.myOpenWindow', function() {
    var winURL = $(this).attr('data-href');
    myOpenWindow(winURL);
});
function myOpenWindow(winURL, winObj) {
    var theWin;
    if (winObj != null) {
        if (!winObj.closed) {
            winObj.focus();
            return winObj;
        }
    }
    theWin = window.open(winURL, 'myWin', "width=900,height=650");
    return theWin;
}
if($("#map"). length) {

	var map;
	var current_lat = $('#current_lat').val();
	var current_long = $('#current_long').val();
	var latlng = new google.maps.LatLng(current_lat,current_long);
	function initialize() {
	    var mapOptions = {
	        center: latlng,
	        zoom: 16,
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
	        radius: 200 // in meters
	    };
	    cityCircle = new google.maps.Circle(sunCircle);
	    place_address(geocoder,map)
	}
	
	function place_address() {
	    var address = document.getElementById('update_address').value;
	    geocoder.geocode( { 'address': address}, function(results, status) {
	      if (status == 'OK') {
	      	place_lat_long = results[0].geometry;
	        map.setCenter(results[0].geometry.location);
	        var sunCircle = {
		        strokeColor: "#edab02",
		        strokeOpacity: 0.8,
		        strokeWeight: 1,
		        fillColor: "#fab505",
		        fillOpacity: 0.35,
		        map: map,
		        center: results[0].geometry.location,
		        radius: 200 // in meters
		    };
		    cityCircle = new google.maps.Circle(sunCircle);
		    distance_Cal();
	      } else {
	        alert('Geocode was not successful for the following reason: ' + status);
	      }
	    });
	}
	initialize();    

	function saveMapToDataUrl() {
	    var element = $("#map");
	    html2canvas(element, {
	        useCORS: true,
	        onrendered: function(canvas) {
	            var dataUrl= canvas.toDataURL("image/png");
	            $('#mapdataUrl').val(dataUrl);
	        }
	    });
	}
}

function update_distance(distance){
	$.ajax({
		url: 'candidates/update_distance',
		type: 'post',
		data: {distance:distance,add_update_id : $('#add_update_id').val()},
		dataType: 'json',
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(jdata) {
			console.log(jdata);
		}
	});
}

function distance_Cal() {

	var url_arress = $('#update_address').val().replace("#", "");
	var url_arress = url_arress.replace("&", " and ");
	var geo_url = encodeURI('https://maps.googleapis.com/maps/api/geocode/json?address='+url_arress+'&key=AIzaSyCetWiu5VcxfP2f77rdIONvFHG_DZjFqBQ');
	console.log(geo_url);
	$.get(geo_url, function( data ) {
		
		if(data != "") {
			var lat1 = data.results[0].geometry.location.lat;
			var lon1 = data.results[0].geometry.location.lng;
			var lat2 = $('#current_lat').val();
			var lon2 = $('#current_long').val();

			var  unit = 'K';
			if ((lat1 == lat2) && (lon1 == lon2)) {
				return 0;
			}
			else {

				var radlat1 = Math.PI * lat1/180;
				var radlat2 = Math.PI * lat2/180;
				var theta = lon1-lon2;
				var radtheta = Math.PI * theta/180;
				var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
				if (dist > 1) {
					dist = 1;
				}
				dist = Math.acos(dist);
				dist = dist * 180/Math.PI;
				dist = dist * 60 * 1.1515;
				if (unit=="K") { dist = dist * 1.609344 }
				if (unit=="N") { dist = dist * 0.8684 }
				dist = dist.toFixed(3);
				$('.distance_by_place').text(dist+'km');
				update_distance(dist);
				return dist;
			}
		}else {
			alert('google place API not finding given address');
			show_alert('google place API not finding given address', 'danger');
		}
	});
}
$(document).on('click', '#btn_pan_verify', function() {
	let pan_card_no = $('#pan_card_no').val();
	let pan_card_name = $('#pan_card_name').val();
	if(pan_card_no.length == 10) {
		$.ajax({
			url: 'candidates/verify_pancard',
			type: 'post',
			dataType: 'json',
			data: {'pan_card_no':pan_card_no, 'pan_card_name':pan_card_name, 'cands_id':$('#cands_update_id').val()},
			beforeSend: function() {
				$('#btn_pan_verify').text('verifying please wait...').attr('disabled','disabled');
			},
			complete: function() {
				$('#btn_pan_verify').text('Verify').removeAttr('disabled');
			},
			success: function(jdata) {
				
				if(typeof jdata.api_response != 'undefined') {
					$('#api_responce').val(jdata.api_response.response_data);
					$('#pan_status').val(jdata.api_response.api_pan_status);
					$('#response_msg').val(jdata.api_response.response_msg);
					$('#pan_name').val(jdata.api_response.api_pan_name);
					$('.verification_response').show();
				}
				if(jdata.status == 200) {
					show_alert(jdata.message, 'success');
				} else {
					show_alert(jdata.message, 'danger');
				}
			}
		});
	}else {
		show_alert('Please enter valid pancard number', 'info');
	}
});

$(document).on('click', '#btn_dl_verify', function() {
	let dl_card_no = $('#dl_card_no').val();
	if(dl_card_no.length > 10) {
		$.ajax({
			url: 'candidates/verify_dl',
			type: 'post',
			dataType: 'json',
			data: {'dl_card_no':dl_card_no,'cands_id':$('#cands_update_id').val()},
			beforeSend: function() {
				$('#btn_dl_verify').text('verifying please wait...').attr('disabled','disabled');
			},
			complete: function() {
				$('#btn_dl_verify').text('Verify Driving Licence').removeAttr('disabled');
			},
			success: function(jdata) {
				console.log(jdata);
				if(typeof jdata.api_response != 'undefined') {
					$('#dl_name').val(jdata.api_response.dl_name);
					$('#dl_dob').val(jdata.api_response.dl_dob);
					$('#dl_blood_group').val(jdata.api_response.dl_blood_group);
					$('#dl_issue_date').val(jdata.api_response.dl_issue_date);
					$('#dl_expiry_date').val(jdata.api_response.dl_expiry_date);
					$('#dl_state').val(jdata.api_response.dl_state);
					$('#dl_cov_details').val(jdata.api_response.dl_cov_details);
					$('#dl_status').val(jdata.api_response.dl_status);
					$('#dl_api_responce').val(jdata.api_response.response_data);
					$('#dl_response_msg').val(jdata.api_response.dl_response_msg);
					$('.dl_verification_response').show();
				}
				if(jdata.status == 200) {
					show_alert(jdata.message, 'success');
				} else {
					show_alert(jdata.message, 'danger');
				}
			}
		});
	}else {
		show_alert('Please enter valid pancard number', 'info');
	}
});

$(document).on('click', '#btn_voter_verify', function() {
	let voter_card_no = $('#voter_card_no').val();
	if(voter_card_no.length > 5) {
		$.ajax({
			url: 'candidates/verify_voter',
			type: 'post',
			dataType: 'json',
			data: {'voter_card_no':voter_card_no,'cands_id':$('#cands_update_id').val()},
			beforeSend: function() {
				$('#voter_card_no').text('verifying please wait...').attr('disabled','disabled');
			},
			complete: function() {
				$('#voter_card_no').text('Verify Driving Licence').removeAttr('disabled');
			},
			success: function(jdata) {
				console.log(jdata);
				if(typeof jdata.api_response != 'undefined') {
					$('#voter_name').val(jdata.api_response.voter_name);
					$('#voter_age').val(jdata.api_response.voter_age);
					$('#district').val(jdata.api_response.district);
					$('#voter_dob').val(jdata.api_response.voter_dob);
					$('#voter_gender').val(jdata.api_response.voter_gender);
					$('#voter_status').val(jdata.api_response.voter_status);
					$('#voter_api_responce').val(jdata.api_response.response_data);
					$('#voter_response_msg').val(jdata.api_response.dl_response_msg);
					$('.voter_verification_response').show();
				}
				if(jdata.status == 200) {
					show_alert(jdata.message, 'success');
				} else {
					show_alert(jdata.message, 'danger');
				}
			}
		});
	}else {
		show_alert('Please enter valid pancard number', 'info');
	}
});

$(document).on('click', '#btn_passport_verify', function() {
	$('#frm_ajax_passport_verification').validate({
		rules: {
			passport_no : {
				required : true
			},
			passport_name : {
				required : true
			},
			passport_last_name : {
				required : true
			},
			passport_dob : {
				required : true
			},
			passport_doe : {
				required : true
			},
			passport_gender : {
				required : true
			},
			passport_type : {
				required : true
			},
			passport_country : {
				required : true
			}
		},
		messages: {
			passport_no : {
				required : "Please enter password number"
			},
			passport_name : {
				required : "Please enter full name"
			},
			passport_last_name : {
				required : "Please enter last name"
			},
			passport_dob : {
				required : "Please enter DOB"
			},
			passport_doe : {
				required : "Please enter DOE"
			},
			passport_gender : {
				required : "Select gender"
			},
			passport_type : {
				required : "Select passport type"
			},
			passport_country : {
				required : "Select Country"
			}
		},
		submitHandler: function(form) {
			$.ajax({
				url: $('#frm_ajax_passport_verification').attr('action'),
				type: 'post',
				data: $('#frm_ajax_passport_verification').serialize()+'&cands_id='+$('#cands_update_id').val(),
				dataType: 'json',
				beforeSend: function() {
					$('#btn_passport_verify').text('loading...').attr('disabled','disabled');
				},
				complete: function() {
					$('#btn_passport_verify').text('Submit').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						console.log(jdata);
						if(typeof jdata.api_response != 'undefined') {
							$('#result_no_1').val(jdata.api_response.voter_name);
							$('#result_no_2').val(jdata.api_response.voter_age);
							$('.passport_verification_response').show();
						}
						if(jdata.status == 200) {
							show_alert(jdata.message, 'success');
						} else {
							show_alert(jdata.message, 'danger');
						}
					} else {
						show_alert(jdata.message, 'danger');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					show_alert('Unable to request the API, please check network connectivity', 'danger');
				}
			});
		}
	});
});

$('.resend_sms_email tbody').on('click', 'td button', function (){
	if(confirm('You need send message sms again') === true) {
		
	    let id = $(this).attr('id');
	    let check = $(this).attr('data-check'); 
	    if(id != "" && check != "") {
	    	$.ajax({
				url: 'candidates/trigger_sms_email_again',
				type: 'post',
				data: {send_id:id,'check_type':check},
				dataType: 'json',
				beforeSend: function() {
					$(this).text('sending...').attr('disabled','disabled');
				},
				complete: function() {
					$(this).text('Send').removeAttr('disabled');
				},
				success: function(jdata) {
					if(jdata.status == 200) {
						show_alert(jdata.message, 'success');
					} else {
						show_alert(jdata.message, 'danger');
					}
				},
				error: function (jqXHR, exception) {
					show_alert(jqXHR, 'danger');
				}
			});
	    }
	}
	else {
		show_alert('cancelled','info');
	}	    
});