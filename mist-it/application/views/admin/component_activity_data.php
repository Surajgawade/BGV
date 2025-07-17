<div class="box-body">
  <table id="datatable-activity-data"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
      <tr>
        <th>Ordered Table</th>
        <th>Created On</th>
        <th>Created By</th>
        <th>Action</th>
        <th>Mode</th>
        <th>Type</th>
        <th>Status</th>
        <th>Next FollowUp</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>
         <?php 
            foreach ($logs as $key => $value) {
                  
                echo '<tr>';
                echo "<td>".$value['created_on']."</td>";
                echo "<td>".convert_db_to_display_date($value['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                echo "<td>".$value['res_created_by']."</td>";
                echo "<td>".$value['action']."</td>";
                echo "<td>".$value['activity_mode']."</td>";
                echo "<td>".$value['activity_type']."</td>";
                echo "<td>".$value['activity_status']."</td>";
                echo "<td>".convert_db_to_display_date($value['next_follow_up_date'])."</td>";
                echo "<td>".$value['remarks']."</td>";
                echo '</tr>';  
              
            }?>
    </tbody>
  </table>
</div>
<script>
$(function () 
{
  $('#datatable-activity-data').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      "searching": false,
      scrollX: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },

      "lengthChange": true,
      "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]],
      "order": [[ 0, 'desc' ]],
      'columnDefs': [
           {
              "targets": [ 0 ],
              "visible": false
           }
        ],
    });
    
});
</script>