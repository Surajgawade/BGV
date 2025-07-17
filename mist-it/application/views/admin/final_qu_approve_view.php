<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Final QC - Approval Queue</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>final_QC">Final QC</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>final_QC/approved_queue">Approval Queue</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  
            </div>
          </div>
    </div>

    <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified">

            <?php   echo "<li class='nav-item waves-effect waves-light active' role='presentation'><a class = 'nav-link active' href='#final_qc_approve_queue' aria-controls='home' data-toggle='tab'>Final QC Approve Queue</a></li>";



            echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."Final_QC/annexture_list_view/' data-can_id='".$this->user_info['id']."'  data-tab_name='annexture_list_view'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#annexture_list_view' aria-controls='home' role='tab' data-toggle='tab'>Annexure List View</a></li>";
         ?>                        
       </ul>
    </div>
   
  <div class="tab-content">
    <div id="final_qc_approve_queue" class="tab-pane active">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <?php if ($this->permission['access_final_aq_view'] == 1) { ?>  
                <?php echo $filter_view_final_qc; ?>
                <div class="row">
                 <div class="col-sm-2 form-group">
                  <?php 
                  $case_status = array('0'=>'Select Status','1'=>'Approve','2'=>'Reject');
                  echo form_dropdown('case_status', $case_status, set_value('case_status'), 'class="select2" id="case_status"');
                  ?>
                 </div>
                 <div class="col-sm-2 form-group">
                  <?php  echo '<button class="btn btn-sm btn-info candidate_report_mail"  id = "candidate_report_mail" >Submit</button>';  ?>
                 </div>
               </div>
               <div class="clearfix"></div>
        
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                    <th>Approved On</th>
                    <th>Approved By</th>
                    <th>Case recvd date</th>
                    <th>Client Name</th>
                    <th>Candidate Name</th>
                    <th>Client Ref. No</th>
                    <th><?php echo REFNO; ?></th>
                    <th>Status</th>
                    <th>Last email</th>
                    <th>Sending Status</th>
                    <th>Entity</th>
                    <th>Package</th>

                </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>

              <?php  }else  { echo "You do not have permission"; } ?>
            </div>
           </div>
          </div>
        </div>
      </div>
     
      <div id="annexture_list_view" class="tab-pane fade in">
      </div>
     </div>
    </div> 
    </div> 
</div>
<div id="myModalCasesAssign" class="modal fade" role="dialog">
  <div class="modal-dialog popup_style">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Report Form View</h4>
      </div>
      <div class="modal-body" id="append_html"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" type="button" name="brn_first_qc_approve" id="brn_first_qc_approve">Approve</button>
        <button type="button" class="btn btn-warning" type="button" name="brn_first_qc_reject" id="brn_first_qc_reject">Reject</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="showFinalReportModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'save_final_report','id'=>'save_final_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4>Final QC</h4>
      </div>
    
         <!-- <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
          <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
          <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">-->
      <span class="errorTxt"></span>
      <div id="append_final_result"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

         <button type='submit' class='btn btn-info' type='button' name='brn_final_qc_approve' id='brn_final_qc_approve'>Approve</button>
         
         
            <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>
            <button type='button' class='btn btn-danger' type='button' name='brn_final_qc_download' id='brn_final_qc_download'>Download</button>

            <button type='button' class='btn btn-default' data-dismiss="modal" name='brn_final_qc_cancel' id='brn_final_qc_cancel'>Cancel</button>
      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
