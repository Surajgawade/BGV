<?php $this->load->view('email_tem/emailer_header'); ?>
</head>
<body>
  <table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
        <tr class="email-head">
            <td>
                <h3 style="text-align: justify; font-family: Calibri;">Dear BGV Team,</h3>
            </td>
        </tr>
        <tr class="email-body">
            <td>
                <p style="text-align: justify; font-family: Calibri;">The following details were submitted by the candidate.</p>
            </td>

        </tr>
          <tr class="email-body">  
            <td>
                <p style="text-align: justify;font-family: Calibri;"><u>Completed</u></p>
                <p style="text-align: justify;font-family: Calibri;"> <?php 
                  $i = 1;
                  foreach ($email_info['pending_component'] as $key => $value) {
                    
                     if($key == "address_component_check" && $value == "0")
                     {
                         echo  "Address"."<br>";
                     }
                     if($key == "employment_component_check" && $value == "0")
                     {
                         echo  "Employment"."<br>";
                     }
                     if($key == "education_component_check" && $value == "0")
                     {
                         echo "Education"."<br>";
                     }
                     if($key == "court_component_check" && $value == "0")
                     {
                         echo "Court"."<br>";
                     }
                     if($key == "global_component_check" && $value == "0")
                     {
                         echo "Global Datatbase"."<br>";
                     }
                     if($key == "pcc_component_check" && $value == "0")
                     {
                         echo "PCC"."<br>";
                     }
                     if($key == "identity_component_check" && $value == "0")
                     {
                         echo "Identity"."<br>";
                     }
                    if($key == "reference_component_check" && $value == "0")
                     {
                         echo "Reference Verification"."<br>";
                     }
                     if($key == "drugs_component_check" && $value == "0")
                     {
                         echo "Drugs"."<br>";
                     }
                     if($key == "credit_report_component_check" && $value == "0")
                     {
                         echo "Credit Report"."<br>";
                     }
 
                   $i++; 

                  } ?></p>
            </td>

        </tr>
         <tr class="email-body">  
            <td>
                <p style="text-align: justify;font-family: Calibri;"><u>Pending</u></p>
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
    </table>
</body>
</html>