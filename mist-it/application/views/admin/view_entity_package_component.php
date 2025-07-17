
<div class="filterable">
<table id="tbl_datatable" class="table table-bordered table-hover">
  <thead>
    <tr class="filters">
	<th> <input type="text" class="form-control" placeholder="ID"></th>
	<th> <input type="text" class="form-control" placeholder="Entity Name"> </th>
	<th> <input type="text" class="form-control" placeholder="Package Name"> </th>
	<th> <input type="text" class="form-control" placeholder="Count"> </th>
	<th> <input type="text" class="form-control" placeholder="Component"> </th>
   </thead>
</tr>
<?php
$x=1;
foreach ($candidate_details as $candidate_detail)
{
  $count = explode(',',$candidate_detail['component_id']);
  $count_array = count($count);  
?>

  <tr>
  	<td> <?php echo $x?> </td>
  	<td> <?php echo $candidate_detail['entity_name'];?> </td>
    <td> <?php echo $candidate_detail['package_name'];?> </td>
    <td> <?php echo $count_array;?> </td>
  	<td> <?php echo $candidate_detail['component_id'];?> </td>

  </tr>
<?php
$x++;
}
?>
</table>
</div>
<script type="text/javascript">
  $(document).ready(function(){
  
    $('.filterable .btn-filter').click(function(){

        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
      
        /* Ignore tab key */
        var code = e.keyCode || e.which;
      
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');

        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
        var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});
</script>
<style type="text/css">
  .filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
    size:5px;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
    text-align: center; 
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
    text-align: center; 

}

</style>