var $ = jQuery;
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
            url: "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable'; ?>",
            pages: 5,
            async: true,
            method: 'POST',
            data: { 'clientid':function(){return $("#clientid").val(); },'entity':function(){return $("#entity").val(); },'package':function(){return $("#package").val(); },'overallstatus':function(){return $("#overallstatus").val(); } }
            //async: true 

        } ),
          order: [[ 1, 'asc' ]],
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
     
        "columns":[{"data":"id"},{"data":"final_qc_approve_reject_timestamp"},{"data":"created_by"},{"data":"caserecddate"},{"data":"clientname"},{"data":"CandidateName"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"overallstatus"},{"data":"final_qc_send_mail_timestamp"},{"data":"sending_status"},{"data":"entity"},{"data":"package"}],

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


    $('#tbl_datatable').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });



 $(document).on('click','.candidate_report_mail',function() {
    select_one = oTable.column(0).checkboxes.selected().join(",");
    var count_record = select_one.split(",").length;
    
        if(select_one != '')
        {
            /*var tableControl = document.getElementById('tbl_datatable');

            var result = []
            $('input:checkbox:checked', tableControl).each(function() {
                result.push($(this).parent().next().next().next().next().next().next().text());
            });
            var set  =  result.splice(0, 1);
         
            var new_value_condition = myFunc(result);
            //console.log(new_value_condition);
            if(new_value_condition == true)
            {*/

            var case_status =  $('#case_status').val();

            if(case_status != '0')
            {
                $.ajax({
                    url : "<?php echo ADMIN_SITE_URL.'Final_QC/report_send_mail'; ?>",
                    data : "candidate_user_id="+select_one+"&case_status="+case_status,
                    type: 'post',
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend:function(){
                      $('.candidate_report_mail').val('Sending...');
                      $('.candidate_report_mail').attr('disabled','disabled');
                    },
                    complete:function(){
                         
                    },
                    success: function(jdata){
                      var message =  jdata.message || '';
                      if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                        show_alert(message,'success'); 
                         location.reload(); 
                      } else {
                        show_alert(message,'error'); 
                      }
                    }
                });

            }
            else
            {
              alert('Please Select Status');
            }    

               /* var url = $(this).data('url');
                var arr = url.split('/');
                $('#report_type').val(arr[7]);
                $('.append-email_report').load(url+'/'+select_one,function(){
                  $('#report_mail').modal('show');
                  $('#report_mail').addClass("show");
                  $('#report_mail').css({background: "#0000004d"});
                });*/ 
           /* }
            else
            {
               alert('Please select same client name record');
            }*/

        }
        else
        {
          alert('please select atleast one');
        }
   
});
 

  $('div.dataTables_filter input').unbind();
  $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  function myFunc(arr){
        var x= arr[0];
        return arr.every(function(item){
            return item=== x;
        });
    }
 

  /*$('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['encry_id'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });*/

  $('#clientid,#entity,#package').on('change', function(){
    var entity = $('#entity').val();
    var package = $('#package').val();
    var clientid = $('#clientid').val();
   // var overallstatus = $('#overallstatus').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable'; ?>?clientid="+clientid+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords').on('click', function() {
    var entity = $('#entity').val();
    var package = $('#package').val();
    var clientid = $('#clientid').val();
   // var overallstatus = $('#overallstatus').val();
   
    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_approve_datatable'; ?>?clientid="+clientid+"&entity="+entity+"&package="+package;
       oTable.ajax.url(new_url).load();
  
  });

       

  
  
   
  /*$('#clientid').on('change',function(){
  var clientid = $(this).val();
    if(clientid != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            success:function(jdata) {
              if(jdata.status = 200)
              {
                alert(jdata.cmp_ref_no);
                $('#cmp_ref_no').val(jdata.cmp_ref_no);
                $('#entity').empty();
                $.each(jdata.entity_list, function(key, value) {
                  $('#entity').append($("<option></option>").attr("value",key).text(value));
                });
              }
            }
        });
    }
  }).trigger('change');
  */


 $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    var url = data['encry_id'];
    $('#append_final_result').load(url,function(){
      $('#showFinalReportModel').modal('show');
    });
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

   $(document).on('change', '#clientids', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entitys').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entitys').html(html);
          }
      });
  }
});

  $(document).on('change', '#entitys', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#packages').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#packages').html(html);
            }
        });
    }
  });



   $('#send_candidate_report_mail').validate({
    rules : { 
      to_email : {
        required : true,
        multiemails : true,
        matches: true
      },
    cc_email :{
        multiemails : true
      }
    },
    messages : {
      to_email : {
        required : "Enter Email To ID's",
        multiemails : "Enter Valid Email ID"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'Final_QC/report_send_mail'; ?>",
        data : $('#send_candidate_report_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#send_mail_report_frm').val('Sending...');
          $('#send_mail_report_frm').attr('disabled','disabled');
        },
        complete:function(){
         // $('#send_mail_report_frm').removeAttr('disabled');
          $('#send_mail_report_frm').val('Send');                
        },
        success: function(jdata){
          $('#report_mail').modal('hide');
          $('#send_candidate_report_mail')[0].reset();
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            show_alert(message,'success'); 
             location.reload(); 
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    },
  });

   jQuery.validator.addMethod("multiemails", function (value, element) {
    if (this.optional(element)) {
    return true;
    }

    var emails = value.split(','),
    valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
    value = jQuery.trim(emails[i]);
    valid = valid && jQuery.validator.methods.email.call(this, value, element);
    }
    return valid;
    }, "Invalid email format: please use a comma to separate multiple email addresses.");

   
});
</script>

