<form>
<div class="row">
 <div class="col-sm-3 form-group">
  <?php
   echo form_dropdown('filter_by_executive_assign',$user_list_name, set_value('filter_by_executive_assign',$this->user_info['id']), 'class="select2" id="filter_by_executive_assign"');?>
</div>
<div class="col-sm-3 form-group">
  <?php echo form_dropdown('filter_by_status_assign', $status, set_value('filter_by_status_assign','WIP'), 'class="select2" id="filter_by_status_assign"');?>
</div>
<div class="col-sm-3 form-group">
  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status_assign', $sub_status, set_value('filter_by_sub_status_assign'), 'class="select2" id="filter_by_sub_status_assign"');?>
</div>
<div class="col-sm-3 form-group">
  <?php echo form_dropdown('filter_by_clientid_assign', $clients, set_value('filter_by_clientid_assign'), 'class="select2" id="filter_by_clientid_assign"');?>
</div>
 
<div class="col-sm-3 form-group">
     <input type="date" name="start_date_assign" id="start_date_assign" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
</div>
                   
 <div class="col-sm-3 form-group">
      <input type="date" name="end_date_assign" id="end_date_assign" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
 </div>


<!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <input type="text" name="start_end_date" id="start_end_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
</div>-->
<div class="col-sm-4 form-group">
  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
</div>
</div>
</form>
<div class="clearfix"></div>


<?php if(!empty($this->assign_options)) {
	echo '<div class="col-sm-3 form-group">';
	echo form_dropdown('cases_assgin_vendor',  $this->assign_options_vendor, set_value('cases_assgin_vendor'), 'class="custom-select" id="cases_assgin_vendor"');
	echo "</div>";
}
?>
<script type="text/javascript">
  $('#filter_by_status_assign').css('background-color','Yellow');
  $('#filter_by_executive_assign').css('background-color','Yellow');

  $('#filter_by_status_assign').one("change",function(){
     $(this).css('background-color','white');
  });
  

  $('#filter_by_executive_assign').one("change",function(){
       $('#filter_by_executive_assign').css('background-color','white');
  });
 
</script>