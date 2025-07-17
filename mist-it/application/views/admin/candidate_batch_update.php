<div class="content-wrapper">
    <section class="content-header">
      <h1>Candidates -  Candidate Details Batch Update</h1>
      <ol class="breadcrumb">
        <?php $access_import = ($this->permission['access_import']) ? '#myModelImport'  : ''; ?>
        <li><button class="btn btn-sm btn-default" data-toggle="modal" data-target="#myModelTemplate"><i class="fa fa-download"></i> Template</button></li> 

        <li><button class="btn btn-sm btn-default" data-toggle="modal" data-target="<?php echo $access_import;?>"><i class="fa fa-download"></i> Import Attachment</button></li>
        <li><button class="btn btn-default btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>candidates"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <?php echo form_open_multipart('#', array('name'=>'frm_batch_update','id'=>'frm_batch_update')); ?>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label >Select Client <span class="error"> *</span></label>
                <?php
                  echo form_dropdown('clientid',$clients, set_value('clientid'), 'class="form-control" id="clientid"');
                  echo form_error('clientid');
                ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label >Select Entiy<span class="error"> *</span></label>
                <?php
                  echo form_dropdown('entity', array(), set_value('entity'), 'class="form-control" id="entity"');
                  echo form_error('entity');
                ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label >Select Package<span class="error"> *</span></label>
                 <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
                <?php echo form_error('package');?>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <label>Upload File</label>
                <input type="file" name="cands_bulk_sheet" accept=".xlsx,.xls" id="cands_bulk_sheet" class="form-control">
              </div>
              <div class="clearfix"></div>
              <div class="box-body">
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                <input type="submit" name="btn_add" id='btn_add' value="Submit" class="btn btn-success">
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </section>
</div>
<div id="myModelImport" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Import Candidate Attachment</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_attchment','id'=>'frm_attchment')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8 col-sm-12 col-xs-8 form-group">
            <label>Upload File</label>
            <input type="file" name="attachment_zip" accept=".zip" id="attachment_zip" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-md" id="btn_attchment">Upload</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>

  </div>
</div>
<div id="myModelTemplate" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Candidate Column Template</h4>
      </div>
      <?php echo form_open_multipart('#', array('name'=>'frm_batch_update_temp','id'=>'frm_batch_update_temp')); ?>   
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-sm-12 col-xs-6 form-group">
            <label>Select Columns<span class="error"> *</span></label>
            <?php
              echo form_multiselect('columns[]', $columns , set_value('columns'), 'class="form-control multiSelect" id="columns"');
              echo form_error('columns');
            ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info btn-md" id="btn_download">Download</button>
        <button type="button" class="btn btn-default btn-md" data-dismiss="modal" >Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>

  </div>
</div>

<script>
$(document).ready(function(){
  
  $('#btn_download').on('click',function(){
    var columns = $('#columns').val();
    
    if(columns != '') { 
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'candidates/batch_update_template'; ?>',
          data : 'columns='+columns,
          type: 'post',
          dataType:'json',
          beforeSend:function(){
              $('#btn_download').text('downloading..');
              $('#btn_download').attr('disabled','disabled');
            },
            complete:function(){
              $('#btn_download').text('Template');
             // $('#btn_download').removeAttr('disabled');                
            },
            success: function(jdata){
              $("#myModelTemplate .close").click()
              $('#frm_batch_update_temp')[0].reset();
              var message =  jdata.message || '';
              if(jdata.status == <?php echo SUCCESS_CODE; ?>) {
                show_alert(message,'success'); 
              } else {
                show_alert(message,'error'); 
              }
            }
          }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name+".xlsx");
            $a[0].click();
            $a.remove();
        });
    } else {
      show_alert('Select Columns','error'); 
      return false;
    }
  });

  $('#frm_attchment').validate({ 
      rules: {
        attachment_zip : {
          required : true,
          extension : 'zip'
        }
      },
      messages: {
        attachment_zip : {
          required : "Select File",
          extension : 'Select Zip File to Upload'
        }
      },
      submitHandler: function(form) 
      {      
            $.ajax({
              url : '<?php echo ADMIN_SITE_URL.'candidates/batch_attchment'; ?>',
              data : new FormData(form),
              type: 'post',
              contentType:false,
              cache: false,
              processData:false,
              dataType:'json',
              beforeSend:function(){
                $('#btn_attchment').attr('disabled','disabled');
              },
              complete:function(){
                $('#btn_attchment').removeAttr('disabled');
              },
              success: function(jdata){
                var message =  jdata.message || '';
                if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                  show_alert(message,'success');
                }else {
                  show_alert(message,'error'); 
                }
              }
            });    
      }
  });

  $('#frm_batch_update').validate({
    rules : { 
      clientid : {
        required : true,
        greaterThan : 0
      },
      cands_bulk_sheet : {
        required : true,
        extension : '.xlsx|.xls'
      }
    },
    messages: {
      clientid : {
        required : "Select Client"
      },
      cands_bulk_sheet : {
        required : "Select Excel file to update",
        extension : "Upload valid excel file"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'candidates/frm_batch_update'; ?>',
        data : new FormData(form),
        type: 'post',
        contentType:false,
        cache: false,
        processData:false,
        dataType:'json',
        beforeSend:function(){
          $('#btn_add').attr('disabled',true);
        },
        complete:function(){
          $('#btn_add').attr('disabled',false);
        },
        success: function(jdata){
          var message =  jdata.message || '';
          if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
            show_alert(message,'success');
            $('#frm_batch_update')[0].reset();
          }else {
            show_alert(message,'error'); 
          }
        }
      });           
    }
  });

  $('#clientid').on('change',function(){
    var clientid = $(this).val();
    if(clientid != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/cmp_ref_no'; ?>',
            data:'clientid='+clientid,
            success:function(jdata) {
              if(jdata.status = 200)
              {
                $('#cmp_ref_no').val(jdata.cmp_ref_no);
                $('#entity').empty();
                $.each(jdata.entity_list, function(key, value) {
                  $('#entity').append($("<option></option>").attr("value",key).text(value));
                });
              }
            }
        });
    }
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

});
</script>