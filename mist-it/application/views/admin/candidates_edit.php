<div class="content-page">
<div class="content">
<div class="container-fluid">

    
    <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Candidate</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidate</a></li>
                  <li class="breadcrumb-item active">Candidate Edit</li>
                  <li class="breadcrumb-item active"><?php echo $candidate_details['CandidateName']; ?></li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                   <li><button class="btn btn-secondary waves-effect btn_clicked btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>candidates"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>
   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            <div class="nav-tabs-custom">
      
             <ul class="nav nav-pills nav-justified" role="tablist">
                <?php
                $components = json_decode($client_components['component_name'],true);
             
                $selected_component = explode(',', $client_components['component_id']);
                $is_check = array_column($this->user_info['menu'], 'controllers');
                $active_tab = '';

                echo "<li class='nav-item waves-effect waves-light'><a class = 'nav-link active' href='#candidate_tab' data-toggle='tab' role='tab'>Candidate</a></li>";

                foreach ($components as $key => $component) 
                {

                    if(in_array($key, $selected_component))
                    {

                      $tabs_panels[] = $key;
                      $component  =   explode(' ',trim($component));
                      $component  =   explode('/',trim($component[0]));
                      echo "<li role='presentation' data-url='".ADMIN_SITE_URL."candidates/ajax_tab_data/' data-can_id=".$candidate_details['id']." data-tab_name=".$key." class='view_component_tab nav-item waves-effect waves-light'><a class='nav-link' href='#".$key."' aria-controls='home' role='tab' data-toggle='tab'>  ".$component[0]."</a></li>";
                    }
                }
                
                ?>
              </ul>
              <div class="tab-content">
               <!--<div  class="container" style="width: 100%; border-style: ridge;">-->
                <?php
                  echo "<div class='active tab-pane' id='candidate_tab'>";
                  $this->load->view('admin/candidates_edit_view');

                  ?>
                  <input type="hidden" id="check_status" name="check_status">
                 <hr border-top: 2px solid #bb4c4c;>

                
                <div class="nav-tabs-custom">
                  <ul class="nav nav-pills nav-justified">
                     <?php  echo "<li class='nav-item waves-effect waves-light active'  role='presentation' data-url='".ADMIN_SITE_URL."address/approval_queue/' data-can_id='1' data-tab_name='view_candidate_log_tabs'  class='view_component_tab'><a class = 'nav-link active' href='#view_candidate_log_tabs' aria-controls='home' data-toggle='tab'>Candidate Log</a></li>";?>
                    <!--<li class="active"><a href="#result_log_tabs" id="view_candidate_log_tabs" data-toggle="tab">Candidate Log</a></li>-->
                     <li class='nav-item waves-effect waves-light'><a class = 'nav-link'  href="#tab_candidate_report_log"  data-cands_id="<?php echo $candidate_details['id']; ?>"  id="view_candidate_report_log" data-toggle="tab">Reports Log</a></li>  
                  </ul>
                   <br>
                  <div class="tab-content">
                   
                    <div class="tab-pane active" id="view_candidate_log_tabs">
                      <table id="tbl_datatable" class="table table-bordered table-hover" width="100%">
                    <thead>
                      <tr>

                        <th>Sr No</th>
                        <th>Modofied On</th>
                        <th>Modified By</th>
                        <th>Type</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                   
                    </tbody>
                  </table>
                      
                    </div>


                     <div class="tab-pane" id="tab_candidate_report_log">

                          <div style="float: right;">
     
                            <button class="btn btn-info btn-sm"  data-toggle="modal" data-target="#ImportReport">Import Report</button>


                          <?php

                            if($interiam_final_report == 1)
                            {?>
                             <button class="btn btn-info btn-sm" data-url ="<?php echo ADMIN_SITE_URL.'Final_QC/report_html_form_final/'.encrypt($candidate_details['id']).'/Final' ?>" id ="CreateFinalReport">Create Final</button>&nbsp;

                             
                          <?php  }
                            if($interiam_final_report == 2)
                            {
                              ?>
                             <button class="btn btn-info btn-sm"  data-url ="<?php echo ADMIN_SITE_URL.'Final_QC/report_html_form_final/'.encrypt($candidate_details['id']).'/Interiam' ?>" id ="CreateInterimReport">Create Interim</button>&nbsp;
                           <?php 
                             }

                            ?>
                           
                              <a target="__blank" href="<?php echo ADMIN_SITE_URL.'candidates/report_client/'.encrypt($candidate_details['id']).'/final_report'?>" style="float: right;"> <button type="button" class="btn btn-info btn-sm" > Download </button></a><br>
                          </div>
                           <div class="clearfix"></div>
                          <!--<div id='tbl_report_log'></div>-->
                        <table id="tbl_report_log" class="table table-bordered datatable_logs"></table>

                     </div>
                  </div>
                </div>

      
       
               <?php  echo "</div>";
                  foreach ($tabs_panels as $key => $tabs_panel) 
                  { 
                      echo "<div id='".$tabs_panel."' class='tab-pane fade in'>";

                      echo "</div>";
                  }
                ?> 
              </div>
            </div>

        </div>
      </div>


   
    </div>
  </div>
    
