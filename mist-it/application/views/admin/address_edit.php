<script type="text/javascript" src="<?php echo SITE_URL; ?>assets/js/html2canvase.js"></script>
<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Address</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>address">Address</a></li>
                  <li class="breadcrumb-item active">Address Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>address"><i class="fa fa-arrow-left"></i> Back</button></li>  
               </ol>
              </div>
          </div>
        </div>
    </div>

    <div class="row">
     <div class="col-12">
      <div class="card m-b-20">
       <div class="card-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-pills nav-justified" role="tablist">
            <li class="nav-item waves-effect waves-light active"><a class = 'nav-link active' href="#address_tab" data-toggle="tab">Address Details</a></li>
            <li class="nav-item waves-effect waves-light view_component_tab" data-url="<?php echo ADMIN_SITE_URL.'candidates/ajax_view_component/'?>" data-can_id="<?php echo encrypt($addressver_details['candsid']);?>" data-clients_id="<?php echo $addressver_details['clientid'];?>" data-tab_name="candidate_tab" ><a href="#candidate_tab" class = 'nav-link' data-toggle="tab">Candidate Details</a></li>
          </ul>
          <div class="tab-content">
            <div class="active tab-pane" id="address_tab">
              <?php $this->load->view('admin/address_edit_form'); ?> 
            </div>
            <div class="tab-pane" id="candidate_tab">
              <div class=""></div>
            </div>
          </div>
        </div>
      </div>
    </div>  
    
    <div class="card m-b-20">
      <div class="card-body">    
        <div class="nav-tabs-custom">
          <ul class="nav nav-pills nav-justified">
            <li class="nav-item waves-effect waves-light active"><a class = 'nav-link active' href="#activity_log_tabs" id="view_activity_log_tabs" data-toggle="tab">Activity</a></li>
            <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#clk_insuff" id='clk_insuff_log' data-emp_id="<?php echo $addressver_details['id']; ?>" data-toggle="tab">Insufficiency</a></li>
      
            <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#result_log_tabs" id="view_result_log_tabs" data-emp_id="<?php echo $addressver_details['id']; ?>" data-cand_id="<?php echo $addressver_details['candsid']; ?>" data-toggle="tab">Result Log</a></li>
            <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#tab_vendor_log" id="view_tab_vendor_log" data-toggle="tab">Vendor Log</a></li>
            <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#tab_client_charges" id="view_tab_client_charges" data-toggle="tab">Client Charges</a></li>
          </ul>
          <br>
          <div class="tab-content">

            <div class="active tab-pane" id="activity_log_tabs">
              <div id="tbl_activity_log" ></div>
            </div>
            <div class="tab-pane" id="clk_insuff">
              <div style="float: right;"><button class="btn btn-info btn-sm InsuffRaiseModel" data-editUrl='<?=($this->permission['access_address_list_insuff_raise']) ? $insuff_raise_id : ''; ?>' <?php echo $check_insuff_raise;?>>Raise Insuff</button><br>
              </div>
              <div id='append_insuff_view' style="text-align: center"></div>
            </div>
        
            <div class="tab-pane" id="result_log_tabs">
              <?php
               if($reinitiated == "1")
              {

              $access_reverification = ($this->permission['access_address_list_reverification'] == '1') ? 'ReInitiateModel'  : '';
              ?>
            
               <div style="float: right;"><button class="btn btn-info btn-sm <?php echo $access_reverification;?>" id="re-initiated">Reverify</button><br>
              </div>
               <?php
               }
              ?>
              <table id="tbl_address_result" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"></table>

                 
            </div>
            <div class="tab-pane" id="tab_vendor_log">
            <?php
               if($physical_button == "1")
              {?>

              <div style="float: right;"><button class="btn btn-info btn-sm" data-toggle="modal" data-target ="#VendorChangeModal">Physical Address</button><br>
              </div>
              
               <?php
               }
               else
               {
                
                echo form_open_multipart('#', array('name'=>'frm_digital_address','id'=>'frm_digital_address'));

               ?>
                 <input type="hidden" name="digital_verification_case_id" id = "digital_verification_case_id" value="<?php echo set_value('digital_verification_case_id',$addressver_details['id']); ?>">

               <div style="float: right;"><button type="submit" class="btn btn-info btn-sm" id="btn_convert_degital_address" >Digital Address</button><br>
                </div>
            
               <?php 
                echo form_close(); 

               }
               ?>
             <table id="tbl_vendor_log"  class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"></table>
               
            </div>
          </div>
        </div>
      </div>
    </div>  
   </div>
</div>
</div>
</div>
</div>

<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_view','id'=>'add_verificarion_result_view')); ?>
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Result Log Details</h4>
      </div>
      <span class="errorTxt"></span>
        <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$addressver_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$addressver_details['package_id']); ?>">
      <input type="hidden" name="tbl_addrver" value="<?php echo set_value('tbl_addrver',$addressver_details['id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$addressver_details['clientid']); ?>">
      <input type="hidden" name="addrverres_id" value="<?php echo set_value('addrverres_id',$addressver_details['addrverres_id']); ?>">
      <input type="hidden" name="add_com_ref" value="<?php echo set_value('add_com_ref',$addressver_details['add_com_ref']); ?>">
      <div id="append_result_model1"></div>

      
      <input type="hidden" name="attchments_ver" id="attachment_list_result" value=""> 
       <input type="hidden" name="upload_capture_image_address_ver_result" id="upload_capture_image_address_ver_result" value=""> 
      <div class="modal-footer">
     
        <button  class="btn btn-info btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'address/address_result_attachment/'.$addressver_details['id'] ?>" data-toggle="modal" id="arr_result_attachment" type="button" >Attachment</button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_address_ver_result">Copy Attachment</button>
        <button type="submit" id="sblogresult" name="sblogresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="capture_image_copy_address_ver_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_address_ver_result','id'=>'frm_upload_copied_image_address_ver_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-address-ver-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_address_ver_result" name="upload_copied_image_address_ver_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>




