<div class="content-wrapper">
    <section class="content-header">
      <h1>Candiidate Details Batch Update</h1>
      <ol class="breadcrumb">
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
              <div class="col-md-8 col-sm-12 col-xs-8 form-group">
                <label >Select Client <span class="error"> *</span></label>
                <?php
                  echo form_dropdown('clientid',$clients, set_value('clientid'), 'class="form-control" id="clientid"');
                  echo form_error('clientid');
                ?>
              </div>
              <div class="clearfix"></div>
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
              
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </section>
</div>
<script>
$(document).ready(function(){
  
  $('#frm_batch_update').validate({
    rules : { 
      universityname : {
        required : true
      }
    },
    messages: {
      universityname : {
        required : "Enter University"
      }
    },
    submitHandler: function(form) 
    {
      $.ajax({
        url : '<?php echo ADMIN_SITE_URL.'universities/add_university'; ?>',
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