<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userverification extends Admin_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url','security'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
	}

  public function index()
  {
    $data['page_title']='User Verification';
		$this->view('admin/userverification_view',$data);
  }

}
