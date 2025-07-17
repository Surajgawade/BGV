

          <div class="row">
            <div class="col-12">
              <div class="card m-b-20">
                <div class="card-body">
                  <div class=clearfix></div>
                  <form id = "export_mail_candidate" name = "export_mail_candidate">  
                    <ol>
                      <button class="btn btn-secondary waves-effect" data-toggle="modal" id = "export_mail"><i class="fa fa-download"></i> Export</button>
                    </ol>
                  </form>
                          <table id="tbl_datatable_mail_view" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                <th>Mail</th>
                                <th>SMS</th>
                                <th>Copy Link</th>
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
  <script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>
 
<script>

$(document).ready(function() {
   var oTable =  $('#tbl_datatable_mail_view').DataTable( {
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
            url: "<?php echo CLIENT_SITE_URL.'candidates/data_table_cands_view_mail_view'; ?>",
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
        "columns":[{"data":"id"},{"data":"caserecddate"},{"data":"CandidateName"},{"data":"overallstatus"},{'data':'pending_check'},{'data':'insufficiency_check'},{'data':'closed_check'},{"data":"overallclosuredate"},{"data":"mail_sent"},{"data":"sms_sent"},{"data":"copy_link"},{"data":"Entity"},{"data":"Package"},{"data":"ClientRefNumber"},{"data":"cmp_ref_no"}]
    } );
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
    });

      $('#tbl_datatable_mail_view tbody').on('click', 'td.details-control', function () {
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

  /* $('#tbl_datatable_mail_view tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data();
    if(data['edit_id'] != 0)
      var url = data['edit_id'];

      $('#append_candidate_edit_model').load(url,function(){
      $('#EditCandidateModel').modal('show');
      $('#EditCandidateModel').addClass("show");
      $('#EditCandidateModel').css({background: "#0000004d"});
      });    
    });*/
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
 /* if(status === "WIP")
  {
    var result_ = confirm("The case is not completed, do you want to see the report.");

    if(result_ === true)
    {
      url = $(this).data('href');
      var win= window.open(url, '_blank');
    }
    else
    {
      return true;
    }
  }
  else
  {
    url = $(this).data('href');
    var win= window.open(url, '_blank');
  }*/
    
  

});

