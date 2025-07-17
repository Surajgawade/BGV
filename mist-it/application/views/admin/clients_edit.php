<div class="content-page">
<div class="content">
<div class="container-fluid">

   <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Client</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>clients">Clients</a></li>
                  <li class="breadcrumb-item active">Clients Edit</li>
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
                <div class="text-white div-header">
                 Client Details
                </div>
                <br>
                  <div style="float: right;">
                    <button class="btn btn-secondary nav-item waves-effect waves-light btn-sm edit_btn_click" data-frm_name='frm_update_client' data-editUrl='<?= ($this->permission['access_clients_list_edit']) ? encrypt($client_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit</button>
                    <button class="btn btn-warning nav-item waves-effect waves-light btn-sm delete" id="<?php echo encrypt($client_details['id']); ?>"  data-accessUrl="<?php echo ($this->permission['access_clients_list_delete']) ? ADMIN_SITE_URL.'candidates/delete/'.encrypt($client_details['id']) : '' ?>"><i class="fa fa-trash"></i> Delete</button>
                  </div>
                  <div class="clearfix"></div>
                  <?php echo form_open_multipart('#', array('name'=>'frm_update_client','id'=>'frm_update_client')); ?>
                    <div class="row">
                      <div class="col-sm-4 form-group">
                        <label >Client Name <span class="error"> *</span></label>
                        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$client_details['id']); ?>">
                        <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$client_details['clientname']); ?>" class="form-control">
                        <?php echo form_error('clientname'); ?>
                      </div>
                      <div class="col-sm-4 form-group">
                        <label>Client Manager<span class="error"> *</span></label>
                        <?php
                        
                          echo form_dropdown("clientmgr", $clientmgr, set_value('clientmgr',$client_details['clientmgr']), 'class="form-control select2" id="clientmgr"');
                          echo form_error('clientmgr');
                        ?>
                      </div>
                     
                      <div class="col-sm-4 form-group">
                        <label>Sales Manager <span class="error"> *</span></label>
                        <?php
                      
                          echo form_dropdown("sales_manager", $sales_manager, set_value('sales_manager',$client_details['sales_manager']), 'class="form-control select2" id="sales_manager"');
                          echo form_error('sales_manager');
                        ?>
                      </div>
                       
                    </div>
                       
                      <div id="append_aggrement"></div>
                      <p id="addClientAggr"></p>
                     

                      <div class="row">
                      <div class="col-sm-4 form-group">
                        <label >Logo <span class="error">(Existing logo will override)</span></label>
                        <input type="file" name="comp_logo" accept=".png, .jpg, .jpeg" id="comp_logo" value="<?php echo set_value('comp_logo');?>" class="form-control">
                        <?php echo form_error('comp_logo'); ?>
                      </div>
                      </div>
                       <div class="clearfix"></div>

                    <div class="col-sm-4  form-group">
                         
                        <?php
                             if(!empty($logo[0]['comp_logo']))
                             {
          
                              echo  "<h5> Logo </h5>"; 
                           foreach ($logo as $key => $value) {
                               
                              $url  = SITE_URL.CLIENT_LOGO;
                             
                                ?><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['comp_logo']; ?>", "myWin", "height=250,width=480"); 
                           return false'><?= $value['comp_logo']?></a><?php
                            
                            }

                          }
                       ?>
                   </div>


                    <div class="col-sm-8 form-group">

                         <?php 
                             if(!empty($attachments[0]['aggrement_file']))
                             {

                              echo  "<h5> Attachment </h5>";

                            foreach ($attachments as $key => $value) {
                            
                              $url  = SITE_URL.CLIENT.'/';
                             
                                ?><li><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['aggrement_file']; ?>", "myWin", "height=250,width=480"); 
                           return false'><?= $value['aggrement_file']?></a></li> <?php
                            
                            }

                          }
                        ?>

                    </div>

                                                 
                      <div class="clearfix"></div>
                      
                      <div class="box-header">
                        <div class="text-white div-header">
                           Client Portal Users
                        </div>
                        <br>
                        <div style="float: right;">
                          <button type="btn btn-secondary nav-item waves-effect waves-light btn-sm button" style="float: right;" class="btn btn-info btn-xs" id="addClientPortalModal"><i class="fa fa-plus"></i> Add Users</button>
                          </div>
                      </div>
                      <div class="clearfix"></div>
                      
                      <div class="clearfix"></div>
                      <div id='append_client_acc'></div>
                      <div class="clearfix"></div>
                      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                        <input type="submit" name="btn_client_save" id='btn_client_save' value="Update" class="btn btn-primary">
                      </div>
                    
                  <?php echo form_close(); ?>
          
              <!--</div>-->
            <!--  <div class="tab-pane" id="entity_pack_details">-->
                 <div class="text-white div-header">
                  Entity Package Details
                 </div>
                 <br>
                  <div style="float: right;">
                    <button class="btn btn-secondary nav-item waves-effect waves-light btn-sm edit_btn_click" type="button" data-frm_name='frm_update_component' data-editUrl='<?= ($this->permission['access_clients_list_edit']) ? encrypt($client_details['id']) : ''?>'><i class="fa fa-edit"></i> Edit </button>

                    <button class="btn btn-info nav-item waves-effect waves-light btn-sm float-left" type="button" id="addEntityPackage"  data-value="<?= ($this->permission['access_clients_list_add']) ? '1' : '0'; ?>"><i class="fa fa-plus"></i> Entity and Package </button>

                  </div>
                  <div class="clearfix"></div>
                   <div class="clearfix"></div>
                  <div class="box-body">
                    <table id="tbl_datatable" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                      <tr>
                        <th>#</th>
                        <th>Created By</th>
                        <th>Created On</th>
                        <th>Entity </th>
                        <th>Package</th>
                        <th>Component</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody id="tbl_rows">
                        <?php
                          $count = 0;
                          foreach ($entitypackages as $key => $entitypackage) {
                            $count = $key+1;
                              
                            $component = explode(",",$entitypackage['component_id']);
                            $comp = [];
                            if(in_array("addrver",$component))
                            {
                               $comp[] = "Address";
                            }
                            if(in_array("courtver",$component))
                            {
                               $comp[] = "Court";
                            }
                            if(in_array("globdbver",$component))
                            {
                               $comp[] = "Global Database";
                            }
                            if(in_array("narcver",$component))
                            {
                               $comp[] = "Drugs</br>";
                            }
                             if(in_array("refver",$component))
                            {
                               $comp[] = "Reference";
                            }
                            if(in_array("empver",$component))
                            {
                               $comp[] = "Employment";
                            }
                             
                            if(in_array("eduver",$component))
                            {
                               $comp[] = "Education";
                            }
                            if(in_array("identity",$component))
                            {
                               $comp[] = "Identity</br>";
                            }
                           
                             if(in_array("cbrver",$component))
                            {
                               $comp[] = "Credit Report";
                            }
                            if(in_array("crimver",$component))
                            {
                               $comp[] = "PCC";
                            }
                            if(!empty($comp))
                            {
                            $comp1 = implode(", ", $comp);
                            } 
                            else
                            {
                              $comp1 = '';
                            }
                                                       
                          
                            echo "<tr class='tr_entity_package".$count."' data-entity_id='".$entitypackage['entity_id']."' data-client_id ='".$client_details['id']."' data-package_id='".$entitypackage['package_id']."' >";
                            echo "<td>".$count."</td>";
                            echo "<td>".$entitypackage['created_by']."</td>";
                            echo "<td>".convert_db_to_display_date($entitypackage['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                            echo "<td>".$entitypackage['entity_name']."</td>";
                            echo "<td>".$entitypackage['package_name']."</td>";
                            echo "<td>".$comp1."</td>";
                            echo "<td><button> Duplicate</button>  <button> Delete</button> </td>";
                           // echo "";
                            echo "</tr>";
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php echo form_open('#', array('name'=>'frm_update_component','id'=>'frm_update_component')); ?>
                    <div id='load_view'></div>
                    <div class="box-body" id="submit_tbi">
                      <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                        <input type="submit" name="btn_update_data" id='btn_update_data' value="Update" class="btn btn-primary">
                      </div>   
                    </div>
                  <?php echo form_close(); ?>
                </section>
             <!-- </div>-->
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<div id="addModalEntityPackage" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <?php echo form_open('#', array('name'=>'frm_entity_package','id'=>'frm_entity_package')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Create Enity Package</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <input type="hidden" name="copy" id="copy" value="1">
          <input type="hidden" name="tbl_client_id" id="tbl_client_id" value="<?php echo set_value('tbl_client_id',$client_details['id']);?>" class="append_client_id"> 
          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isPackage' class="entity_package" value="isEntity" checked>Add Entity</label>
            <label class="radio-inline"><input type="radio" name="entity_package" data-entity_package='isEntity' class="entity_package" value="isPackage">Add Package</label>
          </div>
          <div class="clearfix"></div>
          <div id="isEntity">
            <div class="col-md-12 col-sm-12 col-xs-12 form-group" id="idEntity">
              <label >Entity (Enter multiple entity by comma separated)<span class="error"> *</span></label>
              <input type="text" name="Entity" id="Entity" value="<?php echo set_value('Entity');?>" class="form-control">
              <?php echo form_error('Entity'); ?>
            </div>
          </div>
          <div id="isPackage" style="display: none;">
            <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
              <label >Select Entity<span class="error"> *</span></label>
              <?php
               
               echo form_dropdown('entity_list', $entity_list , set_value('entity_list'), 'class="form-control append_entity" id="entity_list"');
                echo form_error('entity_list');
                  
              ?>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
              <label>Package<span class="error"> *</span></label>
              <input type="text" name="package" id="package" value="<?php echo set_value('package');?>" class="form-control ">
              <?php echo form_error('package'); ?>
            </div>
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

$(document).ready(function(){

  $('#submit_tbi').hide();
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
   
    var entity = $('.tr_entity_package'+row_data[0]).attr('data-entity_id');

    var new_add = $('.tr_entity_package'+row_data[0]).attr('data-new_add');
    
    var package = $('.tr_entity_package'+row_data[0]).attr('data-package_id');
   
    var client_id = $('.tr_entity_package'+row_data[0]).attr('data-client_id');
    if(entity != "" && package !="")
    {
        $.ajax({
            type:'POST',
            url : '<?php echo ADMIN_SITE_URL.'clients/frm_edit_entity_package_view' ?>',
            data : 'entity='+entity+'&package='+package+'&client_id='+client_id,
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('#submit_tbi').show();
              jQuery('#load_view').html(html);
            }
        });
    }
  });

  $('#addClientPortalModal').click(function(){
    $.ajax({
      url : '<?php echo ADMIN_SITE_URL.'clients/client_portal_view'?>',
      data : 'tbl_client_id='+$('#tbl_client_id').val(),
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
  }).trigger('click');

  $('#addClientAggr').click(function(){
    $.ajax({
      url : '<?php echo ADMIN_SITE_URL.'clients/client_aggrement_view/'.$client_details['id']; ?>',
      data : '',
      type: 'post',
      beforeSend:function(){
        //$('#roles_id').attr('disabled','disabled');
      },
      complete:function(){
        //$('#roles_id').removeAttr('disabled');
      },
      success: function(html){
        $('#append_aggrement').append(html);
      }
    });
  }).trigger('click');

  

  $('#frm_update_client').validate({ 
      rules: {
        update_id : {
          required : true,
          greaterThan : 0
        },
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
        update_id : {
          required : "Client ID Missing",
          greaterThan : "Client ID Missing"
        },
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
            url : '<?php echo ADMIN_SITE_URL.'clients/update_client'; ?>',
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
             // $('#btn_client_save').attr('disabled',false);
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>' && jdata.client_id != 0){
                show_alert(message,'success');
                location.reload();
                //window.location = jdata.redirect;
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });      
      }
  });

  $('#addEntityPackage').click(function(){
    var id = '<?php echo ADMIN_SITE_URL.'clients/entity_package_view'; ?>';
    var data_value = $(this).attr("data-value");
  
    $('.append_model').load(id,function(){
     if(data_value == 1)
     {
        $('#addModalEntityPackage').modal('show');
        $('#addModalEntityPackage').addClass("show");
        $('#addModalEntityPackage').css({background: "#0000004d"}); 
   
     }
     else
     {
         show_alert('Access Denied, You don’t have permission to access this page');
     }
    }); 

  });
  
  $('#frm_entity_package').validate({ 
    rules: {
      copy : {
        required : true
      },
      tbl_client_id : {
        required : true,
        greaterThan : 0
      },
      entity_package : {
        required : true
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
      entity_package : {
        required : "Enter Client Name"
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
        url : '<?php echo ADMIN_SITE_URL.'clients/save_entity_package/'?>',
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
          $('#btn_entity_package').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            if(jdata.list_show)
            {
              show_alert(message,'success',true);
              return;
            }
            show_alert(message,'success');
            $('#entity_list').empty();
            $.each(jdata.entity_list, function(key, value) {
              $('#entity_list').append($("<option></option>").attr("value",key).text(value));
            });
          } else {
            show_alert(message,'error'); 
          }
          $('#entity_package_view').modal('hide');
        }
      });      
    }
  });

  $('#frm_update_component').validate({ 
    rules: {
      update_id : {
        required : true,
        greaterThan : 0
      },
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
      report_type : {
        required : true
      },
      first_qc : {
        required : true
      },
      final_qc : {
        required : true
      },
      auto_report : {
        required : true
      },
      insuff_report : {
        required : true
      },
      case_type : {
        required : true
      },
      "spoc_manager_email[]" :{
        multiemails : true
      },
      'components[]' : {
        required : true
      }
      
    },
    messages: {
      update_id : {
        required : "Update ID Misssing",
        greaterThan : "Update ID Misssing"
      },
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
      report_type : {
        required : "Select Report Type"
      },
      first_qc : {
        required : "Select First QC"
      },
      final_qc : {
        required : "Select Final QC"
      },
      auto_report : {
        required : "Select Auto Report"
      },
      insuff_report : {
        required : "Select Insuff Report"
      },
      case_type : {
        required : "Select Case Type"
      },
      'components[]' : {
        required : 'Select atleast one component'
      },
      "spoc_manager_email[]" :{
        multiemails : "Insert Proper Email ID"
      }
    },
    submitHandler: function(form) 
    {
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'clients/update_clients_component'; ?>',
            data : new FormData(form),
            type: 'post',
            contentType:false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend:function(){
              $('#btn_update_data').attr('disabled','disabled');
              $('.body_loading').show();
            },
            complete:function(){
             // $('#btn_update_data').attr('disabled',false);
              $('.body_loading').hide();
            },
            success: function(jdata){
              var message =  jdata.message || '';
              (typeof(jdata.upload_error) != 'undefined') ? show_alert(upload_error,'error') : '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success',true);
                window.location = jdata.redirect;
              }else {
                show_alert(message,'error'); 
              }
            }
        });      
      }
  });

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
            jQuery('#package').html(html);
          }
      });
  }
});

$(window).on('load', function() {
  $('.multiSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });

  $("#frm_update_client :input").prop("disabled", true);
});
</script>
<script type="text/javascript">
 
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
</script>
<script type="text/javascript">
  $(document).on('click', '.delete', function(){  
           var client_id = $(this).attr("id");

           if(confirm("Are you sure you want to delete this?"))  
           {  
                $.ajax({  
                     url:"<?php echo ADMIN_SITE_URL.'clients/delete';?>",  
                     method:"POST",  
                     data:{client_id:client_id},  
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