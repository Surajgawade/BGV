<div class="box box-primary">
<br>
<div class="text-white div-header">
  Education Case Details
</div>
<br>
<?php echo form_open_multipart('#', array('name'=>'frm_education','id'=>'frm_education')); ?>
  <div class="box-body">
   <div class="row">    
    <div class="col-sm-4 form-group">
      <label>Candidate Name</label>
      <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="text" name="CandidateName"  value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Client</label>
      <input type="text" name="clientname" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" value="<?php echo set_value('entity_name',$get_cands_details['entity_name']); ?>" class="form-control readonly">
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" value="<?php echo set_value('package_name',$get_cands_details['package_name']); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($get_cands_details['caserecddate'])); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Client Ref No</label>
      <input type="text" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control readonly">
    </div>
    </div>
  
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Candidate Ref No</label>
      <input type="text" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
    </div>
    <div class="col-sm-4 form-group">
      <label>Comp Int Date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>

     <?php
   
    if(!empty($mode_of_verification))
    {
        $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <div class="col-sm-4 form-group">
        <label>Mode of Verification</label>
         <input type="text" name="mode_of_veri" readonly="readonly" id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->eduver)) { echo $mode_of_verification_value->eduver; } ?>" class="form-control cls_disabled">
        <?php echo form_error('mod_of_veri'); ?>
      </div>
    </div>

    <div class="text-white div-header">
       Candidate Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Date of Birth</label>
      <input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo set_value('date_of_birth',convert_db_to_display_date($get_cands_details['DateofBirth'])); ?>" class="form-control myDatepicker readonly" placeholder="DD-MM-YYYY">
      <?php echo form_error('date_of_birth'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Gender</label>
     <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$get_cands_details['gender']), 'class="select2 gen" id="gender"');
        echo form_error('gender');
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Father's Name</label>
      <input type="text" name="father_name" id="father_name" value="<?php echo set_value('father_name',$get_cands_details['NameofCandidateFather']); ?>" class="form-control readonly">
      <?php echo form_error('father_name'); ?>
    </div>
    </div>
   
    <div class="text-white div-header">
      Education Details
    </div>
    <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>School/College</label>
      <input type="text" name="school_college" id="school_college" value="<?php echo set_value('school_college'); ?>" class="form-control">
      <?php echo form_error('school_college'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>University/Board <span class="error">*</span></label>
      <?php 
      echo form_dropdown('university_board', $universityname, set_value('university_board'), 'class="select2" id="university_board"');
      echo form_error('university_board'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Grade/Class/Marks</label>
      <input type="text" name="grade_class_marks" id="grade_class_marks" value="<?php echo set_value('grade_class_marks'); ?>" class="form-control">
      <?php echo form_error('grade_class_marks'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Qualification <span class="error">*</span></label>
      <?php 
      echo form_dropdown('qualification', $qualification_name, set_value('qualification'), 'class="select2" id="qualification"');
      echo form_error('qualification'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Major</label>
      <input type="text" name="major" id="major" value="<?php echo set_value('major'); ?>" class="form-control">
      <?php echo form_error('major'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Course Start Date</label>
      <input type="text" name="course_start_date" id="course_start_date" value="<?php echo set_value('course_start_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
      <?php echo form_error('course_start_date'); ?>
    </div>
    </div>
     <div class="row">
    <div class="col-sm-4 form-group">
      <label>Course End Date</label>
      <input type="text" name="course_end_date" id="course_end_date" value="<?php echo set_value('course_end_date'); ?>" class="form-control myDatepicker" placeholder="DD-MM-YYYY">
      <?php echo form_error('course_end_date'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Month of Passing <span class="error">*</span></label>
      <input type="text" name="month_of_passing" id="month_of_passing" value="<?php echo set_value('month_of_passing'); ?>" class="form-control">
      <?php echo form_error('month_of_passing'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Year of Passing <span class="error">*</span></label>
      <input type="text" name="year_of_passing" id="year_of_passing" value="<?php echo set_value('year_of_passing'); ?>" class="form-control">
      <?php echo form_error('year_of_passing'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Roll No</label>
      <input type="text" name="roll_no" id="roll_no" value="<?php echo set_value('roll_no'); ?>" class="form-control">
      <?php echo form_error('roll_no'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Enrollment No <span class="error">*</span></label>
      <input type="text" name="enrollment_no" id="enrollment_no" value="<?php echo set_value('enrollment_no'); ?>" class="form-control">
      <?php echo form_error('enrollment_no'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>PRN Number</label>
      <input type="text" name="PRN_no" id="PRN_no" value="<?php echo set_value('PRN_no'); ?>" class="form-control">
      <?php echo form_error('PRN_no'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Documents Provided</label>
      <?php 
      echo form_dropdown('documents_provided[]', array('' => 'select','provisional certificate'=>'Provisional Certificate','degree' => 'Degree','marksheet' => 'Marksheet','other' => 'Other'), set_value('documents_provided'), 'class="select2" id="documents_provided"');
      echo form_error('documents_provided'); 
      ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>City</label>
      <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
      <?php echo form_error('city'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="select2" id="state"');
        echo form_error('state');
      ?>
    </div>
    </div>
    
    <div class="text-white div-header">
       Attachments & other
    </div>
    <br>
   <!-- <div class="col-sm-4 form-group">
      <label>Genuineness</label>
      <input type="text" name="genuineness" id="genuineness" value="<?php echo set_value('genuineness'); ?>" class="form-control">
      <?php echo form_error('genuineness'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>Online URL</label>
      <input type="url" name="online_URL" id="online_URL" value="<?php echo set_value('online_URL'); ?>" class="form-control">
      <?php echo form_error('online_URL'); ?>
    </div>-->
    <div class="row">
    <div class="col-sm-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
   
    <div class="col-sm-4 form-group">
      <label>Executive Name<span class="error">*</span></label>
      <?php
        echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id'), 'class="select2 cls_disabled" id="has_case_id"');
        echo form_error('has_case_id');
      ?>
    </div>
    </div> 

    <input type="hidden" name="upload_capture_image_education" id="upload_capture_image_education" value=""> 
    <div class="col-sm-4 form-group">
      <input type="submit" name="btn_education" id='btn_education' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
      <!--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_education">Copy Attachment</button>--> 
    </div>
  </div>
<?php echo form_close(); ?>
</div>

<div id="capture_image_copy_education" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_education','id'=>'frm_upload_copied_image_education')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-education"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_education" name="upload_copied_image_education">Upload</button>
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
.image-content-result-education {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-education::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-education:not(:empty)::after,
.image-content-result-education:focus::after {
  display: none;
}

.image-content-result-education * {
  max-width: 100%;
  border: 0;
}
</style>

<script>
$(document).ready(function(){

 /* $.getJSON("<?php echo ADMIN_SITE_URL.'education/university_dropdown'; ?>",function(data){
    var stringToAppend = "";
    $.each(data.university_list,function(key,val) {
      stringToAppend += "<option value='" + key + "'>" + val + "</option>";
    });
    $("#university_board").html(stringToAppend);
  });

  $.getJSON("<?php echo ADMIN_SITE_URL.'education/qualification_dropdown'; ?>",function(data){
    var stringToAppend = "";
    $.each(data.qualification_list,function(key,val) {
      stringToAppend += "<option value='" + key + "'>" + val + "</option>";
    });
    $("#qualification").html(stringToAppend);
  });*/
    $('.gen').prop('disabled', true);
   
    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200,
  });
  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);

  $('#upload_copied_image_education').on('click',function() {
      var get_image_name =  $('#upload_capture_image_education').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-education img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_education').val(get_image_base);
          $('#capture_image_copy_education').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });

  $('#frm_education').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      education_com_ref : {
        required : true
      },
      university_board : {
        required : true,
        greaterThan : 0
      },
      qualification : {
        required : true,
        greaterThan : 0
      },
       month_of_passing : {
        required : true
      },
      year_of_passing : {
        required : true
      },
      enrollment_no : {
        required : true
      },
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      city : {
        lettersonly : true
      },
      has_case_id :{
        required : true,
        greaterThan : 0
      },
      iniated_date:{
        required : true
      }
    },
    messages: {
      clientid : {
        required : "Select Client"
      },
      candsid : {
        required : "Select Candidate"
      },
      education_com_ref : {
        required :  "Enter Component Ref No"
      },
      university_board : {
        required : "Select University Board",
        greaterThan : "Select University Board"
      },
       qualification : {
        required : "Select Qualification",
        greaterThan : "Select Qualification"
      },
       month_of_passing : {
        required : "Enter Month of Passing"
      },
       year_of_passing : {
        required : "Enter Year of Passing"
      },
       enrollment_no : {
        required : "Enter Enrollment No"
      },
      address : {
        required : "Enter address"
      },
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
      has_case_id : {
        greaterThan : "Select Executive"
      }, 
      iniated_date:{
        required : "Enter Initiate Date"
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'education/save_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_education').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          //$('#btn_education').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            //window.location = jdata.redirect;
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
});
</script>
<script type="text/javascript">
  $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });

</script>