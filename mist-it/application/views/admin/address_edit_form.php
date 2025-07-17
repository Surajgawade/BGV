<?php
if(strpos($addressver_details['stay_from'],"-") == 4)
{
   
   $stay_from  = date("d-m-Y", strtotime($addressver_details['stay_from']));
}
else
{
   $stay_from  =  $addressver_details['stay_from'];
}

if(strpos($addressver_details['stay_to'],"-") == 4)
{
   
   $stay_to  = date("d-m-Y", strtotime($addressver_details['stay_to']));
}
else
{
   $stay_to  = $addressver_details['stay_to'];
}

?>



  <div class="row">
    <div class="col-sm-12">
      <div class="page-title-box">
       <!-- <h4 class="page-title">Case Details</h4>-->
        <div class="text-white div-header">
          Case Details
        </div>
         <br>
          <div style="float: right;">
            <ol class="breadcrumb">
              <li><button class="btn btn-secondary waves-effect  edit_btn_click" data-frm_name='frm_add_update' data-editUrl="<?= ($this->permission['access_address_list_edit']) ? encrypt($addressver_details['id']) : ''?>"><i class="fa fa-edit"></i> Edit</button></li>
              <li><button class="btn btn-secondary waves-effect delete" id="<?php echo $addressver_details['id'] ?>" data-accessUrl="<?= ADMIN_SITE_URL.'address/delete/'.encrypt($addressver_details['id']); ?>"><i class="fa fa-trash"></i> Delete</button></li>
            </ol>
          </div>
      </div>
    </div>
  </div>
