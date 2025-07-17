<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Information Provided</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Information Verified</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
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
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_type_action" data-val="res_address_type" value="not-Obtained">Not Disclosed</label>
    </div>-->
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address</label>
      <input type="text" name="address" id="address" value="<?php echo set_value('address',$details['address']); ?>" class="form-control" readonly>
      <?php echo form_error('address'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address<span class="error"> *</span></label>
      <input type="text" name="res_address" id="res_address" value="<?php echo set_value('res_address',$details['res_address']); ?>" class="form-control">
      <?php echo form_error('res_address'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" id="address_action" data-val="res_address" value="yes" <?php if(isset($details['address_action'])){ if($details['address_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action" id="address_action" data-val="res_address" value="no" <?php if(isset($details['address_action'])){ if($details['address_action'] == 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="address_action"  id="address_action" data-val="res_address" value="not-verified" <?php if(isset($details['address_action'])){ if($details['address_action']== 'not-verified') {echo "checked='checked'";}} ?>>Not Verified</label>

    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City</label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control" readonly>
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City<span class="error"> *</span></label>
      <input type="text" name="res_city" id="res_city" value="<?php echo set_value('res_city',$details['res_city']); ?>" class="form-control">
      <?php echo form_error('res_city'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action"  id="city_action" data-val="res_city" value="yes" <?php if(isset($details['city_action'])){ if($details['city_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action"   id="city_action" data-val="res_city" value="no" <?php if(isset($details['city_action'])){ if($details['city_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="city_action"   id="city_action" data-val="res_city" value="not-obtained" <?php if(isset($details['city_action'])){ if($details['city_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control" readonly>
      <?php echo form_error('pincode'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Pincode<span class="error"> *</span></label>
      <input type="text" name="res_pincode" id="res_pincode" value="<?php echo set_value('res_pincode',$details['res_pincode']); ?>" class="form-control">
      <?php echo form_error('res_pincode'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="yes"  <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="no" <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="pincode_action" data-val="res_pincode" value="not-obtained" <?php if(isset($details['pincode_action'])){ if($details['pincode_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state',$details['state']), 'class="form-control singleSelect cls_disabled" id="state" disabled');
        echo form_error('state');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State<span class="error"> *</span></label>
        <?php
          echo form_dropdown('res_state', $states, set_value('res_state',$details['res_state']), 'class="form-control singleSelect cls_disabled" id="res_state"');
          echo form_error('res_state');
        ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="yes" <?php if(isset($details['state_action'])){ if($details['state_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="no" <?php if(isset($details['state_action'])){ if($details['state_action']== 'no') { echo 'checked';}} ?> >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="state_action" data-val="res_state" value="not-verified" <?php if(isset($details['state_action'])){ if($details['state_action']== 'not-verified') { echo 'checked';}} ?>>Not Verified</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$details['stay_from']);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
      <?php echo form_error('stay_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay From <span class="error"> *</span></label>
      <input type="text" name="res_stay_from" id="res_stay_from" value="<?php echo set_value('res_stay_from',$details['res_stay_from']);?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_stay_from'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="yes" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="no" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'no') { echo 'checked';}} ?> >No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_from_action" data-val="res_stay_from" value="not-obtained" <?php if(isset($details['stay_from_action'])){ if($details['stay_from_action']== 'not-obtained') { echo 'checked';}} ?> >Not Obtained</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to',$details['stay_to']);?>" class="form-control" placeholder='DD-MM-YYYY' readonly>
      <?php echo form_error('stay_to'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Stay To <span class="error"> *</span></label>
      <input type="text" name="res_stay_to" id="res_stay_to" value="<?php echo set_value('res_stay_to',$details['res_stay_to']);?>" class="form-control " placeholder='DD-MM-YYYY'>
      <?php echo form_error('res_stay_to'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <br>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="yes" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'yes') { echo 'checked';}} ?>>Yes </label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="no" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'no') { echo 'checked';}} ?>>No</label>
      <label class="radio-inline"><input type="radio" class="rto_clicked" name="stay_to_action" data-val="res_stay_to" value="not-obtained" <?php if(isset($details['stay_to_action'])){ if($details['stay_to_action']== 'not-obtained') { echo 'checked';}} ?>>Not Obtained</label>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Mode of verification<span class="error"> *</span></label>
      <?php
        $mode_of_verification = array('personal visit'=> 'Personal Visit','verbal'=>'Verbal','others'=>'Others');

        echo form_dropdown('mode_of_verification', $mode_of_verification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="form-control" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Resident Status<span class="error"> *</span></label>
      <?php
      $resident_status = array(''=> 'Select Resident Status','rented'=> 'Rented','rwned'=>'Owned','pg'=>'PG','relatives'=>'Relatives','hostel'=>'Hostel');
        echo form_dropdown('resident_status', $resident_status, set_value('resident_status',$details['resident_status']), 'class="form-control" id="resident_status"');
        echo form_error('resident_status');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Landmark</label>
      <textarea name="landmark" id="landmark" rows="1" class="form-control add_res_landmark"><?php echo set_value('landmark',$details['landmark']);?></textarea>
      <?php echo form_error('landmark'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Verified By<span class="error"> *</span></label>
      <input type="text" name="verified_by" id="verified_by" value="<?php echo set_value('verified_by',$details['verified_by']);?>" class="form-control">
      <?php echo form_error('verified_by'); ?>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour 1</label>
      <input type="text" name="neighbour_1" id="neighbour_1" value="<?php echo set_value('neighbour_1',$details['neighbour_1']);?>" class="form-control">
      <?php echo form_error('neighbour_1'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour Details 1</label>
      <input type="text" name="neighbour_details_1" id="neighbour_details_1" value="<?php echo set_value('neighbour_details_1',$details['neighbour_details_1']);?>" class="form-control">
      <?php echo form_error('neighbour_details_1'); ?>
    </div>
     <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour 2</label>
      <input type="text" name="neighbour_2" id="neighbour_2" value="<?php echo set_value('neighbour_2',$details['neighbour_2']);?>" class="form-control">
      <?php echo form_error('neighbour_2'); ?>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Neighbour Details 2</label>
      <input type="text" name="neighbour_details_2" id="neighbour_details_2" value="<?php echo set_value('neighbour_details_2',$details['neighbour_details_2']);?>" class="form-control">
      <?php echo form_error('neighbour_details_2'); ?>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Addr. Proof Collected<span class="error"> *</span></label>
      <!--<input type="text" name="addr_proof_collected" id="addr_proof_collected" value="<?php echo set_value('addr_proof_collected',$details['addr_proof_collected']);?>" class="form-control">-->
      <?php
        $addr_proof_collected = array(''=> 'Select Address Proof','aadhar card'=> 'Aadhar Card','ration card'=>'Ration Card','electricity bill '=>'Electricity Bill','voter id'=> 'Voter ID','driving license'=> 'Driving License','others'=> 'Others','none'=> 'None');

        echo form_dropdown('addr_proof_collected', $addr_proof_collected, set_value('addr_proof_collected',$details['addr_proof_collected']), 'class="form-control" id="addr_proof_collected"');
        echo form_error('addr_proof_collected');
      ?>
      
      <!--<?php echo form_error('addr_proof_collected'); ?>-->
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date<span class="error"> *</span></label>
      <input type="text" name="closuredate" id="closuredate" value="<?php if(!empty($details['closuredate'])) { echo set_value('closuredate',convert_db_to_display_date($details['closuredate'])); } else{ echo date('d-m-Y');} ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Remarks<span class="error"> *</span></label>
      <textarea name="remarks" id="remarks" rows="1" class="form-control add_res_remarks"><?php echo set_value('remarks',$details['remarks']);?></textarea>
      <?php echo form_error('remarks'); ?>
    </div>

    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg"  onchange="readURL(this);" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
    <div class="clearfix">

      <table border="1" id="table1" align="center">
        <tr>
        <th>Source</th>
        <th>File Name</th>
        <th>Thumbnail</th>
        <th><input type="checkbox" checked="checked" onclick="toggle(this);" />Check all<br /></th>
      
        
      </tr>
    <tbody class="row_position">
       
        <?php 
           foreach ($attachments as $key => $value) {
                
                  $url  = SITE_URL.CANDIDATES.$details['clientid'].'/';
                  ?>
                   <tr class = '<?php echo $value['file_name'];  ?>'>
                    <td><?php echo "TASK";  ?></td>
                    <td><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></td>
                     <td><img src="<?php echo $url.$value['file_name']; ?>" alt="Thumb" width="42" height="70"></td> 
                     <td><input type="checkbox" name="order[]" id="order" class="order" checked="checked"> </td>    
                  
                  </tr>

                    <?php
              }?>
           
            
        
        <?php 
           foreach ($attachments1 as $key => $value) {
                
                   $url1  = SITE_URL.ADDRESS.'vendor_file'.'/';
                  ?>
                   <tr class = '<?php echo $value['file_name'];  ?>'>
                     <td><?php echo "Vendor";  ?></td>
                    <td><a href='javascript:;'onClick='myOpenWindow("<?php echo $url1.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
                    <td><img src="<?php echo $url1.$value['file_name']; ?>" alt="Thumb" width="42" height="70"></td>
                    <td><input type="checkbox" name="order[]" id="order" class="order" checked="checked"> </td>
                   
                   </tr>
                  <?php
              }?>

               <?php 
           foreach ($attachments2 as $key => $value) {
                
                   $url2  = SITE_URL.ADDRESS.'1/';
                  ?>
                   <tr class = '<?php echo $value['file_name'];  ?>'>
                     <td><?php echo "Address";  ?></td>
                    <td><a href='javascript:;'onClick='myOpenWindow("<?php echo $url2.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
                    <td><img src="<?php echo $url2.$value['file_name']; ?>" alt="Thumb" width="42" height="70"></td>
                    <td><input type="checkbox" name="order[]" id="order" class="order" checked="checked"> </td>
                     
                   </tr>
                  <?php
              }?>
           <?php for ($i=1; $i < 30; $i++) { 
           echo  '<tr id="demo'.$i.'"  ><tr>';
       
           }
            ?>
      
    </tbody>    
</table>

</div>
<script src="<?= SITE_JS_URL ?>jquery-ui.min.js"></script>
<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  endDate: new Date()
});

  function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}


function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}

  function readURL(input) {
           var x = document.getElementById("attchments_ver");
            var txt = "";
            if ('files' in x) {
              if (x.files.length == 0) {
                txt = "Select one or more files.";
              } else {
                for (var i = 0; i < x.files.length; i++) {
                 // txt += "<br><strong>" + (i+1) + ". file</strong><br>";
                  var file = x.files[i];

                  if ('name' in file) {

                    txt = "<td>Address</td><td><a href ='#'>" + file.name + "</a></td><td><img src='"+URL.createObjectURL(event.target.files[i])+"' alt='Thumb' width='42' height='70'></td><td><input type='checkbox' name='order[]' id='order' class='order'  checked='checked'> </td> ";
                    
                  }

             
                    document.getElementById("demo"+(i+1)).innerHTML = txt;

                    $("#demo"+(i+1)).addClass(file.name);
  

                }
              
               
              }
            } 
            else {
              if (x.value == "") {
                txt += "Select one or more files.";
              } else {
                txt += "The files property is not supported by your browser!";
                txt  += "<br>The path of the selected file: " + x.value; // If the browser does not support the files property, it will return the path of the selected file instead. 
              }
            }
         
        }

$( "tbody" ).sortable();

  $( "tbody" ).disableSelection();


</script>


