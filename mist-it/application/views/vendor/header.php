<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />
	<base href="<?=VENDOR_SITE_URL?>">
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title><?php echo isset($header_title) ? ucwords(strtolower($header_title)) : CRMNAME; ?></title>
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

                <!-- LOGO -->
            <div class="topbar-left">
                    <a href="<?php echo VENDOR_SITE_URL ?>" class="logo">
                    <span>
                        <img src="<?php echo SITE_IMAGES_URL ?>logo.png" alt="" height="35">
                    </span>
                </a>
            </div>

                <nav class="navbar-custom">

                    <ul class="navbar-right d-flex list-inline float-right mb-0">
                        <li class="dropdown notification-list d-none d-sm-block">
                            <form role="search" class="app-search">
                                <div class="form-group mb-0"> 
                                    <input type="text" class="form-control" placeholder="Search..">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </form> 
                        </li>

                       
                        <li class="dropdown notification-list">
                            <div class="dropdown notification-list nav-pro-img">
                                <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="<?= SITE_IMAGES_URL.'default.png' ?>" alt="user" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                    <!-- item-->
                                   
                                    <a class="dropdown-item text-danger" href="<?= VENDOR_SITE_URL?>logout?id=<?= $this->vendor_info['id']?>"><i class="mdi mdi-power text-danger"></i> Logout</a>
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
                                    Hi Welcome <?php echo $this->vendor_info['first_name']; ?>
                                </a>
                                
                               
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
			              if($this->vendor_nemus[0]['component_key'] == 'addrver')
			              {
			              	?>
                              <li <?=($this->uri->segment(2) == 'users') ? 'class="active"' : '';?> >
				                <a href="users" class="waves-effect">
				                    <i class="ti-user"></i>
				                    <span>Users</span>
				                </a>
				             </li>
			             <?php } 
				            foreach($this->vendor_nemus as $key => $value) {
				            	$active = ($this->uri->segment(2) == $value['component_key']) ? 'active' : '';
				            	echo "<li>";

				            	if($value['component_key'] == 'addrver' || $value['component_key'] == 'empver'||  $value['component_key'] == 'eduver'||   $value['component_key'] == 'courtver'||  $value['component_key'] == 'globdbver'||  $value['component_key'] == 'narcver'|| $value['component_key'] == 'crimver'|| $value['component_key'] == 'identity' || $value['component_key'] == 'cbrver')
				            	{

					            	 	echo "<a href='javascript:void(0);' class='waves-effect'>";
	                                    echo "<i class='".$value['vendor_icon']."'></i>";
	                                    echo "<span>".$value['show_component_name']."<span class='float-right menu-arrow'><i class='fa fa-angle-left pull-right'></i></span></span>";
	                                    echo "</a>";
				            	 

					                    $show = ($active != "") ? 'style="display:block"' : '';
					                    $active_sub = ($this->uri->segment(2) == $value['component_key']) ? ' class="active" ' : '';

					                    echo "<ul class='submenu'>";

					                    echo "<li class='".$active_sub."'><a href='".$value['component_key']."/".$value['component_key']."_wip'><i class='mdi mdi-checkbox-blank-circle-outline'></i>WIP</a></li>";

					                    echo "<li class='".$active_sub."'><a href='".$value['component_key']."/".$value['component_key']."_insufficiency'><i class='mdi mdi-checkbox-blank-circle-outline'></i>Insufficiency</a></li>"; 

					                    echo "<li class='".$active_sub."'><a href='".$value['component_key']."/".$value['component_key']."_closed'><i class='mdi mdi-checkbox-blank-circle-outline'></i>Closed</a></li>";
					                    echo "</ul>";
   
					            }
					            else
					            {
					             	if($value['component_key'] != 'refver')
					             	{
					             	
                                      
                                        echo "<a href='".$value['component_key']."' class='waves-effect'>";
	                                    echo "<i class='".$value['vendor_icon']."'></i>";
	                                    echo "<span>".$value['show_component_name']."</span>";
	                                    echo "</a>";

					                }
					            }
					            echo "</li>";


				            }
				            ?>
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
<script src="<?php echo SITE_URL; ?>assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
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
<script src="<?php echo  SITE_URL; ?>assets/plugins/select2/js/select2.min.js"></script>

<script src="<?php echo SITE_JS_URL; ?>app.js"></script>

 
