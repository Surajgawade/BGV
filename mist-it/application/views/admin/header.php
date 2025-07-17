<!DOCTYPE html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo CRMNAME; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="<?php echo SITE_IMAGES_URL;?>favicon.ico" type="image/ico" />
    <title><?php echo isset($header_title) ? $header_title : CRMNAME; ?></title>
  
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>dataTables.checkboxes.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>metismenu.min.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>icons.css">
    <link rel="stylesheet" href="<?php echo SITE_CSS_URL; ?>style.css">
    <link href="<?php echo SITE_CSS_URL; ?>daterangepicker.css" rel="stylesheet">
    <link href="<?php echo SITE_CSS_URL; ?>datepicker3.css" rel="stylesheet">
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE_PLUGINS_URL; ?>datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

</head>
<body>
    
<div id="wrapper" class="wrapper">
   <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <a href="<?php echo ADMIN_SITE_URL ?>" class="logo">
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
                            <img src="<?=SITE_URL . PROFILE_PIC_PATH . $this->user_info['profile_pic']?>" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                           
                            <a class="dropdown-item text-danger" href="<?=ADMIN_SITE_URL?>logout?id=<?=$this->user_info['id']?>"><i class="mdi mdi-power text-danger"></i> Logout</a>
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
                        <a class="btn btn-light dropdown-toggle" href="#" role="button" id="dropdownMenuLink" >
                            Hi Welcome <?php echo $this->user_info['user_name']; ?>
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
                

                    <?php    
                if(!empty($this->menus))  
                {
                
                foreach ($this->menus as $menu)
                {
                    if($this->uri->rsegment(2) == 'index')
                    {
                    $active = ($this->uri->segment(2) == $menu['controllers']) ? 'active' : '';
                    }
                    else
                    {
                    $active = '';
                    }
                
                    echo "<li>";
                
                    echo "<a href='javascript:void(0);' class='waves-effect'>";
                    echo "<i class='".$menu['icon']."'></i>";
                    echo "<span>".$menu['name']."<span class='float-right menu-arrow'><i class='fa fa-angle-left pull-right'></i></span></span>";
                
                    echo "</a>";
                    $main_menu_copy = "<li class='".$active."'><a href='".ADMIN_SITE_URL.$menu['controllers']."'><i class='mdi mdi-checkbox-blank-circle-outline'></i> List View </a></li>";   

                    echo "<ul class='submenu'>";
                        
                    echo $main_menu_copy;
                    if(!empty($menu['sub']))
                        {
                            foreach($menu['sub'] as $submenus) 
                            {
                                if($submenus['controllers'] == $this->uri->segment(2)."/".$this->uri->rsegment(2))
                                {
                                    $active_sub = ($this->uri->segment(2)."/".$this->uri->rsegment(2) == $submenus['controllers']) ? ' class="active" ' : '';
                                }
                                else
                                {
                                    $active_sub = ($this->uri->segment(2) == $submenus['controllers']) ? ' class="active" ' : '';
                                }

                                
                                
                                echo "<li ".$active_sub."><a href='".ADMIN_SITE_URL.$submenus['controllers']."'><i class='mdi mdi-checkbox-blank-circle-outline' ></i> ".$submenus['name']." </a></li>  ";
                    
                                
                            }  
                        }
                    echo "</ul>";

                    echo "</li>";        
                }
                }
                ?>

                </ul>

            </div>
            <!-- Sidebar -->
            <div class="clearfix"></div>

        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->

<script src="<?php echo SITE_JS_URL;?>jquery.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>metisMenu.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.slimscroll.js"></script>
<script src="<?php echo SITE_JS_URL;?>waves.min.js"></script>
<script src="<?php echo SITE_JS_URL; ?>notify.js"></script>
<script src="<?php echo SITE_JS_URL;?>jquery.validate.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL; ?>jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>demo.js"></script>
<script src="<?php echo SITE_JS_URL;?>bootstrap-multiselect.js"></script>
<script src="<?php echo SITE_JS_URL; ?>daterangepicker.js"></script>
<script src="<?php echo SITE_JS_URL; ?>bootstrap-datepicker.js"></script>

<!-- Required datatable js -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo SITE_JS_URL;?>dataTables.checkboxes.min.js"></script>
<!-- Buttons examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.bootstrap4.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/jszip.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/pdfmake.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/vfs_fonts.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.html5.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.print.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/dataTables.responsive.min.js"></script>
<script src="<?php echo SITE_PLUGINS_URL;?>datatables/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="<?php echo  SITE_URL; ?>assets/pages/datatables.init.js"></script>
<script src="<?php echo  SITE_URL; ?>assets/plugins/select2/js/select2.min.js"></script>
    
<script src="<?php echo  SITE_JS_URL; ?>app.js"></script>

<script type="text/javascript">

var closing_window = false;
    $(window).on('focus', function () {
        closing_window = false;
});

$(window).on('blur', function () {

    closing_window = true;
    if (!document.hidden) { //when the window is being minimized
        closing_window = false;
    }
    $(window).on('resize', function (e) { //when the window is being maximized
        closing_window = false;
    });
    $(window).off('resize'); //avoid multiple listening
});

$('html').on('mouseenter', function () {
    closing_window = false;
});

$(document).on('keydown', function (e) {

    if (e.keyCode == 91 || e.keyCode == 18) {
        closing_window = false; //shortcuts for ALT+TAB and Window key
    }

    if (e.keyCode == 116 || (e.ctrlKey && e.keyCode == 82)) {
        closing_window = false; //shortcuts for F5 and CTRL+F5 and CTRL+R
    }

    if (e.altKey && e.keyCode== 115) {
        closing_window = true; //shortcuts for alt + F4
    }
});

// Prevent logout when clicking in a hiperlink
$(document).on("click", "a", function () {
    closing_window = false;
});

// Prevent logout when clicking in a button (if these buttons rediret to some page)
$(document).on("click", "button", function () {
    closing_window = false;

});
// Prevent logout when submiting
$(document).on("submit", "form", function () {
    closing_window = false;
});
// Prevent logout when submiting
$(document).on("click", "input[type=submit]", function () {
    closing_window = false;
});

var toDoWhenClosing = function() {
 $.ajax({
        type: "GET",
        contentType: "application/json; charset=utf-8",
        url: '<?php echo ADMIN_SITE_URL?>logout',
        data: { "id": <?php echo $this->user_info['id']; ?>  },
        async: false,
        success: function (data) {
            $('#Loader').hide();

        },
        error: function (e) {
            $('#Loader').hide();
        }
    });
};


window.onbeforeunload = function () {
    if (closing_window) {

        toDoWhenClosing();
    }
};
</script>