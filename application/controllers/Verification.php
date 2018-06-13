<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verification extends Front_Controller {

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
		if (!$this->tank_auth->is_logged_in(FALSE)) {									// logged in
			redirect('login');
		}
		if (is_null($datas = $this->users->can_user_verifided($this->session->userdata('user_id'))))
		{
			redirect('personal_information');
		}else{
			$status1=$this->users->get_user_verification_status($this->session->userdata('user_id'));
			$data['status']=$status1->status;
		}
		$data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
		$data['page_title']='Verification';
		$this->view('verification_view',$data);
	}
}
