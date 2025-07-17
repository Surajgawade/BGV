<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Reference Verification</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>reference_verificatiion">Reference Verification</a></li>
                  <li class="breadcrumb-item active">Reference Verification Edit</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>reference_verificatiion"><i class="fa fa-arrow-left"></i> Back</button></li>  
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
            <li class="nav-item waves-effect waves-light active"><a class = 'nav-link active' href="#address_tab" data-toggle="tab">Reference Details</a></li>
            <li class="nav-item waves-effect waves-light view_component_tab" data-url="<?php echo ADMIN_SITE_URL.'candidates/ajax_view_component/'?>" data-can_id="<?php echo encrypt($get_cands_details['candsid']);?>" data-clients_id="<?php echo $get_cands_details['clientid'];?>" data-tab_name="candidate_tab" ><a href="#candidate_tab" class = 'nav-link' data-toggle="tab">Candidate Details</a></li>
          </ul>
          <div class="tab-content">
            <div class="active tab-pane" id="address_tab">
              <?php $this->load->view('admin/reference_edit'); ?> 
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
              <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#clk_insuff" id='clk_insuff_log' data-emp_id="<?php echo $selected_data['reference_id']; ?>" data-toggle="tab">Insufficiency</a></li>
             <!-- <li><a href="#address_log_tabs" id="view_insuff_log_tabs" data-toggle="tab">Reference Log</a></li>-->
              <li class="nav-item waves-effect waves-light"><a class = 'nav-link' href="#result_log_tabs" id="view_result_log_tabs" data-toggle="tab">Result Log</a></li>
            </ul>
            <br>
            <div class="tab-content">
              <div class="active tab-pane" id="activity_log_tabs">
                <div id="tbl_activity_log"></div>
              </div>
              <div class="tab-pane" id="clk_insuff">
                <div style="float: right;"><button class="btn btn-info btn-sm InsuffRaiseModel" data-editUrl='<?=($this->permission['access_reference_list_insuff_raise']) ? $insuff_raise_id : ''; ?>' <?php echo $check_insuff_raise;?>>Raise Insuff</button><br>
                </div>
                <div id='append_insuff_view'></div>
              </div>
              <div class="tab-pane" id="address_log_tabs">
                <span id="emp_log"></span>
                <table id="tbl_address_log" class="table table-bordered datatable_logs"></table>
              </div>
              <div class="tab-pane" id="result_log_tabs">

              <?php
              // if($reinitiated == "1")
              // {

                 $access_reverification = ($this->permission['access_reference_list_reverification'] == '1') ? 'ReInitiateModel'  : '';
              ?>
               <div style="float: right;"><button class="btn btn-info btn-sm <?php echo $access_reverification;?>" id="re-initiated">Reverify</button><br>
              </div>
               <?php
               //}
              ?>
              <div class="clearfix"></div>
                <table id="tbl_reference_result" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"></table>
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

<div id="ReInitiateModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_reinitiated','id'=>'frm_reinitiated')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Re-Initiated Component</h5>
      </div>
      <div class="modal-body">
         <input type="hidden" name="reference_id" value="<?php echo set_value('reference_id',$selected_data['reference_id']); ?>">
        <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$get_cands_details['candsid']); ?>">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
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
      <div class="modal-footer">
        <button type="submit" id="btn_submit_re_initited_date" name="btn_submit_re_initited_date" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
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
      <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$get_cands_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']);?>">

      <input type="hidden" name="reference_id" value="<?php echo set_value('reference_id',$selected_data['reference_id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
      <input type="hidden" name="reference_result_id" value="<?php echo set_value('reference_result_id',$selected_data['reference_result_id']); ?>">
      <input type="hidden" name="reference_com_ref" value="<?php echo set_value('reference_com_ref',$selected_data['reference_com_ref']); ?>">

      <input type="hidden" name="url_action" value="" id="url_action">

      <div id="append_result_model"></div>
      <input type="hidden" name="upload_capture_image_reference_result" id="upload_capture_image_reference_result" value=""> 

      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_reference_result">Copy Attachment</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="capture_image_copy_reference_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_reference_result','id'=>'frm_upload_copied_image_reference_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy marge to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-reference-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_reference_result" name="upload_copied_image_reference_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>



