<div class="content-page">
  <div class="content">
    <div class="container-fluid">
  

      <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Task Scheduled List</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>scheduler">Task Scheduled</a></li>
                  <li class="breadcrumb-item active">List View</li>
              </ol>
      
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>scheduler"><i class="fa fa-arrow-left"></i> Back</button></li> 
                </ol>
              </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            
              <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Report Name</th>
                    <th>Date Range</th>
                    <th>Last Run By</th>
                    <th>Last Run On</th>
                    <th>Status</th>
                    <th>Report</th>
                    <th>Action</th>
                  </tr>

                </thead>
                <tbody>
              
                  <?php
                 
                   if ($this->permission['access_report_schedule_list'] == 1) { 
                    $i = 1;
                    foreach ($lists as $list)
                    {
                        $url = ADMIN_SITE_URL.'scheduler/edit/'.encrypt($list['id']);

                       // $linkpath = ADMIN_SITE_URL.'scheduler/file_download_activity/'.$value['file_name']; 
                        $linkpath = ADMIN_SITE_URL.'scheduler/file_download_activity/'.$list['file_name']; 
 
                        $last_exe = ($list['last_run_on'] != '') ? convert_db_to_display_date($list['last_run_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12) : '';
                        $run_status = ($list['run_status'] == '1') ? "Success" : '';
                        echo  "<tr>";
                        echo  "<td>".$i."</td>";
                       // echo  "<td>".$list['type']."</td>";
                        echo  "<td>".$list['report_name']."</td>";
                        echo  "<td>".$list['date_range']."</td>";
                        echo  "<td>".$list['username']."</td>";
                        echo  "<td>".$last_exe."</td>";
                        echo "<td>".$run_status."</td>";
                       
                        if($list['id'] == 1)
                        {
                         $access_pre_post = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelExportPrePost'  : ''; 
                           echo "<td><a href='$linkpath' class='btn btn-link'>".$list['file_name']."</a></td>";
                           echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_pre_post.'">Pre Post</button>
                                 <button class="btn btn-sm btn btn-info" data-url="'.ADMIN_SITE_URL.'/scheduler/prepost_recent_file"  data-toggle="modal" id="prepost_file">Pre Post Files</button>
                           </td>';
                        }
                        if($list['id'] == 2)
                        {
                          $access_report = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelReport'  : ''; 
                          $access_id_report = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelReportID'  : ''; 

                          $linkpath = ADMIN_SITE_URL.'scheduler/file_download_zip/'.$list['file_name']; 
                            echo "<td><a href='$linkpath' class='btn btn-link'>".$list['file_name']."</a></td>";
                           echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_report.'">Create  Report</button>&nbsp;';
                           echo '<button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_id_report.'">ID Wise  Report</button>&nbsp;';
                             echo '</td>';
                        }
                        if($list['id'] == 3)
                        {
                           $access_closure = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelClosure'  : ''; 

                            echo "<td><a href='$linkpath' class='btn btn-link'>".$list['file_name']."</a></td>";

                           echo '<td><button  class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_closure.'">Run</button>';                   
                           echo '</td>';
                        }
                         if($list['id'] == 4)
                        {

                          echo "<td><a href='$linkpath' class='btn btn-link'>".$list['file_name']."</a></td>";

                          $access_export = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelComponentExport'  : ''; 
                          echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_export.'">Create  Report</button></td>';
                             
                        }
                        if($list['id'] == 5)
                        {
                          echo "<td><a href='$linkpath' class='btn btn-link'>".$list['file_name']."</a></td>";

                          $access_tracker_export = ($this->permission['access_report_schedule_run'] == "1") ? '#myModelTrackerExport'  : ''; 
                          echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_tracker_export.'">Create  Report</button></td>';
                        }

                        if($list['id'] == 6)
                        {
                          $access_import_export = ($this->permission['access_candidates_list_export']) ? '#myModelExport'  : '';
                           echo '<td><button class="btn btn-sm btn btn-info" data-toggle="modal" data-target="'.$access_import_export.'">Export Candidate</button> 
                           </td>';
                        }

                       
                        echo "</tr>";
                        $i++;
                    } 
                  }
                  else{
                    echo "<tr>";
                    echo "<td colspan = '8' align = 'center'>Not Permission </td>";
                    echo "<tr>";
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

<div id="myModelExportPrePost" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm_prepost','id'=>'export_to_excel_frm_prepost')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidates Records Pre Post</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Client</label>
              <?php 
                echo form_dropdown('client_id_prepost', $clients_id_prepost, set_value('client_id_prepost'), 'class="form-control" id="client_id_prepost"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_prepost', array(), set_value('entity_id_prepost'), 'class="form-control" id="entity_id_prepost"');?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
               <label>Select Package</label>
             <select id="package_id_prepost" name="package_id_prepost" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
        
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export_prepost" name="export_prepost" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>      

<div id="myModelReport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_report','id'=>'frm_report')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Report</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Client</label>
              <?php 
                echo form_dropdown('client_id_report', $clients_id_report, set_value('client_id_report'), 'class="form-control" id="client_id_report"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_report', array(), set_value('entity_id_report'), 'class="form-control" id="entity_id_report"');?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
               <label>Select Package</label>
             <select id="package_id_report" name="package_id_report" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
        
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="create_report" name="create_report" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelReportID" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_id_report','id'=>'frm_id_report')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Report ID Wise</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            
            <div class="clearfix"></div>
            <div class="col-sm-12 form-group"> 
               <label>Insert Candidate ID</label>
               <textarea name="candidate_ids" id="candidate_ids" rows="2" class="form-control"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="create_id_report" name="create_id_report" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelClosure" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_closure','id'=>'frm_closure')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Closure Report</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Client</label>
              <?php 
                echo form_dropdown('client_id_closure', $clients_id_report, set_value('client_id_closure'), 'class="form-control" id="client_id_closure"');
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id_closure', array(), set_value('entity_id_closure'), 'class="form-control" id="entity_id_closure"');?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
               <label>Select Package</label>
             <select id="package_id_closure" name="package_id_closure" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
        
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="create_closure" name="create_closure" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>      


<div id="ActivityReportModel" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title header_title">Activity Report</h4>
      </div>
       <?php echo form_open_multipart('#', array('name'=>'frm_activity_data','id'=>'frm_activity_data')); ?>  
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>From Date<span class="error"> *</span></label>
             <input type="text" name="activity_from" id="activity_from"  class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>To Date<span class="error"> *</span></label>
              <input type="text" name="activity_to" id="activity_to"  class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="btn_activity_report">Create</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
         <?php echo form_close(); ?>
    </div>

  </div>
</div>

<div id="myModelExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'export_to_excel_frm','id'=>'export_to_excel_frm')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Export Candidates Records</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <label>Select Client</label>
              <?php 
                 $clients_id["All"] = "All";
               echo form_dropdown('client_id', $clients_id, set_value('client_id'), 'class="form-control" id="client_id"');?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_id', array(), set_value('entity_id'), 'class="form-control" id="entity_id"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
               <label>Select Package</label>
             <select id="package_id" name="package_id" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Status</label>
             <?php
                echo form_dropdown('status_value', $status_value, set_value('status_value'), 'class="form-control" id="status_value"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                 <label>Select Sub Status</label>
               <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_status', $sub_status, set_value('sub_status'), 'class="form-control" id="sub_status"');?>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="export" name="export" class="btn btn-success"> Export</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="prepost_file_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pre Post Recent File</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          
          <div class="row">
           
            <div class="append-prepostfile"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
     
    </div>
  </div>
</div>

<div id="wip_insuff_file_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">WIP Insuff Recent File</h4>
      </div>
      <?php echo form_open('#', array('name'=>'wip_insuff_activity','id'=>'wip_insuff_activity')); ?>
      <div class="modal-body">
          
          <div class="row">
           
            <div class="append-wip_insuff_file"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
     
    </div>
  </div>
</div>

<div id="axis_file_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Axis Secutity Recent File</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_axis','id'=>'cases_axis')); ?>
      <div class="modal-body">
          
          <div class="row">
           
            <div class="append-axis_file"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
     
    </div>
  </div>
</div>

<div id="axis_ikya_file_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Axis Secutity (Ikya) Recent File</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_axis_ikya','id'=>'cases_axis_ikya')); ?>
      <div class="modal-body">
          
          <div class="row">
           
            <div class="append-axis_ikya_file"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
     
    </div>
  </div>
</div>

<div id="axis_teamlease_file_model" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;width: 80%;  margin-top: 40px; margin-bottom:40px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Axis Secutity (Teamlease) Recent File</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_axis_teamlease','id'=>'cases_axis_teamlease')); ?>
      <div class="modal-body">
          
          <div class="row">
           
            <div class="append-axis_teamlease_file"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
     
    </div>
  </div>
</div>


<div id="myModelComponentExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_component_export','id'=>'frm_component_export')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Report</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            
              <?php 
                  echo form_dropdown('components_id', $components, set_value('components_id'), 'class="custom-select" id="components_id"');
                  echo form_error('components_id'); 
               ?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            
              <?php 
                 $clients_id_report['All'] = 'All';
                 echo form_dropdown('client_id_report_component', $clients_id_report, set_value('client_id_report_component'), 'class="form-control" id="client_id_report_component"');
               ?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <?php
               unset($status_value['NA']);
               echo form_dropdown('status_value_component', $status_value, set_value('status_value_component','WIP'), 'class="form-control status_value_component" id="status_value_component"');?>
            </div>

          <div id="display_from_to_date" style="display: none"> 
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>From Date</label>
             <input type="text" name="from_date" id="from_date" class="form-control myDatepicker" placeholder="dd-mm-yyyy" >
           </div>
                           
           <div class="col-md-6 col-sm-12 col-xs-6 form-group">
             <label>To Date</label>
              <input type="text" name="to_date" id="to_date" class="form-control myDatepicker" placeholder="dd-mm-yyyy" >
           </div>
          </div>
          <div class="clearfix"></div>
        
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="create_component_report" name="create_component_report" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div id="myModelTrackerExport" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" style="width: 700px !important;">
    <div class="modal-content">
      <?php echo form_open('#', array('name'=>'frm_tracker_export','id'=>'frm_tracker_export')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tracker Report</h4>
      </div>
      <div class="modal-body" style="padding: 5px !important;">
        <div class="result_error" id="result_error"></div>
        <div class="modal-body">
           <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-8 form-group">
              <label>Select Client</label>
              <?php 
                 $clients_id["All"] = "All";
               echo form_dropdown('client_tracker_id', $clients_id, set_value('client_tracker_id'), 'class="form-control" id="client_tracker_id"');?>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Entity</label>
              <?php echo form_dropdown('entity_tracker_id', array(), set_value('entity_tracker_id'), 'class="form-control" id="entity_tracker_id"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
               <label>Select Package</label>
             <select id="package_tracker_id" name="package_tracker_id" class="form-control"><option value="0">Select Package</option></select>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
              <label>Select Status</label>
             <?php
                echo form_dropdown('status_tracker_value', $status_value, set_value('status_tracker_value'), 'class="form-control" id="status_tracker_value"');?>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                 <label>Select Sub Status</label>
               <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('sub_tracker_status', $sub_status, set_value('sub_tracker_status'), 'class="form-control" id="sub_tracker_status"');?>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="create_tracker_report" name="create_tracker_report" class="btn btn-success"> Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  
  $('#frm_activity_data').validate({ 
      rules: {
        activity_from : {
          required : true
        },
        activity_to : {
          required : true
        }
      },
      messages: {
        activity_from : {
          required : "Select  From Date"
        },
        activity_to : {
          required : "Select To Date"
        }
      },
      submitHandler: function(form) 
      { 
          
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_activity_data'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_activity_report').attr('disabled','disabled');
          },
          complete:function(){
            //$('#btn_activity_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#ActivityReportModel').modal('hide');
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


   $('#frm_axis_data').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_axis_report'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_axis_report').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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

   $('#frm_axis_data_new').validate({ 
     
      submitHandler: function(form) 
      { 

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_axis_report_new'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_axis_report_new').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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
  
   $('#frm_axis_ikya_data_new').validate({ 
     
      submitHandler: function(form) 
      { 
     
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_axis_ikya_report_new'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_ikya_axis_report_new').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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


  $('#frm_axis_ikya_data').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_axis_ikya_report'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_ikya_axis_report').attr('disabled','disabled');
          },
          complete:function(){
           // $('#btn_ikya_axis_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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

   $('#frm_wip_insuff_report').validate({ 
   
      submitHandler: function(form) 
      {    

        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_wip_insuff_report'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_wip_insuff_report').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_axis_teamlease_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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

   $('#frm_axis_teamlease_data').validate({ 
     
      submitHandler: function(form) 
      {    
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'Scheduler/export_axis_teamlease_report'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_axis_teamlease_report').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#btn_axis_teamlease_report').removeAttr('disabled');
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              $('#AxisReportModel').modal('hide');
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


$(document).on('change', '#client_id', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id').html(html);
          }
      });
  }
});

  $(document).on('change', '#entity_id', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id').html(html);
            }
        });
    }
  });

  $(document).on('change', '#status_value', function(){
    var status = $("#status_value option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#sub_status').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_status').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_status').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_status').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});  

  $('#export_to_excel_frm').validate({ 
        rules: {
          client_id : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id').val();
            var entity = $('#entity_id').val();
            var package = $('#package_id').val();
            var fil_by_status = $('#status_value').val();
            var fil_by_sub_status = $('#sub_status').val();
            var client_name = $('#client_id option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package+'&fil_by_status='+ fil_by_status+'&fil_by_sub_status='+ fil_by_sub_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/export_to_excel'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export').text('exporting..');
              $('#export').attr('disabled','disabled');
            },
            complete:function(){
              $('#export').text('Export');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#export_to_excel_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelExport').modal('hide');
                 show_alert(message,'success');
                 location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
  });

    $('#frm_report').validate({ 
        rules: {
          client_id_report : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id_report : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id_report').val();
            var entity = $('#entity_id_report').val();
            var package = $('#package_id_report').val();
           
            var client_name = $('#client_id_report option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/create_report'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#create_report').text('Creating..');
              $('#create_report').attr('disabled','disabled');
            },
            complete:function(){
              $('#create_report').text('Submit');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#frm_report')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelExport').modal('hide');
                 show_alert(message,'success');
                 location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
  });

  $('#frm_id_report').validate({ 
        rules: {
          candidate_ids : {
            required : true
          }
        },
        messages: {
          candidate_ids : {
            required : "Enter Candidate ID"
          }
        },
        submitHandler: function(form) 
        {      
            var candidate_ids = $('#candidate_ids').val();
         
            var dataString = 'candidate_id=' + candidate_ids;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/create_id_report'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#create_id_report').text('Creating..');
              $('#create_id_report').attr('disabled','disabled');
            },
            complete:function(){
              $('#create_id_report').text('Submit');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#frm_id_report')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelReportID').modal('hide');
                 show_alert(message,'success');
                 location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
  });

  $('#frm_closure').validate({ 
        rules: {
          client_id_closure : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id_closure : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id_closure').val();
            var entity = $('#entity_id_closure').val();
            var package = $('#package_id_closure').val();
           
            var client_name = $('#client_id_closure option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/create_closure'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#create_closure').text('Creating..');
              $('#create_closure').attr('disabled','disabled');
            },
            complete:function(){
              $('#create_closure').text('Submit');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelClosure').modal('hide');
                 show_alert(message,'success');
                 location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          
        });    
      }
  });

  $('#prepost_file').click(function(){
    var url = $(this).data("url");
    $('.append-prepostfile').load(url,function(){});
    $('#prepost_file_model').modal('show');
    $('#prepost_file_model').addClass("show");
    $('#prepost_file_model').css({background: "#0000004d"});  
  });

  $('#axis_tracker_file').click(function(){
    var url = $(this).data("url");
    $('.append-axis_file').load(url,function(){});
    $('#axis_file_model').modal('show');
    $('#axis_file_model').addClass("show");
    $('#axis_file_model').css({background: "#0000004d"}); 
  });

   $('#axis_tracker_ikya_file').click(function(){
    var url = $(this).data("url");
    $('.append-axis_ikya_file').load(url,function(){});
    $('#axis_ikya_file_model').modal('show');
    $('#axis_ikya_file_model').addClass("show");
    $('#axis_ikya_file_model').css({background: "#0000004d"}); 
  });

    $('#axis_tracker_teamlease_file').click(function(){
    var url = $(this).data("url");
    $('.append-axis_teamlease_file').load(url,function(){});
    $('#axis_teamlease_file_model').modal('show');
    $('#axis_teamlease_file_model').addClass("show");
    $('#axis_teamlease_file_model').css({background: "#0000004d"}); 
  });

    $('#wip_insuff_file').click(function(){
    var url = $(this).data("url");
    $('.append-wip_insuff_file').load(url,function(){});
    $('#wip_insuff_file_model').modal('show');
    $('#wip_insuff_file_model').addClass("show");
    $('#wip_insuff_file_model').css({background: "#0000004d"}); 
  });

$(document).on('change', '#client_id_prepost', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id_prepost').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id_prepost').html(html);
          }
      });
  }
});

$(document).on('change', '#entity_id_prepost', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_id_prepost').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id_prepost').html(html);
            }
        });
    }
  });

