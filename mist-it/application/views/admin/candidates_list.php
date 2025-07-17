<div class="content-page">
<div class="content">
  <div class="container-fluid">
     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Candidates - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidate</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                    
                      <li><button class="btn btn-secondary waves-effect btn_clicked" data-accessUrl="<?= ($this->permission['access_candidates_list_add']) ? ADMIN_SITE_URL.'candidates/add' : '' ?>"><i class="fa fa-plus"></i> Add Candidate</button></li>
                      <?php $access_import = ($this->permission['access_candidates_list_import']) ? '#myModelImport'  : '';  ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import</button></li>
                      <?php $access_import_export = ($this->permission['access_candidates_list_export']) ? '#myModelExport'  : '';  ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import_export;?>"><i class="fa fa-download"></i> Export</button></li>
                       <?php $access_import_export_tracker = ($this->permission['access_candidates_list_export']) ? '#myModelExportTracker'  : '';  ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import_export_tracker;?>"><i class="fa fa-download"></i> Tracker</button></li>

                      <?php $access_upate_cands = ($this->permission['access_candidates_list_special_acitivity_status'] == "1") ? '#myBluckUpdate'  : '';  ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_upate_cands ;?>"><i class="fa fa-download"></i> Bulk Update</button></li>
                      
                   </ol>
                
                    <ol class="breadcrumb" style = "float:right;">
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal"  data-target="#mySearch"> Search ID</button></li>
                      <?php $access_stop_check = ($this->permission['access_candidates_list_special_acitivity_status'] == "1") ? '#myBluckstopcheck'  : '';  ?>
                      <li><button class="btn btn-secondary waves-effect" data-toggle="modal"  data-target="<?php echo $access_stop_check ;?>"> Stop Cases</button></li>
                      
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

                    <th>#</th>
                    <th>Case recvd date</th>
                    <th>Client Name</th>
                    <th>Entity</th>
                    <th>Package</th>
                    <th>Candidate Name</th>
                    <th>Client Ref. No</th>
                    <th>Comp</th>
                    <th>status</th>
                    <th>WIP</th>
                    <th>Insufficiency</th>
                    <th>Closed</th>
                    <th>Closure Date</th>
                    <th>Due Date</th>
                    <th>TAT</th>
                    <th>Action</th>
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

<div id="myBluckstopcheck" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bulk Stop Check</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12 form-group"> 
          <label>Insert Ref No</label>
            <textarea name="case_assign_to_ref_no" id="case_assign_to_ref_no" rows="2" class="form-control"></textarea>
        </div>
      </div>
    
      <div class="modal-footer">
        
        <button type="submit" class="btn btn-info btn-md" id="update_cases_bulk">Update</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
     </div>
  </div>
</div>

<div id="mySearch" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Search MIST ID</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12 form-group"> 
          <label>Aadhar/Pan Card Number <span class = "error"> * </span></label>
            <textarea name="aadhar_pan_card" id="aadhar_pan_card" rows="2" class="form-control"></textarea>
            <div id = "cmp_ref_no_show"  style= "color:red;"></div>
        </div>
      
      </div>
    
      <div class="modal-footer">
        
        <button type="submit" class="btn btn-info btn-md" id="search_aadhar_pan">Search</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
     </div>
  </div>
</div>


<div id="myBluckUpdate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Candidat Update</h4>
      </div>
      
      <?php echo form_open_multipart('#', array('name'=>'frm_update_candidate_bulk','id'=>'frm_update_candidate_bulk')); ?>   
      <div class="modal-body">
        <div class="row">

          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Upload File</label>
            <input type="file" name="update_candidate_bulk_sheet_attachment" id="update_candidate_bulk_sheet_attachment" class="form-control">
          </div>
        </div>
      </div>
    
      <div class="modal-footer">
        
        <button type="submit" class="btn btn-info btn-md" id="update_candidate_bulk">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
     </div>
  </div>
</div>


