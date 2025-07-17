<div class="content-wrapper">    
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
           
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
                  <label>Client/Employee ID<span class="error"> *</span></label>
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
                    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Contact No (3)</label>
                    <input type="text" name="ContactNo2" maxlength="12" id="ContactNo2" value="<?php echo set_value('ContactNo2'); ?>" class="form-control">
                    <div id = "candidateContactNo2" class="error"></div>
                    <?php echo form_error('ContactNo2'); ?>
                    </div>
                </div>
             
                  <br>
                  <div class="text-white div-header">
                    Attachments & other
                  </div>
                  <br>
                  <div class="row">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Attachment <span class="error"></span></label>
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
                    <?php echo form_error('attchments'); ?>
                  </div>
                  <div class="col-sm-4">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="1" maxlength="250"></textarea>
                        <?php echo form_error('remarks'); ?>
                  </div> 
                </div> 
              </div>
           
          </div>
        </div>
      </div>
    

<script>
$(document).ready(function(){

$('#clientid').attr("style", "pointer-events: none;");

   $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });


get_entity_package();

 
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


$(document).ready(function(){
    $("#attchments").on('change',function(){

        var fileInput = document.getElementById('attchments').files[0].name;   

        document.getElementById('remarks').innerText = fileInput;
  
    });
});

</script>