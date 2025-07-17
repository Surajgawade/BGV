<div class="content-page">
<div class="content">
<div class="container-fluid">

  <div class="row">
        <div class="col-sm-12">
          <div class="page-title-box">
            <h5 class="page-title">Create Role</h5>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>dashboard"><?php echo SHORT_CO_NAME ?></a></li>
                  <li class="breadcrumb-item"><a href="<?php echo ADMIN_SITE_URL ?>roles">Roles</a></li>
                  <li class="breadcrumb-item active">Add Role</li>
              </ol>
            
              <div class="state-information d-none d-sm-block">
                <ol class="breadcrumb">
                    <li><button class="btn btn-secondary waves-effect btn-sm btn_clicked" data-accessUrl="<?= ADMIN_SITE_URL?>roles"><i class="fa fa-arrow-left"></i> Back</button></li> 
               </ol>
              </div>
          </div>
        </div>
  </div>

    <div class="row">
      <div class="col-12">
      <div class="card m-b-20">
       <div class="card-body">
          
            <?php echo form_open('#', array('name'=>'save_new_groups','id'=>'save_new_groups')); ?>
            <div class = "row">
              <div class="col-sm-4 form-group">
                <label>Role Name<span class="error"> *</span></label>
                <input type="text" name="role_name" id="role_name" value="<?php echo set_value('role_name'); ?>" class="form-control">
                <?php echo form_error('role_name'); ?>
              </div>
              <div class="col-sm-4 form-group">
                <label>Select Groups<span class="error"> *</span></label>
                <?php
                    echo form_multiselect('groups_id[]', $groups_list , set_value('groups_id'), 'class="form-control multiSelect" id="groups_id"');
                    echo form_error('groups_id');
                ?>
              </div>
              <div class="col-sm-4 form-group">
                <label >Description</label>
                <textarea name="role_description" rows="1" id="role_description" class="form-control"><?php echo set_value('role_description'); ?></textarea>
                <?php echo form_error('role_description'); ?>
              </div>
              </div>
            
              <div class="col-sm-12" id = "admin" style="display: none">
                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">ADMIN
                    <input type="checkbox" id="global_checkall_admin" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall_admin" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('admin1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('admin1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="admin1" style="display: none;">
                   
                  <?php

                  foreach ($access as $key => $value) 
                  {
                 
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_admin') !== false)  
                        { 
                            
                            if (strpos($value, 'access_admin_list') !== false)  
                          
                              { 
                                  if($key == '2') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>

                              <?php
                        
                              }
                            elseif(strpos($value, 'access_admin_role') !== false)
                              {
                           
                                if($key == '8') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Role</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_admin_group') !== false)
                             {
                           
                                if($key == '14') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Groups</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }
                          
                          elseif(strpos($value, 'access_admin_holiday') !== false)
                            {
                          
                               if($key == '20') 
                               { 
                                 echo '<div class="clearfix"></div><h4>Holiday</h4>';
                               }
                          
                            ?>
                              <div class="item col-md-4 col-sm-12 col-xs-12">
                                <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>"       title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                            </div>

                           <?php
                
                           }

                          elseif(strpos($value, 'access_admin_activity') !== false)
                            {
                          
                               if($key == '24') 
                               { 
                                 echo '<div class="clearfix"></div><h4>Activity</h4>';
                               }
                          
                            ?>
                             <div class="item col-md-4 col-sm-12 col-xs-4">
                                <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                 <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                             </div>
                        
                            <?php

                             }
                          elseif(strpos($value, 'access_admin_vendor') !== false)
                            {
                          
                                if($key == '30') 
                                 { 
                                   echo '<div class="clearfix"></div><h4>Vendor</h4>';
                                 }
                          
                             ?>
                              <div class="item col-md-4 col-sm-12 col-xs-4">
                                 <input type="checkbox" class="single_check_box_admin" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                 <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                              </div>

                           <?php

                              }
                       }
                      

                    } 
                  } 


                  ?>
                </div>
                </fieldset>
              </div>
             <div class="col-xs-12 col-md-12 col-sm-12" id = "client" style="display: none;" > 
                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">CLIENT
                    <input type="checkbox" id="global_checkall_client" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('clients1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('clients1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                  <div id="clients1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {

                     if (strpos($value, 'access_') !== false) 
                     { 
  

                      if (strpos($value, 'access_clients') !== false)  
                        { 
                         
                            if($key == '34') 
                            {
                             echo '<div class="clearfix"></div>';
                            }  

                            if (strpos($value, 'access_clients_list') !== false)  
                          
                              { 
                                
                                  if($key == '34') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_client" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                        
                              }
                         }
                      
                    } 
                  } 


                  ?>
                </div>
                </fieldset>
              </div>

              <div class="col-xs-12 col-md-12 col-sm-12" id = "candidates" style="display: none" > 
             
                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">CANDIDATES
                    <input type="checkbox" id="global_checkall_candidates" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('candidates1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('candidates1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="candidates1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {

                     if (strpos($value, 'access_') !== false) 
                     { 
  

                   if (strpos($value, 'access_candidates') !== false)  
                        { 

                         
                            if($key == '40') 
                            {
                             echo '<div class="clearfix"></div>';
                            }  

                            if (strpos($value, 'access_candidates_list') !== false)  
                          
                              { 
                                  if($key == '40') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_candidates" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                        
                              }
                          
                        }   

                    } 
                  } 

                ?>
                 </div>
                </fieldset>
              </div>

              <div class="col-xs-12 col-md-12 col-sm-12" id = "address" style="display: none" > 

                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">ADDRESS
                    <input type="checkbox" id="global_checkall_address" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                  <div align="right"><a href="javascript:hide('address1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('address1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="address1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {

                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_address') !== false)  
                        { 
                            if($key == '49') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_address_list') !== false)  
                          
                              { 
                                  if($key == '49') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                 if(($value != 'access_address_list_assign_executive') and ($value != 'access_address_list_re_assign_executive'))
                                 {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                }
                        
                              }
                              elseif(strpos($value, 'access_address_employment_visits') !== false)
                              {
                           
                                if($key == '63') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Employment Visits</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_address_location_database') !== false)
                             {
                            
                                if($key == '69') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Location Database</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }
                          
                          elseif(strpos($value, 'access_address_vendor_database') !== false)
                            {
                          
                               if($key == '75') 
                               { 
                                 echo '<div class="clearfix"></div><h4>Vendor Database</h4>';
                               }
                          
                            ?>
                              <div class="item col-md-4 col-sm-12 col-xs-12">
                                <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>"       title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                            </div>

                           <?php
                
                           }

                          elseif(strpos($value, 'access_address_aq') !== false)
                            {
                          
                               if($key == '81') 
                               { 
                                 echo '<div class="clearfix"></div><h4>Assress Aq</h4>';
                               }
                          
                            ?>
                             <div class="item col-md-4 col-sm-12 col-xs-4">
                                <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                 <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                             </div>
                        
                            <?php

                             }
                          elseif(strpos($value, 'access_address_assign_add') !== false)
                            {
                          
                                if($key == '83') 
                                 { 
                                   echo '<div class="clearfix"></div><h4>Address Assign</h4>';
                                 }
                          
                             ?>
                              <div class="item col-md-4 col-sm-12 col-xs-4">
                                 <input type="checkbox" class="single_check_box_address" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                 <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                              </div>

                           <?php

                              }
                     
                         }  
                       

                    } 
                  } 


                  ?>
                 </div>
                </fieldset>
              </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "employment" style="display: none" > 


                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">EMPLOYMENT
                    <input type="checkbox" id="global_checkall_employment" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                  <div align="right"><a href="javascript:hide('employment1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('employment1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="employment1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_employment') !== false)  
                        { 
                            if($key == '85') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_employment_list') !== false)  
                          
                              { 
                                
                                  if($key == '85') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                if(($value != 'access_employment_list_assign') and ($value != 'access_employment_list_re_assign'))
                                 {

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_employment" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                  }
                        
                              }
                            elseif(strpos($value, 'access_employment_suspicious_company') !== false)
                              {
                            
                                if($key == '105') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Employment Suspicious Company</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_employment" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_employment_hr_database') !== false)
                             {
                         
                                if($key == '111') 
                                { 
                                 echo '<div class="clearfix"></div><h4>HR Database</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_employment" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

               </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "education" style="display: none" > 


                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">EDUCATION
                    <input type="checkbox" id="global_checkall_education" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label>

                   <div align="right"><a href="javascript:hide('education1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('education1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="education1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

              
                        if (strpos($value, 'access_education') !== false)  
                        { 
                            if($key == '118') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_education_list') !== false)  
                          
                              { 
                                  if($key == '118') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_education_list_assign') and ($value != 'access_education_list_re_assign'))
                                  {

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_education" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                 }
                        
                              }
                            elseif(strpos($value, 'access_education_universities') !== false)
                              {
                           
                                if($key == '136') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Education Universities</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_education" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_education_fake') !== false)
                             {
                         
                                if($key == '142') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Education Fake Universities</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_education" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }


                            elseif(strpos($value, 'access_education_aq') !== false)
                             {
                                 
                                if($key == '148') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Education AQ</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_education" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }

                            elseif(strpos($value, 'access_education_assign') !== false)
                             {
                           
                                if($key == '150') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Education Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_education" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                  </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "reference" style="display: none" > 


                  <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">REFERENCE
                    <input type="checkbox" id="global_checkall_reference" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('reference1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('reference1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="reference1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_reference') !== false)  
                        { 
                            if($key == '152') 
                            {
                             echo '<div class="clearfix">';
                            }  
 
                            if (strpos($value, 'access_reference_list') !== false)  
                          
                              { 
                                  if($key == '152') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_reference_list_assign') and ($value != 'access_reference_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_reference" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php

                                }
                        
                              }
                            
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                 </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "court" style="display: none" > 


                  <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">COURT
                    <input type="checkbox" id="global_checkall_court" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('court1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('court1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="court1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                   
                        if (strpos($value, 'access_court') !== false)  
                        { 
                            if($key == '170') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_court_list') !== false)  
                          
                              { 
                                  if($key == '170') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_court_list_assign') and ($value != 'access_court_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_court" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                  }
                        
                              }
                            elseif(strpos($value, 'access_court_aq') !== false)
                              {
                          
                                if($key == '185') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Court AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_court" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_court_assign_court') !== false)
                             {
                           
                                if($key == '187') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Court Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_court" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                 </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "global" style="display: none" > 

                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">GLOBAL DATABASE
                    <input type="checkbox" id="global_checkall_global" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                <div align="right"><a href="javascript:hide('global1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('global1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="global1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_global') !== false)  
                        { 
                            if($key == '189') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_global_list') !== false)  
                          
                              { 
                                  if($key == '189') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_global_list_assign') and ($value != 'access_global_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_global" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                  }
                        
                              }
                            elseif(strpos($value, 'access_global_aq') !== false)
                              {
                              
                                if($key == '204') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Global AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_global" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_global_assign') !== false)
                             {
                           
                                if($key == '206') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Global Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_global" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

               </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "drugs" style="display: none" > 



                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">DRUGS
                    <input type="checkbox" id="global_checkall_drugs" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('drugs1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('drugs1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="drugs1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_drugs') !== false)  
                        { 
                            if($key == '208') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_drugs_list') !== false)  
                          
                              { 
                                  if($key == '208') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                 
                                  if(($value != 'access_drugs_list_assign') and ($value != 'access_drugs_list_re_assign'))
                                  {

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_drugs" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                  }
                              }
                            elseif(strpos($value, 'access_drugs_aq') !== false)
                              {
                             
                                if($key == '223') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Drugs AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_drugs" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_drugs_assign') !== false)
                             {
                           
                                if($key == '225') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Drugs Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_drugs" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "pcc" style="display: none" > 

                 <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">PCC
                    <input type="checkbox" id="global_checkall_pcc" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                  <div align="right"><a href="javascript:hide('pcc1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('pcc1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="pcc1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_pcc') !== false)  
                        { 
                            if($key == '227') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_pcc_list') !== false)  
                          
                              { 
                                  if($key == '227') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_pcc_list_assign') and ($value != 'access_pcc_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_pcc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                  }
                        
                              }
                            elseif(strpos($value, 'access_pcc_aq') !== false)
                              {
                         
                                if($key == '242') 
                                { 
                                  echo '<div class="clearfix"></div><h4>PCC AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_pcc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_pcc_assign') !== false)
                             {
                           
                                if($key == '244') 
                                { 
                                 echo '<div class="clearfix"></div><h4>PCC Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_pcc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "identity" style="display: none" > 


                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">IDENTITY
                    <input type="checkbox" id="global_checkall_identity" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('identity1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('identity1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="identity1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                 
                        if (strpos($value, 'access_identity') !== false)  
                        { 
                            if($key == '246') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_identity_list') !== false)  
                          
                              { 
                                  if($key == '246') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_identity_list_assign') and ($value != 'access_identity_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_identity" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                 }
                        
                              }
                            elseif(strpos($value, 'access_identity_aq') !== false)
                              {
                          
                                if($key == '261') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Identity AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_identity" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_identity_assign') !== false)
                             {
                           
                                if($key == '263') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Identity Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_identity" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>
               
              </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "credit" style="display: none" > 


                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">CREDIT REPORT
                    <input type="checkbox" id="global_checkall_credit" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                  <div align="right"><a href="javascript:hide('credit1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('credit1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="credit1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_credit') !== false)  
                        { 
                            if($key == '265') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_credit_list') !== false)  
                          
                              { 
                                  if($key == '265') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_credit_list_assign') and ($value != 'access_credit_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_credit" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                 }
                        
                              }
                            elseif(strpos($value, 'access_credit_aq') !== false)
                              {
                           
                                if($key == '280') 
                                { 
                                  echo '<div class="clearfix"></div><h4>Credit Report AQ</h4>';
                                }
                          
                              ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">    
                                 <input type="checkbox" class="single_check_box_credit" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                            <?php


                              }

                            elseif(strpos($value, 'access_credit_assign') !== false)
                             {
                           
                                if($key == '282') 
                                { 
                                 echo '<div class="clearfix"></div><h4>Credit Report Assign</h4>';
                                }
                          
                               ?>
                                <div class="item col-md-4 col-sm-12 col-xs-4">
                                   <input type="checkbox" class="single_check_box_credit" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                               </div>
                           <?php  

                             }   
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>

                 </div>

                <div class="col-xs-12 col-md-12 col-sm-12" id = "task" style="display: none" > 

                 

               <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">TASK MANAGER
                    <input type="checkbox" id="global_checkall_task_manager" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                  <div align="right"><a href="javascript:hide('task1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('task1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="task1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {

                     if (strpos($value, 'access_') !== false) 
                     { 

                    
                        if (strpos($value, 'access_task') !== false)  
                        { 
                            if($key == '260') 
                            {
                             echo '<div class="clearfix"></div>';
                            }  

                            if (strpos($value, 'access_task_list') !== false)  
                          
                              { 
                                  if($key == '260') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                

                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_task_manager" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                        
                              }
                     
                         }  

                    } 
                  } 


                  ?>
                </div>
                </fieldset>

              </div>

              <div class="col-xs-12 col-md-12 col-sm-12" id = "final" style="display: none" > 

                 

            <fieldset id="fieldset_user_global_rights">
              <legend data-submenu-label="Global">Final QC
                <input type="checkbox" id="global_checkall_final_qc" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
              <div align="right"><a href="javascript:hide('final1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('final1')" ><i class="fa fa-sort-down" ></i></a></div>
              </legend>

                <div id="final1" style="display: none;">
              <?php

              foreach ($access as $key => $value) 
              {

                  if (strpos($value, 'access_') !== false) 
                  { 

                
                    if (strpos($value, 'access_') !== false)  
                    { 
                        if($key == '292') 
                        {
                          echo '<div class="clearfix"></div>';
                        }  

                        if (strpos($value, 'access_final_list') !== false)  
                      
                          { 
                              if($key == '292') 
                              { 
                                echo '<h4>List View</h4>';
                              }
                            

                            ?> 
                              <div class="item col-md-4 col-sm-12 col-xs-4" >
                                  <input type="checkbox" class="single_check_box_final_qc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                  <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                              </div>
                          <?php
                    
                          }
                          elseif(strpos($value, 'access_final_aq') !== false)
                          {
                       
                            if($key == '295') 
                            { 
                              echo '<div class="clearfix"></div><h4>Final QC AQ</h4>';
                            }
                      
                          ?>
                            <div class="item col-md-4 col-sm-12 col-xs-4">    
                             <input type="checkbox" class="single_check_box_final_qc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                            <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                           </div>
                        <?php


                          }
                          elseif(strpos($value, 'access_final_annexture') !== false)
                          {
                       
                            if($key == '297') 
                            { 
                              echo '<div class="clearfix"></div><h4>Final QC Annexture</h4>';
                            }
                      
                          ?>
                            <div class="item col-md-4 col-sm-12 col-xs-4">    
                             <input type="checkbox" class="single_check_box_final_qc" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                            <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                           </div>
                        <?php


                          }
                  
                      }  

                } 
              } 


              ?>
            </div>
            </fieldset>

            </div>


            <div class="col-xs-12 col-md-12 col-sm-12" id = "report" style="display: none" > 

                 

              <fieldset id="fieldset_user_global_rights">
                <legend data-submenu-label="Global">Report
                  <input type="checkbox" id="global_checkall_report" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                <div align="right"><a href="javascript:hide('report1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('report1')" ><i class="fa fa-sort-down" ></i></a></div>
                </legend>

                  <div id="report1" style="display: none;">
                <?php

                foreach ($access as $key => $value) 
                {

                    if (strpos($value, 'access_') !== false) 
                    { 

                  
                      if (strpos($value, 'access_') !== false)  
                      { 
                          if($key == '299') 
                          {
                            echo '<div class="clearfix"></div>';
                          }  

                          if (strpos($value, 'access_report_list') !== false)  
                        
                            { 
                                if($key == '299') 
                                { 
                                  echo '<h4>List View</h4>';
                                }
                              

                              ?> 
                                <div class="item col-md-4 col-sm-12 col-xs-4" >
                                    <input type="checkbox" class="single_check_box_report" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                    <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                </div>
                            <?php
                      
                            }
                            elseif(strpos($value, 'access_report_schedule') !== false)
                            {
                        
                              if($key == '304') 
                              { 
                                echo '<div class="clearfix"></div><h4>Report Schedule</h4>';
                              }
                        
                            ?>
                              <div class="item col-md-4 col-sm-12 col-xs-4">    
                              <input type="checkbox" class="single_check_box_report" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                              <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                            </div>
                          <?php


                            }
                            elseif(strpos($value, 'access_report_cron') !== false)
                            {
                        
                              if($key == '306') 
                              { 
                                echo '<div class="clearfix"></div><h4>Report Cron Job</h4>';
                              }
                        
                            ?>
                              <div class="item col-md-4 col-sm-12 col-xs-4">    
                              <input type="checkbox" class="single_check_box_report" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                              <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                            </div>
                          <?php


                            }
                    
                        }  

                  } 
                } 


                ?>
              </div>
              </fieldset>

              </div>



              <div class="col-xs-12 col-md-12 col-sm-12" id = "social_media" style="display: none" > 


                <fieldset id="fieldset_user_global_rights">
                  <legend data-submenu-label="Global">Social Media
                    <input type="checkbox" id="global_checkall_social_media" data-check='cfalse' class="checkall_box" title="Check all"> <label for="global_checkall" id='change_text'>Check all</label> 
                   <div align="right"><a href="javascript:hide('social_media1')"><i class="fa fa-sort-up"></i></a>&nbsp;&nbsp;<a href="javascript:show('social_media1')" ><i class="fa fa-sort-down" ></i></a></div>
                  </legend>

                   <div id="social_media1" style="display: none;">
                  <?php

                  foreach ($access as $key => $value) 
                  {
            
                     if (strpos($value, 'access_') !== false) 
                     { 

                 
                        if (strpos($value, 'access_social_media') !== false)  
                        { 
                            if($key == '246') 
                            {
                             echo '<div class="clearfix">';
                            }  

                            if (strpos($value, 'access_social_media_list') !== false)  
                          
                              { 
                                  if($key == '246') 
                                  { 
                                    echo '<h4>List View</h4>';
                                  }
                                
                                  if(($value != 'access_social_media_list_assign') and ($value != 'access_social_media_list_re_assign'))
                                  {
                                ?> 
                                  <div class="item col-md-4 col-sm-12 col-xs-4" >
                                      <input type="checkbox" class="single_check_box_social_media" name="permission[]" id="<?php echo $value; ?>" value="<?php echo $value; ?>" title="Click to allow page access of <?php echo $value; ?>">
                                      <label for="<?php echo $value; ?>"><?php echo ucwords(str_replace('access', ' ', str_replace('_', ' ', $value)));?></label>
                                  </div>
                              <?php
                                 }
                        
                              } 
                     
                         }  
                       
                    } 
                  } 


                  ?>
                </div>
                </fieldset>
               
              </div>



              <div class="clearfix"></div>
              <div class="col-md-6 col-sm-12">
                <button type="submit" id="btn_add_group" name="btn_add_group" class="btn btn-primary">Submit</button>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="test"></div>
</div>
<style>
  #fieldset_user_global_rights fieldset {
        float: left;
  }
  fieldset fieldset {
    margin: .8em;
    background: #fff;
    border: 1px solid #aaa;
    background: #E8E8E8;
  }
  fieldset legend {
    color: #444;
    padding: 2px 15px;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border: 1px solid #aaa;
    background-color: #fff;
    -moz-box-shadow: 3px 3px 15px #bbb;
    -webkit-box-shadow: 3px 3px 15px #bbb;
    box-shadow: 3px 3px 15px #bbb;
    max-width: 100%;
  }
  legend {
      display: block;
      width: 100%;
      padding: 0;
      margin-bottom: 20px;
      font-size: 17px;
      line-height: inherit;
      color: #333;
      border: 0;
      border-bottom: 1px solid #e5e5e5;
  }
  input[type=checkbox] {
    margin: 2px;
  }
  fieldset  {
      margin-top: 1em;
      border-radius: 4px 4px 0 0;
      -moz-border-radius: 4px 4px 0 0;
      -webkit-border-radius: 4px 4px 0 0;
      border: #aaa solid 1px;
      padding: 0.5em;
      background: #eee;
      text-shadow: 1px 1px 2px #fff inset;
      -moz-box-shadow: 1px 1px 2px #fff inset;
      -webkit-box-shadow: 1px 1px 2px #fff inset;
      box-shadow: 1px 1px 2px #fff inset;
  }
</style>
<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap-multiselect.css">
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script>
$(document).ready(function(){

  $("#global_checkall_admin").on("click", function(){
    var checkBoxes = $(".single_check_box_admin");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });


  $("#global_checkall_client").on("click", function(){
    var checkBoxes = $(".single_check_box_client");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
  

   $("#global_checkall_candidates").on("click", function(){
    var checkBoxes = $(".single_check_box_candidates");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
 
  $("#global_checkall_address").on("click", function(){
    var checkBoxes = $(".single_check_box_address");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

  $("#global_checkall_employment").on("click", function(){
    var checkBoxes = $(".single_check_box_employment");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

   $("#global_checkall_education").on("click", function(){
    var checkBoxes = $(".single_check_box_education");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

    $("#global_checkall_reference").on("click", function(){
    var checkBoxes = $(".single_check_box_reference");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

    $("#global_checkall_court").on("click", function(){
    var checkBoxes = $(".single_check_box_court");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
 
    $("#global_checkall_global").on("click", function(){
    var checkBoxes = $(".single_check_box_global");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

     $("#global_checkall_drugs").on("click", function(){
    var checkBoxes = $(".single_check_box_drugs");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

      $("#global_checkall_pcc").on("click", function(){
    var checkBoxes = $(".single_check_box_pcc");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
  
  $("#global_checkall_identity").on("click", function(){
    var checkBoxes = $(".single_check_box_identity");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

     $("#global_checkall_credit").on("click", function(){
    var checkBoxes = $(".single_check_box_credit");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });  


  $("#global_checkall_task_manager").on("click", function(){
    var checkBoxes = $(".single_check_box_task_manager");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
 
  $("#global_checkall_final_qc").on("click", function(){
    
    var checkBoxes = $(".single_check_box_final_qc");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
 
  $("#global_checkall_report").on("click", function(){
    var checkBoxes = $(".single_check_box_report");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });
 
  $("#global_checkall_social_media").on("click", function(){
    var checkBoxes = $(".single_check_box_social_media");
    checkBoxes.prop("checked", !checkBoxes.prop("checked"));
  });

  $('.multiSelect').multiselect({
      buttonWidth: '320px',
      enableFiltering: true,
      includeSelectAllOption: true,
      maxHeight: 200
  });

  $('#save_new_groups').validate({ 
      rules: {
        role_name : {
          required : true
        },
        'groups_id[]' : {
          required : true,
          greaterThan: 0
        },
        'permission[]' : {
          required : true
        },
        reporting_manager : {
          required : true,
          greaterThan : 0
        }
      },
      messages: {
        role_name : {
          required : "Enter Role Name"
        },
        'groups_id[]' : {
          required : "Select Groups",
          greaterThan : "Select Groups"
        },
        'permission[]' : {
          required : "Select Permission"
        },
        reporting_manager : { 
          required : 'Select Reporting Manager',
          greaterThan : 'Select Reporting Manager'
        }    
      },
      submitHandler: function(form) 
      {
          $.ajax({
            url : '<?php echo ADMIN_SITE_URL.'roles/save_new_role'; ?>',
            data : $( form ).serialize(),
            type: 'post',
            dataType:'json',
            beforeSend:function(){
              $('#btn_add_group').attr('disabled','disabled');
            },
            complete:function(){
             // $('#btn_add_group').removeAttr('disabled');                
            },
            success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                show_alert(message,'success');
              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
          });           
      }
  });
});
</script>
<script type="text/javascript">
 var $ = jQuery;
$("#groups_id").on("change",function(e) {

      var id =  $("#groups_id").val();
      $.ajax({
        url: '<?php echo ADMIN_SITE_URL.'roles/get_page_name'; ?>',
        type: 'post',
        data: {id:id},
    
      success: function(jdata){
              var message =  jdata.message || '';
              if(jdata.status == '<?php echo SUCCESS_CODE; ?>'){
                if($.inArray("super admin",message) !== -1) 
               {
                  $('#admin').show();
                  $('#client').show();
                  $('#candidates').show();
                  $('#address').show();
                  $('#employment').show();
                  $('#education').show();
                  $('#reference').show();
                  $('#court').show();
                  $('#global').show();
                  $('#drugs').show();
                  $('#pcc').show();
                  $('#identity').show();
                  $('#credit').show();
                  $('#task').show();
                  $('#final').show();
                  $('#report').show();
                  $('#social_media').show();

               } 
            
               if($.inArray("admin",message) !== -1) 
               {
                  $('#admin').show();
               }  
               if($.inArray("clients",message) !== -1)
               {
                 $('#client').show();
                
               }
                if($.inArray("candidates",message) !== -1)
               {
                 $('#candidates').show();
                
               }
                if($.inArray("address",message) !== -1)
               {
                 $('#address').show();
                
               }
               if($.inArray("employment",message) !== -1)
               {
                 $('#employment').show();
                
               }
              if($.inArray("education",message) !== -1)
               {
                 $('#education').show();
                
               }
              if($.inArray("references",message) !== -1)
               {
                 $('#reference').show();
                
               }
              if($.inArray("court",message) !== -1)
               {
                 $('#court').show();
                
               } 
               if($.inArray("global database",message) !== -1)
               {
                 $('#global').show();
                
               }
               if($.inArray("drugs",message) !== -1)
               {
                 $('#drugs').show();
                
               }
              if($.inArray("pcc",message) !== -1)
               {
                 $('#pcc').show();
                
               }
                if($.inArray("identity",message) !== -1)
               {
                 $('#identity').show();
                
               }
                if($.inArray("credit report",message) !== -1)
               {
                 $('#credit').show();
                
               }
               if($.inArray("task management",message) !== -1)
               {
                 $('#task').show();
                }

                if($.inArray("final qc",message) !== -1)
               {
                 $('#final').show();
                }
                if($.inArray("reports",message) !== -1)
               {
                 $('#report').show();
                }
                if($.inArray("social media",message) !== -1)
               {
                 $('#social_media').show();
                
               }
               

              }
              if(jdata.status == <?php echo ERROR_CODE; ?>){
                show_alert(message,'error'); 
              }
            }
        });
    });

</script>
<script type="text/javascript">
  
 /* function showhide(id)
  {
    var e = document.getElementById(id);
    alert(e.style.display);
    e.style.display = (e.style.display == 'block')  ? 'none' : 'block';
   }
*/
   function show(id)
  {
    var e = document.getElementById(id);
    e.style.display = (e.style.display == 'none')  ? 'block' : 'block';
   }

   function hide(id)
   {
    var e = document.getElementById(id);
    e.style.display = (e.style.display == 'block')  ? 'none' : 'none';
   }
</script>