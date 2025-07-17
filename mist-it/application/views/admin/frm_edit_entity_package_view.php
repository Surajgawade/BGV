<script type="text/javascript">
$(document).on('click', '.clkSlaModel', function(){

   var txt_val = $(this).data('val');
   document.getElementById("client_component").value = txt_val;
   $("#client_component1").html(txt_val);

    var client_id = document.getElementById("client_name_id").value;
     if(client_id != "" && txt_val !="")
    {
        $.ajax({
            type:'POST',
            url : '<?php echo ADMIN_SITE_URL.'clients/view_sla_details' ?>',
            data : 'client_id='+client_id+'&txt_val='+txt_val,
          

            success:function(response)
            {

               if(response == "Error")
               {

                 if(txt_val == "Address")
                 {
           
                 $('#sla_view').modal('show');
                 $('#address_show').show();
                 $('#employment_show').hide();
                 $('#education_show').hide();
                 $('#references_show').hide();
                 $('#court_show').hide();
                 $('#global_show').hide();
                 $('#drugs_show').hide();
                 $('#pcc_show').hide();
                 $('#credit_show').hide();

                 }
                 else if(txt_val == "Employment")
                 {
                   
                
                   $('#sla_view').modal('show');
                   $('#employment_show').show();
                   $('#address_show').hide();
                   $('#education_show').hide();
                   $('#references_show').hide();
                   $('#court_show').hide();
                   $('#global_show').hide();
                   $('#drugs_show').hide();
                   $('#pcc_show').hide();
                   $('#credit_show').hide();
                 }

               else if(txt_val == "Education")
               {
                
                  
                   
                  $('#sla_view').modal('show');
                  $('#education_show').show();
                  $('#address_show').hide();
                  $('#employment_show').hide();  
                  $('#references_show').hide();
                  $('#court_show').hide();
                  $('#global_show').hide();
                  $('#drugs_show').hide();
                  $('#pcc_show').hide();
                  $('#credit_show').hide();
              
               }
               else if(txt_val == "References")
               {
                
                  
                  

                    $('#sla_view').modal('show');
                    $('#references_show').show();
                    $('#education_show').hide();
                    $('#address_show').hide();
                    $('#employment_show').hide(); 
                    $('#court_show').hide();
                    $('#global_show').hide();
                    $('#drugs_show').hide();
                    $('#pcc_show').hide();
                    $('#credit_show').hide(); 
               }
               else if(txt_val == "Court")
               {
                 
                   
                  
                    $('#sla_view').modal('show');
                    $('#court_show').show();
                    $('#references_show').hide();
                    $('#education_show').hide();
                    $('#address_show').hide();
                    $('#employment_show').hide(); 
                    $('#global_show').hide();
                    $('#drugs_show').hide();
                    $('#pcc_show').hide();
                    $('#credit_show').hide(); 

               }
               else if(txt_val == "Global")
               {

                   $('#sla_view').modal('show');
                   $('#global_show').show();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#drugs_show').hide();
                   $('#pcc_show').hide();
                   $('#credit_show').hide(); 
               }
              else if(txt_val == "Drugs/Narcotics")
               {
                  
                 
                   $('#sla_view').modal('show');
                   $('#drugs_show').show();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#pcc_show').hide();
                   $('#global_show').hide();
                   $('#credit_show').hide(); 
               }
               else if(txt_val == "PCC")
               {
                 
                  
                   
                   $('#sla_view').modal('show');
                   $('#pcc_show').show();
                   $('#drugs_show').hide();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#global_show').hide();
                   $('#credit_show').hide(); 
               }
               else if(txt_val == "Credit")
               {
                 
                  
                   $('#sla_view').modal('show');
                   $('#credit_show').show();
                   $('#pcc_show').hide();
                   $('#drugs_show').hide();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#global_show').hide();
              
               }
               }
               else
               {


               if(txt_val == "Address")
               {
             
               $('#address_client_name_selection').val(response['0']['selected_selection']);
               $('#address_client_name_remark').val(response['0']['remarks']);
               $('#address_candidate_calling_selection').val(response['1']['selected_selection']);
               $('#address_candidate_calling_remark').val(response['1']['remarks']);
               $('#address_add_charges_selection').val(response['2']['selected_selection']);
               $('#address_add_charges_remark').val(response['2']['remarks']);
               $('#address_mode_of_verification_selection').val(response['3']['selected_selection']);
               $('#address_mode_of_verification_remark').val(response['3']['remarks']);
               $('#address_display_matrix_selection').val(response['4']['selected_selection']);
               $('#address_display_matrix_remark').val(response['4']['remarks']);
               $('#address_insufficiency_selection').val(response['5']['selected_selection']);
               $('#address_insufficiency_remark').val(response['5']['remarks']);
               $('#address_unable_verify_selection').val(response['6']['selected_selection']);
               $('#address_unable_verify_remark').val(response['6']['remarks']);
               $('#address_insufficiency_not_cleared_selection').val(response['7']['selected_selection']);
               $('#address_insufficiency_not_cleared_remark').val(response['7']['remarks']);
                

                 $('#sla_view').modal('show');
                 $('#address_show').show();
                 $('#employment_show').hide();
                 $('#education_show').hide();
                 $('#references_show').hide();
                 $('#court_show').hide();
                 $('#global_show').hide();
                 $('#drugs_show').hide();
                 $('#pcc_show').hide();
                 $('#credit_show').hide();
   
 
 
                 }
                 else if(txt_val == "Employment")
                 {
                   
                   $('#employment_client_name_selection').val(response['0']['selected_selection']);
                   $('#employment_client_name_remark').val(response['0']['remarks']);
                   $('#employment_candidate_calling_selection').val(response['1']['selected_selection']);
                   $('#employment_candidate_calling_remark').val(response['1']['remarks']);
                   $('#employment_add_charges_selection').val(response['2']['selected_selection']);
                   $('#employment_add_charges_remark').val(response['2']['remarks']);
                   $('#employment_overseas_check_selection').val(response['3']['selected_selection']);
                   $('#employment_overseas_check_remark').val(response['3']['remarks']);
                   $('#employment_mode_of_verification_selection').val(response['4']['selected_selection']);
                   $('#employment_mode_of_verification_remark').val(response['4']['remarks']);
                   $('#employment_candidate_previously_employed_selection').val(response['5']['selected_selection']);
                   $('#employment_candidate_previously_employed_remark').val(response['5']['remarks']);
                   $('#employment_current_selection').val(response['6']['selected_selection']);
                   $('#employment_current_remark').val(response['6']['remarks']);
                   $('#emploment_supervisor_calling_selection').val(response['7']['selected_selection']);
                   $('#emploment_supervisor_calling_remark').val(response['7']['remarks']);
                   $('#employment_left_organization_selection').val(response['8']['selected_selection']);
                   $('#employment_left_organization_remark').val(response['8']['remarks']);
                   $('#employment_site_visit_selection').val(response['9']['selected_selection']);
                   $('#employment_site_visit_remark').val(response['9']['remarks']);
                   $('#employment_display_matrix_selection').val(response['10']['selected_selection']);
                   $('#employment_display_matrix_remark').val(response['10']['remarks']);
                   $('#employment_insufficiency_selection').val(response['11']['selected_selection']);
                   $('#employment_insufficiency_remark').val(response['11']['remarks']);
                   $('#employment_unable_verify_selection').val(response['12']['selected_selection']);
                   $('#employment_unable_verify_remark').val(response['12']['remarks']);
                   $('#employment_insufficiency_not_cleared_selection').val(response['13']['selected_selection']);
                   $('#employment_insufficiency_not_cleared_remark').val(response['13']['remarks']);

                   $('#sla_view').modal('show');
                   $('#employment_show').show();
                   $('#address_show').hide();
                   $('#education_show').hide();
                   $('#references_show').hide();
                   $('#court_show').hide();
                   $('#global_show').hide();
                   $('#drugs_show').hide();
                   $('#pcc_show').hide();
                   $('#credit_show').hide();
                 }

               else if(txt_val == "Education")
               {
                
                   $('#education_overseas_degree_selection').val(response['0']['selected_selection']);
                   $('#employment_overseas_degree_remark').val(response['0']['remarks']);
                   $('#education_candidate_calling_selection').val(response['1']['selected_selection']);
                   $('#education_candidate_calling_remark').val(response['1']['remarks']);
                   $('#education_initiate_pursuing_degree_selection').val(response['2']['selected_selection']);
                   $('#education_initiate_pursuing_degree_remark').val(response['2']['remarks']);
                   $('#education_add_charges_selection').val(response['3']['selected_selection']);
                   $('#education_add_charges_remark').val(response['3']['remarks']);
                   $('#education_mode_of_verification_selection').val(response['4']['selected_selection']);
                   $('#education_mode_of_verification_remark').val(response['4']['remarks']);
                   $('#education_display_matrix_selection').val(response['5']['selected_selection']);
                   $('#education_display_matrix_remark').val(response['5']['remarks']);
                   $('#education_insufficiency_selection').val(response['6']['selected_selection']);
                   $('#education_insufficiency_remark').val(response['6']['remarks']);
                   $('#education_unable_verify_selection').val(response['7']['selected_selection']);
                   $('#education_unable_verify_remark').val(response['7']['remarks']);
                   $('#education_insufficiency_not_cleared_selection').val(response['8']['selected_selection']);
                   $('#education_insufficiency_not_cleared_remark').val(response['8']['remarks']);
                   
                  $('#sla_view').modal('show');
                  $('#education_show').show();
                  $('#address_show').hide();
                  $('#employment_show').hide();  
                  $('#references_show').hide();
                  $('#court_show').hide();
                  $('#global_show').hide();
                  $('#drugs_show').hide();
                  $('#pcc_show').hide();
                  $('#credit_show').hide();
              
               }
               else if(txt_val == "References")
               {
                
                   $('#references_client_name_selection').val(response['0']['selected_selection']);
                   $('#references_client_name_remark').val(response['0']['remarks']);
                   $('#references_candidate_calling_selection').val(response['1']['selected_selection']);
                   $('#references_candidate_calling_remark').val(response['1']['remarks']);
                   $('#references_overseas_check_selection').val(response['2']['selected_selection']);
                   $('#references_overseas_check_remark').val(response['2']['remarks']);
                   $('#references_mode_of_verification_selection').val(response['3']['selected_selection']);
                   $('#references_mode_of_verification_remark').val(response['3']['remarks']);
                   $('#references_display_matrix_selection').val(response['4']['selected_selection']);
                   $('#references_display_matrix_remark').val(response['4']['remarks']);
                   $('#references_insufficiency_selection').val(response['5']['selected_selection']);
                   $('#references_insufficiency_remark').val(response['5']['remarks']);
                   $('#references_unable_verify_selection').val(response['6']['selected_selection']);
                   $('#references_unable_verify_remark').val(response['6']['remarks']);
                   $('#references_insufficiency_not_cleared_selection').val(response['7']['selected_selection']);
                   $('#references_insufficiency_not_cleared_remark').val(response['7']['remarks']);
                  

                    $('#sla_view').modal('show');
                    $('#references_show').show();
                    $('#education_show').hide();
                    $('#address_show').hide();
                    $('#employment_show').hide(); 
                    $('#court_show').hide();
                    $('#global_show').hide();
                    $('#drugs_show').hide();
                    $('#pcc_show').hide();
                    $('#credit_show').hide(); 
               }
               else if(txt_val == "Court")
               {
                  
                   $('#court_mode_of_verification_selection').val(response['0']['selected_selection']);
                   $('#court_mode_of_verification_remark').val(response['0']['remarks']);
                   $('#court_display_matrix_selection').val(response['1']['selected_selection']);
                   $('#court_display_matrix_remark').val(response['1']['remarks']);
                   $('#court_insufficiency_selection').val(response['2']['selected_selection']);
                   $('#court_insufficiency_remark').val(response['2']['remarks']);
                   $('#court_unable_verify_selection').val(response['3']['selected_selection']);
                   $('#court_unable_verify_remark').val(response['3']['remarks']);
                   $('#court_insufficiency_not_cleared_selection').val(response['4']['selected_selection']);
                   $('#court_insufficiency_not_cleared_remark').val(response['4']['remarks']);
                   
                  
                    $('#sla_view').modal('show');
                    $('#court_show').show();
                    $('#references_show').hide();
                    $('#education_show').hide();
                    $('#address_show').hide();
                    $('#employment_show').hide(); 
                    $('#global_show').hide();
                    $('#drugs_show').hide();
                    $('#pcc_show').hide();
                    $('#credit_show').hide(); 

               }
               else if(txt_val == "Global")
               {
                  $('#global_mode_of_verification_selection').val(response['0']['selected_selection']);
                   $('#global_mode_of_verification_remark').val(response['0']['remarks']);
                   $('#global_display_matrix_selection').val(response['1']['selected_selection']);
                   $('#global_display_matrix_remark').val(response['1']['remarks']);
                   $('#global_insufficiency_selection').val(response['2']['selected_selection']);
                   $('#global_insufficiency_remark').val(response['2']['remarks']);
                   $('#global_unable_verify_selection').val(response['3']['selected_selection']);
                   $('#global_unable_verify_remark').val(response['3']['remarks']);
                   $('#global_insufficiency_not_cleared_selection').val(response['4']['selected_selection']);
                   $('#global_insufficiency_not_cleared_remark').val(response['4']['remarks']);
                   

                   $('#sla_view').modal('show');
                   $('#global_show').show();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#drugs_show').hide();
                   $('#pcc_show').hide();
                   $('#credit_show').hide(); 
               }
              else if(txt_val == "Drugs/Narcotics")
               {
                  
                   $('#drugs_mode_of_verification_selection').val(response['0']['selected_selection']);
                   $('#drugs_mode_of_verification_remark').val(response['0']['remarks']);
                   $('#drugs_display_matrix_selection').val(response['1']['selected_selection']);
                   $('#drugs_display_matrix_remark').val(response['1']['remarks']);
                   $('#drugs_insufficiency_selection').val(response['2']['selected_selection']);
                   $('#drugs_insufficiency_remark').val(response['2']['remarks']);
                   $('#drugs_unable_verify_selection').val(response['3']['selected_selection']);
                   $('#drugs_unable_verify_remark').val(response['3']['remarks']);
                   $('#drugs_insufficiency_not_cleared_selection').val(response['4']['selected_selection']);
                   $('#drugs_insufficiency_not_cleared_remark').val(response['4']['remarks']);
                   
                   $('#sla_view').modal('show');
                   $('#drugs_show').show();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#pcc_show').hide();
                   $('#global_show').hide();
                   $('#credit_show').hide(); 
               }
               else if(txt_val == "PCC")
               {
                 
                   $('#pcc_mode_of_verification_selection').val(response['0']['selected_selection']);
                   $('#pcc_mode_of_verification_remark').val(response['0']['remarks']);
                   $('#pcc_display_matrix_selection').val(response['1']['selected_selection']);
                   $('#pcc_display_matrix_remark').val(response['1']['remarks']);
                   $('#pcc_insufficiency_selection').val(response['2']['selected_selection']);
                   $('#pcc_insufficiency_remark').val(response['2']['remarks']);
                   $('#pcc_unable_verify_selection').val(response['3']['selected_selection']);
                   $('#pcc_unable_verify_remark').val(response['3']['remarks']);
                   $('#pcc_insufficiency_not_cleared_selection').val(response['4']['selected_selection']);
                   $('#pcc_insufficiency_not_cleared_remark').val(response['4']['remarks']);
                   
                   $('#sla_view').modal('show');
                   $('#pcc_show').show();
                   $('#drugs_show').hide();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                   $('#global_show').hide();
                   $('#credit_show').hide(); 
               }
               else if(txt_val == "Credit")
               {
                 
                   $('#pcc_mode_of_verification_selection').val(response['0']['selected_selection']);
                   $('#pcc_mode_of_verification_remark').val(response['0']['remarks']);
                   $('#pcc_display_matrix_selection').val(response['1']['selected_selection']);
                   $('#pcc_display_matrix_remark').val(response['1']['remarks']);
                   $('#pcc_insufficiency_selection').val(response['2']['selected_selection']);
                   $('#pcc_insufficiency_remark').val(response['2']['remarks']);
                   $('#pcc_unable_verify_selection').val(response['3']['selected_selection']);
                   $('#pcc_unable_verify_remark').val(response['3']['remarks']);
                   $('#pcc_insufficiency_not_cleared_selection').val(response['4']['selected_selection']);
                   $('#pcc_insufficiency_not_cleared_remark').val(response['4']['remarks']);
                   $('#sla_view').modal('show');
                   $('#credit_show').show();
                   $('#pcc_show').hide();
                   $('#drugs_show').hide();
                   $('#education_show').hide();
                   $('#address_show').hide();
                   $('#employment_show').hide();
                   $('#references_show').hide(); 
                   $('#court_show').hide();
                 $('#global_show').hide();
              
               }
             }
            }
        });
    }
  

 
});
</script>
<input type="hidden" name="update_id" value="<?php if(isset($client_details['id'])) { echo set_value('update_id',$client_details['id']);}else{ } ?>" id="update_id">
<input type="hidden" name="client_id" value="<?php if(isset($client_details['tbl_clients_id'])) { echo set_value('client_id',$client_details['tbl_clients_id']); }else{ echo $client_entity_package_details['tbl_clients_id']; } ?>" id="client_id" class="append_client_id">
<input type="hidden" name="selected_entity" id="selected_entity" value="<?php if(isset($client_details['entity'])) {  echo set_value('selected_entity',$client_details['entity']);}else{ echo $client_entity_package_details['entity']; } ?>">
<input type="hidden" name="selected_package" id="selected_package" value="<?php  if(isset($client_details['package'])) { echo set_value('selected_package',$client_details['package']);}else{ echo $client_entity_package_details['package']; } ?>">