<div class="box-body">
  <?php echo form_open_multipart('#', array('name'=>'frm_add_update','id'=>'frm_add_update')); ?>  
    <div class="row"> 
      <div class="col-sm-4 form-group">
        <label>Candidate Name</label>
        <input type="hidden" name="address_id" value="<?php echo set_value('address_id',$addressver_details['id']); ?>">
        <input type="hidden" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>">
        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
        <input type="text" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
      </div>
      <div class="col-sm-4 form-group">
        <label>Client</label>
        <input type="text" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
      </div>
      <div class="col-sm-4 form-group">
        <label>Entity</label>
        <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$get_cands_details['entity_name']); ?>" class="form-control">
      </div>
      </div>
      <div class="row"> 
      <div class="col-sm-4 form-group">
        <label>Package</label>
        <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$get_cands_details['package_name']); ?>" class="form-control">
      </div>
      <div class="col-sm-4 form-group">
        <label>Case received date</label>
        <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($get_cands_details['caserecddate'])); ?>" class="form-control">
      </div>
      <div class="col-sm-4 form-group">
        <label>Client Ref No</label>
        <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>" class="form-control">
      </div>
      </div>
      <div class="row"> 
      <div class="col-sm-4 form-group">
        <label>Component Ref No</label>
        <input type="text" name="add_com_ref" id="add_com_ref" readonly="readonly" value="<?php echo set_value('add_com_ref',$addressver_details['add_com_ref']); ?>" class="form-control">
        <?php echo form_error('add_com_ref'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Candidate Ref No</label>
        <input type="text" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$get_cands_details['cmp_ref_no']); ?>" class="form-control">
      </div>
      <div class="col-sm-4 form-group">
        <label>Initiation date<span class="error"> *</span></label>
       <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($addressver_details['iniated_date'])); ?>" class="form-control cls_disabled myDatepicker" placeholder='DD-MM-YYYY'>


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
         <input type="text" name="mod_of_veri"  readonly="readonly" id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->addrver)) { echo $mode_of_verification_value->addrver; } ?>" class="form-control">
        <?php echo form_error('mod_of_veri'); ?>
      </div>

        <?php   
       if($this->user_info['bill_date_permission'] == "yes")
        {
        ?>
        <div class="col-sm-4 form-group">
        <?php 
        }else{ ?>
        <div class="col-sm-4 form-group" style="display: none;">
      <?php } ?>
      <label>Billed Date</label>
       <input type="text" name="build_date" id="build_date" value="<?php echo set_value('build_date',$addressver_details['build_date']); ?>" class="form-control cls_disabled" placeholder='DD-MM-YYYY'>
       <?php echo form_error('build_date'); ?>
    </div> 

      <div class="col-sm-4 form-group">
        <label>Status </label>
        <input type="text" name="status" id="status" readonly="readonly" value="<?php echo set_value('status',$addressver_details['verfstatus']); ?>" class="form-control">
        <?php echo form_error('status'); ?>
      </div>
      </div> 


      <div class="row"> 
        <div class="col-sm-4 form-group">
          <label>Primary Cantact No</label>
          <input type="text" name="primary_contact"  readonly="readonly" id="primary_contact" value="<?php echo set_value('primary_contact',$get_cands_details['CandidatesContactNumber']); ?>"  class="form-control">
          <?php echo form_error('primary_contact'); ?>
        </div>
        <div class="col-sm-4 form-group">
          <label>Cantact No 1</label>
          <input type="text" name="contact_no_1"  readonly="readonly" id="contact_no_1" value="<?php echo set_value('contact_no_1',$get_cands_details['ContactNo1']); ?>"  class="form-control">
          <?php echo form_error('contact_no_1'); ?>
        </div>
        <div class="col-sm-4 form-group">
          <label>Cantact No 2</label>
          <input type="text" name="contact_no_2"  readonly="readonly" id="contact_no_2" value="<?php echo set_value('contact_no_2',$get_cands_details['ContactNo2']); ?>" class="form-control">
          <?php echo form_error('contact_no_2'); ?>
        </div>
      </div>
      <div class="row"> 
      <div class="col-sm-4 form-group">
        <label>Re-Initiated date<span class="error"> *</span></label>
       <input type="text" name="re_iniated_date" id="re_iniated_date" value="<?php echo set_value('re_iniated_date',$addressver_details['add_re_open_date']); ?>" class="form-control cls_disabled myDatepicker" placeholder='DD-MM-YYYY'>
     </div>


        <?php echo form_error('iniated_date'); ?>

        <!-- <input type="date" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',$addressver_details['iniated_date']); ?>" class="form-control cls_disabled myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>-->
      </div>

     <br> 
     <!-- <hr style="border-top: 2px solid #bb4c4c;">-->
    <div class="text-white div-header">
         Candidate Address
    </div>
    <br>
    <div class="row">
      <div class="col-sm-4 form-group">
        <label>Stay From </label>
        <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$stay_from); ?>" class="form-control cls_disabled">
        <?php echo form_error('stay_from'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Stay To</label>
        <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to',$stay_to); ?>" class="form-control cls_disabled">
        <?php echo form_error('stay_to'); ?>
      </div>
    </div>
      <div class="row">
      <div class="col-md-4 col-sm-8 col-xs-4 form-group">
        <label>Address type</label>
        <?php
         echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type',$addressver_details['address_type']), 'class="select2 cls_disabled" id="address_type"');
          echo form_error('address_type'); 
        ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Street Address<span class="error">*</span></label>
        <textarea name="address" rows="1" id="address" class="form-control cls_disabled"><?php echo set_value('address',$addressver_details['address']); ?></textarea>
        <?php echo form_error('address'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>City<span class="error">*</span></label>
        <input type="text" name="city" id="city" value="<?php echo set_value('city',$addressver_details['city']); ?>" class="form-control cls_disabled">
        <?php echo form_error('city'); ?>
      </div>
      </div>
      <div class="row">
      <div class="col-sm-4 form-group">
        <label>Pincode<span class="error">*</span></label>
        <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode',$addressver_details['pincode']); ?>" class="form-control cls_disabled">
        <?php echo form_error('pincode'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>State<span class="error">*</span></label> 
        <?php
          echo form_dropdown('state',$states, set_value('state',strtolower($addressver_details['state'])),'class="select2 cls_disabled" id="state"');
          echo form_error('state');
        ?>
      </div>
      </div>
       <br>
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
        <input type="file" name="attchments_cs[]" accept=".png, .jpg, .jpeg, .pdf" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control cls_disabled">
        <?php echo form_error('attchments'); ?>
      </div>
      <div class="col-sm-4 form-group">
        <label>Executive Name<span class="error">*</span></label>
        <?php
          echo form_dropdown('has_case_id', $assigned_user_id, set_value('has_case_id',$addressver_details['has_case_id']), 'class="select2 cls_disabled" id="has_case_id"');
          echo form_error('has_case_id');
        ?>
      </div>

    </div> 
    <div class="row">
    <div class="col-sm-6 form-group">
    <ol>
    <?php 
    foreach ($attachments as $key => $value) {
      //$url  = SITE_URL.UPLOAD_FOLDER.CANDIDATES.$candidate_details['clientid'].'/';
         $url  = SITE_URL.ADDRESS.$addressver_details['clientid'].'/';
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
      $url  = SITE_URL.ADDRESS.$addressver_details['clientid'].'/';
      if($value['type'] == 2)
      {
        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a>  <?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></li> <?php
      }
    }
    ?>
    </ol>
    </div>
    </div> 
     <input type="hidden" name="upload_capture_image_address" id="upload_capture_image_address" value=""> 
     
      <div class="col-sm-4 form-group">
        <input type="submit" name="btn_address" id='btn_address' value="Update" class="btn btn-success cls_disabled">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#capture_image_copy_address">Copy Attachment</button>
      </div>
  <?php echo form_close(); ?>


  <div class="col-sm-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/1/'.$addressver_details['id'] ?>/address" <?=$check_insuff_raise?>  data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>

   <!--<div class="col-md-12 col-sm-12 col-xs-12 form-group">
    <div style="float: right;">
      <button class="btn btn-info" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view/1/'.$addressver_details['id'] ?>/address/<?=$check_insuff_value?>" data-toggle="modal" id="activityModelClk">Activity</button>
    </div>
  </div>-->
</div>

<div id="capture_image_copy_address" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_address','id'=>'frm_upload_copied_image_address')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-address"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_address" name="upload_copied_image_address">Upload</button>
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
.image-content-result-address {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-address::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-address:not(:empty)::after,
.image-content-result-address:focus::after {
  display: none;
}

.image-content-result-address * {
  max-width: 100%;
  border: 0;
}
</style>
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


 $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  $('input').attr('autocomplete','off');
  $('.readonly').prop('readonly', true); 
  $('.cls_disabled').prop('disabled', true);

  $('#upload_copied_image_address').on('click',function() {
      var get_image_name =  $('#upload_capture_image_address').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-address img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_address').val(get_image_base);
          $('#capture_image_copy_address').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });
  
  $('#frm_add_update').validate({ 
    rules: {
      address_id  : {
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
      pincode : {
        required : true,
        number : true,
        minlength : 6,
        maxlength : 6
      },
      address : {
        required : true
      },
      city : {
         required : true,
        lettersonly : true
      },
       state : {
         required : true,
         greaterThan: 0
      
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
      address : {
        required : "Enter address"
      },
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
       state : {
        required : "Please select state",
        greaterThan : "Please select state"
      },
      iniated_date :{
         required : "Enter Initiate Date",
      }

    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'address/frm_update_address'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_address').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
         // $('#btn_address').attr('disabled',false);
         // jQuery('.body_loading').hide();
        },
        success: function(jdata){

          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
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
});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete', function(){  
           var address_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'address/delete/';?>",  
                     method:"POST",  
                     data:{address_id:address_id},  
                     success: function(jdata){
                    var message =  jdata.message || '';
                    (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
                    if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                      show_alert(message,'success');

                     setTimeout(function(){
                             window.location = "<?php echo ADMIN_SITE_URL.'clients/';?>";
                       },1000);
                    }
                    if(jdata.status == <?php echo ERROR_CODE; ?>){
                      show_alert(message,'error'); 
                    }
                  }
                });  
           }  
           else  
           {  
                return false;       
           }  
      });

      $('.remove_file').on('click',function(){
      var id =  <?= ($this->permission['access_address_list_edit']) ? $addressver_details['id'] : '"permission denied"'?>;
      if(id != "permission denied")
      {  
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo ADMIN_SITE_URL.'address/remove_uploaded_file/' ?>"+remove_id,
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