<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function index()
    {
        if (!$this->is_admin_logged_in()) {
            redirect('admin/login');
        }else {
            redirect('admin');
        }
    }
}