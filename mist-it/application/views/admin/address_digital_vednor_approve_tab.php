<script type="text/javascript" src="<?php echo SITE_URL; ?>assets/js/html2canvase.js"></script>
 <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCetWiu5VcxfP2f77rdIONvFHG_DZjFqBQ&#038;ver=1"></script>

<?php
  if(isset($js_library) && is_array($js_library) && !empty($js_library))
  {

    foreach ($js_library as $key => $js_librar) {

        echo "<script src='".SITE_JS_URL.$js_librar."'></script>";
    }
  }
?>

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
                  
                  <div class="state-information d-none d-sm-block">
                     <ol class="breadcrumb">
                        <li><button class="btn btn-secondary waves-effect btn_clicked btn-sm" data-accessUrl="<?= ADMIN_SITE_URL?>address"><i class="fa fa-arrow-left"></i> Back</button></li>  
                      
                    </ol>
                  </div>
           
            </div>
          </div>
        </div>

  
<br>

<div class="row">
  <div class="col-12">
    <div class="card m-b-20">
      <div class="card-body">

        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <h4 class="page-title text-left"><?php echo $header_title; ?></h4>
        </div>



<input type="hidden" name="address_id" id="address_id" value="<?php echo set_value('address_id',$details['address_id']); ?>">
<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['address_id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['add_com_ref']); ?>">


<input type="hidden" name="entity_id" id="entity_id1" value="<?php echo set_value('entity_id',$details['entity']); ?>">
<input type="hidden" name="package_id" id="package_id1" value="<?php echo set_value('package_id',$details['package']); ?>">
<input type="hidden" name="clientid" id="clientid1" value="<?php echo set_value('clientid',$details['clientid']); ?>">
<input type="hidden" name="addrverres_id" id="addrverres_id1" value="<?php echo set_value('addrverres_id',$details['addrverres_id']); ?>">


<input type="hidden" name="digital_address_closed_id" id="digital_address_closed_id" value="<?php echo set_value('digital_address_closed_id',$details['address_details_id']); ?>">

<input type="hidden" name="current_lat" id="current_lat" value="<?php echo $details['latitude'] ?>">
<input type="hidden" name="current_long" id="current_long" value="<?php echo $details['longitude'] ?>">
<input type="hidden" name="add_update_id" id="add_update_id" value="<?php echo $details['address_id'] ?>">

<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Client</label>
  <input type="text" name="clientname" id="clientname" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Sub Client</label>
  <input type="text" name="entity_name" id="entity_name" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Client ref no</label>
  <input type="text" name="ClientRefNumber" id="ClientRefNumber"  value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Comp ref no</label>
  <input type="text" name="add_com_ref" id="add_com_ref"  value="<?php echo set_value('add_com_ref',$details['add_com_ref']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">

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

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Address Type</label>
  <input type="text" name="address_type" id="address_type"  value="<?php echo set_value('address_type',$details['address_type']); ?>" class="form-control cls_readonly">
</div>
</div>
<div class="row">

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
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>State</label>
  <input type="text" name="state" id="state" value="<?php echo set_value('state',$details['state']); ?>" class="form-control cls_readonly">
</div>
</div>

<div class="row">


<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Contact No 1 </label>
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


<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h4 class="page-title text-left"> Verification Result</h4>
</div>
  <?php echo form_open('#', array('name'=>'candidate_verification_result','id'=>'candidate_verification_result')); ?>

  <input type="hidden" name="address_details_verification_id" id="address_details_verification_id" value="<?php echo set_value('address_details_verification_id',$details['address_details_id']); ?>">

  
<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Stay Type</label>
  <?php   $resident_status = array(''=> 'Select Resident Status','rented'=> 'Rented','rwned'=>'Owned','pg'=>'PG','relatives'=>'Relatives','government quarter'=>'Government Quarter','private quarter'=>'Private Quarter','hostel'=>'Hostel','others'=>'Others');

    echo form_dropdown('stay_type', $resident_status, set_value('stay_type',$details['nature_of_residence']), 'class="form-control" id="stay_type"');
    echo form_error('stay_type');

 ?>

</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Stay From</label>
  <input type="text" name="stay_from" id="stay_from" value="<?php echo set_value('stay_from',$details['period_stay']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Stay To</label>
  <input type="text" name="stay_to" id="stay_to"  value="<?php echo set_value('stay_to',$details['period_to']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Verified By</label>

  <?php
    $verifies_name = array("" => "Select Verifier Name","Self" => "Self","Others" => "Others");
    echo form_dropdown('verified_by_digital', $verifies_name, set_value('verified_by_digital',$details['verifier_name']),'class="form-control" id="verified_by_digital"');
    echo form_error('verified_by_digital');
 ?>
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Relation with candidate</label>
  <input type="text" name="relation_by_verified" id="relation_by_verified"  value="<?php echo set_value('relation_by_verified',$details['relation_verifier_name']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Nearest Landmark</label>
  <input type="text" name="candidate_remarks" id="candidate_remarks"  value="<?php echo set_value('candidate_remarks',$details['candidate_remarks']); ?>" class="form-control">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Candidate Submitted Date</label>
  <input type="text" name="candidate_verification_date" id="candidate_verification_date"  value="<?php echo set_value('candidate_verification_date',convert_db_to_display_date($details['credte_on'], DB_DATE_FORMAT, DISPLAY_DATE_FORMAT12)); ?>" class="form-control cls_readonly" >
</div>
 <div class="col-md-4">
      <div class="text-left">
        <br>
        <button type="submit" id="btn_update_address_verification_result" name="btn_update_address_verification_result" class="btn btn-success btn-fill btn-wd">Update Verification Result</button>
      </div>
    </div>

<?php echo form_close(); ?>

<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h4 class="page-title text-left"> Candidate attchments (IP Address <?php echo $details['ip_address']; ?>)</h4>
</div>

  <div class="row">
    <?php
                    
      $attacments = get_attacments($details);
      $upath= SITE_URL . ADDRESS .'address_verification/';
        foreach ($attacments as $key => $attacment) {
          if($attacment['filename'] != "") {
            $path = $upath.$attacment['filename'];
              echo "<div class='col-md-3'>";
                echo "<div class='thumbnail'>";
                  echo "<a href=".$path." target='_blank'>";
                    echo "<img loading='lazy'  src=".$path." alt='attacments' class = 'rotated_image_$key' style='width:100%'>";
                      echo "<div class='caption'>";
                        echo "<p>".$attacment['type']."</p>";
                        echo "<p>".$attacment['lat_long']."</p>";
                      echo "</div>";
                  echo "</a>";
                echo "</div>";
              echo "</div>";
              echo "<div class='col-md-1'>";
              echo "<a href='javascript:rotation(`".$path."`,`rotated_image_$key`)';><i class='fa fa-rotate-right'></i></a>";
              
              echo "</div>";

          }
        }
    ?>
  </div>
</div>
<div class="row">
  <div class="col-12">
      <div class="card m-b-20">    
        <h4 class="mt-0 header-title">Locator View</h4>
          <div id="map" class="gmaps"></div>
      </div>
  </div>
</div>
<div class="row">
  <div class="card m-b-20">
    <div class="card-body">
      <h4 class="mt-0 header-title">Address shown on the map <span class="distance_by_place"></span></h4>
        <table id="" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
          <thead>
            <tr>
              <th>Address</th>
              <th>Source</th>
              <th>Distance</th>
              <th>Location API</th>
              <th>Legend</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $details['address']; ?></td>
              <td>Input address</td>
              <td>0km</td>
              <td>Google Location API</td>
              <th><input type="color" name="legend_color" id="legend_color" value="#fab505"></th>
            </tr>
             <?php if($details['latitude'] != "" && $details['longitude'] != "") { ?>
            <tr>
              <td><?php echo $details['latitude'].','.$details['longitude']; ?> ( <?php echo $address ?> ) </td>
              <td>GPS</td>
              <td class="distance_by_place">0km</td>
              <td>Google Location API</td>
              <th><input type="color" name="legend_color" id="legend_color" value="#3595db"></th>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <hr><h4 class="mt-0 header-title">Distance Calculate</h4>
    <?php echo form_open('#', array('name'=>'update_address_frm','id'=>'update_address_frm')); ?>
  <div class="row">
    <div class="col-md-8">
      <div class="form-group">
        <label>Update Address (Show Circle on Map) <span class="error">*</span></label>
          <textarea name="update_address" maxlength="250" id="update_address" class="form-control border-input geo_address" rows="1"><?php echo set_value('update_address',$details['address_edit'])?></textarea>
      </div>
    </div>
      <input type="hidden" name="update_address_id"  id = "update_address_id" value="<?php echo set_value('update_address_id',$details['address_details_id']); ?>">

    <div class="col-md-4">
      <div class="text-left">
        <br>
        <button type="submit" id="btn_update_address" name="btn_update_address" class="btn btn-warning btn-fill btn-wd">Update Address</button>
      </div>
    </div>
  </div>
    <?php echo form_close(); ?>
    <div class="row">
    <a target="__blank" href="<?php echo ADMIN_SITE_URL.'address/report_address_verification/'.encrypt($details['address_id']) ?>" > <button type="button" class="btn btn-info btn-sm" id = "report_down"> Report </button></a>&nbsp;&nbsp;
   <button  class="btn btn-success btn-sm approve_reject_id_digital" data-url
      ="<?= ADMIN_SITE_URL.'activity_log/activity_log_view_vendor_form/1/' ?>" data-toggle="modal" id="activityModelClkdigital" type="button" style="display: none;">Approve</button>&nbsp;&nbsp;
      <button  class="btn btn-danger btn-sm approve_reject_id_digital" data-toggle="modal" data-target="#reject_value_digital"  type="button" >Reject</button>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<div id="activityModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Activity Log</h4>
      </div>
      <?php echo form_open('#', array('name'=>'cases_activity','id'=>'cases_activity')); ?>
      <div class="modal-body">
          <div class="acti_error" id="acti_error"></div>
          <div class="row">
            <input type="hidden" name="component_type" id="component_type" value="1">
            <input type="hidden" name="acti_candsid" id = "acti_candsid" value="<?php echo set_value('acti_candsid',$details['candsid']); ?>">

            <input type="hidden" name="comp_table_id"  id = "comp_table_id" value="<?php echo set_value('comp_table_id',$details['address_id']); ?>">
            <input type="hidden" name="ac_ClientRefNumber" id = "ac_ClientRefNumber" value="<?php echo set_value('ac_ClientRefNumber',$details['ClientRefNumber']); ?>">

            <input type="hidden" name="CandidateName"  id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>" class="form-control">
            <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['add_com_ref']); ?>">
            
            <div class="append-activity_view"></div>

          </div>
      </div>
      <div class="modal-footer">
        <div id="btn_action"><button type="button" class="btn btn-default btn-sm left" data-dismiss="modal">Close</button></div>
      </div>
      <?php echo form_close(); ?>
      <div class="append-activity_records"></div>
    </div>
  </div>
</div>

<div id="addAddResultModel" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="overflow-y: scroll;">
  <div class="modal-dialog modal-lg" >

    <?php echo form_open_multipart("#", array('name'=>'add_verificarion_result','id'=>'add_verificarion_result')); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Verification Result</h4>
      </div>
      <span class="errorTxt"></span>
      <input type="hidden" id="candidates_info_id" name="candidates_info_id" value="<?php echo set_value('candidates_info_id',$details['CandidateID']); ?>">
      <input type="hidden" id="entity_id" name="entity_id" value="<?php echo set_value('entity_id',$details['entity']); ?>">
      <input type="hidden" id="package_id" name="package_id" value="<?php echo set_value('package_id',$details['package']); ?>">
      <input type="hidden" id="tbl_addrver" name="tbl_addrver" value="<?php echo set_value('tbl_addrver',$details['address_id']); ?>">
      <input type="hidden" id="clientid"  name="clientid" value="<?php echo set_value('clientid',$details['clientid']); ?>">
      <input type="hidden" id="addrverres_id" name="addrverres_id" value="<?php echo set_value('addrverres_id',$details['addrverres_id']); ?>">

      <div id="append_result_model"></div>
      <div class="modal-footer">
        <button type="button" id="addResultBack" name="addResultBack" class="btn btn-default btn-sm pull-left">Back</button>
        <button type="submit" id="sbresult" name="sbresult" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>


<div id="reject_value_digital" class="modal fade" role="dialog" style="z-index: 2000;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body">
        
       <input type="text" name="reject_reason_closure_digital" maxlength="200" id="reject_reason_closure_digital" placeholder="Reason" class="form-control">
       <br>

        <button type="button" id="reject_digital" name="reject_digital" class="btn btn-danger btn-sm" value = '2'>Reject</button>
        
      </div>      
    </div>
  </div>
</div>

<script>$('.cls_readonly').prop('disabled',true);</script>

<script type="text/javascript">

  if($("#map"). length) {

  var map;
  var current_lat = $('#current_lat').val();
  var current_long = $('#current_long').val();
  var latlng = new google.maps.LatLng(current_lat,current_long);
  function initialize() {
      var mapOptions = {
          center: latlng,
          zoom: 16,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      var el=document.getElementById("map");
      map = new google.maps.Map(el, mapOptions);
      geocoder = new google.maps.Geocoder();
      var sunCircle = {
          strokeColor: "#054a9e",
          strokeOpacity: 0.8,
          strokeWeight: 1,
          fillColor: "#025fd1",
          fillOpacity: 0.35,
          map: map,
          center: latlng,
          radius: 250 // in meters
      };
      cityCircle = new google.maps.Circle(sunCircle);
      var iconBase = 'https://mistitservices.in/mist-it/assets/images/';
      var marker = new google.maps.Marker({
        position: latlng,
      //  title: 'Current Address',
        map: map,
        draggable: true,
        icon: iconBase + 'blue_icon.jpg'

      });
      place_address(geocoder,map)
  }
  
  function place_address() {
      var address = document.getElementById('update_address').value;
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == 'OK') {
          place_lat_long = results[0].geometry;
          map.setCenter(results[0].geometry.location);
          var sunCircle = {
            strokeColor: "#edab02",
            strokeOpacity: 0.8,
            strokeWeight: 1,
            fillColor: "#fab505",
            fillOpacity: 0.35,
            map: map,
            center: results[0].geometry.location,
            radius: 250 // in meters
        };
        cityCircle = new google.maps.Circle(sunCircle);
        var iconBase_pink = 'https://mistitservices.in/mist-it/assets/images/';

        var marker = new google.maps.Marker({
          position: results[0].geometry.location,
         // title: 'Input Address',
          map: map,
          draggable: true,
          icon: iconBase_pink + 'pink_icon.png'
        });
        distance_Cal();
        } else {
          alert('Geocode was not successful for the following reason: ' + status);
        }
      });
  }
  initialize();    

  function saveMapToDataUrl() {
      var element = $("#map");
      html2canvas(element, {
          useCORS: true,
          onrendered: function(canvas) {
              var dataUrl= canvas.toDataURL("image/png");
              $('#mapdataUrl').val(dataUrl);
          }
      });
  }
}
  
