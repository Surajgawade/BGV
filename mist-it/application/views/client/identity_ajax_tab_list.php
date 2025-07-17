<?php 
if(count($identity_lists) >= '1')
{
  if(in_array('identity', explode(',',$component_check))) 
  { 
  ?>

    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='identity_add_frm' data-url="<?=  CLIENT_SITE_URL.'identity/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Identity</button>
<?php 
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='identity_add_frm' data-url="<?=  CLIENT_SITE_URL.'identity/add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Identity</button>
  
<?php
}
?>
<div class="clearfix"></div>
<br>
<table id="tbl_datatable_identity" class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
    <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Document</th>
          <th>Id Number</th>
      
    </tr>
    </thead>
    
    <tbody>
    <?php
    if($identity_lists == "Not Permission")
    {
        echo "<div id = 'permission_identity'></div>";
    }
    else
    {
      $counter = 1;

      if(count($identity_lists) > 0)
      {
        foreach($identity_lists as $identity_list)
        {
          echo  "<tr><td>".$counter."</td>";
          echo  "<td>".convert_db_to_display_date($identity_list['iniated_date'])."</td>";
          echo  "<td>".$identity_list['doc_submited']."</td>";
          echo  "<td>".$identity_list['id_number']." </td>";
          echo "</tr>";
          $counter++;
        }
      }
      else
      {
          echo "<tr><td colspan='4' style='text-align:center;'>No Record Found</td></tr>";
      }
    }
    ?>

  </tbody>
</table>
<div class="identity_add_frm"></div>
<script type="text/javascript">
  $(document).ready(function(){

      if ($("#permission_identity").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
  
   
});
</script>
<script>
$(function ()  {

 var add_otable = $('#tbl_datatable_identity').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
       scrollX: true,
       fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
      },
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

  $('#tbl_datatable_identity tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'identity/view_from_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.identity_add_frm').html(html);
            }
        });
    }
      
});
});
</script>
