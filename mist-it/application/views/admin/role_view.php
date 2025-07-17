<div class="content-page">
  <div class="content">
    <div class="container-fluid">

    

       <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Admin - Roles - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>roles">Roles</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_role_add']) ? ADMIN_SITE_URL.'roles/add' : ''?>"><i class="fa fa-plus"></i> Add Role</button></li>
                      <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_role_export']) ? ADMIN_SITE_URL.'roles/export_roles' : ''?>"><i class="fa fa-upload"></i> Export</button></li> 

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
                <tr>
                  <th>#</th>
                  <th>Created On</th>
                  <th>Created By</th>
                  <th>Role Name</th>
                  <th>Description</th> 
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
          "processing": jQuery('.body_loading').show()
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo ADMIN_SITE_URL.'roles/fetch_roles'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_status':function(){return $("#filter_by_status").val(); } }
        } ),
        order: [[ 0, 'asc' ]],
        'columnDefs': [
           {
              'targets': 0,
              
           }
        ],
        'select': {
           'style': 'multi'
        },
        "columns":[{'data' :'id'},{"data":"created_on"},{"data":"user_name"},{'data' :'role_name'},{"data":"role_description"}]
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
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

