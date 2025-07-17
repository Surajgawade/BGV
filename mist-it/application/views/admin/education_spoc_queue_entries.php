 <style type="text/css">
  div.both {
  display: flex;
}
 </style> 
            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                  <div class="card-body">
                    <div class="both">
                    <form method="post" action="<?php echo ADMIN_SITE_URL.'education/export_education_spoc_doc'?>">
                      <input type="hidden" name="components_check_id" id="components_check_id" class="form-control">
                      <button class="btn btn-secondary waves-effect" id="export_education_cases_doc" data-toggle="modal" data-target="#myModalExport" ><i class="fa fa-download"></i>  Export Doc </button>
                    </form>
                      &nbsp;
                     <button class="btn btn-secondary waves-effect" id="assign_bulk_to_modal_stamp_spoc" data-toggle="modal" data-target="#myModalStampSpoc" style = "height:35px;">Assign Stamp </button>
                     <?php

                      $verification_status = array('' => 'Select','1' => 'Assign to Spoc');

                      echo '<div class="col-sm-3 form-group">';
                      echo form_dropdown('assgin_spoc_vendor',$verification_status, set_value('assgin_spoc_vendor'), 'class="form-control" id="assgin_spoc_vendor"');
                      echo "</div>";
                    ?>
                    
                      <button class="btn btn-secondary waves-effect btn-sm" id="export_education_cases_doc_send_mail" name="export_education_cases_doc_send_mail" style="height: 30px;"> Send Mail </button>
                  </div>
                    <br>
                    <table id="tbl_vendor_spoc_queue" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>#</th>
                          <th>Recvd Date</th>
                          <th>Comp Ref</th>
                          <th>Candidate Name</th>
                          <th>University Name</th>
                          <th>Qualification</th>
                          <th>Action</th>
                          <th>Client Name</th>
                          <th>Spoc Vendor</th>
                          <th>Mode of verification</th>
                        </tr>
                      </thead>
                      <tbody>
              
                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
       
  
  <div id="myModalCasesAssignSpoc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Spoc</h4>
      </div>
        <div class="modal-body">
          <div id = "vendor_details_list_spoc"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_spoc">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<div id="myModalStampSpoc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Stamp</h4>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 form-group"> 
              <label>Insert Ref No</label>
              <textarea name="case_assign_to_stamp_spoc_bulk" id="case_assign_to_stamp_spoc_bulk" rows="2" class="form-control"></textarea>
            </div>
          </div>
          
          <div class="row">
            <div class="col-sm-6 form-group">
             <label>Stamp Status</label>
               <?php
                echo form_dropdown('cases_assgin_stamp_spoc_bulk', $assigned_option, set_value('cases_assgin_stamp_spoc_bulk'), 'class="select2" id="cases_assgin_stamp_spoc_bulk"');?>
            </div>

            <div class="col-sm-6 form-group reject_reason_spoc_bulk" style="display: none;"> 
              <label>Select Stamp Vendor</label>
              <?php  
               echo form_dropdown('vendor_list_stamp_spoc_bulk',$vendor_stamp_list, set_value('vendor_list_stamp_spoc_bulk'), 'class="form-control" id="vendor_list_stamp_spoc_bulk" required="required" '); ?>
            </div>

            <div class="col-sm-6 form-group"> 
              <label>Remarks</label>
              <textarea name="remark_verifiers_stamp_spoc_bulk" rows="1" id="remark_verifiers_stamp_spoc_bulk" class="form-control"><?php echo set_value('remark_verifiers_stamp_spoc_bulk'); ?></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_stamp_spoc_bulk">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function ()  {

  var table =  $('#tbl_vendor_spoc_queue').DataTable( {

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
            url: "<?php echo ADMIN_SITE_URL.'education/education_spoc_verifiers_details'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { }
        } ),
        order: [[ 2, 'asc' ]],
        "aLengthMenu": [[10, 25, 50,1000000000000000], [10, 25, 50,'All']],

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
       
        "columns":[{"data":"checkbox"},{
                "class":          'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },{"data":"modified_on"},{"data":"education_com_ref"},{"data":"CandidateName"},{'data' :'university_board'},{'data' :'qualification'},{"data":"action"},{"data":"clientname"},{"data":"vendor_name"},{'data' :'mode_of_verification'}]
    });


   $('#tbl_vendor_spoc_queue tbody').on('click', 'td.details-control', function () {
        var tr = $(this).parents('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
             if ( table.row( '.shown' ).length ) {
              $('.details-control', table.row( '.shown' ).node()).click();
            }
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

   function format ( d ) {

  var expand_data = '';

   expand_data += '<table  class ="child-table" cellpadding="5"  cellspacing="0" border="1"  style="padding-left:50px;margin-left:60px;"><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Client Id : </b></td><td style="border: 1px solid black">'+d.ClientRefNumber+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>DOB : </b></td><td style="border: 1px solid black">'+d.DateofBirth+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Fathers Name :</b></td><td style="border: 1px solid black">'+d.NameofCandidateFather+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Mothers Name :</b></td><td style="border: 1px solid black">'+d.MothersName+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>School/College :</b></td><td style="border: 1px solid black">'+d.school_college+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Roll No :</b></td><td style="border: 1px solid black">'+d.roll_no+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Enrollment No :</b></td><td style="border: 1px solid black">'+d.enrollment_no+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Major :</b></td><td style="border: 1px solid black">'+d.major+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Grade/Class/Marks :</b></td><td style="border: 1px solid black">'+d.grade_class_marks+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Course Start :</b></td><td style="border: 1px solid black">'+d.course_start_date+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Course End :</b></td><td style="border: 1px solid black">'+d.course_end_date+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Month :</b></td><td style="border: 1px solid black">'+d.month_of_passing+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Year :</b></td><td style="border: 1px solid black">'+d.year_of_passing+'</td><td>'+d.attachment_file+'</td></table>';
  return expand_data ;
}


  $('#tbl_vendor_spoc_queue tbody').on('change', 'input[type="checkbox"]', function(){
    
      select_one = table.column(0).checkboxes.selected().join(",");
      $('#components_check_id').val(select_one);
   });

 $(".dt-checkboxes-select-all").click(function () {
     $('#tbl_vendor_spoc_queue').find('input[type="checkbox"]').trigger('click');

});

 $('#tbl_vendor_spoc_queue tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  });

  $('#tbl_vendor_spoc_queue').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   $('#assgin_spoc_vendor').on('change', function(e){
   
    var cases_assgin_action = $('#assgin_spoc_vendor').val();
    var select_one = table.column(0).checkboxes.selected().join(",");

    if(cases_assgin_action != 0 && select_one != "")
    {
     
        $.post('<?php echo ADMIN_SITE_URL.'education/assign_verifiers_details/' ?>',{cases_assgin_action:cases_assgin_action},function (data, textStatus, jqXHR) {
        
          $('#vendor_details_list_spoc').html(data);
          $('.vendor_list').show();
          $('.header_title').text('Assign Spoc Vendor');
    
          $('#myModalCasesAssignSpoc').modal('show');
          $('#myModalCasesAssignSpoc').addClass("show");
          $('#myModalCasesAssignSpoc').css({background: "#0000004d"});
       });

      var form = this;
      var rows_selected = table.column(0).checkboxes.selected();
      $.each(rows_selected, function(index, rowId){
         $(form).append(
             $('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId)
         );
      });
      $('input[name="id\[\]"]', form).remove();
      e.preventDefault();
    } else {
      $("#assgin_spoc_vendor option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
  });


  $('#btn_assign_action_spoc').on('click', function(e){
    var vendor_list = $('#vendor_list').val();
    var select_one = table.column(0).checkboxes.selected().join(",");
   
    if(vendor_list != "0" && select_one != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_spoc_reinitiated/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_spoc').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalCasesAssignSpoc').modal('hide');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Select Vendor Name');
    }
  });
 


  $('#export_education_cases_doc_send_mail').on('click', function(e){

    var select_one = table.column(0).checkboxes.selected().join(",");
    if(select_one != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_send_mail/' ?>',
          data : 'cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('#export_education_cases_doc_send_mail').text('Processing...');
          },
          complete:function(){
            jQuery('#export_education_cases_doc_send_mail').text('Send Mail');

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
    else {
      alert('Select at least one case');
    }
  });

  

});


   $('#cases_assgin_stamp_spoc_bulk').on('change', function(){
    var cases_assgin_bulk_action = $('#cases_assgin_stamp_spoc_bulk').val();
    
   (cases_assgin_bulk_action == "insufficiency") ?  $('.reject_reason_spoc_bulk').hide() : $('.reject_reason_spoc_bulk').show();
  }); 



  $('#btn_assign_action_stamp_spoc_bulk').on('click', function(e){

    var education_id = $('#case_assign_to_stamp_spoc_bulk').val();
    var status_value = $('#cases_assgin_stamp_spoc_bulk').val();

    if(education_id != "" && status_value != "")
    {
        var vendor_list = $('#vendor_list_stamp_spoc_bulk').val();
        var vendor_remark = $('#remark_verifiers_stamp_spoc_bulk').val();
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_stamp_spoc_bulk/' ?>',
          data : 'vendor_list='+vendor_list+'&education_id='+education_id+'&status_value='+status_value+'&vendor_remark='+vendor_remark,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_stamp_spoc_bulk').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalStampSpoc').modal('hide');
              setTimeout(
              function() 
              {
                location.reload();
              }, 5000);
             
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Select Status or insert ref no');
    }
  });

</script>
