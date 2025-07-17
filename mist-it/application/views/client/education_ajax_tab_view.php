<table id="tbl_datatable_education" class="table table-bordered table-hover tbl_datatable_education">
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
          
                    
                    foreach($education_lists as $education_list)
                    {
                      echo  "<td style='display:none;'>".$education_list['candsid']."</td>";
                      //echo  "<td>".$address_list['ClientRefNumber']."</td>";
                      echo  "<td>".$education_list['education_com_ref']."</td>";
                      echo  "<td>".convert_db_to_display_date($education_list['iniated_date'])."</td>";
                      //echo  "<td>".$address_list['CandidateName']."</td>";
                     
                      echo "</tr>";
                    
                    }


                  ?>
    </tbody>
  </table>
  <div id="ajax-loading-education"></div>
  <div class="add_education_frm"></div>

<script>
  $(function ()  {
 
 var add_otable = $('#tbl_datatable_education').DataTable({
     "paging": false,
      "searching": false,
      "processing": true,
      "ordering": true,
      "bInfo": false,
      "language": {"emptyTable": "No Record Found",},
      "lengthChange": false,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });

 

  $('#tbl_datatable_education tbody').on( 'dblclick', 'tr', function () {

    var row_data = add_otable.row( this ).data();

    if(row_data[0] != "")
    {
        $.ajax({
            type:'GET',
            url:'<?php echo CLIENT_SITE_URL.'candidates_wip/all_component_details/eduver/' ?>'+row_data[0]+'/'+row_data[1],
            beforeSend :function(){
              jQuery('#ajax-loading-education').show();
              jQuery('#ajax-loading-education').html("<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 120px;margin-left: 400px;'>");
            },
            complete:function(){
              jQuery('#ajax-loading-education').hide();
            },
            success:function(html)
            {
              jQuery('.add_education_frm').html(html);
            }
        });
    }
  });

  });
</script>