<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Clients - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>clients">Clients</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_clients_list_add']) ? ADMIN_SITE_URL.'clients/add' : ''; ?>"><i class="fa fa-plus"></i> Add Clients</button></li>
                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_clients_list_export']) ? ADMIN_SITE_URL.'clients/export_logs' : ''; ?>"><i class="fa fa-upload"></i> Export</button></li> 
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
                  <div class="col-sm-2 form-group">
                  <?php
                  $Status_value = array(''=> 'Select Status','1'=> 'Active','0'=>'Inactive');

                     echo form_dropdown('filter_by_status', $Status_value, set_value('filter_by_status'), 'class="select2" id="filter_by_status"');?>
                  </div>
                  <div class="col-sm-2  form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                  </div>
              </div>
       
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                   <th>#</th>
                    <th>Created On</th>
                    <th>Created By</th>
                    <th>Client Name</th> 
                    <th>Client Mgr</th>
                    <th>Sales Mgr</th>
                    <th>Status</th>
                    <th>Aggr End Date</th>
                    <th>Action</th>
                 </tr>
                </thead>
                <tbody id="tbl_rows"></tbody>
              </table>
            </div>
          </div>
          </div>
        </div>
      </div>
  </div>
</div>

<!--<script>
user_list();

function user_list()
{
  $.ajax({
    url : '<?php echo ADMIN_SITE_URL.'clients/fetch_clients'; ?>',
    type: 'post',
    contentType:false,
    cache: false,
    processData:false,
    dataType:'json',
    beforeSend:function(){
      jQuery('.body_loading').show();
    },
    complete:function(){
      jQuery('.body_loading').hide();
    },
    success: function(jdata){
    if(jdata.status == '<?= SUCCESS_CODE?>')
    {
      $('#tbl_datatable').dataTable().fnDestroy();
      var list =  jdata.draw;
      var table_view = '';
      $.each( list, function( index, value ){
        table_view += '<tr class="tbl_row_clicked" data-accessUrl='+value['edit']+'>'
        table_view += '<td>'+value['counter']+'</td>'
        table_view += '<td>'+value['clientname']+'</td>'
        table_view += '<td>'+value['created_on']+'</td>'
        table_view += '<td>'+value['created_on']+'</td>'
        table_view += '<td>'+value['status']+'</td>'
        table_view += '</tr>'
      });
      $('#tbl_rows').html(table_view);
    }
    else
    {
     show_alert(jdata.message,'error'); 
    }
      
    $('#tbl_datatable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": 0 }],
        "order": [[ 0, "asc" ]],
         "pageLength": 25,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    }
  });
}
</script>-->
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
var $ = jQuery;
var select_one = '';
$(document).ready(function() {


  var oTable =  $('#tbl_datatable').DataTable( {
        "serverSide": true,
        "processing": true,
        "ordering": true,
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
            url: "<?php echo ADMIN_SITE_URL.'clients/fetch_clients'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_status':function(){return $("#filter_by_status").val(); } }
        } ),
        order: [[ 0, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0,
              
           }
        ],
        'select': {
           'style': 'multi'
        },
        "columns":[{'data' :'id'},{"data":"created_on"},{"data":"created_by"},{'data' :'clientname'},{"data":"clientmgr"},{"data":"sales_manager"},{"data":"status"},{"data":"agreement_end_date"},{'data' :'delete'}]
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  
 $('#filter_by_status').on('change', function(){

    var filter_by_status = $('#filter_by_status').val();
   
    var new_url = "<?php echo ADMIN_SITE_URL.'clients/fetch_clients'; ?>?filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#searchrecords').on('click', function() {
    var filter_by_status = $('#filter_by_status').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'clients/fetch_clients'; ?>?filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
   
  });


   $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();

   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
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

