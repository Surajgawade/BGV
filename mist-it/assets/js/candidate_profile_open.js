var canvas = document.getElementById('signature-pad');
function resizeCanvas() {

    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

}

//window.onresize = resizeCanvas;
resizeCanvas();

var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)'
});

document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
    $("#signature").val('');
    $('.signature_done').text('');
});

/*document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
    $('.signature_done').text('');
});
$('#signature_upload').on('click',function(){
    if (signaturePad.isEmpty()) {
        alert("Please provide a signature first.");
        return false;
    }
    var data = signaturePad.toDataURL('image/png');
    var input = document.getElementById('signature');
    input.value = data;
    $('.signature_done').text('Done');
});*/


$.validator.addMethod("extension", function(value, element, param) {
  
    param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
    return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, $.validator.format("Please enter a value with a valid extension."));
$(document).ready(function() {
    $('#frm_candidates_update').validate({
        rules: {
            address_edit : {
                required : true
            },
            nature_of_residence : {
                required : true
            },
            period_stay : {
                required : true
            },
            period_to : {
                required : true
            },
            verifier_name : {
                required : true
            },
           
            selfie : { 
                required : true,
                extension : 'jpg|png'
            },
            address_proof : { 
                required : true,
                extension : 'jpg|png'
            },
            location_picture_1 : { 
                required : true,
                extension : 'jpg|png'
            },
            location_picture_2 : { 
                required : true,
                extension : 'jpg|png'
            },
            house_pic_door : { 
                required : true,
                extension : 'jpg|png'
            }
         /*   signature : { 
                required : true
            }*/
        },
        messages: {
            address_edit : {
                required : "Please enter address"
            },
            nature_of_residence : {
                required : "Please enter address type"
            },
            period_stay : {
                required : "Please select stay from "
            },
            period_to : {
                required : "Please select stay to "
            },
            verifier_name : {
                required : "Please enter verifier name"
            },
            
            selfie : { 
                required : "Please upload selfie",
                extension : 'Allowed jpg|png Only'
            },
            address_proof : { 
                required : "Please upload address proof",
                extension : 'Allowed jpg|png Only'
            },
            location_picture_1 : { 
                required : "Please upload location Picture",
                extension : 'Allowed jpg|png Only'
            },
            location_picture_2 : { 
                required : "Please upload location Picture",
                extension : 'Allowed jpg|png Only'
            },
            house_pic_door : { 
                required : "Please upload house door Picture",
                extension : 'Allowed jpg|png Only'
            }
           /* signature : { 
                required : "Please upload Signature"
            }*/
        },
        submitHandler: function(form) {
            
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
         
            if((latitude.length == 0)  && (longitude.length == 0))
            {
              
                show_alert("Please refresh page and try again.",'error');
                return false;
            }
            else{

                if (signaturePad.isEmpty()) {
                   show_alert("Please provide a signature first.",'error');
                   return false;
                } 
                else{

                    var data = signaturePad.toDataURL('image/png');
                    var input = document.getElementById('signature');
                    input.value = data;

                    $.ajax({

                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            var progressBar = $(".progress-bar");
                            //Upload progress
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = (evt.loaded/evt.total)*100;
                                    percentComplete = Math.floor(percentComplete);
                                    console.log(percentComplete);
                                    progressBar.css("width", percentComplete + "%");
                                    progressBar.html(percentComplete+'%');
                                }
                            }, false);
                        return xhr;
                        },
        
                        url: $('#frm_candidates_update').attr('action'),
                        type: 'post',
                        data: new FormData(form),
                        dataType: 'json',
                        contentType:false,
                        cache: false,
                        processData:false,
                        dataType:'json',
                        beforeSend: function() {
                            $('.not_refresh').show();
                            $('#btn_candidate_submit').html('<i class="fa fa-spinner fa-spin"></i> please wait...');
                            $('#btn_candidate_submit').attr('disabled','disabled');
                        },
                        complete: function() {
                            $('#btn_candidate_submit').html('Submit');
                            $('#btn_candidate_submit').removeAttr('disabled');
                        },
                        success: function(jdata) {
                           // alert(jdata.message);
                            
                            if(jdata.status == 200) {
                                show_alert(jdata.message, 'success');
                                $('#frm_candidates_update')[0].reset();
                                window.location.href= jdata.redirect;
                                return;
                            } else {
                                show_alert(jdata.message, 'danger');
                            }
                        }
                    });
                } 
            }
        }
    });
});

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
    $('#location_access').text('');
    $('#btn_candidate_submit').removeAttr('disabled');
}
function showError(error) {
    
    let err = null;
    switch(error.code) {
        case error.PERMISSION_DENIED:
            err = "Please allow location to find your address"
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
    return;
}
var current_file = '';
$(function() {
    $("input:file").change(function (){
        var fileName = $(this).attr('id');
        current_file = fileName;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(current_position);
        }
    });
});

function current_position(position) {
    var lat_long = position.coords.latitude+','+position.coords.longitude;
    $('#'+current_file+'_lat_long').val(lat_long)
}