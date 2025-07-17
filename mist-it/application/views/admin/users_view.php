<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Admin - Users - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>users">Users</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php 
                      $access_update_status = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateStatus'  : '';
                      $access_export = ($this->permission['access_address_list_export']) ? '#myModalExport'  : '';
                      $access_import = ($this->permission['access_address_list_import']) ? '#myModelImport'  : ''; 

                      ?>
                      <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_list_add']) ? ADMIN_SITE_URL.'users/add' : ''?>"><i class="fa fa-plus"></i> Add User</button></li>
                      <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ($this->permission['access_admin_list_export']) ? ADMIN_SITE_URL.'users/export_users_data' : ''?>"><i class="fa fa-upload"></i> Export</button></li> 

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
                 $status = array(''=> 'Select status','0'=>'Inactive','1'=>'Active');

               echo form_dropdown('filter_by_status', $status, set_value('filter_by_status'), 'class="select2" id="filter_by_status"');?>
              </div>
               <div class="col-sm-2 form-group">
                 <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
             </div>
            
            </div>
         
          
               <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Created On</th>
                  <th>Created By</th>
                  <th>Role</th>
                  <th>User Name</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email ID</th>
                  <th>Account Status</th>
                  <th>Department</th>
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
            url: '<?php echo ADMIN_SITE_URL.'users/fetch_user'; ?>',
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
        "columns":[{'data' :'id'},{"data":"created_on"},{"data":"created_by"},{'data' :'role_name'},{"data":"user_name"},{"data":"firstname"},{"data":"lastname"},{"data":"email"},{"data":"status"},{"data":"department"}]
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  
 $('#filter_by_status').on('change', function(){

    var filter_by_status = $('#filter_by_status').val();
   
    var new_url = "<?php echo ADMIN_SITE_URL.'users/fetch_user'; ?>?filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#searchrecords').on('click', function() {
    var filter_by_status = $('#filter_by_status').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'users/fetch_vendor_list'; ?>?filter_by_status="+filter_by_status;
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

