<div class="content-wrapper">
    <section class="content-header">
      <h1>Location Master</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_address_location_database_add']) ? ADMIN_SITE_URL.'locations/add' : '' ?>"><i class="fa fa-plus"></i> Add</button></li>
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_address_location_database_export']) ? ADMIN_SITE_URL.'locations/export' : '' ?>"><i class="fa fa-upload"></i> Export</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <div class="box-body">
              <table id="tbl_datatable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Location</th>
                    <th>Pincode</th>
                    <th>State</th>
                    <th>Created By</th>
                    <th>Created On</th>
                  </tr>
                </thead>
                <tbody id="tbl_rows"></tbody>
              </table>
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
    url : '<?php echo ADMIN_SITE_URL.'locations/fetch_location_list'; ?>',
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
        table_view += '<td>'+value['location']+'</td>'
        table_view += '<td>'+value['pin_code']+'</td>'
        table_view += '<td>'+value['state_name']+'</td>'
        table_view += '<td>'+value['created_by']+'</td>'
        table_view += '<td>'+value['created_on']+'</td>'
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