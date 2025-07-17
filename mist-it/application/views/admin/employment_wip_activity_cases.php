<div class="content-page">
<div class="content">
<div class="container-fluid">

 
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Employment - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                     <li class="breadcrumb-item active">WIP New Case</li>
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
                      <th>Comp INT</th>
                      <th>Client Name</th>
                      <th>Candidate's Name</th>
                      <th>Company Name</th>
                      <th>Status</th>
                      <th>Executive</th>
                      <th>Field status</th>
                      <th>TAT status</th>
                      <th>Due Date</th>
                      <th>Last Activity</th>
                      <th>Mode Of Verification</th>
                      <th><?php echo REFNO; ?></th>
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
<script src="<?php echo  SITE_JS_URL; ?>datatable_ajax.js"></script>
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
            url: "<?php echo ADMIN_SITE_URL.'follow_up_employment/employment_view_datatable_wip_activity'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: {  'filter_by_executive':function(){return $("#filter_by_executive_assign").val(); } }
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"emp_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'coname'},{'data' :'verfstatus'},{"data":"executive_name"},{"data":"field_status"},{'data':'tat_status'},{'data':'due_date'},{'data':"created_on"},{'data':"mode_of_veri"},{'data':"cmp_ref_no"}],
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
      window.location = data['encry_id'];
  });
  
  $('#filter_by_executive_assign').on('change', function(){
    var filter_by_executive_assign = $('#filter_by_executive_assign').val();
    
    var new_url = "<?php echo ADMIN_SITE_URL.'follow_up_employment/employment_view_datatable_wip_activity'; ?>?filter_by_executive="+filter_by_executive_assign;
    oTable.ajax.url(new_url).load();
  });

});
</script>
