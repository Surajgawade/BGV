<div class="content-page">
<div class="content">
<div class="container-fluid">


      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Clients - SLA, Scope of work & Mode of verification</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>client_settings">SLA</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>   
            </div>
          </div>
     </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
              <div class="row">
           
              <table id="tbl_datatable"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Components</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                
                    foreach ($components as $component)
                    {
                      echo  "<tr class='tbl_row_clicked' data-accessUrl=".ADMIN_SITE_URL.'client_settings/default_setting/'.$component['id']." ><td>".$i."</td>";
                      echo "<td>".$component['component_name']."</td>";
                      echo "</tr>";
                      $i++;
                    } 
                  
                   
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
<script>
$('#tbl_datatable').DataTable({
    "columnDefs": [{ "orderable": false, "targets": 0 }],
    "order": [[ 0, "asc" ]],
     "pageLength": 15,
    "lengthMenu": [[25, 50,100, -1], [25, 50,100  , "All"]]
});

</script>