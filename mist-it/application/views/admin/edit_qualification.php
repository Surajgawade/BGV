<div class="col-md-12 col-sm-12 col-xs-12 form-group">
  <label>Qualification<span class="error"> *</span></label>
    <?php 
    echo form_dropdown('qualification', $qualification_list, set_value('qualification',$qualification), 'class="select2 cls_disabled" id="qualification"');
    echo form_error('qualification'); 
    ?>
</div>
<div class="clearfix"></div>
<!--<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>   
<script type="text/javascript">
  $('.singleSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });
</script>-->

<script type="text/javascript">
    $(".select2").select2();
</script>