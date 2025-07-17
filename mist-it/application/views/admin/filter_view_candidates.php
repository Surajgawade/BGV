<form>
  <div class="row">
  <div class="col-sm-2 form-group">
  <?php
   echo form_dropdown('filter_by_status_candidates', $status, set_value('filter_by_status_candidates','WIP'), 'class="select2" id="filter_by_status_candidates"');?>
   </div>
 <div class="col-sm-2 form-group">
  <?php $sub_status =  $sub_status[0] = 'Sub Status'; echo form_dropdown('filter_by_sub_status_candidates', $sub_status, set_value('filter_by_sub_status_candidates'), 'class="select2" id="filter_by_sub_status_candidates"');?>
 </div>
 


<div class="col-sm-2 form-group">
  <?php echo form_dropdown('filter_by_clientid_candidates', $clients, set_value('filter_by_clientid_candidates'), 'class="select2" id="filter_by_clientid_candidates"');?>
</div>

<div class="col-sm-2 form-group">
  <select id="filter_by_entity_candidates" name="filter_by_entity_candidates" class="select2"><option value="0">Select Entity</option></select>
</div>

<div class="col-sm-2 form-group">
    <select id="filter_by_package_candidates" name="filter_by_package_candidates" class="select2"><option value="0">Select Package</option></select>
</div>


<div class="col-sm-2 form-group">
     <input type="date" name="start_date" id="start_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
</div>
                   
 <div class="col-sm-2 form-group">
      <input type="date" name="end_date" id="end_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
 </div>

<!--<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <input type="text" name="start_end_date" id="start_end_date" class="form-control myDateRange" placeholder="Date Range" data-date-format="dd-mm-yyyy">
</div>-->
<div class="col-sm-2 form-group">
  <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
  <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
</div>

</div>
</form>
<div class="clearfix"></div>


<?php if(!empty($this->assign_options)) {
	echo '<div class="col-md-2 col-sm-12 col-xs-2 form-group">';
	echo form_dropdown('cases_assgin', $this->assign_options, set_value('cases_assgin'), 'class="custom-select" id="cases_assgin"');
	echo "</div>";
}
?>
<script type="text/javascript">
  $('#filter_by_status_candidates').css('background-color','Yellow');

  $('#filter_by_status_candidates').one("change",function(){
     $(this).css('background-color','white');

  });
</script>
<script type="text/javascript">
  $('#filter_by_entity_candidates').on('change',function(){
    var entity = $(this).val() || $('#filter_by_entity_candidates').val();
    var selected_paclage = $('#filter_by_package_candidates').val();
    if(entity != 0)
    {
        $.ajax({
            type:'POST',
            url:'<?php echo ADMIN_SITE_URL.'candidates/get_package_list'; ?>',
            data:'entity='+entity+'&selected_paclage='+selected_paclage,
            beforeSend :function(){
              jQuery('#filter_by_package_candidates').find("option:eq(0)").html("Please wait..");
            },
            success:function(html)
            {
              jQuery('#filter_by_package_candidates').html(html);
            }
        });
    }
  }).trigger('change');


  $('#filter_by_clientid_candidates').on('change',function(){
  var clientid = $(this).val();
  var entity = $('#filter_by_entity_candidates').val();
  if(clientid != 0)
  {
      $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'candidates/get_entity_list'; ?>',
          data:'clientid='+clientid+'&selected_entity='+entity,
          beforeSend :function(){
            jQuery('#filter_by_entity_candidates').find("option:eq(0)").html("Please wait..");
          },
          success:function(html)
          {
            jQuery('#filter_by_entity_candidates').html(html);
          }
      });
  }
}).trigger('change'); 

</script>
<script type="text/javascript">
  $(".select2").select2();
</script>