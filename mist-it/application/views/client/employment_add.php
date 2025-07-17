<div class="box box-primary">
    <?php echo form_open_multipart('#', array('name'=>'employment_add','id'=>'employment_add')); ?>
  <div class="box-body">    
      <input type="hidden" id = "candsid" name="candsid" value="<?php echo set_value('candsid',$get_cands_details['candsid']); ?>" class="form-control">
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
      <input type="hidden" name="mod_of_veri"  id="mod_of_veri" value="<?php if(isset($mode_of_verification_value->empver)) { echo $mode_of_verification_value->empver; } ?>" class="form-control cls_disabled">
      <?php echo form_error('mod_of_veri'); ?>

    <br>
    <div class="text-white div-header">
     Previous Employment Details
    </div>
    <br>
    <div class="row">
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Company Name <span class="error"> *</span> </label>
      <?php
         echo form_dropdown('nameofthecompany',array(), set_value('nameofthecompany'), 'class="form-control select2" id="nameofthecompany"');
        echo form_error('nameofthecompany'); 
      ?>
    </div>

    <input type="hidden" name="selected_company_name" id="selected_company_name" value="" class="form-control">        
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Deputed Company</label>
      <input type="text" name="deputed_company" id="deputed_company" value="<?php echo set_value('deputed_company'); ?>" class="form-control">
      <?php echo form_error('deputed_company'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Previous Employee Code</label>
      <input type="text" name="empid" id="empid" value="<?php echo set_value('empid'); ?>" class="form-control">
      <span class="error error_previous_emp_code" style="display: none">Enter Previous Code</span>
      <?php echo form_error('empid'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-8 col-xs-4 form-group">
      <label>Employment Type</label>
      <?php
       echo form_dropdown('employment_type',$this->employment_type, set_value('employment_type'), 'class="custom-select" id="employment_type"');
        echo form_error('employment_type'); 
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employed From <span class="error"> *</span><input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_from" id="calender_display_employee_from" value="text_value"> : Text 
      </label>
      <input type="date" name="empfrom" id="empfrom" value="<?php echo set_value('empfrom'); ?>" class="form-control">
      <?php echo form_error('empfrom'); ?>
    </div>                  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employed To  <span class="error"> *</span><input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="calender_value"> : Calender <input type="radio" name="calender_display_employee_to" id="calender_display_employee_to" value="text_value"> : Text </label>
      <input type="date" name="empto" id="empto" value="<?php echo set_value('empto'); ?>" class="form-control" >
      <?php echo form_error('empto'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation <span class="error"> *</span></label>
      <input type="text" name="designation" id="designation" value="<?php echo set_value('designation'); ?>" class="form-control">
      <?php echo form_error('designation'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remuneration</label>
      <input type="text" name="remuneration" id="remuneration" value="<?php echo set_value('remuneration'); ?>" class="form-control">
      <?php echo form_error('remuneration'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label for="reasonforleaving">Reason for Leaving <span class="error"> *</span></label>
      <textarea name="reasonforleaving" rows="1" id="reasonforleaving" class="form-control"><?php echo set_value('reasonforleaving');?></textarea>
      <?php echo form_error('reasonforleaving'); ?>
    </div>
    </div>
  
    <br>
    <div class="text-white div-header">
      Company contact Details
    </div>
    <br>
     <div class="row"> 
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>Name of HR</label>
      <input type="text" name="compant_contact_name" id="compant_contact_name" value="<?php echo set_value('compant_contact_name'); ?>" class="form-control">
      <?php echo form_error('compant_contact_name'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>HR's Designation</label>
      <input type="text" name="compant_contact_designation" id="compant_contact_designation" value="<?php echo set_value('compant_contact_designation'); ?>" class="form-control">
      <?php echo form_error('compant_contact_designation'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>HR's Email ID</label>
      <input type="text" name="compant_contact_email" id="compant_contact_email" value="<?php echo set_value('compant_contact_email'); ?>" class="form-control">
      <?php echo form_error('compant_contact_email'); ?>
    </div>
     <div class="col-md-3 col-sm-12 col-xs-4 form-group">
      <label>HR's Contact No</label>
      <input type="text" name="compant_contact"  minlength="6" maxlength="13" id="compant_contact" value="<?php echo set_value('compant_contact'); ?>" class="form-control">
      <?php echo form_error('compant_contact'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address</label>
      <textarea name="locationaddr" rows="1" id="locationaddr" class="form-control"><?php echo set_value('locationaddr'); ?></textarea>
      <?php echo form_error('locationaddr'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>City</label>
      <input type="text" name="citylocality" id="citylocality" value="<?php echo set_value('citylocality'); ?>" class="form-control">
       <span class="error error_city" style="display: none">Enter City/Branch(location)</span>
      <?php echo form_error('citylocality'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('state', $states, set_value('state'), 'class="form-control" id="state"');
        echo form_error('state');
      ?>
    </div>
    <div class="col-md-2 col-sm-12 col-xs-2 form-group">
      <label>Pincode</label>
      <input type="text" name="pincode" maxlength="6" id="pincode" value="<?php echo set_value('pincode'); ?>" class="form-control">
      <?php echo form_error('pincode'); ?>
    </div>
    </div>
    <br>
    <div class="text-white div-header">
      Reporting Manager Details
    </div>
    <br>

  
    <div class="row">
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager Name</label>
      <input type="text" name="r_manager_name" value="<?php echo set_value('r_manager_name'); ?>" class="form-control">
      <?php echo form_error('r_manager_name'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's contact</label>
      <input type="text" name="r_manager_no" minlength="10" maxlength="12" id="r_manager_no" value="<?php echo set_value('r_manager_no'); ?>" class="form-control">
      <?php echo form_error('r_manager_no'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Designation</label>
      <input type="text" name="r_manager_designation" id="r_manager_designation" value="<?php echo set_value('r_manager_designation'); ?>" class="form-control">
      <?php echo form_error('r_manager_designation'); ?>
    </div>
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Manager's Email ID</label>
      <input type="text" name="r_manager_email" id="r_manager_email" value="<?php echo set_value('r_manager_email'); ?>" class="form-control">
      <?php echo form_error('r_manager_email'); ?>
    </div>
    </div>
  
    <br>
    <div class="text-white div-header">
      Supervisor Details
    </div>
    <br>
    <input type="hidden" name="hdn_counter" id="hdn_counter" value="1">
    <div id="supervisor_details"><div class="add_supervisor_details_row"></div></div>


    <br>
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Data Entry</label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>

      <div class="col-sm-4 form-group">
      <label>Experience/Relieving Letter</label>
      <input type="file" name="attchments_reliving[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <span class="error error_relieving_letter" style="display: none">Please Attach Experience/Relieving Letter</span>
      <?php echo form_error('attchments'); ?>
    </div>
    <div class="col-sm-4 form-group">
      <label>LOA</label>
      <input type="file" name="attchments_loa[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <span class="error error_loa" style="display: none">Please Attach LOA</span>
      <?php echo form_error('attchments'); ?>
    </div>
  </div>
  
    <div class="clearfix"></div>  
  </div>

    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <input type="submit" name="sbemployment" id='sbemployment' value="Submit" class="btn btn-success">
      <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
    
    
    <div class="clearfix"></div>  
    <?php echo form_close(); ?>
  </div>

<div id="addAddCompanyModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'add_company','id'=>'add_company')); ?>
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Add Company</h4>
    </div>
    <div class="modal-body append_model"></div>
    <div class="modal-footer">
      <button type="submit" id="company_add" name="company_add" class="btn btn-success">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<script>

var counter = $('#hdn_counter').val();

$(document).ready(function(){

    $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

  $.ajax({
   
        type:'POST',
        url:'<?php echo CLIENT_SITE_URL.'candidate_mail/load_supervisor_details/ena/'; ?>'+counter,
        data:'',
        beforeSend :function(){
          //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
        },
        success:function(html)
        {
          jQuery('#supervisor_details').append(html);
        }
  });

$(document).on('click', '.add_supervisor_details_row', function(){
  counter++;
  if(counter != 0 && counter < 6)
  {
    $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'Empver/load_supervisor_details/ena/'; ?>'+counter,
          data:'',
          beforeSend :function(){
            //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#supervisor_details').append(html);
          }
    });
  }
  else
  {
    show_alert('Max 5 row can add','warning');
  }
}).trigger('click');

$(document).on('click', '.remove_supervisor_details_row', function(){
    if(counter != 1){
      $('#counter_id_'+$(this).data('id')).remove();
      counter--;
    }else{
      show_alert("Can't Remove",'warning');
    }
}).trigger('click');

$('input[name = "calender_display_employee_from"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empfrom"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empfrom"]').prop('type','text');
      }
     
  $('input[name = "calender_display_employee_to"]').change(function()  {
      if(this.value  === 'calender_value')
      {
        $('input[name="empto"]').prop('type','date');
      }
       if(this.value  === 'text_value')
      {
        $('input[name="empto"]').prop('type','text');
      }   
    
  });
 });   

  $('input').attr('autocomplete','on');
  $('.readonly').prop('readonly', true);


   $('#employment_add').validate({
      rules: {
      nameofthecompany : {
        required : true
      },
       empfrom : {
        required : true
      },
       empto : {
        required : true
      },
       designation : {
        required : true
      },
      reasonforleaving : {
        required : true
      }
    },
    messages: {
      nameofthecompany : {
        required : "Enter Name of Company"
      },
      empfrom : {
        required : "Enter Employment From"
      },
       empto : {
        required : "Enter Employment To"
      },
       designation : {
        required :  "Enter Employment Designation"
      },
      reasonforleaving : {
        required : "Enter Reason of Leaving"
      }
    },
    submitHandler: function(form) 
    {
      
      $.ajax({
        url : '<?php echo CLIENT_SITE_URL.'Empver/save_employment'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbemployment').attr('disabled','disabled');
          jQuery('.body_loading').show();
        },
        complete:function(){
          $('#sbemployment').attr('disabled',false);
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

</script>
<script type="text/javascript">
  // Select2
  $(".select2").select2();
         
    $("#nameofthecompany").select2({
      placeholder: "Select Company",
      multiple: false,
      minimumInputLength: 3,
      ajax: 
      {
          url: "<?php echo CLIENT_SITE_URL.'empver/get_company_details' ?>",
          dataType: 'json',
          data: function(term) {
              return {
                  q: term
              };
          },
          processResults: function(response) {
            var myResults = [];
              $.each(response, function (index, item) {
                    myResults.push({
                        'id': item.id,
                        'text': item.company_name
                    });
                });
              return {results: myResults};
          },
          cache: true
      },
    
  });
    

  $('#nameofthecompany').change(function(){
    
      var selected_company =  $(this).find(":selected").text();

      $('#selected_company_name').val(selected_company);
    
      if(selected_company == "Other")
      {
        var id = '<?php echo CLIENT_SITE_URL.'empver/addAddCompanyModel'?>';
          $('.append_model').load(id,function(){
            $('#addAddCompanyModel').modal('show');
            $('#addAddCompanyModel').addClass("show");
            $('#addAddCompanyModel').css({background: "#0000004d"});
          }); 
      }

    $.ajax({  
                     
      url: "<?php echo CLIENT_SITE_URL.'empver/get_required_fields' ?>",
      type: "post",
      async:false,
      data: { company_name: function() { return $( "#nameofthecompany" ).val(); } },
      success: function(response){
        if(response.branch_location == '1'){  
          $('.error_city').css('display','inline-block'); 
        }else{
          $('.error_city').css('display','none');
        }
        if(response.previous_emp_code == '1'){ 
          $('.error_previous_emp_code').css('display','inline-block'); 
        }else{
          $('.error_previous_emp_code').css('display','none');
        }
        if(response.experience_letter == '1'){ 
          $('.error_relieving_letter').css('display','inline-block'); 
        }else{
          $('.error_relieving_letter').css('display','none');
        }
        if(response.loa == '1'){ 
          $('.error_loa').css('display','inline-block'); 
        }else{
          $('.error_loa').css('display','none');
        }
                     
      }
    });
  
  }); 

  $('#add_company').validate({ 
      rules: {
        coname : {
          required : true
        },
        address : {
          required : true
        },
        city : {
          required : true,
          lettersonly : true
        },
        pincode : {
          required : true,
          number : true,
          minlength : 6,
          maxlength : 6
        },
        state : {
          required : true,
          greaterThan: 0
        },
        co_email_id : {
          multiemails : true
        },
        cc_email_id : {
           multiemails : true
        }
      },
      messages: {
        coname : {
          required : "Enter Name"
        },
        address : {
          required : "Enter Address"
        },
        city : {
          required : "Enter Address"
        },
        pincode : {
          required : "Enter Pincode"
        },
        state : {
          required : "Select State",
          greaterThan : "Select State"
        },
        co_email_id:{
         multiemails : "Enter Valid Email Id"
        },
        cc_email_id :{
           multiemails : "Enter Valid Email ID"
        }
      },
      submitHandler: function(form) 
      {      
          $.ajax({
          url: '<?php echo CLIENT_SITE_URL.'empver/save_company'; ?>',
          data: new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#company_add').attr('disabled','disabled');
          },
          complete:function(){
            $('#company_add').removeAttr('disabled');           
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#add_company')[0].reset();
              $('#addAddCompanyModel').modal('hide');
              var newOption = new Option(jdata.coname, jdata.insert_id, true, true);
 
              $('#nameofthecompany').append(newOption).trigger('change');
              $('#selected_company_name').val(jdata.coname);
            }
            else{
              show_alert(message,'error'); 
            }
          }
        });      
      }
  }); 

jQuery.validator.addMethod("multiemails", function (value, element) {
    if (this.optional(element)) {
    return true;
    }

    var emails = value.split(','),
    valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
    value = jQuery.trim(emails[i]);
    valid = valid && jQuery.validator.methods.email.call(this, value, element);
    }
    return valid;
    }, "Invalid email format: please use a comma to separate multiple email addresses.");
    
    jQuery.validator.addMethod('matches', function (value, element) {
    return this.optional(element) || /^[a-z0-9](\.?[a-z0-9]){5,}@m$/i.test(value);
    }, "use be a  e-mail address");
</script>