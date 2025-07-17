<div class="box-body">
  <table id="datatable-acitivity_record" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Activity Status</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i = 1;
        foreach ($candiate_activity as $candiate_activitys)
        {
          $created_on = convert_db_to_display_date($candiate_activitys['created_on'],DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12);
          echo  "<tr><td>".$i."</td>";
          echo  "<td>".$created_on."</td>";
          echo  "<td>".$candiate_activitys['create_by']."</td>";
          echo  "<td>".$candiate_activitys['activity_status']."</td>";
          echo  "<td>".$candiate_activitys['remark']."</td>";
      
         
          echo "</tr>";
          $i++;
        }
      ?>
    </tbody>
  </table>
</div>
<script>
$(function () 
{
  $('#datatable-acitivity_record').DataTable({
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
      "lengthMenu": [[10,25, 50, 100, -1], [10,25, 50, 100, "All"]]
    });
});
</script>