<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result','id'=>'add_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$addressver_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$addressver_details['package_id']); ?>">
      <input type="hidden" name="tbl_addrver" value="<?php echo set_value('tbl_addrver',$addressver_details['id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$addressver_details['clientid']); ?>">
      <input type="hidden" name="addrverres_id" value="<?php echo set_value('addrverres_id',$addressver_details['addrverres_id']); ?>">
      <input type="hidden" name="add_com_ref" value="<?php echo set_value('add_com_ref',$addressver_details['add_com_ref']); ?>">

      <div id="append_result_model"></div>
      <input type="hidden" name="attchments_ver" id="attachment_list" value=""> 

      <input type="hidden" name="upload_capture_image_address_result" id="upload_capture_image_address_result" value=""> 

      <div class="modal-footer">
        <button  class="btn btn-info btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'address/address_result_attachment/'.$addressver_details['id'] ?>" data-toggle="modal" id="arr_attachment" type="button" >Attachment</button>
        <button type="button" class="btn btn-info btn-sm address_form_display"  data-toggle="modal" id ="generate_form">Generate form</button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_address_result">Copy Attachment</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="add_result_attachment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_attachment','id'=>'add_verificarion_result_attachment')); ?>
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Attchment</h4>
      </div>
      <span class="errorTxt"></span>
       
      <div id="append_add_result_attachment"></div>

      
      <div class="modal-footer">
     
        <button type="submit" id="sbresultattachment" name="sbresultattachment" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="add_attachment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_attachment','id'=>'add_verificarion_attachment')); ?>
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Attchment</h4>
      </div>
      <span class="errorTxt"></span>
       
      <div id="append_add_attachment"></div>

      
      <div class="modal-footer">
     
        <button type="submit" id="sbattachment" name="sbattachment" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="capture_image_copy_address_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_address_result','id'=>'frm_upload_copied_image_address_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy marge to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-address-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_address_result" name="upload_copied_image_address_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
      <input type="hidden" name="tbl_addrver" value="<?php echo set_value('tbl_addrver',$addressver_details['id']); ?>">
      <input type="hidden" name="fin_status"  id = "fin_status"  class="fin_status" value="">

      <span class="errorTxt"></span>
      <div class="modal-body">
      <input type="hidden" name="attachment_list_vendor" id="attachment_list_vendor" value=""> 

      <div id="append_vendor_model"></div>

       <div class="tab-pane" id="tab_vendor_log1">
          <table id="tbl_vendor_log1" class="table table-bordered datatable_logs"></table>
        </div>
      </div>   
      <div class="modal-footer">
      <button type="button" id="vendor_details_back" name="vendor_details_back" class="btn btn-default btn-sm pull-left">Back</button>
      <div class="showbutton">
      <button type="submit" id="vendor_result_submit" name="vendor_result_submit" class="btn btn-success btn-sm">Save</button>
      <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

     </div>
     <div class="showbutton1" style="display: none;">

      <button  class="btn btn-success btn-sm" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_vendor_form/1/'.$addressver_details['id'] ?>/address" <?=$check_insuff_raise?> data-toggle="modal" id="activityModelClk1" type="button" >Continue</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

     </div>

      </div>
    </div>
    <?php echo form_close(); ?>
     
  </div>
</div>

<div id="vendor_add_attachment" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_attachment','id'=>'add_vendor_attachment')); ?>
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Attchment</h4>
      </div>
      <span class="errorTxt"></span>
       
      <div id="append_vendor_add_attachment"></div>

      
      <div class="modal-footer">
     
        <button type="submit" id="sbvendorattachment" name="sbvendorattachment" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="showvendorModel_cost" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_cost','id'=>'add_vendor_details_view_cost')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Costing Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body"> 
        <div id="append_vendor_model_cost"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="vendor_details_back_cost" name="vendor_details_back_cost" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="vendor_result_submit_cost" name="vendor_result_submit_cost" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel_cancel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_cancel','id'=>'add_vendor_details_view_cancel')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Vendor Reject</h4>
      </div>
      <input type="hidden" name="address_vendor_log_id"  id = "address_vendor_log_id">

      <span class="errorTxt"></span>
      <div class="modal-body"> 
      <div id="append_vendor_model_cancel"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="vendor_details_back_cancel" name="vendor_details_back_cancel" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="vendor_result_submit_cancel" name="vendor_result_submit_cancel" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModeldigital_cancel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_digital_cancel','id'=>'add_vendor_details_view_digital_cancel')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Vendor Cancel</h4>
      </div>

      <span class="errorTxt"></span>
      <div class="modal-body"> 
      <div id="append_vendor_model_digital_cancel"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="vendor_details_back_digital_cancel" name="vendor_details_back_digital_cancel" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="vendor_result_submit_digital_cancel" name="vendor_result_submit_digital_cancel" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>



<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="1">
            <input type="hidden" name="acti_candsid" value="<?php echo set_value('acti_candsid',$addressver_details['candsid']); ?>">
            <input type="hidden" name="comp_table_id" value="<?php echo set_value('comp_table_id',$addressver_details['id']); ?>">
            <input type="hidden" name="ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber',$addressver_details['ClientRefNumber']); ?>">

            <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
            <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$addressver_details['add_com_ref']); ?>">
            
            <div class="append-activity_view"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
      <div class="append-activity_records"></div>
    </div>
  </div>
</div>

