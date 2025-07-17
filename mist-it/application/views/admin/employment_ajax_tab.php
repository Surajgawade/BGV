<button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='emp_add_frm' data-url="<?= ($this->permission['access_employment_list_add']) ? ADMIN_SITE_URL.'employment/add/'.$cand_id : '' ?>" ><i class="fa fa-plus"></i> Employment</button>

<div class="clearfix"></div>
<table id="tbl_datatable_emp" class="table table-bordered table-hover tbl_datatable_emp">
   
   <thead>
        <tr class="filters">
          <th>Sr No</th>
          <th>Client Ref No</th>
          <th>Comp Ref No</th>
          <th>Comp INT</th>
          <th>Client Name</th>
          <th>Candidate's Name</th>
          <th>Company Name</th>
          <th>Status</th>
          <th>Executive</th>
          <th>Field status</th>
          <th>QC status</th>
          <th>TAT status</th>
          <th>Due Date</th>
          <th>Last Activity</th>
        </tr>
  </thead>

    <tbody>
      <?php

      if($employment_lists == "Not Permission")
            {
              echo "<div id = 'permission_employment'></div>";
            }
           else
           {  
          $counter = 1;
          foreach ($employment_lists as $employment_list)
          {
            echo  "<tr><td>".$counter."</td>";
            echo  "<td>".$employment_list['ClientRefNumber']."</td>";
            echo  "<td>".$employment_list['emp_com_ref']."</td>";
            echo  "<td>".convert_db_to_display_date($employment_list['iniated_date'])."</td>";
            echo  "<td>".$employment_list['clientname']."</td>";
            echo  "<td>".$employment_list['CandidateName']."</td>";
            echo  "<td>".$employment_list['coname']." </td>";
            echo  "<td>".$employment_list['verfstatus']." </td>";
            echo  "<td>".$employment_list['executive_name']." </td>";
            echo  "<td></td>";
            echo  "<td>".$employment_list['first_qc_approve']." </td>";
            echo  "<td>".$employment_list['tat_status']."</td>";
            echo  "<td>".$employment_list['due_date']."</td>";
            echo  "<td>".convert_db_to_display_date($employment_list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)." </td>";
            echo "</tr>";
            $counter++;
          }

        }
        ?>
    </tbody>
</table>
<div class="emp_add_frm"></div>
<script type="text/javascript">
  $(document).ready(function(){

      if ($("#permission_employment").is(":visible")) {
    show_alert('Access Denied, You don’t have permission to access this page');
   }
  
});
</script>
<script>
$(function ()  {


  var otable = $('#tbl_datatable_emp').DataTable({
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

  otable.columns().every( function () {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
       if ( that.search() !== this.value ) {
           that.search( this.value ).draw();
       }
    });
  });

  $('#tbl_datatable_emp tbody').on( 'dblclick', 'tr', function () {
    var row_data = otable.row( this ).data();
    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'employment/view_from_cands/' ?>'+row_data[2],
            beforeSend :function(){
              jQuery('.body_loading').show();
            },
            complete:function(){
              jQuery('.body_loading').hide();
            },
            success:function(html)
            {
              jQuery('.emp_add_frm').html(html);
            }
        });
    }
  });

});
</script>