function distance_Cal() {

  var url_arress = $('#update_address').val().replace("#", "");
  var url_arress = url_arress.replace("&", " and ");
  var geo_url = encodeURI('https://maps.googleapis.com/maps/api/geocode/json?address='+url_arress+'&key=AIzaSyCetWiu5VcxfP2f77rdIONvFHG_DZjFqBQ');
  console.log(geo_url);
  $.get(geo_url, function( data ) {
    
    if(data != "") {
      var lat1 = data.results[0].geometry.location.lat;
      var lon1 = data.results[0].geometry.location.lng;
      var lat2 = $('#current_lat').val();
      var lon2 = $('#current_long').val();

      var  unit = 'K';
      if ((lat1 == lat2) && (lon1 == lon2)) {
        return 0;
      }
      else {

        var radlat1 = Math.PI * lat1/180;
        var radlat2 = Math.PI * lat2/180;
        var theta = lon1-lon2;
        var radtheta = Math.PI * theta/180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
          dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        if (unit=="K") { dist = dist * 1.609344 }
        if (unit=="N") { dist = dist * 0.8684 }
        dist = dist.toFixed(3);
        $('.distance_by_place').text(dist+'km');
        update_distance(dist);
        return dist;
      }
    }else {
      alert('google place API not finding given address');
      show_alert('google place API not finding given address', 'danger');
    }
  });
}  

