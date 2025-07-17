            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                  <div class="card-body">
                    
                    
                    <table id="tbl_vendor_stamp_verifiers_closure_queue" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr>
                          <th>SrId</th>
                          <th>Recvd date</th>
                          <th>Candidate Name</th>       
                          <th>University</th>
                          <th>Qualification</th>
                          <th>Mode</th>
                          <th>Status</th>
                          <th>Created</th>
                          <th>Client</th>
                          <th>Sub Client</th>
                          <th>Component ID</th>
                          <th>Client ID</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        $name = array_column($stamp_verifiers_closures_entries, 'education_com_ref');
                        $filteredKeys = array_unique($name);

                        foreach (array_keys($filteredKeys) as $key => $value) {
                          $filtered [] = $stamp_verifiers_closures_entries[$value];
    
                        }
                      
                        $i = 1;
                        $html_view = '';
                        foreach ($stamp_verifiers_closures_entries as $key => $value) {

                           $mode_of_verification_value = json_decode($value['mode_of_verification']);
                            if(($value['final_status'] == "clear")  ||  ($value['final_status'] == "Clear")){
                              $font_name = "style='color: black;'";
                                
                            } 
                            else{
                               $font_name = "style='color: red;'";
                            }    
                            $html_view .= "<tr id = ".$value['education_id']." url = ".ADMIN_SITE_URL."education/View_vendor_log1/".$value['id']." ".$font_name.">";
                            $html_view .= "<td>".$i."</td>";
                            $html_view .= "<td>".convert_db_to_display_date($value['modified_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)."</td>";
                            $html_view .= "<td>".$value['CandidateName']."</td>";
                            $html_view .= "<td>".$value['actual_university_board']."</td>";
                            $html_view .= "<td>".$value['actual_qualification_name']."</td>";
                            $html_view .= "<td>".$mode_of_verification_value->eduver."</td>";
                            $html_view .= "<td>".$value['final_status']."</td>";
                            $html_view .= "<td>".$value['crated_name']."</td>";
                            $html_view .= "<td>".$value['clientname']."</td>";
                            $html_view .= "<td>".$value['entity_name']."</td>";
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
  var table = $('#tbl_vendor_stamp_verifiers_closure_queue').DataTable({

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

   
  $('.tbl_vendor_stamp_queue-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

  $('#tbl_vendor_stamp_verifiers_closure_queue').on('dblclick', 'tr', function () { 
    var id = $(this).attr('id');
    $('.education_id').val(id);
    var url = $(this).attr('url');
    $('#append_vendor_model').load(url,function(){
      $('#showvendorModel').modal('show');
      $('#showvendorModel').addClass("show");
      $('#showvendorModel').css({background: "#0000004d"});
    });
  });

});

</script>
