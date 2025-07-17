<div class="content-wrapper">
  <section class="content-header">
    <h1>KYC Verification</h1>
    <ol class="breadcrumb">
      <li><button class="btn btn-default btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>KYC_collection"><i class="fa fa-arrow-left"></i> Back</button></li> 
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="nav-tabs-custom">
          <?php $this->load->view('admin/kyc_edit'); ?> 
        </div> 
        <div class="nav-tabs-custom">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity_log_tabs" id="view_activity_log_tabs" data-toggle="tab">Activity</a></li>
              <li><a href="#clk_insuff" id='clk_insuff_log' data-emp_id="<?php echo $selected_data['id']; ?>" data-toggle="tab">Insufficiency</a></li>
              <li><a href="#address_log_tabs" id="view_insuff_log_tabs" data-toggle="tab">KYC Log</a></li>
              <li><a href="#result_log_tabs" id="view_result_log_tabs" data-toggle="tab">Result Log</a></li>
              <li><a href="#vendor_log_tabs" id="vendor_tab" data-toggle="tab">Vendor</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity_log_tabs">
                <table id="tbl_activity_log" class="table table-bordered datatable_logs"></table>
              </div>
              <div class="tab-pane" id="clk_insuff">
                <div style="float: right;"><button class="btn btn-info btn-sm InsuffRaiseModel" data-editUrl='<?=($this->permission['access_insuff']) ? $insuff_raise_id : ''; ?>' <?php echo $check_insuff_raise;?>>Raise Insuff</button><br>
                </div>
                <div id='append_insuff_view'></div>
              </div>
              <div class="tab-pane" id="address_log_tabs">
                <span id="emp_log"></span>
                <table id="tbl_address_log" class="table table-bordered datatable_logs"></table>
              </div>
              <div class="tab-pane" id="result_log_tabs">
                <table id="tbl_address_result" class="table table-bordered datatable_logs"></table>
              </div>
              <div class="active tab-pane" id="vendor_log_tabs">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result','id'=>'add_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" name="kyc_id" value="<?php echo set_value('kyc_id',$selected_data['id']); ?>">
      <input type="hidden" name="identity_result_id" value="<?php echo set_value('identity_result_id',$selected_data['identity_result_id']); ?>">
      
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$selected_data['clientid']); ?>">
      <div id="append_result_model"></div>
      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>

      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="14">
            <input type="hidden" name="comp_table_id" value="<?php echo set_value('comp_table_id',$selected_data['id']); ?>">
            <input type="hidden" name="ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber',$selected_data['ClientRefNumber']); ?>">
            <div class="append-activity_view"></div>
          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
      <div class="append-activity_records"></div>
    </div>
  </div>
</div>

<div id="insuffClearModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open_multipart("#", array('name'=>'frm_insuff_clear','id'=>'frm_insuff_clear')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insuff Clear</h4>
      </div>
      <div class="modal-body">
        <span class="errorTxt"></span>
        <input type="hidden" name="check_insuff_raise" id="check_insuff_raise" value="<?php echo set_value('check_insuff_raise'); ?>">
        <input type="hidden" name="clear_clientid" value="<?php echo set_value('clientid',$selected_data['clientid']); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="<?php echo set_value('clear_update_id',$selected_data['id']); ?>">
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Clear Date<span class="error"> *</span></label>
          <input type="text" name="insuff_clear_date" id="insuff_clear_date" value="<?php echo set_value('insuff_clear_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('insuff_clear_date'); ?>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-8 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_remarks" rows="1" maxlength="500" id="insuff_remarks"  class="form-control"><?php echo set_value('insuff_remarks'); ?></textarea>
          <?php echo form_error('insuff_remarks'); ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error"> *</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
        </div>
      </div>  
      <div class="modal-footer">
        <button type="submit" id="btn_insuff_clear" name="btn_insuff_clear" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="insuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insiff</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$selected_data['id']); ?>">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise" disabled="disabled" id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',$this->insuff_date); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('txt_insuff_raise'); ?>
        </div>
        <div class="col-md-6 col-sm-8 col-xs-6 form-group">
          <label>Reason</label>
          <?php
           echo form_dropdown('insff_reason', $insuff_reason_list, set_value('insff_reason'), 'class="form-control setinsff_reason" id="insff_reason"');
            echo form_error('insff_reason'); 
          ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_raise_remark" rows="1" maxlength="500" id="insuff_raise_remark"  class="form-control insuff_raise_remark"><?php echo set_value('insuff_raise_remark'); ?></textarea>
          <?php echo form_error('insuff_raise_remark'); ?>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<div id="editInsuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 60%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_edit_insuff_raise','id'=>'frm_edit_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Details</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$selected_data['id']); ?>">
        <div id="appedEditIsuffClear"></div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script>
