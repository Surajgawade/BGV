 
    <div class="row">
      <div class="col-sm-12 col-md-12">
        <div class="box box-primary">
          <div class="box-header">
            <div class="text-white div-header">
                  Case Details
            </div>
            <br>
           <?php 
          
           // if($comp['address_id'] == '' && $comp['employment_id'] == ''  && $comp['education_id'] == ''  && $comp['reference_id'] == ''  && $comp['court_id'] == ''  && $comp['global_id'] == ''  && $comp['pcc_id'] == ''  && $comp['identity_id'] == ''  && $comp['credit_id'] == ''  && $comp['drugs_id'] == '') { 
            ?>
            <div style="float: right;">
              <button class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name='frm_update_candidates' data-editUrl='<?=  encrypt($candidate_details['id']) ?>'><i class="fa fa-edit"></i> Edit</button>
            </div>
            <?php 
          //  }
            ?>
        </div>
      <div class="clearfix"></div>
  <?php echo form_open('#', array('name'=>'frm_update_candidates','id'=>'frm_update_candidates')); ?>

   <div class="row">
   
    <div class="col-md-3 col-sm-12 col-xs-3 form-group">

      <label >Select Client <span class="error"> *</span></label>
      <?php
        echo form_dropdown('clientid1', $this->session->userdata('client')['client_name'], set_value('clientid1',$candidate_details['clientid']), 'class="custom-select" id="clientid1" ');
        echo form_error('clientid');
      ?>
    </div>

   
    <div class="col-md-1 col-sm-12 col-xs-1 form-group">
      <label>&nbsp;&nbsp;</label>
        <button class="btn btn-default" data-url ="<?= CLIENT_SITE_URL.'candidates/entity_package_view/' ?>"  data-toggle="modal" id="show_entity_package_modal" >Check</button>
      </div>
      <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',encrypt($candidate_details['id'])); ?>">
       <input type="hidden" name="clientid" id="clientid" value="<?php echo $candidate_details['clientid'];?>">
     <input type="hidden" name="cp_update_id" id="cp_update_id" value="<?php echo set_value('cp_update_id',$candidate_details['id']); ?>">

      <input type="hidden" name="selected_client" id="selected_client" value="<?php echo $candidate_details['clientid'];?>">
    <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $candidate_details['package'];?>">
    <input type="hidden" name="selected_entity" id="selected_entity" value="<?php echo $candidate_details['entity'];?>">
    <input type="hidden" name="selected_package" id="selected_package" value="<?php echo $candidate_details['package'];?>">
    <input type="hidden" name="cands_info_id" id="cands_info_id" value="<?php echo $candidate_details['cands_info_id'];?>">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Select Entity<span class="error"> *</span></label>
      <?php

        echo form_dropdown('entity', $client_components['entity'], set_value('entity'), 'class="custom-select" id="entity"');
        echo form_error('entity');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label >Select Spoc/Package<span class="error"> *</span></label>
      <?php

        echo form_dropdown('package', $client_components['package'], set_value('package'), 'class="custom-select" id="package"');
        echo form_error('package');
      ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case Received Date<span class="error"> *</span></label>
      <input type="text" name="caserecddate" id="caserecddate" value="<?php echo set_value('caserecddate',convert_db_to_display_date($candidate_details['caserecddate'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
        <?php echo form_error('caserecddate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client/Employee ID <span class="error"> *</span></label>
      <input type="text" name="ClientRefNumber"  id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$candidate_details['ClientRefNumber']); ?>" class="form-control">
      <?php echo form_error('ClientRefNumber'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Candidate Name <span class="error"> *</span></label>
      <input type="text" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$candidate_details['CandidateName']); ?>" class="form-control">
      <?php echo form_error('CandidateName'); ?>
    </div>
  </div>
   <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Email ID</label>
      <input type="text" name="cands_email_id" maxlength="50" id="cands_email_id" value="<?php echo set_value('cands_email_id',$candidate_details['cands_email_id']); ?>" class="form-control">
      <?php echo form_error('cands_email_id'); ?>
    </div>
     
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Primary Contact<span class="error"> *</span></label>
      <input type="text" name="CandidatesContactNumber" maxlength="12" id="CandidatesContactNumber" value="<?php echo set_value('CandidatesContactNumber',$candidate_details['CandidatesContactNumber']); ?>" class="form-control">
      <div id = "candidatecontact" class="error"></div>
      <?php echo form_error('CandidatesContactNumber'); ?>
    </div>
       
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Contact No (2)</label>
      <input type="text" name="ContactNo1" id="ContactNo1" maxlength="12" value="<?php echo set_value('ContactNo1',$candidate_details['ContactNo1']); ?>" class="form-control">
      <div id = "candidateContactNo1" class="error"></div>
      <?php echo form_error('ContactNo1'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Contact No (3)</label>
      <input type="text" name="ContactNo2" id="ContactNo2" maxlength="12" value="<?php echo set_value('ContactNo2',$candidate_details['ContactNo2']); ?>" class="form-control">
      <div id = "candidateContactNo2" class="error"></div>
      <?php echo form_error('ContactNo2'); ?>
    </div>

    <?php 
    if(isset($check_component[0]['candidate_add_component']))
    {
    if($check_component[0]['candidate_add_component'] == 1)
    { ?>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group" id = "comp_add">
      <label>&nbsp;</label>
        <label><span class="error"><h5 style="margin-left:20px;"><input type="checkbox" id="add_candidate_mail" name="add_candidate_mail" class="form-check-input" <?php if($candidate_details['check_mail_send'] == 1){ echo  "checked"; } ?> style="height: 20px;width: 20px;" >&nbsp;&nbsp;Candidate to update</h5></span></label>
    </div>  
    <?php  
    } 
    }
    ?>
  </div>  


    <div class="clearfix"></div>
  
    <br>
    <div class="text-white div-header">
      Joining Details
    </div>
    <br>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Date of Joining</label>
      <input type="text" name="DateofJoining" id="DateofJoining" value="<?php echo set_value('DateofJoining',convert_db_to_display_date($candidate_details['DateofJoining'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofJoining'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Designation</label>
      <input type="text" name="DesignationJoinedas" id="DesignationJoinedas" value="<?php echo set_value('DesignationJoinedas',$candidate_details['DesignationJoinedas']); ?>" class="form-control">
      <?php echo form_error('DesignationJoinedas'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Branch Location</label>
      <input type="text" name="Location" id="Location" value="<?php echo set_value('Location',$candidate_details['Location']); ?>" class="form-control">
      <?php echo form_error('Location'); ?>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Department</label>
      <input type="text" name="Department" id="Department" value="<?php echo set_value('Department',$candidate_details['Department']); ?>" class="form-control">
      <?php echo form_error('Department'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Employee Code</label>
      <input type="text" name="EmployeeCode" id="EmployeeCode" value="<?php echo set_value('EmployeeCode',$candidate_details['EmployeeCode']); ?>" class="form-control">
      <?php echo form_error('EmployeeCode'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Work Experience</label>
       <?php
         $work_experiance = array(''=> 'Select Work Experience','fresher'=>'Fresher','experienced'=>'Experienced');
            
         echo form_dropdown('branch_name', $work_experiance, set_value('branch_name',$candidate_details['branch_name']), 'class="custom-select" id="branch_name"');
         echo form_error('branch_name');

      ?>
     
      <?php echo form_error('branch_name'); ?>
    </div>
    </div>

   <!-- <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Region</label>
      <input type="text" name="region" id="region" value="<?php echo set_value('region',$candidate_details['region']); ?>" class="form-control">
      <?php echo form_error('region'); ?>
    </div>-->
    <div class="row">

    <div class="col-sm-4 form-group">
      <label>Grade</label>
        <input type="text" name="grade" id="grade" value="<?php echo set_value('grade',$candidate_details['grade']); ?>" class="form-control">
      <?php echo form_error('grade'); ?>
    </div>  
    <div class="col-md-8 col-sm-12 col-xs-8 form-group">
      <label >Remarks</label>
      <textarea name="remarks" rows="1" id="remarks" rows="1" class="form-control"><?php echo set_value('remarks',$candidate_details['remarks']); ?></textarea>
    </div>
    </div>
  

    <br>
    <div class="text-white div-header">
      Candidate Details
    </div>
    <br>
    <div class="row">

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Gender<span class="error"> *</span></label>
     <?php
        echo form_dropdown('gender', GENDER, set_value('gender',$candidate_details['gender']), 'class="custom-select" id="gender"');
        echo form_error('gender');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Date of Birth <span class="error"> *</span></label>
      <input type="text" name="DateofBirth" id="DateofBirth" value="<?php echo set_value('DateofBirth',convert_db_to_display_date($candidate_details['DateofBirth'])); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('DateofBirth'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Father's Name <span class="error"> *</span></label>
      <input type="text" name="NameofCandidateFather" id="NameofCandidateFather" value="<?php echo set_value('NameofCandidateFather',$candidate_details['NameofCandidateFather']); ?>" class="form-control">
      <?php echo form_error('NameofCandidateFather'); ?>
    </div>
    </div>
    <div class="row">
   
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Mother's Name</label>
      <input type="text" name="MothersName" id="MothersName" value="<?php echo set_value('MothersName',$candidate_details['MothersName']); ?>" class="form-control">
      <?php echo form_error('MothersName'); ?>
    </div>
     
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PAN No.</label>
      <input type="text" name="PANNumber" id="PANNumber" value="<?php echo set_value('PANNumber',$candidate_details['PANNumber']); ?>" class="form-control">
      <?php echo form_error('PANNumber'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>AADHAR No.</label>
      <input type="text" name="AadharNumber" id="AadharNumber" maxlength="12" value="<?php echo set_value('AadharNumber',$candidate_details['AadharNumber']); ?>" class="form-control">
      <?php echo form_error('AadharNumber'); ?>
    </div>  

    </div>

    
    <div class="row">
    
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Passport No.</label>
      <input type="text" name="PassportNumber" id="PassportNumber" value="<?php echo set_value('PassportNumber',$candidate_details['PassportNumber']); ?>" class="form-control">
      <?php echo form_error('PassportNumber'); ?>
    </div>

     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Street Address</label>
      <textarea name="prasent_address" rows="1" id="prasent_address" class="form-control"><?php echo set_value('prasent_address',$candidate_details['prasent_address']); ?></textarea>
    </div> 
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>City</label>
      <input type="text" name="cands_city" id="cands_city" value="<?php echo set_value('cands_city',$candidate_details['cands_city']); ?>" class="form-control">
      <?php echo form_error('cands_city'); ?>
    </div>

    </div>
    <div class="row">
  
   
        

    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>PIN Code</label>
      <input type="text" name="cands_pincode" id="cands_pincode" maxlength="6" value="<?php echo set_value('cands_pincode',$candidate_details['cands_pincode']); ?>" class="form-control">
      <?php echo form_error('cands_pincode'); ?>
    </div>
     <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>State</label>
      <?php
        echo form_dropdown('cands_state', $states, set_value('cands_state',$candidate_details['cands_state']), 'class="custom-select" id="cands_state"');
        echo form_error('cands_state');
      ?>
    </div>
    </div>
   
  
    <br>
    <div class="text-white div-header">
      Attachments & other
    </div>
    <br>
    <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment <span class="error">(Not more than 20 MB)</span></label>
      <input type="file" name="attchments[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments" value="<?php echo set_value('attchments'); ?>" class="form-control">
      <?php echo form_error('attchments'); ?>
    </div>
    
    </div>

<?php  if(!empty($attachments)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
       <th style='border: 1px solid black;padding: 8px;'>Size</th>
       <th style='border: 1px solid black;padding: 8px;'>Action</th>
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments as $key => $value) {
       
       $url  = SITE_URL.CANDIDATES.$candidate_details['clientid'].'/';

       if($value['type'] == 0)
       {
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         <td style='border: 1px solid black;padding: 8px;'></td>
         <td style='border: 1px solid black;padding: 8px;'><?php echo "<a href='javascript:void(0)' class='remove_file' data-id=".$value['id']."><i class='fa fa-trash'></i></a>"; ?></td>
         </tr>

      <?php 
       }

      } ?>
    </tr>
  </table>
  <br>
    <div class="box-body">
      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
        <input type="submit" name="btn_update" id='btn_update' value="Submit" class="btn btn-success">
        <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
    </div>
    </div>
    <div class="clearfix"></div> 
   <?php echo form_close(); ?>
</div>
</div>
</div>


<div id="show_entity_package_modal1" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
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

<script type="text/javascript">

$(document).ready(function(){


  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,  
  });

$('#clientid1').attr("style", "pointer-events: none;");

  $("#frm_update_candidates :input").prop("disabled", true);

 
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

 $('#frm_update_candidates').validate({ 
    rules: {
      clientid : {
        required : true
      },
      caserecddate : {
        required : true,
        validDateFormat : true
      },
      CandidateName : {
        required : true,
        lettersonly : true
      },
      ClientRefNumber : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        noSpace : true
      },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        greaterThan:  function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return 0;
          }
        }
      },
     cands_email_id :
       {
         email : true,
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      }, 

      cands_city : {
        lettersonly : true
      },
      cands_pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      DateofBirth : {
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
      },
      NameofCandidateFather : {
          required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return true;
          }
        },
        lettersonly : true
      },  
      MothersName : {
        lettersonly : true
      },
      CandidatesContactNumber : {
        required : true,
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo1 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      ContactNo2 : {
        digits : true,
        minlength : 8,
        maxlength : 12,
      },
      password : {
        required : true,
        minlength : 7,
      },
      AadharNumber : {
        digits: true,
        minlength : 12,
        maxlength : 12,
      }
     
    },
    messages: {
        clientid : {
          required : "Enter Client Name"
        },
        caserecddate : {
          required : "Select Case Received Date"
        },
        CandidateName : {
          required : "Enter Candidate Name"
        },
        cands_email_id :
        {
         email : "Enter Valid Email ID",
         required : function(){
          if($('input[name=add_candidate_mail]:checked').length == 0)
          {
            return false;
          }
          else
          {
             return "Please Enter Email ID";
          }
         }
        }, 
        ClientRefNumber : {
          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Client/Employee ID";
            }
          }
        },
        DateofBirth : {
          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Date of Birth";
            }
          }
        },
       NameofCandidateFather : {

          required : function(){
            if($('input[name=add_candidate_mail]:checked').length > 0)
            {
              return false;
            }
            else
            {
               return "Enter Name of Father";
            }
          }
       
        },
      password : {
        required : "Enter Password",
        minlength : "Password content min 7 charecter long",
       },
      gender : {
        required : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        },
        greaterThan : function(){
          if($('input[name=add_candidate_mail]:checked').length > 0)
          {
            return false;
          }
          else
          {
             return "Select Gender";
          }
        }
      },
      CandidatesContactNumber : {
         required : "Please Enter Primary Contact"
      }
      },
      submitHandler: function(form) 
      { 
          $.ajax({
            url : '<?php echo CLIENT_SITE_URL.'candidates/candidates_update'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update').attr('disabled','disabled');
              jQuery('.body_loading').show();
            },
            complete:function(){
          
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

    jQuery.validator.addMethod("noSpace",function(value,element){

      return value.indexOf(" ") < 0 && value != "";
  },"Space are not allowed");    

});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete_candidate', function(){  
           var candidates_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'candidates/delete/';?>",  
                     method:"POST",  
                     data:{candidates_id:candidates_id},  
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




