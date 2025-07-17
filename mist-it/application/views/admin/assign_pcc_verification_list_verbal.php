      <div class="row">
          <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">PCC - Assign PCC</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>pcc">PCC</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>assign_pcc">Assign PCC</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php $access_export = ($this->permission['access_pcc_list_export']) ? '#myModalExport'  : ''; ?>
                      <li><button class="btn btn-secondary waves-effect btn-sm" data-toggle="modal" data-target="<?php echo $access_export;?>"><i class="fa fa-download"></i> Export</button></li> 
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
                <?php echo $filter_view; ?>
              </div>
          
                 <table id="tbl_datatable_pcc_assign"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr>
                     <th><input type="checkbox" name="allCheckboxSelect" id="allCheckboxSelect" class="form-control"></th>

                      <th>Sr No</th>
                      <th>Client Ref No</th>
                      <th>Comp Ref No</th>
                      <th>Comp INT</th>
                      <th>Client Name</th>
                      <th>Candidate's Name</th>
                      <th>Vendor</th>
                      <th>Status</th>
                      <th>Address</th>
                      <th>State</th>
                      <th>City</th>
                      <th>Executive</th>
                      <th>QC status</th>
                      <th>TAT status</th>
                      <th>Due Date</th>
                      <th>Last Activity</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    
<div id="myModalCasesAssignvendor" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-12 form-group vendor_list">
            <?php echo form_dropdown('vendor_list', $vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list" required="required" ');?>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 form-group vendor_list">
            <?php $vendor_list_mode = array(''=>'Select','verbal'=> 'Verbal','official'=> 'Official');
               echo form_dropdown('vendor_list_mode', $vendor_list_mode, set_value('vendor_list_mode'), 'class="form-control" id="vendor_list_mode" required="required" ');?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn_assign_action_vendor">Assign</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import PCC Records</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import','id'=>'frm_download_tem_and_import')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="cands_bulk_sheet" id="cands_bulk_sheet" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-info pull-left btn-md" id="download_candidate_template">Template</button>
        <button type="submit" class="btn btn-info btn-md" id="import_candidate_template">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>  
var $ = jQuery;
$(document).ready(function() {
  
  var oTable =  $('#tbl_datatable_pcc_assign').DataTable( {
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
            url: "<?php echo ADMIN_SITE_URL.'assign_pcc/pcc_view_datatable_assign_verbal'; ?>",
            pages: 1,
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        }),
        order: [[ 3, 'desc' ]],
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"pcc_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'vendor_name'},{'data' :'verfstatus'},{"data":"street_address"},{"data":"state"},{"data":"city"},{"data":"executive_name"},{'data':'first_qc_approve'},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"}]
  } );

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#tbl_datatable_pcc_assign tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });

  $('#tbl_datatable_pcc_assign tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  });

    $('#tbl_datatable_pcc_assign').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });
  
  $('#filter_by_clientid,#filter_by_status').on('change', function(){
    var filter_by_clientid = $('#filter_by_clientid').val();
    var filter_by_status = $('#filter_by_status').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'pcc/pcc_view_datatable'; ?>?filter_by_clientid="+filter_by_clientid+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#cases_assgin_vendor').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin_vendor').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
      if(cases_assgin_action == 1) 
      {
        $('.vendor_list').show();
        $('.header_title').text('Assign Vendor');
      }

      $('#myModalCasesAssignvendor').modal('show');
      $('#myModalCasesAssignvendor').addClass("show");
      $('#myModalCasesAssignvendor').css({background: "#0000004d"});

      var form = this;
      var rows_selected = oTable.column(0).checkboxes.selected();
      $.each(rows_selected, function(index, rowId){
         $(form).append(
             $('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId)
         );
      });
      $('input[name="id\[\]"]', form).remove();
      e.preventDefault();
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
  });

  $('#btn_assign_action_vendor').on('click', function(e){
   
    var vendor_list = $('#vendor_list').val();
    var vendor_list_mode = $('#vendor_list_mode').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");

    if(vendor_list != "0" && select_one != "")
    {

      if(vendor_list_mode != "")
      {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'pcc/assign_to_vendor/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one+'&vendor_list_mode='+vendor_list_mode,
          dataType:'json',
          beforeSend :function(){
            
            jQuery('#btn_assign_action_vendor').text('Processing...');
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModalCasesAssignvendor').modal('hide');
              show_alert(message,'success');
              $('.view_assign_tab a.active').click();
              $("div.modal-backdrop").remove();
            }else {
              show_alert(message,'error'); 
            }
          }
        });
    }
     else
      {
           alert('Select Vendor List Mode');
      }
    }
    else {
      alert('Select Vendor Name');
    }

  });
  
  $('#frm_download_tem_and_import').validate({ 
      rules: {
        cands_bulk_sheet : {
          required : true
        }
      },
      messages: {
        cands_bulk_sheet : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'pcc/bulk_upload_pcc'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_candidate_template').attr('disabled','disabled');
          },
          complete:function(){
            //$('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModelImport').modal('hide');
              show_alert(message,'success',true);
              return;
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });
});

$('#download_candidate_template').click(function(e) {
    e.preventDefault();
    window.location.href = "<?= COM_ATTACHMENTS_PATH.PCC.'/pcc_bulk_template.xlsx'; ?>" ;
});
</script>
<script type="text/javascript">
  $('.select2').select2();
</script>