<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h5 class="page-title">Report - List View</h5>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>download_report">Report</a></li>
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
                <div class="col-sm-4 form-group">
                   
                  <input type="text" name="from_date" id="from_date" value="<?php echo set_value('from_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                </div> 
                <div class="col-sm-4 form-group"> 
                 
                  <input type="text" name="todate" id="todate" value="<?php echo set_value('todate'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                </div>

                <div class="col-sm-4 form-group">

                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                   <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>

             </div>
             <button type="button" class="btn btn-danger" id= "download_report"> Download </button>
              <div class=clearfix></div>
              <br>

                      <table id="tbl_datatable1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                           
                           <th>#</th>
                            <th>Case Initiated</th>
                            <th>Candidate Name</th>
                            <th>Overall Status</th>
                            <th>WIP</th>
                            <th>Insufficiency</th>
                            <th>Closed </th>
                            <th>Closure Date</th>
                            <th>TAT</th>
                            <th>Remarks</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>Package</th>
                            <th>Client Ref No</th>
                            <th><?php echo REFNO; ?></th>
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
   var oTable =  $('#tbl_datatable1').DataTable( {
        "processing": true,
        "serverSide": true,
         bSortable: true,
         bRetrieve: true,
         scrollX: true,
         scrollCollapse: true,
         fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 15, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CLIENT_SITE_URL.'download_report/data_table_cands_view_report'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'from_date':function(){return $("#from_date").val(); },'todate':function(){return $("#todate").val(); } }  
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
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'},{"data":"overallclosuredate"},{"data":"TAT"},{"data":"remarks"},{"data":"Action"},{"data":"entity"},{"data":"package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}]
    } );

    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });

    $('#searchrecords').on('click', function() {

    var from_date = $('#from_date').val();
    var todate = $('#todate').val();
 
    if(from_date > todate)
    {
      alert('Please select to date greater than from date');
    }
    else
    {
       var new_url = "<?php echo CLIENT_SITE_URL.'download_report/data_table_cands_view_report'; ?>?from_date="+from_date+"&todate="+todate;
       oTable.ajax.url(new_url).load();
    }
  });


  $(document).on('click', '#download_report', function(){
    
    select_one = oTable.column(0).checkboxes.selected().join(",");
   
      if(select_one != "")
      { 
        $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'download_report/report/' ?>',
          data : 'cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign').text('Processing...');
            
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        }); 

      } else {
      
       alert('Select atleast one case');
      }
});  


});
</script>
