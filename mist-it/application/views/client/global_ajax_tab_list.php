<?php 
if(count($global_cand_lists) >= '1')
{
  if(in_array('globdbver', explode(',',$component_check))) 
  { 
  ?>

    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='global_add_frm' data-url="<?= CLIENT_SITE_URL.'globdver/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Global</button>
  <?php 
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='global_add_frm' data-url="<?= CLIENT_SITE_URL.'globdver/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Global</button>
<?php
}
?>

<div class="clearfix"></div>
<br>
  <table id="tbl_datatable_gb"  class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">     
   <thead>
        <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Address Type</th>
          <th>Address</th>
          <th>State</th>
          <th>City</th>
          <th>Pin Code</th>
        </tr>
  </thead>
    <tbody>
    <?php

    if($global_cand_lists == "Not Permission")
    {
        echo "<div id = 'permission_global'></div>";
    }
    else
    {
      $counter = 1;
      if(count($global_cand_lists) > 0)
      {
      
        foreach($global_cand_lists as $global_cand_list)
        {
          echo  "<tr><td>".$counter."</td>";
          echo  "<td>".convert_db_to_display_date($global_cand_list['iniated_date'])."</td>";
          echo  "<td>".$global_cand_list['address_type']." </td>";
          echo  "<td>".$global_cand_list['street_address']." </td>";
          echo  "<td>".$global_cand_list['state']."</td>";
          echo  "<td>".$global_cand_list['city']." </td>";
          echo  "<td>".$global_cand_list['pincode']." </td>";
          echo "</tr>";
          $counter++;
        }
      }
      else
      {
         echo "<tr><td colspan='7' style='text-align:center;'>No Record Found</td></tr>";
      }

    }
    ?>
  </tbody>
</table>
<div class="global_add_frm"></div>

<script type="text/javascript">
  $(document).ready(function(){

     if ($("#permission_global").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
  

});
</script>

<script>
$(function ()  {
  
 var add_otable = $('#tbl_datatable_gb').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "ordering": true,
      "autoWidth": false,
      "bInfo": false,
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

  $('#tbl_datatable_gb tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'global_database/view_from_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.global_add_frm').html(html);
            }
        });
    }
  });

});
</script>

