<div class="content-page">
<div class="content">
<div class="container-fluid">
  
  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Employment</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORTCRMNAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>employment">Employment</a></li>
                  <li class="breadcrumb-item active">Employment Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>employment"><i class="fa fa-arrow-left"></i> Back</button></li>  
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
            <li class="nav-item waves-effect waves-light active"><a class = 'nav-link active' href="#employment_tab" data-toggle="tab">Employer Details</a></li>
            <li class="nav-item waves-effect waves-light view_component_tab" data-url="<?php echo ADMIN_SITE_URL.'candidates/ajax_view_component/'?>" data-can_id="<?php echo encrypt($empt_details['candsid']);?>" data-clients_id="<?php echo $empt_details['clientid'];?>" data-tab_name="candidate_tab" ><a href="#candidate_tab" class = 'nav-link' data-toggle="tab">Candidate Details</a></li>
          </ul>
          <div class="tab-content">
            <div class="active tab-pane" id="employment_tab">
              <?php $this->load->view('admin/employment_edit_form'); ?> 
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
              <li class="nav-item waves-effect waves-light active"><a  class = 'nav-link active' href="#activity_log_tabs" id="view_activity_log_tabs" data-toggle="tab">Activity</a></li>
              <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#clk_insuff" id='clk_insuff_log' data-emp_id="<?php echo $empt_details['id']; ?>" data-toggle="tab">Insufficiency</a></li>
          
              <li  class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#result_log_tabs" id="view_result_log_tabs" data-emp_id="<?php echo $empt_details['id']; ?>" data-toggle="tab">Result Log</a></li>
                
              <li class="nav-item waves-effect waves-light"><a  class = 'nav-link' href="#tab_vendor_log" id="view_tab_vendor_log" data-toggle="tab">Field visit</a></li>
              <li class="nav-item waves-effect waves-light"><a  class = 'nav-link' href="#tab_client_charges" id="view_tab_client_charges" data-toggle="tab">Client Charges</a></li>
            </ul>
            <br>
            <div class="tab-content">
              <div class="active tab-pane" id="activity_log_tabs">
                <div id="tbl_activity_log"></div>
              </div>
              <div class="tab-pane" id="clk_insuff">
                <div style="float: right;"><button class="btn btn-info btn-sm InsuffRaiseModel" data-editUrl='<?=($this->permission['access_employment_list_insuff_raise']) ? $insuff_raise_id : ''; ?>' <?php echo $check_insuff_raise;?>>Raise Insuff</button><br>
                </div>
                <div id='append_insuff_view'></div>
              </div>

              <div class="tab-pane" id="clk_field_visit">
               
                <div id='append_field_visit_view'></div>
              </div>

              <div class="tab-pane" id="result_log_tabs">


              <?php
               if($reinitiated == "1")
               {

                  $access_reverification = ($this->permission['access_employment_list_reverification'] == '1') ? 'ReInitiateModel'  : '';
              ?>
               <div style="float: right;"><button class="btn btn-info btn-sm <?php echo $access_reverification;?>" id="re-initiated">Reverify</button><br>
              </div>
               <?php
               }
              ?>
                 <table id="tbl_employment_result" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"></table>
              </div>


               <div class="tab-pane" id="tab_vendor_log">
                <?php 
              
                   if($empt_details['field_visit_status'] == "WIP") 
                   {
                    ?>
                
                 <?php 
                   }
                   else
                   {
                  ?>
                  <div style="float: right;"><button class="btn btn-info" data-url="<?= ADMIN_SITE_URL.'employment/field_visit/'.encrypt($empt_details['id']) ?>" data-toggle="field_visit" id="field_visit">Add Visit</button><br></div>
                    <div class="clearfix"></div>
                  <?php 
                   }
                   ?>
                <table id="tbl_vendor_log" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"></table>
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

<div id="addAddCompanyModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
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

<div id="addEditCompanyModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <?php echo form_open('#', array('name'=>'edit_company','id'=>'edit_company')); ?>
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>

      <input type="hidden" name="tbl_empver_id" id="tbl_empver_id" value="<?php echo set_value('tbl_empver_id',$empt_details['id']); ?>">
      <h4 class="modal-title">Edit Company</h4>
    </div>
    <div class="modal-body append_edit_model"></div>
    <div class="modal-footer">
      <button type="submit" id="company_edit" name="company_edit" class="btn btn-success">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-xl">

    <?php echo form_open_multipart("#", array('name'=>'empt_verificarion_result','id'=>'empt_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>

      
      <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$empt_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$empt_details['package_id']);?>">
      <input type="hidden" name="tbl_empver_id" value="<?php echo set_value('tbl_empver_id',$empt_details['id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
      <input type="hidden" name="empverres_id" value="<?php echo set_value('empverres_id',$empt_details['empverres_id']); ?>">
      <input type="hidden" name="emp_com_ref" value="<?php echo set_value('emp_com_ref',$empt_details['emp_com_ref']); ?>">
      
      <input type="hidden" name="url_action" value="" id="url_action">

      <input type="hidden" name="upload_capture_image_employment_result" id="upload_capture_image_employment_result" value=""> 

      <div id="append_result_model"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_employment_result">Copy Attachment</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="capture_image_copy_employment_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_employment_result','id'=>'frm_upload_copied_image_employment_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-employment-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_employment_result" name="upload_copied_image_employment_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="ReInitiateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_reinitiated','id'=>'frm_reinitiated')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Re-Initiated Component</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
        
        <div class="row">
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
         <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark<span class="error"> *</span></label>
          <textarea  name="reinitiated_remark" rows="1" maxlength="500" id="reinitiated_remark"  class="form-control reinitiated_remark"><?php echo set_value('reinitiated_remark'); ?></textarea>
          <?php echo form_error('reinitiated_remark'); ?>
        </div>
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


<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div class="modal-body">
        <div id="append_vendor_model"></div>
      </div>   

      
      <div class="modal-footer">
        <button type="button" id="vendor_details_back" name="vendor_details_back" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="vendor_result_submit" name="vendor_result_submit" class="btn btn-success btn-sm">Save</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel_cost" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl" >

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_cost','id'=>'add_vendor_details_view_cost')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Costing Details</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_vendor_model_cost"></div>



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
  <div class="modal-dialog modal-xl" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_cancel','id'=>'add_vendor_details_view_cancel')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Vendor Reject</h4>
      </div>
      <span class="errorTxt"></span>
      <div id="append_vendor_model_cancel"></div>



      <div class="modal-footer">
        <button type="button" id="vendor_details_back_cancel" name="vendor_details_back_cancel" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="vendor_result_submit_cancel" name="vendor_result_submit_cancel" class="btn btn-success btn-sm">Submit</button>
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
            <input type="hidden" name="component_type" id="component_type" value="2">
            <input type="hidden" name="acti_candsid" value="<?php echo set_value('acti_candsid',$empt_details['candsid']); ?>">
            <input type="hidden" name="comp_table_id" value="<?php echo set_value('comp_table_id',$empt_details['id']); ?>">
            <input type="hidden" name="ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber',$empt_details['ClientRefNumber']); ?>">

            <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$empt_details['emp_com_ref']); ?>">
            <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">  
            
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

