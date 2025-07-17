<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
<body>

  <div id="wrapper">
    <div class="content-pagea">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12"></div>
          </div>

          <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">  
                  <button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= VENDOR_EXECUTIVE_SITE_URL?>addrver_exe/addrver_exe_wip" style="float: right;"><i class="fas fa-arrow-left"></i> Back</button>
                <br><br>

            <div class="m-t-40 text-left">
              <div id="location_access">
                  <p  class="error"> Location services not enabled. Refresh the page once done.  </p>
              </div>
            </div>

              <?php echo form_open_multipart(VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/vendor_submit_details', array('name'=>'frm_vendor_details','id'=>'frm_vendor_details')); ?>
                
                <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('transaction_id',$details['trasaction_id']); ?>" class="form-control">

                <input type="hidden" name="status_redirect" id="status_redirect" value="<?php echo set_value('status_redirect',$status_redirect); ?>" class="form-control">


                <input type="hidden" name="t_id" id="t_id" value="<?php echo set_value('t_id',$details['id']); ?>" class="form-control">

                  <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                      <label>Check Status<span class="error"> *</span></label>
                      <?php

                      $check_status = array('wip'=> 'WIP','candidate shifted'=>'Candidate Shifted','unable to verify'=>'Unable to Verify','denied verification'=>'Denied Verification','resigned'=>'Resigned','candidate not responding'=>'Candidate not Responding','clear'=>'Clear');
                        echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select status" id="status"');
                        echo form_error('status');
                      ?>
                    </div>
                  </div>

             <!--   <hr>
                <?php
                $street_address = $details['address'].','.$details['city'].','.$details['pincode'].','.$details['state'];
                 
                if(strpos($details['stay_from'],"-") == 4)
                {
                   
                   $stay_from  = date("d-m-Y", strtotime($details['stay_from']));
                }
                else
                {
                   $stay_from  =  $details['stay_from'];
                }

                if(strpos($details['stay_to'],"-") == 4)
                {
                   
                   $stay_to  = date("d-m-Y", strtotime($details['stay_to']));
                }
                else
                {
                   $stay_to  = $details['stay_to'];
                }

                ?>-->

                <div class="row">
                  
                    <div class="col-sm-4 form-group">
                      <label >Remarks<span class="error"> *</span></label>
                      <textarea name="remarks" id="remarks" rows="2" class="form-control"></textarea>
                      <?php echo form_error('remarks'); ?>
                    </div>
                   
                </div>  

              <!--  <div class="address_form" style="display: none;">  
                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label>Street Address<span class="error"> *</span></label>
                      <input type="text" name="address" id="address" value="<?php echo set_value('address',$street_address); ?>" class="form-control" readonly>
                      <?php echo form_error('address'); ?>
                    </div>
   
                    <div class="col-sm-4 form-group">
                      <label></label>
                      <br>
                      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="yes" <?php if(isset($details['address_action'])){ if($details['address_action']== 'yes') { echo 'checked';}} ?> >Yes </label>
                      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="no" <?php if(isset($details['address_action'])){ if($details['address_action'] == 'no') { echo 'checked';}} ?>>No</label>
                    </div>

                    <div class="col-sm-4 form-group" >
                      <input type="text" name="res_address" id="res_address" value="<?php echo set_value('res_address'); ?>" class="form-control" placeholder="Enter Street Address" style="display: none;">
                      <?php echo form_error('res_address'); ?>
                    </div>
                  </div>
   
  
                  <div class="row">
                      <div class="col-sm-4 form-group">
                        <label >Stay From <span class="error"> *</span></label>
                        <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$stay_from);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
                        <?php echo form_error('stay_from'); ?>
                      </div>
                      
                      <div class="col-sm-4 form-group">
                        <label></label>
                        <br>
                        <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="yes"  <?php if(isset($details['stay_from'])){ if(empty($stay_from)) { echo  'style="display: none;"'; }} ?> ><?php if(isset($details['stay_from'])){ if(!empty($stay_from)) { echo 'Yes'; }} ?> </label>
                        <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="no" <?php if(isset($details['stay_from'])){ if(empty($stay_from)) { echo 'checked';}} ?> >No</label>
                      </div>

                      <div class="col-sm-4 form-group" >
                        
                        <input type="text" name="res_stay_from" id="res_stay_from" value="<?php echo set_value('res_stay_from'); ?>" class="form-control" placeholder="Enter Stay From" <?php if(isset($details['stay_from'])){ if(!empty($stay_from)) { echo  'style="display: none;"'; }} ?>>
                        <?php echo form_error('res_stay_from'); ?>
                      </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label >Stay To <span class="error"> *</span></label>
                      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to',$stay_to);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
                      <?php echo form_error('stay_to'); ?>
                    </div>
                 
                    <div class="col-sm-4 form-group">
                      <label></label>
                      <br>
                      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="yes" <?php if(isset($details['stay_to'])){ if(empty($stay_to)) { echo  'style="display: none;"'; }} ?> ><?php if(isset($details['stay_to'])){ if(!empty($stay_to)) { echo 'Yes'; }} ?></label>
                      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="no" <?php if(isset($details['stay_to'])){ if(empty($stay_to)) { echo 'checked';}} ?>>No</label>
                    </div>

                    <div class="col-sm-4 form-group" >
                      <input type="text" name="res_stay_to" id="res_stay_to" placeholder="Enter Stay To" value="<?php echo set_value('res_stay_to'); ?>" class="form-control" <?php if(isset($details['stay_to'])){ if(!empty($stay_to)) { echo  'style="display: none;"'; }} ?>>
                      <?php echo form_error('res_stay_to'); ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label> Mode of verification<span class="error"> *</span></label>
                      <?php
                        $mode_of_verification = array('personal visit'=> 'Personal Visit');

                        echo form_dropdown('mode_of_verification', $mode_of_verification, set_value('mode_of_verification'), 'class="custom-select" id="mode_of_verification"');
                        echo form_error('mode_of_verification');
                      ?>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label> Resident Status<span class="error"> *</span></label>
                      <?php
                      $resident_status = array(''=> 'Select Resident Status','rented'=> 'Rented','rwned'=>'Owned','pg'=>'PG','relatives'=>'Relatives','government quarter'=>'Government Quarter','private quarter'=>'Private Quarter','hostel'=>'Hostel','others'=>'Others');
                        echo form_dropdown('resident_status', $resident_status, set_value('resident_status'), 'class="custom-select" id="resident_status"');
                        echo form_error('resident_status');
                      ?>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label >Landmark</label>
                      <textarea name="landmark" id="landmark" rows="1" class="form-control add_res_landmark"></textarea>
                      <?php echo form_error('landmark'); ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label >Met with<span class="error"> *</span></label>
                      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by');?>" class="form-control">
                      <?php echo form_error('verified_by'); ?>
                    </div>

                    <div class="col-sm-4 form-group">
                      <label >Neighbour 1</label>
                      <input type="text" name="neighbour_1" id="neighbour_1" value="<?php echo set_value('neighbour_1');?>" class="form-control">
                      <?php echo form_error('neighbour_1'); ?>
                    </div>
                    <div class="col-sm-4 form-group">
                      <label >Neighbour Details 1</label>
                      <input type="text" name="neighbour_details_1" id="neighbour_details_1" value="<?php echo set_value('neighbour_details_1');?>" class="form-control">
                      <?php echo form_error('neighbour_details_1'); ?>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-sm-4 form-group">
                      <label >Neighbour 2</label>
                      <input type="text" name="neighbour_2" id="neighbour_2" value="<?php echo set_value('neighbour_2');?>" class="form-control">
                      <?php echo form_error('neighbour_2'); ?>
                    </div>

                    
                    <div class="col-sm-4 form-group">
                      <label >Neighbour Details 2</label>
                      <input type="text" name="neighbour_details_2" id="neighbour_details_2" value="<?php echo set_value('neighbour_details_2');?>" class="form-control">
                      <?php echo form_error('neighbour_details_2'); ?>
                    </div>
                    
                    <div class="col-sm-4 form-group">
                      <label >Addr. Proof Collected<span class="error"> *</span></label>
                 
                      <?php
                        $addr_proof_collected = array(''=> 'Select Address Proof','aadhar card'=> 'Aadhar Card','ration card'=>'Ration Card','electricity bill '=>'Electricity Bill','voter id'=> 'Voter ID','driving license'=> 'Driving License','others'=> 'Others','none'=> 'None');

                        echo form_dropdown('addr_proof_collected', $addr_proof_collected, set_value('addr_proof_collected'), 'class="custom-select" id="addr_proof_collected"');
                        echo form_error('addr_proof_collected');
                      ?>
                      
                      
                    </div>
                 </div>
              </div>-->

  

            
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Address Form </b><span class="error" id= 'add_fr'></span></label>
                        <input type="file" name="address_forms" id="address_forms" accept="image/*" capture="camera" class="form-control border-input" value="" onchange="load_file_address_form(event)">
                    </div>
                    <input type="hidden" name="address_forms_lat_long" id="address_forms_lat_long" value="">
                  </div> 

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="address_form_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                   
                  </div> 

                  <div class="col-md-3 aadhar_card_front" style="display: none;">
               
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Address proof (Front Side) </b><span class="error" id= 'add_pf'></span></label>
                      <input type="file" name="address_proof_front" id="address_proof_front" accept="image/*" capture="camera" class="form-control border-input" value="" onchange="load_aadhar_card_front(event)">
                    </div>
                    <input type="hidden" name="address_proof_front_lat_long" id="address_proof_front_lat_long" value="">
                  </div>


                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="address_proof_front_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                   
                  </div>
                                           
                  <div class="col-md-3 aadhar_card_back" style="display: none;">
              
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Address proof (Back Side) </b><span class="error" id= 'add_pb'></span></label>
                        <input type="file" name="address_proof_back" id="address_proof_back" accept="image/*" capture="camera" class="form-control border-input" value=""  onchange="load_aadhar_card_back(event)">
                    </div>
                    <input type="hidden" name="address_proof_back_lat_long" id="address_proof_back_lat_long" value="">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="address_proof_back_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                   
                  </div>
                                          
              <!--    <div class="col-md-3 aadhar_card_other" style="display: none;">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Address proof </b><span class="error add_proof" id= 'add_p'></span></label>
                        <input type="file" name="address_proof" id="address_proof" accept="image/*" capture="camera" class="form-control border-input" value="">
                    </div>
                    <input type="hidden" name="address_proof_lat_long" id="address_proof_lat_long" value="">
                  </div>-->
                                           
                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Door/Gate photo </b><span class="error" id= 'door'></span></label>
                        <br>
                      <input type="file" name="house_pic_door" id="house_pic_door" accept="image/*" capture="camera" class="form-control border-input" value="" onchange="load_house_pic_door(event)">
                    </div>
                    <input type="hidden" name="house_pic_door_lat_long" id="house_pic_door_lat_long" value="">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="house_pic_door_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                   
                  </div> 
                                    
                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Location Image 1 (Locality photo)</b><span class="error" id= 'location1'></span></label>
                      <input type="file" name="location_picture_1" id="location_pictures_1" accept="image/*" capture="camera" class="form-control border-input" value=""  onchange="load_location_pictures_1(event)">
                      </div>
                    <input type="hidden" name="location_picture_1_lat_long" id="location_pictures_1_lat_long" value="">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="location_picture_1_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                  </div> 
                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Location Image 2 (Locality photo)</b><span class="error"  id= 'location2'></span></label>
                      <input type="file" name="location_picture_2" id="location_pictures_2" accept="image/*" capture="camera" class="form-control border-input" value="" onchange="load_location_pictures_2(event)">
                    </div>
                    <input type="hidden" name="location_picture_2_lat_long" id="location_pictures_2_lat_long" value="">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="location_picture_2_preview"  height="100px" width="100px" style="display:none" />
                    </div>
                  </div>
                                         
                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Location Image 3 (Locality photo)</b><span class="error" id= 'location3'></span></label>
                        <input type="file" name="location_picture_3" id="location_pictures_3" accept="image/*" capture="camera" class="form-control border-input" value=""  onchange="load_location_pictures_3(event)">
                        <input type="hidden" name="location_picture_3_lat_long" id="location_pictures_3_lat_long" value="">
                    </div>                            
                  </div> 

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="location_picture_3_preview"  height="100px" width="100px" style="display:none"  />
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Other</b><span class="error" id= 'other_ast'></span></label>
                        <input type="file" name="other" id="other" accept="image/*" capture="camera" class="form-control border-input" value=""  onchange="load_other(event)">
                        <input type="hidden" name="other_lat_long" id="other_lat_long" value="">
                    </div>                            
                  </div> 

                  <div class="col-md-3">
                    <div class="form-group">
                        <img id="other_preview"  height="100px" width="100px" style="display:none"  />
                    </div>
                  </div>


               <!--   <div class="col-md-12 signature_display" >
                    <div class="form-group">
                      <label style="color: #2b4664"><b>Signature </b><span class="error">*</span></label>
                        <div class="wrapper">
                          <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
                        </div>

                          <input type="hidden" name="signature" id="signature" capture class="form-control border-input" value=""> 
                          <input type="hidden" name="signature_lat_long" id="signature_lat_long" value="">
                                                   
                    </div>
                                            
                    <div class="sig_buttons">
                      <button id="clear" type="button">Clear</button>
                       <span class="signature_done error"></span>
                    </div>
                  </div> -->                          
                                          
                </div>
              
               <hr> 

              <input type="hidden" name="add_com_ref" id="add_com_ref" value="<?php echo set_value('add_com_ref',$details['add_com_ref']);?>">
              <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($details['iniated_date']));?>">
              <input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['candiates_name']);?>">
              <input type="hidden" name="CandidatesContactNumber" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$details['CandidatesContactNumber']);?>">
              <input type="hidden" name="latitude" id="latitude">
              <input type="hidden" name="longitude" id="longitude">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit"  id="btn_vendor_submit_details" name="btn_vendor_submit_details" class="btn btn-info btn-fill btn-wd" disabled="disabled"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Submit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
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
  </div>