<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        <div align="center">
         <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Candidate/Attachment <span class="error"> *</span></label>
            <?php
              $work_candidate_attachment = array(''=> 'Select Candidate/Attachment','candidates'=>'Candidates','attachment'=>'Attachment');
              echo form_dropdown('candidate_attachment', $work_candidate_attachment, set_value('candidate_attachment'), 'class="form-control" id="candidate_attachment"');
              echo form_error('candidate_attachment');
            ?>
          </div>
        </div>
          <div class="clearfix"></div>
     <div  id="candidates_display" style="display: none;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Candidate Records</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import','id'=>'frm_download_tem_and_import')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Client <span class="error"> *</span></label>
            <?php
              echo form_dropdown('clientid',$clients_id, set_value('clientid'), 'class="form-control" id="clientid"');
              echo form_error('clientid');
            ?>
          </div>
          <div class="clearfix"></div>
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
            <label >Select Entiy<span class="error"> *</span></label>
            <?php
              echo form_dropdown('entity', array(), set_value('entity'), 'class="form-control" id="entity"');
              echo form_error('entity');
            ?>
          </div>
          <div class="col-md-4 col-sm-12 col-xs-4 form-group">
            <label >Select Package<span class="error"> *</span></label>
             <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
            <?php echo form_error('package');?>
          </div>
          <div class="clearfix"></div>

          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="cands_bulk_sheet" id="cands_bulk_sheet" class="form-control">
          </div>
        </div>
      </div>
      <input type="hidden" name="count_record_update" id="count_record_update" class="form-control">
      
      <div class="modal-footer">
          <button type="button" class="btn btn-info pull-left btn-md" id="download_candidate_template">Template</button>

          <button type="button" class="btn btn-info pull-left btn-md" id="download_candidate_latest_record" style="display: none">Download</button>

          
        <button type="submit" class="btn btn-info btn-md" id="import_candidate_template">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
    

   <div id="attachment_display" style="display: none;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Candidate Attachment</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_download_tem_and_import_attachment','id'=>'frm_download_tem_and_import_attachment')); ?>   
      <div class="modal-body">
        <div class="row">

          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label >Select Client <span class="error"> *</span></label>
            <?php
              echo form_dropdown('clientid_attachment',$clients, set_value('clientid_attachment'), 'class="form-control" id="clientid_attachment"');
              echo form_error('clientid_attachment');
            ?>
          </div>
          
          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>Attach Document</label>
              <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
            <?php echo form_error('attchments'); ?>
           </div>

          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Upload File</label>
            <input type="file" name="cands_bulk_sheet_attachment" id="cands_bulk_sheet_attachment" class="form-control">
          </div>
        </div>
      </div>
     
      
      <div class="modal-footer">
        <button type="button" class="btn btn-info pull-left btn-md" id="download_candidate_template_attachment">Template</button> 
        <button type="submit" class="btn btn-info btn-md" id="import_candidate_template_attachment">Import</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
     </div>

    </div>
  </div>
</div>


