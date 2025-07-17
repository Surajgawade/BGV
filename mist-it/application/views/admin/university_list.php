<div class="content-page">
<div class="content">
<div class="container-fluid">


        <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Education - Universities</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>education">Education</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>universities">Universities</a></li>

                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php 
                      $access_update_status = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateStatus'  : '';
                      $access_export = ($this->permission['access_address_list_export']) ? '#myModalExport'  : '';
                      $access_import = ($this->permission['access_address_list_import']) ? '#myModelImport'  : ''; 

                      ?>
                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_education_universities_add']) ? ADMIN_SITE_URL.'universities/add' : '' ?>"><i class="fa fa-plus"></i> Add</button></li>
                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_education_universities_export']) ? ADMIN_SITE_URL.'universities/export' : '' ?>"><i class="fa fa-upload"></i> Export</button></li> 

                   </ol>

                  </div>
            </div>
          </div>
    </div>

  
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr >
                    <th>#</th>
                    <th>Univercity Name</th>
                    <th>Vendor Name</th>
                    <th>Year of Passing</th>
                    <th>URL Link</th>
                    <th>Created On</th>
                    <th>Status</th>
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
<script>
group_list();

function group_list()
{
  $.ajax({
    url : '<?php echo ADMIN_SITE_URL.'universities/fetch_univer_list'; ?>',
    type: 'post',
    contentType:false,
    cache: false,
    processData:false,
    dataType:'json',
    beforeSend:function(){
      $('.loading_wrp').show();
    },
    complete:function(){
      $('.loading_wrp').hide();
    },
    success: function(jdata){
    if(jdata.status == '<?= SUCCESS_CODE?>')
    {
      $('#tbl_datatable').dataTable().fnDestroy();
      var list =  jdata.draw;
      var table_view = '';
      var counter = 1;
      $.each( list, function( index, value ){
        table_view += '<tr class="tbl_row_clicked" data-accessUrl='+value['edit']+'>'
        table_view += '<td>'+counter+'</td>'
        table_view += '<td>'+value['universityname']+'</td>'
        table_view += '<td>'+value['vendor_id']+'</td>'
       
        table_view += '<td>'+value['year_of_passing']+'</td>'
        table_view += '<td>'+value['url_link']+'</td>'
        table_view += '<td>'+value['created_on']+'</td>'
        table_view += '<td>'+value['status']+'</td>'
        table_view += '</tr>'
        counter++;
      });
      $('#tbl_rows').html(table_view);
    }
      
    var oTable = $('#tbl_datatable').DataTable({
        "columnDefs": [{ "orderable": false, "targets": 0 }],
        "order": [[ 0, "asc" ]],
          "scrollX": true,
         "pageLength": 25,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    }
  });
}
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