<div class="col-md-4 col-sm-8 col-xs-4 form-group">
    <label>Company Name<span class="error"> *</span></label>
    <?php
     echo form_dropdown('nameofthecompany', $company, set_value('nameofthecompany',$nameofthecompany), 'class="form-control singleSelect cls_disabled" id="nameofthecompany1"');
      echo form_error('nameofthecompany'); 
    ?>
</div>
<div class="clearfix"></div>
  <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Deputed Company</label>
    <input type="text" name="deputed_company" id="deputed_company1" value="<?php echo set_value('deputed_company',str_replace("||",' ', $deputed_company)); ?>" class="form-control cls_disabled">
    <?php echo form_error('deputed_company'); ?>
  </div>
  <div class="clearfix"></div>

<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>   
<script type="text/javascript">
  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200,
       onChange: function() {
          $('#deputed_company1').val($("#nameofthecompany1 option:selected").text());
      }
  });
</script>