<table id="tbl_datatable_reference" class="table table-bordered table-hover tbl_datatable_reference">
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
          
                    
                    foreach($reference_lists as $reference_list)
                    {
                      echo  "<td style='display:none;'>".$reference_list['candsid']."</td>";
                      //echo  "<td>".$address_list['ClientRefNumber']."</td>";
                      echo  "<td>".$reference_list['reference_com_ref']."</td>";
                      echo  "<td>".convert_db_to_display_date($reference_list['iniated_date'])."</td>";
                      //echo  "<td>".$address_list['CandidateName']."</td>";
                     
                      echo "</tr>";
                    
                    }


                  ?>
    </tbody>
  </table>
  <div id="ajax-loading-reference"></div>
  <div class="add_reference_frm"></div>

<script>
  $(function ()  {
 
 var add_otable = $('#tbl_datatable_reference').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "ordering": true,
      "bInfo": false,
      "language": {"emptyTable": "No Record Found",},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

 

  $('#tbl_datatable_reference tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'candidates_wip/all_component_details/refver/' ?>'+row_data[0]+'/'+row_data[1],
            beforeSend :function(){
              jQuery('#ajax-loading-reference').show();
              jQuery('#ajax-loading-reference').html("<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 120px;margin-left: 400px;'>");
            },
            complete:function(){
              jQuery('#ajax-loading-reference').hide();
            },
            success:function(html)
            {
              jQuery('.add_reference_frm').html(html);
            }
        });
    }
  });

  });
</script>