<div id="insuffClearModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open_multipart("#", array('name'=>'frm_insuff_clear','id'=>'frm_insuff_clear')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insuff Clear</h4>
      </div>
      <div class="modal-body">
        <span class="errorTxt"></span>
        <input type="hidden" name="check_insuff_raise" id="check_insuff_raise" value="<?php echo set_value('check_insuff_raise'); ?>">
        <input type="hidden" name="clear_clientid" value="<?php echo set_value('clientid',$addressver_details['clientid']); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="<?php echo set_value('clear_update_id',$addressver_details['id']); ?>">
         <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
         <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
         <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$addressver_details['add_com_ref']); ?>">
        <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-4 form-group">
          <label>Clear Date<span class="error"> *</span></label>
          <input type="text" name="insuff_clear_date" id="insuff_clear_date" value="<?php echo set_value('insuff_clear_date'); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('insuff_clear_date'); ?>
        </div>
        <div class="col-md-8 col-sm-12 col-xs-8 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_remarks" rows="1" maxlength="500" id="insuff_remarks"  class="form-control"><?php echo set_value('insuff_remarks'); ?></textarea>
          <?php echo form_error('insuff_remarks'); ?>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
        </div>
       </div>
      </div>  
      <div class="modal-footer">
        <button type="submit" id="btn_insuff_clear" name="btn_insuff_clear" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="firstQCModelclk" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">First QC</h4>
      </div>
      <?php echo form_open('#', array('name'=>'frm_fist_qc','id'=>'frm_fist_qc')); ?>
      <div class="modal-body">
          <div class="row">
            <input type="hidden" name="fq_candsid" value="<?php echo set_value('fq_candsid',$addressver_details['candsid']); ?>">
            <input type="hidden" name="fq_addrverres_id" id="fq_addrverres_id" value="<?php echo set_value('fq_addrverres_id',$addressver_details['addrverres_id']); ?>">
            <input type="hidden" name="fq_component_emp_id" value="<?php echo set_value('fq_component_emp_id',$addressver_details['id']); ?>">
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <select id="first_qc_approve" class="form-control" name="first_qc_approve">
                <option value="">Select</option>
                <option value="First QC approved">First QC approved</option>
                <option value="First QC reject">First QC reject</option>
              </select>
            </div>
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <input type="text" name="first_qu_reject_reason" placeholder="Reason" id="first_qu_reject_reason" value="<?php echo set_value('first_qu_reject_reason'); ?>" class="form-control" placeholder='DD-MM-YYYY'>
              <?php echo form_error('first_qu_reject_reason'); ?>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_first_qc" name="btn_submit_first_qc" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="insuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insuff</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>">
         <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
         <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$addressver_details['add_com_ref']); ?>">
          <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
        
        <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise" id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise'); ?>" class="form-control myDatepicker1" placeholder='DD-MM-YYYY'>
          <?php echo form_error('txt_insuff_raise'); ?>
        </div>
        <div class="col-md-6 col-sm-8 col-xs-6 form-group">
          <label>Reason</label>
          <?php
           echo form_dropdown('insff_reason', $insuff_reason_list, set_value('insff_reason'), 'class="form-control setinsff_reason" id="insff_reason"');
            echo form_error('insff_reason'); 
          ?>
        </div>
        </div>
        <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="insuff_raise_remark" rows="1" maxlength="500" id="insuff_raise_remark"  class="form-control insuff_raise_remark"><?php echo set_value('insuff_raise_remark'); ?></textarea>
          <?php echo form_error('insuff_raise_remark'); ?>
        </div>
       </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="ReInitiateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_reinitiated','id'=>'frm_reinitiated')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Re-Initiated Component</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>">
         <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
         <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$addressver_details['clientid']); ?>">

        <div class = "row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Re-Initiated Date<span class="error"> *</span></label>
          <input type="text" name="reinitiated_date" id="reinitiated_date" value="<?php echo set_value('reinitiated_date',date('d-m-Y')); ?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
          <?php echo form_error('txt_reinitiated_date'); ?>
        </div>

        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
         <label>Re-Initiated Document</label>
        <input type="file" name="attachment_reinitiated[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attachment_reinitiated" value="<?php echo set_value('attachment_reinitiated'); ?>" class="form-control">
             <?php echo form_error('attachment_reinitiated'); ?>
        </div>
        
        </div>
        <div class = "row">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="reinitiated_remark" rows="1" maxlength="500" id="reinitiated_remark"  class="form-control reinitiated_remark"><?php echo set_value('reinitiated_remark'); ?></textarea>
          <?php echo form_error('reinitiated_remark'); ?>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_re_initited_date" name="btn_submit_re_initited_date" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
</div>
<div id="editInsuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 60%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_edit_insuff_raise','id'=>'frm_edit_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Details</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$addressver_details['candsid']); ?>">
        <div id="appedEditIsuffClear"></div>
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="AddGenerateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_generate_result_form','id'=>'add_generate_result_form')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Address Form Generate</h4>
      </div>
      <span class="errorTxt"></span>
      
      <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$addressver_details['clientid']); ?>">
      <input type="hidden" name="add_com_ref" value="<?php echo set_value('add_com_ref',$addressver_details['add_com_ref']); ?>">


      <div id="address_verification_details">
       <div class="content-pages" style = "padding-left: 40px;padding-right: 40px;padding-bottom: 40px;color:black;background-color:white;">
       <div style= "float:left;">V – 1.0 | Confidential</div>
       <img src="<?= SITE_IMAGES_URL; ?>logo.png" alt = "logo" style= "float:right;width:225px;height:70px;">
      <br>
      <br>
      <br>
      <br>
    
      <h6 style="text-align:center;">MIST IT SERVICES PVT LTD ADDRESS VERIFICATION FORM</h6> 
      <p><b> MIST IT Services Private Ltd</b> is a background verification company conducting employment verification services for their clients.</p>

    
       <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2">
        <tr>
            <th style='background-color: #0000004f;'><b>Check id</b></th>
            <th style='background-color: #0000004f;'><b>Initiation date (DD/MM/YY)</b></th> 
            <th style='background-color: #0000004f;'><b>Date of Visit (DD/MM/YYYY)</b></th> 
            <th style='background-color: #0000004f;'><b>Status</b></th> 
        </tr>
        <tr>
            <td ><div id = 'check_id'></div></td>
            <td ><div id = "initiation_date"></div></td>
            <td ><div id = "date_of_visit"></div></td>
            <td ><div id = "case_status"></div></td>
        </tr>
        </table>
       <br>
      <p style="text-align: justify;"><b><u>Candidate details:</u></b></p>

        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" >
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Name of the candidate</b></td><td  style="padding-left: 5px;"><div id = "CandidateNames"></div></td></tr> 
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Provided Address</b></td><td style="padding-left: 5px;"><div id = "provided_address"></div></td></tr>
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Contact No</b></td><td style="padding-left: 5px;"><div id = "candidate_contact_no"></div></td></tr>
          
        </table>

        <br>
        <p style="text-align: justify;"><b><u>Verified Information:</u></b></p>

        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" >
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Period of Stay</b></td><td  style="padding-left: 5px;"><div id = "period_stay_frm_to"></div></td></tr> 
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Residency status</b></td><td  style="padding-left: 5px;"><div id = "residency_status"></div></td></tr>
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Document proof provided</b></td><td style="padding-left: 5px;"><div id = "doc_provided"></div></td></tr>
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Verified by</b></td><td style="padding-left: 5px;"><div id = "veri_by"></div></td></tr>
        </table>

        <br>

        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2">
        <tr>
            <th style='background-color: #0000004f;'><b>Respondent’s Name</b></th>
            <th style='background-color: #0000004f;'><b>Contact details</b></th> 
            <th style='background-color: #0000004f;'><b>Signature</b></th> 
        </tr>
        <tr>
            <td ><div id = "respondent_name"></div></td>
            <td ><div id = "respondent_contact_no"></div></td>
            <td >NA</td>
        </tr>
        </table>
        <br>  
        <table style="border-spacing: 10; border-collapse: collapse; width:90%; max-width: 90%;" border="2" >
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Name & Signature of the Field Executive</b></td><td>NA</td></tr> 
          <tr><td style="background-color: #0000004f;padding-left: 5px;"><b>Remarks</b></td><td style="padding-left: 5px;"><div id = "verified_remark"></div></td></tr>
         
        </table>

        <br>
        <p style="text-align: center;"><b><u>Instructions to be Read and Signed by Respondent</u></b></p>
        <p style="text-align: justify;">•	No Fees are payable to the “Field Executive” during address verification. It is FREE. Please answer questions stated on the form. Do not provide any additional information which has no relation to address verification. </p>
        <p style="text-align: justify;">•	External house photograph is required for authenticity of the report. Please do not allow any other picture to be taken. For Example: - Inside house photo.</p>
        <br>
        <br>
        <p style="float:left;"><a href ="https://mistitservices.com/">www.mistitservices.com</a></p>
        <p style="float:right;">I-185,Garwhali Mohalla, Block I, Laxmi Nagar, Delhi-110092</p>
        </div>
        </div>
      <div class="modal-footer">
        <button type="submit" id="sbaddress_generate_frm" name="sbaddress_generate_frm" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="VendorChangeModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_vendor_change','id'=>'frm_vendor_change')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Case to Physical</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$addressver_details['id']); ?>">
        
        <div class = "row">
          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Vendor Name<span class="error"> *</span></label>
             <?php
                echo form_dropdown('change_vendor_name', $vendor_id, set_value('change_vendor_name'), 'class="select2" id="change_vendor_name"');
                 echo form_error('change_vendor_name');
           ?>
          </div>
        
        </div>
        
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_change_vendor" name="btn_submit_change_vendor" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
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
.image-content-result-address-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-address-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-address-result:not(:empty)::after,
.image-content-result-address:focus::after {
  display: none;
}