<div class="clearfix"></div>

<hr style="border-top: 2px solid #bb4c4c;">
<div class="row">
<div class="col-sm-4 form-group">
  <label>Address <span class="error"> *</span></label>
  <input type="text" name="clientaddress" id="clientaddress" value="<?php if(isset($client_details['clientaddress'])) { echo set_value('clientaddress',$client_details['clientaddress']); }else{ } ?>" class="form-control">
  <?php echo form_error('clientaddress'); ?>
</div>

<div class="col-sm-4 form-group">
  <label>City <span class="error"> *</span></label>
  <input type="text" name="clientcity" id="clientcity" value="<?php if(isset($client_details['clientcity'])) { echo set_value('clientcity',$client_details['clientcity']); }else{ } ?>" class="form-control">
  <?php echo form_error('clientcity'); ?>
</div>
<div class="col-sm-4 form-group">
  <label>PIN Code <span class="error"> *</span></label>
  <input type="text" name="clientpincode" id="clientpincode" maxlength="6" value="<?php  if(isset($client_details['clientpincode'])) { echo set_value('clientpincode',$client_details['clientpincode']); }else{ } ?>" class="form-control">
  <?php echo form_error('clientpincode'); ?>
</div>
</div>
<div class="row">
<div class="col-sm-4 form-group">
  <label>State <span class="error"> *</span></label>
  <?php
  if(isset($client_details['clientstate']))
  {
    $clientstate = $client_details['clientstate'];
  }
  else
  {
    $clientstate = '';
  }
    echo form_dropdown("clientstate", $states, set_value('clientstate', $clientstate), 'class="custom-select" id="clientstate"');
    echo form_error('clientstate');
  ?>
