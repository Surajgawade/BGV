<?php 
if(count($court_list) >= '1')
{
  if(in_array('courtver', explode(',',$component_check))) 
  { 
  ?>

    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='court_add_frm' data-url="<?= CLIENT_SITE_URL.'courtver/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Court</button>
  <?php 
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='court_add_frm' data-url="<?= CLIENT_SITE_URL.'courtver/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Court</button>
<?php
}
?>

<div class="clearfix"></div>
<br>
  <table id="tbl_datatable_court"  class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;"> 
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
      if($court_list == "Not Permission")
      {
          echo "<div id = 'permission_court'></div>";
      }
      else
      {
        $counter = 1;

        if(count($court_list) > 0)
        {
                  
          foreach($court_list as $court_lis)
          {
            echo  "<tr><td>".$counter."</td>";
            echo  "<td>".convert_db_to_display_date($court_lis['iniated_date'])."</td>";
            echo  "<td>".$court_lis['address_type']." </td>";
            echo  "<td>".$court_lis['street_address']." </td>";
            echo  "<td>".$court_lis['state']."</td>";
            echo  "<td>".$court_lis['city']." </td>";
            echo  "<td>".$court_lis['pincode']." </td>";
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

<div class="court_add_frm"></div>

<script type="text/javascript">
  $(document).ready(function(){

      if ($("#permission_court").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }

});
</script>

<script>
$(function ()  {

 var add_otable = $('#tbl_datatable_court').DataTable({
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

  $('#tbl_datatable_court tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'court_verificatiion/view_form_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.court_add_frm').html(html);
            }
        });
    }
  });

});
</script>
