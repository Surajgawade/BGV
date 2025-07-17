<div class="content-page">
  <div class="content">
    <div class="container-fluid">

      <div class="row">
         <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Candidates - Reject View</h4>
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>candidates">Candidate</a></li>
                     <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>reject_cases">Reject View</a></li>
                     <li class="breadcrumb-item active">List View</li>
                  </ol>
            
                  
            </div>
          </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
             
             
              <div class="clearfix"></div>
            
       
              <table id="datatable-reject_cases" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                  
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    foreach ($component_reject_cases_details as $value)
                    {
                      
                      echo '<tr class="open_popup" data-com_id="'.$value['id'].'" data-tab_name="append_html" data-url="'.ADMIN_SITE_URL.$value['component_name'].'/add_form_componet/">';
                     

                      echo "<td>".$counter."</td>";
                      echo  "<td>".convert_db_to_display_date($value['iniated_date'])."</td>";
                      echo  "<td>".$value['component_name']."</td>";
                      echo  "<td>".$value['clientname']."</td>";
                      echo  "<td>".$value['entity_name']."</td>";
                      echo  "<td>".$value['package_name']."</td>";
                      echo  "<td>".$value['CandidateName']."</td>";
                      echo  "<td>".$value['component_id']."</td>";
                      echo  "</tr>";
                    
                      $counter++;
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

<div id="myModalComponentSave" class="modal fade" role="dialog">
  <div class="modal-dialog popup_style modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Component Details</h4>
        
      </div>

      <div class="modal-body" id="append_html">
        
      </div>
      <div class="modal-footer">
      
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 

<script>

$(function () 
{
    $('#datatable-reject_cases').DataTable({
      "serverSide": false,
      "processing": false,
       bSortable: true,
       bRetrieve: true,
       scrollX: true,
      "iDisplayLength": 25, // per page
      "language": {
        "emptyTable": "No Record Found",
        "processing": "<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 65px;'>" 
    }
  });

    $(document).on('dblclick', '.open_popup', function(){
    var com_id = $(this).data('com_id');
    var tab_name = $(this).data('tab_name');
    var url = $(this).data('url');
    
    if(com_id != 0 && url != "")
    {
        $.ajax({
            type:'GET',
            url:url+com_id,
            beforeSend :function(){
                jQuery('.body_loading').show();
            },
            complete:function(){
                jQuery('.body_loading').hide();
            },
            success:function(html) {
                jQuery('#'+tab_name).html(html);
                jQuery('#myModalComponentSave').modal('show');
                jQuery('#myModalComponentSave').addClass("show");
                jQuery('#myModalComponentSave').css({background: "#0000004d"});
            }
        });
    }
    else {
        show_alert('Access Denied, You don’t have permission to access this page');
    }
  }); 
});
</script>