<?php $this->load->view('email_tem/emailer_header'); ?>
</head>
<body>
<table style="width:855px; max-width: 814px;background-color: #fff;">

        <tr class="email-body">
            <td>
                <p style="text-align: justify; margin-left: 100px;">Greetings from <?php echo CRMNAME; ?></p>
                <p style="text-align: justify; margin-left: 100px">Please find the link for report/s. Kindly ensure you are logged in to the portal before accessing the link.</p>
            </td>
        </tr>
</table>

    <table class="table-content" style="width:800px; max-width: 800px;border-spacing: 10; border-collapse: collapse;margin-bottom: 10px; margin-left: 100px" border="1">
        <tbody>

        <tr>
            <th style='background-color: #EDEDED;width: 400px'>Sr No</th>
            <th style='background-color: #EDEDED;width: 200px'><b>Client Ref #</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b><?php echo REFNO; ?></b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Candidate Name</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Status</b></th>
            <th style='background-color: #EDEDED;width: 200px'><b>Report Link</b></th>
        </tr>
        <?php 
 
      foreach ($email_info as $key => $value) {
        $counter =  $key+1;
         ?>
         <tr>

            <td><?=  $counter  ?></td>
            <td ><?= $value['ClientRefNumber'] ?></td>
            <td ><?= $value['cmp_ref_no'] ?></td>
            <td ><?= $value['CandidateName'] ?></td>
            <?php  
            if($value['status_value'] == "Major Discrepancy")
            {
                $color = 'style="background: #ff2300eb"';
            }
            elseif($value['status_value'] == "Minor Discrepancy")
            {
                 $color = 'style="background:  #ff9933"';
            }
            elseif($value['status_value'] == "Minor Discrepancy")
            {
                 $color = 'style="background:  #ffff00"';
            }
            elseif($value['status_value'] == "Minor Discrepancy")
            {
                 $color = 'style="background:  #4c9900"';
            }
            elseif($value['status_value'] == "Minor Discrepancy")
            {
                 $color = 'style="background:  #4c9900"';
            }
            else
            {
                $color = '';
            }
            ?>
            <td <?php echo $color ?>><?= $value['status_value'] ?></td>
           <!-- <td ><?=  $url ?></td>-->
            <td ><?php echo '<a target="__blank" href="'.ADMIN_SITE_URL.'candidates/report/'.encrypt($value['id']).'/final_report'.'" style="float: right;">Report Link</a>'; ?></td>
        </tr>
        <?php }
      ?>
        </tbody>
    </table>
    <br>
    <?php $this->load->view(EMAIL_VIEW_FOLDER_NAME.'/emailer_signature'); ?>
</body>
</html>