$(document).on('change', '#client_id_report', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id_report').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id_report').html(html);
          }
      });
  }
});

$(document).on('change', '#entity_id_report', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_id_report').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id_report').html(html);
            }
        });
    }
  });


  $(document).on('change', '#client_id_closure', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_id_closure').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_id_closure').html(html);
          }
      });
  }
});

$(document).on('change', '#entity_id_closure', function(){
    var entity = $(this).val();
    var selected_clientid = '';
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_id_closure').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_id_closure').html(html);
            }
        });
    }
  });



$('#export_to_excel_frm_prepost').validate({ 
        rules: {
          client_id_prepost : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id_prepost : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_id_prepost').val();
            var entity = $('#entity_id_prepost').val();
            var package = $('#package_id_prepost').val();
            var client_name = $('#client_id_prepost option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/export_to_excel_prepost'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#export_prepost').text('exporting..');
              $('#export_prepost').attr('disabled','disabled');
            },
            complete:function(){
              $('#export_prepost').text('Export');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#export_to_excel_frm_prepost')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelExportPrePost').modal('hide');
                 show_alert(message,'success');
                 location.reload();
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
  });
</script>
<script>
$(document).ready(function(){
  $('#tbl_datatable').DataTable({
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      "order": [[ 0, "asc" ]],
      scrollX: true,
       "pageLength": 25,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
  });
})


$('.status_value_component').on('change', function(e){
     var status_value = $(this).val();

     if(status_value == "Closed")
     {
    
       $('#display_from_to_date').show();
     }
     else
     {
     
       $('#display_from_to_date').hide();
     }
});


    $('#frm_component_export').validate({ 
        rules: {
          components_id : {
            required : true
          }
        },
        messages: {
          components_id : {
            required : "Enter Component Name"
          }
        },
        submitHandler: function(form) 
        {   

           var components_id = $('#components_id').val();
           var status_value_component = $('#status_value_component').val();
           var client_id = $('#client_id_report_component').val();
           var from_date = $('#from_date').val();
           var end_date = $('#to_date').val();
           var client_name = $('#client_id_report_component option:selected').html(); 
           var dataString = 'clientid=' + client_id + '&client_name='+ client_id+'&components_id='+ components_id+'&status_value_component='+ status_value_component+ '&from_date='+ from_date+ '&end_date='+ end_date;   

          $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/create_component_report'; ?>',
            data:  dataString,
            type: 'POST',
            beforeSend:function(){
              $('#create_component_report').text('Creating..');
              $('#create_component_report').attr('disabled','disabled');
            },
            complete:function(){
              $('#create_component_report').text('Submit');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#frm_component_export')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelComponentExport').modal('hide');
                 show_alert(message,'success');
                 //location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
      });

      $('#frm_tracker_export').validate({ 
        rules: {
          client_id : {
            required : true,
            greaterThan : 0
          }
        },
        messages: {
          client_id : {
            required : "Select Client Name",
            greaterThan : "Select Client Name"
          }
        },
        submitHandler: function(form) 
        {      
            var clientid = $('#client_tracker_id').val();
            var entity = $('#entity_tracker_id').val();
            var package = $('#package_tracker_id').val();
            var fil_by_status = $('#status_tracker_value').val();
            var fil_by_sub_status = $('#sub_tracker_status').val();
            var client_name = $('#client_tracker_id option:selected').html(); 
            var dataString = 'clientid=' + clientid + '&client_name='+ client_name+'&entity='+ entity+'&package='+ package+'&fil_by_status='+ fil_by_status+'&fil_by_sub_status='+ fil_by_sub_status;
            $.ajax({
            url: '<?php echo ADMIN_SITE_URL.'Scheduler/export_tracker'; ?>',
            data: dataString,
            type: 'POST',
            beforeSend:function(){
              $('#create_tracker_report').text('exporting..');
              $('#create_tracker_report').attr('disabled','disabled');
            },
            complete:function(){
              $('#create_tracker_report').text('Export');
             // $('#export').removeAttr('disabled');                
            },
            success: function(jdata){
              $('#export_to_excel_frm')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>)
              {
                 $('#myModelTrackerExport').modal('hide');
                 show_alert(message,'success');
                // location.reload(); 
              }
              else
              {
                show_alert(message,'error'); 
              }
            }
          })    
        }
  });


  $(document).on('change', '#client_tracker_id', function(){
  var clientid = $(this).val();

  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity_tracker_id').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity_tracker_id').html(html);
          }
      });
  }
});

  $(document).on('change', '#entity_tracker_id', function(){
    var entity = $(this).val();
    var selected_clientid = '';

    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'clients/get_package_list'; ?>',
            data:'entity='+entity+'&selected_clientid='+selected_clientid,
            beforeSend :function(){
              jQuery('#package_tracker_id').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#package_tracker_id').html(html);
            }
        });
    }
  });

  $(document).on('change', '#status_tracker_value', function(){
    var status = $("#status_tracker_value option:selected").html();
    $.ajax({
        type:'POST',
        data:'status='+status,
        url : 'candidates/sub_status_list_candidates',
        beforeSend :function(){
            jQuery('#sub_tracker_status').find("option:eq(0)").html("Please wait..");
        },
        complete:function(){
            jQuery('#sub_tracker_status').find("option:eq(0)").html("Sub Status");
        },
        success:function(jdata) {
            $('#sub_tracker_status').empty();
            $.each(jdata.sub_status_list, function(key, value) {
              $('#sub_tracker_status').append($("<option></option>").attr("value",key).text(value));
            });
        }
    });
});     

</script>

