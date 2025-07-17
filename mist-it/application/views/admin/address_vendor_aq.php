<div class="content-page">
  <div class="content">
    <div class="container-fluid">
  
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Address - Address AQ</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>address">Address</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>address/approval_queue">Address AQ</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            </div>
          </div>
    </div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified">
          <?php 

            echo "<li class='nav-item waves-effect waves-light active' role='presentation'><a class = 'nav-link active' href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Vendor Assign/ Reject</a></li>";

            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."address/address_digital_entries/' data-can_id='".$this->user_info['id']."'  data-tab_name='digital_log_tabs'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#digital_log_tabs' aria-controls='home' role='tab'  data-toggle='tab'>Digital</a></li>";
 


            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."address/address_closure_entries/' data-can_id='".$this->user_info['id']."'  data-tab_name='result_log_tabs'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#result_log_tabs' aria-controls='home' role='tab'  data-toggle='tab'>Closure</a></li>";

            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."address/address_closure_entries_vendor_insuff/'  data-can_id='".$this->user_info['id']."'  data-tab_name='vendor_insuff_tab'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#vendor_insuff_tab' aria-controls='home' role='tab'  data-toggle='tab'>Vendor Insuff</a></li>";

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
             <!-- <input type="button" name="btn_assign" id="btn_assign" class="btn btn-info btn-md">-->
              </div>
             
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr >
                    <th><input name="select_all" value="1" id="example-select-all" class="example-select-all" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>Comp Ref No</th>
                    <th>Mode Of Verification</th>
                    <th>Client Name</th>
                    <th>Entity</th>
                    <th>Package</th>
                    <th>Vendor Name</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>State</th>
                    <th>Allocated By</th>
                    <th>Allocated Date</th>
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

     <div id="digital_log_tabs" class="tab-pane fade in">

     </div>

     <div id="result_log_tabs" class="tab-pane fade in">

     </div>
     <div id="vendor_insuff_tab" class="tab-pane fade in" >

     </div>

   </div>
 
</div>
</div>
</div>


<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result','id'=>'add_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" id="candidates_info_id" name="candidates_info_id" value="<?php echo set_value('candidates_info_id'); ?>">
      <input type="hidden" id="entity_id" name="entity_id" value="<?php echo set_value('entity_id'); ?>">
      <input type="hidden" id="package_id" name="package_id" value="<?php echo set_value('package_id'); ?>">
      <input type="hidden" id="tbl_addrver" name="tbl_addrver" value="<?php echo set_value('tbl_addrver'); ?>">
      <input type="hidden" id="clientid"  name="clientid" value="<?php echo set_value('clientid'); ?>">
      <input type="hidden" id="addrverres_id" name="addrverres_id" value="<?php echo set_value('addrverres_id'); ?>">

      <div id="append_result_model"></div>
      <input type="hidden" name="attchments_ver" id="attachment_list" value=""> 

      <div class="modal-footer">
      <button  class="btn btn-info btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'address/address_result_attachment/' ?>" data-toggle="modal" id="arr_attachment" type="button" >Attachment</button>
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="add_attachment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_attachment','id'=>'add_verificarion_attachment')); ?>
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Attchment</h4>
      </div>
      <span class="errorTxt"></span>
       
      <div id="append_add_attachment"></div>

      
      <div class="modal-footer">
     
        <button type="submit" id="sbattachment" name="sbattachment" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
      <input type="hidden" name="tbl_addrver" id = "tbl_addrver"  class = "tbl_addrver" value="">
      <input type="hidden" name="fin_status"  id = "fin_status"  class="fin_status" value="">
      <input type="hidden" name="closure_id"  id = "closure_id"  class="closure_id" value="">

      <span class="errorTxt"></span>
      <div class="modal-body">
      <div id="append_vendor_model"></div>

       <div class="tab-pane" id="tab_vendor_log1">
              <table id="tbl_vendor_log1" class="table table-bordered datatable_logs"></table>
            </div>
      </div>
      <div class="modal-footer">
      <button type="button" id="vendor_details_back" name="vendor_details_back" class="btn btn-default btn-sm pull-left">Back</button>
      <div class="showbutton">
      <button type="submit" id="vendor_result_submit" name="vendor_result_submit" class="btn btn-success btn-sm">Save</button>
      <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

     </div>
     <div class="showbutton1" style="display: none;">

        <button  class="btn btn-success btn-sm approve_reject_id" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_vendor_form/1/' ?>" data-toggle="modal" id="activityModelClk1" type="button" >Approve</button>
      <button  class="btn btn-danger btn-sm approve_reject_id" data-toggle="modal" data-target="#reject_value"  type="button" >Reject</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

     </div>

      </div>
    </div>
    <?php echo form_close(); ?>
     
  </div>