.image-content-result-address-result * {
  max-width: 100%;
  border: 0;
}

.image-content-result-address-ver-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-address-ver-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-address-ver-result:not(:empty)::after,
.image-content-result-address:focus::after {
  display: none;
}

.image-content-result-address-ver-result * {
  max-width: 100%;
  border: 0;
}
</style>

<script>

  $('.myDatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

     $('.myDatepicker1').datepicker({
      daysOfWeekDisabled: [0,6],
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,

      
  });

   $('#upload_copied_image_address_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_address_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-address-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_address_result').val(get_image_base);
          $('#capture_image_copy_address_result').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });
  
   $('#upload_copied_image_address_ver_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_address_ver_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-address-ver-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_address_ver_result').val(get_image_base);
          $('#capture_image_copy_address_ver_result').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });   

$(document).on('change', '.setinsff_reason', function(){
  var insff_reason = $(this).val();
  if(insff_reason != 0)
  {
    $.ajax({
      type:'POST',
      data : 'insff_reason='+insff_reason,
      dataType:'json',
      url:'<?php echo ADMIN_SITE_URL.'address/insuff_raise_remark/'; ?>',
      success:function(jdata) {
        jQuery('.insuff_raise_remark').val(jdata.message);
      }
    });
  } 
});

$(document).on('click','.clkInsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    var clientname = $(this).text();
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'address/insuff_edit_clear_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        beforeSend :function(){
          jQuery(this).text("loading...");
        },success:function(html) {
          $('#appedEditIsuffClear').html(html);
          $('#editInsuffRaiseModel').modal('show'); 
          $('#editInsuffRaiseModel').addClass("show");
          $('#editInsuffRaiseModel').css({background: "#0000004d"});   

        }
    });
  }
  else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }

});

$('.cls_disabled').prop('disabled', true);

$(document).on('click', '#clk_insuff_log', function(){
  var emp_id = $(this).data('emp_id');

  if(emp_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'address/insuff_tab_view/'; ?>'+emp_id,
          beforeSend :function(){
            jQuery('#append_insuff_view').html("<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>");
          },success:function(html) {
            jQuery('#append_insuff_view').html(html);
          }
      });
  } 
});


$(document).on('click','.InsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  var access_insuff = <?=($this->permission['access_address_list_insuff_raise']) ? 1 : 2 ; ?>;

  if(access_insuff == 1)
  {
    $('#insuffRaiseModel').modal('show');
    $('#insuffRaiseModel').addClass("show");
    $('#insuffRaiseModel').css({background: "#0000004d"});

  } else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }

});



$(document).on('click','.ReInitiateModel',function() {
  $('#ReInitiateModel').modal('show');
  $('#ReInitiateModel').addClass("show");
  $('#ReInitiateModel').css({background: "#0000004d"}); 
});

$(document).on('click','.insuffClearModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'address/insuff_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        dataType:'json',
        beforeSend :function(){
          jQuery('.insuffClearModel').text("loading...");
        },success:function(jdata) {
          jQuery('.insuffClearModel').text("Clear");
          $('#check_insuff_raise').val(jdata['insuff_raised_date']);
          $('#insuff_clear_id').val(jdata['id']);
          $('#insuff_clear_date').val(jdata['insuff_clear_date']);
          $('#insuff_remarks').val(jdata['insuff_remarks']);
          $('#insuffClearModel').modal('show');
          $('#insuffClearModel').addClass("show");
          $('#insuffClearModel').css({background: "#0000004d"}); 
        }
    });
  } else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }
});

$(document).on('click','.insuffDelete',function() {
  var insuff_data = $(this).data('editurl'); 

  var cands_id = <?php echo $addressver_details['candsid']; ?>;

  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'address/insuff_delete'; ?>',
        data : 'insuff_data='+insuff_data+'candidates_info_id='+cands_id,
        dataType:'json',
        beforeSend :function(){
          jQuery('.insuffDelete').text("Deleting...");
        },success:function(jdata) {
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
          }else {
            show_alert(message,'error'); 
          }
        }
    });
  }else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }
});


$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    var action2 = $("#action option:selected").text();
    var action1 =  action2.replace(/\s+/g,'');
    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url+"/"+action1,function(){
      $('#addAddResultModel').modal('show');
      $('#addAddResultModel').addClass("show");
      $('#addAddResultModel').css({background: "#0000004d"}); 
    });
  }
});


