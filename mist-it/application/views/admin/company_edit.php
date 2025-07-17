<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Edit Company</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>company_database">Company Details</a></li>
                            <li class="breadcrumb-item active">Company Edit</li>
                        </ol>
                        
                        <div class="state-information d-none d-sm-block">
                            <ol class="breadcrumb">
                                <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>company_database"><i class="fa fa-arrow-left"></i> Back</button></li>  
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
         
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body"> 
                            <div class="text-white div-header">
                            Company Information
                        </div>
                        <br>
                        <div style="float: right;">
                          <button class="btn btn-secondary waves-effect  edit_btn_click" data-frm_name='update_company' data-editUrl="<?= ($this->permission['access_employment_suspicious_company_edit']) ? $company_details['id'] : ''?>"><i class="fa fa-edit"></i> Edit</button>
                      </div>
                      <div class="clearfix"></div>
                        <?php echo form_open('#', array('name'=>'update_company','id'=>'update_company')); ?>
                        <input type="hidden" name="id" id="id" value="<?php echo set_value('id',$company_details['id']);?>" class="form-control ">
                       
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label >Company Name<span class="error"> *</span></label>
                                <input type="text" name="coname" id="coname" value="<?php echo set_value('coname',$company_details['coname']);?>" class="form-control cls_disabled">
                                <?php echo form_error('coname'); ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label >Address</label>
                                <input type="text" name="address" id="address" value="<?php echo set_value('address',$company_details['address']);?>" class="form-control cls_disabled">
                                <?php echo form_error('address'); ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>City</label>
                                <input type="text" name="city" id="city" value="<?php echo set_value('city',$company_details['city']);?>" class="form-control cls_disabled">
                                <?php echo form_error('city'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 form-group">
                                <label>Select State</label>
                                <?php
                                    echo form_dropdown('state', $states, set_value('state',$company_details['state']), 'class="select2 cls_disabled" id="state" ');
                                    echo form_error('state');
                                ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Pincode</label>
                                <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$company_details['pincode']);?>" class="form-control cls_disabled">
                                <?php echo form_error('pincode'); ?>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>To Email ID</label>
                                <input type="text" name="co_email_id" id="co_email_id" value="<?php echo set_value('co_email_id',$company_details['co_email_id']);?>" class="form-control cls_disabled">
                                <?php echo form_error('co_email_id'); ?>
                            </div>

                            <div class="col-sm-6 form-group">
                              <label>CC Email ID</label>
                              <input type="text" name="cc_email_id" id="cc_email_id" value="<?php echo set_value('cc_email_id',$company_details['cc_email_id']);?>" class="form-control cls_disabled">
                              <?php echo form_error('cc_email_id'); ?>
                            </div>
                        </div>

 
    
                            <div class="text-white div-header">
                                Company requirements
                            </div> 
                            <br>
                           <?php

                           if($company_details['previous_emp_code'] == 1)
                           {
                               $css_previous_emp_code = 'style="color:red"';
                           } 
                           else
                           {
                             $css_previous_emp_code = "";
                           }


                           if($company_details['branch_location'] == 1)
                           {
                               $css_branch_location = 'style="color:red"';
                           } 
                           else
                           {
                               $css_branch_location = "";
                           }


                           if($company_details['experience_letter'] == 1)
                           {
                               $css_experience_letter = 'style="color:red"';
                           } 
                           else
                           {
                               $css_experience_letter = "";
                           }

                           if($company_details['loa'] == 1)
                           {
                               $css_loa = 'style="color:red"';
                           } 
                           else
                           {
                               $css_loa = "";
                           }

                           if($company_details['follow_up'] == 1)
                           {
                               $css_follow_up = 'style="color:red"';
                           } 
                           else
                           {
                               $css_follow_up = "";
                           }

                           if($company_details['auto_initiate'] == 1)
                           {
                               $css_auto_initiate = 'style="color:red"';
                           } 
                           else
                           {
                               $css_auto_initiate = "";
                           }

                           if($company_details['auto_initiate'] == 1)
                           {
                               $css_auto_initiate = 'style="color:red"';
                           } 
                           else
                           {
                               $css_auto_initiate = "";
                           }

                            if($company_details['client_disclosure'] == 1)
                           {
                               $css_client_disclosure = 'style="color:red"';
                           } 
                           else
                           {
                               $css_client_disclosure = "";
                           }

                           ?>
                            <input type="checkbox" name="previous_emp_code"  id="previous_emp_code" class="cls_disabled" <?php if(isset($company_details['previous_emp_code'])) {  if($company_details['previous_emp_code'] == 1 ) echo 'checked' ;} ?>>
                            <label <?php echo $css_previous_emp_code ?>>Previous Emp Code</label><br>
                            <input type="checkbox" name="branch_location" id="branch_location" class="cls_disabled"  <?php if(isset($company_details['branch_location'])) {  if($company_details['branch_location'] == 1 ) echo 'checked' ;} ?>>
                            <label <?php echo $css_branch_location ?>> Branch Location</label><br>
                            <input type="checkbox" id="experience_letter" name="experience_letter" class="cls_disabled" <?php if(isset($company_details['experience_letter'])) {  if($company_details['experience_letter'] == 1 ) echo 'checked' ;} ?>>
                            <label <?php echo $css_experience_letter ?>> Relieving/Experience letter</label><br>
                            <input type="checkbox" id="loa" name="loa" class="cls_disabled" <?php if(isset($company_details['loa'])) {  if($company_details['loa'] == 1 ) echo 'checked' ;} ?>>
                            <label <?php echo $css_loa ?>> LOA</label><br>
                            <input type="checkbox" id="auto_initiate" name="auto_initiate" class="cls_disabled" <?php if(isset($company_details['auto_initiate'])) {  if($company_details['auto_initiate'] == 1 ) echo 'checked' ;}else{ echo 'checked' ;} ?>>
                            <label  <?php echo $css_auto_initiate ?>> Auto Initiate</label><br>
                            <input type="checkbox" id="follow_up" name="follow_up" class="cls_disabled" <?php if(isset($company_details['follow_up'])) {  if($company_details['follow_up'] == 1 ) echo 'checked';}else {echo 'checked' ;} ?>>
                            <label <?php echo $css_follow_up ?>>Follow up</label><br>
                            <input type="checkbox" id="client_disclosure" name="client_disclosure"  class="cls_disabled" <?php if(isset($company_details['client_disclosure'])) {  if($company_details['client_disclosure'] == 1 ) echo 'checked';} ?>>
                            <label  <?php echo $css_client_disclosure ?>>Client Disclosure</label><br>
                        
                             
                            <br>
                                            
                            <div class="clearfix"></div>
                            <br>
                            <div class="col-sm-6">
                                <button type="submit" id="company_update" name="company_update" class="btn btn-success cls_disabled">Update</button>
                            </div>
                             <br>
                            <?php echo form_close(); ?>
       
                           <div class="text-white div-header">
                               HR Information 
                           </div>
                           <br>

                           <table id="tbl_vendor_insuff" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr >
                            <th>SrId</th>
                            <th>Verifier Name</th>
                            <th>Verifier Designtion</th>       
                            <th>Verifier Contact No</th>
                            <th>Verifier Email ID</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $i = 1;
                              $html_view = '';
                            foreach ($verifiers_details as $key => $value) {
                                
                            
                                $html_view .= "<td>".$i."</td>";
                            
                                $html_view .= "<td>".$value['verifiers_name']."</td>";
                                $html_view .= "<td>".$value['verifiers_designation']."</td>";
                                $html_view .= "<td>".$value['verifiers_contact_no']."</td>";
                                $html_view .= "<td>".$value['verifiers_email_id']."</td>";
                                $html_view .= '</tr>';  

                                $i++;  
                            }
                             echo  $html_view;
                           ?>

                            </tbody>
                            </table>

                             
                            <div class="text-white div-header">
                               Backup Hr Information 
                           </div>
                           <br>

                           <table id="tbl_back_hr_info" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr >
                            <th>SrId</th>
                            <th>Verifier Name</th>
                            <th>Verifier Designtion</th>       
                            <th>Verifier Contact No</th>
                            <th>Verifier Email ID</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $i = 1;
                              $html_view = '';
                            foreach ($verifiers_details_bk as $key => $value) {
                                
                            
                                $html_view .= "<td>".$i."</td>";
                            
                                $html_view .= "<td>".$value['verifiers_name']."</td>";
                                $html_view .= "<td>".$value['verifiers_designation']."</td>";
                                $html_view .= "<td>".$value['verifiers_contact_no']."</td>";
                                $html_view .= "<td>".$value['verifiers_email_id']."</td>";
                                $html_view .= '</tr>';  

                                $i++;  
                            }
                             echo  $html_view;
                           ?>

                            </tbody>
                            </table>

                        </div>    
                        </div>
                       
                    </div>
                </div>
            </div>

<script>
$(document).ready(function(){
  $('.cls_disabled').prop('disabled', true);

  $('#update_company').validate({ 
      rules: {
        coname : {
          required : true
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
          url: '<?php echo ADMIN_SITE_URL.'company_database/update_company'; ?>',
          data: new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#company_update').attr('disabled','disabled');
          },
          complete:function(){
            //$('#company_add').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.redirect){
              show_alert(message,'success');
             
              window.location = jdata.redirect;
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });      
      }
  });
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