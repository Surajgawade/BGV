<div class="content-wrapper">
    <section class="content-header">
      <h1>Company Genuineness</h1>
      <ol class="breadcrumb">
        <?php $access_import = ($this->permission['access_import']) ? '#myModelImport'  : ''; ?>
        <li><button class="btn btn-sm btn-default btn_clicked" data-accessUrl="<?= ($this->permission['access_add_de']) ? ADMIN_SITE_URL.'cg/add' : '' ?>"><i class="fa fa-plus"></i> Add</button></li>
        <li><button class="btn btn-sm btn-default" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import</button></li>
      </ol>
    </section>
     <section class="content">
      <div class="row">
        <div class="col-xs-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <div class="row">
                <?php echo $filter_view; ?>
              </div>
            <div class="filterable">
            <div class="pull-right">
              <button class="btn btn-sm btn-info btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
            </div>
              <div class="box-body">
                <table id="tbl_datatable_kyc" class="table table-bordered table-hover">
                  <thead>
                    <tr class="filters">
                      <th><input type="checkbox" name="allCheckboxSelect" id="allCheckboxSelect" class="form-control"></th>
                      <th><input type="text" class="form-control" placeholder="Sr No" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Client Ref No" disabled></th>
                      <th><input type="text" class="form-control" placeholder="CG Ref No" disabled></th>
                      <th><input type="text" class="form-control" placeholder="CG Int Date" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Client Name" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Cmp/Cust Name" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Status" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Vendor" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Executive" disabled></th>
                      <th><input type="text" class="form-control" placeholder="TAT status" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Due Date" disabled></th>
                      <th><input type="text" class="form-control" placeholder="Last Activity" disabled></th>
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
    </section>
</div>
<div id="myModalCasesAssign" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Cases</h4>
      </div>
      <div class="modal-body">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group users_list">
          <?php echo form_dropdown('users_list', $users_list, set_value('users_list'), 'class="form-control" id="users_list" required="required" ');?>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group vendor_list">
          <?php echo form_dropdown('vendor_list', $vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list" required="required" ');?>
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
        <h4 class="modal-title">Import Address Records</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import','id'=>'frm_download_tem_and_import')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Client <span class="error"> *</span></label>
            <?php
              echo form_dropdown('clientid',$clients, set_value('clientid'), 'class="form-control" id="clientid"');
              echo form_error('clientid');
            ?>
          </div>
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
<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import CG Records</h4>
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

<script>  
var $ = jQuery;
$(document).ready(function() {
  $.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,// number of pages to cache
        url: '<?php echo ADMIN_SITE_URL.'cg/cg_view_datatable'; ?>',
        data: null,
        method: 'POST' // Ajax HTTP method
    }, opts );
 
   // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
  
    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {                
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
  };
  var oTable =  $('#tbl_datatable_kyc').DataTable( {
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
          "processing": jQuery('.body_loading').show()
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'cg/cg_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 3, 'desc' ]],
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
        "columns":[{'data':'checkbox'},{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"cg_ref_no"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'company_customer_name'},{'data' :'verfstatus'},{"data":"vendor"},{"data":"executive_name"},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"}]
  } );

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#tbl_datatable_kyc tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });
  
  $('#filter_by_clientid,#filter_by_status').on('change', function(){
    var filter_by_clientid = $('#filter_by_clientid').val();
    var filter_by_status = $('#filter_by_status').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'cg/cg_view_datatable'; ?>?filter_by_clientid="+filter_by_clientid+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
      (cases_assgin_action == 1) ? $('.vendor_list').hide() : $('.users_list').hide();
      $('#myModalCasesAssign').modal('show');

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
    var vendor_list = $('#vendor_list').val();
    if(users_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'cg/assign_to_executive/' ?>',
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
              show_alert(message,'success');
              $('#myModalCasesAssign').modal('hide');
            }else {
              show_alert(message,'error'); 
            }
          }
        });
    }

    if(vendor_list != "0" && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'cg/assign_to_vendor/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one,
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
              show_alert(message,'success');
              $('#myModalCasesAssign').modal('hide');
            }else {
              show_alert(message,'error'); 
            }
          }
        });
    }
  });

  $('#export_to_excel_frm').validate({ 
        rules: {
          reasonforleaving : {
            required : true
          }
        },
        messages: {
          reasonforleaving : {
            required : "Enter Reason for Leaving"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#clientid').val();
            var fil_by_status = $('#fil_by_status').val();
            var client_name = $('#clientid option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&fil_by_status='+ fil_by_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'cg/export_to_excel'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
              $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModal .close").click()
              $('#export_to_excel_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                window.location.href = jdata.file;
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          });   
        }
  });

  $('#export_activity_frm').validate({ 
        rules: {
          activity_from : {
            required : true
          },
          activity_to : {
            required : true
          }
        },
        messages: {
          activity_from : {
            required : "Select From Date"
          },
          activity_to : {
            required : "Select To Date"
          }
        },
        submitHandler: function(form) 
        {      
            var $activity_from = $("#activity_from").datepicker('getDate');
            var $activity_to = $("#activity_to").datepicker('getDate');
            var dataString = 'activity_from=' + $activity_from + '&activity_to='+ $activity_to;
            if($activity_from > $activity_to)
            {
                alert("To date shouldn't greater than from date");
                return;
            }
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'cg/export_activity_records'; ?>',
            data: $('#export_activity_frm').serialize(),
            type: 'POST',
            beforeSend:function(){
              $('#export_activity_tbn').text('exporting..');
              $('#export_activity_tbn').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_activity_tbn').text('Export');
              $('#export_activity_tbn').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#activityModel .close").click()
              $('#export_activity_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                window.location.href = jdata.file;
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          });   
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
          url : '<?php echo ADMIN_SITE_URL.'cg/bulk_upload_candidates'; ?>',
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
            $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              window.location = jdata.redirect;
              return;
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });

});

$('#download_candidate_template').click(function(e) {
    e.preventDefault();
    window.location.href = "<?= COM_ATTACHMENTS_PATH.CG.'/cg_bulk_template.xlsx'; ?>" ;
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
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}

</style>