$(document).on('click','#generate_form',function() {
    var address =  $('#res_address').val();
    var city =  $('#res_city').val();
    var pincode =  $('#res_pincode').val();
    var state =  $('#res_state').val();
    var stay_from =  $('#res_stay_from').val();
    var stay_to =  $('#res_stay_to').val();
    var mode_of_verification =  $('#mode_of_verification').val();
    var resident_status =  $('#resident_status').val();
    var verified_by =  $('#verified_by').val();
    var addr_proof_collected =  $('#addr_proof_collected').val();
    var closuredate =  $('#closuredate').val();
    var remarks =  $('#remarks').val();

    
  
    if(address != '' && city != '' && pincode != '' && state != '' && stay_from != '' && stay_to != '' && mode_of_verification != '' && resident_status != '' && verified_by != '' && addr_proof_collected != '' && closuredate != '' && remarks != '')
    {
      var action2 = $("#action option:selected").text();
      var action1 =  action2.replace(/\s+/g,'');
      if(action2 == "Clear")
      {
           var address_com_ref = '<?php echo  $addressver_details['add_com_ref'];   ?>';
           var initiation_date = '<?php echo  convert_db_to_display_date($addressver_details['iniated_date']);   ?>';
           var CandidateName = '<?php echo  $addressver_details['CandidateName'];   ?>';
           var CandidatesContactNumber = '<?php echo  $get_cands_details['CandidatesContactNumber'];   ?>';
           var provided_address =  "<?php echo trim(preg_replace('/\s\s+/', ' ', $addressver_details['address']));  ?>";
           var closure_date  = $('#closuredate').val();
           var mode_of_verification  = $('#mode_of_verification option:selected').text();
           var res_stay_from = $('#res_stay_from').val();
           var res_stay_to = $('#res_stay_to').val();
           var resident_status = $('#resident_status option:selected').text();
           var document_provided = $('#addr_proof_collected option:selected').text();
           var verified_by = $('#verified_by').val();
           var remarks = $('#remarks').val();
           var remarks = $('#remarks').val();
          
           
          
           $('#check_id').html(address_com_ref);
           $('#initiation_date').html(initiation_date);
           $('#date_of_visit').html(closure_date);
           $('#case_status').html(mode_of_verification + ' - ' + action2);
           $('#CandidateNames').html(CandidateName);
           $('#provided_address').html(provided_address);
           $('#candidate_contact_no').html(CandidatesContactNumber);
           $('#period_stay_frm_to').html(res_stay_from + ' To ' + res_stay_to);
           $('#residency_status').html(resident_status);
           $('#doc_provided').html(document_provided);
           $('#veri_by').html(verified_by);
           $('#verified_remark').html(remarks);
           $('#respondent_name').html(verified_by);
           $('#respondent_contact_no').html(CandidatesContactNumber);
          
         
         // $('#append_address_form_model').load(url+"/"+action1,function(){
            $('#AddGenerateModel').modal('show');
            $('#AddGenerateModel').addClass("show");
            $('#AddGenerateModel').css({background: "#0000004d"}); 
        //  });

        
         
      }
      else
      {
        alert('This action only applicable clear status');
      }

    }
    else
    {
       alert('Please fill all mandatory details');
    }
  
});


$(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1)  
    var actual_value=document.getElementById(nameArr).value;

    var rtb_val = $(this).val();

    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
      $("#"+txt_val).removeAttr("readonly");
    }
    else if(rtb_val == 'yes'){
      $("#"+txt_val).val(actual_value);
      $("#"+txt_val).attr("readonly","true");
    } 
    else if(rtb_val == 'not-obtained') {
      $("#"+txt_val).val("Not Disclosed");
      $("#"+txt_val).attr("readonly","true");
    }
    else if(rtb_val == 'not-verified') {
      $("#"+txt_val).val("Not Verified");
      $("#"+txt_val).attr("readonly","true");
    }
});

