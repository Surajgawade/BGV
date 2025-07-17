<button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='reference_add_frm' data-url="<?= ($this->permission['access_reference_list_add']) ? ADMIN_SITE_URL.'reference_verificatiion/add/'.$cand_id : '' ?>" ><i class="fa fa-plus"></i> Reference</button>
<div class="clearfix"></div>
  
<table id="tbl_datatable_reference" class="table table-bordered table-hover tbl_datatable_reference">
   <thead>
        <tr>
          <th>Sr No</th>
          <th>Client Ref No</th>
          <th>Comp Ref No</th>
          <th>Comp INT</th>
          <th>Client Name</th>
          <th>Candidate's Name</th>
          <th>Reference Name</th>
          <th>Status</th>
          <th>Executive</th>
          <th>QC status</th>
          <th>TAT status</th>
          <th>Due Date</th>
          <th>Last Activity</th>
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
      
      foreach($reference_lists as $reference_list)
      {
        echo  "<tr><td>".$counter."</td>";
        echo  "<td>".$reference_list['ClientRefNumber']."</td>";
        echo  "<td>".$reference_list['reference_com_ref']."</td>";
        echo  "<td>".convert_db_to_display_date($reference_list['iniated_date'])."</td>";
        echo  "<td>".$reference_list['clientname']."</td>";
        echo  "<td>".$reference_list['CandidateName']."</td>";
        echo  "<td>".$reference_list['name_of_reference']."</td>";
        echo  "<td>".$reference_list['verfstatus']." </td>";
        echo  "<td>".$reference_list['executive_name']." </td>";
        echo  "<td>".$reference_list['first_qc_approve']." </td>";
        echo  "<td>".$reference_list['tat_status']."</td>";
        echo  "<td>".convert_db_to_display_date($reference_list['due_date'])."</td>";
        echo  "<td>".$reference_list['last_activity_date']."</td>";
        echo "</tr>";
        $counter++;
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
      "language": {"emptyTable": "No Record Found",},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

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