</div>
</div>
</div>


<div id="showcandidatelog" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidate Log  Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_vendor_model"></div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="ImportReport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Report</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_cands_report_file','id'=>'frm_cands_report_file')); ?>   
      <div class="modal-body">
          <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
          <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="cands_report_file" id="cands_report_file" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-md" id="import_candidate_report">Submit</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
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
          
          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
          <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
          <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_final_result"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

         <button type='submit' class='btn btn-info' type='button' name='brn_final_qc_approve' id='brn_final_qc_approve'>Approve</button>
         
         
           <!-- <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>-->
           

            <a target="__blank" href="<?php echo ADMIN_SITE_URL.'candidates/report/'.encrypt($candidate_details['id']).'/final_report' ?>" style="float: right;"> <button type="button" class="btn btn-danger"> Download </button></a>

            <button type='button' class='btn btn-default' data-dismiss="modal">Cancel</button>&nbsp;

      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showInterimReportModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'save_interim_report','id'=>'save_interim_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Interim QC</h4>
      </div>

          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
         <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
         <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_interim_result"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

         <button type='submit' class='btn btn-info' type='button' name='brn_interim_qc_approve' id='brn_interim_qc_approve'>Approve</button>
         
         
          <!--  <button type='button' class='btn btn-warning' type='button' name='brn_final_qc_reject' id='brn_final_qc_reject'>Reject</button>-->
          

            <a target="__blank" href="<?php echo ADMIN_SITE_URL.'candidates/report/'.encrypt($candidate_details['id']).'/final_report' ?>" style="float: right;"> <button type="button" class="btn btn-danger"> Download </button></a>


           <button type='button' class='btn btn-default' data-dismiss="modal"'>Cancel</button>&nbsp;

      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="showFinalReportModel1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'save_final_report','id'=>'save_final_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4>Final QC</h4>
      </div>
    
          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
          <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
          <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_final_result1"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>


            <button type='button' class='btn btn-default' data-dismiss="modal"'>Cancel</button>&nbsp;

      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showInterimReportModel1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'save_interim_report','id'=>'save_interim_report')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4>Interim QC</h4>
      </div>

          <input type="hidden" name="candidate_status"  id = "candidate_status" value="<?php echo set_value('candidate_status',$candidate_details['overallstatus']); ?>">
         <input type="hidden" name="candidate_id"  name="candidate_id" value="<?php echo set_value('candidate_id',$candidate_details['id']); ?>">
         <input type="hidden" name="ClientRefNumber"  name="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>">
      <span class="errorTxt"></span>
      <div id="append_interim_result1"></div>
      <div class="modal-footer" style="margin-top: 0px;">
           <button type="button" id="addFinalReportBack" name="addFinalReportBack" class="btn btn-default btn-sm pull-left">Back</button>

           <button type='button' class='btn btn-default' data-dismiss="modal"'>Cancel</button>&nbsp;

      <!--   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button> -->
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_view','id'=>'add_verificarion_result_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Result Log Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_result_model2"></div>
      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="report_mail" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidate Report Mail</h4>
      </div>
      <?php echo form_open('#', array('name'=>'send_candidate_report_mail','id'=>'send_candidate_report_mail')); ?>
      <div class="modal-body">
    <div align="right">
        <button type="submit" id="send_mail_report_frm" name="send_mail_report_frm" class="btn btn-success"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>

