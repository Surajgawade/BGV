<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Select Candidate </label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$empt_details['CandidateName']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label><?php echo REFNO; ?></label>
  <input type="text" name="cmp_ref_no" id="cmp_ref_no"  value="<?php echo set_value('cmp_ref_no',$empt_details['cmp_ref_no']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Component Ref No</label>
  <input type="text" name="emp_com_ref" id="emp_com_ref"  value="<?php echo set_value('emp_com_ref',$empt_details['emp_com_ref']); ?>" class="form-control cls_readonly">
</div>


<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Previous Employee Code </label>
  <input type="text" name="empid" id="empid" value="<?php echo set_value('empid',$empt_details['empid']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Company Name</label>

   <?php
    echo form_dropdown('nameofthecompany', $company, set_value('nameofthecompany',$empt_details['nameofthecompany']), 'class="custom-select cls_readonly" id="nameofthecompany" disabled' );
    echo form_error('nameofthecompany');
  ?>
  
</div>

 <input type="hidden" name="actual_company_name" id="actual_company_name" value="<?php echo set_value('actual_company_name',$empt_details['actual_company_name']); ?>" class="form-control cls_readonly">
 <input type="hidden" name="clientname" id="clientname" value="<?php echo set_value('clientname',$empt_details['clientname']); ?>" class="form-control cls_readonly">

 <input type="hidden" name="clientid" id="clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>" class="form-control cls_readonly">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Deputed Company</label>
  <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company',$empt_details['deputed_company']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Employment Type</label>
  <input type="text" name="employment_type" id="employment_type" value="<?php echo set_value('employment_type',$empt_details['employment_type']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Company Contact No.</label>
  <input type="text" name="compant_contact" maxlength="13" id="compant_contact" value="<?php echo set_value('compant_contact',$empt_details['compant_contact']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Company Contact Name</label>
  <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name'); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Company Contact Designation</label>
  <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation'); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Employed From</label>
  <input type="text" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom',convert_db_to_display_date($empt_details['empfrom'])); ?>" class="form-control cls_readonly" placeholder='DD-MM-YYYY'>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Employed To</label>
  <input type="text" name="empto" id="empto" value="<?php echo set_value('empto',convert_db_to_display_date($empt_details['empto'])); ?>" class="form-control cls_readonly" placeholder='DD-MM-YYYY'>
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Designation</label>
  <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$empt_details['designation']); ?>" class="form-control cls_readonly">
</div>
 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Remuneration</label>
  <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration',$empt_details['remuneration']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label for="reasonforleaving">Reason for Leaving</label>
  <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control cls_readonly"><?php echo set_value('reasonforleaving',$empt_details['reasonforleaving']);?></textarea>
</div>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Manager Name</label>
  <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name'); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Manager's contact</label>
  <input type="text" name="r_manager_no" minlength="9" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no'); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Designation</label>
  <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation'); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Manager's Email ID</label>
  <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email'); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">

   <table style="border: 1px solid black; ">
          <tr>
             <th style='border: 1px solid black;padding: 8px;'>File Name</th>
             <th style='border: 1px solid black;padding: 8px;'>Uploaded AT</th>
             <th style='border: 1px solid black;padding: 8px;'>Action</th>
          </tr>
          <tr>
            <?php 
              $i = 1; 
          

              foreach ($attachments as $key => $value) {
             
              $url  = SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
              if($value['type'] == 0)
              {
                echo '<tr>'; 
              ?>

               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'></a><?php echo $value['file_name'] ?></td>
               <td style='border: 1px solid black;padding: 8px;'>Others</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>

              <?php
                echo '</tr>';
              }

              if($value['type'] == 2)
              {
                echo '<tr>'; 
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'></a><?php echo  $value['file_name']; ?></td>
               <td style='border: 1px solid black;padding: 8px;'>Insuff Cleared</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
              
                echo '</tr>';
              }

              if($value['type'] == 3)
              {
                 echo '<tr>'; 
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'></a><?php echo  $value['file_name']; ?></td>
               <td style='border: 1px solid black;padding: 8px;'>Experience/Relieving Letter</td>
                <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
                 
                echo '</tr>';
              }

              if($value['type'] == 4)
              {
                 echo '</tr>';
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'></a><?php echo  $value['file_name']; ?></td>
               <td style='border: 1px solid black;padding: 8px;'>LOA</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
                echo '</tr>';
              }
            } 
            ?>

          </tr>
        </table>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Address <span class="error"> *</span></label>
  <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control"><?php echo set_value('locationaddr',$empt_details['locationaddr']); ?></textarea>
  <?php echo form_error('locationaddr'); ?>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>City <span class="error"> *</span></label>
  <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality',$empt_details['citylocality']); ?>" class="form-control">
  <?php echo form_error('citylocality'); ?>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Pincode <span class="error"> *</span></label>
  <input type="text" name="pincode" mxnlength="6" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$empt_details['pincode']); ?>" class="form-control">
  <?php echo form_error('pincode'); ?>
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>State <span class="error"> *</span></label>
  <?php
    echo form_dropdown('state', $states, set_value('state',$empt_details['state']), 'class="custom-select" id="state"');
    echo form_error('state');
  ?>
</div> 
</div>
<div class="row">
<div class="col-sm-4 form-group">
  <label >Select Vendor<span class="error"> *</span></label>
    <?php
      echo form_dropdown('vendor_id', array(), set_value('vendor_id'), 'class="custom-select" id="vendor_id"');
      echo form_error('vendor_id');
    ?>
</div>  
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Additional Remarks</label>

   <textarea name="field_visit_additional_remark" rows="1" id="field_visit_additional_remark" class="form-control"><?php echo set_value('field_visit_additional_remark',$empt_details['field_visit_additional_remark']); ?></textarea>
  <?php echo form_error('field_visit_additional_remark'); ?>
  
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client Disclosure:</label>
      <select id= "client_disclosure" name="client_disclosure" class="form-control">
        <option value="yes"> Yes</option>
        <option value="no" selected="selected">No</option>
      </select>
  </div>
</div> 
<script>$('.cls_readonly').prop('readonly',true);</script>

<script>
$(document).on('change', '#state', function(){
  var state = $(this).val();
  var city = $('#citylocality').val();

  if(state != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/get_vendor_list_for_field_visit'; ?>',
          data:'state='+state+'&city='+city,
          beforeSend :function(){
            jQuery('#vendor_id').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#vendor_id').html(html);
          }
      });
  }
});
</script>