<div id="initiationModel" class="modal fade initiationModel" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <?php echo "<h4 class='modal-title'>Employment verification of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")</h4>"; ?>
      </div>
      <?php echo form_open('#', array('name'=>'send_employment_mail','id'=>'send_employment_mail')); ?>
      <div class="modal-body">
      <div align="right">
        <button type="submit" id="send_mail_frm" name="send_mail_frm" class="btn btn-success"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

      </br>

      <div class="row">
      <input type="hidden" name="emp_user_id" value="<?php echo set_value('emp_user_id',$empt_details['id']); ?>">
         
      
      <div class="div1"> 
      <br>

      <div class="form-row mb-3">
        <label for="from" class="col-2 col-sm-1 col-form-label">From:</label>
          <div class="col-10 col-sm-11">
            <input type="email" class="form-control" id="from" name="from" placeholder="Type email" value="<?php echo $this->user_info['email']; ?>" readonly>
          </div>
      </div>
      

      <div class="form-row mb-3">
        <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
          <div class="col-10 col-sm-11">
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "Employment verification of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")"?>" readonly>
          </div>
      </div>
      
      <div class="form-row mb-3">
        <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
          <div class="col-10 col-sm-11">
            <input type="text" class="form-control" id="to_email" name="to_email" placeholder="Insert Multiple Email Id using comma separated">
          </div>
      </div>

      <div class="form-row mb-3">
        <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
          <div class="col-10 col-sm-11">
            <input type="text" class="form-control" id="cc_email" name="cc_email" placeholder="Insert Multiple Email Id using comma separated">
          </div>
      </div>

      <div class="form-row mb-3">
        <label for="bcc" class="col-2 col-sm-1 col-form-label">CC:</label>
          <div class="col-10 col-sm-11">
            <input type="text" class="form-control" id="bcc_email" name="bcc_email" placeholder="Type email" value="<?php  echo $bcc_email_id;  ?>" readonly>
          </div>
      </div>

      <div class="form-row mb-3">
        <label for="bcc" class="col-2 col-sm-1 col-form-label">Client Disclosure:</label>
          <div class="col-10 col-sm-5">    
            <select id= "client_disclosure" name="client_disclosure" class="form-control">
              <option value="yes"> Yes</option>
              <option value="no" selected="selected">No</option>
            </select>
          </div>
      </div>

         <table style="border: 1px solid black; ">
          <tr>
             <th style='border: 1px solid black;padding: 8px;'>File Name</th>
             <th style='border: 1px solid black;padding: 8px;'>Uploaded AT</th>
             <th style='border: 1px solid black;padding: 8px;'>Action</th>
          </tr>
          <tr>
            <?php 
              $i = 1;  
              foreach ($attachments as $key => $value) {
             

              $url  = SITE_URL.EMPLOYMENT.$empt_details['clientid'].'/';
              if($value['type'] == 0)
              {
                echo '<tr>'; 
              ?>

               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>Others</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>

              <?php
                echo '</tr>';
              }

              if($value['type'] == 2)
              {
                echo '<tr>'; 
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>Insuff Cleared</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
              
                echo '</tr>';
              }

              if($value['type'] == 3)
              {
                 echo '<tr>'; 
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>Experience/Relieving Letter</td>
                <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
                 
                echo '</tr>';
              }

              if($value['type'] == 4)
              {
                 echo '</tr>';
              ?>
               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>LOA</td>
               <td style='border: 1px solid black; text-align:center;'><input type="checkbox" name="attachment[]" value="<?php echo $value['file_name'] ?>"></td>
              <?php
                echo '</tr>';
              }
            } 
            ?>

          </tr>
        </table>

       <div class="clearfix"></div>
        
         </div> 
        
          <div class="append-email_tem"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="send_mail_frm" name="send_mail_frm" class="btn btn-success"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="summeryMailModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <?php echo "<h4 class='modal-title'>Employment verification summary of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")</h4>"; ?>
                  
      </div>
      <?php echo form_open('#', array('name'=>'frm_summery_mail','id'=>'frm_summery_mail')); ?>
      <div class="modal-body">
        <div align="right">
          <button type="submit" id="btn_summary_mail1" name="btn_summary_mail1" class="btn btn-success btn_summary_mail1"> Send</button>

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </br>
        <div class="row">
          <input type="hidden" name="emp_user_id" value="<?php echo set_value('emp_user_id',$empt_details['id']); ?>">
         
          <div class="div1"> 
          <br>

            <div class="form-row mb-3">
                <label for="from" class="col-2 col-sm-1 col-form-label">From:</label>
                <div class="col-10 col-sm-11">
                    <input type="email" class="form-control" id="from" name="from" placeholder="Type email" value="<?php echo $this->user_info['email']; ?>" readonly>
                </div>
            </div>
            
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "Employment verification summary of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")"?>" readonly>
                </div>
            </div>
      
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="to_email" name="to_email" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="cc_email" name="cc_email" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="bcc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="bcc_email" name="bcc_email" placeholder="Type email" value="<?php  echo $bcc_email_id;  ?>" readonly>
                </div>
            </div>

            <div class="form-row mb-3">
                <label for="bcc" class="col-2 col-sm-1 col-form-label">Client Disclosure:</label>
                <div class="col-10 col-sm-5">
                   
                   <select id= "client_disclosure_summary" name="client_disclosure_summary" class="form-control">
                    <option value="yes"> Yes</option>
                    <option value="no" selected="selected">No</option>
                   </select>
                </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="append-summery_mailView"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_summary_mail" name="btn_summary_mail" class="btn btn-success btn_summary_mail"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


 <div id="genericMail" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <?php echo "<h4 class='modal-title'>Employment verification of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")</h4>"; ?>            
      </div>
      <?php echo form_open('#', array('name'=>'frm_generic_mail','id'=>'frm_generic_mail')); ?>
      <div class="modal-body">
        <div align="right">
          <button type="submit" id="btn_generic_mail" name="btn_generic_mail" class="btn btn-success"> Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </br>
      <div class="row">
        <input type="hidden" name="emp_user_id" value="<?php echo set_value('emp_user_id',$empt_details['id']); ?>">
          <div class="div1"> 
          <br>

            <div class="form-row mb-3">
                <label for="from" class="col-2 col-sm-1 col-form-label">From:</label>
                <div class="col-10 col-sm-11">
                    <input type="email" class="form-control" id="from" name="from" placeholder="Type email" value="<?php echo $this->user_info['email']; ?>" readonly>
                </div>
            </div>
      

            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "Employment verification of ".ucwords($email_info['CandidateName'])." - (".strtoupper($email_info['emp_com_ref']).")"?>" readonly>
                </div>
            </div>
      
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="to_email" name="to_email" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                  <input type="text" class="form-control" id="cc_email" name="cc_email" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="bcc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="bcc_email" name="bcc_email" placeholder="Type email" value="<?php  echo $bcc_email_id; ?>" readonly>
                </div>
            </div>

            <div class="clearfix"></div>
          </div>
          <div class="append-genericView"></div>
        </div>
      </div>
        <div class="modal-footer">
          <button type="submit" id="btn_generic_mail" name="btn_generic_mail" class="btn btn-success"> Send</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="followMailModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <?php echo "<h4 class='modal-title'>Employment verification of ".ucwords($email_info['CandidateName'])." - ".$email_info['emp_com_ref']."</h4>"; ?>
                  
      </div>
      <?php echo form_open('#', array('name'=>'frm_follow_mail','id'=>'frm_follow_mail')); ?>
      <div class="modal-body">
        <div align="right">
          <button type="submit" id="btn_followup_mail1" name="btn_followup_mail1" class="btn btn-success btn_followup_mail1"> Send</button>

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        </br>
        <div class="row">
          <input type="hidden" name="emp_user_id" value="<?php echo set_value('emp_user_id',$empt_details['id']); ?>">
         
          <div class="div1"> 
          <br>

            <div class="form-row mb-3">
                <label for="from" class="col-2 col-sm-1 col-form-label">From:</label>
                <div class="col-10 col-sm-11">
                    <input type="email" class="form-control" id="from" name="from" placeholder="Type email" value="<?php echo $this->user_info['email']; ?>" readonly>
                </div>
            </div>
            
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">Subject:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "RE: Employment verification of ".ucwords($email_info['CandidateName'])." - ".$email_info['emp_com_ref']?>" readonly>
                </div>
            </div>
      
            <div class="form-row mb-3">
                <label for="to" class="col-2 col-sm-1 col-form-label">To:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="to_email" name="to_email" value="<?php if(isset($initiation_mail_details) && !empty($initiation_mail_details)){ echo $initiation_mail_details['to_mail_id']; } ?>" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="cc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="cc_email" name="cc_email" value="<?php if(isset($initiation_mail_details) && !empty($initiation_mail_details)){ echo $initiation_mail_details['cc_mail_id']; } ?>" placeholder="Insert Multiple Email Id using comma separated">
                </div>
            </div>
            <div class="form-row mb-3">
                <label for="bcc" class="col-2 col-sm-1 col-form-label">CC:</label>
                <div class="col-10 col-sm-11">
                    <input type="text" class="form-control" id="bcc_email" name="bcc_email" placeholder="Type email" value="<?php  echo $bcc_email_id;  ?>" readonly>
                </div>
            </div>

            <div class="clearfix"></div>
          </div>
          <div class="append-follow_mailView"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_follow_up_mail" name="btn_follow_up_mail" class="btn btn-success btn_follow_up_mail"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>



        <div id="HrDatabaseDetails" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"  style="overflow-x: scroll;">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Company Database Details</h4>
                </div>
                <?php echo form_open('#', array('name'=>'frm_hr_databse_details','id'=>'frm_hr_databse_details')); ?>
                   <div class="modal-body">

                    <div class="append-hrdatabaseView"></div>
              
                  </div>
              
                 <div class="modal-footer">
                 
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                <?php echo form_close(); ?>
              </div>
            </div>
          </div>          