</div>

<div class="col-sm-4 form-group">
  <label>Package Amount</label>
  <input type="text" name="package_amount" id="package_amount" maxlength="10" value="<?php if(isset($client_details['package_amount'])) { echo set_value('package_amount',$client_details['package_amount']); }else{ }  ?>" class="form-control">
  <?php echo form_error('package_amount'); ?>
</div> 

<div class="col-sm-4 form-group">
  <label>Candidate Report Type <span class="error"> *</span></label>
  <div class="select" >
    <select id="report_type" name="report_type" class="custom-select">
       <option value="">Select Report Type</option>
      <option value="1" <?php if(isset($client_details['candidate_report_type'])) { if($client_details['candidate_report_type'] == 1 ) echo 'selected' ; } ?>>Full Report </option>
       <option value="2" <?php if(isset($client_details['candidate_report_type'])) { if($client_details['candidate_report_type'] == 2 ) echo 'selected' ; }?>>Only Annexure </option>  
    </select>
   </div>
</div>
</div> 
<div class="row"> 

<div class="col-sm-4 form-group">
  <label>Final QC <span class="error"> *</span></label>
  <div class="select">  
    <select id="final_qc" name="final_qc" class="custom-select">
       <option value="">Select Final QC</option>
      <option value="1" <?php if(isset($client_details['final_qc'])) {  if($client_details['final_qc'] == 1 ) echo 'selected' ; }?>>Yes </option>
       <option value="2" <?php if(isset($client_details['final_qc'])) { if($client_details['final_qc'] == 2 ) echo 'selected' ; } ?>>No </option>  
    </select>
   
  </div>
</div> 

<div class="col-sm-4 form-group">
  <label>Auto Report <span class="error"> *</span></label>
  <div class="select" >  
  <select id="auto_report" name="auto_report" class="custom-select">
       <option value="">Select Auto Report</option>
       <option value="1" <?php  if(isset($client_details['auto_report'])) { if($client_details['auto_report'] == 1 ) echo 'selected' ; } ?>>Yes</option>
       <option value="2" <?php if(isset($client_details['auto_report'])) {  if($client_details['auto_report'] == 2 ) echo 'selected' ;} ?>>No</option>  
        <option value="3" <?php if(isset($client_details['auto_report'])) {  if($client_details['auto_report'] == 3 ) echo 'selected' ;} ?>>FTP</option>  
    </select>
   
  </div>
</div>
<div class="col-sm-4 form-group">
  <label>Insuff Report <span class="error"> *</span></label>
  <div class="select">  
    <select id="insuff_report" name="insuff_report" class=custom-select>
       <option value="">Select Insuff Report</option>
       <option value="1" <?php if(isset($client_details['insuff_report'])) {  if($client_details['insuff_report'] == 1 ) echo 'selected' ;} ?>>Daily</option>
       <option value="2" <?php if(isset($client_details['insuff_report'])) {  if($client_details['insuff_report'] == 2 ) echo 'selected' ;} ?>>Weekly</option>  
       <option value="3" <?php if(isset($client_details['insuff_report'])) {  if($client_details['insuff_report'] == 3 ) echo 'selected' ; }?>>Never</option>  
    </select>
   
  </div>
</div>  
</div>
<div class="row">
<div class="col-sm-4 form-group">
  <label>Case Type <span class="error"> *</span></label>
  <div class="select">  
    <select id="case_type" name="case_type" class="custom-select">
       <option value="">Select Case Type</option>
       <option value="1" <?php if(isset($client_details['case_type'])) {  if($client_details['case_type'] == 1 ) echo 'selected' ; }?>>BGV</option>
       <option value="2" <?php if(isset($client_details['case_type'])) { if($client_details['case_type'] == 2 ) echo 'selected' ; }?>>Other</option>  
    </select>  
  </div>
</div> 


<div class="col-sm-4 form-group">
  <label>Billing Type <span class="error"> *</span></label>
  <div class="select">  
    <select id="billing_type" name="billing_type" class="custom-select">
       <option value="">Select Billing Type</option>
       <option value="1" <?php if(isset($client_details['billing_type'])) {  if($client_details['billing_type'] == 1 ) echo 'selected' ; }?>>Component</option>
       <option value="2" <?php if(isset($client_details['billing_type'])) { if($client_details['billing_type'] == 2 ) echo 'selected' ; } else { echo 'selected' ; } ?>>Cases</option>  
    </select>
   
  </div>
</div> 

<div class="col-sm-4 form-group">
  <label>Multiple Component Selection<span class="error"> *</span></label>
  <div class="select">  
    <select id="candidate_add" name="candidate_add" class="custom-select">
       <option value="">Select Billing Type</option>
       <option value="1" <?php if(isset($client_details['candidate_add_component'])) {  if($client_details['candidate_add_component'] == 1 ) echo 'selected' ; }?>>Yes</option>
       <option value="2" <?php if(isset($client_details['candidate_add_component'])) { if($client_details['candidate_add_component'] == 2 ) echo 'selected' ; } else { echo 'selected' ; } ?>>No</option>  
    </select>
   
  </div>
</div>

<div class="col-sm-4 form-group">
  <label>Candidate Upload</label>
  <div class="select">  
    <select id="candidate_upload" name="candidate_upload" class="custom-select">
       <option value="">Select Candidate upload</option>
       <option value="1" <?php if(isset($client_details['candidate_upload'])) { if($client_details['candidate_upload'] == 1 ) echo 'selected' ; }?>>Add Candidate</option>
       <option value="2" <?php if(isset($client_details['candidate_upload'])) { if($client_details['candidate_upload'] == 2 ) echo 'selected' ; } ?>>Add case</option>  
       <option value="3" <?php if(isset($client_details['candidate_upload'])) { if($client_details['candidate_upload'] == 3 ) echo 'selected' ; } ?>>Pre-Post</option>  

      </select>
   
  </div>
</div> 

<?php   
  if(!empty($client_details['candidate_upload']))
  {
    if($client_details['candidate_upload'] == "2" || $client_details['candidate_upload'] == "3")
    {
        $user_up = "style = 'display : block;'";
        if($client_details['candidate_upload'] == "3" )
        {
          $pre_post_disply = "style = 'display : block;'";
        }
        else{
          $pre_post_disply = "style = 'display : none;'";
        }
    }
    else{
      $user_up = "style = 'display : none;'";
    }
  }
  else{
    $user_up = "";
  }
  ?>   
<div class="col-sm-4 form-group" id = "user_up"  <?php echo $user_up;  ?>>
  <label>Task handler</label>
  <div class="select"> 
    <?php 
      echo form_dropdown("user_upload", $user_name, set_value('user_upload',$client_details['user_upload']), 'class="form-control select2" id="user_upload"');
      echo form_error('user_upload');
    ?>
  </div>
</div> 
<div class="col-sm-4 form-group pre_post_disply" <?php echo $pre_post_disply;  ?>>
  <label>Pre Component</label>
  <div class="select"> 
    <?php 
      $compo = array('addrver'=>'Address','empver'=>'Employment','eduver'=>'Education','courtver'=>'Court','globdbver'=>'Global Database','refver'=>'Reference','identity'=>'Identity','crimver'=>'PCC','cbrver'=>'Credit Report','narcver'=>'Drugs');
      echo form_multiselect('pre_component[]', $compo, set_value('pre_component',explode(',',$client_details['pre_component'])), 'class="select2" id="client_details"');
      echo form_error('pre_component');
    ?>
  </div>
