<div class="content-page">
<div class="content">
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Create Client</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>clients">Clients</a></li>
                  <li class="breadcrumb-item active">Clients Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>clients"><i class="fa fa-arrow-left"></i> Back</button></li>  
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
               
            <br>
            <div class="tab-content">
             <!-- <div class="active tab-pane" id="activity">-->
              
                  <?php echo form_open_multipart('#', array('name'=>'frm_create_client','id'=>'frm_create_client')); ?>

                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label >Client Name <span class="error"> *</span></label>
                        <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname'); ?>" class="form-control">
                        <?php echo form_error('clientname'); ?>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Client Manager<span class="error"> *</span></label>
                        <?php
                          echo form_dropdown("clientmgr", $clientmgr, set_value('clientmgr'), 'class="form-control select2" id="clientmgr"');
                          echo form_error('clientmgr');
                        ?>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Sales Manager <span class="error"> *</span></label>
                        <?php
                         
                          echo form_dropdown("sales_manager", $sales_manager, set_value('sales_manager'), 'class="form-control select2" id="sales_manager"');
                          echo form_error('sales_manager');
                        ?>
                      </div>
                    </div>
                     <div class="row">
                        <div class="col-sm-2 form-group">
                          <label>Agreement Start Date </label>
                          <input type="text" name="aggr_start[]" id="aggr_start" value="<?php echo set_value('aggr_start'); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
                          <?php echo form_error('aggr_start'); ?>
                        </div> 
                        <div class="col-sm-2 form-group">
                          <label>Agreement End Date </label>
                          <input type="text" name="aggr_end[]" id="aggr_end" value="<?php echo set_value('aggr_end'); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
                          <?php echo form_error('aggr_end'); ?>
                        </div>
                        <div class="col-sm-4 form-group">
                          <label class="error">Agreement (Please upload single PDF File)</label>
                          <input type="file" name="aggrement_file[]" accept=".pdf" multiple id="aggrement_file" value="<?php echo set_value('aggrement_file');?>" class="form-control">
                          <?php echo form_error('aggrement_file'); ?>
                      </div>

                      <div class="col-sm-4 form-group">
                        <label class="error">Logo (Please Upload Image)</label>
                        <input type="file" name="comp_logo" accept=".png, .jpg, .jpeg" id="comp_logo" value="<?php echo set_value('comp_logo');?>" class="form-control">
                        <?php echo form_error('comp_logo'); ?>
                      </div>
                    
                    </div>
                    
                      <div id='append_client_acc'></div>
                      <div class="clearfix"></div>
                      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                        <input type="submit" name="btn_client_save" id='btn_client_save' value="Save" class="btn btn-primary">
                      </div>
                    </div>
                  <?php echo form_close(); ?>
               
           <!--   </div>-->
             <!-- <div class="tab-pane" id="entity_pack_details">-->
             <!--   <section>
                  <button style="float: right;" type="button" class="btn btn-info btn-sm float-left" data-toggle="modal" data-target="#addEntityPackage"><i class="fa fa-plus"></i> Entity and Package</button>
                  <div class="box-body">
                    <table id="tbl_datatable" class="table table-bordered table-hover">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Entity </th>
                        <th>Package</th>
                      </tr>
                      </thead>
                      <tbody id="tbl_rows"></tbody>
                    </table>
                  </div>
                  <?php echo form_open('#', array('name'=>'frm_save_component','id'=>'frm_save_component')); ?>
                    <div id='load_view'></div>
                    <div class="box-body" id="submit_tbi">
                      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                        <input type="submit" name="btn_entity_save" id='btn_entity_save' value="Submit" class="btn btn-primary">
                        <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-info">
                      </div>   
                    </div>
                  <?php echo form_close(); ?>
                </section>-->
            <!--  </div>-->
            </div>
          </div>
        </div>
      </div>
  </div>  
</div>
</div>
</div>