<div id="initiationModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Requesting Reference Verification of Candidate</h4>
      </div>
      <?php echo form_open('#', array('name'=>'send_reference_initial_mail','id'=>'send_reference_initial_mail')); ?>
      <div class="modal-body">
    <div align="right">
        <button type="submit" id="send_mail_frm" name="send_mail_frm" class="btn btn-success"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>

      </br>

        <div class="row">
        <input type="hidden" name="ref_user_id" value="<?php echo set_value('ref_user_id',$selected_data['id']); ?>">
         
      
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
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "Reference verification of ".ucwords($selected_data['CandidateName'])." - ".$selected_data['cmp_ref_no']?>" readonly>
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
             

              $url  = SITE_URL.REFERENCES.$selected_data['clientid'].'/';
              if($value['type'] == 0)
              {
                echo '<tr>'; 
              ?>

               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>Data Entry</td>
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
  <div class="modal-dialog modal-xl" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Summary Mail</h4>
      </div>
      <?php echo form_open('#', array('name'=>'frm_summery_mail','id'=>'frm_summery_mail')); ?>
      <div class="modal-body">
    <div align="right">
        <button type="submit" id="btn_summary_mail" name="btn_summary_mail" class="btn btn-success btn_summary_mail"> Send</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>

      </br>

        <div class="row">
        <input type="hidden" name="ref_user_id" value="<?php echo set_value('ref_user_id',$selected_data['id']); ?>">
         
      
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
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type email" value="<?php  echo "Reference verification summary of ".ucwords($selected_data['CandidateName'])." - ".$selected_data['cmp_ref_no']?>" readonly>
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
             

              $url  = SITE_URL.REFERENCES.$selected_data['clientid'].'/';
              if($value['type'] == 0)
              {
                echo '<tr>'; 
              ?>

               <td style='border: 1px solid black;padding: 8px;'><a style="color: white;" href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); return false'><?= $value['file_name']?></a></td>
               <td style='border: 1px solid black;padding: 8px;'>Data Entry</td>
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

            } 
            ?>

          </tr>
        </table>


            
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

