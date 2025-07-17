<div class="content-wrapper" style="background-color: #ffffff;">
  <section class="content-header">
    <h1>Final QC</h1>
    
     <ol class="breadcrumb">
         
          <li> 
            <button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
            <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>
            <button type='button' class='btn btn-danger' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Download</button>
           <!-- <button type='button' class='btn btn-success' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Approve & Send</button>-->
            <button type='button' class='btn btn-default' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Cancel</button>

          </li> 
        </ol>
  </section>

  <section class="content-report">
    <div class="row">
      <div class="col-xs-12 col-md-12">
        <html>
          <head>
            <title>Candidate Report</title>
            <style> table { margin-top: 15px; } table, td{ border: 1px solid black; padding-left: 5px;height: 35px;} .form-control { border: 0px none; }</style> 
          </head>
          <body>
            
            <table width="97%" cellpadding="5" cellspacing="pixels">
              <tr>
                <td bgcolor="#ccc" style='border-right:2px solid;'><b> NAME OF CANDIDATE</b></td>
                <td colspan="3"> <input type="text" name="" class="form-control" value="<?= $candidate_details['CandidateName'] ?>" > </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> GENDER</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= $candidate_details['gender'] ?>"> </td>
                <td bgcolor="#ccc"><b> DATE OF BIRTH</b></td>
                <td width="140px">  <input type="text" class="form-control" name="" value="<?= $candidate_details['DateofBirth'] ?>"> </td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE INITIATED</b></td>
                <td> <input type="text" name="" class="form-control" value="<?= $candidate_details['caserecddate'] ?>" > </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> DATE OF COMPLETION</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= $candidate_details['overallclosuredate'] ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> <?php echo REFNO; ?></b> </td>
                <td> <input type="text" name=""  class="form-control" value="<?= strtoupper($candidate_details['cmp_ref_no']) ?>"> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b>CLIENT REF NO</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= strtoupper($candidate_details['ClientRefNumber']) ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> ENTITY / PACKAGE</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= $candidate_details['entity_name'].' / '.$candidate_details['package_name'] ?>"> </td>
                <td bgcolor="#ccc"  style='border-right:2px solid;'><b> CASE STATUS</b></td>
                <td width="140px"> <input type="text" class="form-control" name="" value="<?= $candidate_details['overallstatus_value'] ?>"></td>
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
                $counter = ($key == 0) ? ' '  : ' - '.$key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['empver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['coname']."' > </td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($address as $key => $value)  {
                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['addrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($education as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['eduver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['qualification']."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($reference as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b>".$component_name['refver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['name_of_reference']."'> </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($court as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['courtver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($global_db as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['globdbver'].$counter."</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$full_address."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($drug as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['narcver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['drug_test_code']."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($pcc as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['crimver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['police_station']."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($identity as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['identity'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              foreach ($credit_report  as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;

                echo "<tr><td bgcolor='#ccc' style='border-right: 2px solid;'><b> ".$component_name['cbrver'].$counter."</b></td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['doc_submited']."' > </td>";
                echo "<td> <input type='text' name='' class='form-control' value='".$value['verfstatus']."'></td></tr>";
              }
              ?>
              </tr>
            </table>
            <?php
              foreach ($employment as $key => $value)  {
                $value = array_map('ucwords', $value);

                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td><input type='text' name='' class='form-control' value='".$value['coname']."' > </td>";
                echo "<td><input type='text' name='res_coname' class='form-control' value='".$value['res_coname']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EMPLOYEE ID</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['empid']."' > </td>";
                echo "<td><input type='text' name='res_empid' class='form-control' value='".$value['res_empid']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>PERIOD OF EMPLOYMENT<b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['emp_period']."'></td>";
                $verified = ($value['emp_period'] == $value['res_emp_period']) ? $value['res_emp_period'] : $value['emp_period'];
                echo "<td><input type='text' name='' class='form-control' value='".$verified."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['designation']."'></td>";
                echo "<td><input type='text' name='emp_designation' class='form-control' value='".$value['emp_designation']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REMUNERATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remuneration']."'></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['res_remuneration']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>REPORTING MANAGER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['r_manager_name']."'> </td>";
                echo "<td><input type='text' name='reportingmanager' class='form-control' value='".$value['reportingmanager']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REASON OF LEAVING</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['reasonforleaving']."'> </td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['res_reasonforleaving']."' ></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ANY INTEGRITY/DISCIPLINARY ISSUES</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['integrity_disciplinary_issue']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>EXIT FORMALITIES COMPLETED/PENDING</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['exitformalities']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS";
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
                </td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['verfname']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['modeofverification']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
              //  <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.EMPLOYMENT.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }

              }

              foreach ($address as $key => $value)  {

                $value = array_map('ucwords', $value);
                $period_of_stay = $value['stay_from'].' '.($value['stay_to'] != "") ? ' to '.$value['stay_to'] : $value['stay_to'];
                $res_period_of_stay = $value['res_stay_from'].' '.($value['res_stay_to'] != '') ? ' to '.$value['res_stay_to'] : $value['res_stay_to'];
                $verified = ($period_of_stay == $res_period_of_stay) ? 'verified' : $res_period_of_stay;

                $full_address = $value['address'].' '.$value['city'].' '.$value['pincode'].' '.$value['address_state'];
                $res_full_address = $value['res_address'].' '.$value['res_city'].' '.$value['res_pincode'].' '.$value['res_state'];
                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td><input type='text' name='res_full_address' class='form-control' value='".$res_full_address."' ></td>";
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
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
                echo "<td ><input type='text' name='add_comment' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$value['verified_by']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$value['mode_of_verification']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td><input type='text' name='res_remarks' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $add_attachments = ($value['add_attachments'] != NULL && $value['add_attachments'] != '') ?  explode('||', $value['add_attachments']) : '';
                
                if(!empty($add_attachments) && count($add_attachments) > 0)
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($add_attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.ADDRESS.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($education as $key => $value) {
                $value = array_map('ucwords', array_map('strtolower', $value));

                $school_college = $value['school_college'].'/'.$value['university_board'];
                $res_school_college = $value['res_school_college']. '/'.$value['res_university_board'];
                $verified = ($school_college == $res_school_college) ? 'Verified' : $res_school_college;

                $verf_details = ($value['verified_by'] != '') ? $value['verified_by'] : "";
                $verf_details .= ($value['verifier_designation'] != '') ? ','.$value['verifier_designation'] : "";
                $verf_details .= ($value['verifier_contact_details'] != '') ? ','.$value['verifier_contact_details'] : "";

                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>QUALIFICATION ATTAINED</b></td>";
                echo "<td>".$value['qualification']."</td>";
                echo "<td><input type='text' name='res_qualification' class='form-control' value='".$value['res_qualification']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME OF THE COLLEGE / UNIVERSITY</b></td>";
                echo "<td>".$school_college."</td>";
                echo "<td><input type='text' name='res_school_college' class='form-control' value='".$verified."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>YEAR OF PASSING</b></td>";
                echo "<td>".$value['year_of_passing']."</td>";
                echo "<td><input type='text' name='res_year_of_passing' class='form-control' value='".$value['res_year_of_passing']."'></td>";
                echo "</tr>";
                echo "</table>";

                echo "<table width='97%' cellpadding='5' cellspacing='pixels' style='border:2px solid;'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> DETAILS</b></td>";
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> FINDINGS</b></td>";
                echo "</tr>"; 
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS (IF ANY)</b></td>";
                echo "<td ><input type='text' name='res_remarks' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>VERIFIED BY</b></td>";
                echo "<td><input type='text' name='verified_by' class='form-control' value='".$verf_details."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='res_mode_of_verification' class='form-control' value='".$value['res_mode_of_verification']."'></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td><input type='text' name='res_remarks' class='form-control' value='".$value['res_remarks']."'></td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';

                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.EDUCATION.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($reference as $key => $value)  {
                $value = array_map('ucwords', $value);

                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td><input type='text' name='' class='form-control' value='".$value['name_of_reference']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>DESIGNATION</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['designation']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>CONTACT NUMBER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['contact_no']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL REMARKS</b></td>";
                echo "<td ><input type='text' name='' class='form-control' value='".$value['res_remarks']."' ></td>";
                echo "</tr>";
                echo "<tr><td></td><td>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.REFERENCES.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($court as $key => $value)  {
                $value = array_map('ucwords', $value);
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>CRIMINAL RECORD STATUS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='NA' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDITIONAL COMMENTS</b></td>";
                echo "<td ><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
              
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$value['mode_of_verification']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td  bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.COURT_VERIFICATION.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($global_db as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> CRIMINAL DATABASE STATUS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> ADDITIONAL COMMENTS</b></td>";
                echo "<td ><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> VERIFIED BY</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['verified_by']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
               /// echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.GLOBAL_DB.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }

              }

              foreach ($drug as $key => $value)  {
                $value = array_map('ucwords', $value);
                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td><input type='text' name='' class='form-control' value='".$value['amphetamine_screen']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> CANNABINOIDS SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['cannabinoids_screen']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc'  style='border-right: 2px solid;'><b>  COCAINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['cocaine_screen']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b>  OPIATES SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['opiates_screen']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;' ><b> PHENCYCLIDINE SCREEN, URINE</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['phencyclidine_screen']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b> REMARKS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
                //echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
               // <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.DRUGS.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($pcc as $key => $value)  {
                $value = array_map('ucwords', $value);
                $full_address = $value['street_address'].' '.$value['city'].' '.$value['pincode'].' '.$value['state'];
                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td><input type='text' name='police_station_visit_date' class='form-control' value='".$value['police_station_visit_date']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>ADDRESS</b></td>";
                echo "<td><input type='text' name='address' class='form-control' value='".$full_address."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>NAME AND DESIGNATION OF THE POLICE OFFICER</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['name_designation_police']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>CONTACT NUMBER OF POLICE</b></td>";
                echo "<td><input type='text' name='contact_number_police' class='form-control' value='".$value['contact_number_police']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>RESULT OF THE POLICE VERIFICATION RECORD</b></td>";
                echo "<td bgcolor='#ff0000'><input type='text' name='remarks' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>MODE OF VERIFICATION</b></td>";
                echo "<td><input type='text' name='mode_of_verification' class='form-control' value='".$value['mode_of_verification']."' ></td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='border-right: 2px solid;'><b>REMARKS</b></td>";
                echo "<td><input type='text' name='remarks' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
               
               // echo "<button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
                //<button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>";
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.PCC.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              foreach ($identity as $key => $value)  {
                $counter = ($key == 0) ? ''  : $key+1;
                echo "<table width='97%' cellpadding='5' cellspacing='pixels'>";
                echo "<tr>";
                echo "<td bgcolor='#ccc' style='text-align: center;height: 35px;'><b>".$component_name['identity']." VERIFICATION ".$counter."</b></td>";
                echo "</tr>";    
                echo "</table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.IDENTITY.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
                
                foreach ($credit_report as $key => $value)  {
                $value = array_map('ucwords', $value);

                $counter = ($key == 0) ? ''  : $key+1;
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
                echo "<td bgcolor='#ccc' style='border:2px solid;'><b> REMARKS</b></td>";
                echo "<td><input type='text' name='' class='form-control' value='".$value['remarks']."' ></td>";
                echo "</tr>";
                echo "<tr><td style='border:2px solid;'></td><td>";
                 
                echo "</td></tr></table>";

                $attachments = ($value['attachments'] != NULL && $value['attachments'] != '') ?  explode('||', $value['attachments']) : '';
                if(!empty($attachments))
                {
                    echo "<br>";
                    echo "<div class='col-xs-12 col-md-12'>";
                    foreach ($attachments as $key => $attachment) {
                        echo "<div class='col-md-4'>";
                          echo "<div class='thumbnail'>";
                            echo "<img src='".SITE_URL.DRUGS.$attachment."' class='img-rounded' alt='attachments missign' width='304' height='236'> ";
                          echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
              }

              }
            ?>
          </body>
        </html>
        <section class="content-header">
  <ol class="breadcrumb">
         
          <li> 
            <button type='button' class='btn btn-info' type='button' name='brn_first_qc_approve' id='brn_first_qc_approve'>Approve</button>
            <button type='button' class='btn btn-warning' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Reject</button>
            <button type='button' class='btn btn-danger' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Download</button>
       <!--     <button type='button' class='btn btn-success' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Approve & Send</button>-->
            <button type='button' class='btn btn-default' type='button' name='brn_first_qc_reject' id='brn_first_qc_reject'>Cancel</button>

          </li> 
        </ol>
</section>
      </div>

  </div>

  </section>

</div>

