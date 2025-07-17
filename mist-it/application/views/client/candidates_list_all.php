<style type="text/css">
.status_alert {
    text-align:center;
}
a {
    color: #007bff;
}
i {
  font-size: 22px;
}

</style>

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Candidate - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>candidates/index">Candidate</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
            
            </div>
          </div>
    </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <form>
                <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php
                   echo form_dropdown('status_candidate', $status, set_value('status_candidate',$selected_status), 'class="custom-select" id="status_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_status_candidate', $sub_status, set_value('sub_status_candidate'), 'class="custom-select" id="sub_status_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php echo form_dropdown('clientid_candidate', $clients, set_value('clientid_candidate',$this->session->userdata('client')['client_id']), 'class="custom-select" id="clientid_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php echo form_dropdown('entity_candidate',array(), set_value('entity_candidate'), 'class="custom-select" id="entity_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <select id="package_candidate" name="package_candidate" class="custom-select"><option value="0">Select</option></select>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
              </div>
              </form>

            <?php echo form_open('#', array('name'=>'export_to_excel_frm','id'=>'export_to_excel_frm')); ?>
                <button class="btn btn-secondary waves-effect" data-toggle="modal" id= "export"><i class="fa fa-download"></i> Export</button>
              </ol>
             <?php echo form_close(); ?>

              <div class=clearfix></div>

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
                            <th>View Report</th>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="append-data">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
$('#clientid_candidate').attr("style", "pointer-events: none;");

//var status = "<?php echo $this->uri->segment(2); ?>";

$('input[type="search"]').val(status);

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
        "iDisplayLength": 10, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST', 
            data: { 'status':function(){return $("#status_candidate").val(); } }
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
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'},{"data":"overallclosuredate"},{"data":"report"},{"data":"entity"},{"data":"package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}]
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    $('#status_candidate').val('All');
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });


  $('#status_candidate,#sub_status_candidate,#clientid_candidate,#entity_candidate,#package_candidate').on('change', function(){
    var client_id = $('#clientid_candidate').val();
    var status = $('#status_candidate').val();
    var sub_status = $('#sub_status_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
  
    var new_url = "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords').on('click', function() {
    var status = $('#status_candidate').val();
    var sub_status = $('#sub_status_candidate').val();
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
   
    var new_url = "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
    
  });

$('#btn_reset').on('click', function() {
   $("#status_candidate option[value = All]").attr('selected','selected');
    var status = $('#status_candidate').val();
    var client_id = $('#clientid_candidate').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>?status="+status+"&client_id="+client_id;
    oTable.ajax.url(new_url).load();
  });


  });



  $(document).on('click', '.view_details', function(){
    var id = "<?php echo SITE_URL.'candidates/view_details/' ?>"+$(this).data("raw_id");

    $('.append-data').load(id,function(){
        $('#myModal').modal('show');
  });
});

$( document ).ready(function() {
  var clientid = $('#clientid_candidate').val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'Insufficiency/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_candidate').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_candidate').html(html);
          }
      });
  }
});

$(document).on('change', '#entity_candidate', function(){
  var entity = $(this).val();
  var selected_clientid = '';
  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'Insufficiency/get_package_list'; ?>',
          data:'entity='+entity+'&selected_clientid='+selected_clientid,
          beforeSend :function(){
            jQuery('#package_candidate').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#package_candidate').html(html);
          }
      });
  }
});


$(document).on('change', '#status_candidate', function(){
    var status = $("#status_candidate option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : '<?php echo CLIENT_SITE_URL.'candidates/sub_status_list_candidates'; ?>',
        beforeSend :function(){
            jQuery('#sub_status_candidate').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_status_candidate').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_status_candidate').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_status_candidate').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});  

$(document).on('click', '.status_alert', function(){

  var status = $(this).data('overallstatus');
  url = $(this).data('href');
  
  var win= window.open(url, '_blank');

});


$('#CreateFinalReport').click(function(){
    var url = $(this).data("url");
    $('#append_final_result').load(url,function(){});
    $('#showFinalReportModel').modal('show');
  });

  $('#CreateInterimReport').click(function(){
    var url = $(this).data("url");
    $('#append_interim_result').load(url,function(){});
    $('#showInterimReportModel').modal('show');
  });


   $('#export_to_excel_frm').validate({ 
        rules: {
         
        },
        messages: {
         
        },
        submitHandler: function(form) 
        {      
            $.ajax({
            url: '<?php echo CLIENT_SITE_URL.'candidates/export_to_excel'; ?>',
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xls");
            $a[0].click();
            $a.remove();
            location.reload();
        });     
        }
  });

</script>
