 <div class="content-page">
    <div class="content">
     <div class="container-fluid">
    
     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Create Candidate</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidate</a></li>
                  <li class="breadcrumb-item active">Candidate Add</li>
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
            <?php echo form_open('#', array('name'=>'create_candidates','id'=>'create_candidates')); ?>
             
                <div class="text-white div-header">
                  Case Details
                </div>
                <br>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="select2" id="clientid"');
                    echo form_error('clientid');
                  ?>
                </div>
                <div class="col-sm-4 form-group" style="display: none;" id="show_hide_check">
                  <label>&nbsp;&nbsp;</label>
                   <button class="btn btn-default" data-url ="<?= ADMIN_SITE_URL.'candidates/entity_package_view/' ?>"  data-toggle="modal" id="show_entity_package_modal" >Check</button>
                </div>
                <div class="col-sm-4 form-group">
                  <label >Select Entiy<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('entity', array(), set_value('entity'), 'class="select2" id="entity"');
                    echo form_error('entity');
                  ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label >Select Package<span class="error"> *</span></label>
                   <select id="package" name="package" class="select2"><option value="0">Select</option></select>
                  <?php echo form_error('package');?>
                </div>
                </div>

                <div class="row">
               
                <div class="col-sm-4 form-group">
                  <label>Case Received Date<span class="error"> *</span></label>
                  <input type="text" name="caserecddate" id="caserecddate" value="<?php echo set_value('caserecddate'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                    <?php echo form_error('caserecddate'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <br>
                  <label>Create Client id</label>
                  <input type="checkbox" name="create_client_id" id="create_client_id" value="">
                </div>
                <div class="col-sm-4 form-group">
                  <label>Client Ref Number <span class="error"> *</span></label>
                  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber'); ?>" class="form-control">
                  <?php echo form_error('ClientRefNumber'); ?>
                </div>
                </div>
               <!-- <div class="col-sm-4 form-group">
                  <label><?php echo REFNO; ?> <span class="error"> *</span></label>
                  <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no'); ?>" class="form-control">
                  <?php echo form_error('cmp_ref_no'); ?>
                </div>-->
                <div class="clearfix"></div>

                 <input type="hidden" name="overallstatus" id="overallstatus"  value="1" class="form-control">

                
                <!--<div class="col-sm-4 form-group">
                  <label>Overall Status <span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('overallstatus', $status, set_value('overallstatus',1), 'class="form-control" id="overallstatus"');
                    echo form_error('overallstatus');
                  ?>
                </div>
               <div class="col-sm-4 form-group">
                  <label>Billed date</label>
                   <input type="text" name="build_date" id="build_date" value="<?php //echo set_value('build_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                   <?php //echo form_error('build_date'); ?>
                </div> 
                <div class="col-sm-4 form-group">
                  <label>Closure Date <span class="error"> *</span></label>
                  <input type="text" disabled="" name="overallclosuredate" id="overallclosuredate" value="<?php echo set_value('overallclosuredate'); ?>" class="form-control" placeholder='DD-MM-YYYY'>
                  <?php echo form_error('overallclosuredate'); ?>
                </div>-->
                <div class="clearfix"></div>
               
                <div class="text-white div-header">
                    Joining Details
                </div>
                <br>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label>Date of Joining</label>
                  <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                  <?php echo form_error('DateofJoining'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Designation</label>
                  <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas'); ?>" class="form-control">
                  <?php echo form_error('DesignationJoinedas'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Branch Location</label>
                  <input type="text" name="Location" id="Location" value="<?php echo set_value('Location'); ?>" class="form-control">
                  <?php echo form_error('Location'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label>Department</label>
                  <input type="text" name="Department" id="Department" value="<?php echo set_value('Department'); ?>" class="form-control">
                  <?php echo form_error('Department'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Employee Code</label>
                  <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode'); ?>" class="form-control">
                  <?php echo form_error('EmployeeCode'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Work Experience</label>
                  <?php
                   $work_experiance = array(''=> 'Select Work Experience','fresher'=>'Fresher','experienced'=>'Experienced');

                    echo form_dropdown('branch_name', $work_experiance, set_value('branch_name'), 'class="select2" id="branch_name"');
                   echo form_error('branch_name');

                    ?>
                </div>
                </div>

               <!-- <div class="col-sm-4 form-group">
                  <label>Region</label>
                  <input type="text" name="region" id="region" value="<?php echo set_value('region'); ?>" class="form-control">
                  <?php echo form_error('region'); ?>
                </div>-->
                <div class="row">

                <div class="col-sm-4 form-group">
                  <label>Grade</label>
                   <input type="text" name="grade" id="grade" value="<?php echo set_value('grade'); ?>" class="form-control">
                    <?php echo form_error('grade'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label >Remarks</label>
                  <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks'); ?></textarea>
                </div>
                </div>
              
                <div class="text-white div-header">
                  Candidate Details
                </div>
                <br>
                <div class="row"> 
                <div class="col-sm-4 form-group">
                  <label>Candidate Name <span class="error"> *</span></label>
                  <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName'); ?>" class="form-control">
                  <?php echo form_error('CandidateName'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Gender<span class="error"> *</span></label>
                 <?php
                    echo form_dropdown('gender', GENDER, set_value('gender'), 'class="select2" id="gender"');
                    echo form_error('gender');
                  ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Date of Birth<span class="error"> *</span> </label>
                  <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
                  <?php echo form_error('DateofBirth'); ?>
                </div>
                </div>
                <div class="row"> 
                <div class="col-sm-4 form-group">
                  <label>Father's Name<span class="error"> *</span></label>
                  <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather'); ?>" class="form-control">
                  <?php echo form_error('NameofCandidateFather'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Mother's Name</label>
                  <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName'); ?>" class="form-control">
                  <?php echo form_error('MothersName'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Primary Contact<span class="error"> *</span></label>
                  <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber'); ?>" class="form-control">
                  <div id = "candidatecontact" class="error"></div>
                  <?php echo form_error('CandidatesContactNumber'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label>Contact No (2)</label>
                  <input type="text" name="ContactNo1" maxlength="12" id="ContactNo1" value="<?php echo set_value('ContactNo1'); ?>" class="form-control">
                  <?php echo form_error('ContactNo1'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Contact No (3)</label>
                  <input type="text" name="ContactNo2" maxlength="12" id="ContactNo2" value="<?php echo set_value('ContactNo2'); ?>" class="form-control">
                  <?php echo form_error('ContactNo2'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Email ID</label>
                  <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id'); ?>" class="form-control">
                  <?php echo form_error('cands_email_id'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group comp_add"  style="display:none;">
                <label>&nbsp;</label>
                   <label><span class="error"><h6 style="margin-left:20px;"><input type="checkbox" id="add_pan_card_in_identity" name="add_pan_card_in_identity" class="form-check-input" style="height: 15px;width: 15px;">&nbsp;&nbsp;Add Pan card</h6></span></label>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group comp_add" style="display:none;">
                <label>&nbsp;</label>
                   <label><span class="error"><h6 style="margin-left:20px;"><input type="checkbox" id="add_aadhar_card_in_identity" name="add_aadhar_card_in_identity" class="form-check-input" style="height: 15px;width: 15px;">&nbsp;&nbsp;Add aadhar card </h6></span></label>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4 form-group">
                  <label>PAN No.</label>
                  <input type="text" name="PANNumber" maxlength="10" id="PANNumber" value="<?php echo set_value('PANNumber'); ?>" class="form-control">
                  <?php echo form_error('PANNumber'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>AADHAR No.</label>
                  <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber'); ?>" class="form-control">
                  <?php echo form_error('AadharNumber'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>Passport No.</label>
                  <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber'); ?>" class="form-control">
                  <?php echo form_error('PassportNumber'); ?>
                </div>
                </div>
                <!--<div class="col-sm-4 form-group">
                  <label>Batch No.</label>
                  <input type="text" name="BatchNumber" id="BatchNumber" value="<?php echo set_value('BatchNumber'); ?>" class="form-control">
                  <?php echo form_error('BatchNumber'); ?>
                </div>-->
            

                <div class="text-white div-header">
                  Candidate Address
                </div>
                <br>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label>Street Address</label>
                  <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control"><?php echo set_value('prasent_address'); ?></textarea>
                </div> 
                <div class="col-sm-4 form-group">
                  <label>City</label>
                  <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city'); ?>" class="form-control">
                  <?php echo form_error('cands_city'); ?>
                </div>
                <div class="col-sm-4 form-group">
                  <label>PIN Code</label>
                  <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode'); ?>" class="form-control">
                  <?php echo form_error('cands_pincode'); ?>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-4 form-group">
                  <label>State</label>
                  <?php
                    echo form_dropdown('cands_state', $states, set_value('cands_state'), 'class="select2" id="cands_state"');
                    echo form_error('cands_state');
                  ?>
                </div> 
                </div>

                  <div class="text-white div-header">
                      Attachments & other
                  </div>
                  <br>
                  <div class="row">
                  <div class="col-sm-4 form-group">
                    <label>Data Entry</label>
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg, .pdf, .docx, .xls, .xlsx,.tif,.tiff" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>
                 <!-- <div class="col-md-4 col-md-offset-2 col-xs-offset-2 col-sm-12 col-xs-4 form-group">
                    <label>CS</label>
                    <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>-->
                  </div> 
                  <input type="hidden" name="upload_capture_image" id="upload_capture_image" value="">
                  
                <div class="box-body">
                  <div class="col-sm-4 form-group">
                    <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy">Copy Attachment</button>
                  </div>
                </div>
              
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  
</div>
</div>
</div>
<div id="show_entity_package_modal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="max-height:90%;width: 60%;  margin-top: 40px; margin-bottom:40px;">
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


<div id="capture_image_copy" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image','id'=>'frm_upload_copied_image')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image" name="upload_copied_image">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', async function () {
  
  const errorEl = document.getElementById('errorMsg')

  async function askWritePermission() {
    try {
      const { state } = await navigator.permissions.query({ name: 'clipboard-write', allowWithoutGesture: false })
      return state === 'granted'
    } catch (error) {
      errorEl.textContent = `Compatibility error (ONLY CHROME > V66): ${error.message}`
      console.log(error)
      return false
    }
  }

  const canWrite = await askWritePermission()
  const setToClipboard = blob => {
    const data = [new ClipboardItem({ [blob.type]: blob })]
    console.log(data);
    console.log(navigator.clipboard);
    return navigator.clipboard.write(data)
  }
})
</script>

<style>
.image-content-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result:not(:empty)::after,
.image-content-result:focus::after {
  display: none;
}

.image-content-result * {
  max-width: 100%;
  border: 0;
}
</style>
<script>
$(document).ready(function(){
  
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

   $('#upload_copied_image').on('click',function() {
      var get_image_name =  $('#upload_capture_image').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image').val(get_image_base);
          $('#capture_image_copy').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });

  $("#CandidatesContactNumber").keyup(function(){
     var maxLength = 8;
      if(maxLength <= $(this).val().length) {
        var mobile_no = $(this).val();
        var clientid = $('#clientid').val();
          $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/primary_contact_no_check'; ?>',
            data:'mobileno='+mobile_no+'&clientid='+clientid,
            success:function(jdata) {
              if(jdata == 'false')
              {
                 $('#candidatecontact').html('');
              }
              else
              {
                $('#candidatecontact').html('Already Existing '+jdata);
                
              }
            }
        });
     }
  });

  $('#create_candidates').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
       cands_email_id : {
        email : true
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
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      DateofBirth : {
        required : true
      },
      NameofCandidateFather : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : true,
       // noSpace : true,
        remote: {
          url: "<?php echo ADMIN_SITE_URL.'candidates/is_client_ref_exists' ?>",
          type: "post",
          data: { username: function() { return $( "#ClientRefNumber" ).val(); },clientid: function() { return $( "#clientid" ).val(); },entity_name: function() { return $( "#entity" ).val(); },package_name: function() { return $( "#package" ).val(); } }
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
      PANNumber : {
        panvalidation : true
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
     
      NameofCandidateFather : {
        required : "Enter Name Of Father"
      }, 
      caserecddate : {
        required : "Select Case Received Date"
      },
      DateofBirth : {
        required : "Enter Date of Birth"
      },
      NameofCandidateFather : {
        required : "Enter Name of Father"
      },
      CandidateName : {
        required : "Enter Candidate Name"
      },
      ClientRefNumber : {
        required : "Enter Client Ref Number",
        remote : "{0} Client Ref Number Exists"
      },
      DateofBirth : {
        required : "Select Date of Birth"
      },
      gender : {
        required : "Select Gender"
      },
      CandidatesContactNumber : {
         required : "Please Enter Primary Contact"
      },
      PANNumber :{
        panvalidation : "Enter proper Pan card"
      }
    },
    submitHandler: function(form) 
    {      
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'candidates/add_candidate'; ?>',
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
             // $('#save').removeAttr('disabled');
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

 
 /* jQuery.validator.addMethod("noSpace",function(value,element){
      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");
*/
 /* $('#clientid').on('change',function(){
    var clientid = $(this).val();
    if(clientid != 0)
    {
         $('#show_hide_check').show(); 

        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            success:function(jdata) {
              if(jdata.status = 200)
              {
                $('#cmp_ref_no').val(jdata.cmp_ref_no);
                //$('#entity').empty();
               // $.each(jdata.entity_list, function(key, value) {
               //   $('#entity').append($("<option></option>").attr("value",key).text(value));
               // });
              }
            }
        });
    }
  }).trigger('change')*/
  
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


$(document).on('change', '#package', function(){
  var clientid = $('#clientid').val();
  var entity = $('#entity').val();
  var package = $(this).val();

  if(clientid != 0 && entity != 0 && package != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'candidates/check_identity_component'; ?>',
          data:'clientid='+clientid+'&entity='+entity+'&package='+package,
          success:function(response)
          {
         
            var data =  response.message;
            
            if(data.candidate_component == "present")
            {
               $('.comp_add').css('display','block');
            }
            if(data.candidate_component == "not present")
            {
               $('.comp_add').css('display','none');
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

     jQuery.validator.addMethod("panvalidation", function (value, element) {
        var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
          if(value == "")
          {
              valid = true;
          }
          else
          {
            if (regex.test(value)) {
                 valid = true;
            } else {
               
                valid = false;
            }
          }
      return valid;
    });
</script>

<script type="text/javascript">
    $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });
</script>