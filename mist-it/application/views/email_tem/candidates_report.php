<?php $this->load->view('email_tem/emailer_header'); ?>
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
  background-color: #ef6e00;
}

</style>
 <main>
      
        <div class="row">
            <div class="col-sm-11 ml-auto">
<body>
<table style="width:855px; max-width: 814px;background-color: #fff;">  
        <tr class="email-body">
            <td>
                <p style="text-align: justify;">Team,</p>
                <p style="text-align: justify;">Greetings from <?php echo CRMNAME; ?>!</p>
                <p style="text-align: justify;">Attached herewith is the report for <?php echo ucwords($email_info[0]['CandidateName']);  ?></p> 
            </td>
        </tr>
</table>

    <table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="2">
        <tbody>

        <tr>
            <th style='background-color: #EDEDED;width: 200px'>Sr No</th>
            <th style='background-color: #EDEDED;width: 200px'><b>Client Ref #</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b><?php echo COMPANYREFNO ?></b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Entity</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>SPOC/Package</b></th>  
            <th style='background-color: #EDEDED;width: 200px'><b>Candidate Name</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Case Received Date</b></th>
            <?php 
            foreach ($component_details as $key_component => $value_component) {
                
                foreach ($value_component as $key => $value_details) {
                     $keyincreament = $key+1;  
                    echo "<th style='background-color: #EDEDED;width: 200px'><b>".ucwords($key_component)." ".$keyincreament."</b></th>"; 
                 } 
                 
             }
            ?>
            <th style='background-color: #EDEDED;width: 200px'><b>Final Status</b></th>
        </tr>
        <?php 
 
      foreach ($email_info as $key => $value) {
        $counter =  $key+1;
         ?>
         <tr>

            <td style="text-align: center;"><?=  $counter  ?></td>
            <td style="text-align: center;"><?= ucwords($value['ClientRefNumber']) ?></td>
            <td style="text-align: center;"><?= $value['cmp_ref_no'] ?></td>
            <td style="text-align: center;"><?= ucwords($value['entity_name']) ?></td>
            <td style="text-align: center;"><?= ucwords($value['package_name']) ?></td>
            <td style="text-align: center;"><?= ucwords($value['CandidateName']) ?></td>
            <td style="text-align: center;"><?= date('d-M-Y', strtotime($value['caserecddate'])) ?></td>

           <?php 
            foreach ($component_details as $key_component => $value_component) {
              
                foreach ($value_component as $key => $value_details) {
                    if($value_details == "Major Discrepancy")
                    {
                        echo "<td  style='background: #ff2300;text-align: center;'>".$value_details."</td>";
                    }
                    elseif($value_details == "Minor Discrepancy")
                    {
                        echo "<td  style='background: #ff9933;text-align: center;'>".$value_details."</td>";
                    }
                    elseif($value_details == "Unable to Verify")
                    {
                         echo "<td  style='background: #ffff00;text-align: center;'>".$value_details."</td>";
                    }
                    elseif($value_details == "Clear")
                    {

                        echo "<td  style='background: #4c9900;text-align: center;'>Green</td>";
                    }
                    elseif($value_details == "NA")
                    {
                        echo "<td  style='background: #808080;text-align: center;'>".$value_details."</td>";
                    }
                    elseif($value_details == "Stop Check")
                    {
                        echo "<td  style='background: #808080;text-align: center;'>".$value_details."</td>";
                    }
                    else
                    {
                        echo "<td style='text-align: center;'>".$value_details."</td>";

                    }
    
                 }  
             }
            ?>
             <?php  

            if($value['status_value'] == "Major Discrepancy")
            {
                $color = 'style="background: #ff2300; text-align: center;"';
            }
            elseif($value['status_value'] == "Minor Discrepancy")
            {
                 $color = 'style="background:  #ff9933; text-align: center"';
            }
            elseif($value['status_value'] == "Unable to Verify")
            {
                 $color = 'style="background:  #ffff00; text-align: center"';
            }
            elseif($value['status_value'] == "Clear")
            {
                 $color = 'style="background:  #4c9900; text-align: center"';
            }
            elseif($value['status_value'] == "NA")
            {
                 $color = 'style="background:  #808080; text-align: center"';
            }
            elseif($value['status_value'] == "Stop Check")
            {
                 $color = 'style="background:  #808080; text-align: center"';
            }
            else
            {
                $color = '';
            }


            ?>
            <?php  
            if($value['status_value'] != "Clear") 
            {
            ?>           
            <td <?php echo $color ?> style="text-align: center;"><?= $value['status_value'] ?></td>
            <?php 
            }
            else
            {
            ?>
            <td  style="background:  #4c9900; text-align: center;">Green</td>
            <?php 
            }
            ?>

        </tr>
        <?php }
      ?>
        </tbody>
    </table>
    <br>
    <br>
    <br>
     <p><b>Note :</b> <I>This is an auto generated email. Request you to write back to your account manager in case of any discrepancy.</I></p>
    <br>
    <br>
      <p style="text-align:left;">Regards,<br></p>
      <table style="width:525px; font-size:10pt; font-family:Arial, sans-serif;"  cellspacing="0" cellpadding="0" border="0">

   <tr>
      <td style="font-size:10pt; width:18px; padding-right:10px; vertical-align:middle;" valign="middle">
         <p style="margin-bottom:25px; line-height:1.2">
         <strong>
            <span style="font-size:12pt; font-family:Tahoma, sans-serif; color:#f3a825">BGV TEAM</span>
         </strong>
         <br>

         </p>
   
         <a href="<?php echo HTTPWEBSITE ?>" target="_blank" rel="noopener"> <img alt="logo" style="width:143px; height:40px; border:0;" src="<?php echo  COMPANYLOGO ?>" width="143" border="0"> 
         </a>                       
      </td>
  
      <td style="font-family:Tahoma, sans-serif; padding-left:15px; vertical-align:top; line-height:1.2; border-left:solid 2px #cccccc" valign="top">
   
   
      <span>
         <strong>
            <a href="<?php echo HTTPWEBSITE ?>" target="_blank" rel="noopener" style="text-decoration:none;">
               <span style="font-size:9pt; font-family:Tahoma, sans-serif; color:#01527d;"><?php echo WEBSITE ?> </span>
            </a>
         </strong>
      </span>   
    
      <p style="margin-top:5px; margin-bottom:0">
         <strong style="font-family:Tahoma, sans-serif; font-size:9pt; color:#000000;"><?php echo CRMNAME ?></strong>
            <span style="font-size:8pt; font-family:Tahoma, sans-serif;color:#000000;"><br><?php echo COMPANYADDRESS ?> </span>
            <span style="font-size:8pt; font-family:Tahoma, sans-serif;color:#000000;"><?php echo COMPANYADDRESSCITYPIN ?></span>   
      </p>

      <p style="margin-top:20px; margin-bottom:20px"></p>
   
      <a href="<?php echo HTTPWEBSITE ?>" target="_blank" rel="noopener" style="text-decoration:none;"><img alt="banner" style="width:257px; height:35px; border:0;"  src="<?php echo NASSCOM ?>" width="257" border="0"></a>   
   </td>
 </tr>
</table>

    <br>
    <br>
    <p><b>Confidentiality :</b> <I>The contents of this email message and any attachments are intended solely for the addressee(s) and may contain confidential and/or privileged information and may be legally protected from disclosure. If you are not the intended recipient of this message or their agent, or if this message has been addressed to you in error, please immediately alert the sender by reply email and then delete this message and any attachments. If you are not the intended recipient, you are hereby notified that any use, dissemination, copying, or storage of this message or its attachments is strictly prohibited.</I></p>
    <p><b>VIRUS WARNING:</b><I> Computer viruses can be transmitted via email. The recipient should check this email and any attachments for the presence of viruses. The company accepts no liability for any damage caused by any virus transmitted by this email. E-mail transmission cannot be guaranteed to be secure or error-free as information could be intercepted, corrupted, lost, destroyed, arrive late or incomplete, or contain viruses. The sender therefore does not accept liability for any errors or omissions in the contents of this message, which arise as a result of e-mail transmission.</I></p>
</body>
         </div>
        </div>
    </main>