<div id="myModelExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm','id'=>'export_to_excel_frm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Candidates Records</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <label>Select Client</label>
              <?php 
                $clients_id["All"] = "All";
                echo form_dropdown('client_id', $clients_id, set_value('client_id'), 'class="form-control" id="client_id"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id', array(), set_value('entity_id'), 'class="form-control" id="entity_id"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
               <label>Select Package</label>
             <select id="package_id" name="package_id" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Status</label>
             <?php
                echo form_dropdown('status_value', $status_value, set_value('status_value'), 'class="form-control" id="status_value"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                 <label>Select Sub Status</label>
               <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_status', $sub_status, set_value('sub_status'), 'class="form-control" id="sub_status"');?>
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

<div id="myModelExportTracker" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm_tracker','id'=>'export_to_excel_frm_tracker')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidates Records Tracker</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <label>Select Client</label>
              <?php 
                $clients_id["All"] = "All";
                echo form_dropdown('client_id_tracker', $clients_id, set_value('client_id_tracker'), 'class="form-control" id="client_id_tracker"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_tracker', array(), set_value('entity_id_tracker'), 'class="form-control" id="entity_id_tracker"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
               <label>Select Package</label>
             <select id="package_id_tracker" name="package_id_tracker" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Status</label>
             <?php
                echo form_dropdown('status_value_tracker', $status_value, set_value('status_value_tracker'), 'class="form-control" id="status_value_tracker"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                 <label>Select Sub Status</label>
               <?php $sub_status_tracker =  $sub_status_tracker[0] = 'Sub Status'; echo form_dropdown('sub_status_tracker', $sub_status_tracker, set_value('sub_status_tracker'), 'class="form-control" id="sub_status_tracker"');?>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export_tracker" name="export_tracker" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelExportPrePost" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm_prepost','id'=>'export_to_excel_frm_prepost')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidates Records Pre Post</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Client</label>
              <?php 
                echo form_dropdown('client_id_prepost', $clients_id_prepost, set_value('client_id_prepost'), 'class="form-control" id="client_id_prepost"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_prepost', array(), set_value('entity_id_prepost'), 'class="form-control" id="entity_id_prepost"');?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
               <label>Select Package</label>
             <select id="package_id_prepost" name="package_id_prepost" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
        
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export_prepost" name="export_prepost" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
var $ = jQuery;

    var status = $('#filter_by_status_candidates').val();
    if(status = "WIP")
    {
      $('#filter_by_status_candidates').css('background-color','Yellow');
    }

$(document).ready(function() {

  var oTable = $('#tbl_datatable').DataTable( {
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
            url: "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>",
            pages: 5,
            async: true,
            method: 'POST',
            data: { 'client_id':function(){return $("#filter_by_clientid_candidates").val(); },'entity':function(){return $("#filter_by_entity_candidates").val(); },'package':function(){return $("#filter_by_package_candidates").val(); },'status':function(){return $("#filter_by_status_candidates").val(); },'sub_status':function(){return $("#filter_by_sub_status_candidates").val(); },'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); } }
            //async: true 

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
     
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"clientname"},{"data":"entity"},{"data":"package"},{"data":"CandidateName"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"overallstatus"},{"data":"WIP"},{"data":"Insufficiency"},{"data":"Closed"},{"data":"Closure_date"},{"data":"Due_date"},{"data":"TAT"},{"data":"Action"}],

        "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {

         
            switch(aData['overallstatus'])
            {
              case "Major Discrepancy" :
              
                 $('td' , nRow).css('background-color', '#ff0000')
                 break;

              case "Minor Discrepancy" :
              
                 $('td' , nRow).css('background-color', '#ff9933')
                 break;

              case "Unable to verify" :
              
                 $('td' , nRow).css('background-color', '#ffff00')
                 break; 

              default :
                 $('td' , nRow).css('background-color', '#ffffff') 
                
            }
        }
  });

 

  /*$('#tbl_datatable thead tr').clone(true).appendTo( '#tbl_datatable thead' );
    $('#tbl_datatable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        alert(title);
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
  */
  $('div.dataTables_filter input').unbind();
  $('div.dataTables_filter input').bind('keyup', function(e) {
     $('#filter_by_status_candidates').val('All');
     
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
    if(data['encry_id'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });

  $('#filter_by_clientid_candidates,#filter_by_status_candidates,#filter_by_sub_status_candidates,#filter_by_entity_candidates,#filter_by_package_candidates').on('change', function(){

    var client_id = $('#filter_by_clientid_candidates').val();
    var status = $('#filter_by_status_candidates').val();
    var sub_status = $('#filter_by_sub_status_candidates').val();
    var entity = $('#filter_by_entity_candidates').val();
    var package = $('#filter_by_package_candidates').val();

   // requestUrl = "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>";
    //alert(requestUrl);
   // requestData = { "client_id": client_id, "status": status , "sub_status": sub_status ,"entity": entity,"package": package};
  
   // var new_url = "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&entity="+entity+"&package="+package;
    oTable.ajax.url("<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&entity="+entity+"&package="+package).load();
  });


  $('#searchrecords').on('click', function() {
    var status = $('#filter_by_status_candidates').val();
    var sub_status = $('#filter_by_sub_status_candidates').val();
    var client_id = $('#filter_by_clientid_candidates').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var entity = $('#filter_by_entity_candidates').val();
    var package = $('#filter_by_package_candidates').val();
    if(start_date > end_date)
    {
      alert('Please select end date greater than start date');
    }
    else
    {
       var new_url = "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&start_date="+start_date+"&end_date="+end_date+"&entity="+entity+"&package="+package;
       oTable.ajax.url(new_url).load();
    }
  });


  $('#btn_reset').on('click', function() {
   $("#filter_by_status_candidates option[value = All]").attr('selected','selected');
    var status = $('#filter_by_status_candidates').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'candidates/cands_view_datatable'; ?>?status="+status;
    oTable.ajax.url(new_url).load();
  });


  $('#download_candidate_template').on('click',function(){
    var client_id = $('#clientid').val();
    
    if(client_id != 0) { 
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'candidates/template_download'; ?>',
          data : 'client_id='+client_id,
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_template').text('downloading..');
              $('#download_candidate_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_template').text('Template');
              //$('#download_candidate_template').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelImport .close").click()
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
    } else {
      show_alert('Select Client','error'); 
      return false;
    }
  });

   $('#download_candidate_template_attachment').on('click',function(){
  
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'candidates/template_download_attachment'; ?>',
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_template').text('downloading..');
              $('#download_candidate_template').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_template').text('Template');
              //$('#download_candidate_template').removeAttr('disabled');                
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


   $('#download_candidate_latest_record').on('click',function(){
    var count_record = $('#count_record_update').val();
    
    if(count_record != 0) { 
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'candidates/export_to_excel_direct'; ?>',
          data : 'count_record='+count_record,
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#download_candidate_latest_record').text('downloading..');
              $('#download_candidate_latest_record').attr('disabled','disabled');
            },
            complete:function(){
              $('#download_candidate_latest_record').text('Download');
              //$('#download_candidate_latest_record').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelImport .close").click()
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
    else {
      show_alert('Please Import File First','error'); 
      return false;
    } 
  });

  $('#frm_download_tem_and_import').validate({ 
      rules: {
        clientid : {
          required : true,
          greaterThan: 0
        },
        cands_bulk_sheet :
        {
           required : true,
           extension: "xls|xlsx"
        },
        entity : {
          required : true,
          greaterThan : 0
        },
        package : {
          required : true,
          greaterThan : 0
        }
      },
      messages: {
        clientid : {
          required : "Enter Client Name"
        },
        entity : {
          required : "Select Entiy",
          greaterThan : "Select Entiy"
        },
        package : {
          required : "Select Package",
          greaterThan : "Select Package"
        },
         cands_bulk_sheet : {
              required : "Select file to upload",
              extension : "Please upload .xlsx file"
            }   
      },
      submitHandler: function(form) 
      {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'candidates/bulk_upload_candidates'; ?>',
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
                  show_alert(message,'success');
                  $('#count_record_update').val(jdata.count);
                 $('#download_candidate_latest_record').show(); 
                  return;
                }
                if(jdata.status == <?php echo ERROR_CODE; ?>){
                  show_alert(message,'error'); 
                }
              }
            });    
      }
  });
   

   $('#frm_download_tem_and_import_attachment').validate({ 
      rules: {
        'attchments[]' : {
          required : true
        },
        clientid_attachment:
        {
           required : true,
           greaterThan : 0
        },
        cands_bulk_sheet_attachment :
        {
           required : true,
           extension: "xls|xlsx"
        }
      },
      messages: {
        'attchments[]' : {
          required : "Insert Attachment"
        },
        clientid_attachment:
        {
           required : "Select Client Name",
           greaterThan : "Select Client Name"
        },
        cands_bulk_sheet_attachment : {
            required : "Select file to upload",
            extension : "Please upload .xlsx file"
          }   
      },
      submitHandler: function(form) 
      {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'candidates/bulk_upload_candidates_attachment'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#import_candidate_template_attachment').attr('disabled','disabled');
              },
              complete:function(){
               // $('#import_candidate_template_attachment').removeAttr('disabled');
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                  show_alert(message,'success');
                  $('#myModelImport').modal('hide');
                  return;
                }
                if(jdata.status == <?php echo ERROR_CODE; ?>){
                  show_alert(message,'error'); 
                }
              }
            });    
      }
  });
 
  $(document).on('change', '#clientid', function(){
  var clientid = $(this).val();

  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity').html(html);
          }
      });
  }
});

