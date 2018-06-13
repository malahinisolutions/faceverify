<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot_password extends Front_Controller {

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
    if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('verification');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/send_again/');

		} else {
			$this->form_validation->set_rules('login', 'Email', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->tank_auth->send_email('forgot_passwords', $data['email'], $data);
          $this->session->set_flashdata('message', $this->lang->line('auth_message_new_password_sent'));


				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
      $data['page_title']='Forgot Password';
      $this->view('forgot_password_view', $data);
		}

  }

}
