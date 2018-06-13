<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 

/**
 * This is an changes of a few basic user interaction methods  
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Changes extends Api_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct(); 
		  
		$this->load->model('tank_auth/users'); 
         
    }
	
	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email_post()
	{
		 if(count($this->post()) > 3 || count($this->post()) != '3')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$user_id=$this->post('user_id');
		$new_email=$this->post('new_email');
		$current_password=$this->post('current_password');
		if (!isset($user_id) && empty($user_id))
		{
			$this->response(array('error'=>array('user_id'=>'The User ID field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($new_email) && empty($new_email))
		{
			$this->response(array('error'=>array('new_email'=>'The new email field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($current_password) && empty($current_password))
		{
			$this->response(array('error'=>array('current_password'=>'The current password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		$this->form_validation->set_data($this->post());
		 $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|greater_than[0],is_natural_no_zero');
			$this->form_validation->set_rules('new_email', 'New Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								 
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('user_id'),
						$this->form_validation->set_value('new_email'),
						$this->form_validation->set_value('current_password')))) {			 

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->tank_auth->send_email('change_email', $data['new_email'], $data);
					$this->set_response(array('message'=>sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']) ), REST_Controller::HTTP_OK); 

				} else {
					$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}
		 
	}
	
	
	/**
	 * Change user password
	 *
	 * @return void
	 */
	function change_password_post()
	{
		if(count($this->post()) > 4 || count($this->post()) != '4')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$user_id=$this->post('user_id');
		$email=$this->post('email');
		$old_password=$this->post('old_password');
		$new_password=$this->post('new_password');
		if (!isset($user_id) && empty($user_id))
		{
			$this->response(array('error'=>array('user_id'=>'The User ID field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($email) && empty($email))
		{
			$this->response(array('error'=>array('email'=>'The email field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($old_password) && empty($old_password))
		{
			$this->response(array('error'=>array('old_password'=>'The old password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($new_password) && empty($new_password))
		{
			$this->response(array('error'=>array('new_password'=>'The new password key is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
			$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|greater_than[0],is_natural_no_zero');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('user_id'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->set_response(array('message'=>$this->lang->line('auth_message_password_changed') ), REST_Controller::HTTP_OK); 
				} else {
					$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}
		 
	}
	
	
	
	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister_post()
	{
		 if(count($this->post()) > 3 || count($this->post()) != '3')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$user_id=$this->post('user_id');
		$email=$this->post('email');
		$password=$this->post('password');
		if (!isset($user_id) && empty($user_id))
		{
			$this->response(array('error'=>array('user_id'=>'The User ID field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($email) && empty($email))
		{
			$this->response(array('error'=>array('email'=>'The email field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($password) && empty($password))
		{
			$this->response(array('error'=>array('password'=>'The password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		$this->form_validation->set_data($this->post());
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|greater_than[0],is_natural_no_zero');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('user_id'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'))) {		// success 
					$this->set_response(array('message'=>$this->lang->line('auth_message_unregistered') ), REST_Controller::HTTP_OK); 
				} else {														// fail
					$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}
		 
	}

	
	
	 

}
/* End of file Changes.php */
/* Location: ./application/controllers/Changes.php */