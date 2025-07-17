            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                  <div class="card-body">
                  
                    <table id="tbl_vendor_insuff" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr>
                          <th>SrId</th>
                          <th>Rec Dt</th>
                          <th>Candidate Name</th>       
                          <th>University</th>
                          <th>Status</th>
                          <th>Remark</th>
                          <th>Mode</th>
                          <th>Client</th>
                          <th>Sub Client</th>
                          <th>Trans Id</th>
                          <th>Component ID</th>
                          <th>Client ID</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                         $i = 1;
                          $html_view = '';
                        foreach ($vendor_executive_list as $key => $value) {
                                
                            $html_view .= "<tr id = ".$value['education_id']." url = ".ADMIN_SITE_URL."education/check_insuff_already_raised/".$value['education_id']." >";
                            $html_view .= "<td>".$i."</td>";
                            $html_view .= "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                            $html_view .= "<td>".$value['CandidateName']."</td>";
                            $html_view .= "<td>".$value['actual_university_board']."</td>";
                            $html_view .= "<td>".$value['final_status']."</td>";
                            $html_view .= "<td>".$value['vendor_remark']."</td>";
          
                            $html_view .= "<td>".$value['vendor_list_mode']."</td>";
                            $html_view .= "<td>".$value['clientname']."</td>";
                            $html_view .= "<td>".$value['entity_name']."</td>";
                            $html_view .= "<td>".$value['trasaction_id']."</td>";
                            $html_view .= "<td>".$value['education_com_ref']."</td>";
                            $html_view .= "<td>".$value['ClientRefNumber']."</td>";
                            $html_view .= '</tr>';  

                            $i++;  
                        }
                        echo  $html_view;
                        ?>

                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
       

<script type="text/javascript">
$(function ()  {
  var table = $('#tbl_vendor_insuff').DataTable({

        bSortable: true,
        bRetrieve: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns:   {
        leftColumns: 1,
        rightColumns: 1
        },
      "iDisplayLength": 20,
      "lengthMenu": [[20, 40, 100, -1], [20, 40, 100, "All"]],
      'columnDefs': [{
         'targets': 0,
         'searchable':false,
         'orderable':false,
      }],
      'order': [1, 'asc']
  });

  $('.tbl_vendor_insuff-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

});


$('#tbl_vendor_insuff').on('dblclick', 'tr', function () { 

    var id = $(this).attr('id');
  //  $('.education_id').val(id);
    var url = $(this).attr('url');
    $('#append_vendor_model_insuff').load(url,function(){
      $('#insuffRaiseModel').modal('show');
      $('#insuffRaiseModel').addClass("show");
      $('#insuffRaiseModel').css({background: "#0000004d"});
    });
  });
</script>
