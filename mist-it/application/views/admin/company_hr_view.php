<div class="content-page">
<div class="content">
<div class="container-fluid">

   <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Employment - HR Databases</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>hr_database">HR Databases</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                     <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>hr_database/add"><i class="fa fa-plus"></i> HR Details</button></li>
                    <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-toggle="modal" data-target="#myModal"><i class="fa fa-download"></i> Import</button></li>

                   </ol>
                  </div>
             </div>
          </div>
    </div>

      <div class="row">
        <div class="col-12">
           <div class="card m-b-20">
            <div class="card-body">
            <!-- /.box-header -->
           
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Created On</th>
                  <th>Created By</th>
                  <th>Company</th>
                  <th>Deputed Company</th>
                  <th>Verifier's Name</th>
                  <th>Verifier's Designation</th>
                  <th>Verifier's Contact No</th>
                  <th>Verifier's Email ID</th>
                  <th>Remarks</th>
                  <th>Modified On</th>
                  <th>Modified By</th>
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
            url: "<?php echo ADMIN_SITE_URL.'hr_database/hr_database_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 1, 'Asc' ]],
        'columnDefs': [
           {
              'targets': 0
              
           }
        ],
        'select': {
           'style': 'multi'
        },
        "columns":[{'data' :'id'},{'data' :'created_on'},{"data":"created_by"},{"data":"coname"},{"data":"deputed_company"},{'data' :'verifiers_name'},{'data' :'verifiers_designation'},{'data' :'verifiers_contact_no'},{'data' :'verifiers_email_id'},{'data' :'remark'},{'data' :'modified_on'},{"data":"modified_by"}]
        
  } );

   $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
      window.location = data['encry_id'];
  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#filter_by_clientid,#filter_by_status').on('change', function(){
    var filter_by_clientid = $('#filter_by_clientid').val();
    var filter_by_status = $('#filter_by_status').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'employment/employement_view_datatable'; ?>?filter_by_clientid="+filter_by_clientid+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#export_to_excel_frm').validate({ 
        rules: {
          reasonforleaving : {
            required : true
          }
        },
        messages: {
          reasonforleaving : {
            required : "Enter Reason for Leaving"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#clientid').val();
            var fil_by_status = $('#fil_by_status').val();
            var client_name = $('#clientid option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&fil_by_status='+ fil_by_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'employment/export_to_excel'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
              //$('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModal .close").click()
              $('#export_to_excel_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                window.location.href = jdata.file;
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          });   
        }
  });

  $('#export_activity_frm').validate({ 
        rules: {
          activity_from : {
            required : true
          },
          activity_to : {
            required : true
          }
        },
        messages: {
          activity_from : {
            required : "Select From Date"
          },
          activity_to : {
            required : "Select To Date"
          }
        },
        submitHandler: function(form) 
        {      
            var $activity_from = $("#activity_from").datepicker('getDate');
            var $activity_to = $("#activity_to").datepicker('getDate');
            var dataString = 'activity_from=' + $activity_from + '&activity_to='+ $activity_to;
            if($activity_from > $activity_to)
            {
                alert("To date shouldn't greater than from date");
                return;
            }
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'employment/export_activity_records'; ?>',
            data: $('#export_activity_frm').serialize(),
            type: 'POST',
            beforeSend:function(){
              $('#export_activity_tbn').text('exporting..');
              $('#export_activity_tbn').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_activity_tbn').text('Export');
             // $('#export_activity_tbn').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#activityModel .close").click()
              $('#export_activity_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                window.location.href = jdata.file;
                show_alert(message,'success'); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          });   
        }
  });
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
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});

$(document).on('change', '#filter_by_status_candidates', function(){
    var status = $("#filter_by_status_candidates option:selected").html();
    if(status == 'Closed')
    {
      $('#date_field_show_hide').show();
    }
    else
    {
      $('#date_field_show_hide').hide();
    }
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#filter_by_sub_status_candidates').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#filter_by_sub_status_candidates').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#filter_by_sub_status_candidates').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#filter_by_sub_status_candidates').append($("<option></option>").attr("value",key).text(value));
            });
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
    text-align: center; 
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
    text-align: center; 

}

</style>
