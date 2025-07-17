<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('client_list'))
{
	function client_list($where_array = array()) {

        $lists = $this->common_model->select('user_profile',FALSE,array("id,concat(firstname,' ',lastname) as fullname
            "),$where_array);

        return convert_to_single_dimension_array($lists,'id','fullname');
	}
}
