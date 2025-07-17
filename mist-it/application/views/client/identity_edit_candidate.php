<div class="box box-primary" >
<?php echo form_open_multipart('#', array('name'=>'identity_edit','id'=>'identity_edit')); ?>
  <div class="box-body">    
    
      <input type="hidden" name="update_id"  id="update_id" value="<?php echo set_value('update_id',$selected_data['identity_id']); ?>" class="form-control">
      <input type="hidden" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>" class="form-control">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>" class="form-control">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']); ?>" class="form-control">
      <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
       <input type="hidden" name="clientname" readonly="readonly" value="<?php echo set_value('clientname',$get_cands_details['clientname']); ?>" class="form-control">
   
   
      <div class="text-white div-header">
       Provided Documents
      </div>    
    </br>  

   <div class="clearfix"></div>
   <div class="row">
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Doc Submitted<span class="error">*</span></label>
      <?php
       echo form_dropdown('doc_submited', array('' => 'Select','aadhar card' => 'Aadhar Card','pan card' => 'PAN Card'), set_value('doc_submited'), 'class="custom-select" id="doc_submited"');
        echo form_error('doc_submited'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Id Number<span class="error">*</span></label>
      <input type="text" name="id_number" id="id_number" value="<?php echo set_value('id_number'); ?>" class="form-control">
      <?php echo form_error('id_number'); ?>
    </div>
   
   </div>
    <br>
      <div class="text-white div-header">
       Attachments & other
      </div>    
    </br>  
  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachments <span class="error">*</span> <span class="error">(Not More Than 20 MB)</span></label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="sbidentity" id='sbidentity' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
    
    <div class="clearfix"></div>  
    <?php echo form_close(); ?>
  </div>
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

    $('#identity_edit').validate({
      rules: {
      doc_submited : {
        required :  true
      },
      id_number : {
         required :  true  
      },
      "attchments[]" : {
        required : true
      }
    },
    messages: {

      doc_submited : {
        required : "Select Document Submitted"
      },
      id_number : {
        required :  "Enter Id Number" 
      },
      "attchments[]" : {
        required : "Please Attach Document"
      }  
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'candidate_mail/update_identity'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbidentity').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbidentity').attr('disabled',false);
          jQuery('.body_loading').hide();
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

 
</script>