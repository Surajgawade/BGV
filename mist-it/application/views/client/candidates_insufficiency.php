<style type="text/css">

  .tablink:hover {
  background-color: #777;
  }
  table tr.separator{ 
  height: 30px;
}
  td.details-control {
    background: url('assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('assets/images/details_close.png') no-repeat center center;
}
</style>
<div class="content-page">
<div class="content">
<div class="container-fluid">

        <div class="row">
               <div class="col-sm-12">
                  <div class="page-title-box">
                      <h4 class="page-title">Candidates - All Components Insufficiency</h4>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                           <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>Overall_insufficiency">Insufficiency</a></li>
                           <li class="breadcrumb-item active">List View</li>
                        </ol>
                
                  </div>
                </div>
          </div>

          <div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-justified"> 
        
             <?php   echo "<li class='nav-item waves-effect waves-light active'  role='presentation' data-tab_name='candidate_wise' ><a class = 'nav-link active' href='#candidate_wise' aria-controls='home' data-toggle='tab'>Candidate Wise</a></li>";
 
              echo "<li  role='presentation' data-url='".CLIENT_SITE_URL."insufficiency/get_component_insufficiency/'  data-tab_name='component_wise'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#component_wise' aria-controls='home' role='tab'  data-toggle='tab'>Component Wise</a></li>"; 
             
              ?>                        
            </ul>
          </div>     


      <div class="tab-content">
        <div id="candidate_wise" class="tab-pane active">
          <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">
                <form>
                <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <?php echo form_dropdown('clientid_candidate', $clients, set_value('clientid_candidate',$this->session->userdata('client')['client_id']), 'class="custom-select" id="clientid_candidate"');?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <?php echo form_dropdown('entity_candidate',array(), set_value('entity_candidate'), 'class="custom-select" id="entity_candidate"');?>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <select id="package_candidate" name="package_candidate" class="custom-select"><option value="0">Select</option></select>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <input type="button" name="searchrecords_candidate" id="searchrecords_candidate" class="btn btn-md btn-info" value="Filter">
                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
                </div>
                </form>
             <br>


                   <table id="tbl_datatable1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                           <th>#</th>
                            <th></th>
                            <th>Case Received Date</th>
                            <th>Candidate Name</th>
                            <th>Overall Status</th>
                            <th>Insufficiency</th>
                            <th>Remarks</th>
                            <th>Entity</th>
                            <th>Package</th>
                            <th>Client Ref No</th>
                            <th><?php echo REFNO;?></th>
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

          <div id="component_wise" class="tab-pane fade in">
            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                  <div class="card-body">

                    <table id="datatable-insuff_view" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                    <th>#</th>
                    <th>Initiated Date</th>
                    <th>Component Name</th>
                    <th>Client Name</th>
                    <th>Entity Name</th>
                    <th>Package Name</th>
                    <th>Candidate Name</th>
                    <th>Component Id</th>
                    <th>Raised Date</th>
                    <th>Insuff Reason</th>
                    <th>Remarks</th>
                    <th>Pending From</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i = 1;

                    foreach ($insuff_details as $value)
                    {
                      $remarks = substr($value['insuff_raise_remark'], 0, 50);
                      echo "<tr>";
                      echo "<td>".$i."</td>"; 
                      echo  "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";  
                      echo  "<td>".$value['component_name']."</td>";
                      echo  "<td>".$value['clientname']."</td>";
                      echo  "<td>".$value['entity_name']."</td>";
                      echo  "<td>".$value['package_name']."</td>";
                      echo  "<td>".$value['CandidateName']."</td>";
                      echo  "<td>".$value['component_id']."</td>";
                       echo  "<td>".$value['insuff_raised_date']."</td>";
                      echo  "<td>".$value['insff_reason']."</td>";
                      echo  "<td>".wordwrap($value['insuff_raise_remark'],45,"<br>\n")."</td>";
                      echo  "<td></td>";
                      echo '<td> <button data-id="'.$value['id'].'"  data-candsid="'.$value['candidate_id'].'"  data-toggle="modal" data-target="#insuffClearModel"  data-clientid="'.$value['clientid'].'" data-accessUrl="'.$value['insuff_id'].'" data-controller="'.$value['controller'].'" class="btn btn-sm btn-info  tbl_row_edit" style="margin-left: 75px;">Clear</button></td>';
                     
                
                      $i++;
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>


<div id="insuffClearModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open_multipart("#", array('name'=>'frm_insuff_clear','id'=>'frm_insuff_clear')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insuff Clear</h4>
      </div>
      <div class="modal-body">
        <span class="errorTxt"></span>
        <input type="hidden" name="check_insuff_raise" id="check_insuff_raise" value="<?php echo set_value('check_insuff_raise'); ?>">
        <input type="hidden" name="controller" id="controller" value="">
        <input type="hidden" name="clear_clientid" id="clear_clientid" value="<?php echo set_value('clientid'); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="">
        <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Clear Date<span class="error"> *</span></label>
          <input type="text" name="insuff_clear_date" id="insuff_clear_date" value="<?php echo set_value('insuff_clear_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('insuff_clear_date'); ?>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-8 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_remarks" rows="1" maxlength="500" id="insuff_remarks"  class="form-control"><?php echo set_value('insuff_remarks'); ?></textarea>
          <?php echo form_error('insuff_remarks'); ?>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" acceprt = ".jpeg, .jpg, .png, .pdf" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
        </div>
         <div class="clearfix"></div>
      </div>  
      <div class="modal-footer">
        <button type="submit" id="btn_insuff_clear" name="btn_insuff_clear"  class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
  $('#clientid_candidate').attr("style", "pointer-events: none;");
//var status = "<?php echo $this->uri->segment(2); ?>";

 $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

$( document ).ready(function() {
  var clientid = $('#clientid_candidate').val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'Insufficiency/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_candidate').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_candidate').html(html);
          }
      });
  }
});

