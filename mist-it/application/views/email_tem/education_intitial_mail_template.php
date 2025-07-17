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
                <p style="text-align: justify"><b><?php echo CRMNAME; ?> </b> is a background verification company conducting employment verification services for <span id ="clients_disc">their clients </span> We authenticate the details that were presented by candidates during their selection process. These candidates have either joined or have been shortlisted by our clients for employment.</p>
                <p style="text-align: justify">We request your assistance,in verifying some details that were provided by <b><?= ucwords($email_info['CandidateName']) ?></b> as his educational qualification.</p>
                <p style="text-align: justify">Thank you for your valuable time and feedback in advance.</p>
            </td>
        </tr>
    </table>
    <br>
    <table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
        <tbody>
        <tr>
           
            <th style='background-color: #EDEDED;width: 200px' colspan="2"><b>Provided by the candidate</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Your input</b></th>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;' ><b>Name of Student</b></td>
            <td  colspan="2"><?= ucwords($email_info['CandidateName']) ?></td>
        </tr>
        <?php if( !empty($email_info['school_college']) ) 
        { ?>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>School/College</b></td>
            <td ><?= ucwords($email_info['school_college']) ?></td>
            <td ></td>
        </tr>
        <?php } else{?>
         
           <?php } ?>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>University/Board</b></td>
            <td ><?= ucwords($email_info['actual_university_board']) ?></td>
            <td ></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Qualification</b></td>
            <td><?= ucwords($email_info['actual_qualification']) ?></td>
            <td ></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Roll/Enrolment/PRN Number</b></td>
            <td ><?= (!empty($email_info['roll_no']) ? ucwords($email_info['roll_no']) : 'Please Provide') ?>/<?= (!empty($email_info['enrollment_no']) ? ucwords($email_info['enrollment_no']) : 'Please Provide') ?>/<?= (!empty($email_info['PRN_no']) ? ucwords($email_info['PRN_no']) : 'Please Provide') ?></td>
            <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Month/Year of Passing</b></td>
            <td ><?= ucwords($email_info['month_of_passing']) ?>/<?= ucwords($email_info['year_of_passing']) ?></td>
             <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Grade/Class/Marks</b></td>
            <td ><?= (!empty($email_info['grade_class_marks']) ? ucwords($email_info['grade_class_marks']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
         <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Major</b></td>
            <td ><?= (!empty($email_info['major']) ? ucwords($email_info['major']) : 'Please Provide') ?></td>
             <td ></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Verifier's Name & Designation</b></td>
            <td colspan="2"><?= ucwords($email_info['verified_by']) ?> & <?= ucwords($email_info['verifier_designation']) ?></td>
        </tr>
        <tr>
            <td style='background-color: #EDEDED; text-align: left; padding-left: 5px;'><b>Additional Comments</b></td>
            <td colspan="2"><?= ucwords($email_info['remarks']) ?></td>
        </tr>
       
        </tbody>
    </table>
    <br>
    <table style="width:800px; max-width: 800px;background-color: #fff;" border=0>
        <tr><td>
    <p style="text-align: justify"><b><?php echo CRMNAME; ?>.</b> is an organization providing professional consulting and training to various verticals across all sectors. It is headquartered in Singapore and has multi-country presence. We provide a wide array of services which include training, employee background screening, consultancy and solutions to businesses and educational institutes. If you like to partner with us or know more, you can email us at <a href="mailto:<?php echo DISCEMAIL; ?>"> <?php echo DISCEMAIL; ?> </a> or call us at +91 <?php echo MOBILENO;?></p>
        </td></tr>
    </table>
</br>


<?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_signature'); ?>


</body>
                
            </div>
        </div>
    </main>

    