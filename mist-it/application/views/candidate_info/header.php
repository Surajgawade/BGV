<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=0" name="viewport" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<base href="<?=CANDIDATE_SITE_URL?>">
	<link rel="icon" href="appgini-icon.png" type="image/ico" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo isset($header_title) ? ucwords(strtolower($header_title)) : CRMNAME ?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<link href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>paper-dashboard.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>themify-icons.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>custom.css" rel="stylesheet">
	<script src="<?php echo SITE_JS_URL.'jquery.min.js' ?>"></script>
	<script src="<?php echo SITE_JS_URL.'bootstrap.min.js' ?>"></script>
	<script src="<?php echo SITE_JS_URL.'jquery.dataTables.min.js' ?>"></script>
	<script src="<?php echo SITE_JS_URL.'dataTables.bootstrap.min.js' ?>"></script>
	<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="<?= SITE_JS_URL; ?>jquery.validate.min.js"></script>
    <script src="<?php echo SITE_JS_URL; ?>notify.js"></script> 
    <script src="<?php echo SITE_JS_URL; ?>demo.js"></script>
</head>
<body>
<div class="body_loading" style="display: none;"><img src="<?= SITE_IMAGES_URL; ?>ajax-loader.gif"> </div>

   <div class="wrapper">
        
        <nav id="sidebar">
          <div class="sidebar" data-background-color="white" data-active-color="danger">
            <div class="sidebar-wrapper">
            	<div class="logo">
                <a href="" class="simple-text">
	                <img src="<?= SITE_IMAGES_URL; ?>logo.jpg" height="50" width="125" alt="logo">
	            </a>
	            </div>
               
	         
              <ul class="nav nav-list nav-menu-list-style">
           <?php
	            $active = ($this->uri->segment(3) == 'list_view') ? 'active' : '';  
                  	echo "<li class='".$active."'>";  ?>

		           <a data-toggle="collapse" href="#pending_component" class='dropdown-toggle' aria-expanded="false">
		              <i class="ti-user"></i>
		              <p>
		               Pending
		              </p>
		            </a>
		            <?php 
		                      $show = ($active != "") ? 'style="display:block"' : '';
		                      $active_sub = ($this->uri->segment(3) == 'pending_component') ? ' class="active" ' : '';
                         ?>
		         
		              <?php  echo "<ul class='collapse list-unstyled '".$show."' id='pending_component' >"  ?>
		                <li  <?php echo $active_sub ?>>
		                  <a href="Address/list_view" id="address_display" class= "pending_component" style="display: none;">
		                   Address
		                  </a>
		                </li>
		                <li  <?php echo $active_sub ?>>
		                  <a href="Employment/list_view" id="employment_display" class= "pending" style="display: none;"> 
		                    <span class="sidebar-normal">Employment</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Education/list_view" id="education_display" class= "pending" style="display: none;">
		                    <span class="sidebar-normal">Education</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Reference/list_view"  id="reference_display" class= "pending" style="display: none;">
		                    <span class="sidebar-normal">Reference</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Court/list_view"   id="court_display" class= "pending" style="display: none;">  
		                    <span class="sidebar-normal">Court</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Global_database/list_view" id="global_display"  class= "pending" style="display: none;">
		                    <span class="sidebar-normal">Global  Database</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="PCC/list_view" id="pcc_display"  class= "pending" style="display: none;"> 
		                    <span class="sidebar-normal">PCC</span>
		                  </a>
		                </li>
		                 <li <?php echo $active_sub ?>>
		                  <a href="Identity/list_view" id="identity_display"  class= "pending"  style="display: none;">
		                    <span class="sidebar-normal">Identity</span>
		                  </a>
		                </li>
                         <li <?php echo $active_sub ?>>
		                  <a href="Credit_report/list_view"  id="credit_report_display"  class= "pending"  style="display: none;">
		                    <span class="sidebar-normal">Credit Report</span>
		                  </a>
		                </li>

		              </ul>
		           <!-- </div>-->
		        </li>
		           <?php
	            $active = ($this->uri->segment(3) == 'submitted_list_view') ? 'active' : '';  
                  	echo "<li class='".$active."'>";  ?>
		   
	                <a data-toggle="collapse" href="#submitted_component" class='dropdown-toggle' aria-expanded="true">
	                    <i class="ti-panel"></i>
	                    <p>Submitted</p>
	                </a>

	             
		               <?php 
		                      $show = ($active != "") ? 'style="display:block"' : '';
		                      $active_sub = ($this->uri->segment(3) == 'submitted_component') ? ' class="active" ' : '';
                         ?>
		         
		              <?php  echo "<ul class='collapse list-unstyled '".$show."' id='submitted_component' >"  ?>
		                <li <?php echo $active_sub ?>>
		                  <a href="Address/submitted_list_view" id="address_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Address</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Employment/submitted_list_view" id="employment_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Employment</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Education/submitted_list_view"  id="education_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Education</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Reference/submitted_list_view"  id="reference_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Reference</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="Court/submitted_list_view" id="court_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Court</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>> 
		                  <a href="Global_database/submitted_list_view"  id="global_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Global  Database</span>
		                  </a>
		                </li>
		                <li <?php echo $active_sub ?>>
		                  <a href="PCC/submitted_list_view" id="pcc_submit_display" style="display: none;">
		                    <span class="sidebar-normal">PCC</span>
		                  </a>
		                </li>
		                 <li <?php echo $active_sub ?>>
		                  <a href="Identity/submitted_list_view"  id="identity_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Identity</span>
		                  </a>
		                </li>
                         <li <?php echo $active_sub ?>>
		                  <a href="Credit_report/submitted_list_view" id="credit_report_submit_display" style="display: none;">
		                    <span class="sidebar-normal">Credit Report</span>
		                  </a>
		                </li>
		              </ul>
		         <!--   </div>-->
	            </li>
	         </ul>
            </div>
          </div>
        </nav>

            <div id="content">

                 <nav class="navbar navbar-default">
			        <div class="container-fluid">
			            <div class="navbar-header">
			        	    <button type="button" id="sidebarCollapse" class="btn btn-info navbar-btn">
			                                <i class="glyphicon glyphicon-align-left"></i>
			                            </button>
			                        </div>
			            <div class="navbar-header">
			                <a class="navbar-brand" href="#"><?= $header_title;?></a>
			            </div>
			            <div class="collapse navbar-collapse">
			          
			                <ul class="nav navbar-nav navbar-right">
			                	<li class="dropdown">

			                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
			               				<p> Hi <?= ucwords($this->candidate_info['CandidateName']); ?></p>
											<b class="caret"></b>
			                          </a>
			                          <ul class="dropdown-menu">
			                          	<li><a href="<?= SITE_URL.'candidate_info/profile'; ?>"><p> Profile </p></a></li>
			                          	<li><a href="<?= SITE_URL.'candidate_info/logout/'; ?>"><p> Logout </p></a></li>
			                          </ul>
			                    </li>
			                </ul>

			            </div>
			        </div>
			    </nav>

  
     

       <script type="text/javascript">
            $(document).ready(function () {
               

                $('#sidebarCollapse').on('click', function () {

                    $('#sidebar, #content').toggleClass('active');
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });
                 
            });



        $(document).ready(function () {
        	
                $.ajax({
				    type:'POST',
				    url: '<?php echo CANDIDATE_SITE_URL."Candidate_login/display_menu"; ?>',      
				    dataType: 'json',
				    beforeSend :function(){
				    },
				    complete:function(){
				    //$('#wip,#Insufficiency,#discrepancy,#stop-check,#unable-to,#completed,#total').html('0');
				    },
				    success:function(response)
				    {
//alert(response.message);
                          var jsonArray = response.message;

                           $(jsonArray).each(function (index, item) {

               // each iteration
                           var result = item.component_name;

                           if(result == "Address")
                           {

                               document.getElementById("address_display").style.display = 'block';
                           }
                           else if(result == "Address Submit")
                           {

                           	   document.getElementById("address_submit_display").style.display = 'block';
                           }
                           else if(result == "Employment")
                           {
                           	    document.getElementById("employment_display").style.display = 'block';
                           }
                           else if(result == "Employment Submit")
                           {
                           	    document.getElementById("employment_submit_display").style.display = 'block';
                           }
                           else if(result == "Education")
                           {
                           	    document.getElementById("education_display").style.display = 'block';
                           }
                           else if(result == "Education Submit")
                           {
                           	    document.getElementById("education_submit_display").style.display = 'block';
                           }
                           else if(result == "Reference")
                           {
                           	    document.getElementById("reference_display").style.display = 'block';
                           }
                           else if(result == "Reference Submit")
                           {
                           	    document.getElementById("reference_submit_display").style.display = 'block';
                           }
                           else if(result == "Court")
                           {
                           	    document.getElementById("court_display").style.display = 'block';
                           }
                           else if(result == "Court Submit")
                           {
                           	    document.getElementById("court_submit_display").style.display = 'block';
                           }
                           else if(result == "Global DB")
                           {
                           	    document.getElementById("global_display").style.display = 'block';
                           }
                           else if(result == "Global DB Submit")
                           {
                           	    document.getElementById("global_submit_display").style.display = 'block';
                           }
                           else if(result == "PCC")
                           {
                           	    document.getElementById("pcc_display").style.display = 'block';
                           }
                           else if(result == "PCC Submit")
                           {
                           	    document.getElementById("pcc_submit_display").style.display = 'block';
                           }
                           else if(result == "Identity")
                           {
                           	  document.getElementById("identity_display").style.display = 'block';
                           }
                            else if(result == "Identity Submit")
                           {
                           	    document.getElementById("identity_submit_display").style.display = 'block';
                           }
                           else if(result == "Credit Report")
                           {
                           	  document.getElementById("credit_report_display").style.display = 'block';
                           }
                           else if(result == "Credit Report Submit")
                           {
                           	    document.getElementById("credit_report_submit_display").style.display = 'block';
                           }
                           else
                           {
                            /*     document.getElementById("education_display").style.display = 'none';

		                         document.getElementById("reference_display").style.display = 'none';

		                         document.getElementById("court_display").style.display = 'none';

		                         document.getElementById("global_display").style.display = 'none';

		                         document.getElementById("pcc_display").style.display = 'none';

		                         document.getElementById("identity_display").style.display = 'none';

		                         document.getElementById("credit_report_display").style.display = 'none';

		                         document.getElementById("address_submit_display").style.display = 'none';

		                         document.getElementById("employment_submit_display").style.display = 'none';
		                        
		                         document.getElementById("education_submit_display").style.display = 'none';
		                           
		                         document.getElementById("reference_submit_display").style.display = 'none';

		                         document.getElementById("court_submit_display").style.display = 'none';
		                        
		                         document.getElementById("global_submit_display").style.display = 'none';
		  
		                         document.getElementById("pcc_submit_display").style.display = 'none';

		                         document.getElementById("identity_submit_display").style.display = 'none';
		                        
		                         document.getElementById("credit_report_submit_display").style.display = 'none';*/

                           }
          
          
                        });
				       
				    }
				  });
                });


          

        </script>

   



