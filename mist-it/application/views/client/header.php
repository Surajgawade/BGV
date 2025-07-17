<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />
	<base href="<?php echo CLIENT_SITE_URL?>">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo isset($header_title) ? $header_title : CRMNAME; ?></title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>dataTables.checkboxes.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>bootstrap.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>font-awesome.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>metismenu.min.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>icons.css">
	<link rel="stylesheet" href="<?= SITE_CSS_URL?>style.css">
	<link href="<?php echo SITE_CSS_URL; ?>daterangepicker.css" rel="stylesheet">
	<link href="<?php echo SITE_CSS_URL; ?>datepicker3.css" rel="stylesheet">
	<link href="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>select2/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

</head>
  
<body>

	
	      <div class="topbar">
                <?php if($this->client_info['client_id'] == "59"){ ?> 
                <div class="topbar-left">
                	<span >
			            <img src="<?php echo SITE_URL . CLIENT_LOGO . '/' . $this->client_info['comp_logo']; ?>" alt="" height="45" style = 'background: #ffffff;' class= "mt-2">
			        </span>
			    </div>
			    <?php } else { ?> 

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="<?php echo CLIENT_SITE_URL ?>" class="logo">
                        <span>
                            <img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35">
                        </span>
                    </a>
                </div>
                <?php } ?> 
                <nav class="navbar-custom">

                    <ul class="navbar-right d-flex list-inline float-right mb-0">
                      
                        <li class="dropdown notification-list">
                            <div class="dropdown notification-list nav-pro-img">
                                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="<?= SITE_IMAGES_URL.'default.png' ?>" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                            
                                    <a class="dropdown-item text-danger" href="<?= CLIENT_SITE_URL?>logout?id=<?= $this->client_info['id']?>"><i class="mdi mdi-power text-danger"></i> Logout</a>
                                </div>                                                                    
                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>                        
                        <li class="d-none d-sm-block">
                            <div class="dropdown pt-3 d-inline-block">
                                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Hi Welcome <?php echo $this->client_info['first_name']; ?>
                                </a>
                                &nbsp;&nbsp;&nbsp;
                        <?php if($this->client_info['client_id'] == "59"){ ?>

                            
			                        <span>
			                            <img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35" style = 'background: #ffffff;'>
			                        </span>
			                    
                        <?php  } else { ?> 
                                <?php  if($this->client_info['comp_logo'] != '') { ?>
                            
			                        <span >
			                            <img src="<?php echo SITE_URL . CLIENT_LOGO . '/' . $this->client_info['comp_logo']; ?>" alt="" height="45" style = 'background: #ffffff;'>
			                        </span>
			                   
			                    <?php } }?>
                                
                                
                            </div>
                        </li>
                    </ul>

                </nav>

            </div>


            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                
				            <li <?=($this->uri->segment(2) == 'index') ? 'class="active"' : '';?> >
				                <a href="" class="waves-effect">
				                    <i class="ti-panel"></i>
				                    <span>Dashboard</span>
				                </a>
				            </li>
	       
	  
                    <?php
	                $active = ($this->uri->segment(2) == 'candidates') ? 'active' : '';  
                  	  echo "<li>";  ?>          
			   
                      <?php
                 	       echo "<a href='javascript:void(0);' class='waves-effect'>";
	                           echo "<i class='ti-user'></i>";
	                           echo "<span>Candidates<span class='float-right menu-arrow'><i class='fa fa-angle-left pull-right'></i></span></span>";
	                       echo "</a>";

		            ?>
		            <?php 
		            $show = ($active != "") ? 'style="display:block"' : '';
		            $active_sub = ($this->uri->segment(2) == 'candidates') ? ' class="active" ' : '';
                     ?>
                     <?php 
                            echo "<ul class='submenu'>"; 
			                echo "<li class='".$active_sub."'>
                                    <a href='candidates'><i class='mdi mdi-checkbox-blank-circle-outline'></i>List View</a>
			                     </li>";
                            
                            if( $this->candidate_upload[0]['candidate_upload'] == '1')
                            {     
                            	if(($this->client_info['client_id'] != "64") || ($this->client_info['id'] != "34"))
                            	{ 
                                    echo "<li class='".$active_sub."'>
				                          <a href='candidates/add_candiddate_view'><i class='mdi mdi-checkbox-blank-circle-outline'></i>Add Candidate</a>
				                        </li>";
				                }
                            }
                            if( $this->candidate_upload[0]['candidate_upload'] == '2')
                            {     
                            echo "<li class='".$active_sub."'>
			                        <a href='candidates/initiation_list'><i class='mdi mdi-checkbox-blank-circle-outline'></i>Add Case</a>
			                     </li>";
                            }
                            if( $this->candidate_upload[0]['candidate_upload'] == '3')
                            {     
                            echo "<li class='".$active_sub."'>
			                        <a href='candidates/pre_post_list'><i class='mdi mdi-checkbox-blank-circle-outline'></i>Pre Post</a>
			                     </li>";
                            }

			                echo "</ul>";
			                ?> 
		                    <li class="<?=($this->uri->segment(2) == 'insufficiency') ? 'active' : '';?>">
			            
			                <a href="insufficiency" class="waves-effect">
			                    <i class="ti-user"></i>
			                    <span>Insufficiency</span>
			                </a>
			                </li>
                          
                            <li class="<?=($this->uri->segment(2) == 'download_report') ? 'active' : '';?>">
			            
			                <a href="download_report" class="waves-effect">
			                    <i class="ti-user"></i>
			                    <span>Download Report</span>
			                </a>
			                </li> 
                        
			             
                        </ul>

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>



<script src="<?php echo SITE_JS_URL; ?>jquery.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>metisMenu.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>jquery.slimscroll.js"></script>
<script src="<?php echo SITE_JS_URL; ?>waves.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>notify.js"></script>
<script src="<?php echo SITE_JS_URL; ?>jquery.validate.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>demo.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-multiselect.js"></script>
<script src="<?php echo SITE_JS_URL; ?>daterangepicker.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-datepicker.js"></script>

<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>dataTables.checkboxes.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/jszip.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/pdfmake.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/vfs_fonts.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.html5.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.print.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.colVis.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.js"></script>
<script src="<?php echo SITE_URL; ?>assets/pages/datatables.init.js"></script>          
<script src="<?php echo SITE_JS_URL; ?>app.js"></script>
<script src="<?php echo  SITE_URL; ?>assets/plugins/select2/js/select2.min.js"></script>


 

            
