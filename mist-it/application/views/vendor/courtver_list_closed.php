<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?php echo SITE_URL; ?>assets/js/html2canvase.js"></script>

<style type="text/css">
          #court_verification_details{
            color: black;
            background-color: white;
            font-family: Arial;
            font-size: large;

        }

        .content-pages{
            margin-left: 5%;
            margin-right: 5%;

        }

         
</style>
<div class="content-page">
<div class="content">
<div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Vendor - Court - Closed - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>index"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo VENDOR_SITE_URL ?>courtver/courtver_closed">Court Closed</a></li>
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
                      <input type="date" name="start_date" id="start_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy" value="<?php print(date("Y-m-d")); ?>">
                    </div>
                                           
                    <div class="col-md-2 col-sm-12 col-xs-3 form-group">
                        <input type="date" name="end_date" id="end_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy" value="<?php print(date("Y-m-d")); ?>">
                    </div>

                    <div class="col-md-2 col-sm-12 col-xs-3 form-group">
                        <?php

                        $filter_status = array('All' => "All", 'clear' => 'Clear', 'possible match' => 'Possible Match', 'approved' => 'Approved');
                          echo form_dropdown('filter_by_status', $filter_status, set_value('filter_by_status'), 'class="custom-select" id="filter_by_status"');

                          ?>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-2 form-group">
                       <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                       <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                    </div>

                    <?php echo form_open('#', array('name'=>'export_court','id'=>'export_court')); ?>
                    <input type="hidden" name="component_status" id="component_status" class="form-control" value="closed">
                    <button class="btn btn-secondary waves-effect" id="export_court_cases" data-toggle="modal" data-target="#myModalExport" ><i class="fa fa-download"></i> Export</button> 
                    <?php echo form_close(); ?>
                    
                  </div>
                        <div class="clearfix"></div>
                        
                         <table  id="tbl_datatable1"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                               <tr>
                                <th><input type="checkbox" name="allCheckboxSelect" id="allCheckboxSelect" class="form-control"></th>
                                
                                <th>Rec Dt</th>
                                <th>Closure Date</th>
                               	<th>Candidate Name</th>
                               	<th>Address</th>
                              	<th>City</th>
                                <th>Pincode</th>
                                <th>State</th>
                                <th>Status</th>
                                <th>Executive</th>
                                <th>TAT</th>
                                <th>Due Date</th>
                                <th>Mode</th>
                                <th>Trans Id</th>
                                <th>Component ID</th>
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
        <h4 class="modal-title header_title">Court Edit</h4>
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


