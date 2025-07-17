<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Company Database</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>company_database">Company Database</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                       <li><button class="btn btn-secondary waves-effect btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>company_database/add"><i class="fa fa-plus"></i> Company</button></li>
                       <li><button class="btn btn-secondary waves-effect btn_clicked" data-toggle="modal" data-target="#myModal"><i class="fa fa-download"></i> Import</button></li> 

                   </ol>

                  </div>
            </div>
          </div>
    </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <!-- /.box-header -->

            <div class="row">
              <div class="col-sm-2 form-group">
              <?php
                 $status = array('all'=> 'All','0'=>'Inactive','1'=>'Active');

               echo form_dropdown('filter_by_status', $status, set_value('filter_by_status'), 'class="select2" id="filter_by_status"');?>
              </div>
               <div class="col-sm-2 form-group">
                 <input type="button" name="searchrecords" id="searchrecords" class="btn btn-sm btn-info" value="Filter">
             </div>
            </div>
            
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Company</th>
                  <th>Requirements</th>
                  <th>Status</th>
                  <th>Email ID</th>
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
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
  
var $ = jQuery;
$(document).ready(function() {

  var oTable =  $('#tbl_datatable').DataTable( {
        "serverSide": true,
        "processing": true,
         bSortable: true,
         bRetrieve: true,
         "scrollX": true,
        "iDisplayLength": 25, // per page
        "language": {
          "emptyTable": "No Record Found",
           "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'company_database/company_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_status':function(){return $("#filter_by_status").val(); } }
        } ),
        "columns":[{"data":"id"},{"data":"coname"},{"data":"requirement"},{"data":"dropdown_status"},{"data":"co_email_id"}]
  } );

  $('#filter_by_status').on('change', function(){

    var filter_by_status = $('#filter_by_status').val();
   
    var new_url = "<?php echo ADMIN_SITE_URL.'company_database/company_view_datatable'; ?>?filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#searchrecords').on('click', function() {
    var filter_by_status = $('#filter_by_status').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'company_database/company_view_datatable'; ?>?filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
   
  });


  $('#tbl_datatable tbody').on('click', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });
  
  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
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
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}

</style>