$(document).on('change', '.setinsff_reason', function(){
  var insff_reason = $(this).val();
  if(insff_reason != 0)
  {
    $.ajax({
      type:'POST',
      data : 'insff_reason='+insff_reason,
      dataType:'json',
      url:'<?php echo ADMIN_SITE_URL.'employment/insuff_raise_remark/'; ?>',
      success:function(jdata) {
        jQuery('.insuff_raise_remark').val(jdata.message);
      }
    });
  } 
});

$(document).on('click','.InsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  $('#insuffRaiseModel').modal('show');
});

$(document).on('click', '#clk_insuff_log', function(){
  var emp_id = $(this).data('emp_id');
  if(emp_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'identity/insuff_tab_view/'; ?>'+emp_id,
          beforeSend :function(){
            jQuery('#append_insuff_view').html("Please wait...");
          },success:function(html) {
            jQuery('#append_insuff_view').html(html);
          }
      });
  } 
});

 
$(document).on('click','.clkInsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    var clientname = $(this).text();
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'identity/insuff_edit_clear_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        beforeSend :function(){
          jQuery(this).text("loading...");
        },success:function(html) {
          $('#appedEditIsuffClear').html(html);
          $('#editInsuffRaiseModel').modal('show');   
        }
    });
  }
});

$(document).on('click','.insuffClearModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'identity/insuff_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        dataType:'json',
        beforeSend :function(){
          jQuery('.insuffClearModel').text("loading...");
        },success:function(jdata) {
          jQuery('.insuffClearModel').text("Clear");
          $('#check_insuff_raise').val(jdata['insuff_raised_date']);
          $('#insuff_clear_id').val(jdata['id']);
          $('#insuff_clear_date').val(jdata['insuff_clear_date']);
          $('#insuff_remarks').val(jdata['insuff_remarks']);
          $('#insuffClearModel').modal('show');
        }
    });
  } else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }
});

$(document).on('click','.insuffDelete',function() {
  var insuff_data = $(this).data('editurl'); 
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'identity/insuff_delete'; ?>',
        data : 'insuff_data='+insuff_data,
        dataType:'json',
        beforeSend :function(){
          jQuery('.insuffDelete').text("Deleting...");
        },success:function(jdata) {
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }else {
            show_alert(message,'error'); 
          }
        }
    });
  }else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }
});

$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url,function(){
      $('#addAddResultModel').modal('show');
    });
  }
});