<div id="court_verification_details"  style="display: none;" >
  <div class="content-pages" >
    <br>
    <br>
    <br>
    <br>
      <h2 style="text-align:center;">N.J ASSOCIATES</h2> 
      <p style="text-align: center;"> Practice Office: 606,6TH FLOOR PLOT NO 81, UMA PRIDE SECTOR-17 ULWE RAIGAD 410206</p>
      <p style="text-align:center;"><u><i>TO WHOMSOEVER IT MAY CONCERN</i></u></p>

      <p style="text-align: justify;"><u><b>Search criteria</b></u></p>
        
       <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" align="center">
          <tr><td style="padding-left: 5px;"><b>Date of verification</b></td><td id = "initiation_date" style="padding-left: 5px;"><?php echo date('d-m-Y'); ?></td></tr> 
          <tr><td style="padding-left: 5px;"><b>Reference number</b></td><td id = "reference_number" style="padding-left: 5px;"></td></tr>
          <tr><td style="padding-left: 5px;"><b>Name of the subject</b></td><td id = "subject_name" style="padding-left: 5px;"></td></tr>
          <tr><td style="padding-left: 5px;"><b>Date of Birth</b></td><td id = "date_of_birth" style="padding-left: 5px;"></td></tr>
          <tr><td style="padding-left: 5px;"><b>Father’s Name</b></td><td id = "father_name" style="padding-left: 5px;"></td></tr>
          <tr><td style="padding-left: 5px;"><b>Provided address</b></td><td id = "provided_address" style="padding-left: 5px;"></td></tr>
          <tr><td style="padding-left: 5px;"><b>No of years</b> </td><td style="padding-left: 5px;">10 Years</td></tr>
        </table>

      <p style="text-align: justify;"><u><b>Results</b></u></p>

       <p style="text-align: justify;">i. Civil Proceedings: Original Suits/Miscellaneous Suits/Execution Petition</p>
   
        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" align="center">
          <thead><tr><td style="text-align: center;"><b>Court</b></td><td style="text-align: center;"><b>Jurisdiction</b></td><td style="text-align: center;"><b>Case Type</b></td><td style="text-align: center;"><b>Result</b></td></tr></thead>
          <tbody><tr><td style="text-align: center;">Civil Court</td><td id = "civil_civil_proceed_tbl" style="text-align: center;"></td><td style="text-align: center;">Civil cases</td><td style="text-align: center;">No Records</td></tr>
          <tr><td style="text-align: center;">High Court</td><td id = "civil_high_proceed_tbl" style="text-align: center;"></td><td style="text-align: center;">Civil cases</td><td style="text-align: center;">No Records</td></tr></tbody>
          
        </table>
        <br>
       <p style="text-align: justify;max-width: 92%;">ii. Criminal Proceedings: Criminal Petitions/Criminal Appeal/Sessions Case/Criminal Miscellaneous Petition/Criminal Revision Appeal</p>
   
        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" align="center">
          <thead><tr><td style="text-align: center;"><b>Court</b></td><td style="text-align: center;"><b>Jurisdiction</b></td><td style="text-align: center;"><b>Case Type</b></td><td style="text-align: center;"><b>Result</b></td></tr></thead>
          <tbody><tr><td style="text-align: center;">Magistrate Court</td><td id = "criminal_magistrate_proceed_tbl" style="text-align: center;"></td><td style="text-align: center;">Criminal Cases (CC),Private Complaint Report (PCR)</td><td style="text-align: center;">No Records</td></tr>
          <tr><td style="text-align: center;">Sessions Court</td><td id = "criminal_sessions_proceed_tbl" style="text-align: center;"></td><td style="text-align: center;">Criminal Appeals</td><td style="text-align: center;">No Records</td></tr>
          <tr><td style="text-align: center;">High Court</td><td  id = "criminal_high_proceed_tbl" style="text-align: center;"></td><td style="text-align: center;">Criminal Appeals</td><td style="text-align: center;">No Records</td></tr>
        </tbody>
          
        </table>

        <br>
        <br>  
<p style="text-align: justify;"><b>Conclusion:</b> The search results are based on the available court data base in respect of criminal case/s and suit registers in respect of civil case/s maintained in the above-mentioned court data base having jurisdiction over the address where the candidate was said to be residing. Due care has been taken in conducting the search. The records are public records and the search has been conducted on behalf of your good self and the undersigned is not responsible for any errors, inaccuracies, omissions or deletions if any in the said court data records. The above report is based on the data base confirmed online as on the date on which it is confirmed; hence this verification is subjective evidence. The report has been generated on available records in court record system (e-court), no liability for data unavailable or not found on searches. Please do contact your local police for PVC/PCC.</p>
<p style="text-align: justify;">This is not a certificate but just an information.</p>
</br>

</br>
      <p style="text-align: justify;"> <b>Advocate Name: Nikhil Jadhav </b></p>
      <p style="text-align: justify;"> <b>Bar Council No: MAH /3711/2016 </b></p>
      
      <p style="text-align:right;"><img src="<?php echo SITE_IMAGES_URL ?>signature.png" alt = "signature"></p>

    <br>
    <br>
    <br>
    <br>
</div>
</div>
<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script type="text/javascript">


