<?php 
if(count($drug_lists) >= '1')
{
  if(in_array('narcver', explode(',',$component_check))) 
  { 
    if($drugs_details_component_check == "1")
    { 

  ?>

    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='drugs_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/drugs_narcotics_edit/'.$cand_id  ?>" ><i class="fa fa-minus"></i> Update </button>
   <?php 
  }
  else 
  {
  ?> 
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='drugs_add_frm' data-url="<?= CLIENT_SITE_URL.'candidate_mail/drugs_narcotics_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i>Drugs</button>
    <?php
    }
  }
}
else
{
?>
    <button class="btn btn-sm btn-info append_candidate_tab" style="float: left;"  data-tab_name='drugs_add_frm' data-url="<?=  CLIENT_SITE_URL.'candidate_mail/drugs_narcotics_add/'.$cand_id  ?>" ><i class="fa fa-plus"></i>Drugs</button>
  
<?php
}
?>

 <div class="clearfix"></div>
<table id="tbl_datatable_drug"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
      <tr>
          <th>Sr No</th>
          <th>Comp INT</th>
          <th>Comp Ref No</th>
          <th>Appointment Date</th>
          <th>Drugs Name/Code</th>
          <th>Address</th>
          <th>State</th>
          <th>City</th>
          <th>Pinode</th>
        </tr>
    </thead>
    <tbody>
    <?php

    if($drug_lists == "Not Permission")
    {
       echo "<div id = 'permission_drugs'></div>";
    }
    else
    {
      $counter = 1;
      
      foreach($drug_lists as $drug_list)
      {
        echo  "<tr><td>".$counter."</td>";
        echo  "<td>".convert_db_to_display_date($drug_list['iniated_date'])."</td>";
        echo  "<td>".$drug_list['drug_com_ref']."</td>";
        echo  "<td>".convert_db_to_display_date($drug_list['appointment_date'])." </td>";
        echo  "<td>".$drug_list['drug_test_code']." </td>";
        echo  "<td>".$drug_list['street_address']." </td>";
        echo  "<td>".$drug_list['state']." </td>";
        echo  "<td>".$drug_list['city']." </td>";
        echo  "<td>".$drug_list['pincode']." </td>";
        
        echo "</tr>";
        $counter++;
      }
    }
    ?>
  </tbody>
</table>
<div class="drugs_add_frm"></div>

<script type="text/javascript">
  $(document).ready(function(){

     if ($("#permission_drugs").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
 
});
</script>

<script>
$(function ()  {

 var add_otable = $('#tbl_datatable_drug').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "scrollX": true,
      "ordering": true,
      "bInfo": false,
      "language": {"emptyTable": "No Record Found",},
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

  $('#tbl_datatable_drug tbody').on( 'dblclick', 'tr', function () {
    var row_data = add_otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'Candidate_mail/drugs_view_from_inside/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.drugs_add_frm').html(html);
            }
        });
    }
  });

});
</script>