<div id="fieldVisitModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <?php echo form_open_multipart("#", array('name'=>'frm_field_visit','id'=>'frm_field_visit')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Field Visit</h4>
      </div>
      <div class="modal-body"> <div id="append_field_visit_frm"></div> </div>  
      <div class="modal-footer">
        <button type="submit" id="add_field_visit" name="add_field_visit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_view','id'=>'add_verificarion_result_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Result Log Details</h4>
      </div>
      <span class="errorTxt"></span>
       <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$empt_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$empt_details['package_id']);?>">
      <input type="hidden" name="tbl_empver_id" value="<?php echo set_value('tbl_empver_id',$empt_details['id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
      <input type="hidden" name="empverres_id" value="<?php echo set_value('empverres_id',$empt_details['empverres_id']); ?>">
      <input type="hidden" name="emp_com_ref" value="<?php echo set_value('emp_com_ref',$empt_details['emp_com_ref']); ?>">
      <input type="hidden" name="upload_capture_image_employment_ver_result" id="upload_capture_image_employment_ver_result" value=""> 
      <div id="append_result_model1"></div>
      <div class="modal-footer">
       <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_employment_ver_result">Copy Attachment</button>
        <button type="submit" id="sblogresult" name="sblogresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="capture_image_copy_employment_ver_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_employment_ver_result','id'=>'frm_upload_copied_image_employment_ver_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-employment-ver-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_employment_ver_result" name="upload_copied_image_employment_ver_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="insuffClearModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <?php echo form_open_multipart("#", array('name'=>'frm_insuff_clear','id'=>'frm_insuff_clear')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Insuff Clear</h4>
      </div>
      <div class="modal-body">
        <span class="errorTxt"></span>
        <input type="hidden" name="check_insuff_raise" id="check_insuff_raise" value="<?php echo set_value('check_insuff_raise'); ?>">
        <input type="hidden" name="clear_clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="<?php echo set_value('clear_update_id',$empt_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$empt_details['emp_com_ref']); ?>">
        <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">  
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
            <div class="col-sm-4 form-group">
              <label>Others</label>
              <input type="file" name="attchments_clear[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_clear" value="<?php echo set_value('attchments_clear'); ?>" class="form-control">
              <?php echo form_error('attchments_clear'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>Experience/Relieving Letter</label>
              <input type="file" name="attchments_reliving_clear[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving_clear" value="<?php echo set_value('attchments_reliving_clear'); ?>" class="form-control">
             
              <?php echo form_error('attchments_reliving_clear'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>LOA</label>
              <input type="file" name="attchments_loa_clear[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa_clear" value="<?php echo set_value('attchments_loa_clear'); ?>" class="form-control">
             
              <?php echo form_error('attchments_loa_clear'); ?>
            </div>
       <!-- <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
        </div>-->
        
         
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">First QC</h4>
      </div>
      <?php echo form_open('#', array('name'=>'frm_fist_qc','id'=>'frm_fist_qc')); ?>
      <div class="modal-body">
          <div class="row">
            <input type="hidden" name="fq_candsid" value="<?php echo set_value('fq_candsid',$empt_details['candsid']); ?>">
            <input type="hidden" name="fq_empverres_id" id="fq_empverres_id" value="<?php echo set_value('fq_empverres_id',$empt_details['empverres_id']); ?>">
            <input type="hidden" name="fq_component_emp_id" value="<?php echo set_value('fq_component_emp_id',$empt_details['id']); ?>">
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insuff</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="insuff_edit" id="insuff_edit" value="">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$empt_details['emp_com_ref']); ?>">
        <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">  

        <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise"  id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',date(
          'd-m-Y')); ?>" class="form-control myDatepicker1" placeholder='DD-MM-YYYY'>
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

