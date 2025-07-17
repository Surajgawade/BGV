<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_header'); ?>
<style type="text/css">
	.ml-auto {
    margin-left: 85px;

}
.form-control {
    display: block;
    width: 100%;
   
}
@media (min-width: 576px)
.col-sm-11 {
    -ms-flex: 0 0 91.666667%;
    flex: 0 0 91.666667%;
    max-width: 91.666667%;
}

.div1 {
  border: 1px solid black;
  margin-left: 15px;
  margin-right: 15px;
  background-color: #2b4664;
  width: 100%;
  color : aliceblue;
}

</style>
 <main>
      <br>
        <div class="row">
            <div class="col-sm-11 ml-auto">
               <body>
    <table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
        <tr class="email-head">
            <td>
                <h3 style="text-align: justify"><b>Dear Sir/Madam,</b></h3>
            </td>
        </tr>
        <tr class="email-body">
            <td>
                <p style="text-align: justify"><b><?php echo CRMNAME; ?> </b> is a background verification company conducting employment verification services for their clients  We authenticate the details that were presented by candidates during their selection process. These candidates have either joined or have been shortlisted by our clients for employment.</p>
                <p style="text-align: justify">We request your assistance,in verifying some details that were provided by <b><?= ucwords($email_info['CandidateName']) ?></b> who claims to have been associated with your company <b><?= ucwords(strtolower($email_info['coname'])) ?></b>.</p>
                <p style="text-align: justify">Thank you for your valuable time and feedback in advance.</p>
            </td>
        </tr>
    </table>
    <br>
    <div id ="clients_disc"></div>
    <?php
    if(isset($client_disclosure)) 
    {
       if($client_disclosure == 'yes')
      {
          echo  "<b>Client Name : ".ucwords($email_info['clientname']). "</b>";
      ?>
      <?php 
      }
    }  
    ?>

    <br>
    <br>
    <table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
        <tbody>
        <tr>
            <th style='background-color: #EDEDED;width: 400px'></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Provided by the candidate</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Your input</b></th>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;' ><b>Name of Candidate</b></td>
            <td colspan="2"><?= ucwords($email_info['CandidateName']) ?></td>
            <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;' ><b>Name of Company</b></td>
            <td ><?= ucwords($email_info['coname']) ?></td>
            <td ></td>
        </tr>
        <?php if( !empty($email_info['deputed_company']) ) 
        {
                if($email_info['coname'] == ucwords($email_info['deputed_company']))
            {

       ?>
        
        <?php }
        else{?>
          <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Deputed Company</b></td>
            <td ><?= ucwords($email_info['deputed_company']) ?></td>
            <td ></td>
        </tr>

           <?php }
        }?>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Employee ID</b></td>
            <td ><?= (ucwords($email_info['empid']) != "") ? ucwords($email_info['empid']) : 'Please Provide' ?></td>
            <td ></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Employment Type (Full Time/Contractual/Part Time)</b></td>
            <td><?= (ucwords($email_info['employment_type']) != "") ? ucwords($email_info['employment_type']) : 'Please Provide' ?></td>
            <td ></td>
        </tr>

        <?php
        if(strpos($email_info['empfrom'],"-") == 4)
        {
           
           $empfrom  = date("d-M-Y", strtotime($email_info['empfrom']));
        }
        else
        {
           $empfrom  =  $email_info['empfrom'];
        }

        if(strpos($email_info['empto'],"-") == 4)
        {
           
           $empto  = date("d-M-Y", strtotime($email_info['empto']));
        }
        else
        {
           $empto  =  $email_info['empto'];
        }
        ?>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Period of Employment</b></td>
            <td ><?= $empfrom." to ".$empto ; ?></td>
            <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Designation</b></td>
            <td ><?= (!empty($email_info['designation']) ? ucwords($email_info['designation']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Remuneration</b></td>
            <td ><?= (!empty($email_info['remuneration']) ? ucwords($email_info['remuneration']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
       <!-- <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Total Experience</b></td>
            <td ><?= (!empty($email_info['year_of_experience']) ? ucwords($email_info['year_of_experience']) : 'Please Provide') ?></td>
             <td></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Company Address</b></td>
            <td ><?= (!empty($email_info['coaddress']) ? ucwords($email_info['coaddress']) : 'Please Provide') ?></td>
             <td></td>
        </tr>
          <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Company Location</b></td>
            <td ><?= (!empty($email_info['colocation']) ? ucwords($email_info['colocation']) : 'Please Provide') ?></td>
             <td></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reference Name</b></td>
            <td ><?= (!empty($email_info['employment_reference_name']) ? ucwords($email_info['employment_reference_name']) : 'Please Provide') ?></td>
             <td></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reference Phone Number</b></td>
            <td ><?= (!empty($email_info['employment_reference_no']) ? ucwords($email_info['employment_reference_no']) : 'Please Provide') ?></td>
             <td></td>
        </tr>-->

         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reporting Manager's Name</b></td>
            <td ><?= (!empty($email_info['r_manager_name']) ? ucwords($email_info['r_manager_name']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
        <!-- <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reporting Manager's Designation</b></td>
            <td ><?= (!empty($email_info['r_manager_designation']) ? ucwords($email_info['r_manager_designation']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reporting Manager's Contact Number</b></td>
            <td ><?= (!empty($email_info['r_manager_no']) ? ucwords($email_info['r_manager_no']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reporting Manager's Email ID</b></td>
            <td ><?= (!empty($email_info['r_manager_email']) ? ucwords($email_info['r_manager_email']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>-->
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Reason for Leaving</b></td>
            <td><?= (!empty($email_info['reasonforleaving']) ? ucwords($email_info['reasonforleaving']) : 'Please Provide') ?></td>
            <td></td>
        </tr>
        <!--<tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>HR Name</b></td>
            <td>
            <?php 
              if($email_info['verifiers_role'] == 'hr')
              {
              	$hr_name =   (!empty($email_info['verfname']) ? ucwords($email_info['verfname']) : 'Please Provide');
              }
              else
              {
              	$hr_name = "Please Provide";
              }
              echo $hr_name; 
            ?>
             </td>
            <td></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>HR Designation</b></td>
            <td>
            <?php 
              if($email_info['verifiers_role'] == 'hr')
              {
              	$hr_designation =   (!empty($email_info['verfdesgn']) ? ucwords($email_info['verfdesgn']) : 'Please Provide');
              }
              else
              {
              	$hr_designation = "Please Provide";
              }
              echo $hr_designation; 
            ?> 	
            </td>
            <td></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>HR Email ID</b></td>
            <td>
            <?php 
              if($email_info['verifiers_role'] == 'hr')
              {
              	$hr_email_id =   (!empty($email_info['verifiers_email_id']) ? ucwords($email_info['verifiers_email_id']) : 'Please Provide');
              }
              else
              {
              	$hr_email_id = "Please Provide";
              }
              echo $hr_email_id; 
            ?> 	
            </td>
            <td></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>HR Contact Number</b></td>
            <td>
              <?php 
              if($email_info['verifiers_role'] == 'hr')
              {
              	$hr_contact_no =   (!empty($email_info['verifiers_contact_no']) ? ucwords($email_info['verifiers_contact_no']) : 'Please Provide');
              }
              else
              {
              	$hr_contact_no = "Please Provide";
              }
              echo $hr_contact_no; 
             ?> 	
            </td>
            <td></td>
        </tr>-->
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Exit formalities completed? (If No, please provide whether pending from Company or Candidate's end)</b></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>If any Integrity/Disciplinary issues</b></td>
            <td colspan="2"></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Eligible for re-hire (If No, please provide reason)</b></td>
            <td colspan="2"></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left;padding-left: 5px;'><b>Verifier's Name & Designation</b></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left;padding-left: 5px;'><b>Additional HR Comments</b></td>
            <td colspan="2"></td>
        </tr>
        <!--<tr>
            <td style='background-color: #EDEDED; text-align: left;padding-left: 5px;'><b>Remarks</b></td>
            <td colspan="2"><?= (!empty($email_info['remarks']) ? ucwords($email_info['remarks']) : 'Please Provide') ?></td>
        </tr>-->
        </tbody>
    </table>
    <br>
    <table style="width:800px; max-width: 800px;background-color: #fff;" border=0>
        <tr><td>
    <p style="text-align: justify"><b><?php echo CRMNAME; ?>.</b> is an organization providing professional consulting to various verticals across all sectors. We provide a wide array of services which include employee background screening, consultancy and solutions to businesses </p>
     <p style="text-align: justify">If you like to partner with us or know more, you can email us at <a href="mailto:<?php echo DISCEMAIL ?>"> <?php echo DISCEMAIL ?> </a> or call us at +91 <?php echo MOBILENO ?>.</p>

        </td></tr>
    </table>
</br>


<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_signature'); ?>

</body>
                
            </div>
        </div>
    </main>

    <script type="text/javascript">
        
       $('#client_disclosure').change(function(){
            
         var client_disclosure = $('#client_disclosure').val();
         if(client_disclosure == 'yes')
         {
            var clients_name = <?php echo "'".ucwords($email_info['clientname'])."'" ?>;
            $("#clients_disc").html('<b>Client Name : '+clients_name+'</b>'); 
         }
         else
         {
              $("#clients_disc").html(''); 
         }

       });


    </script>