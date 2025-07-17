<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="content">
                  
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
                         <div class="filterable">
                        <table class="table table-hover dataTable no-footer" id="tbl_datatable1" >
                            <thead>
                               <tr class="filters">
                                <th><input name="select_all" value="1" id="chk_datatable" class="chk_datatable" type="checkbox" /></th>
                                
                                <th><input type="text" class="form-control" placeholder="Rec Dt" size ="3"></th>
                           
                               	<th><input type="text" class="form-control" placeholder="Candidate Name" size ="3"></th>
                               	<th><input type="text" class="form-control" placeholder="Address" size ="3"></th>
                              	<th><input type="text" class="form-control" placeholder="City" size ="3" ></th>
                                <th><input type="text" class="form-control" placeholder="Pincode" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="State" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Status" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Executive" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="TAT" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Due Date" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Mode" size ="3"></th>
                               <!-- <th><input type="text" class="form-control" placeholder="Client" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Sub Client" size ="3"></th>-->
                                <th><input type="text" class="form-control" placeholder="Trans Id" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Component ID" size ="3"></th>
                                <th><input type="text" class="form-control" placeholder="Client ID" size ="3"></th>
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
            <?php echo form_dropdown('vendor_executive_list', $vendor_executive_list, set_value('vendor_executive_list'), 'class="form-control" id="vendor_executive_list" required="required" ');?>
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
  <div class="modal-dialog addr" >

     <?php echo form_open('#', array('name'=>'update_case','id'=>'update_case')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">PCC Edit</h4>
      </div>
      <div class="modal-body">
        <div class="row">
   
          
       <table id='tbl_vendor_log' ></table>

        
        </div>
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
    
      $('#btn_decline').on('click', function(){
       
       $('.reject_reason').show();
        $('#btn_decline').hide();

      
  });
      $('#chk_datatable').on('click', function() {
        if (this.checked == true)
            $('#tbl_datatable1').find('input[name="case_id"]').prop('checked', true);
        else
            $('#tbl_datatable1').find('input[name="case_id"]').prop('checked', false);
    });

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
          "processing": jQuery('.body_loading').show()
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo VENDOR_SITE_URL.'crimver/crimver_view_datatable_assign_closed'; ?>',
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
        "columns":[{'data' :'checkbox'},{'data' :'created_on'},{"data":"CandidateName"},{'data' :'street_address'},{'data' :'city'},{'data' :'pincode'},{'data' :'state'},{'data' :'status'},{'data' :'vendor_executive_id'},{'data' :'test2'},{"data":"tat_status"},{'data':'vendor_list_mode'},{'data':'trasaction_id'},{'data':'pcc_com_ref'},{'data':"client_ref_no"}]
  });

   $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();

   
      $('#myModalCasesedit').modal('show');
   
      $.ajax({
      type : 'POST',
      url : '<?php echo VENDOR_SITE_URL.'crimver/view_vendor_logs/' ?>',
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


/*$('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();

   //alert(data['encry_id']);
     
   // if(data['edit_access'] != 0)
    window.location = data['encry_id'];
 

  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });*/
   /*$(document).on('click', '#btn_assign', function(){
    

    var reject_reason = $('#reject_reason').val();


    //$.each($("input[name='cases_id[]']:checked"), function(){            
   //     select_one.push($(this).val());
   // });
  //  var ids = [];
$('.case_id:checked').each(function(i, e) {
    select_one.push($(this).val());
});

// select_one = oTable.column(0).checkboxes.selected().join(",");

    if(select_one != "")
    { 

      if(reject_reason == "")
        {
     
        show_alert('Please insert reject reason','error'); 
        
        }
       else
       {

        $.ajax({
          type:'POST',
          url:'<?php echo VENDOR_SITE_URL.'addrver/reject_vendor_address/' ?>',
          data : 'cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#btn_assign').text("loading...");
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
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
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
});
 */
 $('#cases_assgin').on('change', function(e){

    var cases_assgin_action = $('#cases_assgin').val();
   
   

    $('.case_id:checked').each(function(i, e) {
    select_one.push($(this).val());
});
   
    if(cases_assgin_action != 0 && select_one != "")
    {
      
      if(cases_assgin_action == 1) 
      {
        
        $('.vendor_executive_list').show();
        $('.header_title').text('Assign Executive');
      }
      
      $('#myModalCasesAssign').modal('show');

      var form = this;
      var rows_selected =   $('.case_id:checked').each(function(i, e) {
      select_one.push($(this).val()); });

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

     $('.case_id:checked').each(function(i, e) {
      select_one.push($(this).val());
     });


    if(vendor_executive_list != '0'  && select_one != "")
    {
        $.ajax({
          type:'POST',
          url:'<?php echo VENDOR_SITE_URL.'crimver/assign_to_executive/' ?>',
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
    var new_url = "<?php echo VENDOR_SITE_URL.'crimver/crimver_view_datatable_assign_closed'; ?>?start_date="+start_date+"&end_date="+end_date;
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
        url : '<?php echo VENDOR_SITE_URL.'crimver/update_crimver_insufficiency'; ?>',
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