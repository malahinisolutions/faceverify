<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Controller {

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
		if ($this->tank_auth->is_admin_login()) {
			redirect('userverification');
		}
    $this->form_validation->set_rules('login', 'Email', 'trim|required|xss_clean');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
    if ($this->form_validation->run()) {								// validation ok
      if ($this->tank_auth->adminlogin(
          $this->form_validation->set_value('login'),
          $this->form_validation->set_value('password'))) {
            redirect('userverification');
      }else{
        $errors = $this->tank_auth->get_error_message();
         foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				 if(isset($errors['password']) && !is_null($errors['password']) && !empty($errors['password'])){
					 $this->session->set_flashdata('error', $this->lang->line('auth_incorrect_password'));
				 }
				 if(isset($errors['login']) && !is_null($errors['login']) && !empty($errors['login'])){
					 $this->session->set_flashdata('error', $this->lang->line('auth_incorrect_login'));
				 }
      }
    }
    $data['page_title']='Login';
		$this->view('admin/login_view',$data);
  }


  function logout()
  {
    $this->tank_auth->logout();
    $this->session->set_flashdata('message', $this->lang->line('auth_message_logged_out'));
     redirect('admin/login');
  }
}
