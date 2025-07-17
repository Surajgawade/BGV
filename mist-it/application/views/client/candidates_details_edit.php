 <!--<style type="text/css">
   a[data-toggle="collapse"] {
    position: unset;
}
 </style>

 <ul class="nav nav-tabs" id="myTab">
  <?php  
  echo "<li class='active' data-tab_name='candidate_tab'><a href='#candidate_tab' data-toggle='tab'>Candidate Details</a></li>";
 echo "<li role='presentation' data-tab_name='component_pending'  class='view_component_tab'><a href='#component_pending' aria-controls='home' role='tab' data-toggle='tab'>Component Pending</a></li>"; 

  echo "<li role='presentation' data-can_id='1' data-tab_name='component_approve'  class='view_component_tab'><a href='#component_approve' aria-controls='home' role='tab' data-toggle='tab'>Component Submitted</a></li>"; 
  ?>
  </ul>
<div class="tab-content">
<div class='active tab-pane' id='candidate_tab'>

  <section class="container" style="border-style: groove;  margin-top: 30px; border-style: groove; margin-left: 40px; margin-right: 30px; width: 93%;"> 
 <div class="box-primary">
  <div class="box-header">
    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <h3 class="box-title">Case Details</h3>
    </div>

     <div style="float: right;">
      <button class="btn btn-default btn-sm edit_btn_click" data-frm_name='frm_update_candidates' data-editUrl='<?= ($this->permission['access_candidates_list_edit']) ? encrypt($candidate_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
   </div>
  </div>
  <div class="clearfix"></div>
  <?php echo form_open('#', array('name'=>'frm_update_candidates','id'=>'frm_update_candidates')); ?>

   
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">

      <label >Select Client <span class="error"> *</span></label>
      <?php
        echo form_dropdown('clientid1', $this->session->userdata('client')['client_name'], set_value('clientid1',$candidate_details['clientid']), 'class="form-control" id="clientid1" ');
        echo form_error('clientid');
      ?>
    </div>

   
    <div class="col-md-1 col-sm-12 col-xs-1 form-group">
      <label>&nbsp;&nbsp;</label>
        <button class="btn btn-default" data-url ="<?= CLIENT_SITE_URL.'candidates/entity_package_view/' ?>"  data-toggle="modal" id="show_entity_package_modal" >Check</button>
      </div>
      <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',encrypt($candidate_details['id'])); ?>">
       <input type="hidden" name="clientid" id="clientid" value="<?php echo $candidate_details['clientid'];?>">
     <input type="hidden" name="cp_update_id" id="cp_update_id" value="<?php echo set_value('cp_update_id',$candidate_details['id']); ?>">

      <input type="hidden" name="selected_client" id="selected_client" value="<?php echo $candidate_details['clientid'];?>">
    <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $candidate_details['package'];?>">
    <input type="hidden" name="selected_entity" id="selected_entity" value="<?php echo $candidate_details['entity'];?>">
    <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $candidate_details['package'];?>">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Select Entity<span class="error"> *</span></label>
      <?php

        echo form_dropdown('entity', $client_components['entity'], set_value('entity'), 'class="form-control" id="entity"');
        echo form_error('entity');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Select Package<span class="error"> *</span></label>
      <?php

        echo form_dropdown('package', $client_components['package'], set_value('package'), 'class="form-control" id="package"');
        echo form_error('package');
      ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case Received Date<span class="error"> *</span></label>
      <input type="text" name="caserecddate" id="caserecddate" readonly value="<?php echo set_value('caserecddate',convert_db_to_display_date($candidate_details['caserecddate'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
        <?php echo form_error('caserecddate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Ref Number <span class="error"> *</span></label>
      <input type="text" name="ClientRefNumber"  id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>" class="form-control">
      <?php echo form_error('ClientRefNumber'); ?>
    </div>
    
    <div class="clearfix"></div>
    
    
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">
    <div class="box-header">
    <div class="col-md-12 col-sm-12 col-xs-12 form-group">

      <h3 class="box-title">Joining Details</h3>
    </div>
    </div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Date of Joining</label>
      <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining',convert_db_to_display_date($candidate_details['DateofJoining'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofJoining'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation</label>
      <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas',$candidate_details['DesignationJoinedas']); ?>" class="form-control">
      <?php echo form_error('DesignationJoinedas'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Branch Location</label>
      <input type="text" name="Location" id="Location" value="<?php echo set_value('Location',$candidate_details['Location']); ?>" class="form-control">
      <?php echo form_error('Location'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Department</label>
      <input type="text" name="Department" id="Department" value="<?php echo set_value('Department',$candidate_details['Department']); ?>" class="form-control">
      <?php echo form_error('Department'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employee Code</label>
      <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode',$candidate_details['EmployeeCode']); ?>" class="form-control">
      <?php echo form_error('EmployeeCode'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Work Experience</label>
       <?php
         $work_experiance = array(''=> 'Select Work Experience','fresher'=>'Fresher','experienced'=>'Experienced');
            
         echo form_dropdown('branch_name', $work_experiance, set_value('branch_name',$candidate_details['branch_name']), 'class="form-control" id="branch_name"');
         echo form_error('branch_name');

      ?>
     
      <?php echo form_error('branch_name'); ?>
    </div>
    <div class="clearfix"></div>-->

   <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Region</label>
      <input type="text" name="region" id="region" value="<?php echo set_value('region',$candidate_details['region']); ?>" class="form-control">
      <?php echo form_error('region'); ?>
    </div>-->
    
    <!--<div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <label >Remarks</label>
      <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks',$candidate_details['remarks']); ?></textarea>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">

    <div class="box-header">
       <div class="col-md-12 col-sm-12 col-xs-12 form-group">

      <h3 class="box-title">Candidate Details</h3>
    </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Candidate Name <span class="error"> *</span></label>
      <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control">
      <?php echo form_error('CandidateName'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Gender<span class="error"> *</span></label>
     <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="form-control" id="gender"');
        echo form_error('gender');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Date of Birth <span class="error"> *</span></label>
      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofBirth'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Father's Name <span class="error"> *</span></label>
      <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control">
      <?php echo form_error('NameofCandidateFather'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Mother's Name</label>
      <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName',$candidate_details['MothersName']); ?>" class="form-control">
      <?php echo form_error('MothersName'); ?>
    </div>

   
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Primary Contact<span class="error"> *</span></label>
      <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control">
      <?php echo form_error('CandidatesContactNumber'); ?>
    </div>
     
    <div class="clearfix"></div>


    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Contact No (2)</label>
      <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control">
      <?php echo form_error('ContactNo1'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Contact No (3)</label>
      <input type="text" name="ContactNo2" id="ContactNo2" maxlength="12" value="<?php echo set_value('ContactNo2',$candidate_details['ContactNo2']); ?>" class="form-control">
      <?php echo form_error('ContactNo2'); ?>
    </div>

   

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Email ID</label>
      <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id',$candidate_details['cands_email_id']); ?>" class="form-control">
      <?php echo form_error('cands_email_id'); ?>
    </div>

     <div class="clearfix"></div>


    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PAN No.</label>
      <input type="text" name="PANNumber" id="PANNumber" value="<?php echo set_value('PANNumber',$candidate_details['PANNumber']); ?>" class="form-control">
      <?php echo form_error('PANNumber'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>AADHAR No.</label>
      <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber',$candidate_details['AadharNumber']); ?>" class="form-control">
      <?php echo form_error('AadharNumber'); ?>
    </div>
 

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Passport No.</label>
      <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber',$candidate_details['PassportNumber']); ?>" class="form-control">
      <?php echo form_error('PassportNumber'); ?>
    </div>

      <div class="clearfix"></div>
   
    
   
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address</label>
      <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control"><?php echo set_value('prasent_address',$candidate_details['prasent_address']); ?></textarea>
    </div> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City</label>
      <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city',$candidate_details['cands_city']); ?>" class="form-control">
      <?php echo form_error('cands_city'); ?>
    </div>
        

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PIN Code</label>
      <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode',$candidate_details['cands_pincode']); ?>" class="form-control">
      <?php echo form_error('cands_pincode'); ?>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('cands_state', $states, set_value('cands_state',$candidate_details['cands_state']), 'class="form-control" id="cands_state"');
        echo form_error('cands_state');
      ?>
    </div> 

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <label>Email Candidate</label>
           <?php
              $email_candidate = array(''=> 'Select','complete details'=>'Complete details','partial details'=>'Partial Details','no'=>'No');
            
                 echo form_dropdown('email_candidate', $email_candidate, set_value('email_candidate',$candidate_details['email_candidate']), 'class="form-control" id="email_candidate"');
                 echo form_error('email_candidate');

            ?>
    </div>

    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">
    <div class="box-header">
     <div class="col-md-12 col-sm-12 col-xs-12 form-group">

      <h3 class="box-title">Attachments & other</h3>
    </div>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    
    <div class="clearfix"></div> 
    <div class="col-md-6 col-sm-12 col-xs-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.CANDIDATES.$candidate_details['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
   return false'><?= $value['file_name']?></a></li> <?php
      }
    }
    ?>
    </ol>
    </div>
  
    <div class="clearfix"></div> 
    <div class="box-body">
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <input type="submit" name="btn_update" id='btn_update' value="Update" class="btn btn-success">
      </div>
    </div>
  <?php echo form_close(); ?>
</div>
</section>

</div>

<?php
$components = json_decode($client_components['component_name'],true);
$selected_component = explode(',', $client_components['component_id']);
?>
<div id="component_pending" class="tab-pane fade in">
  <div class="container" style="border-style: groove; margin-top: 30px; margin-left: 40px; margin-right: 30px; width: 93%;">



   <div class="accordion md-accordion" id="accordionEx1" role="tablist" aria-multiselectable="true">

  
    <?php echo form_open('#', array('name'=>'create_address','id'=>'create_address')); ?>

    <?php 
    if(in_array('addrver',$selected_component))
      {
     ?>
        
           <div class="card">

          
          <div class="card-header" role="tab" id="headingTwo1">
            <a class="collapsed" data-toggle="collapse" data-parent="#AddressCollapseEx1" href="#AddressCollapse"
              aria-expanded="false" aria-controls="AddressCollapse">
              <h5 class="mb-0">
               Address <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="AddressCollapse" class="collapse" role="tabpanel" aria-labelledby="headingTwo1"
            data-parent="#AddressCollapseEx1">
            <div class="card-body"> 
             <input type="hidden" name="clientid" id="clientid" value="<?php echo set_value('clientid',$candidate_details['clientid'] ); ?>" class="form-control">
              
             <input type="hidden" name="candsid" id="candsid" value="<?php echo set_value('candsid',$candidate_details['id'] ); ?>" class="form-control">

              <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$candidate_details['entity']); ?>" class="form-control">
             <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$candidate_details['package']); ?>" class="form-control">  

             <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Stay From </label>
              <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from'); ?>" class="form-control">
              <?php echo form_error('stay_from'); ?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Stay To</label>
              <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to'); ?>" class="form-control">
              <?php echo form_error('stay_to'); ?>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-4 col-sm-8 col-xs-4 form-group">
              <label>Address type</label>
              <?php
               echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
                echo form_error('address_type'); 
              ?>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Street Address<span class="error">*</span></label>
              <textarea name="address" rows="1" id="address" class="form-control"><?php echo set_value('address'); ?></textarea>
              <?php echo form_error('address'); ?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>City<span class="error">*</span></label>
              <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
              <?php echo form_error('city'); ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Pincode<span class="error">*</span></label>
              <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
              <?php echo form_error('pincode'); ?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>State<span class="error">*</span></label>
              <?php
                echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                echo form_error('state');
              ?>
            </div>

             <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Attachment</label>
                <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                <?php echo form_error('attchments'); ?>
              </div>
              <div class="clearfix"></div>
              

               <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_address" id='save_address' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

        </div>
       <?php 
        }
       ?>

   <?php echo form_close(); ?>

        

    <?php echo form_open('#', array('name'=>'create_employment','id'=>'create_employment')); ?>
       
        <?php 
       if(in_array('empver',$selected_component))
        {
        ?>
        
        
        <div class="card">

          
          <div class="card-header" role="tab" id="headingTwo2">
            <a class="collapsed" data-toggle="collapse" data-parent="#EmployeeCollapseEx1" href="#EmployeeCollapse"
              aria-expanded="false" aria-controls="EmployeeCollapse">
              <h5 class="mb-0">
               Employment <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="EmployeeCollapse" class="collapse" role="tabpanel" aria-labelledby="headingTwo21"
            data-parent="#EmployeeCollapseEx1">
            <div class="card-body">
                  <div class="box-header">
                    <h3 class="box-title">Previous Employment Details</h3>
                  </div>
                  <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                    <label>Company Name <span class="error"> *</span> </label>
                    <?php
                     echo form_dropdown('nameofthecompany', $company_list, set_value('nameofthecompany'), 'class="form-control singleSelect" id="nameofthecompany"');
                      echo form_error('nameofthecompany'); 
                    ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Deputed Company</label>
                    <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company'); ?>" class="form-control">
                    <?php echo form_error('deputed_company'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Previous Employee Code</label>
                    <input type="text" name="empid" id="empid" value="<?php echo set_value('empid'); ?>" class="form-control">
                    <?php echo form_error('empid'); ?>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                    <label>Employment Type</label>
                    <?php

                     $employment_type  = array('' => 'Select','full time' =>'Full time','contractual' =>  'Contractual','part time' => 'Part time');
                     echo form_dropdown('employment_type',$employment_type, set_value('employment_type'), 'class="form-control" id="employment_type"');
                      echo form_error('employment_type'); 
                    ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Employed From 
                    </label>
                    <input type="date" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom'); ?>" class="form-control">
                    <?php echo form_error('empfrom'); ?>
                  </div>                  
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Employed To  </label>
                    <input type="date" name="empto" id="empto" value="<?php echo set_value('empto'); ?>" class="form-control" >
                    <?php echo form_error('empto'); ?>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
                    <?php echo form_error('designation'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Remuneration</label>
                    <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration'); ?>" class="form-control">
                    <?php echo form_error('remuneration'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
                    <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control"><?php echo set_value('reasonforleaving');?></textarea>
                    <?php echo form_error('reasonforleaving'); ?>
                  </div>



                  <div class="clearfix"></div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Street Address</label>
                    <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control"><?php echo set_value('locationaddr'); ?></textarea>
                    <?php echo form_error('locationaddr'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>City</label>
                    <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality'); ?>" class="form-control">
                    <?php echo form_error('citylocality'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>State</label>
                    <?php
                      echo form_dropdown('state', $states, set_value('state'), 'class="form-control" id="state"');
                      echo form_error('state');
                    ?>
                  </div>
                  <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                    <label>Pincode</label>
                    <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                    <?php echo form_error('pincode'); ?>
                  </div>
                  <div class="clearfix"></div>

                  
                 
                  <div class="col-md-3 col-sm-12 col-xs-4 form-group">
                    <label>Company HR Name</label>
                    <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name'); ?>" class="form-control">
                    <?php echo form_error('compant_contact_name'); ?>
                  </div>

                   <div class="col-md-3 col-sm-12 col-xs-4 form-group">
                    <label>Company HR No.</label>
                    <input type="text" name="compant_contact"  minlength="6" maxlength="13" id="compant_contact" value="<?php echo set_value('compant_contact'); ?>" class="form-control">
                    <?php echo form_error('compant_contact'); ?>
                  </div>
                 
                  <div class="col-md-3 col-sm-12 col-xs-4 form-group">
                    <label>Company HR Designation</label>
                    <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation'); ?>" class="form-control">
                    <?php echo form_error('compant_contact_designation'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-4 form-group">
                    <label>Company HR Email ID</label>
                    <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email'); ?>" class="form-control">
                    <?php echo form_error('compant_contact_email'); ?>
                  </div>
                  
                  <div class="clearfix"></div>
                 
                  
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Manager Name</label>
                    <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name'); ?>" class="form-control">
                    <?php echo form_error('r_manager_name'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Manager's contact</label>
                    <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no'); ?>" class="form-control">
                    <?php echo form_error('r_manager_no'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Designation</label>
                    <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation'); ?>" class="form-control">
                    <?php echo form_error('r_manager_designation'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Manager's Email ID</label>
                    <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email'); ?>" class="form-control">
                    <?php echo form_error('r_manager_email'); ?>
                  </div>
                     
                  <div class="clearfix"></div>
                 
                   <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                    <label>Supervisor Name </label>
                    <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("superv_details[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_name'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Supervisor's contact</label>
                    <input type="text" name="supervisor_contact_details[]" maxlength="12" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_contact_details'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Designation </label>
                    <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" class="form-control">
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Supervisor's Email ID </label>
                    <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_i[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_email_id'); ?>
                  </div>
                   <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                    <label>Supervisor Name </label>
                    <input type="text" name="supervisor_name[]" id="supervisor_name" value="<?php echo set_value("superv_details[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_name'); ?>
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Supervisor's contact</label>
                    <input type="text" name="supervisor_contact_details[]" maxlength="12" id="supervisor_contact_details" value="<?php echo set_value("supervisor_contact_details[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_contact_details'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Designation </label>
                    <input type="text" name="supervisor_designation[]" id="supervisor_designation" value="<?php echo set_value("supervisor_designation[]"); ?>" class="form-control">
                  </div>
                  <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <label>Supervisor's Email ID </label>
                    <input type="text" name="supervisor_email_id[]" id="supervisor_email_id" value="<?php echo set_value("supervisor_email_i[]"); ?>" class="form-control">
                    <?php echo form_error('supervisor_email_id'); ?>
                  </div>

                  <div class="clearfix"></div>
                  <hr style="border-top: 2px solid #bb4c4c;">

                 
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Attachment</label>
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>
                  <div class="clearfix"></div>

                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_employment" id='save_employment' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

        </div>
         
      <?php 
        }
       ?>

   <?php echo form_close(); ?>
        


  <?php echo form_open('#', array('name'=>'create_education','id'=>'create_education')); ?>

       <?php 
       if(in_array('eduver',$selected_component))
        {
        ?>
        
        

        
        <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#EducationCollapseEx1" href="#EducationCollapse"
              aria-expanded="false" aria-controls="EducationCollapse">
              <h5 class="mb-0">
                Education <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="EducationCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#EducationCollapseEx1">
            <div class="card-body">
                   <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>School/College</label>
                    <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college'); ?>" class="form-control">
                    <?php echo form_error('school_college'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>University/Board <span class="error">*</span></label>
                    <?php 
                    echo form_dropdown('university_board', $university_name, set_value('university_board'), 'class="form-control singleSelect" id="university_board"');
                    echo form_error('university_board'); 
                    ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Grade/Class/Marks</label>
                    <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks'); ?>" class="form-control">
                    <?php echo form_error('grade_class_marks'); ?>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Qualification <span class="error">*</span></label>
                    <?php 
                    echo form_dropdown('qualification', $qualification_name, set_value('qualification'), 'class="form-control singleSelect" id="qualification"');
                    echo form_error('qualification'); 
                    ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Major</label>
                    <input type="text" name="major" id="major" value="<?php echo set_value('major'); ?>" class="form-control">
                    <?php echo form_error('major'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Course Start Date</label>
                    <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
                    <?php echo form_error('course_start_date'); ?>
                  </div>
                  <div class="clearfix"></div>

                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Course End Date</label>
                    <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
                    <?php echo form_error('course_end_date'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Month of Passing <span class="error">*</span></label>
                    <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing'); ?>" class="form-control">
                    <?php echo form_error('month_of_passing'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Year of Passing <span class="error">*</span></label>
                    <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing'); ?>" class="form-control">
                    <?php echo form_error('year_of_passing'); ?>
                  </div>
                  <div class="clearfix"></div>

                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Roll No</label>
                    <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no'); ?>" class="form-control">
                    <?php echo form_error('roll_no'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Enrollment No <span class="error">*</span></label>
                    <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no'); ?>" class="form-control">
                    <?php echo form_error('enrollment_no'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>PRN Number</label>
                    <input type="text" name="PRN_no" id="PRN_no" value="<?php echo set_value('PRN_no'); ?>" class="form-control">
                    <?php echo form_error('PRN_no'); ?>
                  </div>
                  <div class="clearfix"></div>

                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Documents Provided</label>
                    <?php 
                    echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided'), 'class="form-control" id="documents_provided"');
                    echo form_error('documents_provided'); 
                    ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>City</label>
                    <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                    <?php echo form_error('city'); ?>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>State</label>
                    <?php
                      echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                      echo form_error('state');
                    ?>
                  </div>
                  
                  
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Attachment</label>
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>

                  <div class="clearfix"></div>

                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_education" id='save_education' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

          </div>

           <?php 
            }
           ?>

           <?php echo form_close(); ?>
        

      <?php echo form_open('#', array('name'=>'create_reference','id'=>'create_reference')); ?>
           
       <?php 
       if(in_array('refver',$selected_component))
        {
        ?>

         <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#ReferenceCollapseEx1" href="#ReferenceCollapse"
              aria-expanded="false" aria-controls="ReferenceCollapse">
              <h5 class="mb-0">
                Reference <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="ReferenceCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#ReferenceCollapseEx1">
            <div class="card-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Name of Reference<span class="error"> *</span></label>
                  <input type="text" name="name_of_reference" id="name_of_reference" value="<?php echo set_value('name_of_reference'); ?>" class="form-control">
                  <?php echo form_error('name_of_reference'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Designation<span class="error"> *</span></label>
                  <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
                  <?php echo form_error('designation'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact Number<span class="error"> *</span></label>
                  <input type="text" name="contact_no" minlength="8" maxlength="13" id="contact_no" value="<?php echo set_value('contact_no'); ?>" class="form-control">
                  <?php echo form_error('contact_no'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Email ID</label>
                  <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id'); ?>" class="form-control">
                  <?php echo form_error('email_id'); ?>
                </div>
               

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>
                 <div class="clearfix"></div>

                  <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_reference" id='save_reference' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>

            </div>
          </div>

          </div>
           <?php 
            }
           ?>
        

         <?php echo form_close(); ?>

        <?php echo form_open('#', array('name'=>'create_court','id'=>'create_court')); ?>

        <?php 
       if(in_array('courtver',$selected_component))
        {
        ?>

         <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#CourtCollapseEx1" href="#CourtCollapse"
              aria-expanded="false" aria-controls="CourtCollapse">
              <h5 class="mb-0">
                Court <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="CourtCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#CourtCollapseEx1">
            <div class="card-body">
                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Address type</label>
                  <?php
                   echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
                    echo form_error('address_type'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address<span class="error">*</span></label>
                  <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                  <?php echo form_error('street_address'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City<span class="error">*</span></label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Pincode<span class="error">*</span></label>
                  <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                    echo form_error('state');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>
               <div class="clearfix"></div>

                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_court" id='save_court' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

          </div>

           <?php 
            }
           ?>
        

         <?php echo form_close(); ?>

        <?php echo form_open('#', array('name'=>'create_global','id'=>'create_global')); ?>
      
        <?php 
       if(in_array('globdbver',$selected_component))
        {
        ?>

          <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#GlobalCollapseEx1" href="#GlobalCollapse"
              aria-expanded="false" aria-controls="GlobalCollapse">
              <h5 class="mb-0">
                Global <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="GlobalCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#GlobalCollapseEx1">
            <div class="card-body">
                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Address type</label>
                  <?php
                   echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
                    echo form_error('address_type'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address<span class="error">*</span></label>
                  <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                  <?php echo form_error('street_address'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Pincode<span class="error">*</span></label>
                  <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                    echo form_error('state');
                  ?>
                </div>
               
               
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>
                 <div class="clearfix"></div>

                 <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_global" id='save_global' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

          </div>

          <?php 
            }
           ?>
        

         <?php echo form_close(); ?>

         <?php echo form_open('#', array('name'=>'create_pcc','id'=>'create_pcc')); ?>

         <?php 
         if(in_array('crimver',$selected_component))
           {
           ?>

          <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#PCCCollapseEx1" href="#PCCCollapse"
              aria-expanded="false" aria-controls="PCCCollapse">
              <h5 class="mb-0">
                PCC <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="PCCCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#PCCCollapseEx1">
            <div class="card-body">
              <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                <label>Address type</label>
                <?php
                 echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
                  echo form_error('address_type'); 
                ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Street Address<span class="error">*</span></label>
                <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                <?php echo form_error('street_address'); ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>City<span class="error">*</span></label>
                <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                <?php echo form_error('city'); ?>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Pincode<span class="error">*</span></label>
                <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                <?php echo form_error('pincode'); ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>State</label>
                <?php
                  echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                  echo form_error('state');
                ?>
              </div>
              <div class="clearfix"></div>
              
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Reference </label>
                  <input type="text" name="references[]" id="references" value="<?php echo set_value('references'); ?>" class="form-control">
                  <?php echo form_error('references'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact Number </label>
                  <input type="text" name="references_no[]" maxlength="14" id="references_no" value="<?php echo set_value('references_no'); ?>" class="form-control">
                  <?php echo form_error('references_no'); ?>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Reference </label>
                  <input type="text" name="references[]" id="references" value="<?php echo set_value('references'); ?>" class="form-control">
                  <?php echo form_error('references'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact Number </label>
                  <input type="text" name="references_no[]" maxlength="14" id="references_no" value="<?php echo set_value('references_no'); ?>" class="form-control">
                  <?php echo form_error('references_no'); ?>
                </div>

                 <div class="clearfix"></div>-->

               <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Reference </label>
                  <input type="text" name="references[]" id="references" value="<?php echo set_value('references'); ?>" class="form-control">
                  <?php echo form_error('references'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact Number </label>
                  <input type="text" name="references_no[]" maxlength="14" id="references_no" value="<?php echo set_value('references_no'); ?>" class="form-control">
                  <?php echo form_error('references_no'); ?>
                </div>-->
            
               <!-- <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>

                </div>
                <div class="clearfix"></div>

                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_pcc" id='save_pcc' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

          </div>

          <?php 
            }
           ?>

          <?php echo form_close(); ?>

          <?php echo form_open('#', array('name'=>'create_identity','id'=>'create_identity')); ?>

          <?php 
         if(in_array('identity',$selected_component))
           {
           ?>

        
         <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#IdCollapseEx1" href="#IDCollapse"
              aria-expanded="false" aria-controls="IDCollapse">
              <h5 class="mb-0">
                Identity <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="IDCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#IdCollapseEx1">
            <div class="card-body">
              <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Doc Submitted<span class="error">*</span></label>
                  <?php
                   echo form_dropdown('doc_submited', array('' => 'Select','aadhar_card' => 'Aadhar Card',
                    'pan_card' => 'PAN Card'), set_value('doc_submited'), 'class="form-control" id="doc_submited"');
                    echo form_error('doc_submited'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Id Number</label>
                  <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number'); ?>" class="form-control">
                  <?php echo form_error('id_number'); ?>
                </div>

                 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>
                <div class="clearfix"></div>

                 <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_identity" id='save_identity' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>
            </div>
          </div>

          </div>

            <?php 
            }
            ?>
           
          <?php echo form_close(); ?>

         
        
         <?php echo form_open('#', array('name'=>'create_credit_report','id'=>'create_credit_report')); ?>

           <?php 
           if(in_array('cbrver',$selected_component))
           {
           ?>

           <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#CreditReportCollapseEx1" href="#CreditReportCollapse"
              aria-expanded="false" aria-controls="CreditReportCollapse">
              <h5 class="mb-0">
                Credit Report <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="CreditReportCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#CreditReportCollapseEx1">
            <div class="card-body">
                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Doc Submitted</label>
                  <?php
                   echo form_dropdown('doc_submited', array('' => 'Select','aadhar_card' => 'Aadhar Card',
                    'pan_card' => 'PAN Card'), set_value('doc_submited'), 'class="form-control" id="doc_submited"');
                    echo form_error('doc_submited'); 
                  ?>
                </div>
               <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Id Number</label>
                <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number'); ?>" class="form-control">
                <?php echo form_error('id_number'); ?>
              </div>

              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Street Address</label>
              <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
              <?php echo form_error('street_address'); ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>City</label>
              <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
              <?php echo form_error('city'); ?>
            </div>

             <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>State</label>
              <?php
                echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                echo form_error('state');
              ?>
            </div>

              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Pincode</label>
                <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                <?php echo form_error('pincode'); ?>
              </div>

              
                 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>

               <div class="clearfix"></div>

                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_credit_report" id='save_credit_report' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>

            </div>
          </div>

          </div>

           <?php 
            }
            ?>

          <?php echo form_close(); ?>
    
        

        <?php echo form_open('#', array('name'=>'create_drugs','id'=>'create_drugs')); ?>
          <?php 
           if(in_array('narcver',$selected_component))
           {
           ?>

          <div class="card">

          
          <div class="card-header" role="tab" id="headingThree31">
            <a class="collapsed" data-toggle="collapse" data-parent="#DrugsCollapseEx1" href="#DrugsCollapse"
              aria-expanded="false" aria-controls="DrugsCollapse">
              <h5 class="mb-0">
                Drugs <i class="fa fa-angle-down rotate-icon"></i>
              </h5>
            </a>
          </div>

         
          <div id="DrugsCollapse" class="collapse" role="tabpanel" aria-labelledby="headingThree31"
            data-parent="#DrugsCollapseEx1">
            <div class="card-body">
                <div class="col-md-6 col-sm-8 col-xs-6 form-group">
                  <label>Appointment Date</label>
                  <input type="text" name="appointment_date" id="appointment_date" value="<?php echo set_value('appointment_date'); ?>" class="form-control myDatepicker" Placeholder='DD-MM-YYYY'>
                  <?php echo form_error('appointment_date'); ?>
                </div>
                <div class="col-md-6 col-sm-8 col-xs-6 form-group">
                  <label>Appointment Time</label>
                  <input type="text" name="appointment_time" id="appointment_time" value="<?php echo set_value('appointment_time'); ?>" class="form-control myTimepicker" Placeholder='HH:MM'>
                  <?php echo form_error('appointment_time'); ?>
                </div>
               
                <div class="clearfix"></div>

               <?php
               
                if(!empty($scope_of_work))
                {
                    $scope_of_work_value = json_decode($scope_of_work[0]['scope_of_work']);
                } 
                ?>
                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Drug Test Panel/Code</label>
                  <input type="text" name="drug_test_code" id="drug_test_code" readonly="readonly" value="<?php if(isset($scope_of_work_value->narcver)) { echo $scope_of_work_value->narcver; } ?>" class="form-control">
                  <?php echo form_error('drug_test_code'); ?>
                </div>
                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Facility Name/Code</label>
                  <input type="text" name="facility_name" id="facility_name" value="<?php echo set_value('facility_name'); ?>" class="form-control">
                  <?php echo form_error('facility_name'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address<span class="error"> *</span></label>
                  <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                  <?php echo form_error('street_address'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City<span class="error"> *</span></label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                    echo form_error('state');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Pincode<span class="error"> *</span></label>
                  <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
               
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Attachment</label>
                  <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                  <?php echo form_error('attchments'); ?>
                </div>
                <div class="clearfix"></div>

                  <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save_drugs" id='save_drugs' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              <div class="clearfix"></div>

            </div>
          </div>

          </div>

           <?php 
            }
            ?>
        

         <?php echo form_close(); ?>

         </div>
       </div>
    </div>


  <div class="clearfix"></div>
</div>
<div id="show_entity_package_modal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="max-height:90%;width: 60%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Show Entity Package</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="append_entity_package"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(document).ready(function(){


     $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,  
  });

$('#clientid').attr("style", "pointer-events: none;");
   

$('#btn_update').click(function(){ 

    $('#frm_update_candidates').validate({ 
    rules: {
      clientid : {
        required : true
      },
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : true,
        noSpace : true
      },
      gender : {
        required : true,
        greaterThan: 0
      },
     cands_email_id :
       {
         email : true,
         required : function(){
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return true;
          }
          else
          {
             return false;
          }
        }
      }, 

      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      DateofBirth : {
        required : true
      },
      NameofCandidateFather : {
        required : true,
        lettersonly : true
      },  
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        required : true,
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo2 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      }
     
    },
    messages: {
        clientid : {
          required : "Enter Client Name"
        },
        caserecddate : {
          required : "Select Case Received Date"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
         cands_email_id :
       {
         email : "Enter Valid Email ID",
         required : function(){
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return "Enter Email ID";
          }
          else
          {
             return false;
          }
        }
      }, 
        ClientRefNumber : {
          required : "Enter Client Ref Number"
        },
        DateofBirth : {
        required : "Enter Date Of Birth"
         },
       NameofCandidateFather : {
        required : "Enter Name of Father"
        },
        gender : {
          required : "Select Gender"
        },
          CandidatesContactNumber : {
         required : "Please Enter Primary Contact"
      }
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
              jQuery('.body_loading').show();
            },
            complete:function(){
              $("#frm_update_candidates :input").prop("disabled", true);
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
               location.reload();
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
      }
  });

});

jQuery.validator.addMethod("noSpace",function(value,element){

      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");    

  $('.open_attachment').on('click',function(){
    var url = $(this).data('href');
    window.open('javascript:window.open('+url+', "_self", "");window.close();', '_self');
    //window.open(url, "_blank", "toolbar=yes,width=900,height=650");
  });


  $("#frm_update_candidates :input").prop("disabled", true);

  $("#caserecddate").datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    daysOfWeekDisabled: [0,6],
    endDate: new Date()
  }).on('changeDate', function (selected) {
      var minDate = $(this).val();
      $('#overallclosuredate').datepicker('setStartDate', minDate);
  });

  $('#overallclosuredate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    endDate: new Date()
  });


  
});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete_candidate', function(){  
           var candidates_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'candidates/delete/';?>",  
                     method:"POST",  
                     data:{candidates_id:candidates_id},  
                     success: function(jdata){
                    var message =  jdata.message || '';
                    (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                    if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                      show_alert(message,'success');

                     setTimeout(function(){
                             window.location = "<?php echo ADMIN_SITE_URL.'clients/';?>";
                       },1000);
                    }
                    if(jdata.status == <?php echo ERROR_CODE; ?>){
                      show_alert(message,'error'); 
                    }
                  }
                });  
           }  
           else  
           {  
                return false;       
           }  
      }); 

$('#frm_update_candidates').on('click','#show_entity_package_modal',function(){
    var url = $(this).data("url");
    var clientid = $('#clientid').val();
    $('.append_entity_package').load(url+""+clientid,function(){});
    $('#show_entity_package_modal1').modal('show');
   
     return false;
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





</script>-->

<!--<div class="content-wrapper">
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <?php
                $components = json_decode($client_components['component_name'],true);
             
                $selected_component = explode(',', $client_components['component_id']);
                //$is_check = array_column($this->user_info['menu'], 'controllers');
                $active_tab = '';

                echo "<li class='active'><a href='#candidate_tab' data-toggle='tab'>Candidate Details</a></li>";

                foreach ($components as $key => $component) 
                {

                    if(in_array($key, $selected_component))
                    {

                      $tabs_panels[] = $key;

                      echo "<li role='presentation' data-url='".CLIENT_SITE_URL."candidates/ajax_tab_data/' data-can_id=".$candidate_details['cands_info_id']." data-tab_name=".$key." class='view_component_tab'><a href='#".$key."' aria-controls='home' role='tab' data-toggle='tab'>  ".$component."</a></li>";
                    }
                }
                ?>
              </ul>
              <div class="tab-content">
               
                <?php
                  echo "<div class='active tab-pane' id='candidate_tab'>";
                  $this->load->view('client/candidates_edit_view');

                  ?>
                 
          <hr border-top: 2px solid #bb4c4c;>
            
               <?php  echo "</div>";
                  foreach ($tabs_panels as $key => $tabs_panel) 
                  { 
                      echo "<div id='".$tabs_panel."' class='tab-pane fade in'>";

                      echo "</div>";
                  }
                ?> 
              </div>
            </div>

        </div>
      </div>
    </section>
</div>
-->

<div id="showcandidatelog" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidate Log  Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_vendor_model"></div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showFinalReportModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'save_final_report','id'=>'save_final_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4>Final QC</h4>
      </div>
    
          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
          <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
          <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_final_result"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

         <button type='submit' class='btn btn-info' type='button' name='brn_final_qc_approve' id='brn_final_qc_approve'>Approve</button>
         
         
            <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>
           

            <a target="__blank" href="<?php echo ADMIN_SITE_URL.'candidates/report/'.encrypt($candidate_details['id']).'/final_report' ?>" style="float: right;"> <button type="button" class="btn btn-danger"> Download </button></a>

            <button type='button' class='btn btn-default' data-dismiss="modal" name='brn_final_qc_cancel' id='brn_final_qc_cancel'>Cancel</button>
      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showInterimReportModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'save_interim_report','id'=>'save_interim_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Interim QC</h4>
      </div>

          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
         <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
         <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_interim_result"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

         <button type='submit' class='btn btn-info' type='button' name='brn_final_qc_approve' id='brn_final_qc_approve'>Approve</button>
         
         
            <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>
            <button type='button' class='btn btn-danger' type='button' name='brn_final_qc_download' id='brn_final_qc_download'>Download</button>

            <button type='button' class='btn btn-default' data-dismiss="modal" name='brn_final_qc_cancel' id='brn_final_qc_cancel'>Cancel</button>
      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_view','id'=>'add_verificarion_result_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Result Log Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_result_model2"></div>
      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript">
 $(document).on('click','.showcandidatelog',function() {

    var url = $(this).data('url');
    var id = $(this).data('id');



    $('#append_vendor_model').load(url,function(){
      $('#showcandidatelog').modal('show');
     
    /*  $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/vendor_logs_cost/' ?>',
      data: 'id='+id,
      beforeSend :function(){
        jQuery('#tbl_vendor_log1').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {

            $('#tbl_vendor_log1').html(message);

        }
        else
        {
          $('#tbl_vendor_log1').html(message);
        }

          
          
        }
    }); */

    });
   
});
</script>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script type="text/javascript">

  var $ = jQuery;
var select_one = '';


$(document).ready(function() {

  var oTable =  $('#tbl_datatable').DataTable( {
    
        "serverSide": true,
        "processing": true,
        bSortable: true,
        bRetrieve: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 25, // per page
        "language": {
          "emptyTable": "No Record Found",
         "processing": jQuery('.body_loading').show()
        },

        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo ADMIN_SITE_URL.'candidates/view_candidate_log/'.$candidate_details['id']; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 3, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0
              
           }
        ],
        
        "columns":[{'data' :'id'},{"data":"created_on"},{'data' :'create_by'},{'data' :'is_bulk_upload'},{"data":"encry_id"}]
  });

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0alert(data['encry_id']);

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

     
  });

});

$(document).on('click', '#view_candidate_report_log', function(){
  var cands_id = $(this).data('cands_id');

  if(cands_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'candidates/report_logs/'; ?>'+cands_id,
           data: '',
          beforeSend :function(){
            jQuery('#tbl_report_log').html("Loading..");
         },
         success:function(jdata)
         {
          var message = jdata.message;
          if(jdata.status = 200)
          {
            if(jdata.check_status == "2")
            {
             //  $("#CreateInterimReport").prop('disabled',true);
            }  
            $("#check_status").val(jdata.check_status);
            $('#tbl_report_log').html(message);

          }
          else
          {
             $("#check_status").val(jdata.check_status);
            if(jdata.check_status == "2")
            {
              // $("#CreateInterimReport").prop('disabled',true);
            }
            $('#tbl_report_log').html(message);
          }

          
            var tbl_report_log =  $('#tbl_report_log').DataTable( { "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 25,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });
        }
    }); 
  }
}); 


 $(document).on('click','.showAddResultModel',function() {
    var url = $(this).data('url');
    $('#append_result_model2').load(url,function(){
      $('#showAddResultModel').modal('show');
    });
   
});

 /*$(document).on('click','.downloadinteriamfinalreport',function() {
    var url = $(this).data('url');
    var filename = $(this).data('file-name');
     
      $('.downloadinteriamfinalreport').load(url+"/"+filename,function(){});
    
   
});*/
/*$(document).on('click','.downloadinteriamfinalreport',function() {
    
    
    
    window.location.href = "<?= SITE_URL.UPLOAD_FOLDER.'candidate_report_file/final_report_2020-06-11-22-05-28.html'; ?>" ;
})*/

 


 $('#CreateFinalReport').click(function(){
    var url = $(this).data("url");
    $('#append_final_result').load(url,function(){});
    $('#showFinalReportModel').modal('show');
  });

 $('#CreateInterimReport').click(function(){
    var check_interiam_value =  $("#check_status").val();
    var url = $(this).data("url");
    if(check_interiam_value == "1")
    {
    $('#append_interim_result').load(url,function(){});
    $('#showInterimReportModel').modal('show');
    }

    if(check_interiam_value == "2")
    {
      show_alert('Access Denied, Please Clear First QC Pending First');
    }
  });

 $('#brn_final_qc_approve').click(function(){ 

  $('#save_final_report').validate({ 
   
      submitHandler: function(form) 
      {  
     
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'Final_qc/save_final_report'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#brn_final_qc_approve').attr('disabled','disabled');
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
               location.reload();
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