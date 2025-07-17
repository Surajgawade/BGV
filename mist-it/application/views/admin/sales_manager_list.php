<div class="content-wrapper">
    <section class="content-header">
      <h1>Roles</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_add_de']) ? ADMIN_SITE_URL.'sales_manager/add' : ''?>"><i class="fa fa-plus"></i> Add New</button></li>
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_export']) ? ADMIN_SITE_URL.'sales_manager/export_roles' : ''?>"><i class="fa fa-upload"></i> Export</button></li> 
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
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email ID</th>
                  <th>Mobile No.</th>
                  <th>Created On</th>
                  <th>Created By</th>
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
user_list();

function user_list()
{
  $.ajax({
    url : '<?php echo ADMIN_SITE_URL.'sales_manager/fetch_roles'; ?>',
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
        table_view += '<td>'+value['first_name']+'</td>'
        table_view += '<td>'+value['last_name']+'</td>'
        table_view += '<td>'+value['email_id']+'</td>'
        table_view += '<td>'+value['mobile_no']+'</td>'
        table_view += '<td>'+value['created_on']+'</td>'
        table_view += '<td>'+value['created_by']+'</td>'
        table_view += '</tr>'
        counter++;
      });
      $('#tbl_rows').html(table_view);
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
</script>