</div>                           
<!--  <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
  <script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAU_tcKKvL8NP97duFTiS4-Urc6S1JKJ4g&#038;ver=1"></script>

  <script type="text/javascript">
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
</script>  -->

<script type="text/javascript">
  $(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1)  
    var actual_value=document.getElementById(nameArr).value;

    var rtb_val = $(this).val();

    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
      $("#"+txt_val).removeAttr("readonly");
      $("#"+txt_val).css("display",'block');
    }
    else if(rtb_val == 'yes'){
      $("#"+txt_val).val(actual_value);
      $("#"+txt_val).attr("readonly","true");
      $("#"+txt_val).css("display",'none');
    } 

});

$('#status').on('change', function() {
    var status = $(this).val();
    
    if(status == "wip" || status == "candidate shifted" || status == "unable to verify" || status == "denied verification" || status == "resigned" ||   status == "candidate not responding")
    {
        $("#add_pf").html('');
        $("#add_pb").html('');
        $("#add_p").html('');
        $("#door").html('');
        $("#location1").html('');
        $("#location2").html('');
        $("#location3").html('');
        $("#other_ast").html('');
        $("#add_fr").html('');
        $(".aadhar_card_front").css("display",'none');
        $(".aadhar_card_back").css("display",'none');
        $(".address_form").css("display",'none');
        $(".signature_display").css("display",'none');
        

    }
    else if(status == "clear")
    {
        $("#add_pf").html('*');
     //   $("#add_pb").html('*');
        $("#add_p").html('*');
        $("#door").html('*');
        $("#location1").html('*');
        $("#location2").html('*');
        $("#location3").html('*');
        $("#other_ast").html('*');
        $("#add_fr").html('*');
        $(".aadhar_card_front").css("display",'block');
        $(".aadhar_card_back").css("display",'block');
        $(".address_form").css("display",'block');
        $(".signature_display").css("display",'block');
    }

});
/*
$('#addr_proof_collected').on('change', function() {
    var addr_proof = $(this).val();
    
    if(addr_proof == "aadhar card")
    {
        $(".aadhar_card_front").css("display",'block');
        $(".aadhar_card_back").css("display",'block');
        $(".aadhar_card_other").css("display",'none');
    }
    else if(addr_proof == "none")
    {
        $(".aadhar_card_other").css("display",'none');
        $(".aadhar_card_front").css("display",'none');
        $(".aadhar_card_back").css("display",'none');
    }
    else
    {
        $(".add_proof").html('( '+addr_proof+' ) *');
        $(".aadhar_card_other").css("display",'block');
        $(".aadhar_card_front").css("display",'none');
        $(".aadhar_card_back").css("display",'none');
    }

});

*/

