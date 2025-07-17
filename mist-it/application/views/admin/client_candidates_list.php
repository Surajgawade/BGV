<div class="content-page">
<div class="content">
  <div class="container-fluid">
     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Client Candidates - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidate</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>Client_cases">Client Candidate</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>

                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                    <div id ="massage_credit" style="color : green;"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <form  name="export_client_candidate" id = "export_client_candidate">
                    <input type="hidden" name="coomponent_check_id" id="coomponent_check_id" class="form-control">
                    <button class="btn btn-secondary waves-effect" id="export_client_cands" ><i class="fa fa-download"></i>  Export </button>
                    </form>
                   </ol>
                  </div>
            </div>
          </div>
      </div>

   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
                <div class ="row">
                    <div class="col-sm-3 form-group">
                      <?php echo form_dropdown('filter_by_clientid', $clients_id, set_value('filter_by_clientid'), 'class="select2" id="filter_by_clientid"');?>
                    </div>

                    <div class="col-sm-3 form-group">
                      <select id="filter_by_entity" name="filter_by_entity" class="select2"><option value="0">Select Entity</option></select>
                    </div>

                    <div class="col-sm-3 form-group">
                        <select id="filter_by_package" name="filter_by_package" class="select2"><option value="0">Select Package</option></select>
                    </div> 

                    <div class="col-sm-2 form-group">
                    <?php
                      unset($status['NA']);
                      echo form_dropdown('filter_by_status_candidates', $status, set_value('filter_by_status_candidates','Insufficiency'), 'class="select2" id="filter_by_status_candidates"');?>
                    </div>
                    <div class="col-sm-2 form-group">
                      <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status_candidates', $sub_status, set_value('filter_by_sub_status_candidates'), 'class="select2" id="filter_by_sub_status_candidates"');?>
                    </div> 
                </div>
               
                <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                  <tr>
                        <th>#</th>
                        <th>Case Initiated</th>
                        <th>Candidate Name</th>
                        <th>Overall Status</th>
                        <th>Activity</th>
                        <th>Last Activity</th>
                        <th>Copy Link</th>
                        <th>Mail</th>
                        <th>Date</th>
                        <th>SMS</th>
                        <th>Date</th>
                        <th>Candidate Visit</th>
                        <th>Date</th>
                        <th>WIP</th>
                        <th>Insufficiency</th>
                        <th>Closed </th>
                        <th>Client Name</th>
                        <th>Entity</th>
                        <th>Package</th>
                        <th>Client Ref No</th>
                        <th><?php echo REFNO; ?></th>
                  </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<div id="showactivityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_activity_details','id'=>'frm_activity_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Result</h4>
      </div>
      <div class="modal-body">
        <div class="row">
           <input type="hidden" name="candidate_id" id="candidate_id" value=""> 
           
          <div class="col-sm-6 form-group">
             <label>Activity Status <span class="error"> *</span></label>
            <?php
              $activity = array('WIP'=>'WIP','Not Joining'=>'Not Joining','No Response'=>'No Response','Will share shortly'=>'Will share shortly','Callback'=>'Callback');
              echo form_dropdown('activity_status', $activity, set_value('activity_status'), 'class="select2" id="activity_status"');?>
          </div>
          <div class="col-sm-6 form-group">
            <label>Remarks <span class="error"> *</span></label>
              <textarea name="activity_remark" rows="1" id="activity_remark" class="form-control"><?php echo set_value('activity_remark'); ?></textarea>
              
          </div> 
          </div>
          <div class="clearfix"></div>
          <br>
          <div class="tab-pane" id="tab_candidate_activity_result">
            <table id="tab_candidate_activity_result" class="table table-bordered  nowrap"></table>
          </div>  
     
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_activity_details" name="btn_submit_activity_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm','id'=>'export_to_excel_frm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Candidates Records</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <label>Select Client</label>
              <?php 
                $clients_id["All"] = "All";
                echo form_dropdown('client_id', $clients_id, set_value('client_id'), 'class="form-control" id="client_id"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id', array(), set_value('entity_id'), 'class="form-control" id="entity_id"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
               <label>Select Package</label>
             <select id="package_id" name="package_id" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Status</label>
             <?php
                echo form_dropdown('status_value', $status_value, set_value('status_value'), 'class="form-control" id="status_value"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                 <label>Select Sub Status</label>
               <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_status', $sub_status, set_value('sub_status'), 'class="form-control" id="sub_status"');?>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export" name="export" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
var $ = jQuery;

    var status = $('#filter_by_status_candidates').val();
    if(status = "WIP")
    {
      $('#filter_by_status_candidates').css('background-color','Yellow');
    }

$(document).ready(function() {

    $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'client_cases/get_credit_list'; ?>',
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#massage_credit').html('SMS CREDIT : '+0);
        },
        complete:function(){
                   
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == 200) {
           
             $('#massage_credit').html('SMS CREDIT : '+message);
          }
          if(jdata.status == 400) {
             $('#massage_credit').html('SMS CREDIT : '+ message);
          }
        }
    })


  var oTable = $('#tbl_datatable').DataTable( {
        "processing": true,
        "serverSide": true,
         bSortable: true,
         bRetrieve: true,
         scrollX: true,
         scrollCollapse: true,
         fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 25,
       // "aaSorting": [[ 4, "desc" ]],
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'client_cases/client_cands_view_datatable'; ?>",
            pages: 5,
            async: true,
            method: 'POST',
            data: { 'client_id':function(){return $("#filter_by_clientid").val(); },'entity':function(){return $("#filter_by_entity").val(); },'package':function(){return $("#filter_by_package").val(); },'status':function(){return $("#filter_by_status_candidates").val(); },'sub_status':function(){return $("#filter_by_sub_status_candidates").val(); } }
            //async: true 

        } ),
        order: [[ 1, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0,
              'checkboxes': {
                 'selectRow': true
              }
           }
        ],
        'select': {
           'style': 'multi'
        },
     
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{"data":"activity_buttom"},{"data":"last_activity"},{"data":"copy_link"},{"data":"mail_sent"},{"data":"last_email_on"},{"data":"sms_sent"},{"data":"last_sms_on"},{"data":"candidate_visit"},{"data":"candidate_visit_on"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'},{"data":"clientname"},{"data":"Entity"},{"data":"Package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}],

  });

