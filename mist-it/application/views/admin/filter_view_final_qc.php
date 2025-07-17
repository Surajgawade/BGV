<form>
  <div class="row">
    <div class="col-sm-3 form-group">
      <?php
         if($this->user_info['id'] == 24)
         { 
             $clients = array('0'=>'select Client','43'=>'Vio');
         }
         else
         {
            $clients = $clients;
         }
         echo form_dropdown('clientid', $clients, set_value('clientid'), 'class="custom-select" id="clientid"');
      ?>
    </div>

     
    <div class="col-sm-3 form-group">
        <?php
          echo form_dropdown('entity', array(), set_value('entity'), 'class="custom-select" id="entity"');
          echo form_error('entity');
        ?>
    </div>
                       
     <div class="col-sm-3  form-group">
         <select id="package" name="package" class="custom-select"><option value="0">Select</option></select>
     </div>


    <div class="col-sm-3  form-group">
      <input type="button" name="searchrecords" id="searchrecords" class="btn btn-md btn-info" value="Filter">
      <input type="reset" name="btn_reset" id="btn_reset" class="btn btn-md btn-info" value="Reset">
    </div>
  </div> 
</form>