$(document).on('click', '.copyLink', function() {
    let urlcopy = $(this).data('link');

    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(urlcopy).select();
    document.execCommand("copy");
    $temp.remove();

    $(this).text('link copied').removeClass('btn-info').addClass('btn-primary');
    setTimeout(function(){$('.copyLink').text('Copy Link').removeClass('btn-primary').addClass('btn-info');}, 2000);
});

    $('#tbl_datatable_mail_view tbody').on('click', '.trigger_email_again', function (){
        if(confirm('You need send message email again') === true) {
            
            var id = $(this).attr('id'); 

            if(id != "") {
                $.ajax({
                    url: "<?php echo CLIENT_SITE_URL.'candidates/trigger_email_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                            show_alert(message,'success',true);
                            location.reload();
                            return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });


     $('#tbl_datatable_mail_view tbody').on('click', '.trigger_sms_again', function (){
        if(confirm('You need send message sms again') === true) {
            
            var id = $(this).attr('id'); 
            if(id != "") {
                $.ajax({
                    url: "<?php echo CLIENT_SITE_URL.'candidates/trigger_sms_again'; ?>",
                    type: 'post',
                    data: {send_id:id},
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).text('sending...').attr('disabled','disabled');
                    },
                    complete: function() {
                        $(this).text('Send').removeAttr('disabled');
                    },
                    success: function(jdata) {
                        var message =  jdata.message || '';
                        if(jdata.status == 200) {
                          show_alert(message,'success',true);
                          location.reload();
                          return;
                        } else {
                           show_alert(message,'error'); 
                        }
                    },
                    error: function (jqXHR, exception) {
                        show_alert(jqXHR, 'danger');
                    }
                });
            }
        }
        else {
            show_alert('Cancelled ','info');
        }       
    });



$(document).on('click','#add_candidate',function() {
    var url = $(this).data('url');
    $('#append_add_candidate_model').load(url,function(){
      $('#addAddCandidateModel').modal('show');
      $('#addAddCandidateModel').addClass("show");
      $('#addAddCandidateModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.address_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_address_model').load(url+'/'+id,function(){
      $('#addAddaddressModel').modal('show');
      $('#addAddaddressModel').addClass("show");
      $('#addAddaddressModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.employment_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_employment_model').load(url+'/'+id,function(){
      $('#addAddEmploymentModel').modal('show');
      $('#addAddEmploymentModel').addClass("show");
      $('#addAddEmploymentModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.education_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_education_model').load(url+'/'+id,function(){
      $('#addAddeducationModel').modal('show');
      $('#addAddeducationModel').addClass("show");
      $('#addAddeducationModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.reference_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_reference_model').load(url+'/'+id,function(){
      $('#addAddreferenceModel').modal('show');
      $('#addAddreferenceModel').addClass("show");
      $('#addAddreferenceModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.court_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_court_model').load(url+'/'+id,function(){
      $('#addAddcourtModel').modal('show');
      $('#addAddcourtModel').addClass("show");
      $('#addAddcourtModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.global_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_global_model').load(url+'/'+id,function(){
      $('#addAddglobalModel').modal('show');
      $('#addAddglobalModel').addClass("show");
      $('#addAddglobalModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.pcc_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_pcc_model').load(url+'/'+id,function(){
      $('#addAddpccModel').modal('show');
      $('#addAddpccModel').addClass("show");
      $('#addAddpccModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.identity_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_identity_model').load(url+'/'+id,function(){
      $('#addAddidentityModel').modal('show');
      $('#addAddidentityModel').addClass("show");
      $('#addAddidentityModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.credit_report_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_credit_report_model').load(url+'/'+id,function(){
      $('#addAddcreditreportModel').modal('show');
      $('#addAddcreditreportModel').addClass("show");
      $('#addAddcreditreportModel').css({background: "#0000004d"}); 
    });
  
});

$(document).on('click','.drugs_add',function() {
    var url = $(this).data('url');
    var id = $(this).data('id');
    $('#append_add_drugs_model').load(url+'/'+id,function(){
      $('#addAdddrugsModel').modal('show');
      $('#addAdddrugsModel').addClass("show");
      $('#addAdddrugsModel').css({background: "#0000004d"}); 
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
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return true;
          }
          else
          {
             return false;
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
        required : true
      },
      NameofCandidateFather : {
        required : true,
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
      crmpassword : {
        required : true,
        equalTo: "#password"
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : true,
       // noSpace : true,
        remote: {
          url: "<?php echo CLIENT_SITE_URL.'candidates/is_client_ref_exists' ?>",
          type: "post",
          data: { username: function() { return $( "#ClientRefNumber" ).val(); },clientid: function() { return $( "#clientid" ).val(); },entity_name: function() { return $( "#entity" ).val(); },package_name: function() { return $( "#package" ).val(); }}
        }
      },
      gender : {
        required : true,
        greaterThan: 0
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
       cands_email_id : {
        email : "Enter Valid Email ID"
      }, 
        DateofBirth : {
        required : "Enter Date Of Birth"
      },
      crmpassword : {
        required : "Enter Comfirm Password",
        equalTo : "Comfirm password same as Password"
      },
      cands_email_id : {
        required : "Enter Email ID",
        email : "Enter Valid Email ID"
      },
      cands_email_id :
       {
         email : "Enter Valid Email ID",
         required : function(){
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return "Enter Email ID";
          }
          else
          {
             return false;
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
        required : "Enter Client Ref Number",
        remote : "{0} Client Ref Number Exists"
      },
      DateofBirth : {
        required : "Enter Date of Birth"
      },
       NameofCandidateFather : {
        required : "Enter Name of Father"
      },
      gender : {
        required : "Select Gender"
      },
      overallstatus : {
        required : "Select Status",
        greaterThan : "Select Status"
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
        required : true,
        noSpace : true
      },
      gender : {
        required : true,
        greaterThan: 0
      },
     cands_email_id :
       {
         email : true,
         required : function(){
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return true;
          }
          else
          {
             return false;
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
        required : true
      },
      NameofCandidateFather : {
        required : true,
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
          var  email_candidate = $('#email_candidate').val();
          if((email_candidate == "complete details") || (email_candidate == "partial details"))
          {
            return "Enter Email ID";
          }
          else
          {
             return false;
          }
        }
      }, 
        ClientRefNumber : {
          required : "Enter Client Ref Number"
        },
        DateofBirth : {
        required : "Enter Date Of Birth"
         },
       NameofCandidateFather : {
        required : "Enter Name of Father"
        },
        password : {
        required : "Enter Password",
        minlength : "Password content min 7 charecter long",
       },
        gender : {
          required : "Select Gender"
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
              $('#btn_update').attr('disabled','disabled');
              jQuery('.body_loading').show();
            },
            complete:function(){
             // $("#frm_update_candidates :input").prop("disabled", true);
             // jQuery('.body_loading').hide();
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



$('#export_mail').click(function(){ 

    $('#export_mail_candidate').validate({ 
    rules: {
    },
    messages: {
        
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/export_candidate'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#export_mail').attr('disabled','disabled');
              
            },
            complete:function(){
              $("#export_mail").prop("disabled", true);
            
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

jQuery.validator.addMethod("noSpace",function(value,element){

      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");    

 
</script>

