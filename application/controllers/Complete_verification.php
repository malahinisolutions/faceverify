<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Complete_verification extends Front_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url','security'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
	}

  function index()
  {
		if (!$this->tank_auth->is_logged_in()) {									// logged in
			redirect('login');
		}
		$data['finish']="";
		$this->form_validation->set_rules('finish', 'finish', 'trim|xss_clean');
		if ($this->form_validation->run()) {
			if($this->form_validation->set_value('finish')=='1')
			{
				redirect('verification');
			}
		}
    $data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
    $data['page_title']='Verification Complete';
		$this->view('complete_verification_view',$data);
  }

}
