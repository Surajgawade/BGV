<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?php echo CRMNAME; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="<?= SITE_IMAGES_URL; ?>appgini-icon.png" type="image/ico" />
  <title><?php echo isset($header_title) ? $header_title : CRMNAME ?></title>

  <link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
  <link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
  <link rel="stylesheet" href="<?= SITE_CSS_URL?>dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?= SITE_CSS_URL?>dataTables.checkboxes.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
  <link href="<?php echo SITE_CSS_URL; ?>daterangepicker.css" rel="stylesheet">
  <link href="<?php echo SITE_CSS_URL; ?>datepicker3.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">

  <script src="<?= SITE_JS_URL; ?>jquery.min.js"></script>
  <script src="<?= SITE_JS_URL; ?>bootstrap.min.js"></script>
  <script src="<?= SITE_JS_URL; ?>fastclick.js"></script>
  <script src="<?= SITE_JS_URL; ?>adminlte.min.js"></script>
  <script src="<?= SITE_JS_URL; ?>jquery.sparkline.min.js"></script>
  <script src="<?= SITE_JS_URL; ?>jquery.validate.min.js"></script>
  <script src="<?php echo SITE_JS_URL; ?>moment.min.js"></script>
  <script src="<?php echo SITE_JS_URL; ?>daterangepicker.js"></script>
  <script src="<?php echo SITE_JS_URL; ?>bootstrap-datepicker.js"></script>
  <script src="<?php echo SITE_JS_URL; ?>notify.js"></script> 
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
        <h1>Address Report List</h1>
      </section>
       <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <div class="box">
              <div class="box-header">
                <div class="box-body">
                  <table id="tbl_datatable" class="table table-bordered table-hover">
                    <thead><tr>
                      <th>Sr No.</th><th>Client Name</th><th>Entity</th><th>Package</th><th>Client Ref No</th><th><?php echo REFNO; ?></th><th>Candidate Name</th><th>Received Date</th><th>Component Ref No</th><th>Component Initiation Date</th><th>Vendor</th><th>Stay From</th><th>Stay To</th><th>Address type</th><th>Street Address</th><th>City</th><th>Pincode</th><th>State</th><th>Executive Name</th><th>Mode of verification</th><th>Resident Status</th><th>Landmark</th><th>Neighbour 1</th><th>Neighbour Details 1</th><th>Neighbour 2</th><th>Neighbour Details 2</th><th>Verified By</th><th>Addr. Proof Collected</th><th>verification Status</th><th>Remarks</th><th>QC Status</th><th>TAT Status</th><th>Check Closure Date</th><th>Last Activity</th>
                      </tr>
                    </thead>
                      <?php
                      $x = 1;
                      foreach ($lists as $key => $value) {
                        echo "<tr>";
                          echo "<td>".$x++."</td>";
                          echo "<td>".$value['client_name']."</td>";
                          echo "<td>".$value['entity_name']."</td>";
                          echo "<td>".$value['package_name']."</td>";
                          echo "<td>".$value['ClientRefNumber']."</td>";
                          echo "<td>".$value['cmp_ref_no']."</td>";
                          echo "<td>".$value['CandidateName']."</td>";
                          echo "<td>".$value['caserecddate']."</td>";
                          echo "<td>".$value['add_com_ref']."</td>";
                          echo "<td>".$value['iniated_date']."</td>";
                          echo "<td>".$value['vendor_id']."</td>";
                          echo "<td>".$value['stay_from']."</td>";
                          echo "<td>".$value['stay_to']."</td>";
                          echo "<td>".$value['address_type']."</td>";
                          echo "<td>".$value['address']."</td>";
                          echo "<td>".$value['city']."</td>";
                          echo "<td>".$value['pincode']."</td>";
                          echo "<td>".$value['state']."</td>";
                          echo "<td>".$value['executive_name']."</td>";
                          echo "<td>".$value['mode_of_verification']."</td>";
                          echo "<td>".$value['resident_status']."</td>";
                          echo "<td>".$value['landmark']."</td>";
                          echo "<td>".$value['neighbour_1']."</td>";
                          echo "<td>".$value['neighbour_details_1']."</td>";
                          echo "<td>".$value['neighbour_2']."</td>";
                          echo "<td>".$value['neighbour_details_2']."</td>";
                          echo "<td>".$value['verified_by']."</td>";
                          echo "<td>".$value['addr_proof_collected']."</td>";
                          echo "<td>".$value['verfstatus']."</td>";
                          echo "<td>".$value['remarks']."</td>";
                          echo "<td>".$value['first_qc_approve']."</td>";
                          echo "<td>".$value['tat_status']."</td>";
                          echo "<td>".$value['closuredate']."</td>";
                          echo "<td>".$value['last_activity_date']."</td>";
                        echo "</tr>";
                      }
                      ?>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
               <!-- <?php $access_export =($this->permission['access_scheduler']) ? '#myModelFilterList'  : ''; ?>-->
                <button class="btn btn-warning" data-toggle="modal" data-target="#myModelFilterList">Schedule Report</button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