//var screen_percentage = Math.round(window.devicePixelRatio * 100);
//if((screen_percentage > 100) || (screen_percentage < 100))
//{
 // alert("Please keep windows zoom level on 100%");
//}

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
        "iDisplayLength": 15, // per page
        "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "All"]],
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?php echo VENDOR_SITE_URL.'courtver/court_view_datatable_assign_closed'; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'start_date':function(){return $("#start_date").val(); },'end_date':function(){return $("#end_date").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); } }
        } ),
        order: [[ 2, 'desc' ]],
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
        "columns":[{'data' :'checkbox'},{'data' :'created_on'},{'data':'modified_on'},{"data":"CandidateName"},{'data' :'street_address'},{'data' :'city'},{'data' :'pincode'},{'data' :'state'},{'data' :'status'},{'data' :'vendor_executive_id'},{'data' :'test2'},{"data":"tat_status"},{'data':'vendor_list_mode'},{'data':'trasaction_id'},{'data':'court_com_ref'}]
  });

   $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();

   
      $('#myModalCasesedit').modal('show');
      $('#myModalCasesedit').addClass("show");
      $('#myModalCasesedit').css({background: "#0000004d"}); 
   
      $.ajax({
      type : 'POST',
      url : '<?php echo VENDOR_SITE_URL.'courtver/view_vendor_logs/' ?>',
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
          url:'<?php echo VENDOR_SITE_URL.'courtver/assign_to_executive/' ?>',
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
    var filter_by_status = $('#filter_by_status').val();

    if(start_date > end_date)
    {
      alert('Please select end date greater than start date');
    }
    else
    {
    var new_url = "<?php echo VENDOR_SITE_URL.'courtver/court_view_datatable_assign_closed'; ?>?filter_by_status="+filter_by_status+"&start_date="+start_date+"&end_date="+end_date;
    oTable.ajax.url(new_url).load();
    }
  });  

   $('#btn_reset').on('click', function() {

    $('#start_date').val(''); 
    $('#end_date').val(''); 
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var filter_by_status = $('#filter_by_status').val();

    var new_url = "<?php echo VENDOR_SITE_URL.'courtver/court_view_datatable_assign_closed'; ?>?start_date="+start_date+"&end_date="+end_date+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
  });

  $('#filter_by_status').on('change', function(){
    var filter_by_status = $('#filter_by_status').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
  
    var new_url = "<?php echo VENDOR_SITE_URL.'courtver/court_view_datatable_assign_closed'; ?>?start_date="+start_date+"&end_date="+end_date+"&filter_by_status="+filter_by_status;
    oTable.ajax.url(new_url).load();
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
        
      }
     
    },
    messages: {
    
      transaction_id : {
        required : "Enter Enter tansaction ID"
      },
      /*  vendor_remark : {
        required : {
         depends: function () {
            if( $('#status').val() != "wip"){
              return "Enter Vendor Remark";
            }
          }
        }
      }*/
    },
    submitHandler: function(form) 
    {
     // var screen_percentage = Math.round(window.devicePixelRatio * 100);
    //  if((screen_percentage > 100 && screen_percentage < 100) ||  (screen_percentage == 100))
    //  {
       document.getElementById("court_verification_details").style.display = "block";
        div_content =  document.getElementById('court_verification_details');

        html2canvas(div_content).then(function(canvas) {
                    //change the canvas to jpeg image
                    data = canvas.toDataURL('image/png');
        
       
      var formData = new FormData(form);
      formData.append('img', data);

      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'courtver/update_court_wip'; ?>',
        data : formData,
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
     });
   // }
   // else
   // {
   //     alert("Please keep windows zoom level on 100%");
  //  }           
    }
  });

  $('#export_court').validate({
    
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo VENDOR_SITE_URL.'courtver/export_court'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#export_court_cases').attr('disabled',true);
        },
        complete:function(){
          $('#export_court_cases').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xls");
            $a[0].click();
            $a.remove();
        });              
    }
  });


});


</script>