$(document).on('change', '#entity_candidate', function(){
  var entity = $(this).val();
  var selected_clientid = '';
  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'Insufficiency/get_package_list'; ?>',
          data:'entity='+entity+'&selected_clientid='+selected_clientid,
          beforeSend :function(){
            jQuery('#package_candidate').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#package_candidate').html(html);
          }
      });
  }
});



 $('#datatable-insuff_view').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      "searching": false,
      scrollX: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },
      "lengthChange": true,
      "lengthMenu": [[10,25, 50, 100, -1], [10,25, 50, 100, "All"]]
  });

$('input[type="search"]').val(status);




$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '<?php echo CLIENT_SITE_URL.'insufficiency/data_table_cands_view_insufficiency'; ?>/',
        data: {'status': status},  
        method: 'POST' // Ajax HTTP method
    }, opts );
 
   // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {      
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( $.isFunction ( conf.data ) ) {                
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            settings.jqXHR = $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};

function insertLines (a) {

    var a_split = a.split(" ");
    var res = "";

    for(var i = 0; i < a_split.length; i++) {
        res += a_split[i] + " ";
        if ((i+1) % 10 === 0)
            res += "<br>";
    }

    return res;
}

function format ( d ) {
  
  var expand_data = '';

  var expand_data = '<table  class ="child-table" cellpadding="5"  cellspacing="0" border="0" style="padding-left:50px;margin-left:60px;">';

  expand_data += '<tr><th>Component Name</th><th>Component Id</th><th>Component Received Date</th><th>Raised Date</th><th>Raised Remark</th><th>Action</th></tr>';
 /* for (var key in d.address){

        if((key.slice(0,-1) == "Clear button") )
       {
        
          

       }
       else
       { 
           expand_data += '<tr>';
       }


       if(key.slice(0,-1) == "Component Name")
       {
        
       }
       else if(key.slice(0,-1) == "Clear button")
       {
          expand_data += '';
       }
       else
       {
          expand_data += '<td>' + key.slice(0,-1) +'</td>';
       }
       if(key.slice(0,-1) == "Component Name")
       {
          expand_data += '<td style="font-weight:bold;text-align:center;" colspan = "2">' + d.address[key] +'</td>';
       }
       else if(key.slice(0,-1) == "Raised Remark")
       {
          expand_data += '<td style="padding-left: 40px">' +  d.address[key] +'</td></tr><tr class="separator" ></tr>';
       }
       else if(key.slice(0,-1) == "Clear button")
       {
         expand_data += '<td rowspan="3">' + d.address[key] +'</td>';
       }
       else
       {
          expand_data += '<td  style="padding-left: 40px">' + d.address[key] +'</td>';

       }
     
     
    } */
 // expand_data += '<tr>';
  for (var key in d.address){

       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }
 
         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.address[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.address[key] +'</td>';
         }  

    }
    //  expand_data += '<tr>'; 
    expand_data += '</tr>';
    for (var key in d.employment){

       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.employment[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.employment[key] +'</td>';
         }
      

    }  
   expand_data += '</tr>';
    for (var key in d.education){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }
      
         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.education[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.education[key] +'</td>';
         }
      

    } 
    expand_data += '</tr>';
    for (var key in d.reference){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }
       
         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.reference[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.reference[key] +'</td>';
         }


    }  
    expand_data += '</tr>';
    for (var key in d.court){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.court[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.court[key] +'</td>';
         }

    }  
    expand_data += '</tr>';
    for (var key in d.global){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

        if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.global[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.global[key] +'</td>';
         }

    }  
    expand_data += '</tr>';
    for (var key in d.pcc){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.pcc[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.pcc[key] +'</td>';
         }
    } 
    expand_data += '</tr>';
    for (var key in d.identity){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.identity[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.identity[key] +'</td>';
         }
    } 
    expand_data += '</tr>';
    for (var key in d.credit_report){


       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }

         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.credit_report[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.credit_report[key] +'</td>';
         }
    } 
    expand_data += '</tr>';
    for (var key in d.drugs){

      
       if(key.slice(0,-1) == "Component Name")
       {
           expand_data += '<tr>'; 
       }
      
         if(key.slice(0,-1) == "Raised Remark")
         {  
          expand_data += '<td>' + insertLines(d.drugs[key]); +'</td>';
         }
         else
         {
          expand_data += '<td >' + d.drugs[key] +'</td>';
         }

    }     
    expand_data += '</tr>';


    expand_data += '</table>' ;   
  return expand_data ;
}