<div id="editInsuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl" style="overflow-y: scroll; max-height:90%;width: 60%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_edit_insuff_raise','id'=>'frm_edit_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Details</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$empt_details['id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$empt_details['candsid']); ?>">
        <div id="appedEditIsuffClear"></div>
         <input type="hidden" name="clear_clientid" value="<?php echo set_value('clientid',$empt_details['clientid']); ?>">
         <div class="row">
            <div class="col-sm-4 form-group">
              <label>Others</label>
              <input type="file" name="attchments_clear_edit[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_clear_edit" value="<?php echo set_value('attchments_clear_edit'); ?>" class="form-control">
              <?php echo form_error('attchments_clear_edit'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>Experience/Relieving Letter</label>
              <input type="file" name="attchments_reliving_clear_edit[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_reliving_clear_edit" value="<?php echo set_value('attchments_reliving_clear_edit'); ?>" class="form-control">
             
              <?php echo form_error('attchments_reliving_clear_edit'); ?>
            </div>
            <div class="col-sm-4 form-group">
              <label>LOA</label>
              <input type="file" name="attchments_loa_clear_edit[]" accept=".png, .jpg, .jpeg,.eml,.pdf,.docx,.xls,.xlsx" multiple="multiple" id="attchments_loa_clear_edit" value="<?php echo set_value('attchments_loa_clear_edit'); ?>" class="form-control">
             
              <?php echo form_error('attchments_loa_clear_edit'); ?>
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
.image-content-result-employment-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-employment-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-employment-result:not(:empty)::after,
.image-content-result-employment-result:focus::after {
  display: none;
}

.image-content-result-employment-result * {
  max-width: 100%;
  border: 0;
}

.image-content-result-employment-ver-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-employment-ver-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-employment-ver-result:not(:empty)::after,
.image-content-result-employment-ver-result:focus::after {
  display: none;
}

.image-content-result-employment-ver-result * {
  max-width: 100%;
  border: 0;
}
</style>

<link rel="stylesheet" href="<?php echo SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>   
<script>

$('.myDatepicker1').datepicker({
    daysOfWeekDisabled: [0,6],
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true,  
});

$('#upload_copied_image_employment_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_employment_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-employment-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_employment_result').val(get_image_base);
          $('#capture_image_copy_employment_result').modal('hide');
          $('.image-content-result-employment-result').empty();
          show_alert('Image copied success', 'success');
      }
  });
  
   $('#upload_copied_image_employment_ver_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_employment_ver_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-employment-ver-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_employment_ver_result').val(get_image_base);
          $('#capture_image_copy_employment_ver_result').modal('hide');
          $('.image-content-result-employment-ver-result').empty();
          show_alert('Image copied success', 'success');
      }
  });   


var counter = 1;
$(document).on('click', '#clk_insuff_log', function(){
  var emp_id = $(this).data('emp_id');
  if(emp_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'employment/insuff_tab_view/'; ?>'+emp_id,
          beforeSend :function(){
            jQuery('#append_insuff_view').html("Please wait...");
          },success:function(html) {
            jQuery('#append_insuff_view').html(html);
          }
      });
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
          url:'<?php echo ADMIN_SITE_URL.'employment/insuff_raise_remark/'; ?>',
          success:function(jdata) {
            jQuery('.insuff_raise_remark').val(jdata.message);
          }
      });
  } 
});
 
$(document).on('change', '.verfstatus,.verfstatusup', function(){
  verfstatus = $("select.verfstatus option:selected").text();
  onChangeStatus(verfstatus);   
});

$(document).on('change', '#info_integrity_disciplinary_issue', function(){
  var  integrity_disciplinary_issue = $('#info_integrity_disciplinary_issue').val();

    if(integrity_disciplinary_issue == "yes" )
     {
       $('#integrity_disciplinary').show();
     }
     else
     {
       $('#integrity_disciplinary').hide();
     }
     
});

$(document).on('change', '#info_eligforrehire', function(){
  var  info_eligforrehire = $('#info_eligforrehire').val();
  
    if(info_eligforrehire == "no" )
     {
       $('#eligible_for_rehire').show();
     }
     else
     {
       $('#eligible_for_rehire').hide();
     }
     
});

$(document).on('change', '#info_exitformalities', function(){
  var  info_exitformalities = $('#info_exitformalities').val();
  
    if(info_exitformalities == "no" )
     {
       $('#exit_formality').show();
     }
     else
     {
       $('#exit_formality').hide();
     }
     
});

$(document).on('click','.InsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  var access_insuff = <?=($this->permission['access_employment_list_insuff_raise']) ? 1 : 2 ; ?>;

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
          url : '<?php echo ADMIN_SITE_URL.'employment/employment_reinitiated_date'; ?>',
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
           // $('#btn_submit_re_initited_date').removeAttr('disabled',false);
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


   $('#edit_company').validate({ 
    rules: {
      tbl_empver_id : {
        required : true
      },
      nameofthecompany : {
        required : true,
        greaterThan : 0
      },
      
    },
    messages: {
      tbl_empver_id : {
        required : "Update ID missing"
      },
       nameofthecompany : {
        required : "Please Select Company",
        greaterThan : "Please Select Company"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/employment_update_company'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#company_edit').attr('disabled','disabled');
          },
          complete:function(){
           // $('#company_edit').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#addEditCompanyModel').modal('hide');
            $('#edit_company')[0].reset();
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

$(document).on('click','.clkInsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    var btn_text = $(this).text();
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'employment/insuff_edit_clear_raised_data/'; ?>',
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
});

$(document).on('click','.insuffClearModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'employment/insuff_raised_data/'; ?>',
        data : 'insuff_data='+insuff_data,
        dataType:'json',
        beforeSend :function(){
          jQuery(this).text("loading...");
        },success:function(jdata) {
          jQuery(this).text("Clear");
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

  var cands_id = <?php echo $empt_details['candsid']; ?>;
   
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'employment/insuff_delete'; ?>',
        data : 'insuff_data='+insuff_data+'candidates_info_id='+cands_id,
        dataType:'json',
        beforeSend :function(){
          jQuery(this).text("Deleting...");
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


$(document).on('click','#field_visit',function() {
    var url = $(this).data('url');
    $('#append_field_visit_frm').load(url,function(){
      $('#fieldVisitModel').modal('show');
      $('#fieldVisitModel').addClass("show");
      $('#fieldVisitModel').css({background: "#0000004d"});   
    });
});

$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    var action2 = $("#action option:selected").text();
    var action1 =  action2.replace(/\s+/g,'');

    $('#url_action').val(action1);

    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url+"/"+action1,function(){
      $('#addAddResultModel').modal('show');
      $('#addAddResultModel').addClass("show");
      $('#addAddResultModel').css({background: "#0000004d"}); 
    });
  }
});

$('#tbl_employment_result tbody').on( 'dblclick', 'tr', function () {
  var row_data = $(this).attr('id');
  
  if(row_data != "")
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'employment/add_result_model/' ?>'+row_data,
          beforeSend :function(){
            jQuery('.body_loading').show();
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(html)
          {
            $('#append_result_model').html(html);
            $('#empt_verificarion_result input,textarea,select,button').prop('disabled', true);
            $('#addAddResultModel').modal('show');
          }
      });
  }
});



