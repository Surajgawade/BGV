<div class="component-model">
    <h4 class="modal-title">Candidate Details of : <?= $candidate_details['CandidateName'] ?></h4>
    <ul class="nav nav-tabs navigate-tab">
    <?php
        $my_components[0] = 'Cadidates Details';
        foreach ($my_components as $key => $value) 
        {
            $active  = ($key === 0) ? "active" : '';

            echo "<li data-tab_name=".$key." data-cands_id=".$candidate_details['id']." class='".$active." get_component_details nav-item'><a href='#".$key."' role='tab' data-toggle='tab' class='nav-link'>".$value."</a></li>";
        }
    ?>
    </ul>
</div>
<div class="tab-content">
    <div class="modal-body tab-pane fade in active" id="0">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Client Ref Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['ClientRefNumber']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Date of Birth</label>
                    <input type="email" class="form-control" disabled  value="<?php echo convert_db_to_display_date($candidate_details['DateofBirth']); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Gender</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['gender']; ?>">
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Father's Name</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['NameofCandidateFather']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Mother's Name</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['MothersName']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Primary Contact Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['CandidatesContactNumber']; ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Date of Joining</label>
                    <input type="email" class="form-control" disabled  value="<?php echo convert_db_to_display_date($candidate_details['DateofJoining']); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Location</label>
                    <input type="email" class="form-control" disabled  value="<?php echo ucwords($candidate_details['Location']); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Designation</label>
                    <input type="email" class="form-control" disabled  value="<?php echo ucwords($candidate_details['DesignationJoinedas']); ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Department</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['Department']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Employee Code</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['EmployeeCode']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">PAN Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['PANNumber']; ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">AADHAR Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['AadharNumber']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Passport Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['PassportNumber']; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Batch Number</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['BatchNumber']; ?>">
                </div>
            </div>
            <div class="clearfix"></div>
            
            
            <div class="col-md-4">
                <div class="form-group label-floating">
                    <label class="control-label">Remarks</label>
                    <input type="email" class="form-control" disabled  value="<?php echo $candidate_details['remarks']; ?>">
                </div>
            </div>
        </div>
    </div>
    <?php 
        unset($my_components[0]);
        foreach ($my_components as $key => $tabs_panel) 
        { 
            echo "<div id='".$key."' class='tab-pane fade in'><br>";
            echo "<div id='load-".$key."'></div>";
            echo "</div>";
        }
    ?>
</div>
<script>
$(document).ready(function(){
    $('.get_component_details').on('click',function(){
    var tab_name = $(this).data('tab_name');
    var id = $(this).data('cands_id');
    if(tab_name != "")
    {
      $.ajax({
          type:'GET',
          url:'<?php echo CLIENT_SITE_URL.'candidates_wip/ajax_tab_data_view/'; ?>'+tab_name+'/'+id,
          beforeSend :function(){
          jQuery('#load-'+tab_name).html("<img src='<?php echo SITE_IMAGES_URL; ?>loader_a.gif' style='width: 120px;margin-left: 400px;'>");
          },
          success:function(html)
          {
            jQuery('#load-'+tab_name).html(html);
          }
      });
    }
    });
})
</script>