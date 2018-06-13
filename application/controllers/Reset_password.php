<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset_password extends Front_Controller {

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
    $user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					 $new_pass_key,
					$this->form_validation->set_value('new_password')))) { 	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				$this->tank_auth->send_email('reset_password', $data['email'], $data);
        $this->session->set_flashdata('message', $this->lang->line('auth_message_new_password_activated').' '.anchor('login', 'Login',array("color"=>"#000","title"=>"Login")));


			}else{
        	// fail
          $this->session->set_flashdata('error', $this->lang->line('auth_message_new_password_failed'));

			}
		}else{
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
        $this->session->set_flashdata('error', $this->lang->line('auth_message_new_password_failed'));

			}
		}
		$data['page_title']='Reset Password';
		$this->view('reset_password_view', $data);
  }

}