$(document).ready(function(){

  $('#send_employment_mail').validate({
    rules : { 
      to_email : {
        required : true,
        multiemails : true
      },
    cc_email :{
        multiemails : true
      }, 
      bcc_email :{
        multiemails : true
      }
    },
    messages : {
      to_email : {
        required : "Enter Email To ID's",
        multiemails : "Enter Valid Email ID"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }, 
       bcc_email : {
       multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'employment/emp_send_mail'; ?>",
        data : $('#send_employment_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#send_mail_frm').val('Sending...');
          $('#send_mail_frm').attr('disabled','disabled');
        },
        complete:function(){
         // $('#send_mail_frm').removeAttr('disabled');
          $('#send_mail_frm').val('Send');                
        },
        success: function(jdata){
          $('#initiationModel').modal('hide');
          $('#send_employment_mail')[0].reset();
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            show_alert(message,'success'); 
             location.reload(); 
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    },
  });

  

 $('#frm_summery_mail').validate({
    rules : { 
      to_email : {
        required : true,
        multiemails : true
      },
    cc_email :{
        multiemails : true
      }, 
      bcc_email :{
        multiemails : true
      }
    },
    messages : {
      to_email : {
        required : "Enter Email To ID's",
        multiemails : "Enter Valid Email ID"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }, 
       bcc_email : {
       multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){

      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'employment/emp_send_summary_mail'; ?>",
        data : $('#frm_summery_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_summary_mail1').val('Sending...');
          $('#btn_summary_mail1').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_summary_mail').removeAttr('disabled');
          $('#btn_summary_mail1').val('Send');                
        },
        success: function(jdata){
          $('#summeryMailModel').modal('hide');
          $('#frm_summery_mail')[0].reset();
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            show_alert(message,'success');
            location.reload(); 
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    },
  });

  $('#frm_generic_mail').validate({
    rules : { 
      to_email : {
        required : true,
        multiemails : true
      },
    cc_email :{
        multiemails : true
      }, 
      bcc_email :{
        multiemails : true
      }
    },
    messages : {
      to_email : {
        required : "Enter Email To ID's",
        multiemails : "Enter Valid Email ID"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }, 
       bcc_email : {
       multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'employment/emp_send_generic_mail'; ?>",
        data : $('#frm_generic_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_generic_mail').val('Sending...');
          $('#btn_generic_mail').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_generic_mail').removeAttr('disabled');
          $('#btn_generic_mail').val('Send');                
        },
        success: function(jdata){
          $('#genericMail').modal('hide');
          $('#frm_generic_mail')[0].reset();
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            show_alert(message,'success');
            location.reload(); 
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    },
  });


   $('#frm_follow_mail').validate({
    rules : { 
      to_email : {
        required : true,
        multiemails : true
      },
      cc_email :{
        multiemails : true
      }, 
      bcc_email :{
        multiemails : true
      }
    },
    messages : {
      to_email : {
        required : "Enter Email To ID's",
        multiemails : "Enter Valid Email ID"
      },
      cc_email :{
        multiemails : "Enter Valid Email ID"
      }, 
       bcc_email : {
       multiemails : "Enter Valid Email ID"
      }

    },
    submitHandler: function(){
      $.ajax({
        url : "<?php echo ADMIN_SITE_URL.'employment/emp_send_follow_mail'; ?>",
        data : $('#frm_follow_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_follow_up_mail').val('Sending...');
          $('#btn_follow_up_mail').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_generic_mail').removeAttr('disabled');
          $('#btn_follow_up_mail').val('Send');                
        },
        success: function(jdata){
          $('#followMailModel').modal('hide');
          $('#frm_follow_mail')[0].reset();
          var message =  jdata.message || '';
          if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
            show_alert(message,'success');
            location.reload(); 
          } else {
            show_alert(message,'error'); 
          }
        }
      });
    },
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

        

$(document).ready(function(){
  $("#frm_emp_update :input").prop("disabled", true);

  if('<?=!empty($check_insuff_raise)?>') {
    show_alert('<?= $insuff_raise_message?>')
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
  
  $('#emp_initiation_mail').click(function(){
    var emp_from = $('#empfrom').val();
    var emp_to = $('#empto').val();
    var designation = $('#designation').val();
    if(emp_from != "" && emp_to != "" && designation != "")
    {
      var url = $(this).data("url");
      $('.append-email_tem').load(url,function(){
        $('#initiationModel').modal('show');
        $('#initiationModel').addClass("show");
        $('#initiationModel').css({background: "#0000004d"}); 
      });
    }
    else
    {
      show_alert('Employed From and To date and Designation should not be empty','error');
      return true;
    }

  });



  $(document).on('click','.emp_initiation_mail1',function() {  
    var emp_from = $('#empfrom').val();
    var emp_to = $('#empto').val();
    var designation = $('#designation').val();
    if(emp_from != "" && emp_to != "" && designation != "")
    {
       
      $('#HrDatabaseDetails').modal('hide');
      var url = $(this).data("url");
      var email  = $(this).data("email_id");
      $('#to_email').val(email);
      $('.append-email_tem').load(url,function(){
        $('#initiationModel').modal('show');
        $('#initiationModel').addClass("show");
        $('#initiationModel').css({background: "#0000004d"}); 

      });
    }
    else
    {
      show_alert('Employed From and To date and Designation should not be empty','error');
      return true;
    }
   
});

  $('#summery_mail').click(function(){
    var url = $(this).data("url");
      $('.append-summery_mailView').load(url,function(){
        $('#summeryMailModel').modal('show');
        $('#summeryMailModel').addClass("show");
        $('#summeryMailModel').css({background: "#0000004d"}); 
      });
  });

  $('#follow_up').click(function(){
    var url = $(this).data("url");
      $('.append-follow_mailView').load(url,function(){
        $('#followMailModel').modal('show');
        $('#followMailModel').addClass("show");
        $('#followMailModel').css({background: "#0000004d"}); 
      });
  });



  $('#generic_main').click(function(){
    var emp_from = $('#empfrom').val();
    var emp_to = $('#empto').val();
    var designation = $('#designation').val();
    if(emp_from != "" && emp_to != "" && designation != "")
    {
      var url = $(this).data("url");
      $('#frm_generic_mail')[0].reset();
      $('.append-genericView').load(url,function(){
        $('#genericMail').modal('show');
        $('#genericMail').addClass("show");
        $('#genericMail').css({background: "#0000004d"});   
      });
    }
    else
    {
      show_alert('Employed From and To date and Designation should not be empty','error');
      return true;
    } 
  });

  $('#hr_database').click(function(){
     var url = $(this).data("url");
      $('.append-hrdatabaseView').load(url,function(){
        $('#HrDatabaseDetails').modal('show');
        $('#HrDatabaseDetails').addClass("show");
        $('#HrDatabaseDetails').css({background: "#0000004d"}); 
      });
   
  });

  $('#frm_fist_qc').validate({ 
    rules: {
      fq_empverres_id : {
        required : true,
        greaterThan : 0
      },
      first_qc_approve : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      fq_empverres_id : {
        required : "Update ID missing"
      },
      first_qc_approve : {
        required : "Select"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'employment/frm_first_qc'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_first_qc').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_submit_first_qc').removeAttr('disabled',false);
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
              url : '<?php echo ADMIN_SITE_URL.'employment/insuff_raised'; ?>',
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
          url : '<?php echo ADMIN_SITE_URL.'employment/update_insuff_details'; ?>',
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

  $('#frm_field_visit').validate({ 
        rules: {
          update_id : {
            required : true
          },
          locationaddr : {
            required : true
          },
          citylocality : {
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
          vendor_id : {
            required : true,
            greaterThan: 0
          }
        },
        messages: {
          update_id : {
            required : "Update ID missing"
          },
          locationaddr : {
             required : "Enter address"
          },
          citylocality : {
            required : "Enter City"
          },
          pincode : {
            required : "Enter Pincode"
          },
          state : {
           required : "Please select state",
           greaterThan:  "Please select state"
          },
          vendor_id : {
           required : "Please select Vendor Name",
           greaterThan:  "Please select Vendor Name"
          }
        },
        submitHandler: function(form) 
        {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'employment/add_field_visit'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#add_field_visit').attr('disabled','disabled');
              },
              complete:function(){
               // $('#add_field_visit').removeAttr('disabled',false);
              },
              success: function(jdata){
                var message =  jdata.message || '';
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



  

  $.validator.addMethod("checkClearDate", function(value, element) {
      var startDate = $('#check_insuff_raise').val();
      var startDate = startDate.split("-").reverse().join("-");
      var value = value.split("-").reverse().join("-");
      return value >= startDate  || value == "";
  }, "Insuff cleared date can not be less than the raised date.");

  $('#frm_insuff_clear').validate({ 
      rules: {
        clear_update_id : {
          required : true
        },
        insuff_clear_date : {
          required : true,
          checkClearDate: true
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
            url : '<?php echo ADMIN_SITE_URL.'employment/insuff_clear'; ?>',
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
              //$('#btn_insuff_clear').removeAttr('disabled',false);
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


  $(document).on('click','.showAddResultModel',function() {

    var url = $(this).data('url');
 
    $('#append_result_model1').load(url,function(){
      $('#showAddResultModel').modal('show');
      $('#showAddResultModel').addClass("show");
      $('#showAddResultModel').css({background: "#0000004d"}); 
    });
   
});

$('#btn_update').click(function(){

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
      compant_contact : {
        number : true,
        minlength : 6
      },
      compant_contact_email : {
        email: true
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
      iniated_date :{
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
      empfrom : {
        required : "Enter Employment From"
      },
      empto : {
        required : "Enter Employment To"
      },
      iniated_date :{
         required : "Enter Initiate Date",
      }
      
    },
    submitHandler: function(form) 
    {      
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'employment/update_employment'; ?>',
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
          $("#frm_emp_update :input").prop("disabled", true);
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
  
  $('#addAddCompanyModelclk').click(function(){
    var id = '<?php echo ADMIN_SITE_URL.'company_database/addAddCompanyModel'?>';
    $('.append_model').load(id,function(){
      $('#addAddCompanyModel').modal('show');
      $('#addAddCompanyModel').addClass("show");
      $('#addAddCompanyModel').css({background: "#0000004d"}); 
    }); 
  });

   $('#addEditCompanyModelclk').click(function(){
    var id = "<?php echo ADMIN_SITE_URL.'Employment/edit_company/'.$empt_details["nameofthecompany"].'/'.str_replace(' ','||', $empt_details['deputed_company']) ?>";
    $('.append_edit_model').load(id,function(){
      $('#addEditCompanyModel').modal('show');
      $('#addEditCompanyModel').addClass("show");
      $('#addEditCompanyModel').css({background: "#0000004d"}); 
    }); 
  });

  $.ajax({
      type:'POST',
      url:'<?php echo ADMIN_SITE_URL.'employment/load_supervisor_details/dis/'; ?>'+counter,
      data:'details_fields='+$('#update_id').val()+'&hdn_counter'+$('#hdn_counter').val(),
      beforeSend :function(){
        //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
      },
      success:function(html)
      {
        jQuery('#supervisor_details').append(html);
      }
  });

 
   $('#view_activity_log_tabs').click(function(){
      $.ajax({
          type : 'POST',
          url : '<?php echo ADMIN_SITE_URL.'activity_log/activity_log_databale/'.$empt_details['id'].'/2'; ?>',
          data: '',
          beforeSend :function(){
            jQuery('#tbl_activity_log').html("Please wait...");
          },success:function(html) {
            jQuery('#tbl_activity_log').html(html);
          }
      });
  }).trigger('click');

   $('#view_result_log_tabs').click(function(){

    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'employment/employment_result_list/'.$empt_details['id'].''; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_employment_result').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
       
        if(jdata.status = 200)
        {
          $('#tbl_employment_result').html(message);
        }
        else {
          $('#tbl_employment_result').html(message);
        }
        var tbl_employment_result =  $('#tbl_employment_result').DataTable( {scrollX:true, "ordering": true,searching: false,bFilter: false,bLengthChange : true,bSortable: true,bRetrieve: true,"iDisplayLength": 5,"lengthChange": true,"lengthMenu": [[5,25, 50, -1], [5,25, 50, "All"]],"aaSorting": [] });

        tbl_employment_result.columns.adjust().draw();
      }
    }); 
  });


  
   $('#view_tab_vendor_log').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'employment/vendor_logs/'.$empt_details['id']; ?>',
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
  });


   $(document).on('click','.showvendorModel',function() {

    var url = $(this).data('url');

    var id = $(this).data('id');


    $('#append_vendor_model').load(url,function(){
      $('#showvendorModel').modal('show');
    

    });
   
});

 $(document).on('click','.showvendorModel_cost',function() {

    var url = $(this).data('url');

    $('#append_vendor_model_cost').load(url,function(){
      $('#showvendorModel_cost').modal('show');

    });
   
});

  $(document).on('click','.showvendorModel_cancel',function() {

    var url = $(this).data('url');

    $('#append_vendor_model_cancel').load(url,function(){
      $('#showvendorModel_cancel').modal('show');
    });
   
});

        jQuery.validator.addMethod("valDomain",function(nname)
                {
                        name = nname.replace('http://','');
      nname = nname.replace('https://','');

                        var arr = new Array(
                        '.com','.net','.org','.biz','.coop','.info','.museum','.name',
                        '.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag',
                        '.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw',
                        '.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm',
                        '.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc',
                        '.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr',
                        '.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz',
                        '.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm',
                        '.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm',
                        '.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm',
                        '.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq',
                        '.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki',
                        '.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li',
                        '.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg',
                        '.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt',
                        '.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng',
                        '.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf',
                        '.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py',
                        '.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg',
                        '.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv',
                        '.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn',
                        '.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um',
                        '.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws',
                        '.wf','.ye','.yt','.yu','.za','.zm','.zw','.capital');

                        var mai = nname;
                        var val = true;

                        var dot = mai.lastIndexOf(".");
                        var dname = mai.substring(0,dot);
                        var ext = mai.substring(dot,mai.length);
                        //alert(ext);
                                
                        if(dot>2 && dot<57)
                        {
                                for(var i=0; i<arr.length; i++)
                                {
                                  if(ext == arr[i])
                                  {
                                        val = true;
                                        break;
                                  }     
                                  else
                                  {
                                        val = false;
                                  }
                                }
                                if(val == false)
                                {
                                         return false;
                                }
                                else
                                {
                                        for(var j=0; j<dname.length; j++)
                                        {
                                          var dh = dname.charAt(j);
                                          var hh = dh.charCodeAt(0);
                                          if((hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==45 || hh==46)
                                          {
                                                 if((j==0 || j==dname.length-1) && hh == 45)    
                                                 {
                                                          return false;
                                                 }
                                          }
                                        else    {
                                                 return false;
                                          }
                                        }
                                }
                        }
                        else
                        {
                         return false;
                        }
                        return true;
                       

                }, 'Invalid domain name.');

  $('#empt_verificarion_result').validate({
    rules: {
      res_empfrom : {
        required : true
      },
      res_empto : {
        required : true
      },
      emp_designation : {
        required : true
      },
      res_empid : {
        required : true
      },
      res_reportingmanager : {
        required : true
      },
      res_reasonforleaving : {
        required : true
      },
      info_integrity_disciplinary_issue : {
        required : true
      },
      info_exitformalities : {
        required : true
      },
      info_eligforrehire : {
        required : true
      },
      integrity_disciplinary_issue : {

          required : function(){
          var  integrity_disciplinary_issue = $('#info_integrity_disciplinary_issue').val();
          if(integrity_disciplinary_issue == "yes" )
          {

            return true;
          }
          else
          {
             return false;
          }
         }
        },

       exitformalities : {

          required : function(){
          var  exitformalities = $('#info_exitformalities').val();
          if(exitformalities == "no" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },

        eligforrehire : {

          required : function(){
          var  exitformalities = $('#info_eligforrehire').val();
          if(exitformalities == "no" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },  
      verfname : {
        required : true
      },
      modeofverification : {
        required : true
      },
      verifiers_role : {
        required : true,
        greaterThan: 0
      },
      verifiers_contact_no : {
        required : true
      },
      verifiers_email_id : {
        email: true,
        required : true
      },
      verfdesgn : {
        required : true
      },
      justdialwebcheck : {
        required : true
      },
      mcaregn : {
        required : true
      },
      domainname : {
        required : true
      },
      res_remuneration : {
        required : true
      },
      
      closuredate : {
        required : true
      },
      remarks : {
        required : true
      },
      deputed_company_action : {
        required : true
      },
      company_action : {
        required : true
      },
      empfrom_action : {
        required : true
      },
       empto_action : {
        required : true
      },
      designation_action : {
          required : true
       },
      empid_action : {
          required : true
      },
      reportingmanager_action : {
        required : true
      },
       reasonforleaving_action : {
        required : true
      },
      remuneration_action : {
          required : true
       }

    },
    messages: {
      res_empfrom : {
        required : "Select Employed From"
      },
      res_empto : {
        required : "Select Employed To"
      },
      emp_designation : {
        required : "Select Designation"
      },
      res_empid : {
        required : "Enter Employee ID/Code"
      },
      res_reportingmanager : {
        required : "Enter Reporting Manager"
      },
      res_reasonforleaving : {
        required : "Enter Reason for Leaving"
      },
      info_integrity_disciplinary_issue : {
        required : "Enter Issues"
      },
      info_exitformalities : {
        required : "Select Exit Formalities Completed"
      },
     
      info_eligforrehire : {
        required : "Select Eligible for Rehire"
      },
      verfname : {
        required : "Enter Verifiers Name"
      },
      modeofverification : {
        required : "modeofverification"
      },
      verifiers_role : {
         required : "Select Role",
         greaterThan : "Select Role"
      },
      verifiers_contact_no : {
        required : "Enter Contact No"
      },
      verifiers_email_id : {
        email: "Enter Valid Email ID",
        required : "Enter Email ID"
      },
      verfdesgn : {
        required : "Enter Verifiers Designation"
      },
      justdialwebcheck : {
        required : "Select Web Status Check"
      },
      mcaregn : {
        required : "Select Register with MCA"
      },
      domainname : {
        required : "Enter Domain Name"
      },
      res_remuneration : {
        required : "Enter Remuneration"
      },
      
      closuredate : {
        required : "Select Closure Date"
      },
      remarks : {
        required : "Enter Remarks"
      },
       deputed_company_action : {
        required : "Enter Deputed Company"
      },
      company_action : {
        required : "Enter Company Name"
      },
      empfrom_action : {
         required : "Enter Employment From"
      },
       empto_action : {
        required : "Enter Employment To"
      },
      designation_action : {
          required : "Enter Employment Designation"
       },
      empid_action : {
          required : "Enter Employment Code"
      },
      reportingmanager_action : {
        required : "Enter Repoting Manager Name"
      },
       reasonforleaving_action : {
        required : "Enter Reason of Leaving"
      },
      remuneration_action : {
          required :  "Enter Remuneration"
       } 
    },
    submitHandler: function(form) 
    { 
      var activityData = $('#empt_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=2'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
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
      $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'employment/empt_verificarion_result'; ?>',
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
         // $('#sbresult').removeAttr('disabled');                
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
    }
  });
}); 




$('#add_verificarion_result_view').validate({
    rules: {
      res_empfrom : {
        required : true
      },
      res_empto : {
        required : true
      },
      emp_designation : {
        required : true
      },
      res_empid : {
        required : true
      },
      res_reportingmanager : {
        required : true
      },
      res_reasonforleaving : {
        required : true
      },
      info_integrity_disciplinary_issue : {
        required : true
      },
      info_exitformalities : {
        required : true
      },
      info_eligforrehire : {
        required : true
      },
      integrity_disciplinary_issue : {

          required : function(){
          var  integrity_disciplinary_issue = $('#info_integrity_disciplinary_issue').val();
          if(integrity_disciplinary_issue == "yes" )
          {

            return true;
          }
          else
          {
             return false;
          }
         }
        },

       exitformalities : {

          required : function(){
          var  exitformalities = $('#info_exitformalities').val();
          if(exitformalities == "no" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },

        eligforrehire : {

          required : function(){
          var  exitformalities = $('#info_eligforrehire').val();
          if(exitformalities == "no" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },  
      verfname : {
        required : true
      },
      modeofverification : {
        required : true
      },
      verifiers_role : {
        required : true,
        greaterThan: 0
      },
      verifiers_contact_no : {
        required : true
      },
      verifiers_email_id : {
        email: true,
        required : true
      },
      verfdesgn : {
        required : true
      },
      justdialwebcheck : {
        required : true
      },
      mcaregn : {
        required : true
      },
      domainname : {
        required : true
      },
      res_remuneration : {
        required : true
      },
      
      closuredate : {
        required : true
      },
      remarks : {
        required : true
      },
      deputed_company_action : {
        required : true
      },
      company_action : {
        required : true
      },
      empfrom_action : {
        required : true
      },
       empto_action : {
        required : true
      },
      designation_action : {
          required : true
       },
      empid_action : {
          required : true
      },
      reportingmanager_action : {
        required : true
      },
       reasonforleaving_action : {
        required : true
      },
      remuneration_action : {
          required : true
       }

    },
    messages: {
      res_empfrom : {
        required : "Select Employed From"
      },
      res_empto : {
        required : "Select Employed To"
      },
      emp_designation : {
        required : "Select Designation"
      },
      res_empid : {
        required : "Enter Employee ID/Code"
      },
      res_reportingmanager : {
        required : "Enter Reporting Manager"
      },
      res_reasonforleaving : {
        required : "Enter Reason for Leaving"
      },
      info_integrity_disciplinary_issue : {
        required : "Enter Issues"
      },
      info_exitformalities : {
        required : "Select Exit Formalities Completed"
      },
      info_eligforrehire : {
        required : "Select Eligible for Rehire"
      },
      verfname : {
        required : "Enter Verifiers Name"
      },
      modeofverification : {
        required : "modeofverification"
      },
      verifiers_role : {
         required : "Select Role",
         greaterThan : "Select Role"
      },
      verifiers_contact_no : {
        required : "Enter Contact No"
      },
      verifiers_email_id : {
        email: "Enter Valid Email ID",
        required : "Enter Email ID"
      },
      verfdesgn : {
        required : "Enter Verifiers Designation"
      },
      justdialwebcheck : {
        required : "Select Web Status Check"
      },
      mcaregn : {
        required : "Select Register with MCA"
      },
      domainname : {
        required : "Enter Domain Name"
      },
      res_remuneration : {
        required : "Enter Remuneration"
      },
      
      closuredate : {
        required : "Select Closure Date"
      },
      remarks : {
        required : "Enter Remarks"
      },
       deputed_company_action : {
        required : "Enter Deputed Company"
      },
      company_action : {
        required : "Enter Company Name"
      },
      empfrom_action : {
         required : "Enter Employment From"
      },
       empto_action : {
        required : "Enter Employment To"
      },
      designation_action : {
          required : "Enter Employment Designation"
       },
      empid_action : {
          required : "Enter Employment Code"
      },
      reportingmanager_action : {
        required : "Enter Repoting Manager Name"
      },
       reasonforleaving_action : {
        required : "Enter Reason of Leaving"
      },
      remuneration_action : {
          required :  "Enter Remuneration"
       } 
    },
    submitHandler: function(form) 
    { 
      
      var sortable_data = $("ul.sortable" ).sortable('serialize');
      var formData = new FormData(form);

      formData.append("sortable_data",sortable_data);
  
        $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'employment/empt_verificarion_ver_result'; ?>',
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
            window.location = jdata.redirect;
            return;
          }else{
            show_alert(message,'error');
          }
        }
      });  
    }
  });
   


$(document).on('click', '.add_supervisor_details_row', function(){

  counter = $('.total_count').length;
  if(counter != 0 && counter < 5)
  {
    counter++;
    $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'employment/load_supervisor_details/end/'; ?>'+counter,
          data:'',
          beforeSend :function(){
            //jQuery('#candsid').find("option:eq(0)").html("Please wait..");
          },
          success:function(html) {
            jQuery('#supervisor_details').append(html);
          }
    });
  } else {
    show_alert('Max 5 row can add','warning');
  }
}).trigger('click');

$(document).on('click', '.remove_supervisor_details_row', function(){
  counter = $(this).data('id');
  if(counter != 1){
    $('#counter_id_'+$(this).data('id')).remove();
    counter--;
  }else{
    show_alert("Can't Remove",'warning');
  }
}).trigger('click');

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
          url : "<?php echo ADMIN_SITE_URL.'employment/Save_vendor_details_cost'; ?>",
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
            //$('#vendor_result_submit_cost').removeAttr('disabled');                
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
          url : "<?php echo ADMIN_SITE_URL.'employment/Save_vendor_details_cancel'; ?>",
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
           // $('#vendor_result_submit_cancel').removeAttr('disabled');                
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
        
        var activityData =  new FormData($('#add_vendor_details_view')[0]);

        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'employment/Save_vendor_details'; ?>",
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