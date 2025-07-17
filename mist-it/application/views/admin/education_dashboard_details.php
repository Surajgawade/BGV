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
        <h1>Education List</h1>
      </section>
       <section class="content">
        <div class="row">
          <div class="col-xs-12 col-md-12">
            <div class="box">
              <div class="box-header">
                <div class="box-body">
                  <table id="tbl_datatable" class="table table-bordered table-hover">
                    <thead><tr>
                      <th>Sr No.</th><th>Client Ref No</th><th>Component Ref No</th><th>Component INT</th><th>Client Name</th><th>Entity</th><th>Package</th><th><?php echo REFNO; ?></th><th>Candidate Name</th><th>Vendor</th><th>Status</th><th>School/College</th><th>University</th><th>Qualification</th><th>Year of Passing</th><th>Executive Name</th><th>TAT Status</th><th>Due Date</th><th>Last Activity</th><th>Mode of verification</th>
                      </tr>
                    </thead>
                      <?php
                      $x = 1;
                      foreach ($lists as $key => $value) {
                        echo "<tr>";
                          echo "<td>".$x++."</td>";
                          echo "<td>".$value['ClientRefNumber']."</td>";
                          echo "<td>".$value['education_com_ref']."</td>";
                          echo "<td>".$value['iniated_date']."</td>";
                          echo "<td>".$value['client_name']."</td>";
                          echo "<td>".$value['entity_name']."</td>";
                          echo "<td>".$value['package_name']."</td>";
                          echo "<td>".$value['cmp_ref_no']."</td>";
                          echo "<td>".$value['CandidateName']."</td>";
                          echo "<td>".$value['vendor_name']."</td>";
                          echo "<td>".$value['verfstatus']."</td>";
                          echo "<td>".$value['school_college']."</td>";
                          echo "<td>".$value['university_name']."</td>"; 
                          echo "<td>".$value['qualification_name']."</td>";
                          echo "<td>".$value['year_of_passing']."</td>";
                          echo "<td>".$value['executive_name']."</td>";
                          echo "<td>".$value['tat_status']."</td>";
                          echo "<td>".$value['due_date']."</td>";
                          echo "<td>".convert_db_to_display_date($value['last_activity_date'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                          echo "<td>".$value['mode_of_veri']."</td>";                         
                        echo "</tr>";
                      }
                      ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
</div>

<footer class="main-footer">
 
  <strong>&copy; 2020 <a href="#"><?php echo CRMNAME; ?></a>.</strong> All rights
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
    buttons: []
  });
  
  $('.myTimepicker').timepicker({showInputs: false});

});
</script>
</body>
</html>