$(document).on('change', '#client_id', function(){
  var clientid = $(this).val();

  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id').html(html);
          }
      });
  }
});

$(document).on('change', '#client_id_tracker', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id_tracker').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id_tracker').html(html);
          }
      });
  }
});


$(document).on('change', '#candidate_attachment', function(){
  var candidate_attachment = $(this).val();

  if(candidate_attachment == "candidates")
  {
     $('#candidates_display').show(); 
     $('#attachment_display').hide();  
  }
  if(candidate_attachment == "attachment")
  {
     $('#attachment_display').show(); 
     $('#candidates_display').hide(); 
  }
   if(candidate_attachment == "")
  {
     $('#attachment_display').hide(); 
     $('#candidates_display').hide(); 
  }

});


  $(document).on('change', '#entity', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  });


  $(document).on('change', '#entity_id', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id').html(html);
            }
        });
    }
  });

  $(document).on('change', '#entity_id_tracker', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_id_tracker').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id_tracker').html(html);
            }
        });
    }
  });
  
   
  
  
});

  $('#export_to_excel_frm').validate({ 
        rules: {
          client_id : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id').val();
            var entity = $('#entity_id').val();
            var package = $('#package_id').val();
            var fil_by_status = $('#status_value').val();
            var fil_by_sub_status = $('#sub_status').val();
            var client_name = $('#client_id option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package+'&fil_by_status='+ fil_by_status+'&fil_by_sub_status='+ fil_by_sub_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'candidates/export_to_excel'; ?>',
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
            $("#myModelExport").hide();
            location.reload();
        });     
        }
  });

   $('#export_to_excel_frm_insufficiency').validate({ 
        rules: {
          client_id_insufficiency : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id_insufficiency : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id_insufficiency').val();
            var entity = $('#entity_id_insufficiency').val();
            var package = $('#package_id_insufficiency').val();
            var client_name = $('#client_id_insufficiency option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'candidates/export_to_excel_insufficiency'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export_insufficiency').text('exporting..');
              $('#export_insufficiency').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_insufficiency').text('Export');
             // $('#export_insufficiency').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelExportInsuffciency.close").click()
              $('#export_to_excel_frm_insufficiency')[0].reset();
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
        });     
        }
  });

   $('#export_to_excel_frm_tracker').validate({ 
        rules: {
          client_id_tarcker : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id_tarcker : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id_tracker').val();
            var entity = $('#entity_id_tracker').val();
            var package = $('#package_id_tracker').val();
            var fil_by_status = $('#status_value_tracker').val();
            var fil_by_sub_status = $('#sub_status_tracker').val();
            var client_name = $('#client_id_tracker option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package+'&fil_by_status='+ fil_by_status+'&fil_by_sub_status='+ fil_by_sub_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'candidates/export_to_excel_tracker'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export_tracker').text('exporting..');
              $('#export_tracker').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_tracker').text('Export');
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
            $('#export_to_excel_frm_tracker')[0].reset();
            $("#myModelExportTracker").hide();
            location.reload();
        });     
        }
  });

