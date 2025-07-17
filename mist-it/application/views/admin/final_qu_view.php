<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Final QC</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>final_QC">Final QC</a></li>
                    
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  
            </div>
          </div>
    </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <?php if ($this->permission['access_final_list_view'] == 1) { ?>  
                <?php echo $filter_view_final_qc; ?>

           
         
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th>Created On</th>
                    <th>Case recvd date</th>
                    <th>Client Name</th>
                    <th>Candidate Name</th>
                    <th>Status</th>
                    <th>Last email</th>
                    <th>Last component</th>
                    <th>Action</th>
                    <th>Client Ref. No</th>
                    <th><?php echo REFNO; ?></th>
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
       <!-- <button type="button" class="btn btn-warning" type="button" name="brn_first_qc_reject" id="brn_first_qc_reject">Reject</button>-->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="showFinalReportModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

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
         
         
          <!--  <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>-->
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
            url: "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_datatable'; ?>",
            pages: 5,
            async: true,
            method: 'POST',
            data: { 'clientid':function(){return $("#clientid").val(); },'entity':function(){return $("#entity").val(); },'package':function(){return $("#package").val(); },'overallstatus':function(){return $("#overallstatus").val(); } }
            //async: true 

        } ),
         order: [[ 0, 'asc' ]],
      
        "columns":[{"data":"modified_on"},{"data":"caserecddate"},{"data":"clientname"},{"data":"CandidateName"},{"data":"overallstatus"},{"data":"final_qc_send_mail_timestamp"},{"data":"last_activity_component"},{"data":"action"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"},{"data":"entity"},{"data":"package"}],

        "fnRowCallback" : function(nRow,aData,iDisplayIndex)
        {

         
            switch(aData['overallstatus'])
            {
              case "Major Discrepancy" :
              
                 $('td' , nRow).css('background-color', '#f/f0000')
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
  $('div.dataTables_filter input').unbind();
  $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });

  /*$('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['encry_id'] != 0)
      window.location = data['encry_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });*/

  $('#tbl_datatable').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

  $('#clientid,#entity,#package').on('change', function(){
    var entity = $('#entity').val();
    var package = $('#package').val();
    var clientid = $('#clientid').val();
   // var overallstatus = $('#overallstatus').val();
  
    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_datatable'; ?>?clientid="+clientid+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords').on('click', function() {
    var entity = $('#entity').val();
    var package = $('#package').val();
    var clientid = $('#clientid').val();
  //  var overallstatus = $('#overallstatus').val();
   
    var new_url = "<?php echo ADMIN_SITE_URL.'final_QC/final_qc_view_datatable'; ?>?clientid="+clientid+"&entity="+entity+"&package="+package;
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


/* $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    var url = data['encry_id'];
    $('#append_final_result').load(url,function(){
      $('#showFinalReportModel').modal('show');
      $('#showFinalReportModel').addClass("show");
      $('#showFinalReportModel').css({background: "#0000004d"});
    });
  });*/


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

      $('#tbl_datatable tbody').on('click', '.brn_first_qc_approve', function (){
      
            var id = $(this).attr('id'); 

            if(id != "") {
                $.ajax({
                    url: '<?php echo ADMIN_SITE_URL.'Final_QC/save_final_report'; ?>',
                    type: 'post',
                    data: {candidate_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                      var message =  jdata.message || '';
                      if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                        show_alert(message,'success');
                       location.reload();
                      }
                      if(jdata.status == <?php echo ERROR_CODE; ?>){
                        show_alert(message,'error'); 
                      }
                    }
                });
            }
            else
            {
                alert('Candidate Id Not Found');
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
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
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
    text-align: center; 
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
    text-align: center; 

}

</style>