$(document).ready(function(){

  if('<?=!empty($check_insuff_raise)?>') {
    show_alert('<?= $insuff_raise_message?>')
  }

  if('<?=!empty($assign_list_pending)?>') {
    show_alert('<?= $assign_list_pending?>')
  }


  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });

  $('#activityModelClk').click(function(){
    var url = $(this).data("url");
    $('.append-activity_view').load(url,function(){});
    $('#activityModel').modal('show');
    $('#activityModel').addClass("show");
    $('#activityModel').css({background: "#0000004d"}); 
  });


 $('#activityModelClk1').click(function(){
    var url = $(this).data("url");
    $('.append-activity_view').load(url,function(){});
    $('#activityModel').modal('show');
    $('#activityModel').addClass("show");
    $('#activityModel').css({background: "#0000004d"}); 
    $('#showvendorModel').modal('hide');

  
  });
  /*$(document).on('click','.showAddResultModel',function() {

  var result_id = $(this).data('url');
   alert(result_id);
  if(result_id != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'address/address_result_list_idwise/'; ?>',
        data : 'result_id='+result_id,
        dataType:'json',
        //beforeSend :function(){
       //   jQuery('.showAddResultModel').text("loading...");
       // },
       success:function(jdata) {
        
           $('#append_result_model1').html(resp);
          jQuery("#showAddResultModel").modal('show');
        //  $('#showAddResultModel').modal('show');
        }
    });
  }
});*/
 

 $(document).on('click','.showAddResultModel',function() {

    var url = $(this).data('url');
 
    $('#append_result_model1').load(url,function(){
      $('#showAddResultModel').modal('show');
      $('#showAddResultModel').addClass("show");
      $('#showAddResultModel').css({background: "#0000004d"}); 
    });
   
});

 $(document).on('click','#arr_result_attachment',function() {

    var url = $(this).data('url');
    $('#append_add_result_attachment').load(url,function(){
      $('#add_result_attachment').modal('show');
      $('#add_result_attachment').addClass("show");
      $('#add_result_attachment').css({background: "#0000004d"}); 
    });
   
});

 $(document).on('click','#arr_attachment',function() {

    var url = $(this).data('url');
    $('#append_add_attachment').load(url,function(){
      $('#add_attachment').modal('show');
      $('#add_attachment').addClass("show");
      $('#add_attachment').css({background: "#0000004d"}); 
    });
   
});

 $(document).on('click','#arr_vendor_attachment',function() {

    var url = $(this).data('url');
    $('#append_vendor_add_attachment').load(url,function(){
      $('#vendor_add_attachment').modal('show');
      $('#vendor_add_attachment').addClass("show");
      $('#vendor_add_attachment').css({background: "#0000004d"}); 
    });
   
});

  $('#frm_insuff_raise').validate({ 
    rules: {
      update_id : {
        required : true
      },
      insff_reason : {
        required : true,
        greaterThan : 0
      },
      txt_insuff_raise : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      insff_reason : {
        required : "Select Reason",
        greaterThan : "Select Reason"
      },
      txt_insuff_raise : {
        required : "Select Insuff Date"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/insuff_raised'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_insuff').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffRaiseModel').modal('hide');
            $('#frm_insuff_raise')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $('#add_generate_result_form').validate({ 
    rules: {
     
    },
    messages: {
     
    },
    submitHandler: function(form) 
    { 
      div_content =  document.getElementById('address_verification_details');

      html2canvas(div_content).then(function(canvas) {
                    //change the canvas to jpeg image
      data = canvas.toDataURL('image/png');
     
      var formData = new FormData(form);
      formData.append('img', data);  

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/save_address_generate_verification'; ?>',
          data : formData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbaddress_generate_frm').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#AddGenerateModel').modal('hide');
            }else {
              show_alert(message,'error'); 
            }
          }
        });     
      });  
    }
  });
   

  $('#frm_reinitiated').validate({ 
    rules: {
      update_id : {
        required : true
      },
      reinitiated_date : {
        required : true
      },
      reinitiated_remark : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      reinitiated_date : {
        required : "Insert Reinitiated Date"
      },
      reinitiated_remark : {
        required : "Enter Reinitiated Remark"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/add_reinitiated_date'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_re_initited_date').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_submit_re_initited_date').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#ReInitiateModel').modal('hide');
            $('#frm_reinitiated')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });
 
 
  $('#frm_edit_insuff_raise').validate({ 
    rules: {
      update_id : {
        required : true
      },
      insff_reason : {
        required : true,
        greaterThan : 0
      },
      txt_insuff_raise : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
      insff_reason : {
        required : "Select Reason",
        greaterThan : "Select Reason"
      },
      txt_insuff_raise : {
        required : "Select Insuff Date"
      },
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/update_insuff_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_insuff').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffRaiseModel').modal('hide');
            $('#frm_insuff_raise')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $('#frm_insuff_clear').validate({ 
    rules: {
      clear_update_id : {
        required : true
      },
      insuff_clear_date : {
        required : true
      },
      insuff_remarks : {
        required : true
      }
    },
    messages: {
      clear_update_id : {
        required : "Update ID missing"
      },
      insuff_clear_date : {
        required : "Select Insuff CLear Date"
      },
      insuff_remarks : {
        required : "Enter remarks"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/insuff_clear'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_insuff_clear').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_insuff_clear').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffClearModel').modal('hide');
            $('#frm_insuff_clear')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $('#frm_emp_update').validate({ 
    rules: {
      update_id : {
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
      employercontactno : {
        number : true
      },
      pincode : {
        number : true,
        minlength : 6,
        maxlength : 6
      },
      citylocality : {
        lettersonly : true
      },
      remuneration : {
        required: true
      },
      compant_contact : {
        number : true,
        minlength : 6
      },
      designation : {
        required : true
      },
      empfrom : {
        required : true
      },
      empto : {
        required : true
      },
      reasonforleaving : {
        required : true
      }
    },
    messages: {
      update_id : {
        required : "Update ID missing"
      },
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
      designation : {
        required : "Enter Designation"
      },
      remuneration : {
        required : "Enter Remuneration"
      }
    },
    submitHandler: function(form) 
    {      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'address/update_address'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_update').attr('disabled','disabled');
        },
        complete:function(){
        //  $("#frm_emp_update :input").prop("disabled", true);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
          }
          if(jdata.status == <?php echo ERROR_CODE; ?>){
            show_alert(message,'error'); 
          }
        }
      });       
    }
  });
  
   $(document).on('click','.showvendorModel',function() {

    var url = $(this).data('url');
    var id = $(this).data('id');
 
      $('#append_vendor_model').load(url,function(){
        $('#showvendorModel').modal('show');
      });
    
});

 $(document).on('click','.showvendorModel_cost',function() {

    var permissionid = <?php echo ($this->permission['access_address_aq_allow'] == '1') ? '1'  : '2' ?>;
    if(permissionid == '1'){
      var url = $(this).data('url');
      $('#append_vendor_model_cost').load(url,function(){
        $('#showvendorModel_cost').modal('show');
      }); 
    }
    else{
      alert('You have not permission to access this page');
    }  
   
});

  $(document).on('click','.showvendorModel_cancel',function() {

   
    var permissionid = <?php echo ($this->permission['access_address_aq_allow'] == '1') ? '1'  : '2' ?>;
    if(permissionid == '1'){
      
      var url = $(this).data('url');
      var case_id = $(this).data('case-id');
      $('#address_vendor_log_id').val(case_id);
      $('#append_vendor_model_cancel').load(url,function(){
        $('#showvendorModel_cancel').modal('show');
      });
    }
    else{
      alert('You have not permission to access this page');
    } 
   
});


  $(document).on('click','.showvendorModeldigital_cancel',function() {

   
    var permissionid = <?php echo ($this->permission['access_address_aq_allow'] == '1') ? '1'  : '2' ?>;
    if(permissionid == '1'){
      
      var url = $(this).data('url');

      $('#append_vendor_model_digital_cancel').load(url,function(){
        $('#showvendorModeldigital_cancel').modal('show');
      });
    }
    else{
      alert('You have not permission to access this page');
    } 
   
});


  $('#view_tab_vendor_log').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/vendor_logs/'.$addressver_details['id']; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_vendor_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
         $('#tbl_vendor_log').html(message);
        }
        else
        {
          $('#tbl_vendor_log').html(message);
        }

          var tbl_vendor_log =  $('#tbl_vendor_log').DataTable( {scrollX:true, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });

          tbl_vendor_log.columns.adjust().draw();
        }
    }); 
   
  }).trigger('click');



  $('#view_result_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'address/address_result_list/'.$addressver_details['id'].''; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_address_result').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
       
        if(jdata.status = 200)
        {

          $('#tbl_address_result').html(message);
        }
        else 
        {
          $('#tbl_address_result').html(message);
        }
        var tbl_address_result =  $('#tbl_address_result').DataTable( {scrollX: true,  "autoWidth": false,"ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });

       tbl_address_result.columns.adjust().draw();
      }
    }); 

    
  }).trigger('click');
  


 
 /* $('#view_activity_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'activity_log/activity_log_databale/'.$addressver_details['id'].'/1'; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_activity_log').html("Loading..");
      },
     
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          $('#tbl_activity_log').html(message);
        }
        else {
          $('#tbl_activity_log').html(message);
        }     

      var tbl_activity_log =  $('#tbl_activity_log').DataTable( { scrollX:true, "paging": true,  "processing": true,  "searching": false, "ordering": true, bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]]});
      }
    }); 
  }).trigger('click');*/

