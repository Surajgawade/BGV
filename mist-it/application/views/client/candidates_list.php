<style type="text/css">

a {
    color: #007bff;
}
i {
  font-size: 22px;
}
</style>

<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h5 class="page-title">Candidate - List View</h5>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo CLIENT_SITE_URL ?>candidates/add_candiddate_view">Add Candidate</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            </div>
          </div>
      </div>
     
      
      <div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-justified"> 
        
             <?php   
              echo "<li class='nav-item waves-effect waves-light active'  role='presentation' data-tab_name='candidate_list_add' ><a class = 'nav-link active' href='#candidate_list_add' aria-controls='home' data-toggle='tab'>Candidate List</a></li>";
 
              echo "<li  role='presentation' data-url='".CLIENT_SITE_URL."candidates/get_candidate_mail_list/'  data-tab_name='candidate_pending'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#candidate_pending' aria-controls='home' role='tab'  data-toggle='tab'>Pending From Candidate</a></li>"; 
             
              ?>                        
            </ul>
      </div>     
  

  <div class="tab-content">
    <div id="candidate_list_add" class="tab-pane active"> 
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
               <form>
                <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php
                   echo form_dropdown('status_candidate', $status, set_value('status_candidate',$selected_status), 'class="custom-select" id="status_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_status_candidate', $sub_status, set_value('sub_status_candidate'), 'class="custom-select" id="sub_status_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php echo form_dropdown('clientid_candidate', $clients, set_value('clientid_candidate',$this->session->userdata('client')['client_id']), 'class="custom-select" id="clientid_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <?php echo form_dropdown('entity_candidate',array(), set_value('entity_candidate'), 'class="custom-select" id="entity_candidate"');?>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <select id="package_candidate" name="package_candidate" class="custom-select"><option value="0">Select</option></select>
                </div>
                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
                  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
                </div>
              </div>
              </form>
              <div style="float: right;">
                <button class="btn btn-info" data-url ="<?= CLIENT_SITE_URL.'candidates/add/' ?>"  data-toggle="modal" id="add_candidate"> <i class="fa fa-plus"></i> Add Candidate</button>    
              </div>
              <br>
              <br>
              <div class=clearfix></div>

                      <table id="tbl_datatable1" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Case Initiated</th>
                            <th>Candidate Name</th>
                            <th>Overall Status</th>
                            <th>WIP</th>
                            <th>Insufficiency</th>
                            <th>Closed </th>
                            <th>Closure Date</th>
                            <th>View Report</th>
                            <th>Entity</th>
                            <th>Package</th>
                            <th>Client Ref No</th>
                            <th><?php echo REFNO; ?></th>
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
          <div id="candidate_pending" class="tab-pane fade in">
          </div>
       </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="append-data">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-simple" data-dismiss="modal">Close</button>
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

<div id="addAddCandidateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
<div class="modal-dialog"  style="max-width: 900px;">

    <?php echo form_open_multipart("#", array('name'=>'add_candidates','id'=>'add_candidates')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Add Candidates</h5>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body">
      <div id="append_add_candidate_model"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="addCandidateBack" name="addCandidateBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbcandidate" name="sbcandidate" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
<script>
//var status = "<?php echo $this->uri->segment(2); ?>";
$('#clientid_candidate').attr("style", "pointer-events: none;");

$('input[type="search"]').val(status);

   var oTable =  $('#tbl_datatable1').DataTable( {
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
            url: "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view_add_candidate'; ?>",
            pages: 5, // number of pages to cache
            async: true,
            method: 'POST', 
            data: { 'status':function(){return $("#status_candidate").val(); } } 
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
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'},{"data":"overallclosuredate"},{"data":"report"},{"data":"entity"},{"data":"package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}]
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    $('#status_candidate').val('All');
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });

   $('#status_candidate,#sub_status_candidate,#clientid_candidate,#entity_candidate,#package_candidate').on('change', function(){
    var client_id = $('#clientid_candidate').val();
    var status = $('#status_candidate').val();
    var sub_status = $('#sub_status_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
  
    var new_url = "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view_add_candidate'; ?>?client_id="+client_id+"&status="+status+"&sub_status="+sub_status+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
  });


  $('#searchrecords').on('click', function() {
    var status = $('#status_candidate').val();
    var sub_status = $('#sub_status_candidate').val();
    var client_id = $('#clientid_candidate').val();
    var entity = $('#entity_candidate').val();
    var package = $('#package_candidate').val();
   
    var new_url = "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view_add_candidate'; ?>?status="+status+"&sub_status="+sub_status+"&client_id="+client_id+"&entity="+entity+"&package="+package;
    oTable.ajax.url(new_url).load();
    
  });

$('#btn_reset').on('click', function() {
   $("#status_candidate option[value = All]").attr('selected','selected');
    var status = $('#status_candidate').val();
    var client_id = $('#clientid_candidate').val();
    var new_url = "<?php echo ADMIN_SITE_URL.'candidates/data_table_cands_view_add_candidate'; ?>?status="+status+"&client_id="+client_id;
    oTable.ajax.url(new_url).load();
  });

   /*$('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();
    if(data['edit_id'] != 0)
      var url = data['edit_id'];

      $('#append_candidate_edit_model').load(url,function(){
      $('#EditCandidateModel').modal('show');
      $('#EditCandidateModel').addClass("show");
      $('#EditCandidateModel').css({background: "#0000004d"});
      });    
    });*/

    $('#tbl_datatable1 tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();
    if(data['edit_id'] != 0)
      window.location =  data['edit_id'];

    });


  $(document).on('click', '.view_details', function(){
    var id = "<?php echo SITE_URL.'candidates/view_details/' ?>"+$(this).data("raw_id");

    $('.append-data').load(id,function(){
        $('#myModal').modal('show');
  });
});

$(document).on('click', '.status_alert', function(){

  var status = $(this).data('overallstatus');
  url = $(this).data('href');
  
  var win= window.open(url, '_blank');


});

$(document).on('click','#add_candidate',function() {
    var url = $(this).data('url');
    $('#append_add_candidate_model').load(url,function(){
      $('#addAddCandidateModel').modal('show');
      $('#addAddCandidateModel').addClass("show");
      $('#addAddCandidateModel').css({background: "#0000004d"}); 
    });
  
});




  $('#add_candidates').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
       cands_email_id :
       {
         email : true,
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      }, 
       
      entity : {
        required : true,
        greaterThan : 0
      },
      package : {
        required : true,
        greaterThan : 0
      },
       DateofBirth : {
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      NameofCandidateFather : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        lettersonly : true
      },     
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      password : {
        required : true,
        minlength : 7,
      },

      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
       // noSpace : true,
        remote: {
          url: "<?php echo CLIENT_SITE_URL.'candidates/is_client_ref_exists' ?>",
          type: "post",
          data: { username: function() { return $( "#ClientRefNumber" ).val(); },clientid: function() { return $( "#clientid" ).val(); },entity_name: function() { return $( "#entity" ).val(); },package_name: function() { return $( "#package" ).val(); }}
        }
      },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        greaterThan : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return 0;
          }
        }

      },
      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
     
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        required : true,
        digits : true,
        minlength : 10,
        maxlength : 11,
      },
      ContactNo1 : {
        digits : true,
        minlength : 10,
        maxlength : 11,
      },
      ContactNo2 : {
        digits : true,
        minlength : 10,
        maxlength : 11,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      },
      overallstatus : {
        required : true,
        greaterThan : 0
      },
      "attchments[]" : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return true;
          }
          else
          {
             return false;
          }
        }
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
       password : {
        required : "Enter Password",
        minlength : "Password content min 7 charecter long",
      },
      
      DateofBirth : {
          required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Enter Date Of Birth";
          }
        }
      },
    
      cands_email_id :
       {
         email : "Enter Valid Email ID",
         required : function(){
           if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return "Please Enter Email ID";
          }
        }
      }, 
      CandidatesContactNumber : {
        required : "Please Enter Contact No"
      }, 
      caserecddate : {
        required : "Select Case Received Date"
      },
      CandidateName : {
        required : "Enter Candidate Name"
      },
      ClientRefNumber : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Enter Client/Employee ID";
          }
        },
        remote : "{0} Client/Employee ID Exists"
      },
      DateofBirth : {
        required : "Enter Date of Birth"
      },
       NameofCandidateFather : {

         required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Enter Name of Father";
          }
        }

      },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        },
        greaterThan : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        }
       
      },
      overallstatus : {
        required : "Select Status",
        greaterThan : "Select Status"
      },
       "attchments[]" : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return "Please Attach Document";
          }
          else
          {
             return false;
          }
        }
      }
    },
    submitHandler: function(form) 
    {      
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/add_candidate'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#sbcandidate').attr('disabled','disabled');
            },
            complete:function(){
              $('#sbcandidate').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $('#addAddCandidateModel').modal('hide');
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



$('#sbeditcandidate').click(function(){ 

    $('#edit_candidates').validate({ 
    rules: {
      clientid : {
        required : true
      },
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        noSpace : true
      },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        greaterThan:  function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return 0;
          }
        }
      },
     cands_email_id :
       {
         email : true,
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      }, 

      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      DateofBirth : {
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      NameofCandidateFather : {
          required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        lettersonly : true
      },  
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        required : true,
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo2 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      password : {
        required : true,
        minlength : 7,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      }
     
    },
    messages: {
        clientid : {
          required : "Enter Client Name"
        },
        caserecddate : {
          required : "Select Case Received Date"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
        cands_email_id :
        {
         email : "Enter Valid Email ID",
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return "Please Enter Email ID";
          }
         }
        }, 
        ClientRefNumber : {
          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Client/Employee ID";
            }
          }
        },
        DateofBirth : {
          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Date of Birth";
            }
          }
        },
       NameofCandidateFather : {

          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Name of Father";
            }
          }
       
        },
      password : {
        required : "Enter Password",
        minlength : "Password content min 7 charecter long",
       },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        },
        greaterThan : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        }
      },
      CandidatesContactNumber : {
         required : "Please Enter Primary Contact"
      }
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#sbeditcandidate').attr('disabled','disabled');
            },
            complete:function(){
              $('#sbeditcandidate').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $('#EditCandidateModel').modal('hide');
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

$(document).on('change', '#status_candidate', function(){
    var status = $("#status_candidate option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : '<?php echo CLIENT_SITE_URL.'candidates/sub_status_list_candidates'; ?>',
        beforeSend :function(){
            jQuery('#sub_status_candidate').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_status_candidate').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_status_candidate').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_status_candidate').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
}); 
</script>

