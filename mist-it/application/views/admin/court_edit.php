  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
        <div class="text-white div-header">
          Court Verification Case Details
        </div>
        <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect btn-sm  edit_btn_click" data-frm_name='frm_court_update' data-editUrl="<?= ($this->permission['access_court_list_edit']) ? encrypt($selected_data['courtver_id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
             
              <li><button class="btn btn-secondary waves-effect btn-sm" data-accessUrl="<?= ADMIN_SITE_URL.'court_verificatiion/delete/'.encrypt($selected_data['courtver_id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_court_update','id'=>'frm_court_update')); ?>
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Candidate Name</label>
        <input type="hidden" name="courtver_id" id="courtver_id" value="<?php echo set_value('courtver_id',$selected_data['courtver_id']); ?>" class="form-control">
        <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
        <input type="text" name="CandidateName" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control readonly">
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
        <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control readonly">
      </div>
      </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Component Ref No</label>
        <input type="text" name="court_com_ref" id="court_com_ref"  value="<?php echo set_value('court_com_ref',$selected_data['court_com_ref']); ?>" class="form-control readonly">
        <?php echo form_error('court_com_ref'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Candidate Ref No</label>
        <input type="text" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control readonly">
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
         <input type="text" name="mode_of_veri" readonly="readonly"  id="mode_of_veri" value="<?php if(isset($mode_of_verification_value->courtver)) { echo $mode_of_verification_value->courtver; } ?>" class="form-control cls_disabled">
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
        <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',convert_db_to_display_date($selected_data['courtver_re_open_date'])); ?>" class="form-control myDatepicker cls_disabled" placeholder='DD-MM-YYYY'>
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
        Candidate Address
      </div>
      <br>
    <div class="row">
    <div class="col-sm-4 form-group">
      <input type="checkbox" name="candidate_extract_court" id="candidate_extract_court" class="check_court" value="" onchange="stickyheaddsadaer(this)"/>
      <em> Check this box to get details from candidate</em>
    </div>
    <div class="col-sm-4 form-group">
      <input type="checkbox" name="address_extract_court" id="address_extract_court"  class="check_court" value="{TT_sticky_header}" onchange="stickyheaddsadaer_address(this)"/>
      <em> Check this box to get details from Address</em>
    </div>
    </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Address type</label>
        <?php
         echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type',$selected_data['address_type']), 'class="select2 cls_disabled" id="address_type"');
          echo form_error('address_type'); 
        ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Street Address</label>
        <textarea name="street_address" rows="1" maxlength="200" id="street_address" class="form-control cls_disabled"><?php echo set_value('street_address',$selected_data['street_address']); ?></textarea>
        <?php echo form_error('street_address'); ?>
      </div>
     </div>
     <div class="row">
      <div class="col-sm-4 form-group">
        <label>City</label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$selected_data['city']); ?>" class="form-control cls_disabled">
        <?php echo form_error('city'); ?>
      </div>
     
      <div class="col-sm-4 form-group">
        <label>Pincode</label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$selected_data['pincode']); ?>" class="form-control cls_disabled">
        <?php echo form_error('pincode'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>State</label>
        <?php
          echo form_dropdown('state', $states, set_value('state',$selected_data['state_id']), 'class="custom-select cls_disabled" id="state"');
          echo form_error('state');
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
      <div class="row">
   <div class="col-sm-4 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.COURT_VERIFICATION.$selected_data['clientid'].'/';
      if($value['type'] == 0)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    <div class="col-sm-4 form-group"> 
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
     // $url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
      $url  = SITE_URL.COURT_VERIFICATION.$selected_data['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    </div>  
      <div class="col-md-8 col-sm-12 col-xs-8 form-group">
        <input type="submit" name="btn_court_update" id='btn_court_update' value="Update" class="btn btn-success cls_disabled">
      </div>
    
  <?php echo form_close(); ?>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/3/'.$selected_data['courtver_id'] ?>/court_verificatiion" <?= $check_insuff_raise?> data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>
</div>
<script>
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


$(document).ready(function(){
  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true);
  $('.cls_disabled').prop('disabled', true);
  $('.gen').prop('disabled', true);


  $('#frm_court_update').validate({ 
    rules: {
      courtver_id : {
        required : true
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
      court_com_ref : {
        required : true
      },
      date_of_birth : {
        required : true
      },
      street_address : {
        required : true
      },
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      city : {
        required : true,
        lettersonly : true
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
      court_com_ref : {
        required : "Enter Component Verification Number"
      },
      date_of_birth : {
        required : "Select Date of Birth"
      },
      street_address : {
        required : "Enter Address"
      },     
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
      iniated_date :{
          required : "Enter Initiate Date",
      }

    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'court_verificatiion/update_form'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_court_update').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
         // $('#btn_court_update').attr('disabled',false);
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
  var id =  <?= ($this->permission['access_court_list_edit']) ? $selected_data['courtver_id'] : '"permission denied"'?>;
  if(id != "permission denied")
  {
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'court_verificatiion/remove_uploaded_file/' ?>"+remove_id,
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
function stickyheaddsadaer(obj) {
  if($(obj).is(":checked")){

    <?php if(!empty($get_cands_details)) {  ?>

     document.getElementById('street_address').value = '<?php echo  $get_cands_details["prasent_address"]  ?>';
     document.getElementById('city').value = '<?php echo  $get_cands_details["cands_city"] ?>';
     document.getElementById('pincode').value = '<?php echo $get_cands_details['cands_pincode'] ?>';
     document.getElementById('state').value = '<?php echo  $get_cands_details["cands_state"]  ?>';

     <?php } else {  ?>

      document.getElementById('address').value = '';
      document.getElementById('city').value = '';
      document.getElementById('pincode').value = '';
      document.getElementById('state').value = '';

      <?php } ?> 
    
  }else{
     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';
    
  }
  
}

function stickyheaddsadaer_address(obj) {
  if($(obj).is(":checked")){
    
    <?php if(!empty($get_address_details)) {  ?> 
 
     document.getElementById('address_type').value = '<?php echo  $get_address_details["address_type"]  ?>';
     document.getElementById('street_address').value = '<?php echo  $get_address_details["address"]  ?>';
     document.getElementById('city').value = '<?php echo  $get_address_details["city"] ?>';
     document.getElementById('pincode').value = '<?php echo $get_address_details['pincode'] ?>';
     document.getElementById('state').value = '<?php echo  $get_address_details["state"]  ?>';

    <?php } else {  ?>

     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';

      <?php } ?> 
    
    
  }else{
     document.getElementById('address_type').value = '';
     document.getElementById('street_address').value = '';
     document.getElementById('city').value = '';
     document.getElementById('pincode').value = '';
     document.getElementById('state').value = '';
    
  }
  
}


</script>
<script type="text/javascript">
    $(".select2").select2();
</script>