$('#view_activity_log_tabs').click(function(){
      $.ajax({
          type : 'POST',
          url : '<?php echo ADMIN_SITE_URL.'activity_log/activity_log_databale/'.$addressver_details['id'].'/1'; ?>',
          data: '',
          beforeSend :function(){
            jQuery('#tbl_activity_log').html("Please wait...");
          },success:function(html) {
            jQuery('#tbl_activity_log').html(html);
          }
      });
  }).trigger('click');

  $('#add_verificarion_result').validate({
      rules: {
        tbl_addrver : {
          required : true
        },
        addrverres_id : {
          required : true
        },
        mode_of_verification : {
          required : true
        },     
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        },
         resident_status : {
          required : true
        },
         verified_by : {
          required : true
        },
        addr_proof_collected : {
          required : true
        },
        res_address : {
          required : true
        },
        res_city : {
          required : true
        },
         res_pincode : {
          required : true
        },
         res_state : {
          required : true,
          greaterThan : 0
        },
         res_stay_from : {
          required : true
        },
         res_stay_to : {
          required : true
        },
         address_action : {
          required : true
        },
          city_action : {
          required : true
        },
        pincode_action : {
          required : true
        },
       state_action : {
          required : true
        },
         stay_from_action : {
          required : true
        },
         stay_to_action : {
          required : true
        }
      },
      messages: {
        tbl_addrver : {
          required : "ID"
        },
        addrverres_id : {
          required : "ID"
        },
        mode_of_verification : {
          required : "Select Mode Of Verification"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        }, 
         resident_status : {
          required : "Select Resident Status"
        },
         verified_by : {
           required : "Enter Verified By"
        },
        res_address : {
           required : "Enter Street Address"
        },
        res_city : {
           required : "Enter City"
        },
         res_pincode : {
           required : "Enter Pincode"
        },
         res_state : {
           required : "Select State",
           greaterThan : "Select State Name"
        },
         res_stay_from : {
           required : "Enter Stay Form"
        },
         res_stay_to : {
           required : "Enter Stay To"
        },
         address_action : {
           required : "Please Selected address Action"
        },
         city_action : {
           required : "Please Selected city Action"
        },
          pincode_action : {
           required : "Please Selected pincode Action"
        },
        
        state_action : {
           required : "Please Selected state Action"
        },
        stay_from_action : {
           required : "Please Selected stay from  Action"
        },
        stay_to_action : {
           required : "Please Selected stay to  Action"
        }
       
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=1'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
          data : activityData,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          success: function(jdata){
            $('#activityModel').modal('hide');
         
        var activityData = new FormData(form);
        activityData.append('action_val',$("#action option:selected").text());
        activityData.append('component_type',$("#action option:selected").text());
        activityData.append('activity_last_id',jdata.last_id);
        var sortable_data = $("ul.sortable" ).sortable('serialize'); 
        activityData.append('sortable_data',sortable_data);

        var sortable_data_vendor = $("ul.sortable1" ).sortable('serialize');
        activityData.append("sortable_data_vendor",sortable_data_vendor);

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_result'; ?>',
          data:  activityData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbresult').attr('disabled','disabled');
          },
          complete:function(){
            //$('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
          var  final_status = $('#fin_status').val();
          if(final_status == "Closed")
          {
             //var finalData = $('#add_vendor_details_view').serialize();
             var finalData =  new FormData($('#add_vendor_details_view')[0]);
      
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details'; ?>",
          data :  finalData,
          type: 'post',
          async:false,
          cache: false,
          mimeType: "multipart/form-data",
          contentType: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
          //  $('#vendor_result_submit').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#vendor_result_submit').removeAttr('disabled');                
          },
          success: function(jdata){
        
         
          
              }
            });  
           
          }
            var message =  jdata.message || '';
            if(jdata.redirect){
              show_alert(message,'success');
              location.reload();
              //window.location = jdata.redirect;
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
         }
        });  
      }
  });



   $('#add_verificarion_result_view').validate({
      rules: {
        tbl_addrver : {
          required : true
        },
        addrverres_id : {
          required : true
        },
        mode_of_verification : {
          required : true
        },     
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        },
         resident_status : {
          required : true
        },
         verified_by : {
          required : true
        },
        addr_proof_collected : {
          required : true
        },
        res_address : {
          required : true
        },
        res_city : {
          required : true
        },
         res_pincode : {
          required : true
        },
         res_state : {
          required : true,
          greaterThan : 0
        },
         res_stay_from : {
          required : true
        },
         res_stay_to : {
          required : true
        },
         address_action : {
          required : true
        },
          city_action : {
          required : true
        },
        pincode_action : {
          required : true
        },
       state_action : {
          required : true
        },
         stay_from_action : {
          required : true
        },
         stay_to_action : {
          required : true
        }

      },
      messages: {
        tbl_addrver : {
          required : "ID"
        },
        addrverres_id : {
          required : "ID"
        },
        mode_of_verification : {
          required : "Select Mode Of Verification"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        }, 
         resident_status : {
          required : "Select Resident Status"
        },
         verified_by : {
           required : "Enter Verified By"
        },
        res_address : {
           required : "Enter Street Address"
        },
        res_city : {
           required : "Enter City"
        },
         res_pincode : {
           required : "Enter Pincode"
        },
         res_state : {
           required : "Select State",
           greaterThan : "Select State Name"
        },
         res_stay_from : {
           required : "Enter Stay Form"
        },
         res_stay_to : {
           required : "Enter Stay To"
        },
         address_action : {
           required : "Please Selected address Action"
        },
         city_action : {
           required : "Please Selected city Action"
        },
          pincode_action : {
           required : "Please Selected pincode Action"
        },
        
        state_action : {
           required : "Please Selected state Action"
        },
        stay_from_action : {
           required : "Please Selected stay from  Action"
        },
        stay_to_action : {
           required : "Please Selected stay to  Action"
        }
      },
      submitHandler: function(form) 
      { 

        var sortable_data = $("ul.sortable" ).sortable('serialize');
        var sortable_data_vendor = $("ul.sortable1" ).sortable('serialize');
        var formData = new FormData(form);

         formData.append("sortable_data",sortable_data);
         formData.append("sortable_data_vendor",sortable_data_vendor);

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_ver_result'; ?>',
          data : formData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sblogresult').attr('disabled','disabled');
          },
          complete:function(){
           // $('#sblogresult').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.redirect){
              show_alert(message,'success');
              location.reload();
              //window.location = jdata.redirect;
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }    
  });


    $('#add_verificarion_result_attachment').validate({
      rules: {
        address_id : {
          required : true
        } 
      },
      messages: {
        address_id : {
          required : "ID"
        }
      },
      submitHandler: function(form) 
      { 

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_ver_result_attachment'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbresultattachment').attr('disabled','disabled');
          },
          complete:function(){
            $('#sbresultattachment').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status = 200){
              show_alert(message,'success');
              $('#attachment_list_result').val(jdata.attachments);
              $('#add_result_attachment').modal('hide');
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }    
  });

  $('#add_verificarion_attachment').validate({
      rules: {
        address_id : {
          required : true
        } 
      },
      messages: {
        address_id : {
          required : "ID"
        }
      },
      submitHandler: function(form) 
      { 

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_ver_result_attachment'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbattachment').attr('disabled','disabled');
          },
          complete:function(){
            $('#sbattachment').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status = 200){
              show_alert(message,'success');
              $('#attachment_list').val(jdata.attachments);
              $('#add_attachment').modal('hide');
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }    
  });


   $('#add_vendor_attachment').validate({
      rules: {
        vendor_log_id : {
          required : true
        } 
      },
      messages: {
        vendor_log_id : {
          required : "ID"
        }
      },
      submitHandler: function(form) 
      { 

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_vendor_result_attachment'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbvendorattachment').attr('disabled','disabled');
          },
          complete:function(){
            $('#sbvendorattachment').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status = 200){
              show_alert(message,'success');
              $('#attachment_list_vendor').val(jdata.attachments);
              $('#vendor_add_attachment').modal('hide');
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
      }    
  });
 

  $('#add_vendor_details_view').validate({
     
    rules: {
        trasaction_id : {
          required : true
        },
         vendor_remark : {
          required : true
        },
         vendor_date : {
          required : true
        }
     },
      messages: {
        trasaction_id : {
          required : "ID"
         },
          vendor_remark : {
           required : "Enter Vendor Remark"
        },
         vendor_date : {
          required : "Enter Vendor Closure Date"
        }
        },
      submitHandler: function(form) 
      { 
        
        //var activityData = $('#add_vendor_details_view').serialize();

        var activityData =  new FormData($('#add_vendor_details_view')[0]);

      
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details'; ?>",
          data :  activityData,
          type: 'post',
          async:false,
          cache: false,
          mimeType: "multipart/form-data",
          contentType: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#vendor_result_submit').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#vendor_result_submit').removeAttr('disabled');                
          },
          success: function(jdata){
          
          var message =  jdata.message || '';

           $('#showvendorModel').modal('hide');

          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
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

  $('#add_vendor_details_view_cost').validate({
     
       rules: {
        update_id : {
          required : true
        },

         charges : {
         required : true,
         number : true
        },

        additional_charges : {
         number : true
        }, 
     },


      messages: {
        update_id : {
          required : "ID"
         },

          charges : {
          required : "Please Enter Charges",
          number : "Please Enter Number only"

         },

          additional_charges : {
      
          number : "Please Enter Number only"

         },

        },


      submitHandler: function(form) 
      { 
        
      //  var activityData   =    new FormData(form);
     
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details_cost'; ?>",
          data :   new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#vendor_result_submit_cost').attr('disabled','disabled');
          },
          complete:function(){
           // $('#vendor_result_submit_cost').removeAttr('disabled');                
          },
          success: function(jdata){
          
          var message =  jdata.message || '';

          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

             $('#showvendorModel_cost').modal('hide');
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


    $('#add_vendor_details_view_cancel').validate({
     
       rules: {
        update_id : {
          required : true
        },

        venodr_reject_reason : {
         required : true
      
        },   
     },

      messages: {
        update_id : {
          required : "ID"
         },

        venodr_reject_reason : {
          required : "Please Vendor Reject Reason"
         
         },

        },


      submitHandler: function(form) 
      { 
        
      //  var activityData   =    new FormData(form);
     
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details_cancel'; ?>",
          data :   new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#vendor_result_submit_cancel').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#vendor_result_submit_cancel').removeAttr('disabled');                
          },
          success: function(jdata){
          var message =  jdata.message || '';

          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

             $('#showvendorModel_cancel').modal('hide');
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



    $('#add_vendor_details_view_digital_cancel').validate({
     
       rules: {
        update_id : {
          required : true
        },

        venodr_reject_digital_reason : {
         required : true
      
        } 
     },

      messages: {
        update_id : {
          required : "ID"
         },

        venodr_reject_digital_reason : {
          required : "Please Enter Reject Reason"
         
         }

        },


      submitHandler: function(form) 
      { 
        
      //  var activityData   =    new FormData(form);
     
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details_digital_cancel'; ?>",
          data :   new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#vendor_result_submit_digital_cancel').attr('disabled','disabled');
          },
          complete:function(){
            $('#vendor_result_submit_digital_cancel').removeAttr('disabled');                
          },
          success: function(jdata){
          var message =  jdata.message || '';

          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

             $('#showvendorModeldigital_cancel').modal('hide');
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
  
  $.validator.addMethod('filesize', function (value, element, arg) {
    var attachment_count = ($("#attchments_ver")[0].files.length); 
    var status = 2;  
    for(var i = 0; i < attachment_count; i++)
    {
      
      var file_size = element.files[i].size;
      if(arg<file_size){
        status = 1;
        break;
      }else{
        status = 2; 
        continue;
      }
    }
    if(status == 2)
    {
      return true;
    }
    else{

      return false;
    }

  });

   $('#frm_vendor_change').validate({
     
      rules: {
        update_id : {
          required : true
        },

        change_vendor_name : {
         required : true
        }
      },
      messages: {
        update_id : {
          required : "ID"
         },

        change_vendor_name : {
          required : "Please Select Vendor Name",
    
         }

      },
      submitHandler: function(form) 
      { 
      
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/change_physical_address'; ?>",
          data :   new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_change_vendor').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_change_vendor').removeAttr('disabled');                
          },
          success: function(jdata){
          
          var message =  jdata.message || '';

          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){

             $('#VendorChangeModal').modal('hide');
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



   $('#frm_digital_address').validate({
     
      rules: {
        digital_verification_case_id : {
          required : true
        }
      },
      messages: {
        digital_verification_case_id : {
          required : "ID"
         }

      },
      submitHandler: function(form) 
      { 
      
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/change_digital_address'; ?>",
          data :   new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_convert_degital_address').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_convert_degital_address').removeAttr('disabled');                
          },
          success: function(jdata){
          
          var message =  jdata.message || '';

          (jdata.upload_error != 'undefined') ? show_alert(jdata.upload_error,'error') : '';
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


$(document).on('click', '#show_vendor_insuff', function(e){
  var url = $(this).attr('data-url');
  var case_id = $(this).attr('data-case-id');
  var id = $(this).attr('data-id');
  var stat = $(this).attr('data-stat');

       $.ajax({
          url : url,
          data :  'case_id='+ case_id  + '&id='+ id + '&stat='+ stat, 
          type: 'post',
      
          beforeSend:function(){
            $('#show_vendor_insuff').attr('disabled','disabled');
          },
          complete:function(){
            $('#show_vendor_insuff').removeAttr('disabled');                
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
});

$(document).on('click', '#hide_vendor_insuff', function(e){

  var url = $(this).attr('data-url');
  var case_id = $(this).attr('data-case-id');
  var id = $(this).attr('data-id');
  var stat = $(this).attr('data-stat');

       $.ajax({
          url : url,
          data : 'case_id='+ case_id  + '&id='+ id + '&stat='+ stat, 
          type: 'post',
        
          beforeSend:function(){
            $('#show_vendor_insuff').attr('disabled','disabled');
          },
          complete:function(){
            $('#show_vendor_insuff').removeAttr('disabled');                
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
});

 $(document).on('click', '.copyLink', function() {
    let urlcopy = $(this).data('link');

    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(urlcopy).select();
    document.execCommand("copy");
    $temp.remove();

    $(this).text('link copied').removeClass('btn-info').addClass('btn-primary');
    setTimeout(function(){$('.copyLink').text('Copy Link').removeClass('btn-primary').addClass('btn-info');}, 2000);
   });
</script>


