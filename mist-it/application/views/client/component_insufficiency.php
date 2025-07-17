 <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
             
               
             
              <div class="clearfix"></div>
            
       
              <table id="datatable-insuff_view" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr class="filters">
                    <th>Sr No</th>
                    <th>Initiated Date</th>
                    <th>Component Name</th>
                    <th>Client Name</th>
                    <th>Entity</th>
                    <th>Package</th>
                    <th>Candidate Name</th>
                    <th>Component Id</th>
                    <th>Raised Date</th>
                    <th>Raised By</th>
                    <th>Insuff Reason</th>
                    <th>Remarks</th>
                    <th>Pending From</th>
                    <th>Action</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i = 1;
                    foreach ($insuff_details as $value)
                    {
                       $remarks = substr($value['insuff_raise_remark'], 0, 50);
                     // $access_insuff_clear = ($this->permission['access_candidates_overall_insuff_clear']) ? $value['controller']  : '';
                    
                      echo "<tr>";
                      echo "<td>".$i."</td>";
                      echo  "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo  "<td>".$value['component_name']."</td>";
                      echo  "<td>".$value['clientname']."</td>";
                      echo  "<td>".$value['entity_name']."</td>";
                      echo  "<td>".$value['package_name']."</td>";
                      echo  "<td>".$value['CandidateName']."</td>";
                      echo  "<td>".$value['component_id']."</td>";
                      echo  "<td>".$value['insuff_raised_date']."</td>";
                      echo  "<td>".$value['raised_by']."</td>";
                      echo  "<td>".$value['insff_reason']."</td>";
                    
                      echo  "<td >".wordwrap($value['insuff_raise_remark'],45,"<br>\n")."</td>";
                      echo  "<td></td>";

                      echo '<td> <button data-id="'.$value['id'].'"  data-candsid="'.$value['candidate_id'].'"  data-toggle="modal" data-target=""#insuffClearModel"  data-clientid="'.$value['clientid'].'" data-accessUrl="'.$value['insuff_id'].'" data-candsname="'.$value['CandidateName'].'" data-componentrefno="'.$value['component_id'].'" data-controller="'.$value['controller'].'" class="btn btn-sm btn-info  tbl_row_edit">Clear</button></td>';

                    /*  echo '<td><button data-id="" data-url ="address/View_vendor_log/" data-toggle="modal" data-target="#showvendorModel"  class="btn btn-sm btn-info  showvendorModel"> View </button>
                      <button data-id="" data-url ="address/View_vendor_log/" data-toggle="modal" data-target="#showvendorModel"  class="btn btn-sm btn-info  showvendorModel">Clear</button>
                      <button data-id="" data-url ="address/View_vendor_log/" data-toggle="modal" data-target="#showvendorModel"  class="btn btn-sm btn-info  showvendorModel">Email Candidate</button></td>';*/
                      $i++;
                    }
                  ?>
                </tbody>
              </table>
           
          </div>
        </div>
      </div>
    </div>

<script>

$(function () 
{
    $('#datatable-insuff_view').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      "searching": false,
      scrollX: true,
      scrollY: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },
      "lengthChange": true,
      "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
  });
      });