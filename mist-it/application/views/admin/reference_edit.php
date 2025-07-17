
  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
          Reference Verification Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect btn-sm  edit_btn_click" data-frm_name='frm_reference_update' data-editUrl="<?= ($this->permission['access_reference_list_edit']) ? encrypt($selected_data['reference_id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
            
              <li><button class="btn btn-secondary waves-effect btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'court_verificatiion/delete/'.encrypt($selected_data['reference_id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
  <br>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_reference_update','id'=>'frm_reference_update')); ?>
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Candidate Name</label>
        <input type="hidden" name="reference_id" value="<?php echo set_value('reference_id',$selected_data['reference_id']); ?>">
        <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
        <input type="text" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
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
        <label>Component Ref No</label>
        <input type="text" name="reference_com_ref" id="reference_com_ref" value="<?php echo set_value('reference_com_ref',$selected_data['reference_com_ref']); ?>" class="form-control readonly">
        <?php echo form_error('reference_com_ref'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Candidate Ref No</label>
        <input type="text"readonly="readonly" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
      </div>
      <div class="col-sm-4 form-group">
        <label>Comp Int Date<span class="error"> *</span></label>
        <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($selected_data['iniated_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
        <?php echo form_error('iniated_date'); ?>
      </div>
      </div>   
         <?php      
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Mode of Verification</label>
         <input type="text" name="mode_of_veri" readonly="readonly" id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->refver)) { echo $mode_of_verification_value->refver; } ?>" class="form-control cls_disabled">
        <?php echo form_error('mod_of_veri'); ?>
      </div>

   <?php   
       if($this->user_info['bill_date_permission'] == "yes")
        {
        ?>
        <div class="col-sm-4 form-group">
        <?php 
        }else
        { ?>
        <div class="col-sm-4 form-group" style="display: none;">
      <?php 
       } 
      ?>
      <label>Billed Date</label>
       <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',$selected_data['build_date']); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
       <?php echo form_error('build_date'); ?>
    </div> 

      <div class="col-sm-4 form-group">
        <label>Status </label>
        <input type="text" name="status" id="status" readonly="readonly" value="<?php echo set_value('status',$selected_data['verfstatus']); ?>" class="form-control">
        <?php echo form_error('status'); ?>
      </div>
    </div> 
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Re Initiation date<span class="error"> *</span></label>
        <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',convert_db_to_display_date($selected_data['reference_re_open_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
        <?php echo form_error('re_iniated_date'); ?>
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
        Reference Details
      </div>
      <br>
      <div class="row">
      <div class="col-sm-3 form-group">
        <label>Name of Reference<span class="error"> *</span></label>
        <input type="text" name="name_of_reference" id="name_of_reference" value="<?php echo set_value('name_of_reference',$selected_data['name_of_reference']); ?>" class="form-control  cls_disabled">
        <?php echo form_error('name_of_reference'); ?>
      </div>
      <div class="col-sm-3 form-group">
        <label>Designation<span class="error"> *</span></label>
        <input type="text" name="designation" id="designation" value="<?php echo set_value('designation',$selected_data['designation']); ?>" class="form-control  cls_disabled">
        <?php echo form_error('designation'); ?>
      </div>
      <div class="col-sm-3 form-group">
        <label>Primary Contact Number<span class="error"> *</span></label>
        <input type="text" name="contact_no" minlength="11" maxlength="13" id="contact_no" value="<?php echo set_value('contact_no',$selected_data['contact_no']); ?>" class="form-control cls_disabled">
        <?php echo form_error('contact_no'); ?>
      </div>

      <div class="col-sm-3 form-group">
      <label>Contact Number 1</label>
      <input type="text" name="contact_no_first" minlength="11" maxlength="13" id="contact_no_first" value="<?php echo set_value('contact_no_first',$selected_data['contact_no_first']); ?>" class="form-control cls_disabled">
      <?php echo form_error('contact_no_first'); ?>
    </div>
    </div>
    <div class="row">
     <div class="col-sm-3 form-group">
      <label>Contact Number 2</label>
      <input type="text" name="contact_no_second" minlength="11" maxlength="13" id="contact_no_second" value="<?php echo set_value('contact_no_second',$selected_data['contact_no_second']); ?>" class="form-control cls_disabled">
      <?php echo form_error('contact_no_second'); ?>
    </div>
      
      <div class="col-sm-3 form-group">
        <label>Email ID</label>
        <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id',$selected_data['email_id']); ?>" class="form-control cls_disabled">
        <?php echo form_error('email_id'); ?>
      </div>
    </div>

      <div class="text-white div-header">
        Attachments & other
      </div>
      <br>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Data Entry</label>
        <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Insuff Cleared<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
        <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Executive Name<span class="error">*</span></label>
        <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$selected_data['has_case_id']), 'class="select2 cls_disabled" id="has_case_id"');
          echo form_error('has_case_id');
        ?>
      </div>
     </div>  
    <div class = "row">
    <div class="col-sm-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.REFERENCES.$selected_data['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="col-sm-6 form-group"> 
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
     // $url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
      $url  = SITE_URL.REFERENCES.$selected_data['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    </div>
      <div class="col-sm-8 form-group">
        <input type="submit" name="btn_reference_edit" id='btn_reference_edit' value="Update" class="btn btn-success cls_disabled">
      </div>
    
  <?php echo form_close(); ?>
  <div class="col-sm-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" id="ref_initiation_mail" data-url="<?= ADMIN_SITE_URL.'Reference_verificatiion/initiation_mail/'.$selected_data['id'] ?>">Initiation Mail</button>
      <button class="btn btn-info"  id="ref_summery_mail" data-url="<?= ADMIN_SITE_URL.'Reference_verificatiion/summary_mail/'.$selected_data['id'] ?>" data-toggle="summery_mail" >Summary Mail</button> 
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/9/'.$selected_data['reference_id'] ?>/reference_verificatiion" <?=$check_insuff_raise?> data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>

<script>
   $('.gen').prop('disabled', true);
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

 $('.myDatepicker1').datepicker({
      daysOfWeekDisabled: [0,6],
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true, 
  });


 $('#ref_initiation_mail').click(function(){
    var name_of_reference = $('#name_of_reference').val();

    if(name_of_reference != "")
    {
      var url = $(this).data("url");
      $('.append-email_tem').load(url,function(){
        $('#initiationModel').modal('show');
        $('#initiationModel').addClass("show");
        $('#initiationModel').css({background: "#0000004d"});
      });
    }
    else
    {
      show_alert('Name of reference should not be empty','error');
      return true;
    }

  });

 $('#ref_summery_mail').click(function(){
   
      var url = $(this).data("url");
      $('.append-summery_mailView').load(url,function(){
        $('#summeryMailModel').modal('show');
        $('#summeryMailModel').addClass("show");
        $('#summeryMailModel').css({background: "#0000004d"});
      });
  });

  
  
$(document).ready(function(){
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);
  $('.cls_disabled').prop('disabled', true);
  
  $('#frm_reference_update').validate({ 
    rules: {
      reference_id : {
        required :true
      },
      has_case_id : {
        required : true,
        greaterThan: 0
      },
      clientid : {
        required : true,
        greaterThan: 0
      },
      candsid : {
        required : true,
        greaterThan: 0
      },
      email_id : {
        email : true
      },
      reference_com_ref : {
        required : true
      },
      name_of_reference : {
        required : true
      },
      designation : {
        required : true
      },
      contact_no : {
        required : true,
        number : true,
        minlength : 11,
        maxlength : 13
      },
      iniated_date :{
          required : true
      }
    },
    messages: {
        has_case_id : {
          required : "Select Executive Name",
          greaterThan : "Select Executive Name"
        },
        clientid : {
          required : "Select Client"
        },
        candsid : {
          required : "Select Candidate"
        },
        reference_com_ref : {
          required : "Enter Component Verification Number"
        },
        name_of_reference : {
          required : "Enter Reference Name"
        },
        designation : {
          required : "Enter Designation"
        }, 
       email_id : {
        email : "Enter Valid Email ID"
        },   
        contact_no : {
          required : "Enter Contact No"
        },
        iniated_date :{
          required : "Enter Initiate Date",
        }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/update_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_reference_edit').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
         // $('#btn_reference_edit').attr('disabled',false);
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


$('.remove_file').on('click',function(){
  var id =  <?= ($this->permission['access_reference_list_edit']) ? $selected_data['reference_id'] : '"permission denied"'?>;
  if(id != "permission denied")
  {
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'reference_verificatiion/remove_uploaded_file/' ?>"+remove_id,
              type: 'GET',
              beforeSend:function(){
                //show_alert('deleting...','info');
              },
              complete:function(){
                $('#'+remove_id).remove();                
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == <?php echo SUCCESS_CODE; ?>)
                {
                  show_alert(message,'success'); 
                }
                else
                {
                  show_alert(message,'danger'); 
                }
                location.reload(); 
              }
          });
      } 
      else 
      {
        return;
      }
    }
    else 
    {
       alert('You have not permission to delete the file'); 
    } 
    });
</script>
<script type="text/javascript">
    $(".select2").select2();
</script>