</div> 
<div class="col-sm-4 form-group pre_post_disply"  <?php echo $pre_post_disply;  ?>>
  <label>Post Component</label>
  <div class="select"> 
    <?php
      $compo = array('addrver'=>'Address','empver'=>'Employment','eduver'=>'Education','courtver'=>'Court','globdbver'=>'Global Database','refver'=>'Reference','identity'=>'Identity','crimver'=>'PCC','cbrver'=>'Credit Report','narcver'=>'Drugs');
      echo form_multiselect("post_component[]", $compo, set_value('post_component',explode(',',$client_details['post_component'])), 'class="select2" id="post_component"');
      echo form_error('post_component');
    ?>
  </div>
</div> 
<div class="col-sm-4 form-group">
  <label>Client Disclosure <span class="error"> *</span></label>
  <div class="select">  
    <select id="client_disclosures" name="client_disclosures" class="custom-select">
       <option value="">Select Client Disclosure</option>
       <option value="1" <?php if(isset($client_details['client_disclosures'])) {  if($client_details['client_disclosures'] == 1 ) echo 'selected' ; }?>>Yes</option>
       <option value="2" <?php if(isset($client_details['client_disclosures'])) { if($client_details['client_disclosures'] == 2 ) echo 'selected' ; } ?>>No</option>  
    </select>
   
  </div>
</div> 
<div class="col-sm-4 form-group">
  <label>Case Activity <span class="error"> *</span></label>
   <div class="select">  
    <select id="case_activity" name="case_activity" class="custom-select">
       <option value="">Select Case Activity</option>
       <option value="1" <?php if(isset($client_details['case_activity'])) {  if($client_details['case_activity'] == 1 ) echo 'selected' ; }?>>Yes</option>
       <option value="2" <?php if(isset($client_details['case_activity'])) { if($client_details['case_activity'] == 2 ) echo 'selected' ; } ?>>No</option>  
    </select>
  </div>
</div>


</div>
<hr style="border-top: 2px solid #bb4c4c;">
<div class="box-header with-border">
<h5 class="box-title">Client Spoc Details</h5>
<button type="button" style="float: right;" class="btn btn-info btn-xs" id="addSpocModal"><i class="fa fa-plus"></i> Add Spoc</button>
</div>
<?php
if(isset($client_spoc_details)){
foreach ($client_spoc_details as $key => $value) {
  ?>
    <input type="hidden" name="spoc_id[]" id="spoc_id" maxlength="40" value="<?=$value['id'] ?>" class="form-control">
  <div class="row">
  <div class="col-sm-3 form-group">
    <label>Spoc Name <label class="error">*</label></label>
    <input type="text" name="spoc_name[]" id="spoc_name" maxlength="40" value="<?=$value['spoc_name'] ?>" class="form-control">
    <?php echo form_error('spoc_name'); ?>
  </div>

    <div class="col-sm-3 form-group">
    <label>Spoc Mobile No</label>
    <input type="text" name="spoc_mobile_no[]" id="spoc_mobile_no" maxlength="10" value="<?=$value['spoc_mobile'] ?>"  class="form-control">
    <?php echo form_error('spoc_mobile_no'); ?>
  </div>

  <div class="col-sm-3 form-group">
    <label>Spoc Email ID <label class="error">*</label></label>
    <input type="email" name="spoc_email[]" id="spoc_email" maxlength="40" value="<?=$value['spoc_email'] ?>" class="form-control">
    <?php echo form_error('spoc_email'); ?>
  </div>

  <div class="col-sm-3 form-group">
    <label>CC Group</label>
    <input type="text" name="spoc_manager_email[]" id="spoc_manager_email"  value="<?=$value['spoc_manager_email'] ?>"  class="form-control" multiple>
    <?php echo form_error('spoc_manager_email'); ?>
  </div>
</div>
  <?php
}
}
?>
<div class="getSpocDiv">
  <div class="row">
  <div class="col-sm-3 form-group">
    <label>Spoc Name <label class="error">*</label></label>
    <input type="text" name="spoc_name[]" id="spoc_name" maxlength="40" class="form-control">
    <?php echo form_error('spoc_name'); ?>
  </div> 

    <div class="col-sm-3 form-group">
    <label>Spoc Mobile No</label>
    <input type="text" name="spoc_mobile_no[]" id="spoc_mobile_no" maxlength="10" minlength="10" class="form-control">
    <?php echo form_error('spoc_mobile_no'); ?>
  </div>
  <div class="col-sm-3 form-group">
    <label>Spoc Email ID <label class="error">*</label></label>
    <input type="email" name="spoc_email[]" id="spoc_email" maxlength="40" class="form-control">
    <?php echo form_error('spoc_email'); ?>
  </div>

  <div class="col-sm-3 form-group">
    <label>CC Group</label>
    <input type="Text" name="spoc_manager_email[]" id="spoc_manager_email"   class="form-control" multiple>
    <?php echo form_error('spoc_manager_email'); ?>
  </div>
  </div>
</div>
<hr style="border-top: 2px solid #bb4c4c;">
<div id="appendSpocModal"></div>
<div class="clearfix"></div>
<div class="box-header with-border">
<h5 class="box-title">Component Details</h5>
</div>
<div class="row">
<div class='col-sm-2 form-group'>
  <label>Components <span class="error"> *</span></label>
</div>
<div class='col-sm-2 form-group'>
  <label>Scope of work</label>
</div>
<div class='col-sm-2 form-group'>
  <label>Mode of verification</label>
</div>
<div class='col-sm-2 form-group'>
  <label>TAT</label>
</div>

<div class='col-sm-2 form-group'>
  <label>Price</label>
</div>
<div class='col-sm-2 form-group'>
  <label>Multiple Add/Not</label>
</div>

</div>


<?php

if(!empty($client_details))

