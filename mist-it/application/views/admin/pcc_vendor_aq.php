<div class="content-page">
  <div class="content">
    <div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">PCC - PCC AQ</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>pcc">PCC</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>pcc/approval_queue">PCC AQ</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
            
            </div>
          </div>
    </div>  


    <div class="nav-tabs-custom">
      <ul class="nav nav-pills nav-justified">
   <?php   echo "<li class='nav-item waves-effect waves-light active'  role='presentation' ><a class = 'nav-link active' href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Vendor Assign/ Reject</a></li>";


    echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."pcc/pcc_closure_entries/'  data-tab_name='result_log_tabs'  data-can_id='".$this->user_info['id']."'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#result_log_tabs' aria-controls='home' role='tab'  data-toggle='tab'>Closure</a></li>";

    echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."pcc/pcc_closure_entries_vendor_insuff/' data-can_id='".$this->user_info['id']."'  data-tab_name='vendor_insuff_tab'  class='nav-item waves-effect waves-light view_component_tab'><a  class = 'nav-link' href='#vendor_insuff_tab' aria-controls='home' role='tab'  data-toggle='tab'>Vendor Insuff</a></li>";
   ?>
     </ul>
    </div>


<div class="tab-content">
    <div id="Vendor_assign_reject" class="tab-pane  active">
 
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

              <table id="example" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr >
                    <th><input name="select_all" value="1" id="example-select-all" class="example-select-all" type="checkbox" /></th>
                    <th>Sr No</th>
                    <th>Comp Ref No</th>
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
  
    <div id="result_log_tabs" class="tab-pane fade in">
    </div>
    <div id="vendor_insuff_tab" class="tab-pane fade in">
    </div>

   </div>
 
</div>
</div>
</div>

<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'frm_verificarion_result','id'=>'frm_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" id="candidates_info_id" name="candidates_info_id" value="<?php echo set_value('candidates_info_id'); ?>">
      <input type="hidden" id="entity_id" name="entity_id" value="<?php echo set_value('entity_id'); ?>">
      <input type="hidden" id="package_id" name="package_id" value="<?php echo set_value('package_id'); ?>">
      <input type="hidden" id="crimver_id" name="pcc_id" value="<?php echo set_value('crimver_id'); ?>">
      <input type="hidden" id="clientid"  name="clientid" value="<?php echo set_value('clientid'); ?>">


      <input type="hidden" id="pcc_result_id" name="pcc_result_id" value="<?php echo set_value('pcc_result_id'); ?>">

      <input type="hidden" name="url_action" value="" id="url_action">
      <div id="append_result_model"></div>
      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
      <input type="hidden" name="pcc_id" id = "pcc_id"  class = "pcc_id" value="">
      <input type="hidden" name="fin_status"  id = "fin_status"  class="fin_status" value="">
      <input type="hidden" name="closure_id"  id = "closure_id"  class="closure_id" value="">
      <div class="modal-body">
      <span class="errorTxt"></span>
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
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_vendor_form/4/' ?>" data-toggle="modal" id="activityModelClk1" type="button" >Approve</button>
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
            <input type="hidden" name="component_type" id="component_type" value="8">
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
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
  var $ = jQuery;
var select_one = '';


