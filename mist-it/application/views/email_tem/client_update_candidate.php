<?php $this->load->view('email_tem/emailer_header'); ?>
</head>
<body>
  <table style="width:800px; max-width: 800px;background-color: #fff;" border="0" cellpadding="0" cellspacing="0" >
        <!--<tr class="email-head">
            <td>
                <h3 style="text-align: justify"><b>Dear <?= ucwords($email_info['cands_name']) ?>,</b></h3>
            </td>
        </tr>-->
        <tr class="email-body">
            <td>
                <p style="text-align: justify">Greetings from <?php echo CRMNAME; ?>! </p>
                <p style="text-align: justify">The mentioned candidate has following Details.</p>
            </td>

        </tr>

    </table>

     <table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px;" border="1">
        <tbody>
        <tr>
        <th>Component Name</th> 
        <th>Component Ref No</th> 
        </tr>   
        <?php 
        if(!empty($email_info['component_details']))
        {  
            foreach ($email_info['component_details'] as $key => $value) 
            {
             ?>
             <tr>
                 <td style='background-color: #EDEDED;width: 200px'><b><?= $value['component_name'] ?></b></td> 
       
                 <td style="text-align: center;"> <?= $value['component_ref_no'] ?></td>
             </tr>
           <?php 
            } 
        }  
        ?>
        
        </tbody>
    </table>
  
</body>
</html>