$('#filter_by_clientid,#filter_by_entity,#filter_by_package,#filter_by_status_candidates,#filter_by_sub_status_candidates').on('change', function(){

    var client_id = $('#filter_by_clientid').val();
    var entity = $('#filter_by_entity').val();
    var package = $('#filter_by_package').val();
    var status = $('#filter_by_status_candidates').val();
    var sub_status = $('#filter_by_sub_status_candidates').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'client_cases/client_cands_view_datatable'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
   var data = oTable.row( this ).data(); 

     window.open(data['encry_id'], '_blank');
  });

  $('#tbl_datatable tbody').on('change', 'input[type="checkbox"]', function(){
 
      select_one = oTable.column(0).checkboxes.selected().join(",");
      $('#coomponent_check_id').val(select_one);
  });
});

 
   $('#tbl_datatable tbody').on('click', '.trigger_email_again', function (){
        if(confirm('You need send message email again') === true) {
            
            var id = $(this).attr('id'); 
     
            if(id != "") {
                $.ajax({
                    url: "<?php echo ADMIN_SITE_URL.'client_cases/trigger_email_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                            show_alert(message,'success',true);
                            location.reload();
                            return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });


     $('#tbl_datatable tbody').on('click', '.trigger_sms_again', function (){
        if(confirm('You need send message sms again') === true) {
            
            var id = $(this).attr('id'); 

            if(id != "") {
                $.ajax({
                    url: "<?php echo ADMIN_SITE_URL.'client_cases/trigger_sms_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                          show_alert(message,'success',true);
                          location.reload();
                          return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });

    $(document).on('click', '.copyLink', function() {
    let urlcopy = $(this).data('link');

    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(urlcopy).select();
    document.execCommand("copy");
    $temp.remove();

    $(this).text('link copied').removeClass('btn-info').addClass('btn-primary');
    setTimeout(function(){$('.copyLink').text('Copy Link').removeClass('btn-primary').addClass('btn-info');}, 2000);
   });

    $(document).on('change', '#filter_by_clientid', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#filter_by_entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#filter_by_entity').html(html);
          }
      });
  }
});

 $(document).on('change', '#filter_by_entity', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#filter_by_package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#filter_by_package').html(html);
            }
        });
    }
  }); 

  $(document).on('change', '#filter_by_status_candidates', function(){
    var status = $("#filter_by_status_candidates option:selected").html();
    if(status == 'Closed')
    {
      $('#date_field_show_hide').show();
    }
    else
    {
      $('#date_field_show_hide').hide();
    }
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#filter_by_sub_status_candidates').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#filter_by_sub_status_candidates').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#filter_by_sub_status_candidates').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#filter_by_sub_status_candidates').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});  

$(document).on('click', '.showactivityModel', function(){
    var candidate_id = $(this).data("id");
    var url = $(this).data("url");
    $('#tab_candidate_activity_result').load(url,function(){});
  
    $("#candidate_id").val(candidate_id);
    $('#showactivityModel').modal('show');
    $('#showactivityModel').addClass("show");
    $('#showactivityModel').css({background: "#0000004d"});

 

  });

  $('#frm_activity_details').validate({ 
    rules: {
      candidate_id : {
        required : true
      },
      activity_status : {
        required : true
      },
      activity_remark : {
        required : true
      }
    },
    messages: {
      candidate_id : {
        required : "Update ID missing"
      },
      activity_status : {
        required : "Please Select Activity Status"
      },
      cases_assgin : {
        required : "Please Enter Remark"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'client_cases/save_candidate_activity_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_activity_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_activity_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showactivityModel').modal('hide');
            $('#frm_activity_details')[0].reset();
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


  $('#export_client_candidate').validate({ 
        rules: {
          
        },
        messages: {
         
        },
        submitHandler: function(form) 
        {      
            var component_id = $('#coomponent_check_id').val();
          
            var dataString = 'coomponent_check_id=' + component_id;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'client_cases/export_client_candidate_details'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export_client_cands').text('exporting..');
              $('#export_client_cands').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_client_cands').text('Export');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xls");
            $a[0].click();
            $a.remove();
            $('#export_client_candidate')[0].reset();
            location.reload();
        });     
        }
  });



 </script>

  