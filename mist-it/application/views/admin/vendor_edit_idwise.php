
<input type="hidden" name="update_id" id="update_id" value="<?php echo set_value('update_id',$details['id']); ?>">

<div class="row">
<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>First Name</label>
  <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name',$details['first_name']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-8 col-xs-3 form-group">
  <label>Email Id</label>
  <input type="text" name="email_id" id="email_id" value="<?php echo set_value('email_id',$details['email_id']); ?>" class="form-control">
</div>

<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Mobile No</label>
  <input type="text" name="mobile_no" id="mobile_no"  value="<?php echo set_value('mobile_no',$details['mobile_no']); ?>" class="form-control">
</div>
<div class="col-md-3 col-sm-12 col-xs-3 form-group">
  <label>Password</label>
  <input type="text" name="password" id="password"  value="<?php echo set_value('password'); ?>" class="form-control">
</div>
</div>

