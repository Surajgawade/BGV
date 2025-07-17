<div class="content-page">
<div class="content">
<div class="container-fluid">

   <br>
   <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified"> 
        
           <?php  
            echo "<li class='nav-item waves-effect waves-light res active'  role='presentation' data-tab_name='employment_list_view' ><a class = 'nav-link active' href='#employment_list_view' aria-controls='home' data-toggle='tab'>Employment List View</a></li>";
            
            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."employment/get_assign_employment_list_view/'  data-tab_name='assign_employment_list_view'  class='nav-item waves-effect waves-light view_assign_tab'><a class = 'nav-link' href='#assign_employment_list_view' aria-controls='home' role='tab'  data-toggle='tab'>New Cases</a></li>";
   
         ?>                        
       </ul>
    </div>   

 <div class="tab-content">
  <div id="employment_list_view" class="tab-pane active">  
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Employment - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                       <?php
                        $access_update_status = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateStatus'  : ''; 
                        $access_update_case_received_date = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateCaseReceiveDate'  : ''; 
                        $access_update_uin_number = ($this->permission['access_employment_list_import'] == "1") ? '#myModalUpdateUINNumber'  : ''; 

                        $access_update_closure_date = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateClosureDate'  : ''; 
                        $access_export = ($this->permission['access_employment_list_export'] == "1") ? '#myModalExport'  : '';
                        $access_import = ($this->permission['access_employment_list_import'] == "1") ? '#myModelImport'  : ''; 
                        ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_status;?>"><i class="fa fa-download"></i> Update Status</button></li>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import</button></li>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_export;?>"><i class="fa fa-download"></i> Export</button> </li>
                   <!--   <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_case_received_date;?>"><i class="fa fa-download"></i> Update Received Date</button> </li>-->
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_uin_number;?>"><i class="fa fa-download"></i> Update UIN Number</button> </li>

                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_closure_date;?>"><i class="fa fa-download"></i> Update Closure Date</button> </li>
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
            
              
               <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                    <tr>
                      <th>Sr No</th>
                      <th>Client Ref No</th>
                      <th>Comp Ref No</th>
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
  <div id="assign_employment_list_view" class="tab-pane fade in">
     
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
          <div class="col-md-12 col-sm-12 col-xs-12 form-group vendor_list">
            <?php echo form_dropdown('vendor_list', $vendor_list, set_value('vendor_list'), 'class="form-control" id="vendor_list" required="required" ');?>
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

<div id="myModalUpdateStatus" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Employment Status</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_status_update','id'=>'frm_download_tem_and_import_status_update')); ?>   
    
      <div class="clearfix"></div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Status <span class="error"> *</span></label>
            <?php
                  $select_status = array(''=> 'Select Upload Status','stop_check'=>'Stop Check');

                    echo form_dropdown('upload_status', $select_status, set_value('upload_status'), 'class="form-control" id="upload_status"');
                   echo form_error('upload_status');

            ?>
          </div>
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="status_change_bulk_upload" id="status_change_bulk_upload" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-info pull-left btn-md" id="download_status_change_bulk_upload">Template</button>
        <button type="submit" class="btn btn-info btn-md" id="import_status_change_bulk_upload">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModalUpdateCaseReceiveDate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Employment Case Received Date</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_update_case_received_date','id'=>'frm_download_tem_and_import_update_case_received_date')); ?>   
    
      <div class="clearfix"></div>
      <div class="modal-body">
        <div class="row">
          
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="case_received_date_change_bulk_upload" id="case_received_date_change_bulk_upload" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
         
        <button type="submit" class="btn btn-info btn-md" id="import_case_received_date_change_bulk_upload">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModalUpdateUINNumber" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Employment UIN Number</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_update_uin_number','id'=>'frm_download_tem_and_import_update_uin_number')); ?>   
    
      <div class="clearfix"></div>
      <div class="modal-body">
        <div class="row">
          
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="uin_number_change_bulk_upload" id="uin_number_change_bulk_upload" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
         
        <button type="submit" class="btn btn-info btn-md" id="uin_number_change_bulk_upload_button">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="myModalUpdateClosureDate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Employment Closure Date</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_update_closure_date','id'=>'frm_download_tem_and_import_update_closure_date')); ?>   
    
      <div class="clearfix"></div>
      <div class="modal-body">
        <div class="row">
          
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="closure_date_change_bulk_upload" id="closure_date_change_bulk_upload" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
         
        <button type="submit" class="btn btn-info btn-md" id="import_closure_date_change_bulk_upload">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Employment Records</h4>
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

