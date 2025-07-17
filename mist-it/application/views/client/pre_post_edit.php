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
                <input type="hidden" name="update_id" id="update_id" value="<?php echo $pre_post_details[0]['id'];?>">
                <input type="hidden" name="selected_client" id="selected_client" value="<?php echo $pre_post_details[0]['client_id'];?>">
                <input type="hidden" name="selected_entity" id="selected_entity" value="<?php echo $pre_post_details[0]['entity'];?>">
                <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $pre_post_details[0]['package'];?>">
    
                <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-3 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                                   
                    echo form_dropdown('clientid', $clients, set_value('clientid',$pre_post_details[0]['client_id']), 'class="custom-select" id="clientid" disabled');
                    echo form_error('clientid');
                  ?>
                </div>
                
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Entity<span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('entity', array(), set_value('entity',$pre_post_details[0]['entity']), 'class="custom-select" id="entity" disabled');
                    echo form_error('entity');
                  ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Spoc/Package<span class="error"> *</span></label>
                   <select id="package" name="package" class="custom-select" disabled><option value="0">Select</option></select>
                  <?php echo form_error('package');?>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Case Received Date<span class="error"> *</span></label>
                  <input type="text" name="caserecddate" id="caserecddate" value="<?php echo convert_db_to_display_date($pre_post_details[0]['initiation_date']); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' readonly>
                    <?php echo form_error('caserecddate'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Client/Employee ID<span class="error"> *</span></label>
                  <input type="text" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$pre_post_details[0]['client_ref_no']); ?>" class="form-control" readonly>
                  <?php echo form_error('ClientRefNumber'); ?>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label>Candidate Name <span class="error"> *</span></label>
                  <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$pre_post_details[0]['candidate_name']); ?>" class="form-control" readonly>
                  <?php echo form_error('CandidateName'); ?>
                </div>
                </div>
                <div class="row">
              
                    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Primary Contact <span class="error"> *</span></label>
                    <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$pre_post_details[0]['primary_contact']); ?>" class="form-control" readonly>
                    <div id = "candidatecontact" class="error"></div>
                    <?php echo form_error('CandidatesContactNumber'); ?>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Contact No (2)</label>
                    <input type="text" name="ContactNo1" maxlength="12" id="ContactNo1" value="<?php echo set_value('ContactNo1',$pre_post_details[0]['contact_two']); ?>" class="form-control" readonly>
                    <div id = "candidateContactNo1" class="error"></div>
                    <?php echo form_error('ContactNo1'); ?>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Contact No (3)</label>
                    <input type="text" name="ContactNo2" maxlength="12" id="ContactNo2" value="<?php echo set_value('ContactNo2',$pre_post_details[0]['contact_three']); ?>" class="form-control" readonly>
                    <div id = "candidateContactNo2" class="error"></div>
                    <?php echo form_error('ContactNo2'); ?>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Mist ID</label>
                    <input type="text" name="mist_id" id="mist_id" value="<?php echo set_value('mist_id',$pre_post_details[0]['component_ref_no']); ?>" class="form-control" readonly>
                    <?php echo form_error('mist_id'); ?>
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
                    <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control" disabled>
                    <?php echo form_error('attchments'); ?>
                  </div>

                 
                <div class="col-sm-12 form-group">
                    <ol>
                    <?php 
                    foreach ($attachment as $key => $value) {
                        $url  =  SITE_URL. "uploads/task_file/";
                    
                        ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
                        return false'><?= $value['file_name']?></a></li> <?php
                    
                    }
                    ?>
                    </ol>
                </div> 
                </div> 
                 <br>
                  <div class="text-white div-header">
                   Post Details
                  </div>
                  <br>
                <div class = "row">
                    <div class="col-sm-4">
                        <label>Case Received Date<span class="error"> *</span></label>
                        <input type="text" name="caserecddate_post" id="caserecddate_post" value="<?php echo date('d-m-Y'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY' readonly>
                        <?php echo form_error('caserecddate_post'); ?>
                    </div>
                    <div class="col-sm-4">
                        <label>Attachment <span class="error"></span></label>
                        <input type="file" name="attchments_post[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_post" value="<?php echo set_value('attchments_post'); ?>" class="form-control">
                        <?php echo form_error('attchments_post'); ?>
                    </div>
                    <div class="col-sm-4">
                        <label>Remarks<span class="error"> *</span></label>
                        <textarea class="form-control" name="remarks_post" id="remarks_post" rows="1" maxlength="250"></textarea>
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

  $('#clientid').on('change',function(){
  var clientid = $(this).val();
  var entity = $('#selected_entity').val();
  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/get_entity_list'; ?>',
          data:'clientid='+clientid+'&selected_entityy='+entity,
          beforeSend :function(){
            jQuery('#entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity').html(html);
          }
      });
  }
}).trigger('change'); 


$('#entity').on('change',function(){
    var entity = $(this).val() || $('#selected_entity').val();
    var selected_paclage = $('#selected_package').val();
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo CLIENT_SITE_URL.'candidates/get_package_list'; ?>',
            data:'entity='+entity+'&selected_paclage='+selected_paclage,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package').html(html);
            }
        });
    }
  }).trigger('change');


}); 

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
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#attchments_post").on('change',function(){

        var fileInput = document.getElementById('attchments_post').files[0].name;   

        document.getElementById('remarks_post').innerText = fileInput;
  
    });
});
</script>