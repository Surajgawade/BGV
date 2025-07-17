
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <?php if ($this->permission['access_final_annexture_view'] == 1) { ?>  
              <form>
              <div class="row">
                <div class="col-sm-3 form-group">
                  <?php
                    $filter_client_id = array('43'=> 'Vio','0'=> 'All');
                   echo form_dropdown('clientid_annexture', $filter_client_id, set_value('clientid_annexture','43'), 'class="custom-select" id="clientid_annexture"');?>
                </div>

                <div class="col-sm-3 form-group">
                    <input type="text" name="start_date_annexture" id="start_date_annexture" class="form-control myDatepicker" value = "<?php echo date('d-m-Y') ?>" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                </div>
                                  
                <div class="col-sm-3 form-group">
                      <input type="text" name="end_date_annexture" id="end_date_annexture" class="form-control myDatepicker" value = "<?php echo date('d-m-Y') ?>" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                </div>

                <div class="col-sm-3  form-group">
                  <input type="button" name="searchrecords_annexture" id="searchrecords_annexture" class="btn btn-md btn-info" value="Filter">
                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
              </div> 
            </form>
              
            <?php if ($this->permission['access_final_annexture_download'] == 1) { ?>  
              <form method="post"  id = "demo">
                   <input type="hidden" name="court_id" id="court_id" class="form-control">

                   <?php  echo '<button class="btn btn-xl btn-danger download_report"  id = "download_report"  data-action="'.ADMIN_SITE_URL.'Final_QC/download_annexure_report">Download</button>';  ?>

                </form>     
            <?php } ?>
              <!--<div class="col-sm-2 form-group">
                  <?php  echo '<button class="btn btn-xl btn-danger download_report"  id = "download_report" >Download</button>';  ?>
                 </div>-->
              <table id="tbl_datatable_annexture" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                    <th>Closed On</th>
                    <th>Closed By</th>
                    <th>Case recvd date</th>
                    <th>Client Name</th>
                    <th>Candidate Name</th>
                    <th>Client Ref. No</th>
                    <th><?php echo REFNO; ?></th>
                    <th>Status</th>
                    <th>Entity</th>
                    <th>Package</th>

                </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>

              <?php  }else  { echo "You do not have permission"; } ?>
            </div>
           </div>
          </div>
        </div>
    
   

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
  
  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });
</script>
<script>
var $ = jQuery;
$(document).ready(function() {

  var oTable = $('#tbl_datatable_annexture').DataTable( {
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
        "iDisplayLength": 10,
       // "aaSorting": [[ 4, "desc" ]],
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable_annexture'; ?>",
            pages: 10,
            async: true,
            method: 'POST',
            data: {'clientid':function(){return $("#clientid_annexture").val(); },'start_dates':function(){return $("#start_date_annexture").val(); },'end_dates':function(){return $("#end_date_annexture").val(); }}
            //async: true 

        } ),
          order: [[ 1, 'asc' ]],
          "aLengthMenu": [[ 10, 25, 50,-1], [10, 25, 50,"All"]],

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
     
        "columns":[{"data":"id"},{"data":"mod_time"},{"data":"vendor_name"},{"data":"iniated_date"},{"data":"clientname"},{"data":"CandidateName"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"overallstatus"},{"data":"entity"},{"data":"package"}],

        
  });

  $('#clientid_annexture').on('change', function(){

    var client_id = $('#clientid_annexture').val();
    var start_date = $('#start_date_annexture').val();
    var end_date = $('#end_date_annexture').val();
    

    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable_annexture'; ?>?clientid="+client_id+"&start_dates="+start_date+"&end_dates="+end_date;
    oTable.ajax.url(new_url).load();
});


$('#searchrecords_annexture').on('click', function() {
  var client_id = $('#clientid_annexture').val();
  var start_date = $('#start_date_annexture').val();
  var end_date = $('#end_date_annexture').val();

  var new_start_date = start_date.split("-").reverse().join("-");

  var new_end_date = end_date.split("-").reverse().join("-");
 
  if(new_start_date > new_end_date)
  {
    alert('Please select end date greater than start date');
  }
  else
  {
    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable_annexture'; ?>?clientid="+client_id+"&start_dates="+start_date+"&end_dates="+end_date;
    oTable.ajax.url(new_url).load();
  }
});
   

 /*$(document).on('click','.download_report',function() {
    select_one = oTable.column(0).checkboxes.selected().join(",");
    var count_record = select_one.split(",").length;
  
        if(select_one != '')
        {
            var tableControl = document.getElementById('tbl_datatable_annexture');
           
            var result = []
            $('input:checkbox:checked', tableControl).each(function() {
                result.push($(this).parent().next().next().next().next().text());
            });

          
            var set  =  result.splice(0, 1);
            
            var new_value_condition = myFunc(result);
           
            if(new_value_condition == true)
            {
             
               $('#candidate_user_id').val(select_one);
               var action = $(this).data('action');
               $('#demo').attr('action', action);
               $('#demo').submit();*/
              /*  $.ajax({
                    url : "<?php echo ADMIN_SITE_URL.'Final_QC/download_annexure_report'; ?>",
                    data : "candidate_user_id="+select_one,
                    type: 'post',
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend:function(){
                      $('.download_report').val('Sending...');
                      $('.download_report').attr('disabled','disabled');
                    },
                    complete:function(){
                         
                    },
                    success: function(jdata){
                      var message =  jdata.message || '';
                      if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                        show_alert(message,'success'); 
                         location.reload(); 
                      } else {
                        show_alert(message,'error'); 
                      }
                    }
                });*/
          /*  }
            else
            {
               alert('Please select same client name record');
            }

        }
        else
        {
          alert('please select atleast one');
        }
   
});

function myFunc(arr){
        var x= arr[0];
        return arr.every(function(item){
            return item=== x;
        });
    }
 
});*/
  $(document).on('click','.download_report',function() {
      select_one = oTable.column(0).checkboxes.selected().join(",");
      var count_record = select_one.split(",").length;
    
          if(select_one != '')
          {
              $('#court_id').val(select_one);
              var action = $(this).data('action');
              $('#demo').attr('action', action);
              $('#demo').submit(); 
          }
          else
          {
            alert('please select atleast one');
          }
    
  });

});
</script>

