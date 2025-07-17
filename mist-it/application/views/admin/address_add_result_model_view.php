<div class="modal-body">


  <?php 
 if(($url == "OverseasCheck") || ($url == "StopCheck") ||  ($url == "UnableToVerify") || ($url == "ChangeOfAddress"))
 {
   if($url == "OverseasCheck")
   { 
      $details['remarks'] = "Site visit not conducted since it is an Overseas Check.";
   }
   if($url == "StopCheck")
   { 
      $details['remarks'] = "Communication received from client requesting to Stop Check.";
   }
  
  
 }

 if(($url == "OverseasCheck") || ($url == "StopCheck") || ($url == "NA") || ($url == "UnableToVerify") || ($url == "ChangeOfAddress"))
   {
     $css_style = 'style="display: none;"';

      echo '<input type="radio" class="rto_clicked" name="address_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="city_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="pincode_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="state_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="stay_from_action" checked value="" style="display:none">';
      echo '<input type="radio" class="rto_clicked" name="stay_to_action" checked value="" style="display:none">';
   }
   else
   {
     $css_style = '';
   }

  ?>


  <?php
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

?>

  <div class="result_error" id="result_error"></div>
  
    <div <?php echo $css_style; ?>>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Information Provided</label>
    </div>
    <div class="col-sm-4 form-group">
      <label>Information Verified</label>
    </div>
    <div class="col-sm-4 form-group">
      <label>Action</label>
    </div>

   <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Address Type</label>
      <?php
        echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type',$details['address_type']), 'class="form-control" id="address_type"');
        echo form_error('address_type');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Address Type</label>
      <?php
        echo form_dropdown('res_address_type', ADDRESS_TYPE, set_value('res_address_type',$details['res_address_type']), 'class="form-control" id="res_address_type"');
        echo form_error('res_address_typ');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="yes">Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="no" checked>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="not-verified">Not Disclosed</label>
    </div>-->
   </div>
   <div class="row">
    <div class="col-sm-4 form-group">
      <label>Street Address</label>
      <input type="text" name="address" id="address" value="<?php echo set_value('address',$details['address']); ?>" class="form-control" readonly>
      <?php echo form_error('address'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Street Address<span class="error"> *</span></label>

   
      <input type="text" name="res_address" id="res_address" value="<?php if(isset($res_address)){ echo $res_address;} else { echo set_value('res_address');} ?>" class="form-control" readonly>
      <?php echo form_error('res_address'); ?>
    </div>

    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="yes" <?php if(isset($details['address_action'])){ if($details['address_action']== 'yes') { echo 'checked';}} ?> >Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="no" <?php if(isset($details['address_action'])){ if($details['address_action'] == 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" data-val="res_address" value="not-verified" <?php if(isset($details['address_action'])){ if($details['address_action']== 'not-verified') {echo "checked='checked'";}} ?>>Not Verified</label>
    </div>
    </div>

   <div class="row">
    <div class="col-sm-4 form-group">
      <label>City</label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control" readonly>
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>City<span class="error"> *</span></label>
      <input type="text" name="res_city" id="res_city" value="<?php  if(isset($res_city)){ echo $res_city;} else { echo set_value('res_city');} ?>" class="form-control" readonly>
      <?php echo form_error('res_city'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action" data-val="res_city" value="yes" <?php if(isset($details['city_action'])){ if($details['city_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action" data-val="res_city" value="no" <?php if(isset($details['city_action'])){ if($details['city_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action" data-val="res_city" value="not-verified" <?php if(isset($details['city_action'])){ if($details['city_action']== 'not-verified') { echo 'checked';}} ?>>Not Verified</label>
    </div>
    </div>
    
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control" readonly>
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Pincode<span class="error"> *</span></label>
      <input type="text" name="res_pincode" id="res_pincode" value="<?php if(isset($res_pincode)){ echo $res_pincode;} else { echo set_value('res_pincode');} ?>" class="form-control" readonly>
      <?php echo form_error('res_pincode'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="yes"  <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="no" <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="not-verified" <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'not-verified') { echo 'checked';}} ?>>Not Verified</label>
    </div>
    </div>
     <div class="row">
    <div class="col-sm-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="select2 singleSelect cls_disabled" id="state" disabled');
        echo form_error('state');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>State<span class="error"> *</span></label>
       <!-- <?php
          echo form_dropdown('res_state', $states, set_value('res_state'), 'class="form-control singleSelect cls_disabled" id="res_state"');
          echo form_error('res_state');
        ?>-->
        <input type="text" name="res_state" id="res_state" value="<?php if(isset($res_state)){ echo $res_state;} else { echo set_value('res_state');}?>" class="form-control" readonly>
    </div>
    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="yes" <?php if(isset($details['state_action'])){ if($details['state_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="no" <?php if(isset($details['state_action'])){ if($details['state_action']== 'no') { echo 'checked';}} ?> >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="not-verified" <?php if(isset($details['state_action'])){ if($details['state_action']== 'not-verified') { echo 'checked';}} ?>>Not Verified</label>
    </div>
    </div>
   <div class="row">
    <div class="col-sm-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$stay_from);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
      <?php echo form_error('stay_from'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="res_stay_from" id="res_stay_from" value="<?php if(isset($res_stay_from)){ echo $res_stay_from;} else { echo set_value('res_stay_from'); }?>" class="form-control" readonly>
      <?php echo form_error('res_stay_from'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="yes" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'yes') { echo 'checked';}} ?> >Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="no" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'no') { echo 'checked';}} ?> >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="not-verified" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'not-verified') { echo 'checked';}} ?> >Not Verified</label>
    </div>
    </div>

   <div class="row">
    <div class="col-sm-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to',$stay_to);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
      <?php echo form_error('stay_to'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="res_stay_to" id="res_stay_to" value="<?php if(isset($res_stay_to)){ echo $res_stay_to;} else { echo set_value('res_stay_to'); }?>" class="form-control" readonly>
      <?php echo form_error('res_stay_to'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="yes" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'yes') { echo 'checked';}} ?> >Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="no" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="not-verified" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'not-verified') { echo 'checked';}} ?>>Not Verified</label>
    </div>
   </div>
  <div class="row">
    <div class="col-sm-4 form-group">
      <label> Mode of verification<span class="error"> *</span></label>
      <?php
        $mode_of_verification = array('personal visit'=> 'Personal Visit','verbal'=>'Verbal','digital'=>'Digital');

        echo form_dropdown('mode_of_verification', $mode_of_verification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="select2" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label> Resident Status<span class="error"> *</span></label>
      <?php
      $resident_status = array(''=> 'Select Resident Status','rented'=> 'Rented','rwned'=>'Owned','pg'=>'PG','relatives'=>'Relatives','government quarter'=>'Government Quarter','private quarter'=>'Private Quarter','hostel'=>'Hostel','others'=>'Others');
        echo form_dropdown('resident_status', $resident_status, set_value('resident_status',$details['resident_status']), 'class="select2" id="resident_status"');
        echo form_error('resident_status');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label >Landmark</label>
      <textarea name="landmark" id="landmark" rows="1" class="form-control add_res_landmark"><?php echo set_value('landmark',$details['landmark']);?></textarea>
      <?php echo form_error('landmark'); ?>
    </div>
    </div>
  <div class="row">
    <div class="col-sm-4 form-group">
      <label >Verified By<span class="error"> *</span></label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>

    <div class="col-sm-4 form-group">
      <label >Neighbour 1</label>
      <input type="text" name="neighbour_1" id="neighbour_1" value="<?php echo set_value('neighbour_1',$details['neighbour_1']);?>" class="form-control">
      <?php echo form_error('neighbour_1'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label >Neighbour Details 1</label>
      <input type="text" name="neighbour_details_1" id="neighbour_details_1" value="<?php echo set_value('neighbour_details_1',$details['neighbour_details_1']);?>" class="form-control">
      <?php echo form_error('neighbour_details_1'); ?>
    </div>
  </div>
 <div class="row">
    <div class="col-sm-4 form-group">
      <label >Neighbour 2</label>
      <input type="text" name="neighbour_2" id="neighbour_2" value="<?php echo set_value('neighbour_2',$details['neighbour_2']);?>" class="form-control">
      <?php echo form_error('neighbour_2'); ?>
    </div>

    
    <div class="col-sm-4 form-group">
      <label >Neighbour Details 2</label>
      <input type="text" name="neighbour_details_2" id="neighbour_details_2" value="<?php echo set_value('neighbour_details_2',$details['neighbour_details_2']);?>" class="form-control">
      <?php echo form_error('neighbour_details_2'); ?>
    </div>
    
    <div class="col-sm-4 form-group">
      <label >Addr. Proof Collected<span class="error"> *</span></label>
      <!--<input type="text" name="addr_proof_collected" id="addr_proof_collected" value="<?php echo set_value('addr_proof_collected',$details['addr_proof_collected']);?>" class="form-control">-->
      <?php
        $addr_proof_collected = array(''=> 'Select Address Proof','aadhar card'=> 'Aadhar Card','ration card'=>'Ration Card','electricity bill '=>'Electricity Bill','voter id'=> 'Voter ID','driving license'=> 'Driving License','others'=> 'Others','none'=> 'None');

        echo form_dropdown('addr_proof_collected', $addr_proof_collected, set_value('addr_proof_collected',$details['addr_proof_collected']), 'class="select2" id="addr_proof_collected"');
        echo form_error('addr_proof_collected');
      ?>
      
      <!--<?php echo form_error('addr_proof_collected'); ?>-->
    </div>
  </div>

</div>

   <div class="row">
    <div class="col-sm-4 form-group">
      <label> Closure Date<span class="error"> *</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php if(!empty($details['closuredate'])) { echo set_value('closuredate',convert_db_to_display_date($details['closuredate'])); } else{ echo date('d-m-Y');} ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>


  <span id="demo"></span>
    <div class="col-sm-4 form-group">
      <label >Remarks<span class="error"> *</span></label>
      <textarea name="remarks" id="remarks" rows="1" class="form-control add_res_remarks add_rem_update"><?php echo set_value('remarks',$details['remarks']);?></textarea>
      <?php echo form_error('remarks'); ?>
    </div>


  </div>
   <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div style="display: flex;">
     
      <ul class="sortable">
            <?php        
                    if($attachments)
                        {
                          
                          echo '<div class="col-md-12 col-sm-12 col-xs-12" >';
                          echo "<div class = 'row'>";
                          echo '<div class="col-md-10 col-sm-10 col-xs-10" style = "border: 1px solid black; align :center;">';
                          echo 'Ops Attachment';
                          echo "</div></div>";
                          echo "<div class = 'row'>";
                          echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                          echo 'Image';
                          echo "</div>";
                          echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                          echo 'Attachment';
                          echo "</div></div></div>";

                            for($i=0; $i < count($attachments); $i++) 
                            { 
                                $url_address  = "'".SITE_URL.ADDRESS.$details['clientid'].'/';
                                $actual_file  = $attachments[$i]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-12 col-sm-12 col-xs-12  thumb" id="item-'.$attachments[$i]['id'].'">';
                                echo "<div class = 'row'>";
                                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                                echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url_address.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url_address.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';
                                echo "</div>";
                              
                                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                                if($attachments[$i]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='remove_file[]' value='".$attachments[$i]['id']."' id='remove_file' > Remove Attachment</label>";
                                }

                                if($attachments[$i]['status'] == "2")
                                {
                                   echo "<label style='color:red;'><input type='checkbox' name='add_file[]' value='".$attachments[$i]['id']."' id='add_file' > Add Attachment</label>";
                                }
                                 
                              echo '</div></div></div>';
                              
                            }
                        }
                        ?>
              </ul>  
             
             <ul class="sortable1">
            <?php        
                    if($vendor_attachments)
                        { 
                          echo '<div class="col-md-12 col-sm-12 col-xs-12" >';
                          echo "<div class = 'row'>";
                          echo '<div class="col-md-10 col-sm-10 col-xs-10" style = "border: 1px solid black; align :center;">';
                          echo 'Vendor Attachment';
                          echo "</div></div>";
                          echo "<div class = 'row'>";
                          echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                          echo 'Image';
                          echo "</div>";
                         
                          echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                          echo 'Attachment';
                          echo "</div></div></div>";
                           
                          
                            for($j=0; $j < count($vendor_attachments); $j++) 
                            { 
                                $folder_name = "vendor_file";
                                $url_address_vendor = "'".SITE_URL.ADDRESS.$folder_name.'/';
                                $actual_file  = $vendor_attachments[$j]['file_name']."'";
                                $myWin  = "'"."myWin"."'";
                                $attribute  = "'"."height=250,width=480"."'";
                               
                                echo '<div class="col-md-12 col-sm-12 col-xs-12 thumb" id="item-'.$vendor_attachments[$j]['id'].'">';
                                echo "<div class = 'row'>";
                                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                                echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url_address_vendor.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url_address_vendor.$actual_file.' height = "75px" width = "75px" > </a>&nbsp;&nbsp;&nbsp;&nbsp;';
                                echo "</div>";
                               
                                echo '<div class="col-md-5 col-sm-5 col-xs-5" style = "border: 1px solid black;">';
                                if($vendor_attachments[$j]['status'] == "1")
                                {
                              
                                 echo "<label><input type='checkbox'  name='vendor_remove_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_remove_file' > Remove Attachment</label>";
                                }

                                if($vendor_attachments[$j]['status'] == "2")
                                {
                                   echo "<label style='color:red;'><input type='checkbox' name='vendor_add_file[]' value='".$vendor_attachments[$j]['id']."' id='vendor_add_file' >Add Attachment </label>";
                                }
                                 
                              echo '</div></div></div>'; 
                            }
                        }

                        ?>
             </ul>  
         </div>             
        </div>
      <div class="clearfix"></div>
  </div>
</div>
  
<script>
$(function(){

  $('#verified_by').keyup(function(){
        
        var verified_by  = document.getElementById("verified_by").value;
       // document.getElementById("demo").innerHTML = verified_by;
       var mode_of_verification  = document.getElementById("mode_of_verification").value;

        if("<?php echo $url; ?>" == "Clear")
        {

           if(mode_of_verification == "digital") 
           {
              $('.add_rem_update').val("Digital verification completed by "+verified_by+" who verified all details as Clear");
           }
           else{
              $('.add_rem_update').val("Site visit conducted and met "+verified_by+" who verified all details as Clear");
           }  
        }

  });
});

$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  endDate: new Date()
});


$('#res_state').attr("style", "pointer-events: none;");

</script>
<script src="<?= SITE_JS_URL ?>jquery-ui.min.js"></script>
<script type="text/javascript">
  $("ul.sortable" ).sortable({
    revert: 100,
    placeholder: 'placeholder'
  });

  $( "ul.sortable" ).disableSelection();

  $("ul.sortable1" ).sortable({
    revert: 100,
    placeholder: 'placeholder'
  });

  $( "ul.sortable1" ).disableSelection();
</script>
<script type="text/javascript">
   $(".select2").select2();
</script>