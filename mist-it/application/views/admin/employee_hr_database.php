 <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body"> 
                       
                        <div class="text-white div-header">
                            Company Information
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label >Company Name<span class="error"> *</span></label>
                                <input type="text" name="coname" id="coname" value="<?php echo set_value('coname',$company_details['coname']);?>" class="form-control" readonly>
                                <?php echo form_error('coname'); ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label >Address<span class="error"> *</span></label>
                                <input type="text" name="address" id="address" value="<?php echo set_value('address',$company_details['address']);?>" class="form-control"  readonly>
                                <?php echo form_error('address'); ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>City<span class="error"> *</span></label>
                                <input type="text" name="city" id="city" value="<?php echo set_value('city',$company_details['city']);?>" class="form-control"  readonly>
                                <?php echo form_error('city'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 form-group">
                                <label>Select State<span class="error"> *</span></label>
                                <?php
                                    echo form_dropdown('state', $states, set_value('state',$company_details['state']), 'class="custom-select" id="state" disabled');
                                    echo form_error('state');
                                ?>
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Pincode<span class="error"> *</span></label>
                                <input type="text" name="pincode" id="pincode" value="<?php echo set_value('pincode',$company_details['pincode']);?>" class="form-control"  readonly>
                                <?php echo form_error('pincode'); ?>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>To Email ID</label>
                                <input type="text" name="co_email_id" id="co_email_id" value="<?php echo set_value('co_email_id',$company_details['co_email_id']);?>" class="form-control"  readonly>
                                <?php echo form_error('co_email_id'); ?>
                            </div>

                            <div class="col-sm-6 form-group">
                              <label>CC Email ID</label>
                              <input type="text" name="cc_email_id" id="cc_email_id" value="<?php echo set_value('cc_email_id',$company_details['cc_email_id']);?>" class="form-control"  readonly>
                              <?php echo form_error('cc_email_id'); ?>
                            </div>
                        </div>

 
    
                            <div class="text-white div-header">
                                Company requirements
                            </div> 
                            <br>
                            
                            <input type="checkbox" name="previous_emp_code" id="previous_emp_code" <?php if(isset($company_details['previous_emp_code'])) {  if($company_details['previous_emp_code'] == 1 ) echo 'checked' ;} ?> disabled>
                            <label>Previous Emp Code</label>
                            <input type="checkbox" name="branch_location" id="branch_location"  <?php if(isset($company_details['branch_location'])) {  if($company_details['branch_location'] == 1 ) echo 'checked' ;} ?> disabled>
                            <label> Branch Location</label>
                            <input type="checkbox" id="experience_letter" name="experience_letter" <?php if(isset($company_details['experience_letter'])) {  if($company_details['experience_letter'] == 1 ) echo 'checked' ;} ?> disabled>
                            <label> Relieving/Experience letter</label><br>
                            <input type="checkbox" id="loa" name="loa" <?php if(isset($company_details['loa'])) {  if($company_details['loa'] == 1 ) echo 'checked' ;} ?> disabled>
                            <label> LOA</label>
                            <input type="checkbox" id="auto_initiate" name="auto_initiate" <?php if(isset($company_details['auto_initiate'])) {  if($company_details['auto_initiate'] == 1 ) echo 'checked' ;}else{ echo 'checked' ;} ?> disabled>
                            <label> Auto Initiate</label>
                            <input type="checkbox" id="follow_up" name="follow_up" <?php if(isset($company_details['follow_up'])) {  if($company_details['follow_up'] == 1 ) echo 'checked';}else {echo 'checked' ;} ?> disabled>
                            <label>Follow up</label>
                            <input type="checkbox" id="client_disclosure" name="client_disclosure" <?php if(isset($company_details['client_disclosure'])) {  if($company_details['client_disclosure'] == 1 ) echo 'checked';} ?> disabled>
                            <label>Client Disclosure</label>
                          </br>

                             
                            <br>
                                            
                            <div class="clearfix"></div>
                           
                            
       
                           <div class="text-white div-header">
                               HR Information 
                           </div>
                           <br>

                           <table id="tbl_vendor_insuff" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr >
                            <th>SrId</th>
                            <th>Verifier Name</th>
                            <th>Verifier Designtion</th>       
                            <th>Verifier Contact No</th>
                            <th>Verifier Email ID</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $i = 1;
                              $html_view = '';
                            foreach ($verifiers_details as $key => $value) {
                                
                            
                                $html_view .= "<td>".$i."</td>";
                            
                                $html_view .= "<td>".$value['verifiers_name']."</td>";
                                $html_view .= "<td>".$value['verifiers_designation']."</td>";
                                $html_view .= "<td>".$value['verifiers_contact_no']."</td>";
                                $html_view .= "<td>".$value['verifiers_email_id']."</td>";
                                $html_view .= '</tr>';  

                                $i++;  
                            }
                             echo  $html_view;
                           ?>

                            </tbody>
                            </table>

                             
                            <div class="text-white div-header">
                               Backup Hr Information 
                           </div>
                           <br>

                           <table id="tbl_back_hr_info" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr >
                            <th>SrId</th>
                            <th>Verifier Name</th>
                            <th>Verifier Designtion</th>       
                            <th>Verifier Contact No</th>
                            <th>Verifier Email ID</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $i = 1;
                              $html_view = '';
                            foreach ($verifiers_details_bk as $key => $value) {
                                
                            
                                $html_view .= "<td>".$i."</td>";
                            
                                $html_view .= "<td>".$value['verifiers_name']."</td>";
                                $html_view .= "<td>".$value['verifiers_designation']."</td>";
                                $html_view .= "<td>".$value['verifiers_contact_no']."</td>";
                                $html_view .= "<td>".$value['verifiers_email_id']."</td>";
                                $html_view .= '</tr>';  

                                $i++;  
                            }
                             echo  $html_view;
                           ?>
                          
                            </tbody>
                            </table>

                            <div class="text-white div-header">
                              M - Info
                           </div>
                           <br>


                            <table id="tbl_vendor_insuff_m" class="table table-bordered  nowrap"  style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr >
                            <th>SrId</th>
                            <th>Company Name</th>
                            <th>Verifier Name</th>
                            <th>Verifier Designtion</th>       
                            <th>Verifier Contact No</th>
                            <th>Verifier Email ID</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                              $i = 1;
                              $html_view = '';
                            foreach ($get_other_details as $key => $value) {
                                
                            
                                $html_view .= "<td>".$i."</td>";
                                $html_view .= "<td>".$value['coname']."</td>";
                                $html_view .= "<td>".$value['verifiers_name']."</td>";
                                $html_view .= "<td>".$value['verifiers_designation']."</td>";
                                $html_view .= "<td>".$value['verifiers_contact_no']."</td>";
                                $html_view .= "<td>".$value['verifiers_email_id']."</td>";
                                $html_view .= '</tr>';  

                                $i++;  
                            }
                             echo  $html_view;
                           ?>

                            </tbody>
                            </table>

                         
                        </div>    
                        </div>
                       
                    </div>
                </div>
            </div>

