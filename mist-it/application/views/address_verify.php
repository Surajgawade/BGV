<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title><?php echo isset($header_title) ? $header_title : 'MIST IT Services'; ?></title>
        <link rel="icon" href="<?php echo SITE_IMAGES_URL; ?>favicon.ico" type="image/ico" />
        
        <link href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo SITE_CSS_URL; ?>metismenu.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo SITE_CSS_URL;?>icons.css" rel="stylesheet" type="text/css">
        <link href="<?php echo SITE_CSS_URL;?>style.css" rel="stylesheet" type="text/css">

        <style type="text/css">
        .sig_buttons {
            margin-left: 30px;
        }
        .wrapper {
            position: relative;
            width: 100%;
            height: 200px;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .signature-pad {
            border: 1px solid;
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            height:200px;
            background-color: white;
        }
        </style>

      
        <script src="<?php echo SITE_JS_URL?>jquery.min.js"></script>
        <script src="<?php echo SITE_JS_URL?>jquery.validate.min.js"></script>
        <script src="<?php echo SITE_JS_URL?>bootstrap.bundle.min.js"></script>
        <base href="<?php echo SITE_URL?>">
       
        
    </head>

    <body>
    <div class="loader" style="display: none;">Loading&#8230;</div>
    <div id="wrapper">
        <div class="content-pagea">
           
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-20">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">Address Verification Details</h4><hr>
                                    <?php echo form_open_multipart(SITE_URL.'addressverify/address_update_details', array('name'=>'frm_candidates_update','id'=>'frm_candidates_update')); ?>
                                        <input type="text" name="latitude" id="latitude" value="<?php echo $latitude; ?>" readonly>
                                        <input type="text" name="longitude" id="longitude" value="<?php echo $longitude; ?>" readonly>

                                        <input type="hidden" name="address_id" id="address_id" value= "<?php echo  $details['id']; ?>">

                                        <input type="hidden" name="clientid" id="clientid" value= "<?php echo  $details['clientid']; ?>">
                                        <input type="hidden" name="cands_id" id="cands_id" value= "<?php echo  $details['candsid']; ?>">
                                      
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" readonly="readonly" name="candidate_name" id="candidate_name" class="form-control border-input" placeholder="Full Name" value="<?php echo $details['CandidateName'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Email ID</label>
                                                    <input type="text" readonly="readonly" name="email_id" id="email_id" class="form-control border-input" placeholder="Email ID" value="<?php echo $details['candidate_email'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" readonly="readonly" name="mobile_1" id="mobile_1" maxlength="10" minlength="10" class="form-control border-input" placeholder="Mobile Number" value="<?php echo $details['candidate_contact'] ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Address <span class="error">*</span></label>
                                                    <textarea name="address_edit" maxlength="250" id="address_edit" class="form-control border-input" readonly="readonly" rows="1"><?php echo $details['address'] ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Stay Type<span class="error">*</span></label>
                                                    <?php

                                                        $resident_status = array(''=> 'Select Resident Status','rented'=> 'Rented','rwned'=>'Owned','pg'=>'PG','relatives'=>'Relatives','government quarter'=>'Government Quarter','private quarter'=>'Private Quarter','hostel'=>'Hostel','others'=>'Others');

                                                        echo form_dropdown('nature_of_residence', $resident_status, set_value('nature_of_residence'), 'class="form-control" id="nature_of_residence"');
                                                        echo form_error('nature_of_residence');
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Stay From <span class="error">*</span></label>
                                                    <input type="text" name="period_stay" id="period_stay" class="form-control border-input" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Stay To <span class="error">*</span></label>
                                                    <input type="text" name="period_to" id="period_to" class="form-control border-input" value="" max="<?php echo date("Y-m-d")?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Verified By <span class="error">*</span></label>
                                                    <?php
                                                        $verifies_name = array("" => "Select Verifier Name","Self" => "Self","Others" => "Others");
                                                        echo form_dropdown('verifier_name', $verifies_name, set_value('verifier_name'),'class="form-control" id="verifier_name"');
                                                        echo form_error('verifier_name');
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-3 relation_with_cands">
                                                <div class="form-group">
                                                    <label>Relation with candidate</label>
                                                    <input type="text" maxlength="100" name="relation_verifier_name" id="relation_verifier_name" class="form-control border-input" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Nearest Landmark </label>
                                                    <input type="text" maxlength="250" name="candidate_remarks" id="candidate_remarks" class="form-control border-input" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="mt-0 header-title">Upload Documents</h4><hr>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Selfie </b><span class="error">*</span></label>
                                                    <input type="file" name="selfie" id="selfie" accept="image/*" capture="camera" class="form-control border-input" value="">
                                                </div>
                                                <input type="hidden" name="selfie_lat_long" id="selfie_lat_long" value="">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Address proof (Electricity/ Aadhar/Voters card, etc) </b><span class="error">*</span></label>
                                                    <input type="file" name="address_proof" id="address_proof" accept="image/*" capture="camera" class="form-control border-input" value="">
                                                </div>
                                                <input type="hidden" name="address_proof_lat_long" id="address_proof_lat_long" value="">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Door/Gate photo </b><span class="error">*</span></label>
                                                    <input type="file" name="house_pic_door" id="house_pic_door" accept="image/*" capture="camera" class="form-control border-input" value="">
                                                    <input type="hidden" name="house_pic_door_lat_long" id="house_pic_door_lat_long" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Location Image 1 (Locality photo)</b><span class="error">*</span></label>
                                                    <input type="file" name="location_picture_1" id="location_pictures_1" accept="image/*" capture="camera" class="form-control border-input" value="">
                                                    <input type="hidden" name="location_picture_1_lat_long" id="location_pictures_1_lat_long" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Location Image 2 (Locality photo)</b><span class="error">*</span></label>
                                                    <input type="file" name="location_picture_2" id="location_pictures_2" accept="image/*" capture="camera" class="form-control border-input" value="">
                                                    <input type="hidden" name="location_picture_2_lat_long" id="location_pictures_2_lat_long" value="">
                                                </div>
                                            </div>

                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label style="color: #2b4664"><b>Signature </b><span class="error">*</span></label>
                                                    <div class="wrapper">
                                                        <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
                                                    </div>

                                                    <input type="hidden" name="signature" id="signature" capture class="form-control border-input" value=""> 
                                                    <input type="hidden" name="signature_lat_long" id="signature_lat_long" value="<?php echo $latitude; ?>,<?php echo $longitude; ?>">
                                                   
                                                </div>
                                            
                                                <div class="sig_buttons">
                                                   
                                                    <button id="clear" type="button">Clear</button>
                                                    <span class="signature_done error"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit"  id="btn_candidate_submit" name="btn_candidate_submit" class="btn btn-info btn-fill btn-wd"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="progress">
                                        <div class="progress-bar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                        0%
                                        </div>
                                        </div>
                                        <br />
                                        <div class='not_refresh error' style='display:none;'>
                                            <p>Please do not refresh the page,</p>
                                            <p>it takes time to upload the documents.</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    <?php echo form_close(); ?><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                &copy; <?php echo  date('Y'); ?> MIST IT Services. All Rights Reserved.
            </footer>
        </div>
    </div>
    
</body>
  <!--  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://keith-wood.name/js/jquery.signature.js"></script>-->
      
    <script src="<?php echo SITE_JS_URL?>metisMenu.min.js"></script>
    <script src="<?php echo SITE_JS_URL?>jquery.slimscroll.js"></script>
    <script src="<?php echo SITE_JS_URL?>waves.min.js"></script>
    <script src="<?php echo SITE_PLUGINS_URL?>jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo SITE_JS_URL?>bootstrap-notify.js"></script>
    <script src="<?php echo SITE_JS_URL?>app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAU_tcKKvL8NP97duFTiS4-Urc6S1JKJ4g&#038;ver=1"></script>
    <script src="<?php echo SITE_JS_URL?>candidate_profile_open.js"></script>
    <script type="text/javascript">

    $(function(){
        //$('#signature-pad').signature({syncField: '#signature', syncFormat: 'PNG'});
     
      
        $('#verifier_name').on('change',function(){
             
            var verifier_name  = $(this).val();
            if(verifier_name=="Self"){
                $(".relation_with_cands").slideUp(600);
                $("#relation_verifier_name").val("Self");
            }else{
                $(".relation_with_cands").slideDown(600);
                $("#relation_verifier_name").val("");
            }
        });
      
        show_alert = function(content, alert_type) {
            if(content) {
                $.notify(content, alert_type);
            }
        }
    });
     // $("#verifier_name").keyup(function() {
        
    </script>
</html>