</div>

<div id="reject_value" class="modal fade" role="dialog" style="z-index: 2000;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
        
       <input type="text" name="reject_reason_closure" maxlength="200" id="reject_reason_closure" placeholder="Reason" class="form-control">
       <br>

        <button type="button" id="reject" name="reject" class="btn btn-danger btn-sm" value = '2'>Reject</button>
        
      </div>      
    </div>
  </div>
</div>

<div id="insuffRaiseModelVendor" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_insuff','id'=>'add_vendor_details_view_insuff')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
    
      <span class="errorTxt"></span>
      <div class="modal-body">
      <div id="append_vendor_model_insuff_details"></div>
      </div>
      <div class="modal-footer">
      <button type="button" id="vendor_details_back_insuff" name="vendor_details_back_insuff" class="btn btn-default btn-sm pull-left">Back</button>

      <button  class="btn btn-success btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'address/check_insuff_already_raised/' ?>" data-toggle="modal" id="vendor_result_submit_insuff" type="button" >Approve</button>

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



<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="1">
            <input type="hidden" name="acti_candsid" id = "acti_candsid" value="<?php echo set_value('acti_candsid'); ?>">

            <input type="hidden" name="comp_table_id"  id = "comp_table_id" value="<?php echo set_value('comp_table_id'); ?>">
            <input type="hidden" name="ac_ClientRefNumber" id = "ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber'); ?>">

            <input type="hidden" name="CandidateName"  id="CandidateName1" value="<?php echo set_value('CandidateName'); ?>" class="form-control">
            <input type="hidden" name="component_ref_no" id="component_ref_no1" value="<?php echo set_value('component_ref_no'); ?>">
            
            <div class="append-activity_view"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
      <div class="append-activity_records"></div>
    </div>
  </div>
</div>



<div id="showvendorModelDigitalView" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_digital_entries_view','id'=>'add_vendor_details_view_digital_entries_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Digital Address Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body">
      <div id="append_vendor_model_digital_entries_view"></div>
      </div>
      <div class="modal-footer">
      <button type="button" id="vendor_details_back_digital_entries_view" name="vendor_details_back_digital_entries_view" class="btn btn-default btn-sm pull-left">Back</button>
      <button  class="btn btn-success btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_address_verification/1/' ?>" data-toggle="modal" id="activityModelClkFollow" type="button" >Activity</button>
      </div>
    </div>
    <?php echo form_close(); ?>
     
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
  
<script type="text/javascript">
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
            url: "<?php echo ADMIN_SITE_URL.'address/view_approval_queue'; ?>",
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{"data":"add_com_ref"},{"data":"mode_of_verification"},{"data":"clientname"},{"data":"entity"},{"data":"package"},{'data' :'vendor_name'},{'data' :'city'},{'data' :'pincode'},{'data' :'state'},{'data' :'allocated_by'},{"data":"allocated_on"}]
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

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });
   
 $(document).on('click', '#btn_assign', function(){
    var cases_assgin_action = $('#cases_assgin').val();

    var reject_reason = $('#reject_reason').val();


  //  $.each($("input[name='cases_id[]']:checked"), function(){            
  //      select_one.push($(this).val());
  //  });

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
          url:'<?php echo ADMIN_SITE_URL.'address/address_final_assigning/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign').text('Processing...');
            //jQuery('#btn_assign').text("loading...");
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


/* $(document).on('click', '#btn_assign_closure', function(){
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
          url:'<?php echo ADMIN_SITE_URL.'address/address_closure/' ?>',
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
});*/

  $(document).on('click', '#approve', function(){
     var cases_assgin_action = $('#approve').val();
     var selected_id = new Array();
     var selected_id = $('#closure_id').val();  


    if(cases_assgin_action != 0 && selected_id != "")
    { 
  
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/address_closure/' ?>',
          data : 'action='+cases_assgin_action+'&closure_id='+selected_id,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#approve').text("loading...");
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
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });   
    } 
});

