<style type="text/css">
  td.details-control {
    background: url('http://3.109.13.110/mist-it/assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('http://3.109.13.110/mist-it/assets/images/details_close.png') no-repeat center center;
}

.add_plus_minus
{
     background: url('http://3.109.13.110/mist-it/assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}

div.both{
  display: flex;
}

</style>
<div class="content-page">
  <div class="content">
    <div class="container-fluid">
    
    <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Education - Education AQ</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>education">Education</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>education/approval_queue">Education AQ</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
            
            </div>
          </div>
    </div>
      
    <div class="nav-tabs-custom">
        <ul class="nav nav-pills nav-justified">
          <?php
            echo "<li class='nav-item waves-effect waves-light view_component_tab active' role='presentation' data-url='".ADMIN_SITE_URL."education/education_stamp_verifiers/' data-can_id='".$this->user_info['id']."'><a class = 'nav-link active' href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Verifier's Queue</a></li>";

            echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."education/education_spoc_verifiers/' data-can_id='".$this->user_info['id']."' data-tab_name='spoc_verifiers' ><a class = 'nav-link' href='#spoc_verifiers' aria-controls='home' data-toggle='tab'>Spoc Queue</a></li>";

            echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."education/education_stamp_verifiers/' data-can_id='".$this->user_info['id']."'   data-tab_name='stamp_verifiers' ><a class = 'nav-link' href='#stamp_verifiers' aria-controls='home' data-toggle='tab'>Vendor Queue</a></li>";

            echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."education/education_stamp_verifiers_pending_queue/' data-can_id='".$this->user_info['id']."'   data-tab_name='stamp_verifiers_queue' ><a class = 'nav-link' href='#stamp_verifiers_queue' aria-controls='home' data-toggle='tab'>Verifiers Vendor Queue</a></li>";

          
            echo "<li class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."education/education_closure_verifiers_stamp/' data-can_id='".$this->user_info['id']."'   data-tab_name='closure_stamp_verifiers'><a class = 'nav-link' href='#closure_stamp_verifiers' aria-controls='home' data-toggle='tab'>Closure Queue</a></li>";

            echo "<li  class='nav-item waves-effect waves-light view_component_tab' role='presentation' data-url='".ADMIN_SITE_URL."education/education_closure_entries_vendor_insuff/' data-can_id='".$this->user_info['id']."'  data-tab_name='vendor_insuff_tab'><a class = 'nav-link' href='#vendor_insuff_tab' aria-controls='home' role='tab'  data-toggle='tab'>Vendor Insuff</a></li>";
  

            // echo "<li class='nav-item waves-effect waves-light active' role='presentation'><a class = 'nav-link active' href='#Vendor_assign_reject' aria-controls='home' data-toggle='tab'>Vendor Assign/ Reject</a></li>";
  
            
            // echo "<li  role='presentation' data-url='".ADMIN_SITE_URL."education/education_closure_entries/' data-can_id='".$this->user_info['id']."'   data-tab_name='result_log_tabs'  class='nav-item waves-effect waves-light view_component_tab'><a class = 'nav-link' href='#result_log_tabs' aria-controls='home' role='tab'  data-toggle='tab'>Closure</a></li>";

            // 
           ?>                       
       </ul>
    </div>    
   
     

  <div class="tab-content">
    <div id="Vendor_assign_reject" class="tab-pane active">
      <div class="row">
        <div class="col-12">
         <div class="card m-b-20">
            <div class="card-body">
             <div class="both">  
              
                  <form method="post" action="<?php echo ADMIN_SITE_URL.'education/export_education_aq_doc'?>">
                    <input type="hidden" name="coomponent_check_id" id="coomponent_check_id" class="form-control">
                    <button class="btn btn-secondary waves-effect" id="export_education_cases_doc" data-toggle="modal" data-target="#myModalExport" ><i class="fa fa-download"></i>  Export Doc </button>

                  </form>
                    &nbsp;
                
                  <button class="btn btn-secondary waves-effect" id="assign_bulk_to_modal" data-toggle="modal" data-target="#myModalSpoc" style = "height:35px;">Assign Spoc </button>

                  &nbsp;
                
                  <button class="btn btn-secondary waves-effect" id="assign_bulk_to_modal_stamp" data-toggle="modal" data-target="#myModalStamp" style = "height:35px;">Assign Stamp </button>
              
                <?php

                $verification_status = array('' => 'Select','1' => 'Assign to Spoc');

                echo '<div class="col-sm-3 form-group">';
                echo form_dropdown('assgin_spoc',$verification_status, set_value('assgin_spoc'), 'class="custom-select" id="assgin_spoc"');
                echo "</div>";
                    
               ?>
               </div>
              <br>
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr >
                    <th>#</th>
                    <th>#</th>
                    <th>Recvd Date</th>
                    <th>Comp Ref</th>
                    <th>Candidate Name</th>
                    <th>University Name</th>
                    <th>Qualification</th>
                    <th>Action</th>
                    <th>URL</th>
                    <th>Client Name</th>
                    <th>Mode of verification</th>
                  </tr>
                </thead>
                <tbody>
                  
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
        
      <div id="spoc_verifiers" class="tab-pane fade in">
      
      </div>  

      <div id="stamp_verifiers" class="tab-pane fade in">
  
      </div>



      <div id="closure_stamp_verifiers" class="tab-pane fade in">
    
      </div>

      <div id="stamp_verifiers_queue" class="tab-pane fade in">
  
      </div>


      <div id="result_log_tabs" class="tab-pane fade in">
    
     </div>

      <div id="vendor_insuff_tab" class="tab-pane fade in">
        
     </div>

     </div>
   
</div>
</div>
</div>

  
<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'frm_verificarion_result','id'=>'frm_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" id="candidates_info_id" name="candidates_info_id" value="<?php echo set_value('candidates_info_id'); ?>">
      <input type="hidden" id="entity_id" name="entity_id" value="<?php echo set_value('entity_id'); ?>">
      <input type="hidden" id="package_id" name="package_id" value="<?php echo set_value('package_id'); ?>">
      <input type="hidden" id="education_id" name="education_id" value="<?php echo set_value('education_id'); ?>">
      <input type="hidden" id="clientid"  name="clientid" value="<?php echo set_value('clientid'); ?>">
      <input type="hidden" id="education_result_id" name="education_result_id" value="<?php echo set_value('education_result_id'); ?>">
      <input type="hidden" name="university_images"  id="university_images" value="">


      <div id="append_result_model"></div>
      <div class="modal-footer">
        <button  type="button" class="btn btn-info btn-sm" id="university_image" data-url="<?= ADMIN_SITE_URL.'education/university_image_selection_idwise' ?>">University Image</button>

        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="showvendorModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view','id'=>'add_vendor_details_view')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
      <input type="hidden" name="education_id" id = "education_id"  class = "education_id" value="">
      <input type="hidden" name="fin_status"  id = "fin_status"  class="fin_status" value="">
      <input type="hidden" name="closure_id"  id = "closure_id"  class="closure_id" value="">
       
      <div class="modal-body"> 
      <span class="errorTxt"></span>
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

        <button  class="btn btn-success btn-sm approve_reject_id" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_vendor_form/5/' ?>" data-toggle="modal" id="activityModelClk1" type="button" >Approve</button>
      <button  class="btn btn-danger btn-sm approve_reject_id" data-toggle="modal" data-target="#reject_value"  type="button" >Reject</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

     </div>

      </div>
    </div>
    <?php echo form_close(); ?>
     
  </div>
</div>

<div id="reject_value" class="modal fade" role="dialog" style="z-index: 2000;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
        
       <input type="text" name="reject_reason_closure" maxlength="200" id="reject_reason_closure" placeholder="Reason" class="form-control">
       <br>

        <button type="button" id="reject" name="reject" class="btn btn-danger btn-sm" value = '2'>Reject</button>
        
      </div>      
    </div>
  </div>
</div>

<div id="UniversityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
  <?php echo form_open('#', array('name'=>'frm_submit_university_images','id'=>'frm_submit_university_images')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">University Images</h4>
      </div>
     <div class="modal-body">
        <div class="append-university_display"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="submit_university_images" name="submit_university_images">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!--<div id="showvendorModelInsuff" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">

    <?php echo form_open_multipart("#", array('name'=>'add_vendor_details_view_insuff','id'=>'add_vendor_details_view_insuff')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Vendor Details</h4>
      </div>
    

      <span class="errorTxt"></span>
      <div id="append_vendor_model_insuff"></div>
     <div class="clearfix"></div>
      

      <div class="modal-footer">
        <button type="button" id="vendor_details_back" name="vendor_details_back" class="btn btn-default btn-sm pull-left">Back</button>

        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

      </div>
    </div>
    <?php echo form_close(); ?>
     
  </div>
</div>-->
<div id="insuffRaiseModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_insuff_raise','id'=>'frm_insuff_raise')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Raise Insuff</h4>
      </div>
      <div class="modal-body">

          <div id="append_vendor_model_insuff"></div>

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


<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="3">
            <input type="hidden" name="acti_candsid" id = "acti_candsid" value="<?php echo set_value('acti_candsid'); ?>">

            <input type="hidden" name="comp_table_id"  id = "comp_table_id" value="<?php echo set_value('comp_table_id'); ?>">
            <input type="hidden" name="ac_ClientRefNumber" id = "ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber'); ?>">

            <input type="hidden" name="CandidateName"  id="CandidateName1" value="<?php echo set_value('CandidateName'); ?>" class="form-control">
            <input type="hidden" name="component_ref_no" id="component_ref_no1" value="<?php echo set_value('component_ref_no'); ?>">
            
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

<div id="showverifierModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_verifiers_details','id'=>'frm_verifiers_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Verification Result</h4>
      </div>
      <div class="modal-body">
          
        <div class="row">
        
           <input type="hidden" name="verifiers_stamp_id" id="verifiers_stamp_id" value=""> 
           <input type="hidden" name="education_case_id_stamp" id="education_case_id_stamp" value="">
           <input type="hidden" name="mode_veri" id="mode_veri" value=""> 
           
          <div class="col-sm-6 form-group">
             <label>Verifier's Status</label>
            <?php
              echo form_dropdown('cases_assgin', $assigned_option, set_value('cases_assgin'), 'class="select2" id="cases_assgin"');?>
          </div>
          <div class="col-sm-6 form-group reject_reason" style="display: none;">
            <label>Stamp vendor</label>
            <?php  
              $vendor_list['no'] = "No";
               echo form_dropdown('vendor_list_stamp',$vendor_list, set_value('vendor_list_stamp'), 'class="select2" id="vendor_list_stamp" required="required" '); ?>
          </div> 
        </div>
        <div class="row">  
          <div class="col-sm-6 form-group stamp_attachament" style="display: none;">
            <label>Result (Attachment)</label>
             <input type="file" name="attchments_verifiers[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments_verifiers" value="<?php echo set_value('attchments_verifiers'); ?>" class="form-control">
          </div>
          <div class="col-sm-6 form-group verification_remark"> 
              <label>Remarks</label>
              <textarea name="remark_verifiers_stamp" rows="1" id="reamark_verifiers_stamp" class="form-control"><?php echo set_value('reamark_verifiers_stamp'); ?></textarea>
          </div>
        </div>
         
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_verifier_details" name="btn_submit_verifier_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="showURLModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_url_details','id'=>'frm_url_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add URL</h4>
      </div>
      <div class="modal-body">
          
        <div class="row">
        
           <input type="hidden" name="educ_id" id="educ_id" value=""> 
           <input type="hidden" name="educ_case_id" id="educ_case_id" value="">
          <div class="col-sm-6 form-group"> 
              <label>URL  <span class= "error"> * </span></label>
              <input type="text" name="education_url"  id="education_url" class="form-control">
          </div>
        </div>

      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_url_details" name="btn_submit_url_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


<div id="showURLModelSPOC" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_url_details_spoc','id'=>'frm_url_details_spoc')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">URL Details</h4>
      </div>
      <div class="modal-body">
          
        <div class="row">
      
          <div class="col-sm-12 form-group"> 
              <label>URL  <span class= "error"> * </span></label>
              <textarea type="text" name="education_url_spoc"  id="education_url_spoc" class="form-control"></textarea> 
          </div>
        </div>

      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
    
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="showstampModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_stamp_details','id'=>'frm_stamp_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Stamp Details</h4>
      </div>
      <div class="modal-body">
          
        <input type="hidden" name="stamp_id" id="stamp_id" value="">  
        <div class="row"> 

          <div class="col-sm-6 form-group">
             <label>Verifier's Status</label>
                <input type="text" name="cases_assgin_stamp" id="cases_assgin_stamp" class="form-control" readonly> 
          </div>
          
          <div class="col-sm-6 form-group">
            <label>Result (Attachment)</label>
             <input type="file" name="attchments_stamp[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments_stamp" value="<?php echo set_value('attchments_stamp'); ?>" class="form-control">
          </div>
        </div>
         
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_stamp_details" name="btn_submit_stamp_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="showspocModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_spoc_details','id'=>'frm_spoc_details')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Spoc Details</h4>
      </div>
      <div class="modal-body">
          
        <div class="row">
        
           <input type="hidden" name="verifiers_spoc_id" id="verifiers_spoc_id" value=""> 
           <input type="hidden" name="education_case_id_spoc" id="education_case_id_spoc" value="">
           <input type="hidden" name="mode_veri_spoc" id="mode_veri_spoc" value="">  
           
          <div class="col-sm-6 form-group">
             <label>Spoc's Status</label>
            <?php
              echo form_dropdown('cases_assgin_spoc', $assigned_option, set_value('cases_assgin_spoc'), 'class="custom-select" id="cases_assgin_spoc"');?>
          </div>
          <div class="col-sm-6 form-group reject_reason_spoc" style="display: none;">
            <label>Stamp vendor</label>
            <?php  
              $vendor_list['no'] = "No";
               echo form_dropdown('vendor_list_spoc',$vendor_list, set_value('vendor_list_spoc'), 'class="select2" id="vendor_list_spoc" required="required" '); ?>
          </div> 
        </div>
        <div class="row">  
          <div class="col-sm-6 form-group spoc_attachament" style="display: none;">
            <label>Result (Attachment)</label>
             <input type="file" name="attchments_spoc[]" accept=".png, .jpg, .jpeg,.pdf" multiple="multiple" id="attchments_spoc" value="<?php echo set_value('attchments_spoc'); ?>" class="form-control">
          </div>
          <div class="col-sm-6 form-group spoc_remarks"> 
              <label>Remarks</label>
              <textarea name="remark_verifiers_spoc" rows="1" id="reamark_verifiers_spoc" class="form-control"><?php echo set_value('reamark_verifiers_spoc'); ?></textarea>
          </div>
        </div>
         
      </div>
      <div class="clearfix"></div>
      <div class="modal-footer">
        <button type="submit" id="btn_submit_spoc_details" name="btn_submit_spoc_details" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModalCasesAssignSpoc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Spoc</h4>
      </div>
        <div class="modal-body">
          <div id = "vendor_details_list_spoc"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_spoc">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<div id="myModalSpoc" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Spoc</h4>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 form-group"> 
              <label>Insert Ref No</label>
              <textarea name="case_assign_to_spoc_bulk" id="case_assign_to_spoc_bulk" rows="2" class="form-control"></textarea>
            </div>
            <div class="col-sm-12 form-group"> 
              <label>Select Spoc Vendor</label>
              <?php  
               echo form_dropdown('vendor_list_spoc_bulk',$vendor_spoc_list, set_value('vendor_list_spoc_bulk'), 'class="form-control" id="vendor_list_spoc_bulk" required="required" '); ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_spoc_bulk">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<div id="myModalStamp" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Assign Cases to Stamp</h4>
      </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 form-group"> 
              <label>Insert Ref No</label>
              <textarea name="case_assign_to_stamp_bulk" id="case_assign_to_stamp_bulk" rows="2" class="form-control"></textarea>
            </div>
          </div>
          
          <div class="row">
            <div class="col-sm-6 form-group">
             <label>Stamp Status</label>
               <?php
                echo form_dropdown('cases_assgin_stamp_bulk', $assigned_option, set_value('cases_assgin_stamp_bulk'), 'class="select2" id="cases_assgin_stamp_bulk"');?>
            </div>

            <div class="col-sm-6 form-group reject_reason_bulk" style="display: none;"> 
              <label>Select Stamp Vendor</label>
              <?php  
               echo form_dropdown('vendor_list_stamp_bulk',$vendor_stamp_list, set_value('vendor_list_stamp_bulk'), 'class="form-control" id="vendor_list_stamp_bulk" required="required" '); ?>
            </div>

            <div class="col-sm-6 form-group"> 
              <label>Remarks</label>
              <textarea name="remark_verifiers_stamp_bulk" rows="1" id="remark_verifiers_stamp_bulk" class="form-control"><?php echo set_value('remark_verifiers_stamp_bulk'); ?></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_assign_action_stamp_bulk">Assign</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>

<script src="<?php echo SITE_JS_URL;?>datatables.pipeline.js"></script>

<script>
  var $ = jQuery;
var select_one = '';


$(document).ready(function() {

  var oTable =  $('#tbl_datatable').DataTable( {

        "serverSide": true,
        "processing": true,
        bSortable: true,
        bRetrieve: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
        "iDisplayLength": 25, // per page
        "language": {
          "emptyTable": "No Record Found",
         "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>ajax-loader.gif' style='width: 100px;'>"
        },

        "ajax": $.fn.dataTable.pipeline( {
            url: "<?php echo ADMIN_SITE_URL.'education/view_approval_queue'; ?>",
            pages: 1, // number of pages to cache
            async: true,
            method: 'POST',
            data: { }
        } ),
        order: [[ 2, 'asc' ]],
        "aLengthMenu": [[10, 25, 50,100000000000000000], [10, 25, 50,'All']],

          'columnDefs': [
             {
                'targets': 0,
                'checkboxes': {
                   'selectRow': true
                }
             }
          ],
          'select': {
             'style': 'multi'
          },
       
        "columns":[{"data":"checkbox"},{
                "class":          'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },{"data":"allocated_on"},{"data":"education_com_ref"},{"data":"CandidateName"},{'data' :'university_board'},{'data' :'qualification'},{"data":"action"},{"data":"url_link"},{"data":"clientname"},{'data' :'mode_of_verification'}]
    });

    $('#tbl_datatable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).parents('tr');
        var row = oTable.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
             if ( oTable.row( '.shown' ).length ) {
              $('.details-control', oTable.row( '.shown' ).node()).click();
            }
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

  function format ( d ) {

  var expand_data = '';

   expand_data += '<table  class ="child-table" cellpadding="5"  cellspacing="0" border="1"  style="padding-left:50px;margin-left:60px;"><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Client Id : </b></td><td style="border: 1px solid black">'+d.ClientRefNumber+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>DOB : </b></td><td style="border: 1px solid black">'+d.DateofBirth+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Fathers Name :</b></td><td style="border: 1px solid black">'+d.NameofCandidateFather+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Mothers Name :</b></td><td style="border: 1px solid black">'+d.MothersName+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>School/College :</b></td><td style="border: 1px solid black">'+d.school_college+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Roll No :</b></td><td style="border: 1px solid black">'+d.roll_no+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Enrollment No :</b></td><td style="border: 1px solid black">'+d.enrollment_no+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Major :</b></td><td style="border: 1px solid black">'+d.major+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Grade/Class/Marks :</b></td><td style="border: 1px solid black">'+d.grade_class_marks+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Course Start :</b></td><td style="border: 1px solid black">'+d.course_start_date+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Course End :</b></td><td style="border: 1px solid black">'+d.course_end_date+'</td><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Month :</b></td><td style="border: 1px solid black">'+d.month_of_passing+'</td></tr><tr><td style = "background-color : #7a6fbe;color: white;border: 1px solid black"><b>Year :</b></td><td style="border: 1px solid black">'+d.year_of_passing+'</td><td>'+d.attachment_file+'</td></table>';
  return expand_data ;
}

  $('#tbl_datatable tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass("highlighted") ) {
     $(this).removeClass( 'highlighted' );  
    }else{
     $(this).addClass( 'highlighted' );   
    }
  });

  $('#tbl_datatable').on('click', 'tbody td, thead th:first-child', function(e){

      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });


  $('#cases_assgin').on('change', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    var mode_veri = $('#mode_veri').val();
 
   ((cases_assgin_action == "insufficiency") || (mode_veri== "verbal")) ?  $('.reject_reason').hide() : $('.reject_reason').show();
  }); 

   $('#cases_assgin_stamp_bulk').on('change', function(){
    var cases_assgin_bulk_action = $('#cases_assgin_stamp_bulk').val();
    
   (cases_assgin_bulk_action == "insufficiency") ?  $('.reject_reason_bulk').hide() : $('.reject_reason_bulk').show();
  }); 


  $('#cases_assgin_spoc').on('change', function(){
    var cases_assgin_action = $('#cases_assgin_spoc').val();
    var mode_veri = $('#mode_veri_spoc').val();
    ((cases_assgin_action == "insufficiency")  || (mode_veri== "verbal")) ? $('.reject_reason_spoc').hide() : $('.reject_reason_spoc').show();
  });
  $('#vendor_list_stamp').on('change', function(){
    var vendor_list_stamp = $('#vendor_list_stamp').val();
    (vendor_list_stamp == "no") ? $('.stamp_attachament').show() : $('.stamp_attachament').hide();
  });
   $('#vendor_list_spoc').on('change', function(){
    var vendor_list_spoc = $('#vendor_list_spoc').val();
    (vendor_list_spoc == "no") ? $('.spoc_attachament').show() : $('.spoc_attachament').hide();
  });

  $('#cases_assgin_closure').on('change', function(){
    var cases_assgin_action = $('#cases_assgin_closure').val();

    (cases_assgin_action == 1) ? $('.reject_reason_closure').hide() : $('.reject_reason_closure').show();
  });

  
    $('#tbl_datatable tbody').on('change', 'input[type="checkbox"]', function(){
 
      select_one = oTable.column(0).checkboxes.selected().join(",");
      $('#coomponent_check_id').val(select_one);
   });
   
 $(".dt-checkboxes-select-all").click(function () {
     $('#tbl_datatable').parent().find('input[type="checkbox"]').trigger('click');
 
});

$('#assgin_spoc').on('change', function(e){
    var cases_assgin_action = $('#assgin_spoc').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");
    if(cases_assgin_action != 0 && select_one != "")
    {
     
        $.post('<?php echo ADMIN_SITE_URL.'education/assign_verifiers_details/' ?>',{cases_assgin_action:cases_assgin_action},function (data, textStatus, jqXHR) {
        
          $('#vendor_details_list_spoc').html(data);
          $('.vendor_list').show();
          $('.header_title').text('Assign Spoc Vendor');
    
          $('#myModalCasesAssignSpoc').modal('show');
          $('#myModalCasesAssignSpoc').addClass("show");
          $('#myModalCasesAssignSpoc').css({background: "#0000004d"});
       });

     

      var form = this;
      var rows_selected = oTable.column(0).checkboxes.selected();
      $.each(rows_selected, function(index, rowId){
         $(form).append(
             $('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId)
         );
      });
      $('input[name="id\[\]"]', form).remove();
      e.preventDefault();
    } else {
      $("#assgin_spoc option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
  });

 $('#btn_assign_action_spoc_bulk').on('click', function(e){
    var education_id = $('#case_assign_to_spoc_bulk').val();
    var vendor_list = $('#vendor_list_spoc_bulk').val();

    if(education_id != "" && vendor_list != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_spoc_bulk/' ?>',
          data : 'vendor_list='+vendor_list+'&education_id='+education_id,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_spoc_bulk').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalSpoc').modal('hide');
              setTimeout(
              function() 
              {
                location.reload();
              }, 5000);
             
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Select Vendor Name or insert ref no');
    }
  });


  $('#btn_assign_action_stamp_bulk').on('click', function(e){

    var education_id = $('#case_assign_to_stamp_bulk').val();
    var status_value = $('#cases_assgin_stamp_bulk').val();

    if(education_id != "" && status_value != "")
    {
        var vendor_list = $('#vendor_list_stamp_bulk').val();
        var vendor_remark = $('#remark_verifiers_stamp_bulk').val();
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_stamp_bulk/' ?>',
          data : 'vendor_list='+vendor_list+'&education_id='+education_id+'&status_value='+status_value+'&vendor_remark='+vendor_remark,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_stamp_bulk').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalStamp').modal('hide');
              setTimeout(
              function() 
              {
                location.reload();
              }, 5000);
             
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Select Status or insert ref no');
    }
  });

 $('#btn_assign_action_spoc').on('click', function(e){
    var vendor_list = $('#vendor_list').val();
   // var vendor_list_mode = $('#vendor_list_mode').val();
    var select_one = oTable.column(0).checkboxes.selected().join(",");

    if(vendor_list != "0" && select_one != "")
    {
      
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/assign_to_spoc/' ?>',
          data : 'vendor_list='+vendor_list+'&cases_id='+select_one,
          dataType:'json',
          beforeSend :function(){
            jQuery('#btn_assign_action_spoc').text('Processing...');
          },
          complete:function(){
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#myModalCasesAssignSpoc').modal('hide');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });
     
    }
    else {
      alert('Select Vendor Name');
    }
  });
 
 $(document).on('click', '.showverifierModel', function(){
    var varifiers_id = $(this).data("id");
    var education_case_id = $(this).data("educationcaseid");
    var mode_of_verification = $(this).data("mode_of_verification");
    if(mode_of_verification == "verbal")
    {
       $('#mode_veri').val(mode_of_verification);
       $('.reject_reason').css('display','none');
       $('.stamp_attachament').css('display','block');
       $('.verification_remark').css('display','none');
    }
    $("#verifiers_stamp_id").val(varifiers_id);
    $("#education_case_id_stamp").val(education_case_id);
    $('#showverifierModel').modal('show');
    $('#showverifierModel').addClass("show");
    $('#showverifierModel').css({background: "#0000004d"});

  });

  $(document).on('click', '.showURLModel', function(){
    var educ_id = $(this).data("id");
    var educ_case_id = $(this).data("educationcaseid");
  
    $("#educ_id").val(educ_id);
    $("#educ_case_id").val(educ_case_id);
    $('#showURLModel').modal('show');
    $('#showURLModel').addClass("show");
    $('#showURLModel').css({background: "#0000004d"});

    $.post('<?= ADMIN_SITE_URL."education/select_url_details" ?>',   // url
       { education_id: educ_id }, // data to be submit
       function(data, status, jqXHR) {// success callback
        $('#education_url').val(data); 
      })

  });


  $(document).on('click', '.showURLModelSPOC', function(){
    var university_id = $(this).data("university_id");

  
    
    $('#showURLModelSPOC').modal('show');
    $('#showURLModelSPOC').addClass("show");
    $('#showURLModelSPOC').css({background: "#0000004d"});

    $.post('<?= ADMIN_SITE_URL."education/select_url_details_spoc" ?>',   // url
       { university_id: university_id }, // data to be submit
       function(data, status, jqXHR) {// success callback
        $('#education_url_spoc').val(data); 
      })

  });


  $(document).on('click', '.showstampModel', function(){
    var varifiers_id = $(this).data("id");
    var status = $(this).data("status");
    $("#stamp_id").val(varifiers_id);
    $("#cases_assgin_stamp").val(status);
    $('#showstampModel').modal('show');
    $('#showstampModel').addClass("show");
    $('#showstampModel').css({background: "#0000004d"});
  });

  $(document).on('click', '.showspocModel', function(){
    var varifiers_id = $(this).data("id");
    var education_case_id = $(this).data("educationcaseid");
    var mode_of_verification = $(this).data("mode_of_verification");
    if(mode_of_verification == "verbal")
    {
       $('#mode_veri_spoc').val(mode_of_verification);
       $('.reject_reason_spoc').css('display','none');
       $('.spoc_attachament').css('display','block');
       $('.spoc_remarks').css('display','none');
    }
    $("#verifiers_spoc_id").val(varifiers_id);
    $("#education_case_id_spoc").val(education_case_id);
    $('#showspocModel').modal('show');
    $('#showspocModel').addClass("show");
    $('#showspocModel').css({background: "#0000004d"});
  });
   
   
  $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function(e) {
    if(e.keyCode == 13) {
      oTable.search( this.value ).draw();
    }
  });
   
  

   $('#tbl_datatable tbody').on('dblclick', 'tr', function () { 
    var data = oTable.row( this ).data(); 
   // if(data['edit_access'] != 0)
      window.location = data['encry_id'];
  //  else
  //    show_alert('Access Denied, You don’t have permission to access this page');
  });


$(document).on('click', '#btn_assign', function(){
    var cases_assgin_action = $('#cases_assgin').val();
    var reject_reason = $('#reject_reason').val();

   select_one = oTable.column(0).checkboxes.selected().join(",");

    if(cases_assgin_action != 0 && select_one != "")
    { 
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/education_final_assigning/' ?>',
          data : 'action='+cases_assgin_action+'&cases_id='+select_one+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            $('#btn_assign').text('Processing...');
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });      
    } else {
      $("#cases_assgin option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
});

 $(document).on('click', '#btn_assign_closure', function(){
    var cases_assgin_action = $('#btn_assign_closure').val();
    var cases_assgin_action1 = $('#cases_assgin_closure').val();
    var reject_reason = $('#reject_reason_closure').val();

 

    var selected_id = new Array();
       $("input[name='closure_id']:checked").each(function(i) {
    selected_id.push($(this).val());
});

 //select_one = oTable.column(0).checkboxes.selected().join(",");
 
    if(cases_assgin_action != 0 && selected_id != "")
    { 

      if(cases_assgin_action == 2 && reject_reason == "")
        {
     
        show_alert('Please insert reject reason','error'); 
        
        }
       else
       {

        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/education_closure/' ?>',
          data : 'action='+cases_assgin_action1+'&closure_id='+selected_id+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#btn_assign_closure').text("loading...");
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one1 = [];
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
    } else {
      $("#cases_assgin_closure option[value=0]").attr('selected', 'selected');
      alert('Select atleast one case');
    }
});


 $(document).on('click', '#approve', function(){
     var cases_assgin_action = $('#approve').val();
     var selected_id = new Array();
     var selected_id = $('#closure_id').val();  

    if(cases_assgin_action != 0 && selected_id != "")
    { 
  
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/address_closure/' ?>',
          data : 'action='+cases_assgin_action+'&closure_id='+selected_id,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#approve').text("loading...");
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one1 = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });   
    } 
});

$(document).on('click', '#reject', function(){
     var cases_assgin_action = $('#reject').val();

     var selected_id = $('#closure_id').val();  
     var reject_reason = $('#reject_reason_closure').val();  

    if(cases_assgin_action != 0 && selected_id != "")
    { 

      if(reject_reason == "")
      {
     
       show_alert('Please insert reject reason','error'); 
        
      }
      else
      {  
  
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'education/education_closure/' ?>',
          data : 'action='+cases_assgin_action+'&closure_id='+selected_id+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('.body_loading').show();
            jQuery('#reject').text("loading...");
          },
          complete:function(){
            jQuery('.body_loading').hide();
          },
          success:function(jdata)
          {
            select_one1 = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#reject_value').modal('hide');
              $('#showvendorModel').modal('hide');
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        }); 
      }  
    } 
});  

});



$('#activityModelClk1').click(function(){

    var url = $(this).data("url");
    var id = $('.education_id').val();
    var component = 'education';
    $('.append-activity_view').load(url+id+"/"+component,function(){});
    $('#activityModel').modal('show');
    $('#showvendorModel').modal('hide');
    $('#activityModel').addClass("show");
    $('#activityModel').css({background: "#0000004d"});
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

$(document).on('click','#activityModelClk1',function() {
 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var entity_id  = $('#entity_id1').val();
 var package_id  = $('#package_id1').val();
 var clientid  = $('#clientid1').val();
 var education_result_id  = $('#education_result_id1').val();

 document.getElementById("education_id").value = component_id; 
 document.getElementById("candidates_info_id").value = CandidateID; 
 document.getElementById("entity_id").value = entity_id; 
 document.getElementById("package_id").value = package_id; 
 document.getElementById("clientid").value = clientid; 
 document.getElementById("education_result_id").value = education_result_id; 
   
});

$('#activityModelClk1').click(function(){

 var component_id  = $('#component_id').val();
 var CandidateID  = $('#CandidateID').val();
 var ClientRefNumber  = $('#ClientRefNumber').val();
 var CandidateName  = $('#CandidateName').val();
 var component_ref_no  = $('#component_ref_no').val();
 document.getElementById("comp_table_id").value = component_id; 
 document.getElementById("acti_candsid").value = CandidateID; 
 document.getElementById("ac_ClientRefNumber").value = ClientRefNumber; 
 document.getElementById("CandidateName1").value = CandidateName; 
 document.getElementById("component_ref_no1").value = component_ref_no; 



 //$('#comp_table_id') = component_id.val();
 
});

$('.approve_reject_id').click(function(){

 var cl_id  = $('#cl_id').val();
 document.getElementById("closure_id").value = cl_id; 

 //$('#comp_table_id') = component_id.val(); 
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
</script>



<script type="text/javascript">

  $('#frm_verificarion_result').validate({
      rules: {
        res_qualification : {
          required : true,
          greaterThan : 0
        },
        res_school_college : {
          required : true
        },
        res_university_board : {
          required : true,
          greaterThan : 0
        },
        res_month_of_passing : {
          required : true
        },
        res_year_of_passing : {
          required : true
        },
       verified_by : {
          required : true
        },
        verifier_designation : {
          required : true
        },
        closuredate : {
          required : true
        },
        res_remarks : {
          required : true
        },
        qualification_action : {
          required : true
        },
        school_college_action : {
          required : true
        },
        res_mode_of_verification :  {
          required : true
        },
        university_board_action : {
          required : true
        },
       
         month_of_passing_action : {
          required : true
        },
         year_of_passing_action : {
          required : true
        }
         
      },
      messages: {
        res_qualification : {
          required : "Select Qualification",
          greaterThan : "Please Select Qualification",
        },
        res_school_college : {
          required : "Enter School Name"
        },
        res_university_board : {
          required : "Select University",
          greaterThan : "Please Select university Board"
        },
        res_month_of_passing : {
          required : "Enter Month of Passing"
        },
        res_year_of_passing : {
          required : "Enter Year of Passing"
        },
        verified_by : {
          required : "Enter Verifier Name"
        },
        verifier_designation : {
          required : "Enter Designation"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        res_remarks : {
          required : "Enter Remark"
        },
         res_mode_of_verification :  {
          required : "Enter Mode of Verification"
        },
         qualification_action : {
           required : "Please Selected Qualification Action"
        },
         school_college_action : {
           required : "Please Selected School Action"
        },
          university_board_action : {
           required : "Please Selected University Action"
        },
        month_of_passing_action : {
           required : "Please Selected Month of passing Action"
        },
        year_of_passing_action : {
           required : "Please Selected Year of passing Action"
        }
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#frm_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=3'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
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
          url: '<?php echo ADMIN_SITE_URL.'education/add_verificarion_result'; ?>',
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
            $('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
          var  final_status = $('#fin_status').val();
          if(final_status == "Clear" || final_status == "Major Discrepancy" || final_status == "Minor Discrepancy" || final_status == "No Record Found"  || final_status == "Unable To Verify")
          {
            var cases_as = 1;
            var selected_id =  $('#closure_id').val();

            // finalData.append('status','approve');
          $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'education/education_closure/' ?>',
          data :  'action='+cases_as+'&closure_id='+selected_id,
          type: 'post',
          async:false,
          cache: false,
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
              $('#addAddResultModel').modal('hide');
              $('.view_component_tab a.active').click();
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
          url : '<?php echo ADMIN_SITE_URL.'education/insuff_raised'; ?>',
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
            $('#btn_submit_insuff').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#insuffRaiseModel').modal('hide');
            $('#frm_insuff_raise')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              //location.reload();
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });


  $('#frm_verifiers_details').validate({ 
    rules: {
      verifiers_stamp_id : {
        required : true
      },
      remark_verifiers_stamp : {
        required : true
      },
      cases_assgin : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      verifiers_stamp_id : {
        required : "Update ID missing"
      },
      remark_verifiers_stamp : {
        required : "Please Enter Verifiers Remark"
      },
      cases_assgin : {
        required : "Select Status",
        greaterThan : "Select Status"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'education/assign_stamp_verifiers_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_verifier_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_verifier_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showverifierModel').modal('hide');
            $('#frm_verifiers_details')[0].reset();
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

  $('#frm_url_details').validate({ 
    rules: {
      educ_id : {
        required : true
      },
      educ_case_id : {
        required : true
      },
      education_url : {
        required : true
      }
    },
    messages: {
      educ_id : {
        required : "Update ID missing"
      },
      educ_case_id : {
        required : "Please ID"
      },
      education_url : {
        required : "Enter URL"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'education/assign_url_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_url_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_url_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showURLModel').modal('hide');
            $('#frm_url_details')[0].reset();
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

   $('#frm_spoc_details').validate({ 
    rules: {
      spoc_stamp_id : {
        required : true
      },
      remark_verifiers_spoc : {
        required : true
      },
      cases_assgin_spoc : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      spoc_stamp_id : {
        required : "Update ID missing"
      },
      remark_verifiers_spoc : {
        required : "Please Enter Spoc Remark"
      },
      cases_assgin_spoc : {
        required : "Select Status",
        greaterThan : "Select Status"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'education/assign_stamp_spoc_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_spoc_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_spoc_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showspocModel').modal('hide');
            $('#frm_spoc_details')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

   $('#frm_stamp_details').validate({ 
    rules: {
      stamp_id : {
        required : true
      },
      "attchments_stamp[]" : {
        required : true
      }
    },
    messages: {
      stamp_id : {
        required : "Update ID missing"
      },
      "attchments_stamp[]" : {
        required : "Please  attach Document"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'education/assign_stamp_details'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_submit_stamp_details').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_submit_stamp_details').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#showstampModel').modal('hide');
            $('#frm_stamp_details')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
               $('.view_component_tab a.active').click();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
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

$('#cases_assgin').on('change',function(){
  
   var cases_assgin  = $('#cases_assgin').val(); 
    if(cases_assgin == "clear")
    {
     $('#reamark_verifiers_stamp').val(cases_assgin); 
    }
    else
    {
       $('#reamark_verifiers_stamp').val(''); 
    }
 
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


$('#university_image').click(function(){
  
      var url = $(this).data("url");
      var education_id = $('#education_id').val();
      
      $('.append-university_display').load(url+'/'+education_id,function(){
        $('#UniversityModel').modal('show');
        $('#UniversityModel').addClass("show");
        $('#UniversityModel').css({background: "#0000004d"});
      });
    
  });

 $('#submit_university_images').click(function(){
      var fields = $("#frm_submit_university_images").serializeArray();
               
      var myarray = [];    
      jQuery.each(fields, function(i, field){
          myarray.push(field.value);
      });
      $("#university_images").val(myarray);
      $('#UniversityModel').modal('hide');
  });


</script>
