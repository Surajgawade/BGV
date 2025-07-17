<?php 
if(count($reference_lists) >= '1')
{
  if(in_array('reference', explode(',',$component_check))) 
  { 
  ?>

    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='reference_add_frm' data-url="<?=  CLIENT_SITE_URL.'reference/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Reference</button>
<?php 
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='reference_add_frm' data-url="<?=  CLIENT_SITE_URL.'reference/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Reference</button>
  
<?php
}
?>
<div class="clearfix"></div>
  <br>
<table id="tbl_datatable_reference"  class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
   <thead>
        <tr >
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Reference Name</th>
          <th>Designation</th>
          <th>Contact</th>
          <th>Email</th>
        </tr>
  </thead>
    <tbody>
    <?php
    if($reference_lists == "Not Permission")
    {
        echo "<div id = 'permission_reference'></div>";
    }
    else
    {
      $counter = 1;

      if(count($reference_lists) > 0)
      {
      
        foreach($reference_lists as $reference_list)
        {
          echo  "<tr><td>".$counter."</td>";
          echo  "<td>".convert_db_to_display_date($reference_list['iniated_date'])."</td>";
          echo  "<td>".$reference_list['name_of_reference']."</td>";
          echo  "<td>".$reference_list['designation']."</td>";
          echo  "<td>".$reference_list['contact_no']."</td>";
          echo  "<td>".$reference_list['email_id']."</td>";
         
          echo "</tr>";
          $counter++;
        }
      }
      else
      {
        echo "<tr><td colspan='6' style='text-align:center;'>No Record Found</td></tr>";
      }
    }
    ?>
  </tbody>
</table>
<div class="reference_add_frm"></div>
<script type="text/javascript">
  $(document).ready(function(){

      if ($("#permission_reference").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
  
});
</script>
<script>
$(function ()  {


 var add_otable = $('#tbl_datatable_reference').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "ordering": true,
      "bInfo": false,
      "autoWidth": false,
      "language": {"emptyTable": "No Record Found"},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

  add_otable.columns.adjust().draw();


  add_otable.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
       if ( that.search() !== this.value ) {
           that.search( this.value ).draw();
       }
    });
  });

  $('#tbl_datatable_reference tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'reference_verificatiion/view_from_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.reference_add_frm').html(html);
            }
        });
    }
  });

});
</script>


