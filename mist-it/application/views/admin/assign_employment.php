<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Employment - Assign Employment</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>assign_employment">Assign Employment</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php $access_export = ($this->permission['access_employment_list_export']) ? '#myModalExport'  : ''; ?>
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
          
                 <table id="tbl_datatable"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr >
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
        </div>
      </div>
   
</div>
<div id="myModalCasesAssign" class="modal fade" role="dialog">
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

          <?php $vendor_list_mode = array('physical visit'=> 'Physical Visit');
             echo form_dropdown('vendor_list_mode', $vendor_list_mode, set_value('vendor_list_mode'), 'class="form-control" id="vendor_list_mode" required="required" ');?>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="btn_assign_action">Assign</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="fieldVisitModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <?php echo form_open_multipart("#", array('name'=>'frm_field_visit','id'=>'frm_field_visit')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Field Visit</h4>
      </div>
      <div class="modal-body"> <div id="append_field_visit_frm"></div> </div>  
      <div class="modal-footer">
        <button type="submit" id="add_field_visit" name="add_field_visit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
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
            url: "<?php echo ADMIN_SITE_URL.'assign_employment/employment_view_datatable_assign'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"emp_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'vendor_name'},{'data' :'status_value'},{'data' :'locationaddr'},{'data' :'state'},{'data' :'citylocality'},{"data":"executive_name"},{'data':'first_qc_approve'},{'data':'tat_status'},{'data':'check_closure_date'},{'data':"last_activity_date"}]
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#tbl_datatable tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  });

    $('#tbl_datatable').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

  /*$('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 

    if(data['edit_access'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });*/
 $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    var url = data['encry_id'];
    $('#append_field_visit_frm').load(url,function(){
      $('#fieldVisitModel').modal('show');
    });
  });

 $('#frm_field_visit').validate({ 
        rules: {
          update_id : {
            required : true
          }  
        },
        messages: {
          update_id : {
            required : "Update ID missing"
          }
        },
        submitHandler: function(form) 
        {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'employment/add_field_visit'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#add_field_visit').attr('disabled','disabled');
              },
              complete:function(){
                //$('#add_field_visit').removeAttr('disabled',false);
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                  show_alert(message,'success');
                  location.reload();
                }else {
                  show_alert(message,'error'); 
                }
              }
            });       
        }
  });




  $('#filter_by_clientid,#filter_by_status').on('change', function(){
    var filter_by_clientid = $('#filter_by_clientid').val();
    var filter_by_status = $('#filter_by_status').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'address/address_view_datatable'; ?>?filter_by_clientid="+filter_by_clientid+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        $('.users_list').hide();
        $('.vendor_list').show();
        $('.header_title').text('Assign Vendor');
      }
      //else
      // {
       // $('.vendor_list').show(); 
       // $('.users_list').hide();
       // $('.header_title').text('Assign Vendor');
      //}
      $('#myModalCasesAssign').modal('show');

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

  $('#btn_assign_action').on('click', function(e){
    // users_list = $('#users_list').val();

    var vendor_list = $('#vendor_list').val();

  
    if(vendor_list != "0" && select_one != "")
    {
        var vendor_list_mode = $('#vendor_list_mode').val();
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/assign_to_vendor/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one+'&vendor_list_mode='+vendor_list_mode,
          dataType:'json',
          beforeSend :function(){
           
            jQuery('#btn_assign_action').text('Processing...');
          },
          complete:function(){
             jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModalCasesAssign').modal('hide');
              show_alert(message,'success',true);
            }else {
              show_alert(message,'error'); 
            }
          }
        });
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
          url : '<?php echo ADMIN_SITE_URL.'address/bulk_upload_address'; ?>',
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
           // $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success',true);
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
    window.location.href = "<?= COM_ATTACHMENTS_PATH.ADDRESS.'/address_bulk_template.xlsx'; ?>" ;
});

</script>
<script type="text/javascript">
  $(document).ready(function(){
  
    $('.filterable .btn-filter').click(function(){

        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
      
        /* Ignore tab key */
        var code = e.keyCode || e.which;
      
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');

        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
        var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No Record Found</td></tr>'));
        }
    });
});
</script>
<style type="text/css">
  .filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
    size:5px;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}

</style>
