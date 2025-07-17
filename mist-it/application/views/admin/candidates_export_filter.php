<div class="content-wrapper">
    <section class="content-header">
      <h1>Candidate Export</h1>
      <ol class="breadcrumb">
        <li><button class="btn btn-default btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>reports"><i class="fa fa-arrow-left"></i> Back</button></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="box box-primary">
            <?php echo form_open('#', array('name'=>'frm_cg_add','id'=>'frm_cg_add')); ?>
              <div class="box-body">
                <div class="box-header">
                  <h3 class="box-title">Export Filter</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                  <label >Select Client <span class="error"> *</span></label>
                  <?php
                    echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="form-control" id="clientid"');
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
                  <label >Package<span class="error"> *</span></label>
                   <select id="package" name="package" class="form-control"><option value="0">Select</option></select>
                  <?php echo form_error('package');?>
                </div>
                <div class="clearfix"></div>
                <div class="box-body">
                  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <input type="submit" name="save" id='save' value="Generate" class="btn btn-success">
                    <input type="reset" name="reset" id='reset' value="Reset" class="btn btn-primary">
                  </div>
                </div>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<script>
$(document).ready(function(){
  
  $('#frm_cg_add').validate({ 
    rules: {
      clientid : {
        required : true,
        greaterThan: 0
      },
      entity : {
        required : true,
        greaterThan : 0
      },
      package : {
        required : true,
        greaterThan : 0
      }
    },
    messages: {
      clientid : {
        required : "Enter Client Name"
      },
      entity : {
        required : "select Entiy",
        greaterThan : "select Entiy"
      },
      package : {
        required : "select Package",
        greaterThan : "select Package"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'candidates/export'; ?>',
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
              //$('#save').removeAttr('disabled');
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
                window.location = jdata.redirect;
                return;
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
        });    
      }
  });

  $(document).on('change', '#clientid', function(){
  var clientid = $(this).val();

  if(entity != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'clients/get_entity_list'; ?>',
          data:'clientid='+clientid,
          beforeSend :function(){
            jQuery('#entity').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#entity').html(html);
          }
      });
  }
});
  
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
</script>