$(document).on('click', '#reject', function(){
     var cases_assgin_action = $('#reject').val();
     var selected_id = $('#closure_id').val();  
     var reject_reason = $('#reject_reason_closure').val();  

   
    if(cases_assgin_action != 0 && selected_id != "")
    { 

      if(reject_reason == "")
      {
     
       show_alert('Please insert reject reason','error'); 
        
      }
      else
      {  
  
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/address_closure/' ?>',
          data : 'action='+cases_assgin_action+'&closure_id='+selected_id+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#reject').text("loading...");
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
              $('#reject_value').modal('hide');
              $('#showvendorModel').modal('hide');
              $('.view_component_tab a.active').click();
              
            }else {
              show_alert(message,'error'); 
            }
          }
        }); 
      }  
    } 
}); 




});


$('#activityModelClk1').click(function(){
    var url = $(this).data("url");
    var id = $('#address_id').val();
    var component = 'address';


    $.ajax({
      type: "POST",
      url:'<?php echo ADMIN_SITE_URL.'address/check_closure_status_digital/' ?>'+id,
      dataType: "html",
      success:function(data){

        if(data == "success")
        {

          $('.append-activity_view').load(url+id+"/"+component,function(){});
          $('#activityModel').modal('show');
          $('#showvendorModel').modal('hide');
          $('#activityModel').addClass("show");
          $('#activityModel').css({background: "#0000004d"});

        }
        else
        {
           alert('Kindly close digital verification first');
        }  
      }
      });
  });




$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    var action2 = $("#action option:selected").text();
    var action1 =  action2.replace(/\s+/g,'');
    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url+"/"+action1,function(){
      $('#addAddResultModel').modal('show');
      $('#addAddResultModel').addClass("show");
      $('#addAddResultModel').css({background: "#0000004d"});
    });
  }
});

/*  $(document).on('click','.activityModelClk1',function() {
alert('hii');
    var url = $(this).data('url');
 
    $('.append-activity_view').load(url,function(){
      $('#activityModel').modal('show');
      $('#showvendorModel').modal('hide');
    });
   
});*/
$(document).on('click','#activityModelClk1',function() {
 
 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var entity_id  = $('#entity_id1').val();
 var package_id  = $('#package_id1').val();
 var clientid  = $('#clientid1').val();
 var addrverres_id  = $('#addrverres_id1').val();

 document.getElementById("tbl_addrver").value = component_id; 
 document.getElementById("candidates_info_id").value = CandidateID; 
 document.getElementById("entity_id").value = entity_id; 
 document.getElementById("package_id").value = package_id; 
 document.getElementById("clientid").value = clientid; 
 document.getElementById("addrverres_id").value = addrverres_id; 
   
});


$('#activityModelClk1').click(function(){
 
 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var ClientRefNumber  = $('#ClientRefNumber').val();
 var CandidateName  = $('#CandidateName').val();
 var component_ref_no  = $('#component_ref_no').val();
 document.getElementById("comp_table_id").value = component_id; 
 document.getElementById("acti_candsid").value = CandidateID; 
 document.getElementById("ac_ClientRefNumber").value = ClientRefNumber; 
 document.getElementById("CandidateName1").value = CandidateName; 
 document.getElementById("component_ref_no1").value = component_ref_no; 



 //$('#comp_table_id') = component_id.val();
 
});

$('#activityModelClkFollow').click(function(){
 
 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var ClientRefNumber  = $('#ClientRefNumber').val();
 var CandidateName  = $('#CandidateName').val();
 var component_ref_no  = $('#component_ref_no').val();
 document.getElementById("comp_table_id").value = component_id; 
 document.getElementById("acti_candsid").value = CandidateID; 
 document.getElementById("ac_ClientRefNumber").value = ClientRefNumber; 
 document.getElementById("CandidateName1").value = CandidateName; 
 document.getElementById("component_ref_no1").value = component_ref_no; 



 //$('#comp_table_id') = component_id.val();
 
});


$('#vendor_result_submit_insuff').click(function(){
  
    var id =  $('#address_id').val();;
    var url = $(this).data('url');
   
    $('#append_vendor_model_insuff').load(url+id,function(){
    $('#insuffRaiseModelVendor').modal('hide');
    $('#insuffRaiseModel').modal('show');
    $('#insuffRaiseModel').addClass("show");
    $('#insuffRaiseModel').css({background: "#0000004d"});
 });
});

