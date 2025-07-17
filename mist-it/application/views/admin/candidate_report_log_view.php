<div class="box-body">
  <table id="datatable-insuff_view" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Component</th>
        <th>Details</th>
        <th>Created On</th>
        <th>Created By</th>
        
       <!-- <th>Type(Interim/Final)</th>-->
        
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    
    <?php
      $i = count($report_details);
        foreach ($report_details as $report_detail)
        {
           if($report_detail['component_type'] == 1)
           {
             $component_type = "Address";
           }
           elseif($report_detail['component_type'] == 2)
           {
             $component_type = "Employment";
           }
           elseif($report_detail['component_type'] == 3)
           {
             $component_type = "Education";
           }
           elseif($report_detail['component_type'] == 4)
           {
             $component_type = "Reference";
           }
           elseif($report_detail['component_type'] == 5)
           {
             $component_type = "Court";
           }
           elseif($report_detail['component_type'] == 6)
           {
             $component_type = "Global Database";
           }
           elseif($report_detail['component_type'] == 7)
           {
             $component_type = "Drugs";
           }
           elseif($report_detail['component_type'] == 8)
           {
             $component_type = "PCC";
           }
           elseif($report_detail['component_type'] == 9)
           {
             $component_type = "Identity";
           }
           elseif($report_detail['component_type'] == 10)
           {
             $component_type = "Credit Report";
           }
            
          
          echo  "<tr><td>".$i."</td>";
          echo  "<td>".$component_type."</td>";
          echo  "<td>".$report_detail['remarks']."</td>";
          echo  "<td>".convert_db_to_display_date($report_detail['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
          echo  "<td>".$report_detail['username']."</td>";

          echo "<td><button class='btn btn-xs clkInsuffRaiseModel' > <i class='fa fa-eye'></i>View</button> ";
          echo " <button class='btn btn-xs insuffClearModel' ><i class='fa fa-download'></i>Download</button> ";
          echo " <button class='btn btn-xs insuffDelete' ><i class='fa fa-mail'></i>Email</button></td>";
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


