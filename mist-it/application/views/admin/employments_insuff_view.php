 <input type="hidden" name="insuff_edit" id="insuff_edit" value="">
        <input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$selected_data['employment_id']); ?>">

         <input type="hidden" name="candidates_info_id" id="candidates_info_id" value="<?php echo set_value('candidates_info_id',$selected_data['cands_id']); ?>">

        <input type="hidden" name="component_ref_no" id="component_ref_no" value="<?php echo set_value('component_ref_no',$selected_data['emp_com_ref']); ?>">
        <input type="hidden" name="CandidateName" readonly="readonly" value="<?php echo set_value('CandidateName',$get_cands_details['CandidateName']); ?>" class="form-control">  
        <div class = "row">
        <div class="col-md-6 col-sm-12 col-xs-6 form-group">
          <label>Raise Date<span class="error"> *</span></label>
          <input type="text" name="txt_insuff_raise"  id="txt_insuff_raise" value="<?php echo set_value('txt_insuff_raise',$this->insuff_date); ?>" class="form-control myDatepicker1" placeholder='DD-MM-YYYY'>
          <?php echo form_error('txt_insuff_raise'); ?>
        </div>
        <div class="col-md-6 col-sm-8 col-xs-6 form-group">
          <label>Reason</label>
          <?php
           echo form_dropdown('insff_reason', $insuff_reason_list, set_value('insff_reason'), 'class="form-control setinsff_reason" id="insff_reason"');
            echo form_error('insff_reason'); 
          ?>
        </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
          <label>Remark</label>
          <textarea  name="insuff_raise_remark" rows="1" maxlength="500" id="insuff_raise_remark"  class="form-control insuff_raise_remark"><?php echo set_value('insuff_raise_remark'); ?></textarea>
          <?php echo form_error('insuff_raise_remark'); ?>
        </div>

        <script type="text/javascript">
          $('.myDatepicker1').datepicker({
            daysOfWeekDisabled: [0,6],
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,

          });
        </script>