$(document).ready(function() {
   var oTable =  $('#tbl_datatable1').DataTable( {
        "processing": true,
        "serverSide": true,
         scrollX : true,
        "iDisplayLength": 10, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CLIENT_SITE_URL.'insufficiency/data_table_cands_view_insufficiency'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST',  
        } ),
         order: [[ 1, 'desc' ]],
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
        "columns":[{
                "class":          'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{'data':'insufficiency_check'},{"data":"remarks"},{"data":"Entity"},{"data":"Package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}]
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });

     $('#tbl_datatable1 tbody').on('click', 'td.details-control', function () {
        var tr = $(this).parents('tr');
        var row = oTable.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
             if ( oTable.row( '.shown' ).length ) {
              $('.details-control', oTable.row( '.shown' ).node()).click();
            }
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

   
   $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
    if(data['edit_id'] != 0)
      window.location = data['edit_id'];
    else
      show_alert('Access Denied, You don’t have permission to access this page');
  });

    $('#clientid_candidate,#entity_candidate,#package_candidate').on('change', function(){
   
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
  
    var new_url = "<?php echo CLIENT_SITE_URL.'insufficiency/data_table_cands_view_insufficiency'; ?>?clientid="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords_candidate').on('click', function() {
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
  
    var new_url = "<?php echo CLIENT_SITE_URL.'insufficiency/data_table_cands_view_insufficiency'; ?>?clientid="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
   
  });


  });

  $(document).on('click', '.view_details', function(){
    var id = "<?php echo SITE_URL.'candidates/view_details/' ?>"+$(this).data("raw_id");

    $('.append-data').load(id,function(){
        $('#myModal').modal('show');
  });
});

$(document).on('click', '.tbl_row_edit', function(){

    var accessUrl = $(this).attr('data-accessUrl');
    var controller = $(this).data('controller');
    var clientid = $(this).data('clientid');
    var component_id = $(this).data('id');
    var candsid = $(this).data('candsid');
   
    if(controller != "" && accessUrl)
    {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL; ?>insufficiency/insuff_raised_data/'+controller,
          data : 'insuff_data='+accessUrl,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
          },
          complete:function(){
              jQuery('.body_loading').hide();
          },
          success:function(jdata) {
            $('#insuff_clear_id').val(accessUrl); 
            $('#clear_update_id').val(component_id); 
            $('#controller').val(controller);
            $('#clear_clientid').val(clientid);
            $('#candidates_info_id').val(candsid);
            $('#check_insuff_raise').val(jdata['insuff_raised_date']);
            $('#insuffClearModel').modal('show');
            $('#insuffClearModel').addClass("show");
            $('#insuffClearModel').css({background: "#0000004d"});
          }
      });
    } else { 
      show_alert('Access Denied, You don’t have permission to clear insuff');
    }
});

$('#frm_insuff_clear').validate({ 
    rules: {
      clear_update_id : {
        required : true
      },
      controller : {
        required : true
      },
      insuff_clear_date : {
        required : true
      },
      insuff_remarks : {
        required : true
      },
      "clear_attchments[]": {
        extension: "jpeg|jpg|png|pdf"
      }
    },
    messages: {
      clear_update_id : {
        required : "Update ID missing"
      },
      controller : {
        required : "access URL"
      },
      insuff_clear_date : {
        required : "Select Insuff CLear Date"
      },
      insuff_remarks : {
        required : "Enter remarks"
      },
      "clear_attchments[]": {
        extension: "Please upload valid file formats"
      }
    },
    submitHandler: function(form) 
    {      
        var controller = $('#controller').val();
        $.ajax({
          url : '<?php echo CLIENT_SITE_URL; ?>insufficiency/insuff_clear/'+controller,
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_insuff_clear').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_insuff_clear').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffClearModel').modal('hide');
            $('#frm_insuff_clear')[0].reset();
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




</script>

<!--<script type="text/javascript">
  function openPage(pageName, elmnt, color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }

  document.getElementById(pageName).style.display = "block";

    elmnt.style.backgroundColor = color;
}

document.getElementById("defaultOpen").click();
</script>-->


 