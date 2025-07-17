<div class="content-wrapper">
    <section class="content-header">
      <h1>Education - Fake Universities</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_education_fake_add']) ? ADMIN_SITE_URL.'universities/add_fake_university' : '' ?>"><i class="fa fa-plus"></i> Add</button></li>
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_education_fake_export']) ? ADMIN_SITE_URL.'universities/export_fake_university' : '' ?>"><i class="fa fa-upload"></i> Export</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <div class="filterable">
  
            <div class="box-body">
              <table id="tbl_datatable" class="table table-bordered table-hover">
                <thead>
                  <tr class="filters">
                    <th>#</th>
                    <th><input type="text" class="form-control" placeholder="Univercity Name" ></th>
                    <th><input type="text" class="form-control" placeholder="State" ></th>
                    <th><input type="text" class="form-control" placeholder="Created On" ></th>
                    <th><input type="text" class="form-control" placeholder="Status" ></th>
                  </tr>
                </thead>
                <tbody id="tbl_rows"></tbody>
              </table>
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>
</div>
<script>
group_list();

function group_list()
{
  $.ajax({
    url : '<?php echo ADMIN_SITE_URL.'universities/fetch_fake_univer_list'; ?>',
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
        table_view += '<td>'+value['u_name']+'</td>'
        table_view += '<td>'+value['state']+'</td>'
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