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
                    <form method="post" action="<?php echo ADMIN_SITE_URL.'education/export_education_stamp_doc'?>">
                      <input type="hidden" name="component_check_id" id="component_check_id" class="form-control">
                      <button class="btn btn-secondary waves-effect" id="export_education_cases_doc" data-toggle="modal" data-target="#myModalExport" ><i class="fa fa-download"></i>  Export Doc </button>

                   </form>
                
                    <div class="col-sm-3 form-group">
                    <?php  
                      $stamp_vendor_list['all'] = "All"; 
                      echo form_dropdown('vendor_stamp',$stamp_vendor_list, set_value('vendor_stamp','all'), 'class="form-control" id="vendor_stamp" required="required" '); 
                    ?>
                    </div>

                    <?php

                      $verification_status = array('' => 'Select','2' => 'Assign to Stamp');

                      echo '<div class="col-sm-3 form-group">';
                      echo form_dropdown('assgin_stamp_vendor',$verification_status, set_value('assgin_stamp_vendor'), 'class="form-control" id="assgin_stamp_vendor"');
                      echo "</div>";
                    ?>

                  <button class="btn btn-secondary waves-effect btn-sm" id="export_education_cases_stamp_doc_send_mail" name="export_education_cases_stamp_doc_send_mail" style="height: 30px;"> Send Mail </button>

                  </div> 
                   

                   <br>
                    <table id="tbl_vendor_stamp_queue" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>#</th>
                          <th>Recvd Date</th>
                          <th>Comp Ref</th>
                          <th>Candidate Name</th>
                          <th>University Name</th>
                          <th>Qualification</th>
                          <th>Mode of verification</th>
                          <th>Action</th>
                          <th>Status</th>
                          <th>Client Name</th>
                          <th>Verifier/Spoc Vendor</th>
                          <th>Stamp Vendor</th>
                        </tr>
                      </thead>
                      <tbody>


                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
  
<div id="myModalCasesAssignStamp" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Stamp</h4>
      </div>
        <div class="modal-body">
          <div id = "vendor_details_list_stamp"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_stamp">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>


<script type="text/javascript">
$(function ()  {
 
    var table =  $('#tbl_vendor_stamp_queue').DataTable( {

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
            url: "<?php echo ADMIN_SITE_URL.'education/education_stamp_verifiers_details'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'vendor_stamp':function(){return $("#vendor_stamp").val(); } }
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
            },{"data":"modified_on"},{"data":"education_com_ref"},{"data":"CandidateName"},{'data' :'university_board'},{'data' :'qualification'},{'data' :'mode_of_verification'},{"data":"action"},{"data":"remarks"},{"data":"clientname"},{"data":"vendor_name"},{"data":"vendor_stamp_name"}],


         "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {

         
            switch(aData['remarks'])
            {
              case "clear" :
              
                $('td' , nRow).css('color', 'block')
                 break;
   

              default :
                 $('td' , nRow).css('color', 'red') 
                
            }
        }
    });

  $('#tbl_vendor_stamp_queue tbody').on('click', 'td.details-control', function () {
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


  $('#tbl_vendor_stamp_queue tbody').on('change', 'input[type="checkbox"]', function(){
    
    select_one = table.column(0).checkboxes.selected().join(",");
      $('#component_check_id').val(select_one);
  });
  
 $(".dt-checkboxes-select-all").click(function () {
     $('#tbl_vendor_stamp_queue').find('input[type="checkbox"]').trigger('click');
  
});
  $('#vendor_stamp').on('change', function(){
    var vendor_stamp = $('#vendor_stamp').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'education/education_stamp_verifiers_details'; ?>?vendor_stamp="+vendor_stamp;
    table.ajax.url(new_url).load();
  });

  $('#tbl_vendor_stamp_queue tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  });

  $('#tbl_vendor_stamp_queue').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

  $('#assgin_stamp_vendor').on('change', function(e){
   
    var cases_assgin_action = $('#assgin_stamp_vendor').val();
    var select_one = table.column(0).checkboxes.selected().join(",");

    if(cases_assgin_action != 0 && select_one != "")
    {
     
        $.post('<?php echo ADMIN_SITE_URL.'education/assign_verifiers_details/' ?>',{cases_assgin_action:cases_assgin_action},function (data, textStatus, jqXHR) {
        
          $('#vendor_details_list_stamp').html(data);
          $('.vendor_list').show();
          $('.header_title').text('Assign Stamp Vendor');
    
          $('#myModalCasesAssignStamp').modal('show');
          $('#myModalCasesAssignStamp').addClass("show");
          $('#myModalCasesAssignStamp').css({background: "#0000004d"});
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
      $("#assgin_stamp_vendor option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
  });

  $('#btn_assign_action_stamp').on('click', function(e){
    var vendor_list = $('#vendor_list').val();
    var select_one = table.column(0).checkboxes.selected().join(",");
   
    if(vendor_list != "0" && select_one != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_stamp/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_stamp').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalCasesAssignStamp').modal('hide');
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



  $('#export_education_cases_stamp_doc_send_mail').on('click', function(e){

    var select_one = table.column(0).checkboxes.selected().join(",");
    if(select_one != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_stamp_send_mail/' ?>',
          data : 'cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('#export_education_cases_stamp_doc_send_mail').text('Processing...');
          },
          complete:function(){
            jQuery('#export_education_cases_stamp_doc_send_mail').text('Send Mail');

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


/*$('#vendor_stamp').on('change', function(){
    var vendor_stamp = $('#vendor_stamp').val();
    $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'education/education_stamp_verifiers_details/' ?>',
            data : "vendor_stamp="+vendor_stamp,
            dataType:'html',
            success:function(jdata)
            {
              $('#tbl_vendor_stamp_queue').html(jdata);
            }
        }); 
  });            
*/


</script>
