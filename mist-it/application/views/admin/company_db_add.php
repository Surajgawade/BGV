<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h4 class="page-title">Suspicious Company</h4>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>company_database">Suspicious Company</a></li>
                  <li class="breadcrumb-item active">Suspicious Company Add</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                  <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>company_database"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
    </div>

    
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
         
              <?php echo form_open('#', array('name'=>'add_company','id'=>'add_company')); ?>
                <?php echo $form_view; ?>
                <div class="clearfix"></div>
                <div class="col-sm-6">
                  <button type="submit" id="company_add" name="company_add" class="btn btn-success">Submit</button>
                </div>
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="test"></div>
</div>
<script>
$(document).ready(function(){
  $('#add_company').validate({ 
      rules: {
        coname : {
          required : true
        },
        address : {
          required : true
        },
        city : {
          required : true,
          lettersonly : true
        },
        pincode : {
          required : true,
          minlength : 6,
          maxlength : 6
        },
        state : {
          required : true,
          greaterThan: 0
        },
        co_email_id : {
          multiemails : true
        },
        cc_email_id : {
           multiemails : true
        }
      },
      messages: {
        coname : {
          required : "Enter Name"
        },
        address : {
          required : "Enter Address"
        },
        city : {
          required : "Enter Address"
        },
        pincode : {
          required : "Enter Pincode"
        },
        state : {
          required : "Select State",
          greaterThan : "Select State",
        },
        co_email_id:{
         multiemails : "Enter Valid Email Id"
        },
        cc_email_id :{
           multiemails : "Enter Valid Email ID"
        }
      },
      submitHandler: function(form) 
      {      
          $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'company_database/save_company'; ?>',
          data: new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#company_add').attr('disabled','disabled');
          },
          complete:function(){
            //$('#company_add').removeAttr('disabled');                
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == <?php echo SUCCESS_CODE; ?>)
            {
              $('#add_company')[0].reset();
              show_alert(message,'success');
            }
            else
            {
              show_alert(message,'error');
            }
          }
        });      
      }
  });
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
    
    jQuery.validator.addMethod('matches', function (value, element) {
    return this.optional(element) || /^[a-z0-9](\.?[a-z0-9]){5,}@m$/i.test(value);
    }, "use be a  e-mail address");
</script>