<div id="showAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result_view','id'=>'add_verificarion_result_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title">Result Log Details</h5>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$get_cands_details['candsid']); ?>">
      <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>">
      <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']);?>">

      <input type="hidden" name="reference_id" value="<?php echo set_value('reference_id',$selected_data['reference_id']); ?>">
      <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
      <input type="hidden" name="reference_result_id" value="<?php echo set_value('reference_result_id',$selected_data['reference_result_id']); ?>">
      <input type="hidden" name="reference_com_ref" value="<?php echo set_value('reference_com_ref',$selected_data['reference_com_ref']); ?>">

      <input type="hidden" name="url_action" value="" id="url_action">
      <div id="append_result_model1"></div>

      <input type="hidden" name="upload_capture_image_reference_ver_result" id="upload_capture_image_reference_ver_result" value=""> 

      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#capture_image_copy_reference_ver_result">Copy Attachment</button>
        <button type="submit" id="sblogresult" name="sblogresult" class="btn btn-success btn-sm">Submit</button>

        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="capture_image_copy_reference_ver_result" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_upload_copied_image_reference_ver_result','id'=>'frm_upload_copied_image_reference_ver_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Copy mage to Clipboard</h4>
      </div>
      <input type="hidden" id="copy" name="copy">
      <div class="status">
        <p id="errorMsg" class="error"></p>
      </div>
      <div contenteditable="true" class="image-content-result-reference-ver-result"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="upload_copied_image_reference_ver_result" name="upload_copied_image_reference_ver_result">Upload</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="4">
            <input type="hidden" name="acti_candsid" value="<?php echo set_value('acti_candsid',$get_cands_details['candsid']); ?>">
            <input type="hidden" name="comp_table_id" value="<?php echo set_value('comp_table_id',$selected_data['reference_id']); ?>">
            <input type="hidden" name="ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber',$get_cands_details['ClientRefNumber']); ?>">

            <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$selected_data['reference_com_ref']); ?>">

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
        <input type="hidden" name="clear_clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">
        <input type="hidden" name="insuff_clear_id" id="insuff_clear_id" value="">
        <input type="hidden" name="clear_update_id" id="clear_update_id" value="<?php echo set_value('clear_update_id',$selected_data['reference_id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$selected_data['cands_id']); ?>">

        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$selected_data['reference_com_ref']); ?>">

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
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Attachment<span class="error">(jpeg,jpg,png,pdf files only)</span></label>
          <input type="file" name="clear_attchments[]" multiple id="clear_attchments" class="form-control"><?php echo set_value('clear_attchments'); ?>
          <?php echo form_error('clear_attchments'); ?>
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
            <input type="hidden" name="fq_candsid" value="<?php echo set_value('fq_candsid',$get_cands_details['candsid']); ?>">
            <input type="hidden" name="pccver_id" id="pccver_id" value="<?php echo set_value('pccver_id',$selected_data['reference_id']); ?>">
            <input type="hidden" name="fq_reference_result_id" id="fq_reference_result_id" value="<?php echo set_value('fq_reference_result_id',$selected_data['reference_result_id']); ?>">
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
        <input type="hidden" name="insuff_edit" id="insuff_edit" value="">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$selected_data['reference_id']); ?>">

        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$selected_data['cands_id']); ?>">

        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$selected_data['reference_com_ref']); ?>">
        <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">
        <input type="hidden" name="entity_id" value="<?php echo set_value('entity_id',$get_cands_details['entity_id']); ?>">
        <input type="hidden" name="package_id" value="<?php echo set_value('package_id',$get_cands_details['package_id']);?>">
        <input type="hidden" name="clientid" value="<?php echo set_value('clientid',$get_cands_details['clientid']); ?>">  
      <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise" id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',$this->insuff_date); ?>" class="form-control myDatepicker1" placeholder='DD-MM-YYYY'>
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
      <div class="modal-footer">
        <button type="submit" id="btn_submit_insuff" name="btn_submit_insuff" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
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
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$selected_data['reference_id']); ?>">
        <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$selected_data['cands_id']); ?>">
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
.image-content-result-reference-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-reference-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-reference-result:not(:empty)::after,
.image-content-result-reference:focus::after {
  display: none;
}

.image-content-result-reference-result * {
  max-width: 100%;
  border: 0;
}

.image-content-result-reference-ver-result {
  width: 100%;
  height: 400px;
  border: 1px dotted grey;
  overflow-y: auto;
  margin: auto;
  position: relative;
  padding: 2px 4px;
  cursor: copy;
}

.image-content-result-reference-ver-result::after {
  position: absolute;
  content: 'Paste here';
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: rgb(145, 145, 145);
  cursor: copy;
}

.image-content-result-reference-ver-result:not(:empty)::after,
.image-content-result-reference:focus::after {
  display: none;
}

.image-content-result-reference-ver-result * {
  max-width: 100%;
  border: 0;
}
</style>

<script>

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

   $('.myDatepicker1').datepicker({
      daysOfWeekDisabled: [0,6],
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true,

      
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
$(document).on('click','.InsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');

  var access_insuff = <?=($this->permission['access_reference_list_insuff_raise']) ? 1 : 2 ; ?>;

  if(access_insuff == 1)
  {
    $('#insuffRaiseModel').modal('show');
    $('#insuffRaiseModel').addClass("show");
    $('#insuffRaiseModel').css({background: "#0000004d"});
    
  } else { 
    show_alert('Access Denied, You don’t have permission to access this page');
  }  
});

$(document).on('click', '#clk_insuff_log', function(){
  var emp_id = $(this).data('emp_id');
  if(emp_id != 0)
  {
      $.ajax({
          type:'GET',
          url:'<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_tab_view/'; ?>'+emp_id,
          beforeSend :function(){
            jQuery('#append_insuff_view').html("Please wait...");
          },success:function(html) {
            jQuery('#append_insuff_view').html(html);
          }
      });
  } 
});
 
