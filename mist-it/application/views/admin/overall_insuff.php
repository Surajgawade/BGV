
<?php $access_insuff_clear = ($this->permission['access_candidates_overall_insuff_clear']) ? '#insuffClearModel'  : ''; ?>
<div class="content-page">
 <div class="content">
  <div class="container-fluid">

     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Candidates - All Components Insufficiency</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                      <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidates</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>Overall_insufficiency">Insufficiency</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
          
            </div>
          </div>
    </div>
      
   <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified"> 
        
           <?php  
            echo "<li class='nav-item waves-effect waves-light res active'  role='presentation' data-tab_name='candidate_wise' ><a class = 'nav-link active' href='#candidate_wise' aria-controls='home' data-toggle='tab'>Candidate Wise</a></li>";
            
            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."Overall_insufficiency/get_component_insufficiency/'  data-tab_name='component_wise'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#component_wise' aria-controls='home' role='tab'  data-toggle='tab'>Component Wise</a></li>";
   
         ?>                        
       </ul>
    </div>   
     
   <div class="tab-content">
      <div id="candidate_wise" class="tab-pane active">

       <div class="row">
         <div class="col-12">
           <div class="card m-b-20">
              <div class="card-body">
               
               <form>
                <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <?php echo form_dropdown('clientid_candidate', $clients, set_value('clientid_candidate'), 'class="select2" id="clientid_candidate"');?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <?php echo form_dropdown('entity_candidate',array(), set_value('entity_candidate'), 'class="select2" id="entity_candidate"');?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <select id="package_candidate" name="package_candidate" class="select2"><option value="0">Select</option></select>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <input type="button" name="searchrecords_candidate" id="searchrecords_candidate" class="btn btn-md btn-info" value="Filter">
                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
                </div>
                </form>

                <?php $access_import_export_insuff = ($this->permission['access_candidates_list_export']) ? '#myModelExportInsuffciency'  : '';  ?> 
                <button class="btn btn-secondary waves-effect" data-toggle="modal" data-target="<?php echo $access_import_export_insuff;?>"><i class="fa fa-download"></i> Export Insufficiency</button>
             <br>
             <br>
                <table id="tbl_datatable1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                        <tr >
                          <th>Sr No</th>
                            <th>Case Received Date</th>
                            <th>Client Name</th>
                            <th>Entity</th>
                            <th>Package</th>
                            <th>Candidate Name</th>
                            <th>Client Ref No</th>
                            <th><?php echo REFNO; ?></th>
                            <th>Overall Status</th>
                            <th>Insufficiency</th>
                            
                            <th>Remarks</th>
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


    <div id="component_wise" class="tab-pane fade in">
     
    </div>


      </div>
</div>
</div>    
</div>
<div id="insuffClearModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open_multipart("#", array('name'=>'frm_insuff_clear','id'=>'frm_insuff_clear')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insuff Clear</h4>
      </div>
      <div class="modal-body">
        <span class="errorTxt"></span>
        <input type="hidden" name="check_insuff_raise" id="check_insuff_raise" value="<?php echo set_value('check_insuff_raise'); ?>">
        <input type="hidden" name="controller" id="controller" value="">
        <input type="hidden" name="clear_clientid" id="clear_clientid" value="<?php echo set_value('clientid'); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="">
        <input type="hidden" name="CandidateName" id="CandidateName" value="">
        <input type="hidden" name="component_ref_no" id="component_ref_no" value="">
        <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Clear Date<span class="error"> *</span></label>
          <input type="text" name="insuff_clear_date" id="insuff_clear_date" value="<?php echo set_value('insuff_clear_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('insuff_clear_date'); ?>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-8 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_remarks" rows="1" maxlength="500" id="insuff_remarks"  class="form-control"><?php echo set_value('insuff_remarks'); ?></textarea>
          <?php echo form_error('insuff_remarks'); ?>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error"> *</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
        </div>
      </div>  
      <div class="modal-footer">
        <button type="submit" id="btn_insuff_clear" name="btn_insuff_clear" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="myModelExportInsuffciency" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm_insufficiency','id'=>'export_to_excel_frm_insufficiency')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Insufficiency Report</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
            <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
              <label>Select Client</label>
              <?php 
              $clients_id["All"] = "All";
               echo form_dropdown('client_id_insufficiency', $clients_id, set_value('client_id_insufficiency'), 'class="select2" id="client_id_insufficiency"');?>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_insufficiency', array(), set_value('entity_id_insufficiency'), 'class="select2" id="entity_id_insufficiency"');?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
               <label>Select Package</label>
             <select id="package_id_insufficiency" name="package_id_insufficiency" class="select2"><option value="0">Select Package</option></select>
            </div>
           </div>
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export_insufficiency" name="export_insufficiency" class="btn btn-success"> Export Insufficiency</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script>