</script>

<script type="text/javascript">
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

$(document).on('change', '#status_value', function(){
    var status = $("#status_value option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#sub_status').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_status').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_status').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_status').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});

$(document).on('change', '#status_value_tracker', function(){
    var status = $("#status_value_tracker option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#sub_status_tracker').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_status_tracker').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_status_tracker').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_status_tracker').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});



  $('#frm_update_candidate_bulk').validate({ 
      rules: {
         update_candidate_bulk_sheet_attachment : {
          required : true
        }

      },
      messages: {
        update_candidate_bulk_sheet_attachment : {
          required : "Select File"
        }
      },
      submitHandler: function(form) 
      {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'candidates/bulk_update_candidate'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#update_candidate_bulk').attr('disabled','disabled');
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

$('#update_cases_bulk').on('click', function(e){
    var mist_id = $('#case_assign_to_ref_no').val();
    if(mist_id != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'candidates/assign_to_cases/' ?>',
          data : 'mist_id='+mist_id,
          dataType:'json',
          beforeSend :function(){
            jQuery('#update_cases_bulk').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myBluckstopcheck').modal('hide');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('insert ref no');
    }
  }); 

  $('#search_aadhar_pan').on('click', function(e){
    var aadhar_pan_id = $('#aadhar_pan_card').val();
    if(aadhar_pan_id != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'candidates/search_aadhar_card_number' ?>',
          data : 'aadhar_pan_id='+aadhar_pan_id,
          dataType:'json',
          beforeSend :function(){
            jQuery('#search_aadhar_pan').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
            
              $('#cmp_ref_no_show').html(message);
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Enter Aadhar / Pan Card Number');
    }
  }); 
  
</script>
