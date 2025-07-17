<div class="modal-body">
  <div class="result_error" id="result_error"></div>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
    <label>Candidate Name<span class="error"> *</span></label>
    <input type="text" name="candsid" readonly="readonly" id="candsid" value="<?php echo set_value('candsid',$details['CandidateName']); ?>" class="form-control">
    <?php echo form_error('candsid'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Name<span class="error"> *</span></label>
      <input type="text" name="clientname" readonly="readonly" id="candsid" value="<?php echo set_value('clientname',$details['clientname']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Entity</label>
      <input type="text" name="entity_name" readonly="readonly" value="<?php echo set_value('entity_name',$details['entity_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Package</label>
      <input type="text" name="package_name" readonly="readonly" value="<?php echo set_value('package_name',$details['package_name']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Case received date</label>
      <input type="text" name="caserecddate" readonly="readonly" value="<?php echo set_value('caserecddate',convert_db_to_display_date($details['caserecddate'])); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Client Ref No</label>
      <input type="text" readonly="readonly" value="<?php echo set_value('ClientRefNumber',$details['ClientRefNumber']); ?>" class="form-control">
    </div>
    <div class="clearfix"></div>  
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Component Ref No</label>
      <input type="text" name="reference_com_ref" id="reference_com_ref" readonly="readonly" value="<?php echo set_value('reference_com_ref',$details['reference_com_ref']); ?>" class="form-control">
      <?php echo form_error('reference_com_ref'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label><?php echo REFNO; ?></label>
      <input type="text" name="cmp_ref_no" id="cmp_ref_no" readonly="readonly" value="<?php echo set_value('cmp_ref_no',$details['cmp_ref_no']); ?>" class="form-control">
      <?php echo form_error('cmp_ref_no'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Initiation date<span class="error"> *</span></label>
      <input type="text" name="iniated_date" id="iniated_date" value="<?php echo set_value('iniated_date',convert_db_to_display_date($details['iniated_date'])); ?>" class="form-control" placeholder='DD-MM-YYYY'>
      <?php echo form_error('iniated_date'); ?>
    </div>
    <div class="clearfix"></div>
    <hr style="border-top: 2px solid #bb4c4c;">
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Ability to Handle Pressure</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="handle_pressure" id="handle_pressure" value="<?php echo set_value('handle_pressure',$details['handle_pressure']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show handle_pressure">
      <input type="text" name="handle_pressure_value" id="handle_pressure_value" value="<?php echo set_value('handle_pressure_value',$details['handle_pressure_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Attendance</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="attendance" id="attendance" value="<?php echo set_value('attendance',$details['attendance']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show attendance">
      <input type="text" name="attendance_value" id="attendance_value" value="<?php echo set_value('attendance_value',$details['attendance_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Integrity, Character & Honesty</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="integrity" id="integrity" value="<?php echo set_value('integrity',$details['integrity']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show integrity">
      <input type="text" name="integrity_value" id="integrity_value" value="<?php echo set_value('integrity_value',$details['integrity_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Leadership Skills</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="leadership_skills" id="leadership_skills" value="<?php echo set_value('leadership_skills',$details['leadership_skills']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show leadership_skills">
      <input type="text" name="leadership_skills_value" id="leadership_skills_value"  value="<?php echo set_value('leadership_skills_value',$details['leadership_skills_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Responsibilities & Duties</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="responsibilities" id="responsibilities" value="<?php echo set_value('responsibilities',$details['responsibilities']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show responsibilities">
      <input type="text" name="responsibilities_value" id="responsibilities_value"  value="<?php echo set_value('responsibilities_value',$details['responsibilities_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Specific Achievements</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="achievements" id="achievements" value="<?php echo set_value('achievements',$details['achievements']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show achievements">
      <input type="text" name="achievements_value" id="achievements_value"   value="<?php echo set_value('achievements_value',$details['achievements_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Strengths</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="strengths" id="strengths" value="<?php echo set_value('strengths',$details['strengths']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show strengths">
      <input type="text" name="strengths_value" id="strengths_value"  value="<?php echo set_value('strengths_value',$details['strengths_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Team Player</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="team_player" id="team_player" value="<?php echo set_value('team_player',$details['team_player']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show team_player">
      <input type="text" name="team_player_value" id="team_player_value"  value="<?php echo set_value('team_player_value',$details['team_player_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label>Weakness</label>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <input type="number" min="0" max="10" name="weakness" id="weakness" value="<?php echo set_value('weakness',$details['weakness']); ?>" class="form-control">
    </div>
    <div class="col-md-4 col-sm-12 col-xs-34 form-group hide_show weakness">
      <input type="text" name="weakness_value" id="weakness_value" value="<?php echo set_value('weakness_value',$details['weakness_value']); ?>" maxlength="250" class="form-control">
    </div>
    <div class="clearfix"></div>

    <div class="col-md-4 col-sm-12 col-xs-34 form-group">
      <label> Mode of verification</label>
      <?php
      $modeofverification = array('verbal'=> 'Verbal','written'=>'Written');
        echo form_dropdown('mode_of_verification', $modeofverification, set_value('mode_of_verification',$details['mode_of_verification']), 'class="form-control" id="mode_of_verification"');
        echo form_error('mode_of_verification');
      ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label> Closure Date</label>
      <input type="text" name="closuredate" id="closuredate" value="<?php echo set_value('closuredate',convert_db_to_display_date($details['closuredate']));?>" class="form-control myDatepicker" placeholder='DD-MM-YYYY'>
      <?php echo form_error('closuredate'); ?>
    </div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Attachment</label>
      <input type="file" name="attchments_ver[]" accept=".png, .jpg, .jpeg" multiple="multiple" id="attchments_ver" value="<?php echo set_value('attchments_ver'); ?>" class="form-control">
      <?php echo form_error('attchments_ver'); ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-4 col-sm-12 col-xs-4 form-group">
      <label>Remarks</label>
      <textarea id="remarks" name="remarks" class="form-control add_res_remarks" rows="1" value="<?php echo set_value('remarks'); ?>" maxlength="500"><?php echo set_value('attchments_ver',$details['remarks']); ?></textarea>
      <?php echo form_error('attchments_ver'); ?>
    </div>
  </div>
</div>
<script>
$('.myDatepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true
});
$('.hide_show').hide();
$(':input[type="number"]').change(function () { 
  var rating = $(this).val();
  var name = $(this).attr('id');
  if(rating <= 10)
  {
    if(rating > 0 && rating < 5)
      $('.'+name).show();
    else
      $('.'+name).hide();
  } else {
    show_alert('select rating between 0 to 10');
  }

});
</script>