<div id="myModalExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm','id'=>'export_to_excel_frm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Employee Records</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <?php
                $clients_id['All'] = 'All';
               echo form_dropdown('client_id',$clients_id,set_value('client_id'),'class="form-control" id="client_id"');?>
            </div>
           
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <?php
               unset($status_value['NA']);
               echo form_dropdown('status_value', $status_value, set_value('status_value','WIP'), 'class="form-control" id="status_value"');?>
            </div>
          <div class="clearfix"></div>
          <div id="display_from_to_date" style="display: none"> 
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>From Date</label>
             <input type="text" name="from_date" id="from_date" class="form-control myDatepicker" placeholder="dd-mm-yyyy" >
           </div>
                           
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>To Date</label>
              <input type="text" name="to_date" id="to_date" class="form-control myDatepicker" placeholder="dd-mm-yyyy" >
           </div>
           </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export" name="export" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>  
var $ = jQuery;
$(document).ready(function() {

  $('#filter_by_executive').val(<?php echo $this->user_info['id'] ?>);
  $('#filter_by_status').val('WIP');


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
            url: "<?php echo ADMIN_SITE_URL.'employment/employement_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_executive':function(){return $("#filter_by_executive").val(); },'clientid':function(){return $("#filter_by_clientid").val(); },'status':function(){return $("#filter_by_status").val(); },'sub_status':function(){return $("#filter_by_sub_status").val(); },'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); } }
        } ),
        order: [[ 1, 'desc' ]],
       
        "columns":[{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"emp_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'coname'},{'data' :'verfstatus'},{"data":"executive_name"},{"data":"field_status"},{'data':'tat_status'},{'data':'due_date'},{'data':"last_activity_date"},{'data':"mode_of_veri"},{'data':"cmp_ref_no"}],

        "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {
            switch(aData['verfstatus'])
            {
              case "Insufficiency Cleared" :
              
                $('td' , nRow).css('background-color', '#d6695b')
                 break;

              case "New Check" :
              
                 $('td' , nRow).css('background-color', '#ffff00')
                 break;

              case "Final QC Reject" :
              
                $('td' , nRow).css('background-color', '#d6695b')
                 break;
                 
              case "First QC Reject" :
              
                $('td' , nRow).css('background-color', '#d6695b')
                 break;

              case "Re-Initiated" :
              
                $('td' , nRow).css('background-color', '#ff0000')
                 break;  
                
              case "YTR" :
              
                $('td' , nRow).css('background-color', '#ffffff')
                 break;          

              default :
                 $('td' , nRow).css('background-color', '#ffffff') 
                
            }
        }
  } );

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
      $('#filter_by_status').val('All');
      $('#filter_by_executive').val('All');
    if(e.keyCode == 13) {
      var search_value = this.value;
      var search_value_details = $.trim(search_value);
      if( search_value_details.length > 2)
      {
        oTable.search( search_value_details ).draw();
      }
    }
  });

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
      window.location = data['encry_id'];
  });

  $('#filter_by_executive,#filter_by_clientid,#filter_by_status,#filter_by_sub_status').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'employment/employement_view_datatable'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
  });

  $('#filter_by_executive,#filter_by_clientid,#filter_by_status,#filter_by_sub_status').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();

     $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/get_clients_for_employment_list_view/' ?>',
          data : 'client_id='+client_id+'&status='+status+'&sub_status='+sub_status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        });
  });

  
  $('#searchrecords').on('click', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
    var client_id = $('#filter_by_clientid').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'employment/employement_view_datatable'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&start_date="+start_date+"&end_date="+end_date+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
  });

  $('#btn_reset').on('click', function() {

   $("#filter_by_executive option[value = All]").attr('selected','selected');
   $("#filter_by_status option[value = All]").attr('selected','selected');
    
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();

       $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/get_clients_for_employment_list_view/' ?>',
          data : 'status='+status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        });  

    var new_url = "<?php echo ADMIN_SITE_URL.'employment/employement_view_datatable'; ?>?status="+status+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
  });


  $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        $('.users_list').show();
        $('.vendor_list').hide();
        $('.header_title').text('Assign Executive');
      }
      else
      {
        $('.vendor_list').show(); 
        $('.users_list').hide();
        $('.header_title').text('Assign Vendor');
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
          url:'<?php echo ADMIN_SITE_URL.'employment/assign_to_executive/' ?>',
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

    if(vendor_list != "0" && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/assign_to_vendor/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one,
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
          url : '<?php echo ADMIN_SITE_URL.'employment/bulk_upload_employment'; ?>',
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
          //  $('#import_candidate_template').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#myModelImport').modal('hide');
              show_alert(message,'success',true);
            }
            if(jdata.status == <?php echo ERROR_CODE; ?>){
              show_alert(message,'error'); 
            }
          }
        });    
      }
  });



  $('#frm_download_tem_and_import_status_update').validate({ 
      rules: {
        upload_status : {
          required : true
        },
         status_change_bulk_upload : {
          required : true
        }

      },
      messages: {
        upload_status : {
          required : "Select Status"
        },
        status_change_bulk_upload : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/bulk_upload_employment_status_change'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
           $('#import_status_change_bulk_upload').attr('disabled','disabled');
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



  $('#frm_download_tem_and_import_update_case_received_date').validate({ 
      rules: {
         case_received_date_change_bulk_upload : {
          required : true
        }

      },
      messages: {
        case_received_date_change_bulk_upload : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/bulk_update_case_received_date'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_case_received_date_change_bulk_upload').attr('disabled','disabled');
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

 
  $('#frm_download_tem_and_import_update_uin_number').validate({ 
      rules: {
        uin_number_change_bulk_upload : {
          required : true
        }

      },
      messages: {
        uin_number_change_bulk_upload : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/bulk_update_uin_number'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#uin_number_change_bulk_upload_button').attr('disabled','disabled');
          },
          complete:function(){
            $('#uin_number_change_bulk_upload_button').removeAttr('disabled');
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

 $('#frm_download_tem_and_import_update_closure_date').validate({ 
      rules: {
         closure_date_change_bulk_upload : {
          required : true
        }

      },
      messages: {
        closure_date_change_bulk_upload : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/bulk_update_closure_date'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#import_closure_date_change_bulk_upload').attr('disabled','disabled');
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


  $('#download_status_change_bulk_upload').on('click',function(){
    
     var status = $("#upload_status").val();
      if(status  != "")
      {

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Employment/template_download_status_change'; ?>',
          data : "status="+status,
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_status_change_bulk_upload').text('downloading..');
              $('#download_status_change_bulk_upload').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_status_change_bulk_upload').text('Template');
              $('#download_status_change_bulk_upload').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModalUpdateStatus.close").click()
              $('#frm_download_tem_and_import')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                show_alert(message,'success'); 
              } else {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xlsx");
            $a[0].click();
            $a.remove();
        });
      }
      else
      {
        show_alert("Please Select Status",'error'); 
      }
  });


});

$('#download_candidate_template').on('click',function(){
    

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/template_download'; ?>',
          data : '',
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_template').text('downloading..');
              $('#download_candidate_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_template').text('Template');
             // $('#download_candidate_template').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelImport.close").click()
              $('#frm_download_tem_and_import')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                show_alert(message,'success'); 
              } else {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xlsx");
            $a[0].click();
            $a.remove();
        });
  });


   $('#export_to_excel_frm').validate({ 
        rules: {
          client_id : {
            required : true,
            greaterThan : 0
          },
          status_value : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          },
          status_value : {
            required : "Select Status",
            greaterThan : "Select Status"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id').val();
            var fil_by_status = $('#status_value').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var client_name = $('#client_id option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&fil_by_status='+ fil_by_status+'&from_date='+ from_date+'&to_date='+ to_date;
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
            $('#export_to_excel_frm')[0].reset();
            $("#myModalExport").hide();
            location.reload();
        });     
        }
  });

$('#status_value').on('change', function(e){
     var status_value = $('#status_value').val();
     if(status_value == "Closed")
     {
       $('#display_from_to_date').show();
     }
     else
     {
       $('#display_from_to_date').hide();
     }
});

</script>
<script type="text/javascript">
  $(document).ready(function(){

    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
 


     $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/get_clients_for_employment_list_view/' ?>',
          data : 'client_id='+client_id+'&status='+status+'&sub_status='+sub_status+'&filter_by_executive='+filter_by_executive,
          dataType:'json',
          beforeSend :function(){
            
          },
          complete:function(){
            
          },
          success:function(jdata)
          {
            $('#filter_by_clientid').empty();
            $.each(jdata.client_list, function(key, value) {
              $('#filter_by_clientid').append($("<option></option>").attr("value",key).text(value));
            });
          }
        }); 

});
</script>
