<div class="content-page">
<div class="content">
<div class="container-fluid">
  <br>
   <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified"> 
        
           <?php  
            echo "<li class='nav-item waves-effect waves-light res active'  role='presentation' data-tab_name='address_list_view' ><a class = 'nav-link active' href='#address_list_view' aria-controls='home' data-toggle='tab'>Address List View</a></li>";
            
            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."address/get_assign_address_list_view/'  data-tab_name='assign_address_list_view'  class='nav-item waves-effect waves-light view_assign_tab'><a class = 'nav-link' href='#assign_address_list_view' aria-controls='home' role='tab'  data-toggle='tab'>New Cases</a></li>";
   
         ?>                        
       </ul>
    </div>   
     
 <div class="tab-content">
  <div id="address_list_view" class="tab-pane active">  
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Address - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>address">Address</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                      <?php 
                      $access_update_status = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateStatus'  : '';
                      $access_export = ($this->permission['access_address_list_export'] == "1") ? '#myModalExport'  : '';
                      $access_import = ($this->permission['access_address_list_import'] == "1") ? '#myModelImport'  : '';
                      $access_update_case_received_date = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateCaseReceiveDate'  : ''; 
                      $access_update_closure_date = ($this->user_info['import_permission'] == "1") ? '#myModalUpdateClosureDate'  : '';  

                      ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_status;?>"><i class="fa fa-download"></i> Update Status</button></li>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import</button></li>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_export;?>"><i class="fa fa-download"></i> Export</button> </li>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_update_case_received_date;?>"><i class="fa fa-download"></i> Update Received Date</button> </li>
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
                <form>
                  <div class="row">
                  <div class="col-sm-3 form-group">
                    <?php
                    echo form_dropdown('filter_by_executive',$user_list_name, set_value('filter_by_executive',$this->user_info['id']), 'class="select2" id="filter_by_executive"');?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <?php echo form_dropdown('filter_by_status', $status, set_value('filter_by_status','WIP'), 'class="select2" id="filter_by_status"');?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status', $sub_status, set_value('filter_by_sub_status'), 'class="select2" id="filter_by_sub_status"');?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <?php echo form_dropdown('filter_by_clientid', $clients, set_value('filter_by_clientid'), 'class="select2" id="filter_by_clientid"');?>
                  </div>
                  <div class="col-sm-3 form-group">
                    <?php 
                  
                      $array2=array("0"=>"Select Vendor");
                      $vendor_lists = (array_replace($vendor_list,$array2));
                  
                      echo form_dropdown('filter_by_vendor', $vendor_lists, set_value('filter_by_vendor'), 'class="select2" id="filter_by_vendor"');
                    
                    ?>
                  </div>

                  
                  <div class="col-sm-3 form-group">
                      <input type="date" name="start_date" id="start_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                  </div>
                                    
                  <div class="col-sm-3 form-group">
                        <input type="date" name="end_date" id="end_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                  </div>
                  

                  <!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
                    <input type="text" name="start_end_date" id="start_end_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                  </div>-->
                  <div class="col-sm-4 form-group">
                    <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                    <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                  </div>
                  </div>
                </form>
                  <div class="clearfix"></div>


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
                      <th>Vendor</th>
                      <th>Status</th>
                      <th>Address</th>
                      <th>State</th>
                      <th>City</th>
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


    <div id="assign_address_list_view" class="tab-pane fade in">
     
    </div>
  </div>  
    
</div>
</div>
</div>
<!--<div id="myModalCasesAssign" class="modal fade" role="dialog" >
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
</div>-->

<div id="myModalUpdateStatus" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Address Status</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_status_update','id'=>'frm_download_tem_and_import_status_update')); ?>   
      <div align="center">
         <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Status <span class="error"> *</span></label>
            <?php
                  $select_status = array(''=> 'Select Upload Status','stop_check'=>'Stop Check','clear'=>'Clear','insufficiency'=>'Insufficiency');

                    echo form_dropdown('upload_status', $select_status, set_value('upload_status'), 'class="form-control" id="upload_status"');
                   echo form_error('upload_status');

            ?>
          </div>
        </div>
          <div class="clearfix"></div>

     
      <div class="modal-body">
        <div class="row">
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

<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Address Records</h4>
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
        <h4 class="modal-title">Export Address Records</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>Client Name</label>
              <?php
              $clients_id['All'] = 'All';
               echo form_dropdown('client_id', $clients_id, set_value('client_id'), 'class="form-control" id="client_id"');?>
            </div>
           
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Status</label>
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

          <div class="col-sm-6 form-group">
            <?php     
              $array2=array("0"=>"Select Vendor");
              $vendor_lists = (array_replace($vendor_list,$array2));
                  
              echo form_dropdown('vendor_id', $vendor_lists, set_value('vendor_id'), 'class="select2" id="vendor_id"');
                    
            ?>
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

<div id="myModalUpdateCaseReceiveDate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Address Case Received Date</h4>
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

<div id="myModalUpdateClosureDate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Address Closure Date</h4>
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

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
 $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

