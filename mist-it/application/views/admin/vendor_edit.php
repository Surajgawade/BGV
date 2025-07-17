<div class="content-page">
<div class="content">
<div class="container-fluid">

     <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Vendor</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>vendors">Vendor</a></li>
                  <li class="breadcrumb-item active">Vendor Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>vendors"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
    </div>


      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
           <div class="card-body">
              <h5 class="box-title">Vendor Details</h5>
              <div style="float: right;">
                <button type="button" class="btn btn-secondary waves-effect btn-sm edit_btn_click" data-frm_name='frm_update_vendor' data-editUrl='<?= ($this->permission['access_address_vendor_database_edit']) ? encrypt($detailds['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
                <button type="button" class="btn btn-secondary waves-effect btn-sm delete" data-accessUrl="<?= ($this->permission['access_address_vendor_database_delete']) ? ADMIN_SITE_URL.'candidates/delete/'.encrypt($detailds['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
              </div>
            <div class="clearfix"></div>
            <?php echo form_open_multipart('#', array('name'=>'frm_update_vendor','id'=>'frm_update_vendor')); ?>
            <div class = "row">
              <div class="col-sm-4 form-group">
                <label >Vendor Name <span class="error"> *</span></label>
                <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$detailds['id']);?>">
                <input type="text" name="vendor_name" id="vendor_name" value="<?php echo set_value('vendor_name',$detailds['vendor_name']); ?>" class="form-control">
                <?php echo form_error('vendor_name'); ?>
              </div>
         
            </div>
            <div class = "row">
              <div class="col-sm-4  form-group">
                <label>Street Address <span class="error"> *</span></label>
                <input type="text" name="street_address" id="street_address " value="<?php echo set_value('street_address',$detailds['street_address']); ?>" class="form-control">
                <?php echo form_error('street_address'); ?>
              </div>
              <div class="col-sm-4  form-group">
                <label>City <span class="error"> *</span></label>
                <input type="text" name="city" id="city" value="<?php echo set_value('city',$detailds['city']); ?>" class="form-control">
                <?php echo form_error('city'); ?>
              </div>
              <div class="col-sm-4  form-group">
                <label>State <span class="error"> *</span></label>
                <?php
                  echo form_dropdown('state', $states, set_value('state',$detailds['state']), 'class="custom-select" id="state"');
                  echo form_error('state');
                ?>
              </div>
            </div>
            <div class = "row">
              <div class="col-sm-4 form-group">
                <label>PIN Code <span class="error"> *</span></label>
                <input type="text" name="pincode" id="pincode" maxlength="6" value="<?php echo set_value('pincode',$detailds['pincode']); ?>" class="form-control">
                <?php echo form_error('pincode'); ?>
              </div>
              <div class="col-sm-2 form-group">
                <label>Aggr Start Date</label>
                <input type="text" name="aggr_start_date" id="aggr_start_date" value="<?php echo set_value('aggr_start_date',convert_db_to_display_date($detailds['aggr_start_date'])); ?>" class="form-control myDatepicker">
                <?php echo form_error('aggr_start_date'); ?>
              </div>
              <div class="col-sm-2 form-group">
                <label>Aggr End Date</label>
                <input type="text" name="aggr_end_date" id="aggr_end_date" value="<?php echo set_value('aggr_end_date',convert_db_to_display_date($detailds['aggr_end_date'])); ?>" class="form-control myDatepickerFuture">
                <?php echo form_error('aggr_end_date'); ?>
              </div>
              <div class="col-sm-4 form-group">
                <label >Attachment (Please Upload Image)</label>
                <input type="file" name="attchament" accept=".png, .jpg, .jpeg, .pdf" id="attchament" value="<?php echo set_value('attchament');?>" class="form-control">
                <?php echo form_error('attchament'); ?>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-8 form-group">
                <label>Remark</label>
                <textarea name="vendor_remarks" rows="1" maxlength="250" id="vendor_remarks" class="form-control"><?php echo set_value('vendor_remarks',$detailds['vendor_remarks']); ?></textarea>
              </div>
              <div class="col-sm-4 form-group">
                <label>Client Status <span class="error"> *</span></label>
                <?php
                  echo form_dropdown("status", array('1' => 'Active','0' => 'Inactive'), set_value('status',$detailds['status']), 'class="custom-select" id="status"');
                  echo form_error('status');
                ?>
              </div>
            </div>
            <div class="row">
             <div class="col-sm-4 form-group">
              <label>Advocate Name</label>
                <input type="text" name="adv_name" id="adv_name" value="<?php echo set_value('adv_name',$detailds['adv_name']); ?>" class="form-control">
               <?php echo form_error('adv_name'); ?>
            </div>
          </div>
           <hr style="border-top: 2px solid #bb4c4c;">
               <div class="box-header with-border">
                <h3 class="box-title">Spoc Details</h3> 
              </div>
            <div>

            <div class="row">
              <div class="col-sm-4 form-group">
              <label>Spoc Name <span class="error"> *</span></label>
              <input type="text" name="sopc_name" id="sopc_name" value="<?php echo set_value('sopc_name',$detailds['sopc_name']); ?>" class="form-control">
              <?php echo form_error('sopc_name'); ?>
             </div>
             <div class="col-sm-4 form-group">
              <label>Primary Contact <span class="error"> *</span></label>
              <input type="text" name="primary_contact" id="primary_contact" minlength="8" maxlength="12" value="<?php echo set_value('primary_contact',$detailds['primary_contact']); ?>" class="form-control">
              <?php echo form_error('primary_contact'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>Email Id <span class="error"> *</span></label>
              <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id',$detailds['email_id']); ?>" class="form-control">
              <?php echo form_error('email_id'); ?>
            </div>
          </div> 

              <div class="clearfix"></div> 
              <hr style="border-top: 2px solid #bb4c4c;">
              <div class="row">
              <div class='col-sm-2 form-group'>
                <label>Components <span class="error"> *</span></label>
              </div>
              <div class='col-sm-2 form-group'>
                <label>TAT Day's</label>
              </div>
             
              <div class='col-sm-2 form-group'>
                <label>Auto Assign</label>
              </div>
              </div>
              <?php
                
                $vendors_components = explode(',', $detailds['vendors_components']);
                $vendors_components_tat = json_decode($detailds['vendors_components_tat'],TRUE);
                
                foreach ($components as $key => $value)
                {

                  $checked =  FALSE; 
                  $key_lowercase = strtolower($value['component_key']);
                  if(in_array($value['component_key'], $vendors_components))
                  {
                    $checked =  TRUE;
                  }
                  $data = array('name'    => 'components[]',
                                 'id'            => 'components',
                                'value'   => $value['component_key'],
                                'checked' => $checked    
                            );
                  echo "<div class = 'row'><div class='col-sm-2 form-group'><div class='checkbox'><label>";
                  echo form_checkbox($data).$value['component_name'];
                  echo "</label></div></div>";
                  echo "<div class='col-sm-2 form-group'>";
                  echo "<input type='number' min='0' name='vendors_components_tat[]'  class='form-control' value='".$vendors_components_tat[$key_lowercase]."''>";
                  echo "</div>";
              
                  echo "<div class='col-sm-4 form-group'>";
                  if($value['component_key'] == 'addrver')
                  {
                    
                    unset($states[0]);   
                    echo form_multiselect('state_address[]', $states, set_value('state_address',explode(',',$detailds['address_state'])), 'class="custom-select multiSelect state_address" id="state_address"');
                    echo form_error('state_address');
      
                  }
                  if($value['component_key'] == 'empver')
                  {

                    unset($states[0]);   
                    echo form_multiselect('state_employment[]', $employment_states, set_value('state_employment',explode(',',$detailds['employment_states'])), 'class="custom-select multiSelect state_employment" id="state_employment"');
                    echo form_error('state_employment');
      
                  }
                  if($value['component_key'] == 'courtver')
                  {
                    unset($court_clients[0]);  
                    echo form_multiselect('court_clients[]', $court_clients, set_value('court_clients',explode(',',$detailds['court_client'])), 'class="custom-select multiSelect" id="court_clients"');
                    echo form_error('court_clients');
      
                  }
                  if($value['component_key'] == 'globdbver')
                  {
                      
                    unset($global_clients[0]);    
                    echo form_multiselect('global_clients[]', $global_clients, set_value('global_clients',explode(',',$detailds['global_client'])), 'class="custom-select multiSelect" id="global_clients"');
                    echo form_error('global_clients');
      
                  }

                  if($value['component_key'] == 'cbrver')
                  {
                      
                    unset($credit_clients[0]);    
                    echo form_multiselect('credit_clients[]', $credit_clients, set_value('credit_clients',explode(',',$detailds['credit_client'])), 'class="custom-select multiSelect" id="credit_clients"');
                    echo form_error('credit_clients');
      
                  }
                  if($value['component_key'] == 'narcver')
                  {
                         
                   
                    unset($panel[0]);                        
                    echo form_multiselect('narcotis_panel[]', $panel, set_value('narcotis_panel',explode(',',$detailds['panel_code'])), 'class="select2" id="narcotis_panel"');
                    echo form_error('narcotis_panel');
      
                  }
                  if($value['component_key'] == 'eduver')
                  {
                      
                    $verification_status = array('' => 'Select Verification Status','1' => 'Verifier','2' => 'Stamp','3' => 'Spoc');
                    echo "<div class='col-sm-10 form-group'>";     
                    echo form_dropdown('education_verification_status', $verification_status, set_value('education_verification_status',$detailds['education_verification_status']), 'class="custom-select" id="education_verification_status"');
                    echo form_error('education_verification_status');
                    echo "</div>";
      
      
                  }

                  if($value['component_key'] == 'crimver')
                  {
                       
                    $pcc_mode = array('' => 'Select mode','verbal' => 'verbal');
                    echo form_dropdown('crimver_mov', $pcc_mode, set_value('crimver_mov',$detailds['pcc_mov']), 'class="custom-select" id="crimver_mov"');
                    echo form_error('crimver_mov');
      
                  }

                  echo "</div>";
                  if($value['component_key'] == 'addrver')
                  {
                   
                    echo '<div class="col-sm-4 form-group" >';
                    echo ' <label>City Name(seprated By Comma)</label><br><input type="text" id="address_city" name="address_city" value = "'.$detailds['address_city'].'"class="form-contol" placeholder="Enter City Name">';
                     echo '</div>';
                  }
                  if($value['component_key'] == 'empver')
                  {
                   
                    echo '<div class="col-sm-4 form-group" >';
                    echo ' <label>City Name(seprated By Comma)</label><br><input type="text" id="employment_city" name="employment_city" value = "'.$detailds['employment_city'].'"class="form-contol" placeholder="Enter City Name">';
                     echo '</div>';
                  }
                  if($value['component_key'] == 'courtver')
                  {
                      if($detailds['generate'] == 1){ $check_generate = "checked"; }else{ $check_generate = "";}  
                    echo '<div class="col-sm-4 form-group" >
                  
                     <label><input type="checkbox" id="generate" name="generate" class="form-check-input" style="height: 20px;width: 20px;" '.$check_generate.'  >&nbsp;&nbsp;Generate</label>
                  </div>';
                  }

                  if($value['component_key'] == 'crimver')
                  {
                     echo '<div class="col-sm-4 form-group" >';
                     echo ' <label>Email ID</label><br><input type="email" id="pcc_email_id" name="pcc_email_id" value = "'.$detailds['pcc_mov_email'].'" class="form-contol" placeholder="Enter Email ID">';
                     echo '</div>';
                  }
                  echo "</div>";
                  echo "<div class='clearfix'></div>";
                }
              ?>
              <div class="clearfix"></div>
              <hr style="border-top: 2px solid #bb4c4c;">

              <div class="box-header">
                <h3 class="box-title">Vendor Portal User</h3>
                <button type="button" style="float: right;" class="btn btn-info btn-sm" id="addVendorPortalMore"><h5>&nbsp; + &nbsp;</h5></button>
              </div>
              <div class="getVendorpotalDiv">
              <div class="row">
              <input type="hidden" name="client_login_id[]" id="client_login_id" value="<?php echo set_value('client_login_id');?>" class="form-control">
              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >First Name</label>
                <input type="text" name="client_first_name[]" id="client_first_name" value="<?php echo set_value('client_first_name');?>" class="form-control">
                <?php echo form_error('client_first_name'); ?>
              </div>

              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
              <label >Mobile No</label>
              <input type="text" name="client_mobileno[]" maxlength="11" id="client_mobileno" value="<?php echo set_value('client_mobileno');?>" class="form-control">
              <?php echo form_error('client_mobileno'); ?>
            </div>

              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >Email ID</label>
                <input type="text" name="client_email_id[]" id="client_email_id" value="<?php echo set_value('client_email_id');?>" class="form-control">
                <?php echo form_error('client_email_id'); ?>
              </div>
              <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                <label >Password</label>
                <input type="password" name="client_password[]" id="client_password" value="<?php echo set_value('client_password');?>" class="form-control">
                <?php echo form_error('client_password'); ?>
              </div>
              </div>
              </div>
              <div id='append_vendor_acc'></div>
              <div class="clearfix"></div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
                  <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
           <div class="card-body">

          <div class="nav-tabs-custom">
            <ul class="nav nav-pills nav-justified">
              <li class="nav-item waves-effect waves-light active"><a  class = 'nav-link active' href="#activity_log_tabs" id="view_activity_log_tabs" data-toggle="tab">Vendor User List</a></li>
            </ul>
            <br>
            <div class="tab-content">
              <div class="active tab-pane" id="activity_log_tabs">
                <table id="datatable_logs" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>First Name</th>
                      <th>Email ID</th>
                      <th>Mobile No</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php
                   $count = 0;
                   foreach ($vendor_account as $key => $value) {
                     echo "<tr class='tbl_row_clicked'>";
                     echo "<td>".++$count."</td>";
                     echo "<td>".$value['first_name']."</td>";
                     echo "<td>".$value['email_id']."</td>";
                     echo "<td>".$value['mobile_no']."</td>";
                     echo '<td><button id="showVedorEdit" data-url ="' . ADMIN_SITE_URL . 'vendors/vendor_edit_idwise/' . $value['id']. '" data-toggle="modal" data-target="#showVendorEditModel"  class="btn-info"> View </button></td>';
                    
                     echo "</tr>";
                   }
                   ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        
        </div>
      </div>
  </div> 
</div>

<div id="showVendorEditModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'edit_vendor_login_details','id'=>'edit_vendor_login_details')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Vendor Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body">
         <div id="append_vendor_model"></div>
      </div>

      <div class="modal-footer">
     
        <button type="submit" id="sbvendoredit" name="sbvendoredit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>

<script>
$(document).ready(function(){
  $('input,textarea,select').prop('disabled', true);

  $('#frm_update_vendor').validate({
    rules : { 
      update_id : {
        required : true
      },
      vendor_name : {
        required : true,
        minlength: 2
      },
    //  "vendor_managers[]" : {
    //    required : true
    //  },
      sopc_name : {
        lettersonly : true
      },
      primary_contact : {
        digits : true,
        minlength : 8,
        maxlength : 12
      },
      "client_email_id[]" : {
        email: true
      },
      email_id : {
        multiemails : true
      },
      alternate_contact : {
        digits : true,
        minlength : 8,
        maxlength : 12
      },
      city : {
        lettersonly : true
      },
      pincode : {
        digits : true,
        minlength : 6,
        maxlength : 6
      },
      attchament : {
        extension : 'jpeg|jpg|png|pdf'
      },
       'components[]' : {
        required : true
      }
    },
    messages: {
      vendor_name : {
        required : "Enter Username",
        minlength : "User name content min 5 charecter"
      },
      'components[]' : {
        required : 'Select atleast one component'
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'vendors/update_vendor_details'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#save').attr('disabled',true);
        },
        complete:function(){
          //$('#save').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            window.location = jdata.redirect;
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });


  $('#edit_vendor_login_details').validate({
    rules : {
      update_id : {
        required : true
      }, 
      first_name : {
        required : true
      },
      email_id : {
        required : true,
        email: true
      },
 
      mobile_no : {
        digits : true,
        minlength : 8,
        maxlength : 12
      }
    },
    messages: {
      update_id :  {
        required : "Required ID",
      },
      first_name : {
        required : "Enter First Name",
      },
      email_id : {
        required : "Enter Email ID",
        email: "Enter Valid Email ID"
      },
      mobile_no :  {
        required : "Enter mobile no",
        minlength : "Mobile No min 10 charecter"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'vendors/update_vendor_details_idwise'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#sbvendoredit').attr('disabled',true);
        },
        complete:function(){
          $('#sbvendoredit').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            $('#showVendorEditModel').modal('hide');
            $('#edit_vendor_login_details')[0].reset();
            show_alert(message,'success');
            window.location = jdata.redirect;
            return;
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });
  

  $(document).on('click', '#addSpocMore', function(){
  var as = '<div class="clearfix"></div>'+$('.getSpocDiv').html();
  $('#append_spoc').append(as)
});

  $(document).on('click', '#addVendorPortalMore', function(){
  var as = '<div class="clearfix"></div>'+$('.getVendorpotalDiv').html();
  $('#append_vendor_acc').append(as)
});

  var vendor_managers = '<?= $detailds['vendor_managers'] ?>';
  vendor_managers =  vendor_managers.split(',').map(Number)
  $('#vendor_managers').multiselect('select',vendor_managers);

  var emp_log_tbl =  $('#datatable_logs').DataTable( { "ordering": true,bSortable: true,bRetrieve: true,"iDisplayLength": 25, });

});

$(document).on('click','#showVedorEdit',function() {
  
    var url = $(this).data('url');

    $('#append_vendor_model').load(url,function(){
      $('#showVendorEditModel').modal('show');
      $('#showVendorEditModel').addClass("show");
      $('#showVendorEditModel').css({background: "#0000004d"}); 
    });
});
</script>
<script type="text/javascript">
  $(document).on('click', '.delete', function(){  
           var vendor_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'vendors/delete/';?>",  
                     method:"POST",  
                     data:{vendor_id:vendor_id},  
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
  
</script>