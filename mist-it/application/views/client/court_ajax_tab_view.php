<table id="tbl_datatable_court" class="table table-bordered table-hover tbl_datatable_court">
    <thead>
        <tr class="filters">
         <th style='display:none;'>ID</th>

          <!--<th>Client Ref No</th>-->
          <th>Comp Ref No</th>
          <th>Comp INT</th>
  
        <!--  <th>Candidate's Name</th>-->
          
        </tr>
  </thead>
    <tbody>
        <?php
          
                    
                    foreach($court_lists as $court_list)
                    {
                      echo  "<td style='display:none;'>".$court_list['candsid']."</td>";
                      //echo  "<td>".$address_list['ClientRefNumber']."</td>";
                      echo  "<td>".$court_list['court_com_ref']."</td>";
                      echo  "<td>".convert_db_to_display_date($court_list['iniated_date'])."</td>";
                      //echo  "<td>".$address_list['CandidateName']."</td>";
                     
                      echo "</tr>";
                    
                    }


                  ?>
    </tbody>
  </table>
  <div id="ajax-loading"></div>
  <div class="add_add_frm"></div>

<script>
  $(function ()  {
 
 var add_otable = $('#tbl_datatable_court').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "ordering": true,
      "bInfo": false,
      "language": {"emptyTable": "No Record Found",},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

 

  $('#tbl_datatable_court tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'candidates_wip/all_component_details/courtver/' ?>'+row_data[0]+'/'+row_data[1],
            beforeSend :function(){
              jQuery('#ajax-loading').show();
              jQuery('#ajax-loading').html("<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 120px;margin-left: 400px;'>");
            },
            complete:function(){
              jQuery('#ajax-loading').hide();
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