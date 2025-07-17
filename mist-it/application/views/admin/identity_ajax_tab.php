<button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='identity_add_frm' data-url="<?= ($this->permission['access_identity_list_add']) ? ADMIN_SITE_URL.'identity/add/'.$cand_id : '' ?>" ><i class="fa fa-plus"></i> Identity</button>
<div class="clearfix"></div>
 
<table id="tbl_datatable_identity" class="table table-bordered table-hover tbl_datatable_identity">
    <thead>
    <tr>
          <th>Sr No</th>
          <th>Client Ref No</th>
          <th>Comp Ref No</th>
          <th>Comp INT</th>
          <th>Client Name</th>
          <th>Candidate's Name</th>
          <th>Document</th>
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

      if($identity_lists == "Not Permission")
            {
              echo "<div id = 'permission_identity'></div>";
            }
             else
           {
      $counter = 1;
      
      foreach($identity_lists as $identity_list)
      {
        echo  "<tr><td>".$counter."</td>";
        echo  "<td>".$identity_list['ClientRefNumber']."</td>";
        echo  "<td>".$identity_list['identity_com_ref']."</td>";
        echo  "<td>".convert_db_to_display_date($identity_list['iniated_date'])."</td>";
        echo  "<td>".$identity_list['clientname']."</td>";
        echo  "<td>".$identity_list['CandidateName']."</td>";
        echo  "<td>".$identity_list['doc_submited']."</td>";
        echo  "<td>".$identity_list['verfstatus']." </td>";
       
        echo  "<td>".$identity_list['executive_name']." </td>";
        echo  "<td>".$identity_list['first_qc_approve']."</td>";
        echo  "<td>".$identity_list['tat_status']." </td>";
        echo  "<td>".convert_db_to_display_date($identity_list['due_date'])."</td>";
        echo  "<td>".$identity_list['last_activity_date']."</td>";
        echo "</tr>";
        $counter++;
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
