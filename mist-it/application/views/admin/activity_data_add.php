<div class="content-page">
  <div class="content">
    <div class="container-fluid">

     <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Admin - Activity Master - List View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>activity_log">Activity Master</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  <div class="state-information d-none d-sm-block">
                    <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>roles"><i class="fa fa-arrow-left"></i> Back</button></li> 

                   </ol>

                  </div>
            </div>
          </div>
       </div>


   
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
            
                <div class="col-sm-12">
                <div class="panel with-nav-tabs panel-default">
                    <div class="nav-tabs-custom">
                            <ul class="nav nav-pills nav-justified">
                                <li class='nav-item waves-effect waves-light active'><a class = 'nav-link active'  href="#tab1default" data-toggle="tab">Action</a></li>
                                <li class='nav-item waves-effect waves-light'><a  class = 'nav-link'  href="#tab2default" data-toggle="tab">Activity Mode</a></li>
                                <li class='nav-item waves-effect waves-light'><a  class = 'nav-link' href="#tab3default" data-toggle="tab">Activity Type</a></li>
                                <li class='nav-item waves-effect waves-light'><a  class = 'nav-link' href="#tab4default" data-toggle="tab">Activity Status</a></li>
                            </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane  active" id="tab1default">
                              </br>
                                <?php echo form_open(ADMIN_SITE_URL.'activity_log/activity_status ', array('name'=>'frm_activity_status','id'=>'frm_activity_status')); ?>
                                <div class="row">
                                <div class="col-sm-3 form-group">
                                  <label>Activity Category <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('components_id', $components, set_value('components_id'), 'class="custom-select" id="components_id"');
                                    echo form_error('components_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-3 form-group">
                                  <label>Display Result <span class="error"> *</span></label>
                                  <select name="add_result" id="add_result" class="custom-select">
                                    <option value="0">Default</option><option value="1">Show Add Result</option></select>
                                </div>
                                <div class="col-sm-6 form-group">
                                  <label>Enter Activity Status (Enter Name By Comma Separete)</label>
                                  <input type="text" name="activity_status" id="activity_status" value="<?php echo set_value('activity_status'); ?>" class="form-control">
                                  <?php echo form_error('activity_status'); ?>
                                </div>
                              
                                <div class="col-sm-2 form-group">
                                  <input type="submit" name="status_save" id='status_save' value="Submit" class="btn btn-success">
                                </div>
                              </div>
                                <?php echo form_close(); ?>
                             
                            </div>
                            <div class="tab-pane fade" id="tab2default">
                              </br>
                                <?php echo form_open(ADMIN_SITE_URL.'activity_log/activity_mode ', array('name'=>'frm_activity_mode','id'=>'frm_activity_mode')); ?>
                                 
                                <div class="row">
                                <div class="col-sm-4 form-group">
                                  <label>Activity Category <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('components_id', $components, set_value('components_id'), 'class="custom-select change_component" data-appedn_id="activity_mode_id" id="components_id"');
                                    echo form_error('components_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Status <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_mode_id', $activity_mode, set_value('activity_mode_id'), 'class="select2" id="activity_mode_id"');
                                    echo form_error('activity_mode_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Enter Activity Mode </label>
                                  <input type="text" name="activity_mode" id="activity_mode" value="<?php echo set_value('activity_mode'); ?>" class="form-control">
                                  <?php echo form_error('activity_mode'); ?>
                                </div>
                                
                                <div class="col-sm-2 form-group">
                                  <input type="submit" name="mode_save" id='mode_save' value="Submit" class="btn btn-success">
                                </div>
                                </div>
                                <?php echo form_close(); ?>
                              
                            </div>
                            <div class="tab-pane fade" id="tab3default">
                              <br>
                                <?php echo form_open(ADMIN_SITE_URL.'activity_log/activity_type ', array('name'=>'frm_activity_type','id'=>'frm_activity_type')); ?>
                                <div class="row">
                                <div class="col-sm-4 form-group">
                                  <label>Activity Category <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('components_id', $components, set_value('components_id'), 'class="custom-select change_component" data-appedn_id="activity_status_id" id="components_id"');
                                    echo form_error('components_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Status <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_status_id', $activity_mode, set_value('activity_status_id'), 'class="select2" id="activity_status_id"');
                                    echo form_error('activity_status_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Mode <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_mode_id', array(0=> 'Select'), set_value('activity_mode_id'), 'class="select2" id="activity_mode_id_appned"');
                                    echo form_error('activity_mode_id'); 
                                  ?>
                                </div>
                              </div>
                               <div class="row">
                                <div class="col-sm-8 form-group">
                                  <label>Enter Activity Type </label>
                                  <input type="text" name="activity_type" id="activity_type" value="<?php echo set_value('activity_type'); ?>" class="form-control">
                                  <?php echo form_error('activity_type'); ?>
                                </div>
                              </div>
                              <div class="row">
                               
                                <div class="col-md-2 col-sm-12 col-xs-2 form-group">
                                  <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
                                </div>
                              </div>
                                <?php echo form_close(); ?>
                             
                            </div>
                            <div class="tab-pane fade" id="tab4default">
                              <br>
                                <?php echo form_open(ADMIN_SITE_URL.'activity_log/activity_action ', array('name'=>'frm_activity_action','id'=>'frm_activity_action')); ?>
                                <div class="row">
                                <div class="col-sm-4 form-group">
                                  <label>Activity Category <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('components_id', $components, set_value('components_id'), 'class="select2 change_component" data-appedn_id="activity_status_id_action" id="components_id"');
                                    echo form_error('components_id'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Status <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_status_id_action', $activity_mode, set_value('activity_status_id_action'), 'class="select2" id="activity_status_id_action"');
                                    echo form_error('activity_status_id_action'); 
                                  ?>
                                </div>
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Mode <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_mode_id_type', array(0=> 'Select'), set_value('activity_mode_id_type'), 'class="select2" id="activity_mode_id_type"');
                                    echo form_error('activity_mode_id_type'); 
                                  ?>
                                </div>
                               </div>
                               <div class="row">
                                <div class="col-sm-4 form-group">
                                  <label>Select Activity Type <span class="error"> *</span></label>
                                  <?php
                                    echo form_dropdown('activity_mode_id_action', array(0=> 'Select'), set_value('activity_mode_id_action'), 'class="select2" id="activity_mode_id_action"');
                                    echo form_error('activity_mode_id_action'); 
                                  ?>
                                </div>
                                <div class="col-sm-8 form-group">
                                  <label>Enter Activity Action </label>
                                  <input type="text" name="activity_action" id="activity_action" value="<?php echo set_value('activity_action'); ?>" class="form-control">
                                  <?php echo form_error('activity_action'); ?>
                                </div>
                               </div>
                                <div class="row">
                                <div class="col-sm-8 form-group">
                                  <label>Remark</label>
                                  <input type="text" name="active_remarks" id="active_remarks" value="<?php echo set_value('active_remarks'); ?>" class="form-control">
                                  <?php echo form_error('active_remarks'); ?>
                                </div>
                                </div>
                                <div class="col-sm-2 form-group">
                                  <input type="submit" name="save" id='save' value="Submit" class="btn btn-success">
                                </div>
                                <?php echo form_close(); ?>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            
           
              <table id="datatable-responsivelog" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Created On</th>
                  <th>Created By</th>
                  <th>Component</th>
                  <th>Action</th>
                  <th>Mode</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>Verification Result</th>
                  <th>Remarks</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($all_activity_data as $key => $value) 
                  {

                     $add_result   = $value['add_result'] == "0" ? "No verification" : "Add Verification";
                      echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo "<td>".convert_db_to_display_date($value['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                      echo "<td>".$value['user_name']."</td>";
                      echo "<td>".$value['component_name']."</td>";
                      echo "<td>".$value['status_name']."</td>";
                      echo "<td>".$value['mode_name']."</td>";
                      echo "<td>".$value['type_name']."</td>";
                      echo "<td>".$value['action_name']."</td>";
                      echo "<td>".$add_result."</td>";
                      echo "<td>".$value['activity_remark']."</td>";
                      echo "</tr>";
                      $i++;
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
</div> 
<script>
$(document).ready(function(){

  $('#frm_activity_status').validate({ 
          rules: {
            components_id : {
              required : true,
              greaterThan : 0
            },
            activity_status : {
              required : true
            }
          },
          messages: {
            components_id : {
              required : "Select Activity"
            },
            activity_status : {
              required : "Enter Activity Status"
            }
          },
          submitHandler: function(form) 
          {      
              if($("#frm_activity_status").valid())
              {
                $("#status_save").attr("disabled", true);
                form.submit();
              }        
          }
  });

  $('#frm_activity_mode').validate({ 
      rules: {
        components_id : {
          required : true,
          greaterThan : 0
        },
        activity_mode_id : {
          required : true,
          greaterThan : 0
        },
        activity_mode : {
          required : true
        }
      },
      messages: {
        activity_mode_id : {
          required : "Select Activity"
        },
        activity_mode : {
          required : "Enter Activity Mode"
        }
      },
      submitHandler: function(form) 
      {      
          if($("#frm_activity_status").valid())
          {
            $("#mode_save").attr("disabled", true);
            form.submit();
          }        
      }
  });

  $('#frm_activity_type').validate({ 
      rules: {
        components_id : {
          required : true,
          greaterThan : 0
        },
        activity_mode_id : {
          required : true,
          greaterThan : 0
        },
        activity_type : {
          required : true,
        }
      },
      messages: {
        activity_mode_id : {
          required : "Select Activity"
        },
        activity_type : {
          required : "Enter Activity Mode"
        }
      },
      submitHandler: function(form) 
      {      
          if($("#frm_activity_status").valid())
          {
            $("#mode_save").attr("disabled", true);
            form.submit();
          }        
      }
  });

  $('#frm_activity_action').validate({ 
      rules: {
        components_id : {
          required : true,
          greaterThan : 0
        },
        activity_status_id_action : {
          required : true,
          greaterThan : 0
        },
        activity_mode_id_type : {
          required : true,
          greaterThan : 0
        },
        activity_mode_id_action : {
          required : true,
          greaterThan : 0
        },
        activity_mode : {
          required : true
        }
      },
      messages: {
        activity_mode_id : {
          required : "Select Activity"
        },
        activity_mode : {
          required : "Enter Activity Action"
        }
      },
      submitHandler: function(form) 
      {      
          if($("#frm_activity_status").valid())
          {
            $("#mode_save").attr("disabled", true);
            form.submit();
          }        
      }
  });

  $('#activity_status_id').on('change',function(){
      var activity_status = $(this).val();
      var selected = "<?php echo set_value('activity_status_id'); ?>";
      if(activity_status != 0)
      {
          $.ajax({
              type:'POST',
              url:'<?php echo ADMIN_SITE_URL.'activity_log/get_activity_dropdown_mode'; ?>',
              data:'activity_status='+activity_status+'&selected='+selected+'&name=activity_mode',
              beforeSend :function(){
              jQuery('#activity_mode_id_appned').find("option:eq(0)").html("Please wait..");
              },
              success:function(html)
              {
                jQuery('#activity_mode_id_appned').html(html);
              }
          });
      }
    });

  $('#activity_status_id_action').on('change',function(){
    var activity_status = $(this).val();
    var selected = "<?php echo set_value('activity_status_id'); ?>";
    if(activity_status != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'activity_log/get_activity_dropdown_mode'; ?>',
            data:'activity_status='+activity_status+'&selected='+selected+'&name=activity_mode',
            beforeSend :function(){
            jQuery('#activity_mode_id_type').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#activity_mode_id_type').html(html);
            }
        });
    }
  });

  $('.change_component').on('change',function(){
    var components_id = $(this).val();
    var append_id = $(this).attr("data-appedn_id");
    if(components_id != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'activity_log/get_stauts_by_component'; ?>',
            data:'components_id='+components_id+'&append_id='+append_id,
            beforeSend :function(){
              jQuery('#'+append_id).find("option:eq(0)").html("Please wait..");
            },
            success:function(html) {
              jQuery('#'+append_id).html(html);
            }
        });
    }
  });

  $('#activity_mode_id_type').on('change',function(){
    var activity_status = $(this).val();
    var selected = "<?php echo set_value('activity_status_id'); ?>";
    if(activity_status != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'activity_log/get_activity_dropdown_mode'; ?>',
            data:'activity_status='+activity_status+'&selected='+selected+'&name=activity_mode',
            beforeSend :function(){
            jQuery('#activity_mode_id_action').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#activity_mode_id_action').html(html);
            }
        });
    }
  });
  
  $('#datatable-responsivelog').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      scrollX: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },
      "lengthChange": true,
      "lengthMenu": [[25,50 ,-1], [25,50 ,"All"]]
  });  
  
}); 
</script>
<script type="text/javascript">
    $(".select2").select2();

        $(".select2-limiting").select2({
            maximumSelectionLength: 2
        });
</script>