$(document).ready(function(){
  if('<?=!empty($check_insuff_raise)?>') {
    show_alert('<?= $insuff_raise_message?>')
  }

  $('#frm_edit_insuff_raise').validate({ 
    rules: {
      update_id : {
        required : true
      },
      insff_reason : {
        required : true,
        greaterThan : 0
      },
      txt_insuff_raise : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      insff_reason : {
        required : "Select Reason",
        greaterThan : "Select Reason"
      },
      txt_insuff_raise : {
        required : "Select Insuff Date"
      },
    },
    submitHandler: function(form) 
    {      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'identity/update_insuff_details'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_submit_insuff').attr('disabled','disabled');
        },
        complete:function(){
          $('#btn_submit_insuff').removeAttr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          $('#insuffRaiseModel').modal('hide');
          $('#frm_insuff_raise')[0].reset();
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }else {
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });

  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });

  $('#activityModelClk').click(function(){
    var url = $(this).data("url");
    $('.append-activity_view').load(url,function(){});
    $('#activityModel').modal('show');
  });

  $(".frm_validation").addClass("ignoreThisClass" );

  $('#frm_insuff_raise').validate({ 
    rules: {
      update_id : {
        required : true
      },
      insff_reason : {
        required : true,
        greaterThan : 0
      },
      txt_insuff_raise : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      insff_reason : {
        required : "Select Reason",
        greaterThan : "Select Reason"
      },
      txt_insuff_raise : {
        required : "Select Insuff Date"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'identity/insuff_raised'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_insuff').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffRaiseModel').modal('hide');
            $('#frm_insuff_raise')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $('#frm_insuff_clear').validate({ 
    rules: {
      clear_update_id : {
        required : true
      },
      insuff_clear_date : {
        required : true
      },
      insuff_remarks : {
        required : true
      }
    },
    messages: {
      clear_update_id : {
        required : "Update ID missing"
      },
      insuff_clear_date : {
        required : "Select Insuff CLear Date"
      },
      insuff_remarks : {
        required : "Enter remarks"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'identity/insuff_clear'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_insuff_clear').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_insuff_clear').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffClearModel').modal('hide');
            $('#frm_insuff_clear')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $('#view_insuff_log_tabs').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'pcc/address_logs/'.$selected_data['id']; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_address_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          var techTable = '';
          techTable = "<thead>";
          techTable += "<tr>";
          techTable += "<th>Modefied On</th>";
          techTable += "<th>Modefied By</th>";
          techTable += "<th>EMP ID</th>";
          techTable += "<th>Company Address</th>";
          techTable += "</tr>";
          techTable += "</thead>";
          techTable += message;
          $('#tbl_address_log').html(techTable);
          var emp_log_tbl =  $('#tbl_address_log').DataTable( { "ordering": true,bSortable: true,bRetrieve: true,"iDisplayLength": 25, });
        }
        else
        {
          $('#tbl_address_log').html(message);
        }
      }
    }); 
  });

  $('#view_result_log_tabs').click(function(){
    $.ajax({
      type : 'POST',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_address_result').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          var techTable = '';
          techTable = "<thead>";
          techTable += "<tr>";
          techTable += "<th>Status</th>";
          techTable += "<th>Closure Date</th>";
          techTable += "<th>Created By</th>";
          techTable += "<th>Created On</th>";
          techTable += "</tr>";
          techTable += "</thead>";
          techTable += message;
          $('#tbl_address_result').html(techTable);
          var emp_log_tbl =  $('#tbl_address_result').DataTable( { "ordering": true,searching: false,bFilter: false,bLengthChange : false,bSortable: true,bRetrieve: true,"iDisplayLength": 25, });
        }
        else {
          $('#tbl_address_result').html(message);
        }
      }
    }); 
  });

  $('#view_activity_log_tabs').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'activity_log/activity_log_databale/'.$selected_data['id'].'/13'; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_activity_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_activity_log').html(message);
        }
        else {
          $('#tbl_activity_log').html(e_message);
        }
        $('#tbl_activity_log').DataTable().destroy();
        var emp_log_tbl = $('#tbl_activity_log').DataTable( { "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });
      }
    }); 
  }).trigger('click');

  $('#add_verificarion_result').validate({
    rules: {
      identity_result_id : {
        required : true
      },
      mode_of_verification : {
        required : true
      },
      closuredate : {
        required : true
      },
      remarks : {
        required : true
      }
    },
    messages: {
      identity_result_id : {
        required : "ID"
      },
      mode_of_verification : {
        required : "Select Mode of Verification"
      },
      closuredate : {
        required : "Select Closure Date"
      },
      remarks : {
        required : "Enter Remark"
      }
    },
    submitHandler: function(form) 
    { 
      var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=13'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
        data : activityData,
        type: 'post',
        async:false,
        cache: false,
        processData:false,
        dataType:'json',
        success: function(jdata){
          $('#activityModel').modal('hide');
        }
      });

      var activityData = new FormData(form);
      activityData.append('action_val',$("#action option:selected").text());
      
      $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'identity/add_verificarion_result'; ?>',
        data:  activityData,
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbresult').attr('disabled','disabled');
        },
        complete:function(){
          $('#sbresult').removeAttr('disabled');                
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.redirect){
            show_alert(message,'success');
            location.reload();
            //window.location = jdata.redirect;
            return;
          }else{
            show_alert(message,'error');
          }
        }
      });  
    }
  });
});
</script>