$(document).ready(function() {
    $('#frm_vendor_details').validate({
        rules: {
            transaction_id : {
                required : true
            }, 
            status : {
                required : true
            },
            remarks : {
                required : true
            },
            /*address_action : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
            stay_from_action : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
            stay_to_action : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
         
            mode_of_verification : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
            addr_proof_collected : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
             resident_status : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },
            verified_by : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true;
                  }
                }

              }
            },*/
            address_proof_front : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                 //   if( $('#addr_proof_collected').val() == "aadhar card"){
                      return true;
                  //  }
                  }
                }

              }
            },
            address_forms : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                  
                      return true;
                    
                  }
                }

              }
            },
           /* address_proof_back : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    if( $('#addr_proof_collected').val() == "aadhar card"){
                      return true;
                    }
                  }
                }

              }
            },
            address_proof : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    if( $('#addr_proof_collected').val() != "aadhar card"){
                      return true;
                    }
                  }
                }

              }
            },*/
            
            house_pic_door : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true; 
                  }
                }

              }
            },
            location_picture_1 : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true; 
                  }
                }

              }
            },
            location_picture_2 : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true; 
                  }
                }

              }
            },
            location_picture_3 : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true; 
                  }
                }

              }
            },

            other : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return true; 
                  }
                }

              }
            }


        },
        messages: {
            transaction_id : {
                 required : "Tansaction ID Required"
            },
            status : {
                required : "Please Select Status"
            },
            remarks : {
                required : "Please enter remark"
            },
           /* verified_by : {
              required : {
                depends: function () {
                  if( $('#status').val() == "clear"){
                    return "Please Enter Verifiers"
                  }
                }

              }
            }*/
        },
        submitHandler: function(form) {
          
          /*var status = $('#status').val();

          if(status == 'clear')
          {

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
      

                    url: $('#frm_vendor_details').attr('action'),
                    type: 'post',
                    data: new FormData(form),
                    dataType: 'json',
                    contentType:false,
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend: function() {
                        $('.not_refresh').show();
                        $('#btn_vendor_submit_details').html('<i class="fas fa-spinner fa-spin"></i> please wait...');
                        $('#btn_vendor_submit_details').attr('disabled','disabled');
                    },
                    complete: function() {
                        $('#btn_vendor_submit_details').html('Submit');
                        $('#btn_vendor_submit_details').removeAttr('disabled');
                    },
                    success: function(jdata) {
                       // alert(jdata.message);
                        
                        if(jdata.status == 200) {
                            show_alert(jdata.message, 'success');
                            $('#frm_vendor_details')[0].reset();
                            window.location.href= jdata.redirect;
                            return;
                        } else {
                            show_alert(jdata.message, 'danger');
                        }
                    }
                });
            } 
          }
          else
          {  
*/
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
      

                    url: $('#frm_vendor_details').attr('action'),
                    type: 'post',
                    data: new FormData(form),
                    dataType: 'json',
                    contentType:false,
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend: function() {
                        $('.not_refresh').show();
                        $('#btn_vendor_submit_details').html('<i class="fa fa-spinner fa-spin"></i> please wait...');
                        $('#btn_vendor_submit_details').attr('disabled','disabled');
                    },
                    complete: function() {
                        $('#btn_vendor_submit_details').html('Submit');
                        $('#btn_vendor_submit_details').removeAttr('disabled');
                    },
                    success: function(jdata) {
                       // alert(jdata.message);
                        
                        if(jdata.status == 200) {
                            show_alert(jdata.message, 'success');
                            $('#frm_vendor_details')[0].reset();
                            window.location.href= jdata.redirect;
                            return;
                        } else {
                            show_alert(jdata.message, 'danger');
                        }
                    }
                });
         // }
        }
    });
});

