<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Candidate Deatils</h5>
</div>
<input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo set_value('trasaction_id',$details['trasaction_id']); ?>">


<input type="hidden" name="address_id" id="address_id" value="<?php echo set_value('address_id',$details['id']); ?>">


<input type="hidden" name="component_id" id="component_id" value="<?php echo set_value('component_id',$details['id']); ?>">

<input type="hidden" name="CandidateName" id="CandidateName" value="<?php echo set_value('CandidateName',$details['CandidateName']); ?>">
<input type="hidden" name="CandidateID" id="CandidateID" value="<?php echo set_value('CandidateID',$details['CandidateID']); ?>">

<input type="hidden" name="ClientRefNumber" id="ClientRefNumber" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>">

<input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$details['add_com_ref']); ?>">


<input type="hidden" name="entity_id" id="entity_id1" value="<?php echo set_value('entity_id',$details['entity_id']); ?>">
<input type="hidden" name="package_id" id="package_id1" value="<?php echo set_value('package_id',$details['package_id']); ?>">
<input type="hidden" name="clientid" id="clientid1" value="<?php echo set_value('clientid',$details['clientid']); ?>">
<input type="hidden" name="addrverres_id" id="addrverres_id1" value="<?php echo set_value('addrverres_id',$details['addrverres_id']); ?>">

<input type="hidden" name="cl_id" id="cl_id" value="<?php echo set_value('cl_id',$details['view_vendor_master_log_id']); ?>">

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


<h5>Documents</h5>
<?php  if(!empty($attachments_file)) { ?>
 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>File Name</th>
    </tr>
    <tr>
      <?php 
      }
      foreach ($attachments_file as $key => $value) {
       
        $url  = SITE_URL.ADDRESS.$details['clientid'].'/';
        
        ?>
         <tr>
         <td style='border: 1px solid black;padding: 8px;'><a href='javascript:;'onClick='myOpenWindow("<?php echo $url.$value['file_name']; ?>", "myWin", "height=250,width=480"); 
         return false'><?= $value['file_name']?></a></td>
         </tr>

      <?php } ?>

    </tr>
  </table>

<div class="col-md-12 col-sm-12 col-xs-12 form-group">
<h5>Others</h5>
</div>
<div class="row">
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Vendor</label>
  <input type="text" name="Vendor_name" id="Vendor_name" value="<?php echo set_value('Vendor_name',$details['vendor_name']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Executive</label>
  <input type="text" name="state" id="state" value="<?php echo set_value('state',$details['state']); ?>" class="form-control cls_readonly">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>TAT</label>
  <input type="text" name="tat" id="tat" value="<?php echo set_value('tat',$details['tat_status']); ?>" class="form-control cls_readonly">
</div>


 <div class="col-md-3 col-sm-12 col-xs-3 form-group">
      <label>Check Status</label>
      <?php
      $check_status = array('wip'=> 'WIP','candidate shifted'=>'Candidate Shifted','unable to verify'=>'Unable to Verify','denied verification'=>'Denied Verification','resigned'=>'Resigned','candidate not responding'=>'Candidate not Responding','clear'=>'Clear');
      echo form_dropdown('status', $check_status, set_value('status',$details['final_status']), 'class="custom-select status" id="status" readonly' );
      echo form_error('status');
      ?>
 </div>
</div>
<div class="row">
 <div class="col-md-6 col-sm-12 col-xs-6 form-group">
      <label>Remark</label><span class="error">*</span>
      <textarea name="vendor_remark" id ="vendor_remark" rows="1" class="form-control" readonly maxlength="155"><?php if(isset($details['vendor_remark']) && !empty($details['vendor_remark'])) { echo $details['vendor_remark'];}?></textarea>
    </div>
</div>

 
 <h5>Vendor Attachments</h5>
  <?php  if(!empty($attachments)) { ?>
   

 <table style="border: 1px solid black; ">
    <tr>
       <th style='border: 1px solid black;padding: 8px;'>Initiation
       Date</th>
       <th style='border: 1px solid black;padding: 8px;'>Image</th>
       
    </tr>
    <tr>
      <?php 
      }

      for($i=0; $i < count($attachments); $i++) { 
   
        $folder_name = "vendor_file";
        $url  = "'".SITE_URL.ADDRESS.$folder_name.'/';
        $actual_file  = $attachments[$i]['file_name']."'";
        $myWin  = "'"."myWin"."'";
        $attribute  = "'"."height=250,width=480"."'";
        $f_name = explode('.',SITE_URL.ADDRESS.$folder_name.'/'.$attachments[$i]['file_name']);
        $newstring = substr($f_name[0], -19);
        $newstring_date = explode('-',$newstring);
        echo '<tr class="form-group thumb" id="item-'.$attachments[$i]['id'].'">';
        echo '<td style="border: 1px solid black;padding: 8px;">'.$newstring_date['2'].'-'.$newstring_date['1'].'-'.$newstring_date['0'].' '.$newstring_date['3'].':'.$newstring_date['4'].':'.$newstring_date['5'].'</td>';
        echo '<td style="border: 1px solid black;padding: 8px;"><a href="javascript:;" ondblClick="myOpenWindow('.$url.$actual_file.','.$myWin.','.$attribute.'); return false"> <img src='.$url.$actual_file.' height = "75px" width = "75px" > </a></td>';
      
       
        ?>
        
         </tr>
      <?php } ?>
    </tr>
  </table>

<div class="clearfix"></div>


<script>$('.cls_readonly').prop('disabled',true);</script>
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
 $(".status").change(function() {    
   var status = $(".status option:selected").text();

   if(status == 'WIP' || status == 'Candidate Shifted' || status == 'Unable to Verify' || status == 'Denied Verification' || status == 'Resigned' || status == 'Candidate not Responding')
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');

     $("#vendor_remark").attr("readonly", false); 
   }
   if(status == 'Clear' )
   {

     $('.fin_status').val(status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }
  
});

 var f_status = '<?php echo ucwords($details['final_status'])?>';

   if(f_status == 'Wip'  || f_status == 'Candidate Shifted' || f_status == 'Unable To Verify' || f_status == 'Denied Verification' || f_status == 'Resigned' || f_status == 'Candidate not Responding') 
   {
     $('.showbutton').show();
     $('.showbutton1').hide();
     $('.fin_status').val('');

     $("#vendor_remark").attr("readonly", false); 
   }
   if(f_status == 'Clear')
   {

     $('.fin_status').val(f_status);
     $('.showbutton1').show();
     $('.showbutton').hide();
   //$('#showvendorModel').modal('hide');
   }

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
</script>
<script src="<?= SITE_JS_URL ?>jquery-ui.min.js"></script>
<script type="text/javascript">
  $("ul.sortable" ).sortable({
    revert: 100,
    placeholder: 'placeholder'
  });

  $( "ul.sortable" ).disableSelection();
</script>