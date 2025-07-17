<div class="content-page">
 <div class="content">
  <div class="container-fluid">

    <br>
   
<div class="tab-content">
  
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Social Media - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>social_media">Social Media</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                       <?php
                          $access_export = ($this->permission['access_social_media_list_export']) ? '#myModalExport'  : '';
                          $access_import = ($this->permission['access_social_media_list_import']) ? '#myModelImport'  : '';
                          $access_update_case_received_date = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateCaseReceiveDate'  : ''; 
                       ?>
                        <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import</button></li>
                        <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_export;?>"><i class="fa fa-download"></i> Export</button> </li> 
                        <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_case_received_date;?>"><i class="fa fa-download"></i> Update Received Date</button> </li>
                    </ol>
                  </div>
            </div>
          </div>
    </div>
    
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
                <?php echo $filter_view; ?>
              </div>
            
                <table id="tbl_datatable_social_media" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr>
                      <th>Sr No</th>
                      <th>Client Ref No</th>
                      <th>Comp Ref No</th>
                      <th>Comp INT</th>
                      <th>Client Name</th>
                      <th>Candidate's Name</th>
                      <th>Status</th>
                      <th>Executive</th>
                      <th>TAT status</th>
                      <th>Due Date</th>
                      <th>Last Activity</th>
                      <th>Mode of Verification</th>
                      <th>Candidate Ref No</th>

                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
          </div>
        </div>
      </div>
   
  </div> 

</div>
</div>
</div>
<div id="myModalCasesAssign" class="modal fade" role="dialog" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 form-group users_list">
            <?php echo form_dropdown('users_list', $users_list, set_value('users_list'), 'class="form-control" id="users_list" required="required" ');?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn_assign_action">Assign</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Identity Records</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import','id'=>'frm_download_tem_and_import')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="cands_bulk_sheet" id="cands_bulk_sheet" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-info pull-left btn-md" id="download_candidate_template">Template</button>
        <button type="submit" class="btn btn-info btn-md" id="import_candidate_template">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="myModalUpdateCaseReceiveDate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Identity Case Received Date</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_update_case_received_date','id'=>'frm_download_tem_and_import_update_case_received_date')); ?>   
    
      <div class="clearfix"></div>
      <div class="modal-body">
        <div class="row">
          
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="case_received_date_change_bulk_upload" id="case_received_date_change_bulk_upload" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
         
        <button type="submit" class="btn btn-info btn-md" id="import_case_received_date_change_bulk_upload">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>  