function update_distance(distance){
  $.ajax({
    url: '<?php echo ADMIN_SITE_URL.'address/update_distance/' ?>',
    type: 'post',
    data: {distance:distance,add_update_id : $('#add_update_id').val()},
    dataType: 'json',
    beforeSend: function() {
      $('#report_down').attr('disabled', true);
    },
    complete: function() {
       $('#report_down').removeAttr('disabled');

    },
    success: function(jdata) {
      console.log(jdata);
      $('#report_down').removeAttr('disabled');

    }
  });
}


function addMarker(lat, lng) {
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lng),
        map: map
    });
    markers.push(marker);
};



$(document).on('click',".rto_clicked",function() {
    var txt_val = $(this).data('val');
    var nameArr =  txt_val.substring(txt_val.indexOf('_')+1)  
    var actual_value=document.getElementById(nameArr).value;

    var rtb_val = $(this).val();

    if(rtb_val == 'no'){
      $("#"+txt_val).val("");
      $("#"+txt_val).removeAttr("readonly");
    }
    else if(rtb_val == 'yes'){
      $("#"+txt_val).val(actual_value);
      $("#"+txt_val).attr("readonly","true");
    } 
    else if(rtb_val == 'not-obtained') {
      $("#"+txt_val).val("Not Disclosed");
      $("#"+txt_val).attr("readonly","true");
    }
    else if(rtb_val == 'not-verified') {
      $("#"+txt_val).val("Not Verified");
      $("#"+txt_val).attr("readonly","true");
    }
});


