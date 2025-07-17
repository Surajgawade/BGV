<div class="content-page">
<div class="content">
<div class="container-fluid">

 
<div class="tab-content">
  <div id="reference_list_view" class="tab-pane active">  
     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Reference - WIP Cases</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>reference_verificatiion">Reference </a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  
            </div>
          </div>
    </div>
  
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">

              <div class="row">
                 <div class="col-sm-3 form-group">
                  <?php
                   echo form_dropdown('filter_by_executive_assign',$user_list_name, set_value('filter_by_executive_assign',$this->user_info['id']), 'class="select2" id="filter_by_executive_assign"');?>
                </div>
              </div>
              
                <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr>
                     <th><input type="checkbox" name="allCheckboxSelect" id="allCheckboxSelect" class="form-control"></th>
                      <th>Sr No</th>
                      <th>Client Ref No</th>
                      <th><?php echo CMPREFNO; ?></th>
                      <th>Comp Int</th>
                      <th>Client Name</th>
                      <th>Candidate's Name</th>
                      <th>Vendor</th>
                      <th>Reference Name</th>
                      <th>Status</th>
                      <th>Executive</th>
                      <th>TAT status</th>
                      <th>Due Date</th>
                      <th>Last Activity</th>
                      <th>Mode of Verification</th>
                      <th>Candidate Ref No</th>
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
            url: "<?php echo ADMIN_SITE_URL.'follow_up_reference/reference_view_datatable_wip_activity'; ?>",
            pages: 1,
            async: true,
            method: 'POST',
            data: { 'filter_by_executive':function(){return $("#filter_by_executive_assign").val(); } }
        } ),
        order: [[ 1, 'desc' ]],
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"reference_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'vendor_name'},{'data' :'name_of_reference'},{"data":"verfstatus"},{"data":"executive_name"},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"},{'data':"mode_of_veri"},{'data':"cmp_ref_no"}],
       
  } );

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
     $('#filter_by_status').val('All');
     $('#filter_by_executive').val('All');
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });

  
  $('#filter_by_executive_assign').on('change', function(){
    var filter_by_executive_assign = $('#filter_by_executive_assign').val();
    
    var new_url = "<?php echo ADMIN_SITE_URL.'follow_up_reference/reference_view_datatable_wip_activity'; ?>?filter_by_executive="+filter_by_executive_assign;
    oTable.ajax.url(new_url).load();
  });
  
 });
</script>

