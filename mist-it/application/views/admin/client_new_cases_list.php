<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Task Manager - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>client_new_cases">Task Manager</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php $access_add_task = ($this->permission['access_task_list_add']) ? ADMIN_SITE_URL.'client_new_cases/add'  : ''; ?>
                      <li><button class="btn btn-secondary waves-effect btn-sm  btn_clicked" data-toggle="modal" data-accessUrl="<?php echo $access_add_task;?>"><i class="fa fa-plus"></i> Add New</button></li>
                      <li><button class="btn btn-secondary waves-effect  btn_clicked btn-sm" data-toggle="modal" data-target="#myModal"  data-accessUrl="<?= ($this->permission['access_task_list_import']) ? ADMIN_SITE_URL.'client_new_cases/' : ''?>"><i class="fa fa-download"></i> Import</button></li> 
                   </ol>

                  </div>
            </div>
          </div>
    </div>
 
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <form>
                <div class="row">
                <div class="col-md-2 form-group">
                  <?php echo form_dropdown('filter_by_status', array('' => 'Status','wip' => 'WIP','hold' => 'Hold','closed' => 'Completed'), set_value('filter_by_status','wip'), 'class="custom-select" id="filter_by_status"');?>
                </div>
                <div class="col-sm-2 form-group">
                  <?php echo form_dropdown('filter_by_sub_status', array('' => 'Type','new case' => 'New Case','insuff cleared' => 'Insuff Cleared','closures' => '
                    Closures'), set_value('filter_by_sub_status',''), 'class="custom-select" id="filter_by_sub_status1"');?>
                </div>
                <div class="col-sm-2 form-group">
                  <?php echo form_dropdown('filter_by_clientid', $clients, set_value('filter_by_clientid'), 'class="custom-select" id="filter_by_clientid"');?>
                </div>
                 
                <div class="col-sm-2 form-group">
                  <input type="date" name="start_date" id="start_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                </div>
                   
                  
                <div class="col-sm-2 form-group">
                  <input type="date" name="end_date" id="end_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                </div>
               
                <div class="col-sm-2 form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">

                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
               </div>
              </form>
                 <?php if(!empty($this->assign_options)) {
                  echo '<div class="col-sm-2 form-group">';
                  echo form_dropdown('cases_assgin', $this->assign_options, set_value('cases_assgin'), 'class="custom-select" id="cases_assgin"');
                  echo "</div>";
                }?>
             
            <!-- /.box-header -->
            
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>

                  <th><input name="select_all"  id="example-select-all" class="example-select-all" type="checkbox" /></th>
                  <th>Tsk ID</th>
                  <th>Client Name</th>
                  <th>Total Cases</th>
                  <th>Created By</th>
                  <th>Created On</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Task Assign</th>
                  <th>Task Complete</th> 
                </tr>
                </thead>
                <tbody id="append_list"></tbody>
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
          
          <div class="col-md-12 col-sm-12 col-xs-12 form-group users_list">
            <?php echo form_dropdown('users_list', $users_list, set_value('users_list'), 'class="form-control" id="users_list" required="required" ');?>
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
<?php
  /*  $ftp = ftp_connect('mistitservices.com');

    $login_result = ftp_login($ftp, 'webdev@mistitservices.com', 'WebDev@123');

    $mode = ftp_pasv($ftp, TRUE);

    //Login OK ?
    if ((!$ftp) || (!$login_result) || (!$mode)) {
       die("FTP connection has failed !");
    }
    echo "<br />Login Ok.<br />";

    $file_list = ftp_nlist($ftp, "/public_html/uportal/uploads/");
foreach ($file_list as $file)
{
  echo "<br>$file";
}


ftp_close($ftp);*/

?>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
var $ = jQuery;
$(document).ready(function() {


  var oTable = $('#tbl_datatable').DataTable( {
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
        "iDisplayLength": 10,
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },

         
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'client_new_cases/list_data/'; ?>",
            pages: 1,
            async: true,
            method: 'POST',
            data: { 'client_id':function(){return $("#filter_by_clientid").val(); },'status':function(){return $("#filter_by_status").val(); },'sub_status':function(){return $("#filter_by_sub_status1").val(); },'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); }, },
             async: true ,
            
                        
        } 
       ),

         order: [[ 4, 'desc' ]],
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
    
        "columns":[{'data':'checkbox'},{'data':'id'},{"data":"clientname"},{"data":"total_cases"},{"data":"created_by"},{"data":"created_on"},{"data":"type"},{"data":"status"},{"data":"task_pending"},{"data":"task_complete"}]
        

  });
   

    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });
 
  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['access_task_list_edit'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });
  


  $('#filter_by_clientid,#filter_by_status,#filter_by_sub_status1').on('change', function(){

    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status1').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'client_new_cases/list_data'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords').on('click', function() {
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status1').val();
    var client_id = $('#filter_by_clientid').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(start_date > end_date)
    {
      alert('Please select end date greater then start date');
    }
    else
    {
    var new_url = "<?php echo ADMIN_SITE_URL.'client_new_cases/list_data'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&start_date="+start_date+"&end_date="+end_date;
    oTable.ajax.url(new_url).load();
   }
  });

   $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    select_one = oTable.column(0).checkboxes.selected().join(",");
    
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        $('.users_list').show();
        $('.header_title').text('Assign Executive');
      }
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
    var users_list = $('#users_list').val();
    var vendor_list = $('#vendor_list').val();
    if(users_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'client_new_cases/assign_to_executive/' ?>',
          data : 'users_list='+users_list+'&cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
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
