<div class="content-wrapper">
    <section class="content-header">
      <h1>Court Report Export</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>reports"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <?php echo form_open('#', array('name'=>'frm_court_report','id'=>'frm_court_report')); ?>
              <div class="box-body">
                <div class="box-header">
                  <h3 class="box-title">Case Details</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="form-control" id="clientid"');
                    echo form_error('clientid');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Entiy<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('entity', array(), set_value('entity'), 'class="form-control" id="entity"');
                    echo form_error('entity');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Package<span class="error"> *</span></label>
                   <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
                  <?php echo form_error('package');?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Case Received Date<span class="error"> *</span></label>
                  <div class="input-group input-daterange">
                      <input type="text" name="overallclosuredate_from" id="overallclosuredate_from" value="<?php echo set_value('overallclosuredate_from'); ?>" class="form-control" value="">
                      <div class="input-group-addon">to</div>
                      <input type="text" name="overallclosuredate_to" id="overallclosuredate_to" value="<?php echo set_value('overallclosuredate_to'); ?>" class="form-control" value="">
                  </div>
                </div>
                <div class="clearfix"></div>
                <hr style="border-top: 2px solid #bb4c4c;">

                <div class="box-header">
                  <h3 class="box-title">Check Address</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Initiation Date<span class="error"> *</span></label>
                  <div class="input-group input-daterange">
                      <input type="text" name="iniated_date_from" id="iniated_date_from" value="<?php echo set_value('iniated_date_from'); ?>" class="form-control" value="">
                      <div class="input-group-addon">to</div>
                      <input type="text" name="iniated_date_to" id="iniated_date_to" value="<?php echo set_value('iniated_date_to'); ?>" class="form-control" value="">
                  </div>
                </div>

                <div class="col-md-4 col-sm-8 col-xs-4 form-group">
                  <label>Address type</label>
                  <?php
                   echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type'), 'class="form-control" id="address_type"');
                    echo form_error('address_type'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address</label>
                  <textarea name="street_address" rows="1" id="street_address" class="form-control"><?php echo set_value('street_address'); ?></textarea>
                  <?php echo form_error('street_address'); ?>
                </div>
                <div class="clearfix"></div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City</label>
                  <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
                  <?php echo form_error('city'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Pincode</label>
                  <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
                  <?php echo form_error('pincode'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('state', $states, set_value('state'), 'class="form-control singleSelect" id="state"');
                    echo form_error('state');
                  ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Executive Name<span class="error">*</span></label>
                  <?php
                    echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id'), 'class="form-control cls_disabled" id="has_case_id"');
                    echo form_error('has_case_id');
                  ?>
                </div>
                <div class="clearfix"></div>
                <hr style="border-top: 2px solid #bb4c4c;">

                <div class="box-header">
                  <h3 class="box-title">Result Details</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Status<span class="error"> *</span></label>
                  <?php 
                    $status = array_combine($status, $status);
                    echo form_dropdown('verfstatus', $status, set_value('verfstatus'), 'class="form-control" id="verfstatus"');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Sub Status<span class="error"> *</span></label>
                  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status', $sub_status, set_value('filter_by_sub_status'), 'class="form-control" id="filter_by_sub_status"');?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Mode of verification</label>
                  <?php
                   echo form_dropdown('mode_of_verification', $this->emp_modeofverification, set_value('mode_of_verification'), 'class="form-control" id="mode_of_verification"');
                    echo form_error('mode_of_verification'); 
                  ?>
                </div>
                <dic class="clearfix"></dic>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>QC Status</label>
                  <?php
                   echo form_dropdown('first_qc_approve', array(), set_value('first_qc_approve'), 'class="form-control" id="first_qc_approve"');
                    echo form_error('first_qc_approve'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>TAT Status</label>
                  <?php
                   echo form_dropdown('tat_status', array(), set_value('tat_status'), 'class="form-control" id="tat_status"');
                    echo form_error('tat_status'); 
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Check Close Date <span class="error"> *</span></label>
                  <div class="input-group input-daterange">
                      <input type="text" name="closuredate_from" id="closuredate_from" value="<?php echo set_value('closuredate_from'); ?>" class="form-control" value="">
                      <div class="input-group-addon">to</div>
                      <input type="text" name="closuredate_to" id="closuredate_to" value="<?php echo set_value('closuredate_to'); ?>" class="form-control" value="">
                  </div>
                </div>
                <div class="clearfix"></div>
                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="generate" id='generate' value="Generate" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#result_log_tabs" id="view_result_log_tabs" data-toggle="tab">Log</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="result_log_tabs">
                <table id="tbl_datatable" class="table table-bordered datatable_logs"> 
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Created By</th>
                      <th>Created On</th>
                    </tr>
                  </thead>
                  <?php
                    $x = 1;
                    foreach ($logs as $key => $value) {
                      echo "<tr>";
                          echo "<td>".$x++."</td>";
                          echo "<td>".$value['executive_name']."</td>";
                          echo "<td>".convert_db_to_display_date($value['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
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

<script>
$(document).ready(function(){
  
  $('#clientid').on('change',function(){
    var clientid = $(this).val();
    if(clientid != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            success:function(jdata) {
              if(jdata.status = 200)
              {
                $('#cmp_ref_no').val(jdata.cmp_ref_no);
                $('#entity').empty();
                $.each(jdata.entity_list, function(key, value) {
                  $('#entity').append($("<option></option>").attr("value",key).text(value));
                });
              }
            }
        });
    }
  }).trigger('change');

  $(document).on('change', '#entity', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  });

  $('#frm_court_report').validate({ 
    rules: {
    },
    messages: {
    },
    submitHandler: function(form) 
    {      
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'reports/court_export'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#generate').attr('disabled','disabled');
              $('.body_loading').show();
            },
            complete:function(){
             // $('#generate').removeAttr('disabled');
              $('.body_loading').hide();
            },
            success: function(jdata) {
              var divText = jdata.message;
              var myWindow = window.open('','reports_popup','width=900,height=640');
              var doc = myWindow.document;
              doc.open();
              doc.write(divText);
              doc.close();
            }
        })
      }
  });

  $('#tbl_datatable').DataTable({
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      "order": [[ 0, "asc" ]],
       "pageLength": 5,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
  });

});

$(document).on('change', '#verfstatus', function(){
  var status = $("#verfstatus option:selected").html();
  $.ajax({
      type:'POST',
      data:'status='+status,
      url : "<?= ADMIN_SITE_URL ?>candidates/sub_status_list",
      beforeSend :function(){
          jQuery('#filter_by_sub_status').find("option:eq(0)").html("Please wait..");
      },
      complete:function(){
          jQuery('#filter_by_sub_status').find("option:eq(0)").html("Sub Status");
      },
      success:function(jdata) {
          $('#filter_by_sub_status').empty();
          $.each(jdata.sub_status_list, function(key, value) {
            $('#filter_by_sub_status').append($("<option></option>").attr("value",key).text(value));
          });
      }
  });
});
</script>