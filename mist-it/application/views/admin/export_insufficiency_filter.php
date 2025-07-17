<div class="content-wrapper">
    <section class="content-header">
      <h1>Insufficiency List</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>reports"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <?php echo form_open('#', array('name'=>'frm_tat_status_report','id'=>'frm_tat_status_report')); ?>
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
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Initiation Date<span class="error"> *</span></label>
                  <div class="input-group input-daterange">
                      <input type="text" name="iniated_date_from" id="iniated_date_from" value="<?php echo set_value('iniated_date_from'); ?>" class="form-control" value="">
                      <div class="input-group-addon">to</div>
                      <input type="text" name="iniated_date_to" id="iniated_date_to" value="<?php echo set_value('iniated_date_to'); ?>" class="form-control" value="">
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
                <table id="tbl_datatable" class="table table-bordered"> 
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

  $('#frm_tat_status_report').validate({ 
    rules: {
    },
    messages: {
    },
    submitHandler: function(form) 
    {      
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'reports/address_export'; ?>',
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
              //$('#generate').removeAttr('disabled');
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
</script>