$(function () 
{
  $('#datatable-insuff_view').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      "searching": false,
      scrollX: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },
      "lengthChange": true,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

  $('#frm_insuff_clear').validate({ 
    rules: {
      clear_update_id : {
        required : true
      },
      controller : {
        required : true
      },
      insuff_clear_date : {
        required : true
      },
      insuff_remarks : {
        required : true
      }
    },
    messages: {
      clear_update_id : {
        required : "Update ID missing"
      },
      controller : {
        required : "access URL"
      },
      insuff_clear_date : {
        required : "Select Insuff CLear Date"
      },
      insuff_remarks : {
        required : "Enter remarks"
      }
    },
    submitHandler: function(form) 
    {      
        var controller = $('#controller').val();
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL; ?>'+controller+'/insuff_clear',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_insuff_clear').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_insuff_clear').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffClearModel').modal('hide');
            $('#frm_insuff_clear')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

});

$(document).on('click', '.tbl_row_edit', function(){
    var accessUrl = $(this).attr('data-accessUrl');
    var controller = $(this).data('controller');
    var clientid = $(this).data('clientid');
    var component_id = $(this).data('id');
    var candsid = $(this).data('candsid');
    var candsname = $(this).data('candsname');
    var componentrefno = $(this).data('componentrefno');
    
   
    if(controller != "" && accessUrl)
    {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL; ?>'+controller+'/insuff_raised_data',
          data : 'insuff_data='+accessUrl,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
          },
          complete:function(){
              jQuery('.body_loading').hide();
          },
          success:function(jdata) {

            $('#insuff_clear_id').val(accessUrl); 
            $('#clear_update_id').val(component_id); 
            $('#controller').val(controller);
            $('#component_ref_no').val(componentrefno);
            $('#CandidateName').val(candsname);
            $('#clear_clientid').val(clientid);
            $('#candidates_info_id').val(candsid);
            $('#check_insuff_raise').val(jdata['insuff_raised_date']);
            $('#insuffClearModel').modal('show');
            $('#insuffClearModel').addClass("show");
            $('#insuffClearModel').css({background: "#0000004d"});
          }
      });
    } else { 
      show_alert('Access Denied, You don’t have permission to clear insuff');
    }
});

$(document).on('click','.clkInsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    var clientname = $(this).text();
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'address/insuff_edit_clear_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        beforeSend :function(){
          jQuery(this).text("loading...");
        },success:function(html) {
          $('#appedEditIsuffClear').html(html);
          $('#editInsuffRaiseModel').modal('show');   
        }
    });
  }
});

$(document).on('change', '#clientid_candidate', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
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
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
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

$(document).on('change', '#client_id_insufficiency', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id_insufficiency').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id_insufficiency').html(html);
          }
      });
  }
});


 $(document).on('change', '#entity_id_insufficiency', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_id_insufficiency').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id_insufficiency').html(html);
            }
        });
    }
  });

</script>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
var $ = jQuery;
var select_one = '';
$(document).ready(function() {

  var oTable =  $('#tbl_datatable1').DataTable( {
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
          "processing": jQuery('.body_loading').show()
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'candidates/data_table_cands_view_insufficiency'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'clientid':function(){return $("#clientid_candidate").val(); },'entity':function(){return $("#entity_candidate").val(); },'package':function(){return $("#package_candidate").val(); } }
        } ),
     //   order: [[ 3, 'desc' ]],
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
       "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"ClientName"},{"data":"Entity"},{"data":"Package"},{"data":"CandidateName"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"overallstatus"},{'data':'Insufficiency'},{"data":"remarks"},{"data":"Action"}]

  });

  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
       $('#filter_by_status').val('All');
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  $('#clientid_candidate,#entity_candidate,#package_candidate').on('change', function(){
   
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'candidates/data_table_cands_view_insufficiency'; ?>?clientid="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords_candidate').on('click', function() {
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();

  
    var new_url = "<?php echo ADMIN_SITE_URL.'candidates/data_table_cands_view_insufficiency'; ?>?clientid="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
   
  });

 });
</script>