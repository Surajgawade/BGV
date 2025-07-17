<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content-page">
<div class="content">
<div class="container-fluid">

        <div class="row">
          <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendor - Employment - Closed - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>empver/empver_closed">Employment Closed</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
          
              </div>
            </div>
          </div>
                        
        <div class="row">
          <div class="col-12">
            <div class="card m-b-20">
              <div class="card-body">   
                <div class="row">

                      <div class="col-md-2 col-sm-12 col-xs-3 form-group">
                        <input type="date" name="start_date" id="start_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                      </div>
                                             
                      <div class="col-md-2 col-sm-12 col-xs-3 form-group">
                          <input type="date" name="end_date" id="end_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
                      </div>

                      <div class="col-md-4 col-sm-12 col-xs-2 form-group">
                         <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                         <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                      </div>
                      </div>
                    
                        <div class="clearfix"></div>
                         <table  id="tbl_datatable1"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                              <tr>
                                <th><input name="select_all" value="1" id="chk_datatable" class="chk_datatable" type="checkbox" /></th>
                                
                                <th>Rec Dt</th>
                                <th>Candidate Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Pincode</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Executive</th>
                                <th>TAT</th>
                                <th>Due Date</th>
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
        <h4 class="modal-title header_title">Assign Cases</h4>
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
        <h4 class="modal-title header_title">Employment Edit</h4>
      </div>
      <div class="modal-body">
        
       <table id='tbl_vendor_log' ></table>

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
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo VENDOR_SITE_URL.'empver/employment_view_datatable_assign_closed'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 3, 'desc' ]],
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
         "columns":[{'data' :'checkbox'},{'data' :'created_on'},{"data":"CandidateName"},{'data' :'locationaddr'},{'data' :'citylocality'},{'data' :'pincode'},{'data' :'state'},{'data' :'status'},{'data' :'vendor_executive_id'},{'data' :'test2'},{"data":"tat_status"},{'data':'trasaction_id'},{'data':'emp_com_ref'},{'data':"client_ref_no"}]
  });

   $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();

  
      $('#myModalCasesedit').modal('show');
      $('#myModalCasesedit').addClass("show");
      $('#myModalCasesedit').css({background: "#0000004d"}); 
   
      $.ajax({
      type : 'POST',
      url : '<?php echo VENDOR_SITE_URL.'empver/view_vendor_logs/' ?>',
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
          url:'<?php echo VENDOR_SITE_URL.'empver/assign_to_executive/' ?>',
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


   $('#searchrecords').on('click', function() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    if(start_date > end_date)
    {
      alert('Please select end date greater than start date');
    }
    else
    {
    var new_url = "<?php echo VENDOR_SITE_URL.'empver/employment_view_datatable_assign_closed'; ?>?start_date="+start_date+"&end_date="+end_date;
    oTable.ajax.url(new_url).load();
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
        
      }
     
    },
    messages: {
    
      transaction_id : {
        required : "Enter Enter tansaction ID"
      }
    },
    submitHandler: function(form) 
    {


      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'empver/update_employment_closed'; ?>',
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
            window.location = jdata.redirect;
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
</script>
