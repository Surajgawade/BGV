<?php 
if($report_type == "Final")
{
?>
<div style="background-color: #ffffff;">
  <section class="content-header">
  
    
     <ol class="breadcrumb" style="margin-top: -40px;">
         
          <li> 
            <button type='button' class='btn btn-default' data-dismiss="modal">Cancel</button>&nbsp;
          </li> 
        </ol>
  </section>
<br>
 
  <section class="content-report" style="border-style: double; margin:15px; margin-top:-15px; font-weight: bold;">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <html>
          <head>
            <title>Candidate Report</title>
            <style> table { margin-top: 15px; } table, td{ border: 2px solid black; height: 35px; } .form-control { border: 0px none; }</style> 
          </head>
          <body>
            <div style="float: left;">
              <?php if(!empty($candidate_details['comp_logo']))
              { ?>
              <img src="<?= SITE_URL.UPLOAD_FOLDER.'logos/'.$candidate_details['comp_logo']; ?>" height="75" width="125" alt="client logo">
              <?php }  ?>
            </div>
            <div style="float: right;"> <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="75" width="125" alt="logo"></div>

            <table width="97%" cellpadding="5" cellspacing="pixels">
            
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> <?=  strtoupper($candidate_details['clientname']) ?> </b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
            
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> BACKGROUND VERIFICATION FINAL REPORT </b></td>
              </tr>
            </table>


            <table width="97%" cellpadding="5" cellspacing="pixels">

             
              <tr>
                <td bgcolor="#ccc" style='border-right:2px solid;'><b> NAME OF CANDIDATE</b></td>
                <td colspan="3"> <input type="text" name="" class="form-control" value="<?= $candidate_details['CandidateName'] ?>" > </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> GENDER</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= $candidate_details['gender'] ?>"> </td>
                <td bgcolor="#ccc"><b> DATE OF BIRTH</b></td>
                <td width="140px">  <input type="text" class="form-control" name="" value="<?= date('d-M-Y', strtotime($candidate_details['DateofBirth'])); ?>"> </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE INITIATED</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= date('d-M-Y', strtotime($candidate_details['caserecddate'])); ?>" > </td>
                <?php 
                if(empty($candidate_details['overallclosuredate']))
                {
                   $overallclosuredate = "NA";
                }
                else
                {
                   $overallclosuredate = date('d-M-Y', strtotime($candidate_details['overallclosuredate']));
                }
                ?>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE OF COMPLETION</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= $overallclosuredate ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> <?php echo REFNO; ?></b></td>
                <td> <input type="text" name=""  class="form-control" value="<?= strtoupper($candidate_details['cmp_ref_no']) ?>"> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b>CLIENT REF NO</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= strtoupper($candidate_details['ClientRefNumber']) ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> ENTITY / PACKAGE</b></td>
                <td width="140px"><!-- <input type="text" class="form-control" name="" value="<?= $candidate_details['entity_name'].' / '.$candidate_details['package_name'] ?>">-->

                <?= $candidate_details['entity_name'].' / '.$candidate_details['package_name'] ?> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> CASE STATUS</b></td>
                
               <?php
                  if($candidate_details['overallstatus_value'] == "Major Discrepancy")
                {
                echo "<td width='140px' style='background-color: #ff2300eb;'>".$candidate_details['overallstatus_value']."</td>";
                }
                elseif($candidate_details['overallstatus_value'] == "Minor Discrepancy")
                {
                 echo "<td width='140px' style='background-color: #ff2300eb;'>".$candidate_details['overallstatus_value']."</td>";
                }
                elseif($candidate_details['overallstatus_value'] == "Unable To Verify")
                {
                echo "<td width='140px' style='background-color: #ffff00;'>".$candidate_details['overallstatus_value']."</td>";
                }
                elseif($candidate_details['overallstatus_value'] == "Clear")
                {
                 echo "<td width='140px' style='background-color: #4c9900;'>".$candidate_details['overallstatus_value']."</td>";
                }
                else
                {
                   echo "<td width='140px' ><input type='text' name=''   class='form-control' value='".$candidate_details['overallstatus_value']."'> </td>";
                }
                ?>
               
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> CLASSIFICATION OF REPORT STATUS</b></td>
              </tr>
              <tr style="text-align: center;">
                <td style="background: #ff2300eb;width: 25%;"><b>MAJOR DISCREPANCY</b></td>
                <td style="background: #ff9933;width: 25%;"><b>MINOR DISCREPANCY</b></td>
                <td style="background: #ffff00;width: 25%;"><b>UNABLE TO VERIFY</b></td>
                <td style="background: #4c9900;width: 25%;"><b>CLEAR</b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
              <tr>
                <td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>EXECUTIVE SUMMARY</b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels" style="border:2px solid;">
              <tr>
                <td bgcolor="#ccc" style="border:2px solid;"><b> TYPE OF CHECK</b></td>
                <td bgcolor="#ccc" style="border:2px solid;"><b> BRIEF DETAILS</b></td>
                <td bgcolor="#ccc"  style="border:2px solid;"><b> STATUS</b></td>
              </tr>
              <?php 
              $component_name  = json_decode($candidate_details['component_name'],true);


              $component_name = array_map('strtoupper', $component_name);
              $employment = $component_details['employment'];
              $address = $component_details['address'];
              $education = $component_details['education'];
              $reference = $component_details['reference'];
              $court = $component_details['court'];
              $global_db = $component_details['global_db'];
              $drug = $component_details['drug'];
              $pcc = $component_details['pcc'];
              $identity = $component_details['identity'];
              $credit_report = $component_details['credit_report'];


              foreach ($employment as $key => $value)  {

                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
               
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['empver'] .$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['coname']."' > </td>";
                if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                
              }
              foreach ($address as $key => $value)  {
                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['addrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($education as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['eduver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['qualification']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($reference as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['refver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['name_of_reference']."'> </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($court as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['courtver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
               elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($global_db as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['globdbver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($drug as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['narcver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['drug_test_code']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($pcc as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['state'].' '.$value['pincode'];
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['crimver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($identity as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['identity'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($credit_report  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['cbrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              ?>
              </tr>
            </table>
            <?php
              foreach ($employment as $key => $value)  {
                
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $empid =  ($value['empid'] == "" || $value['empid'] == "please provide" || $value['empid'] == "Please Provide") ? 'Not Provided' :$value['empid'];
                $designation =  ($value['designation'] == "" || $value['designation'] == "please provide" || $value['designation'] == "Please Provide") ? 'Not Provided' :$value['designation'];
                $remuneration =  ($value['remuneration'] == "" || $value['remuneration'] == "please provide" || $value['remuneration'] == "Please Provide") ? 'Not Provided' :$value['remuneration'];
                $reasonforleaving =  ($value['reasonforleaving'] == "" || $value['reasonforleaving'] == "please provide" || $value['reasonforleaving'] == "Please Provide") ? 'Not Provided' :$value['reasonforleaving'];
                $r_manager_name =  ($value['r_manager_name'] == "" || $value['r_manager_name'] == "please provide" || $value['r_manager_name'] == "Please Provide") ? 'Not Provided' : ucwords($value['r_manager_name']);

                
                $period_of_stay = $value['empfrom'].' to '.$value['empto'];

                if((strpos($value['empfrom'],"-") == 4 || strpos($value['empfrom'],"-") == 2) &&  (strpos($value['empto'],"-") == 4 || strpos($value['empto'],"-") == 2))
                {
                     
                     
                      $period_of_stay1 = date('d-M-Y', strtotime($value['empfrom'])).' to '.date('d-M-Y', strtotime($value['empto']));
                }
                else 
               {

                $period_of_stay1 = $value['empfrom'].' to '.$value['empto'];
                    
               }

                $res_period_of_stay = $value['employed_from'].' to '.$value['employed_to'];
                         
                if((strpos($value['employed_from'],"-") == 4 || strpos($value['employed_from'],"-") == 2) &&  (strpos($value['employed_to'],"-") == 4 || strpos($value['employed_to'],"-") == 2))
               {
                         
                 $res_period_of_stay1 = date('d-M-Y', strtotime($value['employed_from'])).' to '.date('d-M-Y', strtotime($value['employed_to']));
              }
              else
              {

                $res_period_of_stay1 = $value['employed_from'].' to '.$value['employed_to'];
                    
              } 
                                 
                $reportingmanager =  ($value['reportingmanager'] == "") ? 'Not Disclosed' :$value['reportingmanager'];
                $reportingmanager = ($Verified_status == "NA") ? 'NA' :  $reportingmanager;

                $verified_company = ($value['coname'] == $value['res_coname']) ? 'Verified' : $value['res_coname'];
                $verified_company = ($Verified_status == "NA") ? 'NA' : ucwords($verified_company);
                $res_empid = ($Verified_status == "NA") ? 'NA' : $value['res_empid'];


                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay1;
                $verified = ($Verified_status == "NA") ? 'NA' : $verified;

                $emp_designation = ($Verified_status == "NA") ? 'NA' : $value['emp_designation'];

                $res_remuneration = ($Verified_status == "NA") ? 'NA' : $value['res_remuneration'];
                $res_reasonforleaving = ($Verified_status == "NA") ? 'NA' : $value['res_reasonforleaving'];
                $info_integrity_disciplinary_issue = ($Verified_status == "NA") ? 'NA' :  $value['info_integrity_disciplinary_issue'];
                $info_exitformalities = ($Verified_status == "NA") ? 'NA' :  $value['info_exitformalities'];

                $mcaregn = ($Verified_status == "NA") ? 'NA' :  $value['mcaregn'];
                $domainname = ($value['domainname'] == "Na.na.com") ? 'NA' :  $value['domainname'];
                $domainname = ($Verified_status == "NA") ? 'NA' :  $domainname;

                $justdialwebcheck = ($Verified_status == "NA") ? 'NA' :  $value['justdialwebcheck'];
                $fmlyowned = ($Verified_status == "NA") ? 'NA' :  $value['fmlyowned'];

                $verifiers_details =  $value['verfname']."/".$value['verifiers_role']."/".$value['verfdesgn'];
                $verifiers_details = ($Verified_status == "NA") ? 'NA' :  $verifiers_details;

                $modeofverification = ($value['modeofverification'] == "") ? 'NA' :  $value['modeofverification'];
                $modeofverification = ($Verified_status == "NA") ? 'NA' :  $modeofverification;

                $res_remarks = ($value['res_remarks'] == "") ? 'NA' :  $value['res_remarks'];
                 if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $res_remarks :  $res_remarks;
                } 

               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['empver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
               
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME OF THE COMPANY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".ucwords($value['coname'])."' > </td>";
                echo "<td><input type='text' name='res_coname' class='form-control' value='".$verified_company."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EMPLOYEE ID</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$empid."' > </td>";
                echo "<td><input type='text' name='res_empid' class='form-control' value='".$res_empid."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>PERIOD OF EMPLOYMENT<b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$period_of_stay1."'></td>";
                
                echo "<td><input type='text' name='' class='form-control' value='".$verified."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$designation."'></td>";
                echo "<td><input type='text' name='emp_designation' class='form-control' value='".$emp_designation."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REMUNERATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$remuneration."'></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$res_remuneration."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REPORTING MANAGER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$r_manager_name."'> </td>";
                echo "<td><input type='text' name='reportingmanager' class='form-control' value='".$reportingmanager."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REASON OF LEAVING</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$reasonforleaving."'> </td>";
                echo "<td><input type='text' name='' class='form-control' value='".$res_reasonforleaving."' ></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ANY INTEGRITY/DISCIPLINARY ISSUES</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$info_integrity_disciplinary_issue."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EXIT FORMALITIES COMPLETED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$info_exitformalities."'></td>";
                echo "</tr>";
                echo "<tr>";
             /*   echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS";
                  echo "<ul style ='text-align:left;'>
                    <li> The Company is registered in MCA?</li>
                    <li> Domain name</li>
                    <li> The Company is listed in Just Dial and Internet searches? </li>
                    <li> Is it family owned business? </li>
                    </ul></b></td>";
                echo "<td>
                    <ul ><br>
                      <li> <input type='text' name='' class='form-control' value='".$value['mcaregn']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['domainname']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['justdialwebcheck']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['fmlyowned']."'></li>
                    </ul>
                </td>";*/
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>THE COMPANY IS REGISTERED  IN  MCA?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mcaregn."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>DOMAIN NAME</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$domainname."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>THE COMPANY IS LISTED IN JUSTDIAL AND INTERNET SEARCHES?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$justdialwebcheck."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>IS IT FAMILY OWNED  BUSSINESS?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$fmlyowned."'></td>";
                echo "</tr>";

                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$verifiers_details."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$modeofverification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
              //  <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                   
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      //print_r( $attachments);
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4  order_by_image' id='item-employment-".$value['empver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.EMPLOYMENT.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.EMPLOYMENT.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                     echo "</ul>";
                    echo "</div>";
                   
                }

              }

              foreach ($address as $key => $value)  {

                //$period_of_stay = $value['stay_from'].' '.($value['stay_to'] != "") ? ' to '.$value['stay_to'] : $value['stay_to'];
               // $res_period_of_stay = $value['res_stay_from'].' '.($value['res_stay_to'] != '') ? ' to '.$value['res_stay_to'] : $value['res_stay_to'];
               // $verified = ($period_of_stay == $res_period_of_stay) ? 'verified' : $res_period_of_stay;
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                 $value = array_map('ucwords', $value);

                $period_of_stay = $value['stay_from'].' to '.$value['stay_to'];

                $res_period_of_stay = $value['res_stay_from'].' to '.$value['res_stay_to'];

                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay;
                $verified = ($Verified_status == "NA") ? 'NA' :   $verified;

               

                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $res_full_address = $value['res_address'].' '.$value['res_city'].' '.$value['res_pincode'].' '.$value['res_state'];
                $verified_address_status = ($full_address == $res_full_address) ? 'Verified' : $res_full_address;
                $verified_address_status = ($Verified_status == "NA") ? 'NA' :  $verified_address_status;
    
                $verified_by = ($Verified_status == "NA") ? 'NA' :  $value['verified_by'];
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                }
                $mode_of_verification = ($Verified_status == "NA") ? 'NA' :  $value['mode_of_verification'];
              
               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['addrver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td>".$full_address." </td>";
                echo "<td><input type='text' name='res_full_address' class='form-control' value='".$verified_address_status."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>PERIOD OF STAY</b></td>";
                echo "<td>".$period_of_stay."</td>";
                echo "<td><input type='text' name='' class='form-control' value='".$verified."' ></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
               // echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
               // echo "<td ><input type='text' name='add_comment' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$verified_by."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$mode_of_verification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
                
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $add_attachments = ($value['add_attachments'] != NULL && $value['add_attachments'] != '') ?  explode('||', $value['add_attachments']) : '';
                
                if(!empty($add_attachments) && count($add_attachments) > 0)
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                     echo "<ul class='sortable'>";
                    foreach ($add_attachments as $key => $attachment) {

                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-address-".$value['addrver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.ADDRESS.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.ADDRESS.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($education as $key => $value) {
                $counter = ($key == 0) ? ''  : $key+1;
               
                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords',$value);

                $school_college_univerity = ($value['school_college'] != '') ? $value['school_college'].'/' : "";
                $school_college_univerity .= ($value['university_board'] != '') ? $value['university_board'] : "NA";

                $res_school_college_univerity = ($value['res_school_college'] != '') ? $value['res_school_college'].'/' : "";
                $res_school_college_univerity .= ($value['res_university_board'] != '') ? $value['res_university_board'] : "NA";

                $verified = ($school_college_univerity == $res_school_college_univerity) ? 'Verified' : $res_school_college_univerity;



                $school_college = $value['school_college'].'/'.$value['university_board'];
                $res_school_college = $value['res_school_college']. '/'.$value['res_university_board'];
                $verified = ($school_college == $res_school_college) ? 'Verified' : $res_school_college_univerity;


                $verified = ($Verified_status == "NA") ? 'NA' :   $verified;
                $res_qualification = ($Verified_status == "NA") ? 'NA' :   $value['res_qualification'];
                $res_year_of_passing = ($Verified_status == "NA") ? 'NA' :   $value['res_year_of_passing'];
                
               

                $verf_details = ($value['verified_by'] != '') ? $value['verified_by'] : "";
                $verf_details .= ($value['verifier_designation'] != '') ? ','.$value['verifier_designation'] : "";
                $verf_details .= ($value['verifier_contact_details'] != '') ? ','.$value['verifier_contact_details'] : "";

                $verf_details = ($Verified_status == "NA") ? 'NA' :   $verf_details;
                $res_mode_of_verification = ($Verified_status == "NA") ? 'NA' :   $value['res_mode_of_verification'];
                
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                }

               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['eduver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME OF THE COLLEGE / UNIVERSITY</b></td>";
                echo "<td>".$school_college_univerity."</td>";
                echo "<td><input type='text' name='res_school_college' class='form-control' value='".$verified."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>QUALIFICATION ATTAINED</b></td>";
                echo "<td>".$value['qualification']."</td>";
                echo "<td><input type='text' name='res_qualification' class='form-control' value='".$res_qualification."' ></td>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>YEAR OF PASSING</b></td>";
                echo "<td>".$value['year_of_passing']."</td>";
                echo "<td><input type='text' name='res_year_of_passing' class='form-control' value='".$res_year_of_passing."'></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
              //  echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS (IF ANY)</b></td>";
              //  echo "<td ><input type='text' name='res_remarks' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$verf_details."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='res_mode_of_verification' class='form-control' value='".$res_mode_of_verification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

              
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);

                        echo "<div class='col-md-4 order_by_image' id='item-education-".$value['education_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.EDUCATION.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.EDUCATION.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($reference as $key => $value)  {
               
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);
                
                $name_of_reference = ($Verified_status == "NA") ?  "NA" : $value['name_of_reference'];
                $designation = ($Verified_status == "NA") ?  "NA" : $value['designation'];
                $handle_pressure_value = ($Verified_status == "NA") ?  "NA" : $value['handle_pressure_value'];
                $attendance_value = ($Verified_status == "NA") ?  "NA" : $value['attendance_value'];
                $integrity_value = ($Verified_status == "NA") ?  "NA" : $value['integrity_value'];
                $leadership_skills_value = ($Verified_status == "NA") ?  "NA" : $value['leadership_skills_value'];
                $responsibilities_value = ($Verified_status == "NA") ?  "NA" : $value['responsibilities_value'];
                $achievements_value = ($Verified_status == "NA") ?  "NA" : $value['achievements_value'];
                $strengths_value = ($Verified_status == "NA") ?  "NA" : $value['strengths_value'];
                $weakness_value = ($Verified_status == "NA") ?  "NA" : $value['weakness_value'];
                $team_player_value = ($Verified_status == "NA") ?  "NA" : $value['team_player_value'];
                $overall_performance = ($Verified_status == "NA") ?  "NA" : $value['overall_performance'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $value['mode_of_verification'];
              
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                }


                $additional_comments = ($value['additional_comments'] != '') ? $value['additional_comments'] : "Not Provided";

                $additional_comments = ($Verified_status == "NA") ?  "NA" : $additional_comments;

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['refver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b> REFERENCE NAME</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$name_of_reference."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$designation."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ABILITY TO HANDLE PRESSURE</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$handle_pressure_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ATTENDANCE/PUNCTUALITY</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$attendance_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>INTEGRITY, CHARACTER & HONESTY</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$integrity_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>LEADERSHIP SKILLS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$leadership_skills_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>RESPONSIBILITIES & DUTIES</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$responsibilities_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>SPECIFIC ACHIEVEMENTS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$achievements_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>STRENGTHS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$strengths_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>WEAKNESSES</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$weakness_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>TEAM PLAYER</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$team_player_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>OVERALL PERFORMANCE</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$overall_performance."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$mode_of_verification."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$additional_comments."</td>";
                echo "</tr>";
            
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$res_remarks."</td>";
                echo "</tr>";
               

                echo "</table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-reference-".$value['reference_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.REFERENCES.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.REFERENCES.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($court as $key => $value)  {
               
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);


                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";

                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
                }
                


                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['courtver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
               // echo "<tr>";
               // echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>CRIMINAL RECORD STATUS</b></td>";
               // echo "<td><input type='text' name='' class='form-control' value='NA' ></td>";
               // echo "</tr>";
               // echo "<tr>";
               // echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
               // echo "<td ><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
               // echo "</tr>";
              
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-court-".$value['courtver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.COURT_VERIFICATION.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.COURT_VERIFICATION.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($global_db as $key => $value)  {
               

                $counter = ($key == 0) ? ''  : $key+1;
                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;


                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ?  $remarks : $remarks;
                }

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['globdbver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td ><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               /// echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-global-".$value['glodbver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.GLOBAL_DB.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.GLOBAL_DB.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

              }

              foreach ($drug as $key => $value)  {
               
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $amphetamine_screen = ($Verified_status == "NA") ?  "NA" : $value['amphetamine_screen'];
                $cannabinoids_screen = ($Verified_status == "NA") ?  "NA" : $value['cannabinoids_screen'];
                $cocaine_screen = ($Verified_status == "NA") ?  "NA" : $value['cocaine_screen'];
                $opiates_screen = ($Verified_status == "NA") ?  "NA" : $value['opiates_screen'];
                $phencyclidine_screen = ($Verified_status == "NA") ?  "NA" : $value['phencyclidine_screen'];
                
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ?  $value['remarks'] :  $value['remarks'];
                }


                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['narcver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> 5 DRUG PANEL</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> STATUS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> AMPHETAMINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$amphetamine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> CANNABINOIDS SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$cannabinoids_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>  COCAINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$cocaine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b>  OPIATES SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$opiates_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b> PHENCYCLIDINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$phencyclidine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                $drugs_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';


                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-drugs-".$value['drug_narcotis_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.DRUGS.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.DRUGS.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

                if(!empty($drugs_vendor_attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                  
                    foreach ($drugs_vendor_attachments as $key => $vendor_attachments) {
                    
                         echo "<div class='col-md-4 order_by_image'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.DRUGS.'vendor_file'.'/'.$vendor_attachments."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.DRUGS.'vendor_file'.'/'.$vendor_attachments.'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                         echo "</div>";
                    }
                   
                    echo "</div>";
                }
              }

              foreach ($pcc as $key => $value)  {
                
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);


                $police_station_visit_date = ($value['police_station_visit_date'] != '') ? date('d-M-Y', strtotime($value['police_station_visit_date'])) : "NA";
                $police_station_visit_date = ($Verified_status == "NA") ?  "NA" : $police_station_visit_date;


                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $name_designation_police = ($Verified_status == "NA") ?  "NA" :  $value['name_designation_police'];
                $contact_number_police = ($Verified_status == "NA") ?  "NA" :  $value['contact_number_police'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" :  $value['mode_of_verification'];

                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ?  $value['remarks'] :  $value['remarks'];
                }

               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels' >";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['crimver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc'  style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>DATE OF VISIT POLICE STATION</b></td>";
                echo "<td><input type='text' name='police_station_visit_date' class='form-control' value='".$police_station_visit_date."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='address' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME AND DESIGNATION OF THE POLICE OFFICER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$name_designation_police."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>CONTACT NUMBER OF POLICE</b></td>";
                echo "<td><input type='text' name='contact_number_police' class='form-control' value='".$contact_number_police."' ></td>";
                echo "</tr>";
              //  echo "<tr>";
               // echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>RESULT OF THE POLICE VERIFICATION RECORD</b></td>";
               // echo "<td ><input type='text' name='remarks' class='form-control' value='".$value['remarks']."' ></td>";
               // echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='". $mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-pcc-".$value['pcc_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.PCC.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.PCC.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($identity as $key => $value)  {

                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
                $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

                $id_number = ($value['id_number'] != '') ? $value['id_number'] : "NA";
                $id_number = ($Verified_status == "NA") ?  "NA" : $id_number;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ?  $remarks :  $remarks;
                }
               


                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['identity']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>"; 

                echo "</table>";

                 echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DOCUMENT PROVIDED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$doc_submited."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td style='background-color: #0000000f;' style='border:2px solid;'><b> DOCUMENT NUMBER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$id_number."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
               // echo "</tr>";
                
                 
                echo "</table>";


                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-identity-".$value['identity_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.IDENTITY.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.IDENTITY.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }
                
              foreach ($credit_report as $key => $value)  {

                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
                $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $remarks :  $remarks;
                }

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['cbrver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DOCUMENT PROVIDED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$doc_submited."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
                
                 
                echo "</table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-credit_report-".$value['credit_report_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.CREDIT_REPORT.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.CREDIT_REPORT.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              
            ?>
          </body>

          <?php

           echo '<br><div style="text-align:center;"><b>*** END OF REPORT ***</b><br><br>
           <img src="assets/images/nasscom.png"></div><br>';

           echo '<span style="font-weight: bold;">Disclaimer :</span> <span style="text-align:justify;">All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, Service Provider and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report. Furthermore, Service Provider and its affiliates expressly disclaim that the services will meet the clients needs and Service Provider and its affiliates express disclaim all such representations and warranties. Service Provider merely passes on to client information in the form of reports which Service Provider has obtained in respect of the subject of a background screening verification. Service Provider is not the author or creator of that information.
Given the limitations, no responsibility will be taken by Service Provider for the consequences of client in reliance upon information contained in this information or report. However, Service Provider will provide reasonable procedures to protect against any false information being provided to the client. The information within the reports is strictly confidential and is intended to be for the sole purpose of the clients evaluation. The information and the reports are not intended for public dissemination.</span>';

         ?>
        </html>
       
      </div>

  </div>

  </section
</div>

<?php 
}
if($report_type == "Interiam")
{
?>

<div style="background-color: #ffffff;">
  <section class="content-header">
  
    
     <ol class="breadcrumb" style="margin-top: -40px;">
         
          <li> 
            <button type='button' class='btn btn-default' data-dismiss="modal"'>Cancel</button>&nbsp;

          </li> 
        </ol>
  </section>
<br>
 
  <section class="content-report" style="border-style: double; margin:15px; margin-top:-15px; font-weight: bold;">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <html>
          <head>
            <title>Candidate Report</title>
            <style> table { margin-top: 15px; } table, td{ border: 2px solid black; height: 35px;} .form-control { border: 0px none; }</style> 
          </head>
          <body>
            <div style="float: left;">
              <?php if(!empty($candidate_details['comp_logo']))
              { ?>
              <img src="<?= SITE_URL.UPLOAD_FOLDER.'logos/'.$candidate_details['comp_logo']; ?>" height="75" width="125" alt="client logo">
              <?php }  ?>
            </div>
            <div style="float: right;"> <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="75" width="125" alt="logo"></div>

            <table width="97%" cellpadding="5" cellspacing="pixels">
            
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> <?=  strtoupper($candidate_details['clientname']) ?> </b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
            
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> BACKGROUND VERIFICATION INTERIM REPORT </b></td>
              </tr>
            </table>


            <table width="97%" cellpadding="5" cellspacing="pixels">

             
              <tr>
                <td bgcolor="#ccc" style='border-right:2px solid;'><b> NAME OF CANDIDATE</b></td>
                <td colspan="3"> <input type="text" name="" class="form-control" value="<?= $candidate_details['CandidateName'] ?>" > </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> GENDER</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= $candidate_details['gender'] ?>"> </td>
                <td bgcolor="#ccc"><b> DATE OF BIRTH</b></td>
                <td width="140px">  <input type="text" class="form-control" name="" value="<?= date('d-M-Y', strtotime($candidate_details['DateofBirth'])); ?>"> </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE INITIATED</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= date('d-M-Y', strtotime($candidate_details['caserecddate'])); ?>" > </td>

                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE OF COMPLETION</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="NA"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> <?php echo REFNO; ?></b></td>
                <td> <input type="text" name=""  class="form-control" value="<?= strtoupper($candidate_details['cmp_ref_no']) ?>"> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b>CLIENT REF NO</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= strtoupper($candidate_details['ClientRefNumber']) ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> ENTITY / PACKAGE</b></td>
                <td width="140px"><!-- <input type="text" class="form-control" name="" value="<?= $candidate_details['entity_name'].' / '.$candidate_details['package_name'] ?>">-->

                <?= $candidate_details['entity_name'].' / '.$candidate_details['package_name'] ?> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> CASE STATUS</b></td>
                
               <?php
                  if($candidate_details['overallstatus_value'] == "Major Discrepancy")
                {
                echo "<td width='140px' style='background-color: #ff2300eb;'>".$candidate_details['overallstatus_value']."</td>";
                }
                elseif($candidate_details['overallstatus_value'] == "Minor Discrepancy")
                {
                 echo "<td width='140px' style='background-color: #ff2300eb;'>".$candidate_details['overallstatus_value']."</td>";
                }
                elseif($candidate_details['overallstatus_value'] == "Unable To Verify")
                {
                echo "<td width='140px' style='background-color: #ffff00;'>".$candidate_details['overallstatus_value']."</td>";
                }
                 elseif($candidate_details['overallstatus_value'] == "Clear")
                {
                 echo "<td width='140px' style='background-color: #4c9900;'>".$candidate_details['overallstatus_value']."</td>";
                }
                else
                {
                   echo "<td width='140px' ><input type='text' name=''   class='form-control' value='".$candidate_details['overallstatus_value']."'> </td>";
                }
                ?>
               
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
              <tr>
                <td colspan="4" bgcolor="#ccc" style="text-align: center;height: 35px;"><b> CLASSIFICATION OF REPORT STATUS</b></td>
              </tr>
              <tr style="text-align: center;">
                <td style="background: #ff2300eb;width: 25%;"><b>MAJOR DISCREPANCY</b></td>
                <td style="background: #ff9933;width: 25%;"><b>MINOR DISCREPANCY</b></td>
                <td style="background: #ffff00;width: 25%;"><b>UNABLE TO VERIFY</b></td>
                <td style="background: #4c9900;width: 25%;"><b>CLEAR</b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels">
              <tr>
                <td bgcolor="#ccc" style="text-align: center;height: 35px;"><b>EXECUTIVE SUMMARY</b></td>
              </tr>
            </table>

            <table width="97%" cellpadding="5" cellspacing="pixels" style="border:2px solid;">
              <tr>
                <td bgcolor="#ccc" style="border:2px solid;"><b> TYPE OF CHECK</b></td>
                <td bgcolor="#ccc" style="border:2px solid;"><b> BRIEF DETAILS</b></td>
                <td bgcolor="#ccc"  style="border:2px solid;"><b> STATUS</b></td>
              </tr>
              <?php 
              $component_name  = json_decode($candidate_details['component_name'],true);


              $component_name = array_map('strtoupper', $component_name);
              $employment = $component_details['employment'];
              $address = $component_details['address'];
              $education = $component_details['education'];
              $reference = $component_details['reference'];
              $court = $component_details['court'];
              $global_db = $component_details['global_db'];
              $drug = $component_details['drug'];
              $pcc = $component_details['pcc'];
              $identity = $component_details['identity'];
              $credit_report = $component_details['credit_report'];


              foreach ($employment as $key => $value)  {

                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['empver'] .$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['coname']."' > </td>";
                if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                
              }
              foreach ($address as $key => $value)  {
                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['addrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
               elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($education as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['eduver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['qualification']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($reference as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['refver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['name_of_reference']."'> </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($court as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['courtver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                 if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($global_db as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['globdbver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($drug as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['narcver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['drug_test_code']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($pcc as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['state'].' '.$value['pincode'];
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['crimver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($identity as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['identity'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                 elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              foreach ($credit_report  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['cbrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                  if($value['verfstatus'] == "Major Discrepancy")
                {
                echo "<td><input type='text' name=''  style='background-color: #ff2300eb;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Minor Discrepancy")
                {
                echo "<td><input type='text' name='' style='background-color: #ff9933;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Unable To Verify")
                {
                echo "<td><input type='text' name='' style='background-color: #ffff00;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                elseif($value['verfstatus'] == "Clear")
                {
                echo "<td><input type='text' name='' style='background-color: #4c9900;' class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
                else
                {
                  echo "<td><input type='text' name=''  class='form-control' value='".$value['verfstatus']."'></td></tr>";
                }
              }
              ?>
              </tr>
            </table>
            <?php
              foreach ($employment as $key => $value)  {

                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $empid =  ($value['empid'] == "" || $value['empid'] == "please provide" || $value['empid'] == "Please Provide") ? 'Not Provided' :$value['empid'];
                $designation =  ($value['designation'] == "" || $value['designation'] == "please provide" || $value['designation'] == "Please Provide") ? 'Not Provided' :$value['designation'];
                $remuneration =  ($value['remuneration'] == "" || $value['remuneration'] == "please provide" || $value['remuneration'] == "Please Provide") ? 'Not Provided' :$value['remuneration'];
                $reasonforleaving =  ($value['reasonforleaving'] == "" || $value['reasonforleaving'] == "please provide" || $value['reasonforleaving'] == "Please Provide") ? 'Not Provided' :$value['reasonforleaving'];
                $r_manager_name =  ($value['r_manager_name'] == "" || $value['r_manager_name'] == "please provide" || $value['r_manager_name'] == "Please Provide") ? 'Not Provided' : ucwords($value['r_manager_name']);
                
                $period_of_stay = $value['empfrom'].' to '.$value['empto'];
                if((strpos($value['empfrom'],"-") == 4 || strpos($value['empfrom'],"-") == 2) &&  (strpos($value['empto'],"-") == 4 || strpos($value['empto'],"-") == 2))
                {
        
                   $period_of_stay1 = date('d-M-Y', strtotime($value['empfrom'])).' to '.date('d-M-Y', strtotime($value['empto']));
                }
                else
                {

                 $period_of_stay1 = $value['empfrom'].' to '.$value['empto'];
                    
                }

                $res_period_of_stay = $value['employed_from'].' to '.$value['employed_to'];
                 
                if((strpos($value['employed_from'],"-") == 4 || strpos($value['employed_from'],"-") == 2) &&  (strpos($value['employed_to'],"-") == 4 || strpos($value['employed_to'],"-") == 2))
                 {
             
             
                     $res_period_of_stay1 = date('d-M-Y', strtotime($value['employed_from'])).' to '.date('d-M-Y', strtotime($value['employed_to']));
                }
                else
                {

                $res_period_of_stay1 = $value['employed_from'].' to '.$value['employed_to'];
                    
                } 
                                 
                $reportingmanager =  ($value['reportingmanager'] == "") ? 'Not Disclosed' :$value['reportingmanager'];
                $reportingmanager = ($Verified_status == "NA") ? 'NA' :  $reportingmanager;


                $verified_company = ($value['coname'] == $value['res_coname']) ? 'Verified' : $value['res_coname'];
                $verified_company = ($Verified_status == "NA") ? 'NA' : ucwords($verified_company);
                $res_empid = ($Verified_status == "NA") ? 'NA' : $value['res_empid'];


                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay1;
                $verified = ($Verified_status == "NA") ? 'NA' : $verified;

                $emp_designation = ($Verified_status == "NA") ? 'NA' : $value['emp_designation'];

                $res_remuneration = ($Verified_status == "NA") ? 'NA' : $value['res_remuneration'];
                $res_reasonforleaving = ($Verified_status == "NA") ? 'NA' : $value['res_reasonforleaving'];
                $info_integrity_disciplinary_issue = ($Verified_status == "NA") ? 'NA' :  $value['info_integrity_disciplinary_issue'];
                $info_exitformalities = ($Verified_status == "NA") ? 'NA' :  $value['info_exitformalities'];

                $mcaregn = ($Verified_status == "NA") ? 'NA' :  $value['mcaregn'];
                $domainname = ($value['domainname'] == "Na.na.com") ? 'NA' :  $value['domainname'];
                $domainname = ($Verified_status == "NA") ? 'NA' :  $domainname;
                $justdialwebcheck = ($Verified_status == "NA") ? 'NA' :  $value['justdialwebcheck'];
                $fmlyowned = ($Verified_status == "NA") ? 'NA' :  $value['fmlyowned'];

                $verifiers_details =  $value['verfname']."/".$value['verifiers_role']."/".$value['verfdesgn'];
                $verifiers_details = ($Verified_status == "NA") ? 'NA' :  $verifiers_details;

                $modeofverification = ($value['modeofverification'] == "") ? 'NA' :  $value['modeofverification'];
                $modeofverification = ($Verified_status == "NA") ? 'NA' :  $modeofverification;
                $res_remarks = ($value['res_remarks'] == "") ? 'NA' :  $value['res_remarks'];
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $res_remarks :  $res_remarks;
                } 
              
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['empver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
               
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME OF THE COMPANY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".ucwords($value['coname'])."' > </td>";
                echo "<td><input type='text' name='res_coname' class='form-control' value='".$verified_company."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EMPLOYEE ID</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$empid."' > </td>";
                echo "<td><input type='text' name='res_empid' class='form-control' value='".$res_empid."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>PERIOD OF EMPLOYMENT<b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$period_of_stay1."'></td>";
                
                echo "<td><input type='text' name='' class='form-control' value='".$verified."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$designation."'></td>";
                echo "<td><input type='text' name='emp_designation' class='form-control' value='".$emp_designation."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REMUNERATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$remuneration."'></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$res_remuneration."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REPORTING MANAGER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$r_manager_name."'> </td>";
                echo "<td><input type='text' name='reportingmanager' class='form-control' value='".$reportingmanager."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REASON OF LEAVING</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$reasonforleaving."'> </td>";
                echo "<td><input type='text' name='' class='form-control' value='".$res_reasonforleaving."' ></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ANY INTEGRITY/DISCIPLINARY ISSUES</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$info_integrity_disciplinary_issue."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EXIT FORMALITIES COMPLETED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$info_exitformalities."'></td>";
                echo "</tr>";
                echo "<tr>";
             /*   echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS";
                  echo "<ul style ='text-align:left;'>
                    <li> The Company is registered in MCA?</li>
                    <li> Domain name</li>
                    <li> The Company is listed in Just Dial and Internet searches? </li>
                    <li> Is it family owned business? </li>
                    </ul></b></td>";
                echo "<td>
                    <ul ><br>
                      <li> <input type='text' name='' class='form-control' value='".$value['mcaregn']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['domainname']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['justdialwebcheck']."'></li>
                      <li> <input type='text' name='' class='form-control' value='".$value['fmlyowned']."'></li>
                    </ul>
                </td>";*/
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>THE COMPANY IS REGISTERED  IN  MCA?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mcaregn."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>DOMAIN NAME</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$domainname."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>THE COMPANY IS LISTED IN JUSTDIAL AND INTERNET SEARCHES?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$justdialwebcheck."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>IS IT FAMILY OWNED  BUSSINESS?</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$fmlyowned."'></td>";
                echo "</tr>";

                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$verifiers_details."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$modeofverification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                   
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      //print_r( $attachments);
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4  order_by_image' id='item-employment-".$value['empver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.EMPLOYMENT.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.EMPLOYMENT.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

              }

              foreach ($address as $key => $value)  {

                //$period_of_stay = $value['stay_from'].' '.($value['stay_to'] != "") ? ' to '.$value['stay_to'] : $value['stay_to'];
               // $res_period_of_stay = $value['res_stay_from'].' '.($value['res_stay_to'] != '') ? ' to '.$value['res_stay_to'] : $value['res_stay_to'];
               // $verified = ($period_of_stay == $res_period_of_stay) ? 'verified' : $res_period_of_stay;
                $counter = ($key == 0) ? ''  : $key+1;
 
                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $period_of_stay = $value['stay_from'].' to '.$value['stay_to'];

                $res_period_of_stay = $value['res_stay_from'].' to '.$value['res_stay_to'];

                $verified = ($period_of_stay == $res_period_of_stay) ? 'Verified' : $res_period_of_stay;
                $verified = ($Verified_status == "NA") ? 'NA' :   $verified;

               

                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $res_full_address = $value['res_address'].' '.$value['res_city'].' '.$value['res_pincode'].' '.$value['res_state'];
                $verified_address_status = ($full_address == $res_full_address) ? 'Verified' : $res_full_address;
                $verified_address_status = ($Verified_status == "NA") ? 'NA' :  $verified_address_status;
    
                $verified_by = ($Verified_status == "NA") ? 'NA' :  $value['verified_by'];
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                }
                $mode_of_verification = ($Verified_status == "NA") ? 'NA' :  $value['mode_of_verification'];
               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['addrver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td>".$full_address." </td>";
                echo "<td><input type='text' name='res_full_address' class='form-control' value='".$verified_address_status."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>PERIOD OF STAY</b></td>";
                echo "<td>".$period_of_stay."</td>";
                echo "<td><input type='text' name='' class='form-control' value='".$verified."' ></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
               // echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
               // echo "<td ><input type='text' name='add_comment' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$verified_by."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$mode_of_verification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
                
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $add_attachments = ($value['add_attachments'] != NULL && $value['add_attachments'] != '') ?  explode('||', $value['add_attachments']) : '';
                
                if(!empty($add_attachments) && count($add_attachments) > 0)
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                     echo "<ul class='sortable'>";
                    foreach ($add_attachments as $key => $attachment) {

                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-address-".$value['addrver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.ADDRESS.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.ADDRESS.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                     echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($education as $key => $value) {
                $counter = ($key == 0) ? ''  : $key+1;
                

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords',$value);

                $school_college_univerity = ($value['school_college'] != '') ? $value['school_college'].'/' : "";
                $school_college_univerity .= ($value['university_board'] != '') ? $value['university_board'] : "NA";

                $res_school_college_univerity = ($value['res_school_college'] != '') ? $value['res_school_college'].'/' : "";
                $res_school_college_univerity .= ($value['res_university_board'] != '') ? $value['res_university_board'] : "NA";

                $school_college = $value['school_college'].'/'.$value['university_board'];
                $res_school_college = $value['res_school_college']. '/'.$value['res_university_board'];
                $verified = ($school_college == $res_school_college) ? 'Verified' : $res_school_college_univerity;
                $verified = ($Verified_status == "NA") ? 'NA' :   $verified;
                $res_qualification = ($Verified_status == "NA") ? 'NA' :   $value['res_qualification'];
                $res_year_of_passing = ($Verified_status == "NA") ? 'NA' :   $value['res_year_of_passing'];

                $verf_details = ($value['verified_by'] != '') ? $value['verified_by'] : "";
                $verf_details .= ($value['verifier_designation'] != '') ? ','.$value['verifier_designation'] : "";
                $verf_details .= ($value['verifier_contact_details'] != '') ? ','.$value['verifier_contact_details'] : "";

                $verf_details = ($Verified_status == "NA") ? 'NA' :   $verf_details;
                $res_mode_of_verification = ($Verified_status == "NA") ? 'NA' :   $value['res_mode_of_verification'];
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                } 
                  
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['eduver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> COMPONENTS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION PROVIDED</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> INFORMATION VERIFIED</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME OF THE COLLEGE / UNIVERSITY</b></td>";
                echo "<td>".$school_college."</td>";
                echo "<td><input type='text' name='res_school_college' class='form-control' value='".$verified."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>QUALIFICATION ATTAINED</b></td>";
                echo "<td>".$value['qualification']."</td>";
                echo "<td><input type='text' name='res_qualification' class='form-control' value='".$res_qualification."' ></td>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>YEAR OF PASSING</b></td>";
                echo "<td>".$value['year_of_passing']."</td>";
                echo "<td><input type='text' name='res_year_of_passing' class='form-control' value='".$res_year_of_passing."'></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
              //  echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS (IF ANY)</b></td>";
              //  echo "<td ><input type='text' name='res_remarks' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$verf_details."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='res_mode_of_verification' class='form-control' value='".$res_mode_of_verification."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$res_remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);

                        echo "<div class='col-md-4 order_by_image' id='item-education-".$value['education_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.EDUCATION.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.EDUCATION.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($reference as $key => $value)  {
                
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);
                
                $name_of_reference = ($Verified_status == "NA") ?  "NA" : $value['name_of_reference'];
                $designation = ($Verified_status == "NA") ?  "NA" : $value['designation'];
                $handle_pressure_value = ($Verified_status == "NA") ?  "NA" : $value['handle_pressure_value'];
                $attendance_value = ($Verified_status == "NA") ?  "NA" : $value['attendance_value'];
                $integrity_value = ($Verified_status == "NA") ?  "NA" : $value['integrity_value'];
                $leadership_skills_value = ($Verified_status == "NA") ?  "NA" : $value['leadership_skills_value'];
                $responsibilities_value = ($Verified_status == "NA") ?  "NA" : $value['responsibilities_value'];
                $achievements_value = ($Verified_status == "NA") ?  "NA" : $value['achievements_value'];
                $strengths_value = ($Verified_status == "NA") ?  "NA" : $value['strengths_value'];
                $weakness_value = ($Verified_status == "NA") ?  "NA" : $value['weakness_value'];
                $team_player_value = ($Verified_status == "NA") ?  "NA" : $value['team_player_value'];
                $overall_performance = ($Verified_status == "NA") ?  "NA" : $value['overall_performance'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $value['mode_of_verification'];
              
                if($value['verfstatus'] == "Insufficiency")
                {
                  $res_remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $res_remarks = ($Verified_status == "NA") ? $value['res_remarks'] :  $value['res_remarks'];
                } 


                $additional_comments = ($value['additional_comments'] != '') ? $value['additional_comments'] : "Not Provided";

                $additional_comments = ($Verified_status == "NA") ?  "NA" : $additional_comments;

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['refver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b> REFERENCE NAME</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$name_of_reference."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$designation."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ABILITY TO HANDLE PRESSURE</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$handle_pressure_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ATTENDANCE/PUNCTUALITY</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$attendance_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>INTEGRITY, CHARACTER & HONESTY</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$integrity_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>LEADERSHIP SKILLS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$leadership_skills_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>RESPONSIBILITIES & DUTIES</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$responsibilities_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>SPECIFIC ACHIEVEMENTS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$achievements_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>STRENGTHS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$strengths_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>WEAKNESSES</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$weakness_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>TEAM PLAYER</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$team_player_value."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>OVERALL PERFORMANCE</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$overall_performance."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$mode_of_verification."</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$additional_comments."</td>";
                echo "</tr>";
            
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;padding-left: 10px;'>".$res_remarks."</td>";
                echo "</tr>";
              
                echo "</table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

               
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-reference-".$value['reference_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.REFERENCES.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.REFERENCES.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($court as $key => $value)  {
              $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);


                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";

                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $remarks :  $remarks;
                } 

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['courtver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
               // echo "<tr>";
               // echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>CRIMINAL RECORD STATUS</b></td>";
               // echo "<td><input type='text' name='' class='form-control' value='NA' ></td>";
               // echo "</tr>";
               // echo "<tr>";
               // echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
               // echo "<td ><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
               // echo "</tr>";
              
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td  style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                   echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-court-".$value['courtver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.COURT_VERIFICATION.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.COURT_VERIFICATION.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($global_db as $key => $value)  {
               
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;


                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ?  $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $remarks :  $remarks;
                } 

                


                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['globdbver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td ><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               /// echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

               
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-global-".$value['glodbver_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.GLOBAL_DB.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.GLOBAL_DB.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

              }

              foreach ($drug as $key => $value)  {
      
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $amphetamine_screen = ($Verified_status == "NA") ?  "NA" : $value['amphetamine_screen'];
                $cannabinoids_screen = ($Verified_status == "NA") ?  "NA" : $value['cannabinoids_screen'];
                $cocaine_screen = ($Verified_status == "NA") ?  "NA" : $value['cocaine_screen'];
                $opiates_screen = ($Verified_status == "NA") ?  "NA" : $value['opiates_screen'];
                $phencyclidine_screen = ($Verified_status == "NA") ?  "NA" : $value['phencyclidine_screen'];
               
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $value['remarks'] :  $value['remarks'];
                } 

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['narcver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> 5 DRUG PANEL</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> STATUS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> AMPHETAMINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$amphetamine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> CANNABINOIDS SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$cannabinoids_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>  COCAINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$cocaine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b>  OPIATES SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$opiates_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b> PHENCYCLIDINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$phencyclidine_screen."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                $drugs_vendor_attachments = ($value['vendor_attachments'] != NULL && $value['vendor_attachments'] != '') ?  explode('||', $value['vendor_attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-drugs-".$value['drug_narcotis_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.DRUGS.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.DRUGS.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }

                if(!empty($drugs_vendor_attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                  
                    foreach ($drugs_vendor_attachments as $key => $vendor_attachments) {
                    
                         echo "<div class='col-md-4 order_by_image'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.DRUGS.'vendor_file'.'/'.$vendor_attachments."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                            echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.DRUGS.'vendor_file'.'/'.$vendor_attachments.'" class="img-rounded" alt="attachments missign" width="304" height="236"></a> ';
                          echo "</div>";
                         echo "</div>";
                    }
                   
                    echo "</div>";
                }
              }

              foreach ($pcc as $key => $value)  {
               $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);


                $police_station_visit_date = ($value['police_station_visit_date'] != '') ? date('d-M-Y', strtotime($value['police_station_visit_date'])) : "NA";
                $police_station_visit_date = ($Verified_status == "NA") ?  "NA" : $police_station_visit_date;


                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $full_address = ($Verified_status == "NA") ?  "NA" : $full_address;

                $name_designation_police = ($Verified_status == "NA") ?  "NA" :  $value['name_designation_police'];
                $contact_number_police = ($Verified_status == "NA") ?  "NA" :  $value['contact_number_police'];
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" :  $value['mode_of_verification'];

                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $value['remarks'] :  $value['remarks'];
                } 


               
                echo "<table width='97%' cellpadding='5' cellspacing='pixels' >";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['crimver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc'  style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>DATE OF VISIT POLICE STATION</b></td>";
                echo "<td><input type='text' name='police_station_visit_date' class='form-control' value='".$police_station_visit_date."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='address' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME AND DESIGNATION OF THE POLICE OFFICER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$name_designation_police."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>CONTACT NUMBER OF POLICE</b></td>";
                echo "<td><input type='text' name='contact_number_police' class='form-control' value='".$contact_number_police."' ></td>";
                echo "</tr>";
              //  echo "<tr>";
               // echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>RESULT OF THE POLICE VERIFICATION RECORD</b></td>";
               // echo "<td ><input type='text' name='remarks' class='form-control' value='".$value['remarks']."' ></td>";
               // echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='". $mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-pcc-".$value['pcc_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.PCC.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.PCC.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              foreach ($identity as $key => $value)  {
                $counter = ($key == 0) ? ''  : $key+1;


                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
                $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $remarks :  $remarks;
                } 


                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['identity']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>"; 

                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DOCUMENT PROVIDED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$doc_submited."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
               // echo "</tr>";
                
                 
                echo "</table>";


                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                      $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-identity-".$value['identity_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.IDENTITY.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.IDENTITY.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }
                
                foreach ($credit_report as $key => $value)  {
              
                $counter = ($key == 0) ? ''  : $key+1;

                $Verified_status = ($value['verfstatus'] == "" || $value['verfstatus'] == "WIP" || $value['verfstatus'] == "Insufficiency" || $value['verfstatus'] == "Stop Check" || $value['verfstatus'] == 'Unable to verify' || $value['verfstatus'] == 'Overseas check' || $value['verfstatus'] == 'YTR' || str_replace(" ",'', $value['verfstatus']) == "Workedwiththesameorganization") ? "NA" : "Verified";

                $value = array_map('ucwords', $value);

                $doc_submited = ($value['doc_submited'] != '') ? $value['doc_submited'] : "NA";
                $doc_submited = ($Verified_status == "NA") ?  "NA" : $doc_submited;

                $mode_of_verification = ($value['mode_of_verification'] != '') ? $value['mode_of_verification'] : "NA";
                $mode_of_verification = ($Verified_status == "NA") ?  "NA" : $mode_of_verification;

                $remarks = ($value['remarks'] != '') ? $value['remarks'] : "NA";
                if($value['verfstatus'] == "Insufficiency")
                {
                  $remarks = $value['insuff_raise_remark']; 
                }
                else
                {
                  $remarks = ($Verified_status == "NA") ? $remarks :  $remarks;
                } 

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['cbrver']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels'  style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> PARTICULARS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> VERIFIED INFORMATION</b></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DOCUMENT PROVIDED</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$doc_submited."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$mode_of_verification."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> REMARKS</b></td>";
                echo "<td style='text-align: left;'>&nbsp;&nbsp;".$remarks."</td>";
                echo "</tr>";
                
                 
                echo "</table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    echo "<ul class='sortable'>";
                    foreach ($attachments as $key => $attachment) {
                       $attach = explode('/', $attachment);
                        echo "<div class='col-md-4 order_by_image' id='item-credit_report-".$value['credit_report_id']."-".$attach[0]."'>";
                          echo "<div class='thumbnail'>";
                           $url  = "'".SITE_URL.CREDIT_REPORT.$attach[1].'/'.$attach[2]."'";
                           $myWin  = "'"."myWin"."'";
                           $attribute  = "'"."height=250,width=480"."'";
                           echo '<a href="javascript:;" ondblClick="myOpenWindow('.$url.','.$myWin.','.$attribute.'); return false"><img src="'.SITE_URL.CREDIT_REPORT.$attach[1].'/'.$attach[2].'" class="img-rounded" alt="attachments missign" width="304" height="236"></a>';
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
              }

              
            ?>
          </body>

         
          <?php

           echo '<br><div style="text-align:center;"><b>*** DISCLAIMER ***</b><br><br>';

           echo '<p style="text-align:justify;">All services and reports are provided in a professional manner in accordance with industry standards. Except as expressly provided, <b>'.CRMNAME.'</b> and its affiliates make no and disclaim any and all warranties and representations with respect to the services provided herein, whether such warranties and representations are express or implied in fact or by operation of law or otherwise, including without limitation implied warranties of merchantability and fitness for a particular purpose and implied warranties arising from the course of dealing or a course of performance with respect to the accuracy, validity, or completeness of any service or report.</p> <p style="text-align:justify;">Furthermore, <b>'.CRMNAME.'</b> and its affiliates
             expressly disclaim that the services will meet the clients requirements and <b>'.CRMNAME.'</b> and its affiliates express disclaim all such representations and warranties. <b>'.CRMNAME.'</b> merely passes on to client information in the form of reports which <b>'.CRMNAME.'.</b> has obtained in respect of the subject of a background screening verification. <b>'.CRMNAME.'</b> is not the author  or creator of that information. Given the limitations, no responsibility will be taken by <b>'.CRMNAME.'</b> for the consequences of client in reliance upon information contained in this information or report. However, <b>'.CRMNAME.'</b> will provide reasonable procedures to protect against any false information being provided to the client. The information within the reports is strictly confidential and is intended to be for the sole purpose of the clients 
        evaluation. The information and the reports are not intended for public dissemination.</p>
        <p style="text-align:justify;">Any questions or clarifications with respect to the report 
        can be addressed to <b>'.CRMNAME.'</b></p>';

         ?>
        </html>
       
      </div>

  </div>

  </section>

</div>


<?php 
}
?>
<script type="text/javascript">
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
</script>
<script src="<?= SITE_JS_URL ?>jquery-ui.min.js"></script>
<script type="text/javascript">
  $("ul.sortable" ).sortable({
    connectWith: '.sortable',
    revert: true
  });

  $( "ul.sortable" ).disableSelection();
</script>
<script type="text/javascript">
$('#rearrange_images').on('click', function(e){
      e.preventDefault();

  var sortable_data = []; 
  $('.order_by_image').each(function() {
      var id = $(this).attr('id');
      sortable_data.push(id);
  });

      $.ajax({ 
        data: 'sortable_data='+sortable_data,
        type: 'POST',
        url: '<?php echo ADMIN_SITE_URL.'Final_QC/update_arrage_img'; ?>',
        beforeSend:function(){
              $('#save').attr('disabled','disabled');
            },
            complete:function(){
              $('#save').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                show_alert(message,'success');
                location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
      });        
  });
</script>