$(document).ready(function() {
  
  var oTable =  $('#example').DataTable( {

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
            url: "<?php echo ADMIN_SITE_URL.'pcc/view_approval_queue'; ?>",
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
        "columns":[{'data' :'checkbox'},{'data' :'id'},{"data":"pcc_com_ref"},{"data":"clientname"},{"data":"entity_name"},{"data":"package_name"},{'data' :'vendor_name'},{'data' :'city'},{'data' :'pincode'},{'data' :'state'},{'data' :'allocated_by'},{"data":"allocated_on"}]
  });

  $('#example tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
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

  $('#example tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  } );

    $('#example').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
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
          url:'<?php echo ADMIN_SITE_URL.'pcc/pcc_final_assigning/' ?>',
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
          url:'<?php echo ADMIN_SITE_URL.'pcc/pcc_closure/' ?>',
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
          url:'<?php echo ADMIN_SITE_URL.'pcc/pcc_closure/' ?>',
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



$('#activityModelClk1').click(function(){
 
    var url = $(this).data("url");
    var id = $('#pcc_id').val();
    var component = 'pcc';
    $('.append-activity_view').load(url+id+"/"+component,function(){});
    $('#activityModel').modal('show');
    $('#showvendorModel').modal('hide');
    $('#activityModel').addClass("show");
    $('#activityModel').css({background: "#0000004d"});
  });

$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    
    var action2 = $("#action option:selected").text();
    var action1 =  action2.replace(/\s+/g,'');
    $('#url_action').val(action1);
    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url+"/"+action1,function(){
      $('#addAddResultModel').modal('show');
      $('#addAddResultModel').addClass("show");
      $('#addAddResultModel').css({background: "#0000004d"});
    });
  }
});


$(document).on('click','#activityModelClk1',function() {

 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var entity_id  = $('#entity_id1').val();
 var package_id  = $('#package_id1').val();
 var clientid  = $('#clientid1').val();
 var pcc_result_id  = $('#pcc_result_id1').val();


 document.getElementById("crimver_id").value = component_id; 
 document.getElementById("candidates_info_id").value = CandidateID; 
 document.getElementById("entity_id").value = entity_id; 
 document.getElementById("package_id").value = package_id; 
 document.getElementById("clientid").value = clientid; 
 document.getElementById("pcc_result_id").value = pcc_result_id; 
   
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


$('.approve_reject_id').click(function(){
 var cl_id  = $('#cl_id').val();
 document.getElementById("closure_id").value = cl_id; 
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
          url : '<?php echo ADMIN_SITE_URL.'pcc/insuff_raised'; ?>',
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
           // $('#btn_submit_insuff').removeAttr('disabled',false);
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

    $('#frm_verificarion_result').validate({
    rules: {
        mode_of_verification : {
           
        required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return true;
          }

        }
        },
       
        police_station : {
          required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return true;
          }

         }
        },
        pcc_id : {
          required : true
        },

        name_designation_police :
        {
           required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return true;
          }

        }
        },
         contact_number_police :
        {
          required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return true;
          }

        }
        },
        pcc_result_id : {
          required : true
        },
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        }
      },
      messages: {
        mode_of_verification : {
           required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return "Enter Mode of Verification";
          }
        }
        },
       
        police_station : {

          required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return "Enter Police Station";
          }
        }
          
        },
       
        name_designation_police :
        {
          required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return "Enter Police Designation";
          }
        }
          
        },
        contact_number_police :
        {
            required : function(){
          var  url_action = $('#url_action').val();
          if(url_action == "StopCheck")
          {
            return false;
          }
          else
          {
             return "Enter Contact Number";
          }
        }
          
        },
        pcc_id : {
          required : "Entr pcc ID/Code"
        },
        pcc_result_id : {
          required : "Enter pcc ID"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        } 
      },
    submitHandler: function(form) 
    { 
      var activityData = $('#frm_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=8'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
      console.log(activityData);
      console.log($('#frm_verificarion_result').serialize());
      console.log($('#cases_activity').serialize());
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
      $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'pcc/add_verificarion_result'; ?>',
        data:  activityData,
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
           // $('#sbresult').attr('disabled','disabled');
        },
        complete:function(){
            $('#sbresult').removeAttr('disabled');                
        },
        success: function(jdata){
          var  final_status = $('#fin_status').val();
          if(final_status == 'Closed' || final_status == 'closed')
          {
            var cases_as = 1;
            var selected_id =  $('#closure_id').val();

            // finalData.append('status','approve');
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'pcc/pcc_closure/' ?>',
          data :  'action='+cases_as+'&closure_id='+selected_id,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
          //  $('#vendor_result_submit').attr('disabled','disabled');
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
             // location.reload();

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



  $('#view_result_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'pcc/pcc_closure_entries/' ?>',
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
