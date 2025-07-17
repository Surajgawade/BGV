<div class="content-wrapper">    
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
           <!-- <?php echo form_open('#', array('name'=>'create_candidates','id'=>'create_candidates')); ?>-->
              <div class="box-body">
                <div class="box-header">
                <div class="text-white div-header">
                  Case Details
                </div>
                <br>
                <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                                   
                    echo form_dropdown('clientid', $clients, set_value('clientid',$this->session->userdata('client')['client_id']), 'class="custom-select" id="clientid"');
                    echo form_error('clientid');
                  ?>
                </div>
                <div class="col-md-1 col-sm-12 col-xs-1 form-group"  id="show_hide_check">
                  <label>&nbsp;&nbsp;</label>
                   <button class="btn btn-default" data-url ="<?= CLIENT_SITE_URL.'candidates/entity_package_view/' ?>"  data-toggle="modal" id="show_entity_package_modal" >Check</button>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Entity<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('entity', array(), set_value('entity'), 'class="custom-select" id="entity"');
                    echo form_error('entity');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Spoc/Package<span class="error"> *</span></label>
                   <select id="package" name="package" class="custom-select"><option value="0">Select</option></select>
                  <?php echo form_error('package');?>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Case Received Date<span class="error"> *</span></label>
                  <input type="text" name="caserecddate" id="caserecddate" value="<?php echo date('d-m-Y'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' readonly>
                    <?php echo form_error('caserecddate'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Client/Employee ID<span class="error"> *</span><input type="checkbox" name="create_client_id" id="create_client_id" value="">
                  </label>
                  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber'); ?>" class="form-control">
                  <?php echo form_error('ClientRefNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Candidate Name <span class="error"> *</span></label>
                  <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName'); ?>" class="form-control">
                  <?php echo form_error('CandidateName'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Email ID <span class="error"> *</span></label>
                  <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id'); ?>" class="form-control">
                  <?php echo form_error('cands_email_id'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Primary Contact <span class="error"> *</span></label>
                  <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber'); ?>" class="form-control">
                  <div id = "candidatecontact" class="error"></div>
                  <?php echo form_error('CandidatesContactNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact No (2)</label>
                  <input type="text" name="ContactNo1" maxlength="12" id="ContactNo1" value="<?php echo set_value('ContactNo1'); ?>" class="form-control">
                  <div id = "candidateContactNo1" class="error"></div>
                  <?php echo form_error('ContactNo1'); ?>
                </div>
                 <input type="hidden" name="overallstatus" id="overallstatus"  value="1" class="form-control">
                </div>
              <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Contact No (3)</label>
                  <input type="text" name="ContactNo2" maxlength="12" id="ContactNo2" value="<?php echo set_value('ContactNo2'); ?>" class="form-control">
                  <div id = "candidateContactNo2" class="error"></div>
                  <?php echo form_error('ContactNo2'); ?>
                </div>
             
                <div class="col-md-4 col-sm-12 col-xs-4 form-group" id = "comp_add" style="display: none">
                <label>&nbsp;</label>
                   <label><span class="error"><h5 style="margin-left:20px;"><input type="checkbox" id="add_candidate_mail" name="add_candidate_mail" class="form-check-input" style="height: 20px;width: 20px;">&nbsp;&nbsp;Candidate to update </h5></span></label>
                </div>
              </div>
                <br>
                <div class="text-white div-header">
                  Candidate Details
                </div>
                <br>
                <div class="row">
                
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Gender<span class="error"> *</span></label>
                 <?php
                    echo form_dropdown('gender', GENDER, set_value('gender'), 'class="custom-select" id="gender"');
                    echo form_error('gender');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Date of Birth<span class="error"> *</span> </label>
                  <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                  <?php echo form_error('DateofBirth'); ?>
                </div>

                 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Father's Name<span class="error"> *</span></label>
                  <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather'); ?>" class="form-control">
                  <?php echo form_error('NameofCandidateFather'); ?>
                </div>
               </div>
                <div class="row">
               
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Mother's Name</label>
                  <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName'); ?>" class="form-control">
                  <?php echo form_error('MothersName'); ?>
                </div>
                
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Street Address</label>
                  <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control"><?php echo set_value('prasent_address'); ?></textarea>
                </div> 

                 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>City</label>
                  <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city'); ?>" class="form-control">
                  <?php echo form_error('cands_city'); ?>
                </div>
              
                </div>
          

               
              <!--  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Batch No.</label>
                  <input type="text" name="BatchNumber" id="BatchNumber" value="<?php echo set_value('BatchNumber'); ?>" class="form-control">
                  <?php echo form_error('BatchNumber'); ?>
                </div>
                
                <div class="clearfix"></div>-->
                <div class="row">
               
               

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>PIN Code</label>
                  <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode'); ?>" class="form-control">
                  <?php echo form_error('cands_pincode'); ?>
                </div>

                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('cands_state', $states, set_value('cands_state'), 'class="custom-select" id="cands_state"');
                    echo form_error('cands_state');
                  ?>
                </div>

                 <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>PAN No.</label>
                  <input type="text" name="PANNumber" maxlength="10" id="PANNumber" value="<?php echo set_value('PANNumber'); ?>" class="form-control">
                  <?php echo form_error('PANNumber'); ?>
                </div>
                </div>
                <div class="row">
              
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>AADHAR No.</label>
                  <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber'); ?>" class="form-control">
                  <?php echo form_error('AadharNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Passport No.</label>
                  <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber'); ?>" class="form-control">
                  <?php echo form_error('PassportNumber'); ?>
                </div>
               </div>
                
                <br>
                <div class="text-white div-header">
                  Joining Details
                </div>
                <br>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Date of Joining</label>
                  <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                  <?php echo form_error('DateofJoining'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Designation</label>
                  <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas'); ?>" class="form-control">
                  <?php echo form_error('DesignationJoinedas'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Branch Location</label>
                  <input type="text" name="Location" id="Location" value="<?php echo set_value('Location'); ?>" class="form-control">
                  <?php echo form_error('Location'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Department</label>
                  <input type="text" name="Department" id="Department" value="<?php echo set_value('Department'); ?>" class="form-control">
                  <?php echo form_error('Department'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Employee Code</label>
                  <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode'); ?>" class="form-control">
                  <?php echo form_error('EmployeeCode'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Work Experience</label>

                   <?php
                       $work_experiance = array(''=> 'Select Work Experience','fresher'=>'Fresher','experienced'=>'Experienced');
            
                       echo form_dropdown('branch_name', $work_experiance, set_value('branch_name'), 'class="custom-select" id="branch_name"');
                       echo form_error('branch_name');

                   ?>
                </div>
                </div>

                <div class="row">
                
                <div class="col-sm-4 form-group">
                  <label>Grade</label>
                   <input type="text" name="grade" id="grade" value="<?php echo set_value('grade'); ?>" class="form-control">
                    <?php echo form_error('grade'); ?>
                </div>

                <div class="col-md-8 col-sm-12 col-xs-8 form-group">
                  <label >Remarks</label>
                  <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks'); ?></textarea>
                </div>
                </div>
              
                  <br>
                  <div class="text-white div-header">
                    Attachments & other
                  </div>
                  <br>
                  <div class="row">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Attachment <span class="error">(Not more than 20 MB)</span></label>
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>
                </div> 

                  
               
              </div>
           
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>

<div id="show_entity_package_modal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Show Entity Package</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="append_entity_package"></div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){

$('#clientid').attr("style", "pointer-events: none;");

$("#caserecddate").datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    daysOfWeekDisabled: [0,6],
    endDate: new Date()
  }).on('changeDate', function (selected) {
      var minDate = $(this).val();
      $('#overallclosuredate').datepicker('setStartDate', minDate);
  });

  $('#overallclosuredate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,
    endDate: new Date()
  });

   $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  get_entity_package();

  $('#create_candidates').validate({ 
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
  
        DateofBirth : {
        required : "Enter Date Of Birth"
      },
   
      cands_email_id : {
        required : "Enter Email ID",
        email : "Enter Valid Email ID"
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
        required : "Enter Client/Employee ID",
        remote : "{0} Client/Employee ID Exists"
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
              $('#save').attr('disabled','disabled');
            },
            complete:function(){
              $('#save').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                window.location = jdata.redirect;
                return;
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });    
      }
  });

  /* 
  jQuery.validator.addMethod("noSpace",function(value,element){
      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");
  */

 

function get_entity_package()
{
  var clientid = $('#clientid').val();
  if(clientid != 0)
  {

      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/get_entity_list'; ?>',
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
}
  
}); 
$(document).on('change', '#entity', function(){
  var entity = $(this).val();
  var selected_clientid = '';
  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/get_package_list'; ?>',
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

$(document).on('change', '#package', function(){
  var clientid = $('#clientid').val();
  var entity = $('#entity').val();
  var package = $(this).val();

  if(clientid != 0 && entity != 0 && package != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/check_candidate_add_component'; ?>',
          data:'clientid='+clientid+'&entity='+entity+'&package='+package,
          success:function(response)
          {
            var data =  response.message;
            if(data.candidate_add_component == 1)
            {
               $('#comp_add').css('display','block');
            }
            if(data.candidate_add_component == 2)
            {
               $('#comp_add').css('display','none');
            }
           
          }
      });
  }
});

$('#show_entity_package_modal').click(function(){
    var url = $(this).data("url");
    var clientid = $('#clientid').val();
    $('.append_entity_package').load(url+'/'+clientid,function(){});
    $('#show_entity_package_modal1').modal('show');
    $('#show_entity_package_modal1').addClass("show");
    $('#show_entity_package_modal1').css({background: "#0000004d"});
  });

$("#CandidatesContactNumber").keyup(function(){
     var maxLength = 8;
      if(maxLength <= $(this).val().length) {
        var mobile_no = $(this).val();
        var clientid = $('#clientid').val();

          $.ajax({
            type:'POST',
            url:'<?php echo CLIENT_SITE_URL.'candidates/primary_contact_no_check'; ?>',
            data:'mobileno='+mobile_no+'&clientid='+clientid,
            success:function(jdata) {
              if(jdata.ref_no == 'na')
              {
                $('#candidatecontact').html('');
                $('#sbcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidatecontact').html('Already Existing '+jdata.entity+' - '+jdata.package+' - '+jdata.ref_no);
                $('#sbcandidate').attr("disabled", true);
                
              }
            }
        });
     }
  });

$("#ContactNo1").keyup(function(){
     var maxLength = 8;
      if(maxLength <= $(this).val().length) {
        var mobile_no = $(this).val();
        var clientid = $('#clientid').val();

          $.ajax({
            type:'POST',
            url:'<?php echo CLIENT_SITE_URL.'candidates/primary_contact_no_check'; ?>',
            data:'mobileno='+mobile_no+'&clientid='+clientid,
            success:function(jdata) {
              if(jdata.ref_no == 'na')
              {
                $('#candidateContactNo1').html('');
                $('#sbcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidateContactNo1').html('Already Existing '+jdata.entity+' - '+jdata.package+' - '+jdata.ref_no);
                $('#sbcandidate').attr("disabled", true);
                
              }
            }
        });
     }
  });

 $("#ContactNo2").keyup(function(){
     var maxLength = 8;
      if(maxLength <= $(this).val().length) {
        var mobile_no = $(this).val();
        var clientid = $('#clientid').val();

          $.ajax({
            type:'POST',
            url:'<?php echo CLIENT_SITE_URL.'candidates/primary_contact_no_check'; ?>',
            data:'mobileno='+mobile_no+'&clientid='+clientid,
            success:function(jdata) {
              if(jdata.ref_no == 'na')
              {
                $('#candidateContactNo2').html('');
                $('#sbcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidateContactNo2').html('Already Existing '+jdata.entity+' - '+jdata.package+' - '+jdata.ref_no);
                $('#sbcandidate').attr("disabled", true);
                
              }
            }
        });
     }
  });

 $('#create_client_id').bind('change', function () {
   
    var client_name = $('#clientid option:selected').html(); 
    var client_id = $('#clientid').val(); 

    if(client_id != 0)
    {
      
      if ($(this).is(':checked'))
      {
          var words = client_name.split(' ');
          var client_name_word_count = words.length;
          var gethtml ='';
          for(i=0;i< client_name_word_count;i++)
          {
             if(i < '3')
             {
                var clinet_name_word = $('#clientid option:selected').text().replace(/[^\w\s]/gi, '');
                var get_each_word = clinet_name_word.toUpperCase().split(' ')[i]; 
              
                gethtml += get_each_word.charAt(0);
              }
          }
           

          var dateObject = new Date();
          var month =  dateObject.getMonth() + 1;
          var date  =  dateObject.getDate();
          var append_month =  month<10 ? '0'+month : month;
          var append_date = date<10 ? '0'+date : date;
          $('#ClientRefNumber').val(gethtml+'-'+append_date+append_month);
          $('#ClientRefNumber').attr('readonly','true');
        
      }
      else
      {
          $('#ClientRefNumber').val('');
          $('#ClientRefNumber').removeAttr('readonly');

      }

    }
    else
    {
      alert('Please select Client Name');

    }

  }); 
</script>