var $ = jQuery;
$(document).ready(function() {

  $('#filter_by_executive').val(<?php echo $this->user_info['id'] ?>);
  $('#filter_by_status').val('WIP');

  var oTable =  $('#tbl_datatable_social_media').DataTable( {
        "serverSide": true,
        "processing": true,
         bSortable: true,
         bRetrieve: true,
         scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
          leftColumns: 1,
          rightColumns: 1
        },
        "iDisplayLength": 25, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'social_media/social_media_view_datatable'; ?>",
            pages: 1,
            async: true,
            method: 'POST',
            data: { 'filter_by_executive':function(){return $("#filter_by_executive").val(); },'clientid':function(){return $("#filter_by_clientid").val(); },'status':function(){return $("#filter_by_status").val(); },'sub_status':function(){return $("#filter_by_sub_status").val(); },'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); } }
        } ),
        order: [[ 1, 'desc' ]],
        
        "columns":[{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"social_media_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'verfstatus'},{"data":"executive_name"},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"},{'data':"mode_of_veri"},{'data':"cmp_ref_no"}],

        
        "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {
            switch(aData['verfstatus'])
            {
              case "Insufficiency Cleared" :
              
                $('td' , nRow).css('background-color', '#ffff00')
                 break;

              case "New Check" :
              
                 $('td' , nRow).css('background-color', '#ffff00')
                 break;

              case "Final QC Reject" :
              
                $('td' , nRow).css('background-color', '#ff0000')
                 break;
                 
              case "First QC Reject" :
              
                $('td' , nRow).css('background-color', '#ff0000')
                 break;

              case "Re-Initiated" :
              
                $('td' , nRow).css('background-color', '#ff0000')
                 break;  
                
              case "YTR" :
              
                $('td' , nRow).css('background-color', '#ffffff')
                 break;          

              default :
                 $('td' , nRow).css('background-color', '#ffffff') 
                
            }
        }
  } );

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
      $('#filter_by_status').val('All');
      $('#filter_by_executive').val('All');
    if(e.keyCode == 13) {
      var search_value = this.value;
      var search_value_details = $.trim(search_value);
      if( search_value_details.length > 2)
      {
         oTable.search( search_value_details ).draw();
      } 
    }
  });

  $('#tbl_datatable_social_media tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });
  

  $('#filter_by_executive,#filter_by_clientid,#filter_by_status,#filter_by_sub_status').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'social_media/social_media_view_datatable'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
  });


  $('#filter_by_executive,#filter_by_status,#filter_by_sub_status').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();

     $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'social_media/get_clients_for_social_media_list_view/' ?>',
          data : 'client_id='+client_id+'&status='+status+'&sub_status='+sub_status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        });
  });


    $('#searchrecords').on('click', function() {
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
    var client_id = $('#filter_by_clientid').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(start_date > end_date)
    {
      alert('Please select end date greater than start date');
    }
    else
    {
    var new_url = "<?php echo ADMIN_SITE_URL.'social_media/social_media_view_datatable'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&start_date="+start_date+"&end_date="+end_date+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
   }
  });



  $('#frm_download_tem_and_import').validate({ 
      rules: {
        cands_bulk_sheet : {
          required : true
        }
      },
      messages: {
        cands_bulk_sheet : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'identity/bulk_upload_identity'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_candidate_template').attr('disabled','disabled');
          },
          complete:function(){
           // $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModelImport').modal('hide');
              show_alert(message,'success',true);
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });
   
  $('#btn_reset').on('click', function() {

   $("#filter_by_executive option[value = All]").attr('selected','selected');
   $("#filter_by_status option[value = All]").attr('selected','selected');
    
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();
    
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'identity/get_clients_for_identity_list_view/' ?>',
          data : 'status='+status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        }); 

    var new_url = "<?php echo ADMIN_SITE_URL.'social_media/social_media_view_datatable'; ?>?status="+status+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
  });  
   
  $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        $('.users_list').show();
        $('.header_title').text('Assign Executive');
      }
      
      $('#myModalCasesAssign').modal('show');
      $('#myModalCasesAssign').addClass("show");
      $('#myModalCasesAssign').css({background: "#0000004d"});


      var form = this;
      var rows_selected = oTable.column(0).checkboxes.selected();
      $.each(rows_selected, function(index, rowId){
         $(form).append(
             $('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId)
         );
      });
      $('input[name="id\[\]"]', form).remove();
      e.preventDefault();
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
  });


  $('#btn_assign_action').on('click', function(e){
    var users_list = $('#users_list').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");

    if(users_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'identity/assign_to_executive/' ?>',
          data : 'users_list='+users_list+'&cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModalCasesAssign').modal('hide');
              show_alert(message,'success',true);
            }else {
              show_alert(message,'error'); 
            }
          }
        });
    }
});

  $('#frm_download_tem_and_import_update_case_received_date').validate({ 
      rules: {
         case_received_date_change_bulk_upload : {
          required : true
        }

      },
      messages: {
        case_received_date_change_bulk_upload : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'identity/bulk_update_case_received_date'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_case_received_date_change_bulk_upload').attr('disabled','disabled');
          },
          complete:function(){
           // $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });

$('#download_candidate_template').on('click',function(){
    

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'identity/template_download'; ?>',
          data : '',
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_template').text('downloading..');
              $('#download_candidate_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_template').text('Template');
             // $('#download_candidate_template').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelImport.close").click()
              $('#frm_download_tem_and_import')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                show_alert(message,'success'); 
              } else {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xlsx");
            $a[0].click();
            $a.remove();
        });
  });


     $('#export_to_excel_frm').validate({ 
        rules: {
          client_id : {
            required : true,
            greaterThan : 0
          },
          status_value : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          },
          status_value : {
            required : "Select Status",
            greaterThan : "Select Status"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id').val();
            var fil_by_status = $('#status_value').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var client_name = $('#client_id option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&fil_by_status='+ fil_by_status+'&from_date='+ from_date+'&to_date='+ to_date;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Identity/export_to_excel'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
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
            $('#export_to_excel_frm')[0].reset();
            $("#myModalExport").hide();
            location.reload();
        });     
        }
  });

});

$('#status_value').on('change', function(e){
     var status_value = $('#status_value').val();
     if(status_value == "Closed")
     {
       $('#display_from_to_date').show();
     }
     else
     {
       $('#display_from_to_date').hide();
     }
});

</script>

<script type="text/javascript">
  $(document).ready(function(){

    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();


     $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'social_media/get_clients_for_social_media_list_view/' ?>',
          data : 'client_id='+client_id+'&status='+status+'&sub_status='+sub_status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        });
  
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

   
});
</script>