</br>

        <div class="row">
        <input type="hidden" name="candidate_user_id" value="<?php echo set_value('candidate_user_id',$candidate_details['id']); ?>">
        <input type="hidden" name="report_type"  id="report_type"  value="">
        <input type="hidden" name="activity_log_id"  id="activity_log_id"  value="">
         
          <div class="div1"> 
            <br>

            <div class="form-row mb-3">
                <label for="from" class="col-2 col-sm-1 col-form-label">From:</label>
                <div class="col-10 col-sm-11">
                    <input type="email" class="form-control" id="from" name="from" placeholder="Type email" value="<?php echo $this->user_info['email']; ?>" readonly>
                </div>
            </div>
      

            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php echo  $client_name.' - Report/s - '.date('d-M-Y') ?>" readonly>
                </div>
            </div>
           <?php 
             $client_manager_email = array_map('current', $client_manager_email_id);
            ?>
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="to_email" name="to_email" placeholder="Insert Multiple Email Id using comma separated"  value="<?php echo implode(' , ', $client_manager_email);?>">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="cc_email" name="cc_email" placeholder="Insert Multiple Email Id using comma separated" value="<?php  echo $reporting_manager_email['email']." , ".$this->user_info['email'];  ?>" readonly>
                </div>
            </div>
            
           <div class="clearfix"></div>
        
         </div> 
         <br>
        
          <div class="append-email_report"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="send_mail_report_frm" name="send_mail_report_frm" class="btn btn-success"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script type="text/javascript">
 $(document).on('click','.showcandidatelog',function() {

    var url = $(this).data('url');
    var id = $(this).data('id');



    $('#append_vendor_model').load(url,function(){
      $('#showcandidatelog').modal('show');
     
    /*  $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/vendor_logs_cost/' ?>',
      data: 'id='+id,
      beforeSend :function(){
        jQuery('#tbl_vendor_log1').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {

            $('#tbl_vendor_log1').html(message);

        }
        else
        {
          $('#tbl_vendor_log1').html(message);
        }

          
          
        }
    }); */

    });
   
});
</script>
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
            url: '<?php echo ADMIN_SITE_URL.'candidates/view_candidate_log/'.$candidate_details['id']; ?>',
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { 'filter_by_clientid':function(){return $("#filter_by_clientid").val(); },'filter_by_status':function(){return $("#filter_by_status").val(); }, }
        } ),
        order: [[ 3, 'desc' ]],
        'columnDefs': [
           {
              'targets': 0
              
           }
        ],
        
        "columns":[{'data' :'id'},{"data":"created_on"},{'data' :'create_by'},{'data' :'is_bulk_upload'},{"data":"encry_id"}]
  });

  $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0alert(data['encry_id']);

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

     
  });

});