{
  $component_id = explode(',', $client_details['component_id']);
 
  $component_name = json_decode($client_details['component_name'],true);
  $mode_of_verification = json_decode($client_details['mode_of_verification'],true);
  $scope_of_work = json_decode($client_details['scope_of_work'],true);
  $interim_report = json_decode($client_details['interim_report'],true);
  $final_report = json_decode($client_details['final_report'],true);
  $price = json_decode($client_details['price'],true);
  $candidate_component_count = explode(',', $client_details['candidate_component_count']);

  foreach ($components as $key => $value)
  {


    $data = array('name'          => "components[]",
                  'id'            => 'components',
                  'value'         => $value['component_key']
              );
    if(in_array($value['component_key'], $component_id))
      $data['checked'] = TRUE;

    echo "<div class='row'><div class='col-sm-2 form-group'><div class='checkbox'><label style='display: block ruby;'>";
    echo form_checkbox($data);
    echo "<input type='text' name= 'component_name[]' id='".$value['component_key']."' class='form-control' value='".$component_name[$value['component_key']]."''>";
    echo "</label></div></div>";
    if($value['component_key'] == "addrver")
    {
    $address_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown("scope_of_work[]", $address_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
    echo "</div>";

     $address_mode_of_verification = array('verbal'=> 'Verbal','physical'=>'Physical','digital'=>'Digital');
    
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown('mode_of_verification[]', $address_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
    echo "</div>";

    }
    elseif($value['component_key'] == "empver")
    {
       $employee_scope_of_work = array('no'=> 'No','all'=>'All','previous employer'=>'Previous employer','last 2 employers'=> 'Last 2 employers','last 2 years'=> 'Last 2 years','last 5 years'=> 'Last 5 years');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $employee_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $employee_mode_of_verification = array('verbal'=> 'Verbal','written'=>'Written/Summary','on client approval'=>'On Client Approval');
    
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown('mode_of_verification[]', $employee_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
    echo "</div>";
    }
    elseif($value['component_key'] == "eduver")
    { 

      $education_scope_of_work = array('no'=> 'No','all'=>'All','highest qualification'=>'Highest Qualification','last 2 highest qualification'=> 'Last 2 highest qualification','ssc/hsc'=> '
        SSC/HSC');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $education_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $education_mode_of_verification = array('verbal'=> 'Verbal','online'=>'Online','written'=>'Written','letter head'=>'Letter Head');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $education_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }

     elseif($value['component_key'] == "refver")
    { 

      $reference_scope_of_work = array('1'=> '1','2'=>'2','3'=>'3','4'=> '4');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $reference_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $reference_mode_of_verification = array('verbal'=> 'Verbal','written'=>'Written/Summary',''=>'On Client Approval');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $reference_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }

     elseif($value['component_key'] == "courtver")
    { 

      $court_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $court_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $court_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $court_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }

      elseif($value['component_key'] == "globdbver")
    { 

      $global_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $global_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $global_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $global_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
     
    elseif($value['component_key'] == "narcver")
    { 

      $drugs_scope_of_work = array('5 panel'=> '5 Panel','7 panel'=> '7 Panel');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $drugs_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $drugs_mode_of_verification = array('home visit'=>'Home Visit','office visit'=>'Office Visit','lab visit'=>'Lab Visit');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $drugs_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
    elseif($value['component_key'] == "crimver")
    { 

      $pcc_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $pcc_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $pcc_mode_of_verification = array('official'=>'Official','verbal'=>'Verbal');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $pcc_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
     elseif($value['component_key'] == "identity")
    { 

      $pcc_scope_of_work = array('Aadhar/Pan card'=> 'Aadhar/Pan card');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $pcc_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $pcc_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $pcc_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
    elseif($value['component_key'] == "cbrver")
    { 

      $credit_report_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $credit_report_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $credit_report_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $credit_report_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }

    elseif($value['component_key'] == "social_media")
    { 

      $social_media_report_scope_of_work = array('online'=> 'Online');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $social_media_report_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $social_media_report_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $social_media_report_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
    
    echo "<div class='col-sm-2 form-group'>";
    echo "<input type='number' min='0' name='tat_".$value['component_key']."' id='".$value['component_key']."' value='".@$client_details['tat_'.$value['component_key']]."' class='form-control'>";
    echo "</div>";
  
    echo "<div class='col-sm-2 form-group'>";
    echo "<input type='number' min='0' name='price[]' id='".$value['component_key']."' class='form-control' value='".$price[$value['component_key']]."'>";
    echo "</div>";
    
    if(isset($client_details['candidate_add_component'])) 
    {  
      if($client_details['candidate_add_component'] == 1 )
      {
        $style = "style = 'display:block;'";
      }

      if($client_details['candidate_add_component'] == 2 )
      {
        $style = "style = 'display:none;'";
      }
    }
    else
    {
       $style = "style = 'display:block;'";
    }

  $data = array('name'          => "candidate_component_count[]",
                'id'            => 'candidate_component_count',
                'value'         => $value['component_key']
            );
  if(in_array($value['component_key'], $candidate_component_count))
    $data['checked'] = TRUE;



  echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group show_hide_candidate_component' $style><div class='checkbox'><label>";
  echo form_checkbox($data);
  echo "</div>";
  echo "</div>";
  echo "</div>";
    echo "<div class='clearfix'></div>";
  }
}
else
{

  foreach ($components as $key => $value)
  {
    $data = array('name'          => "components[]",
                  'id'            => 'components',
                  'value'         => $value['component_key']
              );
    echo "<div class='row'><div class='col-md-2 col-sm-12 col-xs-2 form-group'><div class='checkbox'><label style='display: block ruby;'>";
    echo form_checkbox($data);
    echo "<input type='text' name= 'component_name[]' id='".$value['component_key']."'  class='form-control' value='".$value['show_component_name']."''>";
    echo "</label></div></div>";

    if($value['component_key'] == "addrver")
    {
    $address_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown("scope_of_work[]", $address_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
    echo "</div>";

     $address_mode_of_verification = array('verbal'=> 'Verbal','physical'=>'Physical','digital'=>'Digital');
    
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown('mode_of_verification[]', $address_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
    echo "</div>";

    }
    elseif($value['component_key'] == "empver")
    {
       $employee_scope_of_work = array('no'=> 'No','all'=>'All','previous employer'=>'Previous employer','last 2 employers'=> 'Last 2 employers','last 2 years'=> 'Last 2 years','last 5 years'=> 'Last 5 years');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $employee_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $employee_mode_of_verification = array('verbal'=> 'Verbal','written'=>'Written/Summary','on client approval'=>'On Client Approval');
    
    echo "<div class='col-sm-2 form-group'>";
    echo form_dropdown('mode_of_verification[]', $employee_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
    echo "</div>";
    }
    elseif($value['component_key'] == "eduver")
    { 

      $education_scope_of_work = array('no'=> 'No','all'=>'All','highest qualification'=>'Highest Qualification','last 2 highest qualification'=> 'Last 2 highest qualification','ssc/hsc'=> '
        SSC/HSC');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $education_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $education_mode_of_verification = array('verbal'=> 'Verbal','online'=>'Online','written'=>'Written','letter head'=>'Letter Head');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $education_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }

    elseif($value['component_key'] == "refver")
    { 

      $reference_scope_of_work = array('1'=> '1','2'=>'2','3'=>'3','4'=> '4');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $reference_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $reference_mode_of_verification = array('verbal'=> 'Verbal','written'=>'Written/Summary',''=>'On Client Approval');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $reference_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }

    elseif($value['component_key'] == "courtver")
    { 

      $court_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $court_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $court_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $court_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }

    elseif($value['component_key'] == "globdbver")
    { 

      $global_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $global_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $global_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $global_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }
     
    elseif($value['component_key'] == "narcver")
    { 

      $drugs_scope_of_work = array('5 panel'=> '5 Panel','7 panel'=> '7 Panel');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $drugs_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

      $drugs_mode_of_verification = array('home visit'=>'Home Visit','office visit'=>'Office Visit','lab visit'=>'Lab Visit');

    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $drugs_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }
    elseif($value['component_key'] == "crimver")
    { 

      $pcc_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $pcc_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $pcc_mode_of_verification = array('official'=>'Official','verbal'=>'Verbal');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $pcc_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }
    elseif($value['component_key'] == "identity")
    { 

      $pcc_scope_of_work = array('Aadhar/Pan card'=> 'Aadhar/Pan card');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $pcc_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $pcc_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $pcc_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }
    elseif($value['component_key'] == "cbrver")
    { 

      $credit_report_scope_of_work = array('no'=> 'No','all'=>'All','permanent'=>'Permanent','current'=> 'Current');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $credit_report_scope_of_work, set_value('scope_of_work'), 'class="form-control" id="scope_of_work"');
       echo "</div>";

     $credit_report_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $credit_report_mode_of_verification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
     echo "</div>";

    }

    elseif($value['component_key'] == "social_media")
    { 

      $social_media_report_scope_of_work = array('online'=> 'Online');
       echo "<div class='col-sm-2 form-group'>";
       echo form_dropdown("scope_of_work[]", $social_media_report_scope_of_work, set_value('scope_of_work',@$scope_of_work[$value['component_key']]), 'class="custom-select" id="scope_of_work"');
       echo "</div>";

     $social_media_report_mode_of_verification = array('written'=>'Written');
    
     echo "<div class='col-sm-2 form-group'>";
     echo form_dropdown('mode_of_verification[]', $social_media_report_mode_of_verification, set_value('mode_of_verification',@$mode_of_verification[$value['component_key']]), 'class="custom-select" id="mode_of_verification"');
     echo "</div>";

    }
 
    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'>";
    echo "<input type='number' min='0' name='tat_".$value['component_key']."' id='".$value['component_key']."' class='form-control'>";
    echo "</div>";
   
    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group'>";
    echo "<input type='number' min='0' name='price[]' id='".$value['component_key']."' class='form-control'>";
    echo "</div>";
      $data = array('name' => "candidate_component_count[]",
        'id' => 'candidate_component_count',
        'value'   => $value['component_key']
       );
      if(in_array($value['component_key'], $components))
      $data['checked'] = TRUE;



    echo "<div class='col-md-2 col-sm-12 col-xs-2 form-group show_hide_candidate_component' style='display:none;'><div class='checkbox'><label>";
    echo form_checkbox($data);
    echo "</div>";
    echo "</div>";
    
    echo "</div>";
  }
}
   //echo "<button type='button' name='export' id='export' class='btn btn-info form-control export_sla' data-val=".$client_details['id'].">Test</button>";
?>
  
<div class="clearfix"></div>
<!--<div id="sla_view" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'frm_sla','id'=>'frm_sla')); ?>
    <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title" >SLA For <span id="client_component1"></span></h4>
   <input type="hidden" name="client_name_id" id="client_name_id" value="<?php echo $client_details['id']; ?>" class="form-control">

    <input type="hidden" name="client_component" id="client_component" value="">
    <div id ="address_show" style="display: none;">
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_client_name" id="address_client_name" value="Client Name disclosure" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $address_client_name_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('address_client_name_selection', $address_client_name_selection, set_value('address_client_name_selection'), 'class="form-control" id="address_client_name_selection"');
        echo form_error('address_client_name_selection');
      ?>
     
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_client_name_remark" id="address_client_name_remark" value="" class="form-control" >
   
    </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_candidate_calling" id="address_candidate_calling" value="Candidate calling" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <?php
        $address_candidate_calling_selection = array('Yes'=> 'Yes','No'=>'No');
        echo form_dropdown('address_candidate_calling_selection', $address_candidate_calling_selection, set_value('address_candidate_calling_selection'), 'class="form-control" id="address_candidate_calling_selection"');
        echo form_error('address_candidate_calling_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_candidate_calling_remark" id="address_candidate_calling_remark" value="" class="form-control" >
   
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_add_charges" id="address_add_charges" value="Add on charges (If any)" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $address_add_charges_selection = array('less than or equal to INR 500'=> '<= INR 500','No Limit'=>'No Limit','On client approval'=>'On client approval');
        echo form_dropdown('address_add_charges_selection', $address_add_charges_selection, set_value('address_add_charges_selection'), 'class="form-control" id="address_add_charges_selection"');
        echo form_error('address_add_charges_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_add_charges_remark" id="address_add_charges_remark" value="" class="form-control" >
   
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="address_mode_of_verification" id="address_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $address_mode_of_verification_selection = array('Verbal'=> 'Verbal','Physical'=>'Physical');
        echo form_dropdown('address_mode_of_verification_selection', $address_mode_of_verification_selection, set_value('address_mode_of_verification_selection'), 'class="form-control" id="address_mode_of_verification_selection"');
        echo form_error('address_mode_of_verification_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_mode_of_verification_remark" id="address_mode_of_verification_remark" value="" class="form-control" >
   
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_display_matrix" id="address_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $address_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('address_display_matrix_selection', $address_display_matrix_selection, set_value('address_display_matrix_selection'), 'class="form-control" id="address_display_matrix_selection"');
        echo form_error('address_display_matrix_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_display_matrix_remark" id="address_display_matrix_remark" value="" class="form-control" >
    </div>

       <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="address_insufficiency" id="address_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $address_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('address_insufficiency_selection', $address_insufficiency_selection, set_value('address_insufficiency_selection'), 'class="form-control" id="address_insufficiency_selection"');
        echo form_error('address_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_insufficiency_remark" id="address_insufficiency_remark"  class="form-control" value="If no address/documents were provided, Incorrect or short address, Candidate is no longer employed with the client. Any pending approvals from the client" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_unable_verify" id="address_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $address_unable_verify_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval','If door was locked'=> 'If door was locked','If candidate is not reachable'=> 'If candidate is not reachable');
        echo form_dropdown('address_unable_verify_selection', $address_unable_verify_selection, set_value('address_unable_verify_selection'), 'class="form-control" id="address_unable_verify_selection"');
        echo form_error('address_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_unable_verify_remark" id="address_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="address_insufficiency_not_cleared" id="address_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $address_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('address_insufficiency_not_cleared_selection', $address_insufficiency_not_cleared_selection, set_value('address_insufficiency_not_cleared_selection'), 'class="form-control" id="address_insufficiency_not_cleared_selection"');
        echo form_error('address_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="address_insufficiency_not_cleared_remark" id="address_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>

    <div id ="employment_show" style="display: none;">

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_client_name" id="employment_client_name" value="Client Name disclosure" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_client_name_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('employment_client_name_selection', $employment_client_name_selection, set_value('employment_client_name_selection'), 'class="form-control" id="employment_client_name_selection"');
        echo form_error('employment_client_name_selection');
      ?>
     
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_client_name_remark" id="employment_client_name_remark" value="" class="form-control" >
   
    </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_candidate_calling" id="employment_candidate_calling" value="Candidate calling" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <?php
        $employment_candidate_calling_selection = array('Yes'=> 'Yes','No'=>'No');
        echo form_dropdown('employment_candidate_calling_selection', $employment_candidate_calling_selection, set_value('employment_candidate_calling_selection'), 'class="form-control" id="employment_candidate_calling_selection"');
        echo form_error('employment_candidate_calling_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_candidate_calling_remark" id="employment_candidate_calling_remark" value="" class="form-control" >
   
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_add_charges" id="employment_add_charges" value="Add on charges (If any)" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $employment_add_charges_selection = array('less than or equal to INR 500'=> '<= INR 500','No Limit'=>'No Limit','On client approval'=>'On client approval');
        echo form_dropdown('employment_add_charges_selection', $employment_add_charges_selection, set_value('employment_add_charges_selection'), 'class="form-control" id="employment_add_charges_selection"');
        echo form_error('employment_add_charges_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_add_charges_remark" id="employment_add_charges_remark" value="" class="form-control" >
   
    </div>

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="employment_overseas_check" id="employment_overseas_check" value="Overseas check" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_overseas_check_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('employment_overseas_check_selection', $employment_overseas_check_selection, set_value('employment_overseas_check_selection'), 'class="form-control" id="employment_overseas_check_selection"');
        echo form_error('employment_overseas_check_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_overseas_check_remark" id="employment_overseas_check_remark" value="" class="form-control" >
   
    </div>

    
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="employment_mode_of_verification" id="employment_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_mode_of_verification_selection = array('Verbal'=> 'Verbal','Written/Summary'=>'Written/Summary','On client approval'=>'On client approval');
        echo form_dropdown('employment_mode_of_verification_selection', $employment_mode_of_verification_selection, set_value('employment_mode_of_verification_selection'), 'class="form-control" id="employment_mode_of_verification_selection"');
        echo form_error('employment_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_mode_of_verification_remark" id="employment_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_candidate_previously_employed" id="employment_candidate_previously_employed" value="Should employment check be initiated if the candidate was previously employed with the client" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_candidate_previously_employed_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('employment_candidate_previously_employed_selection', $employment_candidate_previously_employed_selection, set_value('employment_candidate_previously_employed_selection'), 'class="form-control" id="employment_candidate_previously_employed_selection"');
        echo form_error('employment_candidate_previously_employed_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_candidate_previously_employed_remark" id="employment_candidate_previously_employed_remark" value="" class="form-control" >
    </div>


    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_current" id="employment_current" value="Current Employment" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_current_selection = array('Yes'=> 'Yes','No'=>'No','Raise Insufficiency'=>'Raise Insufficiency');
        echo form_dropdown('employment_current_selection', $employment_current_selection, set_value('employment_current_selection'), 'class="form-control" id="employment_current_selection"');
        echo form_error('employment_current_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_current_remark" id="employment_current_remark" value="" class="form-control" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_supervisor_calling" id="employment_supervisor_calling" value="Supervisor calling" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $emploment_supervisor_calling_selection = array('Yes'=> 'Yes','No'=>'No','Post 3 attempts with HR'=>'Post 3 attempts with HR');
        echo form_dropdown('emploment_supervisor_calling_selection', $emploment_supervisor_calling_selection, set_value('emploment_supervisor_calling_selection'), 'class="form-control" id="emploment_supervisor_calling_selection"');
        echo form_error('emploment_supervisor_calling_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="emploment_supervisor_calling_remark" id="emploment_supervisor_calling_remark" value="" class="form-control" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_left_organization" id="employment_left_organization" value="Can supervisor be called if he/she has left the organisation" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
      $employment_left_organization_selection = array('Yes'=> 'Yes','No'=>'No','Raise Insufficiency For Alternate Supervisor'=>'Raise Insufficiency For Alternate Supervisor');
        echo form_dropdown('employment_left_organization_selection', $employment_left_organization_selection, set_value('employment_left_organization_selection'), 'class="form-control" id="employment_left_organization_selection"');
        echo form_error('employment_left_organization_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_left_organization_remark" id="employment_left_organization_remark" value="" class="form-control" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_site_visit" id="employment_site_visit" value="Site Visit" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_site_visit_selection = array('Yes'=> 'Yes','No'=>'No','
          On client approval'=>'On client approval','If company is not registered with MCA'=> 'If company is not registered with MCA','If no revert from HR/Supervisor post 5 attempts'=>'If no revert from HR/Supervisor post 5 attempts');
        echo form_dropdown('employment_site_visit_selection', $employment_site_visit_selection, set_value('employment_site_visit_selection'), 'class="form-control" id="employment_site_visit_selection"');
        echo form_error('employment_site_visit_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_site_visit_remark" id="employment_site_visit_remark" value="" class="form-control" >
    </div>


    

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="emploment_display_matrix" id="emploment_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('employment_display_matrix_selection', $employment_display_matrix_selection, set_value('employment_display_matrix_selection'), 'class="form-control" id="employment_display_matrix_selection"');
        echo form_error('employment_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_display_matrix_remark" id="employment_display_matrix_remark" value="" class="form-control" >
    </div>

       <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="employment_insufficiency" id="employment_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $employment_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('employment_insufficiency_selection', $employment_insufficiency_selection, set_value('employment_insufficiency_selection'), 'class="form-control" id="employment_insufficiency_selection"');
        echo form_error('employment_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_insufficiency_remark" id="employment_insufficiency_remark"  class="form-control" value="Relieving Letter, Service Letter, Client LOA, Digitall signed LOA, Physically signed LOA, Employee Code, Supervisor Details, Alternate Supervisor Details, Company details and contact information, Any pending approvals from the client." >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_unable_verify" id="employment_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $employment_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('employment_unable_verify_selection', $employment_unable_verify_selection, set_value('employment_unable_verify_selection'), 'class="form-control" id="employment_unable_verify_selection"');
        echo form_error('employment_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_unable_verify_remark" id="employment_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_insufficiency_not_cleared" id="employment_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $employment_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('employment_insufficiency_not_cleared_selection', $employment_insufficiency_not_cleared_selection, set_value('employment_insufficiency_not_cleared_selection'), 'class="form-control" id="employment_insufficiency_not_cleared_selection"');
        echo form_error('employment_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="employment_insufficiency_not_cleared_remark" id="employment_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>



    <div id ="education_show" style="display: none;">

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_overseas_degree" id="education_overseas_degree" value="Overseas degree/university" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $education_overseas_degree_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('education_overseas_degree_selection', $education_overseas_degree_selection, set_value('education_overseas_degree_selection'), 'class="form-control" id="education_overseas_degree_selection"');
        echo form_error('education_overseas_degree_selection');
      ?>
     
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="employment_overseas_degree_remark" id="employment_overseas_degree_remark" value="" class="form-control" >
   
    </div>
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_candidate_calling" id="education_candidate_calling" value="Candidate calling" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <?php
        $education_candidate_calling_selection = array('Yes'=> 'Yes','No'=>'No');
        echo form_dropdown('education_candidate_calling_selection', $education_candidate_calling_selection, set_value('education_candidate_calling_selection'), 'class="form-control" id="education_candidate_calling_selection"');
        echo form_error('education_candidate_calling_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_candidate_calling_remark" id="education_candidate_calling_remark" value="" class="form-control" >
   
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_initiate_pursuing_degree" id="education_initiate_pursuing_degree" value="Initiate pursuing degree/diploma" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $education_initiate_pursuing_degree_selection = array('Yes'=> 'Yes','No'=>'No');
        echo form_dropdown('education_initiate_pursuing_degree_selection', $education_initiate_pursuing_degree_selection, set_value('education_initiate_pursuing_degree_selection'), 'class="form-control" id="education_initiate_pursuing_degree_selection"');
        echo form_error('education_initiate_pursuing_degree_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_initiate_pursuing_degree_remark" id="education_initiate_pursuing_degree_remark" value="" class="form-control" >
   
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_add_charges" id="education_add_charges" value="Add on charges (If any)" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $education_add_charges_selection = array('less than equal to INR 500'=> '<= INR 500','No Limit'=>'No Limit','On client approval'=>'On client approval');
        echo form_dropdown('education_add_charges_selection', $education_add_charges_selection, set_value('education_add_charges_selection'), 'class="form-control" id="education_add_charges_selection"');
        echo form_error('education_add_charges_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_add_charges_remark" id="education_add_charges_remark" value="" class="form-control" >
   
    </div>

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="education_mode_of_verification" id="education_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $education_mode_of_verification_selection = array('Online'=> 'Online','Verbal'=> 'Verbal','Signed/Stamped'=> 'Signed/Stamped');
        echo form_dropdown('education_mode_of_verification_selection', $education_mode_of_verification_selection, set_value('education_mode_of_verification_selection'), 'class="form-control" id="education_mode_of_verification_selection"');
        echo form_error('education_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_mode_of_verification_remark" id="education_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_display_matrix" id="education_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $education_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('education_display_matrix_selection', $education_display_matrix_selection, set_value('education_display_matrix_selection'), 'class="form-control" id="education_display_matrix_selection"');
        echo form_error('education_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_display_matrix_remark" id="education_display_matrix_remark" value="" class="form-control" >
    </div>

       <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="education_insufficiency" id="education_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $education_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('education_insufficiency_selection', $education_insufficiency_selection, set_value('education_insufficiency_selection'), 'class="form-control" id="education_insufficiency_selection"');
        echo form_error('education_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_insufficiency_remark" id="education_insufficiency_remark"  class="form-control" value="Required documents, Unclear documents, Any pending approvals from the client." >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_unable_verify" id="education_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $education_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('education_unable_verify_selection', $education_unable_verify_selection, set_value('education_unable_verify_selection'), 'class="form-control" id="education_unable_verify_selection"');
        echo form_error('education_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_unable_verify_remark" id="education_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="education_insufficiency_not_cleared" id="education_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $education_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('education_insufficiency_not_cleared_selection', $education_insufficiency_not_cleared_selection, set_value('education_insufficiency_not_cleared_selection'), 'class="form-control" id="education_insufficiency_not_cleared_selection"');
        echo form_error('education_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="education_insufficiency_not_cleared_remark" id="education_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>

    <div id ="references_show" style="display: none;">

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_client_name" id="references_client_name" value="Client Name disclosure" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $references_client_name_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('references_client_name_selection', $references_client_name_selection, set_value('references_client_name_selection'), 'class="form-control" id="references_client_name_selection"');
        echo form_error('references_client_name_selection');
      ?>
     
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_client_name_remark" id="references_client_name_remark" value="" class="form-control" >
   
    </div>

      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_candidate_calling" id="references_candidate_calling" value="Candidate calling" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <?php
        $references_candidate_calling_selection = array('Yes'=> 'Yes','No'=>'No');
        echo form_dropdown('references_candidate_calling_selection', $references_candidate_calling_selection, set_value('references_candidate_calling_selection'), 'class="form-control" id="references_candidate_calling_selection"');
        echo form_error('references_candidate_calling_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_candidate_calling_remark" id="references_candidate_calling_remark" value="" class="form-control" >
    </div>

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="references_overseas_check" id="references_overseas_check" value="Overseas check" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $references_overseas_check_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('references_overseas_check_selection', $references_overseas_check_selection, set_value('references_overseas_check_selection'), 'class="form-control" id="references_overseas_check_selection"');
        echo form_error('references_overseas_check_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_overseas_check_remark" id="references_overseas_check_remark" value="" class="form-control" >
   
    </div>

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="references_mode_of_verification" id="references_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $references_mode_of_verification_selection = array('Verbal'=> 'Verbal','Writte/Summary'=> 'Writte/Summary','On client approval'=> 'On client approval');
        echo form_dropdown('references_mode_of_verification_selection', $references_mode_of_verification_selection, set_value('references_mode_of_verification_selection'), 'class="form-control" id="references_mode_of_verification_selection"');
        echo form_error('references_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_mode_of_verification_remark" id="references_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_display_matrix" id="references_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $references_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('references_display_matrix_selection', $references_display_matrix_selection, set_value('references_display_matrix_selection'), 'class="form-control" id="references_display_matrix_selection"');
        echo form_error('references_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_display_matrix_remark" id="references_display_matrix_remark" value="" class="form-control" >
    </div>

       <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="references_insufficiency" id="references_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $references_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('references_insufficiency_selection', $references_insufficiency_selection, set_value('references_insufficiency_selection'), 'class="form-control" id="references_insufficiency_selection"');
        echo form_error('references_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_insufficiency_remark" id="references_insufficiency_remark"  class="form-control" value="Name of reference, No number/Incorrect/Invalid number, Reference refused to provide verification, Any pending approvals from the client" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_unable_verify" id="references_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $references_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('references_unable_verify_selection', $references_unable_verify_selection, set_value('references_unable_verify_selection'), 'class="form-control" id="references_unable_verify_selection"');
        echo form_error('references_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_unable_verify_remark" id="references_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="references_insufficiency_not_cleared" id="references_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $references_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('references_insufficiency_not_cleared_selection', $references_insufficiency_not_cleared_selection, set_value('references_insufficiency_not_cleared_selection'), 'class="form-control" id="references_insufficiency_not_cleared_selection"');
        echo form_error('references_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="references_insufficiency_not_cleared_remark" id="references_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>
    

  <div id ="court_show" style="display: none;">

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="court_mode_of_verification" id="court_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $court_mode_of_verification_selection = array('Written'=> 'Written');
        echo form_dropdown('court_mode_of_verification_selection', $court_mode_of_verification_selection, set_value('court_mode_of_verification_selection'), 'class="form-control" id="court_mode_of_verification_selection"');
        echo form_error('court_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_mode_of_verification_remark" id="court_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_display_matrix" id="court_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $court_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('court_display_matrix_selection', $court_display_matrix_selection, set_value('court_display_matrix_selection'), 'class="form-control" id="court_display_matrix_selection"');
        echo form_error('court_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_display_matrix_remark" id="court_display_matrix_remark" value="" class="form-control" >
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="court_insufficiency" id="court_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $court_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('court_insufficiency_selection', $court_insufficiency_selection, set_value('court_insufficiency_selection'), 'class="form-control" id="court_insufficiency_selection"');
        echo form_error('court_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_insufficiency_remark" id="court_insufficiency_remark"  class="form-control" value="If no address/documents were provided, Incorrect or short address, DOB, Father Name" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_unable_verify" id="court_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $court_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('court_unable_verify_selection', $court_unable_verify_selection, set_value('court_unable_verify_selection'), 'class="form-control" id="court_unable_verify_selection"');
        echo form_error('court_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_unable_verify_remark" id="court_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="court_insufficiency_not_cleared" id="court_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $court_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('court_insufficiency_not_cleared_selection', $court_insufficiency_not_cleared_selection, set_value('court_insufficiency_not_cleared_selection'), 'class="form-control" id="court_insufficiency_not_cleared_selection"');
        echo form_error('court_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="court_insufficiency_not_cleared_remark" id="court_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>


    <div id ="global_show" style="display: none;">

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="global_mode_of_verification" id="global_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $global_mode_of_verification_selection = array('Online'=> 'Online');
        echo form_dropdown('global_mode_of_verification_selection', $global_mode_of_verification_selection, set_value('global_mode_of_verification_selection'), 'class="form-control" id="global_mode_of_verification_selection"');
        echo form_error('global_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_mode_of_verification_remark" id="global_mode_of_verification_remark" value="Sanction Check Database, Global Web Media, Severe Crime Database, Regulatory and compliance check" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_display_matrix" id="global_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $global_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('global_display_matrix_selection', $global_display_matrix_selection, set_value('global_display_matrix_selection'), 'class="form-control" id="global_display_matrix_selection"');
        echo form_error('global_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_display_matrix_remark" id="global_display_matrix_remark" value="" class="form-control" >
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="global_insufficiency" id="global_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $global_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('global_insufficiency_selection', $global_insufficiency_selection, set_value('global_insufficiency_selection'), 'class="form-control" id="global_insufficiency_selection"');
        echo form_error('global_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_insufficiency_remark" id="global_insufficiency_remark"  class="form-control" value="If no address/documents were provided, Incorrect or short address, DOB, Father Name" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_unable_verify" id="global_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $global_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('global_unable_verify_selection', $global_unable_verify_selection, set_value('global_unable_verify_selection'), 'class="form-control" id="global_unable_verify_selection"');
        echo form_error('global_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_unable_verify_remark" id="global_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="global_insufficiency_not_cleared" id="global_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $global_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('global_insufficiency_not_cleared_selection', $global_insufficiency_not_cleared_selection, set_value('global_insufficiency_not_cleared_selection'), 'class="form-control" id="global_insufficiency_not_cleared_selection"');
        echo form_error('global_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="global_insufficiency_not_cleared_remark" id="global_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>

    <div id ="drugs_show" style="display: none;">

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="drugs_mode_of_verification" id="drugs_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $drugs_mode_of_verification_selection = array('Written'=> 'Written');
        echo form_dropdown('drugs_mode_of_verification_selection', $drugs_mode_of_verification_selection, set_value('drugs_mode_of_verification_selection'), 'class="form-control" id="drugs_mode_of_verification_selection"');
        echo form_error('drugs_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_mode_of_verification_remark" id="drugs_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_display_matrix" id="drugs_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $drugs_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('drugs_display_matrix_selection', $drugs_display_matrix_selection, set_value('drugs_display_matrix_selection'), 'class="form-control" id="drugs_display_matrix_selection"');
        echo form_error('drugs_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_display_matrix_remark" id="drugs_display_matrix_remark" value="" class="form-control" >
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="drugs_insufficiency" id="drugs_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $drugs_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('drugs_insufficiency_selection', $drugs_insufficiency_selection, set_value('drugs_insufficiency_selection'), 'class="form-control" id="drugs_insufficiency_selection"');
        echo form_error('drugs_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_insufficiency_remark" id="drugs_insufficiency_remark"  class="form-control" value="If candidate is not providing date and time for the test" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_unable_verify" id="drugs_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $drugs_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('drugs_unable_verify_selection', $drugs_unable_verify_selection, set_value('drugs_unable_verify_selection'), 'class="form-control" id="drugs_unable_verify_selection"');
        echo form_error('drugs_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_unable_verify_remark" id="drugs_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="drugs_insufficiency_not_cleared" id="drugs_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $drugs_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('drugs_insufficiency_not_cleared_selection', $drugs_insufficiency_not_cleared_selection, set_value('drugs_insufficiency_not_cleared_selection'), 'class="form-control" id="drugs_insufficiency_not_cleared_selection"');
        echo form_error('drugs_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="drugs_insufficiency_not_cleared_remark" id="drugs_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>


  <div id ="pcc_show" style="display: none;">

     
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <input type="text" name="pcc_mode_of_verification" id="pcc_mode_of_verification" value="Mode of verification" class="form-control" readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $pcc_mode_of_verification_selection = array('Written'=> 'Written');
        echo form_dropdown('pcc_mode_of_verification_selection', $pcc_mode_of_verification_selection, set_value('pcc_mode_of_verification_selection'), 'class="form-control" id="pcc_mode_of_verification_selection"');
        echo form_error('pcc_mode_of_verification_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_mode_of_verification_remark" id="pcc_mode_of_verification_remark" value="" class="form-control" >
   
    </div>
  

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_display_matrix" id="pcc_display_matrix" value="Disparity matrix" class="form-control" readonly>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $pcc_display_matrix_selection = array('Standard'=> 'Standard','Client specific'=>'Physical');
        echo form_dropdown('pcc_display_matrix_selection', $pcc_display_matrix_selection, set_value('pcc_display_matrix_selection'), 'class="form-control" id="pcc_display_matrix_selection"');
        echo form_error('pcc_display_matrix_selection');
      ?>

    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_display_matrix_remark" id="pcc_display_matrix_remark" value="" class="form-control" >
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
  
       <input type="text" name="pcc_insufficiency" id="pcc_insufficiency" value="Insufficiency" class="form-control"  readonly>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $pcc_insufficiency_selection = array('Yes'=> 'Yes');
        echo form_dropdown('pcc_insufficiency_selection', $pcc_insufficiency_selection, set_value('pcc_insufficiency_selection'), 'class="form-control" id="pcc_insufficiency_selection"');
        echo form_error('pcc_insufficiency_selection');
      ?>
  
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_insufficiency_remark" id="pcc_insufficiency_remark"  class="form-control" value="If no address/documents were provided, Incorrect or short address, DOB, Father Name" >
    </div>


     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_unable_verify" id="pcc_unable_verify" value="Unable to verify" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <?php
        $pcc_unable_verify_selection = array('Yes'=> 'Yes','If unable to connect/obtain verification post 7 days'=>'If unable to connect/obtain verification post 7 days','On client approval'=>'On client approval');
        echo form_dropdown('pcc_unable_verify_selection', $pcc_unable_verify_selection, set_value('pcc_unable_verify_selection'), 'class="form-control" id="pcc_unable_verify_selection"');
        echo form_error('pcc_unable_verify_selection');
      ?>
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_unable_verify_remark" id="pcc_unable_verify_remark" value="" class="form-control" >
   
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
      <input type="text" name="pcc_insufficiency_not_cleared" id="pcc_insufficiency_not_cleared" value="If Insufficiency is not cleared by client within 15 days of raising it" class="form-control" readonly>

    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
       <?php
        $pcc_insufficiency_not_cleared_selection = array('Yes'=> 'Yes','No'=>'No','On client approval'=>'On client approval');
        echo form_dropdown('pcc_insufficiency_not_cleared_selection', $pcc_insufficiency_not_cleared_selection, set_value('pcc_insufficiency_not_cleared_selection'), 'class="form-control" id="pcc_insufficiency_not_cleared_selection"');
        echo form_error('pcc_insufficiency_not_cleared_selection');
      ?>
   
   
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label></label>
     <input type="text" name="pcc_insufficiency_not_cleared_remark" id="pcc_insufficiency_not_cleared_remark" value="" class="form-control" >
   
    </div>
   </div>

    </div>
    <div class="modal-body append_model"></div>
    <div class="modal-footer">
      <button type="submit" id="btn_sla" name="btn_sla" class="btn btn-success">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>-->


<script>
  $("#frm_update_component :input,select").prop("disabled", true);
</script>

<script>
$(document).ready(function(){

  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true); 
  
  $('#frm_sla').validate({ 
    rules: {
      client_id  : {
        required : true
      }
    },
   
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'clients/save_sla'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_address').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#btn_address').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
});
</script>
<script type="text/javascript">
$(document).on('click', '.export_sla', function(){

   var client_id = $(this).data('val');
   var expt = "expt";

     if(client_id != "" && expt !="")
    {
        $.ajax({
            type:'POST',
            url : '<?php echo ADMIN_SITE_URL.'clients/sla_pdf' ?>',
            data : 'client_id='+client_id+'&expt='+expt,
          

            success:function(jdata)
            {
               var message =  jdata.message || '';
              (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
               // setTimeout(function () { location.reload(1); }, 5000);
                return;
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
         
            }
            
        });
    }
});


$(document).on('change', '#candidate_add', function(){
  var first_qc = $(this).val();

  if(first_qc == '1')
  {
     $('.show_hide_candidate_component').show();
  }
  if(first_qc == '2')
  {
    $('.show_hide_candidate_component').hide();
  }
});

$(document).on('change', '#candidate_upload', function(){
var candidate_upload =  $(this).val();
if(candidate_upload == "2" || candidate_upload == "3")
{
  $("#user_up").css('display', 'block');
  if(candidate_upload == "3")
  {
    $(".pre_post_disply").css('display', 'block');
  }
  else{
    $(".pre_post_disply").css('display', 'none');
  }
}
else{
  $("#user_up").css('display', 'none');
}
});

$(".select2").select2();
</script>