<div id="addEntityPackage" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'frm_entity_package','id'=>'frm_entity_package')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Enity Package</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <input type="hidden" name="copy" id="copy" value="0">
          <input type="hidden" name="tbl_client_id" id="tbl_client_id" value="" class="append_client_id"> 
          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isPackage' class="entity_package" value="isEntity" checked>Add Entity</label>
            <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isEntity' class="entity_package" value="isPackage">Add Package</label>
          </div>
          <div class="clearfix"></div>
          <div id="isEntity">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="idEntity">
              <label >Entity (Enter multiple entity by comma separated)<span class="error"> *</span></label>
              <input type="text" name="Entity" id="Entity" value="<?php echo set_value('Entity');?>" class="form-control ">
              <?php echo form_error('Entity'); ?>
            </div>
          </div>
          <div id="isPackage" style="display: none;">
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label >Select Entity<span class="error"> *</span></label>
              <?php
               echo form_dropdown('entity_list',array(), set_value('entity_list'), 'class="form-control append_entity" id="entity_list"');
                echo form_error('entity_list'); 
              ?>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-6 form-group">
              <label>Package<span class="error"> *</span></label>
              <input type="text" name="package" id="package" value="<?php echo set_value('package');?>" class="form-control ">
              <?php echo form_error('package'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" id="btn_entity_package" name="btn_entity_package" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div id="sla_view" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'frm_sla','id'=>'frm_sla')); ?>
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">SLA</h4>
    </div>
    <div class="modal-body append_model"></div>
    <div class="modal-footer">
      <button type="submit" id="btn_sla" name="btn_sla" class="btn btn-success">Submit</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>
function laod_entity_datatable(data)
{
  $('#tbl_datatable').dataTable().fnDestroy();
  var table_view = '';
  var counter = 1;
  $.each( data, function( index, value ){
    table_view += '<tr class="tbl_entity_package'+counter+'" data-entity_id='+value['entity_id']+' data-package_id='+value['package_id']+'>'
    table_view += '<td>'+counter+'</td>'
    table_view += '<td>'+value['created_by']+'</td>'
    table_view += '<td>'+value['created_on']+'</td>'
    table_view += '<td>'+value['entity_name']+'</td>'
    table_view += '<td>'+value['package_name']+'</td>'
    table_view += '</tr>'
    counter++;
  });
  $('#tbl_rows').html(table_view);

  var otable = $('#tbl_datatable').DataTable({
      "columnDefs": [{ "orderable": false, "targets": 0 }],
      "order": [[ 0, "asc" ]],
      "searching": false,
      "bFilter" : false,               
      "bLengthChange": false,
      "bPaginate": false
  });

  $('#tbl_datatable tbody').on( 'dblclick', 'tr', function () {
    var row_data = otable.row( this ).data();
    console.log(row_data);
    var entity = $('.tbl_entity_package'+row_data[0]).attr('data-entity_id');
    var package = $('.tbl_entity_package'+row_data[0]).attr('data-package_id');
    var client_id = $('#tbl_client_id').val();
    if(entity != "" && package != '' && client_id != "")
    {
        $.ajax({
            type:'POST',
            url : 'frm_entity_package_view',
            data : 'entity='+entity+'&package='+package+'&client_id='+client_id,
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html) {
              $('#submit_tbi').show();
              jQuery('#load_view').html(html);
            }
        });
    }
  });
}