var $ = jQuery;
var select_one = '';
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
            url: "<?php echo ADMIN_SITE_URL.'address/address_view_datatable'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_executive':function(){return $("#filter_by_executive").val(); },'clientid':function(){return $("#filter_by_clientid").val(); },'status':function(){return $("#filter_by_status").val(); },'sub_status':function(){return $("#filter_by_sub_status").val(); },'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); },'vendor_id':function(){return $("#filter_by_vendor").val(); } }
        } ),
        order: [[ 1, 'desc' ]],
        "columns":[{'data' :'id'},{'data' :'ClientRefNumber'},{"data":"add_com_ref"},{"data":"iniated_date"},{"data":"clientname"},{'data' :'CandidateName'},{'data' :'vendor_name'},{'data' :'status_value'},{'data' :'address'},{'data' :'state'},{'data' :'city'},{"data":"executive_name"},{'data':'tat_status'},{'data':'check_closure_date'},{'data':"last_activity_date"},{'data':"mod_of_veri"},{'data':"cmp_ref_no"}],

         "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {

         
            switch(aData['status_value'])
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

  });

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

   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });
   

 

  $('#filter_by_executive,#filter_by_clientid,#filter_by_status,#filter_by_sub_status,#filter_by_vendor').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
    var filter_by_vendor = $('#filter_by_vendor').val();

  
    var new_url = "<?php echo ADMIN_SITE_URL.'address/address_view_datatable'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&filter_by_executive="+filter_by_executive+"&filter_by_vendor="+filter_by_vendor;
    oTable.ajax.url(new_url).load();
  });
  

   $('#filter_by_executive,#filter_by_status,#filter_by_sub_status').on('change', function(){
    var filter_by_executive = $('#filter_by_executive').val();
    var client_id = $('#filter_by_clientid').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
    var sub_status = $('#filter_by_sub_status').val();


     $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/get_clients_for_address_list_view/' ?>',
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

  $('#searchrecords').on('click', function() {
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();
    var sub_status = $('#filter_by_sub_status').val();
    var client_id = $('#filter_by_clientid').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var filter_by_vendor = $('#filter_by_vendor').val();

    if(start_date > end_date)
    {
      alert('Please select end date greater than start date');
    }
    else
    {
    var new_url = "<?php echo ADMIN_SITE_URL.'address/address_view_datatable'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&start_date="+start_date+"&end_date="+end_date+"&filter_by_executive="+filter_by_executive+"&filter_by_vendor="+filter_by_vendor;
    oTable.ajax.url(new_url).load();
   }
  });

 $('#btn_reset').on('click', function() {

   $("#filter_by_executive option[value = All]").attr('selected','selected');
   $("#filter_by_status option[value = All]").attr('selected','selected');
    
    var filter_by_executive = $('#filter_by_executive').val();
    var status = $('#filter_by_status').val();

    $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/get_clients_for_address_list_view/' ?>',
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


    var new_url = "<?php echo ADMIN_SITE_URL.'address/address_view_datatable'; ?>?status="+status+"&filter_by_executive="+filter_by_executive;
    oTable.ajax.url(new_url).load();
     
  });

  

 /* $('#cases_assgin').on('change', function(e){
    var cases_assgin_action = $('#cases_assgin').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");
    
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        $('.users_list').show();
        $('.header_title').text('Assign Executive');
      }
      $('#myModalCasesAssign').modal('show');
      $('#myModalCasesAssign').addClass("show");
      $('#myModalCasesAssign').css({background: "#0000004d"});

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
  });*/

 /* $('#btn_assign_action').on('click', function(e){
    var users_list = $('#users_list').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");

    if(users_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/assign_to_executive/' ?>',
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
  }); */   

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
          url : '<?php echo ADMIN_SITE_URL.'address/bulk_update_closure_date'; ?>',
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
          url : '<?php echo ADMIN_SITE_URL.'address/bulk_upload_address_status_change'; ?>',
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

});



$('#download_candidate_template').on('click',function(){
    

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Address/template_download'; ?>',
          data : '',
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_template').text('downloading..');
              $('#download_candidate_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_template').text('Template');
              $('#download_candidate_template').removeAttr('disabled');                
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
          url : '<?php echo ADMIN_SITE_URL.'address/bulk_update_case_received_date'; ?>',
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




$('#download_status_change_bulk_upload').on('click',function(){
    
     var status = $("#upload_status").val();
      if(status  != "")
      {

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Address/template_download_status_change'; ?>',
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
            var vendor_id = $('#vendor_id').val();

            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&fil_by_status='+ fil_by_status+'&from_date='+ from_date+'&to_date='+ to_date+'&vendor_id='+ vendor_id;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'address/export_to_excel'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
              $('#export').removeAttr('disabled');                
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
          url:'<?php echo ADMIN_SITE_URL.'address/get_clients_for_address_list_view/' ?>',
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
<script type="text/javascript">
  $('#filter_by_status').css('background-color','Yellow');
  $('#filter_by_executive').css('background-color','Yellow');

  $('#filter_by_status').one("change",function(){
     $(this).css('background-color','white');
  });
  

  $('#filter_by_executive').one("change",function(){
       $('#filter_by_executive').css('background-color','white');
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
