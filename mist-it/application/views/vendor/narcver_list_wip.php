<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendor - Drugs - WIP - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>narcver/narcver_wip">Drugs WIP</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
          
            </div>
          </div>
      </div>

        <div class="row">
          <div class="col-12">
            <div class="card m-b-20">
              <div class="card-body">
                        
                        <div class="clearfix"></div>
                        
                        <table  id="tbl_datatable"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                               <tr>
                                <th><input type="checkbox" name="allCheckboxSelect" id="allCheckboxSelect" class="form-control"></th>
                                
                                <th>Rec Dt</th>
                                <th>Candidate Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Pincode</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Due Date</th>
                                <th>Mode</th>
                                <th>Client</th>
                                <th>Sub Client</th>
                                <th>Trans Id</th>
                                <th>Component ID</th>
                                <th>Client ID</th>
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
<div id="myModalCasesAssign" class="modal fade" role="dialog" data-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title header_title">Assign Cases</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 form-group users_list">
            <?php echo form_dropdown('vendor_executive_list', $vendor_executive_list, set_value('vendor_executive_list'), 'class="custom-select" id="vendor_executive_list" required="required" ');?>
          </div>
        
        </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-success" id="btn_assign_action">Submit</button>
          
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="myModalCasesedit" class="modal fade" role="dialog" data-backdrop="false">
  <div class="modal-dialog addr modal-lg" >

     <?php echo form_open('#', array('name'=>'update_case','id'=>'update_case')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Drugs Edit</h4>
      </div>
      <div class="modal-body">
      
         <table id='tbl_vendor_log'></table>
       
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_update_case" name="btn_update_case" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script type="text/javascript">

function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}
  
</script>
<script>
var $ = jQuery;
var select_one = [];
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
        "iDisplayLength": 15, // per page
        "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo VENDOR_SITE_URL.'narcver/narcver_view_datatable_assign_wip'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: ""
        } ),
        order: [[ 12, 'asc' ]],
        'columnDefs': [
           {
              "orderable": false,
              'targets': 0,
              'checkboxes': {
                 'selectRow': true
              }
           }
        ],
        'select': {
           'style': 'multi',
        },
        "columns":[{'data' :'checkbox'},{'data' :'created_on'},{"data":"CandidateName"},{'data' :'street_address'},{'data' :'city'},{'data' :'pincode'},{'data' :'state'},{'data' :'status'},{"data":"tat_status"},{'data':'vendor_list_mode'},{'data':'clientname'},{'data':'entity_name'},{'data':'trasaction_id'},{'data':'drug_com_ref'},{'data':"client_ref_no"}]
  });

  $('div.dataTables_filter input').unbind();
  $('div.dataTables_filter input').bind('keyup', function(e) {
     
    if(e.keyCode == 13) {
      var search_value = this.value;
      var search_value_details = $.trim(search_value);
     
      oTable.search( search_value_details ).draw();
      
    }
  });


   $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();


      $('#myModalCasesedit').modal('show');
      $('#myModalCasesedit').addClass("show");
      $('#myModalCasesedit').css({background: "#0000004d"}); 
   
      $.ajax({
      type : 'POST',
      url : '<?php echo VENDOR_SITE_URL.'narcver/view_vendor_logs/' ?>',
      data: 'id='+data['encry_id'],
      beforeSend :function(){
        jQuery('#tbl_vendor_log').html("Loading..");
      },
      success:function(jdata)
      {
        if(jdata != "")
        {
            $('#tbl_vendor_log').html(jdata);

        }
        else
        {
           $('#tbl_vendor_log').html(jdata);
        }  
        }
    }); 

 
  });



 $('#cases_assgin').on('change', function(e){

    var cases_assgin_action = $('#cases_assgin').val();
  
    select_one = oTable.column(0).checkboxes.selected().join(",");
  
   
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        
        $('.vendor_executive_list').show();
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
  });

   $('#btn_assign_action').on('click', function(e){
    var vendor_executive_list = $('#vendor_executive_list').val();
    var vendor_list = $('#vendor_list').val();

    select_one = oTable.column(0).checkboxes.selected().join(",");


    if(vendor_executive_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo VENDOR_SITE_URL.'narcver/assign_to_executive/' ?>',
          data : 'vendor_executive_list='+vendor_executive_list+'&cases_id='+select_one,
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

<script>$('.cls_readonly').prop('disabled',true);</script>

<script>
$(document).ready(function(){

  $('#update_case').validate({
    rules : { 
      transaction_id : {
        required : true 
      },
      vendor_remark : {
        required : {
          depends: function () {
            if( $('#status').val() != "wip"){
              return true;
            }
          }

        }
        
      },
      "attchments_file[]" : {
        filesize: 2000000
      }

    },
    messages: {
      transaction_id : {
        required : "Enter Enter tansaction ID"
      },
      "attchments_file[]" : {
          filesize : "File size must be less than 2 MB."
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'narcver/update_narcver_wip'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update_case').attr('disabled',true);
        },
        complete:function(){
          $('#btn_update_case').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  
 $.validator.addMethod('filesize', function (value, element, arg) {
    var attachment_count = ($("#attchments_file")[0].files.length); 
    var status = 2;  
    for(var i = 0; i < attachment_count; i++)
    {
      
      var file_size = element.files[i].size;
      if(arg<file_size){
        status = 1;
        break;
      }else{
        status = 2; 
        continue;
      }
    }
    if(status == 2)
    {
      return true;
    }
    else{

      return false;
    }

  });


});
</script>