$(document).on('change', '.verfstatus,.verfstatusup', function(){
  verfstatus = $("select.verfstatus option:selected").text();
  onChangeStatus(verfstatus);   
});

$(document).on('click','.clkInsuffRaiseModel',function() {
  var insuff_data = $(this).data('editurl');
  if(insuff_data != "")
  {
    var clientname = $(this).text();
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_edit_clear_raised_data/'; ?>',
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
        url:'<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_raised_data/'; ?>',
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
  var cands_id = <?php echo $selected_data['cands_id']; ?>;
  if(insuff_data != "")
  {
    $.ajax({
        type:'POST',
        url:'<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_delete'; ?>',
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
/*$(document).on('click','#addFirstQCModelclk',function() {
  if("<?= ($this->permission['access_first_QC_approve'])?>") {
    $('#firstQCModelclk').modal('show');
  } else {
    show_alert('Access Denied, You don’t have permission to access this page','error');
  }
});*/

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

$(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var rtb_val = $(this).val();
    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
    } else if(rtb_val == 'not-disclosed') {
      $("#"+txt_val).val("Not Disclosed");
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
          url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/reference_reinitiated_date'; ?>',
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
 


$(document).ready(function(){

  if('<?=!empty($check_insuff_raise)?>') {
    show_alert('<?= $insuff_raise_message?>')
  }

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
          url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/update_insuff_details'; ?>',
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
          url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_raised'; ?>',
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
          //  $('#btn_submit_insuff').removeAttr('disabled',false);
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
          url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/insuff_clear'; ?>',
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
         //   $('#btn_insuff_clear').removeAttr('disabled',false);
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

  
  $('#view_insuff_log_tabs').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/address_logs/'.$selected_data['reference_id']; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_address_log').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
        if(jdata.status = 200)
        {
          var techTable = '';
          techTable = "<thead>";
          techTable += "<tr>";
          techTable += "<th>Modefied On</th>";
          techTable += "<th>Modefied By</th>";
          techTable += "<th>EMP ID</th>";
          techTable += "<th>Company Address</th>";
          techTable += "</tr>";
          techTable += "</thead>";
          techTable += message;
          $('#tbl_address_log').html(techTable);
          var emp_log_tbl =  $('#tbl_address_log').DataTable( { "ordering": true,bSortable: true,bRetrieve: true,"iDisplayLength": 25, });
        }
        else
        {
          $('#tbl_address_log').html(message);
        }
      }
    }); 
  });

  $('#view_result_log_tabs').click(function(){
    $.ajax({
      type : 'POST',
      url : '<?php echo ADMIN_SITE_URL.'reference_verificatiion/reference_result_list/'.$selected_data['reference_id']; ?>',
      data: '',
      beforeSend :function(){
        jQuery('#tbl_reference_result').html("Loading..");
      },
      success:function(jdata)
      {
        var message = jdata.message;
          if(jdata.status = 200)
        {
          $('#tbl_reference_result').html(message);
        }
        else {
          $('#tbl_reference_result').html(message);
        }
        
        var tbl_reference_result =  $('#tbl_reference_result').DataTable( {scrollX:true,"ordering": true,searching: false,bFilter: false,bLengthChange : false,bSortable: true,bRetrieve: true,"iDisplayLength": 25 });

        tbl_reference_result.columns.adjust().draw();
      }
    }); 
  });

  $('#view_activity_log_tabs').click(function(){
      $.ajax({
        type : 'POST',
        url : '<?php echo ADMIN_SITE_URL.'activity_log/activity_log_databale/'.$selected_data['reference_id'].'/4'; ?>',
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
        reference_result_id : {
          required : 'ID'
        },
        handle_pressure_value : {
          required :  true
        },
         attendance_value : {
          required :  true
        },
         integrity_value : {
          required :  true
        },
         leadership_skills_value : {
          required :  true
        },
         responsibilities_value : {
          required :  true
        },
         achievements_value : {
          required :  true
        },
         strengths_value : {
          required :  true
        },
        weakness_value : {
          required :  true
        },
        team_player_value : {
          required :  true
        },
         overall_performance : {
          required :  true
        },
         additional_comments : {

          required : function(){
          var  overall_performance = $('#overall_performance').val();
          if(overall_performance <= "4" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },
        mode_of_verification : {
          required :  true
        },
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        }
      },
      messages: {
        closuredate : {
          required : "Select Closure Date"
        },
         handle_pressure_value : {
          required : 'Enter Ability to handle pressure'
        },
        attendance_value : {
          required :  'Enter Attancance Value'
        },
        
        mode_of_verification : {
          required :  'Please select Mode of Verification'
        },
        remarks : {
          required : "Enter Remarks"
        } 
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=4'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
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
        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'reference_verificatiion/add_verificarion_result'; ?>',
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
        reference_result_id : {
          required : 'ID'
        },
        handle_pressure_value : {
          required :  true
        },
         attendance_value : {
          required :  true
        },
         integrity_value : {
          required :  true
        },
         leadership_skills_value : {
          required :  true
        },
         responsibilities_value : {
          required :  true
        },
         achievements_value : {
          required :  true
        },
         strengths_value : {
          required :  true
        },
        weakness_value : {
          required :  true
        },
        team_player_value : {
          required :  true
        },
         overall_performance : {
          required :  true
        },
         additional_comments : {

          required : function(){
          var  overall_performance = $('#overall_performance').val();
          if(overall_performance <= "4" )
          {
            return true;
          }
          else
          {
             return false;
          }
         }
        },
        mode_of_verification : {
          required :  true
        },
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        }
      },
      messages: {
        closuredate : {
          required : "Select Closure Date"
        },
         handle_pressure_value : {
          required : 'Enter Ability to handle pressure'
        },
        attendance_value : {
          required :  'Enter Attancance Value'
        },
        
        mode_of_verification : {
          required :  'Please select Mode of Verification'
        },
        remarks : {
          required : "Enter Remarks"
        } 
      },
      submitHandler: function(form) 
      { 
        
        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'reference_verificatiion/add_verificarion_ver_result'; ?>',
          data:  new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sblogresult').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#sblogresult').removeAttr('disabled');                
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
    
});


  $('#send_reference_initial_mail').validate({
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
        url : "<?php echo ADMIN_SITE_URL.'reference_verificatiion/ref_initial_send_mail'; ?>",
        data : $('#send_reference_initial_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#send_mail_frm').val('Sending...');
          $('#send_mail_frm').attr('disabled','disabled');
        },
        complete:function(){
          //$('#send_mail_frm').removeAttr('disabled');
          $('#send_mail_frm').val('Send');                
        },
        success: function(jdata){
          $('#initiationModel').modal('hide');
          $('#send_reference_initial_mail')[0].reset();
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
        url : "<?php echo ADMIN_SITE_URL.'reference_verificatiion/reference_send_summary_mail'; ?>",
        data : $('#frm_summery_mail').serialize(),
        type: 'post',
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_summary_mail').val('Sending...');
          $('#btn_summary_mail').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_summary_mail').removeAttr('disabled');
          $('#btn_summary_mail').val('Send');                
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

    $('#upload_copied_image_reference_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_reference_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-reference-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_reference_result').val(get_image_base);
          $('#capture_image_copy_reference_result').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });
  
   $('#upload_copied_image_reference_ver_result').on('click',function() {
      var get_image_name =  $('#upload_capture_image_reference_ver_result').val();
      var get_image_base_value = document.querySelectorAll('.image-content-result-reference-ver-result img')[0].src
       
      if( get_image_name == '' ) {
        var get_image_base  = get_image_base_value;
      }
      else{
        var get_image_base  = get_image_name+'||'+get_image_base_value;
      }
      if(get_image_base_value) {
          $('#upload_capture_image_reference_ver_result').val(get_image_base);
          $('#capture_image_copy_reference_ver_result').modal('hide');
          show_alert('Image copied success', 'success');
      }
  });   

</script>