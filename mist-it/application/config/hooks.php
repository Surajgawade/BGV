<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'] = array(
        'class'    => 'Is_page_access',
        'function' => 'check_permissions',
        'filename' => 'Is_page_access.php',
        'filepath' => 'hooks',
        'params'   => 'vendor'
);