$(document).on('change', '#package', function(){
  var clientid = $('#clientid').val();
  var entity = $('#entity').val();
  var package = $(this).val();

  if(clientid != 0 && entity != 0 && package != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo CLIENT_SITE_URL.'candidates/check_candidate_add_component'; ?>',
          data:'clientid='+clientid+'&entity='+entity+'&package='+package,
          success:function(response)
          {
            var data =  response.message;
            if(data.candidate_add_component == 1)
            {
               $('#comp_add').css('display','block');
            }
            if(data.candidate_add_component == 2)
            {
               $('#comp_add').css('display','none');
            }
           
          }
      });
  }
});  

$('#show_entity_package_modal').on('click',function(){
 
    var url = $(this).data("url");
    var clientid = $('#clientid').val();
    $('.append_entity_package').load(url+""+clientid,function(){});
    $('#show_entity_package_modal1').modal('show');
    $('#show_entity_package_modal1').addClass("show");
    $('#show_entity_package_modal1').css({background: "#0000004d"});
     return false;
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
                $('#sbeditcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidatecontact').html('Already Existing '+jdata.entity+' - '+jdata.package+' - '+jdata.ref_no);
                $('#sbeditcandidate').attr("disabled", true);
                
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
                $('#sbeditcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidateContactNo1').html('Already Existing  '+jdata.entity+' - '+jdata.package+'- '+jdata.ref_no);
                $('#sbeditcandidate').attr("disabled", true);
                
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
                $('#sbeditcandidate').removeAttr("disabled");
              }
              else
              {
                
                $('#candidateContactNo2').html('Already Existing '+jdata.entity+' - '+jdata.package+' - '+jdata.ref_no);
                $('#sbeditcandidate').attr("disabled", true);
                
              }
            }
        });s
     }
  });


    $('.remove_file').on('click',function(){
    
      var confirmr = confirm("Are you sure,you want to delete this file?");
      if (confirmr == true) 
      {
          var remove_id = $(this).data("id");

          $.ajax({
              url: "<?php  echo CLIENT_SITE_URL.'candidates/remove_uploaded_file/' ?>"+remove_id,
              type: 'GET',
              beforeSend:function(){
                show_alert('deleting...','info');
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
    
    });
</script>