$(document).ready(function(){

  $('#submit_tbi').hide();
  $('#frm_create_client').validate({ 
      rules: {
        clientname : {
          required : true
        },
        clientmgr : {
          required : true,
          greaterThan: 0
        },
        sales_manager : {
          required : true,
          greaterThan: 0
        },
        comp_logo : {
          extension : 'jpg|jpeg|png',
          filesize : 2
        },
        'aggrement_file[]' : {
          extension : 'PDF',
          filesize : 2
        }
      },
      messages: {
        clientname : {
          required : "Enter Client Name"
        },
        clientmgr : {
          required : "Select Client Manager",
          greaterThan: "Select Client Manager"
        },
        sales_manager : {
          required : "Select Sales Manager",
          greaterThan: "Select Sales Manager"
        },
        comp_logo : {
          extension : 'Image file allowed'
        },
        aggrement_file : {
          'extension[]' : 'PDF file allowed'
        }
      },
      submitHandler: function(form) 
      {
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'clients/save_clients'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_client_save').attr('disabled','disabled');
            },
            complete:function(){
             // $('#btn_client_save').attr('disabled','disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>' && jdata.return_client_id != 0)
              {
                show_alert(message,'success');
                window.location = jdata.redirect;
               // $('.append_client_id').prop("value", jdata.return_client_id);
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });      
      }
  });

  $('#frm_entity_package').validate({ 
    rules: {
      tbl_client_id : {
        required : true,
        greaterThan : 0
      },
      Entity : {
        required : true
      },
      entity_list : {
        required : true,
        greaterThan : 0
      },
      package : {
        required : true
      }
    },
    messages: {
      tbl_client_id : {
        required : "Select Client",
        greaterThan : "Select Client"
      },
      Entity : {
        required : 'Enter Entity'
      },
      entity_list : {
        required : "Select Entity",
        greaterThan : "Select Entity"
      },
      package : {
        required : "Enter Package"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : 'save_entity_package',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_entity_package').attr('disabled','disabled');
        },
        complete:function(){
         // $('#btn_entity_package').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            if(jdata.list_show)
            {
              laod_entity_datatable(jdata.entity_package_list);
            }
            else
            {
              $('#Entity').attr('value', '');
              $('#entity_list').html();
              $.each(jdata.entity_list, function(key, value) {
              $('#entity_list').append($("<option></option>").attr("value",key).text(value));
              });
            }
          } else {
            show_alert(message,'error'); 
          }
          if(jdata.list_show == '1')
          {
          $('#addEntityPackage').modal('hide');
          }
        }
      });      
    }
  });

  $('#frm_save_component').validate({ 
    rules: {
      client_id : {
        required : true,
        greaterThan : 0
      },
      selected_entity : {
          required : true
      },
      selected_package : {
          required : true
      },
      clientaddress : {
        required : true
      },
      clientcity : {
        required : true
      },
      clientpincode : {
        required : true
      },
      'components[]' : {
        required : true
      }
    },
    messages: {
      client_id : {
        required : "Client ID Misssing",
        greaterThan : "Client ID Misssing"
      },
      selected_entity : {
        required : "Entity ID required"
      },
      selected_package : {
        required : "package ID required"
      },
      clientaddress : {
        required : "Enter Address"
      },
      clientcity : {
        required : "Enter City"
      },
      clientpincode : {
        required : "Enter Pincode"
      },
      'components[]' : {
        required : 'Select atleast one component'
      }
    },
    submitHandler: function(form) 
    {
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'clients/save_clients_component'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#save').attr('disabled','disabled');
            },
            complete:function(){
             // $('#save').attr('disabled',false);
            },
            success: function(jdata){
              var message =  jdata.message || '';
              (typeof(jdata.upload_error) != 'undefined') ? show_alert(upload_error,'error') : '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                $("#frm_save_component :input").prop("disabled", true);
                location.reload();
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });      
      }
  });

  /*$('#addClientPortalModal').click(function(){
    
    $.ajax({
      url : 'client_portal_view',
      data : '',
      type: 'post',
      beforeSend:function(){
        //$('#roles_id').attr('disabled','disabled');
      },
      complete:function(){
        //$('#roles_id').removeAttr('disabled');
      },
      success: function(html){
        $('#append_client_acc').append(html);
      }
    });
  }).trigger('click');*/
});

$(document).on('click', '.entity_package', function(){
  var radiohide = $("input[name='entity_package']:checked").data('entity_package');
  var radioshow = $("input[name='entity_package']:checked").val();
  $('#'+radiohide).hide();
  $('#'+radioshow).show();
});

$(document).on('click', '.clkSlaModel', function(){
  $('#sla_view').modal('show');
});

$(document).on('click', '#addSpocModal', function(){
  var as = '<div class="clearfix"></div>'+$('.getSpocDiv').html();
  $('#appendSpocModal').append(as)
});

$(document).on('change', '#entity', function(){
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
            jQuery('.append_package').html(html);
          }
      });
  }
});
</script>