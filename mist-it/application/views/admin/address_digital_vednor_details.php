<input type="hidden" name="address_id" id="address_id" value="<?php echo set_value('address_id',$details['address_id']); ?>">


<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['address_id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['add_com_ref']); ?>">


<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Entity</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Package</label>
  <input type="text" name="package_name" id="package_name" value="<?php echo set_value('package_name',$details['package_name']); ?>" class="form-control cls_readonly">
</div>


<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>


</div>
<div class="row">

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="add_com_ref" id="add_com_ref"  value="<?php echo set_value('add_com_ref',$details['add_com_ref']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate  Name</label>
  <input type="text" name="candsid"  id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay From</label>
  <input type="text" name="stay_from" id="stay_from"  value="<?php echo set_value('stay_form',$details['stay_from']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay To</label>
  <input type="text" name="stay_to" id="stay_to"  value="<?php echo set_value('stay_to',$details['stay_to']); ?>" class="form-control cls_readonly">
</div>


</div>
<div class="row">

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Address Type</label>
  <input type="text" name="address_type" id="address_type"  value="<?php echo set_value('address_type',$details['address_type']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Street Address</label>
   <textarea name="address" id="address" rows="2" class="form-control cls_readonly"><?php echo set_value('address',$details['address']);?></textarea>
  
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>City </label>
  <input type="text" name="city" id="city" value="<?php echo set_value('city',$details['city']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Pincode</label>
  <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$details['pincode']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class = "row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>State</label>
  <input type="text" name="state" id="state" value="<?php echo set_value('state',$details['state']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact No 1</label>
  <input type="text" name="contact_no1" id="contact_no1" value="<?php echo set_value('contact_no1',$details['CandidatesContactNumber']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Contact No 2</label>
  <input type="text" name="contact_no2" id="contact_no2" value="<?php echo set_value('contact_no2',$details['ContactNo1']); ?>" class="form-control cls_readonly">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact No 3</label>
  <input type="text" name="contact_no3" id="contact_no3" value="<?php echo set_value('contact_no3',$details['ContactNo2']); ?>" class="form-control cls_readonly">
</div>
</div>


 <table id="datatable-activity-data"  class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
      <tr>
        <th>Ordered Table</th>
        <th>Created On</th>
        <th>Created By</th>
        <th>Action</th>
        <th>Mode</th>
        <th>Type</th>
        <th>Status</th>
        <th>Next FollowUp</th>
        <th>Remark</th>
      </tr>
    </thead>
    <tbody>
         <?php 
            foreach ($logs as $key => $value) {
                  
                echo '<tr>';
                echo "<td>".$value['created_on']."</td>";
                echo "<td>".convert_db_to_display_date($value['created_on'],DB_DATE_FORMAT,DISPLAY_DATE_FORMAT12)."</td>";
                echo "<td>".$value['res_created_by']."</td>";
                echo "<td>".$value['action']."</td>";
                echo "<td>".$value['activity_mode']."</td>";
                echo "<td>".$value['activity_type']."</td>";
                echo "<td>".$value['activity_status']."</td>";
                echo "<td>".convert_db_to_display_date($value['next_follow_up_date'])."</td>";
                echo "<td>".$value['remarks']."</td>";
                echo '</tr>';  
              
            }?>
    </tbody>
  </table>

<script>
    $('.cls_readonly').prop('readonly', true);

$(function () 
{
  $('#datatable-activity-data').DataTable({
     "paging": true,
      "processing": true,
      "ordering": true,
      "searching": false,
      scrollX: true,
      "autoWidth": false,
      "language": {
      "emptyTable": "No Record Found",
      },

      "lengthChange": true,
      "lengthMenu": [[5, 25, 50, -1], [5, 25, 50, "All"]],
      "order": [[ 0, 'desc' ]],
      'columnDefs': [
           {
              "targets": [ 0 ],
              "visible": false
           }
        ],
    });
    
});
</script>


