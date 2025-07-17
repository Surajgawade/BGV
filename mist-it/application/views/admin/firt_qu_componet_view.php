<div class="content-wrapper">
    <section class="content-header">
      <h1>First QC</h1>
    </section>
     <section class="content">
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
          <div class="filterable">
            <div class="box-body">
              <table id="tbl_datatable" class="table table-bordered table-hover">
                <thead>
                  <tr class="filters">
                  <th>#</th>
                  <th><input type="text" class="form-control" placeholder="Created On"></th>
                  <th><input type="text" class="form-control" placeholder="Component"></th>
                  <th><input type="text" class="form-control" placeholder="Status"></th>
                  <th><input type="text" class="form-control" placeholder="Candidate Name"></th>
                  <th><input type="text" class="form-control" placeholder="Client Name"></th>
                  <th><input type="text" class="form-control" placeholder="Entity"></th>
                  <th><input type="text" class="form-control" placeholder="Package"></th>
                  <th><input type="text" class="form-control" placeholder="Created By"></th>
                  <th><input type="text" class="form-control" placeholder="Case Received Date"></th>
                  <th><input type="text" class="form-control" placeholder="Component Ref No"></th>
                  <th><input type="text" class="form-control" placeholder="<?php echo REFNO; ?>"></th>
                  <th><input type="text" class="form-control" placeholder="Client Ref No"></th>
                  
                </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                   
                    if(!empty($address))
                    {

                    foreach ($address as $key => $value) {
                      $com_id = encrypt($value['id']);
                      $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
			                     
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_address/" '.$status_color.' >';

                     // echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';0
                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Address</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['add_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                      
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  if(!empty($Employment))
                  {
                    foreach ($Employment as $key => $value) {
                      $com_id = encrypt($value['id']);
                       $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_employment/" '.$status_color.' >';
                    // echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';
                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Employment</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['emp_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  if(!empty($education))
                    {
                    foreach ($education as $key => $value) {
                      $com_id = encrypt($value['education_id']);

                        $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_education/" '.$status_color.' >';
                       //echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';
                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Education</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['education_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                      echo "</tr>";
                      $counter++;
                    }
                  }
                   if(!empty($reference))
                    {
                    foreach ($reference as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_reference/"  '.$status_color.' >';

                     //  echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';

                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Reference</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['reference_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                     
                      echo "</tr>";
                      $counter++;
                    }
                  }

                  if(!empty($court))
                    {
                    foreach ($court as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_court/"  '.$status_color.' >';

                      // echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';
                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Court</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['court_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                     
                      
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  if(!empty($global_db))
                    {
                    foreach ($global_db as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_global/"  '.$status_color.' >';

                     //  echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';

                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Global DB</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['global_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";
                          
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  
                   if(!empty($pcc))
                    {
                    foreach ($pcc as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_pcc/"   '.$status_color.' >';

                    //   echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';

                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>PCC</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['pcc_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";  
                     
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  if(!empty($identity))
                    {
                    foreach ($identity as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_identity/"  '.$status_color.' >';

                     //  echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';

                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Identity</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['identity_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>";  

                      echo "</tr>";
                      $counter++;
                    }
                  }
                  if(!empty($credit_report))
                    {
                    foreach ($credit_report as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_credit_report/"  '.$status_color.' >';

                     //  echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';

                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Credit Report</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['credit_report_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>"; 
                     
                     
                      echo "</tr>";
                      $counter++;
                    }
                  }

                    if(!empty($drug))
                    {
                    foreach ($drug as $key => $value) {
                      $com_id = encrypt($value['id']);
                         $status  = $value['verfstatus'];
			            switch( $status)
			            {
			              case "Major Discrepancy" :
			              
			                $status_color = 'style="background-color: red;"';
			                 break;

			              case "Minor Discrepancy" :
			              
			                  $status_color = 'style="background-color: orange;"';
			                 break;

			              case "Unable to verify" :
			              
			                 $status_color = 'style="background-color: yellow;"';
			                 break; 

			              default :
			                 $status_color = 'style="background-color: white;"';
			                
			            }
                      echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form_drugs/"  '.$status_color.' >';

                      // echo '<tr class="open_popup" data-com_id="'.$com_id.'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.'first_QC/report_html_form/">';
                      echo "<td>".$counter."</td>";
                      echo "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>Drug</td>";
                      echo "<td>".$value['verfstatus']."</td>";
                      echo "<td>".$value['CandidateName']."</td>";
                      echo "<td>".$value['clientname']."</td>";
                      echo "<td>".$value['entity_name']."</td>";
                      echo "<td>".$value['package_name']."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo "<td>".$value['drug_com_ref']."</td>";
                      echo "<td>".$value['cmp_ref_no']."</td>";
                      echo "<td>".$value['ClientRefNumber']."</td>"; 
                      
                      
                      echo "</tr>";
                      $counter++;
                    }
                  }
                  
                  ?>
                </tbody>
              </table>
            </div>
           </div>
          </div>
        </div>
      </div>
    </section>
</div>
<div id="myModalCasesAssign" class="modal fade" role="dialog">
  <div class="modal-dialog popup_style">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">First QC</h4>
         <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">Cancel</button>
      </div>

      <div class="modal-body" id="append_html"></div>
      <div class="modal-footer">
       <!-- <button type="button" class="btn btn-info" type="button" name="brn_first_qc_approve" id="brn_first_qc_approve">Approve</button>
        <button type="button" class="btn btn-warning" type="button" name="brn_first_qc_reject" id="brn_first_qc_reject">Reject</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {

  var oTable =  $('#tbl_datatable').DataTable( {

      "serverSide": false,
      "processing": false,
       bSortable: true,
       bRetrieve: true,
       scrollX: true,
      "iDisplayLength": 25, // per page
      "language": {
        "emptyTable": "No Record Found",
        "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 65px;'>" 
    } 

  });
  

  $(document).on('click', '.approve_first_qc', function(){
    var frist_qc_id = $(this).data('frist_qc_id');
    var controller = $(this).data('frist_comp_url');
    var frist_cands_id = $(this).data('frist_cands_id');
    var frist_comp_id = $(this).data('frist_comp_id');
    var frist_cands_name = $(this).data('frist_cands_name');
    var frist_ref_no = $(this).data('frist_ref_no');
    var approve_case = "/approve_first_qc";
  
    var url = "<?= ADMIN_SITE_URL ?>";
    if(frist_qc_id != 0 && controller != "" && frist_cands_id != '')
    {
        $.ajax({
            type:'POST',
            url:url+controller+approve_case,
            data: 'frist_qc_id='+frist_qc_id+'&frist_cands_id='+frist_cands_id+'&frist_comp_id='+frist_comp_id+'&frist_cands_name='+frist_cands_name+'&frist_ref_no='+frist_ref_no+'&first_qc_status=approve',
            datatype: 'json',
            beforeSend :function(){
                jQuery('.body_loading').show();
            },
            complete:function(){
                jQuery('.body_loading').hide();
            },
            success:function(jdata) {
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $('#myModalCasesAssign').modal('hide');
                location.reload();
              } else {
                show_alert(message,'error'); 
              }
            }
        });
    }
    else {
        show_alert('Access Denied, You don’t have permission to access this page');
    }
  });

  $(document).on('click', '.reject_first_qc', function(){
    var reject_reason = prompt("Reject Reason", "");
    var frist_qc_id = $(this).data('frist_qc_id');
    var controller = $(this).data('frist_comp_url');
    var frist_cands_id = $(this).data('frist_cands_id');
    var frist_comp_id = $(this).data('frist_comp_id');
    var frist_cands_name = $(this).data('frist_cands_name');
    var frist_ref_no = $(this).data('frist_ref_no');
    
    var rejected_case = "/rejected_first_qc";
    var url = "<?= ADMIN_SITE_URL ?>";
    if(frist_qc_id != 0 && controller != "" && reject_reason != null && reject_reason != "")
    {
        $.ajax({
            type:'POST',
            url:url+controller+rejected_case,
            data: 'frist_qc_id='+frist_qc_id+'&frist_cands_id='+frist_cands_id+'&reject_reason='+reject_reason+'&frist_comp_id='+frist_comp_id+'&frist_cands_name='+frist_cands_name+'&frist_ref_no='+frist_ref_no+'&first_qc_status=reject',
            datatype: 'json',
            beforeSend :function(){
                jQuery('.body_loading').show();
            },
            complete:function(){
                jQuery('.body_loading').hide();
            },
            success:function(jdata) {
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $('#myModalCasesAssign').modal('hide');
                location.reload();
              } else {
                show_alert(message,'error'); 
              }
            }
        });
    }
    else {
      show_alert('Please enter reject reason');
    }
  });

  $(document).on('dblclick', '.open_popup', function(){
    var com_id = $(this).data('com_id');
    var tab_name = $(this).data('tab_name');
    var url = $(this).data('url');
    if(com_id != 0 && url != "")
    {
        $.ajax({
            type:'GET',
            url:url+com_id,
            beforeSend :function(){
                jQuery('.body_loading').show();
            },
            complete:function(){
                jQuery('.body_loading').hide();
            },
            success:function(html) {
                jQuery('#'+tab_name).html(html);
                jQuery('#myModalCasesAssign').modal('show');
            }
        });
    }
    else {
        show_alert('Access Denied, You don’t have permission to access this page');
    }
  });

});
</script>

<script type="text/javascript">
  $(document).ready(function(){
  
    $('.filterable .btn-filter').click(function(){

        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
      
        /* Ignore tab key */
        var code = e.keyCode || e.which;
      
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');

        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
        var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});
</script>
<style type="text/css">
  .filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
    size:5px;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
    text-align: center; 

}

</style>
