<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendor - Users - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>users">Users</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?=  VENDOR_SITE_URL.'users/add/' ?>"><i class="fa fa-plus"></i> Add User</button></li>
                   </ol>

                  </div>
            </div>
          </div>
    </div>

    <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
             
               <table id="tbl_datatable1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th><input name="select_all" value="1" id="chk_datatable" class="chk_datatable" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email ID</th>
                    <th>mobile No</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>


<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script type="text/javascript">
    

      $('#chk_datatable').on('click', function() {
        if (this.checked == true)
            $('#tbl_datatable1').find('input[name="user_id"]').prop('checked', true);
        else
            $('#tbl_datatable1').find('input[name="user_id"]').prop('checked', false);
    });

</script>
<script>
var $ = jQuery;
var select_one = [];
$(document).ready(function() {

  var oTable =  $('#tbl_datatable1').DataTable( {

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
               url: '<?php echo VENDOR_SITE_URL.'users/fetch_user'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 3, 'desc' ]],
        'columnDefs': [
           {
              "orderable": false,
              'targets': 0,
              'checkboxes': {
                 'selectRow': true
              }
           }
        ],
        'select': {
           'style': 'multi',
        },
        "columns":[{'data' :'checkbox'},{'data' :'id'},{"data":"first_name"},{"data":"last_name"},{"data":"email_id"},{'data' :'mobile_no'},{'data' :'status'}]
  });

   $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });

   
});


</script>


