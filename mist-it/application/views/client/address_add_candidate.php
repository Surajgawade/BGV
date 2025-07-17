<div class="box box-primary">
 <?php echo form_open_multipart('#', array('name'=>'frm_address','id'=>'frm_address')); ?>
  <div class="box-body">    
    
      <input type="hidden" name="candsid"  id = "candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',date('d-m-Y')); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
       <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
   
    <?php
   
    if(!empty($mode_of_verification))
    {
    $mode_of_verification_value = json_decode($mode_of_verification[0]['mode_of_verification']);
    } 
    ?>
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->addrver)) { echo $mode_of_verification_value->addrver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>
      
    <br>
   
   <div class="clearfix"></div>
   <div class="row">
    <table border = "1">
      <tr>
        <th style="padding: 10px;"> Field </th>
        <th style="padding: 10px;"> Input </th>
      </tr>
      <tr>
        <td style="padding: 10px;"> Address type </td>
        <td style="padding: 10px;">  
          <?php
           echo form_dropdown('address_type', ADDRESS_TYPE, set_value('address_type',$address_type_status), 'class="custom-select" id="address_type"');
            echo form_error('address_type'); 
          ?> 
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> Stay From </td>
        <td style="padding: 10px;">  
           <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from'); ?>" class="form-control">
           <?php echo form_error('stay_from'); ?>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> Stay To </td>
        <td style="padding: 10px;">  
           <input type="text" name="stay_to" id="stay_to" value="<?php echo set_value('stay_to'); ?>" class="form-control">
           <?php echo form_error('stay_to'); ?>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> Street Address </td>
        <td style="padding: 10px;">  
          <textarea name="address" rows="1" id="address" class="form-control"><?php echo set_value('address'); ?></textarea>
          <?php echo form_error('address'); ?>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> City </td>
        <td style="padding: 10px;">  
          <input type="text" name="city" id="city" value="<?php echo set_value('city'); ?>" class="form-control">
          <?php echo form_error('city'); ?>

        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> Pincode </td>
        <td style="padding: 10px;">  
          <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
          <?php echo form_error('pincode'); ?>

        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> State </td>
        <td style="padding: 10px;">  
          <?php
            echo form_dropdown('state', $states, set_value('state'), 'class="custom-select" id="state"');
            echo form_error('state');
          ?>
        </td>
      </tr>
      <tr>
        <td style="padding: 10px;"> Attachment  (Not More Than 20 MB)</td>
        <td style="padding: 10px;">  
          <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
          <?php echo form_error('attchments'); ?>
        </td>
      </tr>
      <?php if($address_type_status == "current")
      {
      ?>
      <tr>
        <td colspan="2" style="padding: 10px;"><input type="checkbox" name="current_permanent_address" id="current_permanent_address" value="" onchange="stickyheaddsadaer(this)"/>
        <em> Check this box if current address and permanent address Same</em></td>
      </tr>
      <?php 
      }
      ?>
      <tr>
        <td colspan="2" style="padding: 10px;">
          <input type="submit" name="btn_address" id='btn_address' value="Submit" class="btn btn-success">
          <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
        </td>
      </tr>
    </table>
    
    <div class="clearfix"></div>
    <div class="clearfix"></div> 
  </div>
   <?php echo form_close(); ?>
</div>



<script>
$(document).ready(function(){

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);


  $('#frm_address').validate({
      rules: {
      address : {
        required : true
      },
      stay_from : {
        required : true
      },
      stay_to : {
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
     
      state : {
         required : true,
         greaterThan: 0
      },
      address_type : {
         required : true,
         greaterThan: 0
      }
    },
    messages: {
     
      address : {
        required : "Enter address"
      },
      stay_from : {
        required : "Enter Stay From"
      },
      stay_to : {
        required : "Enter Stay To"
      },
      city : {
        required : "Enter City"
      },
      pincode : {
        required : "Enter Pincode"
      },
       state : {
        required : "Please select state",
        greaterThan:  "Please select state"
      },
       address_type : {
         required : "Please select address type",
         greaterThan: "Please select address type"
      }
    },
    submitHandler: function(form) 
    {

      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'candidate_mail/save_address'; ?>',
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
          $('#btn_address').attr('disabled',false);
          jQuery('.body_loading').hide();
        },
        success: function(jdata){
          var message =  jdata.message || '';
        
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


function stickyheaddsadaer(obj) {
    if($(obj).is(":checked")){
      $('.permanent_address').hide();
    }else{
      $('.permanent_address').show();
    }
  }

</script>