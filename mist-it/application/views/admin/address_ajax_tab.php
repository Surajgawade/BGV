<button class="btn btn-sm btn-info append_candidate_tab" style="float: right;"  data-tab_name='add_add_frm' data-url="<?= ($this->permission['access_address_list_add']) ? ADMIN_SITE_URL.'address/add/'.$cand_id : '' ?>" ><i class="fa fa-plus"></i> Address</button>

<div class="clearfix"></div>
 <!-- <div class="pull-right">
    <button class="btn btn-sm btn-info btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button>
  </div>-->
  <table id="tbl_datatable_add" class="table table-bordered table-hover tbl_datatable_add">
    <thead>
        <tr>
          <th>Sr No</th>
          <th>Client Ref No</th>
          <th>Comp Ref No</th>
          <th>Comp INT</th>
          <th>Client Name</th>
          <th>Candidate's Name</th>
          <th>Vendor</th>
          <th>Status</th>
          <th>Address</th>
          <th>State</th>
          <th>City</th>
          <th>Executive</th>
          <th>QC status</th>
          <th>TAT status</th>
          <th>Due Date</th>
          <th>Last Activity</th>
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
                    
                    foreach($address_lists as $address_list)
                    {
                      echo  "<tr><td>".$counter."</td>";
                      echo  "<td>".$address_list['ClientRefNumber']."</td>";
                      echo  "<td>".$address_list['add_com_ref']."</td>";
                      echo  "<td>".convert_db_to_display_date($address_list['iniated_date'])."</td>";
                      echo  "<td>".$address_list['clientname']."</td>";
                      echo  "<td>".$address_list['CandidateName']."</td>";
                      echo  "<td></td>";
                      echo  "<td>".$address_list['verfstatus']." </td>";
                      echo  "<td>".$address_list['address']." </td>";
                      echo  "<td>".$address_list['state']."</td>";
                      echo  "<td>".$address_list['city']." </td>";
                      echo  "<td>".$address_list['executive_name']." </td>";
                      echo  "<td></td>";
                      //echo  "<td>".$address_list['first_qc_approve']." </td>";
                      echo  "<td>".$address_list['tat_status']."</td>";
                      echo  "<td>".convert_db_to_display_date($address_list['due_date'])."</td>";
                     // echo  "<td>".$address_list['has_assigned_on']."</td>";
                      echo  "<td>".convert_db_to_display_date($address_list['has_assigned_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)." </td>";
                      echo "</tr>";
                      $counter++;
                    }


            }
                  ?>
    </tbody>
  </table>
</div>
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

  $('#tbl_datatable_add tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo ADMIN_SITE_URL.'address/view_form_inside/' ?>'+row_data[2],
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

<!--<script type="text/javascript">
$(document).on('click', '.append_candidate_tab', function(){
   
  var tab_name = $(this).data('tab_name');
  var url = $(this).data('url');
  if(tab_name != 0 && url != "")
  {
      $.ajax({
        type:'GET',
        url:url,
        beforeSend :function(){
            jQuery('.body_loading').show();
        },
        complete:function(){
            jQuery('.body_loading').hide();
        },
        success:function(html){
            jQuery('.'+tab_name).html(html);
        }
      });
  }
  else {
        show_alert('Access Denied, You don’t have permission to access this page');
    }
});

</script>-->