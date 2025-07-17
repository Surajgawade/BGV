<div class="content-page">
  <div class="content">
    <div class="container-fluid">
  

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Cron Job</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>Cron_job">Cron Job</a></li>
                  <li class="breadcrumb-item active">List View</li>
              </ol>
      
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>scheduler"><i class="fa fa-arrow-left"></i> Back</button></li> 

                  <li><button class="btn btn-secondary waves-effect btn-sm CronRaiseModel" data-accessUrl="<?= ADMIN_SITE_URL?>scheduler">Add Cron Job</button></li> 
                </ol>
              </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            
              <div style="float: right;">
                <button class="btn btn-info btn-sm direct_access_url" data-accessUrl ="<?php echo ADMIN_SITE_URL.'Cron_job/Set_cron_job' ?>">Run Cron Job</button>
              </div>
              <div class="clearfix"></div>
             
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr>
                      <th>Sr No</th>
                      <th>Activity Name</th>
                      <th>Last Executed By</th>
                      <th>Last Executed On</th>
                      <th>Status</th>
                      <th>Action</th>  
                    </tr>
                  </thead>
                  <tbody>
                      <?php

                  if ($this->permission['access_report_cron_list'] == 1) {   
                    $i = 1;
                    foreach ($lists as $list)
                    {
                       
                        $run_status = ($list['status'] == '1') ? "Success" : '';
                        echo  "<tr>";
                        echo  "<td>".$i."</td>";
                       
                        echo  "<td>".$list['activity_name']."</td>";
                        echo  "<td>".$list['user_name']."</td>";
                        echo  "<td>".convert_db_to_display_date($list['executed_on'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)."</td>";
                        echo  "<td>".$run_status."</td>";
                        if($list['cronjob_id'] == 1)
                        {
                           echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="#VendorWIp">Run</button>'; 
                        }
                        if($list['cronjob_id'] == 2)
                        {
                           echo form_open_multipart('#', array('name'=>'frm_tat_calculation','id'=>'frm_tat_calculation'));
                           echo '<td><button type="submit" class="btn btn-sm btn btn-info" id="btn_tat_calculation">Calculate Tat</button>&nbsp;';
                          echo form_close(); 
                        }
                        if($list['cronjob_id'] == 4)
                        {
                           echo form_open_multipart('#', array('name'=>'fino_finance_send_mail','id'=>'fino_finance_send_mail'));
                           echo '<td><button type="submit" class="btn btn-sm btn btn-info" id="btn_fino_finance">Send Mail</button>&nbsp;';
                          echo form_close(); 
                        }
                        if($list['cronjob_id'] == 5)
                        {

                          echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="#component_selection">Modify</button>'; 
                          
                        }

                        if($list['cronjob_id'] == 6)
                        {

                          echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="#final_qc_approve_selection">Modify</button>'; 
                          
                        }
                        echo "</tr>";
                        $i++;
                    }
                  }
                  else{
                    echo "<tr>";
                    echo "<td colspan = '6' align = 'center'>Not Permission </td>";
                    echo "<tr>";
                  }     
                  ?>
                  </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
</div>

<div id="cron_job_add_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'add_cron_job','id'=>'add_cron_job')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Cron Job</h4>
      </div>
      <div class="modal-body">
       
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label>Common Settings</label>
            <input type="text" name="common_settings" id="common_settings" value="<?php echo set_value('common_settings'); ?>" class="form-control">
            <?php echo form_error('common_settings'); ?>
          </div>
          <div class="col-md-4 col-sm-4 col-xs-4 form-group">
            <label>Minute:</label>
            <input type="text" name="minutes" id="minutes" value="<?php echo set_value('minutes'); ?>" class="form-control">
            <?php echo form_error('minutes'); ?>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-8 form-group">
            <label>&nbsp;</label>
            <input type="text" name="minutes" id="minutes" value="<?php echo set_value('minutes'); ?>" class="form-control">
            <?php echo form_error('minutes'); ?>
          </div>

           <div class="col-md-4 col-sm-4 col-xs-4 form-group">
            <label>Hour:</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-8 form-group">
            <label>&nbsp;</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-4 form-group">
            <label>Day:</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-8 form-group">
            <label>&nbsp;</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-4 form-group">
            <label>Month:</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-8 form-group">
            <label>&nbsp;</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>

          <div class="col-md-4 col-sm-4 col-xs-4 form-group">
            <label>Weekday:</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-8 form-group">
            <label>&nbsp;</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label>Command:</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>

           <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <label>Insert Controllers and function Name(example controller name/function name):</label>
            <input type="text" name="hour" id="hour" value="<?php echo set_value('hour'); ?>" class="form-control">
            <?php echo form_error('hour'); ?>
          </div>
        </div>
        
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Add New Cron Job</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="VendorWIp" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_wip_vendor_details','id'=>'frm_wip_vendor_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Vendor Selection</h4>
      </div>
      <div class="modal-body">
       
        <div class="row">
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Select Vendor</label>
             <?php
          
               echo form_dropdown('vendor_list', $vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list"');?>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_wip_vendor_details" name="btn_wip_vendor_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="component_selection" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_aq_component_selection','id'=>'frm_aq_component_selection')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Component Selection</h4>
      </div>
      <div class="modal-body">
       
        <div class="row">
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Select Component</label><br>
              <?php
         
               $component_id = explode(',', $components_aq_selection[0]['cron_job_component_selection']);

              echo "<table id = 'component' name = 'component' border = 1 >";

              echo "<tr>";
              echo  "<td>Component Name</td><td>Selection</td>";
              echo "</tr>";
              foreach ($components as $key => $value) {
                
                $data = array('name'          => "components[]",
                    'id'            => 'components',
                    'value'         => $value['component_key']
                );
                if(in_array($value['component_key'], $component_id))
                {
                    $data['checked'] = TRUE;
                }
  

                echo "<tr>";
                echo  "<td>".$value['component_name']. "</td><td>".form_checkbox($data)."</td>";
                echo "</tr>";
              }
              echo "</table>";
        
              ?>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_aq_component_selection" name="btn_aq_component_selection" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="final_qc_approve_selection" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_aq_final_qc_selection','id'=>'frm_aq_final_qc_selection')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Final QC Selection</h4>
      </div>
      <div class="modal-body">
       
        <div class="row">
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Select Final QC</label><br>
              <?php
            
              echo "<table id = 'final' name = 'final' border = 1 >";

              echo "<tr>";
              echo  "<td>Final AQ Cron</td><td>Selection</td>";
              echo "</tr>";
              echo "<tr>";
              echo  "<td>Active</td>";
              if($final_qc_aq_selection[0]['cron_job_component_selection'] == "Yes"){
                 $check =  "checked = 'check'";
              }
              else{
                 $check = "";
              }
              echo  "<td><input type = 'checkbox' name = 'final_qc_selection' id = 'final_qc_selection' ".$check."></td>";
              echo "</tr>";
       
              echo "</table>";
        
              ?>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_aq_final_qc_selection" name="btn_aq_final_qc_selection" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('#tbl_datatable').DataTable({
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      "order": [[ 0, "asc" ]],
      scrollX: true,
       "pageLength": 25,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
  });
})


  $('#frm_wip_vendor_details').validate({ 

     
      submitHandler: function(form) 
      {  
        
        <?php  if ($this->permission['access_report_cron_run'] == 1) { ?>
          

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/set_wip_vendor_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_wip_vendor_details').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_ikya_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        }); 
        
       <?php  } else {  ?>
            alert('You do have not permission to access to send mail');
       <?php } ?>
      }

  }); 


   $('#frm_tat_calculation').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/tat_calculation'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_tat_calculation').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_ikya_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  }); 


  $('#fino_finance_send_mail').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/fino_finance_send_mail'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_fino_finance').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_ikya_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  }); 


$(document).on('click','.CronRaiseModel',function() {
 
  $('#cron_job_add_model').modal('show');
  $('#cron_job_add_model').addClass("show");
  $('#cron_job_add_model').css({background: "#0000004d"}); 
});
</script>


<script>
     
    
  
  $('#frm_aq_component_selection').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/component_selection_aq'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_aq_component_selection').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_aq_component_selection').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });  

  $('#frm_aq_final_qc_selection').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/final_qc_selection_aq'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_aq_final_qc_selection').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_aq_final_qc_selection').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });  


     $('#frm_court_export').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Cron_job/export_to_excel_courtver'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_court_export').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_court_export').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
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
