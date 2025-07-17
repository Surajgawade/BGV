<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h5 class="page-title">Candidate - List View</h5>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>candidates/initiation_list">Initiation List</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            </div>
          </div>
      </div>
     


  <div class="tab-content">
   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
               
              <div style="float: right;">
                <button class="btn btn-info" data-url ="<?= CLIENT_SITE_URL.'candidates/add_initiation' ?>"  data-toggle="modal" id="add_initiation1"> <i class="fa fa-plus"></i> Add Case</button>    
              </div>
              <br>
              <br>
              <div class=clearfix></div>

                      <table id="tbl_datatable_initiation" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Received Date</th>
                            <th>Candidate Name</th>
                            <th>Status</th>
                            <th>Report</th>
                            <th>Mist ID</th>
                            <th>Client ID</th>
                            <th>Entity</th>
                            <th>Package</th>
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
  </div>


<div id="EditCandidateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog" style="max-width: 900px;">

    <?php echo form_open_multipart("#", array('name'=>'edit_candidates','id'=>'edit_candidates')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Edit Candidates</h5>
      </div>
      <span class="errorTxt"></span>
     <div class="modal-body">
      <div id="append_candidate_edit_model"></div>
     </div>
      <div class="modal-footer">
        <button type="button" id="editCandidateBack" name="editCandidateBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbeditcandidate" name="sbeditcandidate" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="addInitiationModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog"  style="max-width: 900px;">

    <?php echo form_open_multipart("#", array('name'=>'add_initiation','id'=>'add_initiation')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Add Case</h5>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body">
      <div id="append_initiation_model"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="addInitiationBack" name="addInitiationBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbinitiation" name="sbinitiation" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>

$('#clientid_candidate').attr("style", "pointer-events: none;");


   var oTable =  $('#tbl_datatable_initiation').DataTable( {
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
        "iDisplayLength": 15, // per page
        "language": {
          "emptyTable": "No Record Found",
          "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 100px;'>" 
        },
        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view_initiation'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST', 
            data: { } 
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
        "columns":[{"data":"id"},{"data":"initiation_date"},{"data":"candidate_name"},{"data":"status"},{"data":"report"},{"data":"component_ref_no"},{"data":"clientname"},{"data":"entity"},{'data':'package'}]
    } );

    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });


  
$(document).on('click','#add_initiation1',function() {
    var url = $(this).data('url');
    $('#append_initiation_model').load(url,function(){
      $('#addInitiationModel').modal('show');
      $('#addInitiationModel').addClass("show");
      $('#addInitiationModel').css({background: "#0000004d"}); 
    });
});

  $('#add_initiation').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      entity : {
        required : true,
        greaterThan : 0
      },
      package : {
        required : true,
        greaterThan : 0
      },
      caserecddate : {
        required : true
      },
      ClientRefNumber : {
        required : true,
        remote: {
          url: "<?php echo CLIENT_SITE_URL.'candidates/is_client_ref_exists_pre_post' ?>",
          type: "post",
          data: { username: function() { return $( "#ClientRefNumber" ).val(); },clientid: function() { return $( "#clientid" ).val(); }}
        }
      },     
      CandidateName : {
        required : true
      },
      CandidatesContactNumber : {
        required : true
      }

    },
    messages: {
      clientid : {
        required : "Enter Client Name"
      },
      entity : {
        required : "select Entiy",
        greaterThan : "select Entiy"
      },
      package : {
        required : "select Package",
        greaterThan : "select Package"
      },
      caserecddate : {
        required :  "Select Case Received Date"
      },
      ClientRefNumber : {
        required : "Enter Client Ref No",
        remote:  "{0} Client/Employee ID Exists"
      },     
      CandidateName : {
        required : "Enter Candidate Name"
      },
      CandidatesContactNumber : {
        required : "Enter Contact No"
      }
    },
    submitHandler: function(form) 
    {  
       
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/frm_add_initiation'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#sbinitiation').attr('disabled','disabled');
            },
            complete:function(){
              $('#sbinitiation').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $('#addPrePostModel').modal('hide');
                 location.reload();
                return;
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
    }
  });


jQuery.validator.addMethod("noSpace",function(value,element){

      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");    

 
</script>
<script type="text/javascript">
  $( document ).ready(function() {
  var clientid = $('#clientid_candidate').val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/get_entity_list'; ?>',
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


</script>

