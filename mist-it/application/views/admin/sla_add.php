<div class="content-wrapper">
    <section class="content-header">
      <h1>Setting</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL.'SLA/'?>"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $component_details['component_name'];?> SLA Setting</h3>
            </div>
            <div class="box-body">
              <?php echo form_open('#', array('name'=>'frm_sla','id'=>'frm_sla')); ?>
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Particulars<span class="error"> *</span></label>
                  <input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$component_details['id']); ?>">
                  <input type="text" name="particulars" id="particulars" value="<?php echo set_value('particulars'); ?>" class="form-control">
                  <?php echo form_error('particulars'); ?>
                </div>
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Selection <span class="error">(Multiple section enter by comma separated) *</span></label>
                  <input type="text" name="section" id="section" value="<?php echo set_value('section'); ?>" class="form-control">
                  <?php echo form_error('section'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label>Default Selection <span class="error">*</span></label>
                  <input type="text" name="selected_selection" id="selected_selection" value="<?php echo set_value('selected_selection'); ?>" class="form-control">
                  <?php echo form_error('selected_selection'); ?>
                </div>
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label >Remark</label>
                  <textarea name="remarks" id="remarks" class="form-control"><?php echo set_value('remark'); ?></textarea>
                  <?php echo form_error('remarks'); ?>
                </div>
                
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_add" name="btn_add" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
            <div class="box-body">
              <table id="tbl_datatable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Particulars</th>
                    <th>Section</th>
                    <th>Default</th>
                    <th>Remarks</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($SLA_settings as $SLA_setting)
                  {
                    echo  "<tr class='' data-accessUrl=".ADMIN_SITE_URL.'SLA/default_setting/'.$SLA_setting['id']." ><td>".$i."</td>";
                    echo "<td>".$SLA_setting['particulars']."</td>";
                    echo "<td>".$SLA_setting['section']."</td>";
                    echo "<td>".$SLA_setting['selected_selection']."</td>";
                    echo "<td>".$SLA_setting['remarks']."</td>";
                    echo "<td><button class='btn btn-sm'>Delete</button></td>";
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
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Scope of work</h3>
            </div>
            <div class="box-body">
              <?php echo form_open('#', array('name'=>'frm_scope_of_work','id'=>'frm_scope_of_work')); ?>
                <input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$component_details['id']); ?>">
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label >Scop of Word Option </label>
                  <textarea name="scop_of_word" id="scop_of_word" class="form-control"><?php echo set_value('scop_of_word'); ?></textarea>
                  <?php echo form_error('scop_of_word'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_scope_of_work" name="btn_scope_of_work" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
            <div class="box-body">
              <table id="tbl_datatable_scope" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Scop of Word</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($scope_of_work as $scope_of_wor)
                  {
                    echo  "<tr><td>".$i."</td>";
                    echo "<td>".$scope_of_wor['scop_of_word']."</td>";
                    echo "<td><button class='btn btn-sm'>Delete</button></td>";
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
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Mode of Verification</h3>
            </div>
            <div class="box-body">
              <?php echo form_open('#', array('name'=>'frm_mode_of_verification','id'=>'frm_mode_of_verification')); ?>
                <input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$component_details['id']); ?>">
                <div class="col-xs-6 col-md-6 col-sm-12">
                  <label >Mode of Verification </label>
                  <textarea name="mode_of_verification" id="mode_of_verification" class="form-control"><?php echo set_value('mode_of_verification'); ?></textarea>
                  <?php echo form_error('mode_of_verification'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 col-sm-12">
                  <button type="submit" id="btn_mode_of_vefi" name="btn_mode_of_vefi" class="btn btn-primary">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
            <div class="box-body">
              <table id="tbl_datatable_mode" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Mode of Verification</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  foreach ($mode_of_verification as $mode_of_verificatio)
                  {
                    echo  "<tr><td>".$i."</td>";
                    echo "<td>".$mode_of_verificatio['mode_of_verification']."</td>";
                    echo "<td><button class='btn btn-sm'>Delete</button></td>";
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
    </section>
    <div class="test"></div>
</div>
<script src="<?= SITE_JS_URL; ?>jquery.dataTables.min.js"></script>
<script src="<?= SITE_JS_URL; ?>dataTables.bootstrap.min.js"></script>
<script>
$(document).ready(function(){

  $('#tbl_datatable,#tbl_datatable_mode,#tbl_datatable_scope').DataTable({
    "pageLength": 15,
    "lengthMenu": [[5,25, 50,100, -1], [5,25, 50,100, "All"]]
  });

  $('#frm_sla').validate({ 
    rules: {
      component_id : {
        required : true
      },
      particulars : {
        required : true
      },
      section : {
        required : true
      },
      selected_selection : {
        required : true
      }
    },
    messages: {
      component_id : {
        required : "Component ID missing"
      },
      particulars : {
        required : "Enter Particulars"
      },
      section : {
        required : "Enter section"
      },
      selected_selection : {
        required : "Enter Default Section"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'client_settings/save_sla_setting'; ?>',
        data : $( form ).serialize(),
        type: 'post',
        dataType:'json',
        beforeSend:function(){
          $('#btn_add').attr('disabled','disabled');
        },
        complete:function(){
          $('#btn_add').removeAttr('disabled');                
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            return;
          }
          else{
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  $('#frm_scope_of_work').validate({ 
    rules: {
      component_id : {
        required : true
      },
      scop_of_word : {
        required : true
      }
    },
    messages: {
      component_id : {
        required : "Component ID missing"
      },
      scop_of_word : {
        required : "Enter Scop of Word Option"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'client_settings/save_scope_setting'; ?>',
        data : $( form ).serialize(),
        type: 'post',
        dataType:'json',
        beforeSend:function(){
          $('#btn_scope_of_work').attr('disabled','disabled');
        },
        complete:function(){
          $('#btn_scope_of_work').removeAttr('disabled');                
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            return;
          }
          else{
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  $('#frm_mode_of_verification').validate({ 
    rules: {
      component_id : {
        required : true
      },
      mode_of_verification : {
        required : true
      }
    },
    messages: {
      component_id : {
        required : "Component ID missing"
      },
      mode_of_verification : {
        required : "Enter Mode Of Verification Option"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'client_settings/save_mode_of_verification'; ?>',
        data : $( form ).serialize(),
        type: 'post',
        dataType:'json',
        beforeSend:function(){
          $('#btn_mode_of_vefi').attr('disabled','disabled');
        },
        complete:function(){
          $('#btn_mode_of_vefi').removeAttr('disabled');                
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            location.reload();
            return;
          }
          else{
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });
});
</script>