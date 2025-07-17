<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Employment - Employment AQ</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment/approval_queue">Employment AQ</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
            </div>
          </div>
    </div>

    <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified">

        <?php   

          echo "<li class='nav-item waves-effect waves-light active' role='presentation'><a class = 'nav-link active' href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Vendor Assign/ Reject</a></li>";
 
          //echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."employment/vendor_cost_approval/' data-can_id='1' data-tab_name='vendor_cost_approve'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#vendor_cost_approve' aria-controls='home' role='tab' data-toggle='tab'>Vendor Charges</a></li>";


          echo "<li role='presentation' data-url='".ADMIN_SITE_URL."employment/employment_closure_entries/' data-can_id='".$this->user_info['id']."' data-tab_name='result_log_tabs'  class='nav-item waves-effect waves-light  view_component_tab'><a href='#result_log_tabs'  class = 'nav-link' aria-controls='home' role='tab'  data-toggle='tab'>Closure</a></li>";

        echo "<li role='presentation' data-url='".ADMIN_SITE_URL."employment/employment_closure_entries_vendor_insuff/'  data-can_id='".$this->user_info['id']."' data-tab_name='vendor_insuff_tab'  class='nav-item waves-effect waves-light view_component_tab'><a href='#vendor_insuff_tab'  class = 'nav-link' aria-controls='home' role='tab'  data-toggle='tab'>Vendor Insuff</a></li>";

         ?>                        
       </ul>
    </div>
  
    
  <div class="tab-content">
    <div id="Vendor_assign_reject" class="tab-pane active">
      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
            
              <div class="col-sm-2 form-group">
              <?php echo form_dropdown('cases_assgin', $assigned_option, set_value('cases_assgin'), 'class="select2" id="cases_assgin"');?>
              </div>
              <div class="col-sm-2 form-group reject_reason" style="display: none;">
              <input type="text" name="reject_reason" maxlength="200" id="reject_reason" placeholder="Reason" class="form-control">
               <?php echo form_error('reject_reason_error'); ?>
              </div>
              <div class="col-sm-2">
               <button type="button" class="btn btn-info btn-md" name="btn_assign" id="btn_assign">Submit</button>
              </div>
              </div>
              
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th><input name="select_all" value="1" id="example-select-all" class="example-select-all" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>Comp Ref No</th>
                    <th>Client Name</th>
                    <th>Entity</th>
                    <th>Package</th>
                    <th>Vendor Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>Allocated By</th>
                    <th>Allocated Date</th>
                  </tr>
                </thead>
                <tbody>
                  
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  <div id="vendor_cost_approve" class="tab-pane fade in">
 
  </div>


     <div id="result_log_tabs" class="tab-pane fade in">

     </div>
     <div id="vendor_insuff_tab" class="tab-pane fade in">

     </div>

</div>
 
</div>
</div>
</div>
  
<div id="fieldVisitModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >
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

<div id="insuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insuff</h4>
      </div>
      <div class="modal-body">

          <div id="append_vendor_model_insuff"></div>

      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
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
            url: "<?php echo ADMIN_SITE_URL.'employment/view_approval_queue'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { }
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{"data":"emp_com_ref"},{"data":"clientname"},{"data":"entity_name"},{"data":"package_name"},{'data' :'vendor_name'},{'data' :'locationaddr'},{'data' :'citylocality'},{'data' :'pincode'},{'data' :'allocated_by'},{"data":"allocated_on"}]
  });
  $('#cases_assgin').on('change', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    (cases_assgin_action == 1) ? $('.reject_reason').hide() : $('.reject_reason').show();
  });

  $('#cases_assgin_closure').on('change', function(){
    var cases_assgin_action = $('#cases_assgin_closure').val();

    (cases_assgin_action == 1) ? $('.reject_reason_closure').hide() : $('.reject_reason_closure').show();
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
  } );

    $('#tbl_datatable').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });  


  /* $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });
  */

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    var url = data['encry_id'];
    $('#append_field_visit_frm').load(url,function(){
      $('#fieldVisitModel').modal('show');
      $('#fieldVisitModel').addClass("show");
      $('#fieldVisitModel').css({background: "#0000004d"});
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
              //  $('#add_field_visit').removeAttr('disabled',false);
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

$(document).on('click', '#btn_assign', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    var reject_reason = $('#reject_reason').val();

   select_one = oTable.column(0).checkboxes.selected().join(",");

    if(cases_assgin_action != 0 && select_one != "")
    { 
      if(cases_assgin_action == 2 && reject_reason == "")
      {
     
        show_alert('Please insert reject reason','error'); 
        
      }
      else
      {

        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/employment_final_assigning/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign').text('Processing...');
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

 $(document).on('click', '#btn_assign_closure', function(){
    var cases_assgin_action = $('#btn_assign_closure').val();
    var cases_assgin_action1 = $('#cases_assgin_closure').val();
    var reject_reason = $('#reject_reason_closure').val();

 

    var selected_id = new Array();
       $("input[name='closure_id']:checked").each(function(i) {
    selected_id.push($(this).val());
});

 //select_one = oTable.column(0).checkboxes.selected().join(",");
 
    if(cases_assgin_action != 0 && selected_id != "")
    { 

      if(cases_assgin_action == 2 && reject_reason == "")
        {
     
        show_alert('Please insert reject reason','error'); 
        
        }
       else
       {

        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/employment_closure/' ?>',
          data : 'action='+cases_assgin_action1+'&closure_id='+selected_id+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#btn_assign_closure').text("loading...");
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one1 = [];
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
      $("#cases_assgin_closure option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
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

   
});
</script>

<script type="text/javascript">

   $('#frm_insuff_raise').validate({ 
    rules: {
      update_id : {
        required : true
      },
      insff_reason : {
        required : true,
        greaterThan : 0
      },
      txt_insuff_raise : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      insff_reason : {
        required : "Select Reason",
        greaterThan : "Select Reason"
      },
      txt_insuff_raise : {
        required : "Select Insuff Date"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/insuff_raised'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_insuff').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffRaiseModel').modal('hide');
            $('#frm_insuff_raise')[0].reset();
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


  $('#view_result_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'employment/employment_closure_entries/' ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_activity_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_activity_log').html(message);
        }
        else {
          $('#tbl_activity_log').html(message);
        }     
        var tbl_activity_log =  $('#tbl_activity_log').DataTable( { scrollX: true,"paging": true,  "processing": true,  "searching": false, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });
      }
    }); 
  })
</script>
