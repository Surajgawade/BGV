<?php 
if(count($address_lists) >= '1')
{
  if(in_array('addrver', explode(',',$component_check))) 
  { 
    if($address_details_component_check == "1") 
    {
    
  ?>
    
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_edit/'.$cand_id  ?>" ><i class="fa fa-minus"></i> Update </button>
  <?php
    }
    else 
    {

    ?>
     <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Address</button>
    <?php
    }
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='add_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/address_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i> Address</button>
<?php
}
?>
<div class="clearfix"></div>

  <table id="tbl_datatable_add"  class="table table-bordered"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Comp Ref No</th>
          <th>Address Type</th>
          <th>Stay From</th>
          <th>Stay To</th>
          <th>Address</th>
          <th>State</th>
          <th>City</th>
          <th>Pincode</th>
        </tr>
  </thead>
    <tbody>
        <?php
           if($address_lists == "Not Permission")
            {
              echo "<div id = 'permission_address'></div>";
            }
           else
           {   
                $counter = 1;

                if(count($address_lists) > 0)
                {
                    
                    foreach($address_lists as $address_list)
                    {
                      echo  "<tr>";
                      echo  "<td>".$counter."</td>";
                      echo  "<td>".convert_db_to_display_date($address_list['iniated_date'])."</td>";
                      echo  "<td>".$address_list['add_com_ref']."</td>";
                      echo  "<td>".$address_list['address_type']." </td>";
                      echo  "<td>".$address_list['stay_from']." </td>";
                      echo  "<td>".$address_list['stay_to']." </td>";
                      echo  "<td>".$address_list['address']." </td>";
                      echo  "<td>".$address_list['state']." </td>";
                      echo  "<td>".$address_list['city']." </td>";
                      echo  "<td>".$address_list['pincode']." </td>";
                      echo "</tr>";
                      $counter++;
                    }
                }
                else
                {
                  echo "<tr><td colspan='9' style='text-align:center;'>No Record Found</td></tr>";
                }

            }
          ?>
    </tbody>
  </table>

<div class="add_add_frm"></div>
  
<script type="text/javascript">
  $(document).ready(function(){

  if ($("#permission_address").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
  
});
</script>
<script>
$(function ()  {
 
 var add_otable = $('#tbl_datatable_add').DataTable({
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

  $('#tbl_datatable_add tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'Candidate_mail/address_view_form_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.add_add_frm').html(html);
            }
        });
    }
  });

});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $(".append_candidate_tab").click(function() {
      $('html, body').animate({
        scrollTop: $(".add_add_frm").offset().top
      }, 2000);
   });
 });

</script>