</div>
<div id="myModelFilterList" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Schedule Report</h4>
      </div>
      <?php echo form_open('#', array('name'=>'frm_schedul_task','id'=>'frm_schedul_task')); ?>
      <div class="modal-body">
        <div class="row">
          <input type="hidden" name="report_id" id="report_id" value="<?php echo set_value('report_id',$report_id); ?>">
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
            <label>Activity Days</label>
            <?php
              $days = array('Sun' =>'Sun', 'Mon' =>'Mon', 'Tue' =>'Tue', 'Wed' =>'Wed', 'Thu' =>'Thu', 'Fri' =>'Fri', 'Sat' =>'Sat');
              echo form_multiselect('days[]', $days, set_value('days'), 'class="form-control multiSelect" id="days"');
              echo form_error('days'); 
            ?>
          </div>
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
            <label>Portal Users<span class="error">*</span></label>
            <?php
              unset($assigned_user_id[0]);
              echo form_multiselect('portal_users[]', $assigned_user_id, set_value('portal_users'), 'class="form-control multiSelect" id="portal_users"');
              echo form_error('portal_users');
            ?>
          </div>
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Activity Time</label>
              <input type="text" name="time_stamp" id="time_stamp" value="<?php echo set_value('time_stamp'); ?>" class="form-control myTimepicker" Placeholder='HH:MM' value="">
          </div>
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Report Name</label>
              <input type="text" name="report_name" id="report_name" value="<?php echo set_value('report_name'); ?>" class="form-control">
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success btn-md" id="btn_scheduler" name="btn_scheduler">Schedule</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>

  </div>
</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>version</b> 2.0 Developed By SM 
  </div>
  <strong>&copy; 2018 <a href="#"><?php echo CRMNAME; ?></a>.</strong> All rights
  reserved.
</footer>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-timepicker.min.css">
<script src="<?= SITE_JS_URL; ?>bootstrap-timepicker.min.js"></script>
<script src="<?= SITE_JS_URL; ?>jquery.dataTables.min.js"></script>
<script src="<?= SITE_JS_URL; ?>dataTables.bootstrap.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>dataTables.checkboxes.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script src="<?= SITE_JS_URL; ?>demo.js"></script>
<script type="text/javascript">

$(document).ready(function(){

  $('#tbl_datatable').DataTable({
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf']
  });
  
  $('.myTimepicker').timepicker({showInputs: false});

  $('#frm_schedul_task').validate({ 
      rules: {
        time_stamp : {
          required : true
        },
        'portal_users[]' : {
          required : true
        },
        'days[]' : {
          required : true
        },
        report_name : {
          required : true
        }
      },
      messages: { 
        time_stamp : {
          required : "Select Activity Time"
        },
        'portal_users[]' : {
          required : "Select Portal User"
        },
        'days[]' : {
          required : true
        },
        report_name : {
          required : "Enter Report Name"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'scheduler/set_scheduler'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_scheduler').attr('disabled','disabled');
            },
            complete:function(){
              $('#btn_scheduler').removeAttr('disabled');
            },
            success: function(jdata) {
              var message =  jdata.message || '';
              $('#myModelFilterList').modal('hide');
              $('#frm_schedul_task')[0].reset();
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
              }else {
                show_alert(message,'error'); 
              }
            }
        })
      }
  });

});
</script>
</body>
</html>