$('#activityModelClkdigital').click(function(){

    var url = $(this).data("url");
    var id = $('#address_id').val();
    var component = 'address';
    
    $.ajax({
      type: "POST",
      url:'<?php echo ADMIN_SITE_URL.'address/check_closure_status_physical/' ?>'+id,
      dataType: "html",
      success:function(data){


        if(data == "success")
        {
          $('.append-activity_view').load(url+id+"/"+component,function(){});
          $('#activityModel').modal('show');
          $('#activityModel').addClass("show");
          $('#activityModel').css({background: "#0000004d"});
        }
        else
        {
           alert('Kindly Close physical verification first');
        }
      }
     });
  });


$(document).on('click','#clkAddResultModel',function() {
  if($("#cases_activity").valid())
  {
    var action2 = $("#action option:selected").text();
    var action1 =  action2.replace(/\s+/g,'');
    $('#activityModel').modal('hide');
    var url = $(this).data('url');
    $('#append_result_model').load(url+"/"+action1,function(){
      $('#addAddResultModel').modal('show');
      $('#addAddResultModel').addClass("show");
      $('#addAddResultModel').css({background: "#0000004d"});
    });
  }
});


 $('#add_verificarion_result').validate({
      rules: {
        tbl_addrver : {
          required : true
        },
        addrverres_id : {
          required : true
        },
        mode_of_verification : {
          required : true
        },     
        closuredate : {
          required : true
        },
        remarks : {
          required : true
        },
         resident_status : {
          required : true
        },
         verified_by : {
          required : true
        },
        addr_proof_collected : {
          required : true
        },
        res_address : {
          required : true
        },
        res_city : {
          required : true
        },
         res_pincode : {
          required : true
        },
         res_state : {
          required : true,
          greaterThan : 0
        },
         res_stay_from : {
          required : true
        },
         res_stay_to : {
          required : true
        },
         address_action : {
          required : true
        },
          city_action : {
          required : true
        },
        pincode_action : {
          required : true
        },
       state_action : {
          required : true
        },
         stay_from_action : {
          required : true
        },
         stay_to_action : {
          required : true
        },
        "attchments_ver[]" : {
          filesize: 2000000
        }
      },
      messages: {
        tbl_addrver : {
          required : "ID"
        },
        addrverres_id : {
          required : "ID"
        },
        mode_of_verification : {
          required : "Select Mode Of Verification"
        },
        closuredate : {
          required : "Select Closure Date"
        },
        remarks : {
          required : "Enter Remarks"
        }, 
         resident_status : {
          required : "Select Resident Status"
        },
         verified_by : {
           required : "Enter Verified By"
        },
        res_address : {
           required : "Enter Street Address"
        },
        res_city : {
           required : "Enter City"
        },
         res_pincode : {
           required : "Enter Pincode"
        },
         res_state : {
           required : "Select State",
           greaterThan : "Select State Name"
        },
         res_stay_from : {
           required : "Enter Stay Form"
        },
         res_stay_to : {
           required : "Enter Stay To"
        },
         address_action : {
           required : "Please Selected address Action"
        },
         city_action : {
           required : "Please Selected city Action"
        },
          pincode_action : {
           required : "Please Selected pincode Action"
        },
        
        state_action : {
           required : "Please Selected state Action"
        },
        stay_from_action : {
           required : "Please Selected stay from  Action"
        },
        stay_to_action : {
           required : "Please Selected stay to  Action"
        },
        "attchments_ver[]" : {
            filesize : "File size must be less than 2 MB."
        }
      },
      submitHandler: function(form) 
      { 
        var activityData = $('#add_verificarion_result').serialize()+'&'+$('#cases_activity').serialize()+"&activity_status_val=" + $("#activity_status option:selected").text()+ "&activity_mode_val=" + $("#activity_mode option:selected").text()+ "&activity_type_val=" + $("#activity_type option:selected").text()+ "&action_val=" + $("#action option:selected").text()+'&component_type=1'+'&auto_tag=3'+'&remarks='+$('.add_res_remarks').val();
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'activity_log/save_activity'; ?>",
          data : activityData,
          type: 'post',
          async:false,
          cache: false,
          processData:false,
          dataType:'json',
          success: function(jdata){
            $('#activityModel').modal('hide');
         
        var activityData = new FormData(form);
        activityData.append('action_val',$("#action option:selected").text());
        activityData.append('component_type',$("#action option:selected").text());
        activityData.append('activity_last_id',jdata.last_id);
        var sortable_data = $("ul.sortable" ).sortable('serialize'); 
        activityData.append('sortable_data',sortable_data);

        var sortable_data_vendor = $("ul.sortable1" ).sortable('serialize');
        activityData.append("sortable_data_vendor",sortable_data_vendor);

        $.ajax({
          url: '<?php echo ADMIN_SITE_URL.'address/add_verificarion_result'; ?>',
          data:  activityData,
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#sbresult').attr('disabled','disabled');
          },
          complete:function(){
            //$('#sbresult').removeAttr('disabled');                
          },
          success: function(jdata){
          var  final_status = $('#fin_status').val();
          if(final_status == "Closed")
          {
             //var finalData = $('#add_vendor_details_view').serialize();
             var finalData =  new FormData($('#add_vendor_details_view')[0]);
      
        $.ajax({
          url : "<?php echo ADMIN_SITE_URL.'address/Save_vendor_details'; ?>",
          data :  finalData,
          type: 'post',
          async:false,
          cache: false,
          mimeType: "multipart/form-data",
          contentType: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
          //  $('#vendor_result_submit').attr('disabled','disabled');
          },
          complete:function(){
          //  $('#vendor_result_submit').removeAttr('disabled');                
          },
          success: function(jdata){
        
         
          
              }
            });  
           
          }
            var message =  jdata.message || '';
            if(jdata.redirect){
              show_alert(message,'success');
              location.reload();
              //window.location = jdata.redirect;
              return;
            }else{
              show_alert(message,'error');
            }
          }
        });
         }
        });  
      }
  });


  $('#update_address_frm').validate({ 
    rules: {
      update_address_id : {
        required : true
      },
      update_address : {
        required : true
      }
    },
    messages: {
      update_address_id : {
        required : "Update ID missing"
      },
      update_address : {
        required : "Enter Address"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/update_address'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_update_address').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_update_address').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            $('#update_address_frm')[0].reset();
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });


  $('#candidate_verification_result').validate({ 
    rules: {
      address_details_verification_id : {
        required : true
      }
    },
    messages: {
      address_details_verification_id : {
        required : "Update ID missing"
      }
    },
    submitHandler: function(form) 
    {      
        $.ajax({
          url : '<?php echo ADMIN_SITE_URL.'address/update_candidate_verification_result'; ?>',
          data : new FormData(form),
          type: 'post',
          contentType:false,
          cache: false,
          processData:false,
          dataType:'json',
          beforeSend:function(){
            $('#btn_update_address_verification_result').attr('disabled','disabled');
          },
          complete:function(){
            $('#btn_update_address_verification_result').removeAttr('disabled',false);
          },
          success: function(jdata){
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              location.reload();
            }else {
              show_alert(message,'error'); 
            }
          }
        });       
    }
  });

  $(document).on('click', '#reject_digital', function(){
     var cases_assgin_action = $('#reject_digital').val();
     var address_ids = $('#add_update_id').val();
     var selected_id = $('#digital_address_closed_id').val();  
     var reject_reason = $('#reject_reason_closure_digital').val();  

   
    if(cases_assgin_action != 0 && selected_id != "")
    { 

      if(reject_reason == "")
      {
     
       show_alert('Please insert reject reason','error'); 
        
      }
      else
      {  
  
        $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'address/address_closure_digital/' ?>',
          data : 'action='+cases_assgin_action+'&closure_id='+selected_id+'&address_ids='+address_ids+'&reject_reason='+reject_reason,
          dataType:'json',
          beforeSend :function(){
            jQuery('#reject_digital').text("loading...");
          },
          complete:function(){
            jQuery('#reject_digital').text("Reject");
          },
          success:function(jdata)
          {
            select_one1 = [];
            var message =  jdata.message || '';
            if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
              show_alert(message,'success');
              $('#reject_value_digital').modal('hide');
              window.location = jdata.redirect
            }else {
              show_alert(message,'error'); 
            }
          }
        }); 
      }  
    } 
});  

$('#report_down').click(function(){
  $("#activityModelClkdigital").css("display", "block");
});  
</script>
<script type="text/javascript">
function myOpenWindow(winURL, winName, winFeatures, winObj)
{
  var theWin;

  if (winObj != null)
  {
    if (!winObj.closed) {
      winObj.focus();
      return winObj;
    } 
  }
  theWin = window.open(winURL, winName, "width=900,height=650"); 
  return theWin;
}

function rotation(filename,image_id) {
   var file_name = filename;
   var image_id = image_id;
   $.ajax({
          type:'POST',
          url:'<?php echo ADMIN_SITE_URL.'digital_closure/rotate_image' ?>',
          data : 'file_name='+file_name,
          success: function(result) {
            location.reload();
             $("."+image_id).attr("src", result);
          },
          error: function(result) {
            alert('error');
          }
         
         
        }); 
}
</script>

