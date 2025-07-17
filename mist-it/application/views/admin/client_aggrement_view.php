<?php 
if(!empty($client_aggr_details))
{
	//for ($i=0; $i < count($client_aggr_details); $i++) {
	?>
	<div class="row">

	<div class="col-sm-4 form-group">
	<label>Agreement Start Date </label>
	<input type="text" name="aggr_start[]" disabled id="aggr_start_1" value="<?php echo set_value("aggr_start",convert_db_to_display_date($client_aggr_details['aggr_start'])); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
	<?php echo form_error('aggr_start'); ?>
	</div> 
	<div class="col-sm-4 form-group">
	<label>Agreement End Date </label>
	<input type="text" name="aggr_end[]" disabled id="aggr_end_1" value="<?php echo set_value("aggr_end",convert_db_to_display_date($client_aggr_details['aggr_end'])); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
	<?php echo form_error('aggr_end'); ?>
	</div>
	<div class="col-sm-4 form-group">
	<label class="error">Agreement Attachment (Please upload single PDF File)</label>
	<input type="file" name="aggrement_file[]" accept=".pdf" multiple id="aggrement_file_1" value="<?php echo set_value('aggrement_file');?>" class="form-control" disabled>
	<?php echo form_error('aggrement_file'); ?>
	</div>
   </div>
	<?php 
	//}
}
else
{ ?>
	<div class="row">
	<div class="col-sm-4 form-group">
	<label>Agreement Start Date </label>
	<input type="text" name="aggr_start[]" id="aggr_start" value="<?php echo set_value('aggr_start'); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
	<?php echo form_error('aggr_start'); ?>
	</div> 
	<div class="col-sm-4 form-group">
	<label>Agreement End Date </label>
	<input type="text" name="aggr_end[]" id="aggr_end" value="<?php echo set_value('aggr_end'); ?>" class="form-control myDatepickerFuture" placeholder='DD-MM-YYYY'>
	<?php echo form_error('aggr_end'); ?>
	</div>
	<div class="col-sm-4 form-group">
	<label class="error">Agreement Attachment (Please upload single PDF File)</label>
	<input type="file" name="aggrement_file[]" accept=".pdf" multiple id="aggrement_file" value="<?php echo set_value('aggrement_file');?>" class="form-control">
	<?php echo form_error('aggrement_file'); ?>
	</div>
    </div>
<?php } ?>
<script>
$('.myDatepickerFuture').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});
</script>