</script>
<script type="text/javascript">
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
    $('#location_access').text('');
    $('#btn_vendor_submit_details').removeAttr('disabled');
    
    
}
function showError(error) {
    
    let err = null;
    switch(error.code) {
        case error.PERMISSION_DENIED:
            err = "Enable location to proceed"
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
    if(err != null)
    {
      alert(err);

      if('<?php echo $status_redirect  ?>' ==  "current")
      {
         window.location = '<?php echo VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_exe_'.$status_redirect; ?>';
      }
      else if('<?php echo $status_redirect  ?>' ==  "wip")
      {
         window.location = '<?php echo VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_exe_'.$status_redirect; ?>';
      }
      else if('<?php echo $status_redirect  ?>' ==  "insufficiency")
      {
         window.location = '<?php echo VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_'.$status_redirect; ?>';
      }
      else if('<?php echo $status_redirect  ?>' ==  "closed")
      {
         window.location = '<?php echo VENDOR_EXECUTIVE_SITE_URL.'addrver_exe/addrver_'.$status_redirect; ?>';
      }
    }
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



  var load_file_address_form = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#address_form_preview').css('display','block');
      var address_form_preview = document.getElementById('address_form_preview');
      address_form_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

  var load_aadhar_card_front = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#address_proof_front_preview').css('display','block');
      var address_proof_front_preview = document.getElementById('address_proof_front_preview');
      address_proof_front_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };


var load_aadhar_card_back = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#address_proof_back_preview').css('display','block');
      var address_proof_back_preview = document.getElementById('address_proof_back_preview');
      address_proof_back_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };


   var load_house_pic_door = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#house_pic_door_preview').css('display','block');
      var house_pic_door_preview = document.getElementById('house_pic_door_preview');
      house_pic_door_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

   var load_location_pictures_1 = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#location_picture_1_preview').css('display','block');
      var location_picture_1_preview = document.getElementById('location_picture_1_preview');
      location_picture_1_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

   var load_location_pictures_2 = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#location_picture_2_preview').css('display','block');
      var location_picture_2_preview = document.getElementById('location_picture_2_preview');
      location_picture_2_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

   var load_location_pictures_3 = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#location_picture_3_preview').css('display','block');
      var location_picture_3_preview = document.getElementById('location_picture_3_preview');
      location_picture_3_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };

   var load_other = function(event) {
    var reader = new FileReader();
    reader.onload = function(){
      $('#other_preview').css('display','block');
      var other_preview = document.getElementById('other_preview');
      other_preview.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  };


document.getElementById('btn').addEventListener('click', () => { 
  document.getElementById('input').click();
})
</script>




