<div class="box-body">
  <table id="datatable-insuff_view" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Created By</th>
        <th>Created Date</th>
        <th>Raised Remark</th>
        <th>Cleared By</th>
        <th>Cleared Date</th>
        <th>Remarks</th>
        <th>Insuff Days</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $i = count($insuff_details);
        foreach ($insuff_details as $insuff_detail)
        {
          $Raised = convert_db_to_display_date($insuff_detail['insuff_raised_date']);
          echo  "<tr><td>".$i."</td>";
          echo  "<td>".$insuff_detail['insuff_raised_by']."</td>";
          echo  "<td>".$Raised."</td>";
          echo  "<td>".$insuff_detail['insuff_raise_remark']."</td>";
          echo  "<td>".$insuff_detail['insuff_cleared_by']."</td>";
          echo  "<td>".convert_db_to_display_date($insuff_detail['insuff_clear_date'])."</td>";
          echo  "<td>".$insuff_detail['insuff_remarks']."</td>";
          echo  "<td>".$insuff_detail['hold_days']."</td>";
          $access_insuff = ($this->permission['access_reference_list_insuff_edit']) ? $insuff_detail['id'] : '';
          $access_insuff_clear = ($this->permission['access_reference_list_insuff_clear']) ? $insuff_detail['id'] : '';
          $access_insuff_delete = ($this->permission['access_reference_list_insuff_delete']) ? $insuff_detail['id'] : '';
         

          echo "<td><button class='btn btn-xs clkInsuffRaiseModel' data-editUrl=".$access_insuff."> <i class='fa fa-edit'></i> Edit</button> ";
          echo " <button class='btn btn-xs insuffClearModel' data-editUrl=".$access_insuff_clear." ><i class='fa fa-edit'></i> Clear</button> ";
          echo " <button class='btn btn-xs insuffDelete' data-editUrl=".$access_insuff_delete."><i class='fa fa-trash'></i> Delete</button></td>";
          echo "</tr>";
          $i--;
        }
      ?>
    </tbody>
  </table>
</div>
<script>
$(function () 
{
  $('#datatable-insuff_view').DataTable({
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
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
    });

  $('#add_new_address').on('click',function(){
    var id = '<?php echo ADMIN_SITE_URL.'employment/'; ?>';
    $('.modal-body').load(id,function(){
      $('#myModal').modal('show');
    });
  });
});
</script>