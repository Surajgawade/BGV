<form>
  <div class="row">
    <div class="col-sm-3 form-group">
      <?php echo form_dropdown('clientids', $clients_ids, set_value('clientids'), 'class="custom-select" id="clientids"');?>
    </div>

     
    <div class="col-sm-3 form-group">
        <?php
          echo form_dropdown('entitys', array(), set_value('entitys'), 'class="custom-select" id="entitys"');
          echo form_error('entitys');
        ?>
    </div>
                       
     <div class="col-sm-3  form-group">
         <select id="packages" name="packages" class="custom-select"><option value="0">Select</option></select>
     </div>
    
     <div class="col-sm-3 form-group">
     <input type="text" name="start_dates" id="start_dates" class="form-control myDateRange" placeholder="Date Range" value = "<?php echo date('d-m-Y') ?>" data-date-format="dd-mm-yyyy">
   </div>

    <div class="col-sm-3 form-group">
     <input type="text" name="end_dates" id="end_dates" class="form-control myDateRange" placeholder="Date Range" value = "<?php echo date('d-m-Y') ?>" data-date-format="dd-mm-yyyy">
   </div>

    <div class="col-sm-3  form-group">
      <input type="button" name="searchrecords_annexure" id="searchrecords_annexure" class="btn btn-md btn-info" value="Filter">
      <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
    </div>
  </div> 
</form>
<script type="text/javascript">
$('.myDateRange').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight: true,
      autoclose: true
  });

</script>
