<form>
<div class="row">
 <div class="col-sm-3 form-group">
  <?php
   echo form_dropdown('filter_by_executive',$user_list_name, set_value('filter_by_executive',$this->user_info['id']), 'class="select2" id="filter_by_executive"');?>
</div>
<div class="col-sm-3 form-group">
  <?php echo form_dropdown('filter_by_status', $status, set_value('filter_by_status','WIP'), 'class="select2" id="filter_by_status"');?>
</div>
<div class="col-sm-3 form-group">
  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status', $sub_status, set_value('filter_by_sub_status'), 'class="select2" id="filter_by_sub_status"');?>
</div>
<div class="col-sm-3 form-group">
  <?php echo form_dropdown('filter_by_clientid', $clients, set_value('filter_by_clientid'), 'class="select2" id="filter_by_clientid"');?>
</div>
 
<div class="col-sm-3 form-group">
     <input type="date" name="start_date" id="start_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
</div>
                   
 <div class="col-sm-3 form-group">
      <input type="date" name="end_date" id="end_date" class="custom-select myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
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


<!--<?php if(!empty($this->assign_options)) {
	echo '<div class="col-sm-3 form-group">';
	echo form_dropdown('cases_assgin', $this->assign_options, set_value('cases_assgin'), 'class="custom-select" id="cases_assgin"');
	echo "</div>";
}
?>-->
<script type="text/javascript">
  $('#filter_by_status').css('background-color','Yellow');
  $('#filter_by_executive').css('background-color','Yellow');

  $('#filter_by_status').one("change",function(){
     $(this).css('background-color','white');
  });
  

  $('#filter_by_executive').one("change",function(){
       $('#filter_by_executive').css('background-color','white');
  });

</script>