$('.approve_reject_id').click(function(){
 var cl_id  = $('#cl_id').val();

 document.getElementById("closure_id").value = cl_id; 

 //$('#comp_table_id') = component_id.val(); 
});


$(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1)  
    var actual_value=document.getElementById(nameArr).value;

    var rtb_val = $(this).val();

    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
      $("#"+txt_val).removeAttr("readonly");
    }
    else if(rtb_val == 'yes'){
      $("#"+txt_val).val(actual_value);
      $("#"+txt_val).attr("readonly","true");
    } 
    else if(rtb_val == 'not-obtained') {
      $("#"+txt_val).val("Not Disclosed");
      $("#"+txt_val).attr("readonly","true");
    }
    else if(rtb_val == 'not-verified') {
      $("#"+txt_val).val("Not Verified");
      $("#"+txt_val).attr("readonly","true");
    }
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

  $(document).ready(function(){

  $('#verified_by').keyup(function(){

        var verified_by  = document.getElementById("verified_by").value;
       // document.getElementById("demo").innerHTML = verified_by;
       /*if(isset($url))
       {
        if("<?php echo $url; ?>" == "Clear")
        {
        $('.add_rem_update').val("Site visit conducted and met "+verified_by+" who verified all details as Clear");   
        }
       }*/
  });
});

</script>

