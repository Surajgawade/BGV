<div class="content-wrapper">
    <section class="content-header">
      <h1>Identity Export</h1>
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
                  <h3 class="box-title">Filters</h3>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-4 form-group">
                    <label>Select Filter<span class="error"> *</span></label>
                    <select id="filter_option" name="filter_option" class="form-control">
                      <option>Select</option>
                      <optgroup label="Case Details">
                        <option value="candidates_info.clientid">Client</option>
                        <option value="candidates_info.caserecddate">Case Received Date</option>
                      </optgroup>
                      <optgroup label="Check Details">
                        <option value="addrver.iniated_date">Initiation Date</option>
                        <option value="addrver.address_type">Address type</option>
                        <option value="addrver.address">Street Address</option>
                        <option value="addrver.city">City</option>
                        <option value="addrver.pincode">Pincode</option>
                        <option value="addrver.state">State</option>
                        <option value="addrver.has_case_id">Executive Name</option>
                      </optgroup>
                      <optgroup label="Result Details">
                        <option value="addrverres.verfstatus">Status</option>
                        <option value="addrverres.verf_sub_status">Sub Status</option>
                        <option value="addrverres.closuredate">Closure Date</option>
                        <option value="addrverres.mode_of_verification">Mode of verification</option>
                        <option value="addrverres.first_qc_approve">QC Status</option>
                        <option value="addrver.tat_status">TAT Status</option>
                        <option value="addrver.due_date">Check Close Date</option>
                      </optgroup>
                    </select>
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
  
  $('#filter_option').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      maxHeight: 200
  });

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
            url : '<?php echo ADMIN_SITE_URL.'reports/identity_export'; ?>',
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
             // $('#save').removeAttr('disabled');
            },
            success: function(jdata){
            }
        }).done(function(jdata){
            var $a = $("<a>");
            $a.attr("href",jdata.file);
            $("body").append($a);
            $a.attr("download",jdata.file_name);
            $a[0].click();
            $a.remove();
        });;    
      }
  });  
});
</script>