$(document).on('click', '#view_candidate_report_log', function(){
  var cands_id = $(this).data('cands_id');

  if(cands_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'candidates/report_logs/'; ?>'+cands_id,
           data: '',
          beforeSend :function(){
            jQuery('#tbl_report_log').html("Loading..");
         },
         success:function(jdata)
         {
          var message = jdata.message;
          if(jdata.status = 200)
          {

            if(jdata.check_status == "2")
            {
             //  $("#CreateInterimReport").prop('disabled',true);
            }  
            $("#check_status").val(jdata.check_status);
            $('#tbl_report_log').html(message);

          }
          else
          {
             $("#check_status").val(jdata.check_status);
            if(jdata.check_status == "2")
            {
              // $("#CreateInterimReport").prop('disabled',true);
            }
            $('#tbl_report_log').html(message);
          }

          
            var tbl_report_log =  $('#tbl_report_log').DataTable( { "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 25,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });
        }
    }); 
  }
}); 


 $(document).on('click','.showAddResultModel',function() {
    var url = $(this).data('url');
    $('#append_result_model2').load(url,function(){
      $('#showAddResultModel').modal('show');
    });
   
});

 /*$(document).on('click','.downloadinteriamfinalreport',function() {
    var url = $(this).data('url');
    var filename = $(this).data('file-name');
     
      $('.downloadinteriamfinalreport').load(url+"/"+filename,function(){});
    
   
});*/
/*$(document).on('click','.downloadinteriamfinalreport',function() {
    
    
    
    window.location.href = "<?= SITE_URL.UPLOAD_FOLDER.'candidate_report_file/final_report_2020-06-11-22-05-28.html'; ?>" ;
})*/

 


 $('#CreateFinalReport').click(function(){
    var url = $(this).data("url");
    $('#append_final_result').load(url,function(){});
    $('#showFinalReportModel').modal('show');
    $('#showFinalReportModel').addClass("show");
    $('#showFinalReportModel').css({background: "#0000004d"}); 
  });

 $('#CreateInterimReport').click(function(){

    var check_interiam_value =  $("#check_status").val();
    var url = $(this).data("url");
    if(check_interiam_value == "2")
    {
    $('#append_interim_result').load(url,function(){});
    $('#showInterimReportModel').modal('show');
    $('#showInterimReportModel').addClass("show");
    $('#showInterimReportModel').css({background: "#0000004d"}); 
    }

    if(check_interiam_value == "1")
    {
      show_alert('Access Denied, Please Clear First QC Pending First');
    }
  });

$(document).on('click','.CreateFinalReport',function() {
    var url = $(this).data("url");
   
    $('#append_final_result1').load(url,function(){});
    $('#showFinalReportModel1').modal('show');
  });

$(document).on('click','.CreateInterimReport',function() {
    var check_interiam_value =  $("#check_status").val();
    var url = $(this).data("url");
    if(check_interiam_value == "2")
    {
    $('#append_interim_result1').load(url,function(){});
    $('#showInterimReportModel1').modal('show');
    }

    if(check_interiam_value == "1")
    {
      show_alert('Access Denied, Please Clear First QC Pending First');
    }
  });

 $('#brn_final_qc_approve').click(function(){ 
 
  $('#save_final_report').validate({ 
   
      submitHandler: function(form) 
      {  
  
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'Final_QC/save_final_report'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#brn_final_qc_approve').attr('disabled','disabled');
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
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
  });

 

});

  $('#brn_interim_qc_approve').click(function(){ 
 
  $('#save_interim_report').validate({ 
   
      submitHandler: function(form) 
      {  
  
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'Final_QC/save_interiam_report'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#brn_interim_qc_approve').attr('disabled','disabled');
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
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
  });

 

});

  $('#frm_cands_report_file').validate({ 
    rules: {
      candidate_id : {
        required : true
      }
    },
    messages: {
      candidate_id : {
        required : "Candidate ID missing"
      }
    },
      submitHandler: function(form) 
      {  
     
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'candidates/save_report'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#import_candidate_report').attr('disabled','disabled');
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success: function(jdata){
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
  });

  
 $(document).on('click','.candidate_report_mail',function() {
    var url = $(this).data('url');
    var activity_log_id = $(this).data('id');
    var arr = url.split('/');
    $('#report_type').val(arr[8]);
    $('#activity_log_id').val(activity_log_id);
    $('.append-email_report').load(url,function(){
      $('#report_mail').modal('show');
    }); 
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
        multiemails : "Enter Valid Email ID",
        matches: "use be a  e-mail address"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'candidates/report_send_mail'; ?>",
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
    
    jQuery.validator.addMethod('matches', function (value, element) {
    return this.optional(element) || /^[a-z0-9](\.?[a-z0-9]){5,}@m$/i.test(value);
    }, "use be a  e-mail address");

 </script>