<script type="text/javascript">


  $('#view_result_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/address_closure_entries/' ?>',
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


  $('#view_vendor_insuff_tab').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/address_closure_entries_vendor_insuff/' ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_vendor_insuff').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_vendor_insuff').html(message);
        }
        else {
          $('#tbl_vendor_insuff').html(message);
        }     
      var tbl_vendor_insuff =  $('#tbl_vendor_insuff').DataTable( { scrollX: true, "paging": true,  "processing": true,  "searching": false, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 10,"lengthChange": true,"lengthMenu": [[10,25, 50, -1], [10,25, 50, "All"]],"aaSorting": [] });
      }
    }); 
  })


  $('#add_verificarion_result').validate({
      rules: {
        tbl_addrver : {
          required : true
        },
        addrverres_id : {
          required : true
        },
        mode_of_verification : {
          required : true
        },     
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        },
         resident_status : {
          required : true
        },
         verified_by : {
          required : true
        },
        addr_proof_collected : {
          required : true
        },
        res_address : {
          required : true
        },
        res_city : {
          required : true
        },
         res_pincode : {
          required : true
        },
         res_state : {
          required : true,
          greaterThan : 0
        },
         res_stay_from : {
          required : true
        },
         res_stay_to : {
          required : true
        },
         address_action : {
          required : true
        },
          city_action : {
          required : true
        },
        pincode_action : {
          required : true
        },
       state_action : {
          required : true
        },
         stay_from_action : {
          required : true
        },
         stay_to_action : {
          required : true
        }

      },
      messages: {
        tbl_addrver : {
          required : "ID"
        },
        addrverres_id : {
          required : "ID"
        },
        mode_of_verification : {
          required : "Select Mode Of Verification"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        }, 
         resident_status : {
          required : "Select Resident Status"
        },
         verified_by : {
           required : "Enter Verified By"
        },
        res_address : {
           required : "Enter Street Address"
        },
        res_city : {
           required : "Enter City"
        },
         res_pincode : {
           required : "Enter Pincode"
        },
         res_state : {
           required : "Select State",
           greaterThan : "Select State Name"
        },
         res_stay_from : {
           required : "Enter Stay Form"
        },
         res_stay_to : {
           required : "Enter Stay To"
        },
         address_action : {
           required : "Please Selected address Action"
        },
         city_action : {
           required : "Please Selected city Action"
        },
          pincode_action : {
           required : "Please Selected pincode Action"
        },
        
        state_action : {
           required : "Please Selected state Action"
        },
        stay_from_action : {
           required : "Please Selected stay from  Action"
        },
        stay_to_action : {
           required : "Please Selected stay to  Action"
        }
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=1'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
          data : activityData,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          success: function(jdata){
            $('#activityModel').modal('hide');
         
        var activityData = new FormData(form);
        activityData.append('action_val',$("#action option:selected").text());
        activityData.append('component_type',$("#action option:selected").text());
        activityData.append('activity_last_id',jdata.last_id);
        var sortable_data = $("ul.sortable" ).sortable('serialize'); 
        activityData.append('sortable_data',sortable_data);
        var sortable_data_vendor = $("ul.sortable1" ).sortable('serialize');
        activityData.append("sortable_data_vendor",sortable_data_vendor);

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_result'; ?>',
          data:  activityData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbresult').attr('disabled','disabled');
          },
          complete:function(){
            //$('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
          var  final_status = $('#fin_status').val();

          if(final_status == "Clear" || final_status == "Candidate Shifted" || final_status == "Unable To Verify" || final_status == "Unable to verify" || final_status == "denied verification" || final_status == "resigned" || final_status == "candidate not responding")
          {
            var cases_as = 1;
            var selected_id =  $('#closure_id').val();

            // finalData.append('status','approve');
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/address_closure/' ?>',
          data :  'action='+cases_as+'&closure_id='+selected_id,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            //$('#vendor_result_submit').attr('disabled','disabled');
          },
          complete:function(){
            $('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
        
         
          
              }
            });  
           
          }
            var message =  jdata.message || '';
            if(jdata.redirect){
              show_alert(message,'success');
              $('#addAddResultModel').modal('hide');
              $('.view_component_tab a.active').click();
              //window.location = jdata.redirect;
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
         }
        });  
      }
  });


  $('#add_verificarion_attachment').validate({
      rules: {
        address_id : {
          required : true
        } 
      },
      messages: {
        address_id : {
          required : "ID"
        }
      },
      submitHandler: function(form) 
      { 

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_ver_result_attachment'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbattachment').attr('disabled','disabled');
          },
          complete:function(){
            $('#sbattachment').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status = 200){
              show_alert(message,'success');
              $('#attachment_list').val(jdata.attachments);
              $('#add_attachment').modal('hide');
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }    
  });



  $('#add_vendor_details_view').validate({
      rules: {
        transaction_id : {
          required : true
        },
        status : {
          required : true,
          greaterThan : true
        },
        vendor_remark : {
          required : true
        }
      },
      messages: {
        transaction_id : {
          required : "Transaction ID"
        },
        status : {
          required : "Please select Status"
        },
        vendor_remark : {
          required : "Enter Vendor Remark"
        }
      },
      submitHandler: function(form) 
      { 
        var activityData = new FormData(form);
        activityData.append('vendor_date','');
        activityData.append('view_vendor_master_log_id','');


        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/Save_vendor_details'; ?>',
          data:  activityData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#vendor_result_submit').attr('disabled','disabled');
          },
          complete:function(){
            $('#vendor_result_submit').removeAttr('disabled');                
          },
          success: function(jdata){
         
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#showvendorModel').modal('hide');
              location.reload();
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }
       
  });


   $(document).on('click','#arr_attachment',function() {

    var url = $(this).data('url');
    var address_id = $('#tbl_addrver').val();
 
    $('#append_add_attachment').load(url+'/'+address_id,function(){
      $('#add_attachment').modal('show');
      $('#add_attachment').addClass("show");
      $('#add_attachment').css({background: "#0000004d"}); 
    });
   
});


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
          url : '<?php echo ADMIN_SITE_URL.'address/insuff_raised'; ?>',
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
            $('#btn_submit_insuff').removeAttr('disabled',false);
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

 $.validator.addMethod('filesize', function (value, element, arg) {
    var attachment_count = ($("#attchments_ver")[0].files.length); 
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

$('#activityModelClkFollow').click(function(){
    var url = $(this).data("url");
    var id = $('#address_id').val();
    var component = 'address';


    $.ajax({
      type: "POST",
      url:'<?php echo ADMIN_SITE_URL.'address/check_closure_status_digital/' ?>'+id,
      dataType: "html",
      success:function(data){

        if(data == "success")
        {

          $('.append-activity_view').load(url+id+"/"+component,function(){});
          $('#activityModel').modal('show');
          $('#showvendorModelDigitalView').modal('hide');
          $('#activityModel').addClass("show");
          $('#activityModel').css({background: "#0000004d"});

        }
        else
        {
           alert('Please Check Vendor Status or address verification done or not');
        }  
      }
      });
  });

</script>

