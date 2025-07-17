<div class="content-page">
<div class="content">
<div class="container-fluid">
      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Address - Digital Closure</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>digital_closure">Digital Closure</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
                 
           
            </div>
          </div>
        </div>

            <div class="row">
              <div class="col-12">
                <div class="card m-b-20">
                  <div class="card-body">
                  
                
                    <table id="tbl_activity_log_digital" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                      <thead>
                        <tr >
                          <th>SrId</th>
                          <th>Rec Dt</th>
                          <th>Candidate Name</th>       
                          <th>State</th>
                          <th>Client</th>
                          <th>Sub Client</th>
                          <th>Component ID</th>
                          <th >Client ID</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                        
                        $html_view = '';
                          
                        if(isset($vendor_digital_vendor_list)) 
                        {  
                          $j =  1;
                             
                          foreach ($vendor_digital_vendor_list as $key => $value) {
                                  
                              $html_view .= "<tr id = ".$value['address_id']." url = ".ADMIN_SITE_URL."digital_closure/view_vendor_log_digital/".$value['address_id'].">";
                              $html_view .= "<td>".$j."</td>";
                              $html_view .= "<td>".convert_db_to_display_date($value['modified_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                              $html_view .= "<td>".$value['CandidateName']."</td>";
                              $html_view .= "<td>".$value['state']."</td>";
                              $html_view .= "<td>".$value['clientname']."</td>";
                              $html_view .= "<td>".$value['entity_name']."</td>";
                              $html_view .= "<td>".$value['add_com_ref']."</td>";
                              $html_view .= "<td>".$value['ClientRefNumber']."</td>";
                              $html_view .= '</tr>';  

                              $j++;  
                          }

                        }
                        echo  $html_view;
                        ?>

                      </tbody>
                    </table>
                   
                  </div>
                </div>
                </div>
              </div>
            </div> 

</div>
</div>
</div>
<script type="text/javascript">
$(function ()  {
  var table = $('#tbl_activity_log_digital').DataTable({

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

  $('.tbl_activity_log_digital-select-all').on('click', function(){
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
  });

});


$('#tbl_activity_log_digital').on('dblclick', 'tr', function () { 
 
  /*  var id = $(this).attr('id');
    $('.tbl_addrver').val(id);
    var url = $(this).attr('url');
   
      $('#append_vendor_model_digital').load(url,function(){
        $('#showvendorModelDigital').modal('show');
        $('#showvendorModelDigital').addClass("show");
        $('#showvendorModelDigital').css({background: "#0000004d"});
      
      });*/
      
      var view_page = $(this).attr('url');

 
      window.location = view_page;
         
  });
 
</script>
