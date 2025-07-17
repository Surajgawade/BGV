<?php $this->load->view('email_tem/emailer_header'); ?>

<body>
  <table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
        <tr class="email-head">
            <td>
                <h3 style="text-align: justify;font-family: Calibri;">Dear <b><?= ucwords($email_info['cands_name']) ?></b>,</h3>
            </td>
        </tr>
        <tr class="email-body">
            <td>
                <p style="text-align: justify;font-family: Calibri;">Greetings from <?php echo CRMNAME ?>.</p>
                <p style="text-align: justify;font-family: Calibri;">We have partnered with your current employer <b><?= ucwords($email_info['clientname']) ?></b> to conduct your background verification.</p>
                <p style="text-align: justify;font-family: Calibri;">You are requested to visit the below link and update the required details at the earliest before the link expires.</p>
              
                <p style="text-align: justify;font-family: Calibri;"><a href="<?php echo $email_info['url']; ?>"> Click here</a> to update your details.</p>
               
            </td>
        </tr>
         <tr class="email-body">  
            <td>
                <p style="text-align: justify;font-family: Calibri;"><b><u>Pending</u></b></p>
                <p style="text-align: justify;font-family: Calibri;"> <?php 
                  $i = 1;
                  foreach ($email_info['pending_component'] as $key => $value) {
                    
                     if($key == "address_component_check" && $value == "1")
                     {
                         echo  "Address"."<br>";
                     }
                     if($key == "employment_component_check" && $value == "1")
                     {
                         echo  "Employment"."<br>";
                     }
                     if($key == "education_component_check" && $value == "1")
                     {
                         echo "Education"."<br>";
                     }
                     if($key == "court_component_check" && $value == "1")
                     {
                         echo "Court"."<br>";
                     }
                     if($key == "global_component_check" && $value == "1")
                     {
                         echo "Global Datatbase"."<br>";
                     }
                     if($key == "pcc_component_check" && $value == "1")
                     {
                         echo "PCC"."<br>";
                     }
                     if($key == "identity_component_check" && $value == "1")
                     {
                         echo "Identity"."<br>";
                     }
                    if($key == "reference_component_check" && $value == "1")
                     {
                         echo "Reference Verification"."<br>";
                     }
                     if($key == "drugs_component_check" && $value == "1")
                     {
                         echo "Drugs"."<br>";
                     }
                     if($key == "credit_report_component_check" && $value == "1")
                     {
                         echo "Credit Report"."<br>";
                     }

                   
                   $i++; 

                  } ?></p>
            </td>

        </tr>
        <tr>
        	  <td><br><p style="text-align: justify;font-family: Calibri;">Thanking you in advance and wish you all the best for your new role.</p></td>
        </tr>

    </table>

</body>
</html>